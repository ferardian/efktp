# 🧪 Panduan Konsolidasi & Deduplikasi Master Tarif Lab (EFKTP)

Dokumen ini berisi panduan lengkap untuk membersihkan dan men-deduplikasi data master tarif laboratorium (`jns_perawatan_lab`) serta sub-item parameter ujinya (`template_laboratorium`) pada aplikasi EFKTP menggunakan Artisan command bawaan.

---

## 📌 1. Latar Belakang Masalah

Pada basis data bawaan **SIMKES Khanza** (legacy rumah sakit), satu jenis pemeriksaan laboratorium yang sama sering kali diduplikasi menjadi **8 hingga 10 baris terpisah** hanya untuk membedakan kelas rawat inap dan selisih tarif:
- `100-RJ` : Hematologi Darah Rutin (Rawat Jalan)
- `103-K.1`: Hematologi Darah Rutin (Kelas 1)
- `104-VIP`: Hematologi Darah Rutin (VIP)
- `105-VVIP`: Hematologi Darah Rutin (VVIP), dst.

### Dampak Negatif Duplikasi:
1. **Pencarian Membingungkan**: Dokter / petugas lab melihat banyak pilihan tes dengan nama yang sama saat membuat order lab.
2. **Database Membengkak**: Setiap duplikat master menduplikasi seluruh sub-item parameter di `template_laboratorium` (contoh: 50 pemeriksaan menduplikasi hingga 400+ baris dan 2.000+ template).
3. **Tidak Relevan untuk FKTP**: Fasilitas Kesehatan Tingkat Pertama (Klinik Pratama / Puskesmas) pada umumnya menggunakan tarif standar **Rawat Jalan**, bukan tarif berjenjang per kelas rawat inap seperti rumah sakit tipe B/C.

Command `lab:deduplicate-master` hadir untuk merapikan data ini menjadi **1 master terbaik per jenis pemeriksaan**.

---

## 🛡️ 2. Fitur Keamanan (Safety Features)

1. **Auto-Backup Otomatis**:
   Sebelum menghapus data apa pun, sistem secara otomatis mencadangkan seluruh tabel ke:
   - `jns_perawatan_lab_backup_20261011`
   - `template_laboratorium_backup_20261011`
2. **Perlindungan Riwayat Transaksi Pasien (Protected Codes)**:
   Pemeriksaan yang **sudah pernah memiliki histori transaksi pasien** di tabel `periksa_lab` atau `permintaan_pemeriksaan_lab` **DIJAMIN TIDAK DIHAPUS** dan otomatis dipilih sebagai master utama.
3. **Penyelamatan Template Sub-Pemeriksaan**:
   Jika master utama yang dipilih belum memiliki template tetapi salah satu duplikatnya memiliki template, sub-item template akan otomatis disalin ke master utama sebelum duplikat dihapus.
4. **Database Transaction (Atomic)**:
   Proses pembersihan dibungkus dalam `DB::beginTransaction()`. Jika terjadi kendala di tengah jalan, seluruh perubahan otomatis dibatalkan (*auto-rollback*) sehingga database tetap aman.
5. **Instant Rollback**:
   Tersedia opsi `--rollback` untuk mengembalikan kondisi data 100% seperti semula kapan saja.

---

## 🚀 3. Cara Penggunaan Command

### A. Langkah 1: Jalankan Mode Simulasi (Dry-Run)
Gunakan opsi `--dry-run` terlebih dahulu untuk melihat preview data mana yang akan dipertahankan dan mana yang akan dihapus **tanpa mengubah isi database**:

```bash
php artisan lab:deduplicate-master --dry-run
```

*Contoh Output:*
```
Hasil Analisis Deduplikasi:
- Data yang akan DIPERTAHANKAN : 75 baris
- Data duplikat yang akan DIHAPUS : 336 baris
```

---

### B. Langkah 2: Eksekusi Pembersihan Nyata
Setelah yakin dengan hasil simulasi, jalankan command tanpa opsi `--dry-run`:

```bash
php artisan lab:deduplicate-master
```

Command ini akan:
1. Membuat tabel cadangan `*_backup_YYYYMMDD`.
2. Menghapus data duplikat dan template redundan.
3. Menormalkan kolom kelas seluruh master menjadi `Rawat Jalan`.

---

### C. Langkah 3: Mengembalikan Data Semula (Rollback)
Jika karena alasan tertentu Anda ingin mengembalikan kondisi data ke kondisi sebelum deduplikasi:

```bash
php artisan lab:deduplicate-master --rollback
```

Sistem akan meminta konfirmasi `yes/no` lalu mengembalikan seluruh baris dari tabel backup.

---

## 💻 4. Menjalankan di Berbagai Environment

### 1. Menjalankan di Lingkungan Docker Lokal (Laravel Sail / Docker Compose)
```bash
docker exec -it efktp-laravel.test-1 php artisan lab:deduplicate-master --dry-run
docker exec -it efktp-laravel.test-1 php artisan lab:deduplicate-master
```

### 2. Menjalankan di Terminal Host macOS (Direct PHP CLI)
Jika `.env` Anda terset `DB_HOST=host.docker.internal`:
```bash
DB_HOST=127.0.0.1 php artisan lab:deduplicate-master --dry-run
DB_HOST=127.0.0.1 php artisan lab:deduplicate-master
```

### 3. Menjalankan di Server Client / Production (SSH Linux / Container `efktp-app`)
```bash
# Masuk ke container aplikasi efktp di server:
docker exec -it efktp-app php artisan lab:deduplicate-master --dry-run
docker exec -it efktp-app php artisan lab:deduplicate-master
```

---

## 🔍 5. Logika Prioritas Pemilihan Master Utama (Keeper)

Sistem menggunakan hierarki penentuan cerdas saat memilih 1 record yang dipertahankan:

```
[Kandidat Duplikat dengan Nama yang Sama]
               │
               ▼
[Apakah ada yang pernah dipakai transaksi pasien?] ──► Ya ──► PILIH RECORD INI (Wajib)
               │ Tidak
               ▼
[Apakah ada kode yang berakhiran '-RJ'?] ──────────► Ya ──► PILIH RECORD '-RJ'
               │ Tidak
               ▼
[Apakah ada record dengan kelas 'Rawat Jalan'?] ───► Ya ──► PILIH RECORD RAWAT JALAN
               │ Tidak
               ▼
[Pilih record dengan jumlah template terbanyak & tarif > 0]
```

---

## ❓ 6. FAQ (Pertanyaan Umum)

**Q: Apakah aman dijalankan di database client yang sudah ada transaksi pasien bertahun-tahun?**  
*A: Sangat aman. Sistem secara otomatis mendeteksi transaksi di tabel `periksa_lab` dan `permintaan_pemeriksaan_lab`. Kode yang sudah pernah ada transaksinya otomatis diproteksi agar integritas database rekam medis tidak rusak.*

**Q: Apakah tabel backup memakan banyak kapasitas database?**  
*A: Tidak. Tabel master tarif lab dan template hanya berukuran beberapa ratus kilobyte (sangat ringan).*

**Q: Bagaimana jika suatu saat ada client klinik yang membuka layanan Rawat Inap dan butuh tarif kelas lagi?**  
*A: Anda cukup membuka menu **Master > Tarif Lab** dan menambahkan tarif kelas rawat inap yang diinginkan secara spesifik pada form input.*

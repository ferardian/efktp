# 📖 Panduan Seeder & Hak Akses Menu (EFKTP)

Dokumen ini berisi panduan untuk me-running seeder menu serta mengelola hak akses role menu dinamis pada aplikasi EFKTP.

---

## 📌 1. Penjelasan Singkat
Aplikasi EFKTP memiliki dua mode navigasi menu:
1. **Mode Static (Default)**: Menu dibaca langsung dari file Blade template.
2. **Mode Dynamic Role (`ENABLE_MENU_ROLE=true`)**: Menu dibaca secara dinamis dari database (tabel `menus` dan `menu_role`) berdasarkan role akun user yang sedang login (`admin`, `dokter`, `apoteker`, `petugas`, `owner`).

---

## 🚀 2. Cara Menjalankan Seeder Menu

### A. Menjalankan dari Host Terminal (macOS / Local OS)
Jika web server berjalan di container Docker dan `.env` terkonfigurasi `DB_HOST=host.docker.internal`:

```bash
DB_HOST=127.0.0.1 php artisan db:seed --class=MenuSeeder
```

> **Catatan:** `DB_HOST=127.0.0.1` ditambahkan secara temporer di depan command agar PHP CLI di Host OS bisa terhubung langsung ke port MySQL lokal (`3310`).

---

### B. Menjalankan dari Dalam Container Docker
Jika Anda me-running artisan dari dalam container Docker aplikasi web:

```bash
php artisan db:seed --class=MenuSeeder
```

---

## ⚠️ 3. Troubleshooting Error Koneksi DB

| Error | Penyebab | Solusi |
| :--- | :--- | :--- |
| `getaddrinfo for host.docker.internal failed` | Menjalankan `php artisan` dari terminal Host macOS tanpa flag `DB_HOST=127.0.0.1`. | Gunakan `DB_HOST=127.0.0.1 php artisan ...` saat run di terminal Host. |
| `Connection refused (select * from user...)` | File `.env` ter-set `DB_HOST=127.0.0.1` padahal aplikasi web berjalan di dalam container Docker. | Pastikan file `.env` diisi `DB_HOST=host.docker.internal`. |

---

## 🛠️ 4. Cara Menambahkan Menu Baru pada `MenuSeeder.php`

Jika ingin menambahkan menu atau submenu baru secara terstruktur:

1. Buka file `database/seeders/MenuSeeder.php`.
2. Tambahkan item array baru pada variabel `$menus`:

```php
// Contoh Tambah Menu Parent di Navbar
[
    'id' => 44, 
    'name' => 'Keuangan', 
    'url' => null, 
    'icon' => '<i class="ti ti-report-money fs-2"></i>', 
    'parent_id' => null, 
    'order_num' => 8, 
    'target' => '_self', 
    'position' => 'navbar', // 'navbar' (Top Bar) atau 'sidebar' (Menu Lainnya)
    'roles' => ['admin', 'dokter', 'petugas', 'owner'] // Role yang diizinkan
],

// Contoh Tambah Submenu
[
    'id' => 45, 
    'name' => 'Pembayaran Rawat Jalan', 
    'url' => 'keuangan/pembayaran-ralan', 
    'icon' => null, 
    'parent_id' => 44, // Menginduk ke ID Menu Keuangan
    'order_num' => 1, 
    'target' => '_self', 
    'position' => 'navbar', 
    'roles' => ['admin', 'dokter', 'petugas', 'owner']
]
```

3. Jalankan kembali seeder menu:
   ```bash
   DB_HOST=127.0.0.1 php artisan db:seed --class=MenuSeeder
   ```

---

## 🗄️ 5. Direct SQL Insert (Opsi Tanpa Reset Menu)

Jika hanya ingin menambahkan menu tertentu tanpa me-reset seluruh tabel `menus`:

```sql
-- 1. Tambah Menu Utama 'Keuangan'
INSERT INTO `menus` (`id`, `name`, `url`, `icon`, `parent_id`, `order_num`, `target`, `position`, `created_at`, `updated_at`)
VALUES (44, 'Keuangan', NULL, '<i class="ti ti-report-money fs-2"></i>', NULL, 8, '_self', 'navbar', NOW(), NOW());

-- 2. Tambah Sub-Menu 'Pembayaran Rawat Jalan'
INSERT INTO `menus` (`id`, `name`, `url`, `icon`, `parent_id`, `order_num`, `target`, `position`, `created_at`, `updated_at`)
VALUES (45, 'Pembayaran Rawat Jalan', 'keuangan/pembayaran-ralan', NULL, 44, 1, '_self', 'navbar', NOW(), NOW());

-- 3. Daftarkan Hak Akses Role
INSERT INTO `menu_role` (`menu_id`, `role`) VALUES 
(44, 'admin'), (44, 'dokter'), (44, 'petugas'), (44, 'owner'),
(45, 'admin'), (45, 'dokter'), (45, 'petugas'), (45, 'owner');
```

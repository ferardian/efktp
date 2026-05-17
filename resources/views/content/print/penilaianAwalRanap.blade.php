@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div class="m-4" style="font-size: 11px; font-family: Arial, Helvetica, sans-serif; line-height: 1.4;">
        <!-- Header -->
        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td style="font-size: 14px; font-weight: bold;">{{ $setting->nama_instansi }}</td>
                <td style="text-align: right; color: #555;">FORM: RM/RI/PA-KEP/01</td>
            </tr>
        </table>
        <hr style="margin: 5px 0 10px 0; border: 0; border-top: 2px solid #333;" />
        
        <table width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <td style="border: 1px solid #333; text-align: center; width: 50%; padding: 8px; background-color: #f5f5f5;">
                    <h4 style="margin: 0; font-size: 13px; text-transform: uppercase;">PENILAIAN AWAL KEPERAWATAN UMUM<br>PASIEN RAWAT INAP</h4>
                </td>
                <td style="border: 1px solid #333; padding: 6px; width: 50%; vertical-align: top;">
                    <table width="100%" style="border-collapse: collapse;">
                        <tr><td style="width: 35%;">Nama Pasien</td><td>: <b>{{ $data->regPeriksa->pasien->nm_pasien }}</b> ({{ $data->regPeriksa->pasien->jk }})</td></tr>
                        <tr><td>No. Rekam Medis</td><td>: <b>{{ $data->regPeriksa->no_rkm_medis }}</b></td></tr>
                        <tr><td>No. Rawat / Kamar</td><td>: {{ $data->no_rawat }}</td></tr>
                        <tr><td>Tgl. Lahir / Umur</td><td>: {{ Carbon\Carbon::parse($data->regPeriksa->pasien->tgl_lahir)->translatedFormat('d F Y') }} / {{ $data->regPeriksa->umurdaftar }} {{ $data->regPeriksa->sttsumur }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Metadata -->
        <table width="100%" style="border-collapse: collapse; margin-bottom: 10px; background-color: #fafafa;">
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; width: 50%;"><b>Tgl/Jam Pengkajian:</b> {{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y H:i:s') }}</td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 50%;"><b>DPJP Registrasi:</b> {{ $data->dokter ? $data->dokter->nm_dokter : '-' }}</td>
            </tr>
        </table>

        <!-- I. ANAMNESIS -->
        <table width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="2" style="border: 1px solid #333; padding: 4px; font-size: 11px; text-transform: uppercase;">I. ANAMNESIS & RIWAYAT KESEHATAN</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; width: 50%; vertical-align: top;">
                    <b>1. Cara Masuk & Anamnesis:</b>
                    <ul>
                        <li><b>Metode:</b> {{ $data->informasi }} (Hubungan: {{ $data->ket_informasi ?: '-' }})</li>
                        <li><b>Cara Masuk:</b> {{ $data->cara_masuk }} | <b>Kasus:</b> {{ $data->kasus_trauma }}</li>
                        <li><b>Tiba di Ruang Rawat:</b> {{ $data->tiba_diruang_rawat ?: '-' }}</li>
                    </ul>
                    <b>2. Riwayat Alergi & Hamil:</b>
                    <ul>
                        <li><b>Riwayat Alergi:</b> <span style="color:red; font-weight:bold;">{{ $data->riwayat_alergi ?: 'Tidak Ada' }}</span></li>
                        <li><b>Riwayat Kehamilan / Menyusui:</b> {{ $data->riwayat_kehamilan }} (Usia: {{ $data->riwayat_kehamilan_perkiraan ?: '-' }})</li>
                        <li><b>Transfusi Darah:</b> {{ $data->riwayat_tranfusi ?: '-' }} | <b>Alat Bantu:</b> {{ $data->alat_bantu_dipakai ?: '-' }}</li>
                    </ul>
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 50%; vertical-align: top;">
                    <b>3. Riwayat Penyakit:</b>
                    <ul>
                        <li><b>Riwayat Penyakit Sekarang (RPS):</b><br>{{ $data->rps }}</li>
                        <li><b>Riwayat Penyakit Dahulu (RPD):</b><br>{{ $data->rpd }}</li>
                        <li><b>Riwayat Penyakit Keluarga (RPK):</b><br>{{ $data->rpk }}</li>
                        <li><b>Riwayat Operasi:</b> {{ $data->riwayat_pembedahan ?: '-' }}</li>
                        <li><b>Riwayat Dirawat di RS:</b> {{ $data->riwayat_dirawat_dirs ?: '-' }}</li>
                        <li><b>Riwayat Penggunaan Obat:</b> {{ $data->rpo ?: '-' }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid #ddd; padding: 5px;">
                    <b>4. Riwayat Kebiasaan / Gaya Hidup:</b> 
                    Merokok: {{ $data->riwayat_merokok }} ({{ $data->riwayat_merokok_jumlah }} batang/hari) | 
                    Konsumsi Alkohol: {{ $data->riwayat_alkohol }} ({{ $data->riwayat_alkohol_jumlah }} gelas/hari) | 
                    Penggunaan Zat Tidur/Obat: {{ $data->riwayat_narkoba }} | 
                    Olahraga Rutin: {{ $data->riwayat_olahraga }}
                </td>
            </tr>
        </table>

        <!-- II. PEMERIKSAAN FISIK & TTV -->
        <table width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="4" style="border: 1px solid #333; padding: 4px; font-size: 11px; text-transform: uppercase;">II. PEMERIKSAAN FISIK & TANDA VITAL</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; width: 25%;"><b>Keadaan Umum:</b> {{ $data->pemeriksaan_keadaan_umum }}</td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 25%;"><b>Kesadaran / Mental:</b> {{ $data->pemeriksaan_mental }}</td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 25%;"><b>GCS (E,V,M):</b> {{ $data->pemeriksaan_gcs }}</td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 25%;"><b>Tekanan Darah:</b> {{ $data->pemeriksaan_td }} mmHg</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px;"><b>Nadi:</b> {{ $data->pemeriksaan_nadi }} x/menit</td>
                <td style="border: 1px solid #ddd; padding: 5px;"><b>Respirasi:</b> {{ $data->pemeriksaan_rr }} x/menit</td>
                <td style="border: 1px solid #ddd; padding: 5px;"><b>Suhu:</b> {{ $data->pemeriksaan_suhu }} °C</td>
                <td style="border: 1px solid #ddd; padding: 5px;"><b>SpO2:</b> {{ $data->pemeriksaan_spo2 }} %</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px;"><b>Berat Badan:</b> {{ $data->pemeriksaan_bb }} Kg</td>
                <td style="border: 1px solid #ddd; padding: 5px;"><b>Tinggi Badan:</b> {{ $data->pemeriksaan_tb }} cm</td>
                <td colspan="2" style="border: 1px solid #ddd; padding: 5px;">
                    @php
                        $bb = floatval($data->pemeriksaan_bb);
                        $tb = floatval($data->pemeriksaan_tb);
                        $bmi = '-';
                        if($bb > 0 && $tb > 0) {
                            $h = $tb / 100;
                            $bmi = round($bb / ($h * $h), 2);
                        }
                    @endphp
                    <b>BMI (IMT):</b> {{ $bmi }} Kg/m²
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid #ddd; padding: 5px; vertical-align: top;">
                    <b>Pemeriksaan Sistem Organ (Head-to-Toe):</b>
                    <table width="100%" style="border-collapse: collapse; margin-top: 4px;">
                        <tr>
                            <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                                <ul>
                                    <li><b>Kepala:</b> {{ $data->pemeriksaan_susunan_kepala }} (Ket: {{ $data->pemeriksaan_susunan_kepala_keterangan ?: '-' }})</li>
                                    <li><b>Wajah:</b> {{ $data->pemeriksaan_susunan_wajah }} (Ket: {{ $data->pemeriksaan_susunan_wajah_keterangan ?: '-' }})</li>
                                    <li><b>Leher:</b> {{ $data->pemeriksaan_susunan_leher }} | <b>Kejang:</b> {{ $data->pemeriksaan_susunan_kejang }} (Ket: {{ $data->pemeriksaan_susunan_kejang_keterangan ?: '-' }})</li>
                                    <li><b>Kardiovaskuler:</b> Nadi: {{ $data->pemeriksaan_kardiovaskuler_denyut_nadi }} | Sirkulasi: {{ $data->pemeriksaan_kardiovaskuler_sirkulasi }} (Ket: {{ $data->pemeriksaan_kardiovaskuler_sirkulasi_keterangan ?: '-' }}) | Pulsasi: {{ $data->pemeriksaan_kardiovaskuler_pulsasi }}</li>
                                    <li><b>Respirasi:</b> Pola: {{ $data->pemeriksaan_respirasi_pola_nafas }} | Retraksi: {{ $data->pemeriksaan_respirasi_retraksi }} | Suara: {{ $data->pemeriksaan_respirasi_suara_nafas }} | Volume: {{ $data->pemeriksaan_respirasi_volume_pernafasan }} | Irama: {{ $data->pemeriksaan_respirasi_irama_nafas }} | Batuk: {{ $data->pemeriksaan_respirasi_batuk }}</li>
                                    <li><b>Neurologi:</b> Lihat: {{ $data->pemeriksaan_neurologi_pengelihatan }} (Kacamata: {{ $data->pemeriksaan_neurologi_alat_bantu_penglihatan }}) | Dengar: {{ $data->pemeriksaan_neurologi_pendengaran }} | Bicara: {{ $data->pemeriksaan_neurologi_bicara }} (Ket: {{ $data->pemeriksaan_neurologi_bicara_keterangan ?: '-' }}) | Sensorik: {{ $data->pemeriksaan_neurologi_sensorik }} | Motorik: {{ $data->pemeriksaan_neurologi_motorik }} | Kekuatan: {{ $data->pemeriksaan_neurologi_kekuatan_otot }}</li>
                                </ul>
                            </td>
                            <td style="width: 50%; vertical-align: top;">
                                <ul>
                                    <li><b>Gastrointestinal:</b>
                                        Mulut: {{ $data->pemeriksaan_gastrointestinal_mulut }} (Ket: {{ $data->pemeriksaan_gastrointestinal_mulut_keterangan ?: '-' }}) | 
                                        Lidah: {{ $data->pemeriksaan_gastrointestinal_lidah }} (Ket: {{ $data->pemeriksaan_gastrointestinal_lidah_keterangan ?: '-' }}) | 
                                        Gigi: {{ $data->pemeriksaan_gastrointestinal_gigi }} (Ket: {{ $data->pemeriksaan_gastrointestinal_gigi_keterangan ?: '-' }}) | 
                                        Abdomen: {{ $data->pemeriksaan_gastrointestinal_abdomen }} (Ket: {{ $data->pemeriksaan_gastrointestinal_abdomen_keterangan ?: '-' }}) | 
                                        Peristaltik: {{ $data->pemeriksaan_gastrointestinal_peistatik_usus }} | Anus: {{ $data->pemeriksaan_gastrointestinal_anus }}
                                    </li>
                                    <li><b>Integument (Kulit):</b> Warna: {{ $data->pemeriksaan_integument_warnakulit }} | Turgor: {{ $data->pemeriksaan_integument_turgor }} | Kulit: {{ $data->pemeriksaan_integument_kulit }} | Resiko Decubitus: {{ $data->pemeriksaan_integument_dekubitas }}</li>
                                    <li><b>Muskuloskeletal:</b> Sendi: {{ $data->pemeriksaan_muskuloskletal_pergerakan_sendi }} | Kekuatan: {{ $data->pemeriksaan_muskuloskletal_kekauatan_otot }} | Fraktur: {{ $data->pemeriksaan_muskuloskletal_fraktur }} (Ket: {{ $data->pemeriksaan_muskuloskletal_fraktur_keterangan ?: '-' }}) | Nyeri Sendi: {{ $data->pemeriksaan_muskuloskletal_nyeri_sendi }} (Ket: {{ $data->pemeriksaan_muskuloskletal_nyeri_sendi_keterangan ?: '-' }}) | Oedema: {{ $data->pemeriksaan_muskuloskletal_oedema }} (Ket: {{ $data->pemeriksaan_muskuloskletal_oedema_keterangan ?: '-' }})</li>
                                    <li><b>Eliminasi:</b>
                                        BAB: {{ $data->pemeriksaan_eliminasi_bab_frekuensi_jumlah }} {{ $data->pemeriksaan_eliminasi_bab_frekuensi_durasi }} (Konsistensi: {{ $data->pemeriksaan_eliminasi_bab_konsistensi }} | Warna: {{ $data->pemeriksaan_eliminasi_bab_warna }})<br>
                                        BAK: {{ $data->pemeriksaan_eliminasi_bak_frekuensi_jumlah }} {{ $data->pemeriksaan_eliminasi_bak_frekuensi_durasi }} (Warna: {{ $data->pemeriksaan_eliminasi_bak_warna }} | Ket: {{ $data->pemeriksaan_eliminasi_bak_lainlain }})
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- III. POLA KEBIASAAN, FUNGSIONAL, PSIKOSOSIAL -->
        <table width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="2" style="border: 1px solid #333; padding: 4px; font-size: 11px; text-transform: uppercase;">III. POLA KEBIASAAN, FUNGSIONAL & PSIKOSOSIAL</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; width: 50%; vertical-align: top;">
                    <b>1. Pola ADL (Aktivitas Harian Barthel Index):</b>
                    <ul>
                        <li><b>Makan/Minum:</b> {{ $data->pola_aktifitas_makanminum }}</li>
                        <li><b>Mandi:</b> {{ $data->pola_aktifitas_mandi }}</li>
                        <li><b>Eliminasi:</b> {{ $data->pola_aktifitas_eliminasi }}</li>
                        <li><b>Berpakaian:</b> {{ $data->pola_aktifitas_berpakaian }}</li>
                        <li><b>Berpindah/Mobilisasi:</b> {{ $data->pola_aktifitas_berpindah }}</li>
                    </ul>
                    <b>2. Nutrisi & Tidur:</b>
                    <ul>
                        <li><b>Frekuensi Makan:</b> {{ $data->pola_nutrisi_frekuesi_makan }} x/hari | <b>Porsi:</b> {{ $data->pola_nutrisi_porsi_makan }} | <b>Jenis:</b> {{ $data->pola_nutrisi_jenis_makanan }}</li>
                        <li><b>Durasi Tidur:</b> {{ $data->pola_tidur_lama_tidur }} Jam/Hari | <b>Gangguan:</b> {{ $data->pola_tidur_gangguan }}</li>
                    </ul>
                    <b>3. Pengkajian Fungsi Tubuh:</b>
                    <ul>
                        <li><b>Kemampuan Harian:</b> {{ $data->pengkajian_fungsi_kemampuan_sehari }}</li>
                        <li><b>Aktivitas:</b> {{ $data->pengkajian_fungsi_aktifitas }} | <b>Ambulasi:</b> {{ $data->pengkajian_fungsi_ambulasi }}</li>
                        <li><b>Berjalan:</b> {{ $data->pengkajian_fungsi_berjalan }} (Ket: {{ $data->pengkajian_fungsi_berjalan_keterangan }})</li>
                        <li><b>Ekstremitas Atas / Bawah:</b> {{ $data->pengkajian_fungsi_ekstrimitas_atas }} / {{ $data->pengkajian_fungsi_ekstrimitas_bawah }}</li>
                        <li><b>Menggenggam / Koordinasi:</b> {{ $data->pengkajian_fungsi_menggenggam }} / {{ $data->pengkajian_fungsi_koordinasi }}</li>
                        <li><b>Kesimpulan Gangguan Fungsi:</b> <b>{{ $data->pengkajian_fungsi_kesimpulan }}</b></li>
                    </ul>
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 50%; vertical-align: top;">
                    <b>4. Psikologis & Hubungan Sosial:</b>
                    <ul>
                        <li><b>Kondisi Psikologis:</b> {{ $data->riwayat_psiko_kondisi_psiko }}</li>
                        <li><b>Riwayat Gangguan Jiwa:</b> {{ $data->riwayat_psiko_gangguan_jiwa }}</li>
                        <li><b>Perilaku:</b> {{ $data->riwayat_psiko_perilaku }} (Ket: {{ $data->riwayat_psiko_perilaku_keterangan ?: '-' }})</li>
                        <li><b>Hubungan dengan Keluarga:</b> {{ $data->riwayat_psiko_hubungan_keluarga }}</li>
                        <li><b>Tinggal Bersama:</b> {{ $data->riwayat_psiko_tinggal }} (Ket: {{ $data->riwayat_psiko_tinggal_keterangan ?: '-' }})</li>
                    </ul>
                    <b>5. Nilai Kepercayaan & Edukasi:</b>
                    <ul>
                        <li><b>Nilai Kepercayaan/Budaya Khusus:</b> {{ $data->riwayat_psiko_nilai_kepercayaan }} (Ket: {{ $data->riwayat_psiko_nilai_kepercayaan_keterangan ?: '-' }})</li>
                        <li><b>Kebutuhan Edukasi Kepada:</b> {{ $data->riwayat_psiko_edukasi_diberikan }} (Ket: {{ $data->riwayat_psiko_edukasi_diberikan_keterangan ?: '-' }})</li>
                        <li><b>Pendidikan Terakhir PJ:</b> {{ $data->riwayat_psiko_pendidikan_pj ?: '-' }}</li>
                    </ul>
                </td>
            </tr>
        </table>

        <!-- IV. NYERI & SKRINING RESIKO JATUH & GIZI -->
        <table width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="3" style="border: 1px solid #333; padding: 4px; font-size: 11px; text-transform: uppercase;">IV. NYERI, RESIKO JATUH & GIZI</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 5px; width: 34%; vertical-align: top;">
                    <b>1. Asesmen Nyeri:</b><br>
                    Status Nyeri: <b>{{ $data->penilaian_nyeri }}</b>
                    @if($data->penilaian_nyeri == 'Ada Nyeri')
                        <ul>
                            <li><b>Penyebab (P):</b> {{ $data->penilaian_nyeri_penyebab }} (Ket: {{ $data->penilaian_nyeri_ket_penyebab }})</li>
                            <li><b>Kualitas (Q):</b> {{ $data->penilaian_nyeri_kualitas }} (Ket: {{ $data->penilaian_nyeri_ket_kualitas }})</li>
                            <li><b>Lokasi (R):</b> {{ $data->penilaian_nyeri_lokasi }} (Menyebar: {{ $data->penilaian_nyeri_menyebar }})</li>
                            <li><b>Skala Nyeri (S):</b> <b style="color:red; font-size:12px;">{{ $data->penilaian_nyeri_skala }}</b> / 10</li>
                            <li><b>Waktu/Durasi (T):</b> {{ $data->penilaian_nyeri_waktu }}</li>
                            <li><b>Hilang Bila:</b> {{ $data->penilaian_nyeri_hilang }} (Ket: {{ $data->penilaian_nyeri_ket_hilang }})</li>
                            <li><b>Laporkan Dokter:</b> {{ $data->penilaian_nyeri_diberitahukan_dokter }} (Jam: {{ $data->penilaian_nyeri_jam_diberitahukan_dokter ?: '-' }})</li>
                        </ul>
                    @else
                        <p style="color:green; font-weight:bold; margin-top:5px;">Tidak ada keluhan nyeri pada pasien saat ini.</p>
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 33%; vertical-align: top;">
                    <b>2. Risiko Jatuh (Skala Morse / Sydney):</b><br>
                    @if($data->penilaian_jatuhmorse_totalnilai > 0)
                        <b>[DEWASA] Skala Morse:</b>
                        <ul>
                            <li>R. Jatuh (Nilai 1): {{ $data->penilaian_jatuhmorse_nilai1 }}</li>
                            <li>Dx Sekunder (Nilai 2): {{ $data->penilaian_jatuhmorse_nilai2 }}</li>
                            <li>Alat Ambulasi (Nilai 3): {{ $data->penilaian_jatuhmorse_nilai3 }}</li>
                            <li>Infus / IV Line (Nilai 4): {{ $data->penilaian_jatuhmorse_nilai4 }}</li>
                            <li>Gaya Berjalan (Nilai 5): {{ $data->penilaian_jatuhmorse_nilai5 }}</li>
                            <li>Status Mental (Nilai 6): {{ $data->penilaian_jatuhmorse_nilai6 }}</li>
                            <li style="font-size:12px;"><b>Total Skor Morse: <span style="color:red;">{{ $data->penilaian_jatuhmorse_totalnilai }}</span></b></li>
                            <li>
                                <b>Kesimpulan:</b>
                                @if($data->penilaian_jatuhmorse_totalnilai <= 24)
                                    <span style="color:green; font-weight:bold;">Resiko Rendah</span>
                                @elseif($data->penilaian_jatuhmorse_totalnilai >= 25 && $data->penilaian_jatuhmorse_totalnilai <= 44)
                                    <span style="color:orange; font-weight:bold;">Resiko Sedang</span>
                                @else
                                    <span style="color:red; font-weight:bold;">Resiko Tinggi</span>
                                @endif
                            </li>
                        </ul>
                    @else
                        <b>[ANAK] Skala Humpty Dumpty:</b>
                        <ul style="font-size: 10px;">
                            <li>Nilai Umur (1): {{ $data->penilaian_jatuhsydney_nilai1 }} | Sex (2): {{ $data->penilaian_jatuhsydney_nilai2 }}</li>
                            <li>Diagnosis (3): {{ $data->penilaian_jatuhsydney_nilai3 }} | Kognitif (4): {{ $data->penilaian_jatuhsydney_nilai4 }}</li>
                            <li>Lingkungan (5): {{ $data->penilaian_jatuhsydney_nilai5 }} | Bedah (6): {{ $data->penilaian_jatuhsydney_nilai6 }}</li>
                            <li>Obat (7): {{ $data->penilaian_jatuhsydney_nilai7 }} | Gerak (8): {{ $data->penilaian_jatuhsydney_nilai8 }}</li>
                            <li>Motorik (9): {{ $data->penilaian_jatuhsydney_nilai9 }} | Sensorik (10): {{ $data->penilaian_jatuhsydney_nilai10 }}</li>
                            <li>Orang Tua (11): {{ $data->penilaian_jatuhsydney_nilai11 }}</li>
                            <li style="font-size:11px;"><b>Total Skor Humpty: <span style="color:red;">{{ $data->penilaian_jatuhsydney_totalnilai }}</span></b></li>
                            <li>
                                <b>Kesimpulan:</b>
                                @if($data->penilaian_jatuhsydney_totalnilai <= 7)
                                    <span style="color:green; font-weight:bold;">Resiko Rendah</span>
                                @elseif($data->penilaian_jatuhsydney_totalnilai >= 8 && $data->penilaian_jatuhsydney_totalnilai <= 11)
                                    <span style="color:orange; font-weight:bold;">Resiko Sedang</span>
                                @else
                                    <span style="color:red; font-weight:bold;">Resiko Tinggi</span>
                                @endif
                            </li>
                        </ul>
                    @endif
                </td>
                <td style="border: 1px solid #ddd; padding: 5px; width: 33%; vertical-align: top;">
                    <b>3. Skrining Gizi MST:</b>
                    <ul>
                        <li><b>A. Turun Berat Badan:</b> {{ $data->skrining_gizi1 }} (Skor: {{ $data->nilai_gizi1 }})</li>
                        <li><b>B. Nafsu Makan Turun:</b> {{ $data->skrining_gizi2 }} (Skor: {{ $data->nilai_gizi2 }})</li>
                        <li style="font-size:11px;"><b>Total Skor MST: <span style="color:red;">{{ $data->nilai_total_gizi }}</span></b></li>
                        <li><b>Kesimpulan Gizi:</b> <b>{{ $data->nilai_total_gizi >= 2 ? 'Beresiko Malnutrisi (Rujuk Dietisen!)' : 'Resiko Rendah' }}</b></li>
                        <li><b>Diagnosa Khusus:</b> {{ $data->skrining_gizi_diagnosa_khusus }} (Ket: {{ $data->skrining_gizi_ket_diagnosa_khusus ?: '-' }})</li>
                        <li><b>Ket Dietisen:</b> Dibaca Dietisen: {{ $data->skrining_gizi_diketahui_dietisen }} (Jam: {{ $data->skrining_gizi_jam_diketahui_dietisen ?: '-' }})</li>
                    </ul>
                </td>
            </tr>
        </table>

        <!-- V. RENCANA KEPERAWATAN -->
        <table width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
            <tr style="background-color: #eee; font-weight: bold;">
                <td style="border: 1px solid #333; padding: 4px; font-size: 11px; text-transform: uppercase;">V. RENCANA TINDAKAN & KEPERAWATAN AWAL</td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; vertical-align: top; min-height: 50px;">
                    {!! nl2br(e($data->rencana)) !!}
                </td>
            </tr>
        </table>

        <!-- Signature Section -->
        <table width="100%" style="border-collapse: collapse; margin-top: 30px;">
            <tr>
                <td style="text-align: center; width: 33%; vertical-align: top;">
                    Dokter DPJP Pasien
                    <br><br><br><br>
                    <b><u>{{ $data->dokter ? $data->dokter->nm_dokter : '-' }}</u></b>
                </td>
                <td style="text-align: center; width: 33%; vertical-align: top;">
                    Perawat Pengkaji 2
                    <br><br><br><br>
                    <b><u>{{ $data->pegawai2 ? $data->pegawai2->nama : '-' }}</u></b>
                    <br><span style="color:#555; font-size:10px;">NIP/NIK: {{ $data->nip2 ?: '-' }}</span>
                </td>
                <td style="text-align: center; width: 34%; vertical-align: top;">
                    Perawat Pengkaji 1 (Utama)
                    <br><br><br><br>
                    <b><u>{{ $data->pegawai1 ? $data->pegawai1->nama : '-' }}</u></b>
                    <br><span style="color:#555; font-size:10px;">NIP/NIK: {{ $data->nip1 ?: '-' }}</span>
                </td>
            </tr>
        </table>
    </div>
@endsection

@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <style>
        @page {
            margin: 115px 20px 50px 20px;
        }
        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 90px;
        }
        footer {
            position: fixed;
            bottom: -35px;
            left: 0px;
            right: 0px;
            height: 15px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 3px;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
        table {
            font-size: 11px;
            width: 100%;
            border-collapse: collapse;
        }
        .subtitle {
            font-size: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .section-title {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 4px 8px;
            margin-top: 10px;
            margin-bottom: 5px;
            border: 1px solid #000;
            font-size: 12px;
        }
        .table-data {
            border: 1px solid #000;
            width: 100%;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        .table-data th {
            background-color: #f9f9f9;
        }
        .text-pre {
            white-space: pre-wrap;
            font-family: inherit;
            margin: 0;
        }
        .no-border td {
            border: none !important;
            padding: 2px 0px;
        }
    </style>

    <footer>
        <span class="page-number"></span>
    </footer>

    <header>
        <!-- Header Instansi -->
        <table class="header-table" width="100%">
            <tr>
                <td width="12%">
                    <img src="{{ 'data:image/jpeg;base64,' . base64_encode($setting->logo) }}" alt="" width="55px">
                </td>
                <td width="53%" style="text-align: left;">
                    <div style="font-size: 15px; font-weight: bold; text-transform: uppercase;">{{ $setting->nama_instansi }}</div>
                    <div class="subtitle">
                        {{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}, {{ $setting->propinsi }}<br/>
                        Telp. {{ $setting->kontak }}, Email: {{ $setting->email }}
                    </div>
                </td>
                <td width="35%" style="text-align: right; vertical-align: top;">
                    <div style="font-size: 13px; font-weight: bold; border: 1.5px solid #000; padding: 6px; text-align: center; border-radius: 4px; background-color: #fdfdfd; margin-top: 5px;">
                        HASIL MEDICAL CHECK UP
                    </div>
                </td>
            </tr>
        </table>
        
        <hr style="border: 0; border-top: 2px double #000; margin-top: 5px; margin-bottom: 0px;"/>
    </header>

    <div class="container">

        <!-- Demografi Pasien -->
        <table width="100%" class="table-data" style="margin-bottom: 10px;">
            <tr>
                <td width="18%"><strong>Nama Pasien</strong></td>
                <td width="32%">: {{ $data->regPeriksa->pasien->nm_pasien }}</td>
                <td width="18%"><strong>No. Rawat</strong></td>
                <td width="32%">: {{ $data->no_rawat }}</td>
            </tr>
            <tr>
                <td><strong>No. Rekam Medis</strong></td>
                <td>: {{ $data->regPeriksa->no_rkm_medis }}</td>
                <td><strong>Tgl. Pemeriksaan</strong></td>
                <td>: {{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Umur / JK</strong></td>
                <td>: {{ $data->regPeriksa->umurdaftar }} {{ $data->regPeriksa->sttsumur }} / {{ $data->regPeriksa->pasien->jk == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                <td><strong>Dokter Pemeriksa</strong></td>
                <td>: {{ $data->dokter->nm_dokter }}</td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td colspan="3">: {{ $data->regPeriksa->pasien->alamat }}, {{ $data->regPeriksa->pasien->kel->nm_kel }}, {{ $data->regPeriksa->pasien->kec->nm_kec }}, {{ $data->regPeriksa->pasien->kab->nm_kab }}</td>
            </tr>
        </table>

        <!-- Section I: Pemeriksaan Fisik Dasar -->
        <div class="section-title">I. PEMERIKSAAN FISIK DASAR</div>
        <table class="table-data" width="100%">
            <tr>
                <td width="25%"><strong>Tinggi Badan (TB)</strong></td>
                <td width="25%">: {{ $data->tb && $data->tb != '-' ? $data->tb . ' cm' : '-' }}</td>
                <td width="25%"><strong>Tekanan Darah (TD)</strong></td>
                <td width="25%">: {{ $data->td && $data->td != '-' ? $data->td . ' mmHg' : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Berat Badan (BB)</strong></td>
                <td>: {{ $data->bb && $data->bb != '-' ? $data->bb . ' kg' : '-' }}</td>
                <td><strong>Denyut Nadi</strong></td>
                <td>: {{ $data->nadi && $data->nadi != '-' ? $data->nadi . ' x/menit' : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Indeks Massa Tubuh (BMI)</strong></td>
                <td>: {{ $data->bmi && $data->bmi != '-' ? $data->bmi . ' kg/m²' : '-' }}</td>
                <td><strong>Laju Respirasi (RR)</strong></td>
                <td>: {{ $data->rr && $data->rr != '-' ? $data->rr . ' x/menit' : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Klasifikasi BMI</strong></td>
                <td>: {{ $data->kasifikasi_bmi ?? '-' }}</td>
                <td><strong>Suhu Tubuh</strong></td>
                <td>: {{ $data->suhu && $data->suhu != '-' ? $data->suhu . ' °C' : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Keadaan Umum</strong></td>
                <td>: {{ $data->keadaan ?? '-' }}</td>
                <td><strong>Tingkat Kesadaran</strong></td>
                <td>: {{ $data->kesadaran ?? '-' }}</td>
            </tr>
        </table>

        <!-- Section II: Pemeriksaan Fisik Sistemik -->
        <div class="section-title">II. PEMERIKSAAN FISIK SISTEMIK</div>
        <table class="table-data" width="100%">
            <!-- Jantung & Paru -->
            <tr>
                <td colspan="4" style="background-color: #fdfdfd; font-weight: bold;">Jantung & Paru-Paru</td>
            </tr>
            <tr>
                <td width="25%">Bunyi Napas</td>
                <td width="25%">: {{ $data->bunyi_napas ?? '-' }}</td>
                <td width="25%">Bunyi Tambahan</td>
                <td width="25%">: {{ $data->bunyi_tambahan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Bunyi Jantung</td>
                <td>: {{ $data->bunyi_jantung ?? '-' }}</td>
                <td>Batas Jantung</td>
                <td>: {{ $data->batas ?? '-' }}</td>
            </tr>

            <!-- Abdomen -->
            <tr>
                <td colspan="4" style="background-color: #fdfdfd; font-weight: bold;">Pemeriksaan Perut/Abdomen</td>
            </tr>
            <tr>
                <td>Inspeksi Abdomen</td>
                <td>: {{ $data->inspeksi ?? '-' }}</td>
                <td>Palpasi Abdomen</td>
                <td>: {{ $data->palpasi ?? '-' }}</td>
            </tr>
            <tr>
                <td>Hepar (Hati)</td>
                <td>: {{ $data->hepar ?? '-' }}</td>
                <td>Limpa</td>
                <td>: {{ $data->limpa ?? '-' }}</td>
            </tr>

            <!-- Tulang Belakang & Anggota Gerak -->
            <tr>
                <td colspan="4" style="background-color: #fdfdfd; font-weight: bold;">Tulang Belakang & Anggota Gerak</td>
            </tr>
            <tr>
                <td>Scoliosis / Kelainan</td>
                <td>: {{ $data->scoliosis ?? '-' }}</td>
                <td>Ekstremitas Atas</td>
                <td>: {{ $data->ekstrimitas_atas ?? '-' }} {{ $data->ekstrimitas_atas_ket && $data->ekstrimitas_atas_ket != '-' ? '(' . $data->ekstrimitas_atas_ket . ')' : '' }}</td>
            </tr>
            <tr>
                <td>Ekstremitas Bawah</td>
                <td colspan="3">: {{ $data->ekstrimitas_bawah ?? '-' }} {{ $data->ekstrimitas_bawah_ket && $data->ekstrimitas_bawah_ket != '-' ? '(' . $data->ekstrimitas_bawah_ket . ')' : '' }}</td>
            </tr>

            <!-- Kulit & Penglihatan -->
            <tr>
                <td colspan="4" style="background-color: #fdfdfd; font-weight: bold;">Kulit & Penglihatan</td>
            </tr>
            <tr>
                <td>Kondisi Kulit</td>
                <td>: {{ $data->kondisi_kulit ?? '-' }} {{ $data->penyakit_kulit && $data->penyakit_kulit != '-' ? '(' . $data->penyakit_kulit . ')' : '' }}</td>
                <td>Tes Buta Warna</td>
                <td>: {{ $data->buta_warna ?? '-' }}</td>
            </tr>
            <tr>
                <td>Visus / Ketajaman</td>
                <td colspan="3">: {{ $data->visus ?? '-' }}</td>
            </tr>

            <!-- Pendengaran -->
            <tr>
                <td colspan="4" style="background-color: #fdfdfd; font-weight: bold;">Pendengaran / Telinga</td>
            </tr>
            <tr>
                <td>Daun Telinga</td>
                <td>: {{ $data->daun_telinga ?? '-' }}</td>
                <td>Lubang Telinga</td>
                <td>: {{ $data->lubang_telinga ?? '-' }}</td>
            </tr>
            <tr>
                <td>Selaput Pendengaran</td>
                <td>: {{ $data->selaput_pendengaran ?? '-' }}</td>
                <td>Proc. Mastoideus</td>
                <td>: {{ $data->proc_mastoideus ?? '-' }}</td>
            </tr>
        </table>

        <!-- Section III: Pemeriksaan Laboratorium & Pencitraan (Penunjang) -->
        <div class="section-title">III. PEMERIKSAAN PENUNJANG & LABORATORIUM</div>
        <table class="table-data" width="100%">
            <tr>
                <th width="50%">Pemeriksaan Laboratorium</th>
                <th width="50%">Pencitraan (Rontgen Dada / Foto Thorax)</th>
            </tr>
            <tr>
                <td>
                    <div class="text-pre">{{ $data->laborat && $data->laborat != '-' ? $data->laborat : 'Tidak dilakukan pemeriksaan laboratorium.' }}</div>
                </td>
                <td>
                    <div class="text-pre">{{ $data->radiologi && $data->radiologi != '-' ? $data->radiologi : 'Tidak dilakukan pemeriksaan rontgen dada.' }}</div>
                </td>
            </tr>
        </table>
        
        <table class="table-data" width="100%" style="margin-top: 5px;">
            <tr>
                <td width="33%"><strong>Audiometri (Nada Murni)</strong></td>
                <td width="33%"><strong>Pemeriksaan EKG</strong></td>
                <td width="34%"><strong>Spirometri</strong></td>
            </tr>
            <tr>
                <td><div class="text-pre">{{ $data->audiometri && $data->audiometri != '-' ? $data->audiometri : '-' }}</div></td>
                <td><div class="text-pre">{{ $data->ekg && $data->ekg != '-' ? $data->ekg : '-' }}</div></td>
                <td><div class="text-pre">{{ $data->spirometri && $data->spirometri != '-' ? $data->spirometri : '-' }}</div></td>
            </tr>
            <tr>
                <td><strong>Treadmill Test</strong></td>
                <td colspan="2"><strong>Penunjang Lain-lain</strong></td>
            </tr>
            <tr>
                <td><div class="text-pre">{{ $data->treadmill && $data->treadmill != '-' ? $data->treadmill : '-' }}</div></td>
                <td colspan="2"><div class="text-pre">{{ $data->lainlain && $data->lainlain != '-' ? $data->lainlain : '-' }}</div></td>
            </tr>
        </table>

        <!-- Section IV: Kesimpulan & Anjuran -->
        <div class="section-title">IV. KESIMPULAN & ANJURAN MEDIS</div>
        <table class="table-data" width="100%">
            <tr>
                <td width="50%">
                    <strong>Kesimpulan Akhir:</strong>
                    <div class="text-pre" style="margin-top: 4px; font-weight: bold;">{{ $data->kesimpulan && $data->kesimpulan != '-' ? $data->kesimpulan : '-' }}</div>
                </td>
                <td width="50%">
                    <strong>Anjuran / Saran Medis:</strong>
                    <div class="text-pre" style="margin-top: 4px;">{{ $data->anjuran && $data->anjuran != '-' ? $data->anjuran : '-' }}</div>
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan Dokter -->
        <table width="100%" style="margin-top: 20px; border: none;">
            <tr class="no-border">
                <td width="60%"></td>
                <td width="40%" style="text-align: center;">
                    <p>{{ $setting->kabupaten }}, {{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</p>
                    <p style="margin-bottom: 5px;">Dokter Pemeriksa,</p>
                    
                    <div style="margin: 6px auto;">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Hasil Pemeriksaan MCU Pasien ' . $data->regPeriksa->pasien->nm_pasien . ' No. Rawat ' . $data->no_rawat . ' diverifikasi oleh dokter ' . $data->dokter->nm_dokter, 'QRCODE') }}" height="65" width="65" />
                    </div>
                    
                    <p style="font-weight: bold; text-decoration: underline; margin-top: 5px;">{{ $data->dokter->nm_dokter }}</p>
                    <p class="subtitle" style="margin-top: 2px;">SIP: {{ $data->dokter->no_ijn_praktek ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiket Obat - {{ $resep->no_resep }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        body {
            background: #fff;
            color: #000;
            @if($ukuran == '8x6')
                padding: 1.5mm 2.2mm 1.5mm 1.6mm;
            @elseif($ukuran == '8x5')
                padding: 1mm 2.2mm 1mm 1.5mm;
            @elseif($ukuran == '7x5')
                padding: 1mm 2mm 1mm 1.4mm;
            @elseif($ukuran == '5x3')
                padding: 0.8mm 1.5mm 0.8mm 1mm;
            @else
                padding: 1mm 2.2mm 1mm 1.5mm;
            @endif
        }
        .etiket-card {
            width: 100%;
            page-break-after: always;
        }
        .etiket-card:last-child {
            page-break-after: avoid;
        }

        /* ========================================================
           UKURAN: 8 x 6 cm (Width: 226.77pt, Height: 170.08pt)
           ======================================================== */
        @if($ukuran == '8x6')
        .etiket-frame {
            border: 1.8pt solid #0b5e28;
            box-sizing: border-box;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .instansi-logo {
            max-width: 36px;
            max-height: 36px;
            vertical-align: middle;
        }
        .clinic-name {
            font-size: 10.5pt;
            font-weight: bold;
            color: #000;
            line-height: 1.1;
        }
        .clinic-addr {
            font-size: 6pt;
            color: #222;
            line-height: 1.1;
            margin-top: 0.5px;
        }
        .clinic-phone {
            font-size: 7.8pt;
            font-weight: bold;
            color: #000;
            line-height: 1.1;
            margin-top: 0.5px;
        }
        .clinic-petugas {
            font-size: 5.6pt;
            color: #111;
            line-height: 1.1;
        }
        .divider {
            border-bottom: 1.8pt solid #0b5e28;
        }
        .section-title {
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 1px 0;
            line-height: 1.1;
        }
        .table-meta {
            width: 100%;
            border-collapse: collapse;
            padding: 0 4px;
        }
        .waktu-col {
            font-size: 6.6pt;
            font-weight: bold;
            padding-left: 3px;
        }
        .tgl-lahir-col {
            font-size: 6.6pt;
            font-weight: bold;
            text-align: right;
            padding-right: 3px;
        }
        .table-pasien {
            width: 100%;
            border-collapse: collapse;
            margin: 1px 0;
            padding: 0 4px;
        }
        .table-pasien td {
            vertical-align: top;
            line-height: 1.12;
            padding: 0.2px 0;
        }
        .col-lbl {
            width: 38px;
            font-size: 6.6pt;
            padding-left: 3px !important;
        }
        .col-sep {
            width: 8px;
            font-size: 6.6pt;
            text-align: center;
        }
        .col-val {
            font-size: 6.6pt;
            padding-right: 3px !important;
        }
        .val-bold {
            font-weight: bold;
        }
        .table-obat {
            width: 100%;
            border-collapse: collapse;
            padding: 1.5px 3px 0 3px;
        }
        .obat-nama {
            font-size: 8pt;
            font-weight: bold;
            padding-left: 3px;
        }
        .obat-qty {
            font-size: 8pt;
            font-weight: bold;
            text-align: right;
            padding-right: 3px;
        }
        .obat-racik-komposisi {
            font-size: 5.2pt;
            font-style: italic;
            color: #444;
            padding: 0 3px;
            line-height: 1;
        }
        .table-signa {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }
        .signa-spacer {
            width: 20%;
        }
        .signa-dosis {
            width: 50%;
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            line-height: 1;
        }
        .signa-satuan-note {
            width: 30%;
            text-align: right;
            font-size: 5pt;
            font-style: italic;
            vertical-align: middle;
            padding-right: 3px;
            white-space: nowrap;
        }
        .signa-petunjuk {
            text-align: center;
            font-size: 6.5pt;
            line-height: 1;
            margin-top: 0.5px;
        }
        .signa-doa {
            text-align: center;
            font-size: 5.8pt;
            line-height: 1;
            margin-top: 0.3px;
            padding-bottom: 1px;
        }

        /* ========================================================
           UKURAN: 8 x 5 cm (Width: 226.77pt, Height: 141.73pt) - DEFAULT
           ======================================================== */
        @elseif($ukuran == '8x5')
        .etiket-frame {
            border: 1.5pt solid #0b5e28;
            box-sizing: border-box;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .instansi-logo {
            max-width: 30px;
            max-height: 30px;
            vertical-align: middle;
        }
        .clinic-name {
            font-size: 9.5pt;
            font-weight: bold;
            color: #000;
            line-height: 1.05;
        }
        .clinic-addr {
            font-size: 5.5pt;
            color: #222;
            line-height: 1.05;
            margin-top: 0.5px;
        }
        .clinic-phone {
            font-size: 7pt;
            font-weight: bold;
            color: #000;
            line-height: 1.05;
            margin-top: 0.5px;
        }
        .clinic-petugas {
            font-size: 5pt;
            color: #111;
            line-height: 1;
        }
        .divider {
            border-bottom: 1.5pt solid #0b5e28;
        }
        .section-title {
            text-align: center;
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            padding: 0.5px 0;
            line-height: 1;
        }
        .table-meta {
            width: 100%;
            border-collapse: collapse;
            padding: 0 3px;
        }
        .waktu-col {
            font-size: 6pt;
            font-weight: bold;
            padding-left: 3px;
        }
        .tgl-lahir-col {
            font-size: 6pt;
            font-weight: bold;
            text-align: right;
            padding-right: 3px;
        }
        .table-pasien {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5px 0;
            padding: 0 3px;
        }
        .table-pasien td {
            vertical-align: top;
            line-height: 1.05;
            padding: 0.1px 0;
        }
        .col-lbl {
            width: 34px;
            font-size: 6pt;
            padding-left: 3px !important;
        }
        .col-sep {
            width: 6px;
            font-size: 6pt;
            text-align: center;
        }
        .col-val {
            font-size: 6pt;
            padding-right: 3px !important;
        }
        .val-bold {
            font-weight: bold;
        }
        .table-obat {
            width: 100%;
            border-collapse: collapse;
            padding: 1px 3px 0 3px;
        }
        .obat-nama {
            font-size: 7.2pt;
            font-weight: bold;
            padding-left: 3px;
        }
        .obat-qty {
            font-size: 7.2pt;
            font-weight: bold;
            text-align: right;
            padding-right: 3px;
        }
        .obat-racik-komposisi {
            font-size: 4.8pt;
            font-style: italic;
            color: #444;
            padding: 0 3px;
            line-height: 1;
        }
        .table-signa {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5px;
        }
        .signa-spacer {
            width: 20%;
        }
        .signa-dosis {
            width: 50%;
            text-align: center;
            font-size: 11.5pt;
            font-weight: bold;
            line-height: 1;
        }
        .signa-satuan-note {
            width: 30%;
            text-align: right;
            font-size: 4.6pt;
            font-style: italic;
            vertical-align: middle;
            padding-right: 3px;
            white-space: nowrap;
        }
        .signa-petunjuk {
            text-align: center;
            font-size: 5.8pt;
            line-height: 1;
            margin-top: 0.3px;
        }
        .signa-doa {
            text-align: center;
            font-size: 5pt;
            line-height: 1;
            margin-top: 0.3px;
            padding-bottom: 0.5px;
        }

        /* ========================================================
           UKURAN: 7 x 5 cm (Width: 198.43pt, Height: 141.73pt)
           ======================================================== */
        @elseif($ukuran == '7x5')
        .etiket-frame {
            border: 1.3pt solid #0b5e28;
            box-sizing: border-box;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .instansi-logo {
            max-width: 26px;
            max-height: 26px;
            vertical-align: middle;
        }
        .clinic-name {
            font-size: 8.5pt;
            font-weight: bold;
            color: #000;
            line-height: 1.05;
        }
        .clinic-addr {
            font-size: 5pt;
            color: #222;
            line-height: 1;
        }
        .clinic-phone {
            font-size: 6.5pt;
            font-weight: bold;
            color: #000;
            line-height: 1;
        }
        .clinic-petugas {
            font-size: 4.6pt;
            color: #111;
            line-height: 1;
        }
        .divider {
            border-bottom: 1.3pt solid #0b5e28;
        }
        .section-title {
            text-align: center;
            font-size: 7.8pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            padding: 0.5px 0;
        }
        .table-meta {
            width: 100%;
            border-collapse: collapse;
            padding: 0 3px;
        }
        .waktu-col {
            font-size: 5.5pt;
            font-weight: bold;
            padding-left: 2px;
        }
        .tgl-lahir-col {
            font-size: 5.5pt;
            font-weight: bold;
            text-align: right;
            padding-right: 2px;
        }
        .table-pasien {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5px 0;
            padding: 0 3px;
        }
        .table-pasien td {
            vertical-align: top;
            line-height: 1.05;
            padding: 0.1px 0;
        }
        .col-lbl {
            width: 30px;
            font-size: 5.5pt;
            padding-left: 2px !important;
        }
        .col-sep {
            width: 5px;
            font-size: 5.5pt;
            text-align: center;
        }
        .col-val {
            font-size: 5.5pt;
            padding-right: 2px !important;
        }
        .val-bold {
            font-weight: bold;
        }
        .table-obat {
            width: 100%;
            border-collapse: collapse;
            padding: 1px 2px 0 2px;
        }
        .obat-nama {
            font-size: 6.8pt;
            font-weight: bold;
            padding-left: 2px;
        }
        .obat-qty {
            font-size: 6.8pt;
            font-weight: bold;
            text-align: right;
            padding-right: 2px;
        }
        .obat-racik-komposisi {
            font-size: 4.5pt;
            font-style: italic;
            color: #444;
            padding: 0 2px;
        }
        .table-signa {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5px;
        }
        .signa-spacer {
            width: 20%;
        }
        .signa-dosis {
            width: 50%;
            text-align: center;
            font-size: 12.5pt;
            font-weight: bold;
            line-height: 1;
        }
        .signa-satuan-note {
            width: 30%;
            text-align: right;
            font-size: 4.2pt;
            font-style: italic;
            vertical-align: middle;
            padding-right: 2px;
            white-space: nowrap;
        }
        .signa-petunjuk {
            text-align: center;
            font-size: 5.8pt;
            line-height: 1;
            margin-top: 0.5px;
        }
        .signa-doa {
            text-align: center;
            font-size: 5.2pt;
            line-height: 1;
            margin-top: 0.5px;
            padding-bottom: 1px;
        }

        /* ========================================================
           UKURAN: 5 x 3 cm (Width: 141.73pt, Height: 85.04pt) - COMPACT
           ======================================================== */
        @elseif($ukuran == '5x3')
        .etiket-frame {
            border: 0.8pt solid #0b5e28;
            box-sizing: border-box;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .clinic-name {
            font-size: 5.8pt;
            font-weight: bold;
            color: #000;
            line-height: 1;
        }
        .clinic-addr {
            display: none;
        }
        .clinic-phone {
            font-size: 4.8pt;
            font-weight: bold;
            color: #000;
            line-height: 1;
        }
        .clinic-petugas {
            font-size: 3.8pt;
            color: #111;
            line-height: 0.95;
        }
        .divider {
            border-bottom: 0.8pt solid #0b5e28;
        }
        .section-title {
            text-align: center;
            font-size: 5pt;
            font-weight: bold;
            padding: 0;
            line-height: 1;
        }
        .table-meta {
            width: 100%;
            border-collapse: collapse;
            font-size: 4pt;
        }
        .waktu-col {
            font-size: 4pt;
            font-weight: bold;
            padding-left: 1px;
        }
        .tgl-lahir-col {
            font-size: 4pt;
            font-weight: bold;
            text-align: right;
            padding-right: 1px;
        }
        .table-pasien {
            width: 100%;
            border-collapse: collapse;
            font-size: 4pt;
        }
        .table-pasien td {
            vertical-align: top;
            line-height: 0.95;
            padding: 0;
        }
        .col-lbl {
            width: 20px;
            font-size: 4pt;
            padding-left: 1px !important;
        }
        .col-sep {
            width: 3px;
            font-size: 4pt;
            text-align: center;
        }
        .col-val {
            font-size: 4pt;
            padding-right: 1px !important;
        }
        .val-bold {
            font-weight: bold;
        }
        .table-obat {
            width: 100%;
            border-collapse: collapse;
        }
        .obat-nama {
            font-size: 4.8pt;
            font-weight: bold;
            padding-left: 1px;
        }
        .obat-qty {
            font-size: 4.8pt;
            font-weight: bold;
            text-align: right;
            padding-right: 1px;
        }
        .obat-racik-komposisi {
            font-size: 3.5pt;
            font-style: italic;
            color: #444;
            padding: 0 1px;
        }
        .table-signa {
            width: 100%;
            border-collapse: collapse;
        }
        .signa-spacer {
            width: 15%;
        }
        .signa-dosis {
            width: 70%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            line-height: 1;
        }
        .signa-satuan-note {
            width: 15%;
            text-align: right;
            font-size: 3.5pt;
            font-style: italic;
            vertical-align: middle;
            padding-right: 1px;
        }
        .signa-petunjuk {
            text-align: center;
            font-size: 4.2pt;
            line-height: 1;
        }
        .signa-doa {
            text-align: center;
            font-size: 3.8pt;
            line-height: 1;
        }
        @endif
    </style>
</head>
<body>

@php
    $pasien = $pasien ?? ($resep->regPeriksa->pasien ?? null);
    $namaDokter = $dokter_nama ?? ($resep->dokter->nm_dokter ?? ($resep->regPeriksa->dokter->nm_dokter ?? '-'));
    
    // Format kontak otomatis dari data setting faskes
    $rawKontak = trim($setting->kontak ?? '');
    $kontakFormatted = '-';
    if (!empty($rawKontak)) {
        if (preg_match('/^(hp|telp|wa)\b/i', $rawKontak)) {
            $kontakFormatted = $rawKontak;
        } else {
            $kontakFormatted = 'Hp: ' . $rawKontak;
        }
    }
@endphp

@foreach($items as $index => $item)
<div class="etiket-card">
    <div class="etiket-frame">
        <!-- 1. HEADER INSTANSI & APOTEKER (3 KOLOM AGAR TEKS 100% CENTER) -->
        <table class="header-table">
            <tr>
                @if($ukuran !== '5x3')
                    <td width="30" style="text-align: center; vertical-align: middle; padding-left: 2px;">
                        @if($setting && $setting->logo)
                            <img class="instansi-logo" src="data:image/jpeg;base64,{{ base64_encode($setting->logo) }}" alt="Logo">
                        @else
                            <svg width="24" height="24" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="18" stroke="#0b5e28" stroke-width="2" fill="none"/>
                                <rect x="16" y="8" width="8" height="24" rx="1" fill="#0b5e28"/>
                                <rect x="8" y="16" width="24" height="8" rx="1" fill="#0b5e28"/>
                            </svg>
                        @endif
                    </td>
                @endif
                <td style="text-align: center; vertical-align: middle; padding: 1px 2px;">
                    <div class="clinic-name">{{ $setting->nama_instansi ?? 'KLINIK' }}</div>
                    @if($ukuran !== '5x3')
                        <div class="clinic-addr">
                            {{ $setting->alamat_instansi ?? '' }}{{ !empty($setting->kabupaten) ? ', ' . $setting->kabupaten : '' }}
                        </div>
                    @endif
                    <div class="clinic-phone">{{ $kontakFormatted }}</div>
                    <div class="clinic-petugas">Apoteker : {{ $apoteker_nama ?: '-' }}</div>
                    <div class="clinic-petugas">SIPA : {{ $apoteker_sipa ?: '-' }}</div>
                </td>
                @if($ukuran !== '5x3')
                    <td width="30" style="text-align: center; vertical-align: middle;">
                        <!-- Spacer penyeimbang kolom logo agar posisi teks benar-benar di tengah -->
                    </td>
                @endif
            </tr>
        </table>

        <div class="divider"></div>

        <!-- 2. INSTALASI FARMASI & DATA PASIEN -->
        <div class="section-title">INSTALASI FARMASI</div>
        
        <table class="table-meta">
            <tr>
                <td class="waktu-col">{{ $waktu_lengkap }}</td>
                <td class="tgl-lahir-col">
                    Tgl.Lahir : {{ (!empty($pasien) && !empty($pasien->tgl_lahir)) ? date('d/m/Y', strtotime($pasien->tgl_lahir)) : '-' }}
                </td>
            </tr>
        </table>

        <table class="table-pasien">
            <tr>
                <td class="col-lbl">No.RM</td>
                <td class="col-sep">:</td>
                <td class="col-val val-bold">{{ $pasien->no_rkm_medis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-lbl">Nama</td>
                <td class="col-sep">:</td>
                <td class="col-val val-bold">
                    {{ strtoupper($pasien->nm_pasien ?? '-') }}
                    @if(!empty($pasien->jk) || !empty($resep->regPeriksa->umurdaftar))
                        / {{ $pasien->jk ?? '-' }} / {{ $resep->regPeriksa->umurdaftar ?? '' }} {{ $resep->regPeriksa->sttsumur ?? 'Th' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="col-lbl">Alamat</td>
                <td class="col-sep">:</td>
                <td class="col-val">{{ $alamat_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-lbl">Dokter</td>
                <td class="col-sep">:</td>
                <td class="col-val val-bold">{{ $namaDokter }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- 3. OBAT & ATURAN PAKAI (SIGNA) -->
        <table class="table-obat">
            <tr>
                <td class="obat-nama">{{ strtoupper($item['nama']) }}</td>
                <td class="obat-qty">{{ $item['jml_formatted'] }} {{ ucfirst(strtolower($item['satuan'])) }}</td>
            </tr>
        </table>

        @if(!empty($item['detail_racik']) && count($item['detail_racik']) > 0 && $ukuran !== '5x3')
            <div class="obat-racik-komposisi">
                ({{ implode(', ', array_slice($item['detail_racik'], 0, 4)) }}{{ count($item['detail_racik']) > 4 ? '...' : '' }})
            </div>
        @endif

        <table class="table-signa">
            <tr>
                <td class="signa-spacer"></td>
                <td class="signa-dosis">{{ $item['dosis'] }}</td>
                <td class="signa-satuan-note">{{ $item['satuan_note'] ?? '(sendok takar/tablet/bungkus)' }}</td>
            </tr>
        </table>

        <div class="signa-petunjuk">{{ $item['petunjuk'] ?: '-' }}</div>
        <div class="signa-doa">Semoga lekas sembuh</div>
    </div>
</div>
@endforeach

</body>
</html>

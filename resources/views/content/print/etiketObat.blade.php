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
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
        }
        .etiket-card {
            width: 100%;
            height: 100%;
            page-break-after: always;
            position: relative;
            box-sizing: border-box;
        }
        .etiket-card:last-child {
            page-break-after: avoid;
        }

        .border-line {
            border-bottom: 1px solid #000;
            margin: 2px 0;
        }
        .border-line-dashed {
            border-bottom: 1px dashed #444;
            margin: 2px 0;
        }

        /* ========================================================
           UKURAN: 8 x 6 cm (Width: 226.77pt, Height: 170.08pt)
           ======================================================== */
        @if($ukuran == '8x6')
        .etiket-box {
            padding: 7px 10px;
            font-size: 7.5pt;
            line-height: 1.2;
        }
        .instansi-header {
            width: 100%;
            border-collapse: collapse;
        }
        .instansi-logo {
            width: 28px;
            max-height: 28px;
            vertical-align: middle;
        }
        .instansi-nama {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .instansi-alamat {
            font-size: 6pt;
            color: #222;
        }
        .sub-header {
            text-align: center;
            font-size: 6.5pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            margin-top: 1px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.2pt;
            margin-top: 2px;
        }
        .info-table td {
            vertical-align: top;
            padding: 0.5px 0;
        }
        .pasien-nama {
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        .obat-header {
            margin-top: 3px;
            font-size: 8pt;
        }
        .obat-nama {
            font-weight: bold;
            font-size: 8.5pt;
        }
        .obat-qty {
            float: right;
            font-weight: bold;
            font-size: 8pt;
        }
        .signa-container {
            margin: 4px 0 2px 0;
            padding: 4px 6px;
            border: 1.5px dashed #000;
            text-align: center;
            background: #fafafa;
        }
        .signa-text {
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.3px;
        }
        .signa-ket {
            font-size: 7pt;
            font-style: italic;
            margin-top: 2px;
        }
        .racikan-detail {
            font-size: 6pt;
            color: #444;
            margin-top: 2px;
            line-height: 1.1;
        }
        .etiket-footer {
            margin-top: 3px;
            font-size: 6.2pt;
            color: #333;
            width: 100%;
        }

        /* ========================================================
           UKURAN: 8 x 5 cm (Width: 226.77pt, Height: 141.73pt)
           Standard Khanza label (rptItemResep)
           ======================================================== */
        @elseif($ukuran == '8x5')
        .etiket-box {
            padding: 5px 8px;
            font-size: 7pt;
            line-height: 1.15;
        }
        .instansi-header {
            width: 100%;
            border-collapse: collapse;
        }
        .instansi-logo {
            width: 22px;
            max-height: 22px;
            vertical-align: middle;
        }
        .instansi-nama {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .instansi-alamat {
            font-size: 5.5pt;
            color: #222;
        }
        .sub-header {
            text-align: center;
            font-size: 6pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            margin-top: 1px;
        }
        .info-table td {
            vertical-align: top;
            padding: 0.5px 0;
        }
        .pasien-nama {
            font-weight: bold;
            font-size: 7.5pt;
            text-transform: uppercase;
        }
        .obat-header {
            margin-top: 2px;
            font-size: 7.5pt;
        }
        .obat-nama {
            font-weight: bold;
            font-size: 8pt;
        }
        .obat-qty {
            float: right;
            font-weight: bold;
            font-size: 7.5pt;
        }
        .signa-container {
            margin: 3px 0 1px 0;
            padding: 3px 4px;
            border: 1.2px dashed #000;
            text-align: center;
            background: #fafafa;
        }
        .signa-text {
            font-size: 8.5pt;
            font-weight: bold;
        }
        .signa-ket {
            font-size: 6.5pt;
            font-style: italic;
            margin-top: 1px;
        }
        .racikan-detail {
            font-size: 5.5pt;
            color: #444;
            line-height: 1.1;
        }
        .etiket-footer {
            margin-top: 2px;
            font-size: 5.8pt;
            color: #333;
            width: 100%;
        }

        /* ========================================================
           UKURAN: 7 x 5 cm (Width: 198.43pt, Height: 141.73pt)
           ======================================================== */
        @elseif($ukuran == '7x5')
        .etiket-box {
            padding: 5px 6px;
            font-size: 6.8pt;
            line-height: 1.15;
        }
        .instansi-header {
            width: 100%;
            border-collapse: collapse;
        }
        .instansi-logo {
            width: 20px;
            max-height: 20px;
            vertical-align: middle;
        }
        .instansi-nama {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .instansi-alamat {
            font-size: 5.2pt;
            color: #222;
        }
        .sub-header {
            text-align: center;
            font-size: 5.8pt;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.5pt;
            margin-top: 1px;
        }
        .info-table td {
            vertical-align: top;
            padding: 0.5px 0;
        }
        .pasien-nama {
            font-weight: bold;
            font-size: 7.2pt;
            text-transform: uppercase;
        }
        .obat-header {
            margin-top: 2px;
            font-size: 7.2pt;
        }
        .obat-nama {
            font-weight: bold;
            font-size: 7.8pt;
        }
        .obat-qty {
            float: right;
            font-weight: bold;
            font-size: 7.2pt;
        }
        .signa-container {
            margin: 3px 0 1px 0;
            padding: 2.5px 4px;
            border: 1.2px dashed #000;
            text-align: center;
            background: #fafafa;
        }
        .signa-text {
            font-size: 8.2pt;
            font-weight: bold;
        }
        .signa-ket {
            font-size: 6.2pt;
            font-style: italic;
        }
        .racikan-detail {
            font-size: 5.2pt;
            color: #444;
            line-height: 1.1;
        }
        .etiket-footer {
            margin-top: 2px;
            font-size: 5.5pt;
            color: #333;
            width: 100%;
        }

        /* ========================================================
           UKURAN: 5 x 3 cm (Width: 141.73pt, Height: 85.04pt)
           Ultra-compact thermal label
           ======================================================== */
        @elseif($ukuran == '5x3')
        .etiket-box {
            padding: 3px 4px;
            font-size: 5.8pt;
            line-height: 1.1;
        }
        .instansi-header {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .instansi-nama {
            font-size: 6.8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .instansi-alamat {
            font-size: 4.8pt;
            color: #333;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 5.6pt;
            margin-top: 1px;
        }
        .info-table td {
            vertical-align: top;
            padding: 0;
        }
        .pasien-nama {
            font-weight: bold;
            font-size: 6.2pt;
            text-transform: uppercase;
        }
        .obat-header {
            margin-top: 1px;
            font-size: 6pt;
        }
        .obat-nama {
            font-weight: bold;
            font-size: 6.6pt;
        }
        .obat-qty {
            float: right;
            font-weight: bold;
            font-size: 6pt;
        }
        .signa-container {
            margin: 1.5px 0 1px 0;
            padding: 1.5px 2px;
            border: 1px dashed #000;
            text-align: center;
        }
        .signa-text {
            font-size: 7pt;
            font-weight: bold;
        }
        .signa-ket {
            font-size: 5.2pt;
            font-style: italic;
        }
        .racikan-detail {
            font-size: 4.8pt;
            color: #444;
            line-height: 1;
        }
        .etiket-footer {
            margin-top: 1px;
            font-size: 5pt;
            color: #444;
            width: 100%;
        }
        @endif
    </style>
</head>
<body>

@foreach($items as $index => $item)
<div class="etiket-card">
    <div class="etiket-box">
        <!-- HEADER INSTANSI -->
        @if($ukuran !== '5x3')
            <table class="instansi-header">
                <tr>
                    @if($setting && $setting->logo)
                        <td width="30" style="vertical-align: middle;">
                            <img class="instansi-logo" src="data:image/jpeg;base64,{{ base64_encode($setting->logo) }}" alt="Logo">
                        </td>
                    @endif
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="instansi-nama">{{ $setting->nama_instansi ?? 'INSTALASI FARMASI' }}</div>
                        <div class="instansi-alamat">
                            {{ $setting->alamat_instansi ?? '' }}{{ $setting->kabupaten ? ', ' . $setting->kabupaten : '' }}
                            {{ $setting->kontak ? ' | Telp: ' . $setting->kontak : '' }}
                        </div>
                    </td>
                </tr>
            </table>
            <div class="sub-header">INSTALASI FARMASI / APOTEK</div>
        @else
            <!-- Header Ringkas untuk 5x3 cm -->
            <div class="instansi-header">
                <div class="instansi-nama">{{ $setting->nama_instansi ?? 'FARMASI' }}</div>
                @if($setting && $setting->kontak)
                    <div class="instansi-alamat">Telp: {{ $setting->kontak }}</div>
                @endif
            </div>
        @endif

        <div class="border-line"></div>

        <!-- INFO RESEP & PASIEN -->
        <table class="info-table">
            <tr>
                <td width="55%">
                    <strong>No:</strong> {{ $resep->no_resep }}
                </td>
                <td width="45%" style="text-align: right;">
                    {{ date('d/m/Y', strtotime($resep->tgl_peresepan ?? date('Y-m-d'))) }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>RM:</strong> {{ $resep->regPeriksa->no_rkm_medis ?? '-' }}
                </td>
                <td style="text-align: right;">
                    @if(!empty($resep->regPeriksa->pasien->tgl_lahir))
                        Lhr: {{ date('d/m/Y', strtotime($resep->regPeriksa->pasien->tgl_lahir)) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="pasien-nama">{{ $resep->regPeriksa->pasien->nm_pasien ?? '-' }}</span>
                    @if(!empty($resep->regPeriksa->umurdaftar))
                        <span style="font-weight: normal;">({{ $resep->regPeriksa->umurdaftar }} {{ $resep->regPeriksa->sttsumur ?? 'Th' }} / {{ $resep->regPeriksa->pasien->jk ?? '' }})</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="border-line-dashed"></div>

        <!-- DETAIL OBAT & ATURAN PAKAI -->
        <div class="obat-header">
            <span class="obat-qty">{{ $item['jml'] }} {{ strtoupper($item['satuan']) }}</span>
            <span class="obat-nama">{{ $item['nama'] }}</span>
        </div>

        @if(!empty($item['detail_racik']) && count($item['detail_racik']) > 0 && $ukuran !== '5x3')
            <div class="racikan-detail">
                ({{ implode(', ', array_slice($item['detail_racik'], 0, 3)) }}{{ count($item['detail_racik']) > 3 ? '...' : '' }})
            </div>
        @endif

        <!-- SIGNA / ATURAN PAKAI BOX -->
        <div class="signa-container">
            <div class="signa-text">{{ $item['aturan_pakai'] }}</div>
            @if(!empty($item['keterangan']))
                <div class="signa-ket">{{ $item['keterangan'] }}</div>
            @endif
        </div>

        <!-- FOOTER ETIKET -->
        <table class="etiket-footer">
            <tr>
                <td width="60%">
                    @if($ukuran !== '5x3')
                        <span>Semoga Lekas Sembuh</span>
                    @else
                        <span>Farmasi</span>
                    @endif
                </td>
                <td width="40%" style="text-align: right;">
                    {{ $petugas ?? 'Apoteker' }}
                </td>
            </tr>
        </table>
    </div>
</div>
@endforeach

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Registrasi - {{ $reg->no_rawat }}</title>
    <style>
        @page {
            margin: 0;
            size: {{ $paperWidth == '58' ? '58mm' : '80mm' }} auto;
        }
        body {
            margin: 0;
            padding: 10px;
            font-family: 'Courier New', Courier, monospace, 'Segoe UI', Tahoma, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
            max-width: {{ $paperWidth == '58' ? '54mm' : '76mm' }};
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .header { margin-bottom: 6px; }
        .header h1 { font-size: 13px; margin: 0 0 2px 0; text-transform: uppercase; }
        .header p { font-size: 9px; margin: 0; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .title-struk { font-size: 11px; font-weight: bold; letter-spacing: 0.05em; margin: 2px 0; }
        .badge-poli {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #000;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            margin: 2px 0;
        }
        .nomor-antrean {
            font-size: 46px;
            font-weight: 900;
            letter-spacing: 2px;
            margin: 4px 0;
            line-height: 1;
        }
        table.info-table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
            margin: 6px 0;
        }
        table.info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        table.info-table td.label {
            width: 30%;
            color: #333;
        }
        table.info-table td.colon {
            width: 5%;
        }
        table.info-table td.val {
            font-weight: bold;
        }
        .footer { font-size: 9px; margin-top: 6px; }
    </style>
</head>
<body>
    <div class="text-center header">
        <h1>{{ $setting->nama_instansi ?? 'KLINIK / FASILITAS KESEHATAN' }}</h1>
        <p>{{ $setting->alamat_instansi ?? '' }}</p>
        @if(!empty($setting->kontak))
            <p>Telp: {{ $setting->kontak }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <div class="text-center">
        <div class="title-struk">BUKTI REGISTRASI MANDIRI (APM)</div>
        <div class="badge-poli">{{ $reg->poliklinik->nm_poli ?? '-' }}</div>
        
        <div style="font-size: 10px; margin-top: 4px;">NOMOR ANTREAN POLIKLINIK</div>
        <div class="nomor-antrean">
            {{ $reg->pcarePendaftaran->noUrut ?? $reg->no_reg }}
        </div>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td class="label">No. RM</td>
            <td class="colon">:</td>
            <td class="val">{{ $reg->no_rkm_medis }}</td>
        </tr>
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td class="val">{{ $reg->pasien->nm_pasien ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Usia</td>
            <td class="colon">:</td>
            <td class="val">{{ $reg->umurdaftar }} {{ $reg->sttsumur }}</td>
        </tr>
        <tr>
            <td class="label">Dokter</td>
            <td class="colon">:</td>
            <td class="val">{{ $reg->dokter->nm_dokter ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Penjamin</td>
            <td class="colon">:</td>
            <td class="val">{{ $reg->penjab->png_jawab ?? 'UMUM' }}</td>
        </tr>
        <tr>
            <td class="label">Waktu</td>
            <td class="colon">:</td>
            <td class="val">
                {{ \Carbon\Carbon::parse($reg->tgl_registrasi)->translatedFormat('d/m/Y') }} {{ substr($reg->jam_reg, 0, 5) }}
            </td>
        </tr>
        <tr>
            <td class="label">No. Rawat</td>
            <td class="colon">:</td>
            <td class="val">{{ $reg->no_rawat }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center footer">
        <p>Silakan menuju ruang tunggu <strong>{{ $reg->poliklinik->nm_poli ?? 'Poliklinik' }}</strong> dan tunggu pemanggilan nomor antrean Anda.</p>
        <p style="margin-top: 4px; font-weight: bold;">Semoga lekas sembuh!</p>
    </div>
</body>
</html>

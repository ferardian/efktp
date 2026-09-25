<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Antrean Loket - {{ $antrean->antrian }}</title>
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
        .header { margin-bottom: 8px; }
        .header h1 { font-size: 13px; margin: 0 0 2px 0; text-transform: uppercase; }
        .header p { font-size: 9px; margin: 0; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .title-struk { font-size: 12px; font-weight: bold; letter-spacing: 0.05em; margin: 4px 0; }
        .service-badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1px solid #000;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            margin: 4px 0;
        }
        .nomor-antrean {
            font-size: 42px;
            font-weight: 900;
            letter-spacing: 2px;
            margin: 6px 0;
            line-height: 1;
        }
        .meta-info { font-size: 10px; margin: 4px 0; }
        .footer { font-size: 9px; margin-top: 8px; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center header">
        <h1>{{ $setting->nama_instansi ?? 'KLINIK / FASILITAS KESEHATAN' }}</h1>
        <p>{{ $setting->alamat_instansi ?? '' }}</p>
        @if(!empty($setting->kontak))
            <p>Telp: {{ $setting->kontak }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <div class="text-center">
        <div class="title-struk">NOMOR ANTREAN PENDAFTARAN</div>
        <div class="service-badge">{{ $label }}</div>
        <div class="nomor-antrean">{{ $antrean->antrian }}</div>
        <div class="meta-info">
            {{ \Carbon\Carbon::parse($antrean->date_list)->translatedFormat('d/m/Y') }} · {{ substr($antrean->jam, 0, 5) }} WIB
        </div>
    </div>

    <div class="divider"></div>

    <div class="text-center footer">
        <p>Silakan menunggu hingga nomor antrean Anda dipanggil ke loket.</p>
        <p style="margin-top: 4px; font-weight: bold;">Terima kasih atas kesabaran Anda</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelang Pasien - {{ $pasien->nm_pasien }}</title>
    <style>
        @page {
            size: 57.15mm 18.6972mm;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }
        body {
            width: 57.15mm;
            height: 18.6972mm;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #000;
            overflow: hidden;
            position: relative;
        }
        .content {
            position: absolute;
            top: 1.5mm;
            left: 2.5mm;
            right: 2.5mm;
            bottom: 1.5mm;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: -0.2px;
            border-bottom: 0.5px solid #ccc;
            padding-bottom: 0.2mm;
            margin-bottom: 0.5mm;
        }
        .nama {
            font-size: 9pt;
            font-weight: 800;
            white-space: nowrap;
            line-height: 1;
            margin-bottom: 0.8mm;
            text-transform: uppercase;
        }
        .alamat-detail {
            font-size: 5.8pt;
            line-height: 1.1;
            height: 6mm;
            overflow: hidden;
            color: #333;
        }
        .no-rawat {
            position: absolute;
            bottom: 0;
            right: 0;
            font-size: 5pt;
            font-family: 'Courier New', Courier, monospace;
            background: #eee;
            padding: 0 1mm;
            border-radius: 1px;
        }
    </style>
</head>
<body onload="window.print(); setTimeout(window.close, 1500);">
    <div class="content">
        <div class="header-info">
            <span>RM: {{ $pasien->no_rkm_medis }}</span>
            <span>Lahir: {{ date('d/m/Y', strtotime($pasien->tgl_lahir)) }} ({{ $pasien->jk }})</span>
        </div>
        <div class="nama">{{ $pasien->nm_pasien }} ({{ $usia }})</div>
        <div class="alamat-detail">
            {{ $pasien->alamat }}<br>
            {{ $pasien->kel->nm_kel ?? '-' }}, {{ $pasien->kec->nm_kec ?? '-' }}
        </div>
        <div class="no-rawat">{{ $no_rawat }}</div>
    </div>
</body>
</html>

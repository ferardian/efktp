<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Resep_Obat_" . $tgl_awal . "_sd_" . $tgl_akhir . ".xls");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Resep Obat</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .meta-table td {
            font-size: 11px;
            font-weight: normal;
        }
        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        .data-table th {
            border: 1px solid #000000;
            background-color: #e3e3e3;
            font-weight: bold;
            font-size: 11px;
            padding: 6px;
            text-align: center;
        }
        .data-table td {
            border: 1px solid #000000;
            font-size: 11px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="title">REKAPITULASI RESEP OBAT</div>
    
    <table border="0" class="meta-table">
        <tr>
            <td style="font-weight:bold; width: 120px;">Periode</td>
            <td>: {{ $tgl_awal }} s.d {{ $tgl_akhir }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Poliklinik</td>
            <td>: {{ $poliName }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Dokter</td>
            <td>: {{ $dokterName }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Status Validasi</td>
            <td>: {{ $status }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 120px;">Kode Obat</th>
                <th style="width: 350px; text-align: left;">Nama Obat</th>
                <th style="width: 100px;">Satuan</th>
                <th style="width: 100px; text-align: right;">Total Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="text-align:center; mso-number-format:'\@';">{{ $item->kode_brng }}</td>
                    <td>{{ $item->nama_brng }}</td>
                    <td style="text-align:center;">{{ $item->satuan }}</td>
                    <td style="text-align:right;">{{ number_format($item->total_qty, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

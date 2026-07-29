<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Obat_Barang_" . $statusLabel . "_" . date('Y-m-d') . ".xlsx");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Obat dan Barang</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
        .meta-table td {
            font-size: 11px;
            font-weight: normal;
        }
        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        .data-table th {
            border: 1px solid #000000;
            background-color: #2fb344;
            color: #ffffff;
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
    <div class="title">LAPORAN DATA OBAT / BARANG FARMASI</div>
    
    <table border="0" class="meta-table">
        <tr>
            <td style="font-weight:bold; width: 120px;">Instansi</td>
            <td>: {{ $setting->nama_instansi ?? 'KLINIK' }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Status Filter</td>
            <td>: {{ $statusLabel }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">Tanggal Cetak</td>
            <td>: {{ date('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 120px;">Kode Barang</th>
                <th style="width: 300px; text-align: left;">Nama Obat / Barang</th>
                <th style="width: 80px;">Dosis</th>
                <th style="width: 100px;">Satuan Kecil</th>
                <th style="width: 80px;">Stok Total</th>
                <th style="width: 150px;">Kandungan / Letak</th>
                <th style="width: 120px;">Jenis</th>
                <th style="width: 120px;">Kategori</th>
                <th style="width: 120px;">Golongan</th>
                <th style="width: 150px;">Industri / Produsen</th>
                <th style="width: 200px;">Mapping PCare</th>
                <th style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $idx => $row)
                @php
                    $stok = 0;
                    if ($row->gudangBarang && is_iterable($row->gudangBarang)) {
                        foreach ($row->gudangBarang as $g) {
                            $stok += (float)$g->stok;
                        }
                    }
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $idx + 1 }}</td>
                    <td style="text-align:center; mso-number-format:'\@';">{{ $row->kode_brng }}</td>
                    <td>{{ $row->nama_brng }}</td>
                    <td style="text-align:center;">{{ $row->kapasitas ?: '-' }}</td>
                    <td style="text-align:center;">{{ $row->satuan->satuan ?? '-' }}</td>
                    <td style="text-align:right;">{{ $stok }}</td>
                    <td>{{ $row->letak_barang ?: '-' }}</td>
                    <td>{{ $row->jenis->nama ?? '-' }}</td>
                    <td>{{ $row->kategori->nama ?? '-' }}</td>
                    <td>{{ $row->golongan->nama ?? '-' }}</td>
                    <td>{{ $row->industri->nama_industri ?? '-' }}</td>
                    <td>{{ $row->mapping->nama_brng_pcare ?? '-' }}</td>
                    <td style="text-align:center;">{{ $row->status == '1' ? 'Aktif' : 'Non-Aktif' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

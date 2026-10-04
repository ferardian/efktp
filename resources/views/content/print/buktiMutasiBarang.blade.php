<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Mutasi Obat & BHP - {{ $tgl }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            color: #555;
        }
        .title-doc {
            text-align: center;
            margin-bottom: 15px;
        }
        .title-doc h3 {
            margin: 0;
            font-size: 14px;
            text-decoration: underline;
        }
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #777;
            padding: 5px 6px;
            font-size: 10.5px;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .signatures {
            width: 100%;
            margin-top: 30px;
        }
        .signatures td {
            text-align: center;
            width: 33.33%;
            vertical-align: top;
        }
        .sign-space {
            height: 60px;
        }
        @media print {
            body { margin: 10mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; font-size: 12px; cursor: pointer; background: #007bff; color: #fff; border: none; border-radius: 4px;">
            🖨️ Cetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 6px 12px; font-size: 12px; cursor: pointer; background: #6c757d; color: #fff; border: none; border-radius: 4px;">
            Tutup
        </button>
    </div>

    <!-- Kop Surat -->
    <div class="header">
        <h2>{{ $setting->nama_instansi ?? config('app.name', 'KLINIK') }}</h2>
        <p>{{ $setting->alamat_instansi ?? '-' }} | Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <div class="title-doc">
        <h3>BUKTI SERAH TERIMA & MUTASI OBAT / BHP</h3>
        <small style="color: #666;">Standar SIMKES Khanza (mutasibarang)</small>
    </div>

    <!-- Metadata Informasi -->
    <table class="info-table">
        <tr>
            <td width="18%"><strong>Tanggal Mutasi</strong></td>
            <td width="3%">:</td>
            <td width="35%">{{ date('d-m-Y H:i:s', strtotime($tgl)) }}</td>
            <td width="18%"><strong>Gudang/Depo Asal</strong></td>
            <td width="3%">:</td>
            <td width="23%">{{ $bangsalDari->nm_bangsal ?? $items->first()->kd_bangsaldari ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Keterangan</strong></td>
            <td>:</td>
            <td>{{ $items->first()->keterangan ?? '-' }}</td>
            <td><strong>Gudang/Depo Tujuan</strong></td>
            <td>:</td>
            <td><strong>{{ $bangsalKe->nm_bangsal ?? $items->first()->kd_bangsalke ?? '-' }}</strong></td>
        </tr>
    </table>

    <!-- Tabel Daftar Barang -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Kode</th>
                <th width="32%">Nama Obat / BHP</th>
                <th width="8%">Satuan</th>
                <th width="12%">No. Batch</th>
                <th width="10%" class="text-end">Jumlah</th>
                <th width="10%" class="text-end">HPP (Rp)</th>
                <th width="10%" class="text-end">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalJml = 0; 
                $totalNominal = 0;
            @endphp
            @forelse($items as $idx => $it)
                @php
                    $sub = $it->jml * $it->harga;
                    $totalJml += $it->jml;
                    $totalNominal += $sub;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $it->kode_brng }}</td>
                    <td>{{ $it->nama_brng }}</td>
                    <td class="text-center">{{ $it->satuan ?? '-' }}</td>
                    <td class="text-center">{{ !empty($it->no_batch) ? $it->no_batch : '-' }}</td>
                    <td class="text-end"><strong>{{ number_format($it->jml, 0, ',', '.') }}</strong></td>
                    <td class="text-end">{{ number_format($it->harga, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($sub, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">Tidak ada item barang dalam mutasi ini</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #fafafa;">
                <td colspan="5" class="text-end">TOTAL KESELURUHAN:</td>
                <td class="text-end">{{ number_format($totalJml, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-end">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan Serah Terima -->
    <table class="signatures">
        <tr>
            <td>
                Petugas Penyerah,<br>
                <strong>{{ $bangsalDari->nm_bangsal ?? 'Gudang Asal' }}</strong>
                <div class="sign-space"></div>
                ( ............................................ )
            </td>
            <td>
                Mengetahui,<br>
                <strong>Penanggung Jawab Farmasi</strong>
                <div class="sign-space"></div>
                ( ............................................ )
            </td>
            <td>
                Petugas Penerima,<br>
                <strong>{{ $bangsalKe->nm_bangsal ?? 'Depo Tujuan' }}</strong>
                <div class="sign-space"></div>
                ( ............................................ )
            </td>
        </tr>
    </table>
</body>
</html>

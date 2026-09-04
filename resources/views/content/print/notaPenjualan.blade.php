<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - {{ $penjualan->nota_jual }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 3mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 5px;
            max-width: 80mm;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .header-instansi {
            margin-bottom: 5px;
        }
        .nama-instansi {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .alamat-instansi {
            font-size: 9.5px;
            line-height: 1.2;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .table-items td {
            vertical-align: top;
            padding: 2px 0;
        }
        .table-meta {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }
        .table-meta td {
            padding: 1px 0;
        }
        .table-totals {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }
        .table-totals td {
            padding: 1px 0;
        }
        .footer {
            font-size: 9.5px;
            margin-top: 8px;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; }
        }
        .btn-print {
            background: #206bc4;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print text-center" style="margin-bottom: 10px;">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Struk</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">✖️ Tutup</button>
    </div>

    <!-- Header Instansi -->
    <div class="header-instansi text-center">
        @if($setting && $setting->logo)
            <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo" style="max-height: 38px; margin-bottom: 3px;"><br>
        @endif
        <div class="nama-instansi">{{ $setting->nama_instansi ?? 'KLINIK / APOTEK' }}</div>
        <div class="alamat-instansi">{{ $setting->alamat_instansi ?? '' }}</div>
        <div class="alamat-instansi">{{ $setting->kabupaten ?? '' }} {{ $setting->kontak ? '| Telp: ' . $setting->kontak : '' }}</div>
    </div>

    <div class="divider"></div>

    <!-- Meta Penjualan -->
    <table class="table-meta">
        <tr>
            <td width="30%">No. Nota</td>
            <td>: <span class="fw-bold">{{ $penjualan->nota_jual }}</span></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ date('d-m-Y', strtotime($penjualan->tgl_jual)) }} {{ date('H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $penjualan->petugas->nama ?? $penjualan->nip }}</td>
        </tr>
        <tr>
            <td>Pembeli</td>
            <td>: {{ $penjualan->nm_pasien }} {{ $penjualan->no_rkm_medis && $penjualan->no_rkm_medis !== '-' ? '(' . $penjualan->no_rkm_medis . ')' : '' }}</td>
        </tr>
        <tr>
            <td>Jenis</td>
            <td>: {{ $penjualan->jns_jual }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Daftar Item Obat -->
    <table class="table-items">
        @foreach($penjualan->detailJual as $item)
            <tr>
                <td colspan="2" class="fw-bold">{{ $item->barang->nama_brng ?? $item->kode_brng }}</td>
            </tr>
            <tr>
                <td class="text-start">
                    {{ $item->jumlah }} {{ $item->kode_sat }} x {{ number_format($item->h_jual, 0, ',', '.') }}
                    @if($item->dis > 0)
                        (Disc {{ $item->dis }}%)
                    @endif
                </td>
                <td class="text-end fw-bold">
                    {{ number_format($item->total, 0, ',', '.') }}
                </td>
            </tr>
            @if(!empty($item->aturan_pakai))
                <tr>
                    <td colspan="2" style="font-size: 9px; font-style: italic; color: #333;">
                        &bull; Aturan: {{ $item->aturan_pakai }}
                    </td>
                </tr>
            @endif
        @endforeach
    </table>

    <div class="divider"></div>

    <!-- Rincian Total -->
    <table class="table-totals">
        <tr>
            <td>Subtotal</td>
            <td class="text-end">Rp {{ number_format($totalObat, 0, ',', '.') }}</td>
        </tr>
        @if($penjualan->ongkir > 0)
            <tr>
                <td>Ongkos Kirim</td>
                <td class="text-end">Rp {{ number_format($penjualan->ongkir, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if($penjualan->ppn > 0)
            <tr>
                <td>PPN</td>
                <td class="text-end">Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if(isset($pembulatan) && $pembulatan != 0)
            <tr>
                <td>Pembulatan</td>
                <td class="text-end">{{ $pembulatan > 0 ? '+' : '' }}Rp {{ number_format($pembulatan, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="fw-bold" style="font-size: 12px;">
            <td>TOTAL TAGIHAN</td>
            <td class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Metode Bayar</td>
            <td class="text-end">{{ $penjualan->nama_bayar }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td class="text-end fw-bold">{{ $penjualan->status }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Footer Struk -->
    <div class="footer text-center">
        <div>Terima kasih atas kepercayaan Anda</div>
        <div>Semoga lekas sembuh</div>
        <div style="font-size: 8px; color: #555; margin-top: 4px;">Dicetak pada: {{ date('d/m/Y H:i:s') }}</div>
    </div>
</body>
</html>

@extends('content.print.main')

@section('content')
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 10mm 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        .report-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .report-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 4px 0;
            color: #0f172a;
        }

        .report-meta {
            width: 100%;
            margin-bottom: 12px;
            font-size: 11px;
            border-collapse: collapse;
        }

        .report-meta td {
            padding: 3px 6px;
            border: none;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 15px;
        }

        .table-data th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-align: center;
            border: 1px solid #94a3b8;
            padding: 6px 5px;
            font-size: 10px;
            text-transform: uppercase;
        }

        .table-data td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .table-data tr:nth-child(even) td {
            background-color: #fafbfc;
        }

        .table-data tfoot th,
        .table-data tfoot td {
            background-color: #e2e8f0;
            font-weight: 700;
            border: 1px solid #94a3b8;
            padding: 6px 6px;
        }

        .text-center { text-align: center !important; }
        .text-end { text-align: right !important; }
        .text-start { text-align: left !important; }
        .fw-bold { font-weight: 700 !important; }

        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature-table td {
            border: none;
            font-size: 11px;
            text-align: center;
            vertical-align: top;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="no-print" style="margin-bottom: 15px; display: flex; justify-content: flex-end; gap: 8px;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #0284c7; color: #fff; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
            Cetak / Print
        </button>
        <button onclick="window.close()" style="padding: 6px 14px; background: #64748b; color: #fff; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;">
            Tutup
        </button>
    </div>

    {{-- Kop Surat Resmi --}}
    @include('content.print._kopSurat')

    <div class="report-header">
        <div class="report-title">Laporan Penjualan Obat Per Item</div>
    </div>

    {{-- Parameter Informasi Filter --}}
    <table class="report-meta">
        <tr>
            <td style="width: 15%; font-weight: 600;">Periode Penjualan</td>
            <td style="width: 35%;">: {{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }} s.d {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}</td>
            <td style="width: 15%; font-weight: 600;">Sumber Transaksi</td>
            <td style="width: 35%;">: 
                @if($sumber === 'bebas')
                    Penjualan Bebas (Kasir Apotek)
                @elseif($sumber === 'resep')
                    Resep Pasien (Ralan & Ranap)
                @else
                    Semua (Konsolidasi Bebas & Resep)
                @endif
            </td>
        </tr>
        <tr>
            <td style="font-weight: 600;">Kategori / Jenis</td>
            <td>: {{ $namaJenis ?: 'Semua Kategori' }}</td>
            <td style="font-weight: 600;">Waktu Cetak</td>
            <td>: {{ date('d-m-Y H:i:s') }}</td>
        </tr>
    </table>

    {{-- Tabel Rincian Penjualan Per Item --}}
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 9%;">Kode Obat</th>
                <th style="width: 25%;" class="text-start">Nama Obat</th>
                <th style="width: 6%;">Satuan</th>
                <th style="width: 11%;" class="text-start">Jenis</th>
                @if($sumber === 'semua')
                    <th style="width: 6%;">Qty Bebas</th>
                    <th style="width: 6%;">Qty Resep</th>
                    <th style="width: 6%;">Total Qty</th>
                @else
                    <th style="width: 8%;">Qty</th>
                @endif
                <th style="width: 10%;" class="text-end">Total HPP</th>
                <th style="width: 10%;" class="text-end">Total Jual</th>
                <th style="width: 9%;" class="text-end">Laba Kotor</th>
                <th style="width: 6%;" class="text-center">Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $idx => $item)
                @php
                    $marginPersen = floatval($item->total_hpp) > 0 
                        ? round((floatval($item->laba_kotor) / floatval($item->total_hpp)) * 100, 2) 
                        : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center font-monospace" style="font-size: 9.5px;">{{ $item->kode_brng }}</td>
                    <td class="text-start fw-bold">{{ $item->nama_brng }}</td>
                    <td class="text-center">{{ $item->nama_satuan ?? '-' }}</td>
                    <td class="text-start">{{ $item->nama_jenis ?? '-' }}</td>
                    @if($sumber === 'semua')
                        <td class="text-center">{{ number_format($item->qty_bebas, 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($item->qty_resep, 0, ',', '.') }}</td>
                        <td class="text-center fw-bold">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    @else
                        <td class="text-center fw-bold">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    @endif
                    <td class="text-end">Rp {{ number_format($item->total_hpp, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->total_jual, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: {{ $item->laba_kotor >= 0 ? '#15803d' : '#b91c1c' }};">
                        Rp {{ number_format($item->laba_kotor, 0, ',', '.') }}
                    </td>
                    <td class="text-center fw-bold">{{ $marginPersen }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $sumber === 'semua' ? 12 : 10 }}" class="text-center" style="padding: 16px; color: #64748b;">
                        Tidak ada data transaksi penjualan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-center">TOTAL KESELURUHAN</th>
                @if($sumber === 'semua')
                    <th class="text-center">{{ number_format($items->sum('qty_bebas'), 0, ',', '.') }}</th>
                    <th class="text-center">{{ number_format($items->sum('qty_resep'), 0, ',', '.') }}</th>
                    <th class="text-center fw-bold">{{ number_format($grandQty, 0, ',', '.') }}</th>
                @else
                    <th class="text-center fw-bold">{{ number_format($grandQty, 0, ',', '.') }}</th>
                @endif
                <th class="text-end">Rp {{ number_format($grandHpp, 0, ',', '.') }}</th>
                <th class="text-end">Rp {{ number_format($grandJual, 0, ',', '.') }}</th>
                <th class="text-end">Rp {{ number_format($grandLaba, 0, ',', '.') }}</th>
                <th class="text-center fw-bold">{{ $avgMargin }}%</th>
            </tr>
        </tfoot>
    </table>

    {{-- Tanda Tangan --}}
    <table class="signature-table">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 30%;">
                <div>Dicetak pada: {{ date('d F Y') }}</div>
                <div style="margin-top: 4px; font-weight: 600;">Petugas Farmasi / Apoteker,</div>
                <div style="height: 60px;"></div>
                <div style="border-bottom: 1px solid #333; width: 180px; margin: 0 auto;"></div>
                <div style="margin-top: 4px; font-size: 10px; color: #64748b;">( Tanda Tangan & Nama Terang )</div>
            </td>
        </tr>
    </table>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Uncomment jika ingin auto print saat halaman dibuka
            // window.print();
        });
    </script>
@endsection

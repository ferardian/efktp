@extends('content.print.main')

@section('content')
    <style>
        @page {
            margin: 20px 20px 20px 20px !important;
        }
        body {
            font-size: 9px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .kop-table td {
            border: none !important;
        }
        .meta-table td {
            border: none !important;
            padding: 2px 0px;
            font-size: 9px;
        }
        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 8px;
        }
        .data-table th {
            border: 1px solid #000000;
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 8.5px;
            padding: 4px 2px;
            text-align: center;
        }
        .data-table td {
            border: 1px solid #000000;
            font-size: 8px;
            padding: 3px 2px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
    
    <div width="100%">
        <!-- Header Kop Surat -->
        <table width="100%" border="0" class="kop-table" style="margin-bottom: 5px;">
            <tr>
                @if($setting && $setting->logo)
                <td width="10%" style="text-align: center; vertical-align: middle;">
                    <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo" width="45px">
                </td>
                @endif
                <td width="90%" style="text-align: center;">
                    <h3 style="font-size: 13px; font-weight: bold; margin: 0; text-transform: uppercase;">{{ $setting->nama_instansi ?? 'KLINIK' }}</h3>
                    <p style="margin: 2px 0; font-size: 9px; color: #333;">{{ $setting->alamat_instansi ?? '' }}, {{ $setting->kabupaten ?? '' }}</p>
                    <p style="margin: 0; font-size: 9px; color: #333;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
                </td>
            </tr>
        </table>
        <hr style="border: 0; border-top: 1.5px solid #000; margin-top: 2px; margin-bottom: 8px;">

        <div style="text-align: center; font-size: 11px; font-weight: bold; text-decoration: underline; margin-bottom: 8px; text-transform: uppercase;">
            LAPORAN DATA OBAT / BARANG FARMASI
        </div>

        <!-- Meta info -->
        <table width="100%" border="0" class="meta-table" style="margin-bottom: 8px;">
            <tr>
                <td width="12%" style="font-weight: bold;">Status Filter</td>
                <td width="38%">: {{ $statusLabel }}</td>
                <td width="12%" style="font-weight: bold;">Tanggal Cetak</td>
                <td width="38%">: {{ date('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td width="12%" style="font-weight: bold;">Total Items</td>
                <td width="38%">: {{ count($data) }} Item</td>
                <td width="12%"></td>
                <td width="38%"></td>
            </tr>
        </table>

        <!-- Table Data -->
        <table class="data-table" width="100%">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="7%">Kode</th>
                    <th width="20%">Nama Obat / Barang</th>
                    <th width="5%">Dosis</th>
                    <th width="5%">Satuan</th>
                    <th width="5%">Stok</th>
                    <th width="8%">H. Beli</th>
                    <th width="8%">H. Ralan</th>
                    <th width="9%">Kandungan</th>
                    <th width="7%">Jenis</th>
                    <th width="7%">Kategori</th>
                    <th width="8%">Golongan</th>
                    <th width="8%">Industri</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $row)
                    @php
                        $stok = 0;
                        if ($row->gudangBarang && is_iterable($row->gudangBarang)) {
                            foreach ($row->gudangBarang as $g) {
                                $stok += (float)$g->stok;
                            }
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">{{ $row->kode_brng }}</td>
                        <td>{{ $row->nama_brng }}</td>
                        <td class="text-center">{{ $row->kapasitas ?: '-' }}</td>
                        <td class="text-center">{{ $row->satuan->satuan ?? '-' }}</td>
                        <td class="text-center">{{ $stok }}</td>
                        <td class="text-right">{{ number_format($row->h_beli, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row->ralan, 0, ',', '.') }}</td>
                        <td>{{ $row->letak_barang ?: '-' }}</td>
                        <td>{{ $row->jenis->nama ?? '-' }}</td>
                        <td>{{ $row->kategori->nama ?? '-' }}</td>
                        <td>{{ $row->golongan->nama ?? '-' }}</td>
                        <td>{{ $row->industri->nama_industri ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">Tidak ada data obat/barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

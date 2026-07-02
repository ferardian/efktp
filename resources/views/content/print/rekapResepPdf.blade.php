@extends('content.print.main')

@section('content')
    <style>
        @page {
            margin: 40px 30px 40px 30px !important;
        }
        .kop-table td {
            border: none !important;
        }
        .meta-table td {
            border: none !important;
            padding: 3px 0px;
            font-size: 11px;
        }
        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        .data-table th {
            border: 1px solid #000000;
            background-color: #f2f2f2;
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
    
    <div width="100%" style="font-size: 11px;">
        <!-- Header Kop Surat -->
        <table width="100%" border="0" class="kop-table" style="margin-bottom: 5px;">
            <tr>
                @if($setting->logo)
                <td width="12%" style="text-align: center; vertical-align: middle;">
                    <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo" width="55px">
                </td>
                @endif
                <td width="88%" style="text-align: center;">
                    <h3 style="font-size: 15px; font-weight: bold; margin: 0; text-transform: uppercase;">{{ $setting->nama_instansi }}</h3>
                    <p style="margin: 2px 0; font-size: 10px; color: #333;">{{ $setting->alamat_instansi }}</p>
                    <p style="margin: 0; font-size: 10px; color: #333;">Telp: {{ $setting->kontak }} | Email: {{ $setting->email }}</p>
                </td>
            </tr>
        </table>
        <hr style="border: 0; border-top: 1.5px solid #000; margin-top: 2px; margin-bottom: 12px;">

        <div style="text-align: center; font-size: 13px; font-weight: bold; text-decoration: underline; margin-bottom: 15px; text-transform: uppercase;">
            REKAPITULASI RESEP OBAT
        </div>

        <!-- Meta info -->
        <table width="100%" border="0" class="meta-table" style="margin-bottom: 10px;">
            <tr>
                <td width="15%" style="font-weight: bold;">Periode</td>
                <td width="35%">: {{ $tgl_awal }} s.d {{ $tgl_akhir }}</td>
                <td width="15%" style="font-weight: bold;">Poliklinik</td>
                <td width="35%">: {{ $poliName }}</td>
            </tr>
            <tr>
                <td width="15%" style="font-weight: bold;">Status Validasi</td>
                <td width="35%">: {{ $status }}</td>
                <td width="15%" style="font-weight: bold;">Dokter</td>
                <td width="35%">: {{ $dokterName }}</td>
            </tr>
        </table>

        <!-- Table Data -->
        <table class="data-table" width="100%">
            <thead>
                <tr>
                    <th width="6%">No</th>
                    <th width="16%">Kode Obat</th>
                    <th width="48%" style="text-align: left;">Nama Obat</th>
                    <th width="15%">Satuan</th>
                    <th width="15%" style="text-align: right;">Total Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ $item->kode_brng }}</td>
                        <td>{{ $item->nama_brng }}</td>
                        <td style="text-align: center;">{{ $item->satuan }}</td>
                        <td style="text-align: right;">{{ number_format($item->total_qty, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

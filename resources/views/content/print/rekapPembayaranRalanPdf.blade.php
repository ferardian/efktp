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
            margin-top: 10px;
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
                @if($setting->logo)
                <td width="10%" style="text-align: center; vertical-align: middle;">
                    <img src="data:image/png;base64,{{ base64_encode($setting->logo) }}" alt="Logo" width="45px">
                </td>
                @endif
                <td width="90%" style="text-align: center;">
                    <h3 style="font-size: 13px; font-weight: bold; margin: 0; text-transform: uppercase;">{{ $setting->nama_instansi }}</h3>
                    <p style="margin: 2px 0; font-size: 9px; color: #333;">{{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}</p>
                    <p style="margin: 0; font-size: 9px; color: #333;">Telp: {{ $setting->kontak }} | Email: {{ $setting->email }}</p>
                </td>
            </tr>
        </table>
        <hr style="border: 0; border-top: 1.5px solid #000; margin-top: 2px; margin-bottom: 8px;">

        <div style="text-align: center; font-size: 11px; font-weight: bold; text-decoration: underline; margin-bottom: 8px; text-transform: uppercase;">
            REKAPITULASI PEMBAYARAN PASIEN RAWAT JALAN
        </div>

        <!-- Meta info -->
        <table width="100%" border="0" class="meta-table" style="margin-bottom: 8px;">
            <tr>
                <td width="12%" style="font-weight: bold;">Periode</td>
                <td width="38%">: {{ $tgl_awal }} s.d {{ $tgl_akhir }}</td>
                <td width="12%" style="font-weight: bold;">Poliklinik</td>
                <td width="38%">: {{ $poliName }}</td>
            </tr>
            <tr>
                <td width="12%" style="font-weight: bold;">Dokter</td>
                <td width="38%">: {{ $dokterName }}</td>
                <td width="12%" style="font-weight: bold;">Penjab</td>
                <td width="38%">: {{ $penjabName }}</td>
            </tr>
            <tr>
                <td width="12%" style="font-weight: bold;">Status Bayar</td>
                <td width="38%">: {{ $statusBayar }}</td>
                <td width="12%" style="font-weight: bold;">Total Record</td>
                <td width="38%">: {{ count($data) }} Pasien</td>
            </tr>
        </table>

        <!-- Table Data -->
        <table class="data-table" width="100%">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="7%">Tgl</th>
                    <th width="10%">No. Nota</th>
                    <th width="6%">No. RM</th>
                    <th width="14%">Nama Pasien</th>
                    <th width="10%">Poliklinik</th>
                    <th width="6%">Reg</th>
                    <th width="7%">Obat+BHP</th>
                    <th width="8%">Tindakan</th>
                    <th width="6%">Lab</th>
                    <th width="6%">Rad</th>
                    <th width="5%">Tamb</th>
                    <th width="5%">Pot</th>
                    <th width="9%">Total Biaya</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item['tgl_registrasi'] }}</td>
                        <td class="text-center">{{ $item['no_nota'] }}</td>
                        <td class="text-center">{{ $item['no_rkm_medis'] }}</td>
                        <td>{{ $item['nm_pasien'] }}</td>
                        <td>{{ $item['nm_poli'] }}</td>
                        <td class="text-right">{{ number_format($item['biaya_reg'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['biaya_obat'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['biaya_tindakan'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['biaya_lab'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['biaya_rad'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['biaya_tambahan'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['biaya_potongan'], 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold;">{{ number_format($item['total_biaya'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-right">{{ number_format($totals['registrasi'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['obat'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['tindakan'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['laborat'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['radiologi'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['tambahan'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totals['potongan'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($totals['grand_total'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

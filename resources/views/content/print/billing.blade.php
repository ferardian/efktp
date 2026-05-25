@extends('content.print.main')

@section('content')
    @php
        $fontSize = ($size == '58') ? '9px' : '11px';
        $titleSize = ($size == '58') ? '12px' : '14px';
        $headerSize = ($size == '58') ? '10px' : '12px';
        $margin = ($size == '58') ? '2px' : '5px';
    @endphp

    <style>
        @page {
            margin-top: 5px;
            margin-right: {{ $margin }};
            margin-left: {{ $margin }};
            margin-bottom: 5px;
        }
        
        body {
            font-size: {{ $fontSize }};
            line-height: 1.3;
            color: #000;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .receipt-header img {
            display: block;
            margin: 0 auto 5px auto;
            max-width: 40px;
        }

        .receipt-header .instansi-name {
            font-size: {{ $titleSize }};
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .receipt-header .instansi-address {
            font-size: 8px;
            margin: 2px 0;
            color: #333;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
            height: 0;
        }

        .double-divider {
            border-top: 1px double #000;
            margin: 5px 0;
            height: 0;
        }

        .info-table {
            width: 100%;
            border-spacing: 0;
            font-size: {{ $fontSize }};
            margin-bottom: 5px;
        }

        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .items-list {
            width: 100%;
            border-spacing: 0;
            font-size: {{ $fontSize }};
        }

        .items-list td {
            padding: 2px 0;
            vertical-align: top;
        }

        .category-header {
            font-weight: bold;
            text-transform: uppercase;
            padding-top: 4px !important;
            font-size: {{ ($size == '58') ? '8.5px' : '10px' }};
        }

        .item-row {
            padding-left: 5px;
        }

        .item-details {
            font-size: {{ ($size == '58') ? '8px' : '9.5px' }};
            color: #333;
        }

        .text-right {
            text-align: right;
        }

        .grand-total-row {
            font-size: {{ ($size == '58') ? '11px' : '13px' }};
            font-weight: bold;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 12px;
        }
        
        .qr-section img {
            margin-bottom: 3px;
        }
    </style>

    <div class="receipt-header">
        @if($setting->logo)
            <img src="{{ 'data:image/jpeg;base64,' . base64_encode($setting->logo) }}" alt="Logo">
        @endif
        <div class="instansi-name">{{ $setting->nama_instansi }}</div>
        <div class="instansi-address">
            {{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}<br>
            Telp: {{ $setting->kontak }}
        </div>
    </div>

    <div class="divider"></div>
    <div style="text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 5px;">
        Rincian Billing ({{ $data['type'] }})
    </div>
    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td width="30%">No. Nota</td>
            <td width="5%">:</td>
            <td>{{ $data['no_rawat'] }}</td>
        </tr>
        <tr>
            <td>No. R.M.</td>
            <td>:</td>
            <td>{{ $data['no_rm'] }}</td>
        </tr>
        <tr>
            <td>Pasien</td>
            <td>:</td>
            <td>{{ $data['pasien'] }}</td>
        </tr>
        @if($data['type'] == 'RANAP')
            <tr>
                <td>Kamar</td>
                <td>:</td>
                <td>{{ $data['kamar'] }}</td>
            </tr>
            <tr>
                <td>Tgl. Rawat</td>
                <td>:</td>
                <td>{{ $data['tgl_perawatan'] }}</td>
            </tr>
        @else
            <tr>
                <td>Poliklinik</td>
                <td>:</td>
                <td>{{ $data['poli'] }}</td>
            </tr>
            <tr>
                <td>Tgl. Periksa</td>
                <td>:</td>
                <td>{{ date('d-m-Y', strtotime($data['tgl_perawatan'])) }}</td>
            </tr>
        @endif
    </table>

    <div class="double-divider"></div>

    <table class="items-list">
        @foreach ($data['categories'] as $cat)
            @if (count($cat['items']) > 0)
                <tr>
                    <td colspan="2" class="category-header">{{ $cat['label'] }}</td>
                    <td class="category-header text-right">{{ number_format($cat['total'], 0, ',', '.') }}</td>
                </tr>
                @if ($cat['label'] !== 'Obat & Alkes' || $show_obat)
                    @foreach ($cat['items'] as $item)
                        <tr>
                            <td colspan="2" class="item-row">
                                <div>{{ $item['item'] }}</div>
                                <div class="item-details">{{ $item['qty'] }} x {{ number_format($item['tarif'], 0, ',', '.') }}</div>
                            </td>
                            <td class="text-right" style="vertical-align: bottom;">
                                {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endif
        @endforeach
    </table>

    <div class="double-divider"></div>

    <table class="info-table grand-total-row">
        <tr>
            <td>TOTAL BIAYA</td>
            <td class="text-right">Rp. {{ number_format($data['grand_total'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <div style="text-align: center; font-size: 8px; font-style: italic; margin-top: 5px;">
        * Nilai di atas merupakan estimasi biaya berjalan.<br>
        Terima kasih atas kepercayaan Anda.
    </div>

    <div class="qr-section">
        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('No. Rawat: ' . $data['no_rawat'] . ' | Total: Rp.' . number_format($data['grand_total'], 0, ',', '.'), 'QRCODE') }}" height="50" width="50" />
        <div style="font-size: 7px; color: #555;">{{ date('d-m-Y H:i:s') }}</div>
    </div>
@endsection

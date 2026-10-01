@extends('content.print.main')

@section('content')
    @php
        $isFullPage = in_array(strtolower($size ?? '80'), ['a4', 'a5', 'full']);
        
        $terbilang = function($nilai) use (&$terbilang) {
            $nilai = abs((int)$nilai);
            $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            if ($nilai < 12) {
                return " " . $huruf[$nilai];
            } else if ($nilai < 20) {
                return $terbilang($nilai - 10) . " Belas";
            } else if ($nilai < 100) {
                return $terbilang((int)($nilai / 10)) . " Puluh" . $terbilang($nilai % 10);
            } else if ($nilai < 200) {
                return " Seratus" . $terbilang($nilai - 100);
            } else if ($nilai < 1000) {
                return $terbilang((int)($nilai / 100)) . " Ratus" . $terbilang($nilai % 100);
            } else if ($nilai < 2000) {
                return " Seribu" . $terbilang($nilai - 1000);
            } else if ($nilai < 1000000) {
                return $terbilang((int)($nilai / 1000)) . " Ribu" . $terbilang($nilai % 1000);
            } else if ($nilai < 1000000000) {
                return $terbilang((int)($nilai / 1000000)) . " Juta" . $terbilang($nilai % 1000000);
            } else if ($nilai < 1000000000000) {
                return $terbilang((int)($nilai / 1000000000)) . " Milyar" . $terbilang(fmod($nilai, 1000000000));
            }
            return "";
        };

        $fontSize = ($size == '58') ? '8.5px' : '11px';
        $titleSize = ($size == '58') ? '11px' : '14px';
        $headerSize = ($size == '58') ? '9.5px' : '12px';
        $margin = ($size == '58') ? '2px' : '5px';
        $marginRight = ($size == '58') ? '2px' : '5px';

        $hasObat = false;
        foreach ($data['categories'] as $cat) {
            if ($cat['label'] === 'Obat & Alkes' && count($cat['items']) > 0) {
                $hasObat = true;
                break;
            }
        }
    @endphp

    @if($isFullPage)
        {{-- LAYOUT A4 / A5 (LAPORAN BILLING / INVOICE LENGKAP SEPERTI KHANZA) --}}
        <style>
            @page {
                margin: {{ $size == 'a5' ? '8mm 10mm 8mm 10mm' : '10mm 12mm 10mm 12mm' }};
            }
            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: {{ $size == 'a5' ? '8pt' : '9.5pt' }};
                line-height: 1.3;
                color: #111;
            }
            .header-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 4px;
            }
            .header-table td {
                vertical-align: middle;
            }
            .instansi-title {
                font-size: {{ $size == 'a5' ? '12pt' : '14pt' }};
                font-weight: bold;
                text-transform: uppercase;
                margin: 0;
                color: #000;
            }
            .instansi-sub {
                font-size: {{ $size == 'a5' ? '7.5pt' : '8.5pt' }};
                color: #333;
                margin-top: 1px;
            }
            .badge-carabayar {
                border: 1.5px solid #333;
                padding: 4px 10px;
                font-size: {{ $size == 'a5' ? '8.5pt' : '10pt' }};
                font-weight: bold;
                text-align: center;
                border-radius: 4px;
                background-color: #f9f9f9;
                display: inline-block;
            }
            .doc-divider {
                border-top: 2px solid #000;
                border-bottom: 1px solid #000;
                height: 2px;
                margin: 4px 0 6px 0;
            }
            .doc-title {
                text-align: center;
                font-size: {{ $size == 'a5' ? '10pt' : '11.5pt' }};
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 6px;
            }
            .meta-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 8px;
                font-size: {{ $size == 'a5' ? '7.5pt' : '9pt' }};
            }
            .meta-table td {
                padding: 1.5px 3px;
                vertical-align: top;
            }
            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 8px;
                font-size: {{ $size == 'a5' ? '7.5pt' : '9pt' }};
            }
            .items-table th {
                background-color: #eee;
                border-top: 1px solid #000;
                border-bottom: 1px solid #000;
                padding: 4px 5px;
                font-weight: bold;
                text-align: left;
            }
            .items-table td {
                padding: 2.5px 5px;
                vertical-align: top;
            }
            .items-table .category-header-row td {
                background-color: #f7f9fa;
                font-weight: bold;
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                text-transform: uppercase;
                color: #0b5ed7;
            }
            .items-table .item-subrow td {
                border-bottom: 1px dashed #e9ecef;
            }
            .summary-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 4px;
            }
            .summary-table td {
                padding: 2px 5px;
                vertical-align: top;
            }
            .grand-total-box {
                background-color: #f8f9fa;
                border: 1.5px solid #000;
                font-weight: bold;
                font-size: {{ $size == 'a5' ? '9pt' : '10.5pt' }};
            }
            .terbilang-box {
                background-color: #fdfdfd;
                border-left: 3px solid #0b5ed7;
                padding: 4px 8px;
                font-size: {{ $size == 'a5' ? '7pt' : '8.5pt' }};
                font-style: italic;
                margin-top: 4px;
                margin-bottom: 8px;
            }
            .signatures-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
                font-size: {{ $size == 'a5' ? '7.5pt' : '9pt' }};
            }
            .signatures-table td {
                text-align: center;
                vertical-align: top;
                padding: 0 10px;
            }
            .footer-note {
                margin-top: 10px;
                font-size: {{ $size == 'a5' ? '6.5pt' : '7.5pt' }};
                font-style: italic;
                color: #555;
                border-top: 1px solid #ccc;
                padding-top: 4px;
            }
        </style>

        {{-- KOP SURAT / HEADER INSTANSI --}}
        <table class="header-table">
            <tr>
                <td style="width: 15%; text-align: left;">
                    @if($setting->logo)
                        <img src="{{ 'data:image/jpeg;base64,' . base64_encode($setting->logo) }}" class="header-logo" alt="Logo" style="max-height: 50px; max-width: 50px;">
                    @endif
                </td>
                <td style="width: 65%; text-align: center;">
                    <div class="instansi-title">{!! nl2br(e(str_replace('|', ' ', $setting->nama_instansi))) !!}</div>
                    <div class="instansi-sub">
                        {{ $setting->alamat_instansi }}, {{ $setting->kabupaten }}, {{ $setting->propinsi }}<br>
                        Kontak: {{ $setting->kontak }} | E-mail: {{ $setting->email }}
                    </div>
                </td>
                <td style="width: 20%; text-align: right;">
                    <div class="badge-carabayar">
                        {{ $data['penjab'] ?? 'UMUM' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="doc-divider"></div>

        <div class="doc-title">
            @if(isset($is_estimasi) && $is_estimasi)
                ESTIMASI BIAYA SEMENTARA ({{ $data['type'] == 'RANAP' ? 'RAWAT INAP' : 'RAWAT JALAN' }})
                <div style="font-size: 7.5pt; font-weight: normal; text-transform: none; color: #555; margin-top: 1px;">[ BUKAN BUKTI PEMBAYARAN RESMI / HANYA RINCIAN BIAYA ]</div>
            @else
                NOTA BILLING / INVOICE {{ $data['type'] == 'RANAP' ? 'RAWAT INAP' : 'RAWAT JALAN' }}
            @endif
        </div>

        {{-- INFORMASI PASIEN --}}
        <table class="meta-table">
            <tr>
                <td style="width: 15%; font-weight: bold;">{{ (!empty($data['no_nota']) && (!isset($is_estimasi) || !$is_estimasi)) ? 'No. Nota' : 'No. Rawat' }}</td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;">{{ (!empty($data['no_nota']) && (!isset($is_estimasi) || !$is_estimasi)) ? $data['no_nota'] : $data['no_rawat'] }}</td>
                
                <td style="width: 16%; font-weight: bold;">{{ $data['type'] == 'RANAP' ? 'Kamar / Bangsal' : 'Poliklinik' }}</td>
                <td style="width: 2%;">:</td>
                <td style="width: 32%;">{{ $data['type'] == 'RANAP' ? $data['kamar'] : $data['poli'] }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">No. Rekam Medis</td>
                <td>:</td>
                <td>{{ $data['no_rm'] }}</td>
                
                <td style="font-weight: bold;">Tgl. Perawatan</td>
                <td>:</td>
                <td>{{ $data['tgl_perawatan'] }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Nama Pasien</td>
                <td>:</td>
                <td style="font-weight: bold;">{{ $data['pasien'] }}</td>
                
                <td style="font-weight: bold;">Dokter DPJP</td>
                <td>:</td>
                <td>{{ $data['dokter'] ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Jenis / Cara Bayar</td>
                <td>:</td>
                <td>{{ $data['penjab'] ?? '-' }}</td>
                
                <td style="font-weight: bold;">Status Pasien</td>
                <td>:</td>
                <td>{{ $data['stts_pulang'] ?? ($data['status_bayar'] ?? '-') }}</td>
            </tr>
        </table>

        {{-- TABEL RINCIAN ITEM BILLING --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No.</th>
                    <th style="width: 52%;">Uraian Rincian Layanan / Tindakan</th>
                    <th style="width: 8%; text-align: center;">Qty</th>
                    <th style="width: 17%; text-align: right;">Tarif (Rp)</th>
                    <th style="width: 18%; text-align: right;">Subtotal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php $noUrut = 1; @endphp
                @foreach ($data['categories'] as $cat)
                    @if (count($cat['items']) > 0)
                        <tr class="category-header-row">
                            <td colspan="4" style="padding-left: 8px;">{{ $cat['label'] }}</td>
                            <td style="text-align: right;">{{ number_format($cat['total'], 0, ',', '.') }}</td>
                        </tr>
                        @if ($cat['label'] !== 'Obat & Alkes' || $show_obat)
                            @foreach ($cat['items'] as $item)
                                <tr class="item-subrow">
                                    <td style="text-align: center; color: #777;">{{ $noUrut++ }}</td>
                                    <td style="padding-left: 15px;">{{ $item['item'] }}</td>
                                    <td style="text-align: center;">{{ $item['qty'] }}</td>
                                    <td style="text-align: right;">{{ number_format($item['tarif'], 0, ',', '.') }}</td>
                                    <td style="text-align: right;">{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- RINGKASAN PEMBAYARAN & TERBILANG --}}
        <table class="summary-table">
            <tr>
                <td style="width: 55%; vertical-align: top; padding-right: 15px;">
                    <div class="terbilang-box">
                        <strong>Terbilang:</strong><br>
                        # {{ trim($terbilang($data['net_total'] ?? $data['grand_total'])) }} Rupiah #
                    </div>
                    <div style="font-size: {{ $size == 'a5' ? '7pt' : '8pt' }}; color: #555; line-height: 1.4;">
                        @if(isset($is_estimasi) && $is_estimasi)
                            * Nilai ini merupakan perkiraan estimasi biaya berjalan selama perawatan.<br>
                            * Pembayaran resmi dan pelunasan diproses melalui Kasir.
                        @elseif(($data['status_bayar'] ?? '') === 'Sudah Bayar')
                            * Pembayaran telah diproses lunas di Kasir Rumah Sakit.<br>
                            * Bukti ini sah dan dicetak otomatis melalui Sistem Billing.
                        @endif
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; font-size: {{ $size == 'a5' ? '8pt' : '9pt' }};">
                        <tr>
                            <td style="padding: 2px 4px; font-weight: bold;">TOTAL TAGIHAN</td>
                            <td style="padding: 2px 4px; text-align: right; font-weight: bold;">Rp. {{ number_format($data['grand_total'], 0, ',', '.') }}</td>
                        </tr>
                        @if(!empty($data['deposit']) && $data['deposit'] > 0)
                            <tr>
                                <td style="padding: 2px 4px; color: #555;">Titipan Uang Muka / Deposit</td>
                                <td style="padding: 2px 4px; text-align: right; color: #555;">- Rp. {{ number_format($data['deposit'], 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if(!empty($data['potongan']) && $data['potongan'] > 0)
                            <tr>
                                <td style="padding: 2px 4px; color: #555;">Potongan / Diskon</td>
                                <td style="padding: 2px 4px; text-align: right; color: #555;">- Rp. {{ number_format($data['potongan'], 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="grand-total-box">
                            <td style="padding: 4px;">SISA TAGIHAN</td>
                            <td style="padding: 4px; text-align: right;">Rp. {{ number_format($data['net_total'] ?? max(0, $data['grand_total'] - ($data['deposit'] ?? 0)), 0, ',', '.') }}</td>
                        </tr>
                        @if(!empty($data['saved_payments']) && count($data['saved_payments']) > 0)
                            @foreach($data['saved_payments'] as $sp)
                                <tr>
                                    <td style="padding: 2px 4px; font-size: 8pt; color: #333;">Bayar ({{ $sp->nama_bayar }})</td>
                                    <td style="padding: 2px 4px; text-align: right; font-size: 8pt;">Rp. {{ number_format($sp->besar_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        {{-- TANDA TANGAN (REFERENSI KHANZA LaporanBilling2.php) --}}
        <table class="signatures-table">
            <tr>
                <td style="width: 45%;">
                    Mengetahui,<br>
                    a/n Direktur / Kabid Keuangan<br><br><br><br><br>
                    ( .................................................. )
                </td>
                <td style="width: 10%;">&nbsp;</td>
                <td style="width: 45%;">
                    {{ $setting->kabupaten ?? 'Tempat' }}, {{ date('d-m-Y') }}<br>
                    Kasir / Petugas,<br>
                    <div style="margin: 4px 0;">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('No. Rawat: ' . $data['no_rawat'] . ' | Petugas: ' . ($petugas ?? 'Kasir') . ' | Total: Rp.' . number_format($data['grand_total'], 0, ',', '.'), 'QRCODE') }}" height="50" width="50" />
                    </div>
                    ( {{ $petugas ?? 'Petugas Kasir' }} )
                </td>
            </tr>
        </table>

        <div class="footer-note">
            NB : Mohon maaf apabila ada tagihan yang belum tertagihkan dalam perincian ini akan ditagihkan kemudian, dan apabila berlebih akan dikembalikan.
        </div>

    @else
        {{-- LAYOUT THERMAL (58mm / 80mm) --}}
        <style>
            @page {
                margin-top: 5px;
                margin-right: {{ $marginRight }};
                margin-left: {{ $margin }};
                margin-bottom: 5px;
            }
            
            body {
                font-size: {{ $fontSize }};
                line-height: 1.25;
                color: #000;
            }

            .receipt-header {
                text-align: center;
                margin-bottom: 8px;
            }

            .receipt-header img {
                display: block;
                margin: 0 auto 4px auto;
                max-width: 36px;
            }

            .receipt-header .instansi-name {
                font-size: {{ $titleSize }};
                font-weight: bold;
                margin: 0;
                text-transform: uppercase;
            }

            .receipt-header .instansi-address {
                font-size: {{ $headerSize }};
                color: #333;
                margin-top: 2px;
            }

            .divider {
                border-top: 1px dashed #000;
                margin: 4px 0;
                height: 0;
            }

            .double-divider {
                border-top: 1px double #000;
                margin: 4px 0;
                height: 0;
            }

            .info-table {
                width: 100%;
                border-spacing: 0;
                font-size: {{ $fontSize }};
                margin-bottom: 4px;
            }

            .info-table td {
                padding: 1px 0;
                vertical-align: top;
                word-wrap: break-word;
            }

            .items-list {
                width: 100%;
                border-spacing: 0;
                font-size: {{ $fontSize }};
            }

            .items-list td {
                padding: 2px 0;
                vertical-align: top;
                word-wrap: break-word;
            }

            .category-header {
                font-weight: bold;
                text-transform: uppercase;
                padding-top: 3px !important;
                font-size: {{ ($size == '58') ? '8px' : '10px' }};
            }

            .item-row {
                padding-left: 2px;
            }

            .item-details {
                font-size: {{ ($size == '58') ? '7.5px' : '9.5px' }};
                color: #333;
            }

            .text-right {
                text-align: right;
                white-space: nowrap;
                padding-left: 5px;
            }

            .grand-total-row {
                font-size: {{ ($size == '58') ? '10px' : '13px' }};
                font-weight: bold;
            }
            
            .qr-section {
                text-align: center;
                margin-top: 10px;
            }
            
            .qr-section img {
                margin-bottom: 2px;
            }
        </style>

        <div class="receipt-header">
            @if($setting->logo)
                <img src="{{ 'data:image/jpeg;base64,' . base64_encode($setting->logo) }}" alt="Logo">
            @endif
            <div class="instansi-name">{!! nl2br(e(str_replace('|', "
", $setting->nama_instansi))) !!}</div>
            <div class="instansi-address">
                {!! nl2br(e(str_replace('|', "
", "{$setting->alamat_instansi}, {$setting->kabupaten}"))) !!}<br>
                Telp: {{ $setting->kontak }}
            </div>
        </div>

        <div class="divider"></div>
        <div style="text-align: center; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">
            @if(isset($is_estimasi) && $is_estimasi)
                ESTIMASI BIAYA SEMENTARA ({{ $data['type'] }})
                <div style="font-size: 8px; font-weight: normal; text-transform: none; color: #555;">[ BUKAN BUKTI PEMBAYARAN RESMI ]</div>
            @else
                NOTA BILLING {{ $data['type'] == 'RANAP' ? 'RAWAT INAP' : 'RAWAT JALAN' }}
            @endif
        </div>
        <div class="divider"></div>

        <table class="info-table">
            <tr>
                <td style="width: 28%; white-space: nowrap;">{{ (!empty($data['no_nota']) && (!isset($is_estimasi) || !$is_estimasi)) ? 'No. Nota' : 'No. Rawat' }}</td>
                <td style="width: 3%;">:</td>
                <td>{{ (!empty($data['no_nota']) && (!isset($is_estimasi) || !$is_estimasi)) ? $data['no_nota'] : $data['no_rawat'] }}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap;">No. R.M.</td>
                <td>:</td>
                <td>{{ $data['no_rm'] }}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap;">Pasien</td>
                <td>:</td>
                <td>{{ $data['pasien'] }}</td>
            </tr>
            @if($data['type'] == 'RANAP')
                <tr>
                    <td style="white-space: nowrap;">Kamar</td>
                    <td>:</td>
                    <td>{{ $data['kamar'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">Tgl. Rawat</td>
                    <td>:</td>
                    <td>{{ $data['tgl_perawatan'] }}</td>
                </tr>
            @else
                <tr>
                    <td style="white-space: nowrap;">Poliklinik</td>
                    <td>:</td>
                    <td>{{ $data['poli'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">Tgl. Periksa</td>
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
                        <td class="category-header">{{ $cat['label'] }}</td>
                        <td class="category-header text-right">{{ number_format($cat['total'], 0, ',', '.') }}</td>
                    </tr>
                    @if ($cat['label'] !== 'Obat & Alkes' || $show_obat)
                        @foreach ($cat['items'] as $item)
                            <tr>
                                <td class="item-row">
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
                <td style="white-space: nowrap;">TOTAL TAGIHAN</td>
                <td class="text-right">Rp. {{ number_format($data['grand_total'], 0, ',', '.') }}</td>
            </tr>
            @if(!empty($data['deposit']) && $data['deposit'] > 0)
                <tr style="font-size: {{ ($size == '58') ? '8px' : '10px' }}; font-weight: normal; color: #333;">
                    <td style="white-space: nowrap;">Titipan Uang Muka / Deposit</td>
                    <td class="text-right">- Rp. {{ number_format($data['deposit'], 0, ',', '.') }}</td>
                </tr>
                <tr style="font-size: {{ ($size == '58') ? '9px' : '11px' }}; font-weight: bold;">
                    <td style="white-space: nowrap;">SISA TAGIHAN</td>
                    <td class="text-right">Rp. {{ number_format($data['net_total'] ?? max(0, $data['grand_total'] - ($data['deposit'] ?? 0)), 0, ',', '.') }}</td>
                </tr>
            @endif
            @if(!empty($data['saved_payments']) && count($data['saved_payments']) > 0)
                @foreach($data['saved_payments'] as $sp)
                    <tr style="font-size: {{ ($size == '58') ? '8px' : '10px' }}; font-weight: normal; color: #444;">
                        <td style="white-space: nowrap;">Bayar ({{ $sp->nama_bayar }})</td>
                        <td class="text-right">Rp. {{ number_format($sp->besar_bayar, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </table>

        <div class="divider"></div>
        <div style="text-align: left; font-size: 8px; font-style: italic; margin-top: 5px;">
            @if(isset($is_estimasi) && $is_estimasi)
                * Nilai di atas merupakan estimasi sementara selama masa perawatan.<br>
                * Bukan bukti pelunasan atau kwitansi pembayaran resmi.<br>
            @elseif(($data['status_bayar'] ?? '') === 'Sudah Bayar')
                * Pembayaran Lunas / Selesai diproses di Kasir.<br>
            @endif
            @if(config('app.billing_note'))
                * {{ config('app.billing_note') }}<br>
            @endif
            <div style="text-align: center; font-style: normal; margin-top: 5px;">
                Terima kasih atas kepercayaan Anda.
            </div>
        </div>

        <div class="qr-section">
            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('No. Rawat: ' . $data['no_rawat'] . ' | Total: Rp.' . number_format($data['grand_total'], 0, ',', '.'), 'QRCODE') }}" height="50" width="50" />
            <div style="font-size: 7px; color: #555;">{{ date('d-m-Y H:i:s') }}</div>
        </div>
    @endif
@endsection

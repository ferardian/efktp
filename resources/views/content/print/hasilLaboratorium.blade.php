@extends('content.print.main')
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');

    $reg  = $permintaan->registrasi;
    $pas  = $permintaan->pasien;
    $poli = $permintaan->poliklinik;
    $dok  = $permintaan->perujuk;
    $pj   = $permintaan->penjab;

    $umurStr = '-';
    if ($reg) {
        $umurStr = ($reg->umurdaftar ?? '?') . ' ' . ($reg->sttsumur ?? '');
    }

    $tglCetak = Carbon::now()->translatedFormat('d F Y H:i');
@endphp

@section('content')
<style>
    @page {
        margin: 15mm 15mm 20mm 15mm;
        size: A4 portrait;
    }
    * { box-sizing: border-box; }
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        color: #1a1a2e;
        margin: 0;
        padding: 0;
    }

    /* ======== KOP SURAT ======== */
    .kop-wrapper {
        width: 100%;
        border-bottom: 3px solid #1565c0;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }
    .kop-inner {
        display: table;
        width: 100%;
    }
    .kop-logo {
        display: table-cell;
        width: 65px;
        vertical-align: middle;
        text-align: center;
    }
    .kop-logo img {
        width: 55px;
        height: auto;
    }
    .kop-text {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        padding: 0 10px;
    }
    .kop-nama {
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        color: #1565c0;
        letter-spacing: 0.5px;
        margin: 0 0 2px 0;
    }
    .kop-alamat {
        font-size: 9px;
        color: #555;
        margin: 1px 0;
    }
    .kop-kontak {
        font-size: 8.5px;
        color: #777;
    }

    /* ======== JUDUL DOKUMEN ======== */
    .doc-title-block {
        text-align: center;
        margin: 8px 0 10px 0;
        padding: 6px 0;
        background: #1565c0;
        border-radius: 3px;
    }
    .doc-title-block h2 {
        font-size: 13px;
        font-weight: bold;
        color: #fff;
        margin: 0;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .doc-subtitle {
        font-size: 9px;
        color: #cfe2ff;
        margin: 2px 0 0 0;
    }

    /* ======== INFO PASIEN ======== */
    .info-section {
        margin-bottom: 10px;
    }
    .section-header {
        background: #e3f2fd;
        border-left: 4px solid #1565c0;
        padding: 3px 8px;
        font-size: 9.5px;
        font-weight: bold;
        color: #1565c0;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 5px;
    }
    .info-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
    }
    .info-table td {
        padding: 2px 4px;
        vertical-align: top;
    }
    .info-table td.label {
        width: 130px;
        color: #555;
        font-weight: normal;
    }
    .info-table td.sep {
        width: 8px;
        color: #555;
    }
    .info-table td.value {
        font-weight: bold;
        color: #1a1a2e;
    }

    /* ======== TABEL HASIL ======== */
    .result-section {
        margin-top: 8px;
        margin-bottom: 10px;
    }
    .paket-title {
        background: #1565c0;
        color: #fff;
        font-size: 9px;
        font-weight: bold;
        padding: 4px 8px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 0;
        border-radius: 2px 2px 0 0;
    }
    .result-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
        border: 1px solid #c8d8f0;
        margin-bottom: 10px;
    }
    .result-table thead tr {
        background: #dbeafe;
    }
    .result-table thead th {
        padding: 4px 6px;
        text-align: left;
        font-size: 8.5px;
        font-weight: bold;
        color: #1a365d;
        border: 1px solid #93c5fd;
        text-transform: uppercase;
    }
    .result-table thead th.center {
        text-align: center;
    }
    .result-table tbody tr {
        border-bottom: 1px solid #e0ecff;
    }
    .result-table tbody tr:nth-child(even) {
        background: #f7faff;
    }
    .result-table tbody td {
        padding: 4px 6px;
        border: 1px solid #d4e2f7;
        vertical-align: middle;
    }
    .result-table tbody td.center {
        text-align: center;
    }
    .ket-normal {
        color: #1a7f37;
        font-weight: bold;
    }
    .ket-low {
        color: #d97706;
        font-weight: bold;
    }
    .ket-high {
        color: #dc2626;
        font-weight: bold;
    }
    .nilai-bold {
        font-weight: bold;
    }

    /* ======== FOOTER DOKTER ======== */
    .sign-section {
        margin-top: 16px;
        width: 100%;
    }
    .sign-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sign-table td {
        padding: 0;
        vertical-align: top;
        width: 50%;
    }
    .sign-box {
        text-align: center;
        padding: 8px 0;
    }
    .sign-label {
        font-size: 9px;
        color: #555;
        margin-bottom: 4px;
    }
    .sign-place {
        font-size: 9px;
        font-weight: bold;
        margin-bottom: 40px;
    }
    .sign-name {
        font-size: 9.5px;
        font-weight: bold;
        border-top: 1px solid #333;
        padding-top: 3px;
        display: inline-block;
        min-width: 140px;
    }
    .sign-sub {
        font-size: 8px;
        color: #777;
        margin-top: 2px;
    }

    /* ======== CATATAN / NOTE ======== */
    .note-box {
        border: 1px dashed #90bff9;
        background: #f0f7ff;
        border-radius: 3px;
        padding: 5px 8px;
        margin-top: 8px;
        font-size: 8.5px;
        color: #555;
    }
    .note-box strong {
        color: #1565c0;
    }

    /* ======== PAGE FOOTER ======== */
    footer {
        position: fixed;
        bottom: -15mm;
        left: 0;
        right: 0;
        height: 12mm;
        border-top: 1px solid #c8d8f0;
        text-align: center;
        font-size: 8px;
        color: #999;
        padding-top: 3px;
    }
    .page-number:before {
        content: "Halaman " counter(page) " dari " counter(pages);
    }

    .divider {
        border: none;
        border-top: 1px solid #e0ecff;
        margin: 6px 0;
    }
    .badge-anomaly {
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 3px;
        padding: 1px 5px;
        font-size: 8px;
        font-weight: bold;
    }
    .no-result {
        text-align: center;
        color: #aaa;
        font-style: italic;
        padding: 10px;
    }
</style>

<footer>
    <span>Dicetak: {{ $tglCetak }} &nbsp;|&nbsp; No. Order: <strong>{{ $permintaan->noorder }}</strong> &nbsp;|&nbsp; No. Rawat: <strong>{{ $permintaan->no_rawat }}</strong> &nbsp;|&nbsp;</span>
    <span class="page-number"></span>
</footer>

<!-- ======== KOP SURAT ======== -->
<div class="kop-wrapper">
    <div class="kop-inner">
        @if(!empty($setting->logo))
        <div class="kop-logo">
            <img src="data:image/png;base64,{{ is_string($setting->logo) ? base64_encode($setting->logo) : '' }}" alt="Logo">
        </div>
        @endif
        <div class="kop-text">
            <p class="kop-nama">{{ $setting->nama_instansi ?? config('app.name') }}</p>
            <p class="kop-alamat">{{ $setting->alamat_instansi ?? '' }}{{ isset($setting->kabupaten) && $setting->kabupaten ? ', ' . $setting->kabupaten : '' }}</p>
            <p class="kop-kontak">
                @if(!empty($setting->kontak)) Telp: {{ $setting->kontak }} @endif
                @if(!empty($setting->email)) &nbsp;|&nbsp; Email: {{ $setting->email }} @endif
            </p>
        </div>
    </div>
</div>

<!-- ======== JUDUL ======== -->
<div class="doc-title-block">
    <h2>Hasil Pemeriksaan Laboratorium</h2>
    <p class="doc-subtitle">Dokumen Resmi – Dicetak: {{ $tglCetak }}</p>
</div>

<!-- ======== INFO PASIEN ======== -->
<div class="info-section">
    <div class="section-header">&#128100; Informasi Pasien</div>
    <table class="info-table">
        <tr>
            <td class="label">No. Order</td>
            <td class="sep">:</td>
            <td class="value">{{ $permintaan->noorder }}</td>
            <td class="label">Nama Pasien</td>
            <td class="sep">:</td>
            <td class="value">{{ $pas?->nm_pasien ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Rawat</td>
            <td class="sep">:</td>
            <td class="value">{{ $permintaan->no_rawat }}</td>
            <td class="label">Jenis Kelamin</td>
            <td class="sep">:</td>
            <td class="value">{{ $pas?->jk === 'L' ? 'Laki-laki' : ($pas?->jk === 'P' ? 'Perempuan' : '-') }}</td>
        </tr>
        <tr>
            <td class="label">No. RM</td>
            <td class="sep">:</td>
            <td class="value">{{ $pas?->no_rkm_medis ?? '-' }}</td>
            <td class="label">Umur</td>
            <td class="sep">:</td>
            <td class="value">{{ $umurStr }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Permintaan</td>
            <td class="sep">:</td>
            <td class="value">{{ Carbon::parse($permintaan->tgl_permintaan)->translatedFormat('d F Y') }} &nbsp; {{ $permintaan->jam_permintaan }}</td>
            <td class="label">Poliklinik Asal</td>
            <td class="sep">:</td>
            <td class="value">{{ $poli?->nm_poli ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tgl Sampel Diambil</td>
            <td class="sep">:</td>
            <td class="value">
                @if($permintaan->tgl_sampel && $permintaan->tgl_sampel !== '0000-00-00')
                    {{ Carbon::parse($permintaan->tgl_sampel)->translatedFormat('d F Y') }} {{ $permintaan->jam_sampel }}
                @else
                    <span style="color:#aaa;">-</span>
                @endif
            </td>
            <td class="label">Dokter Perujuk</td>
            <td class="sep">:</td>
            <td class="value">{{ $dok?->nm_dokter ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tgl Hasil Keluar</td>
            <td class="sep">:</td>
            <td class="value">
                @if($permintaan->tgl_hasil && $permintaan->tgl_hasil !== '0000-00-00')
                    {{ Carbon::parse($permintaan->tgl_hasil)->translatedFormat('d F Y') }} {{ $permintaan->jam_hasil }}
                @else
                    <span style="color:#aaa;">-</span>
                @endif
            </td>
            <td class="label">Pembiayaan</td>
            <td class="sep">:</td>
            <td class="value">{{ $pj?->png_jawab ?? '-' }}</td>
        </tr>
        @if($periksa)
        <tr>
            <td class="label">Dikerjakan Oleh</td>
            <td class="sep">:</td>
            <td class="value" colspan="4">{{ $periksa->nm_petugas ?? '-' }}</td>
        </tr>
        @endif
    </table>
</div>

<!-- ======== TABEL HASIL ======== -->
<div class="result-section">
    <div class="section-header">&#128300; Hasil Pemeriksaan</div>

    @if(empty($grouped))
        <p class="no-result">Belum ada data hasil pemeriksaan untuk order ini.</p>
    @else
        @foreach($grouped as $paket => $items)
        <div class="paket-title">{{ $paket }}</div>
        <table class="result-table">
            <thead>
                <tr>
                    <th style="width:5%;" class="center">No.</th>
                    <th style="width:30%;">Nama Pemeriksaan</th>
                    <th style="width:15%;" class="center">Hasil</th>
                    <th style="width:8%;" class="center">Satuan</th>
                    <th style="width:27%;">Nilai Rujukan</th>
                    <th style="width:15%;" class="center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $no => $item)
                <tr>
                    <td class="center">{{ $no + 1 }}</td>
                    <td>{{ $item->item_nama }}</td>
                    <td class="center nilai-bold {{ $item->keterangan !== '-' ? ($item->keterangan === 'H' ? 'ket-high' : 'ket-low') : '' }}">
                        {{ $item->nilai !== '' && $item->nilai !== null ? $item->nilai : '-' }}
                    </td>
                    <td class="center">{{ $item->satuan ?? '' }}</td>
                    <td>{{ $item->nilai_rujukan ?? '-' }}</td>
                    <td class="center">
                        @if($item->keterangan === '-' || empty($item->keterangan))
                            <span class="ket-normal">&#10003; Normal</span>
                        @elseif($item->keterangan === 'H')
                            <span class="ket-high">&#9650; Tinggi</span>
                        @elseif($item->keterangan === 'L')
                            <span class="ket-low">&#9660; Rendah</span>
                        @else
                            {{ $item->keterangan }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    @endif
</div>

<!-- ======== CATATAN ======== -->
<div class="note-box">
    <strong>&#9888; Catatan:</strong>
    Hasil pemeriksaan ini bersifat rahasia medis dan hanya untuk keperluan pelayanan kesehatan pasien yang bersangkutan.
    Nilai rujukan dapat bervariasi tergantung pada metode dan peralatan yang digunakan.
    Simbol <strong style="color:#dc2626;">▲ Tinggi (H)</strong> dan <strong style="color:#d97706;">▼ Rendah (L)</strong> menunjukkan nilai di luar kisaran normal.
</div>

<!-- ======== TANDA TANGAN ======== -->
<div class="sign-section">
    <table class="sign-table">
        <tr>
            <td>
                <div class="sign-box">
                    <p class="sign-label">Mengetahui,</p>
                    <p class="sign-place">{{ Carbon::now()->translatedFormat('d F Y') }}</p>
                    <span class="sign-name">{{ $periksa?->nm_dokter ?? $dok?->nm_dokter ?? '___________________' }}</span>
                    <p class="sign-sub">
                        Dokter Penanggung Jawab
                        @if($periksa?->no_ijn_praktek)
                        <br>SIP: {{ $periksa->no_ijn_praktek }}
                        @endif
                    </p>
                </div>
            </td>
            <td>
                <div class="sign-box">
                    <p class="sign-label">Dikerjakan oleh,</p>
                    <p class="sign-place">{{ Carbon::now()->translatedFormat('d F Y') }}</p>
                    <span class="sign-name">{{ $periksa?->nm_petugas ?? '___________________' }}</span>
                    <p class="sign-sub">Analis Laboratorium</p>
                </div>
            </td>
        </tr>
    </table>
</div>

@endsection

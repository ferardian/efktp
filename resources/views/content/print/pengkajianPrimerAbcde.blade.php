@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');

    $airwayTindakan = is_array($penilaian->airway_tindakan) ? $penilaian->airway_tindakan : json_decode($penilaian->airway_tindakan, true);
    $breathingTindakan = is_array($penilaian->breathing_tindakan) ? $penilaian->breathing_tindakan : json_decode($penilaian->breathing_tindakan, true);
    $circulationTindakan = is_array($penilaian->circulation_tindakan) ? $penilaian->circulation_tindakan : json_decode($penilaian->circulation_tindakan, true);
    $disabilityTindakan = is_array($penilaian->disability_tindakan) ? $penilaian->disability_tindakan : json_decode($penilaian->disability_tindakan, true);
    $exposureTindakan = is_array($penilaian->exposure_tindakan) ? $penilaian->exposure_tindakan : json_decode($penilaian->exposure_tindakan, true);

    $reg = $penilaian->regPeriksa;
    $pasien = $reg->pasien ?? null;
@endphp
@section('content')
    <style>
        @page {
            margin: 110px 25px 40px 25px;
        }
        header {
            position: fixed;
            top: -95px;
            left: 0px;
            right: 0px;
            height: 85px;
        }
        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 15px;
            text-align: center;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 2px;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9.5px;
            color: #222;
            line-height: 1.25;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .table-data th, .table-data td {
            border: 1px solid #888;
            padding: 4px 6px;
        }
        .table-data th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section-header {
            background-color: #2b3a4a;
            color: #fff;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 10px;
            margin-top: 6px;
            margin-bottom: 4px;
            border-radius: 2px;
        }
        .section-a { background-color: #b71c1c; }
        .section-b { background-color: #0277bd; }
        .section-c { background-color: #c2185b; }
        .section-d { background-color: #ef6c00; }
        .section-e { background-color: #2e7d32; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 8.5px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-danger { background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .badge-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .badge-warning { background-color: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
        .badge-info { background-color: #e1f5fe; color: #0277bd; border: 1px solid #81d4fa; }
    </style>

    <header>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 12%; text-align: center; vertical-align: middle;">
                    @if (!empty($setting->logo))
                        <img src="data:image/jpeg;base64,{{ base64_encode($setting->logo) }}" width="50px">
                    @endif
                </td>
                <td style="width: 88%; vertical-align: middle; text-align: center;">
                    <div style="font-size: 13px; font-weight: bold; text-transform: uppercase;">{{ $setting->nama_instansi ?? 'KLINIK / RUMAH SAKIT' }}</div>
                    <div style="font-size: 9px;">{{ $setting->alamat_instansi ?? '' }} - {{ $setting->kabupaten ?? '' }}</div>
                    <div style="font-size: 9px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</div>
                    <div style="font-size: 11px; font-weight: bold; margin-top: 4px; border-bottom: 2px solid #222; padding-bottom: 2px; text-transform: uppercase;">
                        PENGKAJIAN PRIMER A-B-C-D-E (GAWAT DARURAT)
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left; width: 50%;">Dicetak pada: {{ date('d-m-Y H:i:s') }}</td>
                <td style="text-align: right; width: 50%;" class="page-number"></td>
            </tr>
        </table>
    </footer>

    {{-- IDENTITAS PASIEN --}}
    <table class="table-data" style="margin-top: 5px;">
        <tr>
            <td style="width: 15%; font-weight: bold; background-color: #f7f7f7;">No. Rawat</td>
            <td style="width: 35%;">{{ $penilaian->no_rawat }}</td>
            <td style="width: 15%; font-weight: bold; background-color: #f7f7f7;">No. Rekam Medis</td>
            <td style="width: 35%;">{{ $pasien->no_rkm_medis ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f7f7f7;">Nama Pasien</td>
            <td><strong>{{ $pasien->nm_pasien ?? '-' }}</strong> ({{ $pasien->jk ?? '-' }})</td>
            <td style="font-weight: bold; background-color: #f7f7f7;">Tgl. Lahir / Umur</td>
            <td>{{ !empty($pasien->tgl_lahir) ? Carbon\Carbon::parse($pasien->tgl_lahir)->translatedFormat('d F Y') : '-' }} ({{ $reg->umurdaftar ?? '-' }} {{ $reg->sttsumur ?? '' }})</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f7f7f7;">Waktu Pengkajian</td>
            <td>{{ Carbon\Carbon::parse($penilaian->tgl_pengkajian)->translatedFormat('d F Y H:i') }} WIB</td>
            <td style="font-weight: bold; background-color: #f7f7f7;">Petugas Pengkaji</td>
            <td>{{ $penilaian->pegawai->nama ?? ($penilaian->petugas->nama ?? $penilaian->nip) }} (NIP: {{ $penilaian->nip }})</td>
        </tr>
    </table>

    {{-- A - AIRWAY --}}
    <div class="section-header section-a">A. AIRWAY (JALAN NAPAS & KONTROL SERVIKAL)</div>
    <table class="table-data">
        <tr>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Kondisi Jalan Napas</td>
            <td style="width: 25%;">
                <span class="badge {{ $penilaian->airway_kondisi == 'Bebas' ? 'badge-success' : 'badge-danger' }}">{{ $penilaian->airway_kondisi }}</span>
            </td>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Kemampuan Bicara</td>
            <td style="width: 25%;">{{ $penilaian->airway_bicara }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Suara Napas Tambahan</td>
            <td>{{ $penilaian->airway_suara }}</td>
            <td style="font-weight: bold; background-color: #fafafa;">Kecurigaan Cedera Leher</td>
            <td>
                <span class="badge {{ $penilaian->airway_cervical == 'Tidak Ada Cedera' ? 'badge-info' : 'badge-danger' }}">{{ $penilaian->airway_cervical }}</span>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Tindakan Jalan Napas</td>
            <td colspan="3">
                @if (!empty($airwayTindakan) && is_array($airwayTindakan))
                    {{ implode(', ', $airwayTindakan) }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- B - BREATHING --}}
    <div class="section-header section-b">B. BREATHING (PERNAPASAN & OKSIGENASI)</div>
    <table class="table-data">
        <tr>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Gerakan Dinding Dada</td>
            <td style="width: 25%;">{{ $penilaian->breathing_gerakan }}</td>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Suara Napas</td>
            <td style="width: 25%;">{{ $penilaian->breathing_suara }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Embusan Napas</td>
            <td>{{ $penilaian->breathing_embusan }}</td>
            <td style="font-weight: bold; background-color: #fafafa;">Frekuensi Napas (RR) / SpO₂</td>
            <td><strong>{{ $penilaian->breathing_rr }} x/menit</strong> | SpO₂: <strong>{{ $penilaian->breathing_spo2 }}%</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Tindakan Oksigenasi</td>
            <td colspan="3">
                @if (!empty($breathingTindakan) && is_array($breathingTindakan))
                    {{ implode(', ', $breathingTindakan) }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- C - CIRCULATION --}}
    <div class="section-header section-c">C. CIRCULATION (SIRKULASI & KONTROL PERDARAHAN)</div>
    <table class="table-data">
        <tr>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Denyut Nadi / Heart Rate</td>
            <td style="width: 25%;">{{ $penilaian->circulation_nadi }} ({{ $penilaian->circulation_hr }} x/menit)</td>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Tekanan Darah (TD)</td>
            <td style="width: 25%;"><strong>{{ $penilaian->circulation_td }} mmHg</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">CRT (Capillary Refill)</td>
            <td>{{ $penilaian->circulation_crt }}</td>
            <td style="font-weight: bold; background-color: #fafafa;">Akral & Warna Kulit</td>
            <td>{{ $penilaian->circulation_akral }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Perdarahan Luar</td>
            <td>{{ $penilaian->circulation_perdarahan }}</td>
            <td style="font-weight: bold; background-color: #fafafa;">Lokasi Perdarahan</td>
            <td>{{ $penilaian->circulation_lokasi_perdarahan ?: '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Tindakan Sirkulasi</td>
            <td colspan="3">
                @if (!empty($circulationTindakan) && is_array($circulationTindakan))
                    {{ implode(', ', $circulationTindakan) }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- D - DISABILITY --}}
    <div class="section-header section-d">D. DISABILITY (STATUS NEUROLOGIS / KESADARAN)</div>
    <table class="table-data">
        <tr>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Tingkat Respons (AVPU)</td>
            <td style="width: 25%;">
                <span class="badge {{ str_contains($penilaian->disability_avpu, 'Alert') ? 'badge-success' : 'badge-warning' }}">{{ $penilaian->disability_avpu }}</span>
            </td>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">GCS Score (E, V, M)</td>
            <td style="width: 25%;">
                E: {{ $penilaian->disability_gcs_e }} | V: {{ $penilaian->disability_gcs_v }} | M: {{ $penilaian->disability_gcs_m }} (<strong>Total: {{ $penilaian->disability_gcs_total }}</strong>)
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Pupil & Diameter</td>
            <td>{{ $penilaian->disability_pupil }} ({{ $penilaian->disability_pupil_diameter }})</td>
            <td style="font-weight: bold; background-color: #fafafa;">Refleks Cahaya / GDS</td>
            <td>Refleks: <strong>{{ $penilaian->disability_refleks_cahaya }}</strong> | GDS: {{ $penilaian->disability_gds }} mg/dL</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Tindakan Neurologis</td>
            <td colspan="3">
                @if (!empty($disabilityTindakan) && is_array($disabilityTindakan))
                    {{ implode(', ', $disabilityTindakan) }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- E - EXPOSURE --}}
    <div class="section-header section-e">E. EXPOSURE (PAPARAN & LINGKUNGAN / CEGAH HIPOTERMIA)</div>
    <table class="table-data">
        <tr>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Pemeriksaan Cedera Tubuh</td>
            <td style="width: 25%;">{{ $penilaian->exposure_cedera }}</td>
            <td style="width: 25%; font-weight: bold; background-color: #fafafa;">Suhu Tubuh / Hipotermia</td>
            <td style="width: 25%;"><strong>{{ $penilaian->exposure_suhu }} °C</strong> ({{ $penilaian->exposure_hipotermia }})</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Deskripsi Cedera Tersembunyi</td>
            <td colspan="3">{{ $penilaian->exposure_deskripsi_cedera ?: 'Tidak ditemukan cedera tersembunyi.' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #fafafa;">Tindakan Exposure</td>
            <td colspan="3">
                @if (!empty($exposureTindakan) && is_array($exposureTindakan))
                    {{ implode(', ', $exposureTindakan) }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- KESIMPULAN & TINDAK LANJUT --}}
    <table class="table-data" style="margin-top: 6px;">
        <tr style="background-color: #f0f0f0;">
            <th colspan="2" style="text-align: left; font-size: 10px;">KESIMPULAN PENGKAJIAN PRIMER & TINDAK LANJUT</th>
        </tr>
        <tr>
            <td style="width: 30%; font-weight: bold;">Status Pasien Pasca Pengkajian</td>
            <td style="width: 70%;">
                <span class="badge {{ $penilaian->status_pasien == 'Stabil' ? 'badge-success' : 'badge-danger' }}" style="font-size: 10px;">
                    {{ $penilaian->status_pasien }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Rencana Tindak Lanjut</td>
            <td><strong>{{ $penilaian->rencana_tindak_lanjut }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Catatan Khusus / Tambahan</td>
            <td>{{ $penilaian->catatan_tambahan ?: '-' }}</td>
        </tr>
    </table>

    {{-- TANDA TANGAN --}}
    <table style="width: 100%; margin-top: 15px; border-collapse: collapse;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <div>Petugas Pengkaji UGD,</div>
                <div style="height: 50px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ $penilaian->pegawai->nama ?? ($penilaian->petugas->nama ?? $penilaian->nip) }}</div>
                <div style="font-size: 8.5px; color: #555;">NIP: {{ $penilaian->nip }}</div>
            </td>
        </tr>
    </table>
@endsection

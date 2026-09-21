@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.3;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #333;
            padding: 4px 6px;
            vertical-align: top;
        }
        .table-noborder th, .table-noborder td {
            border: none;
            padding: 2px 4px;
        }
        .section-title {
            background-color: #eef5f5;
            font-weight: bold;
            font-size: 11px;
            padding: 4px 6px;
            border: 1px solid #333;
            margin-top: 6px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .sub-label {
            font-weight: bold;
            color: #333;
        }
    </style>

    <div style="width: 100%; padding: 10px;">
        <!-- Header Instansi -->
        <table width="100%" class="table-noborder">
            <tr>
                <td style="width: 70%;">
                    <h3 style="margin: 0; padding: 0;">{{ $setting->nama_instansi }}</h3>
                    <div style="font-size: 10px; color: #555;">{{ $setting->alamat_instansi ?? '' }} - {{ $setting->kontak ?? '' }}</div>
                </td>
                <td style="width: 30%; text-align: right; font-size: 10px;">
                    <b>RM 03/RALAN/MEDIS</b><br>
                    <span>Tgl Cetak: {{ date('d/m/Y H:i') }}</span>
                </td>
            </tr>
        </table>
        <hr style="border: 0; border-top: 1.5px solid #222; margin: 4px 0 8px 0;" />

        <!-- Title & Identitas Pasien -->
        <table class="table-custom">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: middle; background-color: #fafafa;">
                    <h4 style="margin: 0; padding: 4px; text-transform: uppercase;">
                        PENILAIAN AWAL MEDIS<br>RAWAT JALAN DEWASA
                    </h4>
                    <div style="font-size: 10px; color: #444; margin-top: 3px;">
                        Tgl Asuhan: <b>{{ $data && $data->tanggal ? Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y H:i:s') : '-' }}</b>
                    </div>
                </td>
                <td style="width: 50%; font-size: 10.5px;">
                    <table class="table-noborder" width="100%">
                        <tr>
                            <td style="width: 35%;" class="sub-label">No. Rawat</td>
                            <td style="width: 5%;">:</td>
                            <td><b>{{ $data->no_rawat ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td class="sub-label">No. RM</td>
                            <td>:</td>
                            <td><b>{{ $data->regPeriksa->no_rkm_medis ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td class="sub-label">Nama Pasien</td>
                            <td>:</td>
                            <td><b>{{ $data->regPeriksa->pasien->nm_pasien ?? '-' }}</b> ({{ $data->regPeriksa->pasien->jk ?? '-' }})</td>
                        </tr>
                        <tr>
                            <td class="sub-label">Tgl Lahir / Umur</td>
                            <td>:</td>
                            <td>
                                {{ $data->regPeriksa->pasien->tgl_lahir ? Carbon\Carbon::parse($data->regPeriksa->pasien->tgl_lahir)->translatedFormat('d F Y') : '-' }}
                                ({{ $data->regPeriksa->umurdaftar ?? '-' }} {{ $data->regPeriksa->sttsumur ?? '' }})
                            </td>
                        </tr>
                        <tr>
                            <td class="sub-label">DPJP / Dokter</td>
                            <td>:</td>
                            <td><b>{{ $data->dokter->nm_dokter ?? ($data->kd_dokter ?? '-') }}</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- I. ANAMNESIS & RIWAYAT KESEHATAN -->
        <div class="section-title">I. ANAMNESIS ({{ $data->anamnesis ?? 'Autoanamnesis' }}{{ $data->hubungan && $data->hubungan !== '-' ? ', Hubungan: ' . $data->hubungan : '' }})</div>
        <table class="table-custom">
            <tr>
                <td style="width: 25%;" class="sub-label">Keluhan Utama</td>
                <td style="width: 75%;" colspan="3">{{ $data->keluhan_utama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Riwayat Penyakit Sekarang (RPS)</td>
                <td colspan="3">{{ $data->rps ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Riwayat Penyakit Dahulu (RPD)</td>
                <td style="width: 30%;">{{ $data->rpd ?? '-' }}</td>
                <td style="width: 20%;" class="sub-label">Riwayat Alergi</td>
                <td style="width: 25%;">{{ $data->alergi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Riwayat Penyakit Keluarga (RPK)</td>
                <td>{{ $data->rpk ?? '-' }}</td>
                <td class="sub-label">Riwayat Penggunaan Obat</td>
                <td>{{ $data->rpo ?? '-' }}</td>
            </tr>
        </table>

        <!-- II. PEMERIKSAAN FISIK & TTV -->
        <div class="section-title">II. PEMERIKSAAN FISIK & TANDA VITAL</div>
        <table class="table-custom">
            <tr>
                <td colspan="4">
                    <span class="sub-label">Keadaan Umum:</span> <b>{{ $data->keadaan ?? 'Sehat' }}</b> &nbsp;|&nbsp;
                    <span class="sub-label">Kesadaran:</span> <b>{{ $data->kesadaran ?? 'Compos Mentis' }}</b> &nbsp;|&nbsp;
                    <span class="sub-label">GCS:</span> <b>{{ $data->gcs ?? '-' }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="sub-label">Tanda Vital:</span>
                    TD: <b>{{ $data->td ?? '-' }} mmHg</b> &nbsp;|&nbsp;
                    Nadi: <b>{{ $data->nadi ?? '-' }} x/mnt</b> &nbsp;|&nbsp;
                    RR: <b>{{ $data->rr ?? '-' }} x/mnt</b> &nbsp;|&nbsp;
                    Suhu: <b>{{ $data->suhu ?? '-' }} °C</b> &nbsp;|&nbsp;
                    SpO2: <b>{{ $data->spo ?? '-' }} %</b> &nbsp;|&nbsp;
                    BB: <b>{{ $data->bb ?? '-' }} Kg</b> &nbsp;|&nbsp;
                    TB: <b>{{ $data->tb ?? '-' }} cm</b>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;"><span class="sub-label">Kepala:</span> {{ $data->kepala ?? 'Normal' }}</td>
                <td style="width: 25%;"><span class="sub-label">Gigi & Mulut:</span> {{ $data->gigi ?? 'Normal' }}</td>
                <td style="width: 25%;"><span class="sub-label">THT:</span> {{ $data->tht ?? 'Normal' }}</td>
                <td style="width: 25%;"><span class="sub-label">Thoraks:</span> {{ $data->thoraks ?? 'Normal' }}</td>
            </tr>
            <tr>
                <td><span class="sub-label">Abdomen:</span> {{ $data->abdomen ?? 'Normal' }}</td>
                <td><span class="sub-label">Genital:</span> {{ $data->genital ?? 'Normal' }}</td>
                <td><span class="sub-label">Ekstremitas:</span> {{ $data->ekstremitas ?? 'Normal' }}</td>
                <td><span class="sub-label">Kulit:</span> {{ $data->kulit ?? 'Normal' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Keterangan Fisik Khusus</td>
                <td colspan="3">{{ $data->ket_fisik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Status Lokalis</td>
                <td colspan="3">{{ $data->ket_lokalis ?? '-' }}</td>
            </tr>
        </table>

        <!-- III. PEMERIKSAAN PENUNJANG -->
        <div class="section-title">III. PEMERIKSAAN PENUNJANG (LABORATORIUM / RADIOLOGI)</div>
        <table class="table-custom">
            <tr>
                <td style="white-space: pre-line;">{{ $data->penunjang && $data->penunjang !== '-' ? $data->penunjang : '-' }}</td>
            </tr>
        </table>

        <!-- IV. DIAGNOSIS / ASESMEN KERJA -->
        <div class="section-title">IV. DIAGNOSIS KERJA & DIAGNOSIS BANDING</div>
        <table class="table-custom">
            <tr>
                <td style="white-space: pre-line; font-weight: bold;">{{ $data->diagnosis && $data->diagnosis !== '-' ? $data->diagnosis : '-' }}</td>
            </tr>
        </table>

        <!-- V. TATA LAKSANA & KONSUL/RUJUK -->
        <div class="section-title">V. TATA LAKSANA / PERENCANAAN PENGOBATAN & VI. KONSUL/RUJUK</div>
        <table class="table-custom">
            <tr>
                <td style="width: 60%;">
                    <span class="sub-label">Rencana Pengobatan / Tindakan / Terapi:</span><br>
                    <div style="white-space: pre-line; margin-top: 3px;">{{ $data->tata && $data->tata !== '-' ? $data->tata : '-' }}</div>
                </td>
                <td style="width: 40%;">
                    <span class="sub-label">Konsul / Rujuk:</span><br>
                    <div style="white-space: pre-line; margin-top: 3px;">{{ $data->konsulrujuk && $data->konsulrujuk !== '-' ? $data->konsulrujuk : '-' }}</div>
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan Dokter -->
        <table width="100%" class="table-noborder" style="margin-top: 15px;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: center;">
                    <div>{{ Carbon\Carbon::parse($data->tanggal ?? date('Y-m-d'))->translatedFormat('d F Y') }}</div>
                    <div style="margin-bottom: 50px;">Dokter Penanggung Jawab Pelayanan,</div>
                    <div><b><u>{{ $data->dokter->nm_dokter ?? ($data->kd_dokter ?? 'Dokter') }}</u></b></div>
                    <div style="font-size: 10px; color: #555;">SIP: {{ $data->dokter->no_ijn_praktek ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endsection

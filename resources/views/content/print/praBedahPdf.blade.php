@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
@endphp

@section('content')
<div style="font-size: 11px; font-family: sans-serif; line-height: 1.35;">
    <!-- KOP SURAT -->
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px;">
        <h3 style="margin: 0; font-size: 15px; font-weight: bold;">{!! nl2br(e(str_replace('|', "\n", $setting->nama_instansi ?? 'KLINIK / RUMAH SAKIT'))) !!}</h3>
        <p style="margin: 2px 0;">{!! nl2br(e(str_replace('|', "\n", $setting->alamat_instansi ?? ''))) !!}</p>
        <p style="margin: 0; font-size: 10px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <!-- JUDUL -->
    <div style="text-align: center; margin-bottom: 15px;">
        <h4 style="margin: 0; font-size: 13px; font-weight: bold; text-decoration: underline;">
            ASESMEN PRA BEDAH (PRE-OPERATIF)
        </h4>
        <p style="margin: 2px 0; font-size: 10px;">Tanggal Asesmen: <strong>{{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y H:i') }}</strong></p>
    </div>

    <!-- DATA PASIEN -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;">
        <tr>
            <td style="width: 20%; font-weight: bold;">No. Rekam Medis</td>
            <td style="width: 2%;">:</td>
            <td style="width: 28%;">{{ $data->regPeriksa->no_rkm_medis ?? '-' }}</td>
            <td style="width: 20%; font-weight: bold;">No. Rawat</td>
            <td style="width: 2%;">:</td>
            <td style="width: 28%;">{{ $data->no_rawat }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Pasien</td>
            <td>:</td>
            <td>{{ $data->regPeriksa->pasien->nm_pasien ?? '-' }}</td>
            <td style="font-weight: bold;">Tgl. Lahir / JK</td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($data->regPeriksa->pasien->tgl_lahir ?? now())->translatedFormat('d F Y') }} / {{ ($data->regPeriksa->pasien->jk ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dokter Operator</td>
            <td>:</td>
            <td colspan="4"><strong>{{ $data->dokter->nm_dokter ?? '-' }}</strong> ({{ $data->kd_dokter }})</td>
        </tr>
    </table>

    <!-- KONTEN ASESMEN PRA BEDAH -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11px;" border="1" cellpadding="6">
        <tr>
            <td style="width: 30%; background-color: #f7f7f7; font-weight: bold;">1. Ringkasan Riwayat Klinik</td>
            <td>{!! nl2br(e($data->ringkasan_klinik)) !!}</td>
        </tr>
        <tr>
            <td style="background-color: #f7f7f7; font-weight: bold;">2. Pemeriksaan Fisik Terkait & TTV</td>
            <td>{!! nl2br(e($data->pemeriksaan_fisik)) !!}</td>
        </tr>
        <tr>
            <td style="background-color: #f7f7f7; font-weight: bold;">3. Pemeriksaan Diagnostik & Penunjang</td>
            <td>{!! nl2br(e($data->pemeriksaan_diagnostik)) !!}</td>
        </tr>
        <tr>
            <td style="background-color: #f7f7f7; font-weight: bold;">4. Diagnosa Pre-Operasi</td>
            <td><strong style="color: #0b5ed7;">{!! nl2br(e($data->diagnosa_pre_operasi)) !!}</strong></td>
        </tr>
        <tr>
            <td style="background-color: #f7f7f7; font-weight: bold;">5. Rencana Tindakan Bedah</td>
            <td><strong>{!! nl2br(e($data->rencana_tindakan_bedah)) !!}</strong></td>
        </tr>
        <tr>
            <td style="background-color: #f7f7f7; font-weight: bold;">6. Hal yang Perlu Dipersiapkan</td>
            <td>{!! nl2br(e($data->hal_hal_yang_perludi_persiapkan)) !!}</td>
        </tr>
        <tr>
            <td style="background-color: #f7f7f7; font-weight: bold;">7. Terapi Pre-Operasi</td>
            <td>{!! nl2br(e($data->terapi_pre_operasi)) !!}</td>
        </tr>
    </table>

    <!-- TANDA TANGAN DOKTER OPERATOR -->
    <table style="width: 100%; text-align: right; border-collapse: collapse; margin-top: 25px; font-size: 11px;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 60px 0;">Dokter Spesialis Bedah / Operator,</p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->dokter->nm_dokter ?? '-' }}</p>
                <p style="margin: 0; font-size: 10px;">SIP: {{ $data->kd_dokter }}</p>
            </td>
        </tr>
    </table>
</div>
@endsection

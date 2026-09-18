@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
@endphp

@section('content')
<div style="font-size: 10.5px; font-family: sans-serif; line-height: 1.35;">
    <!-- KOP SURAT -->
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px;">
        <h3 style="margin: 0; font-size: 15px; font-weight: bold;">{!! nl2br(e(str_replace('|', "\n", $setting->nama_instansi ?? 'KLINIK / RUMAH SAKIT'))) !!}</h3>
        <p style="margin: 2px 0;">{!! nl2br(e(str_replace('|', "\n", $setting->alamat_instansi ?? ''))) !!}</p>
        <p style="margin: 0; font-size: 10px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <!-- JUDUL -->
    <div style="text-align: center; margin-bottom: 12px;">
        <h4 style="margin: 0; font-size: 13px; font-weight: bold; text-decoration: underline;">
            ASESMEN PRA ANESTESI & SEDASI
        </h4>
        <p style="margin: 2px 0; font-size: 10px;">Tanggal Asesmen: <strong>{{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y H:i') }}</strong></p>
    </div>

    <!-- DATA PASIEN -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10.5px;">
        <tr>
            <td style="width: 18%; font-weight: bold;">No. Rekam Medis</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $data->regPeriksa->no_rkm_medis ?? '-' }}</td>
            <td style="width: 18%; font-weight: bold;">No. Rawat</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $data->no_rawat }}</td>
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
            <td style="font-weight: bold;">Dokter Anestesi</td>
            <td>:</td>
            <td><strong>{{ $data->dokter->nm_dokter ?? '-' }}</strong></td>
            <td style="font-weight: bold;">Jadwal Operasi</td>
            <td>:</td>
            <td>{{ $data->tanggal_operasi ? Carbon\Carbon::parse($data->tanggal_operasi)->translatedFormat('d F Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Diagnosis</td>
            <td>:</td>
            <td>{{ $data->diagnosa }}</td>
            <td style="font-weight: bold;">Rencana Tindakan</td>
            <td>:</td>
            <td>{{ $data->rencana_tindakan }}</td>
        </tr>
    </table>

    <!-- TTV & KONDISI FISIK -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 10.5px;" border="1" cellpadding="4">
        <tr style="background-color: #f7f7f7;">
            <th colspan="7" style="text-align: left;">I. TANDA-TANDA VITAL (TTV)</th>
        </tr>
        <tr style="text-align: center;">
            <td style="width: 15%;"><strong>TD</strong>: {{ $data->td }} mmHg</td>
            <td style="width: 15%;"><strong>Nadi</strong>: {{ $data->nadi }} x/m</td>
            <td style="width: 15%;"><strong>RR</strong>: {{ $data->pernapasan }} x/m</td>
            <td style="width: 15%;"><strong>Suhu</strong>: {{ $data->suhu }} °C</td>
            <td style="width: 15%;"><strong>SpO2</strong>: {{ $data->io2 }} %</td>
            <td style="width: 12%;"><strong>TB</strong>: {{ $data->tb }} cm</td>
            <td style="width: 13%;"><strong>BB</strong>: {{ $data->bb }} kg</td>
        </tr>
    </table>

    <!-- SISTEM ORGAN & ALERGI -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 10px;" border="1" cellpadding="4">
        <tr style="background-color: #f7f7f7;">
            <th colspan="2" style="text-align: left;">II. PEMERIKSAAN SISTEM ORGAN & RIWAYAT</th>
        </tr>
        <tr>
            <td style="width: 25%; font-weight: bold;">Kardiovaskuler</td>
            <td>{{ $data->fisik_cardiovasculer }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Paru / Pernapasan</td>
            <td>{{ $data->fisik_paru }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Abdomen</td>
            <td>{{ $data->fisik_abdomen }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Ekstremitas / Endokrin / Ginjal</td>
            <td>Ekstremitas: {{ $data->fisik_extrimitas }} | Endokrin: {{ $data->fisik_endokrin }} | Ginjal: {{ $data->fisik_ginjal }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Laborat & Penunjang</td>
            <td>Laborat: {{ $data->fisik_laborat }} | Penunjang: {{ $data->fisik_penunjang }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Riwayat Alergi</td>
            <td>Alergi Obat: <strong>{{ $data->riwayat_penyakit_alergiobat }}</strong> | Alergi Lainnya: {{ $data->riwayat_penyakit_alergilainnya }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Kebiasaan & Obat Rutin</td>
            <td>Merokok: {{ $data->riwayat_kebiasaan_merokok }} ({{ $data->riwayat_kebiasaan_ket_merokok }}) | Alkohol: {{ $data->riwayat_kebiasaan_alkohol }} ({{ $data->riwayat_kebiasaan_ket_alkohol }}) | Obat: {{ $data->riwayat_kebiasaan_obat }} ({{ $data->riwayat_kebiasaan_ket_obat }})</td>
        </tr>
    </table>

    <!-- RENCANA ANESTESI & STATUS ASA -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10.5px;" border="1" cellpadding="5">
        <tr style="background-color: #e8f0fe;">
            <th colspan="2" style="text-align: left; color: #1a73e8;">III. KEPUTUSAN STATUS ASA & RENCANA ANESTESI</th>
        </tr>
        <tr>
            <td style="width: 30%; font-weight: bold;">Status Fisik ASA</td>
            <td><strong style="color: #d63384; font-size: 12px;">ASA {{ $data->asa }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Rencana Teknik Anestesi</td>
            <td><strong style="color: #0d6efd;">{{ $data->rencana_anestesi }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Mulai Puasa</td>
            <td>{{ $data->puasa ? Carbon\Carbon::parse($data->puasa)->translatedFormat('d F Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Rencana Pasca Bedah</td>
            <td>{{ $data->rencana_perawatan }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Catatan Khusus Anestesi</td>
            <td>{{ $data->catatan_khusus }}</td>
        </tr>
    </table>

    <!-- TANDA TANGAN -->
    <table style="width: 100%; text-align: right; border-collapse: collapse; margin-top: 20px; font-size: 10.5px;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 55px 0;">Dokter Spesialis Anestesiologi,</p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->dokter->nm_dokter ?? '-' }}</p>
                <p style="margin: 0; font-size: 9.5px;">SIP: {{ $data->kd_dokter }}</p>
            </td>
        </tr>
    </table>
</div>
@endsection

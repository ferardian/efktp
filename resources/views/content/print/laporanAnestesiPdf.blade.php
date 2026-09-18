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
            LAPORAN & MONITORING ANESTESI
        </h4>
        <p style="margin: 2px 0; font-size: 10px;">Waktu Operasi: <strong>{{ Carbon\Carbon::parse($data->mulai)->translatedFormat('d F Y H:i') }} s.d {{ Carbon\Carbon::parse($data->selesai)->translatedFormat('d F Y H:i') }}</strong></p>
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
            <td><strong>{{ $data->dokterAnestesi->nm_dokter ?? '-' }}</strong></td>
            <td style="font-weight: bold;">Dokter Operator / Bedah</td>
            <td>:</td>
            <td><strong>{{ $data->dokterOperator1->nm_dokter ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Diagnosa Pra Bedah</td>
            <td>:</td>
            <td>{{ $data->diagnosa_pra_bedah }}</td>
            <td style="font-weight: bold;">Diagnosa Pasca Bedah</td>
            <td>:</td>
            <td>{{ $data->diagnosa_pasca_bedah }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tindakan Operasi</td>
            <td>:</td>
            <td colspan="4">{{ $data->tindakan }}</td>
        </tr>
    </table>

    <!-- TEKNIK & STATUS ANESTESI -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 10px;" border="1" cellpadding="4">
        <tr style="background-color: #f7f7f7;">
            <th colspan="2" style="text-align: left;">I. STATUS & TEKNIK ANESTESI</th>
        </tr>
        <tr>
            <td style="width: 30%; font-weight: bold;">Status Fisik ASA</td>
            <td><strong>{{ $data->status_asa }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Obat Premedikasi</td>
            <td>{{ $data->obat_premedikasi ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Sedasi & Regional</td>
            <td>
                Sedasi: <strong>{{ $data->jenis_anestesi_sedasi }}</strong> | 
                Regional: <strong>{{ $data->jenis_anestesi_regional }}</strong> | 
                Lokasi: <strong>{{ $data->jenis_anestesi_lokasi }}</strong>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">General Anesthesia (GA)</td>
            <td>
                ETT: <strong>{{ $data->jenis_anestesi_ga_ett }}</strong> | 
                NTT: <strong>{{ $data->jenis_anestesi_ga_ntt }}</strong> | 
                LMA: <strong>{{ $data->jenis_anestesi_ga_ema }}</strong> | 
                Face Mask: <strong>{{ $data->jenis_anestesi_ga_bm }}</strong>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Posisi Pasien</td>
            <td>{{ $data->posisi }}</td>
        </tr>
    </table>

    <!-- HASIL & PENGAKHIRAN ANESTESI -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 10px;" border="1" cellpadding="4">
        <tr style="background-color: #f7f7f7;">
            <th colspan="2" style="text-align: left;">II. PENGAKHIRAN ANESTESI & INTRA OPERATIF</th>
        </tr>
        <tr>
            <td style="width: 30%; font-weight: bold;">Pendarahan Intra-Op</td>
            <td>{{ $data->perdarahan }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Output Urine Intra-Op</td>
            <td>{{ $data->urine }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Komplikasi Intra-Op</td>
            <td>{{ $data->komplikasi }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Ekstubasi</td>
            <td>{{ $data->ekstubasi }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jumlah Tampon / Pack</td>
            <td>{{ $data->jumlah_pack }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dipindahkan Ke</td>
            <td><strong>{{ $data->dipindahkan_ke }}</strong> (Serah terima: {{ $data->serah_terima_pasien }})</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Catatan Khusus / Instruksi Pasca Bedah</td>
            <td>{!! nl2br(e($data->catatan)) !!}</td>
        </tr>
    </table>

    <!-- TANDA TANGAN DOKTER ANESTESI -->
    <table style="width: 100%; margin-top: 15px; font-size: 10.5px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0;">{{ $setting->kabupaten ?? 'Tempat' }}, {{ Carbon\Carbon::parse($data->mulai)->translatedFormat('d F Y') }}</p>
                <p style="margin: 0; font-weight: bold;">Dokter Spesialis Anestesiologi,</p>
                <div style="height: 60px;"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ $data->dokterAnestesi->nm_dokter ?? '....................................' }}
                </p>
                <p style="margin: 0; font-size: 9.5px;">SIP: {{ $data->dokterAnestesi->no_ijn_praktek ?? '-' }}</p>
            </td>
        </tr>
    </table>
</div>
@endsection

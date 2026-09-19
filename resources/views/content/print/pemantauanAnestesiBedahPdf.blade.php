@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
@endphp

@section('content')
<div style="font-size: 10px; font-family: sans-serif; line-height: 1.35;">
    <!-- KOP SURAT -->
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px;">
        <h3 style="margin: 0; font-size: 15px; font-weight: bold;">{!! nl2br(e(str_replace('|', "\n", $setting->nama_instansi ?? 'KLINIK / RUMAH SAKIT'))) !!}</h3>
        <p style="margin: 2px 0;">{!! nl2br(e(str_replace('|', "\n", $setting->alamat_instansi ?? ''))) !!}</p>
        <p style="margin: 0; font-size: 10px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <!-- JUDUL -->
    <div style="text-align: center; margin-bottom: 10px;">
        <h4 style="margin: 0; font-size: 12.5px; font-weight: bold; text-decoration: underline;">
            BUKTI JENIS, DOSIS, TEKNIK ANESTESI & PEMANTAUAN STATUS FISIOLOGI PASIEN
        </h4>
        <p style="margin: 2px 0; font-size: 10px;">
            SELAMA SERTA PASCA TINDAKAN ANESTESI DAN BEDAH
        </p>
    </div>

    <!-- DATA PASIEN -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 10px;">
        <tr>
            <td style="width: 18%; font-weight: bold;">No. Rekam Medis</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $regPeriksa->no_rkm_medis ?? '-' }}</td>
            <td style="width: 18%; font-weight: bold;">No. Rawat</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $regPeriksa->no_rawat }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Pasien</td>
            <td>:</td>
            <td>{{ $regPeriksa->pasien->nm_pasien ?? '-' }}</td>
            <td style="font-weight: bold;">Tgl. Lahir / JK</td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($regPeriksa->pasien->tgl_lahir ?? now())->translatedFormat('d F Y') }} / {{ ($regPeriksa->pasien->jk ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
    </table>

    <!-- BAGIAN 1: BUKTI JENIS, DOSIS & SIGN IN ANESTESI -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 9.5px;" border="1" cellpadding="4">
        <tr style="background-color: #f2f2f2;">
            <th colspan="4" style="text-align: left; font-size: 10px;">
                I. BUKTI JENIS, DOSIS, TEKNIK ANESTESI & CHECKLIST SIGN-IN
            </th>
        </tr>
        <tr>
            <td style="width: 20%; font-weight: bold;">Nama Tindakan</td>
            <td style="width: 30%;">{{ $signin->tindakan ?? '-' }}</td>
            <td style="width: 20%; font-weight: bold;">Diagnosa</td>
            <td style="width: 30%;">{{ $signin->diagnosa ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dokter Operator / Bedah</td>
            <td><strong>{{ $signin->dokterBedah->nm_dokter ?? '-' }}</strong></td>
            <td style="font-weight: bold;">Dokter Anestesi</td>
            <td><strong>{{ $signin->dokterAnestesi->nm_dokter ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Verifikasi Identitas Pasien</td>
            <td>{{ ($signin->identitas_sesuai ?? 'Ya') === 'Ya' ? '✔ Sesuai' : '✖ Tidak Sesuai' }}</td>
            <td style="font-weight: bold;">Informed Consent Tindakan</td>
            <td>{{ ($signin->informed_consent ?? 'Ya') === 'Ya' ? '✔ Sudah Disetujui' : '✖ Belum' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Teknik / Rencana Anestesi</td>
            <td><strong>{{ $signin->rencana_anestesi ?? '-' }}</strong></td>
            <td style="font-weight: bold;">Kesiapan Alat & Obat</td>
            <td>{{ $signin->kesiapan_alat_obat ?? 'Lengkap' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Obat Anestesi & Dosis</td>
            <td colspan="3" style="font-size: 10px; background-color: #fafafa;">
                Obat: <strong>{{ $signin->obat_anestesi ?? '-' }}</strong> &nbsp;|&nbsp; 
                Dosis: <strong>{{ $signin->dosis ?? '-' }}</strong>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Riwayat Alergi</td>
            <td>{{ $signin->alergi ?? 'Tidak Ada' }}</td>
            <td style="font-weight: bold;">Perawat / Petugas OK</td>
            <td>{{ $signin->petugasOk->nama ?? '-' }}</td>
        </tr>
        @if(!empty($signin->catatan) && $signin->catatan !== '-')
        <tr>
            <td style="font-weight: bold;">Catatan Tambahan</td>
            <td colspan="3">{{ $signin->catatan }}</td>
        </tr>
        @endif
    </table>

    <!-- BAGIAN 2: PEMANTAUAN STATUS FISIOLOGI PASIEN (INTERVAL WAKTU) -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 9px;" border="1" cellpadding="4">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th colspan="9" style="text-align: left; font-size: 10px;">
                    II. PEMANTAUAN STATUS FISIOLOGI PASIEN SELAMA & PASCA TINDAKAN
                </th>
            </tr>
            <tr style="background-color: #f8f9fa; text-align: center;">
                <th style="width: 16%;">Interval Waktu / Menit</th>
                <th style="width: 9%;">Jam</th>
                <th style="width: 22%;">Keluhan Pasien</th>
                <th style="width: 10%;">TD (mmHg)</th>
                <th style="width: 9%;">Nadi (x/m)</th>
                <th style="width: 9%;">RR (x/m)</th>
                <th style="width: 8%;">Suhu (°C)</th>
                <th style="width: 7%;">SpO2 (%)</th>
                <th style="width: 10%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemantauan as $row)
            <tr style="text-align: center;">
                <td style="font-weight: bold; text-align: left; padding-left: 6px;">{{ $row->waktu_menit }}</td>
                <td>{{ $row->jam }}</td>
                <td style="text-align: left;">{{ $row->keluhan }}</td>
                <td>{{ $row->td }}</td>
                <td>{{ $row->nadi }}</td>
                <td>{{ $row->rr }}</td>
                <td>{{ $row->suhu }}</td>
                <td>{{ $row->spo2 }}</td>
                <td>{{ $row->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; color: #888; padding: 10px;">Belum ada catatan observasi fisiologi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- BAGIAN 3: VERIFIKASI & TANDA TANGAN DOKTER -->
    <table style="width: 100%; margin-top: 15px; font-size: 10px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0;">
                    {{ $setting->kabupaten ?? 'Tempat' }}, 
                    {{ $verifikasi ? Carbon\Carbon::parse($verifikasi->tanggal_verifikasi)->translatedFormat('d F Y') : date('d F Y') }}
                </p>
                <p style="margin: 2px 0 0 0; font-weight: bold;">Dokter Verifikator Tindakan & Anestesi,</p>
                <div style="height: 65px; display: flex; align-items: center; justify-content: center; margin: 4px 0;">
                    @if(!empty($verifikasi) && !empty($verifikasi->tanda_tangan))
                        <img src="{{ public_path($verifikasi->tanda_tangan) }}" style="max-height: 60px; max-width: 140px;" alt="TTD Dokter" />
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ $verifikasi->dokter->nm_dokter ?? ($signin->dokterBedah->nm_dokter ?? '....................................') }}
                </p>
                <p style="margin: 2px 0 0 0; font-size: 9px;">
                    Jam Verifikasi: {{ $verifikasi->jam_verifikasi ?? date('H:i') }} WIB
                </p>
            </td>
        </tr>
    </table>
</div>
@endsection

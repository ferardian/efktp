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
    <div style="text-align: center; margin-bottom: 12px;">
        <h4 style="margin: 0; font-size: 13px; font-weight: bold; text-decoration: underline;">
            PERENCANAAN PEMULANGAN PASIEN (DISCHARGE PLANNING)
        </h4>
        <p style="margin: 2px 0; font-size: 10px;">Estimasi Tanggal Pulang: <strong>{{ Carbon\Carbon::parse($data->rencana_pulang)->translatedFormat('d F Y') }}</strong></p>
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
            <td style="font-weight: bold;">Alasan Masuk</td>
            <td>:</td>
            <td>{{ $data->alasan_masuk }}</td>
            <td style="font-weight: bold;">Diagnosa Medis</td>
            <td>:</td>
            <td>{{ $data->diagnosa_medis }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Keluarga / PJ</td>
            <td>:</td>
            <td><strong>{{ $data->nama_pasien_keluarga }}</strong></td>
            <td style="font-weight: bold;">Petugas Pengkaji</td>
            <td>:</td>
            <td>{{ $data->petugas->nama ?? '-' }}</td>
        </tr>
    </table>

    <!-- SKRINING DISCHARGE PLANNING -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 9.5px;" border="1" cellpadding="4">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: center;">
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Parameter Kebutuhan Rencana Pemulangan</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 35%;">Keterangan / Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Pengaruh Rawat Inap terhadap Pasien & Keluarga</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->pengaruh_ri_pasien_dan_keluarga }}</td>
                <td>{{ $data->keterangan_pengaruh_ri_pasien_dan_keluarga }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Pengaruh Rawat Inap terhadap Pekerjaan / Sekolah</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->pengaruh_ri_pekerjaan_sekolah }}</td>
                <td>{{ $data->keterangan_pengaruh_ri_pekerjaan_sekolah }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Pengaruh Rawat Inap terhadap Masalah Keuangan</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->pengaruh_ri_keuangan }}</td>
                <td>{{ $data->keterangan_pengaruh_ri_keuangan }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Antisipasi Masalah / Hambatan Saat Pulang</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->antisipasi_masalah_saat_pulang }}</td>
                <td>{{ $data->keterangan_antisipasi_masalah_saat_pulang }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Bantuan yang Diperlukan Dalam Aktivitas</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->bantuan_diperlukan_dalam }}</td>
                <td>{{ $data->keterangan_bantuan_diperlukan_dalam }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>Adakah Anggota Keluarga yang Membantu Keperluan Pasien</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->adakah_yang_membantu_keperluan }}</td>
                <td>{{ $data->keterangan_adakah_yang_membantu_keperluan }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>Apakah Pasien Tinggal Sendiri di Rumah</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->pasien_tinggal_sendiri }}</td>
                <td>{{ $data->keterangan_pasien_tinggal_sendiri }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td>Pasien Memerlukan Peralatan Medis di Rumah</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->pasien_menggunakan_peralatan_medis }}</td>
                <td>{{ $data->keterangan_pasien_menggunakan_peralatan_medis }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td>Pasien Memerlukan Alat Bantu Jalan / Fisik</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->pasien_memerlukan_alat_bantu }}</td>
                <td>{{ $data->keterangan_pasien_memerlukan_alat_bantu }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">10</td>
                <td>Memerlukan Perawatan Khusus (Luka, Kateter, NGT, dsb)</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->memerlukan_perawatan_khusus }}</td>
                <td>{{ $data->keterangan_memerlukan_perawatan_khusus }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">11</td>
                <td>Bermasalah dalam Pemenuhan Kebutuhan Hidup Sehari-hari</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->bermasalah_memenuhi_kebutuhan }}</td>
                <td>{{ $data->keterangan_bermasalah_memenuhi_kebutuhan }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">12</td>
                <td>Memiliki Nyeri Kronis yang Memerlukan Penanganan Berlanjut</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->memiliki_nyeri_kronis }}</td>
                <td>{{ $data->keterangan_memiliki_nyeri_kronis }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">13</td>
                <td>Memerlukan Edukasi Kesehatan Lanjutan Bagi Pasien / Keluarga</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->memerlukan_edukasi_kesehatan }}</td>
                <td>{{ $data->keterangan_memerlukan_edukasi_kesehatan }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">14</td>
                <td>Memerlukan Keterampilan Perawatan Khusus oleh Keluarga</td>
                <td style="text-align: center; font-weight: bold;">{{ $data->memerlukan_keterampilkan_khusus }}</td>
                <td>{{ $data->keterangan_memerlukan_keterampilkan_khusus }}</td>
            </tr>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table style="width: 100%; margin-top: 15px; font-size: 10px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0;">Pasien / Keluarga Yang Menerima Edukasi,</p>
                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                    @if(!empty($data->buktiSaksi) && !empty($data->buktiSaksi->photo))
                        <img src="{{ public_path($data->buktiSaksi->photo) }}" style="max-height: 60px; max-width: 140px;" alt="TTD Keluarga" />
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ $data->nama_pasien_keluarga }}
                </p>
                <p style="margin: 0; font-size: 9px;">Keluarga / Penanggung Jawab</p>
            </td>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0;">{{ $setting->kabupaten ?? 'Tempat' }}, {{ Carbon\Carbon::parse($data->rencana_pulang)->translatedFormat('d F Y') }}</p>
                <p style="margin: 0;">Petugas / Case Manager Pemulangan,</p>
                <div style="height: 65px;"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ $data->petugas->nama ?? '....................................' }}
                </p>
                <p style="margin: 0; font-size: 9px;">NIP: {{ $data->nip ?? '-' }}</p>
            </td>
        </tr>
    </table>
</div>
@endsection

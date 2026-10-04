@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
    $pasien = $regPeriksa->pasien;
    $bangsal = $regPeriksa->kamarInap->kamar->bangsal->nm_bangsal ?? ($regPeriksa->kamarInap->kd_kamar ?? '-');
    $tglMasuk = $regPeriksa->kamarInap->tgl_masuk ? Carbon\Carbon::parse($regPeriksa->kamarInap->tgl_masuk)->translatedFormat('d F Y') : Carbon\Carbon::parse($regPeriksa->tgl_registrasi)->translatedFormat('d F Y');
@endphp

@section('content')
<div style="font-size: 10.5px; font-family: sans-serif; line-height: 1.3;">
    <!-- KOP SURAT INSTANSI -->
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px;">
        <h3 style="margin: 0; font-size: 14.5px; font-weight: bold;">{!! nl2br(e(str_replace('|', "\n", $setting->nama_instansi ?? 'KLINIK / RUMAH SAKIT'))) !!}</h3>
        <p style="margin: 2px 0; font-size: 10px;">{!! nl2br(e(str_replace('|', "\n", $setting->alamat_instansi ?? ''))) !!}</p>
        <p style="margin: 0; font-size: 9.5px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <!-- JUDUL LEMBAR CPPT -->
    <div style="text-align: center; margin-bottom: 12px;">
        <h4 style="margin: 0; font-size: 12.5px; font-weight: bold; text-decoration: underline;">
            CATATAN PERKEMBANGAN PASIEN TERINTEGRASI (CPPT) / KAJIAN ULANG
        </h4>
        <p style="margin: 2px 0 0 0; font-size: 9.5px; color: #444;">
            LEMBAR REKAM MEDIS RAWAT INAP &bull; KAJIAN BERKALA EVALUASI PERKEMBANGAN PASIEN
        </p>
    </div>

    <!-- BIODATA PASIEN RAWAT INAP -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; border: 1px solid #999; padding: 4px;" cellpadding="3">
        <tr style="background-color: #fafafa;">
            <td style="width: 15%; font-weight: bold;">Nama Pasien</td>
            <td style="width: 1%;">:</td>
            <td style="width: 34%;"><strong>{{ $pasien->nm_pasien ?? '-' }}</strong></td>
            <td style="width: 15%; font-weight: bold;">No. Rekam Medis</td>
            <td style="width: 1%;">:</td>
            <td style="width: 34%;"><strong>{{ $regPeriksa->no_rkm_medis }}</strong> (No. Rawat: {{ $regPeriksa->no_rawat }})</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tgl. Lahir / JK</td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($pasien->tgl_lahir ?? now())->translatedFormat('d F Y') }} / {{ ($pasien->jk ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td style="font-weight: bold;">Ruang / Kamar</td>
            <td>:</td>
            <td>{{ $bangsal }} @if(!empty($regPeriksa->kamarInap->kd_kamar)) (Bed: {{ $regPeriksa->kamarInap->kd_kamar }}) @endif</td>
        </tr>
        <tr style="background-color: #fafafa;">
            <td style="font-weight: bold;">DPJP Utama</td>
            <td>:</td>
            <td>{{ $regPeriksa->dokter->nm_dokter ?? '-' }}</td>
            <td style="font-weight: bold;">Tgl. Masuk Ranap</td>
            <td>:</td>
            <td>{{ $tglMasuk }} {{ $regPeriksa->kamarInap->jam_masuk ?? '' }}</td>
        </tr>
    </table>

    <!-- TABEL UTAMA CPPT (STANDAR KEMENKES 5 KOLOM) -->
    <table style="width: 100%; border-collapse: collapse; font-size: 9.5px; margin-top: 5px;" border="1" cellpadding="4">
        <thead>
            <tr style="background-color: #e9ecef; text-align: center; font-weight: bold;">
                <th style="width: 12%;">TANGGAL & JAM</th>
                <th style="width: 18%;">PROFESIONAL PEMBERI ASUHAN (PPA)</th>
                <th style="width: 42%;">HASIL ASESMEN PASIEN & PEMBERIAN PELAYANAN (SOAP)</th>
                <th style="width: 16%;">INSTRUKSI TENAGA KESEHATAN</th>
                <th style="width: 12%;">REVIEW & VERIFIKASI DPJP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listCppt as $item)
                @php
                    $isDokter = !empty($item->pegawai->dokter);
                    $namaPpa = $item->pegawai->nama ?? ($item->nip ?: '-');
                    $profesiPpa = $isDokter ? 'Dokter DPJP / Jaga' : 'Perawat / Bidan';
                    $evaluasi = ($item->evaluasi && $item->evaluasi !== '-') ? $item->evaluasi : '';
                @endphp
                <tr>
                    <!-- WAKTU -->
                    <td style="text-align: center; vertical-align: top;">
                        <strong>{{ Carbon\Carbon::parse($item->tgl_perawatan)->format('d/m/Y') }}</strong><br>
                        <span style="font-size: 9px; color: #555;">{{ $item->jam_rawat }} WIB</span>
                    </td>

                    <!-- PPA -->
                    <td style="vertical-align: top;">
                        <strong>{{ $namaPpa }}</strong><br>
                        <span style="font-size: 8.5px; color: {{ $isDokter ? '#0d6efd' : '#198754' }}; font-weight: bold;">
                            [{{ $profesiPpa }}]
                        </span><br>
                        <span style="font-size: 8px; color: #777;">NIP/NIK: {{ $item->nip }}</span>
                    </td>

                    <!-- SOAP -->
                    <td style="vertical-align: top;">
                        <!-- S (Subjektif) -->
                        <div style="margin-bottom: 4px;">
                            <strong style="color: #0d6efd;">[S] Subjektif:</strong><br>
                            {!! nl2br(e($item->keluhan ?: '-')) !!}
                        </div>

                        <!-- O (Objektif) -->
                        <div style="margin-bottom: 4px;">
                            <strong style="color: #198754;">[O] Objektif:</strong><br>
                            <div style="background-color: #f8f9fa; border: 1px dashed #ccc; padding: 2px 4px; margin-bottom: 2px; font-size: 9px;">
                                <strong>TTV:</strong>
                                TD: {{ $item->tensi ?: '-' }} mmHg &bull;
                                N: {{ $item->nadi ?: '-' }} x/m &bull;
                                S: {{ $item->suhu_tubuh ?: '-' }} °C &bull;
                                RR: {{ $item->respirasi ?: '-' }} x/m
                                @if($item->spo2 && $item->spo2 !== '-') &bull; SpO2: {{ $item->spo2 }}% @endif
                                @if($item->gcs && $item->gcs !== '-') &bull; GCS: {{ $item->gcs }} @endif
                                @if($item->kesadaran && $item->kesadaran !== '-') &bull; Kesadaran: {{ $item->kesadaran }} @endif
                            </div>
                            @if($item->pemeriksaan && $item->pemeriksaan !== '-')
                                <span>Pemeriksaan Fisik: {!! nl2br(e($item->pemeriksaan)) !!}</span><br>
                            @endif
                            @if($item->alergi && $item->alergi !== '-')
                                <span style="color: #b00; font-size: 8.5px;">Alergi: {{ $item->alergi }}</span>
                            @endif
                        </div>

                        <!-- A (Asesmen) -->
                        <div style="margin-bottom: 4px;">
                            <strong style="color: #d63384;">[A] Asesmen:</strong><br>
                            {!! nl2br(e($item->penilaian ?: '-')) !!}
                        </div>

                        <!-- P (Plan) -->
                        <div>
                            <strong style="color: #fd7e14;">[P] Plan:</strong><br>
                            {!! nl2br(e($item->rtl ?: '-')) !!}
                        </div>
                    </td>

                    <!-- INSTRUKSI -->
                    <td style="vertical-align: top;">
                        @if($item->instruksi && $item->instruksi !== '-')
                            {!! nl2br(e($item->instruksi)) !!}
                        @else
                            <span style="color: #888;">-</span>
                        @endif
                    </td>

                    <!-- VERIFIKASI DPJP -->
                    <td style="vertical-align: top; text-align: center;">
                        @if($isDokter)
                            <div style="color: #198754; font-weight: bold; font-size: 8.5px; border: 1px solid #198754; padding: 2px; border-radius: 3px; margin-bottom: 4px;">
                                &check; DPJP / Dokter
                            </div>
                            <div style="font-size: 8px; color: #555;">(Terverifikasi saat visite)</div>
                        @else
                            @if(!empty($evaluasi))
                                <div style="font-size: 8.5px; text-align: left; background: #fff3cd; padding: 2px; border: 1px solid #ffeeba; margin-bottom: 2px;">
                                    <strong>Catatan DPJP:</strong><br>{{ $evaluasi }}
                                </div>
                                <span style="font-size: 8px; color: #198754; font-weight: bold;">&check; Terverifikasi DPJP</span>
                            @else
                                <br><br>
                                <span style="border-top: 1px dotted #888; font-size: 8px; display: inline-block; padding-top: 2px;">Paraf DPJP</span>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #777;">
                        Belum ada catatan CPPT (Kajian Ulang) yang terdokumentasi untuk pasien ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- CATATAN FOOTER AKREDITASI -->
    <div style="margin-top: 10px; font-size: 8.5px; color: #666; display: flex; justify-content: space-between;">
        <div>
            * Lembar Catatan Perkembangan Pasien Terintegrasi (CPPT) merupakan dokumen rekam medis rahasia.<br>
            * DPJP wajib membaca dan melakukan verifikasi catatan PPA lain dalam waktu maksimal 1 x 24 jam.
        </div>
        <div style="text-align: right;">
            Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>
</div>
@endsection

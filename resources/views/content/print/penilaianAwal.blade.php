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
            background-color: #f0f0f0;
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
                    <b>RM 04/RALAN/KEP</b><br>
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
                        PENILAIAN AWAL KEPERAWATAN<br>RAWAT JALAN UMUM
                    </h4>
                    <div style="font-size: 10px; color: #444; margin-top: 3px;">
                        Tgl Pengkajian: <b>{{ $data && $data->tanggal ? Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y H:i:s') : '-' }}</b>
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
                            <td class="sub-label">Petugas Pengkaji</td>
                            <td>:</td>
                            <td>{{ $data->pegawai->nama ?? ($data->petugas->nama ?? ($data->nip ?? '-')) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- I. KEADAAN UMUM & RIWAYAT KESEHATAN -->
        <div class="section-title">I. KEADAAN UMUM & RIWAYAT KESEHATAN (Anamnesis: {{ $data->informasi ?? 'Autoanamnesis' }})</div>
        <table class="table-custom">
            <tr>
                <td colspan="4">
                    <span class="sub-label">Tanda-Tanda Vital:</span>
                    TD: <b>{{ $data->td ?? '-' }} mmHg</b> &nbsp;|&nbsp;
                    Nadi: <b>{{ $data->nadi ?? '-' }} x/mnt</b> &nbsp;|&nbsp;
                    RR: <b>{{ $data->rr ?? '-' }} x/mnt</b> &nbsp;|&nbsp;
                    Suhu: <b>{{ $data->suhu ?? '-' }} °C</b> &nbsp;|&nbsp;
                    GCS: <b>{{ $data->gcs ?? '-' }}</b>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;" class="sub-label">Keluhan Utama</td>
                <td style="width: 75%;" colspan="3">{{ $data->keluhan_utama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Riwayat Penyakit Dahulu (RPD)</td>
                <td colspan="3">{{ $data->rpd ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Riwayat Penyakit Keluarga (RPK)</td>
                <td style="width: 30%;">{{ $data->rpk ?? '-' }}</td>
                <td style="width: 20%;" class="sub-label">Riwayat Alergi</td>
                <td style="width: 25%;">{{ $data->alergi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="sub-label">Riwayat Penggunaan Obat (RPO)</td>
                <td colspan="3">{{ $data->rpo ?? '-' }}</td>
            </tr>
        </table>

        <!-- II. STATUS NUTRISI & III. FUNGSIONAL -->
        <div class="section-title">II. STATUS NUTRISI & III. FUNGSIONAL</div>
        <table class="table-custom">
            <tr>
                <td style="width: 50%;">
                    <span class="sub-label">Status Nutrisi:</span><br>
                    BB: <b>{{ $data->bb ?? '-' }} Kg</b> &nbsp;|&nbsp;
                    TB: <b>{{ $data->tb ?? '-' }} cm</b> &nbsp;|&nbsp;
                    BMI: <b>{{ $data->bmi ?? '-' }} Kg/m²</b>
                </td>
                <td style="width: 50%;">
                    <span class="sub-label">Status Fungsional:</span><br>
                    - Alat Bantu: <b>{{ $data->alat_bantu ?? 'Tidak' }}</b> (Ket: {{ $data->ket_bantu ?? '-' }})<br>
                    - Prothesa: <b>{{ $data->prothesa ?? 'Tidak' }}</b> (Ket: {{ $data->ket_pro ?? '-' }})<br>
                    - Cacat Fisik: <b>{{ $data->regPeriksa->pasien->cacatFisik->nama_cacat ?? ($data->regPeriksa->pasien->cacat_fisik ?? '-') }}</b><br>
                    - ADL: <b>{{ $data->adl ?? 'Mandiri' }}</b>
                </td>
            </tr>
        </table>

        <!-- IV. RIWAYAT PSIKO-SOSIAL, SPIRITUAL & BUDAYA -->
        <div class="section-title">IV. RIWAYAT PSIKO-SOSIAL, SPIRITUAL & BUDAYA</div>
        <table class="table-custom">
            <tr>
                <td style="width: 33%;">
                    <span class="sub-label">Psikologis:</span> {{ $data->status_psiko ?? 'Tenang' }}<br>
                    <span style="font-size: 10px;">Ket: {{ $data->ket_psiko ?? '-' }}</span>
                </td>
                <td style="width: 33%;">
                    <span class="sub-label">Hub. Keluarga:</span> {{ $data->hub_keluarga ?? 'Baik' }}<br>
                    <span class="sub-label">Tinggal Dengan:</span> {{ $data->tinggal_dengan ?? 'Sendiri' }} ({{ $data->ket_tinggal ?? '-' }})
                </td>
                <td style="width: 34%;">
                    <span class="sub-label">Ekonomi:</span> {{ $data->ekonomi ?? 'Baik' }}<br>
                    <span class="sub-label">Budaya:</span> {{ $data->budaya ?? 'Tidak Ada' }} ({{ $data->ket_budaya ?? '-' }})<br>
                    <span class="sub-label">Edukasi Kepada:</span> {{ $data->edukasi ?? 'Pasien' }} ({{ $data->ket_edukasi ?? '-' }})
                </td>
            </tr>
        </table>

        <!-- V. RESIKO JATUH & VI. SKRINING GIZI -->
        <div class="section-title">V. PENILAIAN RESIKO JATUH & VI. SKRINING GIZI</div>
        <table class="table-custom">
            <tr>
                <td style="width: 55%;">
                    <span class="sub-label">Penilaian Resiko Jatuh (Get Up & Go):</span><br>
                    1. Berjalan limbung/sempoyongan: <b>{{ $data->berjalan_a ?? 'Tidak' }}</b><br>
                    2. Menggunakan alat bantu: <b>{{ $data->berjalan_b ?? 'Tidak' }}</b><br>
                    3. Menopang saat duduk: <b>{{ $data->berjalan_c ?? 'Tidak' }}</b><br>
                    <b>Hasil: {{ $data->hasil ?? 'Tidak beresiko' }}</b><br>
                    Dilaporkan ke Dokter: <b>{{ $data->lapor ?? 'Tidak' }}</b> (Jam: {{ $data->ket_lapor ?? '-' }})
                </td>
                <td style="width: 45%;">
                    <span class="sub-label">Skrining Gizi (MST):</span><br>
                    1. Penurunan BB 6 bulan terakhir: <b>{{ $data->sg1 ?? 'Tidak' }}</b> (Skor: {{ $data->nilai1 ?? '0' }})<br>
                    2. Asupan makan berkurang: <b>{{ $data->sg2 ?? 'Tidak' }}</b> (Skor: {{ $data->nilai2 ?? '0' }})<br>
                    <b>Total Skor Gizi: {{ $data->total_hasil ?? '0' }}</b>
                    ({{ (int)($data->total_hasil ?? 0) >= 2 ? 'Resiko Malnutrisi' : 'Resiko Rendah' }})
                </td>
            </tr>
        </table>

        <!-- VII. PENILAIAN TINGKAT NYERI -->
        <div class="section-title">VII. PENILAIAN TINGKAT NYERI (PQRST)</div>
        <table class="table-custom">
            <tr>
                <td style="width: 25%;">
                    <span class="sub-label">Status Nyeri:</span><br>
                    <b>{{ $data->nyeri ?? 'Tidak Ada Nyeri' }}</b><br>
                    Skala: <b>{{ $data->skala_nyeri ?? '0' }} / 10</b>
                </td>
                <td style="width: 40%;">
                    - Lokasi: <b>{{ $data->lokasi ?? '-' }}</b><br>
                    - Penyebab (Provokes): <b>{{ $data->provokes ?? '-' }}</b> ({{ $data->ket_provokes ?? '-' }})<br>
                    - Kualitas (Quality): <b>{{ $data->quality ?? '-' }}</b> ({{ $data->ket_quality ?? '-' }})
                </td>
                <td style="width: 35%;">
                    - Nyeri Menyebar: <b>{{ $data->menyebar ?? 'Tidak' }}</b><br>
                    - Durasi: <b>{{ $data->durasi ?? '-' }}</b><br>
                    - Hilang Dengan: <b>{{ $data->nyeri_hilang ?? 'Istirahat' }}</b><br>
                    - Diberitahukan ke dr: <b>{{ $data->pada_dokter ?? 'Tidak' }}</b> (Jam: {{ $data->ket_dokter ?? '-' }})
                </td>
            </tr>
        </table>

        <!-- VIII. ASUHAN KEPERAWATAN (MASALAH & RENCANA) -->
        <div class="section-title">VIII. ASUHAN KEPERAWATAN (MASALAH & RENCANA)</div>
        <table class="table-custom">
            <tr>
                <td style="width: 50%;">
                    <span class="sub-label">Masalah Keperawatan:</span>
                    @if ($data->masalah && count($data->masalah) > 0)
                        <ul style="margin: 3px 0 0 15px; padding: 0;">
                            @foreach ($data->masalah as $m)
                                <li><b>[{{ $m->kode_masalah }}]</b> {{ $m->masterMasalah->nama_masalah ?? $m->kode_masalah }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="color: #666; font-style: italic; margin-top: 3px;">Tidak ada masalah keperawatan yang dipilih.</div>
                    @endif
                </td>
                <td style="width: 50%;">
                    <span class="sub-label">Rencana Keperawatan:</span>
                    @if ($data->rencanaKeperawatan && count($data->rencanaKeperawatan) > 0)
                        <ul style="margin: 3px 0 0 15px; padding: 0;">
                            @foreach ($data->rencanaKeperawatan as $r)
                                <li><b>[{{ $r->kode_rencana }}]</b> {{ $r->masterRencana->rencana_keperawatan ?? $r->kode_rencana }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div style="color: #666; font-style: italic; margin-top: 3px;">Tidak ada rencana keperawatan yang dipilih.</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="sub-label">Tindakan / Rencana Tambahan:</span><br>
                    <div style="white-space: pre-line; margin-top: 3px;">{{ $data->rencana && $data->rencana !== '-' ? $data->rencana : '-' }}</div>
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan -->
        <table width="100%" class="table-noborder" style="margin-top: 15px;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: center;">
                    <div>{{ Carbon\Carbon::parse($data->tanggal ?? date('Y-m-d'))->translatedFormat('d F Y') }}</div>
                    <div style="margin-bottom: 50px;">Perawat / Petugas Pengkaji,</div>
                    <div><b><u>{{ $data->pegawai->nama ?? ($data->petugas->nama ?? ($data->nip ?? 'Perawat')) }}</u></b></div>
                    <div style="font-size: 10px; color: #555;">NIP: {{ $data->nip ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endsection

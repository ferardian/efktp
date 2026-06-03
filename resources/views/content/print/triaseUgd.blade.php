@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
    $bodyMapPath = public_path('img/body_map.png');
    $bodyMapBase64 = '';
    if (file_exists($bodyMapPath)) {
        $bodyMapBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($bodyMapPath));
    }
    
    $survey = $data->survey_primer ?? [];
    if (is_string($survey)) {
        $survey = json_decode($survey, true) ?? [];
    }

    if (!function_exists('renderChecked')) {
        function renderChecked($survey, $kategori, $sub, $value, $label) {
            $checked = isset($survey[$kategori][$sub]) && in_array($value, $survey[$kategori][$sub]);
            if ($checked) {
                return '<div style="font-weight: bold; color: #111; margin-bottom: 2px;">[✓] <strong>' . $label . '</strong></div>';
            } else {
                return '<div style="color: #777; margin-bottom: 2px;">[ ] ' . $label . '</div>';
            }
        }
    }

    $points = $data->body_map_points ?? [];
    if (is_string($points)) {
        $points = json_decode($points, true) ?? [];
    }
@endphp
@section('content')
    <style>
        @page {
            margin: 115px 20px 50px 20px;
        }
        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 90px;
        }
        footer {
            position: fixed;
            bottom: -35px;
            left: 0px;
            right: 0px;
            height: 15px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 3px;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
        table {
            font-size: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        .subtitle {
            font-size: 9px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .section-title {
            background-color: #343a40;
            color: #ffffff;
            font-weight: bold;
            padding: 4px 8px;
            margin-top: 8px;
            margin-bottom: 4px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .table-data {
            border: 1px solid #000;
            width: 100%;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        .table-data th {
            background-color: #f9f9f9;
        }
        .text-pre {
            white-space: pre-wrap;
            font-family: inherit;
            margin: 0;
        }
        .no-border td {
            border: none !important;
            padding: 2px 0px;
        }
        
        /* Triage Grid Styling */
        .triage-grid {
            width: 100%;
            border: 1px solid #000;
            table-layout: fixed;
        }
        .triage-grid th, .triage-grid td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            font-size: 9px;
        }
        .triage-grid th {
            text-align: center;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            height: 30px;
        }
        .bg-k1 { background-color: #dc3545 !important; } /* Resusitasi Red */
        .bg-k2 { background-color: #dc3545 !important; } /* Emergensi Red */
        .bg-k3 { background-color: #fd7e14 !important; color: #000 !important; } /* Urgensi Orange */
        .bg-k4 { background-color: #198754 !important; } /* Non Urgensi Green */
        
        .row-header {
            font-weight: bold;
            font-size: 8px;
            padding-bottom: 2px;
            margin-bottom: 3px;
            border-bottom: 1px solid #ccc;
            text-transform: uppercase;
        }
        .color-k1 { color: #dc3545; }
        .color-k2 { color: #dc3545; }
        .color-k3 { color: #e65100; }
        .color-k4 { color: #198754; }

        .scale-badge {
            display: inline-block;
            padding: 4px 10px;
            font-weight: bold;
            color: #fff;
            border-radius: 4px;
            text-align: center;
            font-size: 12px;
            margin-top: 3px;
        }
    </style>

    <footer>
        <span class="page-number"></span>
    </footer>

    <header>
        <!-- Header Instansi -->
        <table class="header-table" width="100%">
            <tr>
                <td width="12%">
                    @if($setting && $setting->logo)
                        <img src="{{ 'data:image/jpeg;base64,' . base64_encode($setting->logo) }}" alt="" width="55px">
                    @endif
                </td>
                <td width="53%" style="text-align: left;">
                    <div style="font-size: 14px; font-weight: bold; text-transform: uppercase;">{{ $setting->nama_instansi ?? 'KLINIK KESEHATAN' }}</div>
                    <div class="subtitle">
                        {{ $setting->alamat_instansi ?? '' }}, {{ $setting->kabupaten ?? '' }}, {{ $setting->propinsi ?? '' }}<br/>
                        Telp. {{ $setting->kontak ?? '' }}, Email: {{ $setting->email ?? '' }}
                    </div>
                </td>
                <td width="35%" style="text-align: right; vertical-align: top;">
                    <div style="font-size: 11px; font-weight: bold; border: 1.5px solid #000; padding: 5px; text-align: center; border-radius: 4px; background-color: #fdfdfd; margin-top: 5px;">
                        ASESMEN TRIASE PASIEN UGD
                    </div>
                </td>
            </tr>
        </table>
        
        <hr style="border: 0; border-top: 2px double #000; margin-top: 5px; margin-bottom: 0px;"/>
    </header>

    <div class="container">
        <!-- Demografi Pasien -->
        <table width="100%" class="table-data" style="margin-bottom: 8px;">
            <tr>
                <td width="16%"><strong>Nama Pasien</strong></td>
                <td width="34%">: {{ $data->regPeriksa->pasien->nm_pasien ?? '-' }}</td>
                <td width="16%"><strong>No. Rawat</strong></td>
                <td width="34%">: {{ $data->no_rawat }}</td>
            </tr>
            <tr>
                <td><strong>No. Rekam Medis</strong></td>
                <td>: {{ $data->regPeriksa->no_rkm_medis ?? '-' }}</td>
                <td><strong>Tgl & Jam Triase</strong></td>
                <td>: {{ $data->tgl_triase ? Carbon\Carbon::parse($data->tgl_triase)->translatedFormat('d F Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Umur / JK</strong></td>
                <td>: {{ $data->regPeriksa->umurdaftar ?? '-' }} {{ $data->regPeriksa->sttsumur ?? '' }} / {{ ($data->regPeriksa->pasien->jk ?? '') == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                <td><strong>Petugas Triase</strong></td>
                <td>: {{ $data->petugas->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Alamat Pasien</strong></td>
                <td>: {{ $data->regPeriksa->pasien->alamat ?? '-' }}, {{ $data->regPeriksa->pasien->kel->nm_kel ?? '' }}, {{ $data->regPeriksa->pasien->kec->nm_kec ?? '' }}</td>
                <td><strong>Status Rujukan</strong></td>
                <td>: {{ $data->rujukan ?? 'Tidak' }} {{ ($data->rujukan ?? '') == 'Ya' ? '(Dari: ' . ($data->rujukan_dari ?? '-') . ')' : '' }}</td>
            </tr>
        </table>

        <!-- Keluhan Utama -->
        <table width="100%" class="table-data" style="margin-bottom: 8px;">
            <tr>
                <td style="background-color: #f5f5f5;"><strong>KELUHAN UTAMA:</strong></td>
            </tr>
            <tr>
                <td><div class="text-pre" style="min-height: 25px;">{{ $data->keluhan_utama ?? '-' }}</div></td>
            </tr>
        </table>

        <!-- Survey Primer Grid -->
        <div class="section-title">SURVEY PRIMER & KATEGORI TRIASE</div>
        <table class="triage-grid">
            <thead>
                <tr>
                    <th class="bg-k1 w-25">KATEGORI 1<br>RESUSITASI</th>
                    <th class="bg-k2 w-25">KATEGORI 2<br>EMERGENSI</th>
                    <th class="bg-k3 w-25">KATEGORI 3<br>URGENSI</th>
                    <th class="bg-k4 w-25">KATEGORI 4<br>NON URGENSI</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1: Respon Awal -->
                <tr>
                    <td>
                        <div class="row-header color-k1">Respon Awal</div>
                        {!! renderChecked($survey, 'k1', 'respon', 'tidak_ada_respon', 'Tidak ada respon') !!}
                        {!! renderChecked($survey, 'k1', 'respon', 'merespon_nyeri', 'Merespon nyeri') !!}
                        {!! renderChecked($survey, 'k1', 'respon', 'kejang', 'Kejang') !!}
                    </td>
                    <td>
                        <div class="row-header color-k2">Respon Awal</div>
                        {!! renderChecked($survey, 'k2', 'respon', 'merespon_suara', 'Merespon suara') !!}
                    </td>
                    <td>
                        <div class="row-header color-k3">Respon Awal</div>
                        {!! renderChecked($survey, 'k3', 'respon', 'sadar', 'Sadar') !!}
                        {!! renderChecked($survey, 'k3', 'respon', 'ku_lemah', 'K/U Lemah') !!}
                    </td>
                    <td>
                        <div class="row-header color-k4">Respon Awal</div>
                        {!! renderChecked($survey, 'k4', 'respon', 'sadar', 'Sadar') !!}
                        {!! renderChecked($survey, 'k4', 'respon', 'ku_baik', 'K/U Baik') !!}
                    </td>
                </tr>
                <!-- Row 2: Jalan Nafas -->
                <tr>
                    <td>
                        <div class="row-header color-k1">Jalan Nafas</div>
                        {!! renderChecked($survey, 'k1', 'nafas', 'obstruksi', 'Obstruksi') !!}
                    </td>
                    <td>
                        <div class="row-header color-k2">Jalan Nafas</div>
                        {!! renderChecked($survey, 'k2', 'nafas', 'ancaman_obstruksi', 'Ancaman obstruksi') !!}
                    </td>
                    <td>
                        <div class="row-header color-k3">Jalan Nafas</div>
                        {!! renderChecked($survey, 'k3', 'nafas', 'bebas', 'Bebas') !!}
                    </td>
                    <td>
                        <div class="row-header color-k4">Jalan Nafas</div>
                        {!! renderChecked($survey, 'k4', 'nafas', 'bebas', 'Bebas') !!}
                    </td>
                </tr>
                <!-- Row 3: Pernafasan -->
                <tr>
                    <td>
                        <div class="row-header color-k1">Pernafasan</div>
                        {!! renderChecked($survey, 'k1', 'pernafasan', 'henti_nafas', 'Henti nafas') !!}
                        {!! renderChecked($survey, 'k1', 'pernafasan', 'sesak_nafas_berat', 'Sesak nafas berat') !!}
                        {!! renderChecked($survey, 'k1', 'pernafasan', 'rr_kurang_10', 'RR < 10/mnt') !!}
                        {!! renderChecked($survey, 'k1', 'pernafasan', 'rr_lebih_32', 'RR > 32/mnt') !!}
                        {!! renderChecked($survey, 'k1', 'pernafasan', 'sianosis', 'Sianosis') !!}
                    </td>
                    <td>
                        <div class="row-header color-k2">Pernafasan</div>
                        {!! renderChecked($survey, 'k2', 'pernafasan', 'sesak_nafas', 'Sesak nafas') !!}
                        {!! renderChecked($survey, 'k2', 'pernafasan', 'frek_nafas_lebih_32', 'Frek. nafas > 32/mnt') !!}
                    </td>
                    <td>
                        <div class="row-header color-k3">Pernafasan</div>
                        {!! renderChecked($survey, 'k3', 'pernafasan', 'sesak_nafas', 'Sesak nafas') !!}
                        {!! renderChecked($survey, 'k3', 'pernafasan', 'frek_nafas_lebih_32', 'Frek. nafas > 32/mnt') !!}
                        {!! renderChecked($survey, 'k3', 'pernafasan', 'normal', 'Normal') !!}
                    </td>
                    <td>
                        <div class="row-header color-k4">Pernafasan</div>
                        {!! renderChecked($survey, 'k4', 'pernafasan', 'normal', 'Normal') !!}
                    </td>
                </tr>
                <!-- Row 4: Sirkulasi -->
                <tr>
                    <td>
                        <div class="row-header color-k1">Sirkulasi</div>
                        {!! renderChecked($survey, 'k1', 'sirkulasi', 'henti_jantung', 'Henti jantung') !!}
                        {!! renderChecked($survey, 'k1', 'sirkulasi', 'nadi_lemah', 'Nadi lemah') !!}
                        {!! renderChecked($survey, 'k1', 'sirkulasi', 'akral_dingin', 'Akral dingin') !!}
                        {!! renderChecked($survey, 'k1', 'sirkulasi', 'crt_lebih_2', 'CRT > 2detik') !!}
                    </td>
                    <td>
                        <div class="row-header color-k2">Sirkulasi</div>
                        {!! renderChecked($survey, 'k2', 'sirkulasi', 'nadi_irreguler', 'Nadi irreguler') !!}
                    </td>
                    <td>
                        <div class="row-header color-k3">Sirkulasi</div>
                        {!! renderChecked($survey, 'k3', 'sirkulasi', 'nadi_kuat', 'Nadi kuat') !!}
                    </td>
                    <td>
                        <div class="row-header color-k4">Sirkulasi</div>
                        {!! renderChecked($survey, 'k4', 'sirkulasi', 'nadi_kuat', 'Nadi kuat') !!}
                    </td>
                </tr>
                <!-- Row 5: Perhatian / Tindakan -->
                <tr>
                    <td style="background-color: #fafafa; color: #999; text-align: center; vertical-align: middle;">
                        <em>Tidak ada parameter</em>
                    </td>
                    <td>
                        <div class="row-header color-k2">Perhatian / Tindakan</div>
                        {!! renderChecked($survey, 'k2', 'tindakan', 'resiko_perburukan', 'Resiko perburukan') !!}
                        {!! renderChecked($survey, 'k2', 'tindakan', 'nyeri_berat', 'Nyeri berat') !!}
                        {!! renderChecked($survey, 'k2', 'tindakan', 'gg_psikis_berat', 'Gg. Psikis berat') !!}
                    </td>
                    <td>
                        <div class="row-header color-k3">Perhatian / Tindakan</div>
                        {!! renderChecked($survey, 'k3', 'tindakan', 'ada_lebih_2_tanda', 'Ada ≥ 2 tanda') !!}
                        {!! renderChecked($survey, 'k3', 'tindakan', 'problem_kompleks', 'Problem kompleks') !!}
                        {!! renderChecked($survey, 'k3', 'tindakan', 'klinis_stabil', 'Klinis stabil') !!}
                    </td>
                    <td>
                        <div class="row-header color-k4">Perhatian / Tindakan</div>
                        {!! renderChecked($survey, 'k4', 'tindakan', 'tidak_ada', 'Tidak ada') !!}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Hasil Keputusan Kategori Triase -->
        <table width="100%" style="margin-top: 5px; margin-bottom: 8px;">
            <tr>
                <td width="30%" style="vertical-align: middle;"><strong>KEPUTUSAN KATEGORI TRIASE:</strong></td>
                <td width="70%">
                    @php
                        $skala = $data->skala_triase ?? '';
                        $badgeClass = 'bg-k4';
                        if ($skala == 'Kategori 1') $badgeClass = 'bg-k1';
                        elseif ($skala == 'Kategori 2') $badgeClass = 'bg-k2';
                        elseif ($skala == 'Kategori 3') $badgeClass = 'bg-k3';
                    @endphp
                    <span class="scale-badge {{ $badgeClass }}">
                        {{ strtoupper($skala) }}
                    </span>
                </td>
            </tr>
        </table>

        <!-- Asesmen Sekunder (Nyeri, Resiko Jatuh, Luka/BodyMap) -->
        <div class="section-title">ASESMEN SEKUNDER PASIEN</div>
        <table width="100%" class="table-data">
            <tr>
                <!-- Asesmen Nyeri & Resiko Jatuh -->
                <td width="55%" style="padding: 0px; border: none;">
                    <table width="100%" class="table-data" style="border: none;">
                        <tr>
                            <td colspan="2" style="background-color: #f5f5f5; border-top: none; border-left: none; border-right: none;"><strong>1. Asesmen Nyeri & Resiko Jatuh</strong></td>
                        </tr>
                        <tr>
                            <td width="35%" style="border-left: none;">Skala Nyeri (0-10)</td>
                            <td style="border-right: none;">: 
                                <strong>{{ $data->skala_nyeri !== null ? $data->skala_nyeri . '/10' : '-' }}</strong>
                                @if($data->skala_nyeri !== null)
                                    @php
                                        $nyeriDesc = 'Tidak Nyeri';
                                        if($data->skala_nyeri >= 1 && $data->skala_nyeri <= 3) $nyeriDesc = 'Nyeri Ringan';
                                        elseif($data->skala_nyeri >= 4 && $data->skala_nyeri <= 5) $nyeriDesc = 'Nyeri Sedang';
                                        elseif($data->skala_nyeri >= 6 && $data->skala_nyeri <= 7) $nyeriDesc = 'Nyeri Berat';
                                        elseif($data->skala_nyeri >= 8 && $data->skala_nyeri <= 9) $nyeriDesc = 'Nyeri Sangat Berat';
                                        elseif($data->skala_nyeri == 10) $nyeriDesc = 'Nyeri Tidak Tertahankan';
                                    @endphp
                                    ({{ $nyeriDesc }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border-left: none;">Tipe Nyeri</td>
                            <td style="border-right: none;">: {{ $data->nyeri_tipe ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border-left: none;">Lokasi Nyeri</td>
                            <td style="border-right: none;">: {{ $data->nyeri_lokasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border-left: none;">Durasi Nyeri</td>
                            <td style="border-right: none;">: {{ $data->nyeri_durasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border-left: none; border-bottom: none;">Resiko Jatuh</td>
                            <td style="border-right: none; border-bottom: none;">: 
                                <strong>{{ $data->resiko_jatuh ?? '-' }}</strong>
                                @if($data->resiko_jatuh_skor !== null)
                                    (Skor: {{ $data->resiko_jatuh_skor }})
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                
                <!-- Luka & Body Map -->
                <td width="45%" style="padding: 5px; text-align: center; border-left: 1px solid #000; border-top: none; border-bottom: none; border-right: none;">
                    <strong>2. Lokasi Luka / Perdarahan (Body Map)</strong>
                    <div style="position: relative; width: 150px; height: 150px; border: 1px solid #ddd; margin: 5px auto; background-color: #fff;">
                        @if($bodyMapBase64)
                            <img src="{{ $bodyMapBase64 }}" style="width: 150px; height: 150px; object-fit: contain;">
                        @else
                            <div style="font-size: 8px; color: #999; padding-top: 60px;">Gambar Body Map tidak ditemukan</div>
                        @endif
                        @foreach ($points as $index => $pt)
                            @php
                                $x = round($pt['x'] * (150 / 320));
                                $y = round($pt['y'] * (150 / 320));
                            @endphp
                            <div style="position: absolute; left: {{ $x - 5 }}px; top: {{ $y - 5 }}px; width: 10px; height: 10px; background-color: #dc3545; border: 1px solid #fff; border-radius: 50%; color: #fff; font-size: 7px; font-weight: bold; text-align: center; line-height: 10px; z-index: 100;">
                                {{ $index + 1 }}
                            </div>
                        @endforeach
                    </div>
                    <div style="font-size: 8px; text-align: left; margin-top: 4px;">
                        <strong>Keterangan Luka:</strong><br>
                        <div class="text-pre">{{ $data->luka_perdarahan && trim($data->luka_perdarahan) != '' ? $data->luka_perdarahan : 'Tidak ada keterangan luka.' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Rencana Tindak Lanjut & Tujuan Pelayanan -->
        <div class="section-title" style="margin-top: 5px;">RENCANA TINDAK LANJUT / DISPOSISI</div>
        <table width="100%" class="table-data" style="margin-bottom: 10px;">
            <tr>
                <td width="25%"><strong>Tujuan Pelayanan (RTL)</strong></td>
                <td width="75%">: {{ $data->rencana_tindak_lanjut ?? '-' }}</td>
            </tr>
            @if(($data->rencana_tindak_lanjut ?? '') == 'Rujuk')
            <tr>
                <td><strong>RS Rujukan Tujuan</strong></td>
                <td>: {{ $data->rujuk_tujuan ?? '-' }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Keputusan Jam Pelayanan</strong></td>
                <td>: {{ $data->keputusan_jam ? substr($data->keputusan_jam, 0, 5) . ' WIB' : '-' }}</td>
            </tr>
        </table>

        <!-- Tanda Tangan & Verifikasi -->
        <table width="100%" style="margin-top: 15px; border: none;">
            <tr class="no-border">
                <td width="60%"></td>
                <td width="40%" style="text-align: center; font-size: 10px;">
                    <p>{{ $setting->kabupaten ?? 'Kabupaten' }}, {{ $data->tgl_triase ? Carbon\Carbon::parse($data->tgl_triase)->translatedFormat('d F Y') : '-' }}</p>
                    <p style="margin-bottom: 5px;">Petugas Triase UGD,</p>
                    
                    <div style="margin: 6px auto;">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Asesmen Triase UGD Pasien ' . ($data->regPeriksa->pasien->nm_pasien ?? '-') . ' No. Rawat ' . $data->no_rawat . ' diverifikasi oleh petugas ' . ($data->petugas->nama ?? '-'), 'QRCODE') }}" height="60" width="60" />
                    </div>
                    
                    <p style="font-weight: bold; text-decoration: underline; margin-top: 5px;">{{ $data->petugas->nama ?? '-' }}</p>
                    <p class="subtitle" style="margin-top: 1px;">NIP: {{ $data->nip ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>
@endsection

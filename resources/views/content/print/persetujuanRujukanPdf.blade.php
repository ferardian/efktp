@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
    $isSetuju = ($data->jenis === 'Persetujuan');
@endphp

@section('content')
<div style="font-size: 11px; font-family: sans-serif; line-height: 1.35;">
    <!-- KOP SURAT -->
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 15px; font-weight: bold;">{!! nl2br(e(str_replace('|', "\n", $setting->nama_instansi ?? 'KLINIK / FASILITAS KESEHATAN TINGKAT PERTAMA'))) !!}</h3>
        <p style="margin: 2px 0; font-size: 10.5px;">{!! nl2br(e(str_replace('|', "\n", $setting->alamat_instansi ?? ''))) !!}</p>
        <p style="margin: 0; font-size: 10px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <!-- JUDUL -->
    <div style="text-align: center; margin-bottom: 14px;">
        <h4 style="margin: 0; font-size: 13px; font-weight: bold; text-decoration: underline;">
            FORMULIR PEMBERIAN INFORMASI & {{ strtoupper($data->jenis) }} RUJUKAN
        </h4>
        <p style="margin: 3px 0 0 0; font-size: 10px;">
            Nomor Surat: <strong>{{ $data->no_surat }}</strong> &nbsp;|&nbsp; 
            No. Rawat: <strong>{{ $data->no_rawat }}</strong> &nbsp;|&nbsp;
            Tanggal: <strong>{{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y, H:i') }} WIB</strong>
        </p>
    </div>

    <!-- DATA IDENTITAS PASIEN -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;">
        <tr>
            <td style="width: 18%; font-weight: bold;">Nama Pasien</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;"><strong>{{ $data->regPeriksa->pasien->nm_pasien ?? '-' }}</strong></td>
            <td style="width: 18%; font-weight: bold;">No. Rekam Medis</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;"><strong>{{ $data->regPeriksa->no_rkm_medis ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tgl. Lahir / JK</td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($data->regPeriksa->pasien->tgl_lahir ?? now())->translatedFormat('d F Y') }} / {{ ($data->regPeriksa->pasien->jk ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td style="font-weight: bold;">Unit / Poli Asal</td>
            <td>:</td>
            <td>{{ $data->regPeriksa->poliklinik->nm_poli ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dokter Perujuk</td>
            <td>:</td>
            <td>{{ $data->dokter->nm_dokter ?? '-' }} ({{ $data->kd_dokter }})</td>
            <td style="font-weight: bold;">Petugas / Nakes</td>
            <td>:</td>
            <td>{{ $data->petugas->nama ?? ($data->nip ?: '-') }}</td>
        </tr>
    </table>

    <!-- TABEL EDUKASI & INFORMASI RUJUKAN -->
    <div style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">A. PEMBERIAN EDUKASI & INFORMASI RUJUKAN</div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10.5px;" border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: center;">
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Materi Edukasi</th>
                <th style="width: 70%;">Penjelasan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; vertical-align: top;">1</td>
                <td style="vertical-align: top;"><strong>Diagnosis Medis</strong></td>
                <td>{{ $data->diagnosa ?: '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: top;">2</td>
                <td style="vertical-align: top;"><strong>Alasan Rujukan</strong></td>
                <td>{{ $data->alasan_rujuk ?: 'Memerlukan pemeriksaan penunjang/penanganan spesialistik lebih lanjut' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: top;">3</td>
                <td style="vertical-align: top;"><strong>Fasilitas Tujuan Rujukan</strong></td>
                <td>
                    <strong>{{ $data->faskes_tujuan }}</strong>
                    @if($data->bagian_tujuan && $data->bagian_tujuan !== '-')
                        (Poli/Bagian: {{ $data->bagian_tujuan }})
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: top;">4</td>
                <td style="vertical-align: top;"><strong>Moda Transportasi & Pendamping</strong></td>
                <td>Transportasi: <strong>{{ $data->transportasi }}</strong> &nbsp;|&nbsp; Pendamping: <strong>{{ $data->pendamping ?: '-' }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: top;">5</td>
                <td style="vertical-align: top;"><strong>Tindakan Stabilisasi Pra-Rujuk</strong></td>
                <td>{{ $data->tindakan_stabilisasi ?: '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: top;">6</td>
                <td style="vertical-align: top;"><strong>Risiko Selama Perjalanan</strong></td>
                <td>{{ $data->risiko_rujuk ?: 'Kemungkinan perubahan tanda vital/kondisi selama perjalanan rujukan' }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: top;">7</td>
                <td style="vertical-align: top;"><strong>Risiko Jika Tidak Dirujuk</strong></td>
                <td style="color: #b00; font-weight: 500;">{{ $data->risiko_tidak_rujuk ?: 'Komplikasi penyakit berlanjut, kegagalan terapi optimal, keterlambatan penanganan definitif, perburukan klinis hingga ancaman keselamatan jiwa' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- PERNYATAAN PERSETUJUAN / PENOLAKAN -->
    <div style="font-weight: bold; margin-bottom: 4px; font-size: 11px;">B. PERNYATAAN {{ strtoupper($data->jenis) }} RUJUKAN</div>
    <div style="border: 1px solid #777; padding: 8px; margin-bottom: 14px; background-color: {{ $isSetuju ? '#f9fdfa' : '#fff9f9' }}; border-radius: 4px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 10.5px; margin-bottom: 6px;">
            <tr>
                <td style="width: 25%;">Yang bertanda tangan di bawah ini</td>
                <td style="width: 2%;">:</td>
                <td style="width: 73%;"><strong>{{ $data->nama_pj }}</strong> ({{ $data->jk_pj === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $data->umur_pj }} Th)</td>
            </tr>
            <tr>
                <td>Hubungan dengan Pasien</td>
                <td>:</td>
                <td><strong>{{ $data->hubungan }}</strong></td>
            </tr>
            <tr>
                <td>Alamat / No. HP</td>
                <td>:</td>
                <td>{{ $data->alamat_pj }} / {{ $data->no_hp_pj ?: '-' }}</td>
            </tr>
            @if(!$isSetuju && $data->alasan_menolak)
            <tr>
                <td style="color: #a00; font-weight: bold;">Alasan Penolakan Rujukan</td>
                <td>:</td>
                <td style="color: #a00; font-weight: bold;">{!! nl2br(e($data->alasan_menolak)) !!}</td>
            </tr>
            @endif
        </table>

        <p style="margin: 6px 0 0 0; text-align: justify; font-size: 10.5px;">
            @if($isSetuju)
                Menyatakan dengan sesungguhnya dan penuh kesadaran bahwa saya telah menerima dan memahami seluruh penjelasan yang diberikan oleh dokter/petugas kesehatan mengenai indikasi, tujuan, risiko, serta tata cara rujukan pasien di atas, dan dengan ini memberikan <strong>PERSETUJUAN</strong> untuk dirujuk ke <strong>{{ $data->faskes_tujuan }}</strong>.
            @else
                Menyatakan dengan sesungguhnya bahwa setelah menerima penjelasan mengenai alasan rujukan dan risiko yang mungkin terjadi apabila tidak dirujuk, saya atas kehendak sendiri <strong>MENOLAK</strong> untuk dirujuk ke fasilitas kesehatan rujukan. Saya bertanggung jawab sepenuhnya atas segala risiko dan konsekuensi yang dapat timbul akibat penolakan ini tanpa menuntut pihak fasilitas kesehatan.
            @endif
        </p>
    </div>

    <!-- BLOK TANDA TANGAN -->
    <table style="width: 100%; text-align: center; border-collapse: collapse; font-size: 10.5px; margin-top: 5px;">
        <tr>
            <td style="width: 33%;">
                <p style="margin: 0 0 4px 0;">Dokter / Petugas Yang Merujuk,</p>
                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                    @if($data->ttd_dokter && file_exists(public_path($data->ttd_dokter)))
                        <img src="{{ asset($data->ttd_dokter) }}" style="max-height: 60px; max-width: 130px;" alt="TTD Dokter">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->dokter->nm_dokter ?? '-' }}</p>
                <p style="margin: 0; font-size: 9.5px;">NIP/SIP: {{ $data->kd_dokter }}</p>
            </td>
            <td style="width: 33%;">
                <p style="margin: 0 0 4px 0;">Yang Membuat Pernyataan,</p>
                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                    @if($data->ttd_penerima && file_exists(public_path($data->ttd_penerima)))
                        <img src="{{ asset($data->ttd_penerima) }}" style="max-height: 60px; max-width: 130px;" alt="TTD Pembuat Pernyataan">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->nama_pj }}</p>
                <p style="margin: 0; font-size: 9.5px;">(Pasien / {{ $data->hubungan }})</p>
            </td>
            <td style="width: 33%;">
                <p style="margin: 0 0 4px 0;">Saksi Keluarga / Pihak Pasien,</p>
                <div style="height: 65px; display: flex; align-items: center; justify-content: center;">
                    @if($data->ttd_saksi && file_exists(public_path($data->ttd_saksi)))
                        <img src="{{ asset($data->ttd_saksi) }}" style="max-height: 60px; max-width: 130px;" alt="TTD Saksi">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->nama_saksi ?: '-' }}</p>
                <p style="margin: 0; font-size: 9.5px;">(Saksi)</p>
            </td>
        </tr>
    </table>
</div>
@endsection

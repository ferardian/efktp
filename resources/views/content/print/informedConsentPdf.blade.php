@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
    $isSetuju = ($data->pernyataan === 'Persetujuan');
@endphp

@section('content')
<div style="font-size: 11px; font-family: sans-serif; line-height: 1.3;">
    <!-- KOP SURAT -->
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px;">
        <h3 style="margin: 0; font-size: 15px; font-weight: bold;">{!! nl2br(e(str_replace('|', "\n", $setting->nama_instansi ?? 'KLINIK / RUMAH SAKIT'))) !!}</h3>
        <p style="margin: 2px 0;">{!! nl2br(e(str_replace('|', "\n", $setting->alamat_instansi ?? ''))) !!}</p>
        <p style="margin: 0; font-size: 10px;">Telp: {{ $setting->kontak ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
    </div>

    <!-- JUDUL -->
    <div style="text-align: center; margin-bottom: 15px;">
        <h4 style="margin: 0; font-size: 13px; font-weight: bold; text-decoration: underline;">
            FORMULIR {{ strtoupper($data->pernyataan) }} TINDAKAN KEDOKTERAN
        </h4>
        <p style="margin: 2px 0; font-size: 10px;">Nomor: <strong>{{ $data->no_pernyataan }}</strong> | No. Rawat: <strong>{{ $data->no_rawat }}</strong></p>
    </div>

    <!-- DATA PASIEN -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 18%; font-weight: bold;">Nama Pasien</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $data->regPeriksa->pasien->nm_pasien ?? '-' }}</td>
            <td style="width: 18%; font-weight: bold;">No. Rekam Medis</td>
            <td style="width: 2%;">:</td>
            <td style="width: 30%;">{{ $data->regPeriksa->no_rkm_medis ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tgl. Lahir / JK</td>
            <td>:</td>
            <td>{{ Carbon\Carbon::parse($data->regPeriksa->pasien->tgl_lahir ?? now())->translatedFormat('d F Y') }} / {{ ($data->regPeriksa->pasien->jk ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td style="font-weight: bold;">Unit / Poliklinik</td>
            <td>:</td>
            <td>{{ $data->regPeriksa->poliklinik->nm_poli ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dokter Pelaksana</td>
            <td>:</td>
            <td colspan="4">{{ $data->dokter->nm_dokter ?? '-' }} ({{ $data->kd_dokter }})</td>
        </tr>
    </table>

    <!-- TABEL EDUKASI TINDAKAN -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10.5px;" border="1" cellpadding="4">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: center;">
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Jenis Informasi</th>
                <th style="width: 55%;">Isi Informasi / Penjelasan</th>
                <th style="width: 15%;">Tanda Konfirmasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td><strong>Diagnosis (WD / DD)</strong></td>
                <td>{{ $data->diagnosa }}</td>
                <td style="text-align: center;">{{ $data->diagnosa_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td><strong>Dasar Diagnosis / Indikasi</strong></td>
                <td>{{ $data->indikasi_tindakan }}</td>
                <td style="text-align: center;">{{ $data->indikasi_tindakan_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td><strong>Tindakan Kedokteran</strong></td>
                <td><strong>{{ $data->tindakan }}</strong></td>
                <td style="text-align: center;">{{ $data->tindakan_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td><strong>Tata Cara Tindakan</strong></td>
                <td>{!! nl2br(e($data->tata_cara)) !!}</td>
                <td style="text-align: center;">{{ $data->tata_cara_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td><strong>Tujuan Tindakan</strong></td>
                <td>{{ $data->tujuan }}</td>
                <td style="text-align: center;">{{ $data->tujuan_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td><strong>Risiko Tindakan</strong></td>
                <td>{{ $data->risiko }}</td>
                <td style="text-align: center;">{{ $data->risiko_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td><strong>Komplikasi</strong></td>
                <td>{{ $data->komplikasi }}</td>
                <td style="text-align: center;">{{ $data->komplikasi_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td><strong>Prognosis</strong></td>
                <td>{{ $data->prognosis }}</td>
                <td style="text-align: center;">{{ $data->prognosis_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td><strong>Alternatif & Risikonya</strong></td>
                <td>{{ $data->alternatif_dan_risikonya }}</td>
                <td style="text-align: center;">{{ $data->alternatif_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">10</td>
                <td><strong>Perkiraan Biaya</strong></td>
                <td>Rp {{ number_format($data->biaya ?? 0, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $data->biaya_konfirmasi === 'true' ? '✓ Telah Paham' : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- PERNYATAAN KELUARGA / PASIEN -->
    <div style="border: 1px solid #333; padding: 8px; margin-bottom: 15px; background-color: #fafafa;">
        <p style="margin: 0 0 5px 0;">Yang bertanda tangan di bawah ini:</p>
        <table style="width: 100%; border-collapse: collapse; font-size: 10.5px;">
            <tr>
                <td style="width: 25%;">Nama Penerima Informasi</td>
                <td style="width: 2%;">:</td>
                <td style="width: 73%;"><strong>{{ $data->penerima_informasi }}</strong> ({{ $data->jk_penerima_informasi === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $data->umur_penerima_informasi }} Th)</td>
            </tr>
            <tr>
                <td>Hubungan dengan Pasien</td>
                <td>:</td>
                <td>{{ $data->hubungan_penerima_informasi }} @if($data->alasan_diwakilkan_penerima_informasi && $data->alasan_diwakilkan_penerima_informasi !== '-') (Alasan diwakilkan: {{ $data->alasan_diwakilkan_penerima_informasi }}) @endif</td>
            </tr>
            <tr>
                <td>Alamat / No. HP</td>
                <td>:</td>
                <td>{{ $data->alamat_penerima_informasi }} / {{ $data->no_hp }}</td>
            </tr>
        </table>
        <p style="margin: 8px 0 0 0; text-align: justify;">
            Dengan ini menyatakan <strong>{{ strtoupper($data->pernyataan) }}</strong> untuk dilakukan tindakan medis sebagaimana telah dijelaskan di atas. Saya telah memahami sepenuhnya penjelasan yang diberikan oleh dokter mengenai tujuan, manfaat, risiko, dan kemungkinan komplikasi yang dapat terjadi.
        </p>
    </div>

    <!-- AREA TANDA TANGAN -->
    <table style="width: 100%; text-align: center; border-collapse: collapse; font-size: 10.5px;">
        <tr>
            <td style="width: 33%;">
                <p style="margin: 0 0 5px 0;">Dokter Penanggung Jawab,</p>
                <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                    <br><br><br>
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->dokter->nm_dokter ?? '-' }}</p>
                <p style="margin: 0; font-size: 9px;">SIP: {{ $data->kd_dokter }}</p>
            </td>
            <td style="width: 33%;">
                <p style="margin: 0 0 5px 0;">Yang Menyatakan,</p>
                <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                    @if($data->buktiPenerimaInformasi && $data->buktiPenerimaInformasi->photo && file_exists(public_path($data->buktiPenerimaInformasi->photo)))
                        <img src="{{ asset($data->buktiPenerimaInformasi->photo) }}" style="max-height: 65px; max-width: 140px;" alt="TTD Penerima">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->penerima_informasi }}</p>
                <p style="margin: 0; font-size: 9px;">(Pasien / Keluarga / Wali)</p>
            </td>
            <td style="width: 33%;">
                <p style="margin: 0 0 5px 0;">Saksi Keluarga / Pihak Pasien,</p>
                <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                    @if($data->buktiSaksiKeluarga && $data->buktiSaksiKeluarga->photo && file_exists(public_path($data->buktiSaksiKeluarga->photo)))
                        <img src="{{ asset($data->buktiSaksiKeluarga->photo) }}" style="max-height: 65px; max-width: 140px;" alt="TTD Saksi">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $data->saksi_keluarga ?: '-' }}</p>
                <p style="margin: 0; font-size: 9px;">(Saksi)</p>
            </td>
        </tr>
    </table>
</div>
@endsection

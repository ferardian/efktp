@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div style="font-size: 11px; margin: 5px 20px;">
        <table width="100%">
            <tr>
                <td width="55%" style="vertical-align: top;">
                    <img src="{{ asset('img/logo-bpjs.png') }}" alt="" width="170px" style="margin-top: 2px;" />
                </td>
                <td width="45%" style="text-align: right; vertical-align: top;">
                    <p style="font-size: 11px; margin: 0;"><strong>Divisi Regional : {{ !empty($data['detail']['nmKR']) && $data['detail']['nmKR'] !== '-' ? $data['detail']['nmKR'] : ($setting['propinsi'] ?? '-') }}</strong></p>
                    <p style="font-size: 11px; margin: 0;"><strong>Kantor Cabang : {{ !empty($data['detail']['nmKC']) && $data['detail']['nmKC'] !== '-' ? $data['detail']['nmKC'] : ($setting['kabupaten'] ?? '-') }}</strong></p>
                </td>
            </tr>
        </table>
        
        <h3 style="text-align:center; font-size: 15px; margin: 4px 0;">Surat Rujukan FKTP</h3>
        
        <div style="border: 1px solid #000; padding: 6px;">
            <div style="border: 1px solid #000; padding: 6px;">
                <table width="100%">
                    <tr>
                        <td width="15%">No. Rujukan</td>
                        <td width="2%">:</td>
                        <td width="48%"><strong>{{ $data['noKunjungan'] }}</strong></td>
                        <td width="35%" rowspan="3" style="text-align: right; vertical-align: middle;">
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data['noKunjungan'], 'C39E') }}" height="32" width="180" />
                        </td>
                    </tr>
                    <tr>
                        <td>FKTP</td>
                        <td>:</td>
                        <td>{{ !empty($data['detail']['nmPpkAsal']) && $data['detail']['nmPpkAsal'] !== '-' ? $data['detail']['nmPpkAsal'] : ($setting['nama_instansi'] ?? '-') }}{{ !empty($data['detail']['kdPpkAsal']) ? ' ('.$data['detail']['kdPpkAsal'].')' : '' }}</td>
                    </tr>
                    <tr>
                        <td>Kabupaten / Kota</td>
                        <td>:</td>
                        <td>{{ !empty($data['detail']['nmKC']) && $data['detail']['nmKC'] !== '-' ? $data['detail']['nmKC'] : ($setting['kabupaten'] ?? '-') }}{{ !empty($data['detail']['kdKC']) ? ' ('.$data['detail']['kdKC'].')' : '' }}</td>
                    </tr>
                </table>
            </div>
            
            <table width="100%" style="margin-top: 4px;">
                <tr>
                    <td width="16%">Kepada Yth. TS Dokter</td>
                    <td width="2%">:</td>
                    <td>{{ $data['nmSubSpesialis'] ?? $data['nmPoli'] }}</td>
                </tr>
                <tr>
                    <td>Di</td>
                    <td>:</td>
                    <td>{{ $data['nmPPK'] }}</td>
                </tr>
            </table>
            
            <p style="margin: 4px 0 2px 0;">Mohon pemeriksaan dan penanganan lebih lanjut pasien : </p>
            
            <table width="100%" class="table" style="vertical-align: top; line-height: 1.25;">
                <tr>
                    <td width="16%">Nama</td>
                    <td width="2%">:</td>
                    <td width="38%"><strong>{{ $data['nm_pasien'] }}</strong></td>
                    <td width="10%">Umur</td>
                    <td width="2%">:</td>
                    <td width="32%">{{ $data['reg_periksa']['umurdaftar'] ?? '-' }} Tahun : {{ !empty($data['pasien']['tgl_lahir']) ? date('d-M-Y', strtotime($data['pasien']['tgl_lahir'])) : '-' }}</td>
                </tr>
                <tr>
                    <td>No. Kartu BPJS</td>
                    <td>:</td>
                    <td>{{ $data['noKartu'] }}</td>
                    <td>Status</td>
                    <td>:</td>
                    <td>[ 1 ] Utama/Tanggungan &nbsp;&nbsp; [ {{ $data['pasien']['jk'] ?? 'L' }} ] (L / P)</td>
                </tr>
                <tr>
                    <td>Diagnosa</td>
                    <td>:</td>
                    <td colspan="4">{{ $data['nmDiag1'] }} ({{ $data['kdDiag1'] }})</td>
                </tr>
                <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td colspan="4">{{ $data['detail']['catatanRujuk'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Telah diberikan</td>
                    <td>:</td>
                    <td colspan="4">{{ $data['terapi'] ?? '-' }}</td>
                </tr>
            </table>
            
            <p style="margin: 4px 0 8px 0;">Atas bantuannya, diucapkan terima kasih</p>
            
            <table width="100%" style="margin-top: 2px;">
                <tr>
                    <td width="65%" style="vertical-align: top; line-height: 1.25;">
                        <p>Tgl. Rencana Berkunjung : <strong>{{ !empty($data['tglEstRujuk']) ? date('d-M-Y', strtotime($data['tglEstRujuk'])) : '-' }}</strong></p>
                        <p>Jadwal Praktek : <strong>{{ !empty($data['detail']['jadwal']) && $data['detail']['jadwal'] !== '-' ? $data['detail']['jadwal'] : (!empty($data['jadwal']) && $data['jadwal'] !== '-' ? $data['jadwal'] : 'Setiap Hari Kerja') }}</strong></p>
                        <p>Surat rujukan berlaku 1[satu] kali kunjungan, berlaku sampai dengan : <strong>{{ !empty($data['detail']['tglAkhirRujuk']) && $data['detail']['tglAkhirRujuk'] !== '-' ? date('d-M-Y', strtotime($data['detail']['tglAkhirRujuk'])) : date('d-M-Y', strtotime('+89 days', strtotime($data['tglEstRujuk'] ?? date('Y-m-d')))) }}</strong></p>
                    </td>
                    <td width="35%" style="text-align: right; vertical-align: top;">
                        <p style="margin-bottom: 35px;">Salam sejawat,<br/>{{ date('d F Y') }}</p>
                        <p><b><u>{{ $data['nmDokter'] }}</u></b></p>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 6px; border-top: 1px dotted #888; padding-top: 3px; font-size: 10px;">
                Info Denda : {{ $data['detail']['infoDenda'] ?? '-' }}
            </div>
        </div>
    </div>
@endsection

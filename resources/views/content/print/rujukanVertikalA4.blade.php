@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div width="100%" style="margin:10px;font-size:19px">
        <table width="100%">
            <tr>
                <td width="50%" style="padding-right: 10px">
                    <img src="{{ asset('img/logo-bpjs.png') }}" alt="" width="350" style="margin-top:15px:top:0" />
                </td>
                <td width="50%">
                    <h3 style="margin-bottom:0px;margin-top:0px">Divisi Regional : {{ !empty($data['detail']['nmKR']) && $data['detail']['nmKR'] !== '-' ? $data['detail']['nmKR'] : ($setting['propinsi'] ?? '-') }}</h3>
                    <h3 style="margin-bottom:0px;margin-top:0px">Kantor Cabang : {{ !empty($data['detail']['nmKC']) && $data['detail']['nmKC'] !== '-' ? $data['detail']['nmKC'] : ($setting['kabupaten'] ?? '-') }}</h3>
                </td>
            </tr>
        </table>
        <img style="position: absolute;top:150px;right:40px" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data['noKunjungan'], 'C39E') }}" height="50" width="350" />
        <h2 style="text-align:center" class="m-2">Surat Rujukan FKTP</h2>
        <div style="border:1px solid; padding:20px">
            <div style="border:1px solid; padding:10px;">
                <table>
                    <tr>
                        <td>No. Rujukan</td>
                        <td>:</td>
                        <td>{{ $data['noKunjungan'] }}</td>
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
            <table class="mt-2">
                <tr>
                    <td>Kepada Yth. TS Dokter</td>
                    <td>:</td>
                    <td>{{ $data['nmSubSpesialis'] ?? $data['nmPoli'] }}</td>
                </tr>
                <tr>
                    <td>Di</td>
                    <td>:</td>
                    <td>{{ $data['nmPPK'] }}</td>
                </tr>
            </table>
            <p style="margin:10px">Mohon pemeriksaan dan penanganan lebih lanjut pasien : </p>
            <table width="100%" class="table" style="vertical-align: top;">
                <tr>
                    <td width="18%">Nama</td>
                    <td width="2%">:</td>
                    <td width="40%">{{ $data['nm_pasien'] }}</td>
                    <td width="10%">Umur</td>
                    <td width="2%">:</td>
                    <td width="28%">{{ $data['reg_periksa']['umurdaftar'] ?? '-' }} Tahun : {{ !empty($data['pasien']['tgl_lahir']) ? date('d-M-Y', strtotime($data['pasien']['tgl_lahir'])) : '-' }}</td>
                </tr>
                <tr>
                    <td>No. Kartu BPJS</td>
                    <td>:</td>
                    <td>{{ $data['noKartu'] }}</td>
                    <td>Status</td>
                    <td>:</td>
                    <td>[ 1 ] Utama/Tanggungan &nbsp;&nbsp;&nbsp;&nbsp; [ {{ $data['pasien']['jk'] ?? 'L' }} ] (L / P)</td>
                </tr>
                <tr>
                    <td>Diagnosa</td>
                    <td>:</td>
                    <td colspan="4">{{ $data['nmDiag1'] }} ({{ $data['kdDiag1'] }})</td>
                </tr>
                <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td colspan="4" style="vertical-align: bottom;">{{ $data['detail']['catatanRujuk'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Telah diberikan</td>
                    <td>:</td>
                    <td colspan="4">{{ $data['terapi'] ?? '-' }}</td>
                </tr>
            </table>
            <p style="margin:10px 10px 30px 10px">Atas bantuannya, diucapkan terima kasih</p>
            <table>
                <tr>
                    <td width="650px">
                        <p>
                            Tgl. Rencana Berkunjung : <strong>{{ !empty($data['tglEstRujuk']) ? date('d-M-Y', strtotime($data['tglEstRujuk'])) : '-' }}</strong>
                        </p>
                        <p>
                            Jadwal Praktek : <strong>{{ !empty($data['detail']['jadwal']) && $data['detail']['jadwal'] !== '-' ? $data['detail']['jadwal'] : (!empty($data['jadwal']) && $data['jadwal'] !== '-' ? $data['jadwal'] : 'Setiap Hari Kerja') }}</strong>
                        </p>
                        <p>
                            Surat rujukan berlaku 1[satu] kali kunjungan, berlaku sampai dengan : <strong>{{ !empty($data['detail']['tglAkhirRujuk']) && $data['detail']['tglAkhirRujuk'] !== '-' ? date('d-M-Y', strtotime($data['detail']['tglAkhirRujuk'])) : date('d-M-Y', strtotime('+89 days', strtotime($data['tglEstRujuk'] ?? date('Y-m-d')))) }}</strong>
                        </p>

                    </td>
                    <td style="text-align: center">
                        <p style="margin-bottom: 70px">Salam sejawat,<br/>{{ date('d F Y') }}</p>
                        <p class="mt-5"><b><u>{{ $data['nmDokter'] }}</u></b></p>
                    </td>
                </tr>
            </table>
            Info Denda : {{ $data['detail']['infoDenda'] ?? '-' }}
        </div>
    </div>
    {{-- @dd($data) --}}
    {{-- {{ print_r($data) }} --}}
@endsection

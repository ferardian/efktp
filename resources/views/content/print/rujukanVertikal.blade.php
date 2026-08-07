@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div width="100%" style="font-size: 12px;margin:20px">
        <table width="100%">
            <tr>
                <td width="60%" style="padding-right: 10px">
                    <img src="{{ asset('img/logo-bpjs.png') }}" alt="" width="200px" style="margin-top:15px:top:0" />
                </td>
                <td width="40%">
                    <p><strong>Divisi Regional : {{ !empty($data['detail']['nmKR']) && $data['detail']['nmKR'] !== '-' ? $data['detail']['nmKR'] : ($setting['propinsi'] ?? '-') }}</strong></p>
                    <p><strong>Kantor Cabang : {{ !empty($data['detail']['nmKC']) && $data['detail']['nmKC'] !== '-' ? $data['detail']['nmKC'] : ($setting['kabupaten'] ?? '-') }}</strong></p>
                </td>
            </tr>
        </table>
        <img style="position: absolute;top:122px;right:60px" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data['noKunjungan'], 'C39E') }}" height="35" width="200" />
        <h2 style="text-align:center">Surat Rujukan FKTP</h2>
        <div style="border:1px solid; padding:10px; margin:10px">
            <div style="border:1px solid; padding:10px">
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
            <table style="margin-top: 10px">
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
                    <td width="8%">Umur</td>
                    <td width="2%">:</td>
                    <td width="30%">{{ $data['reg_periksa']['umurdaftar'] ?? '-' }} Tahun : {{ !empty($data['pasien']['tgl_lahir']) ? date('d-M-Y', strtotime($data['pasien']['tgl_lahir'])) : '-' }}</td>
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
                    <td width="450px">
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
                        <p style="margin-bottom: 50px">Salam sejawat,<br/>{{ date('d F Y') }}</p>
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

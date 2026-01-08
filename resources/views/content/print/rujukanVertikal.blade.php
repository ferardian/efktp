@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div width="100%" style="font-size: 12px;margin:20px">
        <table width="100%">
            <tr>
                <td width="60%" style="padding-right: 10px">
                    <img src="{{ asset('/public/img/logo-bpjs.png') }}" alt="" width="200px" style="margin-top:15px:top:0" />
                </td>
                <td width="40%">
                    <p><strong>Divisi Regional : {{ $data['detail']['nmKR'] }}</strong></p>
                    <p><strong>Kantor Cabang : {{ $data['detail']['nmKC'] }}</strong></p>
                </td>
            </tr>
        </table>
        <img style="position: absolute;top:122px;right:60px" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data['noKunjungan'], 'C39E') }}" height="35" width="200" />
        <h2 style="text-align:center">Rujukan FKTP</h2>
        <div style="border:1px solid; padding:10px; margin:10px">
            <div style="border:1px solid; padding:10px">
                <table>
                    <tr>
                        <td>No Rujukan</td>
                        <td>:</td>
                        <td>{{ $data['noKunjungan'] }}</td>
                    </tr>
                    <tr>
                        <td>Puskesmas/Dokter Keluarga</td>
                        <td>:</td>
                        <td>{{ $data['detail']['nmPpkAsal'] }}</td>
                    </tr>
                    <tr>
                        <td>Kabupaten/Kota</td>
                        <td>:</td>
                        <td>{{ $data['detail']['nmKC'] }}</td>
                    </tr>
                </table>
            </div>
            <table style="margin-top: 10px">
                <tr>
                    <td>Kepada Yth. Dokter </td>
                    <td>:</td>
                    <td>{{ $data['nmPoli'] }}</td>
                </tr>
                <tr>
                    <td>Di</td>
                    <td>:</td>
                    <td>{{ $data['nmPPK'] }}</td>
                </tr>
            </table>
            <p style="margin:10px">Mohon pemeriksaan dan penanganan lebih lanjut kepada pasien : </p>
            <table width="100%" class="table" style="vertical-align: top;">
                <tr>
                    <td width="15%">Nama </td>
                    <td width="2%">:</td>
                    <td width="50%">{{ $data['nm_pasien'] }} ({{ $data['pasien']['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' }})</td>
                    <td width="5%">Umur</td>
                    <td width="2%">:</td>
                    <td width="20%">{{ $data['reg_periksa']['umurdaftar'] }} {{ $data['reg_periksa']['sttsumur'] }} / {{ Carbon\Carbon::parse($data['pasien']['tgl_lahir'])->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>No Kartu BPJS</td>
                    <td>:</td>
                    <td>{{ $data['noKartu'] }}</td>
                </tr>
                <tr>
                    <td>Diagnosa</td>
                    <td>:</td>
                    <td>{{ $data['kdDiag1'] }} - {{ $data['nmDiag1'] }}</td>
                </tr>
                <tr>
                    <td>Catatan</td>
                    <td>:</td>
                    <td style="vertical-align: bottom;">{{ $data['detail']['catatanRujuk'] }}</td>
                </tr>
            </table>
            <p style="margin:10px">Atas bantuannya, diucapkan terimakasih</p>
            <table>
                <tr>
                    <td width="400px">
                        <p>
                            Tgl Renc. Kunjungan : <strong>{{ date('d-m-Y', strtotime($data['tglEstRujuk'])) }}</strong>
                        </p>
                        <p>
                            Jadwal : <strong>{{ $data['detail']['jadwal'] }}</strong>
                        </p>
                        <p>
                            Surat rujukan berlaku satu kali kunjungan, berlaku sampai : <strong>{{ date('d-m-Y', strtotime($data['detail']['tglAkhirRujuk'])) }}</strong>
                        </p>

                    </td>
                    <td>
                        <p style="margin-bottom: 70px">Salam Sejawat, {{ Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}</p>
                        <p class="mt-5"><b><u>{{ $data['nmDokter'] }}</u></b></p>
                    </td>
                </tr>
            </table>

            Info Denda : {{ $data['detail']['infoDenda'] }}
        </div>
    </div>
    {{-- @dd($data) --}}
    {{-- {{ print_r($data) }} --}}
@endsection

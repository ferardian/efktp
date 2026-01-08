@extends('content.print.main')

@section('content')
    <div width="100%" style="font-size: 11px">
        <img src="{{ asset('/public/img/logo-bpjs.png') }}" alt="" width="200px" style="position: absolute;top:0px;right:40px" />
        <div class="text-center" style="margin-top:40px">
            <h6 style="margin-bottom:0px;margin-top:0px;font-size:10px">Divisi Regional : {{ $data['detail']['nmKR'] }}</h6>
            <h6 style="margin-bottom:0px;margin-top:0px;font-size:10px">Kantor Cabang : {{ $data['detail']['nmKC'] }}</h6>
        </div>
        <img style="position: absolute;top:80px;right:40px" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data['noKunjungan'], 'C39E') }}" height="35" width="200" />
        <h6 style="text-align:center;font-size:10px">Rujukan FKTP</h6>
        <div style="border:1px solid; padding:10px;margin-top:55px;margin-bottom:10px">
            <table>
                <tr>
                    <td>No Rujukan</td>
                    <td>:</td>
                    <td>{{ $data['noKunjungan'] }}</td>
                </tr>
                <tr>
                    <td>Puskesmas/Klinik/Dokter</td>
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
        <table>
            <tr>
                <td>Kepada Yth. Dokter </td>
                <td>:</td>
                <td>{{ $data['nmPoli'] }}</td>
            </tr>
            <tr>
                <td colspan="3">Di {{ $data['nmPPK'] }}</td>
            </tr>
        </table>
        <p style="margin:10px">Mohon pemeriksaan dan penanganan lebih lanjut kepada pasien : </p>
        <table class="table" width="100%" style="vertical-align: top">
            <tr>
                <td width="20%">Nama </td>
                <td width="2%">:</td>
                <td width="70%">{{ $data['nm_pasien'] }} ({{ $data['pasien']['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' }})</td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>:</td>
                <td>{{ $data['reg_periksa']['umurdaftar'] }} {{ $data['reg_periksa']['sttsumur'] }} / {{ date('d F Y', strtotime($data['pasien']['tgl_lahir'])) }}</td>
            </tr>
            <tr>
                <td>No Peserta</td>
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
                <td>{{ $data['detail']['catatanRujuk'] }}</td>
            </tr>
        </table>
        <p style="margin:10px">Atas bantuannya, diucapkan terimakasih</p>
        <table width="100%">
            <tr>
                <td>
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
            </tr>
        </table>

        <p class="mt-1">Info Denda : {{ $data['detail']['infoDenda'] }}</p>
        <div style="margin-top:20px;text-align: center;left:0px;font-size:12px;">
            <p style="margin-bottom:50">Salam Sejawat,{{ date('d/m/Y H:i:s') }}</p>
            <p><b><u>{{ $data['nmDokter'] }}</u></b></p>
        </div>
    </div>
@endsection

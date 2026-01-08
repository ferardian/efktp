@extends('content.print.main')

@section('content')
    <div class="container" style="margin: 20px">
        {{-- @dd($data) --}}
        <div width="100%">
            <img src="{{ 'data:image/jpeg;base64,' . $data['logo'] }}" alt="" width="70px" style="top:20px;left: 25px;position:absolute">
            <div style="text-align: center; margin-left:60px;">
                <h5 class="m-0">{{ $data['nama_instansi'] }}</h5>
                <p class="m-0">{{ $data['alamat_instansi'] }}</p>
                <p class="m-0">Telp. {{ $data['kontak'] }}, Email : {{ $data['email'] }} </p>
            </div>
            <hr>

            {{-- @dd($data) --}}
        </div>
        <div class="no_surat" style="text-align:center;margin-bottom:10px">
            <h5><u>SURAT KETERANGAN SAKIT</u></h5>
            <p>No. {{ $data['no_surat'] }}</p>
        </div>
        <p>Yang bertanda tangan di bawah ini, menerangkan bahwa : </p>
        <table class="table" width="100%">
            <tr>
                <td width="30%">Nama Pasien</td>
                <td width="2%">:</td>
                <td>{{ $data['nm_pasien'] }}</td>
            </tr>
            <tr>
                <td>Tgl. Lahir/Umur</td>
                <td>:</td>
                <td>{{ $data['tgl_lahir'] }} / {{ $data['umur'] }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $data['jk'] }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $data['pekerjaan'] }}</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>{{ $data['instansi'] }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $data['alamat'] }}</td>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>Memerlukan istirahat selama <u><b>{{ $data['lama'] }}</b></u> Hari karena sakit terhitung sejak tanggal <b><u>{{ date('d-m-Y', strtotime($data['tgl_awal'])) }}</u></b> sampai dengan <b><u>{{ date('d-m-Y', strtotime($data['tgl_akhir'])) }}</u></b> </td>
            </tr>
            <tr>
                <td>Diagnosa</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>

        <div style="margin-top:20px;text-align: center;left:0px">
            <p style="margin-bottom:75px">Ttd. Dokter</p>
            <p class="m-0"><u>{{ $data['dokter'] }}</u></p>
            <p class="m-0">SIP : {{ $data['sip'] }}</p>
        </div>
    </div>
@endsection

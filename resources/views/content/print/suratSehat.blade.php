@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <style>
        table {
            font-size : 14px;
            width: 100%;
        }
       .subtitle{
            font-size: 11px !important;
        }
        p {
            font-size:14px ;
        }


    </style>
    <div class="container" style="margin: 15px 20px;">
        @include('content.print._kopSurat')


        <div class="no_surat text-center">
            <h6><u>SURAT KETERANGAN SEHAT</u></h6>
            <p>No. {{ $data['no_surat'] }}</p>
        </div>
        <p style="margin-bottom: 10px;margin-top:10px ">Yang bertanda tangan di bawah ini</p>
        <table class="table" width="100%">
            <tr>
                <td width="25%">Nama</td>
                <td width="2%">:</td>
                <td>{{ $data['dokter'] }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $data['jabatan'] }}</td>
            </tr>
        </table>
        <p style="margin:10px">menerangkan bahwa</p>
        <table class="table" width="100%" style="margin-bottom: 10px">
            <tr>
                <td width="25%">Nama Pasien</td>
                <td width="2%">:</td>
                <td>{{ $data['nm_pasien'] }}</td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>:</td>
                <td>{{ $data['umur'] }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $data['jk'] }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $data['alamat'] }}</td>
        </table>
        <div style="border:1px solid #000" class="p-2">
            <table class="table" width="100%">
                <tr>
                    <td>Berat Badan</td>
                    <td>:</td>
                    <td>{{ $data['berat'] }} Kg</td>
                    <td>Tinggi Badan</td>
                    <td>:</td>
                    <td>{{ $data['tinggi'] }} cm</td>
                </tr>
                <tr>
                    <td>Suhu</td>
                    <td>:</td>
                    <td>{{ $data['suhu'] }} °C</td>
                    <td>Tensi</td>
                    <td>:</td>
                    <td>{{ $data['tensi'] }} mmHG</td>
                </tr>
                <tr>
                    <td>Buta Warna</td>
                    <td>:</td>
                    <td>{{ $data['butawarna'] }}</td>
                </tr>
            </table>

        </div>
        <p style="margin-top: 10px">Berdasarkan pemeriksaan yang telah dilakukan pada
            tanggal {{ Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}, yang bersangkutan
            dinyatakan dalam keadaan <b>{{ strtoupper($data['kesimpulan']) }}</b>. </p>
        <p style="margin-top: 10px">Demikian surat ini kami buat dan digunakan untuk keperluan : <b><u>{{ $data['keperluan'] }}</u></b>, Terimakasih.</p>

        <div style="margin-top:20px;text-align: center;left:0px">
            <p class="m-0">{{ $data['kabupaten'] }}, {{ Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
            <p class="m-0" style="margin-bottom: {{ !empty($data['use_barcode']) ? '5px' : '75px' }}">Ttd. Dokter</p>
            @if(!empty($data['use_barcode']))
                <div style="margin: 4px auto 6px auto;">
                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Dikeluarkan oleh: ' . ($data['nama_instansi'] ?? '') . ' | Surat Keterangan Sehat No: ' . $data['no_surat'] . ' | Dokter: ' . $data['dokter'] . ' (SIP: ' . $data['sip'] . ') | Pasien: ' . $data['nm_pasien'], 'QRCODE') }}" height="60" width="60" />
                </div>
            @endif
            <p class="m-0"><u>{{ $data['dokter'] }}</u></p>
            <p class="m-0">SIP : {{ $data['sip'] }}</p>
        </div>

    </div>
@endsection

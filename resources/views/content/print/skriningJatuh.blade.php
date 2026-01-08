@extends('content.print.main')
@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div class="m-5" width="100%" style="font-size: 13px;">
        <table width="100%">
            <tr>
                <td>{{ $setting->nama_instansi }}</td>
                <td style="text-align: right">04/F/KPBG/2023</td>
            </tr>
        </table>
        <hr />
        <table width="100%" class="borderless">
            <tr>
                <td style="border: 1px solid;text-align:center">
                    <h5>
                        SKRINING RESIKO JATUH
                    </h5>
                </td>
                <td style="border: 1px solid">
                    <ul style="padding:10px;margin-bottom:0px;list-style-type: none;">
                        <li>Nama Pasien : {{ $data->regPeriksa->pasien->nm_pasien }}</li>
                        <li>Tanggal Lahir :{{ Carbon\Carbon::parse($data->regPeriksa->pasien->tgl_lahir)->translatedFormat('d F Y') }}</li>
                        <li>No. Rekam Medis: {{ $data->regPeriksa->no_rkm_medis }}</li>
                    </ul>
                </td>
            </tr>
        </table>
        <p class="mt-2 mb-0"><b>1. Pengkajian Resiko Jatuh <i>"Get Up and Go" </i></b></p>
        <table width="100%" class="table-border" style="font-size: 12px">
            <tr style="text-align: center">
                <th>No</th>
                <th>Penilaian / Pengkajian</th>
                <th>Pengamatan</th>
            </tr>
            <tr>
                <td style="text-align: right">a.</td>
                <td>Pasien memerlukan bantuan saat duduk/berdiri/berjalan</td>
                <td style="text-align: center"><input type="checkbox" name="" id="" class="" style="transform: scale(1.5)" {{ $data->berjalan_a == 'Ya' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td style="text-align: right">b.</td>
                <td>Pasien tampak tidak seimbang (sempoyongan/limbung)</td>
                <td style="text-align: center"><input type="checkbox" name="" id="" class="" style="transform: scale(1.5)" {{ $data->berjalan_b == 'Ya' ? 'checked' : '' }}></td>
            </tr>
        </table>

        <p class="mt-2 mb-0"><b>2. Hasil</b></p>
        <table width="100%" class="table-border" style="font-size: 12px">
            <tr style="text-align: center">
                <th width="5%">No</th>
                <th width="25%">Hasil</th>
                <th width="50%">Pengamatan</th>
                <th>Hasil</th>
            </tr>
            <tr>
                <td style="text-align: right">1.</td>
                <td>{{ $data->ket_hasil }}</td>
                <td>{{ $data->hasil }}</td>
                <td style="text-align: center"><input type="checkbox" name="ckHasil" id="ckHasil" class="" style="transform: scale(1.5)" {{ $data->ket_hasil == 'Beresiko jatuh' ? 'checked' : '' }}></td>
            </tr>
        </table>

        <p class="mt-2 mb-0"><b>3. Tindakan</b></p>
        <table width="100%" class="table-border" style="font-size: 12px">
            <tr style="text-align: center">
                <th width="5%">No</th>
                <th width="25%">Hasil Kajian</th>
                <th width="50%">Tindakan</th>
                <th>Hasil</th>
            </tr>
            <tr>
                <td style="text-align: right">1.</td>
                <td>{{ $data->ket_hasil }}</td>
                <td>{{ $data->tindakan }}</td>
                <td style="text-align: center"><input type="checkbox" name="ckTindakan" id="ckTindakan" class="" style="transform: scale(1.5)" {{ $data->ket_hasil == 'Beresiko jatuh' ? 'checked' : '' }}></td>
            </tr>
        </table>

        {{-- {{ print_r($data->) }} --}}
        {{-- @dd() --}}
        <div style="margin-top:10px;text-align: center;left:0px;font-size:12px;">
            <p class="m-0">{{ Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</p>
            <p class="" style="margin-bottom: 100px">Petugas</p>
            <p class="mt-5"><u><b>{{ $data->pegawai->nama }}</b></u></p>
            {{-- <p class="m-0">NIP : {{ $data->dokter->no_ijn_praktek }}</p> --}}
        </div>

    </div>
@endsection

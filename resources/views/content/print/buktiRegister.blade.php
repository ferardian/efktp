@extends('content.print.main')

@php
    Carbon\Carbon::setLocale('id');
@endphp
@section('content')
    <div width="100%" style="font-size: 12px">
        <div class="container" style="margin: 5px">
            <div width="100%">
                <div style="font-size: 10px" class="text-center">
                    <p style="font-size:14px;font-weight:bold">{{ $setting->nama_instansi }}</p>
                    <p>{{ $setting->alamat_instansi }}</p>
                    <p>Telp. {{ $setting->kontak }}, Email : {{ $setting->email }} </p>
                </div>
                <hr>
            </div>
            <div class="no_surat mb-4" style="text-align: center;font-size:14px">
                <p class="" style="font-size: 14px;font-weight: bold"><u>BUKTI REGISTRASI</u></p>
                <p>No. Urut</p>

                @if (str_contains($data->penjab->png_jawab, 'BPJS'))
                    <p style="font-size: 80px;font-weight: bold">{{ $data->pcarePendaftaran->noUrut }}</p>
                @else
                    <p style="font-size: 80px;font-weight: bold">{{ $data->no_reg }}</p>
                @endif
                <p style="font-size: 16px;font-weight: bold">{{ $data->pasien->nm_pasien }} ( {{ $data->umurdaftar }} {{ $data->sttsumur }} )</p>
                <p>{{ Carbon\Carbon::parse($data->tgl_registrasi)->translatedFormat('d F Y') }} {{ $data->jam_reg }}</p>
                <p>{{ $data->poliklinik->nm_poli }}</p>
                <p>{{ $data->dokter->nm_dokter }}</p>
                <p><strong><i>{{ $data->penjab->png_jawab }}</i></strong></p>
            </div>
            {{-- <table class="table" width="100%">
                <tr>
                    <td width="30%">No. RM</td>
                    <td width="4%">:</td>
                    <td width="">{{ $data->no_rkm_medis }}</td>
                </tr>
                <tr>
                    <td width="30%">No. Rawat</td>
                    <td width="4%">:</td>
                    <td width="">{{ $data->no_rawat }}</td>
                </tr>
                <tr>
                    <td width="30%">No. Peserta</td>
                    <td width="4%">:</td>
                    <td width="">{{ $data->pasien->no_peserta }}</td>
                </tr>
                <tr>
                    <td width="30%">Jenis Kelamin</td>
                    <td width="4%">:</td>
                    <td width="">{{ $data->pasien->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td width="30%">Alamat</td>
                    <td width="4%">:</td>
                    <td style="text-align: justify">{{ $data->pasien->alamat }}, {{ $data->pasien->kel->nm_kel }}</td>
                </tr>
            </table> --}}

            <hr />
            <p class="mt-2 text-center">Terimakasih atas kepercayaan anda, bawalah kartu ini untuk berkunjung</p>
        </div>
    @endsection

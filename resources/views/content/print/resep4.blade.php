@extends('content.print.main')

@section('content')
    {{-- {{ $setting->logo }} --}}

    <div width="100%" style="">
        <img src="{{ 'data:image/jpeg;base64,' . base64_encode($setting->logo) }}" alt="" width="50px" style="left: 10px;position:absolute;top:10px">
        <div style="text-align: center; margin-left:60px;">
            <p class="m-0" style="font-size: 15px;font-weight: bold">{{ $setting->nama_instansi }}</p>
            <p class="m-0" style="font-size: 11px">{{ $setting->alamat_instansi }}, {{ $setting->kabupaten }},{{ $setting->propinsi }}</p>
            <p class="m-0" style="font-size: 11px">Telp. {{ $setting->kontak }}, Email : {{ $setting->email }} </p>
        </div>
        <hr>
        <div class="info" style="margin-bottom:20px">
            <table class="" width="100%" style="border-spacing: 0px;font-size:12px;">
                <tr>
                    <td width="25%">
                        Nama/JK
                    </td>
                    <td width="2%">
                        :
                    </td>
                    <td width="75%">
                        {{ $data->regPeriksa->pasien->nm_pasien }} ({{ $data->regPeriksa->pasien->jk }}) - {{ $data->regPeriksa->penjab->png_jawab }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Tgl. Lahir/Umur
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ date('d-m-Y', strtotime($data->regPeriksa->pasien->tgl_lahir)) }} ({{ $data->regPeriksa->umurdaftar }} {{ $data->regPeriksa->sttsumur }})
                    </td>
                </tr>
                <tr>
                    <td>
                        Alamat
                    </td>
                    <td>
                        :
                    </td>
                    <td>

                        {{ $data->regPeriksa->pasien->alamat }}, {{ $data->regPeriksa->pasien->kel->nm_kel }}
                    </td>
                </tr>
                <tr>
                    <td>
                        No Resep
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ $data->no_resep }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Tgl. Resep
                    </td>
                    <td>
                        :
                    </td>
                    <td>
                        {{ date('d-m-Y', strtotime($data->tgl_peresepan)) }} {{ $data->jam_peresepan }}
                    </td>
                </tr>
            </table>

        </div>
        @if ($data->resepDokter)
            <table width="100%" class="table-print" style="font-size: 12px">
                <tbody>
                    @foreach ($data->resepDokter as $rd)
                        <tr>
                            <td>
                                R/
                            </td>
                            <td>
                                {{ $rd->obat->nama_brng }}
                            </td>
                            <td>
                                {{ $rd->jml }}
                            </td>
                            <td>
                                S {{ $rd->aturan_pakai }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endif
        @if (count($data->resepRacikan))
            <table width="100%" class="table-print">
                <thead>
                    <tr>
                        <th width="2%" colspan="4">
                            RESEP RACIKAN
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->resepRacikan as $rr)
                        <tr style="border:0!important">
                            <td colspan="4">
                                <p>R/ Jumlah : {{ $rr->jml_dr }} {{ $rr->metode->nm_racik }}, S: {{ $rr->aturan_pakai }}</p>
                                <ul style="padding:10px;margin:0px">
                                    @foreach ($rr->detail as $detail)
                                        <li>{{ $detail->obat->nama_brng }} ({{ $detail->kandungan }} mg) , Jml: {{ $detail->jml }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endif
        <table style="text-align: center" width="100%">
            <tr>
                <td>Ttd. Dokter</td>
            </tr>
            <tr>
                <td><img style="" src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Ditandatangani oleh ' . $data->dokter->nm_dokter . ' pada ' . $data->tgl_peresepan . ' ' . $data->jam_peresepan, 'QRCODE') }}" height="60" width="60" /></td>
            </tr>
            <tr>
                <td>
                    <strong><u>{{ $data->dokter->nm_dokter }}</u></strong>
                </td>
            </tr>
            <tr>
                <td>
                    SIP : {{ $data->dokter->no_ijn_praktek }}
                </td>
            </tr>
        </table>
    </div>
@endsection

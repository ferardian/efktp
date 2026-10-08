@extends('content.print.main')

@section('content')
    <div class="container" style="margin: 15px 20px">
        @include('content.print._kopSurat')
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
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>Memerlukan istirahat selama <u><b>{{ $data['lama'] }}</b></u> Hari karena sakit terhitung sejak tanggal <b><u>{{ date('d-m-Y', strtotime($data['tgl_awal'])) }}</u></b> sampai dengan <b><u>{{ date('d-m-Y', strtotime($data['tgl_akhir'])) }}</u></b> </td>
            </tr>
            <tr>
                <td>Diagnosa</td>
                <td>:</td>
                <td>
                    @if(($data['mode_diagnosa'] ?? '') === 'manual' || empty($data['diagnosa']) || $data['diagnosa'] === '-')
                        &nbsp;
                    @else
                        {{ $data['diagnosa'] }}
                    @endif
                </td>
            </tr>
        </table>

        <div style="margin-top:20px;text-align: center;left:0px">
            <p class="m-0" style="margin-bottom: {{ !empty($data['use_barcode']) ? '5px' : '75px' }}">Ttd. Dokter</p>
            @if(!empty($data['use_barcode']))
                <div style="margin: 4px auto 6px auto;">
                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Dikeluarkan oleh: ' . ($data['nama_instansi'] ?? '') . ' | Surat Keterangan Sakit No: ' . $data['no_surat'] . ' | Dokter: ' . $data['dokter'] . ' (SIP: ' . $data['sip'] . ') | Pasien: ' . $data['nm_pasien'], 'QRCODE') }}" height="60" width="60" />
                </div>
            @endif
            <p class="m-0"><u>{{ $data['dokter'] }}</u></p>
            <p class="m-0">SIP : {{ $data['sip'] }}</p>
        </div>
    </div>
@endsection

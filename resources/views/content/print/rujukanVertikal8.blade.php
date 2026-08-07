@extends('content.print.main')

@section('content')
    <div width="100%" style="font-size: 11px">
        <img src="{{ asset('img/logo-bpjs.png') }}" alt="" width="200px" style="position: absolute;top:0px;right:40px" />
        <div class="text-center" style="margin-top:40px">
            <h6 style="margin-bottom:0px;margin-top:0px;font-size:10px">Divisi Regional : {{ !empty($data['detail']['nmKR']) && $data['detail']['nmKR'] !== '-' ? $data['detail']['nmKR'] : ($setting['propinsi'] ?? '-') }}</h6>
            <h6 style="margin-bottom:0px;margin-top:0px;font-size:10px">Kantor Cabang : {{ !empty($data['detail']['nmKC']) && $data['detail']['nmKC'] !== '-' ? $data['detail']['nmKC'] : ($setting['kabupaten'] ?? '-') }}</h6>
        </div>
        <img style="position: absolute;top:80px;right:40px" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data['noKunjungan'], 'C39E') }}" height="35" width="200" />
        <h6 style="text-align:center;font-size:10px">Surat Rujukan FKTP</h6>
        <div style="border:1px solid; padding:10px;margin-top:55px;margin-bottom:10px">
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
        <table>
            <tr>
                <td>Kepada Yth. TS Dokter </td>
                <td>:</td>
                <td>{{ $data['nmSubSpesialis'] ?? $data['nmPoli'] }}</td>
            </tr>
            <tr>
                <td colspan="3">Di {{ $data['nmPPK'] }}</td>
            </tr>
        </table>
        <p style="margin:10px">Mohon pemeriksaan dan penanganan lebih lanjut pasien : </p>
        <table class="table" width="100%" style="vertical-align: top">
            <tr>
                <td width="20%">Nama </td>
                <td width="2%">:</td>
                <td width="70%">{{ $data['nm_pasien'] }}</td>
            </tr>
            <tr>
                <td>Umur</td>
                <td>:</td>
                <td>{{ $data['reg_periksa']['umurdaftar'] ?? '-' }} Tahun : {{ !empty($data['pasien']['tgl_lahir']) ? date('d-M-Y', strtotime($data['pasien']['tgl_lahir'])) : '-' }}</td>
            </tr>
            <tr>
                <td>No. Kartu BPJS</td>
                <td>:</td>
                <td>{{ $data['noKartu'] }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td>[ 1 ] Utama/Tanggungan &nbsp;&nbsp;&nbsp;&nbsp; [ {{ $data['pasien']['jk'] ?? 'L' }} ] (L / P)</td>
            </tr>
            <tr>
                <td>Diagnosa</td>
                <td>:</td>
                <td>{{ $data['nmDiag1'] }} ({{ $data['kdDiag1'] }})</td>
            </tr>
            <tr>
                <td>Catatan</td>
                <td>:</td>
                <td>{{ $data['detail']['catatanRujuk'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Telah diberikan</td>
                <td>:</td>
                <td>{{ $data['terapi'] ?? '-' }}</td>
            </tr>
        </table>
        <p style="margin:10px">Atas bantuannya, diucapkan terima kasih</p>
        <table width="100%">
            <tr>
                <td>
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
            </tr>
        </table>

        <p class="mt-1">Info Denda : {{ $data['detail']['infoDenda'] ?? '-' }}</p>
        <div style="margin-top:15px; text-align: right; font-size:11px;">
            <p style="margin-bottom:35px">Salam Sejawat,<br/>{{ date('d F Y') }}</p>
            <p><b><u>{{ $data['nmDokter'] }}</u></b></p>
        </div>
    </div>
@endsection

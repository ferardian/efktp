@extends('main')

@section('contents')
    <div class="container p-5 bg-dark">
        <div class="row gy-3">
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="card border rounded-4" style="height: 100px">
                    <div class="card-body">
                        <div style="display: flex">
                            {{-- <div class="col-lg-1"> --}}
                            <div>
                                <img src="{{ $data->logo }}" alt="logo" width="70px">
                            </div>
                            {{-- </div> --}}
                            {{-- <div class="col"> --}}
                            <div class="ms-3">
                                <h1 class="m-0" style="font-size: 300%">{{ $data->nama_instansi }}</h1>
                                <p class="m-0" style="font-size: 120%">{{ $data->alamat_instansi }}, {{ $data->kabupaten }}, {{ $data->propinsi }}</p>
                                <p class="m-0" style="font-size: 120%">{{ $data->email }}, {{ $data->kontak }}, {{ $data->propinsi }}</p>
                            </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="card border rounded-4 text-center" style="height: 100px">
                    <div class="card-body">
                        <h1 class="m-0" style="font-size: 405%" id="jam">00:00:00</h1>
                        <h1 class="p-2" style="" id="tanggal">{{ date('d F Y') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="mb-3 card border border-success rounded-4 text-center" id="antreanPenggil">
                    <div class="card-body bg-green-light">
                        <h1 class="mb-5">Nomor Antrean</h1>
                        <h1 class="mb-5" style="font-size: 800%;font-family:Verdana, Geneva, Tahoma, sans-serif" id="nomor">000</h1>
                        <h1 class="" style="font-size: 350%" id="nama">-</h1>
                        <h1 class="m-0" id="poliklinik">Poliklinik</h1>
                        <h1 class="m-0" id="dokter">Dokter</h1>
                    </div>
                </div>
                <div class="row gy-2">
                    @foreach ($poliklinik as $key => $item)
                        <div class="col-4">
                            <div class="card border rounded-4 text-center">
                                <div class="card-body">
                                    <h1 class="card-title" style="font-size:150%">
                                        {{ $item->nm_poli }}
                                    </h1>
                                    <h1 style="font-size:500%;font-family:Verdana, Geneva, Tahoma, sans-serif" id="nomorAntrean{{ $item->kd_poli }}">000</h1>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12" id="frameVideo">
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/videoseries?si=CkH2Y3zTCfsIJ9je&amp;controls=1&amp;list=PL8-ZDsV7brM341rMXOPb1b-Qvi0he4kak&amp;autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card border rounded-4 text-center" style="height: 10vh">
                    <div class="card-body">
                        <marquee behavior="" direction="">
                            <h1 class="0">
                                {{ $data->nama_instansi }}, Alamat : {{ $data->alamat_instansi }}, {{ $data->kabupaten }}, {{ $data->propinsi }}, Kritik, Saran & Informasi : {{ $data->email }}, {{ $data->kontak }},
                            </h1>
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
        {{-- <h1>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quam nisi laudantium aut natus nam, debitis delectus quos eos harum inventore explicabo non earum voluptate dolor eaque quas. Sint, laudantium nam!</h1> --}}
    </div>
    {{-- {{ print_r($data) }} --}}
    {{-- @dd($data) --}}
@endsection
@push('script')
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=Alg0GPi9"></script>
    <script>
        // var url = "{{ url('') }}";


        $(document).ready(() => {
            jam();
            const date = new Date();

            const hari = setHari(date.getDay());
            const tanggal = date.toISOString().split('T')[0]
            // console.log();

            $('#tanggal').html(`${hari}, ${formatTanggal(tanggal)}`);
            const panggilanCounter = {};
            setInterval(() => {
                $.get(`/efktp/registrasi/get/panggil`).done((response) => {
                    if (Object.keys(response).length) {
                        $('#nomor').html(response.no_reg)
                        $('#nama').html(response.pasien.nm_pasien)
                        $('#poliklinik').html(response.poliklinik.nm_poli)
                        $('#dokter').html(response.dokter.nm_dokter)
                        $('#penjab').html(response.penjab.png_jawab)
                        const name = response.pasien.nm_pasien.split(', ')[0].toLowerCase();
                        const poliklinik = response.poliklinik.nm_poli
                        if (!panggilanCounter[response.no_rawat]) {
                            panggilanCounter[response.no_rawat] = 0;
                        }

                        if (panggilanCounter[response.no_rawat] >= 2) {
                            $.post(`/efktp/registrasi/update/status`, {
                                    no_rawat: response.no_rawat,
                                    stts: 'Dirawat'
                                })
                                .done(() => {

                                    console.log(`Status pasien ${response.no_rawat} diupdate`)
                                })
                                .fail(err => console.error("Gagal update status", err));
                        } else {
                            speak(`${name}. ${poliklinik}`)
                        }
                        panggilanCounter[response.no_rawat]++;

                        blinkText($('#nama'));
                        blinkText($('#nomor'));
                    }
                })
                $.get(`/efktp/registrasi/get`, {
                    stts: 'Dirawat'
                }).done((response) => {
                    Object.values(response).map(element => {
                        $(`#nomorAntrean${element.kd_poli}`).html(element.no_reg ? element.no_reg : '-');
                    });
                });
            }, 2500);


        })

       


        function blinkText(element) {
            element.toggleClass('text-success')
        }
    </script>
@endpush

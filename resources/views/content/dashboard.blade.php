@extends('layout')

@section('body')
    <div class="container">
        <div class="card mb-2">
            <div class="card-body">
                <span>Selamat Datang : {{ session()->get('pegawai')->nama }}</span>
                <h1>{{ $data->nama_instansi }}</h1>
            </div>
        </div>
        <div class="row row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Kunjungan Pasien</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2" id="totalKunjungan">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-cashapp" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z" />
                            </svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pembiayaan Umum</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2" id="totalUmum">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-status-top bg-green"></div>
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-green">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-heart-handshake" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                <path d="M12 6l-3.293 3.293a1 1 0 0 0 0 1.414l.543 .543c.69 .69 1.81 .69 2.5 0l1 -1a3.182 3.182 0 0 1 4.5 0l2.25 2.25" />
                                <path d="M12.5 15.5l2 2" />
                                <path d="M15 13l2 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Pembiayaan BPJS</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2" id="totalBpjs">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-status-top bg-red"></div>
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-red">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-stethoscope" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h-1a2 2 0 0 0 -2 2v3.5h0a5.5 5.5 0 0 0 11 0v-3.5a2 2 0 0 0 -2 -2h-1" />
                                <path d="M8 15a6 6 0 1 0 12 0v-3" />
                                <path d="M11 3v2" />
                                <path d="M6 3v2" />
                                <path d="M20 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            </svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Diperiksa/Dirujuk</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2" id="totalDiperiksa">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                @include('content.dashboard._grafikPenyakit')
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                @include('content.dashboard._grafikKelurahan')
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                @include('content.dashboard._grafikKecamatan')
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                @include('content.dashboard._grafikTahunan')
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
    <script>
        $(document).ready(() => {
            setCardKunjungan();
            dataGrafikKecamatan();
            dataGrafikKelurahan();
            dataGrafikTahunan()
        })

        function setCardKunjungan() {
            getRegPeriksa().done((response) => {
                const totalKunjungan = response.length
                const totalBpjs = response.filter((bpjs) => {
                    return bpjs.kd_pj == 'BPJ';
                }).length
                const totalUmum = response.filter((umum) => {
                    return umum.kd_pj == 'A09';
                }).length
                const totalDiperiksa = response.filter((regPeriksa) => {
                    return regPeriksa.stts == 'Sudah';
                }).length
                const totalDirujuk = response.filter((regPeriksa) => {
                    return regPeriksa.stts == 'Dirujuk';
                }).length
                $('#totalKunjungan').html(totalKunjungan)
                $('#totalBpjs').html(totalBpjs)
                $('#totalUmum').html(totalUmum)
                $('#totalDiperiksa').html(`${totalDiperiksa} / ${totalDirujuk}`)
            })
        }
    </script>
@endpush

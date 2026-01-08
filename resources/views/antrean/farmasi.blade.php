@extends('main')
@push('style')
    <style>
        #tabelAntreanFarmasi {
            table-layout: fixed;
            width: 100%;
            white-space: nowrap;

        }

        #tabelAntreanFarmasi thead th,
        #tabelAntreanFarmasi tbody td {
            text-overflow: ellipsis;
            overflow: hidden;
            font-size: 1.3em;
        }

        @keyframes blink {
            0% {
                background-color: #d1e7dd !important;
            }

            50% {
                background-color: transparent !important;
            }

            100% {
                background-color: #d1e7dd !important;
            }
        }

        .blink {
            animation: blink 1s step-start 5;
            /* kedip 5x */
        }
    </style>
@endpush
@section('contents')
    <div class="container p-5 bg-dark">
        <div class="row gy-3">
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="card border rounded-4" style="height: 100px">
                    <div class="card-body">
                        <div style="display: flex">
                            <div>
                                <img src="{{ $data->logo }}" alt="logo" width="70px">
                            </div>

                            <div class="ms-3">
                                <h1 class="m-0" style="font-size: 300%">{{ $data->nama_instansi }}</h1>
                                <p class="m-0" style="font-size: 120%">{{ $data->alamat_instansi }}, {{ $data->kabupaten }}, {{ $data->propinsi }}</p>
                                <p class="m-0" style="font-size: 120%">{{ $data->email }}, {{ $data->kontak }}, {{ $data->propinsi }}</p>
                            </div>
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
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="card card-body overflow-y-auto" style="height: 60vh">
                    <table class="table table-striped table-hover" id="tabelAntreanFarmasi">
                        <thead>
                            <tr>
                                <th>No. Resep</th>
                                <th width="30%">Pasien</th>
                                <th>Jam Resep</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12" id="frameVideo" style="">
                <div class="card border border-success rounded-4 text-center d-none" id="antreanPanggil">
                    <div class="card-body bg-green-light">
                        <h1 class="mb-2">Nomor Antrean</h1>
                        <h1 class="mb-3" style="font-size: 380%;font-family:Verdana, Geneva, Tahoma, sans-serif" id="nomor">000</h1>
                        <h1 class="mb-3" style="font-size: 380%" id="nama">-</h1>
                        <h1 class="mb-1" id="poliklinik">Poliklinik</h1>
                        <h1 class="mb-1" id="dokter">Dokter</h1>
                    </div>
                </div>
                <div id="videoAntrean" style="height:60vh">
                    <iframe class="" width="100%" height="100%" src="https://www.youtube.com/embed/videoseries?si=CkH2Y3zTCfsIJ9je&amp;controls=1&amp;list=PL8-ZDsV7brM341rMXOPb1b-Qvi0he4kak&amp;autoplay=1&enablejsapi=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>

        </div>
        <div class="footer d-print-none footer-transparent rounded">
            <div class="card">
                <div class="card-body">
                    <marquee behavior="" direction="">
                        <h1 class="m-0">
                            {{ $data->nama_instansi }}, Alamat : {{ $data->alamat_instansi }}, {{ $data->kabupaten }}, {{ $data->propinsi }}, Kritik, Saran & Informasi : {{ $data->email }}, {{ $data->kontak }},
                        </h1>
                    </marquee>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(() => {
            $.get(`/efktp/setting/antrean/video`).done((response) => {
                if (response) {
                    $('#videoAntrean').html(response.content);
                } else {
                    $('#videoAntrean').html(`<iframe width="100%" height="100%" src="https://www.youtube.com/embed/videoseries?si=CkH2Y3zTCfsIJ9je&amp;controls=1&amp;list=PL8-ZDsV7brM341rMXOPb1b-Qvi0he4kak&amp;autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`);

                }
                $('iframe').attr('width', '100%').attr('height', '100%')
            })
            // Panggil getDataResep setiap 2 detik
            setInterval(() => {
                getDataResep();
            }, 2000);

            // Simpan nilai sebelumnya untuk cek perubahan
            let lastNoResep = '';
            let lastNama = '';

            // Cek localStorage setiap 1 detik
            setInterval(() => {
                let no_resep = localStorage.getItem('no_resep');
                let nama = localStorage.getItem('nm_pasien');
                let panggil = localStorage.getItem('panggil');
                let poliklinik = localStorage.getItem('resepPoliklinik');
                let dokter = localStorage.getItem('resepDokter');

                $('#nomor').text(no_resep);
                $('#nama').text(nama);
                $('#poliklinik').text(poliklinik);
                $('#dokter').text(dokter);
                if (panggil === 'yes' && nama) {
                    let namaBersih = nama.replace(/[`'`]/g, "").toLowerCase();
                    speak(namaBersih + " , Silahkan mengambil obat di loket farmasi");
                    localStorage.setItem('panggil', 'done');

                    $("#antreanPanggil").removeClass('d-none');
                    $("#frameVideo iframe").addClass('d-none');
                    $('#videoAntrean').prop('style', 'height: 0');
                    pauseVideo();

                    // setelah 5 detik kembali ke video
                    setTimeout(() => {
                        $("#frameVideo iframe").removeClass('d-none');
                        $("#antreanPanggil").addClass('d-none');
                        $('#videoAntrean').prop('style', 'height: 60vh  ');
                        playVideo();

                    }, 15000);
                }
            }, 1000);
        });


        function getDataResep() {

            $.get(`/efktp/farmasi/resep/get`).done((response) => {
                const currentResep = localStorage.getItem('no_resep');

                // Pisahkan data yg sedang dipanggil dan yg lain
                const dipanggil = response.filter(item => item.no_resep === currentResep);
                const lainnya = response.filter(item => item.no_resep !== currentResep);

                // Gabungkan: dipanggil dulu baru lainnya
                const ordered = [...dipanggil, ...lainnya];

                const resep = ordered
                    .filter(item => item.reg_periksa.stts === 'Sudah')
                    .map((item) => {
                        return `<tr id="${item.no_resep}" class="${currentResep===item.no_resep ? 'table-success blink' : '' }">
                    <td>${item.no_resep}</td>
                    <td>${item.reg_periksa.pasien.nm_pasien}</td>
                    <td>${item.jam_peresepan}</td>
                    <td>${item.resep_racikan.length > 0 ? 'Racikan' : 'Non Racikan'}</td>
                    <td>${
                        item.no_resep === currentResep
                            ? 'DIPANGGIL'
                            : item.jam_penyerahan !== '00:00:00'
                                ? 'SELESAI'
                                : item.jam !== '00:00:00'
                                    ? 'DIPROSES'
                                    : '-'
                    }</td>
                    <td>${item.jam_penyerahan !== '00:00:00' ? item.jam_penyerahan : '-'}</td>
                </tr>`;
                    });

                $('#tabelAntreanFarmasi tbody').empty().html(resep);
                // Scroll otomatis ke baris aktif
                let no_resep = localStorage.getItem('no_resep');
                if (no_resep) {
                    let $row = $("#" + no_resep);

                    if ($row.length) {
                        // Scroll baris ke atas tabel
                        $('#tabelAntreanFarmasi tbody').animate({
                            scrollTop: $row.position().top
                        }, 500);
                        highlightRow(no_resep)
                    }
                }

            })
        }

        function highlightRow(no_resep) {
            let $row = $("#" + no_resep);
            if (!$row.length) return;

            $row.addClass("table-success"); // kasih warna dulu

            let count = 0;
            let blinkInterval = setInterval(() => {
                $row.fadeOut(300).fadeIn(300); // 1x kedipan (fade out + fade in)
                count++;

                if (count >= 3) { // ulang 3 kali
                    clearInterval(blinkInterval);
                    $row.stop(true, true).fadeIn(200); // pastikan tampil penuh
                    $row.addClass("table-success");
                }
            }, 600); // jeda antar kedip
        }
    </script>
@endpush

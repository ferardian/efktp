<div class="modal modal-blur fade" id="modalRiwayat" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Riawayat Kunjungan Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-2">
                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                        @include('content.registrasi.riwayat._listRiwayat')
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
                        <div class="card mb-2" id="cardInfoPasien">
                            <div class="card-body">
                                <div class="card-title">Informasi Pasien</div>
                                <div class="datagrid" id="dataInfoPasien">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tgl. Registrasi</div>
                                        <div class="datagrid-content" id="tanggal"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">No. Rawat</div>
                                        <div class="datagrid-content" id="no_rawat"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">No. RM</div>
                                        <div class="datagrid-content" id="no_rkm_medis"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Nama</div>
                                        <div class="datagrid-content" id="nama"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Tgl. Lahir</div>
                                        <div class="datagrid-content" id="tgl_lahir"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Poliklinik</div>
                                        <div class="datagrid-content" id="poli"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Dokter</div>
                                        <div class="datagrid-content" id="dokter"></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Asuransi</div>
                                        <div class="datagrid-content" id="penjab"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-tabs">
                            <ul class="nav nav-tabs" role="tablist" id="tabRiwayat">
                                <li class="nav-item" role="presentation"><a href="#tabCppt" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabCppt" aria-selected="true" role="tab">Pemeriksaan/CPPT</a></li>
                                <li class="nav-item" role="presentation"><a href="#tabPenunjang" class="nav-link" data-bs-toggle="tab" data-bs-target="#tabPenunjang" aria-selected="false" role="tab" tabindex="-1">Penunjang</a></li>
                                {{--                                <li class="nav-item" role="presentation"><a href="#tabSkriningAwal" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Tab 3</a></li> --}}
                                {{--                                <li class="nav-item" role="presentation"><a href="#tab-top-4" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Tab 4</a></li> --}}
                            </ul>
                            <div class="tab-content">
                                <div id="tabCppt" class="card fade tab-pane active show" role="tabpanel">
                                    {{-- card informasi pasien --}}
                                    <div class="card-body">
                                        <div class="card border-0 mb-2" id="">
                                            <div class="row">
                                                <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12" id="cardDiagnosaPasien">
                                                    <div class="card">
                                                        <div class="card-body border-1">
                                                            <div class="card-title">Diagnosa</div>
                                                            <ol id="riwayatDiagnosaPasien">

                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12" id="cardTindakanPasien">
                                                    <div class="card">
                                                        <div class="card-body border-1">
                                                            <div class="card-title">Tindakan</div>
                                                            <ol id="riwayatTindakanPasien">

                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2" id="cardRiwayatCppt">
                                            <div class="card-body">
                                                <div class="card-title">Pemeriksaan/CPPT Ralan</div>
                                                <div id="riwayatPemeriksaanRalan">

                                                </div>
                                            </div>
                                            <div class="card-body mt-2 d-none">
                                                <div class="card-title">Pemeriksaan/CPPT Ranap</div>
                                                <div id="riwayatPemeriksaanRanap">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- card informasi pasien --}}

                                </div>
                                <div id="tabPenunjang" class="card fade tab-pane" role="tabpanel">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <style>
        @media (max-width: 990px) {
            #listRiwayatRegistrasi {
                display: none;
            }
        }

        @media (min-width: 990px) {
            #listRiwayatRegistrasi {
                display: inline !important;
            }
        }

        @media (max-width: 990px) {
            #nav-riwayat {
                display: inline !important;
            }
        }
    </style>
    <script>
        const modalRiwayat = $('#modalRiwayat');
        const tabCppt = $('#tabCppt');
        const tabRiwayat = $('#tabRiwayat');
        const tabPenunjang = $('#tabPenunjang');
        const triggerTabPenunjang = $('a[data-bs-target="#tabPenunjang"]')
        const listRiwayatRegistrasi = $('#listRiwayatRegistrasi');
        const riwayatPemeriksaanRalan = $('#riwayatPemeriksaanRalan');
        const riwayatPemeriksaanRanap = $('#riwayatPemeriksaanRanap');
        const riwayatDiagnosaPasien = $('#riwayatDiagnosaPasien');
        const riwayatTindakanPasien = $('#riwayatTindakanPasien');
        const dataInfoPasien = $('#dataInfoPasien');

        function riwayat(no_rkm_medis) {
            $.get(`/efktp/registrasi/pasien/${no_rkm_medis}`).done((response) => {
                if (!response) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Belum ada kunjungan sebelumnya',
                    });
                    return false;
                }
                modalRiwayat.modal('show')
                setListRiwayatRegistrasi(response)
            })
        }

        modalRiwayat.on('show.bs.modal', () => {
            switcTab(tabRiwayat)
            listRiwayatRegistrasi.empty();
            riwayatPemeriksaanRalan.empty();
        });

        function setContentRiwayat(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien,
                    poliklinik,
                    penjab,
                    dokter,
                    riwayat_pemeriksaan,
                    pemeriksaan_ranap,
                    diagnosa,
                    prosedur
                } = response;
                dataInfoPasien.find('#no_rawat').html(response.no_rawat)
                dataInfoPasien.find('#tanggal').html(`${formatTanggal(response.tgl_registrasi)} ${response.jam_reg}`)
                dataInfoPasien.find('#no_rkm_medis').html(response.no_rkm_medis)
                dataInfoPasien.find('#nama').html(`${pasien.nm_pasien} (${pasien.jk})`)
                dataInfoPasien.find('#tgl_lahir').html(`${formatTanggal(pasien.tgl_lahir)} (${response.umurdaftar} ${response.sttsumur})`)
                dataInfoPasien.find('#poli').html(poliklinik.nm_poli)
                dataInfoPasien.find('#dokter').html(dokter.nm_dokter)
                dataInfoPasien.find('#penjab').html(penjab.png_jawab);
                setContentCpptRalan(riwayat_pemeriksaan)
                setContentCpptRanap(pemeriksaan_ranap)
                setRiwayatDiagnosa(diagnosa);
                setRiwayatTindakan(prosedur);
            })
        }


        function setContentCpptRalan(data) {
            riwayatPemeriksaanRalan.empty();

            const riwayat = data.map((item, index) => {
                const {
                    pegawai
                } = item;
                return `<div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card card-sm mb-2">
                                    <div class="card-header text-bg-primary">
                                        <p class="card-text">${formatTanggal(item.tgl_perawatan)} ${item.jam_rawat} : ${pegawai.nama}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless" style="width: 100%;vertical-align: top">
                                            <tr><td width="20%">Suhu</td><td width="2%">:</td><td>${item.suhu_tubuh} °C</td></tr>
                                            <tr><td>Tensi</td><td>:</td><td>${item.tensi} mm/Hg</td></tr>
                                            <tr><td>Berat</td><td>:</td><td>${item.berat} Kg</td></tr>
                                            <tr><td>Tinggi</td><td>:</td><td>${item.tinggi} Cm</td></tr>
                                            <tr><td>SPO2</td><td>:</td><td>${item.spo2} %</td></tr>
                                            <tr><td>Kesadaran</td><td>:</td><td>${item.kesadaran} x/m</td></tr>
                                            <tr><td>Nadi</td><td>:</td><td>${item.nadi} x/m</td></tr>
                                            <tr><td>Alergi</td><td>:</td><td>${item.alergi} x/m</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <div class="card mb-2">
                                    <div class="card-header text-bg-primary">
                                        <p class="card-text">SOAP/CPPT</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless" style="width: 100%;vertical-align: top">
                                            <tr><td width="20%">Subjek</td><td width="2%">:</td><td>${stringPemeriksaan(item.keluhan)}</td></tr>
                                            <tr><td>Objek</td><td>:</td><td>${stringPemeriksaan(item.pemeriksaan)}</td></tr>
                                            <tr><td>Asesmen</td><td>:</td><td>${stringPemeriksaan(item.penilaian)}</td></tr>
                                            <tr><td>Plan</td><td>:</td><td>${stringPemeriksaan(item.rtl)}</td></tr>
                                            <tr><td>Instruksi</td><td>:</td><td>${stringPemeriksaan(item.instruksi)}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>`
            }).join('')
            riwayatPemeriksaanRalan.append(riwayat);
        }

        function setContentCpptRanap(data) {
            riwayatPemeriksaanRanap.empty();
            if (data.length) {
                const riwayat = data.map((item, index) => {
                    const {
                        pegawai
                    } = item;
                    return `<div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card card-sm mb-2">
                                    <div class="card-header ${pegawai.dokter ? 'text-bg-primary' : 'text-bg-purple'}">
                                        <p class="card-text">${formatTanggal(item.tgl_perawatan)} ${item.jam_rawat} : ${pegawai.nama}</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless" style="width: 100%;vertical-align: top">
                                            <tr><td width="20%">Suhu</td><td width="2%">:</td><td>${item.suhu_tubuh} °C</td></tr>
                                            <tr><td>Tensi</td><td>:</td><td>${item.tensi} mm/Hg</td></tr>
                                            <tr><td>Berat</td><td>:</td><td>${item.berat} Kg</td></tr>
                                            <tr><td>Tinggi</td><td>:</td><td>${item.tinggi} Cm</td></tr>
                                            <tr><td>SPO2</td><td>:</td><td>${item.spo2} %</td></tr>
                                            <tr><td>Kesadaran</td><td>:</td><td>${item.kesadaran} x/m</td></tr>
                                            <tr><td>Nadi</td><td>:</td><td>${item.nadi} x/m</td></tr>
                                            <tr><td>Alergi</td><td>:</td><td>${item.alergi} x/m</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <div class="card mb-2">
                                    <div class="card-header  ${pegawai.dokter ? 'text-bg-primary' : 'text-bg-purple'}">
                                        <p class="card-text">SOAP/CPPT</p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless" style="width: 100%;vertical-align: top">
                                            <tr><td width="20%">Subjek</td><td width="2%">:</td><td>${stringPemeriksaan(item.keluhan)}</td></tr>
                                            <tr><td>Objek</td><td>:</td><td>${stringPemeriksaan(item.pemeriksaan)}</td></tr>
                                            <tr><td>Asesmen</td><td>:</td><td>${stringPemeriksaan(item.penilaian)}</td></tr>
                                            <tr><td>Plan</td><td>:</td><td>${stringPemeriksaan(item.rtl)}</td></tr>
                                            <tr><td>Instruksi</td><td>:</td><td>${stringPemeriksaan(item.instruksi)}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>`
                }).join('')
                riwayatPemeriksaanRanap.parent().removeClass('d-none')
                riwayatPemeriksaanRanap.append(riwayat);
            } else {
                riwayatPemeriksaanRanap.parent().addClass('d-none')
            }
        }

        function setRiwayatDiagnosa(data) {
            riwayatDiagnosaPasien.empty();
            if (data.length) {
                const diagnosa = data.map((item, index) => {
                    return `<li class="${item.prioritas === 1 ? 'text-red' : ''}">${item.kd_penyakit} - ${item.penyakit.nm_penyakit} ${item.prioritas === 1 ? '(*)' : ''}</li>`
                })
                $('#cardDiagnosaPasien').removeClass('d-none')
                riwayatDiagnosaPasien.append(diagnosa)
            } else {
                $('#cardDiagnosaPasien').addClass('d-none')

            }
        }

        function setRiwayatTindakan(data) {
            riwayatTindakanPasien.empty();
            if (data.length) {
                const tindakan = data.map((item, index) => {
                    return `<li class="${item.prioritas === 1 ? 'text-red' : ''}">${item.kode} - ${item.icd9.deskripsi_pendek} ${item.prioritas === 1 ? '(*)' : ''}</li>`
                })
                $('#cardTindakanPasien').removeClass('d-none')
                riwayatTindakanPasien.append(tindakan)
            } else {
                $('#cardTindakanPasien').addClass('d-none')

            }
        }

        triggerTabPenunjang.on('shown.bs.tab', (e) => {
            const no_rawat = $('div#no_rawat').text();
            renderBerkasPenunjang(no_rawat);
        })

        function renderBerkasPenunjang(no_rawat) {

            $.get(`/efktp/upload`, {
                no_rawat: no_rawat,
            }).done((response) => {
                const berkas = response.map((item, index) => {
                    const filetype = item.file.split('.').pop()
                    let content = '';
                    if (filetype !== 'pdf') {
                        content = ` <a class="d-block" data-magnify="gallery" data-src="" data-caption="${item.kategori.kategori} ${item.created_at}" data-group="a" href="{{ asset('public/storage/penunjang/images/') }}/${item.file}">
                                            <img style="width: 100%;height: 200px;object-fit:cover" src="{{ asset('public/storage/penunjang/images') }}/${item.file}" class="card-img-top">
                                         </a>`;
                    } else {
                        content = ` <a class="d-block" href="{{ asset('public/storage/penunjang/pdf') }}/${item.file}" target="_blank">
                                            <img style="width: 100%;height: 200px;object-fit:cover" src="{{ asset('public/img/logo-pdf.png') }}" class="card-img-top">
                                         </a>`;
                    }
                    // <button class="btn btn-outline-success btn-sm w-100"></button>
                    return `<div class="col-sm-6 col-lg-2">
                        <div class="card card-sm" title="">
                            <span class="badge bg-blue text-blue-fg badge-notification badge-pill" style="transform:none!important">${item.kategori.kategori}</span>
                            ${content}
                        </div>
                    </div>`
                });

                const html = `<div class="card-body">
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                Berkas Penunjang
                                            </div>
                                            <div class="card-body">
                                                <div class="row gy-2" id="cardBerkasPenunjang">
                                                    ${berkas}
                                                </div>
                                            </div>
                                        </div>

                                    </div>`
                tabPenunjang.empty().append(html)
            })
        }
    </script>
@endpush

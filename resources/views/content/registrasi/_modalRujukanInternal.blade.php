<div class="modal modal-blur fade" id="modalRujukanInternal" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Rujukan Internal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                                <form action="" id="formRujukanInternalPoli">
                                    <div class="row p-3 gy-2">
                                        <div class="col-xl-3 col-md-6 col-sm-12">
                                            <input type="text" class="form-control" name="no_rawat" id="no_rawat" readonly />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-sm-12">
                                            <input type="text" class="form-control" name="no_rkm_medis" id="no_rkm_medis" readonly />
                                        </div>
                                        <div class="col-xl-7 col-md-6 col-sm-12">
                                            <input type="text" class="form-control" name="nm_pasien" id="nm_pasien" readonly />
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                            <select class="form-select" name="kd_poli" id="kd_poli" style="width:100%"></select>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                            <select class="form-select" name="kd_dokter" id="kd_dokter" style="width:100%"></select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm" id="tbRujukanInternalPoli" width="100%"></table>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <form id="formFilterRujuk">
                                    <div class="input-group">
                                        <input class="form-control filterTangal" placeholder="Select a date" id="tglAwal" name="tglAwal" value="{{ date('d-m-Y') }}">
                                        <span class="input-group-text">s.d</span>
                                        <input class="form-control filterTangal" placeholder="Select a date" id="tglAkhir" name="tglAkhir" value="{{ date('d-m-Y') }}">
                                        <button class="btn w-5 btn-secondary" type="button" onclick="" id="btnFilterRujukan">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="10" height="10" viewBox="-5 -5 24 30" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                <path d="M21 21l-6 -6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnSimpanRujukan" onclick="simpanRujukanInternal()"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        let modalRujukanInternal = $('#modalRujukanInternal');
        let modalCpptRajal = $('#modalCppt')
        let formRujukInternalPoli = $('#formRujukanInternalPoli');
        let formCpptRajal = $('#formCpptRajal');
        let formFilterRujuk = $('#formFilterRujuk');
        let poliRujuk = formRujukInternalPoli.find('select[name=kd_poli]');
        let dokterRujuk = formRujukInternalPoli.find('select[name=kd_dokter]');
        let tglAwalRujuk = formRujukInternalPoli.find('#tglAwal').val();
        let tglAkhirRujuk = formRujukInternalPoli.find('#tglAkhir').val();



        function rujukInternal(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                modalRujukanInternal.modal('show')
                formRujukInternalPoli.find('input[name=no_rawat]').val(no_rawat)
                formRujukInternalPoli.find('input[name=no_rkm_medis]').val(response.no_rkm_medis)
                formRujukInternalPoli.find('input[name=nm_pasien]').val(response.pasien.nm_pasien)
                formRujukInternalPoli.find('input[name=poliklinik]').val(response.poliklinik.nm_poli)

                selectPoliklinik(poliRujuk, modalRujukanInternal);
                selectDokter(dokterRujuk, modalRujukanInternal);
                renderTbRujuk(tglAwal, tglAkhir);
            })
        }

        $('#btnFilterRujukan').on('click', () => {
            renderTbRujuk(tglAwalRujuk, tglAkhirRujuk);
        })

        function simpanRujukanInternal() {
            const data = getDataForm('formRujukanInternalPoli', ['input', 'select']);
            $.post(`/efktp/rujuk/internal/poli`, data).done((response) => {
                if (response) {
                    alertSuccessAjax().then(() => {
                        renderTbRujuk(tglAwalRujuk, tglAkhirRujuk);
                    })
                }
            }).fail((request) => {
                alertErrorAjax(request)
            })
        }

        function renderTbRujuk(tglAwal = '', tglAkhir = '') {
            const tabelTbRujuk = new DataTable('#tbRujukanInternalPoli', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '25vh',
                ajax: {
                    url: `/efktp/rujuk/internal/poli`,
                    data: {
                        dataTable: true,
                        tglAwal: tglAwal,
                        tglAkhir: tglAkhir,
                    },
                },
                createdRow: (row, data, index) => {
                    $(row).addClass('rows-rujuk').attr('data-id', data.no_rawat);
                    if (data.pemeriksaan) {
                        $(row).addClass('bg-green-lt')
                    } else if (!isObjectEmpty(data.pemeriksaan_awal)) {
                        $(row).addClass('bg-yellow-lt')
                    }
                },
                columns: [{
                        title: 'No. Rawat',
                        data: 'reg_periksa.no_rawat',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'No RM',
                        data: 'reg_periksa.no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'Nama',
                        data: 'reg_periksa.pasien',
                        render: (data, type, row, meta) => {
                            return data.nm_pasien
                        },
                    },
                    {
                        title: 'Poli Asal',
                        data: 'poli_asal',
                        render: (data, type, row, meta) => {
                            return data.nm_poli
                        },
                    },
                    {
                        title: 'Dokter',
                        data: 'dokter_asal',
                        render: (data, type, row, meta) => {
                            return data.nm_dokter
                        },
                    },
                    {
                        title: 'Poli Tujuan',
                        data: 'poliklinik',
                        render: (data, type, row, meta) => {
                            return data.nm_poli
                        },
                    },
                    {
                        title: 'Dokter Tujuan',
                        data: 'dokter',
                        render: (data, type, row, meta) => {
                            return data.nm_dokter
                        },
                    },
                    {
                        title: '',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            return `<button type="button" class="btn btn-sm btn-danger" onclick="hapusRujukInternal('${data}')"><i class="ti ti-trash"></i></button>`;
                        },
                    },
                ]
            })
        }

        function hapusRujukInternal(no_rawat) {
            Swal.fire({
                title: "Yakin hapus ?",
                html: "Anda tidak bisa mengembalikan data ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/rujuk/internal/poli/delete`, {
                        no_rawat: no_rawat,
                    }).done((response) => {
                        alertSuccessAjax('Hapus data rujukan')
                        renderTbRujuk(tglAwalRujuk, tglAkhirRujuk);
                    }).fail((request) => {
                        alertErrorAjax(request)
                    })
                }
            });
        }

        function modalCpptRujuk(no_rawat, kd_dokter) {
            $.get(`/efktp/rujuk/internal/poli/show`, {
                no_rawat: no_rawat
            }).done((response) => {
                const regPeriksa = response.reg_periksa;
                const pasien = regPeriksa.pasien;
                const penjab = regPeriksa.penjab;
                const dokter = response.dokter;
                const poliklinik = response.poliklinik;
                const pemeriksaan = regPeriksa.pemeriksaan_dokter;
                if (!pemeriksaan) {
                    alertErrorAjax({
                        status: 204,
                        statusText: 'Belum dilakukan pemeriksaan di Poliklinik asal'
                    });
                    return false;
                }
                modalRujukanInternal.modal('hide');
                modalCpptRajal.modal('show')
                formCpptRajal.find('input[name=no_rawat]').val(no_rawat)
                formCpptRajal.find('input[name=nip]').val(response.kd_dokter)
                formCpptRajal.find('input[name=nm_dokter]').val(dokter.nm_dokter)
                formCpptRajal.find('input[name=nm_pasien]').val(pasien.nm_pasien)
                formCpptRajal.find('input[name=no_rkm_medis]').val(pasien.no_rkm_medis)
                formCpptRajal.find('input[name=tgl_lahir]').val(`${splitTanggal(pasien.tgl_lahir)} / ${regPeriksa.umurdaftar} ${regPeriksa.sttsumur}`)
                formCpptRajal.find('input[name=pembiayaan]').val(penjab.png_jawab)
                formCpptRajal.find('input[name=no_peserta]').val(pasien.no_peserta)
                formCpptRajal.find('input[name=nm_poli]').val(poliklinik.nm_poli)
                if (response.pemeriksaan) {
                    formCpptRajal.find('textarea[name=penilaian]').val(response.pemeriksaan.penilaian)
                    formCpptRajal.find('textarea[name=rtl]').val(response.pemeriksaan.rtl)
                    formCpptRajal.find('textarea[name=instruksi]').val(response.pemeriksaan.instruksi)
                    formCpptRajal.find('textarea[name=keluhan]').val(response.pemeriksaan.keluhan)
                    formCpptRajal.find('textarea[name=pemeriksaan]').val(response.pemeriksaan.pemeriksaan)
                    formCpptRajal.find('input[name=suhu_tubuh]').val(response.pemeriksaan.suhu_tubuh)
                    formCpptRajal.find('input[name=tinggi]').val(response.pemeriksaan.tinggi)
                    formCpptRajal.find('input[name=berat]').val(response.pemeriksaan.berat)
                    formCpptRajal.find('input[name=tensi]').val(response.tensi)
                    formCpptRajal.find('input[name=respirasi]').val(response.pemeriksaan.respirasi)
                    formCpptRajal.find('input[name=nadi]').val(response.pemeriksaan.nadi)
                    formCpptRajal.find('input[name=spo2]').val(response.pemeriksaan.spo2)
                    formCpptRajal.find('input[name=gcs]').val(response.pemeriksaan.gcs)
                    formCpptRajal.find('select[name=kesadaran]').find(`option:contains('${pemeriksaan.kesadaran}')`).attr('selected', 'selected')
                    formCpptRajal.find('input[name=lingkar_perut]').val(pemeriksaan.lingkar_perut)
                } else {
                    formCpptRajal.find('textarea[name=keluhan]').val(pemeriksaan.keluhan)
                    formCpptRajal.find('textarea[name=pemeriksaan]').val(pemeriksaan.pemeriksaan)
                    formCpptRajal.find('input[name=suhu_tubuh]').val(pemeriksaan.suhu_tubuh)
                    formCpptRajal.find('input[name=tinggi]').val(pemeriksaan.tinggi)
                    formCpptRajal.find('input[name=berat]').val(pemeriksaan.berat)
                    formCpptRajal.find('input[name=tensi]').val(pemeriksaan.tensi)
                    formCpptRajal.find('input[name=respirasi]').val(pemeriksaan.respirasi)
                    formCpptRajal.find('input[name=nadi]').val(pemeriksaan.nadi)
                    formCpptRajal.find('input[name=spo2]').val(pemeriksaan.spo2)
                    formCpptRajal.find('input[name=gcs]').val(pemeriksaan.gcs)
                    formCpptRajal.find('select[name=kesadaran]').find(`option:contains('${pemeriksaan.kesadaran}')`).attr('selected', 'selected')
                    formCpptRajal.find('input[name=lingkar_perut]').val(pemeriksaan.lingkar_perut)
                }

                setRiwayat(regPeriksa.no_rkm_medis)
                if (pasien.alergi.length) {
                    const alergi = pasien.alergi;
                    inputAlergi.empty()
                    alergi.forEach((resAlergi) => {
                        const optionAlergi = new Option(resAlergi.alergi, resAlergi.alergi, true, true);
                        inputAlergi.append(optionAlergi).trigger('change');
                    });
                    selectAlergi(inputAlergi, $('#formCpptRajal'))
                } else {
                    inputAlergi.empty()
                    selectAlergi(inputAlergi, $('#formCpptRajal'))
                }
            })
        }
    </script>
@endpush

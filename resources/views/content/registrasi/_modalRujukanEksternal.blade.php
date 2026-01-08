<div class="modal modal-blur fade" id="modalRujukanEksternal" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Rujukan Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <form action="" id="formRujukanEksternal">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                    <div class="row p-3 gy-2">
                                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                            <label for="no_rawat">No. Rawat</label>
                                            <input type="text" class="form-control" name="no_rawat" id="no_rawat" readonly />
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                                            <label for="no_rkm_medis">Pasien</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="no_rkm_medis" id="no_rkm_medis" readonly />
                                                <input type="text" class="form-control w-50" name="nm_pasien" id="nm_pasien" readonly />
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="no_rkm_medis">No. Rujukan</label>
                                            <input type="text" class="form-control" name="no_rujuk" id="no_rujuk">
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="no_rkm_medis">Tgl & Jam Rujuk</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control filterTangal" name="tgl_rujuk" id="tgl_rujuk" value="{{ date('d-m-Y') }}">
                                                <input type="text" class="form-control jam" name="jam" id="jam" value="{{ date('H:i:s') }}" readonly />
                                                <span class="input-group-text">
                                                    <input class="form-check-input m-0" type="checkbox" checked="" id="" name="checkjamRujuk" onchange="toggleTime(this)">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                            <label for="kd_dokter">Dokter Perujuk</label>
                                            <select class="form-select" name="kd_dokter" id="kd_dokter" style="width:100%" data-dropdown-parent="#modalRujukanEksternal"></select>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                            <label for="keterangan_diagnosa">Ket. Diagnosa</label>
                                            <select class="form-select" name="keterangan_diagnosa" id="keterangan_diagnosa" data-tags="true" style="width:100%" data-dropdown-parent="#modalRujukanEksternal"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                    <div class="row p-3 gy-2">
                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                            <label for="rujuk_ke">FK/RS. Rujukan</label>
                                            <select class="form-select" name="rujuk_ke" id="rujuk_ke" data-dropdown-parent="#modalRujukanEksternal" data-tags="true" style="width:100%"></select>
                                        </div>
                                        <div class="col-xl-3 col-md-3 col-sm-12">
                                            <label for="kat_rujuk">Kat. Rujuk</label>
                                            <select class="form-select form-select-2" name="kat_rujuk" id="kat_rujuk" data-dropdown-parent="#modalRujukanEksternal" style="width:100%">
                                                <option value="-">-</option>
                                                <option value="Anak">Anak</option>
                                                <option value="Bedah">Bedah</option>
                                                <option value="Kebidanan">Kebidanan</option>
                                                <option value="Non Bedah">Non Bedah</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-md-3 col-sm-12">
                                            <label for="ambulance">Ambulance</label>
                                            <select class="form-select form-select-2" name="ambulance" id="ambulance" data-dropdown-parent="#modalRujukanEksternal" style="width:100%">
                                                <option value="-">-</option>
                                                <option value="AGD">AGD</option>
                                                <option value="Sendiri">Sendiri</option>
                                                <option value="Swasta">Swasta</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12">
                                            <label for="keterangan">Keterangan</label>
                                            <select class="form-select" name="keterangan" id="keterangan" style="width:100%" data-dropdown-parent="#modalRujukanEksternal">

                                            </select>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12">
                                            <button type="button" class="btn btn-success" id="btnSimpanRujukan" onclick="simpanRujukanEksternal()"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped nowrap" id="tbRujukanEksternal" width="100%"></table>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <form id="formFilterRujukEksternal">
                                    <div class="input-group">
                                        <input class="form-control filterTangal" placeholder="Select a date" id="tglAwal" name="tglAwal" value="{{ date('d-m-Y') }}">
                                        <span class="input-group-text">s.d</span>
                                        <input class="form-control filterTangal" placeholder="Select a date" id="tglAkhir" name="tglAkhir" value="{{ date('d-m-Y') }}">
                                        <button class="btn w-5 btn-secondary" type="button" onclick="" id="btnFilterRujukanEksternal">
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

                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@include('content.registrasi.rujukEksternal._modalPrintRujukEksternal')
@push('script')
    <script>
        let modalRujukanEksternal = $('#modalRujukanEksternal');
        let modalPrintRujukEksternal = $('#modalPrintRujukEksternal');
        let formRujukanEksternal = $('#formRujukanEksternal');
        let formFilterRujukEksternal = $('#formFilterRujukEksternal');
        let tglAwalRujukEks = formRujukanEksternal.find('#tglAwal').val();
        let tglAkhirRujukEks = formRujukanEksternal.find('#tglAkhir').val();
        let diagnosaRujuk = formRujukanEksternal.find('select[name=keterangan_diagnosa]')
        let linkSearchPemeriksaan = $('#linkSearchPemeriksaan')
        const btnSimpanRujukanEksternal = modalRujukanEksternal.find('#btnSimpanRujukan')

        function rujukEksternal(no_rawat) {

            loadRujukEksternal(tglAwalRujukEks, tglAkhirRujukEks)
            getRegDetail(no_rawat).done((response) => {
                modalRujukanEksternal.modal('show')
                formRujukanEksternal.find('input[name=no_rawat]').val(no_rawat)
                formRujukanEksternal.find('input[name=no_rkm_medis]').val(response.no_rkm_medis)
                formRujukanEksternal.find('input[name=nm_pasien]').val(response.pasien.nm_pasien)
                dokterRujuk = formRujukanEksternal.find('select[name=kd_dokter]')
                selectDokter(dokterRujuk, modalRujukanEksternal);
                selectDiagnosa(diagnosaRujuk, modalRujukanEksternal);
                formRujukanEksternal.find('input[name=checkjamRujuk]').trigger('change')
                linkSearchPemeriksaan.attr('onclick', `showPemeriksaanRalan('${no_rawat}')`)


            })
            $.get(`/efktp/rujuk/keluar/detail`, {
                no_rawat: no_rawat
            }).done((response) => {
                if (!Object.keys(response).length) {
                    setNoRujukEksternal().done((result) => {
                        formRujukanEksternal.find('input[name=no_rujuk]').val(result)
                    });
                } else {
                    const {
                        dokter
                    } = response
                    formRujukanEksternal.find('input[name=no_rujuk]').val(response.no_rujuk)
                    const optDokter = new Option(dokter.nm_dokter, response.kd_dokter, true, true);
                    const optDiagnosa = new Option(response.keterangan_diagnosa, response.keterangan_diagnosa, true, true);
                    const optRujuk = new Option(response.rujuk_ke, response.rujuk_ke, true, true)
                    const optKatRujuk = new Option(response.kat_rujuk, response.kat_rujuk, true, true)
                    const optAmbulance = new Option(response.ambulance, response.ambulance, true, true)
                    const optKeterangan = new Option(response.keterangan, response.keterangan, true, true)

                    formRujukanEksternal.find('select[name=kd_dokter]').append(optDokter).trigger('change')
                    formRujukanEksternal.find('select[name=keterangan_diagnosa]').append(optDiagnosa).trigger('change')
                    formRujukanEksternal.find('select[name=rujuk_ke]').append(optRujuk).trigger('change')
                    formRujukanEksternal.find('select[name=kat_rujuk]').append(optKatRujuk).trigger('change')
                    formRujukanEksternal.find('select[name=ambulance]').append(optAmbulance).trigger('change')
                    formRujukanEksternal.find('select[name=keterangan]').append(optKeterangan).trigger('change')
                    formRujukanEksternal.find('input[name=no_rujuk]').val(response.no_rujuk)
                    formRujukanEksternal.find('input[name=tgl_rujuk]').val(splitTanggal(response.tgl_rujuk))
                    formRujukanEksternal.find('input[name=jam]').val(response.jam)
                    formRujukanEksternal.find('input[name=checkjamRujuk]').prop('checked', false).trigger('change')
                    btnSimpanRujukanEksternal.html('<i class="ti ti-device-floppy me-2"></i> Ubah Rujukan')
                    formRujukanEksternal.find('input[name=no_rujuk]').addClass('is-valid').attr('disabled', true)
                }
            })
        }

        modalRujukanEksternal.on('hidden.bs.modal', () => {
            formRujukanEksternal.find('label').removeClass('text-danger');
            formRujukanEksternal.trigger('reset');
            formRujukanEksternal.find('select').val('-').trigger('change');
            formRujukanEksternal.find('input[name=no_rujuk]').hasClass('is-valid') ?
                formRujukanEksternal.find('input[name=no_rujuk]').attr('disabled', true) :
                formRujukanEksternal.find('input[name=no_rujuk]').removeClass('is-valid').prop('disabled', false)

        })

        function toggleTime(param) {
            const isChecked = $(param).is(':checked')
            if (!isChecked) {
                clearInterval(setTime)
                timeDisplay.removeAttr('readonly')
            } else {
                refreshTime()
                timeDisplay.attr('readonly', 'true')
            }
        }

        function setNoRujukEksternal() {
            return $.get(`/efktp/rujuk/keluar/nomor`)
        }

        const selectEksternal = (id) => {
            return formRujukanEksternal.find(`select[name=${id}]`)
        }

        selectEksternal('keterangan').select2({
            tags: true,
            delay: 1,
            scrollAfterSelect: true,
            cache: true,
            ajax: {
                url: `/efktp/rujuk/keluar/keterangan`,
                dataType: 'JSON',

                data: (params) => {
                    return {
                        keterangan: params.term
                    }
                },
                processResults: (data) => {
                    return {
                        results: data.map((item) => {
                            return {
                                id: item.keterangan,
                                text: item.keterangan,
                                detail: item.keterangan,
                            }
                        })
                    }
                }

            },
        })

        selectEksternal('rujuk_ke').select2({
            tags: true,
            delay: 1,
            scrollAfterSelect: true,
            cache: true,
            ajax: {
                url: `/efktp/rujuk/keluar/faskes`,
                dataType: 'JSON',

                data: (params) => {
                    return {
                        faskses: params.term
                    }
                },
                processResults: (data) => {
                    return {
                        results: data.map((item) => {
                            return {
                                id: item.rujuk_ke,
                                text: item.rujuk_ke,
                                detail: item.rujuk_ke,
                            }
                        })
                    }
                }

            },
        })

        function loadRujukEksternal(tglAwal = '', tglAkhir = '') {
            const tbRujukEksternal = new DataTable('#tbRujukanEksternal', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '25vh',
                scrollX: true,
                ajax: {
                    url: `/efktp/rujuk/keluar`,
                    data: {
                        dataTable: true,
                        tglAwal: tglAwal,
                        tglAkhir: tglAkhir,
                    },
                },
                columns: [{
                        title: '',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {

                            return `<button class="btn btn-sm btn-primary" onclick="printRujukEksternal('${data}')"><i class="ti ti-printer"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteRujukEksternal('${data}')"><i class="ti ti-trash"></i></button>`;
                        }
                    },
                    {
                        title: 'No. Rujukan',
                        data: 'no_rujuk',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'No. Rawat',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Pasien',
                        data: 'pasien.no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return `${row.pasien.nm_pasien}`;
                        }
                    },
                    {
                        title: 'Tanggal & Jam',
                        data: 'tgl_rujuk',
                        render: (data, type, row, meta) => {
                            return `${splitTanggal(data)} ${row.jam}`;
                        }
                    },
                    // {
                    //     title: 'Dokter & Poli',
                    //     data: 'poliklinik.nm_poli',
                    //     render: (data, type, row, meta) => {
                    //         return `${data} <br/> ${row.dokter.nm_dokter}`;
                    //     }
                    // },
                    {
                        title: 'Rujuk Ke',
                        data: 'rujuk_ke',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Diagnosa',
                        data: 'keterangan_diagnosa',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                ]

            });
            // .on('draw', () => {
            //     tbRujukEksternal.columns.adjust().draw()
            // });
        }
        $('#btnFilterRujukanEksternal').on('click', (e) => {
            e.preventDefault()
            const tglAwal = formFilterRujukEksternal.find('#tglAwal').val()
            const tglAkhir = formFilterRujukEksternal.find('#tglAkhir').val()
            loadRujukEksternal(tglAwal, tglAkhir)
        })

        function simpanRujukanEksternal() {
            loadingAjax();
            const data = getDataForm('formRujukanEksternal', ['input', 'select']);
            data['keterangan_diagnosa'] = formRujukanEksternal.find('select[name="keterangan_diagnosa"] option:selected').text();
            $.post(`/efktp/rujuk/keluar`, data).done((response) => {
                setStatusLayan(data['no_rawat'], 'Dirujuk').done((response) => {
                    loadingAjax().close();
                    loadRujukEksternal(tglAwal, tglAkhir)
                    formRujukanEksternal.find('input[name=no_rujuk]').val(data['no_rujuk'])
                    formRujukanEksternal.find('label').removeClass('text-danger');
                    formRujukanEksternal.find('input[name=checkjamRujuk]').prop('checked', false).trigger('change')
                    btnSimpanRujukanEksternal.html('<i class="ti ti-device-floppy me-2"></i> Ubah Rujukan')
                    formRujukanEksternal.find('input[name=no_rujuk]').addClass('is-valid').attr('disabled', true)
                    alertSuccessAjax('Berhasil')
                })
            }).fail((request, error, status) => {
                if (request.status === 500) {
                    alertErrorAjax(request)
                    return false;
                }
                const err = request.responseJSON.errors
                swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: `Error Code : ${request.status} ${status} <br> <strong class="text-danger">${request.responseJSON.message}</strong>`,
                }).then(() => {
                    setAlertLabel(err, formRujukanEksternal)
                })

            })
        }

        function setAlertLabel(errors, element) {
            for (let key in errors) {
                const errorMessages = errors[key];
                const errorMessageElement = element.find(`label[for=${key}`);
                if (errorMessageElement) {
                    if (errorMessages.length > 0) {
                        errorMessageElement.addClass('text-danger');
                    }
                }
            }
        }

        function deleteRujukEksternal(no_rawat) {
            Swal.fire({
                title: "Yakin hapus rujukan ?",
                html: `Data yang dihapus tidak dapat dikembalikan`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/rujuk/keluar/delete`, {
                        no_rawat: no_rawat,
                    }).done((response) => {
                        alertSuccessAjax('Hapus data rujukan').then(() => {
                            btnSimpanRujukanEksternal.html('<i class="ti ti-device-floppy me-2"></i> Ubah Rujukan')
                            formRujukanEksternal.find('input[name=no_rujuk]').hasClass('is-valid') ?
                                formRujukanEksternal.find('input[name=no_rujuk]').attr('disabled', true) :
                                formRujukanEksternal.find('input[name=no_rujuk]').removeClass('is-valid').prop('disabled', false)
                            loadRujukEksternal(tglAwal, tglAkhir)
                        })
                    }).fail((request) => {
                        alertErrorAjax(request)
                    })
                }
            });

        }

        function printRujukEksternal(no_rawat) {
            modalPrintRujukEksternal.modal('show');
            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            modalPrintRujukEksternal.find('#print').on('load', (e) => {
                if (e.currentTarget.src) {
                    toast('Berhasil');
                }

            })
            modalPrintRujukEksternal.find("#print").removeAttr('src').attr('src', `/efktp/rujuk/keluar/print?no_rawat=${no_rawat}`)
        }
        modalPrintRujukEksternal.on('hidden.bs.modal', () => {
            modalPrintRujukEksternal.find("#print").removeAttr('src');
        });
    </script>
@endpush

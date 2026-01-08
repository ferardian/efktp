@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" id="cardListTemplate">
                <div class="card">
                    <div class="card-body">
                        <div id="table-default" class="table-responsive">
                            <table class="table table-sm nowrap table-striped" id="tbTemplateRacikan" width="100%">
                            </table>
                        </div>
                    </div>
                    <div class="card-footer justify-content-between d-flex">
                        <select class="form-select form-select-sm me-2" id="selectDokterTemplate"
                                style="width:30% !important">

                        </select>
                        <button type="button" class="btn btn-primary" id="btnCreateTemplateRacikan"
                                onclick="createTemplateRacikan()">
                            <i class="ti ti-plus me-2"></i> Buat Template
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 d-none" id="cardTemplate">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Form Template Racikan</h3>
                        <button type="button" class="btn-close" onclick="closeEdit()"></button>
                    </div>
                    <div class="card-body">
                        <form action="" id="formTemplateResep">
                            <div class="mb-2">
                                <input type="hidden" class="form-label" id="id" name="id">
                                <label for="nm_racikan" class="form-label">Nama Racikan</label>
                                <input type="text" class="form-control" id="nm_racik" name="nm_racik" value="">
                            </div>
                            <div class="mb-2">
                                <label for="nm_racikan" class="form-label">Dokter</label>
                                <select class="form-select" id="kd_dokter" name="kd_dokter" style="width:100%"></select>
                            </div>
                            <div class="mb-2">
                                <label for="nm_racikan" class="form-label">Obat</label>
                                <div id="listObat">

                                </div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" id="btnTambahObat"><i
                                            class="ti ti-plus me-2"></i> Tambah Obat
                                </button>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-success" style="width:100%" id="btnSimpanTemplate">
                                    <i class="ti ti-device-floppy ms-2"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        const cardTemplate = $('#cardTemplate');
        const cardListTemplate = $('#cardListTemplate');
        const selectObat = $('.obat')
        const formTemplateResep = $('#formTemplateResep')
        const listObat = $('#listObat')
        const btnTambahObat = $('#btnTambahObat')
        const btnSimpanTemplate = $('#btnSimpanTemplate')
        const dokter = formTemplateResep.find('select[name=kd_dokter]')

        const selectDokterTemplate = $('#selectDokterTemplate');

        $(document).ready(() => {
            tbTemplateRacikan()
            selectDokter(selectDokterTemplate, '#cardListTemplate');

        })

        function clearBtnEditOnTable() {

            $('.btn-edit-template').each((index, element) => {
                if (element.id != `edit${id}`) {
                    $(element).removeAttr('onclick').removeClass('btn-warning').addClass('btn-outline-warning').attr('onclick', `editTemplate(${element.id.replace('edit', '')})`)
                }
            })
        }

        selectDokterTemplate.on('select2:select', (e) => {
            const kd_dokter = e.params.data.id;
            tbTemplateRacikan(kd_dokter);
        })

        selectDokterTemplate.on('select2:unselect', (e) => {
            tbTemplateRacikan();
        })

        function createTemplateRacikan() {
            cardTemplate.removeClass('d-none');
            cardListTemplate.removeClass('col-lg-12').addClass('col-lg-8');
            dokter.val(null).trigger('change');
            listObat.empty();
            selectDokter(dokter, '');
            clearBtnEditOnTable();


        }

        function tbTemplateRacikan(kd_dokter = '') {
            const tbTemplate = new DataTable('#tbTemplateRacikan', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: setTableHeight(),
                scrollX: true,
                fixedColumns: true,
                ajax: {
                    url: `/efktp/resep/racikan/template/get`,
                    data: {
                        kd_dokter: kd_dokter,
                        datatable: true,
                    },
                },
                columns: [{
                    title: 'Racikan',
                    data: 'nm_racik',
                    render: (data, type, row, meta) => {
                        return data;
                    },

                }, {
                    title: 'Dokter',
                    data: 'dokter.nm_dokter',
                    render: (data, type, row, meta) => {
                        return data;
                    },
                },
                    {
                        title: 'Isi/Obat',
                        data: 'detail',
                        render: (data, type, row, meta) => {
                            const detail = data.map((obat) => {
                                return `<li>${obat.barang.nama_brng}</li>`
                            }).join('');
                            return `<ul>${detail}</ul>`
                        },
                    },
                    {
                        title: '',
                        data: 'id',
                        render: (data, type, row, meta) => {
                            return `<a href="javascript:void(0)" onclick="editTemplate(${data})" class="btn btn-outline-warning btn-sm btn-edit-template" id="edit${data}"><i class="ti ti-pencil"></i> Edit</a>
                         <a href="javascript:void(0)" onclick="hapusTemplate(${data})" class="ms-2 btn btn-outline-danger btn-sm"><i class="ti ti-trash"></i> Hapus</a>`
                        },
                    }
                ]
            })
        }

        function editTemplate(id) {
            cardTemplate.removeClass('d-none');
            cardListTemplate.removeClass('col-lg-12').addClass('col-lg-8')
            clearBtnEditOnTable();

            $(`#edit${id}`).removeAttr('onclick').removeClass('btn-outline-warning').addClass('btn-warning').attr('onclick', `closeEdit(${id})`)

            $.get(`/efktp/resep/racikan/template/get`, {
                id: id
            }).done((response) => {
                listObat.empty();
                formTemplateResep.find('input[name=nm_racik]').val(response.nm_racik)
                formTemplateResep.find('input[name=id]').val(response.id)
                selectDokter(dokter, '');
                const optDokter = new Option(response.dokter.nm_dokter, response.kd_dokter, true, true);
                dokter.append(optDokter).trigger('change')

                response.detail.map((detail, index) => {
                    const select = `
                        <div class="row gy-2 mb-2" id="rowObat${index}">
                                <div class="col-11">
                                    <select class="form-select mb-2" id="obat${index}" style="width:100%" name="kode_brng"></select>
                                </div>
                                <div class="col-1">
                                    <a href="javascript:void(0)" onclick="hapusObatTemplate('${index}')" class="text-danger mt-1"><i class="ti ti-trash"></i></a>
                                </div>
                        </div>`
                    listObat.append(select);
                    const barangSelect = $(`#obat${index}`);
                    selectDataBarang(barangSelect, '');

                    const optObat = new Option(detail.barang.nama_brng, detail.kode_brng, true, true);
                    barangSelect.append(optObat).trigger('change')

                })
            })
        }

        function closeEdit(id) {
            cardTemplate.addClass('d-none');
            cardListTemplate.addClass('col-lg-12').removeClass('col-lg-8')
            listObat.empty();
            clearBtnEditOnTable();
            // $(`#edit${id}`).removeAttr('onclick').removeClass('btn-warning').addClass('btn-outline-warning').attr('onclick', `editTemplate(${id})`)
        }

        btnTambahObat.on('click', (e) => {
            const index = formTemplateResep.find('select[name=kode_brng]').length;
            const select = `<div class="row gy-2 mb-2" id="rowObat${index}">
                          <div class="col-11">
                                <select class="form-select mb-2" id="obat${index}" style="width:100%" name="kode_brng"></select>
                            </div>
                            <div class="col-1">
                                <a href="javascript:void(0)" onclick="hapusObatTemplate('${index}')" class="text-danger mt-1"><i class="ti ti-trash"></i></a>
                            </div>
                            </div>`
            listObat.append(select);
            const barangSelect = $(`#obat${index}`);
            selectDataBarang(barangSelect, '');
        })

        btnSimpanTemplate.on('click', (e) => {
            // e.preventDefault();
            const kode_brng = formTemplateResep.find('select[name=kode_brng]');
            const kd_dokter = formTemplateResep.find('select[name=kd_dokter]').val()
            const nm_racik = formTemplateResep.find('input[name=nm_racik]').val()
            const id = formTemplateResep.find('input[name=id]').val()
            let arrayBarang = new Array();
            kode_brng.each((index, element) => {
                return arrayBarang.push({
                    kode_brng: element.value,
                    nama_brng: element.html
                })
            })

            $.post(`/efktp/resep/racikan/template/update`, {
                id: id,
                kd_dokter: kd_dokter,
                nm_racik: nm_racik,
                obat: arrayBarang
            }).done((response) => {
                alertSuccessAjax('Template berhasil diubah').then(() => {
                    tbTemplateRacikan()
                    listObat.empty();
                    cardTemplate.addClass('d-none');
                    cardListTemplate.removeClass('col-lg-11').addClass('col-lg-12');
                })


            })

        })

        function hapusTemplate(id) {
            Swal.fire({
                title: "Peringatan",
                html: "Yakin hapus temlpate racikan ini ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Hapus",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/resep/racikan/template/delete`, {
                        id: id
                    }).done((response) => {
                        alertSuccessAjax('Template racikan dihapus').then(() => {
                            tbTemplateRacikan()
                            closeEdit(id);
                            listObat.empty();
                        })
                    })
                }
            });

        }

        function hapusObatTemplate(id) {
            $(`#rowObat${id}`).remove();
        }
    </script>
@endpush

@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-2">
            <div class="col-xl-7 col-lg-7 col-lg-12 col-md-12 col-sm-12" id="cardListTemplate">
                <div class="card">
                    <div class="card-body">

                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbPaketObat">
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select-2" name="filter_poli" id="filter_poli"
                                        data-dropdown-parent="body" style="max-width:25% !important"></select>

                            </div>
                            <div class="col-6 ms-auto">
                                <button type="button" class="btn btn-primary" id="btnCreateTemplateRacikan"
                                        onclick="resetFormPaketObat()">
                                    <i class="ti ti-plus me-2"></i> Buat Baru
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xl-5 col-md-12 col-sm-12" id="cardPaketObat">
                <form action="" id="formPaketObat">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Paket Obat</h5>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="mb-2">
                                        <label for="nm_racikan" class="form-label">Nama Paket</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="">
                                        <input type="hidden" class="form-control" id="id" name="id" value="">
                                    </div>

                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <label for="nm_racikan" class="form-label">Poliklinik</label>
                                    <select class="form-select-2" id="kd_poli" name="kd_poli"
                                            style="width:100%"></select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="kode_brng[]" class="form-label">Obat</label>
                                <div class="form-fieldset p-2 bg-light">

                                    <div id="listObat" class="">

                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" id="btnTambahObatPaket"><i
                                                class="ti ti-plus"></i> Obat
                                    </button>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="racikan[]" class="form-label">Racikan</label>
                                <div class="form-fieldset p-2 bg-light">
                                    <div id="listObatRacikan" class="">

                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" id="btnTambahRacikPaket"><i
                                                class="ti ti-plus"></i> Racikan
                                    </button>

                                </div>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-success" style="width:100%"
                                        id="btnCreatePaketObat" onclick="createPaketObat()">
                                    <i class="ti ti-device-floppy ms-2"></i> Simpan Paket Obat
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/template" id="rowContentPaketObat">
        <div class="d-flex gap-1 justify-content-between content-obat mb-1">
            <select class="form-select-2" data-dropdown-parent="body" style="width: 90%" name="kode_brng[]"></select>
            <input class="form-control jumlah w-25" name="jumlah[]" placeholder="Jumlah" value="1"/>
            <div class="input-group w-50">
                <input class="form-control aturan_pakai" name="aturan_pakai[]" placeholder="Aturan Pakai" value="-"
                       onfocus="return removeZero(this)" onblur="isEmpty(this)"/>
                <button class="btn btn-danger delete">
                    <i class="ti ti-x"></i>
                </button>
            </div>

        </div>
    </script>
    <script type="text/template" id="rowContentPaketRacik">
        <div class="d-flex gap-1 justify-content-between content-racik mb-1">
            <select class="form-select-2 template" data-dropdown-parent="body"
                    name="id_template[]"></select>
            <select class="form-select-2 metode w-50" data-dropdown-parent="body" style=""
                    name="kd_racik[]"></select>
            <input class="form-control jumlah w-25" name="jumlah[]" placeholder="Jumlah" value="1"/>
            <div class="input-group w-50">
                <input class="form-control aturan_pakai" name="aturan_pakai[]" placeholder="Aturan Pakai" value="-"
                       onfocus="return removeZero(this)" onblur="isEmpty(this)"/>
                <button type="button" class="btn btn-danger delete-racik">
                    <i class="ti ti-x"></i>
                </button>
            </div>

        </div>
    </script>
@endsection
@push('script')
    <script>
        $(document).ready(() => {
            const formPaketObat = $('#formPaketObat');
            const selectPoliPaket = $('#cardListTemplate').find('#filter_poli')
            renderTablePaketObat();
            bindEventHandlers();
            selectPoliklinik(selectPoliPaket, $('body'))
            selectPoliklinik(formPaketObat.find('#kd_poli'), $('body'));
        });


        function renderTablePaketObat() {
            new DataTable('#tbPaketObat', {
                responsive: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                scrollY: setTableHeight(),
                ajax: {url: "/efktp/paket-obat/datatable"},
                columns: [
                    {title: 'Paket', data: 'nama', width: '15%'},
                    {title: 'Poliklinik', data: 'poliklinik.nm_poli', width: '20%'},
                    {title: 'Umum', data: 'umum', render: renderListObat},
                    {title: 'Racikan', data: 'racikan', render: renderListRacikan},
                    {title: '', data: 'id', render: renderActionButtons}
                ]
            });
        }

        function renderListObat(data) {
            if (!data) return '';
            return data.map(item =>
                `<span class="badge bg-purple-lt">${item.databarang.nama_brng}</span>`
            ).join(', ');
        }

        function renderListRacikan(data) {
            if (!data) return '';
            return data.map(item =>
                `<span class="badge bg-orange-lt">${item.template.nm_racik}</span>`
            ).join(', ');
        }

        function renderActionButtons(id) {
            return `<div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-ghost-warning" onclick="editPaketObat(${id})">
                            <i class="ti ti-pencil"></i> Ubah
                        </button>
                        <button class="btn btn-sm btn-ghost-danger" onclick="deletePaketObat(${id})">
                            <i class="ti ti-trash"></i> Hapus
                        </button>
                    </div>`;
        }

        function bindEventHandlers() {
            $('#btnTambahObatPaket').on('click', addObatRow);
            $('#btnTambahRacikPaket').on('click', addRacikRow);
            $('#listObat').on('click', '.delete', (e) => $(e.target).closest('.content-obat').remove());
            $('#listObatRacikan').on('click', '.delete-racik', (e) => $(e.target).closest('.content-racik').remove());
        }


        function deletePaketObat(id) {
            Swal.fire({
                title: 'Hapus Paket Obat',
                html: `Yakin hapus data paket obat ini ?`,
                icon: 'warning',
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Hapus",
                cancelButtonText: "Tidak, Batalkan",
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/efktp/paket-obat/${id}`,
                        type: 'DELETE',
                        success: () => {
                            showToast('Berhasil Hapus Paket Obat');
                            renderTablePaketObat();
                        },
                        error: (xhr) => {
                            showToast('Gagal menghapus data: ' + xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        }

        function editPaketObat(id) {
            const form = $('#formPaketObat');

            $.get(`/efktp/paket-obat/${id}`).done((response) => {
                const {data} = response;
                if (!data) return;

                prepareFormForEdit(form, data);
                populateObatRows(data.umum);
                populateRacikanRows(data.racikan);
            });
        }

        function createPaketObat() {
            const data = collectFormData();

            $.post(`/efktp/paket-obat`, data)
                .done(() => {
                    resetFormPaketObat();
                    renderTablePaketObat();
                    showToast('Berhasil membuat paket obat');
                })
                .fail((xhr) => showToast(xhr.responseJSON.message, 'error'));
        }


        function prepareFormForEdit(form, data) {
            $('#btnCreatePaketObat')
                .removeClass('btn-success')
                .addClass('btn-warning')
                .html(`<i class="ti ti-pencil"></i> Update Paket Obat`);

            form.find('input[name=id]').val(data.id);
            form.find('input[name=nama]').val(data.nama);

            const option = new Option(data.poliklinik.nm_poli, data.kd_poli, true, true);
            form.find('#kd_poli').append(option).trigger('change');
        }

        function collectFormData() {
            const umum = [];
            const racik = []
            const form = $('#formPaketObat')

            $('#listObat .content-obat').each(function () {
                umum.push({
                    kode_brng: $(this).find('.form-select-2').val(),
                    jumlah: $(this).find('.jumlah').val(),
                    aturan_pakai: $(this).find('.aturan_pakai').val()
                });
            });

            $('#listObatRacikan .content-racik').each(function () {
                racik.push({
                    id_template: $(this).find('.template').val(),
                    jumlah: $(this).find('.jumlah').val(),
                    kd_racik: $(this).find('.metode').val(),
                    aturan_pakai: $(this).find('.aturan_pakai').val()
                });
            });

            return {
                id: form.find('#id').val(),
                nama: form.find('#nama').val(),
                kd_poli: form.find('#kd_poli').val(),
                umum,
                racik
            };
        }

        function resetFormPaketObat() {
            $('#formPaketObat').find('input, select').val('').trigger('change');
            $("#listObat, #listObatRacikan").empty();
            $('#btnCreatePaketObat')
                .removeClass('btn-warning')
                .addClass('btn-success')
                .html(`<i class="ti ti-plus"></i> Buat Paket Obat`);
        }


        function addObatRow() {
            const list = $('#listObat');
            const row = $($('#rowContentPaketObat').html());
            list.append(row);
            selectDataBarang(row.find('.form-select-2'), $('body'));
        }

        function addRacikRow() {
            const list = $('#listObatRacikan');
            const row = $($('#rowContentPaketRacik').html());
            list.append(row);
            selectMetode(row.find('.metode'), $('body'));
            const optionMetode = new Option('Puyer', 'R01', false, true)
            row.find('.metode').append(optionMetode).trigger('change')

            initSelectTemplate(row.find('.template'));
        }

        function populateObatRows(items) {
            const list = $('#listObat');
            list.empty();

            items.forEach(item => {
                const row = $($('#rowContentPaketObat').html());
                list.append(row);

                const select = row.find('.form-select-2');
                const option = new Option(item.databarang.nama_brng, item.databarang.kode_brng, true, true);
                select.append(option).trigger('change');
                selectDataBarang(select, $('body'));

                row.find('.jumlah').val(item.jumlah ?? 1);
                row.find('.aturan_pakai').val(item.aturan_pakai ?? '');
            });
        }

        function populateRacikanRows(items) {
            const list = $('#listObatRacikan');
            list.empty();

            items.forEach(item => {
                const row = $($('#rowContentPaketRacik').html());
                list.append(row);

                const metodeSelect = row.find('.metode');
                selectMetode(metodeSelect, $('body'));
                metodeSelect.append(new Option(item.metode.nm_racik, item.kd_racik, true, true)).trigger('change');

                const templateSelect = row.find('.template');
                initSelectTemplate(templateSelect);
                templateSelect.append(new Option(item.template.nm_racik, item.template_id, true, true)).trigger('change');
            });
        }


        function initSelectTemplate(select) {
            select.select2({
                placeholder: 'Pilih Template Racikan...',
                width: '100%',
                ajax: {
                    url: '/efktp/resep/racikan/template/search',
                    dataType: 'JSON',
                    data: params => ({nm_racik: params.term}),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.nm_racik,
                            detail: item.detail,
                            dokter: item.dokter,
                        }))
                    })
                },
                escapeMarkup: m => m,
                templateResult: renderTemplateOption,
                templateSelection: item => item.text || item.id
            })
        }

        function renderTemplateOption(item) {
            if (item.loading) return item.text;
            if (!item.detail) return `<div>${item.text}</div>`;

            const dokterNama = item.dokter ? item.dokter.nm_dokter : '-';
            let html = `
        <div class="racikan-item">
            <div><strong>${item.text}</strong></div>
            <small style="color:#888;">Dokter: ${dokterNama}</small>
            <ul style="margin:4px 0 0 10px; padding:0;">`;

            item.detail.forEach(d => {
                html += `
            <li style="font-size:9px; list-style:none;">
                ${d.barang.nama_brng}
                <span style="color:#999;">(${d.barang.kode_sat})</span>
                — <strong>${d.barang.ralan.toLocaleString()}</strong>
            </li>`;
            });

            html += `</ul></div>`;
            return html;
        }
    </script>

    {{--    <script>--}}
    {{--        $(document).ready(() => {--}}
    {{--            renderTablePaketObat()--}}
    {{--            const formPaketObat = $('#formPaketObat')--}}
    {{--            selectPoliklinik(formPaketObat.find('#kd_poli'), $('body'))--}}
    {{--        })--}}

    {{--        function renderTablePaketObat() {--}}
    {{--            const tableResepPaketan = new DataTable('#tbPaketObat', {--}}
    {{--                responsive: true,--}}
    {{--                stateSave: true,--}}
    {{--                serverSide: true,--}}
    {{--                destroy: true,--}}
    {{--                processing: true,--}}
    {{--                fixedHeader: false,--}}
    {{--                scrollY: setTableHeight(),--}}
    {{--                ajax: {--}}
    {{--                    url: "/efktp/paket-obat/datatable",--}}
    {{--                },--}}
    {{--                columns: [{--}}
    {{--                    title: 'Paket',--}}
    {{--                    data: 'nama',--}}
    {{--                    width: '15%',--}}
    {{--                }, {--}}
    {{--                    title: "Poliklinik",--}}
    {{--                    data: 'poliklinik.nm_poli',--}}
    {{--                    width: '20%'--}}
    {{--                }, {--}}
    {{--                    title: 'Umum',--}}
    {{--                    data: 'umum',--}}
    {{--                    render: (data, type, row) => {--}}
    {{--                        if (!data) {--}}
    {{--                            return;--}}
    {{--                        }--}}
    {{--                        return data.map((item, index) => {--}}
    {{--                            return `<span class="badge bg-purple-lt">--}}
    {{--                                    ${item.databarang.nama_brng}--}}
    {{--                                </span>`--}}
    {{--                        }).join(', ');--}}
    {{--                    }--}}
    {{--                }, {--}}
    {{--                    title: 'Racikan',--}}
    {{--                    data: 'racikan',--}}
    {{--                    render: (data, type, row) => {--}}
    {{--                        if (!data) {--}}
    {{--                            return;--}}
    {{--                        }--}}
    {{--                        return data.map((item, index) => {--}}
    {{--                            return `<span class="badge bg-orange-lt">${item.template.nm_racik}</span>`--}}
    {{--                        }).join(', ')--}}
    {{--                    }--}}
    {{--                }, {--}}
    {{--                    title: '',--}}
    {{--                    data: 'id',--}}
    {{--                    render: (data, type, row) => {--}}
    {{--                        return `--}}
    {{--            <div class="d-flex justify-content-between">--}}
    {{--                <button class="btn btn-sm btn-ghost-warning" onclick="editPaketObat(${data})">--}}
    {{--                    <i class="ti ti-pencil"></i> Ubah--}}
    {{--                </button>--}}
    {{--                <button class="btn btn-sm btn-ghost-danger" onclick="deletePaketObat(${data})">--}}
    {{--                    <i class="ti ti-trash"></i> Hapus--}}
    {{--                </button>--}}
    {{--            </div>--}}
    {{--            `--}}
    {{--                    }--}}
    {{--                }]--}}

    {{--            })--}}
    {{--        }--}}

    {{--        function deletePaketObat(id) {--}}
    {{--            Swal.fire({--}}
    {{--                title: 'Hapus Paket Obat',--}}
    {{--                html: `Yakin hapus data paket obat ini ?`,--}}
    {{--                icon: 'warning',--}}
    {{--                confirmButtonColor: "#d33",--}}
    {{--                cancelButtonColor: "#3085d6",--}}
    {{--                confirmButtonText: "Iya, Hapus",--}}
    {{--                cancelButtonText: "Tidak, Batalkan",--}}
    {{--                showCancelButton: true,--}}
    {{--            }).then((result) => {--}}
    {{--                if (result.isConfirmed) {--}}
    {{--                    $.ajax({--}}
    {{--                        url: `/efktp/paket-obat/${id}`,--}}
    {{--                        type: 'DELETE',--}}
    {{--                        success: function (response) {--}}
    {{--                            showToast('Berhasil Hapus Paket Obat');--}}
    {{--                            renderTablePaketObat();--}}
    {{--                        },--}}
    {{--                        error: function (xhr, status, error) {--}}
    {{--                            showToast('Gagal menghapus data paket obat' + xhr.responseJSON.message, 'error');--}}
    {{--                        }--}}
    {{--                    })--}}
    {{--                }--}}
    {{--            })--}}
    {{--        }--}}

    {{--        function editPaketObat(id) {--}}
    {{--            const formPaketObat = $('#formPaketObat');--}}

    {{--            $.get(`/efktp/paket-obat/${id}`).done((response) => {--}}
    {{--                const {data} = response;--}}

    {{--                if (data.length === 0) {--}}
    {{--                    return false;--}}
    {{--                }--}}

    {{--                $('#btnCreatePaketObat')--}}
    {{--                    .removeClass('btn-success')--}}
    {{--                    .addClass('btn-warning')--}}
    {{--                    .html(`<i class="ti ti-pencil"></i> Update Paket Obat`)--}}

    {{--                formPaketObat.find('input[name=id]').val(data.id);--}}
    {{--                formPaketObat.find('input[name=nama]').val(data.nama);--}}

    {{--                const poliklinik = new Option(data.poliklinik.nm_poli, data.kd_poli, true, true)--}}
    {{--                formPaketObat.find('#kd_poli').append(poliklinik).trigger('chage')--}}

    {{--                // Kosongkan dulu daftar obat sebelum isi ulang--}}
    {{--                const listObat = $('#listObat');--}}
    {{--                listObat.empty();--}}

    {{--                // Loop data.umum dan buat baris baru untuk setiap item--}}
    {{--                data.umum.forEach((item) => {--}}
    {{--                    const rowObat = $($('#rowContentPaketObat').html());--}}
    {{--                    const select = rowObat.find('.form-select-2'); // select di dalam baris baru--}}

    {{--                    // Tambahkan baris ke dalam list--}}
    {{--                    listObat.append(rowObat);--}}

    {{--                    // Tambahkan option baru ke select--}}
    {{--                    const option = new Option(--}}
    {{--                        item.databarang.nama_brng,--}}
    {{--                        item.databarang.kode_brng,--}}
    {{--                        true,--}}
    {{--                        true--}}
    {{--                    );--}}
    {{--                    select.append(option).trigger('change');--}}

    {{--                    // Inisialisasi select2 khusus untuk elemen baru ini--}}
    {{--                    selectDataBarang(select, $('body'));--}}

    {{--                    // Isi input jumlah & aturan jika ada--}}
    {{--                    rowObat.find('.jumlah').val(item.jumlah ?? 1);--}}
    {{--                    rowObat.find('.aturan_pakai').val(item.aturan_pakai ?? '');--}}
    {{--                });--}}

    {{--                let listObatRacikan = $('#listObatRacikan')--}}
    {{--                listObatRacikan.empty();--}}

    {{--                data.racikan.forEach((item) => {--}}
    {{--                    let rowRacikan = $($('#rowContentPaketRacik').html())--}}

    {{--                    listObatRacikan.append(rowRacikan)--}}

    {{--                    const selectMetodeRacik = listObatRacikan.find('.metode')--}}
    {{--                    selectMetode(selectMetodeRacik, $('body'))--}}

    {{--                    const optionMetode = new Option(item.metode.nm_racik, item.kd_racik, false, true)--}}
    {{--                    selectMetodeRacik.append(optionMetode).trigger('change')--}}

    {{--                    const selectTemplate = rowRacikan.find('.template');--}}
    {{--                    selectTemplate.select2({--}}
    {{--                        placeholder: 'Pilih Template Racikan...',--}}
    {{--                        width: '100%',--}}
    {{--                        ajax: {--}}
    {{--                            url: '/efktp/resep/racikan/template/search',--}}
    {{--                            dataType: 'JSON',--}}

    {{--                            data: (params) => {--}}
    {{--                                return {--}}
    {{--                                    nm_racik: params.term--}}
    {{--                                }--}}
    {{--                            },--}}
    {{--                            processResults: (data) => {--}}
    {{--                                return {--}}
    {{--                                    results: data.map((item) => {--}}
    {{--                                        return {--}}
    {{--                                            id: item.id,--}}
    {{--                                            text: item.nm_racik,--}}
    {{--                                            detail: item.detail,--}}
    {{--                                            dokter: item.dokter,--}}
    {{--                                        }--}}

    {{--                                    })--}}
    {{--                                }--}}
    {{--                            }--}}
    {{--                        },--}}
    {{--                        escapeMarkup: (m) => m, // biar HTML diizinkan--}}
    {{--                        templateResult: function (item) {--}}
    {{--                            if (item.loading) return item.text;--}}
    {{--                            if (!item.detail) return `<div>${item.text}</div>`;--}}

    {{--                            // ambil nama dokter--}}
    {{--                            const dokterNama = item.dokter ? item.dokter.nm_dokter : '-';--}}

    {{--                            let html = `--}}
    {{--                                  <div class="racikan-item">--}}
    {{--                                    <div><strong>${item.text}</strong></div>--}}
    {{--                                    <small style="color:#888;">Dokter: ${dokterNama}</small>--}}
    {{--                                    <ul style="margin:4px 0 0 10px; padding:0;">`;--}}

    {{--                            item.detail.forEach(d => {--}}
    {{--                                html += `--}}
    {{--                                <li style="font-size:9px; list-style:none;">--}}
    {{--                                  ${d.barang.nama_brng}--}}
    {{--                                  <span style="color:#999;">(${d.barang.kode_sat})</span>--}}
    {{--                                  — <strong>${d.barang.ralan.toLocaleString()}</strong>--}}
    {{--                                </li>`;--}}
    {{--                            });--}}

    {{--                            html += `</ul></div>`;--}}
    {{--                            return html;--}}
    {{--                        },--}}
    {{--                        templateSelection: function (item) {--}}
    {{--                            return item.text || item.id;--}}
    {{--                        },--}}
    {{--                    })--}}

    {{--                    const optionTemplate = new Option(item.template.nm_racik, item.template_id, true, true)--}}
    {{--                    selectTemplate.append(optionTemplate).trigger('change');--}}
    {{--                })--}}
    {{--            });--}}
    {{--        }--}}


    {{--        $('#btnTambahObatPaket').on('click', (e) => {--}}
    {{--            let listObat = $('#listObat')--}}
    {{--            let rowObat = $($('#rowContentPaketObat').html());--}}

    {{--            listObat.append(rowObat)--}}

    {{--            const select = listObat.find('.form-select-2')--}}

    {{--            selectDataBarang(select, $('body'))--}}
    {{--        })--}}

    {{--        $('#btnTambahRacikPaket').on('click', (e) => {--}}
    {{--            let listObatRacikan = $('#listObatRacikan')--}}
    {{--            let rowRacikan = $($('#rowContentPaketRacik').html())--}}

    {{--            listObatRacikan.append(rowRacikan)--}}
    {{--            selectMetode(listObatRacikan.find('.metode'), $('body'))--}}

    {{--            rowRacikan.find('.template').select2({--}}
    {{--                placeholder: 'Pilih Template Racikan...',--}}
    {{--                width: '100%',--}}
    {{--                ajax: {--}}
    {{--                    url: '/efktp/resep/racikan/template/search',--}}
    {{--                    dataType: 'JSON',--}}

    {{--                    data: (params) => {--}}
    {{--                        return {--}}
    {{--                            nm_racik: params.term--}}
    {{--                        }--}}
    {{--                    },--}}
    {{--                    processResults: (data) => {--}}
    {{--                        return {--}}
    {{--                            results: data.map((item) => {--}}
    {{--                                return {--}}
    {{--                                    id: item.id,--}}
    {{--                                    text: item.nm_racik,--}}
    {{--                                    detail: item.detail,--}}
    {{--                                    dokter: item.dokter,--}}
    {{--                                }--}}

    {{--                            })--}}
    {{--                        }--}}
    {{--                    }--}}
    {{--                },--}}
    {{--                escapeMarkup: (m) => m, // biar HTML diizinkan--}}
    {{--                templateResult: function (item) {--}}
    {{--                    if (item.loading) return item.text;--}}
    {{--                    if (!item.detail) return `<div>${item.text}</div>`;--}}

    {{--                    // ambil nama dokter--}}
    {{--                    const dokterNama = item.dokter ? item.dokter.nm_dokter : '-';--}}

    {{--                    let html = `--}}
    {{--                                  <div class="racikan-item">--}}
    {{--                                    <div><strong>${item.text}</strong></div>--}}
    {{--                                    <small style="color:#888;">Dokter: ${dokterNama}</small>--}}
    {{--                                    <ul style="margin:4px 0 0 10px; padding:0;">`;--}}

    {{--                    item.detail.forEach(d => {--}}
    {{--                        html += `--}}
    {{--                                <li style="font-size:9px; list-style:none;">--}}
    {{--                                  ${d.barang.nama_brng}--}}
    {{--                                  <span style="color:#999;">(${d.barang.kode_sat})</span>--}}
    {{--                                  — <strong>${d.barang.ralan.toLocaleString()}</strong>--}}
    {{--                                </li>`;--}}
    {{--                    });--}}

    {{--                    html += `</ul></div>`;--}}
    {{--                    return html;--}}
    {{--                },--}}
    {{--                templateSelection: function (item) {--}}
    {{--                    return item.text || item.id;--}}
    {{--                },--}}
    {{--            })--}}

    {{--        })--}}

    {{--        function createPaketObat() {--}}
    {{--            const dataObat = [];--}}
    {{--            const dataRacik = [];--}}

    {{--            $('#listObat .content-obat').each(function () {--}}
    {{--                const kode_brng = $(this).find('.form-select-2').val();--}}
    {{--                const jumlah = $(this).find('.jumlah').val();--}}
    {{--                const aturan = $(this).find('.aturan_pakai').val();--}}

    {{--                dataObat.push({--}}
    {{--                    kode_brng: kode_brng,--}}
    {{--                    jumlah: jumlah,--}}
    {{--                    aturan_pakai: aturan--}}
    {{--                });--}}
    {{--            });--}}
    {{--            $('#listObatRacikan .content-racik').each(function () {--}}
    {{--                const id_template = $(this).find('.template').val();--}}
    {{--                const jumlah = $(this).find('.jumlah').val();--}}
    {{--                const aturan = $(this).find('.aturan_pakai').val();--}}
    {{--                const kd_racik = $(this).find('.metode').val();--}}

    {{--                dataRacik.push({--}}
    {{--                    id_template: id_template,--}}
    {{--                    jumlah: jumlah,--}}
    {{--                    kd_racik: kd_racik,--}}
    {{--                    aturan_pakai: aturan--}}
    {{--                });--}}
    {{--            });--}}

    {{--            const data = {--}}
    {{--                id: $('#formPaketObat').find('#id').val(),--}}
    {{--                nama: $('#formPaketObat').find('#nama').val(),--}}
    {{--                kd_poli: $('#formPaketObat').find('#kd_poli').val(),--}}
    {{--                umum: dataObat,--}}
    {{--                racik: dataRacik--}}
    {{--            }--}}

    {{--            $.post(`/efktp/paket-obat`, data).done((response) => {--}}
    {{--                renderTablePaketObat()--}}
    {{--                $('#formPaketObat').find('input').val("")--}}
    {{--                $('#formPaketObat').find('select').val("").change()--}}
    {{--                $("#listObat").empty()--}}
    {{--                $("#listObatRacikan").empty()--}}


    {{--                showToast('Berhasil membuat paket obat')--}}
    {{--            }).fail((xhr, status, error) => {--}}
    {{--                showToast(xhr.responseJSON.message, 'error')--}}
    {{--            })--}}
    {{--        }--}}

    {{--        // Hapus baris obat saat tombol di dalamnya diklik--}}
    {{--        $('#listObat').on('click', '.delete', function (e) {--}}
    {{--            e.preventDefault();--}}
    {{--            $(this).closest('.content-obat').remove();--}}
    {{--        });--}}

    {{--        $('#listObatRacikan').on('click', '.delete-racik', function (e) {--}}
    {{--            e.preventDefault();--}}
    {{--            $(this).closest('.content-racik').remove();--}}
    {{--        });--}}
    {{--    </script>--}}

@endpush

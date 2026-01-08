<div class="modal modal-blur fade" id="modalReferensiSpesialisKhusus" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Referensi Spesialis Khusus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbReferensiSpesialisKhusus" class="table table-stripped" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var dataKhuhus = [];

        function setSpesialisKhusus(kdKhusus, khusus) {
            formRujukanKhusus.find('input[name=kdKhusus]').val(kdKhusus)
            formRujukanKhusus.find('input[name=khusus]').val(khusus)
            if (kdKhusus != 'THA' || kdKhusus != 'HEM') {
                formRujukanKhusus.find('#kdKhususSub').val('')
                formRujukanKhusus.find('#kdKhususSub').attr('disabled', true)
                formRujukanKhusus.find('#khususSub').val('')
                formRujukanKhusus.find('#khususSub').attr('disabled', true)
                dataKhusus = {
                    kdKhusus: kdKhusus,
                    noKartu: formKunjunganPcare.find('#no_peserta').val(),
                    tglEstRujuk: formRujukanLanjut.find('#tglEstRujukan').val()
                }
                renderFaskesKhusus(dataKhusus.kdKhusus, dataKhusus.noKartu, dataKhusus.tglEstRujuk);

            }
        }

        function setPpkRujukanKhusus(kdPpk, nmPpk) {
            $('#kdPpkRujukan').val(kdPpk)
            $('#ppkRujukan').val(nmPpk)
            formKunjunganPcare.find('button[name=btnPpkRujukan]').attr('disabled', false).attr('onclick', `renderFaskesKhusus('${dataKhusus.kdKhusus}', '${dataKhusus.noKartu}', '${dataKhusus.tglEstRujuk}')`);
            $('#modalReferensiRujukan').modal('hide')
        }

        function renderFaskesKhusus(kode, noKartu, tglEstRujuk) {
            const loading = loadingAjax();
            $.get(`/efktp/bridging/pcare/spesialis/rujukan/khusus`, {
                kdKhusus: kode,
                noKartu: noKartu,
                tglEstRujuk: tglEstRujuk
            }).done((response) => {
                if (response.metaData.code == 200) {
                    loading.close();
                    $('#modalReferensiRujukan').modal('show')
                    $('#modalReferensiSpesialisKhusus').modal('hide')
                    const tbReferensi = new DataTable('#tbRujukan', {
                        autoWidth: true,
                        stateSave: true,
                        serverSide: false,
                        destroy: true,
                        processing: true,
                        data: response.response.list,
                        columnDefs: [{
                            'targets': [0, 1],
                            'createdCell': (td, cellData, rowData, row, col) => {
                                $(td).attr('onclick', `setPpkRujukanKhusus('${rowData.kdppk}', '${rowData.nmppk}')`);
                                $(td).attr('style', `cursor:pointer`);
                            }
                        }],
                        columns: [{
                                title: 'Kode PPK',
                                data: 'kdppk',
                                render: (data, type, row, meta) => {
                                    return `<button type="button" class="btn btn-sm btn-outline-secondary">${data}</button>`
                                }
                            },
                            {
                                title: 'Nama Faskes',
                                data: 'nmppk',
                                render: (data, type, row, meta) => {
                                    return `<button type="button" class="btn btn-sm btn-outline-secondary">${data}</button>`
                                }
                            },
                            {
                                title: 'alamatPpk',
                                data: 'alamatPpk',
                                render: (data, type, row, meta) => {
                                    const jarak = row.distance / 1000
                                    return `${data} (${jarak.toFixed(2)} KM)`
                                }
                            },
                            {
                                title: 'Kelas',
                                data: 'kelas',
                                render: (data, type, row, meta) => {
                                    return data
                                }
                            },
                            {
                                title: 'Telp.',
                                data: 'telpPpk',
                                render: (data, type, row, meta) => {
                                    return data
                                }
                            },
                            {
                                title: 'Jml. Rujukan',
                                data: 'jmlRujuk',
                                render: (data, type, row, meta) => {
                                    return `${data} (${row.persentase} %)`
                                }
                            },
                            {
                                title: 'Kapasitas',
                                data: 'kapasitas',
                                render: (data, type, row, meta) => {
                                    return data
                                }
                            },
                            {
                                title: 'Jadwal',
                                data: 'jadwal',
                                render: (data, type, row, meta) => {
                                    return `${data}`
                                }
                            },
                        ]
                    })
                    console.log(response);
                } else {
                    const error = {
                        status: response.metaData.code,
                        statusText: response.metaData.message,
                    }
                    alertErrorAjax(error);
                }
            })
        }

        function renderReferensiSpesialisKhusus() {
            $('#modalReferensiSpesialisKhusus').find('.modal-title').html('Referensi Spesialis')
            const loading = loadingAjax();
            $.get(`/efktp/bridging/pcare/spesialis/khusus`).done((response) => {
                if (response.metaData.code == 200) {
                    loading.close();
                    $('#modalReferensiSpesialisKhusus').modal('show')
                    const tbReferensi = new DataTable('#tbReferensiSpesialisKhusus', {
                        autoWidth: true,
                        stateSave: true,
                        serverSide: false,
                        destroy: true,
                        processing: true,
                        data: response.response.list,
                        columnDefs: [{
                            'targets': [0, 1],
                            'createdCell': (td, cellData, rowData, row, col) => {
                                $(td).attr('onclick', `setSpesialisKhusus('${rowData.kdKhusus}', '${rowData.nmKhusus}')`);
                            }
                        }],
                        columns: [{
                                title: 'Kode Spesialis Khsusus',
                                data: 'kdKhusus',
                                render: (data, type, row, meta) => {
                                    return `<button type="button" class="btn btn-sm btn-outline-secondary">${data}</button>`
                                }
                            },
                            {
                                title: 'Nama Spesialis Khusus',
                                data: 'nmKhusus',
                                render: (data, type, row, meta) => {
                                    return data
                                }
                            },
                        ]
                    })
                } else {
                    const errorMsg = {
                        status: 500,
                        statusText: 'Gagal mengambil data sub-spesialis'
                    }
                    alertErrorAjax(errorMsg)
                }
            })

        }
    </script>
@endpush

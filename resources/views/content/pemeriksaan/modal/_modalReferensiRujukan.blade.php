<div class="modal modal-blur fade" id="modalReferensiRujukan" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Pilihan Rujukan FKTRL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbRujukan" class="table table-sm table-responsive" width="100%"></table>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        function setPpkRujukan(kdPpk, nmPpk) {
            $('#kdPpkRujukan').val(kdPpk)
            $('#ppkRujukan').val(nmPpk)
            formKunjunganPcare.find('button[name=btnPpkRujukan]').attr('disabled', false).attr('onclick', 'renderRujukan()');
            $('#modalReferensiRujukan').modal('hide')
        }

        function renderRujukan() {
            const loading = loadingAjax();
            $.get(`/efktp/bridging/pcare/spesialis/rujukan`, {
                kdSubSpesialis: $('#kdSubSpesialis').val(),
                kdSarana: $('#kdSarana').val(),
                tglRujuk: $('#tglEstRujukan').val(),
            }).done((response) => {
                if (response.metaData.code == 200) {
                    loading.close();
                    $('#modalReferensiRujukan').modal('show')
                    $('#modalReferensiSubSpesialis').modal('hide')
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
                                $(td).attr('onclick', `setPpkRujukan('${rowData.kdppk}', '${rowData.nmppk}')`);
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

<div class="modal modal-blur fade" id="modalReferensiSpesialis" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Referensi Spesialis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbReferensiSpesialis" class="table table-stripped" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        function setSpesialis(kdSpesialis, spesialis) {
            formRujukanSpesialis.find('input[name=kdSpesialis]').val(kdSpesialis)
            formRujukanSpesialis.find('input[name=spesialis]').val(spesialis)
            $('#btnSarana').attr('onclick', `renderReferensiSubspesialis('${kdSpesialis}')`)
        }

        function renderReferensiSpesialis() {
            $('#modalReferensiSpesialis').find('.modal-title').html('Referensi Spesialis')
            const loading = loadingAjax();
            $.get(`/efktp/bridging/pcare/spesialis`).done((response) => {
                if (response.metaData.code == 200) {
                    loading.close();
                    $('#modalReferensiSpesialis').modal('show')
                    const tbReferensi = new DataTable('#tbReferensiSpesialis', {
                        autoWidth: true,
                        stateSave: true,
                        serverSide: false,
                        destroy: true,
                        processing: true,
                        data: response.response.list,
                        columnDefs: [{
                            'targets': [0, 1],
                            'createdCell': (td, cellData, rowData, row, col) => {
                                $(td).attr('onclick', `renderReferensiSubspesialis('${rowData.kdSpesialis}');setSpesialis('${rowData.kdSpesialis}', '${rowData.nmSpesialis}')`);
                            }
                        }],
                        columns: [{
                                title: 'Kode Spesialis',
                                data: 'kdSpesialis',
                                render: (data, type, row, meta) => {
                                    return `<button type="button" class="btn btn-sm btn-outline-secondary">${data}</button>`
                                }
                            },
                            {
                                title: 'Nama Spesialis',
                                data: 'nmSpesialis',
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

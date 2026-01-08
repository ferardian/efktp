<div class="modal modal-blur fade" id="modalReferensiPoliFktp" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Referensi Poli FKTP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tbReferensiPoliFktp" class="table table-stripped" width="100%"></table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var formRujukanInternal = $('#formRujukanInternal');

        function renderReferensiPoliFktp() {
            loadingAjax();
            $.get(`/efktp/bridging/pcare/fktp/poli`).done((response) => {
                if (response.metaData.code == '200') {

                    loadingAjax().close();
                    $('#modalReferensiPoliFktp').modal('show')
                    const tbReferensi = new DataTable('#tbReferensiPoliFktp', {
                        autoWidth: true,
                        stateSave: true,
                        serverSide: false,
                        destroy: true,
                        processing: true,
                        data: response.response.list,
                        columns: [{
                                title: 'Kode Spesialis',
                                data: 'kdPoli',
                                render: (data, type, row, meta) => {
                                    return `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPoliFktp('${row.kdPoli}', '${row.nmPoli}')">${data}</button>`
                                }
                            },
                            {
                                title: 'Nama Spesialis',
                                data: 'nmPoli',
                                render: (data, type, row, meta) => {
                                    return data
                                }
                            },
                            {
                                title: 'Poli Sakit',
                                data: 'poliSakit',
                                render: (data, type, row, meta) => {
                                    return data ? '<span class="badge bg-primary">YA</span>' : '<span class="badge bg-danger">TIDAK</span>'
                                }
                            },
                        ]
                    })
                }

            })
        }

        function setPoliFktp(kdPoli, nmPoli) {
            formRujukanInternal.find('#kdInternal').val(kdPoli)
            formRujukanInternal.find('#internal').val(nmPoli)
            $('#modalReferensiPoliFktp').modal('hide')
        }
    </script>
@endpush

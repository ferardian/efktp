<div class="modal modal-blur fade" id="modalListRm" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelengkapan Berkas Rekam Medis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" id="btnResetCpptRanap" class="btn btn-warning d-none"><i class="ti ti-reload me-1"></i>Baru</button>
                <button type="button" id="btnSalinCpptRanap" class="btn btn-primary d-none"><i class="ti ti-copy me-1"></i> Copy</button>
                <button type="button" id="btnSimpanCpptRanap" class="btn btn-success" onclick="createCpptRanap()"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        let modalListRm = $('#modalListRm')

        function listRm(no_rawat) {
            modalListRm.modal('show')
        }
    </script>
@endpush

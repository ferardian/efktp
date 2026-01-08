<div class="modal modal-blur fade" id="modalPrintKunjungan" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Print Surat Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row gy-2">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label for="sizePrint" class="form-label mt-2">Ukuran Pinter</label>
                        <select name="size" id="size" class="form-select mb-2">
                            <option value="a5">A5</option>
                            <option value="a4">A4</option>
                            <option value="8">8 cm</option>
                        </select>
                    </div>
                    <input type="hidden" id="noKunjungan" name="noKunjungan">
                </div>
                <iframe id="print" type="" width="100%" height="600"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var modalPrintKunjungan = $('#modalPrintKunjungan');
        var selectSize = modalPrintKunjungan.find('#size');
        var setPaper = localStorage.getItem('setPaper') ? localStorage.getItem('setPaper') : 'a5';

        function renderPrintRujukan(noKunjungan, width = '') {
            const size = width == 'a5' ? '' : `&size=${width}`;
            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            modalPrintKunjungan.find('#print').on('load', (e) => {
                if (e.currentTarget.src) {
                    toast('Berhasil');
                }

            })
            modalPrintKunjungan.find('#print').removeAttr('src').attr('src', `/efktp/pcare/kunjungan/rujuk/subspesialis/print?noKunjungan=${noKunjungan}${size}`);
        }

        selectSize.on('change', (e) => {
            const size = e.currentTarget.value;
            const noKunjungan = modalPrintKunjungan.find('#noKunjungan').val();
            localStorage.setItem('setPaper', size)
            renderPrintRujukan(noKunjungan, size)
        })

        modalPrintKunjungan.on('shown.bs.modal', () => {
            paper = localStorage.getItem('setPaper');
            selectSize.val(setPaper).change();
        })
    </script>
@endpush

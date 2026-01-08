<div class="modal modal-blur fade" id="modalBuktiRegister" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Cetak Bukti Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <div class="row gy-2">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label for="sizePrint" class="form-label mt-2">Ukuran Pinter</label>
                        <select name="size" id="size" class="form-select mb-2">
                            <option value="8">8 cm</option>
                            <option value="4">4 cm</option>
                            <option value="5.8">5.8 cm</option>
                        </select>
                    </div>
                    <input type="hidden" id="no_rawat" name="no_rawat">
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
        var modalBuktiRegister = $('#modalBuktiRegister');
        var selectSizeBukti = modalBuktiRegister.find('#size');
        var setPaperRegister = localStorage.getItem('setPaperRegister') ? localStorage.getItem('setPaperRegister') : '8';

        function buktiRegister(no_rawat, size = '') {
            const sizePaper = size == '8' ? '' : `&width=${size}`;
            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            modalBuktiRegister.find('#print').on('load', (e) => {
                if (e.currentTarget.src) {
                    toast('Berhasil');
                }

            })
            modalBuktiRegister.find('#no_rawat').val(no_rawat)
            modalBuktiRegister.find('#print').attr('src', `/efktp/registrasi/bukti/print?no_rawat=${no_rawat}${sizePaper}`);
            modalBuktiRegister.modal('show');
        }
        selectSizeBukti.on('change', (e) => {
            const size = e.currentTarget.value;
            const no_rawat = modalBuktiRegister.find('#no_rawat').val();
            buktiRegister(no_rawat, size)
            localStorage.setItem('setPaperRegister', size)
        })

        modalBuktiRegister.on('shown.bs.modal', () => {
            paper = localStorage.getItem('setPaperRegister');
            selectSizeBukti.val(paper).change();
        });

        modalBuktiRegister.on('hidden.bs.modal', () => {
            modalBuktiRegister.find('#print').attr('src', '');
        })
    </script>
@endpush

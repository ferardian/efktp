<div class="modal modal-blur fade" id="modalCetakResep" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Detail Racikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <iframe id="print" type="" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        const modalCetakResep = $('#modalCetakResep');

        function cetakResep(no_rawat) {
            modalCetakResep.modal('show')
            console.log(modalCetakResep.find('#print'));
            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            })
            modalCetakResep.find('#print').on('load', (e) => {
                if (e.currentTarget.src) {
                    // Swal.fire('');
                    toast('Berhasil');
                }

            })
            modalCetakResep.find("#print").removeAttr('src').attr('src', `/efktp/resep/print?no_rawat=${no_rawat}`)
        }
        modalCetakResep.on('hidden.bs.modal', () => {
            modalCetakResep.find("#print").removeAttr('src');
        })
    </script>
@endpush

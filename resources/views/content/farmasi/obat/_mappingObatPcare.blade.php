<div class="modal modal-blur fade" id="modalMappingObatPcare" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="" id="formMappingObatPcare">
                    @csrf

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        const modalMappingObatPcare = $('#modalMappingObatPcare')
    </script>
@endpush

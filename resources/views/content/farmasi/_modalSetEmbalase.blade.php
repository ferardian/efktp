<!-- Modal Set Embalase & Tuslah (SIMKES Khanza Standard) -->
<div class="modal modal-blur fade" id="modalSetEmbalase" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title m-0 text-white d-flex align-items-center gap-2">
                    <i class="ti ti-settings text-warning"></i>
                    <span>Set Embalase & Tuslah</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <form id="formSetEmbalase">
                    <div class="mb-3">
                        <label class="form-label fw-bold small mb-1">
                            <i class="ti ti-package me-1 text-primary"></i> Embalase per Obat (Rp)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="any" min="0" class="form-control fw-bold text-end" id="set_embalase_per_obat" name="embalase_per_obat" value="0" placeholder="0">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Biaya wadah, bungkus, plastik klip, pot/botol obat.</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small mb-1">
                            <i class="ti ti-receipt me-1 text-success"></i> Tuslah per Obat / Racik (Rp)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="any" min="0" class="form-control fw-bold text-end" id="set_tuslah_per_obat" name="tuslah_per_obat" value="0" placeholder="0">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Jasa pelayanan dispensing / resep farmasi.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-sm btn-primary" id="btnSimpanSetEmbalase" onclick="simpanPengaturanEmbalase()">
                    <i class="ti ti-check me-1"></i> Simpan Tarif
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModalSetEmbalase() {
        $.get(`{{ url('/farmasi/set-embalase') }}`).done((res) => {
            if (res && res.data) {
                $('#set_embalase_per_obat').val(res.data.embalase_per_obat || 0);
                $('#set_tuslah_per_obat').val(res.data.tuslah_per_obat || 0);
            }
            $('#modalSetEmbalase').modal('show');
        }).fail(() => {
            $('#modalSetEmbalase').modal('show');
        });
    }

    function simpanPengaturanEmbalase() {
        const embalase = parseFloat($('#set_embalase_per_obat').val() || 0);
        const tuslah = parseFloat($('#set_tuslah_per_obat').val() || 0);

        if (embalase < 0 || tuslah < 0) {
            return Swal.fire('Peringatan', 'Tarif tidak boleh negatif', 'warning');
        }

        const btn = $('#btnSimpanSetEmbalase');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

        $.post(`{{ url('/farmasi/set-embalase') }}`, {
            _token: `{{ csrf_token() }}`,
            embalase_per_obat: embalase,
            tuslah_per_obat: tuslah
        }).done((res) => {
            btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Simpan Tarif');
            $('#modalSetEmbalase').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message || 'Pengaturan embalase dan tuslah berhasil disimpan',
                timer: 1500,
                showConfirmButton: false
            });

            // Update local memory if on validasi or penjualan page
            if (typeof valDefaultEmbalase !== 'undefined') {
                valDefaultEmbalase = embalase;
            }
            if (typeof valDefaultTuslah !== 'undefined') {
                valDefaultTuslah = tuslah;
            }
            if (typeof currentDefaultEmbalase !== 'undefined') {
                currentDefaultEmbalase = embalase;
            }
            if (typeof currentDefaultTuslah !== 'undefined') {
                currentDefaultTuslah = tuslah;
            }
        }).fail((err) => {
            btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Simpan Tarif');
            Swal.fire('Gagal', err.responseJSON?.message || 'Gagal menyimpan pengaturan embalase', 'error');
        });
    }
</script>

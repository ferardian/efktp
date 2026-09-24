<div class="modal modal-blur fade" id="modalBeriObatUdd" tabindex="-1" style="z-index: 1065;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-sm bg-white text-primary rounded-circle me-2 shadow-sm">
                        <i class="ti ti-pill fs-2"></i>
                    </span>
                    <div>
                        <h4 class="modal-title text-white fw-bold mb-0">Pemberian Obat Pasien (UDD)</h4>
                        <small class="text-white-50">Pencatatan administrasi dosis & waktu pemberian obat</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formBeriObatUdd">
                <div class="modal-body py-3">
                    <input type="hidden" id="beriUddKodeBrng" name="kode_brng">
                    <input type="hidden" id="beriUddJamJadwal" name="jam_jadwal">
                    <input type="hidden" id="beriUddMaxStok" value="1">

                    <!-- Ringkasan Obat & Stok -->
                    <div class="card card-sm bg-blue-lt border-0 mb-3 shadow-none">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">NAMA OBAT</span>
                                    <h3 class="m-0 text-primary fw-bold" id="beriUddNamaBrng">-</h3>
                                </div>
                                <span class="badge bg-blue text-white px-2 py-1 fs-6">
                                    <i class="ti ti-clock me-1"></i>Jadwal <strong id="beriUddDisplayJadwal">-</strong>
                                </span>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center pt-2 border-top border-blue-subtle">
                                <span class="text-dark small">
                                    <i class="ti ti-prescription text-primary me-1"></i>Aturan: <strong id="beriUddAturan">-</strong>
                                </span>
                                <span class="badge bg-success text-white ms-auto">
                                    <i class="ti ti-archive me-1"></i>Sisa Stok: <strong id="beriUddSisaStokText">0</strong> <span id="beriUddSatuanBadge"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Input Qty Diberikan & Quick Presets -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-dark">
                                <i class="ti ti-calculator text-primary me-1"></i>Jumlah (Qty Diberikan):
                            </label>
                            <!-- Preset Buttons -->
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary py-0 px-2 btn-preset-qty" data-qty="0.5">0.5</button>
                                <button type="button" class="btn btn-outline-secondary py-0 px-2 btn-preset-qty active" data-qty="1">1</button>
                                <button type="button" class="btn btn-outline-secondary py-0 px-2 btn-preset-qty" data-qty="2">2</button>
                            </div>
                        </div>
                        <div class="input-group input-group-lg">
                            <button class="btn btn-outline-secondary" type="button" id="btnMinusQty"><i class="ti ti-minus"></i></button>
                            <input type="number" class="form-control text-center fw-bold fs-3 text-primary" id="beriUddJml" name="jml" value="1" min="0.01" step="any" required>
                            <span class="input-group-text fw-bold bg-light" id="beriUddSatuanAddon">Tab</span>
                            <button class="btn btn-outline-secondary" type="button" id="btnPlusQty"><i class="ti ti-plus"></i></button>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted" style="font-size: 0.75rem;">Bisa diketik manual (angka pecahan misal 0.5 atau bulat)</small>
                            <small class="text-danger fw-semibold d-none" id="alertQtyMax">Melebihi sisa stok ruangan!</small>
                        </div>
                    </div>

                    <!-- Jam Riil Pemberian & Waktu Sekarang -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-dark">
                                <i class="ti ti-clock-check text-primary me-1"></i>Jam Riil Pemberian Obat:
                            </label>
                            <button type="button" class="btn btn-sm btn-ghost-primary py-0 px-2" id="btnSetWaktuSekarang">
                                <i class="ti ti-refresh me-1"></i>Waktu Sekarang
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-clock"></i></span>
                            <input type="text" class="form-control fw-bold font-monospace" id="beriUddJamRiil" name="jam_riil" placeholder="HH:MM:SS" required>
                            <span class="input-group-text bg-light text-muted" id="badgePreviewKepatuhan">
                                <i class="ti ti-info-circle me-1"></i>Tepat Waktu
                            </span>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">Format 24 jam (HH:MM:SS). Sistem otomatis mencatat status ketepatan jam.</small>
                    </div>

                    <!-- Aturan Pakai / Catatan Tambahan (Opsional) -->
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-dark mb-1">Catatan / Aturan Pakai Khusus:</label>
                        <input type="text" class="form-control form-control-sm" id="beriUddCatatanAturan" name="aturan_pakai" placeholder="Contoh: Sesudah makan, Dilarutkan, dll.">
                    </div>

                    <div class="alert alert-info py-2 px-3 mb-0 d-flex align-items-center rounded-3">
                        <i class="ti ti-info-circle fs-3 me-2 text-info"></i>
                        <div style="font-size: 0.78rem;" class="text-dark">
                            Obat yang diberikan akan otomatis tercatat ke <strong>Rincian Billing Rawat Inap</strong> pasien sesuai Qty dan tarif kamar inap.
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success px-4" id="btnSubmitBeriUdd">
                        <i class="ti ti-check me-1"></i> Simpan & Berikan Obat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

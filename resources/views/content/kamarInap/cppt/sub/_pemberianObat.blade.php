<div id="wrapperPemberianObatRanap">
    <!-- Banner status jika billing telah ditutup -->
    <div id="bannerBhpLocked" class="alert alert-warning d-none py-2 px-3 mb-3 d-flex align-items-center" role="alert">
        <i class="ti ti-lock me-2 fs-2"></i>
        <div>
            <strong>Billing Terkunci:</strong> Pasien ini telah diverifikasi & dibayar di Kasir. Penambahan atau pembatalan pemberian obat/BHP dinonaktifkan.
        </div>
    </div>

    <!-- Card Jadwal & Stok Obat UDD Pasien di Ruangan (MAR) -->
    <div class="card border mb-3 shadow-sm">
        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="m-0 text-dark d-flex align-items-center gap-2">
                <i class="ti ti-clock-check text-primary fs-2"></i>
                <div>
                    <span class="fw-bold">Jadwal Pemberian Obat UDD (Stok Ruangan Pasien)</span>
                    <small class="text-muted d-block fw-normal">Dosis 24 jam yang disiapkan oleh Farmasi. Klik jam untuk mencatat kepatuhan pemberian minum obat ke pasien.</small>
                </div>
            </h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btnRefreshStokUddPasien">
                <i class="ti ti-refresh me-1"></i> Refresh Jadwal
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" id="tbJadwalUddRuangan">
                    <thead class="table-light">
                        <tr>
                            <th width="32%">Nama Obat & Aturan Pakai</th>
                            <th width="18%" class="text-center">Stok di Ruangan</th>
                            <th width="50%">Jadwal & Pelaksanaan Pemberian Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Memuat jadwal obat UDD...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Input Pemberian Langsung / BHP Ruangan -->
    <div class="card border mb-3 shadow-none bg-light-subtle">
        <div class="card-header bg-transparent py-1 px-3">
            <span class="small fw-bold text-muted"><i class="ti ti-plus me-1"></i> Input Tambahan Pemberian Obat / BHP Langsung (Non-UDD)</span>
        </div>
        <div class="card-body p-3">
            <form id="formPemberianObatRanap">
                <div class="row g-2 mb-2">
            <div class="col-md-4">
                <label class="form-label mb-1 small fw-bold">Asal Bangsal / Depo Stok</label>
                <select class="form-select form-select-sm" name="kd_bangsal_bhp" id="selectBangsalBhp" style="width: 100%">
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label mb-1 small fw-bold">Pilih Obat, Alkes & BHP Medis</label>
                <select class="form-select form-select-sm" name="kode_brng_bhp" id="selectBarangBhp" style="width: 100%">
                    <option value="">Ketik nama infus, abocath, verban, spuit, atau obat...</option>
                </select>
            </div>
        </div>

        <!-- Info Detail Barang Terpilih -->
        <div id="infoBarangTerpilih" class="card bg-azure-lt border-0 shadow-none p-2 mb-2 d-none">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 small">
                <div>
                    <strong>Barang:</strong> <span id="infoNamaBarang">-</span> (<span id="infoSatuanBarang">-</span>)
                </div>
                <div>
                    <strong>Stok Tersedia:</strong> <span class="badge bg-green text-white" id="infoStokBarang">0</span>
                </div>
                <div>
                    <strong>Tarif Kelas:</strong> <span class="fw-bold text-azure" id="infoHargaBarang">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1 small fw-bold">Jumlah (Qty)</label>
                <input type="number" step="any" min="0.01" class="form-control form-control-sm" name="jml_bhp" id="jml_bhp" placeholder="Qty">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1 small fw-bold">Tanggal</label>
                <input type="text" class="form-control form-control-sm filterTanggal" name="tgl_bhp" id="tgl_bhp" value="{{ date('d-m-Y') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1 small fw-bold">Jam</label>
                <input type="text" class="form-control form-control-sm" name="jam_bhp" id="jam_bhp" value="{{ date('H:i:s') }}">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary btn-sm w-100" id="btnSimpanBhpRanap">
                    <i class="ti ti-plus me-1"></i> Beri Obat / BHP
                </button>
            </div>
        </div>
    </form>
</div>
</div>

    <!-- Tabel Riwayat Pemberian Obat / BHP Pasien -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="m-0 text-muted">
            <i class="ti ti-list-details me-1"></i> Riwayat Obat & BHP Terpakai (Ranap)
        </h6>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnRefreshBhpRanap" title="Refresh">
            <i class="ti ti-refresh me-1"></i> Refresh
        </button>
    </div>

    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
        <table class="table table-sm table-striped table-hover align-middle" id="tabelRiwayatPemberianObat" width="100%">
            <thead class="table-light sticky-top">
                <tr>
                    <th style="width: 120px;">Waktu</th>
                    <th>Nama Obat / BHP</th>
                    <th style="width: 100px;">Depo / Asal</th>
                    <th class="text-end" style="width: 90px;">Harga</th>
                    <th class="text-center" style="width: 70px;">Qty</th>
                    <th class="text-end" style="width: 100px;">Total</th>
                    <th class="text-center" style="width: 50px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Memuat riwayat pemberian...</td>
                </tr>
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="5" class="text-end">Total Biaya Obat & BHP:</td>
                    <td class="text-end text-primary" id="totalBiayaBhpRanap">Rp 0</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

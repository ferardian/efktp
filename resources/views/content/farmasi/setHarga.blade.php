@extends('layout')

@push('style')
<style>
    /* Paksa semua teks di halaman set-harga menjadi kontras gelap dan jelas */
    .set-harga-page,
    .set-harga-page .card,
    .set-harga-page .form-label,
    .set-harga-page label,
    .set-harga-page .form-check-label,
    .set-harga-page .form-selectgroup-label,
    .set-harga-page .form-control,
    .set-harga-page .form-select,
    .set-harga-page select,
    .set-harga-page input,
    .modal-content,
    .modal-content .form-label,
    .modal-content label {
        color: #1e293b !important;
    }

    .set-harga-page .form-label,
    .modal-content .form-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 5px !important;
    }

    .set-harga-page .form-control,
    .set-harga-page .form-select,
    .modal-content .form-control,
    .modal-content .form-select {
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 500 !important;
    }

    .set-harga-page .form-select option,
    .set-harga-page select option,
    .modal-content .form-select option {
        color: #1e293b !important;
        background-color: #ffffff !important;
    }

    .set-harga-page .form-check-label {
        color: #1e293b !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }

    .set-harga-page .form-selectgroup-label {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #1e293b !important;
        border-radius: 8px !important;
    }

    .set-harga-page .form-selectgroup-label * {
        color: #1e293b !important;
    }

    .set-harga-page .form-selectgroup-input:checked + .form-selectgroup-label {
        background-color: #eff6ff !important;
        border-color: #2563eb !important;
    }

    .set-harga-page .form-selectgroup-input:checked + .form-selectgroup-label * {
        color: #1e40af !important;
    }

    .set-harga-page .form-selectgroup-label .text-muted,
    .set-harga-page .form-text,
    .set-harga-page .text-muted {
        color: #64748b !important;
    }

    .set-harga-page .input-group-text {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        border-color: #cbd5e1 !important;
        font-weight: 600 !important;
    }

    /* Badges & Status Banner Alignment */
    .status-banner {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
    }
    .status-banner .title-status {
        color: #0f172a !important;
        font-weight: 700 !important;
        line-height: 1 !important;
    }
    .status-banner .badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        padding: 0 10px !important;
        height: 26px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        line-height: 1 !important;
        border-radius: 6px !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
    }
    .status-banner .badge .ti,
    .status-banner .badge i {
        font-size: 14px !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
        width: 14px !important;
        height: 14px !important;
        position: relative !important;
        top: 0 !important;
        flex-shrink: 0 !important;
    }
    .status-banner .badge span {
        display: inline-flex !important;
        align-items: center !important;
        line-height: 1 !important;
    }

    .set-harga-page .badge {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        line-height: 1 !important;
        vertical-align: middle !important;
    }
    .set-harga-page .badge .ti,
    .set-harga-page .badge i {
        font-size: 13px !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
    }

    .set-harga-page .btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        vertical-align: middle !important;
    }
    .set-harga-page .btn .ti,
    .set-harga-page .btn i {
        font-size: 15px !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
    }

    /* Table text styling */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8fafc !important;
    }
    .table th {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700 !important;
    }
    .table td {
        color: #1e293b !important;
    }
</style>
@endpush

@section('body')
    <div class="container-fluid set-harga-page">
        <!-- Header Page -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h3 class="card-title text-primary fw-bold mb-1">
                        <i class="ti ti-coin me-2 text-primary fs-2"></i> Pengaturan & Margin Harga Jual Obat
                    </h3>
                    <div class="text-muted small">
                        Atur kebijakan penetapan harga jual obat untuk rawat jalan, rawat inap, dan apotek bebas (Adopsi SIMKES Khanza).
                    </div>
                </div>
                <!-- Status Banner Badges -->
                <div class="d-flex flex-wrap gap-2 align-items-center bg-white p-2 rounded-3 border shadow-sm status-banner">
                    <span class="small fw-bold title-status me-1">Status Kebijakan:</span>
                    <span class="badge bg-primary text-white" id="badge_current_mode">
                        <i class="ti ti-layers-subtract"></i> <span>Mode: {{ $pengaturanUmum->setharga }}</span>
                    </span>
                    <span class="badge bg-info text-white" id="badge_current_dasar">
                        <i class="ti ti-receipt-2"></i> <span>Dasar: {{ $pengaturanUmum->hargadasar }}</span>
                    </span>
                    <span class="badge {{ $pengaturanUmum->ppn == 'Yes' ? 'bg-success' : 'bg-secondary' }} text-white" id="badge_current_ppn">
                        <i class="ti ti-percentage"></i> <span>PPN: {{ $pengaturanUmum->ppn == 'Yes' ? 'Ya (11%)' : 'Tidak' }}</span>
                    </span>
                </div>
            </div>

            <div class="card-header border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-kebijakan" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-settings me-2"></i> 1. Kebijakan Sistem & Simulator
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-umum" class="nav-link" data-bs-toggle="tab" role="tab" id="btnTabUmum">
                            <i class="ti ti-world me-2"></i> 2. Margin Harga Umum
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-jenis" class="nav-link" data-bs-toggle="tab" role="tab" id="btnTabJenis">
                            <i class="ti ti-category me-2"></i> 3. Margin Per Jenis Obat
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-barang" class="nav-link" data-bs-toggle="tab" role="tab" id="btnTabBarang">
                            <i class="ti ti-pill me-2"></i> 4. Margin Khusus Per Obat
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <!-- ========================================== -->
                    <!-- TAB 1: KEBIJAKAN SISTEM & SIMULATOR        -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade show active" id="tab-kebijakan" role="tabpanel">
                        <div class="row row-cards">
                            <!-- Kolom Kiri: Form Kebijakan Induk -->
                            <div class="col-lg-5">
                                <div class="card bg-white border shadow-sm">
                                    <div class="card-header bg-light py-3 border-bottom">
                                        <h4 class="card-title fw-bold text-dark mb-0">
                                            <i class="ti ti-adjustments-horizontal me-2 text-primary"></i> Aturan Penetapan Harga (set_harga_obat)
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="formKebijakan">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label required fw-bold text-dark" style="color: #1e293b !important;">Metode Penentuan Harga Jual</label>
                                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                                                    <label class="form-selectgroup-item flex-fill">
                                                        <input type="radio" name="setharga" value="Umum" class="form-selectgroup-input" {{ $pengaturanUmum->setharga == 'Umum' ? 'checked' : '' }}>
                                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                            <div class="me-3">
                                                                <span class="form-selectgroup-check"></span>
                                                            </div>
                                                            <div>
                                                                <span class="fw-bold text-dark d-block" style="color: #1e293b !important;">Margin Umum (Global)</span>
                                                                <span class="text-muted small">Satu persentase margin berlaku sama untuk seluruh data obat.</span>
                                                            </div>
                                                        </div>
                                                    </label>

                                                    <label class="form-selectgroup-item flex-fill">
                                                        <input type="radio" name="setharga" value="Per Jenis" class="form-selectgroup-input" {{ $pengaturanUmum->setharga == 'Per Jenis' ? 'checked' : '' }}>
                                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                            <div class="me-3">
                                                                <span class="form-selectgroup-check"></span>
                                                            </div>
                                                            <div>
                                                                <span class="fw-bold text-primary d-block">Per Jenis / Kategori Obat (Direkomendasikan)</span>
                                                                <span class="text-muted small">Margin dibedakan per kelompok (Injeksi, Tablet Generik, Paten, Sirup, dll).</span>
                                                            </div>
                                                        </div>
                                                    </label>

                                                    <label class="form-selectgroup-item flex-fill">
                                                        <input type="radio" name="setharga" value="Per Barang" class="form-selectgroup-input" {{ $pengaturanUmum->setharga == 'Per Barang' ? 'checked' : '' }}>
                                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                            <div class="me-3">
                                                                <span class="form-selectgroup-check"></span>
                                                            </div>
                                                            <div>
                                                                <span class="fw-bold text-dark d-block" style="color: #1e293b !important;">Per Barang / Obat Spesifik</span>
                                                                <span class="text-muted small">Memprioritaskan margin khusus per masing-masing nama obat.</span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label required fw-bold text-dark" style="color: #1e293b !important;">Dasar Perhitungan Harga Dasar</label>
                                                <select class="form-select text-dark bg-white" name="hargadasar" id="cfg_hargadasar" style="color: #1e293b !important; background-color: #ffffff !important; font-weight: 600;" required>
                                                    <option value="Harga Beli" {{ $pengaturanUmum->hargadasar == 'Harga Beli' ? 'selected' : '' }} style="color: #1e293b; background: #ffffff;">Harga Beli (Faktur Sebelum Diskon)</option>
                                                    <option value="Harga Diskon" {{ $pengaturanUmum->hargadasar == 'Harga Diskon' ? 'selected' : '' }} style="color: #1e293b; background: #ffffff;">Harga Diskon (Setelah Potongan Supplier)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label required fw-bold text-dark" style="color: #1e293b !important;">Sertakan PPN Pembelian (11%)</label>
                                                <div class="d-flex gap-4 p-2 bg-light rounded border">
                                                    <label class="form-check form-check-inline mb-0 cursor-pointer">
                                                        <input class="form-check-input" type="radio" name="ppn" value="Yes" {{ $pengaturanUmum->ppn == 'Yes' ? 'checked' : '' }}>
                                                        <span class="form-check-label fw-bold text-dark" style="color: #1e293b !important;">Ya (Termasuk PPN)</span>
                                                    </label>
                                                    <label class="form-check form-check-inline mb-0 cursor-pointer">
                                                        <input class="form-check-input" type="radio" name="ppn" value="No" {{ $pengaturanUmum->ppn == 'No' ? 'checked' : '' }}>
                                                        <span class="form-check-label fw-bold text-dark" style="color: #1e293b !important;">Tidak (Tanpa PPN)</span>
                                                    </label>
                                                </div>
                                                <div class="form-text small mt-1">Jika "Ya", maka: <code>Harga Dasar = Harga Beli + (Harga Beli × 11%)</code></div>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 mt-2 fw-bold" id="btnSimpanKebijakan">
                                                <i class="ti ti-device-floppy me-1"></i> Simpan Kebijakan Sistem
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Live Interactive Simulator -->
                            <div class="col-lg-7">
                                <div class="card border border-primary-subtle shadow-sm bg-white">
                                    <div class="card-header bg-primary-lt py-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="card-title fw-bold text-primary mb-0">
                                                <i class="ti ti-calculator me-2 fs-2"></i> Live Simulator / Kalkulator Simulasi Harga
                                            </h4>
                                            <div class="text-muted small">Cek langsung bagaimana margin menghasilkan harga jual ralan, ranap, dan apotek bebas</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row row-cards mb-3">
                                            <div class="col-md-5">
                                                <label class="form-label fw-bold text-dark" style="color: #1e293b !important;">Simulasi Harga Beli (Rp)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light fw-bold text-dark">Rp</span>
                                                    <input type="number" class="form-control fw-bold text-dark" style="color: #1e293b !important;" id="sim_h_beli" value="10000" min="100" step="100">
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <label class="form-label fw-bold text-dark" style="color: #1e293b !important;">Pilih Kategori / Jenis Obat</label>
                                                <select class="form-select select-sim-jenis text-dark bg-white" id="sim_kdjns" style="color: #1e293b !important; background-color: #ffffff !important;">
                                                    <option value="" style="color: #1e293b; background: #ffffff;">-- Gunakan Margin Umum --</option>
                                                    @foreach($jenisList as $jns)
                                                        <option value="{{ $jns->kdjns }}" style="color: #1e293b; background: #ffffff;">{{ $jns->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="alert alert-info py-2 mb-3 d-flex justify-content-between align-items-center small text-dark">
                                            <div>
                                                Dasar Perhitungan Terkoreksi: <strong id="sim_lbl_dasar" class="text-primary">Rp 11.100</strong>
                                                <span class="badge bg-white text-info ms-2 border" id="sim_lbl_mode_applied">Mode: Per Jenis</span>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-info fw-bold" onclick="runSimulator()">
                                                <i class="ti ti-refresh me-1"></i> Hitung Ulang
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-dark">Kategori Penjualan</th>
                                                        <th class="text-center text-dark" style="width: 25%">Margin (%)</th>
                                                        <th class="text-end text-dark" style="width: 35%">Harga Jual Akhir (Round Up)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sim_tbody_result">
                                                    <!-- Generated via JS -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 2: MARGIN HARGA UMUM (GLOBAL)          -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade" id="tab-umum" role="tabpanel">
                        <div class="row g-3">
                            <!-- Card Form Margin Umum -->
                            <div class="col-12">
                                <div class="card border shadow-sm bg-white">
                                    <div class="card-header bg-light py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div>
                                            <h4 class="card-title fw-bold text-dark mb-0">
                                                <i class="ti ti-percentage me-2 text-primary fs-2"></i> Standar Margin Umum / Default (setpenjualanumum)
                                            </h4>
                                            <div class="text-muted small">Persentase keuntungan dasar yang digunakan jika obat tidak memiliki aturan khusus jenis/barang.</div>
                                        </div>
                                        <button type="button" class="btn btn-success fw-bold" onclick="applyMarginToDatabarang('umum')">
                                            <i class="ti ti-check-double me-1"></i> Terapkan ke Seluruh Data Obat
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <form id="formMarginUmum">
                                            @csrf
                                            <div class="row row-cards">
                                                <!-- Bagian Rawat Jalan & Apotek -->
                                                <div class="col-12">
                                                    <h5 class="text-success border-bottom pb-2 mb-3">
                                                        <i class="ti ti-building-store me-1"></i> Pelayanan Rawat Jalan & Penjualan Apotek
                                                    </h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label required fw-bold">Rawat Jalan (Ralan)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="ralan" value="{{ $marginUmum->ralan }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <span class="form-text small">Resep Pasien Rawat Jalan</span>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label required fw-bold">Jual Bebas (OTC)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="jualbebas" value="{{ $marginUmum->jualbebas }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <span class="form-text small">Penjualan Apotek Tanpa Resep</span>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label required fw-bold">Karyawan</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="karyawan" value="{{ $marginUmum->karyawan }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <span class="form-text small">Tarif Khusus Staf / Karyawan</span>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label required fw-bold">Beli Luar</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="beliluar" value="{{ $marginUmum->beliluar }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <span class="form-text small">Resep Pembelian dari Luar</span>
                                                </div>

                                                <!-- Bagian Rawat Inap -->
                                                <div class="col-12 mt-3">
                                                    <h5 class="text-purple border-bottom pb-2 mb-3">
                                                        <i class="ti ti-bed me-1"></i> Pelayanan Rawat Inap (Per Kelas Kamar)
                                                    </h5>
                                                </div>
                                                <div class="col-md-2 col-6 mb-3">
                                                    <label class="form-label required fw-bold">Kelas 3</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="kelas3" value="{{ $marginUmum->kelas3 }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 mb-3">
                                                    <label class="form-label required fw-bold">Kelas 2</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="kelas2" value="{{ $marginUmum->kelas2 }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 mb-3">
                                                    <label class="form-label required fw-bold">Kelas 1</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="kelas1" value="{{ $marginUmum->kelas1 }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 mb-3">
                                                    <label class="form-label required fw-bold">Kelas Utama</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="utama" value="{{ $marginUmum->utama }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 mb-3">
                                                    <label class="form-label required fw-bold">Kelas VIP</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="vip" value="{{ $marginUmum->vip }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-6 mb-3">
                                                    <label class="form-label required fw-bold">Kelas VVIP</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" min="0" class="form-control text-end fw-bold" name="vvip" value="{{ $marginUmum->vvip }}" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                                                <button type="submit" class="btn btn-primary px-4" id="btnSimpanMarginUmum">
                                                    <i class="ti ti-device-floppy me-1"></i> Simpan Margin Umum
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Monitoring / Lookup Data Obat Master -->
                            <div class="col-12">
                                <div class="card border shadow-sm bg-white">
                                    <div class="card-header bg-light py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div>
                                            <h4 class="card-title fw-bold text-dark mb-0">
                                                <i class="ti ti-table-alias me-2 text-primary fs-2"></i> Monitoring & Lookup Harga Obat (Master DataBarang)
                                            </h4>
                                            <div class="text-muted small">
                                                Pantau dan verifikasi langsung perubahan nominal harga jual seluruh obat di sistem.
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge bg-blue-lt d-none d-md-inline-flex">
                                                <i class="ti ti-refresh-dot me-1"></i> Auto-refresh saat Terapkan Harga
                                            </span>
                                            <button type="button" class="btn btn-primary btn-sm fw-bold" id="btnRefreshMonitoring">
                                                <i class="ti ti-refresh me-1"></i> Refresh Data Obat
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Filter Bar -->
                                        <div class="row g-2 mb-3 align-items-center">
                                            <div class="col-md-3">
                                                <label class="form-label small fw-bold text-muted mb-1">Filter Kategori / Jenis Obat:</label>
                                                <select class="form-select" id="filter_monitoring_jenis">
                                                    <option value="">-- Semua Jenis Obat --</option>
                                                    @foreach($jenisList as $jns)
                                                        <option value="{{ $jns->kdjns }}">{{ $jns->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-9 text-md-end pt-md-3">
                                                <div class="text-muted small">
                                                    <i class="ti ti-info-circle me-1 text-info"></i> Menampilkan obat berstatus <strong>Aktif</strong>. Klik tombol <span class="badge bg-secondary-lt"><i class="ti ti-eye"></i> Detail Tarif</span> untuk melihat rincian seluruh 10 kategori harga.
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tabel Monitoring Data Obat -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover align-middle nowrap w-100" id="tbMonitoringObat">
                                                <thead>
                                                    <tr>
                                                        <th width="30">No</th>
                                                        <th>Kode</th>
                                                        <th>Nama Obat</th>
                                                        <th>Satuan</th>
                                                        <th>Jenis</th>
                                                        <th class="text-end">H. Beli</th>
                                                        <th class="text-end">H. Dasar</th>
                                                        <th class="text-end">Ralan</th>
                                                        <th class="text-end">Jual Bebas</th>
                                                        <th class="text-end">Kelas 1</th>
                                                        <th class="text-end">Kelas 3</th>
                                                        <th class="text-end">VIP</th>
                                                        <th class="text-end">Karyawan</th>
                                                        <th class="text-center" width="70">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 3: MARGIN PER JENIS OBAT               -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade" id="tab-jenis" role="tabpanel">
                        <div class="card border shadow-sm">
                            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div>
                                    <h4 class="card-title fw-bold mb-0">
                                        <i class="ti ti-category me-2 text-primary fs-2"></i> Pengaturan Margin Per Jenis / Kelompok Barang (setpenjualan)
                                    </h4>
                                    <div class="text-muted small">Atur margin spesifik berdasarkan kelompok obat (misal: Obat Keras, Injeksi, Alkes, Generik).</div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="openModalTambahJenis()">
                                    <i class="ti ti-plus me-1"></i> Tambah Margin Jenis
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle nowrap w-100" id="tbSetJenis">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Nama Kategori / Jenis</th>
                                                <th>Ralan</th>
                                                <th>Jual Bebas</th>
                                                <th>Karyawan</th>
                                                <th>Beli Luar</th>
                                                <th>Kelas 3</th>
                                                <th>Kelas 2</th>
                                                <th>Kelas 1</th>
                                                <th>Utama / VIP</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- TAB 4: MARGIN KHUSUS PER OBAT              -->
                    <!-- ========================================== -->
                    <div class="tab-pane fade" id="tab-barang" role="tabpanel">
                        <div class="card border shadow-sm">
                            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div>
                                    <h4 class="card-title fw-bold mb-0">
                                        <i class="ti ti-pill me-2 text-primary fs-2"></i> Pengaturan Margin Khusus Per Obat (setpenjualanperbarang)
                                    </h4>
                                    <div class="text-muted small">Gunakan ini jika ada obat tertentu yang persentase labanya harus berbeda dari jenis obatnya.</div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="openModalTambahBarang()">
                                    <i class="ti ti-plus me-1"></i> Tambah Margin Per Obat
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle nowrap w-100" id="tbSetBarang">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Obat</th>
                                                <th>Nama Obat / BHP</th>
                                                <th>Satuan</th>
                                                <th>Harga Beli</th>
                                                <th>Ralan</th>
                                                <th>Jual Bebas</th>
                                                <th>Kelas 3</th>
                                                <th>Kelas 2</th>
                                                <th>Kelas 1</th>
                                                <th>VIP / VVIP</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL FORM MARGIN PER JENIS                                   -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalFormJenis" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="titleModalJenis">
                        <i class="ti ti-category me-2"></i> Setup Margin Jenis Obat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formJenisModal">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label required fw-bold">Pilih Kategori / Jenis Obat</label>
                            <select class="form-select select-modal-jenis" name="kdjns" id="modal_kdjns" required style="width: 100%">
                                <option value="">-- Pilih Jenis Obat --</option>
                                @foreach($jenisList as $jns)
                                    <option value="{{ $jns->kdjns }}">{{ $jns->nama }} ({{ $jns->kdjns }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row row-cards">
                            <div class="col-12">
                                <h6 class="text-success border-bottom pb-1 mb-2">Rawat Jalan & Penjualan Apotek</h6>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Ralan (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="ralan" id="mj_ralan" required>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Jual Bebas (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="jualbebas" id="mj_jualbebas" required>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Karyawan (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="karyawan" id="mj_karyawan" required>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Beli Luar (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="beliluar" id="mj_beliluar" required>
                            </div>

                            <div class="col-12 mt-2">
                                <h6 class="text-purple border-bottom pb-1 mb-2">Rawat Inap (Per Kelas)</h6>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Kelas 3 (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="kelas3" id="mj_kelas3" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Kelas 2 (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="kelas2" id="mj_kelas2" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Kelas 1 (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="kelas1" id="mj_kelas1" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Utama (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="utama" id="mj_utama" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">VIP (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="vip" id="mj_vip" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">VVIP (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="vvip" id="mj_vvip" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanModalJenis">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Margin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL FORM MARGIN PER BARANG                                  -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalFormBarang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="titleModalBarang">
                        <i class="ti ti-pill me-2"></i> Setup Margin Khusus Obat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formBarangModal">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label required fw-bold">Cari & Pilih Obat / BHP</label>
                            <select class="form-select" name="kode_brng" id="modal_kode_brng" required style="width: 100%">
                                <option value="">-- Ketik nama atau kode obat --</option>
                            </select>
                            <div class="form-text small" id="lbl_obat_detail"></div>
                        </div>

                        <div class="row row-cards">
                            <div class="col-12">
                                <h6 class="text-success border-bottom pb-1 mb-2">Rawat Jalan & Penjualan Apotek</h6>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Ralan (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="ralan" id="mb_ralan" required>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Jual Bebas (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="jualbebas" id="mb_jualbebas" required>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Karyawan (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="karyawan" id="mb_karyawan" required>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <label class="form-label required">Beli Luar (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="beliluar" id="mb_beliluar" required>
                            </div>

                            <div class="col-12 mt-2">
                                <h6 class="text-purple border-bottom pb-1 mb-2">Rawat Inap (Per Kelas)</h6>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Kelas 3 (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="kelas3" id="mb_kelas3" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Kelas 2 (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="kelas2" id="mb_kelas2" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Kelas 1 (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="kelas1" id="mb_kelas1" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">Utama (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="utama" id="mb_utama" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">VIP (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="vip" id="mb_vip" required>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <label class="form-label required">VVIP (%)</label>
                                <input type="number" step="0.1" min="0" class="form-control" name="vvip" id="mb_vvip" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpanModalBarang">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Margin Obat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL DETAIL 10 TARIF OBAT (LOOKUP)                            -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalDetailTarifObat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ti ti-report-money me-2"></i> Rincian 10 Kategori Tarif Jual Obat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalDetailTarifBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-2 text-muted">Memuat rincian tarif...</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let tbSetJenis = null;
        let tbSetBarang = null;
        let tbMonitoringObat = null;

        // Helper format rupiah & toasts
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) return '0';
            const number = Math.round(Number(angka));
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function toastSuccess(msg) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: msg,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        function toastError(msg) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: msg,
                timer: 3500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        $(document).ready(function() {
            // Inisialisasi select2
            if ($.fn.select2) {
                $('.select-sim-jenis').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
                $('.select-modal-jenis').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#modalFormJenis'),
                    width: '100%'
                });

                $('#modal_kode_brng').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#modalFormBarang'),
                    width: '100%',
                    ajax: {
                        url: `{{ url('/farmasi/set-harga/search-barang') }}`,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { q: params.term };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.kode_brng,
                                        text: `${item.nama_brng} (${item.kode_brng}) - Beli: Rp ${formatRupiah(item.h_beli)}`,
                                        itemData: item
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    const data = e.params.data.itemData;
                    if (data) {
                        $('#lbl_obat_detail').html(`Harga Beli Saat Ini: <strong>Rp ${formatRupiah(data.h_beli)}</strong> | Satuan: <strong>${data.kode_sat || '-'}</strong>`);
                    }
                });
            }

            // Jalankan simulator awal
            runSimulator();

            $('#sim_h_beli').on('input', function() {
                runSimulator();
            });

            $('#sim_kdjns').on('change', function() {
                runSimulator();
            });

            // Submit Form Kebijakan Induk
            $('#formKebijakan').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSimpanKebijakan');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.post(`{{ url('/farmasi/set-harga/update-pengaturan') }}`, $(this).serialize())
                    .done(function(res) {
                        toastSuccess(res.message);
                        // Update Badges
                        const setharga = $('input[name="setharga"]:checked').val();
                        const hargadasar = $('#cfg_hargadasar').val();
                        const ppn = $('input[name="ppn"]:checked').val();

                        $('#badge_current_mode').html(`<i class="ti ti-layers-subtract"></i> <span>Mode: ${setharga}</span>`);
                        $('#badge_current_dasar').html(`<i class="ti ti-receipt-2"></i> <span>Dasar: ${hargadasar}</span>`);
                        $('#badge_current_ppn').html(`<i class="ti ti-percentage"></i> <span>PPN: ${ppn === 'Yes' ? 'Ya (11%)' : 'Tidak'}</span>`);

                        runSimulator();
                    })
                    .fail(function(xhr) {
                        toastError(xhr.responseJSON?.message || 'Gagal menyimpan kebijakan');
                    })
                    .always(function() {
                        btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Kebijakan Sistem');
                    });
            });

            // Submit Form Margin Umum
            $('#formMarginUmum').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSimpanMarginUmum');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.post(`{{ url('/farmasi/set-harga/update-margin-umum') }}`, $(this).serialize())
                    .done(function(res) {
                        toastSuccess(res.message);
                        runSimulator();
                    })
                    .fail(function(xhr) {
                        toastError(xhr.responseJSON?.message || 'Gagal menyimpan margin umum');
                    })
                    .always(function() {
                        btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Margin Umum');
                    });
            });

            // Tab Event Listeners untuk init DataTables
            $('#btnTabUmum').on('shown.bs.tab', function () {
                if (!tbMonitoringObat) {
                    initTableMonitoring();
                } else {
                    tbMonitoringObat.ajax.reload(null, false);
                }
            });

            $('#filter_monitoring_jenis').on('change', function() {
                if (tbMonitoringObat) {
                    tbMonitoringObat.ajax.reload();
                }
            });

            $('#btnRefreshMonitoring').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memuat...');
                if (tbMonitoringObat) {
                    tbMonitoringObat.ajax.reload(function() {
                        btn.prop('disabled', false).html('<i class="ti ti-refresh me-1"></i> Refresh Data Obat');
                        toastSuccess('Data obat berhasil dimuat ulang');
                    }, false);
                } else {
                    initTableMonitoring();
                    btn.prop('disabled', false).html('<i class="ti ti-refresh me-1"></i> Refresh Data Obat');
                }
            });

            $('#btnTabJenis').on('shown.bs.tab', function () {
                if (!tbSetJenis) {
                    initTableJenis();
                } else {
                    tbSetJenis.ajax.reload(null, false);
                }
            });

            $('#btnTabBarang').on('shown.bs.tab', function () {
                if (!tbSetBarang) {
                    initTableBarang();
                } else {
                    tbSetBarang.ajax.reload(null, false);
                }
            });

            // Form Modal Jenis Submit
            $('#formJenisModal').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSimpanModalJenis');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.post(`{{ url('/farmasi/set-harga/store-jenis') }}`, $(this).serialize())
                    .done(function(res) {
                        toastSuccess(res.message);
                        $('#modalFormJenis').modal('hide');
                        if (tbSetJenis) tbSetJenis.ajax.reload(null, false);
                        runSimulator();
                    })
                    .fail(function(xhr) {
                        toastError(xhr.responseJSON?.message || 'Gagal menyimpan margin jenis');
                    })
                    .always(function() {
                        btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Margin');
                    });
            });

            // Form Modal Barang Submit
            $('#formBarangModal').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSimpanModalBarang');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.post(`{{ url('/farmasi/set-harga/store-barang') }}`, $(this).serialize())
                    .done(function(res) {
                        toastSuccess(res.message);
                        $('#modalFormBarang').modal('hide');
                        if (tbSetBarang) tbSetBarang.ajax.reload(null, false);
                    })
                    .fail(function(xhr) {
                        toastError(xhr.responseJSON?.message || 'Gagal menyimpan margin obat');
                    })
                    .always(function() {
                        btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Margin Obat');
                    });
            });
        });

        // -------------------------------------------------------------
        // Live Price Simulator
        // -------------------------------------------------------------
        function runSimulator() {
            const hBeli = parseFloat($('#sim_h_beli').val()) || 0;
            const kdjns = $('#sim_kdjns').val();

            $.get(`{{ url('/farmasi/set-harga/simulate-price') }}`, {
                h_beli: hBeli,
                kdjns: kdjns
            }).done(function(res) {
                $('#sim_lbl_dasar').text('Rp ' + formatRupiah(res.h_dasar));
                $('#sim_lbl_mode_applied').text('Mode: ' + res.mode_applied);

                const labels = {
                    ralan: 'Rawat Jalan (Ralan)',
                    jualbebas: 'Jual Bebas (Apotek Bebas)',
                    karyawan: 'Karyawan',
                    beliluar: 'Beli Luar',
                    kelas3: 'Rawat Inap - Kelas 3',
                    kelas2: 'Rawat Inap - Kelas 2',
                    kelas1: 'Rawat Inap - Kelas 1',
                    utama: 'Rawat Inap - Utama',
                    vip: 'Rawat Inap - VIP',
                    vvip: 'Rawat Inap - VVIP',
                };

                let html = '';
                for (const [key, val] of Object.entries(res.prices)) {
                    html += `
                        <tr>
                            <td>
                                <span class="fw-semibold">${labels[key] || key}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-lt">${val.margin}%</span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp ${formatRupiah(val.harga)}
                            </td>
                        </tr>
                    `;
                }
                $('#sim_tbody_result').html(html);
            });
        }

        // -------------------------------------------------------------
        // DataTables: Margin Per Jenis
        // -------------------------------------------------------------
        function initTableJenis() {
            tbSetJenis = $('#tbSetJenis').DataTable({
                processing: true,
                serverSide: false,
                ajax: `{{ url('/farmasi/set-harga/data-jenis') }}`,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                    { data: 'kdjns', name: 'kdjns', width: '8%' },
                    { 
                        data: 'nama_jenis',
                        name: 'nama_jenis',
                        render: (data, type, row) => `<strong>${data}</strong>`
                    },
                    { data: 'ralan', render: d => `${d}%` },
                    { data: 'jualbebas', render: d => `${d}%` },
                    { data: 'karyawan', render: d => `${d}%` },
                    { data: 'beliluar', render: d => `${d}%` },
                    { data: 'kelas3', render: d => `${d}%` },
                    { data: 'kelas2', render: d => `${d}%` },
                    { data: 'kelas1', render: d => `${d}%` },
                    { 
                        data: null,
                        render: row => `${row.utama}% / ${row.vip}%`
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data) {
                            return `
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="applyMarginToDatabarang('jenis', '${data.kdjns}', '${data.nama_jenis}')" title="Terapkan ke data obat jenis ini">
                                        <i class="ti ti-check-double me-1"></i> Terapkan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick='editMarginJenis(${JSON.stringify(data)})' title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMarginJenis('${data.kdjns}', '${data.nama_jenis}')" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });
        }

        function openModalTambahJenis() {
            $('#formJenisModal')[0].reset();
            $('#modal_kdjns').val('').trigger('change').prop('disabled', false);
            $('#titleModalJenis').html('<i class="ti ti-category me-2"></i> Tambah Margin Jenis Obat');
            $('#modalFormJenis').modal('show');
        }

        function editMarginJenis(data) {
            $('#formJenisModal')[0].reset();
            $('#modal_kdjns').val(data.kdjns).trigger('change').prop('disabled', true);
            $('#mj_ralan').val(data.ralan);
            $('#mj_jualbebas').val(data.jualbebas);
            $('#mj_karyawan').val(data.karyawan);
            $('#mj_beliluar').val(data.beliluar);
            $('#mj_kelas3').val(data.kelas3);
            $('#mj_kelas2').val(data.kelas2);
            $('#mj_kelas1').val(data.kelas1);
            $('#mj_utama').val(data.utama);
            $('#mj_vip').val(data.vip);
            $('#mj_vvip').val(data.vvip);

            $('#titleModalJenis').html('<i class="ti ti-pencil me-2"></i> Edit Margin Jenis Obat: ' + data.nama_jenis);
            $('#modalFormJenis').modal('show');
        }

        function deleteMarginJenis(kdjns, nama) {
            Swal.fire({
                title: 'Hapus Margin Jenis?',
                text: `Pengaturan margin untuk jenis "${nama}" akan dihapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/farmasi/set-harga/delete-jenis') }}/${kdjns}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            toastSuccess(res.message);
                            if (tbSetJenis) tbSetJenis.ajax.reload(null, false);
                            runSimulator();
                        },
                        error: function(xhr) {
                            toastError(xhr.responseJSON?.message || 'Gagal menghapus');
                        }
                    });
                }
            });
        }

        // -------------------------------------------------------------
        // DataTables: Margin Per Barang
        // -------------------------------------------------------------
        function initTableBarang() {
            tbSetBarang = $('#tbSetBarang').DataTable({
                processing: true,
                serverSide: false,
                ajax: `{{ url('/farmasi/set-harga/data-barang') }}`,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                    { data: 'kode_brng', name: 'kode_brng', width: '10%' },
                    { 
                        data: 'nama_brng',
                        name: 'nama_brng',
                        render: (data, type, row) => `<strong>${data}</strong>`
                    },
                    { data: 'satuan', width: '8%' },
                    { 
                        data: 'h_beli', 
                        render: d => 'Rp ' + formatRupiah(d)
                    },
                    { data: 'ralan', render: d => `${d}%` },
                    { data: 'jualbebas', render: d => `${d}%` },
                    { data: 'kelas3', render: d => `${d}%` },
                    { data: 'kelas2', render: d => `${d}%` },
                    { data: 'kelas1', render: d => `${d}%` },
                    { 
                        data: null,
                        render: row => `${row.vip}% / ${row.vvip}%`
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data) {
                            return `
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="applyMarginToDatabarang('barang', '${data.kode_brng}', '${data.nama_brng}')" title="Terapkan ke obat ini">
                                        <i class="ti ti-check-double me-1"></i> Terapkan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick='editMarginBarang(${JSON.stringify(data)})' title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMarginBarang('${data.kode_brng}', '${data.nama_brng}')" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });
        }

        function openModalTambahBarang() {
            $('#formBarangModal')[0].reset();
            $('#modal_kode_brng').val(null).trigger('change').prop('disabled', false);
            $('#lbl_obat_detail').html('');
            $('#titleModalBarang').html('<i class="ti ti-pill me-2"></i> Tambah Margin Khusus Per Obat');
            $('#modalFormBarang').modal('show');
        }

        function editMarginBarang(data) {
            $('#formBarangModal')[0].reset();
            const option = new Option(`${data.nama_brng} (${data.kode_brng})`, data.kode_brng, true, true);
            $('#modal_kode_brng').append(option).trigger('change').prop('disabled', true);
            $('#lbl_obat_detail').html(`Harga Beli Saat Ini: <strong>Rp ${formatRupiah(data.h_beli)}</strong> | Satuan: <strong>${data.satuan || '-'}</strong>`);

            $('#mb_ralan').val(data.ralan);
            $('#mb_jualbebas').val(data.jualbebas);
            $('#mb_karyawan').val(data.karyawan);
            $('#mb_beliluar').val(data.beliluar);
            $('#mb_kelas3').val(data.kelas3);
            $('#mb_kelas2').val(data.kelas2);
            $('#mb_kelas1').val(data.kelas1);
            $('#mb_utama').val(data.utama);
            $('#mb_vip').val(data.vip);
            $('#mb_vvip').val(data.vvip);

            $('#titleModalBarang').html('<i class="ti ti-pencil me-2"></i> Edit Margin Khusus: ' + data.nama_brng);
            $('#modalFormBarang').modal('show');
        }

        function deleteMarginBarang(kode_brng, nama) {
            Swal.fire({
                title: 'Hapus Margin Obat?',
                text: `Pengaturan margin khusus untuk obat "${nama}" akan dihapus.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/farmasi/set-harga/delete-barang') }}/${kode_brng}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            toastSuccess(res.message);
                            if (tbSetBarang) tbSetBarang.ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            toastError(xhr.responseJSON?.message || 'Gagal menghapus');
                        }
                    });
                }
            });
        }

        // -------------------------------------------------------------
        // Terapkan / Update Harga ke Databarang
        // -------------------------------------------------------------
        function applyMarginToDatabarang(scope, target = '', namaTarget = '') {
            let scopeText = '';
            if (scope === 'umum') {
                scopeText = 'Seluruh data master obat di sistem';
            } else if (scope === 'jenis') {
                scopeText = `Seluruh obat dalam kelompok/jenis "${namaTarget}"`;
            } else if (scope === 'barang') {
                scopeText = `Obat "${namaTarget}"`;
            }

            Swal.fire({
                title: 'Terapkan Harga ke Data Obat?',
                html: `Sistem akan menghitung ulang dan memperbarui harga jual (Ralan, Ranap, Jual Bebas, dll) pada <strong>${scopeText}</strong>.<br><br><span class="text-muted small">Proses ini akan merevisi kolom harga di tabel master <code>databarang</code>.</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2fb344',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-check me-1"></i> Ya, Terapkan Sekarang'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Pembaharuan Harga...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post(`{{ url('/farmasi/set-harga/apply-harga') }}`, {
                        _token: '{{ csrf_token() }}',
                        scope: scope,
                        target: target
                    }).done(function(res) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.message,
                            icon: 'success'
                        });
                        if (tbSetBarang) tbSetBarang.ajax.reload(null, false);
                        if (tbMonitoringObat) tbMonitoringObat.ajax.reload(null, false);
                    }).fail(function(xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal menerapkan harga obat',
                            icon: 'error'
                        });
                    });
                }
            });
        }

        // -------------------------------------------------------------
        // Inisialisasi DataTable Monitoring Master Obat
        // -------------------------------------------------------------
        function initTableMonitoring() {
            tbMonitoringObat = $('#tbMonitoringObat').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    emptyTable: 'Tidak ada data obat aktif',
                    search: 'Cari Obat:',
                    searchPlaceholder: 'Ketik nama / kode obat...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    loadingRecords: 'Memuat data...',
                    processing: '<div class="spinner-border spinner-border-sm text-primary"></div> Memuat data...',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: '&raquo;',
                        previous: '&laquo;'
                    }
                },
                ajax: {
                    url: `{{ url('/farmasi/set-harga/data-monitoring-obat') }}`,
                    data: function(d) {
                        d.kdjns = $('#filter_monitoring_jenis').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'kode_brng', name: 'kode_brng', className: 'fw-bold text-muted font-monospace' },
                    { 
                        data: 'nama_brng', 
                        name: 'nama_brng', 
                        className: 'fw-bold text-dark',
                        render: function(data) {
                            return `<span class="fw-bold text-dark">${data}</span>`;
                        }
                    },
                    { data: 'satuan_nama', name: 'satuan.satuan', className: 'text-center' },
                    { 
                        data: 'jenis_nama', 
                        name: 'jenis.nama',
                        render: function(data) {
                            return `<span class="badge bg-secondary-lt">${data || '-'}</span>`;
                        }
                    },
                    { 
                        data: 'h_beli', 
                        name: 'h_beli', 
                        className: 'text-end fw-semibold',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'dasar', 
                        name: 'dasar', 
                        className: 'text-end fw-bold text-info',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'ralan', 
                        name: 'ralan', 
                        className: 'text-end fw-bold text-primary',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'jualbebas', 
                        name: 'jualbebas', 
                        className: 'text-end fw-bold text-success',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'kelas1', 
                        name: 'kelas1', 
                        className: 'text-end',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'kelas3', 
                        name: 'kelas3', 
                        className: 'text-end',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'vip', 
                        name: 'vip', 
                        className: 'text-end',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'karyawan', 
                        name: 'karyawan', 
                        className: 'text-end',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                                <button type="button" class="btn btn-sm btn-outline-info px-2 py-1" onclick="showDetailTarif('${row.kode_brng}')" title="Lihat Rincian 10 Tarif">
                                    <i class="ti ti-eye me-1"></i> Detail
                                </button>
                            `;
                        }
                    }
                ]
            });
        }

        // -------------------------------------------------------------
        // Modal Detail 10 Tarif Obat
        // -------------------------------------------------------------
        function showDetailTarif(kode_brng) {
            $('#modalDetailTarifBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">Memuat rincian tarif...</div>
                </div>
            `);
            $('#modalDetailTarifObat').modal('show');

            $.get(`{{ url('/farmasi/set-harga/detail-obat') }}/${kode_brng}`)
                .done(function(res) {
                    if (res.status === 'success') {
                        const d = res.data;
                        let pricesHtml = '';

                        Object.keys(d.prices).forEach(function(key) {
                            const p = d.prices[key];
                            pricesHtml += `
                                <tr>
                                    <td class="fw-bold text-dark">${p.label}</td>
                                    <td class="text-center"><span class="badge bg-primary-lt fw-bold">${p.margin}%</span></td>
                                    <td class="text-end fw-bold text-primary fs-3">Rp ${formatRupiah(p.harga)}</td>
                                </tr>
                            `;
                        });

                        $('#modalDetailTarifBody').html(`
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body py-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <h4 class="fw-bold text-dark mb-1">${d.nama_brng}</h4>
                                            <div class="text-muted small">
                                                Kode: <span class="badge bg-secondary-lt font-monospace">${d.kode_brng}</span> &bull; 
                                                Satuan: <strong>${d.satuan}</strong> &bull; 
                                                Jenis: <strong>${d.jenis}</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-5 text-md-end mt-2 mt-md-0">
                                            <div class="text-muted small">Harga Beli: <strong>Rp ${formatRupiah(d.h_beli)}</strong></div>
                                            <div class="text-info small fw-bold">Harga Dasar (+PPN): Rp ${formatRupiah(d.dasar)}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kategori Pelayanan</th>
                                            <th class="text-center" width="100">Margin</th>
                                            <th class="text-end" width="160">Harga Jual Obat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${pricesHtml}
                                    </tbody>
                                </table>
                            </div>
                        `);
                    }
                })
                .fail(function(xhr) {
                    $('#modalDetailTarifBody').html(`
                        <div class="alert alert-danger mb-0">
                            <i class="ti ti-alert-circle me-1"></i> ${xhr.responseJSON?.message || 'Gagal memuat detail obat'}
                        </div>
                    `);
                });
        }
    </script>
@endpush

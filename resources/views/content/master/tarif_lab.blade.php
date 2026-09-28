@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-3">
            <!-- Left Side: Table List -->
            <div class="col-xl-7 col-lg-6 col-md-12 col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1 fw-bold text-dark">
                                <i class="ti ti-test-pipe text-primary me-2"></i>Master Tarif & Tindakan Laboratorium
                            </h4>
                            <div class="text-muted small">Kelola master paket pemeriksaan laboratorium, komponen tarif, dan sub-parameter tes</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary shadow-sm" onclick="resetFormTarifLab()">
                            <i class="ti ti-plus me-1"></i> Tambah Baru
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row g-2 mb-3 bg-light p-2 rounded-2">
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Kategori Lab</label>
                                <select class="form-select form-select-sm" id="filter_kategori" onchange="$('#tbTarifLab').DataTable().ajax.reload()">
                                    <option value="">Semua Kategori</option>
                                    <option value="PK">PK (Patologi Klinik)</option>
                                    <option value="PA">PA (Patologi Anatomi)</option>
                                    <option value="MB">MB (Mikrobiologi)</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">Penjamin / Cara Bayar</label>
                                <select class="form-select form-select-sm" id="filter_pj" onchange="$('#tbTarifLab').DataTable().ajax.reload()">
                                    <option value="">Semua Penjamin</option>
                                    @foreach($penjab as $pj)
                                        <option value="{{ $pj->kd_pj }}">{{ $pj->png_jawab }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Status</label>
                                <select class="form-select form-select-sm" id="filter_status" onchange="$('#tbTarifLab').DataTable().ajax.reload()">
                                    <option value="">Semua</option>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5 align-middle" id="tbTarifLab">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80px">Kode</th>
                                        <th>Nama Pemeriksaan</th>
                                        <th width="70px" class="text-center">Kategori</th>
                                        <th>Penjamin</th>
                                        <th width="100px" class="text-center">Sub-Item</th>
                                        <th class="text-end">Total Tarif</th>
                                        <th width="70px" class="text-center">Status</th>
                                        <th width="110px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form Input / Edit -->
            <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12">
                <form id="formTarifLab">
                    @csrf
                    <input type="hidden" id="form_mode" value="create">
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-primary fw-bold" id="formTitle">
                                <i class="ti ti-forms me-1"></i> Form Input Tarif Lab
                            </h5>
                            <span class="badge bg-blue-lt" id="badgeFormMode">Mode: Tambah Baru</span>
                        </div>
                        <div class="card-body">
                            <!-- Basic Information -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Kode Tindakan</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control fw-bold text-uppercase" id="kd_jenis_prw" name="kd_jenis_prw" placeholder="J000110" required maxlength="15">
                                        <button class="btn btn-outline-primary" type="button" onclick="generateKodeLab()" id="btnAutoKode" title="Generate otomatis kode berikutnya">
                                            <i class="ti ti-wand me-1"></i> Auto
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Kategori Lab</label>
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <option value="PK" selected>PK (Patologi Klinik)</option>
                                        <option value="PA">PA (Patologi Anatomi)</option>
                                        <option value="MB">MB (Mikrobiologi)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Nama Pemeriksaan Lab</label>
                                <input type="text" class="form-control" id="nm_perawatan" name="nm_perawatan" placeholder="Contoh: Hematologi Lengkap / Glukosa Puasa" required maxlength="80">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Penjamin (Cara Bayar)</label>
                                    <select class="form-select" id="kd_pj" name="kd_pj" required>
                                        @foreach($penjab as $pj)
                                            <option value="{{ $pj->kd_pj }}" {{ $pj->kd_pj == 'A09' || $pj->kd_pj == '-' ? 'selected' : '' }}>
                                                {{ $pj->png_jawab }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Kelas Pelayanan</label>
                                    <select class="form-select" id="kelas" name="kelas" required>
                                        <option value="Rawat Jalan" selected>Rawat Jalan</option>
                                        <option value="-">-</option>
                                        <option value="Kelas 1">Kelas 1</option>
                                        <option value="Kelas 2">Kelas 2</option>
                                        <option value="Kelas 3">Kelas 3</option>
                                        <option value="Kelas Utama">Kelas Utama</option>
                                        <option value="Kelas VIP">Kelas VIP</option>
                                        <option value="Kelas VVIP">Kelas VVIP</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Aktif</label>
                                <div class="d-flex gap-3">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_1" value="1" checked>
                                        <span class="form-check-label text-success fw-bold"><i class="ti ti-check me-1"></i> Aktif</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_0" value="0">
                                        <span class="form-check-label text-danger fw-bold"><i class="ti ti-x me-1"></i> Non-Aktif</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Tarif Breakdown Section -->
                            <div class="border rounded-2 p-3 bg-light mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark small text-uppercase"><i class="ti ti-calculator me-1"></i> Rincian Komponen Tarif (Rp)</span>
                                    <span class="small text-muted">Kalkulasi Otomatis</span>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small mb-1">Jasa Klinik / Sarana</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="bagian_rs" name="bagian_rs" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1">BHP / Reagen Lab</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="bhp" name="bhp" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1">Jasa Dokter</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="tarif_tindakan_dokter" name="tarif_tindakan_dokter" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-1">Jasa Petugas / Analis</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="tarif_tindakan_petugas" name="tarif_tindakan_petugas" value="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small mb-1">Jasa Perujuk</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="tarif_perujuk" name="tarif_perujuk" value="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small mb-1">KSO</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="kso" name="kso" value="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small mb-1">Manajemen</label>
                                        <input type="number" step="any" min="0" class="form-control form-control-sm text-end calc-tarif" id="menejemen" name="menejemen" value="0">
                                    </div>
                                </div>

                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-4 text-dark">Total Biaya:</span>
                                    <div class="text-end">
                                        <span class="fs-2 fw-bolder text-success" id="displayTotalByr">Rp 0</span>
                                        <input type="hidden" id="total_byr" name="total_byr" value="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary w-50" onclick="resetFormTarifLab()">
                                    <i class="ti ti-refresh me-1"></i> Batal / Reset
                                </button>
                                <button type="submit" class="btn btn-success w-50" id="btnSubmitTarif">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan Tarif
                                </button>
                            </div>

                            <!-- Dedicated Manage Template Button -->
                            <div class="mt-3 pt-2 border-top" id="wrapBtnKelolaTemplate" style="display: none;">
                                <button type="button" class="btn btn-outline-primary w-100 shadow-sm" onclick="openTemplateModalCurrent()">
                                    <i class="ti ti-list-details me-1"></i> Kelola Sub-Pemeriksaan & Nilai Rujukan (<span id="countCurrentTemplate">0</span> Item)
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: KELOLA SUB-PEMERIKSAAN & NILAI RUJUKAN (TEMPLATE LABORATORIUM) -->
    <div class="modal fade" id="modalTemplateLab" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light py-3">
                    <div>
                        <h4 class="modal-title fw-bold text-dark mb-0">
                            <i class="ti ti-dna-2 text-primary me-2"></i>Sub-Pemeriksaan & Nilai Rujukan
                        </h4>
                        <div class="text-muted small mt-1">
                            Pemeriksaan: <strong class="text-primary" id="tplLabNama">-</strong> | 
                            Kode: <span class="badge bg-secondary-lt" id="tplLabKode">-</span> | 
                            Kategori: <span class="badge bg-blue-lt" id="tplLabKategori">-</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-indigo" onclick="openModalCopyTemplate()">
                            <i class="ti ti-copy me-1"></i> Salin dari Paket Lain
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-3">
                    <!-- Quick Form Input / Edit Item Template -->
                    <div class="card border border-primary-subtle bg-light mb-4">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-bold small text-primary" id="tplFormTitle">
                                <i class="ti ti-plus me-1"></i> Tambah Parameter / Sub-Item Baru
                            </span>
                            <span class="badge bg-green-lt" id="badgeTplMode">Mode Tambah</span>
                        </div>
                        <div class="card-body py-3">
                            <form id="formTemplateItem">
                                <input type="hidden" id="tpl_id_template" name="id_template" value="">
                                <input type="hidden" id="tpl_kd_jenis_prw" name="kd_jenis_prw" value="">

                                <div class="row g-2 mb-2">
                                    <div class="col-md-6 col-sm-12">
                                        <label class="form-label small required mb-1">Nama Parameter / Item Uji</label>
                                        <input type="text" class="form-control form-control-sm" id="tpl_Pemeriksaan" name="Pemeriksaan" placeholder="Contoh: Hemoglobin / SGOT / Leukosit" required maxlength="200">
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <label class="form-label small mb-1">Satuan</label>
                                        <input type="text" class="form-control form-control-sm" id="tpl_satuan" name="satuan" placeholder="Contoh: g/dL, mg/dL, /uL, %, -" maxlength="20">
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small mb-1">Urutan (No)</label>
                                        <input type="number" class="form-control form-control-sm text-center" id="tpl_urut" name="urut" placeholder="Auto" min="1">
                                    </div>
                                </div>

                                <!-- Nilai Rujukan (4 Kolom Sesuai Khanza) -->
                                <div class="p-2 border rounded-2 bg-white mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-bold text-muted text-uppercase">Nilai Rujukan / Nilai Normal</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="copyLdToAll()" title="Salin nilai rujukan Laki Dewasa ke semua kolom rujukan lainnya">
                                            <i class="ti ti-arrows-right me-1"></i> Samakan Semua Nilai Rujukan
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small mb-0 text-muted">Laki-laki Dewasa (LD)</label>
                                            <input type="text" class="form-control form-control-sm" id="tpl_nilai_rujukan_ld" name="nilai_rujukan_ld" placeholder="13.5 - 17.5" maxlength="30">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small mb-0 text-muted">Laki-laki Anak (LA)</label>
                                            <input type="text" class="form-control form-control-sm" id="tpl_nilai_rujukan_la" name="nilai_rujukan_la" placeholder="11.5 - 15.5" maxlength="30">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small mb-0 text-muted">Perempuan Dewasa (PD)</label>
                                            <input type="text" class="form-control form-control-sm" id="tpl_nilai_rujukan_pd" name="nilai_rujukan_pd" placeholder="12.0 - 16.0" maxlength="30">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small mb-0 text-muted">Perempuan Anak (PA)</label>
                                            <input type="text" class="form-control form-control-sm" id="tpl_nilai_rujukan_pa" name="nilai_rujukan_pa" placeholder="11.5 - 15.5" maxlength="30">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnCancelTplEdit" onclick="resetFormTemplateItem()" style="display: none;">
                                        <i class="ti ti-x me-1"></i> Batal Edit
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-primary" id="btnSubmitTpl">
                                        <i class="ti ti-plus me-1"></i> Simpan Parameter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Template Table -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0">Daftar Parameter Terdaftar</h5>
                        <span class="small text-muted"><span id="tplCountBadge">0</span> parameter tersimpan</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm w-100 fs-5 align-middle" id="tbTemplateList">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th width="50px">Urut</th>
                                    <th>Parameter Uji</th>
                                    <th width="90px">Satuan</th>
                                    <th>Laki Dewasa (LD)</th>
                                    <th>Laki Anak (LA)</th>
                                    <th>Prmp Dewasa (PD)</th>
                                    <th>Prmp Anak (PA)</th>
                                    <th width="90px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyTemplateList">
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: SALIN TEMPLATE DARI PAKET LAIN -->
    <div class="modal fade" id="modalCopyTemplate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light py-3">
                    <h5 class="modal-title fw-bold text-dark mb-0">
                        <i class="ti ti-copy text-indigo me-1"></i> Salin Sub-Pemeriksaan dari Paket Lain
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Pilih paket pemeriksaan lab sumber. Semua sub-item parameter dari paket sumber akan diduplikasi ke paket <strong id="copyTargetName">-</strong>.
                    </p>
                    <div class="mb-3">
                        <label class="form-label required">Paket Sumber (Asal)</label>
                        <select class="form-select" id="copySourceKd" required>
                            <option value="">-- Pilih Paket Lab Sumber --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-indigo" onclick="executeCopyTemplate()">
                        <i class="ti ti-check me-1"></i> Salin Semua Parameter
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    let currentActiveLab = null;

    $(document).ready(function() {
        initDataTableTarifLab();
        bindCalculators();
        generateKodeLab();

        // Form Submit Master Lab
        $('#formTarifLab').on('submit', function(e) {
            e.preventDefault();
            submitFormTarifLab();
        });

        // Form Submit Template Item
        $('#formTemplateItem').on('submit', function(e) {
            e.preventDefault();
            submitFormTemplateItem();
        });
    });

    // -------------------------------------------------------------
    // DATATABLE MASTER TARIF LAB
    // -------------------------------------------------------------
    function initDataTableTarifLab() {
        $('#tbTarifLab').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('master.tarif-lab.data') }}",
                data: function(d) {
                    d.kategori = $('#filter_kategori').val();
                    d.kd_pj    = $('#filter_pj').val();
                    d.status   = $('#filter_status').val();
                }
            },
            columns: [
                { 
                    data: 'kd_jenis_prw',
                    name: 'kd_jenis_prw',
                    render: function(data) {
                        return `<span class="badge bg-secondary-lt fw-bold">${data}</span>`;
                    }
                },
                { 
                    data: 'nm_perawatan',
                    name: 'nm_perawatan',
                    render: function(data, type, row) {
                        return `<div class="fw-bold text-dark">${data}</div><small class="text-muted">${row.kelas || 'Rawat Jalan'}</small>`;
                    }
                },
                { 
                    data: 'kategori',
                    name: 'kategori',
                    className: 'text-center',
                    render: function(data) {
                        let color = 'primary';
                        if (data === 'PA') color = 'purple';
                        if (data === 'MB') color = 'teal';
                        return `<span class="badge bg-${color}-lt fw-bold">${data}</span>`;
                    }
                },
                { 
                    data: 'penjab_nama',
                    name: 'penjab.png_jawab',
                    render: function(data) {
                        return `<span class="small">${data || '-'}</span>`;
                    }
                },
                { 
                    data: 'template_count',
                    name: 'template_count',
                    className: 'text-center',
                    render: function(data, type, row) {
                        let count = (data !== undefined && data !== null && !isNaN(data)) ? parseInt(data) : (row.template_count ? parseInt(row.template_count) : 0);
                        let badgeClass = count > 0 ? 'bg-indigo-lt text-indigo' : 'bg-light text-muted';
                        return `<button type="button" class="btn btn-xs ${badgeClass} border-0" onclick="openTemplateModal('${row.kd_jenis_prw}')" title="Klik untuk kelola sub-parameter">
                                    <i class="ti ti-dna-2 me-1"></i><strong>${count}</strong> item
                                </button>`;
                    }
                },
                { 
                    data: 'total_byr_formatted',
                    name: 'total_byr',
                    className: 'text-end fw-bold text-success'
                },
                { 
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data == '1') {
                            return `<span class="badge bg-success-lt cursor-pointer" onclick="toggleStatusLab('${row.kd_jenis_prw}')" title="Klik untuk nonaktifkan"><i class="ti ti-check me-1"></i>Aktif</span>`;
                        } else {
                            return `<span class="badge bg-danger-lt cursor-pointer" onclick="toggleStatusLab('${row.kd_jenis_prw}')" title="Klik untuk aktifkan"><i class="ti ti-x me-1"></i>Non-Aktif</span>`;
                        }
                    }
                },
                { 
                    data: 'kd_jenis_prw',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="editTarifLab('${data}')" title="Edit Tarif">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-indigo" onclick="openTemplateModal('${data}')" title="Kelola Sub-Pemeriksaan">
                                    <i class="ti ti-list-details"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="deleteTarifLab('${data}', '${row.nm_perawatan}')" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'asc']],
            language: {
                search: "Cari:",
                lengthMenu: "Tampil _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                emptyTable: "Belum ada master tarif laboratorium",
                paginate: {
                    first: "«",
                    previous: "‹",
                    next: "›",
                    last: "»"
                }
            }
        });
    }

    // -------------------------------------------------------------
    // KALKULATOR REAL-TIME KOMPONEN TARIF
    // -------------------------------------------------------------
    function bindCalculators() {
        $('.calc-tarif').on('input change', function() {
            calculateTotalTarif();
        });
    }

    function calculateTotalTarif() {
        let total = 0;
        $('.calc-tarif').each(function() {
            let val = parseFloat($(this).val()) || 0;
            total += val;
        });

        $('#total_byr').val(total);
        $('#displayTotalByr').text('Rp ' + formatRupiah(total));
    }

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function onKategoriChanged() {
        if ($('#form_mode').val() === 'create') {
            generateKodeLab();
        }
    }

    function generateKodeLab() {
        $('#kd_jenis_prw').attr('placeholder', 'Memuat...');
        $.get("{{ route('master.tarif-lab.next-kode') }}")
            .done(function(res) {
                if (res.next_kode && $('#form_mode').val() === 'create') {
                    $('#kd_jenis_prw').val(res.next_kode);
                }
            });
    }

    // -------------------------------------------------------------
    // FORM MASTER LAB (CREATE / EDIT / RESET / DELETE)
    // -------------------------------------------------------------
    function resetFormTarifLab() {
        $('#form_mode').val('create');
        $('#formTitle').html('<i class="ti ti-forms me-1"></i> Form Input Tarif Lab');
        $('#badgeFormMode').removeClass('bg-orange-lt').addClass('bg-blue-lt').text('Mode: Tambah Baru');
        $('#btnSubmitTarif').html('<i class="ti ti-device-floppy me-1"></i> Simpan Tarif');
        $('#wrapBtnKelolaTemplate').hide();
        $('#countCurrentTemplate').text('0');

        $('#kd_jenis_prw').prop('readonly', false);
        $('#btnAutoKode').prop('disabled', false);

        $('#formTarifLab')[0].reset();
        $('#status_1').prop('checked', true);
        $('.calc-tarif').val(0);
        calculateTotalTarif();
        generateKodeLab();
        currentActiveLab = null;
    }

    function editTarifLab(kd) {
        loadingAjax();
        $.get("{{ url('/master/tarif-lab/detail') }}/" + kd)
            .done(function(res) {
                loadingAjax().close();
                if (res.success && res.data) {
                    let d = res.data;
                    currentActiveLab = d;

                    $('#form_mode').val('edit');
                    $('#formTitle').html('<i class="ti ti-edit me-1"></i> Edit Data Tarif Lab');
                    $('#badgeFormMode').removeClass('bg-blue-lt').addClass('bg-orange-lt').text('Mode: Edit Data (' + d.kd_jenis_prw + ')');
                    $('#btnSubmitTarif').html('<i class="ti ti-check me-1"></i> Perbarui Tarif');

                    $('#kd_jenis_prw').val(d.kd_jenis_prw).prop('readonly', true);
                    $('#btnAutoKode').prop('disabled', true);
                    $('#nm_perawatan').val(d.nm_perawatan);
                    $('#kategori').val(d.kategori);
                    $('#kd_pj').val(d.kd_pj);
                    $('#kelas').val(d.kelas);

                    if (d.status == '1') {
                        $('#status_1').prop('checked', true);
                    } else {
                        $('#status_0').prop('checked', true);
                    }

                    $('#bagian_rs').val(d.bagian_rs || 0);
                    $('#bhp').val(d.bhp || 0);
                    $('#tarif_tindakan_dokter').val(d.tarif_tindakan_dokter || 0);
                    $('#tarif_tindakan_petugas').val(d.tarif_tindakan_petugas || 0);
                    $('#tarif_perujuk').val(d.tarif_perujuk || 0);
                    $('#kso').val(d.kso || 0);
                    $('#menejemen').val(d.menejemen || 0);

                    calculateTotalTarif();

                    let tplCount = d.template ? d.template.length : 0;
                    $('#countCurrentTemplate').text(tplCount);
                    $('#wrapBtnKelolaTemplate').show();

                    $('html, body').animate({
                        scrollTop: $("#formTarifLab").offset().top - 80
                    }, 300);
                }
            })
            .fail(function(err) {
                loadingAjax().close();
                Swal.fire('Error', 'Gagal memuat detail tarif', 'error');
            });
    }

    function submitFormTarifLab() {
        let mode = $('#form_mode').val();
        let kd = $('#kd_jenis_prw').val().trim();
        let url = mode === 'create' ? "{{ route('master.tarif-lab.store') }}" : "{{ url('/master/tarif-lab') }}/" + kd;
        let method = mode === 'create' ? 'POST' : 'PUT';

        let formData = $('#formTarifLab').serialize();
        if (mode === 'edit') {
            formData += '&_method=PUT';
        }

        loadingAjax();
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(res) {
                loadingAjax().close();
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#tbTarifLab').DataTable().ajax.reload(null, false);
                    if (mode === 'create') {
                        // Buka penawaran langsung mengelola template
                        Swal.fire({
                            icon: 'success',
                            title: 'Tarif Berhasil Disimpan',
                            text: 'Apakah Anda ingin langsung menambahkan sub-pemeriksaan & nilai rujukan untuk paket ini?',
                            showCancelButton: true,
                            confirmButtonText: '<i class="ti ti-plus me-1"></i> Ya, Tambah Parameter',
                            cancelButtonText: 'Nanti Saja',
                            confirmButtonColor: '#0d6efd',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                openTemplateModal(kd);
                            }
                            resetFormTarifLab();
                        });
                    } else {
                        editTarifLab(kd);
                    }
                } else {
                    Swal.fire('Perhatian', res.message, 'warning');
                }
            },
            error: function(xhr) {
                loadingAjax().close();
                let msg = 'Gagal menyimpan data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            }
        });
    }

    function toggleStatusLab(kd) {
        loadingAjax();
        $.post("{{ url('/master/tarif-lab/toggle-status') }}/" + kd, { _token: "{{ csrf_token() }}" })
            .done(function(res) {
                loadingAjax().close();
                if (res.success) {
                    $('#tbTarifLab').DataTable().ajax.reload(null, false);
                }
            })
            .fail(function() {
                loadingAjax().close();
                Swal.fire('Error', 'Gagal mengubah status', 'error');
            });
    }

    function deleteTarifLab(kd, nama) {
        Swal.fire({
            title: 'Hapus Pemeriksaan Lab?',
            html: `Apakah Anda yakin ingin menghapus <strong>${nama}</strong> (${kd}) beserta seluruh template sub-pemeriksaannya?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax();
                $.ajax({
                    url: "{{ url('/master/tarif-lab') }}/" + kd,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        loadingAjax().close();
                        if (res.success) {
                            Swal.fire('Terhapus', res.message, 'success');
                            $('#tbTarifLab').DataTable().ajax.reload(null, false);
                            if ($('#kd_jenis_prw').val() === kd) {
                                resetFormTarifLab();
                            }
                        } else {
                            Swal.fire('Gagal', res.message, 'warning');
                        }
                    },
                    error: function(xhr) {
                        loadingAjax().close();
                        let msg = 'Gagal menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Tidak Dapat Dihapus', msg, 'warning');
                    }
                });
            }
        });
    }

    // -------------------------------------------------------------
    // MODAL SUB-PEMERIKSAAN & NILAI RUJUKAN (TEMPLATE LABORATORIUM)
    // -------------------------------------------------------------
    function openTemplateModalCurrent() {
        let kd = $('#kd_jenis_prw').val();
        if (kd) {
            openTemplateModal(kd);
        }
    }

    function openTemplateModal(kd) {
        loadingAjax();
        $.get("{{ url('/master/tarif-lab/detail') }}/" + kd)
            .done(function(res) {
                loadingAjax().close();
                if (res.success && res.data) {
                    let d = res.data;
                    $('#tplLabNama').text(d.nm_perawatan);
                    $('#tplLabKode').text(d.kd_jenis_prw);
                    $('#tplLabKategori').text(d.kategori);
                    $('#tpl_kd_jenis_prw').val(d.kd_jenis_prw);

                    resetFormTemplateItem();
                    loadTemplateList(d.kd_jenis_prw);

                    $('#modalTemplateLab').modal('show');
                }
            })
            .fail(function() {
                loadingAjax().close();
                Swal.fire('Error', 'Gagal memuat data laboratorium', 'error');
            });
    }

    function loadTemplateList(kd) {
        $('#tbodyTemplateList').html('<tr><td colspan="8" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat parameter...</td></tr>');
        
        $.get("{{ url('/master/tarif-lab') }}/" + kd + "/template")
            .done(function(res) {
                if (res.success) {
                    renderTemplateTable(res.data);
                }
            });
    }

    function renderTemplateTable(items) {
        let count = items ? items.length : 0;
        $('#tplCountBadge').text(count);
        $('#countCurrentTemplate').text(count);

        if (count === 0) {
            $('#tbodyTemplateList').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="ti ti-info-circle fs-3 text-secondary d-block mb-1"></i>
                        Belum ada parameter sub-pemeriksaan yang terdaftar untuk paket ini.<br>
                        <small>Gunakan form di atas atau tombol <strong>"Salin dari Paket Lain"</strong> untuk menambahkan parameter.</small>
                    </td>
                </tr>
            `);
            $('#tpl_urut').val(1);
            return;
        }

        let maxUrut = 0;
        let html = '';
        items.forEach(function(item, index) {
            let urutVal = item.urut || (index + 1);
            if (urutVal > maxUrut) maxUrut = urutVal;

            html += `
                <tr>
                    <td class="text-center fw-bold text-muted">${urutVal}</td>
                    <td>
                        <strong class="text-dark">${item.Pemeriksaan}</strong>
                    </td>
                    <td class="text-center"><span class="badge bg-light text-dark">${item.satuan || '-'}</span></td>
                    <td class="small">${item.nilai_rujukan_ld || '-'}</td>
                    <td class="small">${item.nilai_rujukan_la || '-'}</td>
                    <td class="small">${item.nilai_rujukan_pd || '-'}</td>
                    <td class="small">${item.nilai_rujukan_pa || '-'}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-xs btn-outline-primary" onclick='editTemplateItem(${JSON.stringify(item)})' title="Edit Parameter">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger" onclick="deleteTemplateItem(${item.id_template}, '${item.Pemeriksaan}')" title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#tbodyTemplateList').html(html);
        if (!$('#tpl_id_template').val()) {
            $('#tpl_urut').val(maxUrut + 1);
        }
    }

    function resetFormTemplateItem() {
        $('#tpl_id_template').val('');
        $('#tplFormTitle').html('<i class="ti ti-plus me-1"></i> Tambah Parameter / Sub-Item Baru');
        $('#badgeTplMode').removeClass('bg-orange-lt').addClass('bg-green-lt').text('Mode Tambah');
        $('#btnSubmitTpl').html('<i class="ti ti-plus me-1"></i> Simpan Parameter');
        $('#btnCancelTplEdit').hide();

        $('#tpl_Pemeriksaan').val('');
        $('#tpl_satuan').val('');
        $('#tpl_nilai_rujukan_ld').val('');
        $('#tpl_nilai_rujukan_la').val('');
        $('#tpl_nilai_rujukan_pd').val('');
        $('#tpl_nilai_rujukan_pa').val('');
    }

    function editTemplateItem(item) {
        $('#tpl_id_template').val(item.id_template);
        $('#tplFormTitle').html('<i class="ti ti-pencil me-1"></i> Edit Parameter: <strong>' + item.Pemeriksaan + '</strong>');
        $('#badgeTplMode').removeClass('bg-green-lt').addClass('bg-orange-lt').text('Mode Edit');
        $('#btnSubmitTpl').html('<i class="ti ti-check me-1"></i> Perbarui Parameter');
        $('#btnCancelTplEdit').show();

        $('#tpl_Pemeriksaan').val(item.Pemeriksaan);
        $('#tpl_satuan').val(item.satuan);
        $('#tpl_urut').val(item.urut);
        $('#tpl_nilai_rujukan_ld').val(item.nilai_rujukan_ld);
        $('#tpl_nilai_rujukan_la').val(item.nilai_rujukan_la);
        $('#tpl_nilai_rujukan_pd').val(item.nilai_rujukan_pd);
        $('#tpl_nilai_rujukan_pa').val(item.nilai_rujukan_pa);

        $('#tpl_Pemeriksaan').focus();
    }

    function copyLdToAll() {
        let val = $('#tpl_nilai_rujukan_ld').val();
        if (!val) {
            Swal.fire('Info', 'Isi nilai rujukan Laki-laki Dewasa (LD) terlebih dahulu', 'info');
            return;
        }
        $('#tpl_nilai_rujukan_la').val(val);
        $('#tpl_nilai_rujukan_pd').val(val);
        $('#tpl_nilai_rujukan_pa').val(val);
    }

    function submitFormTemplateItem() {
        let kd = $('#tpl_kd_jenis_prw').val();
        let formData = $('#formTemplateItem').serialize() + '&_token={{ csrf_token() }}';

        loadingAjax();
        $.post("{{ route('master.tarif-lab.template.store') }}", formData)
            .done(function(res) {
                loadingAjax().close();
                if (res.success) {
                    resetFormTemplateItem();
                    loadTemplateList(kd);
                    $('#tbTarifLab').DataTable().ajax.reload(null, false);
                } else {
                    Swal.fire('Perhatian', res.message, 'warning');
                }
            })
            .fail(function(xhr) {
                loadingAjax().close();
                let msg = 'Gagal menyimpan item parameter';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            });
    }

    function deleteTemplateItem(idTemplate, nama) {
        let kd = $('#tpl_kd_jenis_prw').val();
        Swal.fire({
            title: 'Hapus Item Parameter?',
            text: `Apakah Anda yakin ingin menghapus parameter "${nama}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax();
                $.ajax({
                    url: "{{ url('/master/tarif-lab/template') }}/" + idTemplate,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        loadingAjax().close();
                        if (res.success) {
                            loadTemplateList(kd);
                            $('#tbTarifLab').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal', res.message, 'warning');
                        }
                    },
                    error: function(xhr) {
                        loadingAjax().close();
                        let msg = 'Gagal menghapus parameter';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Perhatian', msg, 'warning');
                    }
                });
            }
        });
    }

    // -------------------------------------------------------------
    // SALIN TEMPLATE DARI PAKET LAIN
    // -------------------------------------------------------------
    function openModalCopyTemplate() {
        let kdTarget = $('#tpl_kd_jenis_prw').val();
        let nmTarget = $('#tplLabNama').text();
        $('#copyTargetName').text(nmTarget + ' (' + kdTarget + ')');

        // Load dropdown paket lain yang memiliki template
        $('#copySourceKd').html('<option value="">Memuat daftar paket...</option>');
        $.get("{{ route('master.tarif-lab.data') }}?length=500")
            .done(function(res) {
                let options = '<option value="">-- Pilih Paket Lab Sumber --</option>';
                if (res.data) {
                    res.data.forEach(function(item) {
                        if (item.kd_jenis_prw !== kdTarget && item.template_count > 0) {
                            options += `<option value="${item.kd_jenis_prw}">${item.nm_perawatan} (${item.kd_jenis_prw}) - ${item.template_count} item</option>`;
                        }
                    });
                }
                $('#copySourceKd').html(options);
                $('#modalCopyTemplate').modal('show');
            });
    }

    function executeCopyTemplate() {
        let fromKd = $('#copySourceKd').val();
        let toKd = $('#tpl_kd_jenis_prw').val();

        if (!fromKd) {
            Swal.fire('Perhatian', 'Pilih paket sumber terlebih dahulu', 'warning');
            return;
        }

        loadingAjax();
        $.post("{{ route('master.tarif-lab.template.copy') }}", {
            _token: "{{ csrf_token() }}",
            from_kd_jenis_prw: fromKd,
            to_kd_jenis_prw: toKd
        })
        .done(function(res) {
            loadingAjax().close();
            if (res.success) {
                $('#modalCopyTemplate').modal('hide');
                Swal.fire('Berhasil', res.message, 'success');
                loadTemplateList(toKd);
                $('#tbTarifLab').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire('Gagal', res.message, 'warning');
            }
        })
        .fail(function(xhr) {
            loadingAjax().close();
            let msg = 'Gagal menyalin parameter';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire('Error', msg, 'error');
        });
    }
</script>
@endpush

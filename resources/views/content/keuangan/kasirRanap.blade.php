@extends('main')

@section('contents')
<style>
    /* Simetris & Sejajar Ikon dan Teks pada Badge & Tombol */
    .badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: middle !important;
        line-height: 1 !important;
    }
    .badge i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 0 !important;
        font-size: 1.15em !important;
        margin-right: 0.25rem !important;
    }
    .badge span {
        display: inline-block !important;
        line-height: 1 !important;
    }
    .btn.d-inline-flex {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: middle !important;
        line-height: 1 !important;
    }
    .btn.d-inline-flex i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 0 !important;
        font-size: 1.15em !important;
        margin-right: 0.25rem !important;
    }
    .btn.d-inline-flex span {
        display: inline-block !important;
        line-height: 1 !important;
    }
    .antrean-card {
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        border-left: 4px solid transparent;
    }
    .antrean-card:hover {
        background-color: #f8fafc;
        border-left-color: #206bc4;
    }
    .antrean-card.active {
        background-color: #f0f6ff !important;
        border-left-color: #206bc4 !important;
    }
</style>

<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary d-flex align-items-center">
                    <i class="ti ti-bed me-2 fs-1"></i> Kasir & Billing Rawat Inap
                </h2>
                <div class="text-muted mt-1 small">
                    Loket Transaksi Pasien Inap, Rincian Kamar, Deposit/Uang Muka, Penutupan Billing & Penjurnalan Kasir
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex" onclick="loadAntreanRanap()">
                    <i class="ti ti-refresh"></i><span>Refresh Antrean</span>
                </button>
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-danger d-inline-flex" title="Tutup / Kembali ke Beranda">
                    <i class="ti ti-x"></i><span>Tutup</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-3">
            {{-- ==================== KOLOM KIRI: ANTREAN PASIEN RANAP ==================== --}}
            <div class="col-lg-5 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-2 d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span class="fw-bold text-dark d-flex align-items-center">
                                <i class="ti ti-users me-1 text-primary"></i> Antrean Pasien Ranap
                            </span>
                            <span class="badge bg-primary-lt" id="totalAntreanBadge">0 Pasien</span>
                        </div>

                        {{-- Filter Status Bayar --}}
                        <div>
                            <label class="form-label small text-muted mb-1">Status Pembayaran:</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="filter_status_bayar" id="statusBelumBayar" value="Belum Bayar" checked onchange="loadAntreanRanap()">
                                <label class="btn btn-outline-warning btn-sm py-1 fw-bold" for="statusBelumBayar">Belum Bayar</label>

                                <input type="radio" class="btn-check" name="filter_status_bayar" id="statusSudahBayar" value="Sudah Bayar" onchange="loadAntreanRanap()">
                                <label class="btn btn-outline-success btn-sm py-1 fw-bold" for="statusSudahBayar">Sudah Bayar</label>

                                <input type="radio" class="btn-check" name="filter_status_bayar" id="statusSemuaBayar" value="Semua" onchange="loadAntreanRanap()">
                                <label class="btn btn-outline-secondary btn-sm py-1" for="statusSemuaBayar">Semua</label>
                            </div>
                        </div>

                        {{-- Filter Status Pulang --}}
                        <div>
                            <label class="form-label small text-muted mb-1">Status Keberadaan:</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="filter_status_pulang" id="statusBelumPulang" value="Belum Pulang" checked onchange="loadAntreanRanap()">
                                <label class="btn btn-outline-info btn-sm py-1 fw-bold" for="statusBelumPulang">Masih Dirawat</label>

                                <input type="radio" class="btn-check" name="filter_status_pulang" id="statusSudahPulang" value="Sudah Pulang" onchange="loadAntreanRanap()">
                                <label class="btn btn-outline-teal btn-sm py-1 fw-bold" for="statusSudahPulang">Sudah Pulang</label>

                                <input type="radio" class="btn-check" name="filter_status_pulang" id="statusSemuaPulang" value="Semua" onchange="loadAntreanRanap()">
                                <label class="btn btn-outline-secondary btn-sm py-1" for="statusSemuaPulang">Semua</label>
                            </div>
                        </div>

                        {{-- Filter Bangsal, Kamar, Tanggal & Search --}}
                        <div class="row g-1">
                            <div class="col-6">
                                <select class="form-select form-select-sm" id="filter_bangsal" onchange="loadAntreanRanap()">
                                    <option value="">-- Semua Bangsal --</option>
                                    @foreach($bangsal as $b)
                                        <option value="{{ $b->kd_bangsal }}">{{ $b->nm_bangsal }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select form-select-sm" id="filter_penjab" onchange="loadAntreanRanap()">
                                    <option value="">-- Semua Penjamin --</option>
                                    @foreach($penjab as $pj)
                                        <option value="{{ $pj->kd_pj }}">{{ $pj->png_jawab }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" id="filter_tgl_awal" value="{{ date('Y-m-01') }}" onchange="loadAntreanRanap()" title="Tanggal Awal">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" id="filter_tgl_akhir" value="{{ date('Y-m-d') }}" onchange="loadAntreanRanap()" title="Tanggal Akhir">
                            </div>
                            <div class="col-12 mt-1">
                                <div class="input-icon">
                                    <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                    <input type="text" class="form-control form-control-sm" id="search_pasien_ranap" placeholder="Cari Nama / No.RM / No.Rawat / Kamar..." onkeyup="filterAntreanLocal()">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- List Antrean --}}
                    <div class="card-body p-0" style="max-height: calc(100vh - 350px); overflow-y: auto;" id="listAntreanPasienRanap">
                        <div class="text-center p-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat antrean rawat inap...
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== KOLOM KANAN: WORKSPACE BILLING RANAP ==================== --}}
            <div class="col-lg-7 col-xl-8">
                {{-- Placeholder Saat Belum Ada Pasien Dipilih --}}
                <div id="placeholderNoPatient" class="card shadow-sm border-0 h-100 text-center py-5">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                        <div class="avatar avatar-xl rounded-circle bg-primary-lt mb-3">
                            <i class="ti ti-bed fs-1 text-primary"></i>
                        </div>
                        <h3 class="text-dark fw-bold mb-1">Pilih Pasien Rawat Inap</h3>
                        <p class="text-muted small max-w-sm mb-3">
                            Silakan klik salah satu data pasien dari daftar antrean rawat inap di sebelah kiri untuk melihat rincian kamar, tindakan, resep obat, laboratorium, radiologi, dan transaksi kasir.
                        </p>
                    </div>
                </div>

                {{-- Konten Billing Pasien Terpilih --}}
                <div id="patientBillingWorkspace" style="display: none;">
                    {{-- 1. HEADER INFORMASI PASIEN & KAMAR --}}
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom pb-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-md rounded bg-primary text-white fw-bold" id="badgeAvatarRM">
                                        RM
                                    </div>
                                    <div>
                                        <h3 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                            <span id="headerNmPasien">-</span>
                                            <span class="badge bg-secondary-lt fw-normal" id="headerNoRM">RM: -</span>
                                        </h3>
                                        <div class="text-muted small">
                                            <span id="headerNoRawat">-</span> &bull; 
                                            <span id="headerPenjab" class="badge bg-blue-lt">UMUM</span> &bull;
                                            <span id="headerDokter" class="text-dark"><i class="ti ti-user-check me-1"></i> -</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column align-items-end gap-1">
                                    <div class="d-flex gap-1" id="headerStatusBadges">
                                        <span class="badge bg-warning-lt" id="badgeStatusBayar">Belum Bayar</span>
                                        <span class="badge bg-info-lt" id="badgeStatusPulang">Masih Dirawat</span>
                                    </div>
                                    <div class="small text-muted" id="headerNotaInfo"></div>
                                </div>
                            </div>

                            {{-- Info Kamar & Stay --}}
                            <div class="row g-2 text-dark small bg-light p-2 rounded">
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted d-block">Ruangan / Kamar:</span>
                                    <strong class="text-primary d-flex align-items-center">
                                        <i class="ti ti-door me-1"></i> <span id="headerKamar">-</span>
                                    </strong>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted d-block">Tanggal Masuk:</span>
                                    <strong id="headerTglMasuk">-</strong>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted d-block">Tanggal Pulang:</span>
                                    <strong id="headerTglKeluar">-</strong>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <span class="text-muted d-block">Lama Perawatan:</span>
                                    <strong class="text-success fs-4" id="headerLamaHari">0 Hari</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. ALERT BANNER STATUS PASIEN --}}
                    <div id="bannerMasihDirawat" class="alert alert-info border-start border-4 border-info mb-3 d-none">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle fs-2 me-2 flex-shrink-0"></i>
                            <div class="small">
                                <strong>Status Pasien Masih Dirawat:</strong> Billing ini saat ini bersifat <em>estimasi berjalan sementara</em>. Penghitungan hari kamar akan terus bertambah hingga pasien dipulangkan/checkout.
                            </div>
                        </div>
                    </div>

                    <div id="bannerResepUnvalidated" class="alert alert-warning border-start border-4 border-warning mb-3 d-none">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-alert-triangle fs-2 me-2 text-warning flex-shrink-0"></i>
                                <div class="small">
                                    <strong>Peringatan Farmasi:</strong> Terdapat resep obat rawat inap yang belum divalidasi oleh Apotek. Biaya obat belum masuk ke billing hingga divalidasi.
                                </div>
                            </div>
                            <a href="{{ url('/resep-obat') }}" target="_blank" class="btn btn-sm btn-warning d-inline-flex">
                                <i class="ti ti-external-link"></i><span>Buka Farmasi</span>
                            </a>
                        </div>
                    </div>

                    <div id="bannerSudahBayar" class="alert alert-success border-start border-4 border-success mb-3 d-none">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-circle-check fs-2 me-2 text-success flex-shrink-0"></i>
                                <div class="small">
                                    <strong>Billing Telah Ditutup & Dibayar:</strong> No. Nota: <span id="bannerNoNota" class="fw-bold">-</span> | Tanggal: <span id="bannerTglNota">-</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex" onclick="batalBillingRanap()">
                                    <i class="ti ti-arrow-back-up"></i><span>Batal / Reversal Billing</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 3. TAB RINCIAN BILLING & FORM PEMBAYARAN KASIR --}}
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark d-flex align-items-center">
                                <i class="ti ti-receipt me-1 text-primary"></i> Rincian Biaya Rawat Inap
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="printShowObatDetailRanapKasir" checked>
                                    <label class="form-check-label small fw-bold text-dark mb-0" for="printShowObatDetailRanapKasir">Detail Obat</label>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle d-inline-flex" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ti ti-printer"></i><span>Cetak Billing / Nota</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakBillingKasirRanap('80')"><i class="ti ti-receipt me-1"></i> Thermal 80mm</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakBillingKasirRanap('58')"><i class="ti ti-receipt me-1"></i> Thermal 58mm</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" id="tabelRincianBillingRanap">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="py-2 px-3">Kategori & Rincian Layanan / Item</th>
                                            <th class="py-2 px-3 text-center" style="width: 70px;">Qty</th>
                                            <th class="py-2 px-3 text-end" style="width: 140px;">Tarif (Rp)</th>
                                            <th class="py-2 px-3 text-end" style="width: 160px;">Subtotal (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyBillingRanap">
                                        <tr><td colspan="4" class="text-center p-3 text-muted">Memuat data rincian billing...</td></tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light fw-bold">
                                            <th colspan="3" class="py-2 px-3 text-uppercase text-dark">SUBTOTAL BIAYA RAWAT INAP</th>
                                            <th class="py-2 px-3 text-end text-dark fs-4" id="tableSubtotalBiaya">Rp 0</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 4. DESK PEMBAYARAN KASIR & PENUTUPAN BILLING (MULTI-PAYMENT & DEPOSIT) --}}
                    <div class="card shadow-sm border-0 border-top border-4 border-success">
                        <div class="card-header bg-light py-2">
                            <h4 class="card-title fw-bold text-success mb-0 d-flex align-items-center">
                                <i class="ti ti-cash me-1 fs-2"></i> Kasir & Pelunasan Billing Rawat Inap
                            </h4>
                        </div>
                        <div class="card-body p-3">
                            <form id="formCloseBillingRanap">
                                <div class="row g-3">
                                    {{-- Kolom Kiri Form: Potongan, Tambahan & Deposit --}}
                                    <div class="col-md-6 border-end">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Total Tagihan Kumulatif</label>
                                            <input type="text" class="form-control form-control-lg bg-light fw-bold text-dark fs-3" id="co_grand_total" readonly value="Rp 0">
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold text-success">Potongan / Diskon (Rp)</label>
                                                <input type="number" class="form-control form-control-sm" id="co_potongan" value="0" min="0" oninput="hitungTotalKasirRanap()">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold text-primary">Tambahan Biaya (Rp)</label>
                                                <input type="number" class="form-control form-control-sm" id="co_tambahan" value="0" min="0" oninput="hitungTotalKasirRanap()">
                                            </div>
                                        </div>

                                        {{-- Box Titipan Uang Muka / Deposit --}}
                                        <div class="p-2 rounded bg-azure-lt border border-azure mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="small fw-bold text-azure d-flex align-items-center">
                                                    <i class="ti ti-coin me-1"></i> Titipan Uang Muka / Deposit:
                                                </span>
                                                <span class="fw-bold fs-4 text-azure" id="co_deposit_text">Rp 0</span>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                * Otomatis memotong total tagihan berjalan pasien.
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label small fw-bold text-danger">Sisa Tagihan Bersih (Net Total):</label>
                                            <div class="p-2 rounded bg-light border">
                                                <span class="fs-2 fw-bold text-danger" id="co_net_total">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan Form: Multi-Payment & Piutang --}}
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label small fw-bold mb-0">Metode Pembayaran (Multi-Payment):</label>
                                            <button type="button" class="btn btn-xs btn-outline-primary d-inline-flex" onclick="tambahBarisBayarRanap()">
                                                <i class="ti ti-plus"></i><span>Tambah Akun</span>
                                            </button>
                                        </div>

                                        <div id="paymentRowsContainerRanap" class="d-flex flex-column gap-2 mb-3">
                                            {{-- Baris Pembayaran Ditambahkan via JS --}}
                                        </div>

                                        <div class="row g-2 mb-2 bg-light p-2 rounded border">
                                            <div class="col-6">
                                                <span class="small text-muted d-block">Total Pembayaran:</span>
                                                <strong class="fs-4 text-success" id="co_total_bayar_text">Rp 0</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="small text-muted d-block">Kembalian:</span>
                                                <strong class="fs-4 text-primary" id="co_kembalian_text">Rp 0</strong>
                                            </div>
                                        </div>

                                        {{-- Panel Sisa Piutang jika belum lunas --}}
                                        <div id="sectionPiutangRanap" class="p-2 rounded bg-red-lt border border-danger mb-3" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="small fw-bold text-danger"><i class="ti ti-alert-circle me-1"></i> Terdapat Sisa Tagihan (Piutang):</span>
                                                <strong class="fs-4 text-danger" id="co_sisa_piutang_text">Rp 0</strong>
                                            </div>
                                            <div>
                                                <label class="form-label small fw-bold mb-1 text-danger">Pilih Rekening Akun Piutang:</label>
                                                <select class="form-select form-select-sm" id="co_kd_rek_piutang">
                                                    <option value="">-- Pilih Akun Piutang Pasien / Asuransi --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold mb-1">Tanggal Transaksi:</label>
                                                <input type="date" class="form-control form-control-sm" id="co_tgl_bayar" value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-success fw-bold w-100 py-2 d-inline-flex" id="btnSubmitCloseBillingRanap" onclick="submitCloseBillingRanap()">
                                                    <i class="ti ti-device-floppy"></i><span>Simpan & Tutup Billing</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    let antreanRanapData = [];
    let selectedRanapNoRawat = null;
    let billingRanapCurrentData = null;
    let accountsDataCache = null;
    let currentRawGrandTotal = 0;
    let currentDeposit = 0;

    $(document).ready(function() {
        loadAntreanRanap();
        preloadBillingAccounts();
    });

    function preloadBillingAccounts() {
        $.get("{{ url('/billing/accounts') }}", { no_rawat: 'dummy' })
            .done((response) => {
                accountsDataCache = response;
            });
    }

    function loadAntreanRanap() {
        const statusBayar = $('input[name="filter_status_bayar"]:checked').val();
        const statusPulang = $('input[name="filter_status_pulang"]:checked').val();
        const bangsal = $('#filter_bangsal').val();
        const penjab = $('#filter_penjab').val();
        const tglAwal = $('#filter_tgl_awal').val();
        const tglAkhir = $('#filter_tgl_akhir').val();
        const container = $('#listAntreanPasienRanap');

        container.html('<div class="text-center p-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat antrean rawat inap...</div>');

        $.get("{{ url('/kasir/ranap/antrean') }}", {
            status_bayar: statusBayar,
            status_pulang: statusPulang,
            kd_bangsal: bangsal,
            kd_pj: penjab,
            tgl_awal: tglAwal,
            tgl_akhir: tglAkhir
        }).done((response) => {
            antreanRanapData = response.data || [];
            $('#totalAntreanBadge').text(antreanRanapData.length + ' Pasien');
            renderAntreanRanap(antreanRanapData);
        }).fail(() => {
            container.html('<div class="text-center text-danger p-4"><i class="ti ti-x"></i> Gagal memuat antrean</div>');
        });
    }

    function filterAntreanLocal() {
        const keyword = $('#search_pasien_ranap').val().toLowerCase().trim();
        if (!keyword) {
            renderAntreanRanap(antreanRanapData);
            return;
        }

        const filtered = antreanRanapData.filter(item => {
            return (item.nm_pasien && item.nm_pasien.toLowerCase().includes(keyword))
                || (item.no_rkm_medis && item.no_rkm_medis.toLowerCase().includes(keyword))
                || (item.no_rawat && item.no_rawat.toLowerCase().includes(keyword))
                || (item.kamar_full && item.kamar_full.toLowerCase().includes(keyword))
                || (item.stts_pulang && item.stts_pulang.toLowerCase().includes(keyword));
        });

        renderAntreanRanap(filtered);
    }

    function renderAntreanRanap(list) {
        const container = $('#listAntreanPasienRanap');
        if (!list || list.length === 0) {
            container.html('<div class="text-center p-4 text-muted small">Tidak ada antrean pasien rawat inap sesuai filter.</div>');
            return;
        }

        let html = '';
        list.forEach(item => {
            const isActive = (item.no_rawat === selectedRanapNoRawat) ? 'active' : '';

            // Status Pulang Badge
            let sttsPulangBadge = '';
            if (item.is_pulang) {
                sttsPulangBadge = `<span class="badge bg-success-lt"><i class="ti ti-check"></i> ${item.stts_pulang}</span>`;
            } else {
                sttsPulangBadge = `<span class="badge bg-info-lt"><i class="ti ti-bed"></i> Masih Dirawat</span>`;
            }

            // Status Bayar Badge
            let sttsBayarBadge = '';
            if (item.status_bayar === 'Sudah Bayar') {
                sttsBayarBadge = `<span class="badge bg-success-lt"><i class="ti ti-check"></i> Lunas</span>`;
            } else {
                sttsBayarBadge = `<span class="badge bg-warning-lt">Belum Bayar</span>`;
            }

            // Deposit Badge
            let depositBadge = '';
            if (item.deposit > 0) {
                depositBadge = `<span class="badge bg-azure-lt"><i class="ti ti-coin"></i> Dep: Rp ${new Intl.NumberFormat('id-ID').format(item.deposit)}</span>`;
            }

            // Unvalidated Resep Badge
            let resepBadge = '';
            if (item.has_unvalidated_resep) {
                resepBadge = `<span class="badge bg-orange-lt text-orange" title="${item.unvalidated_count} Resep Belum Divalidasi Farmasi"><i class="ti ti-pill"></i> Resep (${item.unvalidated_count})</span>`;
            }

            html += `
                <div class="card antrean-card p-2 border-bottom ${isActive}" onclick="pilihPasienRanap('${item.no_rawat}')">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <strong class="text-dark d-block">${item.nm_pasien}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                RM: <strong>${item.no_rkm_medis}</strong> &bull; ${item.no_rawat}
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            ${sttsBayarBadge}
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-1 mb-1" style="font-size: 0.75rem;">
                        <span class="badge bg-primary-lt"><i class="ti ti-door"></i> ${item.kamar_full}</span>
                        ${sttsPulangBadge}
                        ${depositBadge}
                        ${resepBadge}
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.72rem;">
                        <span><i class="ti ti-calendar me-1"></i> Masuk: ${item.tgl_masuk} (${item.durasi_hari} Hari)</span>
                        <span class="badge bg-secondary-lt">${item.png_jawab}</span>
                    </div>
                </div>
            `;
        });

        container.html(html);
    }

    function pilihPasienRanap(no_rawat) {
        selectedRanapNoRawat = no_rawat;
        $('.antrean-card').removeClass('active');
        $(event.currentTarget).addClass('active');

        $('#placeholderNoPatient').hide();
        $('#patientBillingWorkspace').show();

        loadDetailBillingRanap(no_rawat);
    }

    function loadDetailBillingRanap(no_rawat) {
        $('#tbodyBillingRanap').html('<tr><td colspan="4" class="text-center p-3 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat rincian billing...</td></tr>');
        $('#tableSubtotalBiaya').text('Rp 0');
        $('#bannerSudahBayar').addClass('d-none');
        $('#bannerMasihDirawat').addClass('d-none');
        $('#bannerResepUnvalidated').addClass('d-none');

        $.get("{{ url('/billing/ranap') }}", { no_rawat: no_rawat })
            .done((data) => {
                billingRanapCurrentData = data;
                populateWorkspaceHeader(data);
                renderBillingTable(data);
                setupPaymentDesk(data);
            })
            .fail(() => {
                $('#tbodyBillingRanap').html('<tr><td colspan="4" class="text-center p-3 text-danger"><i class="ti ti-x"></i> Gagal memuat rincian billing pasien</td></tr>');
            });
    }

    function populateWorkspaceHeader(data) {
        $('#headerNmPasien').text(data.pasien || '-');
        $('#headerNoRM').text('RM: ' + (data.no_rm || '-'));
        $('#headerNoRawat').text('No. Rawat: ' + (data.no_rawat || '-'));
        $('#headerPenjab').text(data.penjab || 'UMUM');
        $('#headerDokter').html(`<i class="ti ti-user-check me-1"></i> DPJP: ${data.dokter || '-'}`);

        $('#headerKamar').text(data.kamar || '-');
        $('#headerTglMasuk').text(data.tgl_perawatan ? data.tgl_perawatan.split(' s.d ')[0] : '-');
        $('#headerTglKeluar').text(data.is_pulang ? data.tgl_perawatan.split(' s.d ')[1].split(' (')[0] : 'Masih Dirawat');
        $('#headerLamaHari').text((data.total_hari || 1) + ' Hari');

        // Status Badges
        if (data.status_bayar === 'Sudah Bayar') {
            $('#badgeStatusBayar').removeClass('bg-warning-lt').addClass('bg-success-lt').html('<i class="ti ti-check"></i> Sudah Bayar');
            $('#bannerSudahBayar').removeClass('d-none');
            $('#bannerNoNota').text(data.no_nota || '-');
            $('#bannerTglNota').text(data.tgl_nota || '-');
            $('#btnSubmitCloseBillingRanap').prop('disabled', true).html('<i class="ti ti-lock me-1"></i> Billing Sudah Lunas');
        } else {
            $('#badgeStatusBayar').removeClass('bg-success-lt').addClass('bg-warning-lt').text('Belum Bayar');
            $('#bannerSudahBayar').addClass('d-none');
            $('#btnSubmitCloseBillingRanap').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan & Tutup Billing Ranap');
        }

        if (data.is_pulang) {
            $('#badgeStatusPulang').removeClass('bg-info-lt').addClass('bg-teal-lt').html(`<i class="ti ti-check"></i> ${data.stts_pulang}`);
            $('#bannerMasihDirawat').addClass('d-none');
        } else {
            $('#badgeStatusPulang').removeClass('bg-teal-lt').addClass('bg-info-lt').html(`<i class="ti ti-bed"></i> Masih Dirawat`);
            $('#bannerMasihDirawat').removeClass('d-none');
        }

        if (data.has_unvalidated_resep) {
            $('#bannerResepUnvalidated').removeClass('d-none');
        } else {
            $('#bannerResepUnvalidated').addClass('d-none');
        }

        if (data.no_nota) {
            $('#headerNotaInfo').html(`No. Nota: <strong>${data.no_nota}</strong>`);
        } else {
            $('#headerNotaInfo').html('');
        }
    }

    function renderBillingTable(data) {
        let html = '';
        if (data.categories && data.categories.length > 0) {
            data.categories.forEach(cat => {
                if (cat.items && cat.items.length > 0) {
                    html += `
                        <tr class="bg-light fw-bold">
                            <td colspan="3" class="px-3 text-primary text-uppercase" style="font-size: 0.78rem;">
                                <i class="ti ti-chevron-right me-1"></i> ${cat.label}
                            </td>
                            <td class="px-3 text-end text-primary fw-bold" style="font-size: 0.85rem;">
                                ${new Intl.NumberFormat('id-ID').format(cat.total)}
                            </td>
                        </tr>
                    `;

                    cat.items.forEach(item => {
                        const isNegative = item.subtotal < 0;
                        html += `
                            <tr>
                                <td class="px-3 ps-4 small ${isNegative ? 'text-danger' : 'text-dark'}">${item.item}</td>
                                <td class="px-3 text-center small text-muted">${item.qty}</td>
                                <td class="px-3 text-end small text-muted">${new Intl.NumberFormat('id-ID').format(item.tarif)}</td>
                                <td class="px-3 text-end small fw-500 ${isNegative ? 'text-danger' : 'text-dark'}">
                                    ${new Intl.NumberFormat('id-ID').format(item.subtotal)}
                                </td>
                            </tr>
                        `;
                    });
                }
            });
        }

        if (!html) {
            html = '<tr><td colspan="4" class="text-center p-3 text-muted">Belum ada rincian tindakan atau layanan tercatat.</td></tr>';
        }

        $('#tbodyBillingRanap').html(html);
        $('#tableSubtotalBiaya').text('Rp ' + new Intl.NumberFormat('id-ID').format(data.grand_total));
    }

    function setupPaymentDesk(data) {
        currentRawGrandTotal = parseFloat(data.grand_total) || 0;
        currentDeposit = parseFloat(data.deposit) || 0;

        $('#co_grand_total').val('Rp ' + new Intl.NumberFormat('id-ID').format(currentRawGrandTotal));
        $('#co_potongan').val(data.potongan || 0);
        $('#co_tambahan').val(data.tambahan || 0);
        $('#co_deposit_text').text('Rp ' + new Intl.NumberFormat('id-ID').format(currentDeposit));

        // Setup Akun Piutang dropdown
        $.get("{{ url('/billing/accounts') }}", { no_rawat: data.no_rawat })
            .done((acc) => {
                accountsDataCache = acc;
                let optPiutang = '<option value="">-- Pilih Akun Piutang --</option>';
                if (acc.akun_piutang) {
                    acc.akun_piutang.forEach(ap => {
                        const isDef = (acc.default_piutang && acc.default_piutang.kd_rek === ap.kd_rek) ? 'selected' : '';
                        optPiutang += `<option value="${ap.kd_rek}" ${isDef}>${ap.nama_bayar} (${ap.kd_rek})</option>`;
                    });
                }
                $('#co_kd_rek_piutang').html(optPiutang);

                // Setup Initial Payment Rows
                initPaymentRows(data);
                hitungTotalKasirRanap();
            });
    }

    function initPaymentRows(data) {
        const container = $('#paymentRowsContainerRanap');
        container.empty();

        if (data.saved_payments && data.saved_payments.length > 0) {
            data.saved_payments.forEach((sp, idx) => {
                tambahBarisBayarRanap(sp.nama_bayar, sp.besar_bayar, idx > 0);
            });
        } else {
            // Default 1 row: Akun Cash dari akun_bayar
            let defAkun = 'Bayar Cash';
            if (accountsDataCache && accountsDataCache.akun_bayar && accountsDataCache.akun_bayar.length > 0) {
                const foundCash = accountsDataCache.akun_bayar.find(a => a.nama_bayar.toLowerCase().includes('cash') || a.nama_bayar.toLowerCase().includes('kas'));
                defAkun = foundCash ? foundCash.nama_bayar : accountsDataCache.akun_bayar[0].nama_bayar;
            }
            const defaultNominal = Math.max(0, currentRawGrandTotal - currentDeposit);
            tambahBarisBayarRanap(defAkun, defaultNominal, false);
        }
    }

    function tambahBarisBayarRanap(selectedNamaBayar = '', defaultNominal = 0, canDelete = true) {
        const container = $('#paymentRowsContainerRanap');
        const rowId = 'payRowRanap_' + Date.now() + '_' + Math.floor(Math.random() * 100);

        let optBayar = '';
        if (accountsDataCache && accountsDataCache.akun_bayar && accountsDataCache.akun_bayar.length > 0) {
            accountsDataCache.akun_bayar.forEach(ab => {
                const isSel = (ab.nama_bayar === selectedNamaBayar) ? 'selected' : '';
                optBayar += `<option value="${ab.nama_bayar}" ${isSel}>${ab.nama_bayar}</option>`;
            });
        } else {
            optBayar = `<option value="Bayar Cash">Bayar Cash</option>`;
        }

        const deleteBtn = canDelete ? `
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="$('#${rowId}').remove(); hitungTotalKasirRanap();" title="Hapus Baris">
                <i class="ti ti-trash"></i>
            </button>
        ` : '';

        const html = `
            <div class="row g-2 align-items-center payment-row-ranap" id="${rowId}">
                <div class="col-7">
                    <select class="form-select form-select-sm pay-nama-bayar">
                        ${optBayar}
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" class="form-control form-control-sm pay-besar-bayar" value="${defaultNominal}" min="0" oninput="hitungTotalKasirRanap()">
                </div>
                <div class="col-1 p-0">
                    ${deleteBtn}
                </div>
            </div>
        `;

        container.append(html);
        hitungTotalKasirRanap();
    }

    function hitungTotalKasirRanap() {
        const potongan = parseFloat($('#co_potongan').val()) || 0;
        const tambahan = parseFloat($('#co_tambahan').val()) || 0;

        const adjustedGrandTotal = Math.max(0, (currentRawGrandTotal + tambahan) - potongan);
        const netTotal = Math.max(0, adjustedGrandTotal - currentDeposit);

        $('#co_net_total').text('Rp ' + new Intl.NumberFormat('id-ID').format(netTotal));

        let totalBayar = 0;
        $('.pay-besar-bayar').each(function() {
            totalBayar += parseFloat($(this).val()) || 0;
        });

        $('#co_total_bayar_text').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalBayar));

        if (totalBayar >= netTotal) {
            const kembalian = totalBayar - netTotal;
            $('#co_kembalian_text').text('Rp ' + new Intl.NumberFormat('id-ID').format(kembalian));
            $('#sectionPiutangRanap').hide();
            $('#co_sisa_piutang_text').text('Rp 0');
        } else {
            const sisaPiutang = netTotal - totalBayar;
            $('#co_kembalian_text').text('Rp 0');
            $('#sectionPiutangRanap').show();
            $('#co_sisa_piutang_text').text('Rp ' + new Intl.NumberFormat('id-ID').format(sisaPiutang));
        }
    }

    function submitCloseBillingRanap() {
        if (!selectedRanapNoRawat) {
            Swal.fire('Peringatan', 'Silakan pilih pasien rawat inap terlebih dahulu', 'warning');
            return;
        }

        const payments = [];
        $('.payment-row-ranap').each(function() {
            const namaBayar = $(this).find('.pay-nama-bayar').val();
            const besarBayar = parseFloat($(this).find('.pay-besar-bayar').val()) || 0;
            if (namaBayar && besarBayar > 0) {
                payments.push({ nama_bayar: namaBayar, besar_bayar: besarBayar });
            }
        });

        const potongan = parseFloat($('#co_potongan').val()) || 0;
        const tambahan = parseFloat($('#co_tambahan').val()) || 0;
        const kdRekPiutang = $('#co_kd_rek_piutang').val();
        const tglBayar = $('#co_tgl_bayar').val();

        const netTotal = Math.max(0, (currentRawGrandTotal + tambahan - potongan) - currentDeposit);
        let totalBayar = 0;
        payments.forEach(p => totalBayar += p.besar_bayar);

        if (totalBayar < netTotal && !kdRekPiutang) {
            Swal.fire('Perhatian', 'Sisa tagihan (piutang) belum lunas. Silakan pilih Akun Rekening Piutang terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Tutup Billing Rawat Inap?',
            text: `Simpan pembayaran dan terbitkan nota resmi untuk No. Rawat: ${selectedRanapNoRawat}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tutup Billing',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2fb344'
        }).then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Menyimpan Billing...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

                $.post("{{ url('/billing/ranap/close') }}", {
                    _token: "{{ csrf_token() }}",
                    no_rawat: selectedRanapNoRawat,
                    payments: payments,
                    potongan: potongan,
                    tambahan: tambahan,
                    kd_rek_piutang: kdRekPiutang,
                    tgl_bayar: tglBayar
                }).done((resp) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `Billing Rawat Inap berhasil ditutup. No. Nota: ${resp.no_nota}`,
                        showCancelButton: true,
                        confirmButtonText: 'Cetak Nota (80mm)',
                        cancelButtonText: 'Selesai'
                    }).then((printRes) => {
                        if (printRes.isConfirmed) {
                            cetakBillingKasirRanap('80');
                        }
                    });

                    loadAntreanRanap();
                    loadDetailBillingRanap(selectedRanapNoRawat);
                }).fail((err) => {
                    const msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Gagal menutup billing ranap';
                    Swal.fire('Gagal', msg, 'error');
                });
            }
        });
    }

    function batalBillingRanap() {
        if (!selectedRanapNoRawat) return;

        Swal.fire({
            title: 'Batalkan Billing Ranap?',
            text: 'Pembayaran, nota, dan jurnal akuntansi pasien ini akan dibatalkan/dibalik.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#d63939'
        }).then((res) => {
            if (res.isConfirmed) {
                Swal.fire({ title: 'Membatalkan...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

                $.post("{{ url('/billing/ranap/batal') }}", {
                    _token: "{{ csrf_token() }}",
                    no_rawat: selectedRanapNoRawat
                }).done((resp) => {
                    Swal.fire('Berhasil', resp.message, 'success');
                    loadAntreanRanap();
                    loadDetailBillingRanap(selectedRanapNoRawat);
                }).fail((err) => {
                    const msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Gagal membatalkan billing';
                    Swal.fire('Gagal', msg, 'error');
                });
            }
        });
    }

    function cetakBillingKasirRanap(size = '80') {
        if (!selectedRanapNoRawat) {
            Swal.fire('Peringatan', 'Silakan pilih pasien terlebih dahulu', 'warning');
            return;
        }

        const showObat = $('#printShowObatDetailRanapKasir').is(':checked') ? 1 : 0;
        window.open(`{{ url('/billing/print') }}?no_rawat=${encodeURIComponent(selectedRanapNoRawat)}&size=${size}&show_obat=${showObat}`, '_blank');
    }
</script>
@endpush
@endsection

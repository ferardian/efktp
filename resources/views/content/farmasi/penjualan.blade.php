@extends('layout')

@section('body')
    <div class="container-fluid">
        <!-- Header Page Title & Tabs -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center py-2 bg-light-lt">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar bg-primary-lt text-primary">
                        <i class="ti ti-shopping-cart fs-2"></i>
                    </span>
                    <div>
                        <h3 class="card-title text-primary mb-0">Penjualan Obat Bebas (Diluar Resep)</h3>
                        <div class="text-secondary small">Point of Sale Farmasi & Penjualan Obat Bebas / Karyawan / Luar</div>
                    </div>
                </div>
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-kasir" class="nav-link active" data-bs-toggle="tab">
                            <i class="ti ti-device-desktop-analytics me-1"></i> Kasir Penjualan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-riwayat" class="nav-link" data-bs-toggle="tab" id="navRiwayatTab">
                            <i class="ti ti-history me-1"></i> Riwayat Penjualan
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <!-- TAB 1: KASIR PENJUALAN -->
            <div class="tab-pane active show" id="tab-kasir">
                <form id="formPenjualan" autocomplete="off">
                    @csrf
                    <div class="row g-3">
                        <!-- Left & Center Column: Transaksi, Pencarian Obat & Keranjang -->
                        <div class="col-xl-8 col-lg-8 col-md-12">
                            <!-- Info Header Transaksi -->
                            <div class="card mb-3 shadow-xs border-0">
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label small fw-bold mb-1">No. Nota</label>
                                            <div class="input-group input-group-sm" style="height: 36px;">
                                                <input type="text" class="form-control form-control-sm fw-bold text-primary bg-light" id="nota_jual" name="nota_jual" value="{{ $nextNota }}" readonly style="height: 36px;">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="reloadNextNota()" title="Refresh No. Nota" style="height: 36px;">
                                                    <i class="ti ti-refresh"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label small fw-bold mb-1">Tanggal Transaksi</label>
                                            <input type="date" class="form-control form-control-sm" id="tgl_jual" name="tgl_jual" value="{{ $today }}" style="height: 36px;">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label small fw-bold mb-1">Lokasi Gudang / Depo</label>
                                            <select class="form-select form-select-sm" id="kd_bangsal" name="kd_bangsal" style="height: 36px;">
                                                @foreach($bangsal as $b)
                                                    <option value="{{ $b->kd_bangsal }}" {{ $b->kd_bangsal == 'AP' ? 'selected' : '' }}>
                                                        {{ $b->nm_bangsal }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label small fw-bold mb-1">Petugas / Kasir</label>
                                            <select class="form-select form-select-sm" id="nip" name="nip" style="height: 36px;">
                                                @foreach($petugas as $p)
                                                    <option value="{{ $p->nip }}" {{ $p->nip == $currentNip ? 'selected' : '' }}>
                                                        {{ $p->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1 pt-2 border-top">
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label small fw-bold mb-1">Jenis Penjualan</label>
                                            <select class="form-select form-select-sm text-primary fw-bold" id="jns_jual" name="jns_jual" style="height: 36px;">
                                                <option value="Jual Bebas" selected>Jual Bebas</option>
                                                <option value="Karyawan">Karyawan</option>
                                                <option value="Beli Luar">Beli Luar</option>
                                                <option value="Rawat Jalan">Rawat Jalan</option>
                                                <option value="Kelas 1">Kelas 1</option>
                                                <option value="Kelas 2">Kelas 2</option>
                                                <option value="Kelas 3">Kelas 3</option>
                                                <option value="Utama/BPJS">Utama / BPJS</option>
                                                <option value="VIP">VIP</option>
                                                <option value="VVIP">VVIP</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label small fw-bold mb-1">Nama Pasien / Pembeli</label>
                                            <div class="input-group input-group-sm" style="height: 36px;">
                                                <input type="text" class="form-control form-control-sm" id="nm_pasien" name="nm_pasien" value="UMUM" placeholder="Nama Pembeli / Pasien" style="height: 36px;">
                                                <input type="hidden" id="no_rkm_medis" name="no_rkm_medis" value="-">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openModalCariPasien()" title="Cari Pasien Rekam Medis" style="height: 36px;">
                                                    <i class="ti ti-user-search me-1"></i> Cari RM
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetPasienUmum()" title="Set Pembeli Umum" style="height: 36px;">
                                                    Umum
                                                </button>
                                            </div>
                                            <div id="badgePasienRM" class="small text-success mt-1 d-none">
                                                <i class="ti ti-id-badge-2"></i> No. RM: <span id="textNoRM">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label small fw-bold mb-1">Keterangan / Catatan</label>
                                            <input type="text" class="form-control form-control-sm" id="keterangan" name="keterangan" placeholder="Catatan transaksi (opsional)" style="height: 36px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Pencarian Obat Cepat -->
                            <div class="card mb-3 shadow-xs border-0">
                                <div class="card-body p-3 bg-primary-lt rounded">
                                    <div class="row align-items-center g-2">
                                        <div class="col-md-8 col-sm-12 position-relative">
                                            <label class="form-label small fw-bold text-primary mb-1">
                                                <i class="ti ti-scan me-1"></i> Pencarian Obat / Scan Barcode (Tekan F2)
                                            </label>
                                            <div class="input-group input-group-flat">
                                                <span class="input-group-text bg-white">
                                                    <i class="ti ti-search text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control ps-0" id="inputSearchObat" 
                                                       placeholder="Ketik Nama Obat atau Kode Barang..." 
                                                       autocomplete="off">
                                                <span class="input-group-text bg-white pe-2" id="spinnerCariObat" style="display:none;">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                </span>
                                            </div>
                                            <!-- Dropdown Hasil Pencarian Obat -->
                                            <div id="listHasilCariObat" class="list-group position-absolute w-100 shadow-lg border rounded-bottom" 
                                                 style="z-index: 1050; max-height: 380px; overflow-y: auto; display: none; top: 100%; background: #ffffff;">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 text-md-end text-start mt-2 mt-md-0">
                                            <div class="small text-muted">Barang di keranjang:</div>
                                            <span class="badge bg-primary fs-3 px-3 py-1" id="badgeJumlahItemKeranjang">0 Item</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Keranjang Obat -->
                            <div class="card shadow-xs border-0">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                    <h4 class="card-title text-primary mb-0">
                                        <i class="ti ti-list-check me-1"></i> Daftar Obat Terpilih
                                    </h4>
                                    <button type="button" class="btn btn-outline-danger btn-sm py-1" onclick="clearCart()" id="btnClearCart" style="display: none;">
                                        <i class="ti ti-trash me-1"></i> Kosongkan Keranjang
                                    </button>
                                </div>
                                <div class="table-responsive" style="min-height: 260px; max-height: calc(100vh - 440px); overflow-y: auto;">
                                    <table class="table table-vcenter table-striped table-hover table-bordered mb-0" id="tabelKeranjangPenjualan">
                                        <thead class="table-light sticky-top" style="z-index: 1;">
                                            <tr class="text-center small fw-bold">
                                                <th width="3%">#</th>
                                                <th width="9%">Kode</th>
                                                <th width="19%">Nama Obat / Barang</th>
                                                <th width="14%">Satuan Jual</th>
                                                <th width="8%">Stok Gudang</th>
                                                <th width="11%">Harga (Rp)</th>
                                                <th width="12%">Qty</th>
                                                <th width="6%">Disc(%)</th>
                                                <th width="11%">Subtotal (Rp)</th>
                                                <th width="11%">Aturan Pakai</th>
                                                <th width="4%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartTableBody">
                                            <tr id="emptyCartRow">
                                                <td colspan="11" class="text-center text-muted py-5">
                                                    <i class="ti ti-basket-off fs-1 text-secondary mb-2 d-block"></i>
                                                    Keranjang masih kosong. Gunakan kotak pencarian di atas untuk menambahkan obat.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Billing & Pembayaran -->
                        <div class="col-xl-4 col-lg-4 col-md-12">
                            <div class="card shadow-sm border-0" style="position: sticky; top: 75px; z-index: 10;">
                                <div class="card-header py-2 bg-teal-lt">
                                    <h4 class="card-title text-teal mb-0">
                                        <i class="ti ti-cash me-1"></i> Pembayaran & Billing
                                    </h4>
                                </div>
                                <div class="card-body p-3">
                                    <!-- Grand Total Highlight Card -->
                                    <div class="bg-primary text-white p-3 rounded text-center mb-3 shadow-xs">
                                        <div class="small text-uppercase tracking-wider opacity-75">Total Tagihan (Rp)</div>
                                        <div class="display-6 fw-bold" id="labelGrandTotal">Rp 0</div>
                                    </div>

                                    <!-- Kalkulasi Rincian -->
                                    <div class="d-flex justify-content-between py-1 border-bottom small">
                                        <span class="text-secondary">Subtotal Obat:</span>
                                        <span class="fw-bold" id="labelSubtotalObat">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom small">
                                        <span class="text-secondary">Total Diskon Item:</span>
                                        <span class="text-danger fw-bold" id="labelTotalDiskon">- Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom small">
                                        <span class="text-secondary">Tambahan / Tuslah / Emb:</span>
                                        <span class="fw-bold" id="labelTotalTambahan">Rp 0</span>
                                    </div>

                                    <!-- Ongkir & PPN Row -->
                                    <div class="row g-2 py-2 border-bottom">
                                        <div class="col-6">
                                            <label class="form-label small mb-1 text-secondary">Ongkos Kirim (Rp)</label>
                                            <input type="number" class="form-control form-control-sm text-end" id="ongkir" name="ongkir" value="0" min="0" oninput="calculateBilling()">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small mb-1 text-secondary">PPN (Rp)</label>
                                            <input type="number" class="form-control form-control-sm text-end" id="ppn" name="ppn" value="0" min="0" oninput="calculateBilling()">
                                        </div>
                                    </div>

                                    <!-- Opsi Pembulatan Otomatis -->
                                    <div class="py-2 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label small mb-0 text-secondary fw-semibold">
                                                <i class="ti ti-adjustments-horizontal me-1"></i> Pembulatan Harga
                                            </label>
                                            <span class="badge bg-secondary-lt fw-bold" id="labelSelisihPembulatan">Rp 0</span>
                                        </div>
                                        <select class="form-select form-select-sm" id="opsi_pembulatan" onchange="calculateBilling()">
                                            <option value="none">Tanpa Pembulatan</option>
                                            <option value="ceil100" selected>Ke Atas 100 (Ceil Rp 100)</option>
                                            <option value="round100">Terdekat 100 (Round Rp 100)</option>
                                            <option value="ceil500">Ke Atas 500 (Ceil Rp 500)</option>
                                            <option value="ceil1000">Ke Atas 1.000 (Ceil Rp 1.000)</option>
                                        </select>
                                        <input type="hidden" id="pembulatan" name="pembulatan" value="0">
                                    </div>

                                    <!-- Metode Akun Bayar -->
                                    <div class="mb-3 mt-3">
                                        <label class="form-label small fw-bold mb-1">Metode / Akun Pembayaran</label>
                                        <select class="form-select" id="nama_bayar" name="nama_bayar" onchange="handleAkunBayarChange()">
                                            @foreach($akunBayar as $ab)
                                                <option value="{{ $ab->nama_bayar }}" data-rek="{{ $ab->kd_rek }}" data-ppn="{{ $ab->ppn ?? 0 }}">
                                                    {{ $ab->nama_bayar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="kd_rek" name="kd_rek" value="{{ $akunBayar->first()->kd_rek ?? '111010' }}">
                                    </div>

                                    <!-- Status Pembayaran -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold mb-1">Status Pembayaran</label>
                                        <select class="form-select form-select-sm" id="status_bayar" name="status">
                                            <option value="Sudah Dibayar" selected>Sudah Dibayar (Lunas)</option>
                                            <option value="Belum Dibayar">Belum Dibayar (Piutang)</option>
                                        </select>
                                    </div>

                                    <!-- Uang Diterima & Kembalian -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold mb-1">Uang Diterima (Rp)</label>
                                        <input type="number" class="form-control form-control-lg fw-bold text-end text-success" 
                                               id="uang_bayar" placeholder="0" min="0" oninput="calculateKembalian()">
                                        
                                        <!-- Quick Cash Buttons -->
                                        <div class="d-flex flex-wrap gap-1 mt-2">
                                            <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="setQuickCash('pas')">Uang Pas</button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="setQuickCash(20000)">20.000</button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="setQuickCash(50000)">50.000</button>
                                            <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="setQuickCash(100000)">100.000</button>
                                        </div>
                                    </div>

                                    <!-- Kembalian Display -->
                                    <div class="bg-light p-2 rounded d-flex justify-content-between align-items-center mb-3 border">
                                        <span class="small fw-bold text-secondary">Kembalian:</span>
                                        <span class="fs-2 fw-bold text-teal" id="labelKembalian">Rp 0</span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-success btn-lg shadow-sm" id="btnSimpanCetak" onclick="submitPenjualan(true)">
                                            <i class="ti ti-printer me-2 fs-2"></i> Simpan & Cetak Struk
                                        </button>
                                        <button type="button" class="btn btn-primary" id="btnSimpanSaja" onclick="submitPenjualan(false)">
                                            <i class="ti ti-device-floppy me-2"></i> Simpan Saja
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="resetFormPenjualan()">
                                            <i class="ti ti-rotate-clockwise me-2"></i> Transaksi Baru
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TAB 2: RIWAYAT PENJUALAN -->
            <div class="tab-pane" id="tab-riwayat">
                <div class="card shadow-xs border-0">
                    <div class="card-header py-2 bg-light-lt d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title text-primary mb-0">
                            <i class="ti ti-history me-1"></i> Riwayat Transaksi Penjualan Obat Bebas
                        </h4>
                        <!-- Filters -->
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="input-group input-group-sm" style="width: 290px;">
                                <span class="input-group-text">Periode</span>
                                <input type="date" class="form-control" id="riwayat_tgl_awal" value="{{ $today }}">
                                <input type="date" class="form-control" id="riwayat_tgl_akhir" value="{{ $today }}">
                            </div>
                            <select class="form-select form-select-sm" id="riwayat_filter_status" style="width: 150px;">
                                <option value="semua">Status: Semua</option>
                                <option value="Sudah Dibayar" selected>Sudah Dibayar</option>
                                <option value="Belum Dibayar">Belum Dibayar</option>
                            </select>
                            <button type="button" class="btn btn-primary btn-sm" onclick="reloadTabelRiwayat()">
                                <i class="ti ti-filter me-1"></i> Terapkan
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover nowrap" id="tabelRiwayatPenjualan" width="100%">
                                <thead>
                                    <tr>
                                        <th>No. Nota</th>
                                        <th>Tanggal</th>
                                        <th>Pasien / Pembeli</th>
                                        <th>Jenis Jual</th>
                                        <th>Lokasi Gudang</th>
                                        <th>Petugas / Kasir</th>
                                        <th>Akun Bayar</th>
                                        <th>Status</th>
                                        <th>Total Tagihan (Rp)</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cari Pasien Rekam Medis -->
    <div class="modal fade" id="modalCariPasien" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 bg-primary-lt">
                    <h5 class="modal-title"><i class="ti ti-user-search me-2"></i> Cari Pasien Terdaftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="inputCariPasien" placeholder="Ketik No. RM, Nama Pasien, atau No. KTP..." autocomplete="off">
                        <button class="btn btn-primary" type="button" onclick="doSearchPasien()">
                            <i class="ti ti-search me-1"></i> Cari
                        </button>
                    </div>
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-sm table-hover table-bordered" id="tabelHasilPasien">
                            <thead class="table-light">
                                <tr>
                                    <th>No. RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th width="10%">Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyHasilPasien">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Ketik kata kunci pencarian di atas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi Penjualan -->
    <div class="modal fade" id="modalDetailPenjualan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 bg-light-lt">
                    <h5 class="modal-title text-primary"><i class="ti ti-receipt me-2"></i> Detail Transaksi Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3" id="contentDetailPenjualan">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="btnModalCetakNota">
                        <i class="ti ti-printer me-1"></i> Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    // State Keranjang Belanja
    let cartItems = [];
    let timeoutSearch = null;

    $(document).ready(function () {
        // F2 Keyboard Shortcut to Focus Medicine Search
        $(document).keydown(function (e) {
            if (e.key === 'F2' || e.keyCode === 113) {
                e.preventDefault();
                $('#inputSearchObat').focus().select();
            }
        });

        // Search Obat Live Input
        $('#inputSearchObat').on('input', function () {
            clearTimeout(timeoutSearch);
            const val = $(this).val().trim();
            if (val.length < 1) {
                $('#listHasilCariObat').hide().empty();
                return;
            }
            timeoutSearch = setTimeout(() => {
                fetchSearchObat(val);
            }, 250);
        });

        // Close search results dropdown on outside click
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#inputSearchObat, #listHasilCariObat').length) {
                $('#listHasilCariObat').hide();
            }
        });

        // Event listener saat Jenis Penjualan berubah -> refresh harga pada cart
        $('#jns_jual').on('change', function () {
            const newJenis = $(this).val();
            updateCartPricesByJenis(newJenis);
        });

        // Event listener saat Gudang berubah -> reload search atau notifikasi
        $('#kd_bangsal').on('change', function () {
            const val = $('#inputSearchObat').val().trim();
            if (val.length > 0) {
                fetchSearchObat(val);
            }
        });

        // Enter key di input modal cari pasien
        $('#inputCariPasien').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                doSearchPasien();
            }
        });

        // Init DataTables Riwayat Penjualan saat tab riwayat dibuka
        $('#navRiwayatTab').on('shown.bs.tab', function () {
            initTabelRiwayat();
        });
    });

    // ==========================================
    // NOTA & METADATA HELPERS
    // ==========================================
    function reloadNextNota() {
        const tgl = $('#tgl_jual').val();
        $.get("{{ url('/penjualan/get-next-nota') }}", { tanggal: tgl }, function (res) {
            $('#nota_jual').val(res.next_nota);
        });
    }

    function handleAkunBayarChange() {
        const selected = $('#nama_bayar option:selected');
        const rek = selected.data('rek');
        $('#kd_rek').val(rek);
    }

    // ==========================================
    // PENCARIAN & MANAJEMEN KERANJANG OBAT
    // ==========================================
    function fetchSearchObat(term) {
        const kdBangsal = $('#kd_bangsal').val();
        $('#spinnerCariObat').show();

        $.ajax({
            url: "{{ url('/penjualan/search-obat') }}",
            type: "GET",
            data: { term: term, kd_bangsal: kdBangsal },
            success: function (items) {
                renderSearchResults(items);
            },
            complete: function () {
                $('#spinnerCariObat').hide();
            }
        });
    }

    function renderSearchResults(items) {
        const container = $('#listHasilCariObat');
        container.empty();

        if (items.length === 0) {
            container.append(`
                <div class="list-group-item text-center text-muted py-3 small">
                    <i class="ti ti-alert-circle me-1 text-warning"></i> Tidak ditemukan obat dengan kata kunci tersebut.
                </div>
            `).show();
            return;
        }

        const currentJenis = $('#jns_jual').val();

        items.forEach((item, idx) => {
            const basePrice = getPriceByJenis(item, currentJenis);
            const isi = parseFloat(item.isi) || 1;
            const stockColor = item.stok > 0 ? 'text-teal fw-bold' : 'text-danger fw-bold';
            const safeItem = JSON.stringify(item).replace(/"/g, '&quot;');
            const isSolid = isSolidForm(item.satuan);

            let badgesHtml = '';
            if (item.satuan_besar && isi > 1) {
                badgesHtml = `<span class="badge bg-azure-lt ms-1" style="font-size: 10px; padding: 1px 4px;">1 ${item.satuan_besar} = ${isi} ${item.satuan}</span>`;
            } else if (isSolid) {
                badgesHtml = `<span class="badge bg-secondary-lt ms-1" style="font-size: 10px; padding: 1px 4px;">Strip @10</span>`;
            }

            // Shortcut compact buttons (hanya jika ada kemasan Strip atau Box)
            let quickButtons = '';
            if (isSolid && isi !== 10) {
                const stripPrice = basePrice * 10;
                quickButtons += `
                    <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 ms-1" 
                            style="font-size: 11px; height: 22px; line-height: 20px;"
                            onclick="event.stopPropagation(); addToCart(${safeItem}, 'strip10')"
                            title="Tambah 1 Strip (10 ${item.satuan})">
                        <i class="ti ti-box me-1"></i>+1 Strip (Rp ${formatNumber(stripPrice)})
                    </button>
                `;
            }

            if (item.satuan_besar && isi > 1) {
                const boxPrice = basePrice * isi;
                quickButtons += `
                    <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 ms-1" 
                            style="font-size: 11px; height: 22px; line-height: 20px;"
                            onclick="event.stopPropagation(); addToCart(${safeItem}, 'master_besar')"
                            title="Tambah 1 ${item.satuan_besar} (${isi} ${item.satuan})">
                        <i class="ti ti-package me-1"></i>+1 ${item.satuan_besar} (Rp ${formatNumber(boxPrice)})
                    </button>
                `;
            }

            container.append(`
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 search-obat-item" 
                   style="cursor: pointer;" onclick="addToCart(${safeItem}, 'kecil')">
                    <div style="max-width: 60%;">
                        <div class="fw-bold text-dark d-flex align-items-center flex-wrap" style="font-size: 13px;">
                            <span>${item.nama_brng}</span>
                            ${badgesHtml}
                        </div>
                        <div class="small text-muted" style="font-size: 11px;">
                            <span class="badge bg-secondary-lt me-1 font-monospace" style="font-size: 10px;">${item.kode_brng}</span>
                            Satuan: <b>${item.satuan}</b> | Stok: <span class="${stockColor}">${item.stok}</span>
                            ${item.kapasitas && item.kapasitas !== '-' ? ` | Dosis: ${item.kapasitas}` : ''}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-success" style="font-size: 13.5px;">
                            Rp ${formatNumber(basePrice)} <small class="text-muted" style="font-size: 11px;">/${item.satuan}</small>
                        </div>
                        ${quickButtons ? `<div class="d-flex justify-content-end align-items-center mt-1">${quickButtons}</div>` : ''}
                    </div>
                </div>
            `);
        });

        container.show();
    }

    // Cek apakah bentuk sediaan padat (Tablet, Kapsul, Kaplet, dsb)
    function isSolidForm(satuan) {
        if (!satuan) return false;
        const s = satuan.toUpperCase().trim();
        return ['TAB', 'TABLET', 'KAP', 'KAPLET', 'KAPSUL', 'CAP', 'CAPS', 'PIL', 'BLL'].some(u => s.includes(u));
    }

    // Generator opsi kemasan cerdas (Eceran, Strip x10, Strip Mini x4, Box master)
    function getPackagingOptions(item) {
        const opts = [
            { id: 'kecil', label: item.satuan, multiplier: 1, name: item.satuan }
        ];

        const isi = parseFloat(item.isi) || 1;
        const isSolid = isSolidForm(item.satuan);

        // Jika tablet/kapsul tapi di master data isi bukan 10 (misal Box isi 100 atau isi 1)
        if (isSolid && isi !== 10) {
            opts.push({
                id: 'strip10',
                label: `Strip (10)`,
                multiplier: 10,
                name: 'STRIP'
            });
        }

        // Jika tablet/kapsul dan bukan isi 4 (Strip mini OTC misal Bodrex/Panadol)
        if (isSolid && isi !== 4) {
            opts.push({
                id: 'strip4',
                label: `Strip (4)`,
                multiplier: 4,
                name: 'STRIP MINI'
            });
        }

        // Jika di master data ada satuan_besar dan isi > 1
        if (item.satuan_besar && isi > 1) {
            opts.push({
                id: 'master_besar',
                label: `${item.satuan_besar} (${isi})`,
                multiplier: isi,
                name: item.satuan_besar
            });
        }

        return opts;
    }

    function getPriceByJenis(item, jenis) {
        switch (jenis) {
            case 'Karyawan': return parseFloat(item.karyawan) || 0;
            case 'Beli Luar': return parseFloat(item.beliluar) || 0;
            case 'Rawat Jalan': return parseFloat(item.ralan) || 0;
            case 'Kelas 1': return parseFloat(item.kelas1) || 0;
            case 'Kelas 2': return parseFloat(item.kelas2) || 0;
            case 'Kelas 3': return parseFloat(item.kelas3) || 0;
            case 'Utama/BPJS': return parseFloat(item.utama) || 0;
            case 'VIP': return parseFloat(item.vip) || 0;
            case 'VVIP': return parseFloat(item.vvip) || 0;
            case 'Jual Bebas':
            default:
                return parseFloat(item.jualbebas) || parseFloat(item.ralan) || 0;
        }
    }

    function addToCart(item, targetOptionId = 'kecil') {
        const currentJenis = $('#jns_jual').val();
        const basePrice = getPriceByJenis(item, currentJenis);
        const isi = parseFloat(item.isi) || 1;
        const options = getPackagingOptions(item);

        let selectedOpt = options.find(o => o.id === targetOptionId) || options[0];
        const multiplier = selectedOpt.multiplier;
        const unitPrice = basePrice * multiplier;

        const existingIdx = cartItems.findIndex(c => c.kode_brng === item.kode_brng);
        if (existingIdx !== -1) {
            // Sudah ada di keranjang, tambah kuantitas sesuai opsi yang dipilih
            if (cartItems[existingIdx].current_option_id === targetOptionId) {
                cartItems[existingIdx].qty_input += 1;
            } else {
                // Kasir memilih kemasan lain untuk obat yang sama -> sesuaikan opsi kemasan dan tambah
                cartItems[existingIdx].current_option_id = selectedOpt.id;
                cartItems[existingIdx].current_multiplier = multiplier;
                cartItems[existingIdx].current_satuan_name = selectedOpt.name;
                cartItems[existingIdx].h_jual = cartItems[existingIdx].base_h_jual * multiplier;
                cartItems[existingIdx].qty_input += 1;
            }
            recalculateCartItem(existingIdx);
        } else {
            // Item baru ditambahkan
            cartItems.push({
                kode_brng: item.kode_brng,
                nama_brng: item.nama_brng,
                kode_sat: item.kode_sat,
                satuan: item.satuan,
                kode_satbesar: item.kode_satbesar || item.kode_sat,
                satuan_besar: item.satuan_besar || null,
                isi: isi,
                stok: item.stok,
                base_h_beli: item.h_beli,
                base_h_jual: basePrice,
                h_jual: unitPrice,
                current_option_id: selectedOpt.id,
                current_multiplier: multiplier,
                current_satuan_name: selectedOpt.name,
                qty_input: 1,
                jumlah: multiplier, // base units
                raw_prices: {
                    jualbebas: item.jualbebas,
                    karyawan: item.karyawan,
                    beliluar: item.beliluar,
                    ralan: item.ralan,
                    kelas1: item.kelas1,
                    kelas2: item.kelas2,
                    kelas3: item.kelas3,
                    utama: item.utama,
                    vip: item.vip,
                    vvip: item.vvip
                },
                dis: 0,
                bsr_dis: 0,
                tambahan: 0,
                embalase: 0,
                tuslah: 0,
                aturan_pakai: multiplier > 1 ? `1 ${selectedOpt.name}` : '',
                no_batch: '',
                no_faktur: ''
            });
        }

        $('#listHasilCariObat').hide();
        $('#inputSearchObat').val('').focus();
        renderCartTable();
        calculateBilling();
    }

    function recalculateCartItem(index) {
        const item = cartItems[index];
        if (!item) return;

        const mult = item.current_multiplier || 1;
        item.jumlah = (item.qty_input || 0) * mult;
    }

    function changeItemSatuan(index, optionId) {
        const item = cartItems[index];
        if (!item) return;

        const options = getPackagingOptions(item);
        const selectedOpt = options.find(o => o.id === optionId) || options[0];

        item.current_option_id = selectedOpt.id;
        item.current_multiplier = selectedOpt.multiplier;
        item.current_satuan_name = selectedOpt.name;
        item.h_jual = item.base_h_jual * selectedOpt.multiplier;

        if (selectedOpt.multiplier > 1 && (!item.aturan_pakai || item.aturan_pakai.trim() === '')) {
            item.aturan_pakai = `${item.qty_input} ${selectedOpt.name}`;
        }

        recalculateCartItem(index);
        renderCartTable();
        calculateBilling();
    }

    function stepQty(index, delta) {
        const item = cartItems[index];
        if (!item) return;

        let newQty = (parseFloat(item.qty_input) || 0) + delta;
        if (newQty < 0.01) newQty = 0.01;
        item.qty_input = parseFloat(newQty.toFixed(2));
        recalculateCartItem(index);
        renderCartTable();
        calculateBilling();
    }

    function addQtyDirect(index, count) {
        const item = cartItems[index];
        if (!item) return;

        item.qty_input = (parseFloat(item.qty_input) || 0) + count;
        recalculateCartItem(index);
        renderCartTable();
        calculateBilling();
    }

    function addBaseQtyDirect(index, baseCount) {
        const item = cartItems[index];
        if (!item) return;

        const mult = item.current_multiplier || 1;
        const addPacks = baseCount / mult;
        item.qty_input = parseFloat(((item.qty_input || 0) + addPacks).toFixed(2));
        recalculateCartItem(index);
        renderCartTable();
        calculateBilling();
    }

    function updateCartPricesByJenis(jenis) {
        cartItems.forEach((c, idx) => {
            if (c.raw_prices) {
                c.base_h_jual = getPriceByJenis(c.raw_prices, jenis);
                const mult = c.current_multiplier || 1;
                c.h_jual = c.base_h_jual * mult;
            }
        });
        renderCartTable();
        calculateBilling();
    }

    function renderCartTable() {
        const tbody = $('#cartTableBody');
        tbody.empty();

        if (cartItems.length === 0) {
            tbody.append(`
                <tr id="emptyCartRow">
                    <td colspan="11" class="text-center text-muted py-5">
                        <i class="ti ti-basket-off fs-1 text-secondary mb-2 d-block"></i>
                        Keranjang masih kosong. Gunakan kotak pencarian di atas untuk menambahkan obat.
                    </td>
                </tr>
            `);
            $('#btnClearCart').hide();
            $('#badgeJumlahItemKeranjang').text('0 Item');
            return;
        }

        $('#btnClearCart').show();
        $('#badgeJumlahItemKeranjang').text(`${cartItems.length} Item`);

        cartItems.forEach((item, index) => {
            const subtotal = (item.qty_input || 0) * (item.h_jual || 0);
            const bsrDis = (subtotal * (item.dis || 0)) / 100;
            const itemTotal = subtotal - bsrDis + (item.tambahan || 0) + (item.embalase || 0) + (item.tuslah || 0);

            const isStockDeficit = item.stok < item.jumlah;
            const stockBadge = isStockDeficit 
                ? `<span class="badge bg-danger text-white" title="Stok gudang tidak mencukupi">${item.stok} ${item.satuan}</span>` 
                : `<span class="badge bg-teal-lt">${item.stok} ${item.satuan}</span>`;

            const options = getPackagingOptions(item);
            const currentOptId = item.current_option_id || 'kecil';
            const mult = item.current_multiplier || 1;
            const isSolid = isSolidForm(item.satuan);

            // Satuan selector: hanya render dropdown jika ada lebih dari 1 pilihan
            let satuanSelect = '';
            if (options.length > 1) {
                satuanSelect = `<select class="form-select form-select-sm" onchange="changeItemSatuan(${index}, this.value)">`;
                options.forEach(opt => {
                    const isSel = opt.id === currentOptId ? 'selected' : '';
                    satuanSelect += `<option value="${opt.id}" ${isSel}>${opt.label}</option>`;
                });
                satuanSelect += `</select>`;
            } else {
                satuanSelect = `<span class="badge bg-secondary-lt fw-bold">${item.satuan}</span>`;
            }

            // Qty helper text (misal: = 10 TAB jika pilih Strip)
            let qtyHelper = '';
            if (mult > 1) {
                qtyHelper = `<div class="small text-muted text-center mt-1" style="font-size: 10px;">= ${item.jumlah} ${item.satuan}</div>`;
            }

            // Quick increment chips: HANYA untuk kelipatan relevan (tidak render +1 redundant)
            let quickChips = '';
            if (mult === 1) {
                if (isSolid) {
                    quickChips = `
                        <div class="d-flex justify-content-center gap-1 mt-1">
                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1" style="font-size: 10px; line-height: 1.2;" onclick="addQtyDirect(${index}, 5)" title="Tambah 5">+5</button>
                            <button type="button" class="btn btn-xs btn-outline-success py-0 px-1" style="font-size: 10px; line-height: 1.2;" onclick="addQtyDirect(${index}, 10)" title="Tambah 10 (1 Strip)">+10</button>
                            ${item.isi > 1 && item.satuan_besar ? `
                                <button type="button" class="btn btn-xs btn-outline-info py-0 px-1" style="font-size: 10px; line-height: 1.2;" onclick="addBaseQtyDirect(${index}, ${item.isi})" title="Tambah 1 ${item.satuan_besar}">+Box</button>
                            ` : ''}
                        </div>
                    `;
                } else if (item.isi > 1 && item.satuan_besar) {
                    quickChips = `
                        <div class="d-flex justify-content-center gap-1 mt-1">
                            <button type="button" class="btn btn-xs btn-outline-info py-0 px-1" style="font-size: 10px; line-height: 1.2;" onclick="addBaseQtyDirect(${index}, ${item.isi})" title="Tambah 1 ${item.satuan_besar}">+Box</button>
                        </div>
                    `;
                }
            } else {
                // Di satuan kemasan (Strip / Box)
                quickChips = `
                    <div class="d-flex justify-content-center gap-1 mt-1">
                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size: 10px; line-height: 1.2;" onclick="addQtyDirect(${index}, 1)" title="Tambah 1 ${item.current_satuan_name}">+1</button>
                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size: 10px; line-height: 1.2;" onclick="addQtyDirect(${index}, 2)" title="Tambah 2 ${item.current_satuan_name}">+2</button>
                    </div>
                `;
            }

            tbody.append(`
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="font-monospace small text-muted">${item.kode_brng}</td>
                    <td>
                        <div class="fw-bold text-dark">${item.nama_brng}</div>
                        ${mult > 1 ? `<small class="text-muted"><i class="ti ti-box me-1"></i>1 ${item.current_satuan_name} = ${mult} ${item.satuan}</small>` : ''}
                    </td>
                    <td class="text-center" style="min-width: 100px;">${satuanSelect}</td>
                    <td class="text-center small">
                        ${stockBadge}
                        ${mult > 1 ? `<div class="small text-muted mt-1" style="font-size: 10px;">(~${(item.stok / mult).toFixed(1)} ${item.current_satuan_name})</div>` : ''}
                    </td>
                    <td style="min-width: 95px;">
                        <input type="number" class="form-control form-control-sm text-end" 
                               value="${item.h_jual}" min="0" 
                               onchange="updateCartItemField(${index}, 'h_jual', this.value)">
                    </td>
                    <td style="min-width: 95px;">
                        <div class="input-group input-group-sm" style="max-width: 85px; margin: 0 auto;">
                            <button class="btn btn-outline-secondary px-2 py-0" type="button" onclick="stepQty(${index}, -1)" title="Kurang 1">
                                <i class="ti ti-minus" style="font-size: 10px;"></i>
                            </button>
                            <input type="number" class="form-control text-center fw-bold px-1 py-0 ${isStockDeficit ? 'border-danger text-danger' : ''}" 
                                   value="${item.qty_input}" min="0.01" step="any"
                                   onchange="updateCartItemField(${index}, 'qty_input', this.value)">
                            <button class="btn btn-outline-secondary px-2 py-0" type="button" onclick="stepQty(${index}, 1)" title="Tambah 1">
                                <i class="ti ti-plus" style="font-size: 10px;"></i>
                            </button>
                        </div>
                        ${qtyHelper}
                        ${quickChips}
                    </td>
                    <td style="min-width: 55px;" class="text-center">
                        <input type="number" class="form-control form-control-sm text-center text-danger mx-auto" 
                               style="max-width: 55px;"
                               value="${item.dis}" min="0" max="100"
                               onchange="updateCartItemField(${index}, 'dis', this.value)">
                    </td>
                    <td class="text-end fw-bold text-success fs-4 text-nowrap">
                        Rp ${formatNumber(itemTotal)}
                    </td>
                    <td style="min-width: 100px;">
                        <input type="text" class="form-control form-control-sm" 
                               placeholder="Aturan Pakai" value="${item.aturan_pakai}"
                               onchange="updateCartItemField(${index}, 'aturan_pakai', this.value)">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-danger" onclick="removeCartItem(${index})" title="Hapus">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function updateCartItemField(index, field, value) {
        if (!cartItems[index]) return;

        if (field === 'qty_input') {
            cartItems[index].qty_input = parseFloat(value) || 0;
            recalculateCartItem(index);
        } else if (field === 'h_jual') {
            const newPrice = parseFloat(value) || 0;
            cartItems[index].h_jual = newPrice;
            const mult = cartItems[index].current_multiplier || 1;
            cartItems[index].base_h_jual = newPrice / mult;
        } else if (field === 'dis') {
            cartItems[index].dis = parseFloat(value) || 0;
        } else if (field === 'aturan_pakai') {
            cartItems[index].aturan_pakai = value;
        }

        renderCartTable();
        calculateBilling();
    }

    function removeCartItem(index) {
        cartItems.splice(index, 1);
        renderCartTable();
        calculateBilling();
    }

    function clearCart() {
        if (cartItems.length === 0) return;
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: 'Semua item obat di keranjang akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kosongkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                cartItems = [];
                renderCartTable();
                calculateBilling();
            }
        });
    }

    // ==========================================
    // BILLING & PERHITUNGAN KASIR
    // ==========================================
    function calculateBilling() {
        let subtotalObat = 0;
        let totalDiskon = 0;
        let totalTambahan = 0;

        cartItems.forEach(item => {
            const sub = (item.qty_input || 0) * (item.h_jual || 0);
            const dis = (sub * (item.dis || 0)) / 100;
            const tot = sub - dis + (item.tambahan || 0) + (item.embalase || 0) + (item.tuslah || 0);

            subtotalObat += sub;
            totalDiskon += dis;
            totalTambahan += (item.tambahan || 0) + (item.embalase || 0) + (item.tuslah || 0);
        });

        const totalNetObat = subtotalObat - totalDiskon + totalTambahan;
        const ongkir = parseFloat($('#ongkir').val()) || 0;
        const ppn = parseFloat($('#ppn').val()) || 0;
        const rawGrandTotal = totalNetObat + ongkir + ppn;

        // Opsi Pembulatan Otomatis
        const opsiBulat = $('#opsi_pembulatan').val() || 'none';
        let roundedGrandTotal = rawGrandTotal;

        if (rawGrandTotal > 0) {
            if (opsiBulat === 'ceil100') {
                roundedGrandTotal = Math.ceil(rawGrandTotal / 100) * 100;
            } else if (opsiBulat === 'round100') {
                roundedGrandTotal = Math.round(rawGrandTotal / 100) * 100;
            } else if (opsiBulat === 'ceil500') {
                roundedGrandTotal = Math.ceil(rawGrandTotal / 500) * 500;
            } else if (opsiBulat === 'ceil1000') {
                roundedGrandTotal = Math.ceil(rawGrandTotal / 1000) * 1000;
            }
        }

        const selisihPembulatan = roundedGrandTotal - rawGrandTotal;
        $('#pembulatan').val(selisihPembulatan);

        if (selisihPembulatan > 0) {
            $('#labelSelisihPembulatan').text(`+ Rp ${formatNumber(selisihPembulatan)}`).removeClass('bg-danger-lt bg-secondary-lt').addClass('bg-blue-lt');
        } else if (selisihPembulatan < 0) {
            $('#labelSelisihPembulatan').text(`- Rp ${formatNumber(Math.abs(selisihPembulatan))}`).removeClass('bg-blue-lt bg-secondary-lt').addClass('bg-danger-lt');
        } else {
            $('#labelSelisihPembulatan').text('Rp 0').removeClass('bg-blue-lt bg-danger-lt').addClass('bg-secondary-lt');
        }

        $('#labelSubtotalObat').text(`Rp ${formatNumber(subtotalObat)}`);
        $('#labelTotalDiskon').text(`- Rp ${formatNumber(totalDiskon)}`);
        $('#labelTotalTambahan').text(`Rp ${formatNumber(totalTambahan)}`);
        $('#labelGrandTotal').text(`Rp ${formatNumber(roundedGrandTotal)}`);

        calculateKembalian();
    }

    function calculateKembalian() {
        const grandTotalText = $('#labelGrandTotal').text().replace(/[^0-9]/g, '');
        const grandTotal = parseFloat(grandTotalText) || 0;
        const bayar = parseFloat($('#uang_bayar').val()) || 0;
        const kembalian = bayar - grandTotal;

        if (kembalian >= 0) {
            $('#labelKembalian').text(`Rp ${formatNumber(kembalian)}`).removeClass('text-danger').addClass('text-teal');
        } else {
            $('#labelKembalian').text(`Kurang Rp ${formatNumber(Math.abs(kembalian))}`).removeClass('text-teal').addClass('text-danger');
        }
    }

    function setQuickCash(val) {
        const grandTotalText = $('#labelGrandTotal').text().replace(/[^0-9]/g, '');
        const grandTotal = parseFloat(grandTotalText) || 0;

        if (val === 'pas') {
            $('#uang_bayar').val(grandTotal);
        } else {
            $('#uang_bayar').val(val);
        }
        calculateKembalian();
    }

    // ==========================================
    // PASIEN MODAL & AUTOCOMPLETE
    // ==========================================
    function openModalCariPasien() {
        $('#modalCariPasien').modal('show');
        $('#inputCariPasien').focus();
    }

    function doSearchPasien() {
        const term = $('#inputCariPasien').val().trim();
        if (term.length === 0) return;

        $('#tbodyHasilPasien').html(`
            <tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div> Mencari data pasien...</td></tr>
        `);

        $.get("{{ url('/penjualan/search-pasien') }}", { term: term }, function (data) {
            const tbody = $('#tbodyHasilPasien');
            tbody.empty();

            if (data.length === 0) {
                tbody.append(`<tr><td colspan="5" class="text-center text-muted py-4">Pasien tidak ditemukan.</td></tr>`);
                return;
            }

            data.forEach(p => {
                const safeName = p.nm_pasien.replace(/'/g, "\\'");
                tbody.append(`
                    <tr>
                        <td class="font-monospace fw-bold">${p.no_rkm_medis}</td>
                        <td class="fw-bold text-primary">${p.nm_pasien}</td>
                        <td>${p.alamat || '-'}</td>
                        <td>${p.no_tlp || '-'}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary py-1 px-2" 
                                    onclick="pilihPasien('${p.no_rkm_medis}', '${safeName}')">
                                Pilih
                            </button>
                        </td>
                    </tr>
                `);
            });
        });
    }

    function pilihPasien(noRm, nama) {
        $('#no_rkm_medis').val(noRm);
        $('#nm_pasien').val(nama);
        $('#textNoRM').text(noRm);
        $('#badgePasienRM').removeClass('d-none');
        $('#modalCariPasien').modal('hide');
    }

    function resetPasienUmum() {
        $('#no_rkm_medis').val('-');
        $('#nm_pasien').val('UMUM');
        $('#badgePasienRM').addClass('d-none');
    }

    // ==========================================
    // SUBMIT PENJUALAN
    // ==========================================
    function submitPenjualan(printAfterSave = false) {
        if (cartItems.length === 0) {
            showToast('Keranjang masih kosong. Tambahkan obat terlebih dahulu.', 'warning');
            return;
        }

        const payload = {
            _token: "{{ csrf_token() }}",
            nota_jual: $('#nota_jual').val(),
            tgl_jual: $('#tgl_jual').val(),
            kd_bangsal: $('#kd_bangsal').val(),
            nip: $('#nip').val(),
            jns_jual: $('#jns_jual').val(),
            no_rkm_medis: $('#no_rkm_medis').val(),
            nm_pasien: $('#nm_pasien').val(),
            keterangan: $('#keterangan').val(),
            ongkir: $('#ongkir').val(),
            ppn: $('#ppn').val(),
            pembulatan: $('#pembulatan').val(),
            nama_bayar: $('#nama_bayar').val(),
            kd_rek: $('#kd_rek').val(),
            status: $('#status_bayar').val(),
            items: cartItems.map(item => {
                let finalKodeSat = item.kode_sat;
                let finalAturanPakai = item.aturan_pakai || '';
                const mult = item.current_multiplier || 1;
                const packName = item.current_satuan_name || item.satuan;
                if (mult > 1) {
                    if (!finalAturanPakai.includes(packName)) {
                        finalAturanPakai = finalAturanPakai 
                            ? `(${item.qty_input} ${packName}) ${finalAturanPakai}`
                            : `(${item.qty_input} ${packName})`;
                    }
                }

                return {
                    kode_brng: item.kode_brng,
                    kode_sat: finalKodeSat,
                    h_jual: item.base_h_jual,
                    h_beli: item.base_h_beli,
                    jumlah: item.jumlah,
                    dis: item.dis,
                    tambahan: item.tambahan,
                    embalase: item.embalase,
                    tuslah: item.tuslah,
                    aturan_pakai: finalAturanPakai,
                    no_batch: item.no_batch,
                    no_faktur: item.no_faktur
                };
            })
        };

        loadingAjax('Menyimpan transaksi penjualan obat...');

        $.ajax({
            url: "{{ url('/penjualan/store') }}",
            type: "POST",
            data: payload,
            success: function (res) {
                Swal.close();
                showToast(res.message, 'success');

                const savedNota = res.nota_jual;

                if (printAfterSave) {
                    window.open(`{{ url('/penjualan/print') }}/${savedNota}`, '_blank', 'width=450,height=600');
                }

                resetFormPenjualan();
            },
            error: function (xhr) {
                Swal.close();
                const msg = xhr.responseJSON?.message || 'Gagal menyimpan transaksi penjualan.';
                showToast(msg, 'error');
            }
        });
    }

    function resetFormPenjualan() {
        cartItems = [];
        renderCartTable();
        resetPasienUmum();
        $('#keterangan').val('');
        $('#ongkir').val('0');
        $('#ppn').val('0');
        $('#pembulatan').val('0');
        $('#opsi_pembulatan').val('ceil100');
        $('#uang_bayar').val('');
        reloadNextNota();
        calculateBilling();
    }

    // ==========================================
    // TAB 2: RIWAYAT PENJUALAN DATATABLES
    // ==========================================
    let dtRiwayat = null;

    function initTabelRiwayat() {
        if ($.fn.DataTable.isDataTable('#tabelRiwayatPenjualan')) {
            dtRiwayat.ajax.reload();
            return;
        }

        dtRiwayat = $('#tabelRiwayatPenjualan').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{ url('/penjualan/data') }}",
                type: "GET",
                data: function (d) {
                    d.tgl_awal = $('#riwayat_tgl_awal').val();
                    d.tgl_akhir = $('#riwayat_tgl_akhir').val();
                    d.status = $('#riwayat_filter_status').val();
                }
            },
            columns: [
                {
                    data: 'nota_jual',
                    name: 'nota_jual',
                    render: (data) => `<span class="fw-bold font-monospace text-primary">${data}</span>`
                },
                {
                    data: 'tgl_jual',
                    name: 'tgl_jual',
                    className: 'text-center'
                },
                {
                    data: 'nm_pasien',
                    name: 'nm_pasien',
                    render: (data, type, row) => {
                        const rm = row.no_rkm_medis && row.no_rkm_medis !== '-' ? ` <span class="badge bg-secondary-lt">${row.no_rkm_medis}</span>` : '';
                        return `<b>${data}</b>${rm}`;
                    }
                },
                {
                    data: 'jns_jual',
                    name: 'jns_jual',
                    className: 'text-center',
                    render: (data) => `<span class="badge bg-blue-lt">${data}</span>`
                },
                {
                    data: 'bangsal.nm_bangsal',
                    name: 'bangsal.nm_bangsal',
                    defaultContent: '-',
                    render: (data, type, row) => row.bangsal?.nm_bangsal || row.kd_bangsal || '-'
                },
                {
                    data: 'petugas.nama',
                    name: 'petugas.nama',
                    defaultContent: '-',
                    render: (data, type, row) => row.petugas?.nama || row.nip || '-'
                },
                {
                    data: 'nama_bayar',
                    name: 'nama_bayar',
                    defaultContent: '-'
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: (data) => data === 'Sudah Dibayar' 
                        ? '<span class="badge bg-success">Lunas</span>' 
                        : '<span class="badge bg-warning">Belum Dibayar</span>'
                },
                {
                    data: 'grand_total',
                    name: 'grand_total',
                    className: 'text-end fw-bold text-success',
                    render: (data) => `Rp ${formatNumber(data)}`
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: (data, type, row) => {
                        return `
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-icon btn-primary" title="Lihat Detail" onclick="showDetailPenjualan('${row.nota_jual}')">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-success" title="Cetak Struk Nota" onclick="printStruk('${row.nota_jual}')">
                                    <i class="ti ti-printer"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" title="Batalkan / Hapus" onclick="deletePenjualan('${row.nota_jual}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']]
        });
    }

    function reloadTabelRiwayat() {
        if (dtRiwayat) {
            dtRiwayat.ajax.reload();
        }
    }

    function showDetailPenjualan(nota) {
        $('#contentDetailPenjualan').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2 text-muted">Memuat data nota...</div>
            </div>
        `);
        $('#modalDetailPenjualan').modal('show');
        $('#btnModalCetakNota').attr('onclick', `printStruk('${nota}')`);

        $.get(`{{ url('/penjualan/detail') }}/${nota}`, function (res) {
            const p = res.penjualan;
            let itemsHtml = '';

            p.detail_jual.forEach((d, i) => {
                itemsHtml += `
                    <tr>
                        <td class="text-center">${i + 1}</td>
                        <td class="font-monospace small">${d.kode_brng}</td>
                        <td>${d.barang?.nama_brng || '-'}</td>
                        <td class="text-center">${d.kode_sat || '-'}</td>
                        <td class="text-end">Rp ${formatNumber(d.h_jual)}</td>
                        <td class="text-center">${d.jumlah}</td>
                        <td class="text-center">${d.dis}%</td>
                        <td class="text-end fw-bold">Rp ${formatNumber(d.total)}</td>
                        <td>${d.aturan_pakai || '-'}</td>
                    </tr>
                `;
            });

            $('#contentDetailPenjualan').html(`
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr><td class="text-muted" width="30%">No. Nota</td><td>: <b>${p.nota_jual}</b></td></tr>
                            <tr><td class="text-muted">Tanggal</td><td>: ${p.tgl_jual}</td></tr>
                            <tr><td class="text-muted">Pembeli</td><td>: <b>${p.nm_pasien}</b> (${p.no_rkm_medis})</td></tr>
                            <tr><td class="text-muted">Jenis Jual</td><td>: <span class="badge bg-blue-lt">${p.jns_jual}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr><td class="text-muted" width="30%">Gudang/Depo</td><td>: ${p.bangsal?.nm_bangsal || p.kd_bangsal}</td></tr>
                            <tr><td class="text-muted">Petugas/Kasir</td><td>: ${p.petugas?.nama || p.nip}</td></tr>
                            <tr><td class="text-muted">Akun Bayar</td><td>: ${p.nama_bayar}</td></tr>
                            <tr><td class="text-muted">Status</td><td>: <span class="badge ${p.status === 'Sudah Dibayar' ? 'bg-success' : 'bg-warning'}">${p.status}</span></td></tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered table-striped">
                        <thead class="table-light text-center small">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Disc</th>
                                <th>Total</th>
                                <th>Aturan Pakai</th>
                            </tr>
                        </thead>
                        <tbody>${itemsHtml}</tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <div style="min-width: 250px;">
                        <div class="d-flex justify-content-between py-1 small"><span>Subtotal Obat:</span><b>Rp ${formatNumber(res.total_obat)}</b></div>
                        <div class="d-flex justify-content-between py-1 small"><span>Ongkos Kirim:</span><span>Rp ${formatNumber(p.ongkir || 0)}</span></div>
                        <div class="d-flex justify-content-between py-1 small"><span>PPN:</span><span>Rp ${formatNumber(p.ppn || 0)}</span></div>
                        <div class="d-flex justify-content-between py-2 border-top fs-3 text-success"><span>Grand Total:</span><b>Rp ${formatNumber(res.grand_total)}</b></div>
                    </div>
                </div>
            `);
        });
    }

    function printStruk(nota) {
        window.open(`{{ url('/penjualan/print') }}/${nota}`, '_blank', 'width=450,height=600');
    }

    function deletePenjualan(nota) {
        Swal.fire({
            title: 'Batalkan Transaksi?',
            html: `Apakah Anda yakin ingin membatalkan transaksi <b>${nota}</b>?<br><small class="text-danger">Stok obat akan dikembalikan ke gudang dan jurnal akuntansi terkait akan dihapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Membatalkan transaksi...');
                $.ajax({
                    url: `{{ url('/penjualan/delete') }}/${nota}`,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        Swal.close();
                        showToast(res.message, 'success');
                        reloadTabelRiwayat();
                    },
                    error: function (xhr) {
                        Swal.close();
                        showToast(xhr.responseJSON?.message || 'Gagal membatalkan transaksi.', 'error');
                    }
                });
            }
        });
    }

    // Number formatter helper
    function formatNumber(num) {
        return (parseFloat(num) || 0).toLocaleString('id-ID');
    }
</script>
@endpush

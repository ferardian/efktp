@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-input" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-plus me-2"></i> Input Penerimaan
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-history" class="nav-link" data-bs-toggle="tab" role="tab" id="btnTabHistory">
                            <i class="ti ti-history me-2"></i> Riwayat Penerimaan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Tab Input -->
                    <div class="tab-pane fade show active" id="tab-input" role="tabpanel">
                        <form id="formPenerimaan">
                            @csrf
                            <input type="hidden" id="is_edit" value="0">
                            <input type="hidden" id="original_no_faktur" value="">

                            <!-- Edit Mode Alert Banner -->
                            <div class="alert alert-warning d-none mb-3" id="alert_edit_mode">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ti ti-pencil me-2 fs-3"></i> Mode Edit Faktur: <strong id="lbl_edit_faktur"></strong>
                                        <span class="ms-2 text-muted small">(Perubahan akan merevisi stok & jurnal lama)</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelEditMode()">
                                        <i class="ti ti-x me-1"></i> Batal Edit
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Header Transaksi -->
                                <div class="col-md-12 mb-3">
                                    <div class="card bg-light-lt">
                                        <div class="card-body">
                                            <div class="row row-cards">
                                                <div class="col-md-3">
                                                    <label class="form-label required">No. Faktur / Penerimaan</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="no_faktur" id="no_faktur" placeholder="PB20260821001" required>
                                                        <button type="button" class="btn btn-outline-secondary" onclick="generateNoFaktur()" title="Generate No. Faktur Otomatis">
                                                            <i class="ti ti-refresh"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">No. Order / SP Medis</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="no_order" id="no_order" placeholder="Nomor SP (Optional)">
                                                        <button type="button" class="btn btn-outline-primary" onclick="openModalSp()" title="Cari Surat Pemesanan Medis">
                                                            <i class="ti ti-search me-1"></i> SP
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-label required mb-0">Supplier</label>
                                                        <button type="button" class="btn btn-sm btn-link p-0 text-primary" onclick="openModalQuickSuplier()" title="Tambah Supplier Baru">
                                                            <i class="ti ti-plus"></i> Tambah Baru
                                                        </button>
                                                    </div>
                                                    <select class="form-select select-suplier" name="kode_suplier" id="kode_suplier" style="width: 100%" required>
                                                        <option value="">-- Pilih Supplier --</option>
                                                        @foreach($suplier as $sup)
                                                            <option value="{{ $sup->kode_suplier }}">{{ $sup->nama_suplier }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Lokasi Gudang/Depo Penerima</label>
                                                    <select class="form-select select-bangsal" name="kd_bangsal" id="kd_bangsal" style="width: 100%" required>
                                                        <option value="">-- Pilih Gudang --</option>
                                                        @foreach($bangsal as $bg)
                                                            <option value="{{ $bg->kd_bangsal }}">{{ $bg->nm_bangsal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row row-cards mt-2">
                                                <div class="col-md-3">
                                                    <label class="form-label required">Petugas Penerima Obat</label>
                                                    <select class="form-select select-petugas" name="nip" id="nip" style="width: 100%" required>
                                                        <option value="">-- Pilih Petugas --</option>
                                                        @foreach($petugas as $ptg)
                                                            <option value="{{ $ptg->nip }}" {{ (session('pegawai') && session('pegawai')->nik == $ptg->nip) ? 'selected' : '' }}>
                                                                {{ $ptg->nama }} ({{ $ptg->nip }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label required">Tanggal Faktur</label>
                                                    <input type="date" class="form-control" name="tgl_faktur" id="tgl_faktur" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Tanggal Pesan</label>
                                                    <input type="date" class="form-control" name="tgl_pesan" id="tgl_pesan" value="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Tanggal Jatuh Tempo</label>
                                                    <input type="date" class="form-control" name="tgl_tempo" id="tgl_tempo" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">PPN (%)</label>
                                                    <input type="number" class="form-control" name="ppn_percent" id="ppn_percent" value="11" min="0" step="0.1">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Meterai</label>
                                                    <input type="number" class="form-control" name="meterai" id="meterai_header" value="0" min="0">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">Potongan</label>
                                                    <input type="number" class="form-control" name="potongan" id="potongan_header" value="0" min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keranjang Belanja (Full Width Batch Input) -->
                                <div class="col-md-12">
                                    <div class="card" style="min-height: 50vh;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h4 class="card-title text-success mb-0"><i class="ti ti-shopping-cart me-2"></i> Keranjang Penerimaan Barang Medis</h4>
                                            </div>
                                            
                                            <!-- Batch Search Dropdown -->
                                            <div class="mb-4 bg-light-lt p-3 rounded border">
                                                <label class="form-label fw-bold text-blue mb-1"><i class="ti ti-search me-1"></i> Cari & Tambah Obat/BHP ke Keranjang (Ketik nama obat, lalu pilih/Enter):</label>
                                                <select class="form-select select-obat" id="input_kode_brng_batch" style="width: 100%" data-placeholder="Ketik nama obat / BHP di sini...">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            
                                            <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                                <table class="table table-hover table-striped table-bordered align-middle mb-0" id="tableCart">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 8%">Kode</th>
                                                            <th style="width: 20%">Nama Obat/BHP</th>
                                                            <th style="width: 8%">Satuan Beli</th>
                                                            <th style="width: 12%">No. Batch</th>
                                                            <th style="width: 12%">Expired</th>
                                                            <th class="text-end" style="width: 11%">Harga Beli</th>
                                                            <th class="text-center" style="width: 9%">Qty Beli</th>
                                                            <th class="text-end" style="width: 7%">Disc %</th>
                                                            <th class="text-end" style="width: 10%">Total Netto</th>
                                                            <th class="text-center" style="width: 10%">Harga Jual</th>
                                                            <th class="text-center" style="width: 5%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Dinamis diisi JS -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <!-- Footer Summary & Save -->
                                        <div class="card-footer bg-light">
                                            <div class="row">
                                                <div class="col-md-6 offset-md-6">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-secondary">Subtotal :</span>
                                                        <span class="fw-bold" id="lblSubtotal">Rp 0</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-secondary">Potongan Faktur :</span>
                                                        <span class="fw-bold text-danger" id="lblPotongan">- Rp 0</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-secondary">PPN (<span id="lblPpnPercent">11</span>%) :</span>
                                                        <span class="fw-bold" id="lblPpn">Rp 0</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-secondary">Meterai :</span>
                                                        <span class="fw-bold" id="lblMeterai">Rp 0</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between border-top pt-2 mb-3">
                                                        <span class="h3 mb-0">Grand Total Tagihan :</span>
                                                        <span class="h2 text-success mb-0" id="lblGrandTotal">Rp 0</span>
                                                    </div>
                                                    
                                                    <button type="button" class="btn btn-success w-100 btn-lg" id="btn_save_penerimaan" onclick="savePenerimaan()">
                                                        <i class="ti ti-device-floppy me-2"></i> Simpan Transaksi Penerimaan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Riwayat -->
                    <div class="tab-pane fade" id="tab-history" role="tabpanel">
                        <!-- Filter Bar -->
                        <div class="row row-cards mb-3 align-items-end bg-light-lt p-3 rounded border mx-0">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Awal Faktur</label>
                                <input type="date" class="form-control" id="filter_tgl_awal" value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir Faktur</label>
                                <input type="date" class="form-control" id="filter_tgl_akhir" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cari (No. Faktur / SP / Supplier)</label>
                                <input type="text" class="form-control" id="filter_search" placeholder="Ketik kata kunci pencarian...">
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="button" class="btn btn-primary w-100" onclick="renderTableHistory()">
                                    <i class="ti ti-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover nowrap w-100" id="tbPenerimaan">
                                <thead>
                                    <tr>
                                        <th>No. Faktur</th>
                                        <th>No. Order (SP)</th>
                                        <th>Supplier</th>
                                        <th>Gudang</th>
                                        <th>Tgl Faktur</th>
                                        <th>Tgl Tempo</th>
                                        <th>Subtotal</th>
                                        <th>PPN</th>
                                        <th>Grand Total</th>
                                        <th>Petugas Penerima</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for setting selling prices -->
    <div class="modal fade" id="modalHargaJual" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-coin me-2 text-white"></i> Set Harga Jual Baru (<span id="modal_nama_brng_title"></span>)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_item_index">
                    <div class="alert alert-info py-2 mb-3 small d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-info-circle me-1"></i> Harga Beli per Unit: <strong id="modal_harga_beli_label">Rp 0</strong>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="autoCalculatePricesModal()">
                            <i class="ti ti-calculator me-1"></i> Auto Margin
                        </button>
                    </div>
                    <div class="row row-cards">
                        <div class="col-6 mb-2">
                            <label class="form-label">Harga Ralan</label>
                            <input type="number" class="form-control" id="modal_ralan">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Jual Bebas</label>
                            <input type="number" class="form-control" id="modal_jualbebas">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Kelas 1</label>
                            <input type="number" class="form-control" id="modal_kelas1">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Kelas 2</label>
                            <input type="number" class="form-control" id="modal_kelas2">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Kelas 3</label>
                            <input type="number" class="form-control" id="modal_kelas3">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Utama</label>
                            <input type="number" class="form-control" id="modal_utama">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">VIP</label>
                            <input type="number" class="form-control" id="modal_vip">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">VVIP</label>
                            <input type="number" class="form-control" id="modal_vvip">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Karyawan</label>
                            <input type="number" class="form-control" id="modal_karyawan">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Beli Luar</label>
                            <input type="number" class="form-control" id="modal_beliluar">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="saveSellingPricesModal()">Simpan Harga Jual</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Items -->
    <div class="modal fade" id="modalDetailPenerimaan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="ti ti-file-text me-2 text-primary"></i> Detail Item Penerimaan (<span id="detail_faktur_title"></span>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle" id="tbDetailItems">
                            <thead>
                                <tr class="bg-light">
                                    <th>Kode</th>
                                    <th>Nama Obat/BHP</th>
                                    <th>Satuan</th>
                                    <th>No. Batch</th>
                                    <th>Expired</th>
                                    <th class="text-end">Harga Beli</th>
                                    <th class="text-center">Qty Beli</th>
                                    <th class="text-end">Disc %</th>
                                    <th class="text-end">Total Netto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filled by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Select SP (Surat Pemesanan Medis) -->
    <div class="modal fade" id="modalSp" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-file-description me-2 text-white"></i> Pilih Surat Pemesanan Medis (SP Order)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="search_sp" placeholder="Ketik No. SP / Nama Supplier..." onkeyup="filterSpList()">
                    </div>
                    <div class="table-responsive" style="max-height: 350px;">
                        <table class="table table-hover table-striped align-middle" id="tableSpList">
                            <thead>
                                <tr>
                                    <th>No. SP Order</th>
                                    <th>Supplier</th>
                                    <th>Tanggal SP</th>
                                    <th>Total Tagihan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dinamis via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Quick Add Supplier -->
    <div class="modal fade" id="modalQuickSuplier" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-truck me-2 text-white"></i> Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formQuickSuplier" onsubmit="event.preventDefault(); saveQuickSuplier();">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label required">Kode Supplier</label>
                            <input type="text" class="form-control" id="quick_kode_suplier" readonly required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Nama Supplier / PT</label>
                            <input type="text" class="form-control" id="quick_nama_suplier" placeholder="PT. Kimia Farma Trading" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="quick_alamat" placeholder="Alamat kantor / distributor">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="quick_no_telp" placeholder="021-12345678">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan & Gunakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let cartItems = [];
        let spListCache = [];

        $(document).ready(() => {
            // Initialize select2
            $('.select-suplier').select2();
            $('.select-bangsal').select2();
            $('.select-petugas').select2();

            // Setup select2 data barang search using utility function
            selectDataBarang($('#input_kode_brng_batch'), 'body');

            // Generate No. Faktur if empty
            if (!$('#no_faktur').val()) {
                generateNoFaktur();
            }

            // Handle medicine selection to add directly to table
            $('#input_kode_brng_batch').on('change', function () {
                const selectData = $(this).select2('data')[0];
                if (!selectData || !selectData.detail) return;

                const detail = selectData.detail;
                const itemCode = selectData.id;
                
                // Check if already in current list
                const exists = cartItems.find(x => x.kode_brng === itemCode && x.no_batch === '');
                if (exists) {
                    showToast('Obat sudah ada dalam keranjang', 'warning');
                    $(this).val(null).trigger('change');
                    return;
                }

                const hBeli = detail.h_beli || 0;
                const ppn_percent = parseFloat($('#ppn_percent').val()) || 0;

                // Add item to cartItems
                const newItem = {
                    kode_brng: itemCode,
                    nama_brng: selectData.text,
                    kode_sat: detail.kode_sat || '-',
                    satuan_nama: detail.satuan ? detail.satuan.satuan : '-',
                    isi: parseFloat(detail.isi) || 1,
                    jumlah: 1,
                    h_beli: hBeli,
                    dis: 0,
                    no_batch: '',
                    kadaluarsa: "{{ date('Y-m-d', strtotime('+2 years')) }}",
                    ralan: detail.ralan || 0,
                    jualbebas: detail.jualbebas || 0,
                    kelas1: detail.kelas1 || 0,
                    kelas2: detail.kelas2 || 0,
                    kelas3: detail.kelas3 || 0,
                    utama: detail.utama || 0,
                    vip: detail.vip || 0,
                    vvip: detail.vvip || 0,
                    karyawan: detail.karyawan || 0,
                    beliluar: detail.beliluar || 0
                };

                cartItems.push(newItem);
                const newIndex = cartItems.length - 1;
                renderCartTable();

                // Auto calculate prices for new item
                autoCalculateItemPrices(newIndex, hBeli, ppn_percent);
                
                // Reset select2 and keep it open/focused
                $(this).val(null).trigger('change');
                setTimeout(() => {
                    $(this).select2('open');
                }, 100);
            });

            // Update summaries whenever input changes
            $('#ppn_percent, #meterai_header, #potongan_header').on('input change', function() {
                calculateSummary();
            });

            // Trigger search filter on Enter key
            $('#filter_search').on('keypress', function(e) {
                if (e.which === 13) {
                    renderTableHistory();
                }
            });

            // Load DataTable on tab shown
            $('#btnTabHistory').on('shown.bs.tab', function () {
                renderTableHistory();
            });
        });

        // Generate No. Faktur otomatis dari server
        function generateNoFaktur() {
            if ($('#is_edit').val() === '1') return;
            const tgl = $('#tgl_faktur').val() || "{{ date('Y-m-d') }}";
            $.get("{{ url('/penerimaan/get-next-faktur') }}", { tgl_faktur: tgl })
                .done((response) => {
                    if (response && response.no_faktur) {
                        $('#no_faktur').val(response.no_faktur);
                    }
                });
        }

        // Open modal SP list
        function openModalSp() {
            $('#modalSp').modal('show');
            loadSpList();
        }

        function loadSpList() {
            const tbody = $('#tableSpList tbody');
            tbody.empty().append('<tr><td colspan="5" class="text-center py-3 text-secondary"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat daftar SP Medis...</td></tr>');

            $.get("{{ url('/penerimaan/sp-list') }}")
                .done((response) => {
                    spListCache = response || [];
                    renderSpTable(spListCache);
                })
                .fail(() => {
                    tbody.empty().append('<tr><td colspan="5" class="text-center py-3 text-danger">Gagal memuat daftar SP Medis</td></tr>');
                });
        }

        function renderSpTable(list) {
            const tbody = $('#tableSpList tbody');
            tbody.empty();

            if (!list || list.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center py-3 text-secondary">Tidak ada SP Medis berstatus "Proses Pesan"</td></tr>');
                return;
            }

            list.forEach((item) => {
                const supplierName = item.suplier ? item.suplier.nama_suplier : (item.kode_suplier || '-');
                tbody.append(`
                    <tr>
                        <td class="fw-bold text-primary">${item.no_pemesanan}</td>
                        <td>${supplierName}</td>
                        <td>${item.tanggal || '-'}</td>
                        <td class="fw-bold text-success">Rp ${formatRupiah(parseFloat(item.tagihan || 0))}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-primary" onclick="selectSp('${item.no_pemesanan}')">
                                <i class="ti ti-check me-1"></i> Pilih SP
                            </button>
                        </td>
                    </tr>
                `);
            });
        }

        function filterSpList() {
            const term = ($('#search_sp').val() || '').toLowerCase();
            const filtered = spListCache.filter(x => 
                (x.no_pemesanan || '').toLowerCase().includes(term) ||
                (x.suplier && x.suplier.nama_suplier && x.suplier.nama_suplier.toLowerCase().includes(term))
            );
            renderSpTable(filtered);
        }

        // Pull details of selected SP into cart
        function selectSp(noOrder) {
            loadingAjax('Tarik data item dari SP ' + noOrder + '...');

            $.get(`{{ url('/penerimaan/sp-detail') }}/${noOrder}`)
                .done((sp) => {
                    Swal.close();
                    if (!sp) return;

                    $('#no_order').val(sp.no_pemesanan);
                    if (sp.kode_suplier) {
                        $('#kode_suplier').val(sp.kode_suplier).trigger('change');
                    }
                    if (sp.ppn) {
                        const total2 = parseFloat(sp.total2) || 1;
                        const ppnPct = ((parseFloat(sp.ppn) / total2) * 100).toFixed(1);
                        $('#ppn_percent').val(ppnPct);
                    }
                    if (sp.meterai) {
                        $('#meterai_header').val(sp.meterai);
                    }
                    if (sp.potongan) {
                        $('#potongan_header').val(sp.potongan);
                    }

                    // Reset current cart and import SP items
                    cartItems = [];
                    if (sp.detail && sp.detail.length > 0) {
                        sp.detail.forEach((d) => {
                            const barang = d.barang || {};
                            const satuanNama = barang.satuan ? barang.satuan.satuan : (d.kode_sat || '-');
                            cartItems.push({
                                kode_brng: d.kode_brng,
                                nama_brng: barang.nama_brng || d.kode_brng,
                                kode_sat: d.kode_sat || barang.kode_sat || '-',
                                satuan_nama: satuanNama,
                                isi: parseFloat(barang.isi) || 1,
                                jumlah: parseFloat(d.jumlah) || 1,
                                h_beli: parseFloat(d.h_pesan) || 0,
                                dis: parseFloat(d.dis) || 0,
                                no_batch: '',
                                kadaluarsa: "{{ date('Y-m-d', strtotime('+2 years')) }}",
                                ralan: barang.ralan || 0,
                                jualbebas: barang.jualbebas || 0,
                                kelas1: barang.kelas1 || 0,
                                kelas2: barang.kelas2 || 0,
                                kelas3: barang.kelas3 || 0,
                                utama: barang.utama || 0,
                                vip: barang.vip || 0,
                                vvip: barang.vvip || 0,
                                karyawan: barang.karyawan || 0,
                                beliluar: barang.beliluar || 0
                            });
                        });
                    }

                    renderCartTable();
                    $('#modalSp').modal('hide');
                    showToast('Berhasil menarik item dari SP Order ' + noOrder);
                })
                .fail((xhr) => {
                    Swal.close();
                    showToast('Gagal menarik SP: ' + (xhr.responseJSON?.message || 'Error'), 'error');
                });
        }

        // Auto calculate prices for item using margin settings
        function autoCalculateItemPrices(index, hBeli, ppnPercent) {
            const item = cartItems[index];
            if (!item) return;

            $.post("{{ url('/penerimaan/calculate-prices') }}", {
                _token: "{{ csrf_token() }}",
                kode_brng: item.kode_brng,
                h_beli: hBeli,
                ppn_percent: ppnPercent
            }).done((res) => {
                if (res && res.prices) {
                    const p = res.prices;
                    cartItems[index].ralan = p.ralan;
                    cartItems[index].jualbebas = p.jualbebas;
                    cartItems[index].kelas1 = p.kelas1;
                    cartItems[index].kelas2 = p.kelas2;
                    cartItems[index].kelas3 = p.kelas3;
                    cartItems[index].utama = p.utama;
                    cartItems[index].vip = p.vip;
                    cartItems[index].vvip = p.vvip;
                    cartItems[index].karyawan = p.karyawan;
                    cartItems[index].beliluar = p.beliluar;
                }
            });
        }

        // Update cart item property dynamically
        function updateCartItem(index, key, val) {
            if (key === 'jumlah') {
                cartItems[index].jumlah = parseFloat(val) || 0;
            } else if (key === 'h_beli') {
                cartItems[index].h_beli = parseFloat(val) || 0;
                // Auto calculate prices on h_beli change
                const ppnPercent = parseFloat($('#ppn_percent').val()) || 0;
                autoCalculateItemPrices(index, cartItems[index].h_beli, ppnPercent);
            } else if (key === 'dis') {
                cartItems[index].dis = parseFloat(val) || 0;
            } else {
                cartItems[index][key] = val;
            }
            
            // Recalculate row total
            const subtotal = cartItems[index].jumlah * cartItems[index].h_beli;
            const discAmt = (cartItems[index].dis / 100) * subtotal;
            const total = subtotal - discAmt;
            $(`#total_item_${index}`).text('Rp ' + formatRupiah(total));

            calculateSummary();
        }

        // Open modal to edit selling prices for specific item
        function editSellingPrices(index) {
            const item = cartItems[index];
            $('#modal_item_index').val(index);
            $('#modal_nama_brng_title').text(item.nama_brng);
            $('#modal_harga_beli_label').text('Rp ' + formatRupiah(item.h_beli));
            $('#modal_ralan').val(item.ralan);
            $('#modal_jualbebas').val(item.jualbebas);
            $('#modal_kelas1').val(item.kelas1);
            $('#modal_kelas2').val(item.kelas2);
            $('#modal_kelas3').val(item.kelas3);
            $('#modal_utama').val(item.utama);
            $('#modal_vip').val(item.vip);
            $('#modal_vvip').val(item.vvip);
            $('#modal_karyawan').val(item.karyawan);
            $('#modal_beliluar').val(item.beliluar);
            
            $('#modalHargaJual').modal('show');
        }

        // Trigger auto calculation from modal
        function autoCalculatePricesModal() {
            const index = $('#modal_item_index').val();
            if (index === '') return;

            const item = cartItems[index];
            const ppnPercent = parseFloat($('#ppn_percent').val()) || 0;

            loadingAjax('Menghitung margin harga jual...');
            $.post("{{ url('/penerimaan/calculate-prices') }}", {
                _token: "{{ csrf_token() }}",
                kode_brng: item.kode_brng,
                h_beli: item.h_beli,
                ppn_percent: ppnPercent
            }).done((res) => {
                Swal.close();
                if (res && res.prices) {
                    const p = res.prices;
                    $('#modal_ralan').val(p.ralan);
                    $('#modal_jualbebas').val(p.jualbebas);
                    $('#modal_kelas1').val(p.kelas1);
                    $('#modal_kelas2').val(p.kelas2);
                    $('#modal_kelas3').val(p.kelas3);
                    $('#modal_utama').val(p.utama);
                    $('#modal_vip').val(p.vip);
                    $('#modal_vvip').val(p.vvip);
                    $('#modal_karyawan').val(p.karyawan);
                    $('#modal_beliluar').val(p.beliluar);
                    showToast('Harga jual berhasil dihitung otomatis berdasarkan margin %');
                }
            }).fail(() => {
                Swal.close();
            });
        }

        // Save customized selling prices back to cart array item
        function saveSellingPricesModal() {
            const index = $('#modal_item_index').val();
            if (index === '') return;

            cartItems[index].ralan = parseFloat($('#modal_ralan').val()) || 0;
            cartItems[index].jualbebas = parseFloat($('#modal_jualbebas').val()) || 0;
            cartItems[index].kelas1 = parseFloat($('#modal_kelas1').val()) || 0;
            cartItems[index].kelas2 = parseFloat($('#modal_kelas2').val()) || 0;
            cartItems[index].kelas3 = parseFloat($('#modal_kelas3').val()) || 0;
            cartItems[index].utama = parseFloat($('#modal_utama').val()) || 0;
            cartItems[index].vip = parseFloat($('#modal_vip').val()) || 0;
            cartItems[index].vvip = parseFloat($('#modal_vvip').val()) || 0;
            cartItems[index].karyawan = parseFloat($('#modal_karyawan').val()) || 0;
            cartItems[index].beliluar = parseFloat($('#modal_beliluar').val()) || 0;

            $('#modalHargaJual').modal('hide');
            showToast('Harga jual berhasil disesuaikan untuk item ini');
        }

        // Remove item from cart array
        function removeItemFromCart(index) {
            cartItems.splice(index, 1);
            renderCartTable();
        }

        // Render the shopping cart table content and calculate values
        function renderCartTable() {
            const tbody = $('#tableCart tbody');
            tbody.empty();

            if (cartItems.length === 0) {
                tbody.append('<tr><td colspan="11" class="text-center text-secondary py-3">Belum ada item obat di keranjang</td></tr>');
                calculateSummary();
                return;
            }

            cartItems.forEach((item, index) => {
                const subtotal = item.jumlah * item.h_beli;
                const discAmt = (item.dis / 100) * subtotal;
                const total = subtotal - discAmt;
                const totalStokKecil = item.jumlah * (item.isi || 1);

                tbody.append(`
                    <tr>
                        <td>${item.kode_brng}</td>
                        <td>
                            <div class="fw-bold text-dark">${item.nama_brng}</div>
                            ${item.isi > 1 ? `<span class="badge bg-blue-lt small">1 ${item.satuan_nama} = ${item.isi} unit kecil (Total +${totalStokKecil} stok)</span>` : ''}
                        </td>
                        <td>${item.satuan_nama}</td>
                        <td style="width: 140px;">
                            <input type="text" class="form-control form-control-sm" value="${item.no_batch || ''}" placeholder="No. Batch" oninput="updateCartItem(${index}, 'no_batch', this.value)">
                        </td>
                        <td style="width: 150px;">
                            <input type="date" class="form-control form-control-sm" value="${item.kadaluarsa}" onchange="updateCartItem(${index}, 'kadaluarsa', this.value)">
                        </td>
                        <td style="width: 130px;">
                            <input type="number" class="form-control form-control-sm text-end" value="${item.h_beli}" min="0" oninput="updateCartItem(${index}, 'h_beli', this.value)">
                        </td>
                        <td style="width: 110px;">
                            <input type="number" class="form-control form-control-sm text-center" value="${item.jumlah}" min="0.01" step="any" oninput="updateCartItem(${index}, 'jumlah', this.value)">
                        </td>
                        <td style="width: 90px;">
                            <input type="number" class="form-control form-control-sm text-end" value="${item.dis}" min="0" max="100" oninput="updateCartItem(${index}, 'dis', this.value)">
                        </td>
                        <td class="text-end fw-bold text-dark text-nowrap" id="total_item_${index}" style="width: 120px;">
                            Rp ${formatRupiah(total)}
                        </td>
                        <td class="text-center" style="width: 130px;">
                            <button type="button" class="btn btn-sm btn-ghost-primary" onclick="editSellingPrices(${index})">
                                <i class="ti ti-coin me-1"></i> Harga Jual
                            </button>
                        </td>
                        <td class="text-center" style="width: 50px;">
                            <button type="button" class="btn btn-sm btn-ghost-danger" onclick="removeItemFromCart(${index})">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            calculateSummary();
        }

        // Calculate and refresh all the receipt summaries
        function calculateSummary() {
            let totalItemPrice = 0;

            cartItems.forEach((item) => {
                const sub = item.jumlah * item.h_beli;
                const discVal = (item.dis / 100) * sub;
                totalItemPrice += (sub - discVal);
            });

            const potongan = parseFloat($('#potongan_header').val()) || 0;
            const ppn_percent = parseFloat($('#ppn_percent').val()) || 0;
            const meterai = parseFloat($('#meterai_header').val()) || 0;

            const afterDiscount = Math.max(0, totalItemPrice - potongan);
            const ppn = (ppn_percent / 100) * afterDiscount;
            const grandTotal = afterDiscount + ppn + meterai;

            $('#lblSubtotal').text('Rp ' + formatRupiah(totalItemPrice));
            $('#lblPotongan').text('- Rp ' + formatRupiah(potongan));
            $('#lblPpnPercent').text(ppn_percent);
            $('#lblPpn').text('Rp ' + formatRupiah(ppn));
            $('#lblMeterai').text('Rp ' + formatRupiah(meterai));
            $('#lblGrandTotal').text('Rp ' + formatRupiah(grandTotal));
        }

        // Post the receipt transaction data to controller
        function savePenerimaan() {
            if (!$('#no_faktur').val()) {
                showToast('No. Faktur wajib diisi', 'warning');
                return;
            }
            if (!$('#kode_suplier').val()) {
                showToast('Pilih Supplier terlebih dahulu', 'warning');
                return;
            }
            if (!$('#kd_bangsal').val()) {
                showToast('Pilih Gudang terlebih dahulu', 'warning');
                return;
            }
            if (!$('#nip').val()) {
                showToast('Pilih Petugas Penerima Obat terlebih dahulu', 'warning');
                return;
            }
            if (cartItems.length === 0) {
                showToast('Keranjang item obat masih kosong', 'warning');
                return;
            }

            // Prep values
            let total1 = 0;
            cartItems.forEach((i) => {
                total1 += (i.jumlah * i.h_beli);
            });
            const potongan = parseFloat($('#potongan_header').val()) || 0;
            const total2 = Math.max(0, total1 - potongan);
            const ppn_percent = parseFloat($('#ppn_percent').val()) || 0;
            const ppn = (ppn_percent / 100) * total2;
            const meterai = parseFloat($('#meterai_header').val()) || 0;
            const tagihan = total2 + ppn + meterai;

            const data = {
                _token: "{{ csrf_token() }}",
                is_edit: $('#is_edit').val(),
                original_no_faktur: $('#original_no_faktur').val(),
                no_faktur: $('#no_faktur').val(),
                no_order: $('#no_order').val(),
                kode_suplier: $('#kode_suplier').val(),
                kd_bangsal: $('#kd_bangsal').val(),
                nip: $('#nip').val(),
                tgl_faktur: $('#tgl_faktur').val(),
                tgl_pesan: $('#tgl_pesan').val(),
                tgl_tempo: $('#tgl_tempo').val(),
                ppn: ppn,
                meterai: meterai,
                potongan: potongan,
                total1: total1,
                total2: total2,
                tagihan: tagihan,
                items: cartItems
            };

            const isEditMode = $('#is_edit').val() === '1';
            loadingAjax(isEditMode ? 'Sedang meng-update penerimaan obat & jurnal...' : 'Sedang memproses penerimaan obat & jurnal...');

            $.ajax({
                url: "{{ url('/penerimaan/store') }}",
                type: 'POST',
                data: data,
                success: (response) => {
                    showToast(response.message);
                    resetAllForm();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON?.message || 'Gagal menyimpan transaksi penerimaan', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        // Reset the whole header and cart fields
        function resetAllForm() {
            $('#is_edit').val('0');
            $('#original_no_faktur').val('');
            $('#alert_edit_mode').addClass('d-none');
            $('#btn_save_penerimaan')
                .removeClass('btn-warning')
                .addClass('btn-success')
                .html('<i class="ti ti-device-floppy me-2"></i> Simpan Transaksi Penerimaan');
            $('#formPenerimaan').trigger('reset');
            $('.select-suplier').val('').trigger('change');
            $('.select-bangsal').val('').trigger('change');
            $('.select-petugas').val('').trigger('change');
            cartItems = [];
            renderCartTable();
            $('#input_kode_brng_batch').val(null).trigger('change');
            generateNoFaktur();
        }

        // Load receipts list
        function renderTableHistory() {
            const tglAwal = $('#filter_tgl_awal').val();
            const tglAkhir = $('#filter_tgl_akhir').val();
            const search = $('#filter_search').val();

            $('#tbPenerimaan').DataTable({
                responsive: true,
                serverSide: false,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/penerimaan/data') }}",
                    data: {
                        tgl_awal: tglAwal,
                        tgl_akhir: tglAkhir,
                        search: search
                    },
                    dataSrc: ""
                },
                columns: [
                    { 
                        data: 'no_faktur',
                        render: (data) => `
                            <a href="javascript:void(0)" class="fw-bold text-primary" onclick="showDetailPenerimaan('${data}')">
                                ${data}
                            </a>
                        `
                    },
                    { data: 'no_order', defaultContent: '-' },
                    { data: 'suplier.nama_suplier', defaultContent: '-' },
                    { data: 'bangsal.nm_bangsal', defaultContent: '-' },
                    { data: 'tgl_faktur' },
                    { data: 'tgl_tempo' },
                    { 
                        data: 'total1',
                        render: (data) => 'Rp ' + formatRupiah(data)
                    },
                    { 
                        data: 'ppn',
                        render: (data) => 'Rp ' + formatRupiah(data)
                    },
                    { 
                        data: 'tagihan',
                        render: (data) => '<span class="fw-bold text-success">Rp ' + formatRupiah(data) + '</span>'
                    },
                    { 
                        data: 'nip',
                        render: (data, type, row) => row.petugas ? `${row.petugas.nama} (${data})` : data
                    },
                    {
                        data: null,
                        orderable: false,
                        render: (data) => {
                            return `
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetailPenerimaan('${data.no_faktur}')" title="Lihat Detail">
                                        <i class="ti ti-eye me-1"></i> Detail
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="editPenerimaan('${data.no_faktur}')" title="Edit Transaksi">
                                        <i class="ti ti-pencil me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteHistory('${data.no_faktur}')" title="Batal Transaksi">
                                        <i class="ti ti-trash me-1"></i> Batal
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });
        }

        // Load receipt data into input form for editing
        function editPenerimaan(no_faktur) {
            loadingAjax('Memuat data penerimaan ' + no_faktur + '...');

            $.get(`{{ url('/penerimaan/edit-data') }}/${no_faktur}`)
                .done((data) => {
                    Swal.close();
                    if (!data) return;

                    // Set Edit state
                    $('#is_edit').val('1');
                    $('#original_no_faktur').val(data.no_faktur);

                    // Set header inputs
                    $('#no_faktur').val(data.no_faktur);
                    $('#no_order').val(data.no_order || '');
                    if (data.kode_suplier) $('#kode_suplier').val(data.kode_suplier).trigger('change');
                    if (data.kd_bangsal) $('#kd_bangsal').val(data.kd_bangsal).trigger('change');
                    if (data.nip) $('#nip').val(data.nip).trigger('change');
                    if (data.tgl_faktur) $('#tgl_faktur').val(data.tgl_faktur);
                    if (data.tgl_pesan) $('#tgl_pesan').val(data.tgl_pesan);
                    if (data.tgl_tempo) $('#tgl_tempo').val(data.tgl_tempo);

                    // Calculate PPN %
                    const total2 = parseFloat(data.total2) || 1;
                    const ppnVal = parseFloat(data.ppn) || 0;
                    const ppnPct = total2 > 0 ? ((ppnVal / total2) * 100).toFixed(1) : 11;
                    $('#ppn_percent').val(ppnPct);
                    $('#meterai_header').val(data.meterai || 0);
                    $('#potongan_header').val(data.potongan || 0);

                    // Load cartItems
                    cartItems = [];
                    if (data.detail && data.detail.length > 0) {
                        data.detail.forEach((d) => {
                            const barang = d.barang || {};
                            const satuanNama = barang.satuan ? barang.satuan.satuan : (d.kode_sat || '-');
                            cartItems.push({
                                kode_brng: d.kode_brng,
                                nama_brng: barang.nama_brng || d.kode_brng,
                                kode_sat: d.kode_sat || barang.kode_sat || '-',
                                satuan_nama: satuanNama,
                                isi: parseFloat(barang.isi) || 1,
                                jumlah: parseFloat(d.jumlah) || 1,
                                h_beli: parseFloat(d.h_pesan) || 0,
                                dis: parseFloat(d.dis) || 0,
                                no_batch: d.no_batch || '',
                                kadaluarsa: d.kadaluarsa || "{{ date('Y-m-d', strtotime('+2 years')) }}",
                                ralan: parseFloat(d.ralan || barang.ralan || 0),
                                jualbebas: parseFloat(d.jualbebas || barang.jualbebas || 0),
                                kelas1: parseFloat(d.kelas1 || barang.kelas1 || 0),
                                kelas2: parseFloat(d.kelas2 || barang.kelas2 || 0),
                                kelas3: parseFloat(d.kelas3 || barang.kelas3 || 0),
                                utama: parseFloat(d.utama || barang.utama || 0),
                                vip: parseFloat(d.vip || barang.vip || 0),
                                vvip: parseFloat(d.vvip || barang.vvip || 0),
                                karyawan: parseFloat(d.karyawan || barang.karyawan || 0),
                                beliluar: parseFloat(d.beliluar || barang.beliluar || 0)
                            });
                        });
                    }

                    renderCartTable();

                    // Display alert and switch tab
                    $('#lbl_edit_faktur').text(data.no_faktur);
                    $('#alert_edit_mode').removeClass('d-none');
                    $('#btn_save_penerimaan')
                        .removeClass('btn-success')
                        .addClass('btn-warning')
                        .html('<i class="ti ti-pencil me-2"></i> Update Transaksi Penerimaan');

                    // Switch tab to input
                    const tabInputEl = document.querySelector('a[href="#tab-input"]');
                    if (tabInputEl) {
                        const tabInput = new bootstrap.Tab(tabInputEl);
                        tabInput.show();
                    }

                    showToast('Data faktur ' + data.no_faktur + ' dimuat ke form input', 'info');
                })
                .fail((xhr) => {
                    Swal.close();
                    showToast('Gagal memuat data faktur: ' + (xhr.responseJSON?.message || 'Error'), 'error');
                });
        }

        // Cancel edit mode
        function cancelEditMode() {
            resetAllForm();
            showToast('Mode Edit dibatalkan');
        }

        // Fetch and show items inside the detail modal
        function showDetailPenerimaan(no_faktur) {
            $('#detail_faktur_title').text(no_faktur);
            const tbody = $('#tbDetailItems tbody');
            tbody.empty().append('<tr><td colspan="9" class="text-center text-secondary py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Loading...</td></tr>');
            $('#modalDetailPenerimaan').modal('show');

            $.get("{{ url('/penerimaan/detail') }}", { no_faktur: no_faktur })
                .done((response) => {
                    tbody.empty();
                    if (!response || response.length === 0) {
                        tbody.append('<tr><td colspan="9" class="text-center py-3 text-secondary">Tidak ada data item obat</td></tr>');
                        return;
                    }
                    response.forEach((item) => {
                        const nama_brng = item.barang ? item.barang.nama_brng : '-';
                        const satuan = item.barang && item.barang.satuan ? item.barang.satuan.satuan : '-';
                        tbody.append(`
                            <tr>
                                <td>${item.kode_brng}</td>
                                <td class="fw-bold text-dark">${nama_brng}</td>
                                <td>${satuan}</td>
                                <td>${item.no_batch || '-'}</td>
                                <td>${item.kadaluarsa || '-'}</td>
                                <td class="text-end">Rp ${formatRupiah(parseFloat(item.h_pesan))}</td>
                                <td class="text-center">${item.jumlah}</td>
                                <td class="text-end">${item.dis}%</td>
                                <td class="text-end fw-bold text-dark">Rp ${formatRupiah(parseFloat(item.total))}</td>
                            </tr>
                        `);
                    });
                })
                .fail((xhr) => {
                    showToast('Gagal memuat detail penerimaan: ' + (xhr.responseJSON?.message || 'Error'), 'error');
                });
        }

        // Cancel / delete receipt transaction
        function deleteHistory(no_faktur) {
            Swal.fire({
                title: 'Hapus/Batalkan Penerimaan?',
                html: `Apakah Anda yakin ingin membatalkan transaksi faktur <b>${no_faktur}</b>?<br><b class="text-danger">Tindakan ini akan mengurangi kembali stok obat & membatalkan jurnal terkait!</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Membatalkan penerimaan...');
                    $.ajax({
                        url: `{{ url('/penerimaan/delete') }}/${no_faktur}`,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: (response) => {
                            showToast(response.message);
                            renderTableHistory();
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON?.message || 'Gagal membatalkan transaksi', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

        // Helper to format values as currency string
        function formatRupiah(number) {
            return number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
        }

        // Quick Add Supplier functions
        function openModalQuickSuplier() {
            $('#formQuickSuplier').trigger('reset');
            $.get("{{ url('/suplier/get-next-kode') }}").done((res) => {
                if (res && res.kode_suplier) {
                    $('#quick_kode_suplier').val(res.kode_suplier);
                }
            });
            $('#modalQuickSuplier').modal('show');
        }

        function saveQuickSuplier() {
            const data = {
                _token: "{{ csrf_token() }}",
                kode_suplier: $('#quick_kode_suplier').val(),
                nama_suplier: $('#quick_nama_suplier').val(),
                alamat: $('#quick_alamat').val(),
                no_telp: $('#quick_no_telp').val()
            };

            loadingAjax('Menyimpan supplier baru...');
            $.post("{{ url('/suplier/store') }}", data)
                .done((res) => {
                    Swal.close();
                    if (res && res.data) {
                        showToast('Supplier ' + res.data.nama_suplier + ' berhasil ditambahkan');
                        $('#modalQuickSuplier').modal('hide');
                        reloadSuplierOptions(res.data.kode_suplier);
                    }
                })
                .fail((xhr) => {
                    Swal.close();
                    showToast(xhr.responseJSON?.message || 'Gagal menyimpan supplier baru', 'error');
                });
        }

        function reloadSuplierOptions(selectedCode) {
            $.get("{{ url('/suplier/data') }}").done((list) => {
                const select = $('#kode_suplier');
                select.empty().append('<option value="">-- Pilih Supplier --</option>');
                if (list && list.length > 0) {
                    list.forEach((s) => {
                        const isSelected = s.kode_suplier === selectedCode ? 'selected' : '';
                        select.append(`<option value="${s.kode_suplier}" ${isSelected}>${s.nama_suplier}</option>`);
                    });
                }
                select.trigger('change');
            });
        }
    </script>
@endpush

@push('style')
    <style>
        input[type="date"]::-webkit-datetime-edit-text,
        input[type="date"]::-webkit-datetime-edit-month-field,
        input[type="date"]::-webkit-datetime-edit-day-field,
        input[type="date"]::-webkit-datetime-edit-year-field {
            color: #232e3c !important;
        }
        input[type="date"], .form-control, .form-select {
            color: #232e3c !important;
        }
        .form-label {
            color: #232e3c !important;
        }
        #tableCart input[type="number"]::-webkit-outer-spin-button,
        #tableCart input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        #tableCart input[type="number"] {
            -moz-appearance: textfield;
        }
        #tableCart input.form-control-sm {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
    </style>
@endpush

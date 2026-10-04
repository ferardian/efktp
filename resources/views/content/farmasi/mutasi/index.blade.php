@extends('layout')

@section('body')
<style>
    .cart-row:hover {
        background-color: #f8fafc;
    }
    .badge-lokasi {
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
    }
</style>

<div class="container-fluid">
    <!-- Header Title & Tabs -->
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center py-2 bg-light-lt">
            <div class="d-flex align-items-center gap-2">
                <span class="avatar bg-primary-lt text-primary">
                    <i class="ti ti-arrows-left-right fs-2"></i>
                </span>
                <div>
                    <h3 class="card-title text-primary mb-0">Mutasi Obat & BHP Antar Gudang / Depo</h3>
                    <div class="text-secondary small">Distribusi stok farmasi antar lokasi, audit trail kartu stok (SIMKES Khanza Standard)</div>
                </div>
            </div>
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tab-form-mutasi" class="nav-link active" data-bs-toggle="tab" role="tab">
                        <i class="ti ti-plus me-1 text-primary"></i> Input Mutasi Baru
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tab-riwayat-mutasi" class="nav-link" data-bs-toggle="tab" role="tab" id="navRiwayatTab">
                        <i class="ti ti-history me-1 text-secondary"></i> Riwayat & Audit Mutasi
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content">
        <!-- ========================================== -->
        <!-- TAB 1: FORM INPUT MUTASI BARU              -->
        <!-- ========================================== -->
        <div class="tab-pane active show" id="tab-form-mutasi" role="tabpanel">
            <form id="formMutasiBarang" autocomplete="off" onsubmit="event.preventDefault(); simpanTransaksiMutasi();">
                @csrf
                <div class="row g-3">
                    <!-- Header Info Mutasi -->
                    <div class="col-12">
                        <div class="card shadow-xs border">
                            <div class="card-body p-3">
                                <div class="row g-3 align-items-end">
                                    <!-- Gudang Asal -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-label required fw-bold text-dark small mb-1">
                                            <i class="ti ti-building-warehouse text-primary me-1"></i> Gudang / Depo Asal (Pengirim)
                                        </label>
                                        <select class="form-select select-bangsal" id="kd_bangsaldari" name="kd_bangsaldari" required onchange="onGudangChanged()">
                                            @foreach($bangsal as $bg)
                                                <option value="{{ $bg->kd_bangsal }}" {{ ($bg->kd_bangsal == 'GD' || str_contains(strtoupper($bg->nm_bangsal), 'GUDANG')) ? 'selected' : '' }}>
                                                    {{ $bg->nm_bangsal }} ({{ $bg->kd_bangsal }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tombol Tukar Lokasi -->
                                    <div class="col-auto text-center px-0 pb-1 d-none d-md-block">
                                        <button type="button" class="btn btn-icon btn-outline-secondary" onclick="tukarLokasi()" title="Tukar Asal & Tujuan">
                                            <i class="ti ti-arrows-exchange fs-3"></i>
                                        </button>
                                    </div>

                                    <!-- Gudang Tujuan -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-label required fw-bold text-dark small mb-1">
                                            <i class="ti ti-truck-delivery text-success me-1"></i> Gudang / Depo Tujuan (Penerima)
                                        </label>
                                        <select class="form-select select-bangsal" id="kd_bangsalke" name="kd_bangsalke" required onchange="onGudangChanged()">
                                            @foreach($bangsal as $bg)
                                                <option value="{{ $bg->kd_bangsal }}" {{ ($bg->kd_bangsal == 'AP' || str_contains(strtoupper($bg->nm_bangsal), 'APOTEK')) ? 'selected' : '' }}>
                                                    {{ $bg->nm_bangsal }} ({{ $bg->kd_bangsal }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tanggal Mutasi -->
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label required fw-bold text-dark small mb-1">
                                            <i class="ti ti-calendar me-1"></i> Tanggal & Waktu
                                        </label>
                                        <input type="datetime-local" class="form-control" id="tanggal_mutasi" name="tanggal" value="{{ date('Y-m-d\TH:i') }}" required>
                                    </div>

                                    <!-- Keterangan / Memo -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-label required fw-bold text-dark small mb-1">
                                            <i class="ti ti-note me-1"></i> Keterangan / Memo
                                        </label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" maxlength="60" placeholder="Contoh: Distribusi rutin apotek..." value="Distribusi stok rutin" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Pencarian Obat & Keranjang Mutasi -->
                    <div class="col-12">
                        <div class="card shadow-xs border">
                            <!-- Search & Action Bar -->
                            <div class="card-header bg-light py-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 500px;">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white"><i class="ti ti-search"></i></span>
                                        <input type="text" class="form-control" id="search_obat_input" placeholder="Cari obat di gudang asal (Nama / Kode / Batch)..." autocomplete="off">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 text-nowrap" onclick="openModalLookupObat()">
                                        <i class="ti ti-list-search"></i><span>Lookup Barang</span>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" id="lblStokCountInfo">
                                        Item Dimutasi: <strong id="lblTotalItem" class="text-primary">0</strong>
                                    </span>
                                    <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" onclick="clearCartMutasi()" id="btnClearCart" style="display: none;">
                                        <i class="ti ti-trash"></i><span>Kosongkan List</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Tabel Keranjang Mutasi -->
                            <div class="card-body p-0">
                                <div class="table-responsive" style="min-height: 280px; max-height: 520px; overflow-y: auto;">
                                    <table class="table table-hover table-striped align-middle mb-0" id="tableCartMutasi">
                                        <thead class="table-light sticky-top" style="z-index: 5;">
                                            <tr class="small text-muted fw-bold">
                                                <th width="4%" class="text-center">#</th>
                                                <th width="12%">Kode</th>
                                                <th width="30%">Nama Obat / Alkes / BHP</th>
                                                <th width="8%" class="text-center">Satuan</th>
                                                <th width="12%">No. Batch</th>
                                                <th width="9%" class="text-end text-danger">Stok Asal</th>
                                                <th width="9%" class="text-end text-success">Stok Tujuan</th>
                                                <th width="11%" class="text-center">Qty Mutasi</th>
                                                <th width="5%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartTableBody">
                                            <tr id="emptyCartRow">
                                                <td colspan="9" class="text-center text-muted py-5">
                                                    <i class="ti ti-package-off fs-1 text-secondary mb-2 d-block"></i>
                                                    Belum ada obat yang dipilih. Gunakan kotak pencarian atau tombol <strong>Lookup Barang</strong> di atas untuk menambahkan obat dari gudang asal.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Footer Card Simpan Transaksi -->
                            <div class="card-footer bg-light-lt d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <span class="text-secondary small">Pastikan jumlah mutasi tidak melebihi stok gudang asal.</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetFormMutasi()">
                                        <i class="ti ti-refresh me-1"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1 px-4" id="btnSimpanMutasi" onclick="simpanTransaksiMutasi()">
                                        <i class="ti ti-device-floppy"></i><span>Simpan Mutasi</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: RIWAYAT & AUDIT MUTASI              -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-riwayat-mutasi" role="tabpanel">
            <!-- Filter Riwayat -->
            <div class="card shadow-xs border mb-3">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label small fw-bold mb-1">Tanggal Awal</label>
                            <input type="date" class="form-control form-control-sm" id="hist_tgl_awal" value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label small fw-bold mb-1">Tanggal Akhir</label>
                            <input type="date" class="form-control form-control-sm" id="hist_tgl_akhir" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label small fw-bold mb-1">Gudang Asal</label>
                            <select class="form-select form-select-sm" id="hist_kd_bangsaldari">
                                <option value="">-- Semua Asal --</option>
                                @foreach($bangsal as $bg)
                                    <option value="{{ $bg->kd_bangsal }}">{{ $bg->nm_bangsal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label small fw-bold mb-1">Gudang Tujuan</label>
                            <select class="form-select form-select-sm" id="hist_kd_bangsalke">
                                <option value="">-- Semua Tujuan --</option>
                                @foreach($bangsal as $bg)
                                    <option value="{{ $bg->kd_bangsal }}">{{ $bg->nm_bangsal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1" onclick="reloadTableHistory()">
                                <i class="ti ti-filter"></i><span>Filter Riwayat</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Riwayat DataTables -->
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover nowrap w-100 align-middle" id="tbRiwayatMutasi">
                            <thead class="table-light">
                                <tr>
                                    <th width="4%">#</th>
                                    <th width="12%">Waktu Mutasi</th>
                                    <th width="10%">Kode Obat</th>
                                    <th width="24%">Nama Obat / BHP</th>
                                    <th width="8%" class="text-end">Jumlah</th>
                                    <th width="18%">Dari -> Ke</th>
                                    <th width="12%">Batch / Faktur</th>
                                    <th width="12%">Keterangan</th>
                                    <th width="6%" class="text-center">Aksi</th>
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
<!-- MODAL LOOKUP OBAT & STOK GUDANG ASAL       -->
<!-- ========================================== -->
<div class="modal fade" id="modalLookupObatMutasi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="ti ti-packages"></i>
                    <span>Pilih Obat di <strong id="lblModalGudangAsalText">-</strong></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="modal_search_input" placeholder="Ketik nama obat, kode barang, atau no batch..." autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1" onclick="loadObatGudangAsal()">
                            <i class="ti ti-search"></i><span>Cari Barang</span>
                        </button>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 460px; overflow-y: auto;">
                    <table class="table table-hover table-bordered table-striped align-middle mb-0" id="tbLookupObat">
                        <thead class="table-light sticky-top">
                            <tr class="small text-muted fw-bold">
                                <th width="12%">Kode</th>
                                <th width="38%">Nama Obat / BHP</th>
                                <th width="10%" class="text-center">Satuan</th>
                                <th width="14%">No. Batch</th>
                                <th width="12%" class="text-end text-danger">Stok Asal</th>
                                <th width="14%" class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="tbLookupObatBody">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Memuat data obat dari gudang asal...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <small class="text-muted me-auto">Hanya menampilkan obat yang memiliki saldo stok > 0 di gudang asal.</small>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let cartMutasi = [];
    let tableHistory = null;

    $(document).ready(function () {
        // Inisialisasi DataTable Riwayat saat tab di-klik
        $('#navRiwayatTab').on('shown.bs.tab', function () {
            if (!tableHistory) {
                initTableHistory();
            } else {
                tableHistory.ajax.reload();
            }
        });

        // Autocomplete search input di form mutasi
        let searchTimeout = null;
        $('#search_obat_input').on('keyup', function (e) {
            clearTimeout(searchTimeout);
            const val = $(this).val().trim();
            if (e.key === 'Enter') {
                openModalLookupObat(val);
                return;
            }
            if (val.length >= 3) {
                searchTimeout = setTimeout(() => {
                    openModalLookupObat(val);
                }, 400);
            }
        });

        $('#modal_search_input').on('keyup', function (e) {
            if (e.key === 'Enter') {
                loadObatGudangAsal();
            }
        });
    });

    function onGudangChanged() {
        const dari = $('#kd_bangsaldari').val();
        const ke = $('#kd_bangsalke').val();

        if (dari === ke) {
            Swal.fire({
                icon: 'warning',
                title: 'Lokasi Tidak Valid',
                text: 'Gudang asal dan gudang tujuan tidak boleh sama!'
            });
            return;
        }

        // Jika keranjang ada item, konfirmasi apakah mau dikosongkan
        if (cartMutasi.length > 0) {
            Swal.fire({
                title: 'Gudang Berubah',
                text: 'Mengubah lokasi gudang akan mengosongkan item di keranjang mutasi. Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kosongkan',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    clearCartMutasi();
                }
            });
        }
    }

    function tukarLokasi() {
        const dari = $('#kd_bangsaldari').val();
        const ke = $('#kd_bangsalke').val();

        $('#kd_bangsaldari').val(ke);
        $('#kd_bangsalke').val(dari);

        onGudangChanged();
    }

    // ==========================================
    // LOOKUP MODAL OBAT
    // ==========================================
    function openModalLookupObat(keyword = '') {
        const dari = $('#kd_bangsaldari').val();
        const ke = $('#kd_bangsalke').val();

        if (!dari || !ke || dari === ke) {
            Swal.fire('Perhatian', 'Pilih gudang asal dan tujuan yang berbeda terlebih dahulu!', 'warning');
            return;
        }

        const nmDari = $('#kd_bangsaldari option:selected').text();
        $('#lblModalGudangAsalText').text(nmDari);

        if (keyword) {
            $('#modal_search_input').val(keyword);
        }

        $('#modalLookupObatMutasi').modal('show');
        loadObatGudangAsal();
    }

    function loadObatGudangAsal() {
        const dari = $('#kd_bangsaldari').val();
        const ke = $('#kd_bangsalke').val();
        const q = $('#modal_search_input').val();

        $('#tbLookupObatBody').html('<tr><td colspan="6" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Memuat stok obat...</td></tr>');

        $.get(`{{ url('/farmasi/mutasi/get-stok-asal') }}`, {
            kd_bangsaldari: dari,
            kd_bangsalke: ke,
            q: q
        }).done((items) => {
            if (!items || items.length === 0) {
                $('#tbLookupObatBody').html('<tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada obat dengan stok > 0 di gudang asal</td></tr>');
                return;
            }

            let html = '';
            items.forEach((it) => {
                const encodedItem = encodeURIComponent(JSON.stringify(it));
                html += `
                    <tr>
                        <td class="font-monospace text-muted">${it.kode_brng}</td>
                        <td class="fw-bold text-dark">${it.nama_brng}</td>
                        <td class="text-center">${it.satuan || '-'}</td>
                        <td class="text-muted small">${it.no_batch ? it.no_batch : '<span class="text-secondary">-</span>'}</td>
                        <td class="text-end fw-bold text-danger">${numberFormat(it.stok_asal)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-xs btn-primary d-inline-flex align-items-center gap-1" onclick="pilihObatLookup('${encodedItem}')">
                                <i class="ti ti-plus"></i><span>Pilih</span>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#tbLookupObatBody').html(html);
        }).fail((err) => {
            $('#tbLookupObatBody').html('<tr><td colspan="6" class="text-center text-danger py-4">Gagal memuat data obat</td></tr>');
        });
    }

    function pilihObatLookup(encodedItem) {
        const item = JSON.parse(decodeURIComponent(encodedItem));

        // Cek apakah item + batch sudah ada di keranjang
        const existsIndex = cartMutasi.findIndex(c => c.kode_brng === item.kode_brng && c.no_batch === (item.no_batch || ''));
        if (existsIndex !== -1) {
            Swal.fire({
                icon: 'info',
                title: 'Sudah di List',
                text: `${item.nama_brng} (Batch: ${item.no_batch || '-'}) sudah ada di daftar mutasi!`
            });
            return;
        }

        // Tambah ke keranjang mutasi
        cartMutasi.push({
            kode_brng: item.kode_brng,
            nama_brng: item.nama_brng,
            satuan: item.satuan || '-',
            no_batch: item.no_batch || '',
            no_faktur: item.no_faktur || '',
            stok_asal: parseFloat(item.stok_asal || 0),
            stok_tujuan: parseFloat(item.stok_tujuan || 0),
            h_beli: parseFloat(item.h_beli || 0),
            jml: 1
        });

        renderCartTable();

        // Notifikasi toast kecil
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: `Ditambahkan: ${item.nama_brng}`,
            showConfirmButton: false,
            timer: 1200
        });
    }

    // ==========================================
    // RENDER KERANJANG MUTASI
    // ==========================================
    function renderCartTable() {
        const tbody = $('#cartTableBody');
        tbody.empty();

        if (cartMutasi.length === 0) {
            tbody.html(`
                <tr id="emptyCartRow">
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="ti ti-package-off fs-1 text-secondary mb-2 d-block"></i>
                        Belum ada obat yang dipilih. Gunakan kotak pencarian atau tombol <strong>Lookup Barang</strong> di atas untuk menambahkan obat dari gudang asal.
                    </td>
                </tr>
            `);
            $('#lblTotalItem').text('0');
            $('#btnClearCart').hide();
            return;
        }

        $('#btnClearCart').show();
        $('#lblTotalItem').text(cartMutasi.length);

        cartMutasi.forEach((item, index) => {
            const isOverStock = item.jml > item.stok_asal;
            const rowClass = isOverStock ? 'table-danger' : 'cart-row';
            const alertText = isOverStock ? '<div class="text-danger small mt-1"><i class="ti ti-alert-triangle"></i> Qty melebihi stok asal!</div>' : '';

            tbody.append(`
                <tr class="${rowClass}">
                    <td class="text-center text-muted">${index + 1}</td>
                    <td class="font-monospace text-muted small">${item.kode_brng}</td>
                    <td>
                        <div class="fw-bold text-dark">${item.nama_brng}</div>
                        ${item.no_faktur ? `<small class="text-muted">Faktur: ${item.no_faktur}</small>` : ''}
                    </td>
                    <td class="text-center">${item.satuan}</td>
                    <td class="text-muted small">${item.no_batch ? item.no_batch : '<span class="text-secondary">-</span>'}</td>
                    <td class="text-end fw-bold text-danger">${numberFormat(item.stok_asal)}</td>
                    <td class="text-end fw-bold text-success">${numberFormat(item.stok_tujuan)}</td>
                    <td>
                        <div class="input-group input-group-sm mx-auto" style="max-width: 140px;">
                            <button type="button" class="btn btn-outline-secondary px-2" onclick="stepQty(${index}, -1)">-</button>
                            <input type="number" step="any" min="0.01" max="${item.stok_asal}" class="form-control form-control-sm text-center fw-bold" 
                                   value="${item.jml}" onchange="updateQty(${index}, this.value)" onkeyup="updateQty(${index}, this.value)">
                            <button type="button" class="btn btn-outline-secondary px-2" onclick="stepQty(${index}, 1)">+</button>
                        </div>
                        ${alertText}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-xs btn-outline-danger" onclick="removeCartItem(${index})" title="Hapus dari List">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function updateQty(index, val) {
        if (!cartMutasi[index]) return;
        const q = parseFloat(val) || 0;
        cartMutasi[index].jml = q;
        
        // Re-render hanya jika overstock berubah status
        const isOver = q > cartMutasi[index].stok_asal;
        renderCartTable();
    }

    function stepQty(index, step) {
        if (!cartMutasi[index]) return;
        let newQty = (parseFloat(cartMutasi[index].jml) || 0) + step;
        if (newQty < 0.01) newQty = 0.01;
        cartMutasi[index].jml = newQty;
        renderCartTable();
    }

    function removeCartItem(index) {
        cartMutasi.splice(index, 1);
        renderCartTable();
    }

    function clearCartMutasi() {
        cartMutasi = [];
        renderCartTable();
    }

    function resetFormMutasi() {
        clearCartMutasi();
        $('#keterangan').val('Distribusi stok rutin');
        $('#search_obat_input').val('');
    }

    // ==========================================
    // SIMPAN TRANSAKSI MUTASI
    // ==========================================
    function simpanTransaksiMutasi() {
        const dari = $('#kd_bangsaldari').val();
        const ke = $('#kd_bangsalke').val();
        const tgl = $('#tanggal_mutasi').val();
        const ket = $('#keterangan').val().trim();

        if (!dari || !ke || dari === ke) {
            Swal.fire('Validasi Error', 'Gudang asal dan tujuan harus berbeda!', 'warning');
            return;
        }

        if (cartMutasi.length === 0) {
            Swal.fire('Keranjang Kosong', 'Pilih minimal satu obat untuk dimutasi!', 'warning');
            return;
        }

        if (!ket) {
            Swal.fire('Validasi Error', 'Keterangan mutasi wajib diisi!', 'warning');
            $('#keterangan').focus();
            return;
        }

        // Cek overstock
        let overItem = cartMutasi.find(c => c.jml > c.stok_asal || c.jml <= 0);
        if (overItem) {
            Swal.fire('Peringatan Stok', `Jumlah mutasi untuk "${overItem.nama_brng}" tidak valid (Stok asal: ${overItem.stok_asal}, Qty diminta: ${overItem.jml})!`, 'error');
            return;
        }

        const nmDari = $('#kd_bangsaldari option:selected').text();
        const nmKe = $('#kd_bangsalke option:selected').text();

        Swal.fire({
            title: 'Konfirmasi Mutasi Obat',
            html: `
                <div class="text-start">
                    <p class="mb-2">Anda akan memutasi <strong>${cartMutasi.length} macam obat</strong>:</p>
                    <ul class="mb-3 ps-3 small text-muted">
                        <li><strong>Dari:</strong> ${nmDari}</li>
                        <li><strong>Ke:</strong> ${nmKe}</li>
                        <li><strong>Waktu:</strong> ${tgl}</li>
                    </ul>
                    <p class="mb-0 text-secondary small">Stok di lokasi asal akan otomatis terpotong dan lokasi tujuan akan bertambah.</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan Mutasi!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2fb344'
        }).then((res) => {
            if (res.isConfirmed) {
                loadingAjax('Sedang memproses mutasi stok obat...');

                $.post(`{{ url('/farmasi/mutasi/store') }}`, {
                    kd_bangsaldari: dari,
                    kd_bangsalke: ke,
                    tanggal: tgl,
                    keterangan: ket,
                    items: cartMutasi
                }).done((response) => {
                    loadingAjax().close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showDenyButton: true,
                        denyButtonText: '🖨️ Cetak Bukti Mutasi',
                        denyButtonColor: '#206bc4',
                        confirmButtonText: 'Selesai'
                    }).then((result) => {
                        if (result.isDenied) {
                            cetakBuktiMutasi(response.kd_bangsaldari, response.kd_bangsalke, response.tanggal);
                        }
                    });

                    resetFormMutasi();
                    if (tableHistory) {
                        tableHistory.ajax.reload();
                    }
                }).fail((xhr) => {
                    loadingAjax().close();
                    alertErrorAjax(xhr);
                });
            }
        });
    }

    // ==========================================
    // RIWAYAT & AUDIT MUTASI (TAB 2)
    // ==========================================
    function initTableHistory() {
        tableHistory = $('#tbRiwayatMutasi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ url('/farmasi/mutasi/data') }}`,
                data: function (d) {
                    d.tgl_awal = $('#hist_tgl_awal').val();
                    d.tgl_akhir = $('#hist_tgl_akhir').val();
                    d.kd_bangsaldari = $('#hist_kd_bangsaldari').val();
                    d.kd_bangsalke = $('#hist_kd_bangsalke').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'tanggal', name: 'mutasibarang.tanggal' },
                { data: 'kode_brng', name: 'mutasibarang.kode_brng', className: 'font-monospace text-muted small' },
                { data: 'nama_brng', name: 'databarang.nama_brng', className: 'fw-bold text-dark' },
                { data: 'jml', name: 'mutasibarang.jml', className: 'text-end fw-bold text-primary' },
                { data: 'dari_ke', name: 'dari_ke', orderable: false, searchable: false },
                { data: 'batch_faktur', name: 'batch_faktur', orderable: false, searchable: false },
                { data: 'keterangan', name: 'mutasibarang.keterangan' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[1, 'desc']],
            language: {
                search: "Cari Riwayat:",
                lengthMenu: "Tampil _MENU_ baris",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ mutasi",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Sebelum"
                }
            }
        });
    }

    function reloadTableHistory() {
        if (tableHistory) {
            tableHistory.ajax.reload();
        } else {
            initTableHistory();
        }
    }

    function batalMutasiItem(kode_brng, dari, ke, tanggal, no_batch, no_faktur, jml) {
        Swal.fire({
            title: 'Batalkan Mutasi?',
            html: `
                <div class="text-start">
                    <p>Yakin ingin membatalkan transaksi mutasi barang ini?</p>
                    <ul class="text-muted small">
                        <li><strong>Kode:</strong> ${kode_brng}</li>
                        <li><strong>Jumlah:</strong> ${jml}</li>
                        <li><strong>Waktu:</strong> ${tanggal}</li>
                    </ul>
                    <p class="text-danger small mb-0">Stok di lokasi penerima akan ditarik kembali dan dikembalikan ke lokasi pengirim.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan!',
            confirmButtonColor: '#d63939',
            cancelButtonText: 'Tutup'
        }).then((res) => {
            if (res.isConfirmed) {
                loadingAjax('Memproses pembatalan mutasi...');

                $.post(`{{ url('/farmasi/mutasi/delete') }}`, {
                    kode_brng: kode_brng,
                    kd_bangsaldari: dari,
                    kd_bangsalke: ke,
                    tanggal: tanggal,
                    no_batch: no_batch,
                    no_faktur: no_faktur
                }).done((response) => {
                    loadingAjax().close();
                    Swal.fire('Berhasil', response.message, 'success');
                    if (tableHistory) {
                        tableHistory.ajax.reload();
                    }
                }).fail((xhr) => {
                    loadingAjax().close();
                    alertErrorAjax(xhr);
                });
            }
        });
    }

    function cetakBuktiMutasi(dari, ke, tgl) {
        const url = `{{ url('/farmasi/mutasi/print') }}?kd_bangsaldari=${encodeURIComponent(dari)}&kd_bangsalke=${encodeURIComponent(ke)}&tanggal=${encodeURIComponent(tgl)}`;
        window.open(url, '_blank', 'width=850,height=700');
    }

    // Helper format angka
    function numberFormat(val) {
        return new Intl.NumberFormat('id-ID').format(val);
    }
</script>
@endpush

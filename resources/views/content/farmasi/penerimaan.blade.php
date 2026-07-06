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
                            <div class="row">
                                <!-- Header Transaksi -->
                                <div class="col-md-12 mb-3">
                                    <div class="card bg-light-lt">
                                        <div class="card-body">
                                            <div class="row row-cards">
                                                <div class="col-md-3">
                                                    <label class="form-label required">No. Faktur / Penerimaan</label>
                                                    <input type="text" class="form-control" name="no_faktur" id="no_faktur" placeholder="FKT-XXXXX" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">No. Order (PO)</label>
                                                    <input type="text" class="form-control" name="no_order" id="no_order" placeholder="Optional">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Supplier</label>
                                                    <select class="form-select select-suplier" name="kode_suplier" id="kode_suplier" style="width: 100%" required>
                                                        <option value="">-- Pilih Supplier --</option>
                                                        @foreach($suplier as $sup)
                                                            <option value="{{ $sup->kode_suplier }}">{{ $sup->nama_suplier }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label required">Lokasi Gudang/Depo</label>
                                                    <select class="form-select select-bangsal" name="kd_bangsal" id="kd_bangsal" style="width: 100%" required>
                                                        <option value="">-- Pilih Gudang --</option>
                                                        @foreach($bangsal as $bg)
                                                            <option value="{{ $bg->kd_bangsal }}">{{ $bg->nm_bangsal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label required">Tanggal Faktur</label>
                                                    <input type="date" class="form-control" name="tgl_faktur" id="tgl_faktur" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </div>
                                            <div class="row row-cards mt-2">
                                                <div class="col-md-2">
                                                    <label class="form-label">Tanggal Pesan</label>
                                                    <input type="date" class="form-control" name="tgl_pesan" id="tgl_pesan" value="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Tanggal Jatuh Tempo</label>
                                                    <input type="date" class="form-control" name="tgl_tempo" id="tgl_tempo" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">PPN (%)</label>
                                                    <input type="number" class="form-control" name="ppn_percent" id="ppn_percent" value="11" min="0" step="0.1">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Meterai (Rp)</label>
                                                    <input type="number" class="form-control" name="meterai" id="meterai_header" value="0" min="0">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Potongan Faktur (Rp)</label>
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
                                                <h4 class="card-title text-success mb-0"><i class="ti ti-shopping-cart me-2"></i> Keranjang Penerimaan Barang</h4>
                                            </div>
                                            
                                            <!-- Batch Search Dropdown -->
                                            <div class="mb-4 bg-light-lt p-3 rounded border">
                                                <label class="form-label fw-bold text-blue mb-1"><i class="ti ti-search me-1"></i> Cari & Tambah Obat/BHP ke Keranjang (Ketik nama obat, lalu tekan Enter):</label>
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
                                                            <th style="width: 8%">Satuan</th>
                                                            <th style="width: 12%">No. Batch</th>
                                                            <th style="width: 13%">Expired</th>
                                                            <th class="text-end" style="width: 10%">Harga Beli</th>
                                                            <th class="text-center" style="width: 10%">Qty</th>
                                                            <th class="text-end" style="width: 8%">Disc %</th>
                                                            <th class="text-end" style="width: 10%">Total</th>
                                                            <th class="text-center" style="width: 11%">Harga Jual</th>
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
                                                        <span class="h3 mb-0">Grand Total :</span>
                                                        <span class="h2 text-success mb-0" id="lblGrandTotal">Rp 0</span>
                                                    </div>
                                                    
                                                    <button type="button" class="btn btn-success w-100 btn-lg" onclick="savePenerimaan()">
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
                        <div class="table-responsive">
                            <table class="table table-striped table-hover nowrap w-100" id="tbPenerimaan">
                                <thead>
                                    <tr>
                                        <th>No. Faktur</th>
                                        <th>No. Order</th>
                                        <th>Supplier</th>
                                        <th>Gudang</th>
                                        <th>Tgl Faktur</th>
                                        <th>Tgl Tempo</th>
                                        <th>Subtotal</th>
                                        <th>PPN</th>
                                        <th>Grand Total</th>
                                        <th>NIP Petugas</th>
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
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-coin me-2 text-primary"></i> Set Harga Jual Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_item_index">
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

    <!-- Modal for displaying receipt details -->
    <div class="modal fade" id="modalDetailPenerimaan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-file-text me-2 text-primary"></i> Detail Item Penerimaan (<span id="detail_faktur_title"></span>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered align-middle mb-0" id="tbDetailItems">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Obat/BHP</th>
                                    <th>Satuan</th>
                                    <th>No. Batch</th>
                                    <th>Expired</th>
                                    <th class="text-end">Harga Beli</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Disc %</th>
                                    <th class="text-end">Total</th>
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
@endsection

@push('script')
    <script>
        let cartItems = [];

        $(document).ready(() => {
            // Initialize select2
            $('.select-suplier').select2();
            $('.select-bangsal').select2();

            // Setup select2 data barang search using utility function
            selectDataBarang($('#input_kode_brng_batch'), 'body');

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

                // Add item to cartItems
                cartItems.push({
                    kode_brng: itemCode,
                    nama_brng: selectData.text,
                    kode_sat: detail.kode_sat || '-',
                    satuan_nama: detail.satuan ? detail.satuan.satuan : '-',
                    jumlah: 1,
                    h_beli: detail.h_beli || 0,
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
                });

                renderCartTable();
                
                // Reset select2 and keep it open/focused
                $(this).val(null).trigger('change');
                setTimeout(() => {
                    $(this).select2('open');
                }, 100);
            });

            // Update summaries whenever input changes
            $('#ppn_percent, #meterai, #potongan_header').on('input', function() {
                calculateSummary();
            });

            // Load DataTable on tab shown
            $('#btnTabHistory').on('shown.bs.tab', function () {
                renderTableHistory();
            });
        });

        // Update cart item property dynamically
        function updateCartItem(index, key, val) {
            if (key === 'jumlah') {
                cartItems[index].jumlah = parseFloat(val) || 0;
            } else if (key === 'h_beli') {
                cartItems[index].h_beli = parseFloat(val) || 0;
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

                tbody.append(`
                    <tr>
                        <td>${item.kode_brng}</td>
                        <td class="fw-bold text-dark">${item.nama_brng}</td>
                        <td>${item.satuan_nama}</td>
                        <td style="width: 150px;">
                            <input type="text" class="form-control form-control-sm" value="${item.no_batch || ''}" placeholder="No. Batch" oninput="updateCartItem(${index}, 'no_batch', this.value)">
                        </td>
                        <td style="width: 160px;">
                            <input type="date" class="form-control form-control-sm" value="${item.kadaluarsa}" onchange="updateCartItem(${index}, 'kadaluarsa', this.value)">
                        </td>
                        <td style="width: 130px;">
                            <input type="number" class="form-control form-control-sm text-end" value="${item.h_beli}" min="0" oninput="updateCartItem(${index}, 'h_beli', this.value)">
                        </td>
                        <td style="width: 120px;">
                            <input type="number" class="form-control form-control-sm text-center" value="${item.jumlah}" min="1" oninput="updateCartItem(${index}, 'jumlah', this.value)">
                        </td>
                        <td style="width: 100px;">
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
            const meterai = parseFloat($('#meterai').val()) || 0;

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
            const meterai = parseFloat($('#meterai').val()) || 0;
            const tagihan = total2 + ppn + meterai;

            const data = {
                _token: "{{ csrf_token() }}",
                no_faktur: $('#no_faktur').val(),
                no_order: $('#no_order').val(),
                kode_suplier: $('#kode_suplier').val(),
                kd_bangsal: $('#kd_bangsal').val(),
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

            loadingAjax('Sedang memproses penerimaan obat...');

            $.ajax({
                url: "{{ url('/penerimaan/store') }}",
                type: 'POST',
                data: data,
                success: (response) => {
                    showToast(response.message);
                    resetAllForm();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal menyimpan transaksi penerimaan', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        // Reset the whole header and cart fields
        function resetAllForm() {
            $('#formPenerimaan').trigger('reset');
            $('.select-suplier').val('').trigger('change');
            $('.select-bangsal').val('').trigger('change');
            cartItems = [];
            renderCartTable();
            $('#input_kode_brng_batch').val(null).trigger('change');
        }

        // Load receipts list
        function renderTableHistory() {
            $('#tbPenerimaan').DataTable({
                responsive: true,
                serverSide: false,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/penerimaan/data') }}",
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
                    { data: 'nip' },
                    {
                        data: null,
                        orderable: false,
                        render: (data) => {
                            return `
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetailPenerimaan('${data.no_faktur}')">
                                        <i class="ti ti-eye me-1"></i> Detail
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteHistory('${data.no_faktur}')">
                                        <i class="ti ti-trash me-1"></i> Batal / Hapus
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });
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
                html: `Apakah Anda yakin ingin membatalkan transaksi faktur <b>${no_faktur}</b>?<br><b class="text-danger">Tindakan ini akan mengurangi kembali stok obat terkait!</b>`,
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
                            $('#tbPenerimaan').DataTable().ajax.reload(null, false);
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON.message || 'Gagal membatalkan transaksi', 'error');
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
        /* Sembunyikan spin button (up/down arrow) untuk input number di tabel keranjang */
        #tableCart input[type="number"]::-webkit-outer-spin-button,
        #tableCart input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        #tableCart input[type="number"] {
            -moz-appearance: textfield;
        }
        /* Kurangi padding horizontal agar angka digit banyak tidak terpotong */
        #tableCart input.form-control-sm {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
    </style>
@endpush

@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-input" class="nav-link active" data-bs-toggle="tab" role="tab">
                            <i class="ti ti-plus me-2"></i> Input Stok Opname (Batch)
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-history" class="nav-link" data-bs-toggle="tab" role="tab" id="btnTabHistory">
                            <i class="ti ti-history me-2"></i> Riwayat Stok Opname
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Tab Input Batch -->
                    <div class="tab-pane fade show active" id="tab-input" role="tabpanel">
                        <form id="formOpnameBatch" onsubmit="event.preventDefault(); saveBatchOpname();">
                            @csrf
                            <div class="row">
                                <!-- Header Penyesuaian -->
                                <div class="col-md-12 mb-3">
                                    <div class="card bg-light-lt border-0 shadow-none">
                                        <div class="card-body">
                                            <div class="row row-cards align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label required">Tanggal Opname</label>
                                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label required">Lokasi Gudang/Depo</label>
                                                    <select class="form-select select-bangsal" name="kd_bangsal" id="kd_bangsal" style="width: 100%" required>
                                                        <option value="">-- Pilih Gudang --</option>
                                                        @foreach($bangsal as $bg)
                                                            <option value="{{ $bg->kd_bangsal }}">{{ $bg->nm_bangsal }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label required">Keterangan/Catatan</label>
                                                    <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan opname..." required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-primary w-100" id="btnLoadStok" onclick="loadStokLokasi()">
                                                        <i class="ti ti-refresh me-2"></i> Muat Stok Lokasi
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Batch Items List -->
                                <div class="col-md-12">
                                    <div class="card" style="min-height: 45vh;">
                                         <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                                             <h3 class="card-title text-green"><i class="ti ti-checklist me-2"></i> Daftar Penyesuaian Stok <span id="lblSelectedLokasi" class="text-secondary fw-normal fs-4 ms-1"></span></h3>
                                             
                                             <div class="d-flex align-items-center gap-2">
                                                 <!-- Search Filter -->
                                                 <input type="text" id="filter_search" class="form-control form-control-sm" placeholder="Cari obat di list..." style="width: 180px;">
                                                 
                                                 <!-- Stock Filter -->
                                                 <span class="text-secondary text-nowrap ms-2">Filter Stok:</span>
                                                 <select class="form-select form-select-sm" id="filter_stok" style="width: 140px;">
                                                     <option value="semua">Semua Barang</option>
                                                     <option value="ada">Ada Stok</option>
                                                     <option value="kosong">Stok Kosong</option>
                                                 </select>
                                             </div>
                                         </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                                <table class="table table-hover table-striped table-bordered align-middle mb-0" id="tableOpnameItems">
                                                    <thead class="sticky-top bg-white shadow-sm" style="z-index: 10;">
                                                        <tr>
                                                            <th style="width: 5%">No</th>
                                                            <th style="width: 12%">Kode Obat</th>
                                                            <th style="width: 28%">Nama Obat/BHP</th>
                                                            <th style="width: 10%">Satuan</th>
                                                            <th style="width: 10%">Batch</th>
                                                            <th style="width: 10%">Faktur</th>
                                                            <th class="text-end" style="width: 8%">Stok Sistem</th>
                                                            <th class="text-center" style="width: 10%">Stok Fisik (Real)</th>
                                                            <th class="text-end" style="width: 8%">Selisih</th>
                                                            <th class="text-center" style="width: 5%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="10" class="text-center text-secondary py-4">Silakan pilih Lokasi Gudang dan klik "Muat Stok Lokasi" untuk menampilkan daftar barang</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                            <span class="text-secondary" id="lblInfoCount">Total item: 0</span>
                                            <button type="button" class="btn btn-success" onclick="saveBatchOpname()">
                                                <i class="ti ti-device-floppy me-2"></i> Simpan Batch Stok Opname
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Riwayat -->
                    <div class="tab-pane fade" id="tab-history" role="tabpanel">
                        <!-- Filters for History -->
                        <div class="card bg-light-lt border-0 shadow-none mb-3">
                            <div class="card-body py-3">
                                <div class="row row-cards align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Awal</label>
                                        <input type="date" class="form-control" id="history_tgl_awal" value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="history_tgl_akhir" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Gudang/Depo</label>
                                        <select class="form-select select-bangsal" id="history_kd_bangsal" style="width: 100%">
                                            <option value="">Semua Lokasi</option>
                                            @foreach($bangsal as $bg)
                                                <option value="{{ $bg->kd_bangsal }}">{{ $bg->nm_bangsal }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary w-100" onclick="renderTableHistory()">
                                            <i class="ti ti-filter me-2"></i> Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover nowrap w-100 align-middle" id="tbOpnameHistory">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Gudang</th>
                                        <th>Kode</th>
                                        <th>Nama Obat/BHP</th>
                                        <th>Batch</th>
                                        <th>Faktur</th>
                                        <th class="text-end">Stok Sistem</th>
                                        <th class="text-end">Stok Fisik</th>
                                        <th class="text-end">Selisih</th>
                                        <th class="text-end">Harga Beli</th>
                                        <th>Keterangan</th>
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
@endsection

@push('script')
    <script>
        let opnameItems = [];

        $(document).ready(() => {
            // Init select2 inputs
            $('.select-bangsal').select2();

            // Quick search & stock filter events
            $('#filter_search').on('keyup input', filterTable);
            $('#filter_stok').on('change', filterTable);

            // Load DataTable on tab shown
            $('#btnTabHistory').on('shown.bs.tab', function () {
                renderTableHistory();
            });
        });

        // Client-side quick filter logic
        function filterTable() {
            const searchVal = $('#filter_search').val().toLowerCase();
            const stockFilter = $('#filter_stok').val();

            $('#tableOpnameItems tbody tr').each(function () {
                const row = $(this);
                // System stock is in the 7th column (index 6, td:nth-child(7))
                const systemStockText = row.find('td:nth-child(7)').text().trim();
                const systemStock = parseFloat(systemStockText) || 0;
                
                const textMatches = row.text().toLowerCase().indexOf(searchVal) > -1;
                
                let stockMatches = true;
                if (stockFilter === 'ada') {
                    stockMatches = systemStock > 0;
                } else if (stockFilter === 'kosong') {
                    stockMatches = systemStock === 0;
                }
                
                row.toggle(textMatches && stockMatches);
            });
        }

        // Load stocks from database based on selected warehouse location
        function loadStokLokasi() {
            const kd_bangsal = $('#kd_bangsal').val();
            const tanggal = $('#tanggal').val();
            const keterangan = $('#keterangan').val() ? $('#keterangan').val().trim() : '';

            if (!kd_bangsal) {
                showToast('Silakan pilih Lokasi Gudang terlebih dahulu', 'warning');
                return;
            }
            if (!tanggal) {
                showToast('Silakan pilih Tanggal opname terlebih dahulu', 'warning');
                return;
            }
            if (!keterangan) {
                showToast('Silakan isi Keterangan/Catatan terlebih dahulu', 'warning');
                return;
            }

            loadingAjax('Memuat data stok lokasi...');

            $.ajax({
                url: "{{ url('/opname/get-items') }}",
                type: 'GET',
                data: { kd_bangsal: kd_bangsal },
                success: (response) => {
                    const bangsalName = $('#kd_bangsal option:selected').text();
                    $('#lblSelectedLokasi').text(`- ${bangsalName}`);
                    $('#filter_search').val('');
                    $('#filter_stok').val('semua');
                    opnameItems = response.map(item => ({
                        kode_brng: item.kode_brng,
                        nama_brng: item.nama_brng,
                        satuan: item.satuan || '-',
                        no_batch: item.no_batch || '',
                        no_faktur: item.no_faktur || '',
                        stok: parseFloat(item.stok) || 0,
                        real: parseFloat(item.stok) || 0, // default real to system stock
                        h_beli: parseFloat(item.h_beli) || 0
                    }));
                    
                    renderOpnameTable();
                    if (opnameItems.length === 0) {
                        showToast('Tidak ada stok aktif di lokasi ini. Anda bisa menambahkan obat secara manual.', 'info');
                    } else {
                        showToast(`Berhasil memuat ${opnameItems.length} item obat`);
                    }
                },
                error: (xhr) => {
                    showToast('Gagal memuat stok lokasi', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        // Render current opname list to DOM table
        function renderOpnameTable() {
            const tbody = $('#tableOpnameItems tbody');
            tbody.empty();

            $('#lblInfoCount').text(`Total item: ${opnameItems.length}`);

            if (opnameItems.length === 0) {
                tbody.append('<tr><td colspan="10" class="text-center text-secondary py-4">Daftar item kosong. Pilih Gudang untuk memuat stok atau tambah obat secara manual.</td></tr>');
                return;
            }

            opnameItems.forEach((item, index) => {
                const selisih = item.real - item.stok;
                let selisihClass = '';
                let selisihPrefix = '';

                if (selisih > 0) {
                    selisihClass = 'text-success fw-bold';
                    selisihPrefix = '+';
                } else if (selisih < 0) {
                    selisihClass = 'text-danger fw-bold';
                }

                tbody.append(`
                    <tr>
                        <td class="text-secondary">${index + 1}</td>
                        <td class="fw-medium">${item.kode_brng}</td>
                        <td>
                            <div class="fw-semibold">${item.nama_brng}</div>
                        </td>
                        <td>${item.satuan}</td>
                        <td><span class="badge bg-secondary-lt">${item.no_batch || '-'}</span></td>
                        <td><span class="text-secondary small">${item.no_faktur || '-'}</span></td>
                        <td class="text-end fw-semibold">${item.stok}</td>
                        <td class="text-center">
                            <input type="number" 
                                   class="form-control form-control-sm text-center mx-auto" 
                                   style="width: 90px;" 
                                   value="${item.real}" 
                                   min="0" 
                                   oninput="updateRealStock(this, ${index})">
                        </td>
                        <td class="text-end ${selisihClass}" id="selisih-${index}">
                            ${selisihPrefix}${selisih}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-ghost-danger" onclick="removeItemFromList(${index})">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
        }

        // Callback when user types inside physical stock inputs
        function updateRealStock(input, index) {
            const val = parseFloat(input.value) || 0;
            opnameItems[index].real = val;

            // Recalculate difference
            const item = opnameItems[index];
            const selisih = val - item.stok;
            
            const cell = $(`#selisih-${index}`);
            cell.removeClass('text-success text-danger fw-bold');
            
            let prefix = '';
            if (selisih > 0) {
                cell.addClass('text-success fw-bold');
                prefix = '+';
            } else if (selisih < 0) {
                cell.addClass('text-danger fw-bold');
            }

            cell.text(prefix + selisih);
        }

        // Remove item from UI list
        function removeItemFromList(index) {
            opnameItems.splice(index, 1);
            renderOpnameTable();
        }

        // Save entire batch opname to database
        function saveBatchOpname() {
            const tanggal = $('#tanggal').val();
            const kd_bangsal = $('#kd_bangsal').val();
            const keterangan = $('#keterangan').val() ? $('#keterangan').val().trim() : '';

            if (!kd_bangsal) {
                showToast('Pilih Gudang terlebih dahulu', 'warning');
                return;
            }
            if (!tanggal) {
                showToast('Pilih Tanggal opname', 'warning');
                return;
            }
            if (!keterangan) {
                showToast('Keterangan/Catatan wajib diisi', 'warning');
                return;
            }

            // Filter items to only send ones with modifications/discrepancies
            const adjustedItems = opnameItems.filter(item => (item.real - item.stok) !== 0);

            if (adjustedItems.length === 0) {
                showToast('Tidak ada penyesuaian stok (selisih) yang dilakukan', 'info');
                return;
            }

            Swal.fire({
                title: 'Simpan Batch Opname?',
                html: `Apakah Anda yakin ingin menyimpan penyesuaian stok untuk <b>${adjustedItems.length}</b> item obat?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2fb344',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Sedang memproses penyesuaian stok massal...');

                    $.ajax({
                        url: "{{ url('/opname/store') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            tanggal: tanggal,
                            kd_bangsal: kd_bangsal,
                            keterangan: keterangan,
                            items: adjustedItems
                        },
                        success: (response) => {
                            showToast(response.message);
                            opnameItems = [];
                            $('#keterangan').val('');
                            $('#filter_search').val('');
                            $('#filter_stok').val('semua');
                            $('#lblSelectedLokasi').text('');
                            renderOpnameTable();
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON.message || 'Gagal menyimpan stok opname', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

        // Load opname history list with filters
        function renderTableHistory() {
            const tgl_awal = $('#history_tgl_awal').val();
            const tgl_akhir = $('#history_tgl_akhir').val();
            const kd_bangsal = $('#history_kd_bangsal').val();

            $('#tbOpnameHistory').DataTable({
                responsive: true,
                serverSide: false,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/opname/data') }}",
                    type: "GET",
                    data: {
                        tgl_awal: tgl_awal,
                        tgl_akhir: tgl_akhir,
                        kd_bangsal: kd_bangsal
                    },
                    dataSrc: ""
                },
                columns: [
                    { data: 'tanggal' },
                    { data: 'bangsal.nm_bangsal', defaultContent: '-' },
                    { data: 'kode_brng' },
                    { data: 'barang.nama_brng', defaultContent: '-' },
                    { data: 'no_batch', defaultContent: '-' },
                    { data: 'no_faktur', defaultContent: '-' },
                    { data: 'stok', className: 'text-end' },
                    { data: 'real', className: 'text-end' },
                    { 
                        data: 'selisih', 
                        className: 'text-end fw-bold',
                        render: (data) => {
                            if (data > 0) return `<span class="text-success">+${data}</span>`;
                            if (data < 0) return `<span class="text-danger">${data}</span>`;
                            return `<span>${data}</span>`;
                        }
                    },
                    { 
                        data: 'h_beli', 
                        className: 'text-end',
                        render: (data) => 'Rp ' + formatRupiah(data)
                    },
                    { data: 'keterangan', defaultContent: '-' },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: (data) => {
                            return `
                                <button type="button" class="btn btn-sm btn-ghost-danger" onclick="deleteOpnameHistory('${data.kode_brng}', '${data.tanggal}', '${data.kd_bangsal}', '${data.no_batch}', '${data.no_faktur}')">
                                    <i class="ti ti-trash me-1"></i> Hapus
                                </button>
                            `;
                        }
                    }
                ]
            });
        }

        // Send AJAX request to delete opname record and restore stock
        function deleteOpnameHistory(kode_brng, tanggal, kd_bangsal, no_batch, no_faktur) {
            Swal.fire({
                title: 'Hapus Riwayat Opname?',
                html: `Apakah Anda yakin ingin menghapus catatan opname ini?<br><b class="text-danger">Tindakan ini akan mengembalikan stok di gudang ke nilai sebelum opname dilakukan!</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus catatan opname...');

                    $.ajax({
                        url: "{{ url('/opname/delete') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_brng: kode_brng,
                            tanggal: tanggal,
                            kd_bangsal: kd_bangsal,
                            no_batch: no_batch,
                            no_faktur: no_faktur
                        },
                        success: (response) => {
                            showToast(response.message);
                            $('#tbOpnameHistory').DataTable().ajax.reload(null, false);
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON.message || 'Gagal menghapus riwayat opname', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

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
    </style>
@endpush

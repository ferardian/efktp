@extends('layout')

@push('style')
<style>
    .laporan-penjualan-page,
    .laporan-penjualan-page .card,
    .laporan-penjualan-page .form-label,
    .laporan-penjualan-page label,
    .laporan-penjualan-page .form-control,
    .laporan-penjualan-page .form-select,
    .laporan-penjualan-page select,
    .laporan-penjualan-page input {
        color: #1e293b !important;
    }

    .laporan-penjualan-page .form-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 4px !important;
    }

    .laporan-penjualan-page .form-control,
    .laporan-penjualan-page .form-select {
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 500 !important;
    }

    .laporan-penjualan-page .form-select option {
        color: #1e293b !important;
        background-color: #ffffff !important;
    }

    /* Summary Card Styling */
    .summary-card {
        border-radius: 8px;
        transition: transform 0.15s ease-in-out;
    }
    .summary-card:hover {
        transform: translateY(-2px);
    }
    .summary-card .summary-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .summary-card .summary-value {
        font-size: 20px;
        font-weight: 800;
        line-height: 1.2;
    }

    /* Table styling */
    #tbLaporanItem th {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    #tbLaporanItem td {
        color: #1e293b !important;
        font-size: 11px !important;
    }
    #tbLaporanItem tfoot th {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 11.5px !important;
    }

    .btn-sumber-group .btn-check:checked + .btn {
        font-weight: 700 !important;
        box-shadow: 0 0 0 2px rgba(32, 107, 196, 0.25);
    }
</style>
@endpush

@section('body')
    <div class="container-fluid laporan-penjualan-page">
        <!-- Header Page -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h3 class="card-title text-primary fw-bold mb-1">
                        <i class="ti ti-report-analytics me-2 text-primary fs-2"></i> Laporan Rekap Penjualan Obat Per Item
                    </h3>
                    <div class="text-muted small">
                        Rekapitulasi total kuantitas, nilai modal (HPP), omzet penjualan, dan laba kotor farmasi per item obat.
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-success fw-bold" id="btnExportExcel">
                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel (CSV)
                    </button>
                    <button type="button" class="btn btn-outline-primary fw-bold" id="btnPrintLaporan">
                        <i class="ti ti-printer me-1"></i> Cetak Laporan
                    </button>
                </div>
            </div>

            <!-- Filter Toolbar -->
            <div class="card-body bg-light border-bottom p-3">
                <form id="formFilterLaporan">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control form-control-sm" id="tgl_awal" name="tgl_awal" value="{{ $tglAwal }}">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control form-control-sm" id="tgl_akhir" name="tgl_akhir" value="{{ $tglAkhir }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label">Sumber Transaksi</label>
                            <select class="form-select form-select-sm" id="sumber" name="sumber">
                                <option value="semua" selected>Semua Sumber (Konsolidasi)</option>
                                <option value="bebas">Hanya Penjualan Bebas (Apotek)</option>
                                <option value="resep">Hanya Resep Pasien (Ralan & Ranap)</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label">Jenis / Golongan Obat</label>
                            <select class="form-select form-select-sm" id="kdjns" name="kdjns">
                                <option value="">-- Semua Jenis Obat --</option>
                                @foreach($jenisList as $jns)
                                    <option value="{{ $jns->kdjns }}">{{ $jns->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill" id="btnTampilkan">
                                <i class="ti ti-search me-1"></i> Tampilkan
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnResetFilter" title="Reset">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards Row -->
        <div class="row g-3 mb-3">
            <div class="col-md-4 col-xl-2 col-sm-6">
                <div class="card summary-card border-0 shadow-sm bg-white border-start border-4 border-primary">
                    <div class="card-body p-3">
                        <div class="text-primary summary-title mb-1">
                            <i class="ti ti-packages me-1"></i> Item Terjual
                        </div>
                        <div class="summary-value text-dark" id="lbl_total_items">0</div>
                        <div class="text-muted small mt-1">Jenis Obat Berbeda</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 col-sm-6">
                <div class="card summary-card border-0 shadow-sm bg-white border-start border-4 border-info">
                    <div class="card-body p-3">
                        <div class="text-info summary-title mb-1">
                            <i class="ti ti-pill me-1"></i> Total Kuantitas
                        </div>
                        <div class="summary-value text-dark" id="lbl_grand_qty">0</div>
                        <div class="text-muted small mt-1" id="lbl_qty_breakdown">Bebas: 0 | Resep: 0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2 col-sm-6">
                <div class="card summary-card border-0 shadow-sm bg-white border-start border-4 border-secondary">
                    <div class="card-body p-3">
                        <div class="text-secondary summary-title mb-1">
                            <i class="ti ti-archive me-1"></i> Total Modal (HPP)
                        </div>
                        <div class="summary-value text-dark" id="lbl_grand_hpp">Rp 0</div>
                        <div class="text-muted small mt-1">Beban Pokok Obat</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-3 col-sm-6">
                <div class="card summary-card border-0 shadow-sm bg-white border-start border-4 border-success">
                    <div class="card-body p-3">
                        <div class="text-success summary-title mb-1">
                            <i class="ti ti-cash me-1"></i> Total Penjualan (Omzet)
                        </div>
                        <div class="summary-value text-success" id="lbl_grand_jual">Rp 0</div>
                        <div class="text-muted small mt-1">Pendapatan Bruto Farmasi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-3 col-sm-12">
                <div class="card summary-card border-0 shadow-sm bg-white border-start border-4 border-teal">
                    <div class="card-body p-3">
                        <div class="text-teal summary-title mb-1">
                            <i class="ti ti-trending-up me-1"></i> Laba Kotor & Margin
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="summary-value text-primary" id="lbl_grand_laba">Rp 0</div>
                            <span class="badge bg-teal-lt fw-bold fs-4" id="lbl_avg_margin">0%</span>
                        </div>
                        <div class="text-muted small mt-1">Selisih Omzet & HPP Modal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card border shadow-sm bg-white">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle nowrap w-100" id="tbLaporanItem">
                        <thead>
                            <tr>
                                <th width="30" class="text-center">No</th>
                                <th>Kode</th>
                                <th>Nama Obat & Alkes</th>
                                <th class="text-center">Satuan</th>
                                <th>Jenis</th>
                                <th class="text-center col-qty-bebas">Qty Bebas</th>
                                <th class="text-center col-qty-resep">Qty Resep</th>
                                <th class="text-center fw-bold">Total Qty</th>
                                <th class="text-end">HPP Rata2</th>
                                <th class="text-end">Total HPP</th>
                                <th class="text-end">Total Penjualan</th>
                                <th class="text-end">Laba Kotor</th>
                                <th class="text-center">Margin</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end fw-bold">TOTAL KESELURUHAN:</th>
                                <th class="text-center fw-bold col-qty-bebas" id="foot_qty_bebas">0</th>
                                <th class="text-center fw-bold col-qty-resep" id="foot_qty_resep">0</th>
                                <th class="text-center fw-bold" id="foot_total_qty">0</th>
                                <th class="text-end">-</th>
                                <th class="text-end fw-bold text-dark" id="foot_total_hpp">Rp 0</th>
                                <th class="text-end fw-bold text-success" id="foot_total_jual">Rp 0</th>
                                <th class="text-end fw-bold text-primary" id="foot_total_laba">Rp 0</th>
                                <th class="text-center fw-bold" id="foot_avg_margin">0%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let tbLaporan = null;

        // Helper format rupiah
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) return '0';
            const number = Math.round(Number(angka));
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatAngka(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) return '0';
            return Number(angka).toLocaleString('id-ID');
        }

        $(document).ready(function() {
            initTableLaporan();

            $('#formFilterLaporan').on('submit', function(e) {
                e.preventDefault();
                updateColumnVisibility();
                if (tbLaporan) {
                    tbLaporan.ajax.reload();
                }
            });

            $('#btnResetFilter').on('click', function() {
                $('#tgl_awal').val('{{ date("Y-m-01") }}');
                $('#tgl_akhir').val('{{ date("Y-m-d") }}');
                $('#sumber').val('semua');
                $('#kdjns').val('');
                updateColumnVisibility();
                if (tbLaporan) {
                    tbLaporan.ajax.reload();
                }
            });

            $('#sumber').on('change', function() {
                updateColumnVisibility();
            });

            $('#btnPrintLaporan').on('click', function() {
                const tgl_awal = $('#tgl_awal').val();
                const tgl_akhir = $('#tgl_akhir').val();
                const sumber = $('#sumber').val();
                const kdjns = $('#kdjns').val();

                const url = `{{ url('/farmasi/laporan-penjualan-item/print') }}?tgl_awal=${tgl_awal}&tgl_akhir=${tgl_akhir}&sumber=${sumber}&kdjns=${kdjns}`;
                window.open(url, '_blank');
            });

            $('#btnExportExcel').on('click', function() {
                const tgl_awal = $('#tgl_awal').val();
                const tgl_akhir = $('#tgl_akhir').val();
                const sumber = $('#sumber').val();
                const kdjns = $('#kdjns').val();

                const url = `{{ url('/farmasi/laporan-penjualan-item/export') }}?tgl_awal=${tgl_awal}&tgl_akhir=${tgl_akhir}&sumber=${sumber}&kdjns=${kdjns}`;
                window.location.href = url;
            });
        });

        function updateColumnVisibility() {
            if (!tbLaporan) return;
            const sumber = $('#sumber').val();
            const showBreakdown = (sumber === 'semua');

            tbLaporan.column(5).visible(showBreakdown);
            tbLaporan.column(6).visible(showBreakdown);

            if (showBreakdown) {
                $('.col-qty-bebas, .col-qty-resep').show();
            } else {
                $('.col-qty-bebas, .col-qty-resep').hide();
            }
        }

        function initTableLaporan() {
            tbLaporan = $('#tbLaporanItem').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 25,
                language: {
                    emptyTable: 'Tidak ada transaksi penjualan obat pada periode dan filter ini',
                    search: 'Cari Obat:',
                    searchPlaceholder: 'Ketik nama atau kode obat...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    loadingRecords: 'Memuat data...',
                    processing: '<div class="spinner-border spinner-border-sm text-primary"></div> Memuat data laporan...',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ item',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                    infoFiltered: '(disaring dari _MAX_ total item)',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: '&raquo;',
                        previous: '&laquo;'
                    }
                },
                ajax: {
                    url: `{{ url('/farmasi/laporan-penjualan-item/data') }}`,
                    data: function(d) {
                        d.tgl_awal = $('#tgl_awal').val();
                        d.tgl_akhir = $('#tgl_akhir').val();
                        d.sumber = $('#sumber').val();
                        d.kdjns = $('#kdjns').val();
                    },
                    dataSrc: function(json) {
                        if (json.summary) {
                            const s = json.summary;
                            $('#lbl_total_items').text(formatAngka(s.total_items));
                            $('#lbl_grand_qty').text(formatAngka(s.grand_total_qty));
                            $('#lbl_qty_breakdown').text(`Bebas: ${formatAngka(s.grand_qty_bebas)} | Resep: ${formatAngka(s.grand_qty_resep)}`);
                            $('#lbl_grand_hpp').text('Rp ' + formatRupiah(s.grand_total_hpp));
                            $('#lbl_grand_jual').text('Rp ' + formatRupiah(s.grand_total_jual));
                            $('#lbl_grand_laba').text('Rp ' + formatRupiah(s.grand_laba_kotor));
                            $('#lbl_avg_margin').text(s.avg_margin + '%');

                            // Footer table update
                            $('#foot_qty_bebas').text(formatAngka(s.grand_qty_bebas));
                            $('#foot_qty_resep').text(formatAngka(s.grand_qty_resep));
                            $('#foot_total_qty').text(formatAngka(s.grand_total_qty));
                            $('#foot_total_hpp').text('Rp ' + formatRupiah(s.grand_total_hpp));
                            $('#foot_total_jual').text('Rp ' + formatRupiah(s.grand_total_jual));
                            $('#foot_total_laba').text('Rp ' + formatRupiah(s.grand_laba_kotor));
                            $('#foot_avg_margin').text(s.avg_margin + '%');
                        }
                        return json.data;
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'kode_brng', name: 'u.kode_brng', className: 'fw-bold text-muted font-monospace' },
                    { 
                        data: 'nama_brng', 
                        name: 'b.nama_brng', 
                        className: 'fw-bold text-dark',
                        render: function(data) {
                            return `<span class="fw-bold text-dark">${data}</span>`;
                        }
                    },
                    { data: 'nama_satuan', name: 's.satuan', className: 'text-center' },
                    { 
                        data: 'nama_jenis', 
                        name: 'j.nama',
                        render: function(data) {
                            return `<span class="badge bg-secondary-lt">${data}</span>`;
                        }
                    },
                    { 
                        data: 'qty_bebas', 
                        name: 'qty_bebas', 
                        className: 'text-center col-qty-bebas',
                        render: (data) => formatAngka(data)
                    },
                    { 
                        data: 'qty_resep', 
                        name: 'qty_resep', 
                        className: 'text-center col-qty-resep',
                        render: (data) => formatAngka(data)
                    },
                    { 
                        data: 'total_qty', 
                        name: 'total_qty', 
                        className: 'text-center fw-bold fs-4 text-dark',
                        render: (data) => formatAngka(data)
                    },
                    { 
                        data: 'avg_hpp', 
                        orderable: false, 
                        searchable: false, 
                        className: 'text-end text-muted',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'total_hpp', 
                        name: 'total_hpp', 
                        className: 'text-end fw-semibold',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'total_jual', 
                        name: 'total_jual', 
                        className: 'text-end fw-bold text-success fs-4',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'laba_kotor', 
                        name: 'laba_kotor', 
                        className: 'text-end fw-bold text-primary',
                        render: (data) => `Rp ${formatRupiah(data)}`
                    },
                    { 
                        data: 'margin_persen', 
                        orderable: false, 
                        searchable: false, 
                        className: 'text-center',
                        render: function(data) {
                            const val = parseFloat(data);
                            const badgeClass = val >= 20 ? 'bg-success-lt text-success' : (val > 0 ? 'bg-warning-lt text-warning' : 'bg-danger-lt text-danger');
                            return `<span class="badge ${badgeClass} fw-bold">${val}%</span>`;
                        }
                    }
                ],
                order: [[10, 'desc']], // Urutkan default berdasarkan Total Penjualan tertinggi
                drawCallback: function() {
                    updateColumnVisibility();
                }
            });
        }
    </script>
@endpush

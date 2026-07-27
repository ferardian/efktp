@extends('main')

@section('contents')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-report-money me-2"></i> Rekap Pembayaran Pasien Rawat Jalan
                </h2>
                <div class="text-muted mt-1" style="font-size: 11px;">
                    Data rincian tagihan & pembayaran pasien ralan berdasarkan tanggal registrasi
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        {{-- Card Filter --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light py-2">
                <h3 class="card-title text-dark fw-bold mb-0">
                    <i class="ti ti-filter me-1"></i> Filter Data & Export
                </h3>
            </div>
            <div class="card-body py-3">
                <form id="form-filter">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tanggal Registrasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control filterTangal" id="tgl_awal" value="{{ date('d-m-Y') }}" placeholder="Tgl Awal">
                                <span class="input-group-text">s.d</span>
                                <input type="text" class="form-control filterTangal" id="tgl_akhir" value="{{ date('d-m-Y') }}" placeholder="Tgl Akhir">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Poliklinik</label>
                            <select class="form-select select2" id="kd_poli">
                                <option value="">-- Semua Poli --</option>
                                @foreach($poliklinik as $p)
                                    <option value="{{ $p->kd_poli }}">{{ $p->nm_poli }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Dokter</label>
                            <select class="form-select select2" id="kd_dokter">
                                <option value="">-- Semua Dokter --</option>
                                @foreach($dokter as $d)
                                    <option value="{{ $d->kd_dokter }}">{{ $d->nm_dokter }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-bold">Penjamin / Cara Bayar</label>
                            <select class="form-select select2" id="kd_pj">
                                <option value="">-- Semua Penjamin --</option>
                                @foreach($penjab as $pj)
                                    <option value="{{ $pj->kd_pj }}">{{ $pj->png_jawab }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end gap-1">
                            <div class="flex-grow-1">
                                <label class="form-label fw-bold">Status Bayar</label>
                                <select class="form-select" id="status_bayar">
                                    <option value="Semua">Semua Status</option>
                                    <option value="Sudah Bayar">Sudah Bayar</option>
                                    <option value="Belum Bayar">Belum Bayar</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                            <button type="button" class="btn btn-primary" id="btn-filter">
                                <i class="ti ti-search me-1"></i> Cari Data
                            </button>
                            <button type="button" class="btn btn-success" id="btn-export-excel">
                                <i class="ti ti-file-spreadsheet me-1"></i> Export Excel (XLSX)
                            </button>
                            <button type="button" class="btn btn-danger" id="btn-export-pdf">
                                <i class="ti ti-file-text me-1"></i> Export PDF / Cetak
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ringkasan Nominal Card --}}
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div class="card card-sm bg-blue-lt">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Registrasi</div>
                        </div>
                        <div class="h3 m-0 text-primary fw-bold" id="lbl-total-reg">Rp 0</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-sm bg-green-lt">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Obat & BHP</div>
                        </div>
                        <div class="h3 m-0 text-success fw-bold" id="lbl-total-obat">Rp 0</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-sm bg-yellow-lt">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Tindakan</div>
                        </div>
                        <div class="h3 m-0 text-warning fw-bold" id="lbl-total-tindakan">Rp 0</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-sm bg-purple-lt">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Grand Total Biaya</div>
                        </div>
                        <div class="h3 m-0 text-purple fw-bold" id="lbl-grand-total">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered w-100" id="table-pembayaran">
                        <thead class="table-dark">
                            <tr>
                                <th>Tgl</th>
                                <th>No. Nota</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Dokter</th>
                                <th>Penjab</th>
                                <th>Registrasi</th>
                                <th>Obat+BHP</th>
                                <th>Tindakan</th>
                                <th>Lab</th>
                                <th>Rad</th>
                                <th>Tambahan</th>
                                <th>Potongan</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    /* Styling Filter Section & Select2 Border Fix */
    .card-filter {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }
    .form-label {
        font-size: 11px;
        color: #334155;
        margin-bottom: 4px;
    }
    .form-control, .form-select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        font-size: 12px;
        height: 36px;
        box-shadow: none !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #206bc4 !important;
    }
    /* Select2 Crisp Border & Height Alignment */
    .select2-container .select2-selection--single {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        height: 36px !important;
        padding: 4px 8px !important;
        background-color: #ffffff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important;
        font-size: 12px !important;
        color: #1e293b !important;
        padding-left: 2px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important;
        right: 6px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--open .select2-selection--single {
        border-color: #206bc4 !important;
        box-shadow: 0 0 0 0.25rem rgba(32, 107, 196, 0.15) !important;
    }
    #table-pembayaran th {
        font-size: 10px;
        text-align: center;
        vertical-align: middle;
    }
    #table-pembayaran td {
        font-size: 10px;
        vertical-align: middle;
    }
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    let tablePembayaran = $('#table-pembayaran').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 25,
        language: {
            url: "{{ asset('js/dataTable/indonesian.json') }}"
        },
        columns: [
            { data: 'tgl_registrasi', className: 'text-center' },
            { data: 'no_nota', className: 'text-center' },
            { data: 'no_rkm_medis', className: 'text-center' },
            { data: 'nm_pasien' },
            { data: 'nm_poli' },
            { data: 'nm_dokter' },
            { data: 'png_jawab' },
            { data: 'biaya_reg', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_obat', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_tindakan', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_lab', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_rad', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_tambahan', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'biaya_potongan', className: 'text-end', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { data: 'total_biaya', className: 'text-end fw-bold', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
            { 
                data: 'status_bayar', 
                className: 'text-center',
                render: function(data) {
                    if (data === 'Sudah Bayar') {
                        return '<span class="badge bg-success">Sudah Bayar</span>';
                    } else {
                        return '<span class="badge bg-danger">Belum Bayar</span>';
                    }
                }
            },
            {
                data: 'no_rawat',
                className: 'text-center',
                orderable: false,
                render: function(data) {
                    return `
                        <a href="{{ url('billing/print') }}?no_rawat=${encodeURIComponent(data)}" target="_blank" class="btn btn-sm btn-primary py-0 px-1" title="Cetak Billing">
                            <i class="ti ti-printer"></i>
                        </a>
                    `;
                }
            }
        ]
    });

    function getFilterParams() {
        return {
            tgl_awal: $('#tgl_awal').val(),
            tgl_akhir: $('#tgl_akhir').val(),
            kd_poli: $('#kd_poli').val(),
            kd_dokter: $('#kd_dokter').val(),
            kd_pj: $('#kd_pj').val(),
            status_bayar: $('#status_bayar').val(),
        };
    }

    function loadData() {
        $.ajax({
            url: "{{ url('keuangan/pembayaran-ralan/data') }}",
            type: "GET",
            data: getFilterParams(),
            beforeSend: function() {
                Swal.fire({
                    title: 'Memuat Data...',
                    text: 'Sedang menghitung rekapitulasi pembayaran',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            },
            success: function(response) {
                Swal.close();
                if (response.status === 'success') {
                    tablePembayaran.clear().rows.add(response.data).draw();
                    
                    const formatRp = (num) => 'Rp ' + Number(num).toLocaleString('id-ID');
                    $('#lbl-total-reg').text(formatRp(response.totals.registrasi));
                    $('#lbl-total-obat').text(formatRp(response.totals.obat));
                    $('#lbl-total-tindakan').text(formatRp(response.totals.tindakan));
                    $('#lbl-grand-total').text(formatRp(response.totals.grand_total));
                }
            },
            error: function(err) {
                Swal.close();
                showToast('Gagal memuat data pembayaran', 'error');
            }
        });
    }

    $('#btn-filter').on('click', function() {
        loadData();
    });

    $('#btn-export-excel').on('click', function() {
        const query = $.param(getFilterParams());
        window.open("{{ url('keuangan/pembayaran-ralan/export-excel') }}?" + query, '_blank');
    });

    $('#btn-export-pdf').on('click', function() {
        const query = $.param(getFilterParams());
        window.open("{{ url('keuangan/pembayaran-ralan/export-pdf') }}?" + query, '_blank');
    });

    loadData();
});
</script>
@endpush

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
</style>
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-primary d-flex align-items-center">
                    <i class="ti ti-cash me-2 fs-1"></i> Kasir & Billing Rawat Jalan
                </h2>
                <div class="text-muted mt-1 small">
                    Loket Transaksi Pembayaran Pasien, Validasi Resep, Penutupan Billing & Penjurnalan Kasir
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none d-flex gap-2">
                <a href="{{ url('/keuangan/pembayaran-ralan') }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-report-money me-1"></i> Rekap Pembayaran Ralan
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadAntreanPasien()">
                    <i class="ti ti-refresh me-1"></i> Refresh Antrean
                </button>
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-danger" title="Tutup / Kembali ke Beranda">
                    <i class="ti ti-x me-1"></i> Tutup
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row g-3">
            {{-- KOLOM KIRI: ANTREAN PASIEN RAWAT JALAN --}}
            <div class="col-lg-5 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-2 d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span class="fw-bold text-dark"><i class="ti ti-users me-1"></i> Antrean Pasien Ralan</span>
                            <span class="badge bg-primary-lt" id="totalAntreanBadge">0 Pasien</span>
                        </div>
                        {{-- Filter Status Bayar --}}
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="filter_status_bayar" id="statusBelumBayar" value="Belum Bayar" checked onchange="loadAntreanPasien()">
                            <label class="btn btn-outline-warning btn-sm py-1 fw-bold" for="statusBelumBayar">Belum Bayar</label>

                            <input type="radio" class="btn-check" name="filter_status_bayar" id="statusSudahBayar" value="Sudah Bayar" onchange="loadAntreanPasien()">
                            <label class="btn btn-outline-success btn-sm py-1 fw-bold" for="statusSudahBayar">Sudah Bayar</label>

                            <input type="radio" class="btn-check" name="filter_status_bayar" id="statusSemua" value="Semua" onchange="loadAntreanPasien()">
                            <label class="btn btn-outline-secondary btn-sm py-1" for="statusSemua">Semua</label>
                        </div>
                        {{-- Filter Tanggal, Poli, Status Periksa & Search --}}
                        <div class="row g-1">
                            <div class="col-4">
                                <input type="date" class="form-control form-control-sm" id="filter_tgl" value="{{ date('Y-m-d') }}" onchange="loadAntreanPasien()">
                            </div>
                            <div class="col-4">
                                <select class="form-select form-select-sm" id="filter_poli" onchange="loadAntreanPasien()">
                                    <option value="">-- Semua Poli --</option>
                                    @foreach($poliklinik as $p)
                                        <option value="{{ $p->kd_poli }}">{{ $p->nm_poli }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="form-select form-select-sm" id="filter_status_periksa" onchange="loadAntreanPasien()">
                                    <option value="">-- Status Periksa --</option>
                                    <option value="Sudah">Sudah Periksa</option>
                                    <option value="Belum">Belum Periksa</option>
                                    <option value="Dirawat">Sedang Diperiksa</option>
                                </select>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="input-icon">
                                    <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                                    <input type="text" class="form-control form-control-sm" id="search_pasien" placeholder="Cari Nama / No.RM / No.Rawat / Status..." onkeyup="filterAntreanLocal()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="max-height: 700px; overflow-y: auto;" id="containerAntrean">
                        <div class="list-group list-group-flush" id="listAntreanPasien">
                            <div class="text-center p-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat antrean...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: BILLING & CHECKOUT DESK --}}
            <div class="col-lg-7 col-xl-8">
                {{-- State Kosong Belum Pilih Pasien --}}
                <div class="card shadow-sm border-0 text-center p-5" id="emptyCheckoutState">
                    <div class="my-5 py-4">
                        <div class="mb-3 text-secondary">
                            <i class="ti ti-receipt-tax fs-1" style="font-size: 5rem !important;"></i>
                        </div>
                        <h3 class="text-dark fw-bold">Belum Ada Pasien Dipilih</h3>
                        <p class="text-muted small mx-auto" style="max-width: 420px;">
                            Silakan pilih salah satu pasien dari daftar antrean di sebelah kiri untuk melihat rincian biaya, memvalidasi resep obat, dan memproses penutupan billing.
                        </p>
                    </div>
                </div>

                {{-- State Pasien Dipilih --}}
                <div class="card shadow-sm border-0 d-none" id="activeCheckoutState">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-primary fw-bold mb-0">
                                <i class="ti ti-receipt me-1"></i> Rincian Tagihan & Transaksi Pembayaran
                            </h3>
                            <div class="mt-1 d-flex align-items-center flex-wrap gap-1">
                                <span id="active_status_periksa_badge"></span>
                                <span id="active_status_bayar_badge"></span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="form-check form-switch me-2 mb-0 d-flex align-items-center">
                                <input class="form-check-input me-2" type="checkbox" id="kasirPrintShowObat" checked>
                                <label class="form-check-label small fw-bold text-dark mb-0" for="kasirPrintShowObat">Detail Obat</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="openModalTambahTindakanKasir()" title="Tambah / Entri Tindakan yang Belum Dientri">
                                <i class="ti ti-stethoscope me-1"></i> + Tindakan
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-printer me-1"></i> Cetak Nota
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakNotaKasir('80')">Ukuran 80mm (Thermal)</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakNotaKasir('58')">Ukuran 58mm (Thermal)</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="tutupTransaksiPasien()" title="Tutup Transaksi Pasien">
                                <i class="ti ti-x me-1"></i> Tutup
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        {{-- Ringkasan Identitas Pasien --}}
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body p-2">
                                <div class="row g-2 small text-dark">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-4 fw-bold">No. Rawat</div>
                                            <div class="col-8 text-primary fw-bold" id="kasir_no_rawat">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">No. Nota</div>
                                            <div class="col-8 fw-bold" id="kasir_no_nota">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">Poliklinik</div>
                                            <div class="col-8" id="kasir_poli">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">Dokter</div>
                                            <div class="col-8" id="kasir_dokter">: -</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-4 fw-bold">No. R.M.</div>
                                            <div class="col-8 fw-bold text-dark" id="kasir_no_rm">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">Nama Pasien</div>
                                            <div class="col-8 fw-bold text-dark fs-4" id="kasir_pasien">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">Penjamin</div>
                                            <div class="col-8" id="kasir_penjab">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">Tgl. Daftar</div>
                                            <div class="col-8" id="kasir_tgl">: -</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 fw-bold">Status Periksa</div>
                                            <div class="col-8" id="kasir_status_periksa">: -</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ALERT PASIEN BELUM DIPERIKSA DOKTER --}}
                        <div id="alertBelumPeriksaKasir" class="alert alert-danger-subtle border-start border-4 border-danger mb-3 py-2 d-none">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-alert-triangle fs-2 me-2 text-danger"></i>
                                <div>
                                    <h4 class="mb-0 text-danger fw-bold">Perhatian: Pasien Belum Selesai Diperiksa di Poliklinik!</h4>
                                    <p class="mb-0 small text-muted">Status pelayanan pasien saat ini masih <strong>Belum Diperiksa</strong>. Pastikan dokter / perawat telah selesai memeriksa dan mengentri tindakan medis atau resep obat sebelum memproses pembayaran.</p>
                                </div>
                            </div>
                        </div>

                        {{-- OPSI 3 - HYBRID: ALERT & TABEL RESEP BELUM DIVALIDASI DI KASIR --}}
                        <div id="sectionResepKasirAlert" class="mb-3" style="display:none;">
                            <div class="alert alert-warning border-start border-4 border-warning mb-2 py-2 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-prescription fs-2 me-2 text-warning"></i>
                                    <div>
                                        <h4 class="mb-0 text-warning fw-bold">Perhatian: Resep Belum Divalidasi</h4>
                                        <p class="mb-0 small text-muted">Obat belum dipotong dari stok apotek & belum terhitung di billing.</p>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark px-2 py-1">Menunggu Validasi</span>
                            </div>

                            <div class="card border-warning mb-3">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0 align-middle" id="tabelResepKasir">
                                            <thead class="bg-warning-lt text-dark">
                                                <tr>
                                                    <th class="py-1 px-2 text-center" style="width: 140px;">No. Resep</th>
                                                    <th class="py-1 px-2">Obat / Alkes</th>
                                                    <th class="py-1 px-2 text-center" style="width: 90px;">Jumlah</th>
                                                    <th class="py-1 px-2" style="width: 160px;">Aturan Pakai</th>
                                                    <th class="py-1 px-2 text-center" style="width: 110px;">Status Stok</th>
                                                    <th class="py-1 px-2 text-center" style="width: 130px;">Aksi Cepat</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TABEL RINCIAN BILLING --}}
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered mb-0" id="tabelBillingKasirDetail">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="py-2 px-3">Deskripsi Item / Layanan</th>
                                        <th class="py-2 px-3 text-center" width="60">Qty</th>
                                        <th class="py-2 px-3 text-end" width="120">Tarif (Rp)</th>
                                        <th class="py-2 px-3 text-end" width="140">Subtotal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-primary text-white">
                                        <th colspan="3" class="py-2 px-3 fw-bold text-uppercase">TOTAL ESTIMASI BIAYA SELURUHNYA</th>
                                        <th class="py-2 px-3 text-end fw-bold fs-3" id="grandTotalBillingKasir">Rp. 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- FORM TRANSAKSI PEMBAYARAN & TUTUP BILLING --}}
                        <div class="card border-primary-lt bg-light">
                            <div class="card-header bg-primary-lt py-2">
                                <h4 class="card-title text-primary fw-bold mb-0">
                                    <i class="ti ti-credit-card me-1"></i> Form Pembayaran & Penutupan Billing
                                </h4>
                            </div>
                            <div class="card-body p-3 bg-white">
                                <form id="formCheckoutBilling">
                                    <input type="hidden" id="co_no_rawat">
                                    
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Tanggal Pembayaran</label>
                                            <input type="date" class="form-control form-control-sm" id="co_tgl_bayar" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-warning">Potongan / Diskon (Rp)</label>
                                            <input type="number" class="form-control form-control-sm" id="co_potongan" value="0" min="0" oninput="calculateCheckoutAmounts()">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-primary">Tambahan Biaya (Rp)</label>
                                            <input type="number" class="form-control form-control-sm" id="co_tambahan" value="0" min="0" oninput="calculateCheckoutAmounts()">
                                        </div>
                                    </div>

                                    <div class="mb-3 border-top pt-2">
                                        <label class="form-label small fw-bold text-dark d-flex justify-content-between align-items-center">
                                            <span>Metode Pembayaran (Cash / Transfer / QRIS / EDC)</span>
                                            <button type="button" class="btn btn-xs btn-outline-primary py-0" onclick="addCheckoutPaymentRow()">
                                                <i class="ti ti-plus me-1"></i> Tambah Akun Bayar
                                            </button>
                                        </label>
                                        <div id="co_payments_container">
                                            {{-- Dynamic Payments rows --}}
                                            <div class="payment-row row g-2 mb-2 align-items-center">
                                                <div class="col-7">
                                                    <select class="form-select form-select-sm select-akun-bayar" onchange="calculateCheckoutAmounts()">
                                                        <option value="">-- Pilih Akun Pembayaran --</option>
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <input type="number" class="form-control form-control-sm input-besar-bayar" value="0" min="0" placeholder="Nominal Bayar" oninput="calculateCheckoutAmounts()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Section Piutang --}}
                                    <div class="mb-3 border-top pt-2" id="co_piutang_section" style="display:none;">
                                        <div class="alert alert-warning py-1 px-2 mb-2 small text-dark d-flex align-items-center">
                                            <i class="ti ti-alert-triangle me-2 text-warning fs-3"></i>
                                            <span>Sisa tagihan belum lunas sebesar <strong id="co_sisa_piutang_text">Rp 0</strong> akan dibukukan sebagai piutang.</span>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-danger">Nominal Piutang (Rp)</label>
                                                <input type="text" class="form-control form-control-sm bg-light fw-bold text-danger" id="co_sisa_piutang" readonly value="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Akun Rekening Piutang</label>
                                                <select class="form-select form-select-sm" id="co_kd_rek_piutang">
                                                    <option value="">-- Pilih Akun Piutang --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Summary Bar & Submit Button --}}
                                    <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="small text-muted d-block">Total Tagihan Bersih:</span>
                                            <span class="fs-2 fw-bold text-success" id="co_net_total_text">Rp 0</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success fw-bold px-4" id="btnSubmitCloseBilling" onclick="submitKasirCloseBilling()">
                                                <i class="ti ti-device-floppy me-1"></i> Bayar & Tutup Billing
                                            </button>
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
</div>

{{-- MODAL ENTRI TINDAKAN KASIR --}}
<div class="modal fade" id="modalTambahTindakanKasir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-light py-2 border-bottom">
                <div>
                    <h5 class="modal-title fw-bold text-primary mb-0">
                        <i class="ti ti-stethoscope me-1"></i> Entri Tindakan / Layanan Rawat Jalan
                    </h5>
                    <div class="text-muted small" id="tindakanKasirPasienInfo">Pasien: - | No. Rawat: -</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                {{-- Opsi Pelaksana Tindakan --}}
                <div class="row g-2 mb-3 bg-light p-2 rounded border">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1">Pelaksana Tindakan</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="kasirTindakanType" id="typeDr" value="dr" checked onchange="onTindakanTypeChange()">
                            <label class="btn btn-outline-primary btn-sm py-1 fw-bold" for="typeDr">Dokter</label>

                            <input type="radio" class="btn-check" name="kasirTindakanType" id="typePr" value="pr" onchange="onTindakanTypeChange()">
                            <label class="btn btn-outline-primary btn-sm py-1 fw-bold" for="typePr">Petugas</label>

                            <input type="radio" class="btn-check" name="kasirTindakanType" id="typeDrPr" value="drpr" onchange="onTindakanTypeChange()">
                            <label class="btn btn-outline-primary btn-sm py-1 fw-bold" for="typeDrPr">Dr & Petugas</label>
                        </div>
                    </div>
                    <div class="col-md-4" id="sectionPilihDokter">
                        <label class="form-label small fw-bold mb-1">Dokter Pelaksana</label>
                        <select class="form-select form-select-sm" id="kasirTindakanDokter">
                            @foreach($dokter as $d)
                                <option value="{{ $d->kd_dokter }}">{{ $d->nm_dokter }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4" id="sectionPilihPetugas" style="display: none;">
                        <label class="form-label small fw-bold mb-1">Petugas / Paramedis</label>
                        <select class="form-select form-select-sm" id="kasirTindakanPetugas">
                            <option value="-">-- Tidak Ada / Opsional --</option>
                            @if(isset($petugas))
                                @foreach($petugas as $pt)
                                    <option value="{{ $pt->nip }}">{{ $pt->nama }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Cari & Pilih Tindakan --}}
                <div class="card mb-3 border">
                    <div class="card-header bg-light py-2">
                        <span class="fw-bold small text-dark"><i class="ti ti-search me-1 text-primary"></i> Cari & Tambah Tindakan</span>
                    </div>
                    <div class="card-body p-2">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Nama / Kode Tindakan</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" id="kasirSearchTindakanInput" placeholder="Ketik nama / kode tindakan..." autocomplete="off">
                                    <button type="button" class="btn btn-primary" onclick="searchMasterTindakan()">
                                        <i class="ti ti-search me-1"></i> Cari
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Diskon per Item (Rp)</label>
                                <input type="number" class="form-control form-control-sm" id="kasirTindakanDiskon" value="0" min="0">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="$('#kasirSearchTindakanInput').val(''); searchMasterTindakan();">
                                    <i class="ti ti-list me-1"></i> Tampilkan Semua
                                </button>
                            </div>
                        </div>

                        {{-- Tabel Hasil Pencarian Tindakan --}}
                        <div class="mt-2 table-responsive border rounded" style="max-height: 180px; overflow-y: auto;">
                            <table class="table table-sm table-hover table-striped mb-0" id="tabelHasilPencarianTindakan">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 80px;">Kode</th>
                                        <th>Nama Tindakan / Layanan</th>
                                        <th class="text-end" style="width: 130px;">Tarif</th>
                                        <th class="text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3 small">
                                            Ketik nama tindakan lalu klik Cari, atau klik Tampilkan Semua
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Tindakan yang Akan Ditambahkan (Cart) --}}
                <div class="card border mb-3">
                    <div class="card-header bg-blue-lt py-2 d-flex justify-content-between align-items-center">
                        <span class="fw-bold small text-primary">
                            <i class="ti ti-shopping-cart me-1"></i> Tindakan Baru yang Dipilih (<span id="countTindakanCart">0</span> Item)
                        </span>
                        <span class="fw-bold small text-primary" id="totalTindakanCart">Total: Rp 0</span>
                    </div>
                    <div class="card-body p-0 table-responsive" style="max-height: 140px; overflow-y: auto;">
                        <table class="table table-sm table-bordered mb-0" id="tabelCartTindakan">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Tindakan</th>
                                    <th>Pelaksana</th>
                                    <th class="text-end">Tarif Dasar</th>
                                    <th class="text-end">Diskon</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center" style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3 small">Belum ada tindakan yang dipilih</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Collapsible: Tindakan yang Sudah Tercatat di Pasien Ini --}}
                <div class="accordion" id="accordionExistingTindakan">
                    <div class="accordion-item border">
                        <h2 class="accordion-header" id="headingExisting">
                            <button class="accordion-button collapsed py-2 small fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExisting" aria-expanded="false">
                                <i class="ti ti-history me-1 text-secondary"></i> Tindakan yang Sudah Tercatat Pada Pasien Ini
                            </button>
                        </h2>
                        <div id="collapseExisting" class="accordion-collapse collapse" data-bs-parent="#accordionExistingTindakan">
                            <div class="accordion-body p-0 table-responsive" style="max-height: 140px; overflow-y: auto;">
                                <table class="table table-sm table-bordered mb-0" id="tabelExistingTindakan">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tgl / Jam</th>
                                            <th>Nama Tindakan</th>
                                            <th>Pelaksana</th>
                                            <th class="text-end">Biaya Rawat</th>
                                            <th class="text-center" style="width: 60px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-2 small">Memuat...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-sm btn-success" id="btnSimpanTindakanKasir" onclick="simpanTindakanKasir()">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Tindakan ke Billing
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    let antreanDataList = [];
    let selectedNoRawat = null;
    let accountsDataCache = null;

    $(document).ready(function() {
        loadAntreanPasien();
        preloadBillingAccounts();
    });

    function preloadBillingAccounts() {
        $.get("{{ url('/billing/accounts') }}")
            .done((response) => {
                accountsDataCache = response;
            });
    }

    function loadAntreanPasien() {
        const tgl = $('#filter_tgl').val();
        const poli = $('#filter_poli').val();
        const statusBayar = $('input[name="filter_status_bayar"]:checked').val();
        const statusPeriksa = $('#filter_status_periksa').val();
        const listContainer = $('#listAntreanPasien');

        listContainer.html('<div class="text-center p-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat antrean...</div>');

        $.get("{{ url('/kasir/ralan/antrean') }}", {
            tgl_awal: tgl,
            tgl_akhir: tgl,
            kd_poli: poli,
            status_bayar: statusBayar,
            status_periksa: statusPeriksa
        }).done((response) => {
            antreanDataList = response.data || [];
            $('#totalAntreanBadge').text(antreanDataList.length + ' Pasien');
            renderAntreanList(antreanDataList);
        }).fail(() => {
            listContainer.html('<div class="text-center text-danger p-4"><i class="ti ti-x"></i> Gagal memuat antrean</div>');
        });
    }

    function renderAntreanList(data) {
        const listContainer = $('#listAntreanPasien');
        if (!data || data.length === 0) {
            listContainer.html('<div class="text-center p-4 text-muted">Tidak ada pasien dalam antrean</div>');
            return;
        }

        let html = '';
        data.forEach(item => {
            const isSelected = item.no_rawat === selectedNoRawat ? 'active' : '';
            const statusBadge = item.status_bayar === 'Sudah Bayar'
                ? '<span class="badge bg-success text-white"><i class="ti ti-check"></i><span>Lunas</span></span>'
                : '<span class="badge bg-warning text-dark"><i class="ti ti-clock"></i><span>Belum Bayar</span></span>';

            let periksaBadge = '';
            if (item.stts === 'Sudah' || item.is_sudah_periksa) {
                periksaBadge = '<span class="badge bg-success-subtle text-success border border-success me-1" title="Pasien sudah selesai diperiksa dokter"><i class="ti ti-check"></i><span>Sudah Periksa</span></span>';
            } else if (item.stts === 'Dirawat') {
                periksaBadge = '<span class="badge bg-info-subtle text-info border border-info me-1" title="Pasien sedang dalam pemeriksaan di poliklinik"><i class="ti ti-player-play"></i><span>Sedang Diperiksa</span></span>';
            } else if (item.stts === 'Berkas Diterima') {
                periksaBadge = '<span class="badge bg-purple-subtle text-purple border me-1" title="Berkas diterima / Menunggu panggilan poli"><i class="ti ti-clock"></i><span>Menunggu Poli</span></span>';
            } else if (item.stts === 'Batal') {
                periksaBadge = '<span class="badge bg-danger text-white me-1" title="Pemeriksaan dibatalkan"><i class="ti ti-x"></i><span>Batal</span></span>';
            } else {
                periksaBadge = '<span class="badge bg-danger-subtle text-danger border border-danger me-1" title="Pasien belum diperiksa di poliklinik"><i class="ti ti-clock-pause"></i><span>Belum Periksa</span></span>';
            }

            const resepBadge = item.has_unvalidated_resep
                ? '<span class="badge bg-danger text-white ms-1" title="Ada resep belum divalidasi"><i class="ti ti-prescription"></i><span>Resep Belum Validasi</span></span>'
                : '';

            html += `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action p-2 ${isSelected}" onclick="selectPasienKasir('${item.no_rawat}')">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-bold text-primary">${item.no_rawat}</span>
                        <div class="d-flex align-items-center flex-wrap justify-content-end">
                            ${periksaBadge}
                            ${statusBadge}
                            ${resepBadge}
                        </div>
                    </div>
                    <div class="fw-bold text-dark fs-4 mb-0">${item.nm_pasien}</div>
                    <div class="small text-muted d-flex justify-content-between mt-1">
                        <span><i class="ti ti-id me-1"></i>${item.no_rkm_medis}</span>
                        <span><i class="ti ti-building-hospital me-1"></i>${item.nm_poli}</span>
                    </div>
                    <div class="small text-muted d-flex justify-content-between">
                        <span><i class="ti ti-user me-1"></i>${item.nm_dokter}</span>
                        <span class="badge bg-secondary-lt">${item.png_jawab}</span>
                    </div>
                </a>
            `;
        });

        listContainer.html(html);
    }

    function filterAntreanLocal() {
        const keyword = $('#search_pasien').val().toLowerCase().trim();
        if (!keyword) {
            renderAntreanList(antreanDataList);
            return;
        }

        const filtered = antreanDataList.filter(item => {
            return (item.nm_pasien && item.nm_pasien.toLowerCase().includes(keyword)) ||
                   (item.no_rkm_medis && item.no_rkm_medis.toLowerCase().includes(keyword)) ||
                   (item.status_periksa && item.status_periksa.toLowerCase().includes(keyword)) ||
                   (item.no_rawat && item.no_rawat.toLowerCase().includes(keyword));
        });

        renderAntreanList(filtered);
    }

    function selectPasienKasir(no_rawat) {
        selectedNoRawat = no_rawat;
        $('#listAntreanPasien a').removeClass('active');
        $(`#listAntreanPasien a[onclick="selectPasienKasir('${no_rawat}')"]`).addClass('active');

        $('#emptyCheckoutState').addClass('d-none');
        $('#activeCheckoutState').removeClass('d-none');

        loadKasirBillingDetails(no_rawat);
        loadKasirUnvalidatedResep(no_rawat);
    }

    function tutupTransaksiPasien() {
        selectedNoRawat = null;
        $('#listAntreanPasien a').removeClass('active');
        $('#activeCheckoutState').addClass('d-none');
        $('#emptyCheckoutState').removeClass('d-none');
    }

    function loadKasirBillingDetails(no_rawat) {
        const tbody = $('#tabelBillingKasirDetail tbody');
        const tfoot = $('#grandTotalBillingKasir');

        tbody.html('<tr><td colspan="4" class="text-center p-4"><div class="spinner-border spinner-border-sm text-secondary me-2"></div> Menghitung rincian tagihan...</td></tr>');
        tfoot.text('Rp 0');

        $.get("{{ url('/billing/ralan') }}", { no_rawat: no_rawat })
            .done((response) => {
                currentBillingData = response;
                $('#kasir_no_rawat').text(': ' + response.no_rawat);
                $('#kasir_no_nota').text(': ' + (response.nota ? response.nota.no_nota : (response.no_nota || '-')));
                $('#kasir_no_rm').text(': ' + response.no_rm);
                $('#kasir_pasien').text(': ' + response.pasien);
                $('#kasir_poli').text(': ' + response.poli);
                $('#kasir_dokter').text(': ' + (response.dokter || '-'));
                $('#kasir_penjab').text(': ' + (response.png_jawab || '-'));
                $('#kasir_tgl').text(': ' + formatTanggal(response.tgl_perawatan));

                const statusBadge = $('#active_status_bayar_badge');
                const btnSubmit = $('#btnSubmitCloseBilling');
                if (response.status_bayar === 'Sudah Bayar') {
                    statusBadge.html('<span class="badge bg-success-lt text-success ms-2 px-2 py-1 align-middle"><i class="ti ti-check"></i><span>Sudah Bayar (Lunas)</span></span>');
                    btnSubmit.html('<i class="ti ti-lock-open me-1"></i> Update & Simpan Billing').removeClass('btn-success').addClass('btn-warning');
                } else {
                    statusBadge.html('<span class="badge bg-warning-lt text-warning ms-2 px-2 py-1 align-middle"><i class="ti ti-clock"></i><span>Belum Bayar</span></span>');
                    btnSubmit.html('<i class="ti ti-device-floppy me-1"></i> Bayar & Tutup Billing').removeClass('btn-warning').addClass('btn-success');
                }

                // Status Periksa Badge & Alert
                const isSudahPeriksa = response.is_sudah_periksa || response.stts === 'Sudah';
                const sttsText = response.stts || 'Belum';

                if (isSudahPeriksa) {
                    $('#active_status_periksa_badge').html('<span class="badge bg-success text-white px-2 py-1 align-middle"><i class="ti ti-check"></i><span>Sudah Periksa</span></span>');
                    $('#kasir_status_periksa').html(': <span class="badge bg-success-subtle text-success border border-success"><i class="ti ti-check"></i><span>Sudah Selesai Diperiksa</span></span>');
                    $('#alertBelumPeriksaKasir').addClass('d-none');
                } else if (sttsText === 'Dirawat') {
                    $('#active_status_periksa_badge').html('<span class="badge bg-info text-white px-2 py-1 align-middle"><i class="ti ti-player-play"></i><span>Sedang Diperiksa</span></span>');
                    $('#kasir_status_periksa').html(': <span class="badge bg-info-subtle text-info border border-info"><i class="ti ti-player-play"></i><span>Sedang Dalam Pemeriksaan</span></span>');
                    $('#alertBelumPeriksaKasir').addClass('d-none');
                } else {
                    $('#active_status_periksa_badge').html('<span class="badge bg-danger text-white px-2 py-1 align-middle"><i class="ti ti-clock-pause"></i><span>Belum Periksa</span></span>');
                    $('#kasir_status_periksa').html(': <span class="badge bg-danger-subtle text-danger border border-danger"><i class="ti ti-clock-pause"></i><span>Belum Selesai Diperiksa</span></span>');
                    $('#alertBelumPeriksaKasir').removeClass('d-none');
                }

                let html = '';
                response.categories.forEach(cat => {
                    if (cat.items.length > 0) {
                        html += `<tr class="bg-light fw-bold">
                                    <td colspan="3" class="px-3 text-primary text-uppercase" style="font-size: 0.75rem;">${cat.label}</td>
                                    <td class="px-3 text-end text-primary">${new Intl.NumberFormat('id-ID').format(cat.total)}</td>
                                 </tr>`;
                        cat.items.forEach(item => {
                            const isNegative = item.subtotal < 0;
                            html += `
                                <tr>
                                    <td class="px-3 ps-4 small ${isNegative ? 'text-danger' : ''}">${item.item}</td>
                                    <td class="px-3 text-center small">${item.qty}</td>
                                    <td class="px-3 text-end small">${new Intl.NumberFormat('id-ID').format(item.tarif)}</td>
                                    <td class="px-3 text-end small fw-500 ${isNegative ? 'text-danger' : ''}">
                                        ${new Intl.NumberFormat('id-ID').format(item.subtotal)}
                                    </td>
                                </tr>`;
                        });
                    }
                });

                if (!html) {
                    html = '<tr><td colspan="4" class="text-center p-3 text-muted">Belum ada rincian tindakan / obat</td></tr>';
                }

                tbody.html(html);
                tfoot.text('Rp ' + new Intl.NumberFormat('id-ID').format(response.grand_total));

                // Setup Checkout Form values
                $('#co_no_rawat').val(no_rawat);
                $('#co_total_raw').val(response.grand_total);
                $('#co_potongan').val(0);
                $('#co_tambahan').val(0);
                $('#co_tgl_bayar').val(new Date().toISOString().split('T')[0]);

                initCheckoutPaymentRows(response.grand_total);
            });
    }

    function initCheckoutPaymentRows(totalAmount) {
        $('#co_payments_container').html(`
            <div class="payment-row row g-2 mb-2 align-items-center">
                <div class="col-7">
                    <select class="form-select form-select-sm select-akun-bayar" onchange="calculateCheckoutAmounts()">
                        <option value="">-- Pilih Akun Pembayaran --</option>
                    </select>
                </div>
                <div class="col-5">
                    <input type="number" class="form-control form-control-sm input-besar-bayar" value="${totalAmount}" min="0" placeholder="Nominal Bayar" oninput="calculateCheckoutAmounts()">
                </div>
            </div>
        `);

        if (accountsDataCache) {
            populateCheckoutAccounts(accountsDataCache);
        } else {
            $.get("{{ url('/billing/accounts') }}", { no_rawat: selectedNoRawat })
                .done((data) => {
                    accountsDataCache = data;
                    populateCheckoutAccounts(data);
                });
        }
    }

    function populateCheckoutAccounts(data) {
        $('.select-akun-bayar').each(function() {
            const select = $(this);
            const currentVal = select.val();
            select.empty().append('<option value="">-- Pilih Akun Pembayaran --</option>');
            data.akun_bayar.forEach(acc => {
                select.append(`<option value="${acc.nama_bayar}">${acc.nama_bayar} (${acc.kd_rek})</option>`);
            });
            if (currentVal) {
                select.val(currentVal);
            } else if (data.akun_bayar.length > 0) {
                // Default to first payment account (e.g. Kas Kasir)
                select.val(data.akun_bayar[0].nama_bayar);
            }
        });

        // Populate Piutang
        const piutangSelect = $('#co_kd_rek_piutang');
        piutangSelect.empty().append('<option value="">-- Pilih Akun Piutang --</option>');
        data.akun_piutang.forEach(acc => {
            const selected = data.default_piutang && data.default_piutang.kd_rek === acc.kd_rek ? 'selected' : '';
            piutangSelect.append(`<option value="${acc.kd_rek}" ${selected}>${acc.nama_bayar} (${acc.kd_rek})</option>`);
        });

        calculateCheckoutAmounts();
    }

    function addCheckoutPaymentRow() {
        const row = $(`
            <div class="payment-row row g-2 mb-2 align-items-center">
                <div class="col-7">
                    <select class="form-select form-select-sm select-akun-bayar" onchange="calculateCheckoutAmounts()">
                        <option value="">-- Pilih Akun Pembayaran --</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" class="form-control form-control-sm input-besar-bayar" value="0" min="0" placeholder="Besar Bayar" oninput="calculateCheckoutAmounts()">
                </div>
                <div class="col-1 text-center">
                    <a href="javascript:void(0)" class="text-danger" onclick="$(this).closest('.payment-row').remove(); calculateCheckoutAmounts();">
                        <i class="ti ti-trash fs-3"></i>
                    </a>
                </div>
            </div>
        `);
        $('#co_payments_container').append(row);
        if (accountsDataCache) {
            populateCheckoutAccounts(accountsDataCache);
        }
    }

    function calculateCheckoutAmounts() {
        const grandTotalText = $('#grandTotalBillingKasir').text().replace('Rp ', '').replace(/\./g, '').trim();
        const raw_total = parseFloat(grandTotalText) || 0;
        const potongan = parseFloat($('#co_potongan').val()) || 0;
        const tambahan = parseFloat($('#co_tambahan').val()) || 0;

        const net_total = (raw_total + tambahan) - potongan;
        $('#co_net_total_text').text('Rp ' + new Intl.NumberFormat('id-ID').format(net_total));

        let total_bayar = 0;
        $('.payment-row').each(function() {
            const besar = parseFloat($(this).find('.input-besar-bayar').val()) || 0;
            total_bayar += besar;
        });

        const sisa = net_total - total_bayar;
        if (sisa > 0) {
            $('#co_piutang_section').show();
            $('#co_sisa_piutang_text').text('Rp ' + new Intl.NumberFormat('id-ID').format(sisa));
            $('#co_sisa_piutang').val(new Intl.NumberFormat('id-ID').format(sisa));
        } else {
            $('#co_piutang_section').hide();
            $('#co_sisa_piutang').val(0);
        }
    }

    {{-- OPSI 3: MEMUAT & MEMVALIDASI RESEP DARI KASIR --}}
    function loadKasirUnvalidatedResep(no_rawat) {
        const container = $('#sectionResepKasirAlert');
        const tbody = $('#tabelResepKasir tbody');

        $.get("{{ url('/resep/unvalidated') }}", { no_rawat: no_rawat })
            .done((response) => {
                if (response.resep && response.resep.length > 0) {
                    container.show();
                    let html = '';

                    response.resep.forEach(resep => {
                        let drugs = [];

                        resep.resep_dokter.forEach(rd => {
                            const isEnough = rd.stok >= rd.jml;
                            const stockStatus = isEnough 
                                ? `<span class="badge bg-success-lt fw-bold">Tersedia (${new Intl.NumberFormat('id-ID').format(rd.stok)})</span>`
                                : `<span class="badge bg-danger-lt fw-bold">Kurang (${new Intl.NumberFormat('id-ID').format(rd.stok)})</span>`;
                            drugs.push({
                                name: rd.obat ? rd.obat.nama_brng : 'Obat tidak ditemukan',
                                qty: `${new Intl.NumberFormat('id-ID').format(rd.jml)} ${rd.obat && rd.obat.satuan ? rd.obat.satuan.satuan : ''}`,
                                aturan: rd.aturan_pakai || '-',
                                stockStatus: stockStatus
                            });
                        });

                        resep.resep_racikan.forEach(rr => {
                            drugs.push({
                                name: `<strong>Racikan: ${rr.nama_racik}</strong>`,
                                qty: `${new Intl.NumberFormat('id-ID').format(rr.jml_dr)} Bks/Porsi`,
                                aturan: rr.aturan_pakai || '-',
                                stockStatus: '-'
                            });

                            rr.detail.forEach(rrd => {
                                const isEnough = rrd.stok >= rrd.jml;
                                const stockStatus = isEnough 
                                    ? `<span class="badge bg-success-lt fw-bold">Tersedia (${new Intl.NumberFormat('id-ID').format(rrd.stok)})</span>`
                                    : `<span class="badge bg-danger-lt fw-bold">Kurang (${new Intl.NumberFormat('id-ID').format(rrd.stok)})</span>`;
                                drugs.push({
                                    name: `<span class="ms-3 text-muted">— ${rrd.obat ? rrd.obat.nama_brng : 'Obat tidak ditemukan'}</span>`,
                                    qty: `${new Intl.NumberFormat('id-ID').format(rrd.jml)} ${rrd.obat && rrd.obat.satuan ? rrd.obat.satuan.satuan : ''}`,
                                    aturan: '-',
                                    stockStatus: stockStatus
                                });
                            });
                        });

                        const rowSpan = drugs.length;
                        const actionBtn = `
                            <button type="button" class="btn btn-sm btn-success fw-bold py-1 px-2 d-inline-flex align-items-center justify-content-center" onclick="validasiResepDariKasir('${resep.no_resep}')">
                                <i class="ti ti-check"></i><span>Validasi di Kasir</span>
                            </button>
                        `;

                        if (rowSpan === 0) {
                            html += `<tr>
                                        <td class="px-2 py-1 text-center small fw-bold">${resep.no_resep}</td>
                                        <td colspan="4" class="text-center text-muted small py-2">Tidak ada item obat</td>
                                        <td class="text-center px-2 py-1">${actionBtn}</td>
                                     </tr>`;
                        } else {
                            drugs.forEach((drug, idx) => {
                                if (idx === 0) {
                                    html += `<tr>
                                                <td rowspan="${rowSpan}" class="px-2 py-1 text-center small fw-bold align-middle border-end">
                                                    <span class="text-primary">${resep.no_resep}</span><br>
                                                    <small class="text-muted">${formatTanggal(resep.tgl_peresepan)}</small>
                                                </td>
                                                <td class="px-2 py-1 small">${drug.name}</td>
                                                <td class="px-2 py-1 text-center small">${drug.qty}</td>
                                                <td class="px-2 py-1 small">${drug.aturan}</td>
                                                <td class="px-2 py-1 text-center small">${drug.stockStatus}</td>
                                                <td rowspan="${rowSpan}" class="text-center px-2 py-1 align-middle border-start">${actionBtn}</td>
                                             </tr>`;
                                } else {
                                    html += `<tr>
                                                <td class="px-2 py-1 small">${drug.name}</td>
                                                <td class="px-2 py-1 text-center small">${drug.qty}</td>
                                                <td class="px-2 py-1 small">${drug.aturan}</td>
                                                <td class="px-2 py-1 text-center small">${drug.stockStatus}</td>
                                             </tr>`;
                                }
                            });
                        }
                    });

                    tbody.html(html);
                } else {
                    container.hide();
                    tbody.empty();
                }
            })
            .fail(() => {
                container.hide();
            });
    }

    function validasiResepDariKasir(no_resep) {
        Swal.fire({
            title: 'Validasi Resep di Kasir?',
            text: 'Stok obat apotek akan otomatis dipotong dan nilai biaya obat langsung dimasukkan ke rincian tagihan kasir.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            confirmButtonText: 'Ya, Validasi Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Validasi...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post("{{ url('/resep/validate') }}", {
                    _token: '{{ csrf_token() }}',
                    no_resep: no_resep
                }).done((response) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Resep Berhasil Divalidasi',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Refresh billing & unvalidated resep
                    loadKasirBillingDetails(selectedNoRawat);
                    loadKasirUnvalidatedResep(selectedNoRawat);
                    loadAntreanPasien();
                }).fail((err) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Validasi',
                        text: err.responseJSON?.message || 'Terjadi kesalahan saat validasi'
                    });
                });
            }
        });
    }

    function submitKasirCloseBilling() {
        const no_rawat = $('#co_no_rawat').val();
        const tgl_bayar = $('#co_tgl_bayar').val();
        const potongan = parseFloat($('#co_potongan').val()) || 0;
        const tambahan = parseFloat($('#co_tambahan').val()) || 0;

        const grandTotalText = $('#grandTotalBillingKasir').text().replace('Rp ', '').replace(/\./g, '').trim();
        const raw_total = parseFloat(grandTotalText) || 0;
        const net_total = (raw_total + tambahan) - potongan;

        let payments = [];
        let total_bayar = 0;
        let missingAccount = false;

        $('.payment-row').each(function() {
            const nama = $(this).find('.select-akun-bayar').val();
            const besar = parseFloat($(this).find('.input-besar-bayar').val()) || 0;
            if (besar > 0) {
                if (!nama) {
                    missingAccount = true;
                }
                payments.push({
                    nama_bayar: nama,
                    besar_bayar: besar
                });
                total_bayar += besar;
            }
        });

        if (missingAccount) {
            Swal.fire({
                icon: 'warning',
                title: 'Akun Pembayaran Kosong',
                text: 'Harap pilih akun rekening pembayaran untuk nominal yang diinput!'
            });
            return;
        }

        const sisa = net_total - total_bayar;
        let kd_rek_piutang = '';
        if (sisa > 0) {
            kd_rek_piutang = $('#co_kd_rek_piutang').val();
            if (!kd_rek_piutang) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Akun Piutang Belum Dipilih',
                    text: 'Sisa tagihan belum lunas. Harap tentukan akun rekening piutang!'
                });
                return;
            }
        }

        Swal.fire({
            title: 'Selesaikan & Tutup Billing?',
            text: 'Tagihan pasien akan dikunci, status diubah menjadi Sudah Bayar, dan jurnal akuntansi otomatis diposting.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            confirmButtonText: 'Ya, Tutup Billing',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Penutupan Billing...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ url('/billing/close') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        no_rawat: no_rawat,
                        tgl_bayar: tgl_bayar,
                        potongan: potongan,
                        tambahan: tambahan,
                        payments: payments,
                        kd_rek_piutang: kd_rek_piutang
                    },
                    success: (response) => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Billing Berhasil Ditutup!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        loadKasirBillingDetails(no_rawat);
                        loadAntreanPasien();
                    },
                    error: (xhr) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menutup Billing',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem'
                        });
                    }
                });
            }
        });
    }

    function cetakNotaKasir(size) {
        if (!selectedNoRawat || selectedNoRawat === '-') {
            Swal.fire({
                icon: 'warning',
                title: 'Pasien Belum Dipilih',
                text: 'Pilih pasien terlebih dahulu dari antrean!'
            });
            return;
        }

        const show_obat = $('#kasirPrintShowObat').is(':checked') ? 1 : 0;
        window.open(`{{ url('/billing/print') }}?no_rawat=${selectedNoRawat}&size=${size}&show_obat=${show_obat}`, '_blank');
    }

    // ==========================================
    // ENTRI TINDAKAN DARI MEJA KASIR
    // ==========================================
    let currentBillingData = null;
    let tindakanCart = [];
    let masterTindakanSearchResults = [];

    function openModalTambahTindakanKasir() {
        if (!selectedNoRawat || !currentBillingData) {
            Swal.fire({
                icon: 'warning',
                title: 'Pasien Belum Dipilih',
                text: 'Silakan pilih pasien dari antrean terlebih dahulu!'
            });
            return;
        }

        $('#tindakanKasirPasienInfo').text(`Pasien: ${currentBillingData.pasien} (${currentBillingData.no_rm}) | No. Rawat: ${currentBillingData.no_rawat} | Poli: ${currentBillingData.poli}`);
        if (currentBillingData.kd_dokter) {
            $('#kasirTindakanDokter').val(currentBillingData.kd_dokter);
        }
        $('#kasirTindakanPetugas').val('-');
        $('#typeDr').prop('checked', true);
        onTindakanTypeChange();

        $('#kasirSearchTindakanInput').val('');
        $('#kasirTindakanDiskon').val(0);
        tindakanCart = [];
        renderTindakanCart();

        loadExistingTindakanPasien(selectedNoRawat);
        searchMasterTindakan();

        const modal = new bootstrap.Modal(document.getElementById('modalTambahTindakanKasir'));
        modal.show();
    }

    function onTindakanTypeChange() {
        const type = $('input[name="kasirTindakanType"]:checked').val();
        if (type === 'dr') {
            $('#sectionPilihDokter').show();
            $('#sectionPilihPetugas').hide();
        } else if (type === 'pr') {
            $('#sectionPilihDokter').hide();
            $('#sectionPilihPetugas').show();
        } else {
            $('#sectionPilihDokter').show();
            $('#sectionPilihPetugas').show();
        }
        if (masterTindakanSearchResults && masterTindakanSearchResults.length > 0) {
            renderMasterTindakanTable(masterTindakanSearchResults);
        }
    }

    function searchMasterTindakan() {
        const keyword = $('#kasirSearchTindakanInput').val().trim();
        const pelaksana = $('input[name="kasirTindakanType"]:checked').val();
        const tbody = $('#tabelHasilPencarianTindakan tbody');

        tbody.html('<tr><td colspan="4" class="text-center p-3 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Mencari tindakan...</td></tr>');

        $.get("{{ url('/jns-perawatan/get') }}", {
            keyword: keyword,
            pelaksana: pelaksana,
            kd_poli: currentBillingData ? currentBillingData.kd_poli : null,
            limit: 30
        }).done((response) => {
            masterTindakanSearchResults = response || [];
            renderMasterTindakanTable(masterTindakanSearchResults);
        }).fail(() => {
            tbody.html('<tr><td colspan="4" class="text-center text-danger p-3 small"><i class="ti ti-x"></i> Gagal memuat daftar tindakan</td></tr>');
        });
    }

    function renderMasterTindakanTable(data) {
        const tbody = $('#tabelHasilPencarianTindakan tbody');
        const pelaksana = $('input[name="kasirTindakanType"]:checked').val();

        if (!data || data.length === 0) {
            tbody.html('<tr><td colspan="4" class="text-center text-muted p-3 small">Tindakan tidak ditemukan</td></tr>');
            return;
        }

        let rows = '';
        data.forEach((item, index) => {
            let price = 0;
            if (pelaksana === 'dr') price = parseFloat(item.total_byrdr) || 0;
            else if (pelaksana === 'pr') price = parseFloat(item.total_byrpr) || 0;
            else if (pelaksana === 'drpr') price = parseFloat(item.total_byrdrpr) || 0;

            rows += `<tr>
                <td class="font-monospace small">${item.kd_jenis_prw}</td>
                <td>
                    <div class="fw-bold text-dark small">${item.nm_perawatan}</div>
                    <div class="text-muted" style="font-size: 11px;">Kategori: ${item.kd_kategori || '-'}</div>
                </td>
                <td class="text-end fw-bold text-primary small">Rp ${new Intl.NumberFormat('id-ID').format(price)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-primary px-2" onclick="addTindakanToCart(${index})">
                        <i class="ti ti-plus"></i> Tambah
                    </button>
                </td>
            </tr>`;
        });
        tbody.html(rows);
    }

    function addTindakanToCart(index) {
        const item = masterTindakanSearchResults[index];
        if (!item) return;

        const pelaksana = $('input[name="kasirTindakanType"]:checked').val();
        let price = 0;
        if (pelaksana === 'dr') price = parseFloat(item.total_byrdr) || 0;
        else if (pelaksana === 'pr') price = parseFloat(item.total_byrpr) || 0;
        else if (pelaksana === 'drpr') price = parseFloat(item.total_byrdrpr) || 0;

        const diskon = parseFloat($('#kasirTindakanDiskon').val()) || 0;
        const subtotal = Math.max(0, price - diskon);

        const cartItem = {
            kd_jenis_prw: item.kd_jenis_prw,
            nm_perawatan: item.nm_perawatan,
            type: pelaksana,
            pelaksanaLabel: pelaksana === 'dr' ? 'Dokter' : (pelaksana === 'pr' ? 'Petugas' : 'Dr & Petugas'),
            tarif_tindakandr: parseFloat(item.tarif_tindakandr) || 0,
            tarif_tindakanpr: parseFloat(item.tarif_tindakanpr) || 0,
            material: parseFloat(item.material) || 0,
            bhp: parseFloat(item.bhp) || 0,
            kso: parseFloat(item.kso) || 0,
            menejemen: parseFloat(item.menejemen) || 0,
            price: price,
            diskon: diskon,
            subtotal: subtotal
        };

        tindakanCart.push(cartItem);
        renderTindakanCart();

        // reset diskon input
        $('#kasirTindakanDiskon').val(0);
    }

    function removeTindakanFromCart(idx) {
        tindakanCart.splice(idx, 1);
        renderTindakanCart();
    }

    function renderTindakanCart() {
        const tbody = $('#tabelCartTindakan tbody');
        $('#countTindakanCart').text(tindakanCart.length);

        if (tindakanCart.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center text-muted py-3 small">Belum ada tindakan yang dipilih</td></tr>');
            $('#totalTindakanCart').text('Total: Rp 0');
            return;
        }

        let rows = '';
        let total = 0;
        tindakanCart.forEach((item, idx) => {
            total += item.subtotal;
            rows += `<tr>
                <td class="small">
                    <span class="fw-bold">${item.nm_perawatan}</span>
                    <span class="text-muted" style="font-size: 10px;">(${item.kd_jenis_prw})</span>
                </td>
                <td class="small"><span class="badge bg-blue-lt">${item.pelaksanaLabel}</span></td>
                <td class="text-end small">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</td>
                <td class="text-end small text-danger">${item.diskon > 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.diskon) : '-'}</td>
                <td class="text-end small fw-bold text-success">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="removeTindakanFromCart(${idx})" title="Hapus">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>`;
        });
        tbody.html(rows);
        $('#totalTindakanCart').text('Total: Rp ' + new Intl.NumberFormat('id-ID').format(total));
    }

    function loadExistingTindakanPasien(no_rawat) {
        const tbody = $('#tabelExistingTindakan tbody');
        tbody.html('<tr><td colspan="5" class="text-center text-muted py-2 small"><div class="spinner-border spinner-border-sm text-secondary me-2"></div> Memuat...</td></tr>');

        $.get("{{ url('/pemeriksaan/tindakan-dokter/get') }}", { no_rawat: no_rawat })
            .done((response) => {
                const data = response.data || [];
                if (data.length === 0) {
                    tbody.html('<tr><td colspan="5" class="text-center text-muted py-2 small">Belum ada tindakan yang tercatat pada kunjungan ini</td></tr>');
                    return;
                }

                let rows = '';
                data.forEach((item) => {
                    const namaTindakan = item.tindakan ? item.tindakan.nm_perawatan : item.kd_jenis_prw;
                    rows += `<tr>
                        <td class="small text-muted">${item.tgl_perawatan} ${item.jam_rawat}</td>
                        <td class="small fw-bold">${namaTindakan}</td>
                        <td class="small">${item.pelaksana}<br><span class="text-muted" style="font-size: 10px;">${item.nama_pelaksana}</span></td>
                        <td class="text-end small fw-bold text-primary">Rp ${new Intl.NumberFormat('id-ID').format(item.biaya_rawat)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-xs btn-outline-danger" onclick="hapusExistingTindakan('${item.kd_jenis_prw}', '${item.no_rawat}', '${item.jam_rawat}', '${item.tgl_perawatan}', '${item.type}')" title="Hapus Tindakan">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                tbody.html(rows);
            }).fail(() => {
                tbody.html('<tr><td colspan="5" class="text-center text-danger py-2 small"><i class="ti ti-x"></i> Gagal memuat data tindakan</td></tr>');
            });
    }

    function hapusExistingTindakan(kd, no_rawat, jam, tgl, type) {
        Swal.fire({
            title: 'Hapus Tindakan?',
            text: 'Tindakan ini akan dihapus dari billing pasien dan jurnal terkait akan disesuaikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/pemeriksaan/tindakan-dokter/delete') }}",
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        no_rawat: no_rawat,
                        kd_dokter: currentBillingData ? currentBillingData.kd_dokter : '',
                        tindakan: [{
                            kd_jenis_prw: kd,
                            no_rawat: no_rawat,
                            jam_rawat: jam,
                            tgl_perawatan: tgl,
                            type: type
                        }]
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            text: 'Tindakan berhasil dihapus dari billing',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        loadExistingTindakanPasien(no_rawat);
                        loadKasirBillingDetails(no_rawat);
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal menghapus tindakan', 'error');
                    }
                });
            }
        });
    }

    function simpanTindakanKasir() {
        if (!selectedNoRawat || !currentBillingData) {
            Swal.fire('Peringatan', 'Data pasien belum dipilih', 'warning');
            return;
        }

        if (tindakanCart.length === 0) {
            Swal.fire('Peringatan', 'Silakan pilih minimal 1 tindakan terlebih dahulu!', 'warning');
            return;
        }

        const kd_dokter = $('#kasirTindakanDokter').val();
        const kd_petugas = $('#kasirTindakanPetugas').val();

        const payload = {
            _token: '{{ csrf_token() }}',
            no_rawat: currentBillingData.no_rawat,
            kd_dokter: kd_dokter,
            kd_petugas: kd_petugas,
            nm_pasien: currentBillingData.pasien,
            no_rkm_medis: currentBillingData.no_rm,
            tindakan: tindakanCart.map(item => ({
                kd_jenis_prw: item.kd_jenis_prw,
                type: item.type,
                tarif_tindakandr: item.tarif_tindakandr,
                tarif_tindakanpr: item.tarif_tindakanpr,
                material: item.material,
                bhp: item.bhp,
                kso: item.kso,
                menejemen: item.menejemen,
                price: item.price,
                diskonPersen: "0",
                diskonRupiah: String(item.diskon || 0)
            }))
        };

        $('#btnSimpanTindakanKasir').prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1"></div> Menyimpan...');

        $.post("{{ url('/pemeriksaan/tindakan-dokter') }}", payload)
            .done((res) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Tindakan berhasil ditambahkan ke billing pasien',
                    timer: 1500,
                    showConfirmButton: false
                });
                $('#modalTambahTindakanKasir').modal('hide');
                tindakanCart = [];
                loadKasirBillingDetails(selectedNoRawat);
            })
            .fail((xhr) => {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal menyimpan tindakan', 'error');
            })
            .always(() => {
                $('#btnSimpanTindakanKasir').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Tindakan ke Billing');
            });
    }

    $(document).on('keypress', '#kasirSearchTindakanInput', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            searchMasterTindakan();
        }
    });
</script>
@endpush
@endsection

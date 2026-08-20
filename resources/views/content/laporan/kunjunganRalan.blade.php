@extends('layout')

@section('title', 'Laporan Kunjungan Rawat Jalan')

@section('body')
<div class="container-xl">
    <!-- Page Header -->
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">Modul Laporan</div>
                <h2 class="page-title text-primary"><i class="ti ti-report-medical me-2"></i>Laporan Kunjungan Rawat Jalan</h2>
                <div class="text-muted mt-1">Rekapitulasi data kunjungan pasien rawat jalan berdasarkan tanggal, poliklinik, dokter, penjamin, dan demografi wilayah.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-outline-primary me-2" id="btnPrint">
                    <i class="ti ti-printer me-1"></i> Cetak Laporan
                </button>
                <button type="button" class="btn btn-success" id="btnExportExcel">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Stats Cards -->
    <div class="row row-cards mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar fs-2 shadow-sm">
                                <i class="ti ti-users"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-muted">Total Kunjungan</div>
                            <div class="h2 mb-0" id="statTotal">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar fs-2 shadow-sm">
                                <i class="ti ti-user-check"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-muted">Pasien Baru / Lama</div>
                            <div class="h2 mb-0">
                                <span class="text-success" id="statBaru">0</span> / <span class="text-secondary" id="statLama">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-info text-white avatar fs-2 shadow-sm">
                                <i class="ti ti-gender-male"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-muted">Laki-Laki (L)</div>
                            <div class="h2 mb-0" id="statLaki">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-pink text-white avatar fs-2 shadow-sm">
                                <i class="ti ti-gender-female"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-muted">Perempuan (P)</div>
                            <div class="h2 mb-0" id="statPerempuan">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <h3 class="card-title text-primary"><i class="ti ti-filter me-2"></i>Filter Data Kunjungan</h3>
        </div>
        <div class="card-body">
            <form id="formFilterKunjungan">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label required">Tanggal Awal</label>
                        <input type="text" class="form-control datepicker" name="tglAwal" id="tglAwal" value="{{ date('d-m-Y') }}" autocomplete="off" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Tanggal Akhir</label>
                        <input type="text" class="form-control datepicker" name="tglAkhir" id="tglAkhir" value="{{ date('d-m-Y') }}" autocomplete="off" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Poliklinik / Unit</label>
                        <select class="form-select select2-basic" name="kd_poli" id="kd_poli">
                            <option value="-">-- Semua Poliklinik --</option>
                            @foreach($poliklinik as $p)
                                <option value="{{ $p->kd_poli }}">{{ $p->nm_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dokter Pemeriksa</label>
                        <select class="form-select select2-basic" name="kd_dokter" id="kd_dokter">
                            <option value="-">-- Semua Dokter --</option>
                            @foreach($dokter as $d)
                                <option value="{{ $d->kd_dokter }}">{{ $d->nm_dokter }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Jenis Bayar / Penjab</label>
                        <select class="form-select select2-basic" name="kd_pj" id="kd_pj">
                            <option value="-">-- Semua Penjab --</option>
                            @foreach($penjab as $pj)
                                <option value="{{ $pj->kd_pj }}">{{ $pj->png_jawab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status Pasien</label>
                        <select class="form-select" name="stts_daftar" id="stts_daftar">
                            <option value="-">-- Semua Status --</option>
                            <option value="Lama">Pasien Lama</option>
                            <option value="Baru">Pasien Baru</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kabupaten / Kota</label>
                        <select class="form-select select2-basic" name="kd_kab" id="kd_kab">
                            <option value="">-- Semua Kabupaten --</option>
                            @foreach($kabupaten as $kab)
                                <option value="{{ $kab->kd_kab }}">{{ $kab->nm_kab }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kecamatan</label>
                        <select class="form-select select2-basic" name="kd_kec" id="kd_kec">
                            <option value="">-- Semua Kecamatan --</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->kd_kec }}">{{ $kec->nm_kec }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-9">
                        <label class="form-label">Pencarian Kata Kunci</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Ketik No. Rawat, No. RM, Nama Pasien, Alamat, atau Diagnosa...">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2" id="btnTampilkan">
                            <i class="ti ti-filter me-1"></i> Tampilkan Data
                        </button>
                        <button type="button" class="btn btn-light w-50" id="btnReset">
                            <i class="ti ti-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title text-white m-0"><i class="ti ti-table me-2"></i>Daftar Kunjungan Pasien Rawat Jalan</h3>
            <span class="badge bg-white text-primary fw-bold" id="labelPeriodeInfo">-</span>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter table-striped table-hover card-table" id="tableKunjunganRalan">
                <thead class="table-light">
                    <tr>
                        <th class="w-1 text-center">No</th>
                        <th class="text-center">Status</th>
                        <th>Tgl & Jam Reg</th>
                        <th>No. RM & Nama Pasien</th>
                        <th class="text-center">JK / Umur</th>
                        <th>Alamat Lengkap</th>
                        <th>Diagnosa Utama (ICD-10)</th>
                        <th>Dokter Jaga</th>
                        <th>Poliklinik</th>
                        <th>Penjab</th>
                    </tr>
                </thead>
                <tbody id="tbodyKunjunganRalan">
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="ti ti-info-circle me-1"></i> Silakan atur filter tanggal dan klik <strong>Tampilkan Data</strong>.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        if ($.fn.select2) {
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

        // Auto load on page view
        loadDataKunjungan();

        $('#formFilterKunjungan').on('submit', function(e) {
            e.preventDefault();
            loadDataKunjungan();
        });

        $('#btnReset').on('click', function() {
            $('#formFilterKunjungan')[0].reset();
            $('.select2-basic').val('-').trigger('change');
            $('#tglAwal').val('{{ date("d-m-Y") }}');
            $('#tglAkhir').val('{{ date("d-m-Y") }}');
            loadDataKunjungan();
        });

        $('#btnPrint').on('click', function() {
            window.print();
        });

        $('#btnExportExcel').on('click', function() {
            exportTableToExcel('tableKunjunganRalan', `Laporan_Kunjungan_Ralan_${$('#tglAwal').val()}_s.d_${$('#tglAkhir').val()}`);
        });
    });

    function loadDataKunjungan() {
        const tbody = $('#tbodyKunjunganRalan');
        tbody.html(`
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="spinner-border text-primary me-2" role="status"></div>
                    <span class="text-muted fw-bold">Memuat data kunjungan rawat jalan...</span>
                </td>
            </tr>
        `);

        const tglAwal = $('#tglAwal').val();
        const tglAkhir = $('#tglAkhir').val();
        $('#labelPeriodeInfo').text(`Periode: ${tglAwal} s/d ${tglAkhir}`);

        $.get(`{{ url('/laporan/kunjungan-ralan/data') }}`, $('#formFilterKunjungan').serialize())
            .done(function(res) {
                const summary = res.summary || { total: 0, pasien_baru: 0, pasien_lama: 0, laki_laki: 0, perempuan: 0 };
                $('#statTotal').text(summary.total);
                $('#statBaru').text(summary.pasien_baru);
                $('#statLama').text(summary.pasien_lama);
                $('#statLaki').text(summary.laki_laki);
                $('#statPerempuan').text(summary.perempuan);

                const data = res.data || [];
                if (data.length === 0) {
                    tbody.html(`
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="ti ti-alert-circle me-1 fs-3 d-block mb-1"></i>
                                Tidak ada data kunjungan rawat jalan ditemukan untuk kriteria filter ini.
                            </td>
                        </tr>
                    `);
                    return;
                }

                let html = '';
                data.forEach(item => {
                    const sttsBadge = item.stts_daftar.toLowerCase() === 'baru' 
                        ? '<span class="badge bg-success-lt">Baru</span>' 
                        : '<span class="badge bg-secondary-lt">Lama</span>';

                    const jkBadge = item.jk === 'L' 
                        ? '<span class="badge bg-info-lt">L</span>' 
                        : (item.jk === 'P' ? '<span class="badge bg-pink-lt">P</span>' : '-');

                    html += `
                        <tr>
                            <td class="text-center fw-bold">${item.no}</td>
                            <td class="text-center">${sttsBadge}</td>
                            <td class="small">${item.tgl_registrasi}</td>
                            <td>
                                <div><strong class="text-primary">${item.nm_pasien}</strong></div>
                                <small class="text-muted"><i class="ti ti-id me-1"></i>No. RM: <strong>${item.no_rkm_medis}</strong> | No. Rawat: ${item.no_rawat}</small>
                            </td>
                            <td class="text-center">
                                ${jkBadge}
                                <div class="small text-muted mt-1">${item.umur}</div>
                            </td>
                            <td class="small" style="max-width: 220px;">${item.alamat}</td>
                            <td>
                                ${item.kd_penyakit !== '-' ? `<div><span class="badge bg-purple-lt me-1">${item.kd_penyakit}</span> <strong>${item.nm_penyakit}</strong></div>` : '<span class="text-muted">-</span>'}
                            </td>
                            <td>${item.nm_dokter}</td>
                            <td><span class="badge bg-blue-lt">${item.nm_poli}</span></td>
                            <td><span class="badge bg-green-lt">${item.png_jawab}</span></td>
                        </tr>
                    `;
                });

                tbody.html(html);
            })
            .fail(function(err) {
                tbody.html(`
                    <tr>
                        <td colspan="10" class="text-center py-4 text-danger">
                            <i class="ti ti-alert-triangle me-1 fs-3 d-block mb-1"></i>
                            Gagal memuat data dari server. Silakan coba lagi.
                        </td>
                    </tr>
                `);
            });
    }

    function exportTableToExcel(tableID, filename = '') {
        let downloadLink;
        const dataType = 'application/vnd.ms-excel';
        const tableSelect = document.getElementById(tableID);
        const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        filename = filename ? filename + '.xls' : 'excel_data.xls';
        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if (navigator.msSaveOrOpenBlob) {
            const blob = new Blob(['\ufeff' + tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent('\ufeff' + tableHTML);
            downloadLink.download = filename;
            downloadLink.click();
        }
    }
</script>
@endpush

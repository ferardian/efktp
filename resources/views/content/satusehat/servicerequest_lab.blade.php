@extends('layout')

@section('body')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-blue-lt text-blue rounded-circle" style="width: 38px; height: 38px;">
                            <i class="ti ti-flask fs-2"></i>
                        </div>
                        <div>
                            <h2 class="page-title mb-0">Satu Sehat — ServiceRequest Laboratorium</h2>
                            <div class="text-muted small">Monitoring & Transmisi Order Pemeriksaan Laboratorium ke SatuSehat Kemenkes RI</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto d-flex gap-2">
                    <a href="{{ route('satusehat.mapping.lab.index') }}" class="btn btn-outline-teal btn-sm">
                        <i class="ti ti-dna me-1"></i> Mapping Lab (LOINC)
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" id="btnSyncBatch">
                        <i class="ti ti-cloud-upload me-1"></i> Sinkronisasi Massal (Batch)
                    </button>
                </div>
            </div>
        </div>

@push('style')
    <style>
        .filter-segmented-group {
            background-color: #f1f5f9;
            padding: 3px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 2px;
            border: 1px solid #e2e8f0;
        }

        .filter-segmented-group .btn-filter-status {
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 11.5px;
            font-weight: 500;
            padding: 4px 11px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            line-height: 1.4;
            white-space: nowrap;
        }

        .filter-segmented-group .btn-filter-status:hover {
            color: #1e293b;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .filter-segmented-group .btn-filter-status.active {
            background-color: #ffffff;
            color: #0f172a;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .filter-date-box {
            display: inline-flex;
            align-items: center;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0 8px;
            height: 32px;
            font-size: 11.5px;
            color: #334155;
            transition: all 0.2s ease;
        }

        .filter-date-box:focus-within {
            border-color: #206bc4;
            box-shadow: 0 0 0 3px rgba(32, 107, 196, 0.12);
        }

        .filter-date-box input[type="date"] {
            border: none;
            background: transparent;
            font-size: 11.5px;
            color: #1e293b;
            padding: 2px 4px;
            outline: none;
            box-shadow: none;
            cursor: pointer;
        }

        .search-box-filter {
            position: relative;
            min-width: 250px;
            max-width: 320px;
        }

        .search-box-filter .form-control {
            border-radius: 8px;
            font-size: 11.5px;
            padding-left: 32px;
            padding-right: 30px;
            height: 32px;
            background-color: #ffffff;
            border-color: #e2e8f0;
            transition: all 0.2s ease;
        }

        .search-box-filter .form-control:focus {
            border-color: #206bc4;
            box-shadow: 0 0 0 3px rgba(32, 107, 196, 0.12);
        }

        .search-box-filter .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            font-size: 13px;
        }

        .search-box-filter .clear-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 13px;
            padding: 2px;
            display: none;
        }

        .search-box-filter .clear-icon:hover {
            color: #475569;
        }
    </style>
@endpush

        <!-- Metric Counter Cards -->
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div class="card card-sm border-0 shadow-xs">
                    <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Parameter Order</div>
                            <div class="h3 mb-0 fw-bold" id="statTotal">0</div>
                        </div>
                        <div class="avatar bg-secondary-lt text-secondary rounded">
                            <i class="ti ti-list-details fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-sm border-0 shadow-xs">
                    <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Terkirim (ServiceRequest)</div>
                            <div class="h3 mb-0 fw-bold text-success" id="statSent">0</div>
                        </div>
                        <div class="avatar bg-success-lt text-success rounded">
                            <i class="ti ti-circle-check fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-sm border-0 shadow-xs">
                    <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Belum Terkirim</div>
                            <div class="h3 mb-0 fw-bold text-warning" id="statUnsent">0</div>
                        </div>
                        <div class="avatar bg-warning-lt text-warning rounded">
                            <i class="ti ti-clock-pause fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-sm border-0 shadow-xs">
                    <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Belum Mapping LOINC</div>
                            <div class="h3 mb-0 fw-bold text-danger" id="statUnmapped">0</div>
                        </div>
                        <div class="avatar bg-danger-lt text-danger rounded">
                            <i class="ti ti-alert-triangle fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Table Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Date Range Box -->
                    <div class="filter-date-box shadow-xs" title="Filter Rentang Tanggal Permintaan">
                        <i class="ti ti-calendar text-muted me-1"></i>
                        <input type="date" id="filterTglAwal" value="{{ date('Y-m-d') }}">
                        <span class="text-muted small px-1">s/d</span>
                        <input type="date" id="filterTglAkhir" value="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Segmented Status Tabs -->
                    <div class="filter-segmented-group shadow-xs">
                        <button type="button" class="btn-filter-status active" data-status="unsent" onclick="setStatusFilter('unsent')">
                            <i class="ti ti-clock-pause text-warning me-1"></i> Belum Dikirim
                        </button>
                        <button type="button" class="btn-filter-status" data-status="sent" onclick="setStatusFilter('sent')">
                            <i class="ti ti-circle-check text-success me-1"></i> Terkirim
                        </button>
                        <button type="button" class="btn-filter-status" data-status="" onclick="setStatusFilter('')">
                            Semua
                        </button>
                    </div>

                    <input type="hidden" id="filterStatus" value="unsent">

                    <!-- Modern Search Box with Live Search & Clear Icon -->
                    <div class="search-box-filter shadow-xs">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" id="searchKeyword" class="form-control" placeholder="Cari No. Order, Pasien, RM, Parameter..." autocomplete="off">
                        <i class="ti ti-x clear-icon" id="btnClearSearch" onclick="clearSearch()" title="Hapus pencarian"></i>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small d-none d-md-inline" id="labelRowCount">Memuat data...</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefresh" title="Refresh Data">
                        <i class="ti ti-refresh me-1"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-vcenter table-hover card-table mb-0" id="tableServiceRequestLab" style="font-size: 11px;">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th style="width: 35px;" class="text-center">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                </th>
                                <th style="width: 130px;">Waktu & No.Order</th>
                                <th>Pasien (RM / NIK)</th>
                                <th>Dokter Perujuk</th>
                                <th>Encounter SatuSehat</th>
                                <th>Pemeriksaan & Parameter</th>
                                <th>Standar LOINC</th>
                                <th class="text-center" style="width: 160px;">Status Bridging</th>
                                <th class="text-center" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyServiceRequestLab">
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Silakan klik cari untuk memuat data permintaan laboratorium</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Detail JSON -->
    <div class="modal modal-blur fade" id="modalDetailPayload" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-dark text-white py-2">
                    <h5 class="modal-title mb-0"><i class="ti ti-code me-1"></i> Data ServiceRequest FHIR</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <span class="badge bg-blue" id="detailOrderBadge">Order</span>
                        <span class="badge bg-teal" id="detailParamBadge">Param</span>
                    </div>
                    <label class="form-label small fw-bold">Payload / Respon JSON:</label>
                    <pre class="bg-dark text-light p-3 rounded font-monospace small" id="jsonPreviewContainer" style="max-height: 400px; overflow-y: auto;">
                    </pre>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let labDataStore = [];
        let searchTimeout = null;

        $(document).ready(function() {
            loadServiceRequestData();

            $('#btnRefresh').on('click', function() {
                loadServiceRequestData();
            });

            // Live search with debounce
            $('#searchKeyword').on('input', function() {
                const val = $(this).val();
                if (val.length > 0) {
                    $('#btnClearSearch').show();
                } else {
                    $('#btnClearSearch').hide();
                }

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    loadServiceRequestData();
                }, 350);
            });

            $('#searchKeyword').on('keypress', function(e) {
                if (e.which === 13) {
                    clearTimeout(searchTimeout);
                    loadServiceRequestData();
                }
            });

            // Auto reload when date changed
            $('#filterTglAwal, #filterTglAkhir').on('change', function() {
                loadServiceRequestData();
            });

            $('#checkAll').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.row-checkbox').prop('checked', isChecked);
            });

            $('#btnSyncBatch').on('click', function() {
                handleBatchSync();
            });
        });

        function setStatusFilter(status) {
            $('#filterStatus').val(status);
            $('.btn-filter-status').removeClass('active');
            $(`.btn-filter-status[data-status="${status}"]`).addClass('active');
            loadServiceRequestData();
        }

        function clearSearch() {
            $('#searchKeyword').val('');
            $('#btnClearSearch').hide();
            loadServiceRequestData();
        }

        function loadServiceRequestData() {
            const tglAwal = $('#filterTglAwal').val();
            const tglAkhir = $('#filterTglAkhir').val();
            const status = $('#filterStatus').val();
            const search = $('#searchKeyword').val();

            $('#labelRowCount').text('Memuat data...');
            $('#tbodyServiceRequestLab').html('<tr><td colspan="9" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Memuat data permintaan lab...</td></tr>');
            $('#checkAll').prop('checked', false);

            $.get(`{{ route('satusehat.servicerequest-lab.data') }}`, {
                tgl_awal: tglAwal,
                tgl_akhir: tglAkhir,
                status: status,
                search: search
            }).done(function(res) {
                if (res.success) {
                    labDataStore = res.data;
                    renderTable(res.data);
                } else {
                    $('#labelRowCount').text('0 parameter');
                    $('#tbodyServiceRequestLab').html(`<tr><td colspan="9" class="text-center py-4 text-danger">${res.message}</td></tr>`);
                }
            }).fail(function(err) {
                $('#labelRowCount').text('0 parameter');
                $('#tbodyServiceRequestLab').html('<tr><td colspan="9" class="text-center py-4 text-danger">Gagal memuat data dari server</td></tr>');
            });
        }

        function renderTable(data) {
            let html = '';
            let countTotal = data ? data.length : 0;
            let countSent = 0;
            let countUnsent = 0;
            let countUnmapped = 0;

            if (!data || data.length === 0) {
                $('#labelRowCount').text('0 parameter');
                $('#tbodyServiceRequestLab').html('<tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data permintaan laboratorium ditemukan pada periode ini</td></tr>');
                updateCounters(0, 0, 0, 0);
                return;
            }

            $('#labelRowCount').html(`Menampilkan <strong>${data.length}</strong> parameter`);

            data.forEach(function(item, idx) {
                const isSent = !!item.id_servicerequest;
                const isMapped = !!item.code;
                const hasEncounter = !!item.id_encounter;

                if (isSent) countSent++; else countUnsent++;
                if (!isMapped) countUnmapped++;

                const key = `${item.noorder}___${item.kd_jenis_prw}___${item.id_template}`;

                html += `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input row-checkbox" value="${key}" ${isSent ? 'disabled' : ''}>
                        </td>
                        <td>
                            <div><strong class="text-primary">${item.noorder}</strong></div>
                            <div class="text-muted"><i class="ti ti-clock me-1"></i>${item.tgl_permintaan} ${item.jam_permintaan}</div>
                            <small class="badge bg-light text-dark font-monospace">${item.no_rawat}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">${item.nm_pasien}</div>
                            <div class="text-muted"><small>RM: <code>${item.no_rkm_medis}</code></small></div>
                            <small class="text-muted"><i class="ti ti-id me-1"></i>NIK: ${item.no_ktp ? item.no_ktp : '<span class="text-danger fw-bold">KOSONG</span>'}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">${item.nm_dokter || '-'}</div>
                            <small class="text-muted">NIK: ${item.ktp_dokter ? item.ktp_dokter : '<span class="text-danger">KOSONG</span>'}</small>
                        </td>
                        <td>
                            ${hasEncounter ? `
                                <div><code class="text-success small">${item.id_encounter}</code></div>
                                <span class="badge bg-green-lt"><i class="ti ti-check me-1"></i>Encounter OK</span>
                            ` : `
                                <span class="badge bg-red-lt" title="Harap sinkronkan Encounter kunjungan ini terlebih dahulu"><i class="ti ti-alert-triangle me-1"></i>Belum Ada Encounter</span>
                            `}
                        </td>
                        <td>
                            <div class="fw-bold text-dark">${item.nm_perawatan}</div>
                            <div class="text-primary fw-semibold"><i class="ti ti-chevron-right me-1"></i>${item.Pemeriksaan}</div>
                            ${item.diagnosa_klinis && item.diagnosa_klinis !== '-' ? `<small class="text-muted fst-italic">Klinis: ${item.diagnosa_klinis}</small>` : ''}
                        </td>
                        <td>
                            ${isMapped ? `
                                <div><code class="fw-bold text-dark">${item.code}</code></div>
                                <small class="text-muted">${item.display || '-'}</small>
                            ` : `
                                <span class="badge bg-warning-lt mb-1"><i class="ti ti-alert-circle me-1"></i>Belum Mapping</span><br>
                                <a href="{{ route('satusehat.mapping.lab.index') }}" class="btn btn-outline-teal btn-xs py-0 px-1 mt-1">
                                    <i class="ti ti-pencil me-1"></i>Map LOINC
                                </a>
                            `}
                        </td>
                        <td class="text-center">
                            ${isSent ? `
                                <span class="badge bg-success text-white py-1 px-2 d-inline-block text-truncate" style="max-width: 150px;" title="ID ServiceRequest: ${item.id_servicerequest}">
                                    <i class="ti ti-check me-1"></i>${item.id_servicerequest}
                                </span>
                            ` : `
                                <span class="badge bg-secondary-lt py-1 px-2"><i class="ti ti-hourglass me-1"></i>Belum Terkirim</span>
                            `}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-sm ${isSent ? 'btn-outline-secondary' : 'btn-primary'}"
                                    onclick="sendSingleServiceRequest(${idx})"
                                    title="${isSent ? 'Kirim Ulang / Update (PUT)' : 'Kirim ke SatuSehat'}">
                                    <i class="ti ${isSent ? 'ti-refresh' : 'ti-send'}"></i>
                                </button>
                                ${isSent ? `
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewJsonDetail(${idx})" title="Lihat Detail ServiceRequest">
                                        <i class="ti ti-code"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#tbodyServiceRequestLab').html(html);
            updateCounters(countTotal, countSent, countUnsent, countUnmapped);
        }

        function updateCounters(total, sent, unsent, unmapped) {
            $('#statTotal').text(total);
            $('#statSent').text(sent);
            $('#statUnsent').text(unsent);
            $('#statUnmapped').text(unmapped);
        }

        function sendSingleServiceRequest(idx) {
            const data = labDataStore[idx];
            if (!data) return;

            if (!data.id_encounter) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Encounter Belum Tersedia',
                    text: 'Kunjungan pasien ini belum disinkronkan ke SatuSehat Encounter. Harap sync Encounter terlebih dahulu.',
                });
                return;
            }

            if (!data.code) {
                Swal.fire({
                    icon: 'warning',
                    title: 'LOINC Belum Dimapping',
                    text: `Parameter "${data.Pemeriksaan}" belum memiliki kode LOINC. Silakan mapping terlebih dahulu di menu Mapping Lab.`,
                });
                return;
            }

            Swal.fire({
                title: 'Kirim ServiceRequest?',
                text: `Kirim order "${data.Pemeriksaan}" (${data.noorder}) ke SatuSehat Kemenkes?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#206bc4',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kirim Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim ke SatuSehat...',
                        text: 'Memproses handshake token & pembuatan payload FHIR ServiceRequest...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.post(`{{ route('satusehat.servicerequest-lab.send') }}`, data)
                        .done(function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil Terkirim',
                                    html: `ServiceRequest berhasil diproses.<br><b>ID SatuSehat:</b> <code>${res.id_servicerequest}</code>`,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                                loadServiceRequestData();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Kirim',
                                    text: res.message || 'Terjadi penolakan dari server SatuSehat',
                                });
                            }
                        })
                        .fail(function(err) {
                            const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Terjadi kesalahan sistem';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: msg,
                            });
                        });
                }
            });
        }

        function handleBatchSync() {
            const tglAwal = $('#filterTglAwal').val();
            const tglAkhir = $('#filterTglAkhir').val();

            const selectedKeys = [];
            $('.row-checkbox:checked').each(function() {
                selectedKeys.push($(this).val());
            });

            let confirmText = '';
            if (selectedKeys.length > 0) {
                confirmText = `Kirim ${selectedKeys.length} parameter lab yang dipilih ke SatuSehat?`;
            } else {
                confirmText = `Kirim SEMUA parameter lab yang belum terkirim pada periode ${tglAwal} s/d ${tglAkhir}?`;
            }

            Swal.fire({
                title: 'Sinkronisasi Massal (Batch)',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#206bc4',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Jalankan Sinkronisasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sinkronisasi Batch Sedang Berjalan...',
                        html: 'Mohon tunggu beberapa saat. Sistem memberikan jeda per request untuk menjaga kuota API Kemenkes.<br><div class="progress mt-3"><div class="progress-bar progress-bar-indeterminate bg-primary"></div></div>',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });

                    $.post(`{{ route('satusehat.servicerequest-lab.sync-batch') }}`, {
                        tgl_awal: tglAwal,
                        tgl_akhir: tglAkhir,
                        keys: selectedKeys
                    }).done(function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sinkronisasi Selesai',
                                html: `
                                    <div class="text-start">
                                        <p class="mb-1">${res.message}</p>
                                    </div>
                                `,
                            }).then(() => {
                                loadServiceRequestData();
                            });
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }).fail(function(err) {
                        const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Terjadi kegagalan sinkronisasi batch';
                        Swal.fire('Error', msg, 'error');
                    });
                }
            });
        }

        function viewJsonDetail(idx) {
            const data = labDataStore[idx];
            if (!data) return;

            $('#detailOrderBadge').text(`No.Order: ${data.noorder}`);
            $('#detailParamBadge').text(`Parameter: ${data.Pemeriksaan}`);

            const info = {
                no_rawat: data.no_rawat,
                noorder: data.noorder,
                id_servicerequest: data.id_servicerequest,
                pasien: {
                    nama: data.nm_pasien,
                    no_rkm_medis: data.no_rkm_medis,
                    no_ktp: data.no_ktp
                },
                dokter_perujuk: {
                    nama: data.nm_dokter,
                    nik: data.ktp_dokter
                },
                encounter: {
                    id_encounter: data.id_encounter
                },
                pemeriksaan: {
                    paket: data.nm_perawatan,
                    parameter: data.Pemeriksaan,
                    loinc_code: data.code,
                    loinc_display: data.display
                },
                waktu_order: `${data.tgl_permintaan}T${data.jam_permintaan}+07:00`
            };

            $('#jsonPreviewContainer').text(JSON.stringify(info, null, 2));
            $('#modalDetailPayload').modal('show');
        }
    </script>
@endpush

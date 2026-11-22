@extends('layout')

@section('body')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-purple-lt text-purple rounded-circle" style="width: 38px; height: 38px;">
                            <i class="ti ti-activity fs-2"></i>
                        </div>
                        <div>
                            <h2 class="page-title mb-0">Satu Sehat — Procedure (Tindakan Pasien)</h2>
                            <div class="text-muted small">Pemantauan & Transmisi Tindakan / Prosedur Medis (ICD-9-CM) ke SatuSehat Kemenkes RI</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto d-flex gap-2">
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
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="ti ti-activity fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-total">0</div>
                                <div class="text-muted small">Total Tindakan (ICD-9)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar">
                                    <i class="ti ti-check fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-synced">0</div>
                                <div class="text-muted small">Tersinkron (Synced)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <i class="ti ti-clock fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-unsent">0</div>
                                <div class="text-muted small">Belum Tersinkron</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <i class="ti ti-user-check fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-encounter-ready">0</div>
                                <div class="text-muted small">Encounter Siap</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-body py-2 px-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Date Range -->
                        <div class="filter-date-box">
                            <i class="ti ti-calendar me-1 text-muted"></i>
                            <input type="date" id="start_date" value="{{ date('Y-m-d') }}">
                            <span class="text-muted mx-1">s/d</span>
                            <input type="date" id="end_date" value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Status Filter -->
                        <div class="filter-segmented-group" id="filterStatusGroup">
                            <button type="button" class="btn-filter-status active" data-status="all">Semua</button>
                            <button type="button" class="btn-filter-status" data-status="unsent">Belum Sync</button>
                            <button type="button" class="btn-filter-status" data-status="synced">Synced</button>
                        </div>

                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefresh" title="Refresh">
                            <i class="ti ti-refresh"></i>
                        </button>
                    </div>

                    <!-- Search Filter -->
                    <div class="search-box-filter">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" class="form-control" id="searchKeyword" placeholder="Cari No. Rawat, Pasien, ICD-9...">
                        <i class="ti ti-x clear-icon" id="btnClearSearch"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-striped" id="tableProcedure">
                    <thead>
                        <tr>
                            <th class="w-1 text-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                            </th>
                            <th>No. Rawat & Waktu</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Kode ICD-9</th>
                            <th>Deskripsi Tindakan</th>
                            <th class="text-center">Status / Prioritas</th>
                            <th class="text-center">Encounter</th>
                            <th class="text-center">Status Sync</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyProcedure">
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <span class="spinner-border spinner-border-sm me-2"></span> Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Batch Sync Progress -->
    <div class="modal fade" id="modalBatchSync" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-cloud-upload me-2 text-primary"></i> Sinkronisasi Massal Procedure</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="batchSyncLoading">
                        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
                        <h4 class="mb-1">Sedang Mengirim ke SatuSehat...</h4>
                        <p class="text-muted small mb-0">Mohon jangan menutup jendela ini hingga proses selesai.</p>
                    </div>
                    <div id="batchSyncResult" style="display: none;">
                        <div id="batchSyncIcon" class="mb-3"></div>
                        <h4 id="batchSyncTitle" class="mb-1"></h4>
                        <p id="batchSyncMessage" class="text-muted small mb-3"></p>
                        <div id="batchSyncDetails" class="text-start bg-light p-2 rounded small" style="max-height: 150px; overflow-y: auto;"></div>
                    </div>
                </div>
                <div class="modal-footer" id="batchSyncFooter" style="display: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let currentStatus = 'all';
            let allData = [];

            loadData();

            $('#btnRefresh, #start_date, #end_date').on('change click', function() {
                loadData();
            });

            $('.btn-filter-status').on('click', function() {
                $('.btn-filter-status').removeClass('active');
                $(this).addClass('active');
                currentStatus = $(this).data('status');
                loadData();
            });

            let searchTimeout = null;
            $('#searchKeyword').on('keyup', function() {
                const val = $(this).val();
                $('#btnClearSearch').toggle(val.length > 0);
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadData();
                }, 400);
            });

            $('#btnClearSearch').on('click', function() {
                $('#searchKeyword').val('');
                $(this).hide();
                loadData();
            });

            $('#checkAll').on('change', function() {
                $('.check-item:not(:disabled)').prop('checked', $(this).is(':checked'));
            });

            function loadData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const search = $('#searchKeyword').val();

                $('#tbodyProcedure').html(`
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <span class="spinner-border spinner-border-sm me-2"></span> Memuat data...
                        </td>
                    </tr>
                `);

                $.get("{{ route('satusehat.procedure.data') }}", {
                    start_date: startDate,
                    end_date: endDate,
                    status: currentStatus,
                    search: search
                }, function(res) {
                    if (res.status) {
                        allData = res.data;
                        renderTable(allData);
                        updateStats(allData);
                    } else {
                        $('#tbodyProcedure').html(`
                            <tr>
                                <td colspan="10" class="text-center py-4 text-danger">
                                    <i class="ti ti-alert-triangle me-1"></i> Gagal memuat data.
                                </td>
                            </tr>
                        `);
                    }
                }).fail(function() {
                    $('#tbodyProcedure').html(`
                        <tr>
                            <td colspan="10" class="text-center py-4 text-danger">
                                <i class="ti ti-alert-triangle me-1"></i> Gagal menghubungi server.
                            </td>
                        </tr>
                    `);
                });
            }

            function updateStats(data) {
                let total = data.length;
                let synced = 0;
                let unsent = 0;
                let encounterReady = 0;

                data.forEach(item => {
                    if (item.id_procedure) {
                        synced++;
                    } else {
                        unsent++;
                    }
                    if (item.id_encounter) {
                        encounterReady++;
                    }
                });

                $('#stat-total').text(total);
                $('#stat-synced').text(synced);
                $('#stat-unsent').text(unsent);
                $('#stat-encounter-ready').text(encounterReady);
            }

            function renderTable(data) {
                if (data.length === 0) {
                    $('#tbodyProcedure').html(`
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                Tidak ada data tindakan/prosedur untuk periode dan filter ini.
                            </td>
                        </tr>
                    `);
                    return;
                }

                let html = '';
                data.forEach(item => {
                    const isSynced = !!item.id_procedure;
                    const isEncounterReady = !!item.id_encounter;
                    const isPatientReady = !!item.ktp_pasien;

                    const canSync = !isSynced && isEncounterReady && isPatientReady;
                    const key = `${item.no_rawat}|${item.kode}|${item.status_ralan_ranap}`;

                    let statusBadge = '';
                    if (isSynced) {
                        statusBadge = `<span class="badge bg-success-lt text-success" title="ID: ${item.id_procedure}">
                            <i class="ti ti-check me-1"></i> Synced
                        </span>`;
                    } else if (!isEncounterReady) {
                        statusBadge = `<span class="badge bg-warning-lt text-warning" title="Encounter pasien belum sync">
                            <i class="ti ti-clock me-1"></i> Encounter Pending
                        </span>`;
                    } else if (!isPatientReady) {
                        statusBadge = `<span class="badge bg-danger-lt text-danger">
                            NIK Pasien Kosong
                        </span>`;
                    } else {
                        statusBadge = `<span class="badge bg-secondary-lt text-secondary">
                            Belum Sync
                        </span>`;
                    }

                    const prioritasBadge = item.prioritas == 1 
                        ? `<span class="badge bg-blue-lt">Utama</span>` 
                        : `<span class="badge bg-secondary-lt">Tambahan</span>`;

                    html += `<tr>
                        <td class="text-center">
                            <input class="form-check-input check-item" type="checkbox" value="${key}" ${canSync ? '' : 'disabled'}>
                        </td>
                        <td>
                            <strong class="text-primary">${item.no_rawat}</strong><br>
                            <small class="text-muted"><i class="ti ti-calendar me-1"></i>${item.tgl_registrasi} ${item.jam_reg || ''}</small>
                        </td>
                        <td>
                            <strong>${item.nm_pasien}</strong><br>
                            <small class="text-muted">RM: ${item.no_rkm_medis} | KTP: ${item.ktp_pasien || '-'}</small>
                        </td>
                        <td>
                            <span>${item.nama_dokter || '-'}</span><br>
                            <small class="text-muted">KTP: ${item.ktp_dokter || '-'}</small>
                        </td>
                        <td>
                            <span class="badge bg-dark-lt font-monospace fs-3">${item.kode}</span>
                        </td>
                        <td>
                            <strong>${item.deskripsi_panjang || item.kode}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-muted">${item.status_ralan_ranap}</span> ${prioritasBadge}
                        </td>
                        <td class="text-center">
                            ${isEncounterReady 
                                ? `<span class="badge bg-success-lt" title="${item.id_encounter}"><i class="ti ti-check"></i> Ready</span>` 
                                : `<span class="badge bg-warning-lt">Pending</span>`
                            }
                        </td>
                        <td class="text-center">
                            ${statusBadge}
                            ${item.id_procedure ? `<br><small class="text-muted text-truncate d-inline-block" style="max-width: 120px;" title="${item.id_procedure}">${item.id_procedure}</small>` : ''}
                        </td>
                        <td class="text-center">
                            ${isSynced 
                                ? `<button class="btn btn-sm btn-outline-secondary btn-sync-single" data-rawat="${item.no_rawat}" data-kode="${item.kode}" data-status="${item.status_ralan_ranap}" title="Sync Ulang">
                                    <i class="ti ti-refresh"></i>
                                   </button>`
                                : `<button class="btn btn-sm btn-primary btn-sync-single" data-rawat="${item.no_rawat}" data-kode="${item.kode}" data-status="${item.status_ralan_ranap}" ${canSync ? '' : 'disabled'}>
                                    <i class="ti ti-cloud-upload me-1"></i> Sync
                                   </button>`
                            }
                        </td>
                    </tr>`;
                });

                $('#tbodyProcedure').html(html);
            }

            // Single Item Sync
            $(document).on('click', '.btn-sync-single', function() {
                const btn = $(this);
                const noRawat = btn.data('rawat');
                const kode = btn.data('kode');
                const status = btn.data('status');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.post("{{ route('satusehat.procedure.sync') }}", {
                    _token: "{{ csrf_token() }}",
                    no_rawat: noRawat,
                    kode: kode,
                    status: status
                }, function(res) {
                    if (res.status) {
                        alertify.success('Procedure berhasil disinkronkan!');
                        loadData();
                    } else {
                        alertify.error(res.message || 'Gagal sinkronisasi');
                        btn.prop('disabled', false).html('<i class="ti ti-cloud-upload me-1"></i> Sync');
                    }
                }).fail(function(xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                    alertify.error(msg);
                    btn.prop('disabled', false).html('<i class="ti ti-cloud-upload me-1"></i> Sync');
                });
            });

            // Batch Sync
            $('#btnSyncBatch').on('click', function() {
                const selected = [];
                $('.check-item:checked').each(function() {
                    selected.push($(this).val());
                });

                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                const msg = selected.length > 0 
                    ? `Sinkronkan ${selected.length} tindakan/prosedur terpilih ke SatuSehat?` 
                    : `Sinkronkan semua tindakan/prosedur yang belum terkirim (${startDate} s/d ${endDate})?`;

                if (!confirm(msg)) return;

                $('#batchSyncLoading').show();
                $('#batchSyncResult').hide();
                $('#batchSyncFooter').hide();
                $('#modalBatchSync').modal('show');

                $.post("{{ route('satusehat.procedure.sync-batch') }}", {
                    _token: "{{ csrf_token() }}",
                    start_date: startDate,
                    end_date: endDate,
                    keys: selected
                }, function(res) {
                    $('#batchSyncLoading').hide();
                    $('#batchSyncResult').show();
                    $('#batchSyncFooter').show();

                    if (res.status) {
                        const d = res.data;
                        $('#batchSyncIcon').html('<i class="ti ti-circle-check text-success" style="font-size: 3rem;"></i>');
                        $('#batchSyncTitle').text('Sinkronisasi Selesai');
                        $('#batchSyncMessage').html(`Total: <b>${d.total}</b> | Berhasil: <b class="text-success">${d.success}</b> | Gagal: <b class="text-danger">${d.error}</b> | Dilewati: <b class="text-warning">${d.skip}</b>`);

                        if (d.messages && d.messages.length > 0) {
                            let detailsHtml = '<ul class="mb-0 ps-3">';
                            d.messages.forEach(m => { detailsHtml += `<li class="text-danger">${m}</li>`; });
                            detailsHtml += '</ul>';
                            $('#batchSyncDetails').html(detailsHtml).show();
                        } else {
                            $('#batchSyncDetails').hide();
                        }
                    }
                    loadData();
                }).fail(function(xhr) {
                    $('#batchSyncLoading').hide();
                    $('#batchSyncResult').show();
                    $('#batchSyncFooter').show();

                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                    $('#batchSyncIcon').html('<i class="ti ti-alert-triangle text-danger" style="font-size: 3rem;"></i>');
                    $('#batchSyncTitle').text('Gagal');
                    $('#batchSyncMessage').text(msg);
                    $('#batchSyncDetails').hide();
                });
            });
        });
    </script>
@endpush

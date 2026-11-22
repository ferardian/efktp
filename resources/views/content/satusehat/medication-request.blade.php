@extends('layout')

@section('body')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-blue-lt text-blue rounded-circle" style="width: 38px; height: 38px;">
                            <i class="ti ti-prescription fs-2"></i>
                        </div>
                        <div>
                            <h2 class="page-title mb-0">Satu Sehat — Medication Request (Resep Obat)</h2>
                            <div class="text-muted small">Pemantauan & Sinkronisasi Peresepan Obat Dokter ke SatuSehat FHIR Kemenkes RI</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto d-flex gap-2">
                    <a href="{{ route('satusehat.medication.index') }}" class="btn btn-outline-teal btn-sm">
                        <i class="ti ti-pill me-1"></i> Mapping KFA Medication
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
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="ti ti-list-numbers fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-total">0</div>
                                <div class="text-muted small">Total Peresepan Item</div>
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
                                <span class="bg-danger text-white avatar">
                                    <i class="ti ti-alert-triangle fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-notready">0</div>
                                <div class="text-muted small">Belum Mapping KFA</div>
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
                            <input type="date" id="tgl_awal" value="{{ date('Y-m-d') }}">
                            <span class="text-muted mx-1">s/d</span>
                            <input type="date" id="tgl_akhir" value="{{ date('Y-m-d') }}">
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
                        <input type="text" class="form-control" id="searchKeyword" placeholder="Cari No. Resep, Pasien, Obat...">
                        <i class="ti ti-x clear-icon" id="btnClearSearch"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-striped" id="tableMedicationRequest">
                    <thead>
                        <tr>
                            <th class="w-1 text-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                            </th>
                            <th>No. Resep & Waktu</th>
                            <th>Pasien & No. Rawat</th>
                            <th>Dokter Peresep</th>
                            <th>Obat / Alkes</th>
                            <th class="text-center">Jml & Signa</th>
                            <th class="text-center">KFA Medication</th>
                            <th class="text-center">Encounter</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyMedicationRequest">
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
                    <h5 class="modal-title"><i class="ti ti-cloud-upload me-2 text-primary"></i> Sinkronisasi Massal MedicationRequest</h5>
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

            $('#btnRefresh, #tgl_awal, #tgl_akhir').on('change click', function() {
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
                const tglAwal = $('#tgl_awal').val();
                const tglAkhir = $('#tgl_akhir').val();
                const search = $('#searchKeyword').val();

                $('#tbodyMedicationRequest').html(`
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <span class="spinner-border spinner-border-sm me-2"></span> Memuat data...
                        </td>
                    </tr>
                `);

                $.get("{{ route('satusehat.medication-request.data') }}", {
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir,
                    status: currentStatus,
                    search: search
                }, function(res) {
                    if (res.success) {
                        allData = res.data;
                        renderTable(allData);
                        updateStats(allData);
                    } else {
                        $('#tbodyMedicationRequest').html(`
                            <tr>
                                <td colspan="10" class="text-center py-4 text-danger">
                                    <i class="ti ti-alert-triangle me-1"></i> ${res.message}
                                </td>
                            </tr>
                        `);
                    }
                }).fail(function(xhr) {
                    $('#tbodyMedicationRequest').html(`
                        <tr>
                            <td colspan="10" class="text-center py-4 text-danger">
                                <i class="ti ti-alert-triangle me-1"></i> Gagal memuat data dari server.
                            </td>
                        </tr>
                    `);
                });
            }

            function updateStats(data) {
                let total = data.length;
                let synced = 0;
                let unsent = 0;
                let notready = 0;

                data.forEach(item => {
                    if (item.id_medicationrequest) {
                        synced++;
                    } else {
                        unsent++;
                    }
                    if (!item.id_medication) {
                        notready++;
                    }
                });

                $('#stat-total').text(total);
                $('#stat-synced').text(synced);
                $('#stat-unsent').text(unsent);
                $('#stat-notready').text(notready);
            }

            function renderTable(data) {
                if (data.length === 0) {
                    $('#tbodyMedicationRequest').html(`
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                Tidak ada data resep obat untuk periode dan filter ini.
                            </td>
                        </tr>
                    `);
                    return;
                }

                let html = '';
                data.forEach((item, idx) => {
                    const isSynced = !!item.id_medicationrequest;
                    const isKfaReady = !!item.id_medication;
                    const isEncounterReady = !!item.id_encounter;
                    const isPatientReady = !!item.ktp_pasien;
                    const isDoctorReady = !!item.ktp_dokter;

                    const canSync = !isSynced && isKfaReady && isEncounterReady && isPatientReady && isDoctorReady;

                    const key = `${item.no_resep}|${item.kode_brng}|${item.no_racik || ''}`;

                    let statusBadge = '';
                    if (isSynced) {
                        statusBadge = `<span class="badge bg-success-lt text-success" title="ID: ${item.id_medicationrequest}">
                            <i class="ti ti-check me-1"></i> Synced
                        </span>`;
                    } else if (!isKfaReady) {
                        statusBadge = `<span class="badge bg-danger-lt text-danger" title="Obat belum disinkronkan ke KFA">
                            <i class="ti ti-alert-triangle me-1"></i> KFA Belum Ada
                        </span>`;
                    } else if (!isEncounterReady) {
                        statusBadge = `<span class="badge bg-warning-lt text-warning" title="Encounter pasien belum sync">
                            <i class="ti ti-clock me-1"></i> Encounter Pending
                        </span>`;
                    } else {
                        statusBadge = `<span class="badge bg-secondary-lt text-secondary">
                            Belum Sync
                        </span>`;
                    }

                    const jenisBadge = item.jenis_resep === 'racikan'
                        ? `<span class="badge bg-azure-lt">Racikan (R${item.no_racik})</span>`
                        : `<span class="badge bg-light text-muted">Non-Racik</span>`;

                    html += `<tr>
                        <td class="text-center">
                            <input class="form-check-input check-item" type="checkbox" value="${key}" ${canSync ? '' : 'disabled'}>
                        </td>
                        <td>
                            <strong class="text-primary">${item.no_resep}</strong><br>
                            <small class="text-muted"><i class="ti ti-clock me-1"></i>${item.tgl_peresepan} ${item.jam_peresepan}</small>
                        </td>
                        <td>
                            <strong>${item.nm_pasien}</strong> <span class="badge bg-blue-lt small">${item.status_lanjut}</span><br>
                            <small class="text-muted">${item.no_rawat} | RM: ${item.no_rkm_medis}</small>
                            ${!item.ktp_pasien ? '<br><span class="badge bg-danger-lt small">NIK Pasien Kosong</span>' : ''}
                        </td>
                        <td>
                            <span>${item.nm_dokter}</span>
                            ${!item.ktp_dokter ? '<br><span class="badge bg-danger-lt small">NIK Dokter Kosong</span>' : ''}
                        </td>
                        <td>
                            <strong>${item.nama_brng}</strong> ${jenisBadge}<br>
                            <small class="text-muted">${item.kode_brng} ${item.obat_display ? '| ' + item.obat_display : ''}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary-lt">${item.jml}</span><br>
                            <small class="text-muted">${item.aturan_pakai || '-'}</small>
                        </td>
                        <td class="text-center">
                            ${isKfaReady 
                                ? `<span class="badge bg-success-lt" title="${item.id_medication}"><i class="ti ti-check"></i> Ready</span>` 
                                : `<a href="{{ route('satusehat.medication.index') }}" class="btn btn-outline-danger btn-xs" target="_blank">Map KFA</a>`
                            }
                        </td>
                        <td class="text-center">
                            ${isEncounterReady 
                                ? `<span class="badge bg-success-lt" title="${item.id_encounter}"><i class="ti ti-check"></i> Ready</span>` 
                                : `<span class="badge bg-warning-lt">Pending</span>`
                            }
                        </td>
                        <td class="text-center">
                            ${statusBadge}
                            ${item.id_medicationrequest ? `<br><small class="text-muted text-truncate d-inline-block" style="max-width: 120px;" title="${item.id_medicationrequest}">${item.id_medicationrequest}</small>` : ''}
                        </td>
                        <td class="text-center">
                            ${isSynced 
                                ? `<button class="btn btn-sm btn-outline-secondary btn-sync-single" data-resep="${item.no_resep}" data-kode="${item.kode_brng}" data-racik="${item.no_racik || ''}" title="Sync Ulang">
                                    <i class="ti ti-refresh"></i>
                                   </button>`
                                : `<button class="btn btn-sm btn-primary btn-sync-single" data-resep="${item.no_resep}" data-kode="${item.kode_brng}" data-racik="${item.no_racik || ''}" ${canSync ? '' : 'disabled'}>
                                    <i class="ti ti-cloud-upload me-1"></i> Sync
                                   </button>`
                            }
                        </td>
                    </tr>`;
                });

                $('#tbodyMedicationRequest').html(html);
            }

            // Single Item Sync
            $(document).on('click', '.btn-sync-single', function() {
                const btn = $(this);
                const noResep = btn.data('resep');
                const kodeBrng = btn.data('kode');
                const noRacik = btn.data('racik');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.post("{{ route('satusehat.medication-request.sync') }}", {
                    _token: "{{ csrf_token() }}",
                    no_resep: noResep,
                    kode_brng: kodeBrng,
                    no_racik: noRacik
                }, function(res) {
                    if (res.success) {
                        alertify.success('MedicationRequest berhasil disinkronkan!');
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

                const tglAwal = $('#tgl_awal').val();
                const tglAkhir = $('#tgl_akhir').val();

                const msg = selected.length > 0 
                    ? `Sinkronkan ${selected.length} item terpilih ke SatuSehat?` 
                    : `Sinkronkan semua item resep yang belum terkirim (${tglAwal} s/d ${tglAkhir})?`;

                if (!confirm(msg)) return;

                $('#batchSyncLoading').show();
                $('#batchSyncResult').hide();
                $('#batchSyncFooter').hide();
                $('#modalBatchSync').modal('show');

                $.post("{{ route('satusehat.medication-request.sync-batch') }}", {
                    _token: "{{ csrf_token() }}",
                    tgl_awal: tglAwal,
                    tgl_akhir: tglAkhir,
                    keys: selected
                }, function(res) {
                    $('#batchSyncLoading').hide();
                    $('#batchSyncResult').show();
                    $('#batchSyncFooter').show();

                    if (res.success) {
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


@extends('layout')

@section('body')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-indigo-lt text-indigo rounded-circle" style="width: 38px; height: 38px;">
                            <i class="ti ti-calendar-event fs-2"></i>
                        </div>
                        <div>
                            <h2 class="page-title mb-0">Satu Sehat — Episode Perawatan (EpisodeOfCare)</h2>
                            <div class="text-muted small">Pemantauan & Transmisi Kontinuitas Perawatan Program Khusus (ANC / Kehamilan, TB, DM, Hipertensi) ke SatuSehat Kemenkes RI</div>
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
                                <span class="bg-indigo text-white avatar">
                                    <i class="ti ti-calendar-event fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-total">0</div>
                                <div class="text-muted small">Total Episode Terdata</div>
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
                                    <i class="ti ti-file-text fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium" id="stat-condition-ready">0</div>
                                <div class="text-muted small">Condition Siap</div>
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

                        <!-- Program Filter Dropdown -->
                        <div>
                            <select class="form-select form-select-sm" id="filterTipe" style="height: 32px; font-size: 11.5px; border-radius: 8px;">
                                <option value="all">Semua Program Episode</option>
                                <option value="ANC">ANC (Pemeriksaan Kehamilan)</option>
                                <option value="TB">Tuberkulosis (TB)</option>
                                <option value="DM">Diabetes Mellitus (DM)</option>
                                <option value="HT">Hipertensi (HT)</option>
                                <option value="UMUM">Episode Umum</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefresh" title="Refresh">
                            <i class="ti ti-refresh"></i>
                        </button>
                    </div>

                    <!-- Search Filter -->
                    <div class="search-box-filter">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" class="form-control" id="searchKeyword" placeholder="Cari No. Rawat, Pasien, Diagnosa...">
                        <i class="ti ti-x clear-icon" id="btnClearSearch"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-striped" id="tableEpisode">
                    <thead>
                        <tr>
                            <th class="w-1 text-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                            </th>
                            <th>No. Rawat & Tanggal</th>
                            <th>Pasien</th>
                            <th>Dokter Penanggung Jawab</th>
                            <th>Program Episode</th>
                            <th>Diagnosa ICD-10</th>
                            <th class="text-center">Condition</th>
                            <th class="text-center">Status Sync</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyEpisode">
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <span class="spinner-border spinner-border-sm me-2"></span> Memuat data EpisodeOfCare...
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
                    <h5 class="modal-title"><i class="ti ti-cloud-upload me-2 text-primary"></i> Sinkronisasi Massal EpisodeOfCare</h5>
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
                        <p id="batchSyncDesc" class="text-muted small mb-3"></p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
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

            // Load data pertama kali
            loadData();

            // Event filter tanggal & tipe
            $('#start_date, #end_date, #filterTipe').on('change', function() {
                loadData();
            });

            // Event filter segmented status
            $('#filterStatusGroup').on('click', '.btn-filter-status', function() {
                $('#filterStatusGroup .btn-filter-status').removeClass('active');
                $(this).addClass('active');
                currentStatus = $(this).data('status');
                filterAndRenderTable();
            });

            // Refresh button
            $('#btnRefresh').on('click', function() {
                loadData();
            });

            // Live search
            $('#searchKeyword').on('input', function() {
                let val = $(this).val();
                if (val.length > 0) {
                    $('#btnClearSearch').show();
                } else {
                    $('#btnClearSearch').hide();
                }
                filterAndRenderTable();
            });

            $('#btnClearSearch').on('click', function() {
                $('#searchKeyword').val('');
                $(this).hide();
                filterAndRenderTable();
            });

            // Check All
            $('#checkAll').on('change', function() {
                let checked = $(this).is(':checked');
                $('.check-item:not(:disabled)').prop('checked', checked);
            });

            // Load Data function
            function loadData() {
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();
                let tipe = $('#filterTipe').val();

                $('#tbodyEpisode').html(`
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <span class="spinner-border spinner-border-sm me-2"></span> Memuat data EpisodeOfCare...
                        </td>
                    </tr>
                `);

                $.ajax({
                    url: "{{ route('satusehat.episode-of-care.data') }}",
                    type: "GET",
                    data: {
                        tgl_awal: startDate,
                        tgl_akhir: endDate,
                        tipe: tipe,
                        status: 'all'
                    },
                    success: function(res) {
                        if (res.success) {
                            allData = res.data || [];
                            updateMetrics(allData);
                            filterAndRenderTable();
                        } else {
                            $('#tbodyEpisode').html(`
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-danger">
                                        <i class="ti ti-alert-triangle me-1"></i> ${res.message || 'Gagal memuat data'}
                                    </td>
                                </tr>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#tbodyEpisode').html(`
                            <tr>
                                <td colspan="9" class="text-center py-4 text-danger">
                                    <i class="ti ti-alert-triangle me-1"></i> Terjadi kesalahan server saat mengambil data
                                </td>
                            </tr>
                        `);
                    }
                });
            }

            // Update Metric Cards
            function updateMetrics(data) {
                let total = data.length;
                let synced = data.filter(d => d.id_episode_of_care).length;
                let unsent = total - synced;
                let conditionReady = data.filter(d => d.id_condition).length;

                $('#stat-total').text(total);
                $('#stat-synced').text(synced);
                $('#stat-unsent').text(unsent);
                $('#stat-condition-ready').text(conditionReady);
            }

            // Filter & Render Table
            function filterAndRenderTable() {
                let search = $('#searchKeyword').val().toLowerCase().trim();
                let filtered = allData.filter(function(row) {
                    // Status filter
                    if (currentStatus === 'synced' && !row.id_episode_of_care) return false;
                    if (currentStatus === 'unsent' && row.id_episode_of_care) return false;

                    // Search filter
                    if (search) {
                        let text = [
                            row.no_rawat || '',
                            row.nm_pasien || '',
                            row.no_rkm_medis || '',
                            row.kd_penyakit || '',
                            row.nm_penyakit || '',
                            row.nm_dokter || '',
                            row.tipe_program || ''
                        ].join(' ').toLowerCase();

                        if (!text.includes(search)) return false;
                    }

                    return true;
                });

                renderTable(filtered);
            }

            // Render Rows
            function renderTable(data) {
                if (data.length === 0) {
                    $('#tbodyEpisode').html(`
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="ti ti-inbox fs-2 d-block mb-1"></i> Tidak ada data EpisodeOfCare pada periode ini
                            </td>
                        </tr>
                    `);
                    $('#checkAll').prop('checked', false);
                    return;
                }

                let html = '';
                data.forEach(function(row) {
                    let isSynced = !!row.id_episode_of_care;
                    let hasCondition = !!row.id_condition;

                    let conditionBadge = hasCondition
                        ? `<span class="badge bg-success-lt" title="Condition ID: ${row.id_condition}"><i class="ti ti-check me-1"></i> Ready</span>`
                        : `<span class="badge bg-danger-lt" title="Condition belum tersinkron"><i class="ti ti-x me-1"></i> Belum Sync</span>`;

                    let syncBadge = isSynced
                        ? `<span class="badge bg-success" title="EpisodeOfCare ID: ${row.id_episode_of_care}"><i class="ti ti-check me-1"></i> Synced</span><br><small class="text-muted font-monospace">${row.id_episode_of_care.substring(0, 10)}...</small>`
                        : `<span class="badge bg-warning"><i class="ti ti-clock me-1"></i> Belum Sync</span>`;

                    let badgeColor = 'bg-indigo-lt';
                    if (row.tipe_program === 'ANC') badgeColor = 'bg-pink-lt';
                    else if (row.tipe_program === 'TB') badgeColor = 'bg-orange-lt';
                    else if (row.tipe_program === 'DM') badgeColor = 'bg-azure-lt';
                    else if (row.tipe_program === 'HT') badgeColor = 'bg-purple-lt';

                    let tipeBadge = `<span class="badge ${badgeColor}"><i class="ti ti-tag me-1"></i> ${row.tipe_label || row.tipe_program}</span>`;

                    let syncBtnDisabled = (!hasCondition) ? 'disabled' : '';
                    let actionBtn = isSynced
                        ? `<button type="button" class="btn btn-sm btn-outline-success btn-sync-single" 
                                data-norawat="${row.no_rawat}" 
                                data-kode="${row.kd_penyakit}" 
                                title="Sudah tersinkronisasi">
                                <i class="ti ti-check me-1"></i> Synced
                           </button>`
                        : `<button type="button" class="btn btn-sm btn-primary btn-sync-single" 
                                data-norawat="${row.no_rawat}" 
                                data-kode="${row.kd_penyakit}" 
                                ${syncBtnDisabled} 
                                title="Kirim ke SatuSehat">
                                <i class="ti ti-cloud-upload me-1"></i> Sync
                           </button>`;

                    let jsonKey = JSON.stringify({
                        no_rawat: row.no_rawat,
                        kd_penyakit: row.kd_penyakit
                    });

                    html += `
                        <tr>
                            <td class="text-center">
                                <input class="form-check-input check-item" type="checkbox" 
                                    data-item='${jsonKey}'
                                    ${!hasCondition ? 'disabled title="Condition belum sync"' : ''}>
                            </td>
                            <td>
                                <div class="font-weight-medium">${row.no_rawat}</div>
                                <div class="text-muted small">${row.tgl_registrasi} ${row.jam_reg}</div>
                            </td>
                            <td>
                                <div class="font-weight-medium">${row.nm_pasien}</div>
                                <div class="text-muted small">RM: ${row.no_rkm_medis} | NIK: ${row.ktp_pasien || '-'}</div>
                            </td>
                            <td>
                                <div class="font-weight-medium">${row.nm_dokter || '-'}</div>
                                <div class="text-muted small">NIK: ${row.ktp_dokter || '-'}</div>
                            </td>
                            <td>${tipeBadge}</td>
                            <td>
                                <span class="badge bg-dark-lt">${row.kd_penyakit}</span>
                                <div class="small text-muted">${row.nm_penyakit}</div>
                            </td>
                            <td class="text-center">${conditionBadge}</td>
                            <td class="text-center">${syncBadge}</td>
                            <td class="text-center">${actionBtn}</td>
                        </tr>
                    `;
                });

                $('#tbodyEpisode').html(html);
                $('#checkAll').prop('checked', false);
            }

            // Single Sync Action
            $('#tbodyEpisode').on('click', '.btn-sync-single', function() {
                let btn = $(this);
                let noRawat = btn.data('norawat');
                let kode = btn.data('kode');

                let oldHtml = btn.html();
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: "{{ route('satusehat.episode-of-care.sync') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        no_rawat: noRawat,
                        kd_penyakit: kode
                    },
                    success: function(res) {
                        btn.prop('disabled', false).html(oldHtml);
                        if (res.success) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'EpisodeOfCare berhasil disinkronkan ke SatuSehat ID: ' + res.id_episode_of_care,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                alert('Berhasil! ID EpisodeOfCare: ' + res.id_episode_of_care);
                            }
                            loadData();
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Sinkronisasi',
                                    text: res.message || 'Terjadi kesalahan'
                                });
                            } else {
                                alert('Gagal: ' + (res.message || 'Terjadi kesalahan'));
                            }
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(oldHtml);
                        let err = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: err
                            });
                        } else {
                            alert('Gagal: ' + err);
                        }
                    }
                });
            });

            // Batch Sync Action
            $('#btnSyncBatch').on('click', function() {
                let selectedItems = [];
                $('.check-item:checked').each(function() {
                    let item = $(this).data('item');
                    if (item) selectedItems.push(item);
                });

                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();

                let modal = new bootstrap.Modal(document.getElementById('modalBatchSync'));
                $('#batchSyncLoading').show();
                $('#batchSyncResult').hide();
                modal.show();

                $.ajax({
                    url: "{{ route('satusehat.episode-of-care.sync-batch') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tgl_awal: startDate,
                        tgl_akhir: endDate,
                        keys: selectedItems
                    },
                    success: function(res) {
                        $('#batchSyncLoading').hide();
                        $('#batchSyncResult').show();

                        if (res.success) {
                            let stat = res.data;
                            $('#batchSyncIcon').html('<i class="ti ti-circle-check text-success" style="font-size: 3.5rem;"></i>');
                            $('#batchSyncTitle').text('Sinkronisasi Selesai');
                            $('#batchSyncDesc').html(`
                                Berhasil: <strong class="text-success">${stat.success}</strong> &bull; 
                                Gagal: <strong class="text-danger">${stat.error}</strong> &bull; 
                                Dilewati: <strong class="text-muted">${stat.skip}</strong>
                            `);
                        } else {
                            $('#batchSyncIcon').html('<i class="ti ti-alert-triangle text-danger" style="font-size: 3.5rem;"></i>');
                            $('#batchSyncTitle').text('Sinkronisasi Gagal');
                            $('#batchSyncDesc').text(res.message || 'Terjadi kesalahan');
                        }

                        loadData();
                    },
                    error: function(xhr) {
                        $('#batchSyncLoading').hide();
                        $('#batchSyncResult').show();
                        let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                        $('#batchSyncIcon').html('<i class="ti ti-alert-triangle text-danger" style="font-size: 3.5rem;"></i>');
                        $('#batchSyncTitle').text('Gagal Terhubung');
                        $('#batchSyncDesc').text(msg);
                    }
                });
            });
        });
    </script>
@endpush

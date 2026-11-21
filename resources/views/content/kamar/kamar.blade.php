@extends('layout')

@push('style')
<style>
    .kamar-page .card-stat {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 8px;
    }
    .kamar-page .card-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    }
    .kamar-page .table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        background-color: #f8fafc;
        vertical-align: middle;
    }
    .kamar-page .table td {
        vertical-align: middle;
        font-size: 13px;
    }
    .badge-status-kamar {
        font-size: 11px;
        padding: 4px 8px;
        font-weight: 600;
        border-radius: 6px;
    }
    .filter-panel {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }
</style>
@endpush

@section('body')
<div class="container-xl kamar-page pb-4">
    <!-- Page Header -->
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-muted small">
                    <i class="ti ti-building-hospital me-1"></i> Rawat Inap &bull; SIMRS Khanza DlgKamar
                </div>
                <h2 class="page-title text-primary fw-bold mb-0">
                    <i class="ti ti-bed me-2"></i> Manajemen Kamar & Bed
                </h2>
            </div>
            <div class="col-auto ms-auto d-flex gap-2">
                <a href="{{ url('setting/jam-ranap') }}" class="btn btn-outline-secondary btn-sm" title="Pengaturan Jam Minimal & Ubah Status Kamar">
                    <i class="ti ti-clock-cog me-1"></i> Set Jam Ranap
                </a>
                <a href="{{ url('ranap') }}" class="btn btn-outline-primary btn-sm" title="Kembali ke Pasien Rawat Inap">
                    <i class="ti ti-users me-1"></i> Pasien Ranap
                </a>
                <button type="button" class="btn btn-primary btn-sm shadow-sm" onclick="openModalTambah()">
                    <i class="ti ti-plus me-1"></i> Tambah Kamar / Bed
                </button>
            </div>
        </div>
    </div>

    <!-- Live Statistics Cards -->
    <div class="row row-cards mb-3 g-2">
        <div class="col-6 col-sm-4 col-md-2">
            <div class="card card-sm card-stat border-0 shadow-sm bg-white">
                <div class="card-body p-2 d-flex align-items-center">
                    <span class="avatar avatar-sm bg-primary-lt text-primary me-2 rounded">
                        <i class="ti ti-bed fs-2"></i>
                    </span>
                    <div>
                        <div class="text-muted small" style="font-size: 11px;">Total Bed</div>
                        <div class="fw-bold fs-4 text-dark" id="stat-total">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="card card-sm card-stat border-0 shadow-sm bg-white cursor-pointer" onclick="filterByQuickStatus('KOSONG')">
                <div class="card-body p-2 d-flex align-items-center">
                    <span class="avatar avatar-sm bg-success-lt text-success me-2 rounded">
                        <i class="ti ti-door-enter fs-2"></i>
                    </span>
                    <div>
                        <div class="text-muted small" style="font-size: 11px;">Bed Kosong</div>
                        <div class="fw-bold fs-4 text-success" id="stat-kosong">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="card card-sm card-stat border-0 shadow-sm bg-white cursor-pointer" onclick="filterByQuickStatus('ISI')">
                <div class="card-body p-2 d-flex align-items-center">
                    <span class="avatar avatar-sm bg-danger-lt text-danger me-2 rounded">
                        <i class="ti ti-user-check fs-2"></i>
                    </span>
                    <div>
                        <div class="text-muted small" style="font-size: 11px;">Bed Terisi (ISI)</div>
                        <div class="fw-bold fs-4 text-danger" id="stat-isi">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="card card-sm card-stat border-0 shadow-sm bg-white cursor-pointer" onclick="filterByQuickStatus('DIBERSIHKAN')">
                <div class="card-body p-2 d-flex align-items-center">
                    <span class="avatar avatar-sm bg-warning-lt text-warning me-2 rounded">
                        <i class="ti ti-sparkles fs-2"></i>
                    </span>
                    <div>
                        <div class="text-muted small" style="font-size: 11px;">Dibersihkan</div>
                        <div class="fw-bold fs-4 text-warning" id="stat-dibersihkan">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="card card-sm card-stat border-0 shadow-sm bg-white cursor-pointer" onclick="filterByQuickStatus('DIBOOKING')">
                <div class="card-body p-2 d-flex align-items-center">
                    <span class="avatar avatar-sm bg-info-lt text-info me-2 rounded">
                        <i class="ti ti-bookmark fs-2"></i>
                    </span>
                    <div>
                        <div class="text-muted small" style="font-size: 11px;">Dibooking</div>
                        <div class="fw-bold fs-4 text-info" id="stat-dibooking">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <div class="card card-sm card-stat border-0 shadow-sm bg-white cursor-pointer" onclick="toggleTrashView(true)">
                <div class="card-body p-2 d-flex align-items-center">
                    <span class="avatar avatar-sm bg-secondary-lt text-secondary me-2 rounded">
                        <i class="ti ti-trash fs-2"></i>
                    </span>
                    <div>
                        <div class="text-muted small" style="font-size: 11px;">Terhapus (Trash)</div>
                        <div class="fw-bold fs-4 text-secondary" id="stat-terhapus">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card Content -->
    <div class="card shadow-sm border-0">
        <!-- Filter Bar -->
        <div class="card-body border-bottom py-2 bg-light">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-search text-muted"></i>
                        </span>
                        <input type="text" id="filterKeyword" class="form-control form-control-sm" placeholder="Cari nomor bed, bangsal, pasien...">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select id="filterBangsal" class="form-select form-select-sm">
                        <option value="">-- Semua Bangsal --</option>
                        @foreach($bangsal as $b)
                            <option value="{{ $b->kd_bangsal }}">{{ $b->nm_bangsal }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="filterKelas" class="form-select form-select-sm">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        @foreach($statusList as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 text-end d-flex justify-content-end gap-1">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="btnViewActive" onclick="toggleTrashView(false)">
                            <i class="ti ti-list-check me-1"></i> Aktif
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnViewTrash" onclick="toggleTrashView(true)">
                            <i class="ti ti-trash me-1"></i> Sampah
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="reloadTable()" title="Segarkan Data">
                        <i class="ti ti-refresh"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Responsive -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm mb-0" id="tableKamar" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="w-1 text-center">No</th>
                        <th>Nomer Bed / Kode</th>
                        <th>Bangsal / Ruangan</th>
                        <th>Kelas</th>
                        <th class="text-end">Tarif Kamar</th>
                        <th class="text-center">Status Bed</th>
                        <th>Pasien Rawat Inap Saat Ini</th>
                        <th class="text-center w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Tambah / Edit Kamar -->
<div class="modal fade" id="modalKamar" tabindex="-1" aria-labelledby="modalKamarTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fw-bold" id="modalKamarTitle">
                    <i class="ti ti-bed me-1"></i> Tambah Kamar / Bed Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKamar" onsubmit="saveKamar(event)">
                <div class="modal-body p-3">
                    <input type="hidden" id="formMode" value="create">
                    <input type="hidden" id="origKdKamar" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted required">Nomer Bed / Kode Kamar</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="inputKdKamar" name="kd_kamar" class="form-control fw-bold text-uppercase" placeholder="Contoh: K1.01 atau K000001" required maxlength="15">
                            <button class="btn btn-outline-secondary" type="button" onclick="generateAutoKode()" title="Generate Nomor Otomatis Standar Khanza">
                                <i class="ti ti-sparkles me-1 text-warning"></i> Auto Nomor
                            </button>
                        </div>
                        <div class="form-text small" style="font-size: 11px;">Maksimal 15 karakter sesuai kolom <code>kamar.kd_kamar</code> standar Khanza.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted required">Bangsal / Ruangan</label>
                        <select id="inputKdBangsal" name="kd_bangsal" class="form-select form-select-sm" required onchange="onBangsalChange()">
                            <option value="">-- Pilih Bangsal / Ruangan --</option>
                            @foreach($bangsal as $b)
                                <option value="{{ $b->kd_bangsal }}">{{ $b->nm_bangsal }} ({{ $b->kd_bangsal }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted required">Kelas Kamar</label>
                            <select id="inputKelas" name="kelas" class="form-select form-select-sm" required>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted required">Status Awal</label>
                            <select id="inputStatus" name="status" class="form-select form-select-sm" required>
                                @foreach($statusList as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted required">Tarif Kamar per Hari (Rp)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="inputTrfKamar" name="trf_kamar" class="form-control fw-bold text-end" placeholder="0" min="0" step="500" required>
                        </div>
                        <div class="form-text small text-end fw-semibold text-primary" id="previewTarif">Rp 0</div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary" id="btnSubmitKamar">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Quick Update Status Kamar -->
<div class="modal fade" id="modalQuickStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light py-2">
                <h5 class="modal-title fw-bold small mb-0">
                    <i class="ti ti-exchange me-1 text-primary"></i> Ubah Status Bed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="text-center mb-3">
                    <div class="text-muted small">Nomer Bed:</div>
                    <h4 class="fw-bold text-primary mb-0" id="quickBedName">-</h4>
                    <div class="small text-muted" id="quickBangsalName">-</div>
                </div>
                <input type="hidden" id="quickKdKamar" value="">
                <label class="form-label fw-bold small text-muted required">Pilih Status Baru:</label>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success text-start py-2" onclick="submitQuickStatus('KOSONG')">
                        <i class="ti ti-door-enter me-2 fs-3 text-success"></i> <b>KOSONG</b> <span class="small text-muted d-block ms-4">Siap ditempati pasien baru</span>
                    </button>
                    <button type="button" class="btn btn-outline-warning text-start py-2" onclick="submitQuickStatus('DIBERSIHKAN')">
                        <i class="ti ti-sparkles me-2 fs-3 text-warning"></i> <b>DIBERSIHKAN</b> <span class="small text-muted d-block ms-4">Sedang disterilkan/disiapkan</span>
                    </button>
                    <button type="button" class="btn btn-outline-info text-start py-2" onclick="submitQuickStatus('DIBOOKING')">
                        <i class="ti ti-bookmark me-2 fs-3 text-info"></i> <b>DIBOOKING</b> <span class="small text-muted d-block ms-4">Dipesan untuk pasien</span>
                    </button>
                    <button type="button" class="btn btn-outline-danger text-start py-2" onclick="submitQuickStatus('ISI')">
                        <i class="ti ti-user-check me-2 fs-3 text-danger"></i> <b>ISI</b> <span class="small text-muted d-block ms-4">Terisi pasien rawat inap</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let dtTable = null;
    let currentStatusData = '1'; // 1 = Aktif, 0 = Terhapus

    $(document).ready(function () {
        initDataTable();
        loadStats();

        // Event filters
        $('#filterBangsal, #filterKelas, #filterStatus').on('change', function () {
            reloadTable();
        });

        // Search with debounce
        let searchTimer = null;
        $('#filterKeyword').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                reloadTable();
            }, 300);
        });

        // Format preview tarif
        $('#inputTrfKamar').on('input', function () {
            const val = parseFloat($(this).val()) || 0;
            $('#previewTarif').text('Rp ' + val.toLocaleString('id-ID'));
        });
    });

    function initDataTable() {
        dtTable = $('#tableKamar').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('kamar/data') }}",
                data: function (d) {
                    d.statusdata    = currentStatusData;
                    d.kd_bangsal    = $('#filterBangsal').val();
                    d.kelas         = $('#filterKelas').val();
                    d.status        = $('#filterStatus').val();
                    d.search_custom = $('#filterKeyword').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                {
                    data: 'kd_kamar',
                    name: 'kd_kamar',
                    render: function (data, type, row) {
                        return `<span class="fw-bold text-primary font-monospace fs-4">${data}</span>`;
                    }
                },
                {
                    data: 'nm_bangsal',
                    name: 'nm_bangsal',
                    render: function (data, type, row) {
                        return `<div><span class="fw-semibold">${data}</span> <span class="badge bg-secondary-lt ms-1">${row.kd_bangsal}</span></div>`;
                    }
                },
                {
                    data: 'kelas',
                    name: 'kelas',
                    render: function (data) {
                        let color = 'bg-blue-lt';
                        if (data.includes('VVIP') || data.includes('VIP')) color = 'bg-purple-lt text-purple';
                        else if (data.includes('1')) color = 'bg-azure-lt text-azure';
                        else if (data.includes('2')) color = 'bg-teal-lt text-teal';
                        else if (data.includes('3')) color = 'bg-secondary-lt text-secondary';
                        return `<span class="badge ${color}">${data}</span>`;
                    }
                },
                {
                    data: 'trf_kamar',
                    name: 'trf_kamar',
                    className: 'text-end',
                    render: function (data) {
                        const val = parseFloat(data) || 0;
                        return `<span class="fw-bold text-dark font-monospace">Rp ${val.toLocaleString('id-ID')}</span>`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: function (data, type, row) {
                        let badgeClass = 'bg-success text-white';
                        let icon = 'ti-door-enter';
                        if (data === 'ISI') {
                            badgeClass = 'bg-danger text-white';
                            icon = 'ti-user-check';
                        } else if (data === 'DIBERSIHKAN') {
                            badgeClass = 'bg-warning text-dark';
                            icon = 'ti-sparkles';
                        } else if (data === 'DIBOOKING') {
                            badgeClass = 'bg-info text-white';
                            icon = 'ti-bookmark';
                        }

                        if (currentStatusData === '1') {
                            return `
                                <button type="button" class="btn btn-sm ${badgeClass} badge-status-kamar shadow-none py-1 px-2 border-0" 
                                        onclick="openQuickStatus('${row.kd_kamar}', '${data}', '${row.nm_bangsal}')"
                                        title="Klik untuk ubah status cepat">
                                    <i class="ti ${icon} me-1"></i> ${data}
                                </button>
                            `;
                        } else {
                            return `<span class="badge ${badgeClass}"><i class="ti ${icon} me-1"></i> ${data}</span>`;
                        }
                    }
                },
                {
                    data: 'nm_pasien',
                    name: 'nm_pasien',
                    render: function (data, type, row) {
                        if (row.status === 'ISI' && data) {
                            return `
                                <div>
                                    <span class="fw-bold text-dark"><i class="ti ti-user me-1 text-danger"></i>${data}</span>
                                    <span class="text-muted small">(${row.no_rkm_medis || '-'})</span>
                                    <div class="small text-muted" style="font-size: 11px;">
                                        <i class="ti ti-calendar-time me-1"></i>Masuk: ${row.tgl_masuk || '-'} ${row.jam_masuk || ''} &bull; <span class="font-monospace">${row.no_rawat}</span>
                                    </div>
                                </div>
                            `;
                        } else if (row.status === 'ISI' && !data) {
                            return `<span class="text-muted fst-italic small"><i class="ti ti-alert-triangle text-warning me-1"></i>Status ISI (tanpa link rawat inap)</span>`;
                        }
                        return `<span class="text-muted small">-</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (currentStatusData === '1') {
                            const safeRow = JSON.stringify(row).replace(/"/g, '&quot;');
                            return `
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary py-1 px-2" onclick="openModalEdit(${safeRow})" title="Edit Data Kamar">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger py-1 px-2" onclick="deleteKamar('${row.kd_kamar}')" title="Hapus Kamar">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            `;
                        } else {
                            return `
                                <button type="button" class="btn btn-sm btn-outline-success py-1 px-2" onclick="restoreKamar('${row.kd_kamar}')" title="Pulihkan Kamar">
                                    <i class="ti ti-rotate-clockwise me-1"></i> Pulihkan
                                </button>
                            `;
                        }
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                emptyTable: "Tidak ada data kamar/bed yang sesuai",
                processing: "Memuat data kamar..."
            },
            order: [[1, 'asc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
    }

    function reloadTable() {
        if (dtTable) {
            dtTable.ajax.reload(null, false);
        }
        loadStats();
    }

    function loadStats() {
        $.get("{{ url('kamar/stats') }}", function (res) {
            $('#stat-total').text(res.total || 0);
            $('#stat-kosong').text(res.kosong || 0);
            $('#stat-isi').text(res.isi || 0);
            $('#stat-dibersihkan').text(res.dibersihkan || 0);
            $('#stat-dibooking').text(res.dibooking || 0);
            $('#stat-terhapus').text(res.terhapus || 0);
        });
    }

    function filterByQuickStatus(status) {
        if (currentStatusData !== '1') {
            toggleTrashView(false);
        }
        $('#filterStatus').val(status);
        reloadTable();
    }

    function toggleTrashView(isTrash) {
        currentStatusData = isTrash ? '0' : '1';
        if (isTrash) {
            $('#btnViewTrash').addClass('active btn-secondary').removeClass('btn-outline-secondary');
            $('#btnViewActive').removeClass('active btn-secondary').addClass('btn-outline-secondary');
        } else {
            $('#btnViewActive').addClass('active btn-secondary').removeClass('btn-outline-secondary');
            $('#btnViewTrash').removeClass('active btn-secondary').addClass('btn-outline-secondary');
        }
        reloadTable();
    }

    function generateAutoKode() {
        const kdBangsal = $('#inputKdBangsal').val();
        $.get("{{ url('kamar/auto-kode') }}", { kd_bangsal: kdBangsal }, function (res) {
            if (res.kd_kamar) {
                $('#inputKdKamar').val(res.kd_kamar);
            }
        });
    }

    function onBangsalChange() {
        const mode = $('#formMode').val();
        if (mode === 'create' && !$('#inputKdKamar').val()) {
            generateAutoKode();
        }
    }

    function openModalTambah() {
        $('#formMode').val('create');
        $('#origKdKamar').val('');
        $('#modalKamarTitle').html('<i class="ti ti-bed me-1"></i> Tambah Kamar / Bed Baru');
        $('#formKamar')[0].reset();
        $('#inputKdKamar').prop('readonly', false);
        $('#previewTarif').text('Rp 0');
        generateAutoKode();
        $('#modalKamar').modal('show');
    }

    function openModalEdit(row) {
        $('#formMode').val('edit');
        $('#origKdKamar').val(row.kd_kamar);
        $('#modalKamarTitle').html('<i class="ti ti-pencil me-1"></i> Edit Kamar / Bed: <span class="badge bg-white text-primary ms-1">' + row.kd_kamar + '</span>');
        
        $('#inputKdKamar').val(row.kd_kamar).prop('readonly', false);
        $('#inputKdBangsal').val(row.kd_bangsal);
        $('#inputKelas').val(row.kelas);
        $('#inputStatus').val(row.status);
        $('#inputTrfKamar').val(parseFloat(row.trf_kamar) || 0);

        const val = parseFloat(row.trf_kamar) || 0;
        $('#previewTarif').text('Rp ' + val.toLocaleString('id-ID'));

        $('#modalKamar').modal('show');
    }

    function saveKamar(e) {
        e.preventDefault();
        const mode = $('#formMode').val();
        const origKd = $('#origKdKamar').val();
        const kdKamar = $('#inputKdKamar').val().trim();

        if (!kdKamar) {
            Swal.fire('Peringatan', 'Nomer Bed / Kode Kamar wajib diisi!', 'warning');
            return;
        }

        const payload = {
            _token: "{{ csrf_token() }}",
            kd_kamar: kdKamar,
            new_kd_kamar: kdKamar,
            kd_bangsal: $('#inputKdBangsal').val(),
            kelas: $('#inputKelas').val(),
            status: $('#inputStatus').val(),
            trf_kamar: $('#inputTrfKamar').val()
        };

        const btn = $('#btnSubmitKamar');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

        let url = "{{ url('kamar/store') }}";
        let method = "POST";

        if (mode === 'edit') {
            url = "{{ url('kamar/update') }}/" + encodeURIComponent(origKd);
            payload._method = "PUT";
        }

        $.ajax({
            url: url,
            type: method,
            data: payload,
            success: function (res) {
                $('#modalKamar').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message || 'Data kamar berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                });
                reloadTable();
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal menyimpan data kamar.';
                Swal.fire('Gagal Menyimpan', msg, 'error');
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Data');
            }
        });
    }

    function openQuickStatus(kdKamar, currentStatus, nmBangsal) {
        $('#quickKdKamar').val(kdKamar);
        $('#quickBedName').text(kdKamar);
        $('#quickBangsalName').text(nmBangsal + ' (' + currentStatus + ')');
        $('#modalQuickStatus').modal('show');
    }

    function submitQuickStatus(newStatus) {
        const kdKamar = $('#quickKdKamar').val();
        if (!kdKamar) return;

        $.ajax({
            url: "{{ url('kamar/update-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                kd_kamar: kdKamar,
                status: newStatus
            },
            success: function (res) {
                $('#modalQuickStatus').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Status Diperbarui',
                    text: res.message || 'Status kamar berhasil diubah.',
                    timer: 1200,
                    showConfirmButton: false
                });
                reloadTable();
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal mengubah status kamar.';
                Swal.fire('Gagal', msg, 'error');
            }
        });
    }

    function deleteKamar(kdKamar) {
        Swal.fire({
            title: 'Hapus Kamar / Bed?',
            html: `Apakah Anda yakin ingin menghapus nomor bed <b>${kdKamar}</b> dari data aktif?<br><small class="text-muted">(Data kamar akan dipindahkan ke Sampah dan dapat dipulihkan sewaktu-waktu)</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Kamar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('kamar/destroy') }}/" + encodeURIComponent(kdKamar),
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus',
                            text: res.message || 'Kamar berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        reloadTable();
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus kamar.';
                        Swal.fire('Gagal Menghapus', msg, 'error');
                    }
                });
            }
        });
    }

    function restoreKamar(kdKamar) {
        Swal.fire({
            title: 'Pulihkan Kamar / Bed?',
            html: `Kembalikan nomor bed <b>${kdKamar}</b> ke daftar data aktif?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pulihkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('kamar/restore') }}/" + encodeURIComponent(kdKamar),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dipulihkan',
                            text: res.message || 'Kamar berhasil dipulihkan.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        reloadTable();
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Gagal memulihkan kamar.';
                        Swal.fire('Gagal', msg, 'error');
                    }
                });
            }
        });
    }
</script>
@endpush


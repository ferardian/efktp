<div class="row g-3">
    <!-- SECTION 1: DIAGNOSA PASIEN (ICD-10) -->
    <div class="col-12">
        <div class="card border shadow-sm">
            <div class="card-header bg-primary-lt py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary fw-bold mb-0">
                    <i class="ti ti-stethoscope me-1"></i> Diagnosa Pasien (ICD-10)
                </h5>
                <span class="badge bg-primary text-white" id="badgeCountDiagnosaRanap">0 Diagnosa</span>
            </div>
            <div class="card-body p-3">
                <div class="row g-2 align-items-end mb-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold small">Cari Penyakit / Kode ICD-10</label>
                        <select class="form-select" id="selectDiagnosaRanap" style="width: 100%;"></select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Status Kasus</label>
                        <select class="form-select" id="selectStatusPenyakitRanap">
                            <option value="Baru">Baru</option>
                            <option value="Lama">Lama</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" class="btn btn-primary fw-semibold" id="btnAddDiagnosaRanap" onclick="tambahDiagnosaRanap()">
                            <i class="ti ti-plus me-1"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-vcenter table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="12%" class="text-center">Prioritas</th>
                                <th width="14%">Kode ICD-10</th>
                                <th>Nama Penyakit / Diagnosa</th>
                                <th width="12%" class="text-center">Status</th>
                                <th width="10%" class="text-center">Kasus</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDiagnosaRanap">
                            <tr><td colspan="6" class="text-center text-muted py-3">Belum ada diagnosa tercatat.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: PROSEDUR MEDIS (ICD-9) -->
    <div class="col-12">
        <div class="card border shadow-sm">
            <div class="card-header bg-teal-lt py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-teal fw-bold mb-0">
                    <i class="ti ti-scissors me-1"></i> Prosedur / Tindakan Medis (ICD-9-CM)
                </h5>
                <span class="badge bg-teal text-white" id="badgeCountProsedurRanap">0 Prosedur</span>
            </div>
            <div class="card-body p-3">
                <div class="row g-2 align-items-end mb-3">
                    <div class="col-md-10">
                        <label class="form-label fw-semibold small">Cari Prosedur / Kode ICD-9-CM</label>
                        <select class="form-select" id="selectProsedurRanap" style="width: 100%;"></select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="button" class="btn btn-teal fw-semibold" id="btnAddProsedurRanap" onclick="tambahProsedurRanap()">
                            <i class="ti ti-plus me-1"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-vcenter table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="12%" class="text-center">Prioritas</th>
                                <th width="14%">Kode ICD-9</th>
                                <th>Deskripsi Prosedur</th>
                                <th width="12%" class="text-center">Status</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyProsedurRanap">
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada prosedur tercatat.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    let currentDiagnosaListRanap = [];
    let currentProsedurListRanap = [];

    $(document).ready(function () {
        initSelectDiagnosaRanap();
        initSelectProsedurRanap();

        $('a[href="#tabs-diagnosa-ranap"]').on('shown.bs.tab', function () {
            const noRawat = formCpptRanap.find('input[name=no_rawat]').val();
            if (noRawat) {
                loadDiagnosaProsedurRanap(noRawat);
            }
        });
    });

    function initSelectDiagnosaRanap() {
        $('#selectDiagnosaRanap').select2({
            dropdownParent: $('#modalCpptRanap'),
            placeholder: 'Ketik kode ICD-10 atau nama penyakit...',
            allowClear: true,
            delay: 250,
            ajax: {
                url: `{{ url('/penyakit/get') }}`,
                dataType: 'JSON',
                data: (params) => ({ penyakit: params.term }),
                processResults: (data) => ({
                    results: (data || []).map((item) => ({
                        id: item.kd_penyakit,
                        text: `${item.kd_penyakit} - ${item.nm_penyakit}`,
                        detail: item
                    }))
                }),
                cache: true
            }
        });
    }

    function initSelectProsedurRanap() {
        $('#selectProsedurRanap').select2({
            dropdownParent: $('#modalCpptRanap'),
            placeholder: 'Ketik kode ICD-9 atau nama prosedur...',
            allowClear: true,
            delay: 250,
            ajax: {
                url: `{{ url('/tindakan/get') }}`,
                dataType: 'JSON',
                data: (params) => ({ kode: params.term }),
                processResults: (data) => ({
                    results: (data || []).map((item) => ({
                        id: item.kode,
                        text: `${item.kode} - ${item.deskripsi_panjang}`,
                        detail: item
                    }))
                }),
                cache: true
            }
        });
    }

    function loadDiagnosaProsedurRanap(noRawat) {
        if (!noRawat) return;

        // 1. Load Diagnosa
        $.get(`{{ url('/diagnosa/pasien/get') }}`, { no_rawat: noRawat }).done(function (res) {
            currentDiagnosaListRanap = Array.isArray(res) ? res : [];
            $('#badgeCountDiagnosaRanap').text(`${currentDiagnosaListRanap.length} Diagnosa`);

            if (currentDiagnosaListRanap.length === 0) {
                $('#tbodyDiagnosaRanap').html('<tr><td colspan="6" class="text-center text-muted py-3">Belum ada diagnosa tercatat.</td></tr>');
                return;
            }

            let html = '';
            currentDiagnosaListRanap.forEach((item) => {
                const badgePrioritas = parseInt(item.prioritas, 10) === 1
                    ? '<span class="badge bg-primary text-white">1 (Primer)</span>'
                    : `<span class="badge bg-secondary-lt text-secondary">${item.prioritas} (Sekunder)</span>`;
                const badgeStatus = item.status === 'Ranap'
                    ? '<span class="badge bg-teal-lt text-teal">Ranap</span>'
                    : `<span class="badge bg-blue-lt text-blue">${item.status || '-'}</span>`;
                const nmPenyakit = item.penyakit ? item.penyakit.nm_penyakit : '-';

                html += `
                    <tr>
                        <td class="text-center">${badgePrioritas}</td>
                        <td class="fw-bold">${item.kd_penyakit}</td>
                        <td>${nmPenyakit}</td>
                        <td class="text-center">${badgeStatus}</td>
                        <td class="text-center small">${item.status_penyakit || '-'}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-xs btn-outline-danger" onclick="hapusDiagnosaRanap('${noRawat}', '${item.kd_penyakit}')" title="Hapus Diagnosa">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#tbodyDiagnosaRanap').html(html);
        });

        // 2. Load Prosedur
        $.get(`{{ url('/prosedur/pasien/get') }}`, { no_rawat: noRawat }).done(function (res) {
            currentProsedurListRanap = Array.isArray(res) ? res : [];
            $('#badgeCountProsedurRanap').text(`${currentProsedurListRanap.length} Prosedur`);

            if (currentProsedurListRanap.length === 0) {
                $('#tbodyProsedurRanap').html('<tr><td colspan="5" class="text-center text-muted py-3">Belum ada prosedur tercatat.</td></tr>');
                return;
            }

            let html = '';
            currentProsedurListRanap.forEach((item) => {
                const badgePrioritas = parseInt(item.prioritas, 10) === 1
                    ? '<span class="badge bg-teal text-white">1 (Utama)</span>'
                    : `<span class="badge bg-secondary-lt text-secondary">${item.prioritas}</span>`;
                const badgeStatus = item.status === 'Ranap'
                    ? '<span class="badge bg-teal-lt text-teal">Ranap</span>'
                    : `<span class="badge bg-blue-lt text-blue">${item.status || '-'}</span>`;
                const descProsedur = item.icd9 ? item.icd9.deskripsi_panjang : '-';

                html += `
                    <tr>
                        <td class="text-center">${badgePrioritas}</td>
                        <td class="fw-bold">${item.kode}</td>
                        <td>${descProsedur}</td>
                        <td class="text-center">${badgeStatus}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-xs btn-outline-danger" onclick="hapusProsedurRanap('${noRawat}', '${item.kode}')" title="Hapus Prosedur">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#tbodyProsedurRanap').html(html);
        });
    }

    function tambahDiagnosaRanap() {
        const noRawat = formCpptRanap.find('input[name=no_rawat]').val();
        const kdPenyakit = $('#selectDiagnosaRanap').val();
        const statusPenyakit = $('#selectStatusPenyakitRanap').val() || 'Baru';

        if (!noRawat) return;
        if (!kdPenyakit) {
            showToast('Pilih kode diagnosa ICD-10 terlebih dahulu', 'warning');
            return;
        }

        if (currentDiagnosaListRanap.some(d => d.kd_penyakit === kdPenyakit)) {
            showToast('Diagnosa tersebut sudah tercatat untuk kunjungan ini', 'warning');
            return;
        }

        const maxPrioritas = currentDiagnosaListRanap.reduce((max, d) => Math.max(max, parseInt(d.prioritas, 10) || 0), 0);
        const nextPrioritas = maxPrioritas + 1;

        $('#btnAddDiagnosaRanap').prop('disabled', true);
        $.post(`{{ url('/diagnosa/pasien/create') }}`, {
            _token: "{{ csrf_token() }}",
            data: [{
                no_rawat: noRawat,
                kd_penyakit: kdPenyakit,
                status: 'Ranap',
                prioritas: nextPrioritas,
                status_penyakit: statusPenyakit,
            }]
        }).done(function () {
            showToast('Diagnosa Rawat Inap berhasil ditambahkan');
            $('#selectDiagnosaRanap').val(null).trigger('change');
            loadDiagnosaProsedurRanap(noRawat);
        }).fail(function (xhr) {
            alertErrorAjax(xhr);
        }).always(function () {
            $('#btnAddDiagnosaRanap').prop('disabled', false);
        });
    }

    function hapusDiagnosaRanap(noRawat, kdPenyakit) {
        Swal.fire({
            title: 'Hapus Diagnosa?',
            html: `Hapus kode diagnosa <b>${kdPenyakit}</b> dari pasien ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`{{ url('/diagnosa/pasien/delete') }}`, {
                    _token: "{{ csrf_token() }}",
                    no_rawat: noRawat,
                    kd_penyakit: kdPenyakit
                }).done(function () {
                    showToast('Diagnosa dihapus');
                    loadDiagnosaProsedurRanap(noRawat);
                }).fail(function (xhr) {
                    alertErrorAjax(xhr);
                });
            }
        });
    }

    function tambahProsedurRanap() {
        const noRawat = formCpptRanap.find('input[name=no_rawat]').val();
        const kode = $('#selectProsedurRanap').val();

        if (!noRawat) return;
        if (!kode) {
            showToast('Pilih kode prosedur ICD-9 terlebih dahulu', 'warning');
            return;
        }

        if (currentProsedurListRanap.some(p => p.kode === kode)) {
            showToast('Prosedur tersebut sudah tercatat untuk kunjungan ini', 'warning');
            return;
        }

        const maxPrioritas = currentProsedurListRanap.reduce((max, p) => Math.max(max, parseInt(p.prioritas, 10) || 0), 0);
        const nextPrioritas = maxPrioritas + 1;

        $('#btnAddProsedurRanap').prop('disabled', true);
        $.post(`{{ url('/prosedur/pasien/create') }}`, {
            _token: "{{ csrf_token() }}",
            data: [{
                no_rawat: noRawat,
                kode: kode,
                status: 'Ranap',
                prioritas: nextPrioritas,
            }]
        }).done(function () {
            showToast('Prosedur Rawat Inap berhasil ditambahkan');
            $('#selectProsedurRanap').val(null).trigger('change');
            loadDiagnosaProsedurRanap(noRawat);
        }).fail(function (xhr) {
            alertErrorAjax(xhr);
        }).always(function () {
            $('#btnAddProsedurRanap').prop('disabled', false);
        });
    }

    function hapusProsedurRanap(noRawat, kode) {
        Swal.fire({
            title: 'Hapus Prosedur?',
            html: `Hapus kode prosedur <b>${kode}</b> dari pasien ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`{{ url('/prosedur/pasien/delete') }}`, {
                    _token: "{{ csrf_token() }}",
                    no_rawat: noRawat,
                    kode: kode
                }).done(function () {
                    showToast('Prosedur dihapus');
                    loadDiagnosaProsedurRanap(noRawat);
                }).fail(function (xhr) {
                    alertErrorAjax(xhr);
                });
            }
        });
    }
</script>
@endpush

@extends('layout')

@section('body')
<div class="container-fluid py-3">
    <!-- Page Header -->
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center g-2">
            <div class="col">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar bg-teal-lt text-teal rounded-circle" style="width: 40px; height: 40px;">
                        <i class="ti ti-speakerphone fs-2"></i>
                    </div>
                    <div>
                        <h2 class="page-title mb-0">Pemanggil Antrean Loket Pendaftaran</h2>
                        <div class="text-muted small">Kelola dan panggil nomor antrean tiket Anjungan (BPJS / Umum) secara real-time</div>
                    </div>
                </div>
            </div>
            <div class="col-auto d-flex flex-wrap align-items-center gap-2">
                <div class="form-check form-switch mb-0 me-2">
                    <input class="form-check-input" type="checkbox" id="toggleLocalVoice">
                    <label class="form-check-label small fw-semibold" for="toggleLocalVoice">Suara di Perangkat Ini</label>
                </div>
                <a href="{{ url('antrean/loket') }}" target="_blank" class="btn btn-outline-teal btn-sm">
                    <i class="ti ti-device-tv me-1"></i> Buka Display TV Loket
                </a>
                <a href="{{ url('registrasi') }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-users me-1"></i> Halaman Registrasi
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row row-cards mb-3">
        <div class="col-6 col-md-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green-lt text-green avatar"><i class="ti ti-ticket"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-2 fw-bold" id="sumWaitingA">0</div>
                            <div class="text-muted small">Menunggu A (BPJS)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue-lt text-blue avatar"><i class="ti ti-ticket"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-2 fw-bold" id="sumWaitingB">0</div>
                            <div class="text-muted small">Menunggu B (Umum)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-yellow-lt text-yellow avatar"><i class="ti ti-volume"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-2 fw-bold" id="sumCalling">0</div>
                            <div class="text-muted small">Sedang Dipanggil</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-teal-lt text-teal avatar"><i class="ti ti-check"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium fs-2 fw-bold" id="sumFinished">0</div>
                            <div class="text-muted small">Selesai Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Left Column: Caller Control -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-teal text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 text-white"><i class="ti ti-headset me-2"></i>Konsol Petugas Loket</h3>
                    <span class="badge bg-white text-teal fw-bold" id="badgeLoketAktif">LOKET 1</span>
                </div>
                <div class="card-body">
                    <!-- Pilih Loket & Layanan -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Loket Saya</label>
                            <select class="form-select" id="selectLoket">
                                <option value="1">Loket 1</option>
                                <option value="2">Loket 2</option>
                                <option value="3">Loket 3</option>
                                <option value="4">Loket 4</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Filter Layanan</label>
                            <select class="form-select" id="selectLayanan">
                                <option value="ALL">Semua (A &amp; B)</option>
                                <option value="A">Kode A — BPJS</option>
                                <option value="B">Kode B — Umum / Asuransi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kartu Antrean Sedang Dilayani di Loket Ini -->
                    <div class="card bg-light border-dashed mb-3 text-center">
                        <div class="card-body py-4">
                            <div class="text-muted text-uppercase fw-bold small mb-1" id="currentLoketLabel">Sedang Dipanggil di Loket 1</div>
                            <div id="currentCallNumber" class="fw-bold text-teal my-1" style="font-size: 4rem; line-height: 1.1;">-</div>
                            <div id="currentCallMeta" class="small text-muted mb-3">Belum ada antrean aktif</div>

                            <div class="d-flex flex-wrap justify-content-center gap-2" id="activeCallActions" style="display: none !important;">
                                <button type="button" class="btn btn-warning btn-sm px-3" id="btnRecallActive" onclick="recallActive()">
                                    <i class="ti ti-volume me-1"></i> Panggil Ulang
                                </button>
                                <button type="button" class="btn btn-success btn-sm px-3" id="btnFinishActive" onclick="finishActive()">
                                    <i class="ti ti-check me-1"></i> Selesai
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="btnSkipActive" onclick="skipActive()">
                                    <i class="ti ti-arrow-back-up me-1"></i> Lewati
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Panggil Berikutnya -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-teal btn-lg fw-bold py-3 shadow-sm" id="btnCallNext" onclick="callNext('')">
                            <i class="ti ti-player-skip-forward me-2"></i> PANGGIL ANTREAN BERIKUTNYA
                        </button>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-green w-100 fw-semibold" onclick="callNext('A')">
                                    <i class="ti ti-shield-check me-1"></i> Panggil Kode A (BPJS)
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-blue w-100 fw-semibold" onclick="callNext('B')">
                                    <i class="ti ti-wallet me-1"></i> Panggil Kode B (Umum)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Semua Loket -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0"><i class="ti ti-layout-grid me-2 text-teal"></i>Status Loket Aktif</h3>
                </div>
                <div class="card-body p-2">
                    <div class="row g-2" id="loketGrid">
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="col-6">
                            <div class="border rounded p-2 text-center bg-surface" id="loketCard{{ $i }}">
                                <div class="text-muted small fw-semibold">Loket {{ $i }}</div>
                                <div class="fw-bold fs-2 text-dark" id="loketVal{{ $i }}">-</div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Queue Lists -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tabWaiting" class="nav-link active" data-bs-toggle="tab">
                                <i class="ti ti-clock me-1"></i> Menunggu <span class="badge bg-teal-lt text-teal ms-1" id="badgeTabWaiting">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabCalling" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-volume me-1"></i> Dipanggil <span class="badge bg-yellow-lt text-yellow ms-1" id="badgeTabCalling">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabFinished" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-check me-1"></i> Selesai <span class="badge bg-green-lt text-green ms-1" id="badgeTabFinished">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <!-- Tab Menunggu -->
                        <div class="tab-pane active show" id="tabWaiting">
                            <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                                <table class="table table-vcenter table-hover card-table mb-0">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th>No. Antrean</th>
                                            <th>Layanan</th>
                                            <th>Waktu Ambil</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyWaiting">
                                        <tr><td colspan="4" class="text-center text-muted py-4">Memuat data...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Dipanggil -->
                        <div class="tab-pane" id="tabCalling">
                            <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                                <table class="table table-vcenter table-hover card-table mb-0">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th>No. Antrean</th>
                                            <th>Loket</th>
                                            <th>Panggilan</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyCalling">
                                        <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada antrean dipanggil</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Selesai -->
                        <div class="tab-pane" id="tabFinished">
                            <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                                <table class="table table-vcenter table-hover card-table mb-0">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th>No. Antrean</th>
                                            <th>Layanan</th>
                                            <th>Loket</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyFinished">
                                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada antrean selesai</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    const LOKET_STORAGE_KEY = 'efktp_loket_number';
    const LAYANAN_STORAGE_KEY = 'efktp_loket_layanan';
    const VOICE_STORAGE_KEY = 'efktp_loket_local_voice';
    let currentActiveTicket = null;
    let isActionBusy = false;

    $(document).ready(function () {
        const savedLoket = localStorage.getItem(LOKET_STORAGE_KEY) || '1';
        const savedLayanan = localStorage.getItem(LAYANAN_STORAGE_KEY) || 'ALL';
        const savedVoice = localStorage.getItem(VOICE_STORAGE_KEY) === '1';

        $('#selectLoket').val(savedLoket);
        $('#selectLayanan').val(savedLayanan);
        $('#toggleLocalVoice').prop('checked', savedVoice);
        updateLoketLabels();

        $('#selectLoket').on('change', function () {
            localStorage.setItem(LOKET_STORAGE_KEY, $(this).val());
            updateLoketLabels();
            loadQueueData();
        });

        $('#selectLayanan').on('change', function () {
            localStorage.setItem(LAYANAN_STORAGE_KEY, $(this).val());
            loadQueueData();
        });

        $('#toggleLocalVoice').on('change', function () {
            localStorage.setItem(VOICE_STORAGE_KEY, $(this).is(':checked') ? '1' : '0');
        });

        loadQueueData();
        setInterval(loadQueueData, 3500);
    });

    function updateLoketLabels() {
        const loket = $('#selectLoket').val();
        $('#badgeLoketAktif').text('LOKET ' + loket);
        $('#currentLoketLabel').text('Sedang Dipanggil di Loket ' + loket);
    }

    function formatTimeFromKdList(kdList) {
        if (!kdList || kdList.length < 12) return '-';
        return kdList.substr(6, 2) + ':' + kdList.substr(8, 2) + ':' + kdList.substr(10, 2);
    }

    function layananBadge(kd) {
        if (kd === 'A') {
            return '<span class="badge bg-green-lt text-green">A — BPJS Kesehatan</span>';
        }
        return '<span class="badge bg-blue-lt text-blue">B — Umum / Asuransi</span>';
    }

    function speakTicketIfEnabled(antrian, loket) {
        if (!$('#toggleLocalVoice').is(':checked')) return;
        if (!('speechSynthesis' in window)) return;

        try {
            window.speechSynthesis.cancel();
            const spokenNumber = antrian.split('').join(' ');
            const text = `Nomor antrean ${spokenNumber}, silakan menuju ke loket ${loket}`;
            const utter = new SpeechSynthesisUtterance(text);
            const voices = window.speechSynthesis.getVoices();
            const idVoice = voices.find(v => v.lang && v.lang.toLowerCase().includes('id'));
            if (idVoice) utter.voice = idVoice;
            utter.lang = 'id-ID';
            utter.rate = 0.9;
            window.speechSynthesis.speak(utter);
        } catch (e) {}
    }

    function loadQueueData() {
        const kdLayanan = $('#selectLayanan').val();
        const myLoket = parseInt($('#selectLoket').val(), 10);

        $.get("{{ route('antrean.loket.data') }}", { kd_layanan: kdLayanan })
            .done(function (res) {
                if (!res || !res.success) return;

                // Summary
                $('#sumWaitingA').text(res.summary.waiting_a);
                $('#sumWaitingB').text(res.summary.waiting_b);
                $('#sumCalling').text(res.summary.calling_total);
                $('#sumFinished').text(res.summary.finished_total);

                $('#badgeTabWaiting').text(res.waiting.length);
                $('#badgeTabCalling').text(res.calling.length);
                $('#badgeTabFinished').text(res.finished.length);

                // Active ticket for selected loket
                currentActiveTicket = res.loket_active ? res.loket_active[myLoket] : null;
                if (currentActiveTicket) {
                    $('#currentCallNumber').text(currentActiveTicket.antrian);
                    const labelLayanan = currentActiveTicket.kd_layanan === 'A' ? 'BPJS Kesehatan' : 'Umum / Asuransi';
                    const callCount = currentActiveTicket.call_count || 1;
                    $('#currentCallMeta').html(`${labelLayanan} &bull; Dipanggil <strong>${callCount}x</strong>`);
                    $('#activeCallActions').attr('style', 'display: flex !important;');
                } else {
                    $('#currentCallNumber').text('-');
                    $('#currentCallMeta').text('Belum ada antrean aktif di loket ini');
                    $('#activeCallActions').attr('style', 'display: none !important;');
                }

                // Grid 4 loket
                for (let i = 1; i <= 4; i++) {
                    const t = res.loket_active ? res.loket_active[i] : null;
                    $('#loketVal' + i).text(t ? t.antrian : '-').toggleClass('text-teal', !!t).toggleClass('text-muted', !t);
                    $('#loketCard' + i).toggleClass('border-teal bg-teal-lt', i === myLoket);
                }

                // Render waiting table
                if (res.waiting.length === 0) {
                    $('#tbodyWaiting').html('<tr><td colspan="4" class="text-center text-muted py-4">Tidak ada antrean menunggu</td></tr>');
                } else {
                    let html = '';
                    res.waiting.forEach(item => {
                        html += `
                            <tr>
                                <td><span class="fw-bold fs-3">${item.antrian}</span></td>
                                <td>${layananBadge(item.kd_layanan)}</td>
                                <td class="text-muted small">${formatTimeFromKdList(item.kd_list)} WIB</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-teal" onclick="callSpecific('${item.kd_list}')">
                                        <i class="ti ti-volume me-1"></i> Panggil
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tbodyWaiting').html(html);
                }

                // Render calling table
                if (res.calling.length === 0) {
                    $('#tbodyCalling').html('<tr><td colspan="4" class="text-center text-muted py-4">Tidak ada antrean sedang dipanggil</td></tr>');
                } else {
                    let html = '';
                    res.calling.forEach(item => {
                        html += `
                            <tr>
                                <td><span class="fw-bold fs-3 text-teal">${item.antrian}</span></td>
                                <td><span class="badge bg-teal text-white">Loket ${item.kd_loket ?? '-'}</span></td>
                                <td class="small text-muted">${item.call_count || 1}x</td>
                                <td class="text-end">
                                    <div class="btn-list justify-content-end flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-warning" onclick="callSpecific('${item.kd_list}', ${item.kd_loket || myLoket})">
                                            <i class="ti ti-volume"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" onclick="finishTicket('${item.kd_list}')">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="skipTicket('${item.kd_list}')" title="Kembalikan ke menunggu">
                                            <i class="ti ti-arrow-back-up"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tbodyCalling').html(html);
                }

                // Render finished table
                if (res.finished.length === 0) {
                    $('#tbodyFinished').html('<tr><td colspan="4" class="text-center text-muted py-4">Belum ada antrean selesai</td></tr>');
                } else {
                    let html = '';
                    res.finished.forEach(item => {
                        html += `
                            <tr>
                                <td><span class="fw-bold">${item.antrian}</span></td>
                                <td>${layananBadge(item.kd_layanan)}</td>
                                <td><span class="badge bg-secondary-lt">Loket ${item.kd_loket ?? '-'}</span></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-teal" onclick="callSpecific('${item.kd_list}')">
                                        <i class="ti ti-refresh me-1"></i> Panggil Lagi
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tbodyFinished').html(html);
                }
            });
    }

    function callNext(overrideLayanan) {
        if (isActionBusy) return;
        isActionBusy = true;

        const kdLoket = $('#selectLoket').val();
        const kdLayanan = overrideLayanan || $('#selectLayanan').val();

        $.post("{{ route('antrean.loket.call-next') }}", {
            _token: "{{ csrf_token() }}",
            kd_loket: kdLoket,
            kd_layanan: kdLayanan
        }).done(function (res) {
            if (res.success && res.data) {
                showToast(res.message, 'success');
                speakTicketIfEnabled(res.data.antrian, kdLoket);
            } else {
                showToast(res.message || 'Tidak ada antrean menunggu', 'warning');
            }
            loadQueueData();
        }).fail(function (xhr) {
            showToast(xhr.responseJSON?.message || 'Gagal memanggil antrean', 'error');
        }).always(function () {
            isActionBusy = false;
        });
    }

    function callSpecific(kdList, targetLoket = null) {
        if (isActionBusy) return;
        isActionBusy = true;

        const kdLoket = targetLoket || $('#selectLoket').val();
        $.post("{{ route('antrean.loket.recall') }}", {
            _token: "{{ csrf_token() }}",
            kd_list: kdList,
            kd_loket: kdLoket
        }).done(function (res) {
            if (res.success && res.data) {
                showToast(res.message, 'success');
                speakTicketIfEnabled(res.data.antrian, kdLoket);
            } else {
                showToast(res.message || 'Gagal memanggil antrean', 'warning');
            }
            loadQueueData();
        }).fail(function (xhr) {
            showToast(xhr.responseJSON?.message || 'Gagal memanggil antrean', 'error');
        }).always(function () {
            isActionBusy = false;
        });
    }

    function recallActive() {
        if (!currentActiveTicket) return;
        callSpecific(currentActiveTicket.kd_list);
    }

    function finishActive() {
        if (!currentActiveTicket) return;
        finishTicket(currentActiveTicket.kd_list);
    }

    function skipActive() {
        if (!currentActiveTicket) return;
        skipTicket(currentActiveTicket.kd_list);
    }

    function finishTicket(kdList) {
        if (isActionBusy) return;
        isActionBusy = true;

        $.post("{{ route('antrean.loket.finish') }}", {
            _token: "{{ csrf_token() }}",
            kd_list: kdList
        }).done(function (res) {
            showToast(res.message || 'Antrean selesai', 'success');
            loadQueueData();
        }).fail(function (xhr) {
            showToast(xhr.responseJSON?.message || 'Gagal menyelesaikan antrean', 'error');
        }).always(function () {
            isActionBusy = false;
        });
    }

    function skipTicket(kdList) {
        if (isActionBusy) return;
        isActionBusy = true;

        $.post("{{ route('antrean.loket.skip') }}", {
            _token: "{{ csrf_token() }}",
            kd_list: kdList
        }).done(function (res) {
            showToast(res.message || 'Antrean dikembalikan ke daftar tunggu', 'info');
            loadQueueData();
        }).fail(function (xhr) {
            showToast(xhr.responseJSON?.message || 'Gagal melewati antrean', 'error');
        }).always(function () {
            isActionBusy = false;
        });
    }
</script>
@endpush

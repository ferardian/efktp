@if (config('app.notifikasi_selesai_ralan', true))
<div class="nav-item dropdown me-2" id="container-notif-ralan">
    <a href="javascript:void(0)" class="nav-link px-2 position-relative" data-bs-toggle="dropdown" tabindex="-1" aria-label="Notifikasi Pasien Selesai" id="btn-bell-notif" title="Pasien Selesai Periksa" data-bs-auto-close="outside">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell" id="icon-bell-ralan" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
        </svg>
        <span class="badge bg-danger text-white position-absolute top-0 start-100 badge-pill" id="notif-ralan-badge" style="display: none; font-size: 0.65rem; padding: 2px 5px; transform: translate(-40%, -20%) !important; border: 1.5px solid #fff;">
            0
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end shadow-lg p-0" id="notif-dropdown-menu" style="min-width: 380px; max-width: 420px; font-size: 11px; z-index: 1060;">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
            <div>
                <span class="fw-bold fs-3 notif-patient-name"><i class="ti ti-bell-ringing text-primary me-1"></i> Selesai Periksa</span>
                <span class="badge bg-primary text-white ms-1 px-2" id="notif-header-count">0</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <a href="{{ url('/kasir/ralan') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 10px;">
                    <i class="ti ti-cash me-1"></i> Kasir Ralan
                </a>
                <button type="button" class="btn btn-sm btn-ghost-secondary p-1" onclick="fetchNotifikasiSelesai(true)" title="Segarkan data">
                    <i class="ti ti-refresh" id="notif-refresh-icon"></i>
                </button>
            </div>
        </div>

        <!-- Dynamic Controls (On / Off Switch Mandiri per User) -->
        <div class="bg-light-subtle px-3 py-1 border-bottom d-flex align-items-center justify-content-between" style="font-size: 10px;">
            <div class="form-check form-switch mb-0 d-flex align-items-center me-2">
                <input class="form-check-input me-1" type="checkbox" role="switch" id="toggle-notif-active" checked style="cursor: pointer;">
                <label class="form-check-label user-select-none fw-semibold" for="toggle-notif-active" style="cursor: pointer;" title="Nyalakan/matikan auto-notifikasi di komputer ini">Aktif</label>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0 d-flex align-items-center">
                    <input class="form-check-input me-1" type="checkbox" role="switch" id="toggle-notif-sound" checked style="cursor: pointer;">
                    <label class="form-check-label user-select-none" for="toggle-notif-sound" style="cursor: pointer;" title="Bunyikan suara saat ada pasien baru">
                        <i class="ti ti-volume text-secondary" id="icon-sound-status"></i> Suara
                    </label>
                </div>
                <div class="form-check form-switch mb-0 d-flex align-items-center">
                    <input class="form-check-input me-1" type="checkbox" role="switch" id="toggle-notif-toast" checked style="cursor: pointer;">
                    <label class="form-check-label user-select-none" for="toggle-notif-toast" style="cursor: pointer;" title="Tampilkan pop-up toast di pojok layar">
                        <i class="ti ti-message-2 text-secondary"></i> Pop-up
                    </label>
                </div>
            </div>
        </div>

        <!-- Body / List -->
        <div class="list-group list-group-flush" id="notif-list-container" style="max-height: 360px; overflow-y: auto;">
            <div class="text-center text-muted p-4" id="notif-loading-state">
                <div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div>
                Memeriksa antrean pasien selesai...
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-muted py-1 px-3 d-flex justify-content-between align-items-center border-top bg-surface" style="font-size: 9px;">
            <span id="notif-last-updated"><i class="ti ti-clock me-1"></i> Belum diperbarui</span>
            <span class="badge bg-success-subtle text-success py-0" id="notif-status-indicator">Auto (12s)</span>
        </div>
    </div>
</div>

<!-- Floating Toast Container -->
<div id="notif-toast-container" class="position-fixed" style="bottom: 20px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 8px; pointer-events: none; max-width: 380px; width: 100%;"></div>

<style>
    @keyframes bellRing {
        0% { transform: rotate(0); }
        15% { transform: rotate(15deg); }
        30% { transform: rotate(-15deg); }
        45% { transform: rotate(10deg); }
        60% { transform: rotate(-10deg); }
        75% { transform: rotate(4deg); }
        85% { transform: rotate(-4deg); }
        100% { transform: rotate(0); }
    }
    .bell-ring-anim {
        animation: bellRing 0.8s ease-in-out infinite;
        transform-origin: top center;
        color: #e03131 !important;
    }
    .notif-toast-card {
        pointer-events: auto;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-left: 5px solid #206bc4;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 10px 12px;
        transition: all 0.3s ease;
        animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    [data-bs-theme="dark"] .notif-toast-card {
        background: #1e293b;
        color: #f8fafc;
        border-color: #334155;
    }
    @keyframes toastSlideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .notif-item-hover:hover {
        background-color: rgba(32, 107, 196, 0.08);
    }
    #notif-dropdown-menu .notif-patient-name {
        color: #1e293b !important;
    }
    [data-bs-theme="dark"] #notif-dropdown-menu .notif-patient-name,
    header[data-bs-theme="dark"] #notif-dropdown-menu .notif-patient-name,
    body[data-bs-theme="dark"] #notif-dropdown-menu .notif-patient-name,
    .theme-dark #notif-dropdown-menu .notif-patient-name {
        color: #f8fafc !important;
    }
    #notif-dropdown-menu .notif-meta-text {
        color: #64748b !important;
    }
    [data-bs-theme="dark"] #notif-dropdown-menu .notif-meta-text,
    header[data-bs-theme="dark"] #notif-dropdown-menu .notif-meta-text,
    body[data-bs-theme="dark"] #notif-dropdown-menu .notif-meta-text,
    .theme-dark #notif-dropdown-menu .notif-meta-text {
        color: #cbd5e1 !important;
    }
    #notif-dropdown-menu .list-group-item {
        background-color: transparent !important;
        border-color: rgba(125, 125, 125, 0.15) !important;
    }
</style>

@push('script')
<script>
    (function () {
        // Preference keys
        const KEY_ENABLED = 'efktp_notif_ralan_enabled';
        const KEY_SOUND   = 'efktp_notif_ralan_sound';
        const KEY_TOAST   = 'efktp_notif_ralan_toast';
        const KEY_KNOWN   = 'efktp_notif_known_rawat';

        let pollingTimer = null;
        let isInitialLoad = true;

        // Load preferences
        function getPref(key, defaultVal) {
            const val = localStorage.getItem(key);
            return val === null ? defaultVal : val === 'true';
        }

        let isEnabled = getPref(KEY_ENABLED, true);
        let isSoundOn = getPref(KEY_SOUND, true);
        let isToastOn = getPref(KEY_TOAST, true);

        // Track known no_rawat to identify *new* completions
        let knownRawats = new Set();
        try {
            const stored = JSON.parse(localStorage.getItem(KEY_KNOWN) || '[]');
            if (Array.isArray(stored)) {
                knownRawats = new Set(stored);
            }
        } catch (e) {
            knownRawats = new Set();
        }

        function initNotification() {
            if (typeof $ === 'undefined') return;

            $('#toggle-notif-active').prop('checked', isEnabled);
            $('#toggle-notif-sound').prop('checked', isSoundOn);
            $('#toggle-notif-toast').prop('checked', isToastOn);

            updateSoundIcon();
            updateStatusIndicator();

            $('#toggle-notif-active').on('change', function () {
                isEnabled = $(this).is(':checked');
                localStorage.setItem(KEY_ENABLED, isEnabled);
                updateStatusIndicator();
                if (isEnabled) {
                    fetchNotifikasiSelesai(false);
                    startPolling();
                } else {
                    stopPolling();
                    $('#notif-ralan-badge').hide();
                    $('#icon-bell-ralan').removeClass('bell-ring-anim');
                }
            });

            $('#toggle-notif-sound').on('change', function () {
                isSoundOn = $(this).is(':checked');
                localStorage.setItem(KEY_SOUND, isSoundOn);
                updateSoundIcon();
                if (isSoundOn) {
                    playNotificationChime();
                }
            });

            $('#toggle-notif-toast').on('change', function () {
                isToastOn = $(this).is(':checked');
                localStorage.setItem(KEY_TOAST, isToastOn);
            });

            if (isEnabled) {
                fetchNotifikasiSelesai(false);
                startPolling();
            } else {
                $('#notif-loading-state').html('<div class="text-muted"><i class="ti ti-bell-off me-1"></i> Notifikasi dinonaktifkan di perangkat ini.</div>');
            }
        }

        if (window.jQuery) {
            $(document).ready(initNotification);
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                if (window.jQuery) {
                    $(document).ready(initNotification);
                }
            });
        }

        function updateSoundIcon() {
            if (isSoundOn) {
                $('#icon-sound-status').attr('class', 'ti ti-volume text-success');
            } else {
                $('#icon-sound-status').attr('class', 'ti ti-volume-3 text-muted');
            }
        }

        function updateStatusIndicator() {
            if (isEnabled) {
                $('#notif-status-indicator').removeClass('bg-secondary-subtle text-secondary').addClass('bg-success-subtle text-success').text('Auto (12s)');
            } else {
                $('#notif-status-indicator').removeClass('bg-success-subtle text-success').addClass('bg-secondary-subtle text-secondary').text('Nonaktif');
            }
        }

        function startPolling() {
            stopPolling();
            if (!isEnabled) return;
            pollingTimer = setInterval(function () {
                fetchNotifikasiSelesai(false);
            }, 12000);
        }

        function stopPolling() {
            if (pollingTimer) {
                clearInterval(pollingTimer);
                pollingTimer = null;
            }
        }

        // Web Audio Chime Synthesizer (Zero asset dependency, smooth melodic 2-tone)
        function playNotificationChime() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                if (ctx.state === 'suspended') {
                    ctx.resume();
                }
                const now = ctx.currentTime;

                // High Tone (A5 - 880Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(880, now);
                gain1.gain.setValueAtTime(0.16, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.45);

                // Higher Melody Tone (E6 - 1318.5Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1318.5, now + 0.12);
                gain2.gain.setValueAtTime(0.14, now + 0.12);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.75);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.12);
                osc2.stop(now + 0.75);
            } catch (err) {
                console.warn('Audio chime notice:', err);
            }
        }

        // Main Fetch Function
        window.fetchNotifikasiSelesai = function (isManual = false) {
            if (!isEnabled && !isManual) return;

            const refreshIcon = $('#notif-refresh-icon');
            refreshIcon.addClass('ti-spin');

            $.get(`{{ url('/notifikasi/ralan-selesai') }}`)
                .done(function (res) {
                    refreshIcon.removeClass('ti-spin');

                    if (!res || res.enabled === false) {
                        $('#container-notif-ralan').hide();
                        stopPolling();
                        return;
                    }

                    const count = res.count || 0;
                    const data = res.data || [];

                    // Update Badge Counter
                    const badge = $('#notif-ralan-badge');
                    const bellIcon = $('#icon-bell-ralan');
                    $('#notif-header-count').text(count);

                    if (count > 0) {
                        badge.text(count > 99 ? '99+' : count).show();
                    } else {
                        badge.hide();
                        bellIcon.removeClass('bell-ring-anim');
                    }

                    // Check for newly finished patients
                    let newlyAdded = [];
                    data.forEach(item => {
                        if (!knownRawats.has(item.no_rawat)) {
                            newlyAdded.push(item);
                            knownRawats.add(item.no_rawat);
                        }
                    });

                    // Persist known rawats (keep last 50)
                    const knownArr = Array.from(knownRawats).slice(-50);
                    localStorage.setItem(KEY_KNOWN, JSON.stringify(knownArr));

                    // If newly finished patients found (and not first page load)
                    if (newlyAdded.length > 0 && !isInitialLoad) {
                        bellIcon.addClass('bell-ring-anim');

                        // Ring audio chime if sound enabled
                        if (isSoundOn) {
                            playNotificationChime();
                        }

                        // Show Floating Toast if toast enabled
                        if (isToastOn) {
                            newlyAdded.forEach(p => {
                                showFloatingToast(p);
                            });
                        }
                    } else if (newlyAdded.length === 0 && count === 0) {
                        bellIcon.removeClass('bell-ring-anim');
                    }

                    isInitialLoad = false;
                    renderNotifList(data, count);

                    // Update timestamp
                    const now = new Date();
                    const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0') + ':' + String(now.getSeconds()).padStart(2, '0');
                    $('#notif-last-updated').html(`<i class="ti ti-check text-success me-1"></i> Diperbarui ${timeStr}`);
                })
                .fail(function () {
                    refreshIcon.removeClass('ti-spin');
                    $('#notif-last-updated').html('<i class="ti ti-alert-triangle text-danger me-1"></i> Gagal terhubung');
                });
        };

        // Render List in Dropdown
        function renderNotifList(data, totalCount) {
            const container = $('#notif-list-container');

            if (!data || data.length === 0) {
                container.html(`
                    <div class="text-center text-muted p-4">
                        <i class="ti ti-circle-check text-success fs-1 mb-2 d-block"></i>
                        <div class="fw-semibold">Semua Pasien Sudah Diproses</div>
                        <div class="small">Belum ada pasien yang baru selesai periksa.</div>
                    </div>
                `);
                return;
            }

            let html = '';
            data.forEach(item => {
                const jam = item.jam_selesai || item.jam_reg || '-';
                const penjaminBadge = item.png_jawab && item.png_jawab.toLowerCase().includes('bpjs')
                    ? '<span class="badge bg-green text-white" style="font-size: 8.5px; padding: 2px 6px;">BPJS</span>'
                    : `<span class="badge bg-secondary text-white" style="font-size: 8.5px; padding: 2px 6px;">${item.png_jawab || 'Umum'}</span>`;

                const urlKasir = `{{ url('/kasir/ralan') }}?search=${encodeURIComponent(item.no_rawat)}&auto_select=1`;

                html += `
                    <div class="list-group-item p-2 notif-item-hover border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <span class="fw-bold fs-4 notif-patient-name">${item.nm_pasien}</span>
                                <span class="notif-meta-text ms-1 small">(${item.no_rkm_medis})</span>
                            </div>
                            <span class="badge bg-info-subtle text-info border border-info" style="font-size: 9px;" title="Jam Selesai Periksa">
                                <i class="ti ti-clock me-1"></i>${jam}
                            </span>
                        </div>
                        <div class="small notif-meta-text d-flex justify-content-between align-items-center mb-1">
                            <span><i class="ti ti-building-hospital me-1 text-primary"></i>${item.nm_poli}</span>
                            ${penjaminBadge}
                        </div>
                        <div class="small notif-meta-text d-flex justify-content-between align-items-center">
                            <span><i class="ti ti-stethoscope me-1 text-info"></i>${item.nm_dokter}</span>
                            <a href="${urlKasir}" class="btn btn-sm btn-primary py-0 px-2 d-inline-flex align-items-center" style="font-size: 9.5px;">
                                <i class="ti ti-cash me-1"></i> Kasir
                            </a>
                        </div>
                    </div>
                `;
            });

            if (totalCount > data.length) {
                html += `
                    <div class="text-center p-2 border-top" style="background-color: rgba(125, 125, 125, 0.05);">
                        <a href="{{ url('/kasir/ralan') }}" class="text-primary fw-semibold" style="font-size: 10px;">
                            Lihat ${totalCount - data.length} pasien lainnya di Kasir Ralan &raquo;
                        </a>
                    </div>
                `;
            }

            container.html(html);
        }

        // Floating Toast Popup
        function showFloatingToast(patient) {
            const toastId = 'toast-notif-' + Math.random().toString(36).substr(2, 9);
            const jam = patient.jam_selesai || patient.jam_reg || '';
            const urlKasir = `{{ url('/kasir/ralan') }}?search=${encodeURIComponent(patient.no_rawat)}&auto_select=1`;

            const toastHtml = `
                <div class="notif-toast-card" id="${toastId}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-success text-white py-0 px-1" style="font-size: 8.5px;">
                                <i class="ti ti-check me-1"></i> Selesai Periksa
                            </span>
                            <span class="badge bg-primary-subtle text-primary py-0 px-1" style="font-size: 8.5px;">
                                ${patient.nm_poli}
                            </span>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" style="font-size: 9px;" onclick="$('#${toastId}').fadeOut(250, function(){ $(this).remove(); });"></button>
                    </div>
                    <div class="fw-bold fs-4 mb-0 notif-patient-name">${patient.nm_pasien}</div>
                    <div class="small notif-meta-text d-flex justify-content-between align-items-center mt-1">
                        <span><i class="ti ti-stethoscope me-1 text-primary"></i>${patient.nm_dokter}</span>
                        <span class="text-muted"><i class="ti ti-clock me-1"></i>${jam}</span>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-2 pt-1 border-top">
                        <button type="button" class="btn btn-sm btn-ghost-secondary py-0 px-2" style="font-size: 10px;" onclick="$('#${toastId}').fadeOut(250, function(){ $(this).remove(); });">
                            Tutup
                        </button>
                        <a href="${urlKasir}" class="btn btn-sm btn-primary py-0 px-2" style="font-size: 10px;">
                            <i class="ti ti-cash me-1"></i> Proses Kasir
                        </a>
                    </div>
                </div>
            `;

            const container = $('#notif-toast-container');
            container.append(toastHtml);

            // Auto remove after 9 seconds
            setTimeout(function () {
                $(`#${toastId}`).fadeOut(400, function () {
                    $(this).remove();
                });
            }, 9000);
        }
    })();
</script>
@endpush
@endif

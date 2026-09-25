<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Anjungan Pendaftaran Mandiri - {{ $setting->nama_instansi ?? 'Klinik / FKTP' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tabler CSS -->
    <link rel="stylesheet" href="{{ asset('css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabler-icon/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/sweetalert/sweetalert2.min.css') }}">

    <style>
        :root {
            --kiosk-bg: #090e1a;
            --kiosk-surface: #0f172a;
            --kiosk-surface-card: #141e33;
            --kiosk-border: rgba(255, 255, 255, 0.08);
            --kiosk-primary: #0d9488;
            --kiosk-primary-hover: #0f766e;
            --kiosk-accent: #2563eb;
            --kiosk-accent-hover: #1d4ed8;
            --kiosk-emerald: #10b981;
            --kiosk-amber: #f59e0b;
            --kiosk-text-main: #f8fafc;
            --kiosk-text-muted: #94a3b8;
        }

        * {
            -webkit-tap-highlight-color: transparent;
            user-select: none;
            -webkit-user-select: none;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--kiosk-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.6) 0px, transparent 100%);
            color: var(--kiosk-text-main);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Kiosk Header */
        .kiosk-header {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--kiosk-border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .kiosk-brand-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            padding: 4px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .kiosk-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
        }

        .kiosk-subtitle {
            font-size: 0.85rem;
            color: var(--kiosk-text-muted);
            margin: 0;
        }

        .kiosk-clock-badge {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--kiosk-border);
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .kiosk-clock-time {
            font-size: 1.4rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            color: #38bdf8;
            letter-spacing: 0.05em;
        }

        .kiosk-clock-date {
            font-size: 0.85rem;
            color: #cbd5e1;
            font-weight: 500;
        }

        .live-pulse {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Tactile Touch Cards & Buttons */
        .touch-card {
            background: var(--kiosk-surface-card);
            border: 1px solid var(--kiosk-border);
            border-radius: 20px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .touch-card:hover {
            border-color: rgba(13, 148, 136, 0.4);
            box-shadow: 0 20px 40px -15px rgba(13, 148, 136, 0.25);
            transform: translateY(-3px);
        }

        .touch-card:active {
            transform: scale(0.985);
        }

        .btn-touch {
            border-radius: 14px;
            padding: 1rem 1.75rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            border: none;
        }

        .btn-touch:active {
            transform: scale(0.97);
        }

        .btn-touch-primary {
            background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-touch-primary:hover {
            background: linear-gradient(135deg, #0f766e 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.4);
        }

        .btn-touch-blue {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-touch-blue:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: #ffffff;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        .btn-touch-emerald {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-touch-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid var(--kiosk-border);
        }

        .btn-touch-secondary:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
        }

        /* Virtual Numpad */
        .numpad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            max-width: 360px;
            margin: 0 auto;
        }

        .numpad-key {
            height: 64px;
            font-size: 1.65rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .numpad-key:active {
            background: rgba(13, 148, 136, 0.35);
            border-color: #0d9488;
            transform: scale(0.94);
        }

        .numpad-key.key-action {
            background: rgba(255, 255, 255, 0.03);
            font-size: 1rem;
            font-weight: 600;
            color: #94a3b8;
        }

        .numpad-key.key-delete {
            color: #f87171;
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .numpad-key.key-delete:active {
            background: rgba(239, 68, 68, 0.3);
            border-color: #ef4444;
        }

        /* Input screen */
        .kiosk-display-input {
            background: rgba(0, 0, 0, 0.4);
            border: 2px solid rgba(13, 148, 136, 0.4);
            border-radius: 16px;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-align: center;
            color: #38bdf8;
            padding: 0.75rem 1rem;
            font-variant-numeric: tabular-nums;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.5);
            transition: border-color 0.2s;
        }

        .kiosk-display-input:focus {
            border-color: #38bdf8;
            outline: none;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.5), 0 0 15px rgba(56, 189, 248, 0.3);
        }

        /* Idle Warning Modal */
        #idleModal .modal-content {
            background: #141e33;
            border: 1px solid rgba(245, 158, 11, 0.4);
            color: #ffffff;
            border-radius: 20px;
        }

        /* Hidden Print Frame */
        #printFrame {
            display: none;
            width: 0;
            height: 0;
            border: 0;
        }

        /* Step Indicators */
        .step-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--kiosk-border);
            color: var(--kiosk-text-muted);
        }

        .step-pill.active {
            background: rgba(13, 148, 136, 0.2);
            border-color: #0d9488;
            color: #2dd4bf;
        }

        .step-pill.completed {
            background: rgba(16, 185, 129, 0.15);
            border-color: #10b981;
            color: #34d399;
        }

        /* Poliklinik & Doctor Cards */
        .poli-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--kiosk-border);
            border-radius: 16px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .poli-card:hover {
            background: rgba(13, 148, 136, 0.1);
            border-color: rgba(13, 148, 136, 0.4);
            transform: translateY(-2px);
        }

        .poli-card.selected {
            background: rgba(13, 148, 136, 0.2);
            border-color: #0d9488;
            box-shadow: 0 0 20px rgba(13, 148, 136, 0.25);
        }

        .doctor-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--kiosk-border);
            border-radius: 14px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .doctor-card:hover {
            background: rgba(37, 99, 235, 0.1);
            border-color: rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .doctor-card.selected {
            background: rgba(37, 99, 235, 0.2);
            border-color: #2563eb;
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.25);
        }

        /* Thermal Ticket Preview Modal */
        .ticket-receipt-card {
            background: #ffffff;
            color: #0f172a;
            border-radius: 12px;
            padding: 24px;
            font-family: 'Courier New', Courier, monospace;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            position: relative;
        }

        .ticket-receipt-card::before,
        .ticket-receipt-card::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 8px;
            background-size: 16px 8px;
            background-repeat: repeat-x;
        }

        .ticket-receipt-card::before {
            top: -4px;
            background-image: radial-gradient(circle at 8px 0, transparent 6px, #ffffff 6px);
        }

        .ticket-receipt-card::after {
            bottom: -4px;
            background-image: radial-gradient(circle at 8px 8px, transparent 6px, #ffffff 6px);
        }

        /* Footer */
        .kiosk-footer {
            margin-top: auto;
            border-top: 1px solid var(--kiosk-border);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            padding: 0.75rem 2rem;
            color: var(--kiosk-text-muted);
            font-size: 0.85rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <header class="kiosk-header">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                @if (!empty($setting->logo_base64))
                    <img src="{{ $setting->logo_base64 }}" alt="Logo" class="kiosk-brand-logo">
                @else
                    <div class="kiosk-brand-logo d-flex align-items-center justify-content-center text-teal">
                        <i class="ti ti-building-hospital fs-1"></i>
                    </div>
                @endif
                <div>
                    <h1 class="kiosk-title">{{ $setting->nama_instansi ?? 'KLINIK / FASILITAS KESEHATAN TINGKAT PERTAMA' }}</h1>
                    <p class="kiosk-subtitle">
                        {{ $setting->alamat_instansi ?? '' }} 
                        @if(!empty($setting->kabupaten)) · {{ $setting->kabupaten }} @endif
                        @if(!empty($setting->kontak)) · Telp: {{ $setting->kontak }} @endif
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="kiosk-clock-badge d-none d-md-inline-flex">
                    <span class="live-pulse"></span>
                    <span class="kiosk-clock-time" id="kioskClock">--:--:--</span>
                    <span class="text-secondary">|</span>
                    <span class="kiosk-clock-date" id="kioskDate">--------</span>
                </div>

                <button class="btn btn-touch-secondary px-3 py-2" id="btnFullscreen" title="Layar Penuh">
                    <i class="ti ti-maximize fs-2"></i>
                </button>

                <a href="{{ url('/') }}" class="btn btn-touch-secondary px-3 py-2 text-danger" title="Kembali ke Sistem SIMPUS" id="btnExitKiosk">
                    <i class="ti ti-power fs-2"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-fill py-4">
        @yield('content')
    </main>

    <!-- Kiosk Footer -->
    <footer class="kiosk-footer text-center">
        <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div>
                <span class="fw-semibold text-white">Anjungan Pendaftaran Mandiri (APM)</span> · Sentuh layar untuk berinteraksi
            </div>
            <div class="d-flex align-items-center gap-3 text-secondary">
                <span><i class="ti ti-printer me-1"></i> Printer Thermal Siap</span>
                <span>·</span>
                <span><i class="ti ti-shield-check me-1"></i> Terintegrasi BPJS & SIMPUS</span>
            </div>
        </div>
    </footer>

    <!-- Idle Warning Modal (Auto Reset) -->
    <div class="modal fade" id="idleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3 text-warning">
                    <i class="ti ti-clock-pause" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold mb-2">Apakah Anda Masih di Sini?</h3>
                <p class="text-secondary mb-3">
                    Layar akan kembali ke menu awal secara otomatis demi keamanan privasi data dalam:
                </p>
                <div class="display-3 fw-bolder text-warning mb-4" id="idleCountdown">10</div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-touch btn-touch-primary px-4 py-2" id="btnStayActive">
                        <i class="ti ti-hand-click me-1"></i> Ya, Lanjutkan
                    </button>
                    <button type="button" class="btn btn-touch btn-touch-secondary px-4 py-2" id="btnResetNow">
                        <i class="ti ti-x me-1"></i> Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Iframe for Thermal Printing -->
    <iframe id="printFrame"></iframe>

    <!-- Scripts -->
    <script src="{{ asset('js/jQuery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert/sweetalert2.all.min.js') }}"></script>

    <script>
        // Setup CSRF header
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Web Audio Synthesizer for pleasant tactile sound feedback
        const AudioFeedback = {
            ctx: null,
            init() {
                if (!this.ctx) {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (AudioCtx) this.ctx = new AudioCtx();
                }
            },
            tap() {
                try {
                    this.init();
                    if (!this.ctx) return;
                    if (this.ctx.state === 'suspended') this.ctx.resume();
                    const osc = this.ctx.createOscillator();
                    const gain = this.ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(520, this.ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(340, this.ctx.currentTime + 0.05);
                    gain.gain.setValueAtTime(0.12, this.ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.05);
                    osc.connect(gain);
                    gain.connect(this.ctx.destination);
                    osc.start();
                    osc.stop(this.ctx.currentTime + 0.05);
                } catch(e) {}
            },
            success() {
                try {
                    this.init();
                    if (!this.ctx) return;
                    if (this.ctx.state === 'suspended') this.ctx.resume();
                    const now = this.ctx.currentTime;
                    // Play a pleasant two-tone chord
                    [587.33, 880].forEach((freq, i) => {
                        const osc = this.ctx.createOscillator();
                        const gain = this.ctx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(freq, now + (i * 0.08));
                        gain.gain.setValueAtTime(0.18, now + (i * 0.08));
                        gain.gain.exponentialRampToValueAtTime(0.001, now + (i * 0.08) + 0.35);
                        osc.connect(gain);
                        gain.connect(this.ctx.destination);
                        osc.start(now + (i * 0.08));
                        osc.stop(now + (i * 0.08) + 0.35);
                    });
                } catch(e) {}
            }
        };

        // Tap sound on any touch button
        $(document).on('click', '.btn, .numpad-key, .touch-card, .poli-card, .doctor-card', function() {
            AudioFeedback.tap();
        });

        // Realtime Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
            $('#kioskClock').text(timeStr.replace(/\./g, ':'));
            $('#kioskDate').text(dateStr);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Fullscreen Toggle
        $('#btnFullscreen').on('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
                $(this).find('i').removeClass('ti-maximize').addClass('ti-minimize');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().catch(() => {});
                    $(this).find('i').removeClass('ti-minimize').addClass('ti-maximize');
                }
            }
        });

        // Idle Detector & Auto Reset (60s inactivity on sub-screens)
        let idleTime = 0;
        let idleInterval = null;
        let countdownInterval = null;
        let isSubScreenActive = false; // Set to true when user enters registration wizard

        function resetIdleTimer() {
            idleTime = 0;
        }

        $(document).on('mousemove mousedown touchstart keydown', resetIdleTimer);

        function startIdleMonitor() {
            if (idleInterval) clearInterval(idleInterval);
            idleInterval = setInterval(() => {
                if (!isSubScreenActive) {
                    idleTime = 0;
                    return;
                }
                idleTime += 1;
                if (idleTime >= 45) { // 45 seconds of inactivity
                    showIdleWarning();
                }
            }, 1000);
        }

        function showIdleWarning() {
            clearInterval(idleInterval);
            let timeLeft = 10;
            $('#idleCountdown').text(timeLeft);
            $('#idleModal').modal('show');

            countdownInterval = setInterval(() => {
                timeLeft--;
                $('#idleCountdown').text(timeLeft);
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    $('#idleModal').modal('hide');
                    if (typeof resetToKioskHome === 'function') {
                        resetToKioskHome();
                    } else {
                        window.location.reload();
                    }
                }
            }, 1000);
        }

        $('#btnStayActive').on('click', function() {
            clearInterval(countdownInterval);
            $('#idleModal').modal('hide');
            resetIdleTimer();
            startIdleMonitor();
        });

        $('#btnResetNow').on('click', function() {
            clearInterval(countdownInterval);
            $('#idleModal').modal('hide');
            if (typeof resetToKioskHome === 'function') {
                resetToKioskHome();
            } else {
                window.location.reload();
            }
        });

        startIdleMonitor();

        // Thermal Print via Hidden Iframe
        function triggerThermalPrint(url) {
            const frame = document.getElementById('printFrame');
            frame.src = url;
            frame.onload = function() {
                try {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                } catch(e) {
                    console.error('Print error:', e);
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>

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

    <style>
        :root {
            --kiosk-bg: #f8fafc;
            --kiosk-surface: #ffffff;
            --kiosk-surface-subtle: #f1f5f9;
            --kiosk-border: #e2e8f0;
            --kiosk-border-focus: #0d9488;
            --kiosk-primary: #0d9488;
            --kiosk-primary-hover: #0f766e;
            --kiosk-accent: #2563eb;
            --kiosk-accent-hover: #1d4ed8;
            --kiosk-emerald: #059669;
            --kiosk-amber: #d97706;
            --kiosk-text-main: #0f172a;
            --kiosk-text-muted: #64748b;
            --kiosk-text-secondary: #475569;
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
                radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 50%, #ffffff 0px, transparent 100%);
            color: var(--kiosk-text-main);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Kiosk Header (Light & Crisp) */
        .kiosk-header {
            background: #ffffff;
            border-bottom: 1px solid var(--kiosk-border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .kiosk-brand-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
            border-radius: 12px;
            background: #ffffff;
            padding: 4px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .kiosk-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .kiosk-subtitle {
            font-size: 0.88rem;
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }

        .kiosk-clock-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .kiosk-clock-time {
            font-size: 1.35rem;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            color: #0284c7;
            letter-spacing: 0.05em;
        }

        .kiosk-clock-date {
            font-size: 0.88rem;
            color: #475569;
            font-weight: 600;
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

        /* Tactile Touch Cards (Pristine White Surface) */
        .touch-card {
            background: #ffffff;
            border: 1px solid var(--kiosk-border);
            border-radius: 20px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .touch-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 16px 30px -10px rgba(13, 148, 136, 0.12), 0 4px 12px -2px rgba(0, 0, 0, 0.04);
            transform: translateY(-2px);
        }

        .touch-card:active {
            transform: scale(0.985);
        }

        /* Touch Interactive Guide Badge (Elevated & Tactile) */
        .touch-guide-badge {
            background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);
            border: 1.5px solid #0d9488;
            border-radius: 9999px;
            padding: 0.5rem 1.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: 
                0 10px 25px -4px rgba(13, 148, 136, 0.22),
                0 4px 6px -2px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
            position: relative;
            transition: all 0.25s ease;
        }

        .touch-icon-pulse {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.4);
            position: relative;
            flex-shrink: 0;
            animation: touch-bounce 1.8s infinite ease-in-out;
        }

        .touch-icon-pulse::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(13, 148, 136, 0.65);
            animation: touch-ripple 1.8s infinite cubic-bezier(0, 0.2, 0.8, 1);
        }

        @keyframes touch-ripple {
            0% {
                transform: scale(0.9);
                opacity: 1;
            }
            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

        @keyframes touch-bounce {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-3px) scale(1.06);
            }
        }

        .touch-guide-content {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.25;
        }

        .touch-guide-label {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #0d9488;
        }

        .touch-guide-action {
            font-size: 0.98rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .touch-guide-action .highlight {
            color: #0d9488;
            font-weight: 800;
        }

        .touch-guide-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 0.95rem;
            animation: arrow-bounce 1.5s infinite ease-in-out;
        }

        @keyframes arrow-bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(3px);
            }
        }

        /* High-Clarity Typography for Kiosk Screen (No Flatness) */
        .text-secondary {
            color: #475569 !important;
        }

        .touch-card p.text-secondary {
            color: #334155 !important;
            font-size: 1.15rem;
            line-height: 1.6;
        }

        .touch-card p.text-secondary strong {
            color: #0f172a !important;
            font-weight: 700;
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
            cursor: pointer;
            border: none;
        }

        .btn-touch:active {
            transform: scale(0.97);
        }

        .btn-touch-primary {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.28);
        }

        .btn-touch-primary:hover {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.38);
        }

        .btn-touch-blue {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.28);
        }

        .btn-touch-blue:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.38);
        }

        .btn-touch-emerald {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.28);
        }

        .btn-touch-emerald:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.38);
        }

        .btn-touch-secondary {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .btn-touch-secondary:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* Virtual Numpad (Clean Light Styling) */
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
            font-weight: 800;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #0f172a;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        }

        .numpad-key:active {
            background: #e2e8f0;
            border-color: #cbd5e1;
            transform: scale(0.94);
        }

        .numpad-key.key-action {
            background: #f8fafc;
            font-size: 0.95rem;
            font-weight: 700;
            color: #64748b;
        }

        .numpad-key.key-delete {
            color: #dc2626;
            background: #fef2f2;
            border-color: #fecaca;
        }

        .numpad-key.key-delete:active {
            background: #fee2e2;
            border-color: #ef4444;
        }

        /* Input Screen (Clean & Legible) */
        .kiosk-display-input {
            background: #ffffff;
            border: 2.5px solid #0d9488;
            border-radius: 16px;
            font-size: 2.1rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-align: center;
            color: #0f172a;
            padding: 0.85rem 1rem;
            font-variant-numeric: tabular-nums;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.1);
            transition: all 0.2s;
        }

        .kiosk-display-input:focus {
            border-color: #0f766e;
            outline: none;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.18);
        }

        /* Idle Warning Modal */
        #idleModal .modal-content {
            background: #ffffff;
            border: 1px solid #f59e0b;
            color: #0f172a;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Modal Z-Index & Interactive Backdrop Stacking */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            z-index: 1065 !important;
            position: relative;
        }
        .modal-content {
            pointer-events: auto !important;
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
            padding: 7px 16px;
            border-radius: 9999px;
            font-size: 0.88rem;
            font-weight: 700;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #64748b;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .step-pill.active {
            background: #ccfbf1;
            border-color: #0d9488;
            color: #0f766e;
        }

        .step-pill.completed {
            background: #dcfce7;
            border-color: #10b981;
            color: #15803d;
        }

        /* Poliklinik & Doctor Cards */
        .poli-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.35rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .poli-card:hover {
            background: #f0fdfa;
            border-color: #0d9488;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.12);
        }

        .poli-card.selected {
            background: #f0fdfa;
            border-color: #0d9488;
            border-width: 2.5px;
            box-shadow: 0 0 20px rgba(13, 148, 136, 0.18);
        }

        .doctor-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.15rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .doctor-card:hover {
            background: #eff6ff;
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
        }

        .doctor-card.selected {
            background: #eff6ff;
            border-color: #2563eb;
            border-width: 2.5px;
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.18);
        }

        /* Thermal Ticket Preview Modal */
        .ticket-receipt-card {
            background: #ffffff;
            color: #0f172a;
            border-radius: 12px;
            padding: 24px;
            font-family: 'Courier New', Courier, monospace;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid #e2e8f0;
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
            background: #ffffff;
            padding: 0.85rem 2rem;
            color: #64748b;
            font-size: 0.88rem;
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
                    <span class="text-muted">|</span>
                    <span class="kiosk-clock-date" id="kioskDate">--------</span>
                </div>

                <button class="btn btn-touch-secondary px-3 py-2" id="btnFullscreen" title="Layar Penuh">
                    <i class="ti ti-maximize fs-2 text-secondary"></i>
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
                <span class="fw-bold text-dark">Anjungan Pendaftaran Mandiri (APM)</span> · Sentuh layar untuk berinteraksi
            </div>
            <div class="text-secondary fs-5">
                <span>{{ $setting->nama_instansi ?? '' }}</span>
            </div>
        </div>
    </footer>

    <!-- Modals -->
    <!-- Idle Warning Modal (Auto Reset) -->
    <div class="modal fade" id="idleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <div class="mb-3 text-warning">
                    <i class="ti ti-clock-pause" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold mb-2 text-dark">Apakah Anda Masih di Sini?</h3>
                <p class="text-secondary mb-3">
                    Layar akan kembali ke menu awal secara otomatis demi keamanan privasi data dalam:
                </p>
                <div class="display-3 fw-bolder text-warning mb-4" id="idleCountdown">10</div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-touch btn-touch-primary px-4 py-2" id="btnStayActive" data-bs-dismiss="modal">
                        <i class="ti ti-hand-click me-1"></i> Ya, Lanjutkan
                    </button>
                    <button type="button" class="btn btn-touch btn-touch-secondary px-4 py-2" id="btnResetNow" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    @stack('modals')

    <!-- Hidden Iframe for Thermal Printing -->
    <iframe id="printFrame" style="position: absolute; width: 0; height: 0; border: none; visibility: hidden;"></iframe>

    <!-- Scripts -->
    <script src="{{ asset('js/jQuery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert/sweetalert2@11.js') }}"></script>

    <script>
        // Setup CSRF header
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global Modal Backdrop & Focus Guard Cleanup
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').removeAttr('style');
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

        // Tap sound on touch interactions
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

        // Idle Detector & Auto Reset (45s inactivity on sub-screens)
        let idleTime = 0;
        let idleInterval = null;
        let countdownInterval = null;
        let isSubScreenActive = false;

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
                if (idleTime >= 45) {
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

        // Thermal Print via Dynamic Temporary Iframe with Instant Teardown
        function triggerThermalPrint(url) {
            try {
                // Remove any previous print frame to prevent stale focus locks
                $('#printFrame').remove();

                const iframe = document.createElement('iframe');
                iframe.id = 'printFrame';
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = 'none';
                iframe.style.opacity = '0';
                iframe.style.pointerEvents = 'none';
                document.body.appendChild(iframe);

                iframe.onload = function() {
                    setTimeout(() => {
                        try {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                        } catch(e) {
                            console.warn('Print notification:', e);
                        } finally {
                            // Immediately restore focus to main window and ticket action button
                            window.focus();
                            document.body.focus();
                            const btn = document.getElementById('btnSelesaiLoket');
                            if (btn) btn.focus();
                            // Teardown the iframe so Chrome completely releases focus lock
                            setTimeout(() => {
                                $(iframe).remove();
                            }, 400);
                        }
                    }, 50);
                };

                iframe.src = url;
            } catch(e) {
                console.error('Trigger print error:', e);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>

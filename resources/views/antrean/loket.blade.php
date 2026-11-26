@extends('main')

@section('contents')
<div class="container-fluid p-4 bg-dark min-vh-100 d-flex flex-column justify-content-between">
    <!-- Top Header -->
    <div class="row g-3 mb-3">
        <div class="col-lg-8 col-md-12">
            <div class="card border-0 rounded-4 shadow" style="min-height: 100px;">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        @if (!empty($data->logo))
                            <img src="{{ $data->logo }}" alt="logo" width="68" class="me-3 rounded">
                        @endif
                        <div>
                            <h1 class="m-0 fw-bold" style="font-size: 2rem;">{{ $data->nama_instansi ?? config('app.name') }}</h1>
                            <p class="m-0 text-muted" style="font-size: 1rem;">{{ $data->alamat_instansi ?? '' }} {{ $data->kabupaten ?? '' }} {{ $data->propinsi ?? '' }}</p>
                            <p class="m-0 text-muted small">{{ $data->kontak ?? '' }} {{ !empty($data->email) ? '• ' . $data->email : '' }}</p>
                        </div>
                    </div>
                    <button type="button" id="btnEnableSound" class="btn btn-teal text-white btn-sm rounded-pill px-3" onclick="toggleAudio()" title="Klik untuk mematikan / menyalakan suara panggilan">
                        <i class="ti ti-volume me-1"></i> Suara Aktif
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 rounded-4 shadow text-center d-flex justify-content-center" style="min-height: 100px;">
                <div class="card-body py-2">
                    <div class="fw-bold text-teal" style="font-size: 2.75rem; line-height: 1.1;" id="jam">00:00:00</div>
                    <div class="fw-semibold text-muted mt-1" style="font-size: 1.1rem;" id="tanggal">{{ date('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-3 flex-grow-1 mb-3">
        <!-- Active Call Card -->
        <div class="col-lg-6 col-md-12 d-flex flex-column gap-3">
            <div class="card border-0 rounded-4 shadow flex-grow-1 text-center d-flex justify-content-center" id="activeCallCard" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);">
                <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                    <span class="badge bg-teal text-white rounded-pill px-4 py-2 mb-3 fs-4 fw-bold">
                        NOMOR ANTREAN LOKET
                    </span>
                    <div id="displayNomor" class="fw-bold text-dark my-2" style="font-size: 7.5rem; line-height: 1; letter-spacing: 2px;">-</div>
                    <div id="displayLayanan" class="fs-2 fw-semibold text-muted mb-3">Menunggu Panggilan...</div>
                    <div class="px-5 py-3 rounded-4 bg-teal text-white shadow-sm">
                        <span class="fs-1 fw-bold" id="displayLoket">SILAKAN MENUJU LOKET -</span>
                    </div>
                </div>
            </div>

            <!-- Counters Row (Loket 1 - 4 & Waiting Counts) -->
            <div class="row g-2">
                @for ($i = 1; $i <= 4; $i++)
                <div class="col-3">
                    <div class="card border-0 rounded-4 shadow-sm text-center py-2" id="tvLoketCard{{ $i }}">
                        <div class="text-muted small fw-bold">LOKET {{ $i }}</div>
                        <div class="fs-1 fw-bold text-teal" id="tvLoketVal{{ $i }}">-</div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Video & Queue Summary -->
        <div class="col-lg-6 col-md-12 d-flex flex-column gap-3">
            <div class="card border-0 rounded-4 shadow overflow-hidden flex-grow-1" style="min-height: 360px;">
                <div class="card-body p-0 h-100" id="videoAntrean"></div>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-green-lt text-center py-3">
                        <div class="fw-bold text-green small">SISA ANTREAN BPJS (A)</div>
                        <div class="fs-1 fw-bold text-dark" id="tvWaitingA">0</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 rounded-4 shadow-sm bg-blue-lt text-center py-3">
                        <div class="fw-bold text-blue small">SISA ANTREAN UMUM (B)</div>
                        <div class="fs-1 fw-bold text-dark" id="tvWaitingB">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Marquee -->
    <div class="card border-0 rounded-4 shadow">
        <div class="card-body py-2">
            <marquee behavior="scroll" direction="left" class="fs-2 fw-semibold text-dark">
                Selamat datang di {{ $data->nama_instansi ?? config('app.name') }} &bull;
                Silakan mengambil nomor antrean pendaftaran di mesin Anjungan Mandiri dan menunggu panggilan ke loket &bull;
                {{ $data->alamat_instansi ?? '' }} {{ $data->kontak ? '• Info: ' . $data->kontak : '' }}
            </marquee>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let lastCallSignature = '';
    let audioEnabled = true;
    let speechQueue = [];
    let isSpeaking = false;
    let sharedAudioCtx = null;

    $(document).ready(function () {
        // Buka kunci AudioContext otomatis jika ada klik/sentuhan di mana saja pada halaman
        $(document).one('click touchstart keydown', function () {
            unlockAudioContext();
        });

        // Load video antrean
        $.get("{{ url('/setting/antrean/video') }}").done(function (response) {
            if (response && response.content) {
                $('#videoAntrean').html(response.content);
            } else {
                $('#videoAntrean').html('<div class="d-flex align-items-center justify-content-center h-100 bg-dark text-white-50 p-4 text-center"><div><i class="ti ti-device-tv fs-1 d-block mb-2"></i>Layar Informasi Pelayanan</div></div>');
            }
            $('#videoAntrean iframe').attr('width', '100%').attr('height', '100%');
        });

        if (typeof jam === 'function') {
            jam();
        }
        const date = new Date();
        if (typeof setHari === 'function' && typeof formatTanggal === 'function') {
            const hari = setHari(date.getDay());
            $('#tanggal').html(`${hari}, ${formatTanggal(date.toISOString().split('T')[0])}`);
        }

        pollDisplayData();
        setInterval(pollDisplayData, 2500);
    });

    function unlockAudioContext() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            if (!sharedAudioCtx) {
                sharedAudioCtx = new AudioCtx();
            }
            if (sharedAudioCtx.state === 'suspended') {
                sharedAudioCtx.resume();
            }
        } catch (e) {}
    }

    function toggleAudio() {
        audioEnabled = !audioEnabled;
        if (audioEnabled) {
            unlockAudioContext();
            $('#btnEnableSound')
                .removeClass('btn-outline-secondary')
                .addClass('btn-teal text-white')
                .html('<i class="ti ti-volume me-1"></i> Suara Aktif');
            playChime();
        } else {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
            speechQueue = [];
            isSpeaking = false;
            $('#btnEnableSound')
                .removeClass('btn-teal text-white')
                .addClass('btn-outline-secondary')
                .html('<i class="ti ti-volume-off me-1"></i> Suara Mati');
        }
    }

    function playChime() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            if (!sharedAudioCtx) {
                sharedAudioCtx = new AudioCtx();
            }
            if (sharedAudioCtx.state === 'suspended') {
                sharedAudioCtx.resume();
            }
            const ctx = sharedAudioCtx;
            const now = ctx.currentTime;

            [523.25, 659.25, 783.99].forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, now + idx * 0.14);
                gain.gain.setValueAtTime(0.12, now + idx * 0.14);
                gain.gain.exponentialRampToValueAtTime(0.001, now + idx * 0.14 + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now + idx * 0.14);
                osc.stop(now + idx * 0.14 + 0.36);
            });
        } catch (e) {}
    }

    function enqueueAnnouncement(antrian, loket) {
        if (!audioEnabled || !('speechSynthesis' in window)) return;
        speechQueue.push({ antrian, loket });
        processSpeechQueue();
    }

    function processSpeechQueue() {
        if (isSpeaking || speechQueue.length === 0) return;
        isSpeaking = true;

        const item = speechQueue.shift();
        playChime();

        setTimeout(function () {
            try {
                const spokenNumber = item.antrian.split('').join(' ');
                const text = `Nomor antrean, ${spokenNumber}, silakan menuju ke loket ${item.loket}`;
                const utter = new SpeechSynthesisUtterance(text);
                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang && v.lang.toLowerCase().includes('id'));
                if (idVoice) utter.voice = idVoice;
                utter.lang = 'id-ID';
                utter.rate = 0.88;
                utter.onend = function () {
                    isSpeaking = false;
                    processSpeechQueue();
                };
                utter.onerror = function () {
                    isSpeaking = false;
                    processSpeechQueue();
                };
                window.speechSynthesis.speak(utter);
            } catch (e) {
                isSpeaking = false;
                processSpeechQueue();
            }
        }, 550);
    }

    function pollDisplayData() {
        $.get("{{ route('antrean.loket.data') }}").done(function (res) {
            if (!res || !res.success) return;

            $('#tvWaitingA').text(res.summary.waiting_a);
            $('#tvWaitingB').text(res.summary.waiting_b);

            for (let i = 1; i <= 4; i++) {
                const t = res.loket_active ? res.loket_active[i] : null;
                $('#tvLoketVal' + i).text(t ? t.antrian : '-');
                $('#tvLoketCard' + i).toggleClass('border border-2 border-teal bg-teal-lt', !!t);
            }

            if (res.latest_call) {
                const call = res.latest_call;
                const layananText = call.kd_layanan === 'A' ? 'LOKET BPJS KESEHATAN' : 'LOKET UMUM / ASURANSI';
                $('#displayNomor').text(call.antrian);
                $('#displayLayanan').text(layananText);
                $('#displayLoket').text('SILAKAN MENUJU LOKET ' + (call.kd_loket ?? '-'));

                const sig = `${call.kd_list}_${call.call_count || 0}_${call.called_at || ''}`;
                if (lastCallSignature !== '' && lastCallSignature !== sig) {
                    // Highlight visual & umumkan suara
                    $('#activeCallCard').addClass('border border-3 border-teal');
                    setTimeout(() => $('#activeCallCard').removeClass('border border-3 border-teal'), 2500);
                    enqueueAnnouncement(call.antrian, call.kd_loket || 1);
                }
                lastCallSignature = sig;
            } else {
                $('#displayNomor').text('-');
                $('#displayLayanan').text('Menunggu Panggilan...');
                $('#displayLoket').text('SILAKAN MENUJU LOKET -');
                lastCallSignature = 'EMPTY';
            }
        });
    }
</script>
@endpush


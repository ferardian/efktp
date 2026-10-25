@extends('layout')

@section('body')
    <div class="container-xl">
        <!-- Page Header -->
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title text-primary">
                        <i class="ti ti-file-text-ai me-2"></i> Pengaturan Format Penomoran Surat
                    </h2>
                    <div class="text-muted mt-1 small">
                        Konfigurasi template format nomor surat sakit dan surat sehat, nomor awal / counter terakhir, serta aturan reset penomoran secara dinamis.
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="btnSimpanSemua">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>

        <!-- Alert Contoh Klien -->
        <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class="ti ti-info-circle fs-1"></i>
                </div>
                <div>
                    <h4 class="alert-title mb-1">Contoh Permintaan Klien: <code>KMC/1090/A1/X/26</code></h4>
                    <div class="text-secondary small">
                        Untuk format di atas: <br>
                        • Template Format: <span class="badge bg-blue-lt fw-bold">KMC/{NUM}/A1/{ROMAN_MONTH}/{YEAR2}</span> <br>
                        • Nomor Terakhir Berjalan: Masukkan <span class="badge bg-green-lt fw-bold">1090</span> pada <b>Nomor Terakhir / Mulai Dari</b> agar surat berikutnya otomatis dimulai dari <b>1091</b>. <br>
                        • Periode Reset: Pilih <span class="badge bg-purple-lt fw-bold">Tidak Pernah (Never)</span> jika nomor urut ingin terus berjalan tanpa reset per bulan/tahun.
                    </div>
                </div>
            </div>
        </div>

        <form id="formSettingNomorSurat">
            @csrf

            <div class="row row-cards mb-4">
                <!-- Card 1: Surat Keterangan Sakit -->
                <div class="col-lg-6 col-md-12">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0 text-dark">
                                <i class="ti ti-file-invoice text-danger me-2"></i> Surat Keterangan Sakit (SKS)
                            </h4>
                            <span class="badge bg-danger-lt">Tabel: suratsakit</span>
                        </div>
                        <div class="card-body">
                            <!-- Template Format Input -->
                            <div class="mb-3">
                                <label class="form-label required fw-bold" for="format_sakit">Pola Template Format</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-template"></i></span>
                                    <input type="text" class="form-control font-monospace" id="format_sakit" name="format_sakit" 
                                           value="{{ $setting->format_sakit ?? '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}' }}" required>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Klik token untuk menambahkan:</small>
                                    <div class="d-flex flex-wrap gap-1 token-badges" data-target="#format_sakit">
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{NUM}">{NUM}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{NUM3}">{NUM3}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{NUM4}">{NUM4}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{ROMAN_MONTH}">{ROMAN_MONTH}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{MONTH}">{MONTH}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{YEAR}">{YEAR}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{YEAR2}">{YEAR2}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{DAY}">{DAY}</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Starting Number Input -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="start_num_sakit">
                                    Nomor Terakhir / Mulai Dari
                                    <span class="form-help" data-bs-toggle="popover" data-bs-placement="top" data-bs-html="true"
                                          data-bs-content="Jika diisi <b>1090</b>, maka nomor urut surat berikutnya yang digenerate adalah <b>1091</b>. Isi 0 jika ingin menghitung murni dari data database.">?</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-hash"></i></span>
                                    <input type="number" class="form-control" id="start_num_sakit" name="start_num_sakit" 
                                           value="{{ $setting->start_num_sakit ?? 0 }}" min="0" placeholder="0">
                                </div>
                                <small class="form-hint text-muted">
                                    Nomor surat berikutnya: <span class="fw-bold text-primary" id="lblNextSeqSakit">{{ ($setting->start_num_sakit ?? 0) + 1 }}</span>
                                </small>
                            </div>

                            <!-- Live Simulation Preview Box -->
                            <div class="p-3 bg-light rounded-3 border mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-uppercase fs-6 text-muted">
                                        <i class="ti ti-eye me-1"></i> Live Preview Surat Sakit
                                    </span>
                                    <span class="badge bg-success" id="badgeStatusSakit">Realtime</span>
                                </div>
                                <div class="p-3 bg-white rounded border border-2 border-primary text-center">
                                    <span class="fs-2 font-monospace fw-bold text-primary text-break" id="previewNomorSakit">
                                        {{ $previewSakit['preview'] ?? '...' }}
                                    </span>
                                </div>
                                <div class="row mt-2 g-2 small text-muted">
                                    <div class="col-sm-6">
                                        Tanggal Acuan: <b id="lblTanggalSakit">{{ date('d-m-Y') }}</b>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        Total Data DB Periode Ini: <b id="lblDbCountSakit">{{ $previewSakit['count_in_db'] ?? 0 }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Surat Keterangan Sehat -->
                <div class="col-lg-6 col-md-12">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0 text-dark">
                                <i class="ti ti-heart-handshake text-success me-2"></i> Surat Keterangan Sehat (SK.Sehat)
                            </h4>
                            <span class="badge bg-success-lt">Tabel: surat_keterangan_sehat</span>
                        </div>
                        <div class="card-body">
                            <!-- Template Format Input -->
                            <div class="mb-3">
                                <label class="form-label required fw-bold" for="format_sehat">Pola Template Format</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-template"></i></span>
                                    <input type="text" class="form-control font-monospace" id="format_sehat" name="format_sehat" 
                                           value="{{ $setting->format_sehat ?? '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}' }}" required>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Klik token untuk menambahkan:</small>
                                    <div class="d-flex flex-wrap gap-1 token-badges" data-target="#format_sehat">
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{NUM}">{NUM}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{NUM3}">{NUM3}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{NUM4}">{NUM4}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{ROMAN_MONTH}">{ROMAN_MONTH}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{MONTH}">{MONTH}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{YEAR}">{YEAR}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{YEAR2}">{YEAR2}</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 btn-token" data-token="{DAY}">{DAY}</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Starting Number Input -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="start_num_sehat">
                                    Nomor Terakhir / Mulai Dari
                                    <span class="form-help" data-bs-toggle="popover" data-bs-placement="top" data-bs-html="true"
                                          data-bs-content="Jika diisi <b>0</b>, maka nomor urut dihitung dari data yang tersimpan di database. Jika diisi angka tertentu, surat berikutnya dimulai dari angka tersebut + 1.">?</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-hash"></i></span>
                                    <input type="number" class="form-control" id="start_num_sehat" name="start_num_sehat" 
                                           value="{{ $setting->start_num_sehat ?? 0 }}" min="0" placeholder="0">
                                </div>
                                <small class="form-hint text-muted">
                                    Nomor surat berikutnya: <span class="fw-bold text-success" id="lblNextSeqSehat">{{ ($setting->start_num_sehat ?? 0) + 1 }}</span>
                                </small>
                            </div>

                            <!-- Live Simulation Preview Box -->
                            <div class="p-3 bg-light rounded-3 border mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-uppercase fs-6 text-muted">
                                        <i class="ti ti-eye me-1"></i> Live Preview Surat Sehat
                                    </span>
                                    <span class="badge bg-success" id="badgeStatusSehat">Realtime</span>
                                </div>
                                <div class="p-3 bg-white rounded border border-2 border-success text-center">
                                    <span class="fs-2 font-monospace fw-bold text-success text-break" id="previewNomorSehat">
                                        {{ $previewSehat['preview'] ?? '...' }}
                                    </span>
                                </div>
                                <div class="row mt-2 g-2 small text-muted">
                                    <div class="col-sm-6">
                                        Tanggal Acuan: <b id="lblTanggalSehat">{{ date('d-m-Y') }}</b>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        Total Data DB Periode Ini: <b id="lblDbCountSehat">{{ $previewSehat['count_in_db'] ?? 0 }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Periode Reset Penomoran -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-calendar-repeat text-warning me-2"></i> Periode Reset Nomor Urut
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Pilih kapan nomor urut surat direset kembali ke nomor awal (atau nomor 1):
                    </p>
                    <div class="row g-3 align-items-stretch">
                        <!-- Option 1: Monthly -->
                        <div class="col-md-4 d-flex">
                            <label class="form-selectgroup-item w-100 h-100">
                                <input type="radio" name="reset_periode" value="monthly" class="form-selectgroup-input"
                                       {{ ($setting->reset_periode ?? 'monthly') == 'monthly' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label d-flex align-items-center p-3 h-100 w-100">
                                    <i class="ti ti-calendar-month fs-2 text-primary me-2 flex-shrink-0"></i>
                                    <span class="text-start">
                                        <span class="font-weight-bold d-block text-dark">Reset Setiap Bulan</span>
                                        <span class="text-muted small">Nomor urut kembali ke nomor awal pada setiap pergantian bulan baru (default).</span>
                                    </span>
                                </span>
                            </label>
                        </div>

                        <!-- Option 2: Yearly -->
                        <div class="col-md-4 d-flex">
                            <label class="form-selectgroup-item w-100 h-100">
                                <input type="radio" name="reset_periode" value="yearly" class="form-selectgroup-input"
                                       {{ ($setting->reset_periode ?? 'monthly') == 'yearly' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label d-flex align-items-center p-3 h-100 w-100">
                                    <i class="ti ti-calendar-event fs-2 text-info me-2 flex-shrink-0"></i>
                                    <span class="text-start">
                                        <span class="font-weight-bold d-block text-dark">Reset Setiap Tahun</span>
                                        <span class="text-muted small">Nomor urut terus berlanjut selama 1 tahun, dan kembali ke awal pada awal tahun baru.</span>
                                    </span>
                                </span>
                            </label>
                        </div>

                        <!-- Option 3: Never -->
                        <div class="col-md-4 d-flex">
                            <label class="form-selectgroup-item w-100 h-100">
                                <input type="radio" name="reset_periode" value="never" class="form-selectgroup-input"
                                       {{ ($setting->reset_periode ?? 'monthly') == 'never' ? 'checked' : '' }}>
                                <span class="form-selectgroup-label d-flex align-items-center p-3 h-100 w-100">
                                    <i class="ti ti-infinity fs-2 text-success me-2 flex-shrink-0"></i>
                                    <span class="text-start">
                                        <span class="font-weight-bold d-block text-dark">Tidak Pernah Reset (Never)</span>
                                        <span class="text-muted small">Nomor urut terus bertambah tanpa batas waktu. Cocok untuk counter kontinu seperti <code>KMC/1090/...</code>.</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Tabel Panduan Token -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-help-circle text-primary me-2"></i> Panduan Variabel / Token Format Penomoran
                    </h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped table-hover mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th style="width: 180px;">Token</th>
                                <th>Keterangan</th>
                                <th style="width: 240px;">Contoh Output (Saat Ini)</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="fw-bold fs-6 text-primary">{NUM}</code></td>
                                <td>Nomor urut tanpa padding nol (1, 2, 45, 1091)</td>
                                <td><span class="badge bg-blue-lt font-monospace fs-6">1091</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{NUM}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-primary">{NUM3}</code></td>
                                <td>Nomor urut minimal 3 digit dengan padding nol di depan</td>
                                <td><span class="badge bg-blue-lt font-monospace fs-6">001</span> atau <span class="badge bg-blue-lt font-monospace fs-6">1091</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{NUM3}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-primary">{NUM4}</code></td>
                                <td>Nomor urut minimal 4 digit dengan padding nol di depan</td>
                                <td><span class="badge bg-blue-lt font-monospace fs-6">0001</span> atau <span class="badge bg-blue-lt font-monospace fs-6">1091</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{NUM4}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-primary">{NUM5}</code></td>
                                <td>Nomor urut minimal 5 digit dengan padding nol di depan</td>
                                <td><span class="badge bg-blue-lt font-monospace fs-6">00001</span> atau <span class="badge bg-blue-lt font-monospace fs-6">01091</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{NUM5}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-danger">{ROMAN_MONTH}</code></td>
                                <td>Bulan dalam angka Romawi (I, II, III, ..., X, XI, XII)</td>
                                <td><span class="badge bg-danger-lt font-monospace fs-6">{{ \App\Services\NomorSuratService::getRomanMonth(date('n')) }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{ROMAN_MONTH}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-success">{MONTH}</code></td>
                                <td>Bulan dalam angka 2 digit (01 sampai 12)</td>
                                <td><span class="badge bg-success-lt font-monospace fs-6">{{ date('m') }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{MONTH}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-success">{MONTH_NAME}</code></td>
                                <td>Nama bulan dalam Bahasa Indonesia (Januari, Februari, ...)</td>
                                <td><span class="badge bg-success-lt font-monospace fs-6">{{ \App\Services\NomorSuratService::getIndonesianMonth(date('n')) }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{MONTH_NAME}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-purple">{YEAR}</code></td>
                                <td>Tahun 4 digit</td>
                                <td><span class="badge bg-purple-lt font-monospace fs-6">{{ date('Y') }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{YEAR}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-purple">{YEAR2}</code></td>
                                <td>Tahun 2 digit terakhir</td>
                                <td><span class="badge bg-purple-lt font-monospace fs-6">{{ date('y') }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{YEAR2}">Salin</button>
                                </td>
                            </tr>
                            <tr>
                                <td><code class="fw-bold fs-6 text-secondary">{DAY}</code></td>
                                <td>Tanggal 2 digit (01 sampai 31)</td>
                                <td><span class="badge bg-secondary-lt font-monospace fs-6">{{ date('d') }}</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-copy-token" data-token="{DAY}">Salin</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="button" class="btn btn-primary btn-lg px-4" id="btnSimpanBawah">
                    <i class="ti ti-device-floppy me-2"></i> Simpan Seluruh Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            // Inisialisasi popover Tabler
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Klik token badge untuk menyisipkan ke input
            $('.btn-token').on('click', function () {
                const targetSelector = $(this).closest('.token-badges').data('target');
                const token = $(this).data('token');
                const $input = $(targetSelector);

                const inputEl = $input[0];
                const startPos = inputEl.selectionStart;
                const endPos = inputEl.selectionEnd;
                const currentVal = $input.val();

                const newVal = currentVal.substring(0, startPos) + token + currentVal.substring(endPos, currentVal.length);
                $input.val(newVal).focus();

                inputEl.selectionStart = startPos + token.length;
                inputEl.selectionEnd = startPos + token.length;

                triggerPreview();
            });

            // Tombol salin token di tabel panduan
            $('.btn-copy-token').on('click', function () {
                const token = $(this).data('token');
                navigator.clipboard.writeText(token).then(() => {
                    const $btn = $(this);
                    const originalText = $btn.text();
                    $btn.text('Tersalin!').removeClass('btn-outline-primary').addClass('btn-success');
                    setTimeout(() => {
                        $btn.text(originalText).removeClass('btn-success').addClass('btn-outline-primary');
                    }, 1500);
                });
            });

            let previewTimeout;
            function triggerPreview() {
                clearTimeout(previewTimeout);
                previewTimeout = setTimeout(() => {
                    updatePreview('sakit');
                    updatePreview('sehat');
                }, 300);
            }

            function updatePreview(jenis) {
                const format = (jenis === 'sakit') ? $('#format_sakit').val() : $('#format_sehat').val();
                const startNum = (jenis === 'sakit') ? $('#start_num_sakit').val() : $('#start_num_sehat').val();
                const resetPeriode = $('input[name="reset_periode"]:checked').val() || 'monthly';

                // Update label preview next sequence
                const parsedStart = parseInt(startNum) || 0;
                if (jenis === 'sakit') {
                    $('#lblNextSeqSakit').text(parsedStart + 1);
                } else {
                    $('#lblNextSeqSehat').text(parsedStart + 1);
                }

                $.ajax({
                    url: "{{ url('/setting/nomor-surat/preview') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        jenis: jenis,
                        format: format,
                        start_num: startNum,
                        reset_periode: resetPeriode
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            if (jenis === 'sakit') {
                                $('#previewNomorSakit').text(res.preview);
                                $('#lblDbCountSakit').text(res.count_in_db);
                            } else {
                                $('#previewNomorSehat').text(res.preview);
                                $('#lblDbCountSehat').text(res.count_in_db);
                            }
                        }
                    },
                    error: function () {
                        // Fallback client-side simulated preview jika endpoint gagal
                        fallbackClientPreview(jenis, format, parsedStart);
                    }
                });
            }

            function fallbackClientPreview(jenis, template, startNum) {
                const now = new Date();
                const year = now.getFullYear().toString();
                const year2 = year.slice(-2);
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                const romanMonth = romans[now.getMonth()] || 'I';
                const nextSeq = startNum + 1;

                let str = template;
                str = str.replace(/{NUM}/g, nextSeq);
                str = str.replace(/{NUM3}/g, String(nextSeq).padStart(3, '0'));
                str = str.replace(/{NUM4}/g, String(nextSeq).padStart(4, '0'));
                str = str.replace(/{NUM5}/g, String(nextSeq).padStart(5, '0'));
                str = str.replace(/{ROMAN_MONTH}/g, romanMonth);
                str = str.replace(/{MONTH}/g, month);
                str = str.replace(/{YEAR}/g, year);
                str = str.replace(/{YEAR2}/g, year2);
                str = str.replace(/{DAY}/g, day);

                if (jenis === 'sakit') {
                    $('#previewNomorSakit').text(str);
                } else {
                    $('#previewNomorSehat').text(str);
                }
            }

            // Bind events ke inputs
            $('#format_sakit, #start_num_sakit, #format_sehat, #start_num_sehat').on('input keyup change', function () {
                triggerPreview();
            });

            $('input[name="reset_periode"]').on('change', function () {
                triggerPreview();
            });

            // Tombol Simpan
            $('#btnSimpanSemua, #btnSimpanBawah').on('click', function () {
                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

                $.ajax({
                    url: "{{ url('/setting/nomor-surat') }}",
                    method: 'POST',
                    data: $('#formSettingNomorSurat').serialize(),
                    success: function (res) {
                        $btn.prop('disabled', false).html(originalHtml);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Disimpan',
                                text: res.message || 'Pengaturan nomor surat berhasil diperbarui!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            alert(res.message || 'Pengaturan berhasil disimpan!');
                        }
                        triggerPreview();
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).html(originalHtml);
                        let errMsg = 'Terjadi kesalahan saat menyimpan pengaturan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: errMsg
                            });
                        } else {
                            alert(errMsg);
                        }
                    }
                });
            });
        });
    </script>
@endpush

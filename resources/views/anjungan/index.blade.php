@extends('anjungan.layout')

@section('content')
<div class="container-fluid px-3 px-lg-5">

    <!-- HOME VIEW (Default Screen) -->
    <div id="viewHome">
        <!-- Header Banner (Clean, Minimal, Professional) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="touch-card px-4 py-3 p-lg-4" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%); border-left: 6px solid #0d9488;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <span class="badge bg-teal-lt text-teal mb-1 px-3 py-1 fw-bold fs-6">
                                <i class="ti ti-building-hospital me-1"></i> Pelayanan Pasien Mandiri
                            </span>
                            <h2 class="display-6 fw-extrabold text-dark mb-0" style="letter-spacing: -0.02em;">
                                Pendaftaran & Antrean Pasien
                            </h2>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="touch-guide-badge">
                                <span class="touch-icon-pulse">
                                    <i class="ti ti-hand-finger"></i>
                                </span>
                                <div class="touch-guide-content">
                                    <span class="touch-guide-label">Instruksi Kiosk</span>
                                    <span class="touch-guide-action">
                                        <span class="highlight">Sentuh Layar</span> pada Pilihan Opsi di Bawah
                                    </span>
                                </div>
                                <span class="touch-guide-arrow">
                                    <i class="ti ti-arrow-down"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Primary Action Cards -->
        <div class="row g-4">
            <!-- CARD 1: AMBIL ANTREAN LOKET -->
            <div class="col-12 col-lg-6">
                <div class="touch-card h-100 p-4 p-xl-5 d-flex flex-column justify-content-between border-top border-4 border-teal bg-white">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-teal-lt text-teal px-3 py-1 fs-5 fw-bold">
                                <i class="ti ti-users me-1"></i> OPSI 1
                            </span>
                            <div class="text-teal fs-1">
                                <i class="ti ti-receipt-2"></i>
                            </div>
                        </div>

                        <h3 class="display-6 fw-bolder text-dark mb-2">Ambil Antrean Loket</h3>
                        <p class="text-secondary fs-4 mb-4">
                            Untuk pendaftaran <strong>pasien baru</strong>, keperluan berkas administrasi, informasi rujukan, atau pembayaran kasir.
                        </p>
                    </div>

                    <div class="d-flex flex-column gap-3 mt-4">
                        <!-- Button BPJS -->
                        <button type="button" class="btn btn-touch btn-touch-emerald p-3 p-xl-4 text-start justify-content-between" onclick="ambilAntreanLoket('A')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-success rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                    <i class="ti ti-shield-check fs-1"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-white">PASIEN BPJS KESEHATAN</div>
                                    <div class="fs-5 text-white-50">Ambil Tiket Loket Kode A</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-white text-success fw-bold px-3 py-2 fs-5 rounded-pill shadow-sm" id="badgeLoketA">
                                    <i class="ti ti-ticket me-1"></i> {{ $antreanLoketA }} Antrean
                                </span>
                                <i class="ti ti-chevron-right fs-1 text-white"></i>
                            </div>
                        </button>

                        <!-- Button Umum -->
                        <button type="button" class="btn btn-touch btn-touch-blue p-3 p-xl-4 text-start justify-content-between" onclick="ambilAntreanLoket('B')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                    <i class="ti ti-wallet fs-1"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-white">PASIEN UMUM / ASURANSI</div>
                                    <div class="fs-5 text-white-50">Ambil Tiket Loket Kode B</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-white text-primary fw-bold px-3 py-2 fs-5 rounded-pill shadow-sm" id="badgeLoketB">
                                    <i class="ti ti-ticket me-1"></i> {{ $antreanLoketB }} Antrean
                                </span>
                                <i class="ti ti-chevron-right fs-1 text-white"></i>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CARD 2: PENDAFTARAN MANDIRI (APM) -->
            <div class="col-12 col-lg-6">
                <div class="touch-card h-100 p-4 p-xl-5 d-flex flex-column justify-content-between border-top border-4 border-primary bg-white">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary-lt text-primary px-3 py-1 fs-5 fw-bold">
                                <i class="ti ti-bolt me-1"></i> OPSI 2 · TANPA ANTRE LOKET
                            </span>
                            <div class="text-primary fs-1">
                                <i class="ti ti-device-touch"></i>
                            </div>
                        </div>

                        <h3 class="display-6 fw-bolder text-dark mb-2">Pendaftaran Mandiri</h3>
                        <p class="text-secondary fs-4 mb-3">
                            Khusus <strong>pasien lama</strong> yang telah memiliki Nomor Rekam Medis (RM). Langsung check-in ke poliklinik tujuan!
                        </p>

                        <div class="bg-light p-3 rounded-3 border border-secondary-subtle mb-4">
                            <div class="d-flex align-items-center gap-2 text-dark mb-2">
                                <i class="ti ti-check text-success fs-3"></i>
                                <span class="fs-5">Cari dengan <strong>NIK KTP</strong>, <strong>No. RM</strong>, atau <strong>No. BPJS</strong></span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-dark mb-2">
                                <i class="ti ti-check text-success fs-3"></i>
                                <span class="fs-5">Pilih Poliklinik & Dokter jaga hari ini</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-dark">
                                <i class="ti ti-check text-success fs-3"></i>
                                <span class="fs-5">Karcis poli langsung tercetak otomatis</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="button" class="btn btn-touch btn-touch-primary w-100 p-3 p-xl-4 justify-content-between" onclick="startPendaftaranMandiri()">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-teal rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px;">
                                    <i class="ti ti-fingerprint fs-1"></i>
                                </div>
                                <div class="text-start">
                                    <div class="fs-3 fw-bold text-white">MULAI DAFTAR MANDIRI</div>
                                    <div class="fs-5 text-white-50">Sentuh di sini untuk check-in</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-white text-teal fw-bold px-3 py-2 fs-5 rounded-pill shadow-sm" id="badgeReg">
                                    <i class="ti ti-stethoscope me-1"></i> {{ $registrasiHariIni }} Terdaftar
                                </span>
                                <i class="ti ti-arrow-right fs-1 text-white"></i>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- WIZARD PENDAFTARAN MANDIRI (Hidden by default) -->
    <div id="viewWizard" style="display: none;">
        <!-- Top Nav & Stepper Bar -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <button type="button" class="btn btn-touch btn-touch-secondary px-3 py-2" onclick="resetToKioskHome()">
                <i class="ti ti-arrow-left me-1"></i> Batal / Menu Utama
            </button>

            <!-- Stepper Pills -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="step-pill active" id="stepPill1">
                    <span>1</span> Identitas Pasien
                </div>
                <div class="step-pill" id="stepPill2">
                    <span>2</span> Konfirmasi Pasien
                </div>
                <div class="step-pill" id="stepPill3">
                    <span>3</span> Pilih Poli & Dokter
                </div>
                <div class="step-pill" id="stepPill4">
                    <span>4</span> Cetak Karcis
                </div>
            </div>
        </div>

        <!-- STEP 1: INPUT IDENTITAS -->
        <div id="wizardStep1" class="wizard-step">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8 col-xl-7">
                    <div class="touch-card p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <h3 class="display-6 fw-bold text-dark mb-2">Identifikasi Pasien</h3>
                            <p class="text-secondary fs-4">
                                Silakan pilih jenis identitas lalu ketik nomor menggunakan tombol angka di layar:
                            </p>

                            <!-- Identity Type Tabs -->
                            <div class="d-flex justify-content-center gap-2 my-3 flex-wrap">
                                <button type="button" class="btn btn-touch btn-touch-secondary active px-3 py-2 btn-id-type" data-type="nik" data-placeholder="Masukkan 16 Digit NIK KTP">
                                    <i class="ti ti-id me-1"></i> NIK KTP (16 Digit)
                                </button>
                                <button type="button" class="btn btn-touch btn-touch-secondary px-3 py-2 btn-id-type" data-type="norm" data-placeholder="Masukkan No. Rekam Medis (Contoh: 001234)">
                                    <i class="ti ti-notes me-1"></i> No. Rekam Medis (RM)
                                </button>
                                <button type="button" class="btn btn-touch btn-touch-secondary px-3 py-2 btn-id-type" data-type="nobpjs" data-placeholder="Masukkan 13 Digit No. BPJS / JKN">
                                    <i class="ti ti-credit-card me-1"></i> No. Kartu BPJS (13 Digit)
                                </button>
                            </div>
                        </div>

                        <!-- Big Input Box -->
                        <div class="mb-4 text-center">
                            <input type="text" class="form-control kiosk-display-input" id="inputKeyword" placeholder="Masukkan 16 Digit NIK KTP" autocomplete="off" autofocus>
                            <div class="text-secondary mt-2 fs-5">
                                <i class="ti ti-scan me-1 text-teal"></i> Anda juga dapat memindai barcode / QR pada kartu berobat atau KTP
                            </div>
                        </div>

                        <!-- Virtual Touch Numpad -->
                        <div class="numpad-grid mb-4">
                            <button type="button" class="numpad-key" onclick="numpadInput('1')">1</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('2')">2</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('3')">3</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('4')">4</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('5')">5</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('6')">6</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('7')">7</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('8')">8</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('9')">9</button>
                            <button type="button" class="numpad-key key-action" onclick="numpadClear()">BERSIHKAN</button>
                            <button type="button" class="numpad-key" onclick="numpadInput('0')">0</button>
                            <button type="button" class="numpad-key key-delete" onclick="numpadDelete()"><i class="ti ti-backspace fs-2"></i></button>
                        </div>

                        <!-- Action Submit -->
                        <div>
                            <button type="button" class="btn btn-touch btn-touch-primary w-100 py-3 fs-3" id="btnCariPasien" onclick="submitCariPasien()">
                                <i class="ti ti-search me-2"></i> PERIKSA DATA PASIEN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 2: KONFIRMASI DATA PASIEN & PILIH JALUR -->
        <div id="wizardStep2" class="wizard-step" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="touch-card p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <span class="badge bg-success-lt text-success px-3 py-1 fs-5 fw-bold mb-2">
                                <i class="ti ti-check-circle me-1"></i> Pasien Terverifikasi
                            </span>
                            <h3 class="display-6 fw-bold text-dark mb-1">Konfirmasi Identitas</h3>
                            <p class="text-secondary fs-4">Mohon pastikan data pasien berikut adalah benar milik Anda:</p>
                        </div>

                        <!-- Patient Info Card -->
                        <div class="p-4 rounded-3 mb-4 bg-light border border-secondary-subtle">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-teal-lt text-teal rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px;">
                                        <i class="ti ti-user fs-1"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h2 class="fw-bold text-dark mb-1" id="pasienNama">-</h2>
                                    <div class="d-flex flex-wrap gap-2 text-secondary fs-5">
                                        <span class="badge bg-white text-dark border">No. RM: <strong id="pasienRM">-</strong></span>
                                        <span class="badge bg-white text-dark border">NIK: <strong id="pasienNIK">-</strong></span>
                                        <span class="badge bg-white text-dark border">BPJS: <strong id="pasienBPJS">-</strong></span>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row g-2 text-secondary fs-5">
                                <div class="col-md-4">
                                    <span>Jenis Kelamin:</span>
                                    <div class="fw-bold text-dark" id="pasienJK">-</div>
                                </div>
                                <div class="col-md-4">
                                    <span>Usia:</span>
                                    <div class="fw-bold text-dark" id="pasienUmur">-</div>
                                </div>
                                <div class="col-md-4">
                                    <span>Tanggal Lahir:</span>
                                    <div class="fw-bold text-dark" id="pasienTglLahir">-</div>
                                </div>
                                <div class="col-12 mt-2">
                                    <span>Alamat:</span>
                                    <div class="fw-bold text-dark" id="pasienAlamat">-</div>
                                </div>
                            </div>
                        </div>

                        <!-- If Already Registered Today (Alert) -->
                        <div id="boxAlreadyRegistered" class="alert alert-warning p-4 rounded-3 mb-4" style="display: none; background: #fffbeb; border: 1.5px solid #fde68a; color: #92400e;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="ti ti-alert-triangle fs-1 text-warning"></i>
                                <div>
                                    <h4 class="fw-bold mb-1 text-dark">Pasien Sudah Terdaftar Hari Ini</h4>
                                    <div class="fs-5" id="alreadyRegText">
                                        Anda sudah terdaftar berobat untuk hari ini.
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-touch btn-warning text-dark px-4 py-2" id="btnReprintAlreadyReg">
                                    <i class="ti ti-printer me-1"></i> Cetak Ulang Karcis
                                </button>
                            </div>
                        </div>

                        <!-- Payment Category Selection (BPJS or Umum) -->
                        <div id="boxPilihPenjamin">
                            <h4 class="fw-bold text-dark mb-3">Pilih Jenis Pelayanan / Penjamin:</h4>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="touch-card p-3 p-md-4 h-100 touch-choice" id="choiceBPJS" onclick="selectPenjamin('BPJS')">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-teal fs-6">JALUR BPJS</span>
                                            <i class="ti ti-circle-check fs-2 text-teal choice-icon" style="opacity: 0.3;"></i>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1">BPJS Kesehatan</h4>
                                        <p class="text-secondary fs-6 mb-0">Menggunakan hak kepesertaan JKN / KIS aktif.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="touch-card p-3 p-md-4 h-100 touch-choice" id="choiceUMUM" onclick="selectPenjamin('UMUM')">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-primary fs-6">JALUR UMUM</span>
                                            <i class="ti ti-circle-check fs-2 text-primary choice-icon" style="opacity: 0.3;"></i>
                                        </div>
                                        <h4 class="fw-bold text-dark mb-1">Pasien Umum</h4>
                                        <p class="text-secondary fs-6 mb-0">Pembayaran mandiri atau penjamin asuransi lain.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-3">
                                <button type="button" class="btn btn-touch btn-touch-secondary px-4 py-3" onclick="goToStep(1)">
                                    <i class="ti ti-arrow-left me-1"></i> Ubah Pasien
                                </button>
                                <button type="button" class="btn btn-touch btn-touch-primary px-5 py-3 fs-4" id="btnNextToPoli" onclick="proceedToPoliSelection()">
                                    Lanjut Pilih Poliklinik <i class="ti ti-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 3: PILIH POLIKLINIK & DOKTER -->
        <div id="wizardStep3" class="wizard-step" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="touch-card p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <div>
                                <h3 class="display-6 fw-bold text-dark mb-1">Pilih Poliklinik & Dokter</h3>
                                <p class="text-secondary fs-4 mb-0">Jadwal praktik dokter yang aktif melayani pada hari ini:</p>
                            </div>
                            <span class="badge bg-teal-lt text-teal px-3 py-2 fs-5" id="labelHariJadwal">HARI INI</span>
                        </div>

                        <!-- Poli Cards Grid -->
                        <div class="row g-3 mb-4" id="gridPoliklinik">
                            <div class="col-12 text-center py-5 text-secondary">
                                <div class="spinner-border text-teal mb-3" role="status"></div>
                                <div class="fs-4">Memuat jadwal poliklinik...</div>
                            </div>
                        </div>

                        <!-- Doctor List Accordion/Cards for Selected Poli -->
                        <div id="boxDoctorSelection" style="display: none;">
                            <h4 class="fw-bold text-dark mb-3">Dokter Praktik di <span class="text-teal" id="selectedPoliName">-</span>:</h4>
                            <div class="row g-3 mb-4" id="gridDokter">
                                <!-- Populated dynamically -->
                            </div>
                        </div>

                        <div class="d-flex justify-content-between gap-3 mt-4">
                            <button type="button" class="btn btn-touch btn-touch-secondary px-4 py-3" onclick="goToStep(2)">
                                <i class="ti ti-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-touch btn-touch-primary px-5 py-3 fs-4" id="btnNextToConfirm" onclick="proceedToConfirmation()" disabled>
                                Lanjut Konfirmasi <i class="ti ti-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 4: KONFIRMASI RINGKASAN & DAFTAR -->
        <div id="wizardStep4" class="wizard-step" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="touch-card p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <span class="badge bg-primary-lt text-primary px-3 py-1 fs-5 fw-bold mb-2">
                                <i class="ti ti-notes me-1"></i> Langkah Terakhir
                            </span>
                            <h3 class="display-6 fw-bold text-dark mb-1">Ringkasan Pendaftaran</h3>
                            <p class="text-secondary fs-4">Mohon periksa ringkasan pendaftaran Anda sebelum karcis dicetak:</p>
                        </div>

                        <!-- Summary Card -->
                        <div class="p-4 rounded-3 mb-4 bg-light border border-secondary-subtle">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="text-secondary fs-5">Nama Pasien:</span>
                                    <div class="fs-3 fw-bold text-dark" id="sumPasienNama">-</div>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-secondary fs-5">No. Rekam Medis:</span>
                                    <div class="fs-3 fw-bold text-teal" id="sumPasienRM">-</div>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-secondary fs-5">Poliklinik Tujuan:</span>
                                    <div class="fs-3 fw-bold text-dark" id="sumPoliNama">-</div>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-secondary fs-5">Dokter Pemeriksa:</span>
                                    <div class="fs-3 fw-bold text-dark" id="sumDokterNama">-</div>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-secondary fs-5">Penjamin / Cara Bayar:</span>
                                    <div><span class="badge bg-teal fs-5 mt-1" id="sumPenjaminNama">-</span></div>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-secondary fs-5">Jam Praktik:</span>
                                    <div class="fs-4 fw-semibold text-dark" id="sumJamPraktek">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between gap-3">
                            <button type="button" class="btn btn-touch btn-touch-secondary px-4 py-3" onclick="goToStep(3)">
                                <i class="ti ti-arrow-left me-1"></i> Ubah Poli / Dokter
                            </button>
                            <button type="button" class="btn btn-touch btn-touch-emerald px-5 py-3 fs-3" id="btnFinalSubmit" onclick="executeDaftarMandiri()">
                                <i class="ti ti-printer me-2"></i> DAFTARKAN & CETAK KARCIS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 5: HASIL TIKET & SUKSES -->
        <div id="wizardStep5" class="wizard-step" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-7 col-xl-6">
                    <div class="touch-card p-4 p-md-5 text-center bg-white">
                        <div class="mb-3 text-success">
                            <i class="ti ti-circle-check" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="display-6 fw-bold text-dark mb-1">Pendaftaran Berhasil!</h2>
                        <p class="text-secondary fs-4 mb-4">
                            Karcis pendaftaran Anda sedang dicetak di mesin printer. Harap ambil struk Anda di bawah:
                        </p>

                        <!-- Struk Preview Mockup -->
                        <div class="ticket-receipt-card text-center mb-4 mx-auto" style="max-width: 380px;">
                            <div class="fw-bold fs-4 text-uppercase mb-1">{{ $setting->nama_instansi ?? 'KLINIK' }}</div>
                            <div class="fs-6 text-muted mb-2">BUKTI REGISTRASI MANDIRI (APM)</div>
                            <div style="border-top: 1px dashed #999; margin: 8px 0;"></div>
                            
                            <div class="text-muted fs-6">NOMOR ANTREAN POLIKLINIK</div>
                            <div class="display-3 fw-black text-dark my-1" id="resNomorAntrean" style="letter-spacing: 2px;">001</div>
                            <div class="badge bg-dark text-white fs-5 mb-2" id="resPoli">-</div>

                            <div style="border-top: 1px dashed #999; margin: 8px 0;"></div>
                            <div class="text-start fs-6 text-dark">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>No. RM:</span>
                                    <strong id="resNoRM">-</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Nama:</span>
                                    <strong id="resNama">-</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Dokter:</span>
                                    <strong id="resDokter">-</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Penjamin:</span>
                                    <strong id="resPenjamin">-</strong>
                                </div>
                            </div>
                        </div>

                        <div class="text-warning fs-5 mb-4">
                            Layar akan kembali otomatis ke menu awal dalam <strong id="resCountdown">10</strong> detik.
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-touch btn-touch-secondary px-4 py-3" onclick="reprintCurrentRegistrasi()">
                                <i class="ti ti-printer me-1"></i> Cetak Ulang Karcis
                            </button>
                            <button type="button" class="btn btn-touch btn-touch-primary px-5 py-3 fs-4" onclick="resetToKioskHome()">
                                <i class="ti ti-check me-1"></i> Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Loket Ticket Dialog -->
<div class="modal fade" id="modalLoketTicket" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content touch-card text-center p-4 p-md-5 bg-white">
            <div class="mb-2 text-teal">
                <i class="ti ti-ticket" style="font-size: 4rem;"></i>
            </div>
            <h3 class="display-6 fw-bold text-dark mb-1">Nomor Antrean Anda</h3>
            <p class="text-secondary fs-4" id="modalLoketLayanan">LOKET PENDAFTARAN</p>

            <div class="p-3 my-3 rounded-4" style="background: #f0fdfa; border: 2px dashed #0d9488;">
                <div class="display-1 fw-black text-dark" id="modalLoketNomor" style="letter-spacing: 4px;">A001</div>
            </div>

            <p class="text-secondary fs-5 mb-3">
                Struk antrean Anda telah dicetak. Silakan menunggu pemanggilan di ruang tunggu loket.
            </p>

            <div class="text-warning fs-5 mb-4">
                Kembali ke menu awal dalam <strong id="modalLoketCountdown">8</strong> detik
            </div>

            <button type="button" class="btn btn-touch btn-touch-primary w-100 py-3 fs-4" onclick="closeLoketModal()">
                <i class="ti ti-check me-1"></i> Selesai
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // State Variables for Kiosk Flow
    let currentInputType = 'nik';
    let currentPasienData = null;
    let currentSelectedPenjamin = null;
    let currentJadwalData = [];
    let currentSelectedPoli = null;
    let currentSelectedDokter = null;
    let lastRegisteredNoRawat = null;
    let stepCountdownInterval = null;
    let loketCountdownInterval = null;

    // Reset to Home Screen
    function resetToKioskHome() {
        if (stepCountdownInterval) clearInterval(stepCountdownInterval);
        if (loketCountdownInterval) clearInterval(loketCountdownInterval);
        isSubScreenActive = false;
        currentPasienData = null;
        currentSelectedPenjamin = null;
        currentSelectedPoli = null;
        currentSelectedDokter = null;
        lastRegisteredNoRawat = null;
        $('#inputKeyword').val('');

        $('.wizard-step').hide();
        $('#viewWizard').hide();
        $('#viewHome').fadeIn(200);
    }

    // Start Pendaftaran Mandiri Flow
    function startPendaftaranMandiri() {
        $('#viewHome').hide();
        $('#viewWizard').fadeIn(200);
        isSubScreenActive = true;
        goToStep(1);
    }

    // Switch Wizard Steps
    function goToStep(stepNum) {
        $('.wizard-step').hide();
        $('.step-pill').removeClass('active completed');

        for (let i = 1; i < stepNum; i++) {
            $(`#stepPill${i}`).addClass('completed');
        }
        $(`#stepPill${stepNum}`).addClass('active');

        $(`#wizardStep${stepNum}`).fadeIn(200);

        if (stepNum === 1) {
            $('#inputKeyword').focus();
        }
    }

    // Identity Type Switcher
    $('.btn-id-type').on('click', function() {
        $('.btn-id-type').removeClass('active btn-touch-primary').addClass('btn-touch-secondary');
        $(this).removeClass('btn-touch-secondary').addClass('active btn-touch-primary');
        currentInputType = $(this).data('type');
        const placeholder = $(this).data('placeholder');
        $('#inputKeyword').attr('placeholder', placeholder).val('').focus();
    });

    // Touch Numpad Helpers
    function numpadInput(digit) {
        const input = $('#inputKeyword');
        input.val(input.val() + digit);
    }

    function numpadDelete() {
        const input = $('#inputKeyword');
        input.val(input.val().slice(0, -1));
    }

    function numpadClear() {
        $('#inputKeyword').val('');
    }

    // Handle Enter Key on Input
    $('#inputKeyword').on('keypress', function(e) {
        if (e.which === 13) {
            submitCariPasien();
        }
    });

    // Cari Pasien via AJAX
    function submitCariPasien() {
        const keyword = $('#inputKeyword').val().trim();
        if (!keyword) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Diisi',
                text: 'Silakan masukkan NIK, No. Rekam Medis, atau No. Kartu BPJS Anda.',
                confirmButtonColor: '#0d9488',
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Mencari Data Pasien...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '{{ route("anjungan.cek.pasien") }}',
            type: 'POST',
            data: { keyword: keyword },
            success: function(res) {
                Swal.close();
                if (res.success) {
                    currentPasienData = res.pasien;

                    // Populate Step 2
                    $('#pasienNama').text(res.pasien.nm_pasien);
                    $('#pasienRM').text(res.pasien.no_rkm_medis);
                    $('#pasienNIK').text(res.pasien.no_ktp);
                    $('#pasienBPJS').text(res.pasien.no_peserta);
                    $('#pasienJK').text(res.pasien.jk);
                    $('#pasienUmur').text(res.pasien.umur);
                    $('#pasienTglLahir').text(res.pasien.tgl_lahir);
                    $('#pasienAlamat').text(res.pasien.alamat || '-');

                    // Check if already registered today
                    if (res.status === 'already_registered') {
                        $('#boxAlreadyRegistered').show();
                        $('#boxPilihPenjamin').hide();
                        const reg = res.registrasi;
                        $('#alreadyRegText').html(`
                            Pasien sudah terdaftar berobat hari ini pada jam <strong>${reg.jam_reg} WIB</strong> di <strong>${reg.nm_poli}</strong> bersama <strong>${reg.nm_dokter}</strong>.
                            <br>Nomor Antrean: <strong>${reg.no_urut_pcare || reg.no_reg}</strong>.
                        `);
                        lastRegisteredNoRawat = reg.no_rawat;
                        $('#btnReprintAlreadyReg').off('click').on('click', function() {
                            triggerThermalPrint('{{ url("anjungan/cetak-struk/registrasi") }}/' + encodeURIComponent(reg.no_rawat));
                            Swal.fire({
                                icon: 'success',
                                title: 'Mencetak Karcis',
                                text: 'Karcis pendaftaran Anda sedang dicetak ulang.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                    } else {
                        $('#boxAlreadyRegistered').hide();
                        $('#boxPilihPenjamin').show();

                        // Configure BPJS availability
                        if (res.pasien.has_bpjs) {
                            $('#choiceBPJS').removeClass('disabled opacity-50');
                            selectPenjamin('BPJS');
                        } else {
                            $('#choiceBPJS').addClass('disabled opacity-50');
                            selectPenjamin('UMUM');
                        }
                    }

                    goToStep(2);
                }
            },
            error: function(xhr) {
                Swal.close();
                const res = xhr.responseJSON;
                const msg = res && res.message ? res.message : 'Pasien tidak ditemukan dalam sistem.';
                Swal.fire({
                    icon: 'info',
                    title: 'Pasien Belum Terdaftar',
                    text: msg,
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="ti ti-ticket me-1"></i> Ambil Antrean Loket',
                    cancelButtonText: 'Coba Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        resetToKioskHome();
                        ambilAntreanLoket('A');
                    }
                });
            }
        });
    }

    // Select Penjamin (BPJS / UMUM)
    function selectPenjamin(type) {
        if (type === 'BPJS' && currentPasienData && !currentPasienData.has_bpjs) {
            Swal.fire({
                icon: 'warning',
                title: 'No. Kartu BPJS Belum Ada',
                text: 'Data rekam medis Anda belum memiliki nomor kartu BPJS. Silakan pilih Pasien Umum atau verifikasi ke loket.',
                confirmButtonColor: '#0d9488'
            });
            return;
        }

        currentSelectedPenjamin = type;
        $('.touch-choice').removeClass('border-teal border-primary shadow').css({
            'border-color': 'var(--kiosk-border)',
            'background': '#ffffff'
        });
        $('.choice-icon').css('opacity', '0.3');

        if (type === 'BPJS') {
            $('#choiceBPJS').css({
                'border-color': '#0d9488',
                'border-width': '2.5px',
                'background': '#f0fdfa'
            }).addClass('shadow-sm');
            $('#choiceBPJS .choice-icon').css('opacity', '1');
        } else {
            $('#choiceUMUM').css({
                'border-color': '#2563eb',
                'border-width': '2.5px',
                'background': '#eff6ff'
            }).addClass('shadow-sm');
            $('#choiceUMUM .choice-icon').css('opacity', '1');
        }
    }

    // Proceed to Step 3: Load Jadwal Poli
    function proceedToPoliSelection() {
        if (!currentSelectedPenjamin) {
            selectPenjamin('UMUM');
        }

        goToStep(3);
        loadJadwalPoliklinik();
    }

    // Fetch and render Poliklinik & Doctors
    function loadJadwalPoliklinik() {
        $('#gridPoliklinik').html(`
            <div class="col-12 text-center py-5 text-secondary">
                <div class="spinner-border text-teal mb-3" role="status"></div>
                <div class="fs-4">Memuat data poliklinik aktif...</div>
            </div>
        `);
        $('#boxDoctorSelection').hide();
        $('#btnNextToConfirm').prop('disabled', true);
        currentSelectedPoli = null;
        currentSelectedDokter = null;

        $.ajax({
            url: '{{ route("anjungan.jadwal.poli") }}',
            type: 'GET',
            success: function(res) {
                if (res.success && res.data && res.data.length > 0) {
                    currentJadwalData = res.data;
                    $('#labelHariJadwal').text(res.hari + ', ' + res.tanggal);

                    let html = '';
                    res.data.forEach((poli) => {
                        const doctorCount = poli.doctors ? poli.doctors.length : 0;
                        html += `
                            <div class="col-md-6 col-lg-4">
                                <div class="poli-card h-100" data-kdpoli="${poli.kd_poli}" onclick="selectPoliCard('${poli.kd_poli}')">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-teal-lt text-teal fs-6">${doctorCount} Dokter Praktik</span>
                                        <i class="ti ti-stethoscope fs-2 text-teal"></i>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1">${poli.nm_poli}</h4>
                                    <p class="text-secondary fs-6 mb-0">Sentuh untuk melihat dokter jaga</p>
                                </div>
                            </div>
                        `;
                    });
                    $('#gridPoliklinik').html(html);

                    // Auto-select first poli if only one
                    if (res.data.length === 1) {
                        selectPoliCard(res.data[0].kd_poli);
                    }
                } else {
                    $('#gridPoliklinik').html(`
                        <div class="col-12 text-center py-5 text-secondary">
                            <i class="ti ti-calendar-off fs-1 text-warning mb-2"></i>
                            <div class="fs-4">Tidak ada jadwal poliklinik aktif untuk hari ini.</div>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#gridPoliklinik').html(`
                    <div class="col-12 text-center py-5 text-danger">
                        <i class="ti ti-alert-circle fs-1 mb-2"></i>
                        <div class="fs-4">Gagal memuat jadwal poliklinik. Silakan coba kembali.</div>
                    </div>
                `);
            }
        });
    }

    // Select Poliklinik Card
    function selectPoliCard(kdPoli) {
        currentSelectedPoli = currentJadwalData.find(p => p.kd_poli === kdPoli);
        if (!currentSelectedPoli) return;

        $('.poli-card').removeClass('selected');
        $(`.poli-card[data-kdpoli="${kdPoli}"]`).addClass('selected');

        $('#selectedPoliName').text(currentSelectedPoli.nm_poli);
        $('#boxDoctorSelection').slideDown(200);

        // Render Doctors
        let docHtml = '';
        if (currentSelectedPoli.doctors && currentSelectedPoli.doctors.length > 0) {
            currentSelectedPoli.doctors.forEach(doc => {
                const isFull = doc.is_full;
                docHtml += `
                    <div class="col-md-6">
                        <div class="doctor-card h-100 ${isFull ? 'opacity-50 disabled' : ''}" data-kddokter="${doc.kd_dokter}" onclick="${isFull ? '' : `selectDoctorCard('${doc.kd_dokter}')`}">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge ${isFull ? 'bg-danger' : 'bg-success'} fs-6">
                                    ${isFull ? 'KUOTA PENUH' : `Sisa Kuota: ${doc.sisa_kuota}`}
                                </span>
                                <span class="text-secondary fs-6"><i class="ti ti-clock me-1"></i>${doc.jam_praktek}</span>
                            </div>
                            <h4 class="fw-bold text-dark mb-1">${doc.nm_dokter}</h4>
                            <p class="text-secondary fs-6 mb-0">Terdaftar hari ini: ${doc.terdaftar} pasien</p>
                        </div>
                    </div>
                `;
            });
        } else {
            docHtml = `
                <div class="col-12 text-secondary text-center py-3">
                    Belum ada dokter yang dijadwalkan pada poliklinik ini.
                </div>
            `;
        }
        $('#gridDokter').html(docHtml);

        // Auto select first doctor if available and not full
        const availableDoc = currentSelectedPoli.doctors.find(d => !d.is_full);
        if (availableDoc) {
            selectDoctorCard(availableDoc.kd_dokter);
        } else {
            currentSelectedDokter = null;
            $('#btnNextToConfirm').prop('disabled', true);
        }
    }

    // Select Doctor Card
    function selectDoctorCard(kdDokter) {
        if (!currentSelectedPoli) return;
        currentSelectedDokter = currentSelectedPoli.doctors.find(d => d.kd_dokter === kdDokter);
        if (!currentSelectedDokter) return;

        $('.doctor-card').removeClass('selected');
        $(`.doctor-card[data-kddokter="${kdDokter}"]`).addClass('selected');
        $('#btnNextToConfirm').prop('disabled', false);
    }

    // Proceed to Step 4: Confirmation
    function proceedToConfirmation() {
        if (!currentPasienData || !currentSelectedPoli || !currentSelectedDokter) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Silakan pilih dokter jaga terlebih dahulu.',
                confirmButtonColor: '#0d9488'
            });
            return;
        }

        $('#sumPasienNama').text(currentPasienData.nm_pasien);
        $('#sumPasienRM').text(currentPasienData.no_rkm_medis);
        $('#sumPoliNama').text(currentSelectedPoli.nm_poli);
        $('#sumDokterNama').text(currentSelectedDokter.nm_dokter);
        $('#sumPenjaminNama').text(currentSelectedPenjamin === 'BPJS' ? 'BPJS KESEHATAN' : 'UMUM / MANDIRI');
        $('#sumJamPraktek').text(currentSelectedDokter.jam_praktek + ' WIB');

        goToStep(4);
    }

    // Submit Final Registration (Step 4 -> Step 5)
    function executeDaftarMandiri() {
        $('#btnFinalSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Mendaftarkan...');

        $.ajax({
            url: '{{ route("anjungan.daftar.mandiri") }}',
            type: 'POST',
            data: {
                no_rkm_medis: currentPasienData.no_rkm_medis,
                kd_poli: currentSelectedPoli.kd_poli,
                kd_dokter: currentSelectedDokter.kd_dokter,
                jenis_bayar: currentSelectedPenjamin
            },
            success: function(res) {
                $('#btnFinalSubmit').prop('disabled', false).html('<i class="ti ti-printer me-2"></i> DAFTARKAN & CETAK KARCIS');
                if (res.success) {
                    AudioFeedback.success();
                    lastRegisteredNoRawat = res.data.no_rawat;

                    // Trigger Print automatically
                    triggerThermalPrint('{{ url("anjungan/cetak-struk/registrasi") }}/' + encodeURIComponent(res.data.no_rawat));

                    // Populate Step 5
                    $('#resNomorAntrean').text(res.data.no_urut_display || res.data.no_reg);
                    $('#resPoli').text(res.data.nm_poli);
                    $('#resNoRM').text(res.data.no_rkm_medis);
                    $('#resNama').text(res.data.nm_pasien);
                    $('#resDokter').text(res.data.nm_dokter);
                    $('#resPenjamin').text(res.data.penjamin);

                    goToStep(5);

                    // Update live badge counter
                    let currentReg = parseInt($('#badgeReg').text().replace(/[^0-9]/g, '')) || 0;
                    $('#badgeReg').html('<i class="ti ti-stethoscope me-1"></i> ' + (currentReg + 1) + ' Terdaftar');

                    // Countdown Auto Reset (10s)
                    let timeLeft = 10;
                    $('#resCountdown').text(timeLeft);
                    if (stepCountdownInterval) clearInterval(stepCountdownInterval);
                    stepCountdownInterval = setInterval(() => {
                        timeLeft--;
                        $('#resCountdown').text(timeLeft);
                        if (timeLeft <= 0) {
                            clearInterval(stepCountdownInterval);
                            resetToKioskHome();
                        }
                    }, 1000);
                }
            },
            error: function(xhr) {
                $('#btnFinalSubmit').prop('disabled', false).html('<i class="ti ti-printer me-2"></i> DAFTARKAN & CETAK KARCIS');
                const res = xhr.responseJSON;
                const msg = res && res.message ? res.message : 'Terjadi kesalahan pada sistem.';
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal',
                    text: msg,
                    confirmButtonColor: '#0d9488'
                });
            }
        });
    }

    // Reprint current registration from success screen
    function reprintCurrentRegistrasi() {
        if (lastRegisteredNoRawat) {
            triggerThermalPrint('{{ url("anjungan/cetak-struk/registrasi") }}/' + encodeURIComponent(lastRegisteredNoRawat));
            Swal.fire({
                icon: 'success',
                title: 'Mencetak Ulang',
                text: 'Perintah cetak karcis telah dikirimkan ke printer.',
                timer: 1500,
                showConfirmButton: false
            });
        }
    }

    // Ambil Antrean Loket (A or B)
    function ambilAntreanLoket(jenis) {
        Swal.fire({
            title: 'Mencetak Nomor Antrean...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '{{ route("anjungan.antrean.loket") }}',
            type: 'POST',
            data: { jenis: jenis },
            success: function(res) {
                Swal.close();
                if (res.success) {
                    AudioFeedback.success();

                    // Trigger thermal print
                    triggerThermalPrint('{{ url("anjungan/cetak-struk/loket") }}/' + res.data.id);

                    // Show Modal
                    $('#modalLoketLayanan').text(res.data.layanan);
                    $('#modalLoketNomor').text(res.data.nomor_antrean);
                    $('#modalLoketTicket').modal('show');

                    // Update live badge counter on card
                    if (jenis === 'A') {
                        let currentA = parseInt($('#badgeLoketA').text().replace(/[^0-9]/g, '')) || 0;
                        $('#badgeLoketA').html('<i class="ti ti-ticket me-1"></i> ' + (currentA + 1) + ' Antrean');
                    } else {
                        let currentB = parseInt($('#badgeLoketB').text().replace(/[^0-9]/g, '')) || 0;
                        $('#badgeLoketB').html('<i class="ti ti-ticket me-1"></i> ' + (currentB + 1) + ' Antrean');
                    }

                    // Auto close modal countdown
                    let seconds = 8;
                    $('#modalLoketCountdown').text(seconds);
                    if (loketCountdownInterval) clearInterval(loketCountdownInterval);
                    loketCountdownInterval = setInterval(() => {
                        seconds--;
                        $('#modalLoketCountdown').text(seconds);
                        if (seconds <= 0) {
                            closeLoketModal();
                        }
                    }, 1000);
                }
            },
            error: function(xhr) {
                Swal.close();
                const res = xhr.responseJSON;
                const msg = res && res.message ? res.message : 'Gagal membuat tiket antrean loket.';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: msg,
                    confirmButtonColor: '#0d9488'
                });
            }
        });
    }

    function closeLoketModal() {
        if (loketCountdownInterval) clearInterval(loketCountdownInterval);
        $('#modalLoketTicket').modal('hide');
    }
</script>
@endpush

<div class="modal fade" id="modalSatuSehatRme" data-bs-focus="false" aria-labelledby="modalSatuSehatRmeLabel" aria-hidden="true" style="z-index: 1080;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 760px;">
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div id="ssrme_modal_header" class="modal-header text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #00877a 0%, #00b4a4 100%); padding: 12px 18px; transition: background 0.3s ease;">
                <div class="d-flex align-items-center overflow-hidden me-3" style="min-width: 0; flex: 1;">
                    <div id="ssrme_header_icon_box" class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 38px; height: 38px; min-width: 38px; color: #00877a; margin-right: 12px;">
                        <i id="ssrme_header_icon" class="ti ti-shield-check fs-2"></i>
                    </div>
                    <div class="overflow-hidden" style="min-width: 0; flex: 1;">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="modal-title fw-bold mb-0 text-white text-truncate" id="modalSatuSehatRmeLabel" style="font-size: 14.5px; letter-spacing: 0.2px;">
                                SATUSEHAT Rekam Medis
                            </h6>
                            <span id="ssrme_header_badge" class="badge rounded-pill bg-white text-teal fw-bold d-none" style="font-size: 10px; padding: 2px 8px;">
                                REGULER
                            </span>
                        </div>
                        <div class="text-white-50 text-truncate" style="font-size: 12px; margin-top: 2px;">
                            <span id="ssrme_subtitle_tag">Kemenkes RI</span> &bull; <span id="ssrme_patient_header" class="text-white fw-semibold">-</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 border-white-50 text-white d-flex align-items-center gap-1" onclick="retryOpenSatuSehatRme()" style="font-size: 11.5px; font-weight: 500;" title="Muat Ulang">
                        <i class="ti ti-refresh"></i>
                        <span>Refresh</span>
                    </button>
                    <button type="button" class="btn-close btn-close-white ms-1" data-bs-dismiss="modal" aria-label="Close" style="position: static; width: 28px; height: 28px; padding: 0; margin: 0; font-size: 11px;"></button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-3 position-relative" style="background-color: #f8fafc; min-height: 380px;">

                <!-- 1. LOADING STATE -->
                <div id="ssrme_view_loading" class="d-flex flex-column align-items-center justify-content-center py-5">
                    <div id="ssrme_loading_spinner" class="spinner-border mb-3" style="width: 3rem; height: 3rem; color: #00877a;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h6 id="ssrme_loading_title" class="fw-bold text-dark mb-1">Menghubungkan ke SATUSEHAT</h6>
                    <p class="text-secondary small mb-0 text-center px-3" id="ssrme_loading_text">
                        Sedang memverifikasi izin akses &amp; menyiapkan tautan RME Nasional...
                    </p>
                </div>

                <!-- 2. VIEWER STATE (PILIHAN: TAB BARU / POPUP JENDELA) -->
                <div id="ssrme_view_viewer" class="d-none d-flex flex-column align-items-center justify-content-center py-4 px-2 text-center">
                    <div id="ssrme_viewer_icon_box" class="mb-3 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 68px; height: 68px; background-color: #e6f7f5; color: #00877a;">
                        <i class="ti ti-external-link fs-1"></i>
                    </div>
                    <span id="ssrme_viewer_badge" class="badge bg-success-lt text-success border border-success-subtle rounded-pill px-3 py-1 mb-2 fw-semibold" style="font-size: 11px;">
                        <i class="ti ti-circle-check me-1"></i>Izin Akses Pasien Terverifikasi
                    </span>
                    <h5 id="ssrme_viewer_title" class="fw-bold text-dark mb-1">RME SATUSEHAT Siap Diakses</h5>
                    <p id="ssrme_viewer_desc" class="text-secondary small mb-4 px-3" style="max-width: 500px; line-height: 1.5;">
                        Tautan resmi ke sistem Rekam Medis Elektronik Nasional Kemenkes RI berhasil dibuat. Silakan pilih cara membuka tampilan RME pasien:
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center w-100 px-3 mb-3" style="max-width: 540px;">
                        <button type="button" id="btnSsrmeOpenDirect" class="btn btn-lg flex-fill rounded-pill fw-bold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="openSsrmeDirect()" style="background-color: #00877a; border-color: #00877a; font-size: 13.5px;">
                            <i class="ti ti-external-link"></i>
                            <span>Buka di Tab Baru</span>
                        </button>
                        <button type="button" id="btnSsrmeOpenPopup" class="btn btn-lg flex-fill rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="openSsrmeAsPopup()" style="color: #00877a; border: 1.5px solid #00877a; background-color: #ffffff; font-size: 13.5px;">
                            <i class="ti ti-app-window"></i>
                            <span>Buka Jendela Pop-up</span>
                        </button>
                    </div>

                    <p id="ssrme_viewer_note" class="text-muted small mb-0 px-3" style="font-size: 11px;">
                        <i class="ti ti-shield-check me-1 text-success"></i>Tautan viewer resmi aman dan memiliki masa berlaku dari SATUSEHAT. Mode pop-up memudahkan dokter melihat RME bersandingan dengan layar eFKTP.
                    </p>
                </div>

                <!-- 3A. CONSENT REQUIRED STATE (REGULER - 4 LANGKAH SATUSEHAT MOBILE) -->
                <div id="ssrme_view_consent" class="d-none d-flex flex-column align-items-center py-2 px-1">
                    <!-- Header Banner -->
                    <div class="text-center mb-3">
                        <span class="badge rounded-pill px-3 py-1 mb-1 fw-semibold" style="background-color: #fef3c7; color: #b45309; font-size: 10.5px; border: 1px solid #fde68a;">
                            <i class="ti ti-lock-check me-1"></i>Memerlukan Persetujuan Akses Pasien (Consent)
                        </span>
                        <h6 class="fw-bold text-dark mb-1">Verifikasi Akses via SATUSEHAT Mobile</h6>
                        <p class="text-secondary small mb-0 mx-auto" style="max-width: 560px; font-size: 11.5px; line-height: 1.4;">
                            Sesuai regulasi Kemenkes RI, pasien perlu menyetujui pembagian data RME lintas faskes dengan mengikuti 4 langkah berikut:
                        </p>
                    </div>

                    <!-- Grid 4 Langkah -->
                    <div class="row g-2 w-100 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="card h-100 border shadow-sm rounded-3 p-2 bg-white position-relative overflow-hidden" style="border-top: 3px solid #00877a !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #00877a; font-size: 9.5px;">Langkah 01</span>
                                    <i class="ti ti-device-mobile text-teal"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 11.5px;">Buka Aplikasi</div>
                                <p class="text-secondary mb-0" style="font-size: 10.5px; line-height: 1.35;">
                                    Pasien membuka aplikasi <strong>SATUSEHAT Mobile</strong> di smartphone.
                                </p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card h-100 border shadow-sm rounded-3 p-2 bg-white position-relative overflow-hidden" style="border-top: 3px solid #00877a !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #00877a; font-size: 9.5px;">Langkah 02</span>
                                    <i class="ti ti-qrcode text-teal"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 11.5px;">Menu Resume Medis</div>
                                <p class="text-secondary mb-0" style="font-size: 10.5px; line-height: 1.35;">
                                    Pilih menu <strong>Resume Medis</strong> atau tekan fitur <strong>Scan QR</strong> di beranda.
                                </p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card h-100 border shadow-sm rounded-3 p-2 bg-white position-relative overflow-hidden" style="border-top: 3px solid #00877a !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #00877a; font-size: 9.5px;">Langkah 03</span>
                                    <i class="ti ti-scan text-teal"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 11.5px;">Scan / Beri Izin</div>
                                <p class="text-secondary mb-0" style="font-size: 10.5px; line-height: 1.35;">
                                    Scan QR Code di bawah atau setujui notifikasi akses faskes.
                                </p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card h-100 border shadow-sm rounded-3 p-2 bg-white position-relative overflow-hidden" style="border-top: 3px solid #00877a !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #00877a; font-size: 9.5px;">Langkah 04</span>
                                    <i class="ti ti-circle-check text-teal"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 11.5px;">Refresh &amp; Buka</div>
                                <p class="text-secondary mb-0" style="font-size: 10.5px; line-height: 1.35;">
                                    Setelah pasien menyetujui, klik tombol <strong>Sudah Disetujui</strong> di bawah.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Box & Action Bar -->
                    <div id="ssrme_qr_container" class="card border shadow-sm rounded-4 p-3 bg-white w-100 d-none">
                        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3">
                            <div id="ssrme_qrcode" class="p-2 bg-white border rounded-3 shadow-sm flex-shrink-0"></div>
                            <div class="text-center text-sm-start">
                                <div class="fw-bold text-dark mb-1" style="font-size: 13px;">QR Code Persetujuan Pasien</div>
                                <p class="text-secondary mb-2" style="font-size: 11.5px; line-height: 1.4;">
                                    Minta pasien memindai QR Code ini dengan SATUSEHAT Mobile atau buka tautan verifikasi di bawah.
                                </p>
                                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-sm-start">
                                    <a id="ssrme_verify_link" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-teal rounded-pill px-3" style="font-size: 11.5px;">
                                        <i class="ti ti-external-link me-1"></i>Buka Link Verifikasi
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold" onclick="retryOpenSatuSehatRme()" style="font-size: 11.5px; background-color: #00877a; border-color: #00877a;">
                                        <i class="ti ti-refresh me-1"></i>Sudah Disetujui? Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Refresh Alternatif jika tidak ada QR -->
                    <div id="ssrme_consent_no_qr_actions" class="mt-2 d-flex gap-2">
                        <button type="button" class="btn btn-success rounded-pill px-4 fw-semibold" onclick="retryOpenSatuSehatRme()" style="font-size: 12.5px; background-color: #00877a; border-color: #00877a;">
                            <i class="ti ti-refresh me-1"></i>Sudah Disetujui Pasien? Klik Cek Status
                        </button>
                    </div>
                </div>

                <!-- 3B. EMERGENCY STATE (AKSES KEDARURATAN IGD / UGD) -->
                <div id="ssrme_view_emergency" class="d-none d-flex flex-column align-items-center py-2 px-1">
                    <div class="text-center mb-3">
                        <span class="badge rounded-pill px-3 py-1 mb-1 fw-semibold" style="background-color: #fee2e2; color: #b91c1c; font-size: 10.5px; border: 1px solid #fecaca;">
                            <i class="ti ti-alert-triangle me-1"></i>Otorisasi Akses Kedaruratan Medis (Emergency)
                        </span>
                        <h6 class="fw-bold text-dark mb-1">Formulir Pembukaan Akses Darurat Kemenkes</h6>
                        <p class="text-secondary small mb-0 mx-auto" style="max-width: 560px; font-size: 11.5px; line-height: 1.4;">
                            Dalam kondisi gawat darurat, dokter dapat membuka RME Nasional tanpa OTP pasien melalui formulir otorisasi resmi SATUSEHAT:
                        </p>
                    </div>

                    <div class="row g-2 w-100 mb-3">
                        <div class="col-12 col-md-4">
                            <div class="card h-100 border border-danger-subtle shadow-sm rounded-3 p-2 bg-white" style="border-top: 3px solid #dc2626 !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #dc2626; font-size: 9.5px;">Langkah 01</span>
                                    <i class="ti ti-external-link text-danger"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 12px;">Buka Form Kemenkes</div>
                                <p class="text-secondary mb-0" style="font-size: 11px; line-height: 1.4;">
                                    Klik tombol merah <strong>Buka Form Akses Emergency</strong> di bawah untuk membuka halaman otorisasi kedaruratan resmi.
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card h-100 border border-danger-subtle shadow-sm rounded-3 p-2 bg-white" style="border-top: 3px solid #dc2626 !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #dc2626; font-size: 9.5px;">Langkah 02</span>
                                    <i class="ti ti-edit text-danger"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 12px;">Isi Alasan &amp; Data Wali</div>
                                <p class="text-secondary mb-0" style="font-size: 11px; line-height: 1.4;">
                                    Tuliskan alasan klinis kedaruratan (min 10 karakter) serta identitas pengantar/wali pasien (Nama, NIK, Hubungan).
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card h-100 border border-danger-subtle shadow-sm rounded-3 p-2 bg-white" style="border-top: 3px solid #dc2626 !important;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="badge rounded-pill fw-bold text-white px-2 py-1" style="background-color: #dc2626; font-size: 9.5px;">Langkah 03</span>
                                    <i class="ti ti-circle-check text-danger"></i>
                                </div>
                                <div class="fw-bold text-dark mb-1" style="font-size: 12px;">Ajukan Akses Emergency</div>
                                <p class="text-secondary mb-0" style="font-size: 11px; line-height: 1.4;">
                                    Centang persetujuan &amp; klik <strong>Ajukan Akses Emergency</strong>. RME Nasional pasien langsung terbuka otomatis.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 px-3 mb-2 d-flex flex-column flex-sm-row gap-2 justify-content-center">
                        <button type="button" id="btnSsrmeEmergencyOpenDirect" class="btn btn-lg btn-danger flex-fill rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="openSsrmeDirect()" style="background-color: #dc2626; border-color: #dc2626; font-size: 13.5px;">
                            <i class="ti ti-external-link"></i>
                            <span>Buka Form Akses Emergency (Tab Baru)</span>
                        </button>
                        <button type="button" id="btnSsrmeEmergencyOpenPopup" class="btn btn-lg btn-outline-danger flex-fill rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="openSsrmeAsPopup()" style="color: #dc2626; border: 1.5px solid #dc2626; font-size: 13.5px;">
                            <i class="ti ti-app-window"></i>
                            <span>Buka Jendela Pop-up</span>
                        </button>
                    </div>

                    <div class="w-100 text-center mt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="retryOpenSatuSehatRme()" style="font-size: 11.5px;">
                            <i class="ti ti-refresh me-1"></i>Sudah Submit Form di Kemenkes? Klik Cek Status
                        </button>
                        <p class="text-muted small mt-2 mb-0" style="font-size: 10.5px;">
                            <i class="ti ti-alert-triangle text-danger me-1"></i>Seluruh tindakan pengajuan akses darurat ini diaudit dan dipantau resmi oleh Kementerian Kesehatan RI.
                        </p>
                    </div>
                </div>

                <!-- 4. ERROR STATE -->
                <div id="ssrme_view_error" class="d-none d-flex flex-column align-items-center justify-content-center py-4 text-center">
                    <div class="mb-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #fde8e8; color: #dc2626;">
                        <i class="ti ti-alert-triangle fs-1"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Gagal Membuka RME SATUSEHAT</h6>
                    <p class="text-secondary small mb-3 px-3" id="ssrme_error_message">
                        Terjadi kendala saat menghubungkan ke server SATUSEHAT.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold" onclick="retryOpenSatuSehatRme()">
                            <i class="ti ti-refresh me-1"></i>Coba Lagi
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    #modalSatuSehatRme {
        z-index: 1080 !important;
    }
    #btnSsrmeOpenPopup:hover {
        background-color: #00877a !important;
        color: #ffffff !important;
    }
    #btnSsrmeEmergencyOpenPopup:hover {
        background-color: #dc2626 !important;
        color: #ffffff !important;
    }
    .btn-outline-teal {
        color: #00877a;
        border-color: #00877a;
    }
    .btn-outline-teal:hover {
        background-color: #00877a;
        color: #ffffff;
    }
</style>

@push('script')
<script>
    let ssrmeQrInstance = null;
    let currentShlinkUrl = '';
    let isCurrentEmergency = 0;
    let currentTargetNoRawat = '';
    let currentTargetKdDokter = '';
    let currentSsrmeXhr = null;

    $(document).ready(function() {
        // Jaga agar scroll modal induk tetap aktif saat modal SATUSEHAT ditutup
        $('#modalSatuSehatRme').on('hidden.bs.modal', function(e) {
            e.stopPropagation();
            if ($('#modalCppt').hasClass('show') || $('#modalCpptRanap').hasClass('show') || $('#modalRmIgd').hasClass('show') || $('.modal.show').length > 0) {
                $('body').addClass('modal-open');
            }
        });
    });

    function notifySsrme(message, type = 'info') {
        if (typeof showToast === 'function') {
            showToast(message, type === 'danger' ? 'error' : type);
        } else if (typeof swalToast === 'function') {
            swalToast(message, type);
        } else {
            alert(message);
        }
    }

    function openSsrmeDirect() {
        const url = currentShlinkUrl || $('#btnSsrmeOpenDirect').attr('data-url') || $('#btnSsrmeEmergencyOpenDirect').attr('data-url');
        if (!url || url === '#' || url === 'javascript:void(0)') {
            notifySsrme('Tautan RME belum siap, silakan tunggu...', 'warning');
            return;
        }
        window.open(url, '_blank', 'noopener,noreferrer');
    }

    function openSsrmeAsPopup() {
        const url = currentShlinkUrl || $('#btnSsrmeOpenDirect').attr('data-url') || $('#btnSsrmeEmergencyOpenDirect').attr('data-url');
        if (!url || url === '#' || url === 'javascript:void(0)') {
            notifySsrme('Tautan RME belum siap, silakan tunggu...', 'warning');
            return;
        }

        const width = Math.min(1300, Math.floor(window.screen.availWidth * 0.9));
        const height = Math.min(850, Math.floor(window.screen.availHeight * 0.9));
        const left = Math.max(0, Math.floor((window.screen.availWidth - width) / 2));
        const top = Math.max(0, Math.floor((window.screen.availHeight - height) / 2));

        window.open(
            url,
            'SATUSEHAT_RME_Viewer',
            `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes,status=yes,noopener,noreferrer`
        );
    }

    /**
     * Deteksi otomatis konteks modal aktif di eFKTP (Rajal, Ranap, atau IGD)
     */
    function resolveActivePatientContext() {
        // 1. Cek Modal RM IGD
        if ($('#modalRmIgd').hasClass('show')) {
            return {
                noRawat: $('#formRmIgd input[name="no_rawat"]').val(),
                kdDokter: $('#formRmIgd select[name="kd_dokter"]').val() || '',
                nmPasien: $('#formRmIgd input[name="nm_pasien"]').val() || '-',
                noRm: $('#formRmIgd input[name="no_rkm_medis"]').val() || '-'
            };
        }
        // 2. Cek Modal CPPT Ranap
        if ($('#modalCpptRanap').hasClass('show')) {
            return {
                noRawat: $('#formCpptRanap input[name="no_rawat"]').val(),
                kdDokter: $('#formCpptRanap select[name="nip"]').val() || $('#formCpptRanap input[name="nip"]').val() || '',
                nmPasien: $('#formCpptRanap input[name="nm_pasien"]').val() || '-',
                noRm: $('#formCpptRanap input[name="no_rkm_medis"]').val() || '-'
            };
        }
        // 3. Cek Modal CPPT Rajal (Default)
        if ($('#modalCppt').hasClass('show')) {
            return {
                noRawat: $('#formCpptRajal input[name="no_rawat"]').val(),
                kdDokter: $('#formCpptRajal input[name="nip"]').val() || '',
                nmPasien: $('#formCpptRajal input[name="nm_pasien"]').val() || '-',
                noRm: $('#formCpptRajal input[name="no_rkm_medis"]').val() || '-'
            };
        }

        // Fallback umum
        return {
            noRawat: $('input[name="no_rawat"]').val(),
            kdDokter: $('input[name="nip"]').val() || $('select[name="kd_dokter"]').val() || '',
            nmPasien: $('input[name="nm_pasien"]').val() || '-',
            noRm: $('input[name="no_rkm_medis"]').val() || '-'
        };
    }

    /**
     * Buka RME SATUSEHAT Umum
     * isEmergency: 0 = Reguler (Persetujuan Consent via SS Mobile)
     *              1 = Emergency (Bypass Kode Akses untuk IGD/Kedaruratan)
     */
    function openSatuSehatRme(isEmergency = 0, customNoRawat = null, customKdDokter = null, customNmPasien = null, customNoRm = null) {
        if (currentSsrmeXhr && currentSsrmeXhr.readyState !== 4) {
            currentSsrmeXhr.abort();
        }

        currentShlinkUrl = '';
        isCurrentEmergency = isEmergency ? 1 : 0;

        const ctx = resolveActivePatientContext();
        const noRawat = customNoRawat || ctx.noRawat;
        const kdDokter = customKdDokter || ctx.kdDokter;
        const nmPasien = customNmPasien || ctx.nmPasien;
        const noRm = customNoRm || ctx.noRm;

        currentTargetNoRawat = noRawat;
        currentTargetKdDokter = kdDokter;

        if (!noRawat || noRawat === '-') {
            notifySsrme('Silakan pilih data pasien terlebih dahulu', 'warning');
            return;
        }

        try {
            if (document.activeElement && typeof document.activeElement.blur === 'function') {
                document.activeElement.blur();
            }
        } catch (e) {}

        applySsrmeTheme(isCurrentEmergency);
        $('#ssrme_patient_header').text(`${noRm} - ${nmPasien}`);

        const modalEl = document.getElementById('modalSatuSehatRme');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const ssrmeModal = bootstrap.Modal.getOrCreateInstance(modalEl, {
                backdrop: true,
                keyboard: true,
                focus: false
            });
            ssrmeModal.show();
        } else {
            $('#modalSatuSehatRme').modal({ focus: false }).modal('show');
        }

        showSsrmeView('loading');
        $('#btnSsrmeOpenDirect').removeAttr('data-url');
        $('#btnSsrmeEmergencyOpenDirect').removeAttr('data-url');

        fetchSatuSehatRme(noRawat, kdDokter, isCurrentEmergency);
    }

    function retryOpenSatuSehatRme() {
        if (!currentTargetNoRawat) {
            const ctx = resolveActivePatientContext();
            currentTargetNoRawat = ctx.noRawat;
            currentTargetKdDokter = ctx.kdDokter;
        }
        showSsrmeView('loading');
        fetchSatuSehatRme(currentTargetNoRawat, currentTargetKdDokter, isCurrentEmergency);
    }

    function applySsrmeTheme(isEmergency) {
        const header = $('#ssrme_modal_header');
        const iconBox = $('#ssrme_header_icon_box');
        const icon = $('#ssrme_header_icon');
        const title = $('#modalSatuSehatRmeLabel');
        const badge = $('#ssrme_header_badge');
        const subtag = $('#ssrme_subtitle_tag');
        const loadingSpinner = $('#ssrme_loading_spinner');
        const loadingTitle = $('#ssrme_loading_title');
        const viewerIconBox = $('#ssrme_viewer_icon_box');
        const viewerBadge = $('#ssrme_viewer_badge');
        const btnDirect = $('#btnSsrmeOpenDirect');
        const btnPopup = $('#btnSsrmeOpenPopup');

        if (isEmergency) {
            header.css('background', 'linear-gradient(135deg, #991b1b 0%, #dc2626 100%)');
            iconBox.css('color', '#dc2626');
            icon.removeClass('ti-shield-check').addClass('ti-alert-triangle');
            title.text('SATUSEHAT RME Emergency');
            badge.removeClass('d-none bg-white text-teal').addClass('bg-white text-danger').text('AKSES EMERGENCY (BYPASS)');
            subtag.text('Kondisi Gawat Darurat (IGD/UGD)');

            loadingSpinner.css('color', '#dc2626');
            loadingTitle.text('Menghubungkan ke Akses Emergency SATUSEHAT');

            viewerIconBox.css({ 'background-color': '#fef2f2', 'color': '#dc2626' });
            viewerBadge.removeClass('bg-success-lt text-success border-success-subtle')
                       .addClass('bg-danger-lt text-danger border-danger-subtle')
                       .html('<i class="ti ti-alert-triangle me-1"></i>Otorisasi Akses Emergency Siap');

            btnDirect.css({ 'background-color': '#dc2626', 'border-color': '#dc2626' });
            btnPopup.css({ 'color': '#dc2626', 'border-color': '#dc2626' });
            $('#ssrme_viewer_note').html('<i class="ti ti-alert-triangle me-1 text-danger"></i>Akses Emergency dicatat dalam Audit Trail Kemenkes RI demi keselamatan jiwa pasien.');
        } else {
            header.css('background', 'linear-gradient(135deg, #00877a 0%, #00b4a4 100%)');
            iconBox.css('color', '#00877a');
            icon.removeClass('ti-alert-triangle').addClass('ti-shield-check');
            title.text('SATUSEHAT Rekam Medis');
            badge.removeClass('d-none bg-white text-danger').addClass('bg-white text-teal').text('REGULER');
            subtag.text('Kemenkes RI');

            loadingSpinner.css('color', '#00877a');
            loadingTitle.text('Menghubungkan ke SATUSEHAT');

            viewerIconBox.css({ 'background-color': '#e6f7f5', 'color': '#00877a' });
            viewerBadge.removeClass('bg-danger-lt text-danger border-danger-subtle')
                       .addClass('bg-success-lt text-success border-success-subtle')
                       .html('<i class="ti ti-circle-check me-1"></i>Izin Akses Pasien Terverifikasi');

            btnDirect.css({ 'background-color': '#00877a', 'border-color': '#00877a' });
            btnPopup.css({ 'color': '#00877a', 'border-color': '#00877a' });
            $('#ssrme_viewer_note').html('<i class="ti ti-shield-check me-1 text-success"></i>Tautan viewer resmi aman dan memiliki masa berlaku dari SATUSEHAT. Mode pop-up memudahkan dokter melihat RME bersandingan dengan layar eFKTP.');
        }
    }

    function fetchSatuSehatRme(noRawat, kdDokter, isEmergency = 0) {
        if (currentSsrmeXhr && currentSsrmeXhr.readyState !== 4) {
            currentSsrmeXhr.abort();
        }

        currentSsrmeXhr = $.ajax({
            url: '{{ route("satusehat.rme.open") }}',
            type: 'GET',
            data: {
                no_rawat: noRawat,
                kd_dokter: kdDokter,
                is_emergency: isEmergency
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'ready' && res.shlink_url) {
                    currentShlinkUrl = res.shlink_url;
                    $('#btnSsrmeOpenDirect').attr('data-url', res.shlink_url);
                    showSsrmeView('viewer');
                    notifySsrme('Tautan RME SATUSEHAT siap dibuka', 'success');
                } else if (res.status === 'consent_required') {
                    const targetUrl = res.verification_url || res.shlink_url || '';
                    currentShlinkUrl = targetUrl;

                    const isModeEmergency = Boolean(isEmergency || (res && res.is_emergency));

                    if (isModeEmergency) {
                        $('#btnSsrmeEmergencyOpenDirect').attr('data-url', targetUrl);
                        showSsrmeView('emergency');
                    } else {
                        showSsrmeView('consent');

                        const qrBox = $('#ssrme_qr_container');
                        const noQrActions = $('#ssrme_consent_no_qr_actions');
                        const qrContainer = document.getElementById('ssrme_qrcode');
                        qrContainer.innerHTML = '';

                        if (res.verification_url && typeof QRCode !== 'undefined') {
                            qrBox.removeClass('d-none');
                            noQrActions.addClass('d-none');
                            $('#ssrme_verify_link').attr('href', res.verification_url);
                            ssrmeQrInstance = new QRCode(qrContainer, {
                                text: res.verification_url,
                                width: 130,
                                height: 130,
                                colorDark: "#0f172a",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.M
                            });
                        } else {
                            qrBox.addClass('d-none');
                            noQrActions.removeClass('d-none');
                        }
                    }
                } else {
                    showSsrmeError(res.message || 'Respons tidak valid dari server');
                }
            },
            error: function(xhr, status) {
                if (status === 'abort') {
                    return;
                }
                const res = xhr.responseJSON;
                const msg = res && res.message ? res.message : '';

                if (res && (res.status === 'consent_required' || msg.toLowerCase().includes('consent') || msg.toLowerCase().includes('persetujuan'))) {
                    if (isEmergency) {
                        showSsrmeView('emergency');
                    } else {
                        showSsrmeView('consent');
                        $('#ssrme_qr_container').addClass('d-none');
                        $('#ssrme_consent_no_qr_actions').removeClass('d-none');
                    }
                    return;
                }

                let errorMsg = 'Gagal mengakses RME SATUSEHAT.';
                if (msg) {
                    errorMsg = msg;
                } else if (xhr.statusText) {
                    errorMsg += ' (' + xhr.statusText + ')';
                }
                showSsrmeError(errorMsg);
            }
        });
    }

    function showSsrmeView(view) {
        $('#ssrme_view_loading').addClass('d-none');
        $('#ssrme_view_viewer').addClass('d-none');
        $('#ssrme_view_consent').addClass('d-none');
        $('#ssrme_view_emergency').addClass('d-none');
        $('#ssrme_view_error').addClass('d-none');

        if (view === 'loading') $('#ssrme_view_loading').removeClass('d-none');
        else if (view === 'viewer') $('#ssrme_view_viewer').removeClass('d-none');
        else if (view === 'consent') $('#ssrme_view_consent').removeClass('d-none');
        else if (view === 'emergency') $('#ssrme_view_emergency').removeClass('d-none');
        else if (view === 'error') $('#ssrme_view_error').removeClass('d-none');
    }

    function showSsrmeError(message) {
        $('#ssrme_error_message').text(message);
        showSsrmeView('error');
    }
</script>
@endpush


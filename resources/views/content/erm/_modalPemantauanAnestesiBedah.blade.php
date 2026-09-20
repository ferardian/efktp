<div class="modal modal-blur fade" id="modalPemantauanAnestesiBedah" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-indigo text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-activity-heartbeat me-2 fs-2"></i>
                    Bukti Jenis, Dosis, Teknik Anestesi & Pemantauan Fisiologi Bedah (Sign In & Intra-Op)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- Patient Banner & Smart TTV Sync -->
                <div class="card card-sm mb-3 border-indigo-subtle shadow-sm">
                    <div class="card-body p-2 bg-light-subtle">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-0">No. Rawat / No. RM</label>
                                <div class="fw-bold fs-4 text-indigo" id="pab_display_no_rawat">-</div>
                                <div class="text-muted small" id="pab_display_no_rkm_medis">-</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-0">Pasien</label>
                                <div class="fw-bold fs-4 text-dark" id="pab_display_nm_pasien">-</div>
                                <div class="text-muted small" id="pab_display_umur_jk">-</div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <button type="button" class="btn btn-sm btn-outline-indigo" id="btnSyncTtvPab" title="Tarik data diagnosa dan TTV terbaru">
                                    <i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik Diagnosa & TTV Terkini
                                </button>
                                <div class="small text-muted mt-1" id="pab_ttv_badge">Status: Standby</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nav Tabs -->
                <ul class="nav nav-tabs nav-fill mb-3" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-pab-signin" class="nav-link active fw-bold" data-bs-toggle="tab">
                            <i class="ti ti-checkup-list me-1 text-primary"></i> 1. Bukti Jenis, Dosis, Teknik Anestesi (Sign In)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-pab-observasi" class="nav-link fw-bold" data-bs-toggle="tab">
                            <i class="ti ti-chart-line me-1 text-indigo"></i> 2. Pemantauan Status Fisiologi Pasien Selama & Pasca Tindakan
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- TAB 1: SIGN IN & BUKTI ANESTESI -->
                    <div class="tab-pane active show" id="tab-pab-signin">
                        <form id="formBuktiSignin">
                            <input type="hidden" name="no_rawat" id="signin_no_rawat">
                            <input type="hidden" name="tanggal_lama" id="signin_tanggal_lama">

                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <strong class="text-primary"><i class="ti ti-shield-check me-1"></i> Surgical Safety Checklist — SIGN IN (Sebelum Induksi Anestesi / Tindakan)</strong>
                                    <span class="badge bg-primary-lt">Bukti Pemenuhan Standar Akreditasi</span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label required small fw-bold">Waktu Tindakan / Sign In</label>
                                            <input type="datetime-local" class="form-control form-control-sm" name="tanggal" id="signin_tanggal" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label required small fw-bold">Nama Tindakan Bedah / Prosedur</label>
                                            <input type="text" class="form-control form-control-sm" name="tindakan" id="signin_tindakan" placeholder="Contoh: Hecting Terbuka, Eksisi Kista, Debridement..." required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label required small fw-bold">Diagnosa Pra-Tindakan</label>
                                            <input type="text" class="form-control form-control-sm" name="diagnosa" id="signin_diagnosa" placeholder="Diagnosa pra bedah..." required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required small fw-bold">Dokter Operator / Bedah</label>
                                            <select class="form-select form-select-sm" name="kd_dokter_bedah" id="signin_kd_dokter_bedah" required>
                                                <option value="">-- Pilih Dokter Bedah / Operator --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Dokter Anestesi / Pendamping</label>
                                            <select class="form-select form-select-sm" name="kd_dokter_anestesi" id="signin_kd_dokter_anestesi">
                                                <option value="">-- Pilih Dokter Anestesi / Operator --</option>
                                            </select>
                                        </div>

                                        <!-- Checklist Verifikasi -->
                                        <div class="col-12">
                                            <div class="border rounded p-2 bg-light-subtle">
                                                <div class="row g-2">
                                                    <div class="col-md-4">
                                                        <label class="form-check form-check-inline mb-0">
                                                            <input class="form-check-input" type="checkbox" name="identitas_sesuai" id="signin_identitas_sesuai" value="Ya" checked>
                                                            <span class="form-check-label fw-bold">Verifikasi Identitas Pasien Sesuai</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-check form-check-inline mb-0">
                                                            <input class="form-check-input" type="checkbox" name="informed_consent" id="signin_informed_consent" value="Ya" checked>
                                                            <span class="form-check-label fw-bold">Informed Consent Lengkap & Ditandatangani</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-check form-check-inline mb-0">
                                                            <input class="form-check-input" type="checkbox" name="kesiapan_alat_obat" id="signin_kesiapan_alat_obat" value="Lengkap" checked>
                                                            <span class="form-check-label fw-bold">Kesiapan Alat & Obat Lengkap</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bukti Jenis, Dosis, Teknik Anestesi -->
                                        <div class="col-md-4">
                                            <label class="form-label required small fw-bold text-indigo">Teknik / Rencana Anestesi</label>
                                            <input type="text" class="form-control form-control-sm" name="rencana_anestesi" id="signin_rencana_anestesi" placeholder="Contoh: Lokal Infiltrasi, Blok Lapangan, Sedasi..." required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label required small fw-bold text-indigo">Nama Obat Anestesi</label>
                                            <input type="text" class="form-control form-control-sm" name="obat_anestesi" id="signin_obat_anestesi" placeholder="Contoh: Lidocaine HCl 2%, Bupivacaine, Ketamine..." required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label required small fw-bold text-indigo">Dosis & Rute Pemberian</label>
                                            <input type="text" class="form-control form-control-sm" name="dosis" id="signin_dosis" placeholder="Contoh: 2 amp (4 ml) Infiltrasi lokal, 50 mg IV..." required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Riwayat Alergi Obat / Makanan</label>
                                            <input type="text" class="form-control form-control-sm" name="alergi" id="signin_alergi" value="Tidak Ada" placeholder="Alergi pasien...">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Perawat / Petugas OK Pendamping</label>
                                            <input type="text" class="form-control form-control-sm" id="signin_petugas_display" readonly value="{{ session()->get('pegawai')->nama ?? '-' }}">
                                            <input type="hidden" name="nip_perawat_ok" id="signin_nip_perawat_ok" value="{{ session()->get('pegawai')->nik ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Catatan Khusus Tindakan</label>
                                            <input type="text" class="form-control form-control-sm" name="catatan" id="signin_catatan" placeholder="Catatan tambahan bila ada...">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light py-2 text-end">
                                    <button type="button" class="btn btn-sm btn-primary" id="btnSimpanSignin">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Sign-In & Data Anestesi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: PEMANTAUAN STATUS FISIOLOGI (INTERVAL WAKTU) -->
                    <div class="tab-pane" id="tab-pab-observasi">
                        <form id="formPemantauanFisiologi">
                            <input type="hidden" name="no_rawat" id="obs_no_rawat">

                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <strong class="text-indigo"><i class="ti ti-heart-rate-monitor me-1"></i> Tabel Pemantauan Status Fisiologi Pasien Selama & Pasca Tindakan</strong>
                                    <button type="button" class="btn btn-xs btn-outline-indigo" id="btnAddIntervalRow">
                                        <i class="ti ti-plus me-1"></i> Tambah Baris Waktu
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped align-middle mb-0" id="tableIntervalPemantauan" style="font-size: 12px;">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th style="width: 15%;">Interval Waktu / Menit</th>
                                                    <th style="width: 9%;">Jam</th>
                                                    <th style="width: 20%;">Keluhan Pasien</th>
                                                    <th style="width: 10%;">TD (mmHg)</th>
                                                    <th style="width: 8%;">Nadi (x/m)</th>
                                                    <th style="width: 8%;">RR (x/m)</th>
                                                    <th style="width: 8%;">Suhu (°C)</th>
                                                    <th style="width: 7%;">SpO2 (%)</th>
                                                    <th style="width: 11%;">Keterangan</th>
                                                    <th style="width: 4%;">#</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyIntervalPemantauan">
                                                <!-- Baris di-generate via JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel Verifikasi Dokter & Digital Signature -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-signature me-1"></i> Verifikasi Dokter Penanggung Jawab Tindakan / Anestesi</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label required small fw-bold">Tanggal Verifikasi</label>
                                                    <input type="date" class="form-control form-control-sm" name="tanggal_verifikasi" id="verif_tanggal_verifikasi" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label required small fw-bold">Jam Verifikasi</label>
                                                    <input type="time" class="form-control form-control-sm" name="jam_verifikasi" id="verif_jam_verifikasi" value="{{ date('H:i') }}" required>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <label class="form-label required small fw-bold">Dokter yang Memverifikasi</label>
                                                    <select class="form-select form-select-sm" name="kd_dokter" id="verif_kd_dokter" required>
                                                        <option value="">-- Pilih Dokter Verifikator --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold d-flex justify-content-between align-items-center">
                                                <span>Tanda Tangan Verifikasi Dokter</span>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-outline-primary btn-xs py-0 px-2" id="btnBukaModalTtdVerif" title="Buka TTD di Layar Penuh / HP">
                                                        <i class="ti ti-device-mobile me-1"></i> TTD di Layar / HP
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" id="btnClearSignVerif">
                                                        <i class="ti ti-eraser me-1"></i> Hapus TTD
                                                    </button>
                                                </div>
                                            </label>
                                            <div class="border rounded bg-white text-center position-relative shadow-sm" style="height: 130px;">
                                                <canvas id="canvasSignVerif" class="w-100 h-100" style="cursor: crosshair; touch-action: none;"></canvas>
                                                <div class="position-absolute top-50 start-50 translate-middle text-muted opacity-25 pointer-events-none" id="hintSignVerif">
                                                    <i class="ti ti-signature fs-1 d-block mb-1"></i>
                                                    <small>Sentuh / Goreskan TTD Dokter Disini</small>
                                                </div>
                                            </div>
                                            <input type="hidden" name="tanda_tangan" id="verif_tanda_tangan">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light py-2 text-end">
                                    <button type="button" class="btn btn-sm btn-indigo" id="btnSimpanPemantauan">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Tabel Pemantauan & Verifikasi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-sm btn-danger d-none" id="btnHapusPab">
                        <i class="ti ti-trash me-1"></i> Hapus Seluruh Data
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-indigo d-none" id="btnCetakPab">
                        <i class="ti ti-printer me-1"></i> Cetak Bukti Anestesi & Pemantauan (PDF)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup Canvas TTD Verifikasi Dokter (Khusus Layar Sentuh / HP / Tablet) -->
<div class="modal fade" id="modalTtdPemantauanFullscreen" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title">
                    <i class="ti ti-writing me-1"></i> Tanda Tangan Digital - <span id="modalTtdVerifDokterNama">Dokter Verifikator</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-light">
                <div class="alert alert-info py-2 px-3 small mb-2 text-start d-flex align-items-center">
                    <i class="ti ti-device-mobile fs-2 me-2"></i>
                    <div>
                        <strong>Mode Layar Sentuh / HP / Tablet:</strong> Tanda tangani pada kanvas putih di bawah dengan jari atau stylus pen. Sentuh <em>Terapkan Tanda Tangan</em> setelah selesai.
                    </div>
                </div>
                <div class="signature-modal-wrapper mx-auto" style="width: 100%; max-width: 680px; position: relative;">
                    <canvas id="canvasTtdModalPemantauan" width="680" height="320" style="touch-action: none; background: #ffffff; border: 2px dashed #0d6efd; border-radius: 8px; width: 100%; height: 280px; cursor: crosshair; display: block; box-shadow: inset 0 0 10px rgba(0,0,0,0.03);"></canvas>
                    <div class="position-absolute bottom-0 start-0 w-100 pb-2 text-muted small" style="pointer-events: none; opacity: 0.5;">
                        --- Area Tanda Tangan Digital Dokter ---
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-between bg-white">
                <button type="button" class="btn btn-outline-danger" id="btnClearModalTtdPemantauan">
                    <i class="ti ti-eraser me-1"></i> Hapus Goresan
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-success" id="btnSimpanModalTtdPemantauan">
                        <i class="ti ti-check me-1"></i> Terapkan Tanda Tangan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    (function () {
        var modalEl = document.getElementById('modalPemantauanAnestesiBedah');
        var canvasVerif = document.getElementById('canvasSignVerif');
        var ctxVerif = canvasVerif ? canvasVerif.getContext('2d') : null;
        var isDrawingVerif = false;
        var hasSignatureVerif = false;

        const defaultIntervals = [
            'Sebelum Tindakan',
            'Menit 5',
            'Menit 10',
            'Menit 15',
            'Menit 30',
            'Menit 45',
            'Menit 60',
            'Selesai Tindakan'
        ];

        function resizeCanvasVerif() {
            if (!canvasVerif) return;
            const rect = canvasVerif.parentElement.getBoundingClientRect();
            if (rect.width > 0 && rect.height > 0) {
                canvasVerif.width = rect.width;
                canvasVerif.height = rect.height;
                ctxVerif.lineWidth = 2.5;
                ctxVerif.lineCap = 'round';
                ctxVerif.lineJoin = 'round';
                ctxVerif.strokeStyle = '#0f172a';
            }
        }

        if (canvasVerif) {
            function getPos(e) {
                const rect = canvasVerif.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function startDraw(e) {
                e.preventDefault();
                isDrawingVerif = true;
                $('#hintSignVerif').addClass('d-none');
                const pos = getPos(e);
                ctxVerif.beginPath();
                ctxVerif.moveTo(pos.x, pos.y);
            }

            function moveDraw(e) {
                if (!isDrawingVerif) return;
                e.preventDefault();
                const pos = getPos(e);
                ctxVerif.lineTo(pos.x, pos.y);
                ctxVerif.stroke();
                hasSignatureVerif = true;
            }

            function stopDraw(e) {
                if (!isDrawingVerif) return;
                e.preventDefault();
                isDrawingVerif = false;
            }

            canvasVerif.addEventListener('mousedown', startDraw);
            canvasVerif.addEventListener('mousemove', moveDraw);
            window.addEventListener('mouseup', stopDraw);

            canvasVerif.addEventListener('touchstart', startDraw, { passive: false });
            canvasVerif.addEventListener('touchmove', moveDraw, { passive: false });
            window.addEventListener('touchend', stopDraw, { passive: false });
        }

        $(document).on('click', '#btnClearSignVerif', function () {
            if (ctxVerif && canvasVerif) {
                ctxVerif.clearRect(0, 0, canvasVerif.width, canvasVerif.height);
                hasSignatureVerif = false;
                $('#hintSignVerif').removeClass('d-none');
                $('#verif_tanda_tangan').val('');
            }
        });

        // =========================================================================
        // HANDLER MODAL TTD DIGITAL KHUSUS HP / LAYAR PENUH
        // =========================================================================
        var canvasModalPem = document.getElementById('canvasTtdModalPemantauan');
        var ctxModalPem = canvasModalPem ? canvasModalPem.getContext('2d') : null;
        var isDrawingModalPem = false;
        var hasModalSignaturePem = false;

        if (canvasModalPem && ctxModalPem) {
            ctxModalPem.lineWidth = 3;
            ctxModalPem.lineCap = 'round';
            ctxModalPem.lineJoin = 'round';
            ctxModalPem.strokeStyle = '#0f172a';

            function getModalPemPos(e) {
                const rect = canvasModalPem.getBoundingClientRect();
                const scaleX = canvasModalPem.width / rect.width;
                const scaleY = canvasModalPem.height / rect.height;

                let clientX = e.clientX;
                let clientY = e.clientY;
                if (e.touches && e.touches.length > 0) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                } else if (e.changedTouches && e.changedTouches.length > 0) {
                    clientX = e.changedTouches[0].clientX;
                    clientY = e.changedTouches[0].clientY;
                }

                return {
                    x: (clientX - rect.left) * scaleX,
                    y: (clientY - rect.top) * scaleY
                };
            }

            function startDrawModalPem(e) {
                e.preventDefault();
                isDrawingModalPem = true;
                hasModalSignaturePem = true;
                const pos = getModalPemPos(e);
                ctxModalPem.beginPath();
                ctxModalPem.moveTo(pos.x, pos.y);
            }

            function moveDrawModalPem(e) {
                if (!isDrawingModalPem) return;
                e.preventDefault();
                const pos = getModalPemPos(e);
                ctxModalPem.lineTo(pos.x, pos.y);
                ctxModalPem.stroke();
            }

            function stopDrawModalPem(e) {
                isDrawingModalPem = false;
            }

            canvasModalPem.addEventListener('mousedown', startDrawModalPem);
            canvasModalPem.addEventListener('mousemove', (e) => { if (e.buttons === 1) moveDrawModalPem(e); });
            window.addEventListener('mouseup', stopDrawModalPem);

            canvasModalPem.addEventListener('touchstart', startDrawModalPem, { passive: false });
            canvasModalPem.addEventListener('touchmove', moveDrawModalPem, { passive: false });
            window.addEventListener('touchend', stopDrawModalPem);
        }

        // Buka modal TTD HP / Layar Penuh
        $(document).on('click', '#btnBukaModalTtdVerif', function () {
            const namaDokter = $('#verif_kd_dokter option:selected').text();
            if (namaDokter && !namaDokter.includes('--')) {
                $('#modalTtdVerifDokterNama').text(namaDokter);
            } else {
                $('#modalTtdVerifDokterNama').text('Dokter Verifikator');
            }

            if (canvasModalPem && ctxModalPem) {
                ctxModalPem.clearRect(0, 0, canvasModalPem.width, canvasModalPem.height);
                hasModalSignaturePem = false;

                // Jika canvas utama sudah ada ttd, salin ke modal canvas
                if (hasSignatureVerif && canvasVerif) {
                    ctxModalPem.drawImage(canvasVerif, 0, 0, canvasModalPem.width, canvasModalPem.height);
                    hasModalSignaturePem = true;
                }
            }

            $('#modalTtdPemantauanFullscreen').modal('show');
        });

        // Hapus goresan di modal
        $('#btnClearModalTtdPemantauan').on('click', function () {
            if (canvasModalPem && ctxModalPem) {
                ctxModalPem.clearRect(0, 0, canvasModalPem.width, canvasModalPem.height);
                hasModalSignaturePem = false;
            }
        });

        // Terapkan tanda tangan dari modal ke canvas utama
        $('#btnSimpanModalTtdPemantauan').on('click', function () {
            if (!hasModalSignaturePem) {
                if (typeof showToast === 'function') {
                    showToast('Tanda tangan masih kosong! Silakan tanda tangani terlebih dahulu.', 'warning');
                } else {
                    alert('Tanda tangan masih kosong! Silakan tanda tangani terlebih dahulu.');
                }
                return;
            }

            if (canvasVerif && ctxVerif && canvasModalPem) {
                ctxVerif.clearRect(0, 0, canvasVerif.width, canvasVerif.height);
                ctxVerif.drawImage(canvasModalPem, 0, 0, canvasVerif.width, canvasVerif.height);
                hasSignatureVerif = true;
                $('#hintSignVerif').addClass('d-none');
                $('#verif_tanda_tangan').val(canvasVerif.toDataURL('image/png'));

                $('#modalTtdPemantauanFullscreen').modal('hide');
                if (typeof showToast === 'function') {
                    showToast('Tanda tangan berhasil diterapkan!', 'success');
                }
            }
        });

        $('#modalTtdPemantauanFullscreen').on('hidden.bs.modal', function () {
            if ($('#modalPemantauanAnestesiBedah').hasClass('show')) {
                $('body').addClass('modal-open');
            }
        });

        let dokterOptionsCache = null;

        function populateDokterList(selectedBedah = '', selectedAnestesi = '', selectedVerif = '') {
            function applySelection() {
                if (selectedBedah) $('#signin_kd_dokter_bedah').val(selectedBedah);
                if (selectedAnestesi) $('#signin_kd_dokter_anestesi').val(selectedAnestesi);
                if (selectedVerif) {
                    $('#verif_kd_dokter').val(selectedVerif);
                } else if (selectedBedah && !$('#verif_kd_dokter').val()) {
                    $('#verif_kd_dokter').val(selectedBedah);
                }
            }

            if (dokterOptionsCache) {
                $('#signin_kd_dokter_bedah').html(dokterOptionsCache.bedah);
                $('#signin_kd_dokter_anestesi').html(dokterOptionsCache.anestesi);
                $('#verif_kd_dokter').html(dokterOptionsCache.verif);
                applySelection();
                return;
            }

            $.get(`{{ url('/dokter/get') }}`).done((res) => {
                const list = Array.isArray(res) ? res : (res && res.data ? res.data : []);
                let optsBedah = '<option value="">-- Pilih Dokter Bedah / Operator --</option>';
                let optsAnestesi = '<option value="">-- Pilih Dokter Anestesi --</option>';
                let optsVerif = '<option value="">-- Pilih Dokter Verifikator --</option>';

                if (list && list.length) {
                    list.forEach(d => {
                        if (d.kd_dokter && d.kd_dokter !== '-') {
                            optsBedah += `<option value="${d.kd_dokter}">${d.nm_dokter}</option>`;
                            optsAnestesi += `<option value="${d.kd_dokter}">${d.nm_dokter}</option>`;
                            optsVerif += `<option value="${d.kd_dokter}">${d.nm_dokter}</option>`;
                        }
                    });
                }

                dokterOptionsCache = {
                    bedah: optsBedah,
                    anestesi: optsAnestesi,
                    verif: optsVerif
                };

                $('#signin_kd_dokter_bedah').html(optsBedah);
                $('#signin_kd_dokter_anestesi').html(optsAnestesi);
                $('#verif_kd_dokter').html(optsVerif);

                applySelection();
            }).fail((err) => {
                console.error("Gagal mengambil data dokter:", err);
            });
        }

        function createIntervalRow(index, data = {}) {
            const waktu = data.waktu_menit || (defaultIntervals[index] || `Menit ${index * 15}`);
            const jam = data.jam || '{{ date("H:i") }}';
            const keluhan = data.keluhan || (index === 0 ? 'Nyeri pada luka' : (index === defaultIntervals.length - 1 ? 'Tenang, nyeri berkurang' : 'Tenang'));
            const td = data.td || '';
            const nadi = data.nadi || '';
            const rr = data.rr || '';
            const suhu = data.suhu || '';
            const spo2 = data.spo2 || '99';
            const ket = data.keterangan || (index === 0 ? 'Posisi supine' : '-');

            return `
            <tr data-index="${index}">
                <td>
                    <input type="text" class="form-control form-control-sm fw-bold" name="pemantauan[${index}][waktu_menit]" value="${waktu}" required>
                </td>
                <td>
                    <input type="time" class="form-control form-control-sm text-center" name="pemantauan[${index}][jam]" value="${jam}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="pemantauan[${index}][keluhan]" value="${keluhan}" placeholder="Keluhan pasien...">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="pemantauan[${index}][td]" value="${td}" placeholder="120/80">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="pemantauan[${index}][nadi]" value="${nadi}" placeholder="80">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="pemantauan[${index}][rr]" value="${rr}" placeholder="20">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="pemantauan[${index}][suhu]" value="${suhu}" placeholder="36.5">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="pemantauan[${index}][spo2]" value="${spo2}" placeholder="99">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="pemantauan[${index}][keterangan]" value="${ket}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-xs py-0 px-1 btnDeleteRow" title="Hapus Baris">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>`;
        }

        function renderDefaultIntervalRows() {
            let html = '';
            defaultIntervals.forEach((item, idx) => {
                html += createIntervalRow(idx, { waktu_menit: item });
            });
            $('#tbodyIntervalPemantauan').html(html);
        }

        $(document).on('click', '#btnAddIntervalRow', function () {
            const count = $('#tbodyIntervalPemantauan tr').length;
            const newRow = createIntervalRow(count, { waktu_menit: `Menit ${count * 15}` });
            $('#tbodyIntervalPemantauan').append(newRow);
        });

        $(document).on('click', '.btnDeleteRow', function () {
            $(this).closest('tr').remove();
        });

        // Smart TTV & Diagnosa Sync
        $(document).on('click', '#btnSyncTtvPab', function () {
            const no_rawat = $('#signin_no_rawat').val();
            if (!no_rawat) return;

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menarik data...');

            $.get(`{{ url('/erm/ttv-lookup/latest') }}/${encodeURIComponent(no_rawat)}`)
                .done(function (res) {
                    if (res.success && res.data) {
                        const d = res.data;
                        if (d.diagnosa && d.diagnosa !== '-' && !$('#signin_diagnosa').val()) {
                            $('#signin_diagnosa').val(d.diagnosa);
                        }
                        if (d.alergi && d.alergi !== '-' && $('#signin_alergi').val() === 'Tidak Ada') {
                            $('#signin_alergi').val(d.alergi);
                        }
                        
                        // Isi baris pertama (Sebelum Tindakan) jika masih kosong
                        const firstRow = $('#tbodyIntervalPemantauan tr').first();
                        if (firstRow.length) {
                            if (d.td && !firstRow.find('input[name*="[td]"]').val()) firstRow.find('input[name*="[td]"]').val(d.td);
                            if (d.nadi && !firstRow.find('input[name*="[nadi]"]').val()) firstRow.find('input[name*="[nadi]"]').val(d.nadi);
                            if (d.pernapasan && !firstRow.find('input[name*="[rr]"]').val()) firstRow.find('input[name*="[rr]"]').val(d.pernapasan);
                            if (d.suhu && !firstRow.find('input[name*="[suhu]"]').val()) firstRow.find('input[name*="[suhu]"]').val(d.suhu);
                            if (d.io2 && !firstRow.find('input[name*="[spo2]"]').val()) firstRow.find('input[name*="[spo2]"]').val(d.io2);
                        }

                        $('#pab_ttv_badge').html(`<span class="text-success"><i class="ti ti-check"></i> Disinkron: TD ${d.td || '-'} | S ${d.suhu || '-'}°C | N ${d.nadi || '-'}x/m</span>`);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Terkini Ditarik',
                                text: `Diagnosa: ${d.diagnosa || '-'}, TTV terisi ke observasi awal`,
                                timer: 1600,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        $('#pab_ttv_badge').text('Tidak ada riwayat TTV tersimpan');
                    }
                })
                .always(() => {
                    btn.prop('disabled', false).html('<i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik Diagnosa & TTV Terkini');
                });
        });

        // Global opener function
        window.bukaPemantauanAnestesiBedah = function (no_rawat) {
            $('#formBuktiSignin')[0].reset();
            $('#formPemantauanFisiologi')[0].reset();
            $('#signin_no_rawat').val(no_rawat);
            $('#obs_no_rawat').val(no_rawat);
            $('#pab_display_no_rawat').text(no_rawat);
            $('#pab_display_nm_pasien').text('-');
            $('#pab_display_no_rkm_medis').text('-');
            $('#pab_display_umur_jk').text('-');
            $('#pab_ttv_badge').text('Status: Standby');

            // Default date/time
            const now = new Date();
            const nowIso = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
            $('#signin_tanggal').val(nowIso);
            $('#verif_tanggal_verifikasi').val(now.toISOString().slice(0, 10));
            $('#verif_jam_verifikasi').val(now.toTimeString().slice(0, 5));

            $('#btnClearSignVerif').trigger('click');
            $('#btnCetakPab').addClass('d-none');
            $('#btnHapusPab').addClass('d-none');

            populateDokterList();
            renderDefaultIntervalRows();

            // Open Modal
            if (typeof $ !== 'undefined' && $('#modalPemantauanAnestesiBedah').modal) {
                $('#modalPemantauanAnestesiBedah').modal('show');
            } else if (typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }

            // Get Patient Data
            if (typeof getRegDetail === 'function') {
                getRegDetail(no_rawat).done((res) => {
                    if (res && res.pasien) {
                        $('#pab_display_nm_pasien').text(res.pasien.nm_pasien);
                        $('#pab_display_no_rkm_medis').text(`RM: ${res.no_rkm_medis || '-'}`);
                        $('#pab_display_umur_jk').text(`${res.umurdaftar || '-'} ${res.sttsumur || ''} (${res.pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan'})`);
                        populateDokterList(res.kd_dokter, '', res.kd_dokter);
                    }
                });
            } else {
                $.get(`{{ url('/registrasi/get') }}/${encodeURIComponent(no_rawat)}`).done((res) => {
                    if (res && res.pasien) {
                        $('#pab_display_nm_pasien').text(res.pasien.nm_pasien);
                        $('#pab_display_no_rkm_medis').text(`RM: ${res.no_rkm_medis || '-'}`);
                        $('#pab_display_umur_jk').text(`${res.umurdaftar || '-'} ${res.sttsumur || ''} (${res.pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan'})`);
                        populateDokterList(res.kd_dokter, '', res.kd_dokter);
                    }
                });
            }

            setTimeout(() => {
                resizeCanvasVerif();
                loadDataPemantauanAnestesi(no_rawat);
            }, 300);
        };

        function loadDataPemantauanAnestesi(no_rawat) {
            $.get(`{{ url('/erm/pemantauan-anestesi-bedah/get') }}/${encodeURIComponent(no_rawat)}`)
                .done(function (res) {
                    if (res.success) {
                        // Populate Tab 1 (Sign In)
                        if (res.signin) {
                            const s = res.signin;
                            $('#signin_tanggal').val(s.tanggal ? s.tanggal.replace(' ', 'T').substring(0, 16) : '');
                            $('#signin_tanggal_lama').val(s.tanggal);
                            $('#signin_tindakan').val(s.tindakan);
                            $('#signin_diagnosa').val(s.diagnosa);
                            $('#signin_rencana_anestesi').val(s.rencana_anestesi);
                            $('#signin_obat_anestesi').val(s.obat_anestesi);
                            $('#signin_dosis').val(s.dosis);
                            $('#signin_alergi').val(s.alergi);
                            $('#signin_catatan').val(s.catatan);

                            $('#signin_identitas_sesuai').prop('checked', s.identitas_sesuai === 'Ya');
                            $('#signin_informed_consent').prop('checked', s.informed_consent === 'Ya');
                            $('#signin_kesiapan_alat_obat').prop('checked', s.kesiapan_alat_obat === 'Lengkap');

                            if (s.petugas_ok && s.petugas_ok.nama) {
                                $('#signin_petugas_display').val(s.petugas_ok.nama);
                            }
                            populateDokterList(s.kd_dokter_bedah, s.kd_dokter_anestesi, res.verifikasi?.kd_dokter || s.kd_dokter_bedah);
                            $('#btnCetakPab').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pemantauan-anestesi-bedah/print') }}/${encodeURIComponent(no_rawat)}', '_blank')`);
                            $('#btnHapusPab').removeClass('d-none');
                        }

                        // Populate Tab 2 (Tabel Interval Pemantauan)
                        if (res.pemantauan && res.pemantauan.length > 0) {
                            let rowsHtml = '';
                            res.pemantauan.forEach((row, i) => {
                                rowsHtml += createIntervalRow(i, row);
                            });
                            $('#tbodyIntervalPemantauan').html(rowsHtml);
                        }

                        // Populate Verifikasi Dokter
                        if (res.verifikasi) {
                            const v = res.verifikasi;
                            $('#verif_tanggal_verifikasi').val(v.tanggal_verifikasi);
                            $('#verif_jam_verifikasi').val(v.jam_verifikasi);
                            if (v.kd_dokter) $('#verif_kd_dokter').val(v.kd_dokter);

                            if (v.tanda_tangan) {
                                const img = new Image();
                                img.onload = () => {
                                    ctxVerif.drawImage(img, 0, 0, canvasVerif.width, canvasVerif.height);
                                    $('#hintSignVerif').addClass('d-none');
                                    hasSignatureVerif = true;
                                };
                                img.src = `{{ url('/') }}/${v.tanda_tangan}`;
                            }
                            $('#btnCetakPab').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pemantauan-anestesi-bedah/print') }}/${encodeURIComponent(no_rawat)}', '_blank')`);
                            $('#btnHapusPab').removeClass('d-none');
                        }
                    }
                });
        }

        // Simpan Tab 1 (Sign In)
        $(document).on('click', '#btnSimpanSignin', function () {
            if (!$('#formBuktiSignin')[0].checkValidity()) {
                $('#formBuktiSignin')[0].reportValidity();
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: '{{ url('/erm/pemantauan-anestesi-bedah/store-signin') }}',
                type: 'POST',
                data: $('#formBuktiSignin').serialize() + '&_token={{ csrf_token() }}',
                success: function (res) {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tersimpan!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            alert(res.message);
                        }
                        $('#signin_tanggal_lama').val(res.data.tanggal);
                        $('#btnCetakPab').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pemantauan-anestesi-bedah/print') }}/${encodeURIComponent($('#signin_no_rawat').val())}', '_blank')`);
                        $('#btnHapusPab').removeClass('d-none');
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal menyimpan Sign In');
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Sign-In & Data Anestesi');
                }
            });
        });

        // Simpan Tab 2 (Tabel Pemantauan & Verifikasi)
        $(document).on('click', '#btnSimpanPemantauan', function () {
            if (!$('#formPemantauanFisiologi')[0].checkValidity()) {
                $('#formPemantauanFisiologi')[0].reportValidity();
                return;
            }

            if (hasSignatureVerif && canvasVerif) {
                $('#verif_tanda_tangan').val(canvasVerif.toDataURL('image/png'));
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: '{{ url('/erm/pemantauan-anestesi-bedah/store-pemantauan') }}',
                type: 'POST',
                data: $('#formPemantauanFisiologi').serialize() + '&_token={{ csrf_token() }}',
                success: function (res) {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tersimpan!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            alert(res.message);
                        }
                        $('#btnCetakPab').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pemantauan-anestesi-bedah/print') }}/${encodeURIComponent($('#obs_no_rawat').val())}', '_blank')`);
                        $('#btnHapusPab').removeClass('d-none');
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Gagal menyimpan tabel pemantauan');
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Tabel Pemantauan & Verifikasi');
                }
            });
        });

        // Hapus
        $(document).on('click', '#btnHapusPab', function () {
            const no_rawat = $('#signin_no_rawat').val();
            if (!no_rawat) return;

            const runDelete = () => {
                $.post('{{ url('/erm/pemantauan-anestesi-bedah/delete') }}', {
                    no_rawat: no_rawat,
                    _token: '{{ csrf_token() }}'
                }).done(function (res) {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Dihapus!', res.message, 'success');
                        }
                        if (typeof $ !== 'undefined' && $('#modalPemantauanAnestesiBedah').modal) {
                            $('#modalPemantauanAnestesiBedah').modal('hide');
                        } else if (typeof bootstrap !== 'undefined') {
                            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                        }
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Data Pemantauan Anestesi?',
                    text: 'Seluruh data Sign-In & Observasi fisiologi pasien ini akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((r) => { if (r.isConfirmed) runDelete(); });
            } else if (confirm('Hapus seluruh data pemantauan anestesi pasien ini?')) {
                runDelete();
            }
        });
    })();
</script>
@endpush

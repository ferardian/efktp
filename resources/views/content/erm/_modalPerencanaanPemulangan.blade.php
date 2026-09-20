<div class="modal modal-blur fade" id="modalPerencanaanPemulangan" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-teal text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-door-exit me-2 fs-2"></i>
                    Perencanaan Pemulangan Pasien (Discharge Planning)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- Patient Banner & Smart TTV Sync -->
                <div class="card card-sm mb-3 border-teal-subtle shadow-sm">
                    <div class="card-body p-2 bg-light-subtle">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-0">No. Rawat / No. RM</label>
                                <div class="fw-bold fs-4 text-teal" id="dp_display_no_rawat">-</div>
                                <div class="text-muted small" id="dp_display_no_rkm_medis">-</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small mb-0">Pasien</label>
                                <div class="fw-bold fs-4 text-dark" id="dp_display_nm_pasien">-</div>
                                <div class="text-muted small" id="dp_display_umur_jk">-</div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <button type="button" class="btn btn-sm btn-outline-teal" id="btnSyncTtvDischarge" title="Tarik data diagnosa dan TTV terbaru">
                                    <i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik Diagnosa & TTV Terkini
                                </button>
                                <div class="small text-muted mt-1" id="dp_ttv_badge">Status: Standby</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Discharge Planning -->
                <form id="formPerencanaanPemulangan">
                    <input type="hidden" name="no_rawat" id="dp_no_rawat">
                    <input type="hidden" name="nip" id="dp_nip" value="{{ session()->get('pegawai')->nik ?? '-' }}">

                    <!-- Section 1: Rencana & Diagnosa -->
                    <div class="card mb-3 shadow-sm border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Estimasi Rencana Tanggal Pulang</label>
                                    <input type="date" class="form-control form-control-sm" name="rencana_pulang" id="dp_rencana_pulang" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Alasan Masuk Rawat Inap</label>
                                    <input type="text" class="form-control form-control-sm" name="alasan_masuk" id="dp_alasan_masuk" placeholder="Keluhan utama / indikasi ranap..." required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required small fw-bold">Diagnosa Medis Terakhir</label>
                                    <input type="text" class="form-control form-control-sm" name="diagnosa_medis" id="dp_diagnosa_medis" placeholder="Diagnosa utama..." required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: 14 Parameter Skrining Kebutuhan Pemulangan -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                            <strong class="text-teal"><i class="ti ti-clipboard-list me-1"></i> Parameter Kebutuhan & Kesiapan Pasien Pulang</strong>
                            <span class="badge bg-teal-lt small">Lengkapi penilaian kondisi fisik dan sosial</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-bordered align-middle mb-0" style="font-size: 12.5px;">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 40%;">Parameter Asesmen</th>
                                            <th style="width: 20%;">Pilihan</th>
                                            <th style="width: 35%;">Keterangan / Rencana Tindak Lanjut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- 1 -->
                                        <tr>
                                            <td class="text-center fw-bold">1</td>
                                            <td>Pengaruh rawat inap terhadap pasien & keluarga?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="pengaruh_ri_pasien_dan_keluarga" id="dp_pengaruh_ri_pasien_dan_keluarga">
                                                    <option value="Tidak" selected>Tidak Ada Pengaruh</option>
                                                    <option value="Ya">Ya (Ada Hambatan/Beban)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_pengaruh_ri_pasien_dan_keluarga" id="dp_keterangan_pengaruh_ri_pasien_dan_keluarga" placeholder="Keterangan..."></td>
                                        </tr>
                                        <!-- 2 -->
                                        <tr>
                                            <td class="text-center fw-bold">2</td>
                                            <td>Pengaruh rawat inap terhadap pekerjaan / sekolah?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="pengaruh_ri_pekerjaan_sekolah" id="dp_pengaruh_ri_pekerjaan_sekolah">
                                                    <option value="Tidak" selected>Tidak Ada Pengaruh</option>
                                                    <option value="Ya">Ya (Izin/Kendala)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_pengaruh_ri_pekerjaan_sekolah" id="dp_keterangan_pengaruh_ri_pekerjaan_sekolah" placeholder="Keterangan..."></td>
                                        </tr>
                                        <!-- 3 -->
                                        <tr>
                                            <td class="text-center fw-bold">3</td>
                                            <td>Pengaruh rawat inap terhadap masalah keuangan?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="pengaruh_ri_keuangan" id="dp_pengaruh_ri_keuangan">
                                                    <option value="Tidak" selected>Tidak Ada Kendala</option>
                                                    <option value="Ya">Ya (Terdapat Kendala)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_pengaruh_ri_keuangan" id="dp_keterangan_pengaruh_ri_keuangan" placeholder="Keterangan..."></td>
                                        </tr>
                                        <!-- 4 -->
                                        <tr>
                                            <td class="text-center fw-bold">4</td>
                                            <td>Antisipasi masalah / kendala saat tiba di rumah?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="antisipasi_masalah_saat_pulang" id="dp_antisipasi_masalah_saat_pulang">
                                                    <option value="Tidak" selected>Tidak Ada</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_antisipasi_masalah_saat_pulang" id="dp_keterangan_antisipasi_masalah_saat_pulang" placeholder="Keterangan antisipasi..."></td>
                                        </tr>
                                        <!-- 5 -->
                                        <tr>
                                            <td class="text-center fw-bold">5</td>
                                            <td>Bantuan khusus yang diperlukan pasien di rumah?</td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="bantuan_diperlukan_dalam" id="dp_bantuan_diperlukan_dalam" value="Minum Obat & Mobilitas" placeholder="Contoh: Minum Obat, Mandi, dll">
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_bantuan_diperlukan_dalam" id="dp_keterangan_bantuan_diperlukan_dalam" placeholder="Keterangan bantuan..."></td>
                                        </tr>
                                        <!-- 6 -->
                                        <tr>
                                            <td class="text-center fw-bold">6</td>
                                            <td>Adakah anggota keluarga yang membantu keperluan pasien?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="adakah_yang_membantu_keperluan" id="dp_adakah_yang_membantu_keperluan">
                                                    <option value="Ada" selected>Ada Pendamping</option>
                                                    <option value="Tidak">Tidak Ada</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_adakah_yang_membantu_keperluan" id="dp_keterangan_adakah_yang_membantu_keperluan" placeholder="Keluarga yang mendampingi..."></td>
                                        </tr>
                                        <!-- 7 -->
                                        <tr>
                                            <td class="text-center fw-bold">7</td>
                                            <td>Apakah pasien tinggal sendiri di rumah?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="pasien_tinggal_sendiri" id="dp_pasien_tinggal_sendiri">
                                                    <option value="Tidak" selected>Tidak (Bersama Keluarga)</option>
                                                    <option value="Ya">Ya (Tinggal Sendiri)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_pasien_tinggal_sendiri" id="dp_keterangan_pasien_tinggal_sendiri" placeholder="Keterangan..."></td>
                                        </tr>
                                        <!-- 8 -->
                                        <tr>
                                            <td class="text-center fw-bold">8</td>
                                            <td>Pasien memerlukan peralatan medis di rumah?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="pasien_menggunakan_peralatan_medis" id="dp_pasien_menggunakan_peralatan_medis">
                                                    <option value="Tidak" selected>Tidak Memerlukan</option>
                                                    <option value="Ya">Ya (Tabung Oksigen, Suction, dll)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_pasien_menggunakan_peralatan_medis" id="dp_keterangan_pasien_menggunakan_peralatan_medis" placeholder="Rincian alat medis..."></td>
                                        </tr>
                                        <!-- 9 -->
                                        <tr>
                                            <td class="text-center fw-bold">9</td>
                                            <td>Pasien memerlukan alat bantu jalan / fisik?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="pasien_memerlukan_alat_bantu" id="dp_pasien_memerlukan_alat_bantu">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya (Kursi Roda / Kruk / Walker)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_pasien_memerlukan_alat_bantu" id="dp_keterangan_pasien_memerlukan_alat_bantu" placeholder="Jenis alat bantu..."></td>
                                        </tr>
                                        <!-- 10 -->
                                        <tr>
                                            <td class="text-center fw-bold">10</td>
                                            <td>Memerlukan perawatan luka / khusus di rumah?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="memerlukan_perawatan_khusus" id="dp_memerlukan_perawatan_khusus">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya (Rawat Luka / Kateter / NGT)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_memerlukan_perawatan_khusus" id="dp_keterangan_memerlukan_perawatan_khusus" placeholder="Instruksi perawatan khusus..."></td>
                                        </tr>
                                        <!-- 11 -->
                                        <tr>
                                            <td class="text-center fw-bold">11</td>
                                            <td>Bermasalah dalam pemenuhan nutrisi / aktivitas harian?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="bermasalah_memenuhi_kebutuhan" id="dp_bermasalah_memenuhi_kebutuhan">
                                                    <option value="Tidak" selected>Tidak Bermasalah</option>
                                                    <option value="Ya">Ya Bermasalah</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_bermasalah_memenuhi_kebutuhan" id="dp_keterangan_bermasalah_memenuhi_kebutuhan" placeholder="Keterangan..."></td>
                                        </tr>
                                        <!-- 12 -->
                                        <tr>
                                            <td class="text-center fw-bold">12</td>
                                            <td>Memiliki nyeri kronis atau berkepanjangan?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="memiliki_nyeri_kronis" id="dp_memiliki_nyeri_kronis">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_memiliki_nyeri_kronis" id="dp_keterangan_memiliki_nyeri_kronis" placeholder="Manajemen nyeri..."></td>
                                        </tr>
                                        <!-- 13 -->
                                        <tr>
                                            <td class="text-center fw-bold">13</td>
                                            <td>Memerlukan edukasi kesehatan lanjutan?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="memerlukan_edukasi_kesehatan" id="dp_memerlukan_edukasi_kesehatan">
                                                    <option value="Ya" selected>Ya (Diet, Aktivitas & Obat)</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_memerlukan_edukasi_kesehatan" id="dp_keterangan_memerlukan_edukasi_kesehatan" value="Edukasi jadwal kontrol dan minum obat teratur" placeholder="Materi edukasi..."></td>
                                        </tr>
                                        <!-- 14 -->
                                        <tr>
                                            <td class="text-center fw-bold">14</td>
                                            <td>Memerlukan keterampilan khusus oleh keluarga?</td>
                                            <td>
                                                <select class="form-select form-select-sm" name="memerlukan_keterampilkan_khusus" id="dp_memerlukan_keterampilkan_khusus">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm" name="keterangan_memerlukan_keterampilkan_khusus" id="dp_keterangan_memerlukan_keterampilkan_khusus" placeholder="Keterampilan yang diajarkan..."></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Konfirmasi Saksi / Keluarga & Tanda Tangan Digital -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header py-2 bg-light">
                            <strong class="text-secondary"><i class="ti ti-signature me-1"></i> Konfirmasi Edukasi Pemulangan & Tanda Tangan Saksi / Keluarga</strong>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required small fw-bold">Nama Pasien / Keluarga yang Menerima Penjelasan</label>
                                    <input type="text" class="form-control form-control-sm" name="nama_pasien_keluarga" id="dp_nama_pasien_keluarga" placeholder="Nama lengkap penerima edukasi..." required>
                                    
                                    <div class="mt-3">
                                        <label class="form-label small fw-bold">Petugas / Case Manager yang Mengkaji</label>
                                        <input type="text" class="form-control form-control-sm" id="dp_petugas_display" readonly value="{{ session()->get('pegawai')->nama ?? '-' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold d-flex justify-content-between align-items-center">
                                        <span>Tanda Tangan Saksi / Keluarga Pasien</span>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-outline-primary btn-xs py-0 px-2" id="btnBukaModalTtdDischarge" title="Buka TTD di Layar Penuh / HP">
                                                <i class="ti ti-device-mobile me-1"></i> TTD di Layar / HP
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" id="btnClearSignDischarge">
                                                <i class="ti ti-eraser me-1"></i> Hapus TTD
                                            </button>
                                        </div>
                                    </label>
                                    <div class="border rounded bg-white text-center position-relative shadow-sm" style="height: 140px;">
                                        <canvas id="canvasSignDischarge" class="w-100 h-100" style="cursor: crosshair; touch-action: none;"></canvas>
                                        <div class="position-absolute top-50 start-50 translate-middle text-muted opacity-25 pointer-events-none" id="hintSignDischarge">
                                            <i class="ti ti-signature fs-1 d-block mb-1"></i>
                                            <small>Sentuh / Goreskan Tanda Tangan Disini</small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="ttd_saksi" id="dp_ttd_saksi">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light py-2 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-sm btn-danger d-none" id="btnHapusDischarge">
                        <i class="ti ti-trash me-1"></i> Hapus
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-teal d-none" id="btnCetakDischarge">
                        <i class="ti ti-printer me-1"></i> Cetak Discharge Planning
                    </button>
                    <button type="button" class="btn btn-sm btn-teal" id="btnSimpanDischarge">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Discharge Planning
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Popup Canvas TTD Saksi / Keluarga (Khusus Layar Sentuh / HP / Tablet) -->
<div class="modal fade" id="modalTtdDischargeFullscreen" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-teal text-white py-2">
                <h5 class="modal-title">
                    <i class="ti ti-writing me-1"></i> Tanda Tangan Digital - <span id="modalTtdDischargeNama">Saksi / Keluarga Pasien</span>
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
                    <canvas id="canvasTtdModalDischarge" width="680" height="320" style="touch-action: none; background: #ffffff; border: 2px dashed #0ca678; border-radius: 8px; width: 100%; height: 280px; cursor: crosshair; display: block; box-shadow: inset 0 0 10px rgba(0,0,0,0.03);"></canvas>
                    <div class="position-absolute bottom-0 start-0 w-100 pb-2 text-muted small" style="pointer-events: none; opacity: 0.5;">
                        --- Area Tanda Tangan Digital Saksi / Keluarga Pasien ---
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-between bg-white">
                <button type="button" class="btn btn-outline-danger" id="btnClearModalTtdDischarge">
                    <i class="ti ti-eraser me-1"></i> Hapus Goresan
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-success" id="btnSimpanModalTtdDischarge">
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
        var modalEl = document.getElementById('modalPerencanaanPemulangan');
        var canvasDischarge = document.getElementById('canvasSignDischarge');
        var ctxDischarge = canvasDischarge ? canvasDischarge.getContext('2d') : null;
        var isDrawingDischarge = false;
        var hasSignatureDischarge = false;

        function resizeCanvasDischarge() {
            if (!canvasDischarge) return;
            const rect = canvasDischarge.parentElement.getBoundingClientRect();
            if (rect.width > 0 && rect.height > 0) {
                canvasDischarge.width = rect.width;
                canvasDischarge.height = rect.height;
                ctxDischarge.lineWidth = 2.5;
                ctxDischarge.lineCap = 'round';
                ctxDischarge.lineJoin = 'round';
                ctxDischarge.strokeStyle = '#1e293b';
            }
        }

        if (canvasDischarge) {
            function getPos(e) {
                const rect = canvasDischarge.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function startDraw(e) {
                e.preventDefault();
                isDrawingDischarge = true;
                $('#hintSignDischarge').addClass('d-none');
                const pos = getPos(e);
                ctxDischarge.beginPath();
                ctxDischarge.moveTo(pos.x, pos.y);
            }

            function moveDraw(e) {
                if (!isDrawingDischarge) return;
                e.preventDefault();
                const pos = getPos(e);
                ctxDischarge.lineTo(pos.x, pos.y);
                ctxDischarge.stroke();
                hasSignatureDischarge = true;
            }

            function stopDraw(e) {
                if (!isDrawingDischarge) return;
                e.preventDefault();
                isDrawingDischarge = false;
            }

            canvasDischarge.addEventListener('mousedown', startDraw);
            canvasDischarge.addEventListener('mousemove', moveDraw);
            window.addEventListener('mouseup', stopDraw);

            canvasDischarge.addEventListener('touchstart', startDraw, { passive: false });
            canvasDischarge.addEventListener('touchmove', moveDraw, { passive: false });
            window.addEventListener('touchend', stopDraw, { passive: false });
        }

        $(document).on('click', '#btnClearSignDischarge', function () {
            if (ctxDischarge && canvasDischarge) {
                ctxDischarge.clearRect(0, 0, canvasDischarge.width, canvasDischarge.height);
                hasSignatureDischarge = false;
                $('#hintSignDischarge').removeClass('d-none');
                $('#dp_ttd_saksi').val('');
            }
        });

        // =========================================================================
        // HANDLER MODAL TTD DIGITAL KHUSUS HP / LAYAR PENUH
        // =========================================================================
        var canvasModalDisc = document.getElementById('canvasTtdModalDischarge');
        var ctxModalDisc = canvasModalDisc ? canvasModalDisc.getContext('2d') : null;
        var isDrawingModalDisc = false;
        var hasModalSignatureDisc = false;

        if (canvasModalDisc && ctxModalDisc) {
            ctxModalDisc.lineWidth = 3;
            ctxModalDisc.lineCap = 'round';
            ctxModalDisc.lineJoin = 'round';
            ctxModalDisc.strokeStyle = '#1e293b';

            function getModalDiscPos(e) {
                const rect = canvasModalDisc.getBoundingClientRect();
                const scaleX = canvasModalDisc.width / rect.width;
                const scaleY = canvasModalDisc.height / rect.height;

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

            function startDrawModalDisc(e) {
                e.preventDefault();
                isDrawingModalDisc = true;
                hasModalSignatureDisc = true;
                const pos = getModalDiscPos(e);
                ctxModalDisc.beginPath();
                ctxModalDisc.moveTo(pos.x, pos.y);
            }

            function moveDrawModalDisc(e) {
                if (!isDrawingModalDisc) return;
                e.preventDefault();
                const pos = getModalDiscPos(e);
                ctxModalDisc.lineTo(pos.x, pos.y);
                ctxModalDisc.stroke();
            }

            function stopDrawModalDisc(e) {
                isDrawingModalDisc = false;
            }

            canvasModalDisc.addEventListener('mousedown', startDrawModalDisc);
            canvasModalDisc.addEventListener('mousemove', (e) => { if (e.buttons === 1) moveDrawModalDisc(e); });
            window.addEventListener('mouseup', stopDrawModalDisc);

            canvasModalDisc.addEventListener('touchstart', startDrawModalDisc, { passive: false });
            canvasModalDisc.addEventListener('touchmove', moveDrawModalDisc, { passive: false });
            window.addEventListener('touchend', stopDrawModalDisc);
        }

        // Buka modal TTD HP / Layar Penuh
        $(document).on('click', '#btnBukaModalTtdDischarge', function () {
            const namaKeluarga = $('#dp_nama_pasien_keluarga').val().trim();
            if (namaKeluarga) {
                $('#modalTtdDischargeNama').text(namaKeluarga);
            } else {
                $('#modalTtdDischargeNama').text('Saksi / Keluarga Pasien');
            }

            if (canvasModalDisc && ctxModalDisc) {
                ctxModalDisc.clearRect(0, 0, canvasModalDisc.width, canvasModalDisc.height);
                hasModalSignatureDisc = false;

                // Jika canvas utama sudah ada ttd, salin ke modal canvas
                if (hasSignatureDischarge && canvasDischarge) {
                    ctxModalDisc.drawImage(canvasDischarge, 0, 0, canvasModalDisc.width, canvasModalDisc.height);
                    hasModalSignatureDisc = true;
                }
            }

            $('#modalTtdDischargeFullscreen').modal('show');
        });

        // Hapus goresan di modal
        $('#btnClearModalTtdDischarge').on('click', function () {
            if (canvasModalDisc && ctxModalDisc) {
                ctxModalDisc.clearRect(0, 0, canvasModalDisc.width, canvasModalDisc.height);
                hasModalSignatureDisc = false;
            }
        });

        // Terapkan tanda tangan dari modal ke canvas utama
        $('#btnSimpanModalTtdDischarge').on('click', function () {
            if (!hasModalSignatureDisc) {
                if (typeof showToast === 'function') {
                    showToast('Tanda tangan masih kosong! Silakan tanda tangani terlebih dahulu.', 'warning');
                } else {
                    alert('Tanda tangan masih kosong! Silakan tanda tangani terlebih dahulu.');
                }
                return;
            }

            if (canvasDischarge && ctxDischarge && canvasModalDisc) {
                ctxDischarge.clearRect(0, 0, canvasDischarge.width, canvasDischarge.height);
                ctxDischarge.drawImage(canvasModalDisc, 0, 0, canvasDischarge.width, canvasDischarge.height);
                hasSignatureDischarge = true;
                $('#hintSignDischarge').addClass('d-none');
                $('#dp_ttd_saksi').val(canvasDischarge.toDataURL('image/png'));

                $('#modalTtdDischargeFullscreen').modal('hide');
                if (typeof showToast === 'function') {
                    showToast('Tanda tangan berhasil diterapkan!', 'success');
                }
            }
        });

        $('#modalTtdDischargeFullscreen').on('hidden.bs.modal', function () {
            if ($('#modalPerencanaanPemulangan').hasClass('show')) {
                $('body').addClass('modal-open');
            }
        });

        // Smart TTV & Diagnosa Sync
        $(document).on('click', '#btnSyncTtvDischarge', function () {
            const no_rawat = $('#dp_no_rawat').val();
            if (!no_rawat) return;

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menarik data...');

            $.get(`{{ url('/erm/ttv-lookup/latest') }}/${encodeURIComponent(no_rawat)}`)
                .done(function (res) {
                    if (res.success && res.data) {
                        const d = res.data;
                        if (d.diagnosa && d.diagnosa !== '-' && !$('#dp_diagnosa_medis').val()) {
                            $('#dp_diagnosa_medis').val(d.diagnosa);
                        }
                        if (d.keluhan && d.keluhan !== '-' && !$('#dp_alasan_masuk').val()) {
                            $('#dp_alasan_masuk').val(d.keluhan);
                        }
                        $('#dp_ttv_badge').html(`<span class="text-success"><i class="ti ti-check"></i> Disinkron: TD ${d.td || '-'} | S ${d.suhu || '-'}°C | N ${d.nadi || '-'}x/m</span>`);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Terkini Ditarik',
                                text: `Diagnosa: ${d.diagnosa || '-'}, Keluhan: ${d.keluhan || '-'}`,
                                timer: 1600,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        $('#dp_ttv_badge').text('Tidak ada riwayat TTV tersimpan');
                    }
                })
                .always(() => {
                    btn.prop('disabled', false).html('<i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik Diagnosa & TTV Terkini');
                });
        });

        // Global opener function
        window.bukaPerencanaanPemulangan = function (no_rawat) {
            $('#formPerencanaanPemulangan')[0].reset();
            $('#dp_no_rawat').val(no_rawat);
            $('#dp_nip').val('{{ session()->get('pegawai')->nik ?? '-' }}');
            $('#dp_petugas_display').val('{{ session()->get('pegawai')->nama ?? '-' }}');
            $('#dp_display_no_rawat').text(no_rawat);
            $('#dp_display_nm_pasien').text('-');
            $('#dp_display_no_rkm_medis').text('-');
            $('#dp_display_umur_jk').text('-');
            $('#dp_ttv_badge').text('Status: Standby');

            $('#btnClearSignDischarge').trigger('click');
            $('#btnCetakDischarge').addClass('d-none');
            $('#btnHapusDischarge').addClass('d-none');

            // Show modal (support both jQuery and bootstrap 5)
            if (typeof $ !== 'undefined' && $('#modalPerencanaanPemulangan').modal) {
                $('#modalPerencanaanPemulangan').modal('show');
            } else if (typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }

            // Fetch patient information
            if (typeof getRegDetail === 'function') {
                getRegDetail(no_rawat).done((res) => {
                    if (res && res.pasien) {
                        $('#dp_display_nm_pasien').text(res.pasien.nm_pasien);
                        $('#dp_display_no_rkm_medis').text(`RM: ${res.no_rkm_medis || '-'}`);
                        $('#dp_display_umur_jk').text(`${res.umurdaftar || '-'} ${res.sttsumur || ''} (${res.pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan'})`);
                        if (!$('#dp_nama_pasien_keluarga').val()) {
                            $('#dp_nama_pasien_keluarga').val(res.pasien.nm_pasien);
                        }
                    }
                });
            } else {
                $.get(`{{ url('/registrasi/get') }}/${encodeURIComponent(no_rawat)}`).done((res) => {
                    if (res && res.pasien) {
                        $('#dp_display_nm_pasien').text(res.pasien.nm_pasien);
                        $('#dp_display_no_rkm_medis').text(`RM: ${res.no_rkm_medis || '-'}`);
                        $('#dp_display_umur_jk').text(`${res.umurdaftar || '-'} ${res.sttsumur || ''} (${res.pasien.jk === 'L' ? 'Laki-laki' : 'Perempuan'})`);
                        if (!$('#dp_nama_pasien_keluarga').val()) {
                            $('#dp_nama_pasien_keluarga').val(res.pasien.nm_pasien);
                        }
                    }
                });
            }

            setTimeout(() => {
                resizeCanvasDischarge();
                loadDataPerencanaanPemulangan(no_rawat);
            }, 300);
        };

        function loadDataPerencanaanPemulangan(no_rawat) {
            $.get(`{{ url('/erm/perencanaan-pemulangan/get') }}/${encodeURIComponent(no_rawat)}`)
                .done(function (res) {
                    if (res.success && res.data) {
                        const d = res.data;
                        $('#dp_rencana_pulang').val(d.rencana_pulang ? d.rencana_pulang.substring(0, 10) : '{{ date("Y-m-d") }}');
                        $('#dp_alasan_masuk').val(d.alasan_masuk);
                        $('#dp_diagnosa_medis').val(d.diagnosa_medis);

                        $('#dp_pengaruh_ri_pasien_dan_keluarga').val(d.pengaruh_ri_pasien_dan_keluarga);
                        $('#dp_keterangan_pengaruh_ri_pasien_dan_keluarga').val(d.keterangan_pengaruh_ri_pasien_dan_keluarga);
                        $('#dp_pengaruh_ri_pekerjaan_sekolah').val(d.pengaruh_ri_pekerjaan_sekolah);
                        $('#dp_keterangan_pengaruh_ri_pekerjaan_sekolah').val(d.keterangan_pengaruh_ri_pekerjaan_sekolah);
                        $('#dp_pengaruh_ri_keuangan').val(d.pengaruh_ri_keuangan);
                        $('#dp_keterangan_pengaruh_ri_keuangan').val(d.keterangan_pengaruh_ri_keuangan);
                        $('#dp_antisipasi_masalah_saat_pulang').val(d.antisipasi_masalah_saat_pulang);
                        $('#dp_keterangan_antisipasi_masalah_saat_pulang').val(d.keterangan_antisipasi_masalah_saat_pulang);
                        $('#dp_bantuan_diperlukan_dalam').val(d.bantuan_diperlukan_dalam);
                        $('#dp_keterangan_bantuan_diperlukan_dalam').val(d.keterangan_bantuan_diperlukan_dalam);
                        $('#dp_adakah_yang_membantu_keperluan').val(d.adakah_yang_membantu_keperluan);
                        $('#dp_keterangan_adakah_yang_membantu_keperluan').val(d.keterangan_adakah_yang_membantu_keperluan);
                        $('#dp_pasien_tinggal_sendiri').val(d.pasien_tinggal_sendiri);
                        $('#dp_keterangan_pasien_tinggal_sendiri').val(d.keterangan_pasien_tinggal_sendiri);
                        $('#dp_pasien_menggunakan_peralatan_medis').val(d.pasien_menggunakan_peralatan_medis);
                        $('#dp_keterangan_pasien_menggunakan_peralatan_medis').val(d.keterangan_pasien_menggunakan_peralatan_medis);
                        $('#dp_pasien_memerlukan_alat_bantu').val(d.pasien_memerlukan_alat_bantu);
                        $('#dp_keterangan_pasien_memerlukan_alat_bantu').val(d.keterangan_pasien_memerlukan_alat_bantu);
                        $('#dp_memerlukan_perawatan_khusus').val(d.memerlukan_perawatan_khusus);
                        $('#dp_keterangan_memerlukan_perawatan_khusus').val(d.keterangan_memerlukan_perawatan_khusus);
                        $('#dp_bermasalah_memenuhi_kebutuhan').val(d.bermasalah_memenuhi_kebutuhan);
                        $('#dp_keterangan_bermasalah_memenuhi_kebutuhan').val(d.keterangan_bermasalah_memenuhi_kebutuhan);
                        $('#dp_memiliki_nyeri_kronis').val(d.memiliki_nyeri_kronis);
                        $('#dp_keterangan_memiliki_nyeri_kronis').val(d.keterangan_memiliki_nyeri_kronis);
                        $('#dp_memerlukan_edukasi_kesehatan').val(d.memerlukan_edukasi_kesehatan);
                        $('#dp_keterangan_memerlukan_edukasi_kesehatan').val(d.keterangan_memerlukan_edukasi_kesehatan);
                        $('#dp_memerlukan_keterampilkan_khusus').val(d.memerlukan_keterampilkan_khusus);
                        $('#dp_keterangan_memerlukan_keterampilkan_khusus').val(d.keterangan_memerlukan_keterampilkan_khusus);

                        $('#dp_nama_pasien_keluarga').val(d.nama_pasien_keluarga);
                        if (d.nip) {
                            $('#dp_nip').val(d.nip);
                        }
                        if (d.petugas && d.petugas.nama) {
                            $('#dp_petugas_display').val(d.petugas.nama);
                        }

                        if (d.bukti_saksi && d.bukti_saksi.photo) {
                            const img = new Image();
                            img.onload = () => {
                                ctxDischarge.drawImage(img, 0, 0, canvasDischarge.width, canvasDischarge.height);
                                $('#hintSignDischarge').addClass('d-none');
                                hasSignatureDischarge = true;
                            };
                            img.src = `{{ url('/') }}/${d.bukti_saksi.photo}`;
                        }

                        $('#btnCetakDischarge').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/perencanaan-pemulangan/print') }}/${encodeURIComponent(d.no_rawat)}', '_blank')`);
                        $('#btnHapusDischarge').removeClass('d-none');
                    }
                });
        }

        // Save
        $(document).on('click', '#btnSimpanDischarge', function () {
            if (!$('#formPerencanaanPemulangan')[0].checkValidity()) {
                $('#formPerencanaanPemulangan')[0].reportValidity();
                return;
            }

            if (hasSignatureDischarge && canvasDischarge) {
                $('#dp_ttd_saksi').val(canvasDischarge.toDataURL('image/png'));
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: '{{ url('/erm/perencanaan-pemulangan/store') }}',
                type: 'POST',
                data: $('#formPerencanaanPemulangan').serialize() + '&_token={{ csrf_token() }}',
                success: function (res) {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            alert(res.message);
                        }
                        loadDataPerencanaanPemulangan($('#dp_no_rawat').val());
                    } else {
                        alert(res.message || 'Gagal menyimpan');
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Terjadi kesalahan sistem');
                },
                complete: function () {
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Discharge Planning');
                }
            });
        });

        // Delete
        $(document).on('click', '#btnHapusDischarge', function () {
            const no_rawat = $('#dp_no_rawat').val();
            if (!no_rawat) return;

            const runDelete = () => {
                $.post('{{ url('/erm/perencanaan-pemulangan/delete') }}', {
                    no_rawat: no_rawat,
                    _token: '{{ csrf_token() }}'
                }).done(function (res) {
                    if (res.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Dihapus!', res.message, 'success');
                        }
                        if (typeof $ !== 'undefined' && $('#modalPerencanaanPemulangan').modal) {
                            $('#modalPerencanaanPemulangan').modal('hide');
                        } else if (typeof bootstrap !== 'undefined') {
                            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                        }
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Rencana Pemulangan?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((r) => { if (r.isConfirmed) runDelete(); });
            } else if (confirm('Hapus perencanaan pemulangan ini?')) {
                runDelete();
            }
        });
    })();
</script>
@endpush

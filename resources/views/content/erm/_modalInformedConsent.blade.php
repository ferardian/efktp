<div class="modal modal-blur fade" id="modalInformedConsent" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-file-certificate me-2 fs-2"></i>
                    Informed Consent — Formulir Persetujuan / Penolakan Tindakan Medis
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <!-- Kolom Kiri: Form Informed Consent -->
                    <div class="col-lg-8 col-md-12">
                        <!-- Card Banner Pasien & Smart TTV Lookup -->
                        <div class="card card-sm mb-3 border-primary-subtle shadow-sm">
                            <div class="card-body p-2 bg-light-subtle">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small mb-0">No. Rawat / No. RM</label>
                                        <div class="fw-bold fs-4 text-primary" id="ic_display_no_rawat">-</div>
                                        <div class="text-muted small" id="ic_display_no_rkm_medis">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-0">Pasien</label>
                                        <div class="fw-bold fs-4 text-dark" id="ic_display_nm_pasien">-</div>
                                        <div class="text-muted small" id="ic_display_umur_jk">-</div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSyncTtvInformedConsent" title="Tarik data TTV & Diagnosa terakhir pasien">
                                            <i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik TTV & Diagnosa Terkini
                                        </button>
                                        <div class="small text-muted mt-1" id="ic_ttv_preview_badge">TTV: -</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Utama -->
                        <form id="formInformedConsent">
                            <input type="hidden" name="no_pernyataan" id="ic_no_pernyataan">
                            <input type="hidden" name="no_rawat" id="ic_no_rawat">
                            <input type="hidden" name="nip" id="ic_nip">

                            <!-- Section 1: Pelaksana Tindakan & Dokter -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-user-check me-1"></i> 1. Informasi Pemberi Edukasi & Dokter Pelaksana</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Tanggal Edukasi / Tindakan</label>
                                            <input type="date" class="form-control form-control-sm" name="tanggal" id="ic_tanggal" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label required small">Dokter Pelaksana / DPJP</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm w-25" name="kd_dokter" id="ic_kd_dokter" readonly required>
                                                <input type="text" class="form-control form-control-sm w-75" name="nm_dokter" id="ic_nm_dokter" readonly placeholder="Nama Dokter">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Informasi & Edukasi Tindakan (10 Elemen Edukasi) -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <strong class="text-secondary"><i class="ti ti-list-details me-1"></i> 2. Materi Edukasi Tindakan Medis</strong>
                                    <span class="badge bg-blue-lt small">Beri checklist jika pasien/keluarga telah paham</span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 25%">Materi Informasi</th>
                                                    <th style="width: 60%">Isi Edukasi / Penjelasan</th>
                                                    <th style="width: 15%" class="text-center">Konfirmasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Diagnosis (WD & DD)</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="diagnosa" id="ic_diagnosa" placeholder="Diagnosis penyakit..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="diagnosa_konfirmasi" id="ic_diagnosa_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Dasar Diagnosis / Indikasi</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="indikasi_tindakan" id="ic_indikasi_tindakan" placeholder="Indikasi dilakukan tindakan..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="indikasi_tindakan_konfirmasi" id="ic_indikasi_tindakan_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tindakan Kedokteran</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="tindakan" id="ic_tindakan" placeholder="Nama tindakan medis / operasi..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="tindakan_konfirmasi" id="ic_tindakan_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tata Cara Tindakan</strong></td>
                                                    <td><textarea class="form-control form-control-sm" name="tata_cara" id="ic_tata_cara" rows="2" placeholder="Uraian prosedur pelaksanaan..."></textarea></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="tata_cara_konfirmasi" id="ic_tata_cara_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tujuan Tindakan</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="tujuan" id="ic_tujuan" placeholder="Tujuan dilakukannya tindakan..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="tujuan_konfirmasi" id="ic_tujuan_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Risiko Tindakan</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="risiko" id="ic_risiko" placeholder="Risiko yang mungkin terjadi..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="risiko_konfirmasi" id="ic_risiko_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Komplikasi</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="komplikasi" id="ic_komplikasi" placeholder="Komplikasi yang mungkin timbul..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="komplikasi_konfirmasi" id="ic_komplikasi_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Prognosis</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="prognosis" id="ic_prognosis" placeholder="Prognosis pasca tindakan..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="prognosis_konfirmasi" id="ic_prognosis_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alternatif & Risikonya</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="alternatif_dan_risikonya" id="ic_alternatif_dan_risikonya" placeholder="Pilihan tindakan alternatif..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="alternatif_konfirmasi" id="ic_alternatif_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Perkiraan Biaya</strong></td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control form-control-sm" name="biaya" id="ic_biaya" value="0">
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="biaya_konfirmasi" id="ic_biaya_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Lain-lain</strong></td>
                                                    <td><input type="text" class="form-control form-control-sm" name="lain_lain" id="ic_lain_lain" placeholder="Catatan tambahan bila ada..."></td>
                                                    <td class="text-center">
                                                        <label class="form-check form-check-inline m-0">
                                                            <input class="form-check-input" type="checkbox" name="lain_lain_konfirmasi" id="ic_lain_lain_konfirmasi" value="1" checked>
                                                            <span class="form-check-label small">Paham</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Data Penerima Informasi / Wali -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <strong class="text-secondary"><i class="ti ti-users me-1"></i> 3. Identitas Pasien / Penerima Informasi (Wali)</strong>
                                    <button type="button" class="btn btn-xs btn-outline-secondary" id="btnSalinDariPasienIc">
                                        <i class="ti ti-copy me-1"></i> Samakan dengan Pasien
                                    </button>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-5">
                                            <label class="form-label required small">Nama Lengkap</label>
                                            <input type="text" class="form-control form-control-sm" name="penerima_informasi" id="ic_penerima_informasi" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label required small">Hubungan dg Pasien</label>
                                            <select class="form-select form-select-sm" name="hubungan_penerima_informasi" id="ic_hubungan_penerima_informasi" required>
                                                <option value="Diri Sendiri">Diri Sendiri</option>
                                                <option value="Orang Tua">Orang Tua</option>
                                                <option value="Anak">Anak</option>
                                                <option value="Saudara Kandung">Saudara Kandung</option>
                                                <option value="Teman">Teman</option>
                                                <option value="Lain-lain">Lain-lain</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label required small">Jenis Kelamin</label>
                                            <select class="form-select form-select-sm" name="jk_penerima_informasi" id="ic_jk_penerima_informasi">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Umur (Th)</label>
                                            <input type="text" class="form-control form-control-sm" name="umur_penerima_informasi" id="ic_umur_penerima_informasi">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Tgl. Lahir</label>
                                            <input type="date" class="form-control form-control-sm" name="tanggal_lahir_penerima_informasi" id="ic_tanggal_lahir_penerima_informasi">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">No. HP / Telepon</label>
                                            <input type="text" class="form-control form-control-sm" name="no_hp" id="ic_no_hp">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Alamat Lengkap</label>
                                            <input type="text" class="form-control form-control-sm" name="alamat_penerima_informasi" id="ic_alamat_penerima_informasi">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label small">Alasan Diwakilkan (Jika Pasien Tidak TTD Sendiri)</label>
                                            <input type="text" class="form-control form-control-sm" name="alasan_diwakilkan_penerima_informasi" id="ic_alasan_diwakilkan_penerima_informasi" value="-">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Nama Saksi Keluarga / Pihak Pasien</label>
                                            <input type="text" class="form-control form-control-sm" name="saksi_keluarga" id="ic_saksi_keluarga" placeholder="Nama saksi keluarga...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Pernyataan & Tanda Tangan Digital -->
                            <div class="card mb-3 shadow-sm border-2" id="cardPernyataanBox">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-writing me-1"></i> 4. Pernyataan & Tanda Tangan Digital</strong>
                                </div>
                                <div class="card-body p-3">
                                    <!-- Radio Pernyataan -->
                                    <div class="mb-3 text-center p-2 rounded bg-light border">
                                        <label class="form-label fw-bold mb-2">Pernyataan Keputusan:</label>
                                        <div class="d-flex justify-content-center gap-4">
                                            <label class="form-check form-check-inline cursor-pointer">
                                                <input class="form-check-input" type="radio" name="pernyataan" id="radioPersetujuan" value="Persetujuan" checked>
                                                <span class="form-check-label fw-bold text-success fs-3">
                                                    <i class="ti ti-circle-check me-1"></i> SETUJU (PERSETUJUAN TINDAKAN)
                                                </span>
                                            </label>
                                            <label class="form-check form-check-inline cursor-pointer">
                                                <input class="form-check-input" type="radio" name="pernyataan" id="radioPenolakan" value="Penolakan">
                                                <span class="form-check-label fw-bold text-danger fs-3">
                                                    <i class="ti ti-circle-x me-1"></i> MENOLAK (PENOLAKAN TINDAKAN)
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Canvas Signature Pad -->
                                    <div class="row g-3">
                                        <!-- TTD Penerima Informasi -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-2 text-center bg-white shadow-xs">
                                                <div class="fw-bold small mb-1">Tanda Tangan Pasien / Penerima Informasi</div>
                                                <div class="position-relative d-inline-block border rounded bg-white">
                                                    <canvas id="canvasTtdPenerima" width="320" height="150" style="touch-action: none; cursor: crosshair; display: block;"></canvas>
                                                </div>
                                                <input type="hidden" name="ttd_penerima" id="ic_ttd_penerima">
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-xs btn-outline-danger" id="btnClearTtdPenerima">
                                                        <i class="ti ti-eraser me-1"></i> Hapus TTD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TTD Saksi Keluarga -->
                                        <div class="col-md-6">
                                            <div class="border rounded p-2 text-center bg-white shadow-xs">
                                                <div class="fw-bold small mb-1">Tanda Tangan Saksi Keluarga</div>
                                                <div class="position-relative d-inline-block border rounded bg-white">
                                                    <canvas id="canvasTtdSaksi" width="320" height="150" style="touch-action: none; cursor: crosshair; display: block;"></canvas>
                                                </div>
                                                <input type="hidden" name="ttd_saksi" id="ic_ttd_saksi">
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-xs btn-outline-danger" id="btnClearTtdSaksi">
                                                        <i class="ti ti-eraser me-1"></i> Hapus TTD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Tutup
                                </button>
                                <div class="gap-2 d-flex">
                                    <button type="button" class="btn btn-outline-success d-none" id="btnCetakInformedConsent">
                                        <i class="ti ti-printer me-1"></i> Cetak Surat Persetujuan
                                    </button>
                                    <button type="button" class="btn btn-danger d-none" id="btnHapusInformedConsent">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btnSimpanInformedConsent">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Informed Consent
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Kolom Kanan: Riwayat Informed Consent Pasien -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card shadow-sm sticky-top" style="top: 10px;">
                            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                <strong class="text-secondary"><i class="ti ti-history me-1"></i> Riwayat Informed Consent</strong>
                                <button type="button" class="btn btn-xs btn-outline-primary" id="btnTambahBaruInformedConsent">
                                    <i class="ti ti-plus me-1"></i> Buat Baru
                                </button>
                            </div>
                            <div class="card-body p-2" id="containerRiwayatInformedConsent" style="max-height: 75vh; overflow-y: auto;">
                                <div class="text-center text-muted p-3">Memuat riwayat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function () {
        const modalInformedConsent = $('#modalInformedConsent');
        const formInformedConsent = $('#formInformedConsent');
        const canvasPenerima = document.getElementById('canvasTtdPenerima');
        const canvasSaksi = document.getElementById('canvasTtdSaksi');
        let ctxPenerima = canvasPenerima ? canvasPenerima.getContext('2d') : null;
        let ctxSaksi = canvasSaksi ? canvasSaksi.getContext('2d') : null;
        let isDrawingPenerima = false;
        let isDrawingSaksi = false;
        let hasSignaturePenerima = false;
        let hasSignatureSaksi = false;

        // Inisialisasi Canvas TTD Pad
        function initSignaturePad(canvas, ctx, isDrawingFlagSetter, hasSigSetter) {
            if (!canvas || !ctx) return;
            ctx.strokeStyle = "#002060";
            ctx.lineWidth = 2.5;
            ctx.lineCap = "round";
            ctx.lineJoin = "round";

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function startDraw(e) {
                e.preventDefault();
                isDrawingFlagSetter(true);
                hasSigSetter(true);
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                e.preventDefault();
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            }

            function stopDraw(e) {
                isDrawingFlagSetter(false);
            }

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', (e) => { if (e.buttons === 1) draw(e); });
            window.addEventListener('mouseup', stopDraw);

            canvas.addEventListener('touchstart', startDraw, { passive: false });
            canvas.addEventListener('touchmove', draw, { passive: false });
            window.addEventListener('touchend', stopDraw);
        }

        function clearCanvas(canvas, ctx, hasSigSetter) {
            if (!canvas || !ctx) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSigSetter(false);
        }

        initSignaturePad(canvasPenerima, ctxPenerima, (val) => { isDrawingPenerima = val; }, (val) => { hasSignaturePenerima = val; });
        initSignaturePad(canvasSaksi, ctxSaksi, (val) => { isDrawingSaksi = val; }, (val) => { hasSignatureSaksi = val; });

        $('#btnClearTtdPenerima').on('click', () => {
            clearCanvas(canvasPenerima, ctxPenerima, (val) => { hasSignaturePenerima = val; });
            $('#ic_ttd_penerima').val('');
        });

        $('#btnClearTtdSaksi').on('click', () => {
            clearCanvas(canvasSaksi, ctxSaksi, (val) => { hasSignatureSaksi = val; });
            $('#ic_ttd_saksi').val('');
        });

        // Reset form to clean state
        function resetFormInformedConsent() {
            formInformedConsent.trigger('reset');
            $('#ic_no_pernyataan').val('');
            $('#ic_tanggal').val(new Date().toISOString().split('T')[0]);
            clearCanvas(canvasPenerima, ctxPenerima, (val) => { hasSignaturePenerima = val; });
            clearCanvas(canvasSaksi, ctxSaksi, (val) => { hasSignatureSaksi = val; });
            $('#ic_ttd_penerima').val('');
            $('#ic_ttd_saksi').val('');
            $('#btnCetakInformedConsent').addClass('d-none');
            $('#btnHapusInformedConsent').addClass('d-none');
            $('#radioPersetujuan').prop('checked', true);
        }

        // Buka modal Informed Consent
        window.bukaInformedConsent = function (no_rawat) {
            resetFormInformedConsent();
            $('#ic_no_rawat').val(no_rawat);
            modalInformedConsent.modal('show');

            // 1. Ambil detail registrasi & dokter
            getRegDetail(no_rawat).done((response) => {
                const { pasien, dokter } = response;
                $('#ic_display_no_rawat').text(no_rawat);
                $('#ic_display_no_rkm_medis').text(response.no_rkm_medis);
                $('#ic_display_nm_pasien').text(pasien?.nm_pasien || '-');
                $('#ic_display_umur_jk').text(`${formatTanggal(pasien?.tgl_lahir)} / ${pasien?.jk === 'L' ? 'Laki-laki' : 'Perempuan'}`);

                $('#ic_kd_dokter').val(response.kd_dokter);
                $('#ic_nm_dokter').val(dokter?.nm_dokter || '-');

                // Default penerima informasi = diri sendiri / pasien
                $('#ic_penerima_informasi').val(pasien?.nm_pasien || '');
                $('#ic_jk_penerima_informasi').val(pasien?.jk || 'L');
                $('#ic_tanggal_lahir_penerima_informasi').val(pasien?.tgl_lahir || '');
                $('#ic_alamat_penerima_informasi').val(pasien?.alamat || '');
                $('#ic_no_hp').val(pasien?.no_tlp || '');
                $('#ic_hubungan_penerima_informasi').val('Diri Sendiri');
            });

            // 2. Tarik TTV terkini untuk preview badge & auto-fill
            syncTtvInformedConsent(no_rawat, false);

            // 3. Muat riwayat informed consent pasien
            muatRiwayatInformedConsent(no_rawat);
        };

        // Fungsi Smart TTV Lookup
        function syncTtvInformedConsent(no_rawat, showNotification = true) {
            $.get(`{{ url('/pemeriksaan/ttv/latest') }}/${encodeURIComponent(no_rawat)}`)
                .done((res) => {
                    if (res.success && res.data) {
                        const ttv = res.data;
                        const badgeText = `TD: ${ttv.td} | HR: ${ttv.nadi}x/m | RR: ${ttv.rr}x/m | SpO2: ${ttv.spo2}% | T: ${ttv.suhu}°C (${ttv.waktu_formatted} - ${ttv.petugas})`;
                        $('#ic_ttv_preview_badge').html(`<span class="badge bg-green-lt text-dark border">${badgeText}</span>`);

                        // Jika diminta sync (klik tombol), salin diagnosa atau info
                        if (showNotification) {
                            if (ttv.penilaian && ttv.penilaian !== '-') {
                                $('#ic_diagnosa').val(ttv.penilaian).addClass('border-primary bg-primary-lt');
                            }
                            if (ttv.keluhan && ttv.keluhan !== '-') {
                                $('#ic_indikasi_tindakan').val(`Keluhan: ${ttv.keluhan}`).addClass('border-primary bg-primary-lt');
                            }
                            setTimeout(() => {
                                $('#ic_diagnosa, #ic_indikasi_tindakan').removeClass('border-primary bg-primary-lt');
                            }, 2000);
                            showToast('TTV & Diagnosa Terkini Berhasil Diterapkan ke Form!', 'success');
                        }
                    } else {
                        $('#ic_ttv_preview_badge').html('<span class="text-muted small">Belum ada catatan TTV sebelumnya</span>');
                        if (showNotification) {
                            showToast('Belum ada data TTV sebelumnya untuk nomor rawat ini.', 'info');
                        }
                    }
                })
                .fail(() => {
                    $('#ic_ttv_preview_badge').html('<span class="text-danger small">Gagal memuat TTV</span>');
                });
        }

        $('#btnSyncTtvInformedConsent').on('click', () => {
            const no_rawat = $('#ic_no_rawat').val();
            if (no_rawat) syncTtvInformedConsent(no_rawat, true);
        });

        // Samakan penerima informasi dengan data pasien
        $('#btnSalinDariPasienIc').on('click', () => {
            const no_rawat = $('#ic_no_rawat').val();
            if (!no_rawat) return;
            getRegDetail(no_rawat).done((res) => {
                const p = res.pasien;
                if (p) {
                    $('#ic_penerima_informasi').val(p.nm_pasien);
                    $('#ic_jk_penerima_informasi').val(p.jk);
                    $('#ic_tanggal_lahir_penerima_informasi').val(p.tgl_lahir);
                    $('#ic_alamat_penerima_informasi').val(p.alamat);
                    $('#ic_no_hp').val(p.no_tlp || '-');
                    $('#ic_hubungan_penerima_informasi').val('Diri Sendiri');
                    showToast('Identitas penerima informasi disamakan dengan pasien', 'success');
                }
            });
        });

        // Muat Riwayat Informed Consent
        function muatRiwayatInformedConsent(no_rawat) {
            const container = $('#containerRiwayatInformedConsent');
            container.html('<div class="text-center text-muted p-3"><div class="spinner-border spinner-border-sm me-1"></div> Memuat riwayat...</div>');

            $.get(`{{ url('/erm/persetujuan-tindakan/list') }}/${encodeURIComponent(no_rawat)}`)
                .done((res) => {
                    if (!res.success || !res.data || res.data.length === 0) {
                        container.html('<div class="text-muted text-center p-3 small"><i class="ti ti-notes-off me-1"></i> Belum ada informed consent untuk kunjungan ini.</div>');
                        return;
                    }

                    let html = '<div class="list-group list-group-flush">';
                    res.data.forEach((item) => {
                        const isSetuju = (item.pernyataan === 'Persetujuan');
                        const badgeClass = isSetuju ? 'bg-success text-success-fg' : 'bg-danger text-danger-fg';
                        const icon = isSetuju ? 'ti-circle-check' : 'ti-circle-x';

                        html += `
                            <div class="list-group-item list-group-item-action p-2 cursor-pointer border rounded mb-2 shadow-2xs item-riwayat-ic" data-no="${item.no_pernyataan}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-primary">${item.tindakan || 'Tindakan Medis'}</div>
                                        <div class="small text-muted"><i class="ti ti-calendar me-1"></i> ${formatTanggal(item.tanggal)} | ${item.no_pernyataan}</div>
                                        <div class="small text-muted"><i class="ti ti-user me-1"></i> Penerima: <strong>${item.penerima_informasi}</strong> (${item.hubungan_penerima_informasi})</div>
                                    </div>
                                    <span class="badge ${badgeClass} text-nowrap"><i class="ti ${icon} me-1"></i> ${item.pernyataan}</span>
                                </div>
                                <div class="mt-2 d-flex justify-content-end gap-1">
                                    <a href="{{ url('/erm/persetujuan-tindakan/print') }}/${item.no_pernyataan}" target="_blank" class="btn btn-xs btn-outline-secondary" onclick="event.stopPropagation();">
                                        <i class="ti ti-printer me-1"></i> Cetak
                                    </a>
                                    <button type="button" class="btn btn-xs btn-primary btn-pilih-ic" data-no="${item.no_pernyataan}">
                                        <i class="ti ti-eye me-1"></i> Buka
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.html(html);

                    // Event klik buka detail
                    container.find('.item-riwayat-ic, .btn-pilih-ic').on('click', function (e) {
                        const noPernyataan = $(this).data('no');
                        bukaDetailInformedConsent(noPernyataan);
                    });
                })
                .fail(() => {
                    container.html('<div class="text-danger text-center p-3 small">Gagal memuat riwayat</div>');
                });
        }

        // Buka detail riwayat
        function bukaDetailInformedConsent(no_pernyataan) {
            $.get(`{{ url('/erm/persetujuan-tindakan/show') }}/${encodeURIComponent(no_pernyataan)}`)
                .done((res) => {
                    if (!res.success || !res.data) return;
                    const d = res.data;
                    resetFormInformedConsent();

                    $('#ic_no_pernyataan').val(d.no_pernyataan);
                    $('#ic_no_rawat').val(d.no_rawat);
                    $('#ic_tanggal').val(d.tanggal);
                    $('#ic_kd_dokter').val(d.kd_dokter);
                    $('#ic_nm_dokter').val(d.dokter?.nm_dokter || '-');

                    $('#ic_diagnosa').val(d.diagnosa);
                    $('#ic_diagnosa_konfirmasi').prop('checked', d.diagnosa_konfirmasi === 'true');
                    $('#ic_indikasi_tindakan').val(d.indikasi_tindakan);
                    $('#ic_indikasi_tindakan_konfirmasi').prop('checked', d.indikasi_tindakan_konfirmasi === 'true');
                    $('#ic_tindakan').val(d.tindakan);
                    $('#ic_tindakan_konfirmasi').prop('checked', d.tindakan_konfirmasi === 'true');
                    $('#ic_tata_cara').val(d.tata_cara);
                    $('#ic_tata_cara_konfirmasi').prop('checked', d.tata_cara_konfirmasi === 'true');
                    $('#ic_tujuan').val(d.tujuan);
                    $('#ic_tujuan_konfirmasi').prop('checked', d.tujuan_konfirmasi === 'true');
                    $('#ic_risiko').val(d.risiko);
                    $('#ic_risiko_konfirmasi').prop('checked', d.risiko_konfirmasi === 'true');
                    $('#ic_komplikasi').val(d.komplikasi);
                    $('#ic_komplikasi_konfirmasi').prop('checked', d.komplikasi_konfirmasi === 'true');
                    $('#ic_prognosis').val(d.prognosis);
                    $('#ic_prognosis_konfirmasi').prop('checked', d.prognosis_konfirmasi === 'true');
                    $('#ic_alternatif_dan_risikonya').val(d.alternatif_dan_risikonya);
                    $('#ic_alternatif_konfirmasi').prop('checked', d.alternatif_konfirmasi === 'true');
                    $('#ic_biaya').val(d.biaya);
                    $('#ic_biaya_konfirmasi').prop('checked', d.biaya_konfirmasi === 'true');
                    $('#ic_lain_lain').val(d.lain_lain);
                    $('#ic_lain_lain_konfirmasi').prop('checked', d.lain_lain_konfirmasi === 'true');

                    $('#ic_penerima_informasi').val(d.penerima_informasi);
                    $('#ic_hubungan_penerima_informasi').val(d.hubungan_penerima_informasi);
                    $('#ic_jk_penerima_informasi').val(d.jk_penerima_informasi);
                    $('#ic_umur_penerima_informasi').val(d.umur_penerima_informasi);
                    $('#ic_tanggal_lahir_penerima_informasi').val(d.tanggal_lahir_penerima_informasi);
                    $('#ic_no_hp').val(d.no_hp);
                    $('#ic_alamat_penerima_informasi').val(d.alamat_penerima_informasi);
                    $('#ic_alasan_diwakilkan_penerima_informasi').val(d.alasan_diwakilkan_penerima_informasi);
                    $('#ic_saksi_keluarga').val(d.saksi_keluarga);

                    if (d.pernyataan === 'Penolakan') {
                        $('#radioPenolakan').prop('checked', true);
                    } else {
                        $('#radioPersetujuan').prop('checked', true);
                    }

                    // Tampilkan gambar signature jika ada
                    if (d.bukti_penerima_informasi?.photo) {
                        const img = new Image();
                        img.onload = () => { ctxPenerima.drawImage(img, 0, 0, canvasPenerima.width, canvasPenerima.height); };
                        img.src = `{{ url('/') }}/${d.bukti_penerima_informasi.photo}`;
                        hasSignaturePenerima = true;
                    }
                    if (d.bukti_saksi_keluarga?.photo) {
                        const imgSaksi = new Image();
                        imgSaksi.onload = () => { ctxSaksi.drawImage(imgSaksi, 0, 0, canvasSaksi.width, canvasSaksi.height); };
                        imgSaksi.src = `{{ url('/') }}/${d.bukti_saksi_keluarga.photo}`;
                        hasSignatureSaksi = true;
                    }

                    $('#btnCetakInformedConsent').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/persetujuan-tindakan/print') }}/${d.no_pernyataan}', '_blank')`);
                    $('#btnHapusInformedConsent').removeClass('d-none');
                });
        }

        $('#btnTambahBaruInformedConsent').on('click', () => {
            const no_rawat = $('#ic_no_rawat').val();
            resetFormInformedConsent();
            $('#ic_no_rawat').val(no_rawat);
            getRegDetail(no_rawat).done((res) => {
                $('#ic_kd_dokter').val(res.kd_dokter);
                $('#ic_nm_dokter').val(res.dokter?.nm_dokter || '-');
            });
            showToast('Form siap diisi untuk informed consent baru', 'info');
        });

        // Simpan Data
        $('#btnSimpanInformedConsent').on('click', () => {
            const no_rawat = $('#ic_no_rawat').val();
            if (!no_rawat) {
                showToast('Nomor rawat tidak valid', 'error');
                return;
            }

            const penerima = $('#ic_penerima_informasi').val().trim();
            if (!penerima) {
                showToast('Nama penerima informasi / wali wajib diisi!', 'warning');
                $('#ic_penerima_informasi').focus();
                return;
            }

            // Export canvas TTD ke Base64
            if (hasSignaturePenerima && canvasPenerima) {
                $('#ic_ttd_penerima').val(canvasPenerima.toDataURL('image/png'));
            }
            if (hasSignatureSaksi && canvasSaksi) {
                $('#ic_ttd_saksi').val(canvasSaksi.toDataURL('image/png'));
            }

            const formData = formInformedConsent.serialize();

            $('#btnSimpanInformedConsent').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/erm/persetujuan-tindakan') }}`, formData)
                .done((res) => {
                    $('#btnSimpanInformedConsent').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Informed Consent');
                    if (res.success) {
                        showToast(res.message || 'Informed consent berhasil disimpan!', 'success');
                        $('#ic_no_pernyataan').val(res.no_pernyataan);
                        $('#btnCetakInformedConsent').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/persetujuan-tindakan/print') }}/${res.no_pernyataan}', '_blank')`);
                        $('#btnHapusInformedConsent').removeClass('d-none');
                        muatRiwayatInformedConsent(no_rawat);
                    } else {
                        showToast(res.message || 'Gagal menyimpan data', 'error');
                    }
                })
                .fail((err) => {
                    $('#btnSimpanInformedConsent').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Informed Consent');
                    showToast(err.responseJSON?.message || 'Terjadi kesalahan sistem saat menyimpan!', 'error');
                });
        });

        // Hapus Data
        $('#btnHapusInformedConsent').on('click', () => {
            const no_pernyataan = $('#ic_no_pernyataan').val();
            const no_rawat = $('#ic_no_rawat').val();
            if (!no_pernyataan) return;

            Swal.fire({
                title: 'Hapus Informed Consent?',
                text: `Yakin menghapus formulir nomor ${no_pernyataan}? Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/erm/persetujuan-tindakan/delete') }}`, { no_pernyataan: no_pernyataan })
                        .done((res) => {
                            if (res.success) {
                                showToast('Informed consent berhasil dihapus', 'success');
                                resetFormInformedConsent();
                                $('#ic_no_rawat').val(no_rawat);
                                muatRiwayatInformedConsent(no_rawat);
                            }
                        })
                        .fail((err) => {
                            showToast(err.responseJSON?.message || 'Gagal menghapus', 'error');
                        });
                }
            });
        });
    });
</script>
@endpush

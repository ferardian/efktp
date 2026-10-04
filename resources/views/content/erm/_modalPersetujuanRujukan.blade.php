<div class="modal modal-blur fade" id="modalPersetujuanRujukan" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-ambulance me-2 fs-2"></i>
                    Formulir Persetujuan / Penolakan Rujukan & Edukasi Rujukan Pasien
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <!-- Kolom Kiri: Form Persetujuan / Penolakan Rujukan -->
                    <div class="col-lg-8 col-md-12">
                        <!-- Banner Pasien & Tarik Referensi -->
                        <div class="card card-sm mb-3 border-primary-subtle shadow-sm">
                            <div class="card-body p-2 bg-light-subtle">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-0">No. Rawat / No. RM</label>
                                        <div class="fw-bold fs-4 text-primary" id="pr_display_no_rawat">-</div>
                                        <div class="text-muted small" id="pr_display_no_rkm_medis">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-0">Pasien</label>
                                        <div class="fw-bold fs-4 text-dark" id="pr_display_nm_pasien">-</div>
                                        <div class="text-muted small" id="pr_display_umur_jk">-</div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSyncReferensiRujukan" title="Tarik data rujukan Khanza & diagnosa pasien">
                                            <i class="ti ti-rotate-clockwise me-1 text-primary"></i> Tarik Data Rujukan & Diagnosa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Utama -->
                        <form id="formPersetujuanRujukan">
                            <input type="hidden" name="no_surat" id="pr_no_surat">
                            <input type="hidden" name="no_rawat" id="pr_no_rawat">
                            <input type="hidden" name="nip" id="pr_nip">
                            <input type="hidden" name="ttd_penerima" id="pr_ttd_penerima">
                            <input type="hidden" name="ttd_saksi" id="pr_ttd_saksi">
                            <input type="hidden" name="ttd_dokter" id="pr_ttd_dokter">

                            <!-- Jenis Pernyataan (Persetujuan / Penolakan) -->
                            <div class="card mb-3 border-2 shadow-sm" id="cardJenisPernyataan" style="border-color: #198754;">
                                <div class="card-body p-3">
                                    <label class="form-label fw-bold mb-2">Jenis Pernyataan Pasien / Penanggung Jawab:</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis" id="radioPersetujuanRujuk" value="Persetujuan" checked>
                                            <label class="form-check-label text-success fw-bold fs-3" for="radioPersetujuanRujuk">
                                                <i class="ti ti-circle-check me-1"></i> PERSETUJUAN RUJUKAN
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis" id="radioPenolakanRujuk" value="Penolakan">
                                            <label class="form-check-label text-danger fw-bold fs-3" for="radioPenolakanRujuk">
                                                <i class="ti ti-circle-x me-1"></i> PENOLAKAN RUJUKAN
                                            </label>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger py-2 px-3 mt-2 mb-0 d-none" id="alertPenolakanInfo">
                                        <i class="ti ti-alert-triangle me-1 fs-3"></i>
                                        <strong>Perhatian:</strong> Pasien/keluarga menolak dirujuk ke fasilitas kesehatan lanjutan. Wajib mengisi alasan penolakan dan memastikan edukasi risiko komplikasi/kematian telah disampaikan dengan jelas.
                                    </div>
                                </div>
                            </div>

                            <!-- Section 1: Informasi Edukasi & Rujukan -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-info-circle me-1"></i> 1. Materi Edukasi & Rencana Rujukan</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label required small">Tanggal & Jam Edukasi</label>
                                            <input type="datetime-local" class="form-control" name="tanggal" id="pr_tanggal" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required small">Fasilitas Kesehatan Tujuan Rujukan</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="faskes_tujuan" id="pr_faskes_tujuan" placeholder="Contoh: RSUD dr. Soetomo Surabaya" required>
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Pilihan</button>
                                                <ul class="dropdown-menu dropdown-menu-end" id="listTemplateFaskes">
                                                    <li><a class="dropdown-item faskes-opt" href="javascript:void(0)">RSUD terdekat</a></li>
                                                    <li><a class="dropdown-item faskes-opt" href="javascript:void(0)">RS Khusus Ibu dan Anak</a></li>
                                                    <li><a class="dropdown-item faskes-opt" href="javascript:void(0)">Puskesmas Perawatan / Rawat Inap</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">Bagian / Poli / Dokter Spesialis Tujuan</label>
                                            <input type="text" class="form-control" name="bagian_tujuan" id="pr_bagian_tujuan" placeholder="Contoh: IGD, Poli Bedah, Poli Kebidanan / Sp.OG">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Moda Transportasi</label>
                                            <select class="form-select" name="transportasi" id="pr_transportasi">
                                                <option value="Ambulans Medis (Lengkap Alat & Obat)">Ambulans Medis (Lengkap)</option>
                                                <option value="Ambulans Transport">Ambulans Transport</option>
                                                <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                                                <option value="Kendaraan Umum">Kendaraan Umum</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Pendamping Rujukan</label>
                                            <select class="form-select" name="pendamping" id="pr_pendamping">
                                                <option value="Dokter & Perawat">Dokter & Perawat</option>
                                                <option value="Perawat / Bidan">Perawat / Bidan</option>
                                                <option value="Keluarga & Petugas">Keluarga & Petugas</option>
                                                <option value="Keluarga Pasien">Keluarga Pasien</option>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small required">Diagnosis Medis Pasien</label>
                                            <textarea class="form-control" name="diagnosa" id="pr_diagnosa" rows="2" placeholder="Diagnosa kerja, diagnosa banding, atau temuan klinis saat ini..." required></textarea>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small required">Alasan Perlunya Rujukan (Indikasi Rujukan)</label>
                                            <textarea class="form-control" name="alasan_rujuk" id="pr_alasan_rujuk" rows="2" placeholder="Alasan dirujuk (keterbatasan fasilitas/sarana, butuh penanganan dokter spesialis, dsb)..." required></textarea>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small">Tindakan Stabilisasi & Penanganan Pra-Rujuk yang Telah Diberikan</label>
                                            <textarea class="form-control" name="tindakan_stabilisasi" id="pr_tindakan_stabilisasi" rows="2" placeholder="O2 kanul, pasang infus, resusitasi cairan, pemberian obat emergensi, dsb..."></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small text-warning-emphasis">Risiko Yang Mungkin Terjadi Selama Perjalanan</label>
                                            <textarea class="form-control" name="risiko_rujuk" id="pr_risiko_rujuk" rows="2" placeholder="Perubahan tanda vital, perburukan hemodinamik selama transportasi...">Perubahan tanda vital atau hemodinamik dalam perjalanan, mual/muntah, kejang.</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-danger fw-bold">Risiko Apabila Tidak Dirujuk / Menolak Dirujuk</label>
                                            <textarea class="form-control border-danger" name="risiko_tidak_rujuk" id="pr_risiko_tidak_rujuk" rows="2" placeholder="Komplikasi yang dapat timbul jika pasien tidak segera dirujuk...">Komplikasi penyakit berlanjut, kegagalan penanganan optimal karena keterbatasan sarana, perburukan klinis hingga risiko kecacatan permanen atau kematian.</textarea>
                                        </div>

                                        <!-- Alasan Menolak (Khusus jika Penolakan) -->
                                        <div class="col-12 d-none" id="containerAlasanMenolak">
                                            <label class="form-label small text-danger fw-bold required">Alasan Penolakan Rujukan oleh Pasien / Keluarga</label>
                                            <textarea class="form-control border-danger bg-danger-lt" name="alasan_menolak" id="pr_alasan_menolak" rows="2" placeholder="Jelaskan alasan pasien/keluarga menolak dirujuk (misal: kendala biaya, keluarga belum kumpul, memilih pengobatan alternatif, dsb)..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Identitas Pemberi Pernyataan (Pasien / Penanggung Jawab) -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <strong class="text-secondary"><i class="ti ti-user me-1"></i> 2. Identitas Yang Membuat Pernyataan</strong>
                                    <button type="button" class="btn btn-sm btn-outline-success" id="btnSalinDariPasien">
                                        <i class="ti ti-copy me-1"></i> Salin Dari Identitas Pasien
                                    </button>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Hubungan dengan Pasien</label>
                                            <select class="form-select" name="hubungan" id="pr_hubungan" required>
                                                <option value="Diri Sendiri">Diri Sendiri (Pasien)</option>
                                                <option value="Suami">Suami</option>
                                                <option value="Istri">Istri</option>
                                                <option value="Anak">Anak</option>
                                                <option value="Orang Tua">Orang Tua</option>
                                                <option value="Saudara Kandung">Saudara Kandung</option>
                                                <option value="Wali / Keluarga">Wali / Keluarga</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label required small">Nama Lengkap Penanggung Jawab</label>
                                            <input type="text" class="form-control" name="nama_pj" id="pr_nama_pj" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label required small">Jenis Kelamin</label>
                                            <select class="form-select" name="jk_pj" id="pr_jk_pj">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label small">Umur (Tahun)</label>
                                            <input type="text" class="form-control" name="umur_pj" id="pr_umur_pj" placeholder="Contoh: 35">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Nomor HP / WhatsApp</label>
                                            <input type="text" class="form-control" name="no_hp_pj" id="pr_no_hp_pj" placeholder="08xxxxxxxxxx">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small">Alamat Lengkap</label>
                                            <input type="text" class="form-control" name="alamat_pj" id="pr_alamat_pj" placeholder="Alamat domisili...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Dokter, Petugas, dan Saksi -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-id me-1"></i> 3. Tenaga Medis & Saksi</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Dokter Perujuk / Pelaksana</label>
                                            <select class="form-select select2" name="kd_dokter" id="pr_kd_dokter" required style="width: 100%;">
                                                <option value="">-- Pilih Dokter --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Petugas / Saksi Nakes</label>
                                            <input type="text" class="form-control bg-light" id="pr_display_petugas" readonly value="-">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Nama Saksi Keluarga / Pihak Pasien</label>
                                            <input type="text" class="form-control" name="nama_saksi" id="pr_nama_saksi" placeholder="Nama saksi dari keluarga...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Digital Signature (Tanda Tangan Elektronik) -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-pencil me-1"></i> 4. Tanda Tangan Digital</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <!-- TTD Penerima Pernyataan -->
                                        <div class="col-md-6 text-center">
                                            <label class="form-label small fw-bold mb-1">Tanda Tangan Pembuat Pernyataan</label>
                                            <div class="border rounded bg-white p-2 position-relative" style="height: 160px;">
                                                <canvas id="canvasPrTtdPenerima" width="350" height="140" style="touch-action: none; cursor: crosshair; display: block; margin: 0 auto; max-width: 100%;"></canvas>
                                            </div>
                                            <div class="mt-2 d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnClearPrTtdPenerima">
                                                    <i class="ti ti-eraser me-1"></i> Hapus
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openSignatureModal('pr_penerima')">
                                                    <i class="ti ti-maximize me-1"></i> Layar Penuh
                                                </button>
                                            </div>
                                        </div>

                                        <!-- TTD Saksi Keluarga -->
                                        <div class="col-md-6 text-center">
                                            <label class="form-label small fw-bold mb-1">Tanda Tangan Saksi Keluarga</label>
                                            <div class="border rounded bg-white p-2 position-relative" style="height: 160px;">
                                                <canvas id="canvasPrTtdSaksi" width="350" height="140" style="touch-action: none; cursor: crosshair; display: block; margin: 0 auto; max-width: 100%;"></canvas>
                                            </div>
                                            <div class="mt-2 d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnClearPrTtdSaksi">
                                                    <i class="ti ti-eraser me-1"></i> Hapus
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openSignatureModal('pr_saksi')">
                                                    <i class="ti ti-maximize me-1"></i> Layar Penuh
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Kolom Kanan: Riwayat Surat Persetujuan/Penolakan Rujukan Pasien Ini -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold"><i class="ti ti-history me-1"></i> Riwayat Formulir Rujukan</h6>
                                <button type="button" class="btn btn-xs btn-primary" id="btnBuatBaruRujukan">
                                    <i class="ti ti-plus me-1"></i> Formulir Baru
                                </button>
                            </div>
                            <div class="card-body p-2" style="max-height: 80vh; overflow-y: auto;">
                                <div id="listRiwayatRujukan" class="list-group list-group-flush">
                                    <div class="text-center py-4 text-muted">
                                        <i class="ti ti-file-search fs-1 mb-2 d-block"></i>
                                        Memuat riwayat persetujuan rujukan...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light py-2 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-danger d-none" id="btnHapusPersetujuanRujukan">
                        <i class="ti ti-trash me-1"></i> Hapus Formulir
                    </button>
                    <button type="button" class="btn btn-outline-secondary d-none" id="btnCetakPersetujuanRujukan">
                        <i class="ti ti-printer me-1"></i> Cetak PDF
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="btnSimpanPersetujuanRujukan">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Formulir Rujukan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Signature Layar Penuh -->
<div class="modal modal-blur fade" id="modalSignaturePadRujukan" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title" id="titleModalTtdRujukan">Tanda Tangan Digital</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-light">
                <canvas id="canvasModalTtdRujukan" width="700" height="300" style="touch-action: none; background: #fff; border: 2px dashed #0d6efd; border-radius: 8px; width: 100%; height: 280px; cursor: crosshair; display: block;"></canvas>
                <div class="text-muted small mt-2">Silakan goreskan tanda tangan menggunakan mouse, stylus, atau jari pada layar sentuh</div>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger" id="btnClearModalTtdRujukan">
                    <i class="ti ti-eraser me-1"></i> Bersihkan
                </button>
                <button type="button" class="btn btn-success" id="btnApplyModalTtdRujukan">
                    <i class="ti ti-check me-1"></i> Terapkan Tanda Tangan
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function () {
        const modalPr = $('#modalPersetujuanRujukan');
        const formPr = $('#formPersetujuanRujukan');

        // State signature
        let isDrawingPrPenerima = false;
        let isDrawingPrSaksi = false;
        let hasSigPrPenerima = false;
        let hasSigPrSaksi = false;

        const canvasPrPenerima = document.getElementById('canvasPrTtdPenerima');
        const canvasPrSaksi = document.getElementById('canvasPrTtdSaksi');
        const ctxPrPenerima = canvasPrPenerima ? canvasPrPenerima.getContext('2d') : null;
        const ctxPrSaksi = canvasPrSaksi ? canvasPrSaksi.getContext('2d') : null;

        let activeTtdTarget = 'penerima';
        let isDrawingModalRujuk = false;
        const canvasModalRujuk = document.getElementById('canvasModalTtdRujukan');
        const ctxModalRujuk = canvasModalRujuk ? canvasModalRujuk.getContext('2d') : null;

        // Init Canvas Drawing Helper
        function initSignaturePadHelper(canvas, ctx, setDrawing, setHasSig) {
            if (!canvas || !ctx) return;
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#000000';

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: (clientX - rect.left) * scaleX,
                    y: (clientY - rect.top) * scaleY
                };
            }

            function startDraw(e) {
                if (e.cancelable) e.preventDefault();
                setDrawing(true);
                setHasSig(true);
                const pos = getPos(e);
                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
            }

            function draw(e) {
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            }

            function stopDraw() {
                setDrawing(false);
            }

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', (e) => { if (e.buttons === 1) draw(e); });
            canvas.addEventListener('mouseup', stopDraw);
            canvas.addEventListener('mouseleave', stopDraw);

            canvas.addEventListener('touchstart', startDraw, { passive: false });
            canvas.addEventListener('touchmove', (e) => { if (e.cancelable) e.preventDefault(); draw(e); }, { passive: false });
            canvas.addEventListener('touchend', stopDraw);
        }

        function clearCanvasHelper(canvas, ctx, setHasSig) {
            if (!canvas || !ctx) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            setHasSig(false);
        }

        initSignaturePadHelper(canvasPrPenerima, ctxPrPenerima, (v) => { isDrawingPrPenerima = v; }, (v) => { hasSigPrPenerima = v; });
        initSignaturePadHelper(canvasPrSaksi, ctxPrSaksi, (v) => { isDrawingPrSaksi = v; }, (v) => { hasSigPrSaksi = v; });

        $('#btnClearPrTtdPenerima').on('click', function () {
            clearCanvasHelper(canvasPrPenerima, ctxPrPenerima, (v) => { hasSigPrPenerima = v; });
            $('#pr_ttd_penerima').val('');
        });

        $('#btnClearPrTtdSaksi').on('click', function () {
            clearCanvasHelper(canvasPrSaksi, ctxPrSaksi, (v) => { hasSigPrSaksi = v; });
            $('#pr_ttd_saksi').val('');
        });

        // Modal Signature Layar Penuh
        if (canvasModalRujuk && ctxModalRujuk) {
            ctxModalRujuk.lineWidth = 3;
            ctxModalRujuk.lineCap = 'round';
            ctxModalRujuk.lineJoin = 'round';
            ctxModalRujuk.strokeStyle = '#000000';

            function getModalPos(e) {
                const rect = canvasModalRujuk.getBoundingClientRect();
                const scaleX = canvasModalRujuk.width / rect.width;
                const scaleY = canvasModalRujuk.height / rect.height;
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                return {
                    x: (clientX - rect.left) * scaleX,
                    y: (clientY - rect.top) * scaleY
                };
            }

            canvasModalRujuk.addEventListener('mousedown', (e) => {
                isDrawingModalRujuk = true;
                const pos = getModalPos(e);
                ctxModalRujuk.beginPath();
                ctxModalRujuk.moveTo(pos.x, pos.y);
            });
            canvasModalRujuk.addEventListener('mousemove', (e) => {
                if (e.buttons === 1 && isDrawingModalRujuk) {
                    const pos = getModalPos(e);
                    ctxModalRujuk.lineTo(pos.x, pos.y);
                    ctxModalRujuk.stroke();
                }
            });
            canvasModalRujuk.addEventListener('mouseup', () => { isDrawingModalRujuk = false; });
            canvasModalRujuk.addEventListener('mouseleave', () => { isDrawingModalRujuk = false; });

            canvasModalRujuk.addEventListener('touchstart', (e) => {
                if (e.cancelable) e.preventDefault();
                isDrawingModalRujuk = true;
                const pos = getModalPos(e);
                ctxModalRujuk.beginPath();
                ctxModalRujuk.moveTo(pos.x, pos.y);
            }, { passive: false });
            canvasModalRujuk.addEventListener('touchmove', (e) => {
                if (e.cancelable) e.preventDefault();
                if (isDrawingModalRujuk) {
                    const pos = getModalPos(e);
                    ctxModalRujuk.lineTo(pos.x, pos.y);
                    ctxModalRujuk.stroke();
                }
            }, { passive: false });
            canvasModalRujuk.addEventListener('touchend', () => { isDrawingModalRujuk = false; });
        }

        window.openSignatureModal = function (target) {
            activeTtdTarget = target;
            const modalTitle = target === 'pr_penerima' ? 'Tanda Tangan Pembuat Pernyataan' : 'Tanda Tangan Saksi Keluarga';
            $('#titleModalTtdRujukan').text(modalTitle);
            if (canvasModalRujuk && ctxModalRujuk) {
                ctxModalRujuk.clearRect(0, 0, canvasModalRujuk.width, canvasModalRujuk.height);
                const src = target === 'pr_penerima' ? canvasPrPenerima : canvasPrSaksi;
                if (src) ctxModalRujuk.drawImage(src, 0, 0, canvasModalRujuk.width, canvasModalRujuk.height);
            }
            $('#modalSignaturePadRujukan').modal('show');
        };

        $('#btnClearModalTtdRujukan').on('click', function () {
            if (canvasModalRujuk && ctxModalRujuk) {
                ctxModalRujuk.clearRect(0, 0, canvasModalRujuk.width, canvasModalRujuk.height);
            }
        });

        $('#btnApplyModalTtdRujukan').on('click', function () {
            if (canvasModalRujuk) {
                const targetCanvas = activeTtdTarget === 'pr_penerima' ? canvasPrPenerima : canvasPrSaksi;
                const targetCtx = activeTtdTarget === 'pr_penerima' ? ctxPrPenerima : ctxPrSaksi;
                if (targetCanvas && targetCtx) {
                    targetCtx.clearRect(0, 0, targetCanvas.width, targetCanvas.height);
                    targetCtx.drawImage(canvasModalRujuk, 0, 0, targetCanvas.width, targetCanvas.height);
                    if (activeTtdTarget === 'pr_penerima') hasSigPrPenerima = true;
                    else hasSigPrSaksi = true;
                }
            }
            $('#modalSignaturePadRujukan').modal('hide');
        });

        // Template Faskes Tujuan
        $('.faskes-opt').on('click', function () {
            $('#pr_faskes_tujuan').val($(this).text());
        });

        // Toggle Persetujuan vs Penolakan
        $('input[name="jenis"]').on('change', function () {
            if ($(this).val() === 'Penolakan') {
                $('#cardJenisPernyataan').css('border-color', '#dc3545');
                $('#alertPenolakanInfo').removeClass('d-none');
                $('#containerAlasanMenolak').removeClass('d-none');
                $('#pr_alasan_menolak').prop('required', true);
            } else {
                $('#cardJenisPernyataan').css('border-color', '#198754');
                $('#alertPenolakanInfo').addClass('d-none');
                $('#containerAlasanMenolak').addClass('d-none');
                $('#pr_alasan_menolak').prop('required', false);
            }
        });

        // Salin Dari Data Pasien
        $('#btnSalinDariPasien').on('click', function () {
            const no_rawat = $('#pr_no_rawat').val();
            if (!no_rawat) return;

            getRegDetail(no_rawat).done((res) => {
                if (res && res.pasien) {
                    const p = res.pasien;
                    $('#pr_hubungan').val('Diri Sendiri');
                    $('#pr_nama_pj').val(p.nm_pasien);
                    $('#pr_jk_pj').val(p.jk || 'L');
                    $('#pr_alamat_pj').val(p.alamat || '-');
                    $('#pr_no_hp_pj').val(p.no_tlp || '-');
                    if (p.tgl_lahir) {
                        const birth = new Date(p.tgl_lahir);
                        const diff = new Date().getFullYear() - birth.getFullYear();
                        $('#pr_umur_pj').val(diff);
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Pasien Disalin',
                        text: 'Identitas penanggung jawab berhasil diisi dengan data diri pasien.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        // Reset Form
        function resetFormPersetujuanRujukan() {
            formPr[0].reset();
            $('#pr_no_surat').val('');
            $('#radioPersetujuanRujuk').prop('checked', true).trigger('change');

            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            $('#pr_tanggal').val(`${year}-${month}-${day}T${hours}:${minutes}`);

            clearCanvasHelper(canvasPrPenerima, ctxPrPenerima, (v) => { hasSigPrPenerima = v; });
            clearCanvasHelper(canvasPrSaksi, ctxPrSaksi, (v) => { hasSigPrSaksi = v; });
            $('#pr_ttd_penerima').val('');
            $('#pr_ttd_saksi').val('');

            $('#btnCetakPersetujuanRujukan').addClass('d-none');
            $('#btnHapusPersetujuanRujukan').addClass('d-none');
        }

        // Buka Modal Utama
        window.bukaPersetujuanRujukan = function (no_rawat) {
            resetFormPersetujuanRujukan();
            $('#pr_no_rawat').val(no_rawat);
            modalPr.modal('show');

            // Ambil Detail Pasien & Reg
            getRegDetail(no_rawat).done((res) => {
                const { pasien, dokter } = res;
                $('#pr_display_no_rawat').text(no_rawat);
                $('#pr_display_no_rkm_medis').text(res.no_rkm_medis);
                $('#pr_display_nm_pasien').text(pasien?.nm_pasien || '-');
                const umur = res.umurdaftar ? `${res.umurdaftar} ${res.sttsumur}` : '-';
                $('#pr_display_umur_jk').text(`${umur} / ${pasien?.jk === 'L' ? 'Laki-laki' : 'Perempuan'}`);

                // Load Select Dokter
                populateDokterSelect(res.kd_dokter);

                // Default Penerima Informasi = Pasien
                $('#pr_hubungan').val('Diri Sendiri');
                $('#pr_nama_pj').val(pasien?.nm_pasien || '');
                $('#pr_jk_pj').val(pasien?.jk || 'L');
                $('#pr_alamat_pj').val(pasien?.alamat || '-');
                $('#pr_no_hp_pj').val(pasien?.no_tlp || '-');
                if (pasien?.tgl_lahir) {
                    const diff = new Date().getFullYear() - new Date(pasien.tgl_lahir).getFullYear();
                    $('#pr_umur_pj').val(diff);
                }

                // Petugas / User Login
                const userLogin = '{{ session()->get("pegawai")->nama ?? session()->get("username") ?? "Petugas Medis" }}';
                $('#pr_display_petugas').val(userLogin);
                $('#pr_nip').val('{{ session()->get("pegawai")->nik ?? session()->get("nik") ?? "-" }}');
            });

            // Tarik Referensi Rujukan Otomatis
            syncReferensiRujukan(no_rawat);

            // Load Riwayat
            loadRiwayatRujukan(no_rawat);
        };

        // Populate Dropdown Dokter
        function populateDokterSelect(selectedKd) {
            $.ajax({
                url: '/dokter/get',
                type: 'GET',
                success: function (res) {
                    const sel = $('#pr_kd_dokter');
                    sel.empty().append('<option value="">-- Pilih Dokter --</option>');
                    if (Array.isArray(res)) {
                        res.forEach(d => {
                            const isSel = (d.kd_dokter === selectedKd) ? 'selected' : '';
                            sel.append(`<option value="${d.kd_dokter}" ${isSel}>${d.nm_dokter}</option>`);
                        });
                    }
                }
            });
        }

        // Tarik Data Rujukan & Diagnosa
        function syncReferensiRujukan(no_rawat) {
            $.ajax({
                url: `/erm/persetujuan-rujukan/referensi/${encodeURIComponent(no_rawat)}`,
                type: 'GET',
                success: function (res) {
                    if (res.success) {
                        if (res.diagnosa && !$('#pr_diagnosa').val()) {
                            $('#pr_diagnosa').val(res.diagnosa);
                        }
                        if (res.rujukan) {
                            if (res.rujukan.rujuk_ke && !$('#pr_faskes_tujuan').val()) {
                                $('#pr_faskes_tujuan').val(res.rujukan.rujuk_ke);
                            }
                            if (res.rujukan.kat_rujuk) {
                                $('#pr_bagian_tujuan').val(res.rujukan.kat_rujuk);
                            }
                            if (res.rujukan.ambulance) {
                                $('#pr_transportasi').val(res.rujukan.ambulance === 'Mobil Ambulan' ? 'Ambulans Medis (Lengkap Alat & Obat)' : res.rujukan.ambulance);
                            }
                            if (res.rujukan.keterangan && !$('#pr_alasan_rujuk').val()) {
                                $('#pr_alasan_rujuk').val(res.rujukan.keterangan);
                            }
                        }
                        if (res.tindakan_stabilisasi && !$('#pr_tindakan_stabilisasi').val()) {
                            $('#pr_tindakan_stabilisasi').val(res.tindakan_stabilisasi);
                        }
                    }
                }
            });
        }

        $('#btnSyncReferensiRujukan').on('click', function () {
            const no_rawat = $('#pr_no_rawat').val();
            if (no_rawat) {
                syncReferensiRujukan(no_rawat);
                Swal.fire({
                    icon: 'success',
                    title: 'Sinkronisasi Berhasil',
                    text: 'Data rujukan & diagnosa berhasil ditarik.',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });

        // Load Riwayat Rujukan Pasien
        function loadRiwayatRujukan(no_rawat) {
            const listContainer = $('#listRiwayatRujukan');
            listContainer.html('<div class="text-center py-4 text-muted"><i class="ti ti-loader animate-spin fs-1 mb-2 d-block"></i>Memuat riwayat...</div>');

            $.ajax({
                url: `/erm/persetujuan-rujukan/list/${encodeURIComponent(no_rawat)}`,
                type: 'GET',
                success: function (res) {
                    if (res.success && res.data.length > 0) {
                        let html = '';
                        res.data.forEach((item) => {
                            const isSetuju = (item.jenis === 'Persetujuan');
                            const badgeColor = isSetuju ? 'bg-success-lt text-success' : 'bg-danger-lt text-danger';
                            const badgeIcon = isSetuju ? 'ti-check' : 'ti-x';
                            html += `
                                <div class="list-group-item list-group-item-action p-2 mb-2 rounded border item-riwayat-rujuk" data-nosurat="${item.no_surat}" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge ${badgeColor}"><i class="ti ${badgeIcon} me-1"></i>${item.jenis}</span>
                                        <span class="text-muted small">${item.tanggal ? item.tanggal.substring(0, 16) : '-'}</span>
                                    </div>
                                    <div class="fw-bold text-dark fs-5 mb-1">${item.faskes_tujuan}</div>
                                    <div class="text-muted small mb-1"><i class="ti ti-file-text me-1"></i>No. Surat: <strong>${item.no_surat}</strong></div>
                                    <div class="text-muted small mb-2"><i class="ti ti-user me-1"></i>PJ: ${item.nama_pj} (${item.hubungan})</div>
                                    <div class="d-flex gap-1 justify-content-end">
                                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="editPersetujuanRujukan('${item.no_surat}')">
                                            <i class="ti ti-eye me-1"></i> Buka / Edit
                                        </button>
                                        <a href="/erm/persetujuan-rujukan/print/${item.no_surat}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                            <i class="ti ti-printer me-1"></i> Cetak
                                        </a>
                                    </div>
                                </div>
                            `;
                        });
                        listContainer.html(html);
                    } else {
                        listContainer.html(`
                            <div class="text-center py-4 text-muted">
                                <i class="ti ti-folder-off fs-1 mb-2 d-block"></i>
                                Belum ada formulir rujukan untuk nomor rawat ini.
                            </div>
                        `);
                    }
                },
                error: function () {
                    listContainer.html('<div class="text-center py-3 text-danger"><i class="ti ti-alert-triangle me-1"></i>Gagal memuat riwayat.</div>');
                }
            });
        }

        // Buka / Edit Detail Riwayat
        window.editPersetujuanRujukan = function (no_surat) {
            $.ajax({
                url: `/erm/persetujuan-rujukan/show/${encodeURIComponent(no_surat)}`,
                type: 'GET',
                success: function (res) {
                    if (res.success && res.data) {
                        const d = res.data;
                        $('#pr_no_surat').val(d.no_surat);
                        $('#pr_no_rawat').val(d.no_rawat);
                        if (d.jenis === 'Penolakan') {
                            $('#radioPenolakanRujuk').prop('checked', true).trigger('change');
                            $('#pr_alasan_menolak').val(d.alasan_menolak || '');
                        } else {
                            $('#radioPersetujuanRujuk').prop('checked', true).trigger('change');
                        }

                        if (d.tanggal) {
                            $('#pr_tanggal').val(d.tanggal.replace(' ', 'T').substring(0, 16));
                        }

                        $('#pr_faskes_tujuan').val(d.faskes_tujuan);
                        $('#pr_bagian_tujuan').val(d.bagian_tujuan || '');
                        $('#pr_transportasi').val(d.transportasi || 'Ambulans Medis (Lengkap Alat & Obat)');
                        $('#pr_pendamping').val(d.pendamping || 'Dokter & Perawat');
                        $('#pr_diagnosa').val(d.diagnosa);
                        $('#pr_alasan_rujuk').val(d.alasan_rujuk);
                        $('#pr_tindakan_stabilisasi').val(d.tindakan_stabilisasi);
                        $('#pr_risiko_rujuk').val(d.risiko_rujuk);
                        $('#pr_risiko_tidak_rujuk').val(d.risiko_tidak_rujuk);

                        $('#pr_hubungan').val(d.hubungan);
                        $('#pr_nama_pj').val(d.nama_pj);
                        $('#pr_jk_pj').val(d.jk_pj);
                        $('#pr_umur_pj').val(d.umur_pj);
                        $('#pr_alamat_pj').val(d.alamat_pj);
                        $('#pr_no_hp_pj').val(d.no_hp_pj);

                        $('#pr_kd_dokter').val(d.kd_dokter).trigger('change');
                        $('#pr_nama_saksi').val(d.nama_saksi || '');

                        // Load Signatures
                        clearCanvasHelper(canvasPrPenerima, ctxPrPenerima, (v) => { hasSigPrPenerima = v; });
                        clearCanvasHelper(canvasPrSaksi, ctxPrSaksi, (v) => { hasSigPrSaksi = v; });

                        if (d.ttd_penerima) {
                            const img = new Image();
                            img.src = `/${d.ttd_penerima}`;
                            img.onload = () => {
                                ctxPrPenerima.drawImage(img, 0, 0, canvasPrPenerima.width, canvasPrPenerima.height);
                                hasSigPrPenerima = true;
                            };
                        }
                        if (d.ttd_saksi) {
                            const imgS = new Image();
                            imgS.src = `/${d.ttd_saksi}`;
                            imgS.onload = () => {
                                ctxPrSaksi.drawImage(imgS, 0, 0, canvasPrSaksi.width, canvasPrSaksi.height);
                                hasSigPrSaksi = true;
                            };
                        }

                        // Tampilkan tombol Cetak & Hapus
                        $('#btnCetakPersetujuanRujukan').removeClass('d-none').off('click').on('click', function () {
                            window.open(`/erm/persetujuan-rujukan/print/${d.no_surat}`, '_blank');
                        });
                        $('#btnHapusPersetujuanRujukan').removeClass('d-none');
                    }
                }
            });
        };

        // Tombol Buat Baru
        $('#btnBuatBaruRujukan').on('click', function () {
            const no_rawat = $('#pr_no_rawat').val();
            resetFormPersetujuanRujukan();
            $('#pr_no_rawat').val(no_rawat);
            syncReferensiRujukan(no_rawat);
        });

        // Simpan Form
        $('#btnSimpanPersetujuanRujukan').on('click', function () {
            if (!formPr[0].checkValidity()) {
                formPr[0].reportValidity();
                return;
            }

            // Export signatures
            if (hasSigPrPenerima && canvasPrPenerima) {
                $('#pr_ttd_penerima').val(canvasPrPenerima.toDataURL('image/png'));
            }
            if (hasSigPrSaksi && canvasPrSaksi) {
                $('#pr_ttd_saksi').val(canvasPrSaksi.toDataURL('image/png'));
            }

            const formData = formPr.serialize();

            Swal.fire({
                title: 'Menyimpan Formulir...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: '/erm/persetujuan-rujukan',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    Swal.close();
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        $('#pr_no_surat').val(res.no_surat);
                        $('#btnCetakPersetujuanRujukan').removeClass('d-none').off('click').on('click', function () {
                            window.open(`/erm/persetujuan-rujukan/print/${res.no_surat}`, '_blank');
                        });
                        $('#btnHapusPersetujuanRujukan').removeClass('d-none');
                        loadRiwayatRujukan($('#pr_no_rawat').val());
                    } else {
                        Swal.fire('Gagal!', res.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function (xhr) {
                    Swal.close();
                    let msg = 'Gagal menyimpan formulir rujukan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });

        // Hapus Form
        $('#btnHapusPersetujuanRujukan').on('click', function () {
            const no_surat = $('#pr_no_surat').val();
            if (!no_surat) return;

            Swal.fire({
                title: 'Hapus Formulir Rujukan?',
                text: `Surat nomor ${no_surat} akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    $.ajax({
                        url: '/erm/persetujuan-rujukan/delete',
                        type: 'POST',
                        data: { no_surat: no_surat },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire('Terhapus!', res.message, 'success');
                                const no_rawat = $('#pr_no_rawat').val();
                                resetFormPersetujuanRujukan();
                                $('#pr_no_rawat').val(no_rawat);
                                loadRiwayatRujukan(no_rawat);
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.close();
                            Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menghapus.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

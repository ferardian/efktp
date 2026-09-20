<div class="modal modal-blur fade" id="modalKajianPraAnestesi" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-heart-rate-monitor me-2 fs-2"></i>
                    Kajian Pra Anestesi — Evaluasi Status Fisik & Perencanaan Anestesi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <!-- Kolom Form Utama -->
                    <div class="col-lg-8 col-md-12">
                        <!-- Card Banner Pasien & Smart TTV Lookup -->
                        <div class="card card-sm mb-3 border-primary-subtle shadow-sm">
                            <div class="card-body p-2 bg-light-subtle">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small mb-0">No. Rawat / No. RM</label>
                                        <div class="fw-bold fs-4 text-primary" id="pa_display_no_rawat">-</div>
                                        <div class="text-muted small" id="pa_display_no_rkm_medis">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-0">Pasien</label>
                                        <div class="fw-bold fs-4 text-dark" id="pa_display_nm_pasien">-</div>
                                        <div class="text-muted small" id="pa_display_umur_jk">-</div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSyncTtvPraAnestesi" title="Tarik data TTV & Diagnosa terakhir">
                                            <i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik TTV & Diagnosa Terkini
                                        </button>
                                        <div class="small text-muted mt-1" id="pa_ttv_preview_badge">TTV: -</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="formKajianPraAnestesi">
                            <input type="hidden" name="no_rawat" id="pa_no_rawat">
                            <input type="hidden" name="tanggal_lama" id="pa_tanggal_lama">

                            <!-- Section 1: Tanggal, Dokter & Operasi -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-calendar-event me-1"></i> 1. Informasi Pemeriksaan & Rencana Pembedahan</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Tanggal & Jam Asesmen</label>
                                            <input type="datetime-local" class="form-control" name="tanggal" id="pa_tanggal" style="height: 38px;" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Jadwal Tanggal & Jam Operasi</label>
                                            <input type="datetime-local" class="form-control" name="tanggal_operasi" id="pa_tanggal_operasi" style="height: 38px;">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label required small">Dokter Spesialis Anestesi</label>
                                            <div class="input-group" style="height: 38px;">
                                                <input type="text" class="form-control" style="max-width: 85px; height: 38px;" name="kd_dokter" id="pa_kd_dokter" readonly required placeholder="Kode">
                                                <input type="text" class="form-control" style="height: 38px;" name="nm_dokter" id="pa_nm_dokter" readonly placeholder="Nama Dokter">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label required small">Diagnosis Pre-Operasi</label>
                                            <input type="text" class="form-control form-control-sm" name="diagnosa" id="pa_diagnosa" placeholder="Diagnosa..." required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required small">Rencana Tindakan Pembedahan</label>
                                            <input type="text" class="form-control form-control-sm" name="rencana_tindakan" id="pa_rencana_tindakan" placeholder="Rencana tindakan..." required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Tanda-Tanda Vital & Pemeriksaan Fisik -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-activity me-1"></i> 2. Tanda-Tanda Vital (TTV)</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">TD (mmHg)</label>
                                            <input type="text" class="form-control form-control-sm" name="td" id="pa_td" placeholder="120/80">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">Nadi (x/mnt)</label>
                                            <input type="text" class="form-control form-control-sm" name="nadi" id="pa_nadi" placeholder="80">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">RR (x/mnt)</label>
                                            <input type="text" class="form-control form-control-sm" name="pernapasan" id="pa_pernapasan" placeholder="20">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">Suhu (°C)</label>
                                            <input type="text" class="form-control form-control-sm" name="suhu" id="pa_suhu" placeholder="36.5">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">SpO2 (%)</label>
                                            <input type="text" class="form-control form-control-sm" name="io2" id="pa_io2" placeholder="98">
                                        </div>
                                        <div class="col-md-1 col-sm-2">
                                            <label class="form-label small">TB (cm)</label>
                                            <input type="text" class="form-control form-control-sm" name="tb" id="pa_tb" placeholder="160">
                                        </div>
                                        <div class="col-md-1 col-sm-2">
                                            <label class="form-label small">BB (kg)</label>
                                            <input type="text" class="form-control form-control-sm" name="bb" id="pa_bb" placeholder="60">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Pemeriksaan Fisik Khusus Organ -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-notes me-1"></i> 3. Pemeriksaan Sistem Organ</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Kardiovaskuler</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_cardiovasculer" id="pa_fisik_cardiovasculer" value="Normal">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Paru / Pernapasan</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_paru" id="pa_fisik_paru" value="Normal">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Abdomen</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_abdomen" id="pa_fisik_abdomen" value="Normal">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Ekstremitas</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_extrimitas" id="pa_fisik_extrimitas" value="Normal">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Endokrin</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_endokrin" id="pa_fisik_endokrin" value="Normal">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Ginjal / Saluran Kemih</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_ginjal" id="pa_fisik_ginjal" value="Normal">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Obat-obatan yang Dikonsumsi</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_obatobatan" id="pa_fisik_obatobatan" value="-">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Hasil Laborat</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_laborat" id="pa_fisik_laborat" value="Dalam Batas Normal">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Hasil Penunjang Lain</label>
                                            <input type="text" class="form-control form-control-sm" name="fisik_penunjang" id="pa_fisik_penunjang" value="-">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Riwayat Penyakit, Alergi & Kebiasaan -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-alert-triangle me-1"></i> 4. Riwayat Penyakit, Alergi & Kebiasaan</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Alergi Obat</label>
                                            <input type="text" class="form-control form-control-sm" name="riwayat_penyakit_alergiobat" id="pa_riwayat_penyakit_alergiobat" value="Tidak Ada">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Alergi Lainnya (Makanan/Debu)</label>
                                            <input type="text" class="form-control form-control-sm" name="riwayat_penyakit_alergilainnya" id="pa_riwayat_penyakit_alergilainnya" value="Tidak Ada">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Terapi Berjalan</label>
                                            <input type="text" class="form-control form-control-sm" name="riwayat_penyakit_terapi" id="pa_riwayat_penyakit_terapi" value="-">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Merokok</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-select form-select-sm w-50" name="riwayat_kebiasaan_merokok" id="pa_riwayat_kebiasaan_merokok">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm w-50" name="riwayat_kebiasaan_ket_merokok" id="pa_riwayat_kebiasaan_ket_merokok" placeholder="Ket...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Alkohol</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-select form-select-sm w-50" name="riwayat_kebiasaan_alkohol" id="pa_riwayat_kebiasaan_alkohol">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm w-50" name="riwayat_kebiasaan_ket_alkohol" id="pa_riwayat_kebiasaan_ket_alkohol" placeholder="Ket...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Obat / Jamu</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-select form-select-sm w-50" name="riwayat_kebiasaan_obat" id="pa_riwayat_kebiasaan_obat">
                                                    <option value="-">-</option>
                                                    <option value="Obat Obatan">Obat</option>
                                                    <option value="Vitamin">Vitamin</option>
                                                    <option value="Jamu Jamuan">Jamu</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm w-50" name="riwayat_kebiasaan_ket_obat" id="pa_riwayat_kebiasaan_ket_obat" placeholder="Ket...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 5: Status ASA, Rencana Anestesi & Instruksi Puasa -->
                            <div class="card mb-3 shadow-sm border-2 border-primary-subtle">
                                <div class="card-header py-2 bg-primary-lt">
                                    <strong class="text-primary"><i class="ti ti-shield-check me-1"></i> 5. Keputusan Status ASA & Rencana Anestesi</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Klasifikasi Status Fisik ASA</label>
                                            <select class="form-select form-select-sm fw-bold text-primary" name="asa" id="pa_asa" required>
                                                <option value="1">ASA 1 (Pasien Sehat Normal)</option>
                                                <option value="2" selected>ASA 2 (Penyakit Sistemik Ringan-Sedang)</option>
                                                <option value="3">ASA 3 (Penyakit Sistemik Berat Beraktivitas Terbatas)</option>
                                                <option value="4">ASA 4 (Penyakit Sistemik Mengancam Jiwa)</option>
                                                <option value="5">ASA 5 (Pasien Sekarat / Moribund)</option>
                                                <option value="E">ASA E (Emergency / Kasus Cito)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label required small">Rencana Teknik Anestesi</label>
                                            <select class="form-select form-select-sm fw-bold" name="rencana_anestesi" id="pa_rencana_anestesi" required>
                                                <option value="GA">General Anesthesia (GA)</option>
                                                <option value="RA Spinal" selected>Regional Anesthesia - Spinal</option>
                                                <option value="RA Epidural">Regional Anesthesia - Epidural</option>
                                                <option value="RA Combined">Combined Spinal-Epidural</option>
                                                <option value="Blok Syaraf">Blok Syaraf Perifer</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Mulai Puasa</label>
                                            <input type="datetime-local" class="form-control form-control-sm" name="puasa" id="pa_puasa">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label small">Rencana Perawatan Pasca Operasi</label>
                                            <input type="text" class="form-control form-control-sm" name="rencana_perawatan" id="pa_rencana_perawatan" value="Ruang Rawat Inap Biasa">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Catatan Khusus Dokter Anestesi</label>
                                            <input type="text" class="form-control form-control-sm" name="catatan_khusus" id="pa_catatan_khusus" placeholder="Catatan khusus jalan nafas / intubasi sulit / dll...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Tutup
                                </button>
                                <div class="gap-2 d-flex">
                                    <button type="button" class="btn btn-outline-success d-none" id="btnCetakPraAnestesi">
                                        <i class="ti ti-printer me-1"></i> Cetak Kajian Pra Anestesi
                                    </button>
                                    <button type="button" class="btn btn-danger d-none" id="btnHapusPraAnestesi">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btnSimpanPraAnestesi">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Kajian Pra Anestesi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Kolom Riwayat Kajian Pra Anestesi -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card shadow-sm sticky-top" style="top: 10px;">
                            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                <strong class="text-secondary"><i class="ti ti-history me-1"></i> Riwayat Pra Anestesi</strong>
                                <button type="button" class="btn btn-xs btn-outline-primary" id="btnTambahBaruPraAnestesi">
                                    <i class="ti ti-plus me-1"></i> Buat Baru
                                </button>
                            </div>
                            <div class="card-body p-2" id="containerRiwayatPraAnestesi" style="max-height: 75vh; overflow-y: auto;">
                                <div class="text-center text-muted p-3">Memuat riwayat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    #pa_tanggal,
    #pa_tanggal_operasi,
    #pa_kd_dokter,
    #pa_nm_dokter {
        height: 38px !important;
        min-height: 38px !important;
        line-height: 1.5 !important;
    }
</style>
@endpush

@push('script')
<script>
    $(document).ready(function () {
        const modalKajianPraAnestesi = $('#modalKajianPraAnestesi');
        const formKajianPraAnestesi = $('#formKajianPraAnestesi');

        function setNowDateTimePraAnestesi() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#pa_tanggal').val(now.toISOString().slice(0, 16));
        }

        function resetFormPraAnestesi() {
            formKajianPraAnestesi.trigger('reset');
            setNowDateTimePraAnestesi();
            $('#pa_tanggal_lama').val('');
            $('#btnCetakPraAnestesi').addClass('d-none');
            $('#btnHapusPraAnestesi').addClass('d-none');
        }

        window.bukaKajianPraAnestesi = function (no_rawat) {
            resetFormPraAnestesi();
            $('#pa_no_rawat').val(no_rawat);
            modalKajianPraAnestesi.modal('show');

            getRegDetail(no_rawat).done((res) => {
                const { pasien, dokter } = res;
                $('#pa_display_no_rawat').text(no_rawat);
                $('#pa_display_no_rkm_medis').text(res.no_rkm_medis);
                $('#pa_display_nm_pasien').text(pasien?.nm_pasien || '-');
                $('#pa_display_umur_jk').text(`${formatTanggal(pasien?.tgl_lahir)} / ${pasien?.jk === 'L' ? 'Laki-laki' : 'Perempuan'}`);

                $('#pa_kd_dokter').val(res.kd_dokter);
                $('#pa_nm_dokter').val(dokter?.nm_dokter || '-');
            });

            syncTtvPraAnestesi(no_rawat, false);
            muatRiwayatPraAnestesi(no_rawat);
        };

        function syncTtvPraAnestesi(no_rawat, showNotification = true) {
            $.get(`{{ url('/pemeriksaan/ttv/latest') }}/${encodeURIComponent(no_rawat)}`)
                .done((res) => {
                    if (res.success && res.data) {
                        const ttv = res.data;
                        const badge = `TD: ${ttv.td} | HR: ${ttv.nadi}x/m | RR: ${ttv.rr}x/m | SpO2: ${ttv.spo2}% | T: ${ttv.suhu}°C`;
                        $('#pa_ttv_preview_badge').html(`<span class="badge bg-green-lt text-dark border">${badge}</span>`);

                        if (showNotification) {
                            $('#pa_td').val(ttv.td).addClass('border-primary bg-primary-lt');
                            $('#pa_nadi').val(ttv.nadi).addClass('border-primary bg-primary-lt');
                            $('#pa_pernapasan').val(ttv.rr).addClass('border-primary bg-primary-lt');
                            $('#pa_suhu').val(ttv.suhu).addClass('border-primary bg-primary-lt');
                            $('#pa_io2').val(ttv.spo2).addClass('border-primary bg-primary-lt');
                            $('#pa_tb').val(ttv.tb).addClass('border-primary bg-primary-lt');
                            $('#pa_bb').val(ttv.bb).addClass('border-primary bg-primary-lt');

                            if (ttv.penilaian && ttv.penilaian !== '-') {
                                $('#pa_diagnosa').val(ttv.penilaian).addClass('border-primary bg-primary-lt');
                            }
                            if (ttv.alergi && ttv.alergi !== '-') {
                                $('#pa_riwayat_penyakit_alergiobat').val(ttv.alergi).addClass('border-primary bg-primary-lt');
                            }

                            setTimeout(() => {
                                $('#pa_td, #pa_nadi, #pa_pernapasan, #pa_suhu, #pa_io2, #pa_tb, #pa_bb, #pa_diagnosa, #pa_riwayat_penyakit_alergiobat').removeClass('border-primary bg-primary-lt');
                            }, 2000);

                            showToast('TTV & Diagnosa Terkini Berhasil Ditarik!', 'success');
                        }
                    } else {
                        $('#pa_ttv_preview_badge').html('<span class="text-muted small">Belum ada catatan TTV sebelumnya</span>');
                        if (showNotification) showToast('Belum ada data TTV sebelumnya.', 'info');
                    }
                });
        }

        $('#btnSyncTtvPraAnestesi').on('click', () => {
            const no_rawat = $('#pa_no_rawat').val();
            if (no_rawat) syncTtvPraAnestesi(no_rawat, true);
        });

        function muatRiwayatPraAnestesi(no_rawat) {
            const container = $('#containerRiwayatPraAnestesi');
            container.html('<div class="text-center text-muted p-3"><div class="spinner-border spinner-border-sm me-1"></div> Memuat riwayat...</div>');

            $.get(`{{ url('/erm/pra-anestesi/list') }}/${encodeURIComponent(no_rawat)}`, { no_rawat: no_rawat })
                .done((res) => {
                    if (!res.success || !res.data || res.data.length === 0) {
                        container.html('<div class="text-muted text-center p-3 small"><i class="ti ti-notes-off me-1"></i> Belum ada kajian pra anestesi untuk kunjungan ini.</div>');
                        return;
                    }

                    let html = '<div class="list-group list-group-flush">';
                    res.data.forEach((item) => {
                        html += `
                            <div class="list-group-item list-group-item-action p-2 cursor-pointer border rounded mb-2 shadow-2xs item-riwayat-pa" data-tanggal="${item.tanggal}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-primary">${item.rencana_anestesi || 'Anestesi'} (ASA ${item.asa || '-'})</div>
                                        <div class="small text-muted"><i class="ti ti-calendar me-1"></i> ${item.tanggal}</div>
                                        <div class="small text-muted"><i class="ti ti-user me-1"></i> Dr: <strong>${item.dokter?.nm_dokter || '-'}</strong></div>
                                    </div>
                                    <span class="badge bg-purple text-purple-fg text-nowrap">ASA ${item.asa}</span>
                                </div>
                                <div class="mt-2 d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-cetak-pa-item" data-tanggal="${item.tanggal}">
                                        <i class="ti ti-printer me-1"></i> Cetak
                                    </button>
                                    <button type="button" class="btn btn-xs btn-primary btn-pilih-pa" data-tanggal="${item.tanggal}">
                                        <i class="ti ti-eye me-1"></i> Buka
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.html(html);

                    container.find('.item-riwayat-pa, .btn-pilih-pa').on('click', function (e) {
                        e.stopPropagation();
                        const tgl = $(this).data('tanggal');
                        bukaDetailPraAnestesi(tgl);
                    });

                    container.find('.btn-cetak-pa-item').on('click', function (e) {
                        e.stopPropagation();
                        const tgl = $(this).data('tanggal');
                        const noRawat = $('#pa_no_rawat').val();
                        window.open(`{{ url('/erm/pra-anestesi/print') }}?no_rawat=${encodeURIComponent(noRawat)}&tanggal=${encodeURIComponent(tgl)}`, '_blank');
                    });
                })
                .fail(() => {
                    container.html('<div class="text-danger text-center p-3 small">Gagal memuat riwayat</div>');
                });
        }

        function bukaDetailPraAnestesi(tanggal) {
            const no_rawat = $('#pa_no_rawat').val();
            $.get(`{{ url('/erm/pra-anestesi/first') }}`, { no_rawat: no_rawat, tanggal: tanggal })
                .done((res) => {
                    if (!res.success || !res.data) return;
                    const d = res.data;
                    resetFormPraAnestesi();

                    $('#pa_no_rawat').val(d.no_rawat);
                    $('#pa_tanggal_lama').val(d.tanggal);
                    $('#pa_tanggal').val(d.tanggal.replace(' ', 'T').slice(0, 16));
                    if (d.tanggal_operasi) $('#pa_tanggal_operasi').val(d.tanggal_operasi.replace(' ', 'T').slice(0, 16));
                    if (d.puasa) $('#pa_puasa').val(d.puasa.replace(' ', 'T').slice(0, 16));

                    $('#pa_kd_dokter').val(d.kd_dokter);
                    $('#pa_nm_dokter').val(d.dokter?.nm_dokter || '-');
                    $('#pa_diagnosa').val(d.diagnosa);
                    $('#pa_rencana_tindakan').val(d.rencana_tindakan);

                    $('#pa_td').val(d.td);
                    $('#pa_nadi').val(d.nadi);
                    $('#pa_pernapasan').val(d.pernapasan);
                    $('#pa_suhu').val(d.suhu);
                    $('#pa_io2').val(d.io2);
                    $('#pa_tb').val(d.tb);
                    $('#pa_bb').val(d.bb);

                    $('#pa_fisik_cardiovasculer').val(d.fisik_cardiovasculer);
                    $('#pa_fisik_paru').val(d.fisik_paru);
                    $('#pa_fisik_abdomen').val(d.fisik_abdomen);
                    $('#pa_fisik_extrimitas').val(d.fisik_extrimitas);
                    $('#pa_fisik_endokrin').val(d.fisik_endokrin);
                    $('#pa_fisik_ginjal').val(d.fisik_ginjal);
                    $('#pa_fisik_obatobatan').val(d.fisik_obatobatan);
                    $('#pa_fisik_laborat').val(d.fisik_laborat);
                    $('#pa_fisik_penunjang').val(d.fisik_penunjang);

                    $('#pa_riwayat_penyakit_alergiobat').val(d.riwayat_penyakit_alergiobat);
                    $('#pa_riwayat_penyakit_alergilainnya').val(d.riwayat_penyakit_alergilainnya);
                    $('#pa_riwayat_penyakit_terapi').val(d.riwayat_penyakit_terapi);

                    $('#pa_riwayat_kebiasaan_merokok').val(d.riwayat_kebiasaan_merokok);
                    $('#pa_riwayat_kebiasaan_ket_merokok').val(d.riwayat_kebiasaan_ket_merokok);
                    $('#pa_riwayat_kebiasaan_alkohol').val(d.riwayat_kebiasaan_alkohol);
                    $('#pa_riwayat_kebiasaan_ket_alkohol').val(d.riwayat_kebiasaan_ket_alkohol);
                    $('#pa_riwayat_kebiasaan_obat').val(d.riwayat_kebiasaan_obat);
                    $('#pa_riwayat_kebiasaan_ket_obat').val(d.riwayat_kebiasaan_ket_obat);

                    $('#pa_asa').val(d.asa);
                    $('#pa_rencana_anestesi').val(d.rencana_anestesi);
                    $('#pa_rencana_perawatan').val(d.rencana_perawatan);
                    $('#pa_catatan_khusus').val(d.catatan_khusus);

                    $('#btnCetakPraAnestesi').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pra-anestesi/print') }}?no_rawat=${encodeURIComponent(d.no_rawat)}&tanggal=${encodeURIComponent(d.tanggal)}', '_blank')`);
                    $('#btnHapusPraAnestesi').removeClass('d-none');
                });
        }

        $('#btnTambahBaruPraAnestesi').on('click', () => {
            const no_rawat = $('#pa_no_rawat').val();
            resetFormPraAnestesi();
            $('#pa_no_rawat').val(no_rawat);
            getRegDetail(no_rawat).done((res) => {
                $('#pa_kd_dokter').val(res.kd_dokter);
                $('#pa_nm_dokter').val(res.dokter?.nm_dokter || '-');
            });
            showToast('Form Kajian Pra Anestesi baru siap diisi', 'info');
        });

        $('#btnSimpanPraAnestesi').on('click', () => {
            const no_rawat = $('#pa_no_rawat').val();
            if (!no_rawat) {
                showToast('Nomor rawat tidak valid', 'error');
                return;
            }

            const diagnosa = $('#pa_diagnosa').val().trim();
            const rencana = $('#pa_rencana_tindakan').val().trim();
            if (!diagnosa || !rencana) {
                showToast('Diagnosa dan Rencana Tindakan wajib diisi!', 'warning');
                return;
            }

            const formData = formKajianPraAnestesi.serialize();
            $('#btnSimpanPraAnestesi').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/erm/pra-anestesi') }}`, formData)
                .done((res) => {
                    $('#btnSimpanPraAnestesi').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Kajian Pra Anestesi');
                    if (res.success) {
                        showToast(res.message || 'Kajian Pra Anestesi berhasil disimpan!', 'success');
                        $('#pa_tanggal_lama').val(res.data.tanggal);
                        $('#btnCetakPraAnestesi').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pra-anestesi/print') }}?no_rawat=${encodeURIComponent(res.data.no_rawat)}&tanggal=${encodeURIComponent(res.data.tanggal)}', '_blank')`);
                        $('#btnHapusPraAnestesi').removeClass('d-none');
                        muatRiwayatPraAnestesi(no_rawat);
                    }
                })
                .fail((err) => {
                    $('#btnSimpanPraAnestesi').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Kajian Pra Anestesi');
                    showToast(err.responseJSON?.message || 'Gagal menyimpan data!', 'error');
                });
        });

        $('#btnHapusPraAnestesi').on('click', () => {
            const no_rawat = $('#pa_no_rawat').val();
            const tanggal = $('#pa_tanggal_lama').val();
            if (!no_rawat || !tanggal) return;

            Swal.fire({
                title: 'Hapus Kajian Pra Anestesi?',
                text: 'Data asesmen pra anestesi ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/erm/pra-anestesi/delete') }}`, { no_rawat: no_rawat, tanggal: tanggal })
                        .done((res) => {
                            if (res.success) {
                                showToast('Kajian Pra Anestesi berhasil dihapus', 'success');
                                resetFormPraAnestesi();
                                $('#pa_no_rawat').val(no_rawat);
                                muatRiwayatPraAnestesi(no_rawat);
                            }
                        });
                }
            });
        });
    });
</script>
@endpush

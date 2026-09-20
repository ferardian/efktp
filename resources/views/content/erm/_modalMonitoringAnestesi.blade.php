<div class="modal modal-blur fade" id="modalMonitoringAnestesi" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-heart-rate-monitor me-2 fs-2"></i>
                    Monitoring Anestesi Selama Pembiusan & Laporan Anestesi
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
                                        <div class="fw-bold fs-4 text-primary" id="ma_display_no_rawat">-</div>
                                        <div class="text-muted small" id="ma_display_no_rkm_medis">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-0">Pasien</label>
                                        <div class="fw-bold fs-4 text-dark" id="ma_display_nm_pasien">-</div>
                                        <div class="text-muted small" id="ma_display_umur_jk">-</div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSyncTtvMonitoringAnestesi" title="Tarik data TTV terakhir">
                                            <i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik TTV Terkini
                                        </button>
                                        <div class="small text-muted mt-1" id="ma_ttv_preview_badge">TTV: -</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="formMonitoringAnestesi">
                            <input type="hidden" name="no_rawat" id="ma_no_rawat">
                            <input type="hidden" name="mulai_lama" id="ma_mulai_lama">

                            <!-- Section 1: Waktu & Tim Operasi -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-clock me-1"></i> 1. Waktu, Lokasi & Tim Operasi</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label required small">Waktu Mulai</label>
                                            <input type="datetime-local" class="form-control" name="mulai" id="ma_mulai" style="height: 38px;" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label required small">Waktu Selesai</label>
                                            <input type="datetime-local" class="form-control" name="selesai" id="ma_selesai" style="height: 38px;" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Lama Operasi / Anestesi</label>
                                            <div class="input-group" style="height: 38px;">
                                                <input type="text" class="form-control" style="height: 38px;" name="lama_operasi" id="ma_lama_operasi" placeholder="Operasi">
                                                <input type="text" class="form-control" style="height: 38px;" name="lama_anastesi" id="ma_lama_anastesi" placeholder="Anestesi">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Tempat Pemantauan</label>
                                            <select class="form-select" style="height: 38px;" name="tempat_pemantauan" id="ma_tempat_pemantauan">
                                                <option value="OK" selected>Kamar Operasi (OK)</option>
                                                <option value="Cathlab">Cathlab</option>
                                                <option value="ICU/ICCU">ICU / ICCU</option>
                                                <option value="Radiologi">Radiologi</option>
                                                <option value="Endoscopy">Endoscopy</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Dokter Spesialis Anestesi</label>
                                            <select class="form-select select2-ma-dokter" name="dokter_anestesi" id="ma_dokter_anestesi" style="width: 100%;" required>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Penata / Asisten Anestesi</label>
                                            <select class="form-select select2-ma-pegawai" name="penata_anestesi" id="ma_penata_anestesi" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label required small">Dokter Operator 1</label>
                                            <select class="form-select select2-ma-dokter" name="operator1" id="ma_operator1" style="width: 100%;" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Dokter Operator 2</label>
                                            <select class="form-select select2-ma-dokter" name="operator2" id="ma_operator2" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Asisten Operator</label>
                                            <select class="form-select select2-ma-pegawai" name="asisten_operator" id="ma_asisten_operator" style="width: 100%;">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Perawat Onloop / Sirkuler</label>
                                            <select class="form-select select2-ma-pegawai" name="onloop" id="ma_onloop" style="width: 100%;">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Diagnosa & Premedikasi TTV -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-notes me-1"></i> 2. Diagnosis, Status ASA & Premedikasi</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-5">
                                            <label class="form-label required small">Tindakan Operasi</label>
                                            <input type="text" class="form-control" name="tindakan_operasi" id="ma_tindakan_operasi" placeholder="Nama tindakan..." required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Diagnosa Pre-Op</label>
                                            <input type="text" class="form-control" name="diagnosa_preop" id="ma_diagnosa_preop">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Diagnosa Post-Op</label>
                                            <input type="text" class="form-control" name="diagnosa_postop" id="ma_diagnosa_postop">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Status ASA</label>
                                            <select class="form-select" name="status_asa" id="ma_status_asa">
                                                <option value="1">ASA 1</option>
                                                <option value="2" selected>ASA 2</option>
                                                <option value="3">ASA 3</option>
                                                <option value="4">ASA 4</option>
                                                <option value="5">ASA 5</option>
                                                <option value="E">ASA E</option>
                                            </select>
                                        </div>
                                        <div class="col-md-9">
                                            <label class="form-label small">Obat Premedikasi Diberikan</label>
                                            <input type="text" class="form-control" name="premedikasi" id="ma_premedikasi" placeholder="Nama obat, dosis, cara pemberian...">
                                        </div>
                                    </div>
                                    <!-- TTV Premedikasi -->
                                    <div class="row g-2">
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">TD (mmHg)</label>
                                            <input type="text" class="form-control" name="ttv_premedikasi_td" id="ma_ttv_premedikasi_td" placeholder="120/80">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">Nadi (x/m)</label>
                                            <input type="text" class="form-control" name="ttv_premedikasi_hr" id="ma_ttv_premedikasi_hr" placeholder="80">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">RR (x/m)</label>
                                            <input type="text" class="form-control" name="ttv_premedikasi_rr" id="ma_ttv_premedikasi_rr" placeholder="20">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">SpO2 (%)</label>
                                            <input type="text" class="form-control" name="ttv_premedikasi_spo2" id="ma_ttv_premedikasi_spo2" placeholder="98">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">Suhu (°C)</label>
                                            <input type="text" class="form-control" name="ttv_premedikasi_suhu" id="ma_ttv_premedikasi_suhu" placeholder="36.5">
                                        </div>
                                        <div class="col-md-2 col-sm-4">
                                            <label class="form-label small">EKG</label>
                                            <input type="text" class="form-control" name="ttv_premedikasi_ekg" id="ma_ttv_premedikasi_ekg" value="Sinus">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Jenis Anestesi & Evaluasi Jalan Nafas -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header py-2 bg-light">
                                    <strong class="text-secondary"><i class="ti ti-mask me-1"></i> 3. Teknik Anestesi & Evaluasi Fisik</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Sedasi</label>
                                            <select class="form-select" name="jenis_anestesi_sedasi" id="ma_jenis_anestesi_sedasi">
                                                <option value="Sedang" selected>Sedasi Sedang</option>
                                                <option value="Ringan">Sedasi Ringan</option>
                                                <option value="Berat">Sedasi Berat</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Regional</label>
                                            <select class="form-select" name="jenis_anestesi_regional" id="ma_jenis_anestesi_regional">
                                                <option value="Spinal" selected>Spinal</option>
                                                <option value="Epidural">Epidural</option>
                                                <option value="Combined">Combined</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">GA (ETT / LMA)</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-2">ETT</span>
                                                <select class="form-select px-2" name="jenis_anestesi_ga_ett" id="ma_jenis_anestesi_ga_ett">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                                <span class="input-group-text px-2">LMA</span>
                                                <select class="form-select px-2" name="jenis_anestesi_ga_ema" id="ma_jenis_anestesi_ga_ema">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Posisi Pasien</label>
                                            <input type="text" class="form-control" name="posisi" id="ma_posisi" value="Supine">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-2">
                                            <label class="form-label small">TB / BB</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center px-1" name="keadaan_umum_tb" id="ma_keadaan_umum_tb" placeholder="TB">
                                                <span class="input-group-text px-1 text-muted">/</span>
                                                <input type="text" class="form-control text-center px-1" name="keadaan_umum_bb" id="ma_keadaan_umum_bb" placeholder="BB">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Mallampati</label>
                                            <input type="text" class="form-control" name="keadaan_umum_malampathy" id="ma_keadaan_umum_malampathy" value="Class 1">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">GCS (E / V / M)</label>
                                            <div class="input-group">
                                                <span class="input-group-text px-2">E</span>
                                                <input type="text" class="form-control text-center px-1" name="keadaan_umum_e" id="ma_keadaan_umum_e" value="4">
                                                <span class="input-group-text px-2">V</span>
                                                <input type="text" class="form-control text-center px-1" name="keadaan_umum_v" id="ma_keadaan_umum_v" value="5">
                                                <span class="input-group-text px-2">M</span>
                                                <input type="text" class="form-control text-center px-1" name="keadaan_umum_m" id="ma_keadaan_umum_m" value="6">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small">Riwayat Alergi</label>
                                            <input type="text" class="form-control" name="keadaan_umum_alergi" id="ma_keadaan_umum_alergi" value="Tidak Ada">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Balans Cairan & Pasca Anestesi -->
                            <div class="card mb-3 shadow-sm border-2 border-primary-subtle">
                                <div class="card-header py-2 bg-primary-lt">
                                    <strong class="text-primary"><i class="ti ti-droplet me-1"></i> 4. Balans Cairan & Kriteria Pindah Ruang</strong>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-3">
                                            <label class="form-label small">Perdarahan</label>
                                            <input type="text" class="form-control" name="perdarahan" id="ma_perdarahan" value="± 50 cc">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Produksi Urine</label>
                                            <input type="text" class="form-control" name="urine" id="ma_urine" value="± 100 cc">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Ekstubasi</label>
                                            <input type="text" class="form-control" name="ekstubasi" id="ma_ekstubasi" value="Di Kamar Operasi (OK)">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Komplikasi</label>
                                            <input type="text" class="form-control" name="komplikasi" id="ma_komplikasi" value="Tidak Ada">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Serah Terima Pasien Ke</label>
                                            <select class="form-select" name="serah_terima_pasien" id="ma_serah_terima_pasien">
                                                <option value="RR" selected>Ruang Pemulihan (RR)</option>
                                                <option value="ICU/ICCU">ICU / ICCU</option>
                                                <option value="NICU/PICU">NICU / PICU</option>
                                                <option value="ODC">One Day Care (ODC)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Tujuan Ruang Perawatan</label>
                                            <input type="text" class="form-control" name="dipindahkan_ke" id="ma_dipindahkan_ke" value="Ruang Rawat Inap">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Catatan Pasca Anestesi</label>
                                            <input type="text" class="form-control" name="catatan" id="ma_catatan" placeholder="Instruksi pengawasan pasca bedah...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Tutup
                                </button>
                                <div class="gap-2 d-flex">
                                    <button type="button" class="btn btn-outline-success d-none" id="btnCetakMonitoringAnestesi">
                                        <i class="ti ti-printer me-1"></i> Cetak Laporan Anestesi
                                    </button>
                                    <button type="button" class="btn btn-danger d-none" id="btnHapusMonitoringAnestesi">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btnSimpanMonitoringAnestesi">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Laporan Anestesi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Kolom Riwayat Laporan Anestesi -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card shadow-sm sticky-top" style="top: 10px;">
                            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                <strong class="text-secondary"><i class="ti ti-history me-1"></i> Riwayat Laporan Anestesi</strong>
                                <button type="button" class="btn btn-xs btn-outline-primary" id="btnTambahBaruMonitoringAnestesi">
                                    <i class="ti ti-plus me-1"></i> Buat Baru
                                </button>
                            </div>
                            <div class="card-body p-2" id="containerRiwayatMonitoringAnestesi" style="max-height: 75vh; overflow-y: auto;">
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
    #modalMonitoringAnestesi .form-control,
    #modalMonitoringAnestesi .form-select,
    #modalMonitoringAnestesi .input-group-text {
        height: 38px !important;
        min-height: 38px !important;
        line-height: 1.5 !important;
        font-size: 0.875rem !important;
    }
    #modalMonitoringAnestesi .input-group {
        height: 38px !important;
        min-height: 38px !important;
    }
    #modalMonitoringAnestesi .select2-container {
        width: 100% !important;
    }
    #modalMonitoringAnestesi .select2-container .select2-selection--single {
        height: 38px !important;
        min-height: 38px !important;
        border: 1px solid #d9dbde !important;
        border-radius: 4px !important;
        display: flex !important;
        align-items: center !important;
    }
    #modalMonitoringAnestesi .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 8px !important;
        color: #1e293b !important;
    }
    #modalMonitoringAnestesi .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 6px !important;
    }
</style>
@endpush

@push('script')
<script>
    $(document).ready(function () {
        const modalMonitoringAnestesi = $('#modalMonitoringAnestesi');
        const formMonitoringAnestesi = $('#formMonitoringAnestesi');

        function initSelect2MonitoringAnestesi() {
            $('.select2-ma-dokter').select2({
                dropdownParent: modalMonitoringAnestesi,
                width: '100%',
                placeholder: '-- Pilih Dokter --',
                allowClear: false,
                ajax: {
                    url: `{{ url('/erm/monitoring-anestesi/search-dokter') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { keyword: params.term || '' };
                    },
                    processResults: function (data) {
                        return { results: data.results || [] };
                    },
                    cache: true
                }
            });

            $('.select2-ma-pegawai').select2({
                dropdownParent: modalMonitoringAnestesi,
                width: '100%',
                placeholder: '-- Pilih Petugas / Pegawai --',
                allowClear: false,
                ajax: {
                    url: `{{ url('/erm/monitoring-anestesi/search-petugas') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { keyword: params.term || '' };
                    },
                    processResults: function (data) {
                        return { results: data.results || [] };
                    },
                    cache: true
                }
            });
        }

        initSelect2MonitoringAnestesi();

        function setSelect2Val(selectId, val, text) {
            const el = $(selectId);
            const value = (val !== null && val !== undefined && val !== '') ? val : '-';
            const label = (text !== null && text !== undefined && text !== '') ? text : value;

            if (el.find("option[value='" + value + "']").length) {
                el.val(value).trigger('change');
            } else {
                const newOption = new Option(label, value, true, true);
                el.append(newOption).trigger('change');
            }
        }

        function setNowDateTimeMonitoringAnestesi() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#ma_mulai').val(now.toISOString().slice(0, 16));

            const end = new Date(now.getTime() + 60 * 60 * 1000);
            $('#ma_selesai').val(end.toISOString().slice(0, 16));
        }

        function resetFormMonitoringAnestesi() {
            formMonitoringAnestesi.trigger('reset');
            setNowDateTimeMonitoringAnestesi();
            $('#ma_mulai_lama').val('');
            $('#btnCetakMonitoringAnestesi').addClass('d-none');
            $('#btnHapusMonitoringAnestesi').addClass('d-none');
            setSelect2Val('#ma_dokter_anestesi', '-', '- (Tidak Ada)');
            setSelect2Val('#ma_operator1', '-', '- (Tidak Ada)');
            setSelect2Val('#ma_operator2', '-', '- (Tidak Ada)');
            setSelect2Val('#ma_penata_anestesi', '-', '- (Tidak Ada)');
            setSelect2Val('#ma_asisten_operator', '-', '- (Tidak Ada)');
            setSelect2Val('#ma_onloop', '-', '- (Tidak Ada)');
        }

        window.bukaMonitoringAnestesi = function (no_rawat) {
            resetFormMonitoringAnestesi();
            $('#ma_no_rawat').val(no_rawat);
            modalMonitoringAnestesi.modal('show');

            getRegDetail(no_rawat).done((res) => {
                const { pasien, dokter } = res;
                $('#ma_display_no_rawat').text(no_rawat);
                $('#ma_display_no_rkm_medis').text(res.no_rkm_medis);
                $('#ma_display_nm_pasien').text(pasien?.nm_pasien || '-');
                $('#ma_display_umur_jk').text(`${formatTanggal(pasien?.tgl_lahir)} / ${pasien?.jk === 'L' ? 'Laki-laki' : 'Perempuan'}`);

                const dpjpText = res.kd_dokter ? `${res.kd_dokter} - ${dokter?.nm_dokter || ''}` : '- (Tidak Ada)';
                setSelect2Val('#ma_operator1', res.kd_dokter, dpjpText);
                setSelect2Val('#ma_dokter_anestesi', res.kd_dokter, dpjpText);
                setSelect2Val('#ma_operator2', '-', '- (Tidak Ada)');
                setSelect2Val('#ma_penata_anestesi', '-', '- (Tidak Ada)');
                setSelect2Val('#ma_asisten_operator', '-', '- (Tidak Ada)');
                setSelect2Val('#ma_onloop', '-', '- (Tidak Ada)');
            });

            syncTtvMonitoringAnestesi(no_rawat, false);
            muatRiwayatMonitoringAnestesi(no_rawat);
        };

        function syncTtvMonitoringAnestesi(no_rawat, showNotification = true) {
            $.get(`{{ url('/pemeriksaan/ttv/latest') }}/${encodeURIComponent(no_rawat)}`)
                .done((res) => {
                    if (res.success && res.data) {
                        const ttv = res.data;
                        const badge = `TD: ${ttv.td} | HR: ${ttv.nadi}x/m | RR: ${ttv.rr}x/m | SpO2: ${ttv.spo2}% | T: ${ttv.suhu}°C`;
                        $('#ma_ttv_preview_badge').html(`<span class="badge bg-green-lt text-dark border">${badge}</span>`);

                        if (showNotification) {
                            $('#ma_ttv_premedikasi_td').val(ttv.td).addClass('border-primary bg-primary-lt');
                            $('#ma_ttv_premedikasi_hr').val(ttv.nadi).addClass('border-primary bg-primary-lt');
                            $('#ma_ttv_premedikasi_rr').val(ttv.rr).addClass('border-primary bg-primary-lt');
                            $('#ma_ttv_premedikasi_suhu').val(ttv.suhu).addClass('border-primary bg-primary-lt');
                            $('#ma_ttv_premedikasi_spo2').val(ttv.spo2).addClass('border-primary bg-primary-lt');
                            $('#ma_keadaan_umum_tb').val(ttv.tb).addClass('border-primary bg-primary-lt');
                            $('#ma_keadaan_umum_bb').val(ttv.bb).addClass('border-primary bg-primary-lt');

                            if (ttv.penilaian && ttv.penilaian !== '-') {
                                $('#ma_diagnosa_preop').val(ttv.penilaian).addClass('border-primary bg-primary-lt');
                            }
                            if (ttv.alergi && ttv.alergi !== '-') {
                                $('#ma_keadaan_umum_alergi').val(ttv.alergi).addClass('border-primary bg-primary-lt');
                            }

                            setTimeout(() => {
                                $('#ma_ttv_premedikasi_td, #ma_ttv_premedikasi_hr, #ma_ttv_premedikasi_rr, #ma_ttv_premedikasi_suhu, #ma_ttv_premedikasi_spo2, #ma_keadaan_umum_tb, #ma_keadaan_umum_bb, #ma_diagnosa_preop, #ma_keadaan_umum_alergi').removeClass('border-primary bg-primary-lt');
                            }, 2000);

                            showToast('TTV & Data Klinis Berhasil Ditarik!', 'success');
                        }
                    } else {
                        $('#ma_ttv_preview_badge').html('<span class="text-muted small">Belum ada catatan TTV sebelumnya</span>');
                        if (showNotification) showToast('Belum ada data TTV sebelumnya.', 'info');
                    }
                });
        }

        $('#btnSyncTtvMonitoringAnestesi').on('click', () => {
            const no_rawat = $('#ma_no_rawat').val();
            if (no_rawat) syncTtvMonitoringAnestesi(no_rawat, true);
        });

        function muatRiwayatMonitoringAnestesi(no_rawat) {
            const container = $('#containerRiwayatMonitoringAnestesi');
            container.html('<div class="text-center text-muted p-3"><div class="spinner-border spinner-border-sm me-1"></div> Memuat riwayat...</div>');

            $.get(`{{ url('/erm/monitoring-anestesi/list') }}/${encodeURIComponent(no_rawat)}`, { no_rawat: no_rawat })
                .done((res) => {
                    if (!res.success || !res.data || res.data.length === 0) {
                        container.html('<div class="text-center text-muted p-3 small">Belum ada riwayat laporan anestesi untuk pasien ini</div>');
                        return;
                    }

                    let html = '<div class="list-group list-group-flush">';
                    res.data.forEach((item, idx) => {
                        const tglMulai = item.mulai ? item.mulai.slice(0, 16) : '-';
                        const opNama = item.dokter_operator1?.nm_dokter || item.operator1 || '-';
                        const anesNama = item.dokter_anestesi?.nm_dokter || item.dokter_anestesi || '-';

                        html += `
                            <div class="list-group-item list-group-item-action p-2 cursor-pointer item-riwayat-ma ${idx === 0 ? 'active' : ''}" data-mulai="${item.mulai}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="small text-truncate" style="max-width: 130px;">${item.tindakan_operasi || 'Operasi'}</strong>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge ${idx === 0 ? 'bg-white text-primary' : 'bg-primary-lt'} small">${tglMulai}</span>
                                        <button type="button" class="btn btn-xs ${idx === 0 ? 'btn-light text-primary' : 'btn-outline-secondary'} btn-cetak-ma-item px-1 py-0" data-mulai="${item.mulai}" title="Cetak Laporan Anestesi">
                                            <i class="ti ti-printer"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <div><i class="ti ti-user me-1"></i> Op: ${opNama}</div>
                                    <div><i class="ti ti-activity me-1"></i> Anes: ${anesNama}</div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.html(html);

                    $('.item-riwayat-ma').on('click', function () {
                        $('.item-riwayat-ma').removeClass('active');
                        $(this).addClass('active');
                        bukaDetailMonitoringAnestesi($(this).data('mulai'));
                    });

                    $('.btn-cetak-ma-item').on('click', function (e) {
                        e.stopPropagation();
                        const mulai = $(this).data('mulai');
                        const noRawat = $('#ma_no_rawat').val();
                        window.open(`{{ url('/erm/monitoring-anestesi/print') }}?no_rawat=${encodeURIComponent(noRawat)}&mulai=${encodeURIComponent(mulai)}`, '_blank');
                    });

                    // Buka data pertama kali
                    bukaDetailMonitoringAnestesi(res.data[0].mulai);
                })
                .fail(() => {
                    container.html('<div class="text-danger text-center p-3 small">Gagal memuat riwayat</div>');
                });
        }

        function bukaDetailMonitoringAnestesi(mulai) {
            const no_rawat = $('#ma_no_rawat').val();
            $.get(`{{ url('/erm/monitoring-anestesi/first') }}`, { no_rawat: no_rawat, mulai: mulai })
                .done((res) => {
                    if (!res.success || !res.data) return;
                    const d = res.data;
                    resetFormMonitoringAnestesi();

                    $('#ma_no_rawat').val(d.no_rawat);
                    $('#ma_mulai_lama').val(d.mulai);
                    $('#ma_mulai').val(d.mulai.replace(' ', 'T').slice(0, 16));
                    $('#ma_selesai').val(d.selesai.replace(' ', 'T').slice(0, 16));
                    $('#ma_lama_operasi').val(d.lama_operasi);
                    $('#ma_lama_anastesi').val(d.lama_anastesi);
                    $('#ma_tempat_pemantauan').val(d.tempat_pemantauan);

                    const docAnestesiNama = d.dokter_anestesi_rel?.nm_dokter || d.dokter_anestesi;
                    setSelect2Val('#ma_dokter_anestesi', d.dokter_anestesi, (d.dokter_anestesi && d.dokter_anestesi !== '-') ? `${d.dokter_anestesi} - ${docAnestesiNama}` : '- (Tidak Ada)');

                    const op1Nama = d.dokter_operator1?.nm_dokter || d.operator1;
                    setSelect2Val('#ma_operator1', d.operator1, (d.operator1 && d.operator1 !== '-') ? `${d.operator1} - ${op1Nama}` : '- (Tidak Ada)');

                    const op2Nama = d.dokter_operator2?.nm_dokter || d.operator2;
                    setSelect2Val('#ma_operator2', d.operator2, (d.operator2 && d.operator2 !== '-') ? `${d.operator2} - ${op2Nama}` : '- (Tidak Ada)');

                    const penataNama = d.penata?.nama || d.penata_pegawai?.nama || d.penata_anestesi;
                    setSelect2Val('#ma_penata_anestesi', d.penata_anestesi, (d.penata_anestesi && d.penata_anestesi !== '-') ? `${d.penata_anestesi} - ${penataNama}` : '- (Tidak Ada)');

                    const asistenNama = d.asisten?.nama || d.asisten_pegawai?.nama || d.asisten_operator;
                    setSelect2Val('#ma_asisten_operator', d.asisten_operator, (d.asisten_operator && d.asisten_operator !== '-') ? `${d.asisten_operator} - ${asistenNama}` : '- (Tidak Ada)');

                    const onloopNama = d.perawat_onloop?.nama || d.onloop_pegawai?.nama || d.onloop;
                    setSelect2Val('#ma_onloop', d.onloop, (d.onloop && d.onloop !== '-') ? `${d.onloop} - ${onloopNama}` : '- (Tidak Ada)');

                    $('#ma_tindakan_operasi').val(d.tindakan_operasi);
                    $('#ma_diagnosa_preop').val(d.diagnosa_preop);
                    $('#ma_diagnosa_postop').val(d.diagnosa_postop);
                    $('#ma_status_asa').val(d.status_asa);
                    $('#ma_premedikasi').val(d.premedikasi);

                    $('#ma_ttv_premedikasi_td').val(d.ttv_premedikasi_td);
                    $('#ma_ttv_premedikasi_hr').val(d.ttv_premedikasi_hr);
                    $('#ma_ttv_premedikasi_rr').val(d.ttv_premedikasi_rr);
                    $('#ma_ttv_premedikasi_spo2').val(d.ttv_premedikasi_spo2);
                    $('#ma_ttv_premedikasi_suhu').val(d.ttv_premedikasi_suhu);
                    $('#ma_ttv_premedikasi_ekg').val(d.ttv_premedikasi_ekg);

                    $('#ma_jenis_anestesi_sedasi').val(d.jenis_anestesi_sedasi);
                    $('#ma_jenis_anestesi_regional').val(d.jenis_anestesi_regional);
                    $('#ma_jenis_anestesi_ga_ett').val(d.jenis_anestesi_ga_ett);
                    $('#ma_jenis_anestesi_ga_ema').val(d.jenis_anestesi_ga_ema);
                    $('#ma_posisi').val(d.posisi);

                    $('#ma_keadaan_umum_tb').val(d.keadaan_umum_tb);
                    $('#ma_keadaan_umum_bb').val(d.keadaan_umum_bb);
                    $('#ma_keadaan_umum_malampathy').val(d.keadaan_umum_malampathy);
                    $('#ma_keadaan_umum_e').val(d.keadaan_umum_e);
                    $('#ma_keadaan_umum_v').val(d.keadaan_umum_v);
                    $('#ma_keadaan_umum_m').val(d.keadaan_umum_m);
                    $('#ma_keadaan_umum_alergi').val(d.keadaan_umum_alergi);

                    $('#ma_perdarahan').val(d.perdarahan);
                    $('#ma_urine').val(d.urine);
                    $('#ma_ekstubasi').val(d.ekstubasi);
                    $('#ma_komplikasi').val(d.komplikasi);
                    $('#ma_serah_terima_pasien').val(d.serah_terima_pasien);
                    $('#ma_dipindahkan_ke').val(d.dipindahkan_ke);
                    $('#ma_catatan').val(d.catatan);

                    $('#btnCetakMonitoringAnestesi').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/monitoring-anestesi/print') }}?no_rawat=${encodeURIComponent(d.no_rawat)}&mulai=${encodeURIComponent(d.mulai)}', '_blank')`);
                    $('#btnHapusMonitoringAnestesi').removeClass('d-none');
                });
        }

        $('#btnTambahBaruMonitoringAnestesi').on('click', () => {
            const no_rawat = $('#ma_no_rawat').val();
            resetFormMonitoringAnestesi();
            $('#ma_no_rawat').val(no_rawat);
            getRegDetail(no_rawat).done((res) => {
                const dpjpText = res.kd_dokter ? `${res.kd_dokter} - ${res.dokter?.nm_dokter || ''}` : '- (Tidak Ada)';
                setSelect2Val('#ma_operator1', res.kd_dokter, dpjpText);
                setSelect2Val('#ma_dokter_anestesi', res.kd_dokter, dpjpText);
                setSelect2Val('#ma_operator2', '-', '- (Tidak Ada)');
                setSelect2Val('#ma_penata_anestesi', '-', '- (Tidak Ada)');
                setSelect2Val('#ma_asisten_operator', '-', '- (Tidak Ada)');
                setSelect2Val('#ma_onloop', '-', '- (Tidak Ada)');
            });
            showToast('Form Laporan Anestesi baru siap diisi', 'info');
        });

        $('#btnSimpanMonitoringAnestesi').on('click', () => {
            const no_rawat = $('#ma_no_rawat').val();
            if (!no_rawat) {
                showToast('Nomor rawat tidak valid', 'error');
                return;
            }

            const tindakan = $('#ma_tindakan_operasi').val().trim();
            if (!tindakan) {
                showToast('Nama tindakan operasi wajib diisi!', 'warning');
                return;
            }

            const formData = formMonitoringAnestesi.serialize();
            $('#btnSimpanMonitoringAnestesi').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/erm/monitoring-anestesi') }}`, formData)
                .done((res) => {
                    $('#btnSimpanMonitoringAnestesi').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Laporan Anestesi');
                    if (res.success) {
                        showToast(res.message || 'Laporan Anestesi berhasil disimpan!', 'success');
                        $('#ma_mulai_lama').val(res.data.mulai);
                        $('#btnCetakMonitoringAnestesi').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/monitoring-anestesi/print') }}?no_rawat=${encodeURIComponent(res.data.no_rawat)}&mulai=${encodeURIComponent(res.data.mulai)}', '_blank')`);
                        $('#btnHapusMonitoringAnestesi').removeClass('d-none');
                        muatRiwayatMonitoringAnestesi(no_rawat);
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabelRegistrasi')) {
                            $('#tabelRegistrasi').DataTable().ajax.reload(null, false);
                        }
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabelKamarInap')) {
                            $('#tabelKamarInap').DataTable().ajax.reload(null, false);
                        }
                    }
                })
                .fail((err) => {
                    $('#btnSimpanMonitoringAnestesi').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Laporan Anestesi');
                    showToast(err.responseJSON?.message || 'Gagal menyimpan data!', 'error');
                });
        });

        $('#btnHapusMonitoringAnestesi').on('click', () => {
            const no_rawat = $('#ma_no_rawat').val();
            const mulai = $('#ma_mulai_lama').val();
            if (!no_rawat || !mulai) return;

            Swal.fire({
                title: 'Hapus Laporan Anestesi?',
                text: 'Data laporan anestesi ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/erm/monitoring-anestesi/delete') }}`, { no_rawat: no_rawat, mulai: mulai })
                        .done((res) => {
                            if (res.success) {
                                showToast('Laporan Anestesi berhasil dihapus', 'success');
                                resetFormMonitoringAnestesi();
                                $('#ma_no_rawat').val(no_rawat);
                                muatRiwayatMonitoringAnestesi(no_rawat);
                                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabelRegistrasi')) {
                                    $('#tabelRegistrasi').DataTable().ajax.reload(null, false);
                                }
                                if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabelKamarInap')) {
                                    $('#tabelKamarInap').DataTable().ajax.reload(null, false);
                                }
                            }
                        });
                }
            });
        });
    });
</script>
@endpush

<div class="modal modal-blur fade" id="modalPenilaianMedisRalan" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3 shadow-lg border-0">
            <div class="modal-header bg-teal text-white py-3">
                <h5 class="modal-title m-0 text-white font-weight-bold">
                    <i class="ti ti-stethoscope me-2"></i>Penilaian Awal Medis Rawat Jalan Umum
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Patient Overview Header -->
            <div class="px-4 py-3 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-md bg-teal-lt rounded-circle"><i class="ti ti-user font-size-1-5"></i></span>
                    <div>
                        <div class="font-weight-bold text-dark" id="medis_lbl_nm_pasien">-</div>
                        <div class="small text-muted" id="medis_lbl_no_rawat">-</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div>
                        <div class="small text-muted">No. Rekam Medis</div>
                        <div class="font-weight-bold text-dark" id="medis_lbl_no_rm">-</div>
                    </div>
                    <div>
                        <div class="small text-muted">Tanggal Lahir / Umur</div>
                        <div class="font-weight-bold text-dark" id="medis_lbl_tgl_lahir">-</div>
                    </div>
                    <div>
                        <div class="small text-muted">DPJP Registrasi</div>
                        <div class="font-weight-bold text-dark" id="medis_lbl_dpjp">-</div>
                    </div>
                </div>
            </div>

            <div class="modal-body p-0">
                <!-- Status Alert Bar if already saved -->
                <div class="alert alert-success d-none m-3 border-0" role="alert" id="alertPenilaianMedis">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-circle-check font-size-1-5 me-2"></i>
                        <div>
                            <h4 class="alert-title m-0">Asesmen medis ini sudah pernah disimpan!</h4>
                            <div class="text-secondary small">Terakhir diperbarui pada: <b id="tgl_penilaian_medis">-</b> oleh <b id="dokter_penilaian_medis">-</b></div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="card border-0">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs card-header-tabs m-0 border-bottom-0 bg-light" id="tabsPenilaianMedisRalan" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-medis-riwayat" class="nav-link active py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-notes me-1"></i> I. Anamnesis & Riwayat
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-medis-fisik" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-activity me-1"></i> II. Pemeriksaan Fisik & TTV
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-medis-diagnosis" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-report-medical me-1"></i> III & IV. Penunjang & Diagnosis
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-medis-tata" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-pill me-1"></i> V. Tata Laksana & Konsul
                                </a>
                            </li>
                        </ul>
                    </div>

                    <form action="" id="formPenilaianMedisRalan" class="tab-content card-body p-4">
                        <input type="hidden" id="medis_no_rawat" name="no_rawat">
                        <input type="hidden" id="medis_tanggal" name="tanggal">
                        <input type="hidden" id="medis_kd_dokter" name="kd_dokter">

                        <!-- ==================== TAB 1: ANAMNESIS & RIWAYAT ==================== -->
                        <div class="tab-pane fade show active" id="tab-medis-riwayat" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <div class="row g-2">
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <label for="medis_anamnesis" class="form-label font-weight-bold">Metode Anamnesis</label>
                                                <select class="form-select" name="anamnesis" id="medis_anamnesis" onchange="toggleAnamnesisMedis()">
                                                    <option value="Autoanamnesis" selected>Autoanamnesis (Sendiri)</option>
                                                    <option value="Alloanamnesis">Alloanamnesis (Keluarga / Pengantar)</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12" id="box_hubungan">
                                                <label for="medis_hubungan" class="form-label font-weight-bold">Hubungan / Nama Pengantar</label>
                                                <input type="text" class="form-control" id="medis_hubungan" name="hubungan" placeholder="Misal: Istri / Orang Tua" value="-">
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <label for="medis_nm_dokter" class="form-label font-weight-bold">Dokter Pemeriksa / DPJP</label>
                                                <input type="text" class="form-control bg-white font-weight-bold" id="medis_nm_dokter" readonly value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h4 class="font-weight-bold text-teal mb-2"><i class="ti ti-writing"></i> Riwayat Kesehatan Pasien</h4>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label for="medis_keluhan_utama" class="form-label font-weight-bold">Keluhan Utama</label>
                                            <textarea class="form-control" id="medis_keluhan_utama" name="keluhan_utama" rows="3" placeholder="Keluhan utama pasien saat datang...">-</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="medis_rps" class="form-label font-weight-bold">Riwayat Penyakit Sekarang (RPS)</label>
                                            <textarea class="form-control" id="medis_rps" name="rps" rows="3" placeholder="Riwayat perjalanan penyakit saat ini...">-</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="medis_rpd" class="form-label font-weight-bold">Riwayat Penyakit Dahulu (RPD)</label>
                                            <textarea class="form-control" id="medis_rpd" name="rpd" rows="2" placeholder="Riwayat penyakit sebelumnya...">-</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="medis_rpk" class="form-label font-weight-bold">Riwayat Penyakit Keluarga (RPK)</label>
                                            <textarea class="form-control" id="medis_rpk" name="rpk" rows="2" placeholder="Riwayat penyakit keluarga / keturunan...">-</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="medis_rpo" class="form-label font-weight-bold">Riwayat Penggunaan Obat (RPO)</label>
                                            <textarea class="form-control" id="medis_rpo" name="rpo" rows="2" placeholder="Obat yang sedang / pernah dikonsumsi...">-</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="medis_alergi" class="form-label font-weight-bold">Riwayat Alergi</label>
                                            <textarea class="form-control" id="medis_alergi" name="alergi" rows="2" placeholder="Riwayat alergi obat / makanan...">-</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 2: PEMERIKSAAN FISIK & TTV ==================== -->
                        <div class="tab-pane fade" id="tab-medis-fisik" role="tabpanel">
                            <div class="row g-3">
                                <!-- Keadaan Umum & Kesadaran -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-teal mb-3"><i class="ti ti-activity-heartbeat"></i> 1. Keadaan Umum & Tanda Vital</h4>
                                        <div class="row g-2 mb-3">
                                            <div class="col-lg-4 col-md-6">
                                                <label for="medis_keadaan" class="form-label font-weight-bold">Keadaan Umum</label>
                                                <select class="form-select font-weight-bold" name="keadaan" id="medis_keadaan">
                                                    <option value="Sehat">Sehat</option>
                                                    <option value="Sakit Ringan">Sakit Ringan</option>
                                                    <option value="Sakit Sedang" selected>Sakit Sedang</option>
                                                    <option value="Sakit Berat">Sakit Berat</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <label for="medis_kesadaran" class="form-label font-weight-bold">Kesadaran</label>
                                                <select class="form-select font-weight-bold" name="kesadaran" id="medis_kesadaran">
                                                    <option value="Compos Mentis" selected>Compos Mentis</option>
                                                    <option value="Apatis">Apatis</option>
                                                    <option value="Somnolen">Somnolen</option>
                                                    <option value="Sopor">Sopor</option>
                                                    <option value="Koma">Koma</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <label for="medis_gcs" class="form-label font-weight-bold">GCS (E, V, M)</label>
                                                <input type="text" class="form-control" id="medis_gcs" name="gcs" placeholder="Misal: 15 / E4V5M6" value="-">
                                            </div>
                                        </div>

                                        <!-- Tanda Vital -->
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="font-weight-bold text-dark">Tanda-Tanda Vital :</span>
                                            <button type="button" class="btn btn-outline-teal btn-sm" onclick="salinTtvKeMedis()">
                                                <i class="ti ti-copy me-1"></i> Salin TTV Terakhir
                                            </button>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-lg-2 col-md-4 col-sm-6">
                                                <label for="medis_td" class="form-label">Tensi (TD)</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="medis_td" name="td" value="-">
                                                    <span class="input-group-text">mmHg</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6">
                                                <label for="medis_nadi" class="form-label">Nadi</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="medis_nadi" name="nadi" value="-">
                                                    <span class="input-group-text">x/mnt</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6">
                                                <label for="medis_rr" class="form-label">Respirasi (RR)</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="medis_rr" name="rr" value="-">
                                                    <span class="input-group-text">x/mnt</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6">
                                                <label for="medis_suhu" class="form-label">Suhu</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="medis_suhu" name="suhu" value="-">
                                                    <span class="input-group-text">°C</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6">
                                                <label for="medis_spo" class="form-label">SpO2</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="medis_spo" name="spo" value="-">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-2 col-sm-3">
                                                <label for="medis_bb" class="form-label">BB (Kg)</label>
                                                <input type="text" class="form-control" id="medis_bb" name="bb" value="-">
                                            </div>
                                            <div class="col-lg-1 col-md-2 col-sm-3">
                                                <label for="medis_tb" class="form-label">TB (cm)</label>
                                                <input type="text" class="form-control" id="medis_tb" name="tb" value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pemeriksaan Fisik Sistem Organ -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h4 class="font-weight-bold text-teal m-0"><i class="ti ti-body"></i> 2. Pemeriksaan Sistem Organ</h4>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setSemuaFisikNormal()">
                                                <i class="ti ti-check-all me-1"></i> Set Semua Normal
                                            </button>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_kepala" class="form-label font-weight-bold">Kepala</label>
                                                <select class="form-select" name="kepala" id="medis_kepala">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_gigi" class="form-label font-weight-bold">Gigi & Mulut</label>
                                                <select class="form-select" name="gigi" id="medis_gigi">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_tht" class="form-label font-weight-bold">THT</label>
                                                <select class="form-select" name="tht" id="medis_tht">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_thoraks" class="form-label font-weight-bold">Thoraks</label>
                                                <select class="form-select" name="thoraks" id="medis_thoraks">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_abdomen" class="form-label font-weight-bold">Abdomen</label>
                                                <select class="form-select" name="abdomen" id="medis_abdomen">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_genital" class="form-label font-weight-bold">Genital</label>
                                                <select class="form-select" name="genital" id="medis_genital">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_ekstremitas" class="form-label font-weight-bold">Ekstremitas</label>
                                                <select class="form-select" name="ekstremitas" id="medis_ekstremitas">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="medis_kulit" class="form-label font-weight-bold">Kulit</label>
                                                <select class="form-select" name="kulit" id="medis_kulit">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Tidak Diperiksa">Tidak Diperiksa</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-2 mt-2">
                                            <div class="col-md-6">
                                                <label for="medis_ket_fisik" class="form-label font-weight-bold">Keterangan Pemeriksaan Fisik Khusus</label>
                                                <textarea class="form-control" id="medis_ket_fisik" name="ket_fisik" rows="3" placeholder="Keterangan penemuan fisik...">-</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="medis_ket_lokalis" class="form-label font-weight-bold">Status Lokalis</label>
                                                <textarea class="form-control" id="medis_ket_lokalis" name="ket_lokalis" rows="3" placeholder="Deskripsi status lokalis...">-</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 3: PENUNJANG & DIAGNOSIS ==================== -->
                        <div class="tab-pane fade" id="tab-medis-diagnosis" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-teal mb-3"><i class="ti ti-microscope"></i> III. Pemeriksaan Penunjang</h4>
                                        <label for="medis_penunjang" class="form-label font-weight-bold">Pemeriksaan Penunjang (Laboratorium, Radiologi, EKG, dll)</label>
                                        <textarea class="form-control" id="medis_penunjang" name="penunjang" rows="4" placeholder="Hasil atau instruksi pemeriksaan laboratorium, radiologi, atau penunjang lainnya...">-</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-teal mb-3"><i class="ti ti-file-certificate"></i> IV. Diagnosis / Asesmen Kerja</h4>
                                        <label for="medis_diagnosis" class="form-label font-weight-bold">Diagnosis Kerja & Diagnosis Banding</label>
                                        <textarea class="form-control font-weight-bold" id="medis_diagnosis" name="diagnosis" rows="4" placeholder="Tuliskan diagnosis kerja utama dan diagnosis banding bila ada...">-</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 4: TATA LAKSANA & KONSUL ==================== -->
                        <div class="tab-pane fade" id="tab-medis-tata" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-teal mb-3"><i class="ti ti-pill"></i> V. Tata Laksana / Rencana Terapi</h4>
                                        <label for="medis_tata" class="form-label font-weight-bold">Rencana Pengobatan, Terapi, atau Tindakan Medis</label>
                                        <textarea class="form-control" id="medis_tata" name="tata" rows="5" placeholder="Tuliskan resep obat, tindakan, atau edukasi terapi...">-</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-teal mb-3"><i class="ti ti-arrow-forward-up"></i> VI. Konsul / Rujukan</h4>
                                        <label for="medis_konsulrujuk" class="form-label font-weight-bold">Konsul ke Dokter Spesialis Lain / Rujukan Eksternal</label>
                                        <textarea class="form-control" id="medis_konsulrujuk" name="konsulrujuk" rows="3" placeholder="Rencana konsul poli lain, rawat inap, atau rujukan ke RS lain...">-</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer d-flex justify-content-between bg-light py-2">
                <div>
                    <button type="button" class="btn btn-outline-danger d-none" id="btnHapusPenilaianMedis" onclick="hapusPenilaianMedisRalan()">
                        <i class="ti ti-trash me-1"></i> Hapus Asesmen
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary d-none" id="btnCetakMedis" onclick="printPenilaianMedisRalan()">
                        <i class="ti ti-printer me-1"></i> Cetak PDF
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="btnSimpanPenilaianMedis" onclick="simpanPenilaianMedisRalan()">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Penilaian Medis
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak PDF Medis -->
<div class="modal modal-blur fade" id="modalCetakPenilaianMedis" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-teal text-white py-2">
                <h5 class="modal-title m-0 text-white"><i class="ti ti-printer me-2"></i>Cetak Penilaian Awal Medis Rawat Jalan Umum</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="print_medis" type="" width="100%" height="650" class="border-0"></iframe>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        var modalPenilaianMedisRalan = $('#modalPenilaianMedisRalan');
        var modalCetakPenilaianMedis = $('#modalCetakPenilaianMedis');

        function toggleAnamnesisMedis() {
            const isAllo = $('#medis_anamnesis').val() === 'Alloanamnesis';
            if (isAllo) {
                $('#box_hubungan').removeClass('d-none');
            } else {
                $('#box_hubungan').addClass('d-none');
                $('#medis_hubungan').val('-');
            }
        }

        function setSemuaFisikNormal() {
            $('#medis_kepala').val('Normal');
            $('#medis_gigi').val('Normal');
            $('#medis_tht').val('Normal');
            $('#medis_thoraks').val('Normal');
            $('#medis_abdomen').val('Normal');
            $('#medis_genital').val('Normal');
            $('#medis_ekstremitas').val('Normal');
            $('#medis_kulit').val('Normal');
        }

        var currentNoRawatMedisRalan = '';

        function salinTtvKeMedis() {
            const no_rawat = $('#medis_no_rawat').val() || currentNoRawatMedisRalan;
            if (!no_rawat) return;

            getPemeriksaanRalan(no_rawat).done((response) => {
                if (response && response.length > 0) {
                    const item = response[0];
                    if (item.tensi && item.tensi !== '0/0' && item.tensi !== '-') $('#medis_td').val(item.tensi);
                    if (item.nadi && item.nadi !== '0' && item.nadi !== '-') $('#medis_nadi').val(item.nadi);
                    if (item.respirasi && item.respirasi !== '0' && item.respirasi !== '-') $('#medis_rr').val(item.respirasi);
                    if (item.suhu_tubuh && item.suhu_tubuh !== '0' && item.suhu_tubuh !== '-') $('#medis_suhu').val(item.suhu_tubuh);
                    if (item.spo2 && item.spo2 !== '0' && item.spo2 !== '-') $('#medis_spo').val(item.spo2);
                    if (item.gcs && item.gcs !== '-') $('#medis_gcs').val(item.gcs);
                    if (item.kesadaran) $('#medis_kesadaran').val(item.kesadaran);
                    if (item.berat && item.berat !== '0' && item.berat !== '-') $('#medis_bb').val(item.berat);
                    if (item.tinggi && item.tinggi !== '0' && item.tinggi !== '-') $('#medis_tb').val(item.tinggi);
                    if (item.keluhan && item.keluhan !== '-') $('#medis_keluhan_utama').val(item.keluhan);
                    if (item.alergi && item.alergi !== '-') $('#medis_alergi').val(item.alergi);
                    if (item.penilaian && item.penilaian !== '-') $('#medis_diagnosis').val(item.penilaian);
                    if (item.rtl && item.rtl !== '-') $('#medis_tata').val(item.rtl);

                    Swal.fire({
                        icon: 'info',
                        title: 'TTV Disalin',
                        text: 'Tanda-tanda vital dan keluhan berhasil disalin dari riwayat pemeriksaan poli.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Ada Data',
                        text: 'Belum ada data pemeriksaan TTV ralan yang tersimpan.',
                    });
                }
            });
        }

        // Buka Modal Penilaian Medis Ralan
        function penilaianMedisRalan(no_rawat) {
            currentNoRawatMedisRalan = no_rawat;
            $('#tabsPenilaianMedisRalan a[href="#tab-medis-riwayat"]').tab('show');
            $('#formPenilaianMedisRalan').trigger('reset');
            $('#medis_no_rawat').val(no_rawat);
            $('#btnHapusPenilaianMedis').addClass('d-none');
            $('#btnCetakMedis').addClass('d-none');
            $('#alertPenilaianMedis').addClass('d-none');

            getRegDetail(no_rawat).done((reg) => {
                currentNoRawatMedisRalan = reg.no_rawat;
                $('#medis_no_rawat').val(reg.no_rawat);
                $('#medis_lbl_no_rawat').text(reg.no_rawat);
                $('#medis_lbl_no_rm').text(reg.no_rkm_medis);
                $('#medis_lbl_nm_pasien').text(`${reg.pasien.nm_pasien} (${reg.pasien.jk === 'L' ? 'Laki-Laki' : 'Perempuan'})`);
                $('#medis_lbl_tgl_lahir').text(`${splitTanggal(reg.pasien.tgl_lahir)} / ${reg.umurdaftar} ${reg.sttsumur}`);

                const dokterName = (reg.dokter && reg.dokter.nm_dokter) ? reg.dokter.nm_dokter : (reg.kd_dokter || '-');
                $('#medis_lbl_dpjp').text(dokterName);
                $('#medis_nm_dokter').val(dokterName);
                $('#medis_kd_dokter').val(reg.kd_dokter);

                modalPenilaianMedisRalan.modal('show');

                // Check existing record
                $.get(`{{ url('/penilaian/medis/ralan') }}`, { no_rawat: no_rawat }).done((res) => {
                    if (res && res.no_rawat) {
                        $('#alertPenilaianMedis').removeClass('d-none');
                        $('#tgl_penilaian_medis').text(res.tanggal || '-');
                        $('#dokter_penilaian_medis').text((res.dokter && res.dokter.nm_dokter) ? res.dokter.nm_dokter : res.kd_dokter);
                        $('#btnCetakMedis').removeClass('d-none');
                        $('#btnHapusPenilaianMedis').removeClass('d-none');

                        const form = $('#formPenilaianMedisRalan');
                        $.each(res, function (key, val) {
                            const el = form.find(`[name="${key}"]`);
                            if (el.length > 0 && val !== null) {
                                el.val(val);
                            }
                        });
                        $('#medis_no_rawat').val(res.no_rawat || no_rawat);
                        toggleAnamnesisMedis();
                    } else {
                        $('#btnCetakMedis').addClass('d-none');
                        $('#btnHapusPenilaianMedis').addClass('d-none');
                        $('#alertPenilaianMedis').addClass('d-none');
                        $('#medis_no_rawat').val(no_rawat);
                        toggleAnamnesisMedis();

                        // Auto-fill from pemeriksaan_ralan if exists
                        getPemeriksaanRalan(no_rawat).done((pem) => {
                            if (pem && pem.length > 0) {
                                const last = pem[0];
                                if (last.keluhan && last.keluhan !== '-') $('#medis_keluhan_utama').val(last.keluhan);
                                if (last.tensi && last.tensi !== '0/0' && last.tensi !== '-') $('#medis_td').val(last.tensi);
                                if (last.nadi && last.nadi !== '0' && last.nadi !== '-') $('#medis_nadi').val(last.nadi);
                                if (last.respirasi && last.respirasi !== '0' && last.respirasi !== '-') $('#medis_rr').val(last.respirasi);
                                if (last.suhu_tubuh && last.suhu_tubuh !== '0' && last.suhu_tubuh !== '-') $('#medis_suhu').val(last.suhu_tubuh);
                                if (last.spo2 && last.spo2 !== '0' && last.spo2 !== '-') $('#medis_spo').val(last.spo2);
                                if (last.gcs && last.gcs !== '-') $('#medis_gcs').val(last.gcs);
                                if (last.kesadaran) $('#medis_kesadaran').val(last.kesadaran);
                                if (last.alergi && last.alergi !== '-') $('#medis_alergi').val(last.alergi);
                                if (last.berat && last.berat !== '0' && last.berat !== '-') $('#medis_bb').val(last.berat);
                                if (last.tinggi && last.tinggi !== '0' && last.tinggi !== '-') $('#medis_tb').val(last.tinggi);
                                if (last.penilaian && last.penilaian !== '-') $('#medis_diagnosis').val(last.penilaian);
                                if (last.rtl && last.rtl !== '-') $('#medis_tata').val(last.rtl);
                            }
                        });
                    }
                });
            });
        }

        // Simpan Asesmen Medis
        function simpanPenilaianMedisRalan() {
            const data = getDataForm('formPenilaianMedisRalan', ['input', 'select', 'textarea']);

            data['no_rawat'] = $('#medis_no_rawat').val() || currentNoRawatMedisRalan;
            data['kd_dokter'] = $('#medis_kd_dokter').val() || '{{ session()->get("pegawai")->nik ?? "" }}';

            if (!data['no_rawat']) {
                Swal.fire('Perhatian', 'No. Rawat tidak ditemukan. Silakan buka kembali form.', 'warning');
                return;
            }

            loadingAjax('Menyimpan penilaian awal medis rawat jalan...');
            $.post(`{{ url('/penilaian/medis/ralan') }}`, data).done((response) => {
                swal.close();
                alertSuccessAjax('Penilaian awal medis rawat jalan berhasil disimpan.').then(() => {
                    $('#alertPenilaianMedis').removeClass('d-none');
                    $('#tgl_penilaian_medis').text("{{ date('Y-m-d H:i:s') }}");
                    $('#btnCetakMedis').removeClass('d-none');
                    $('#btnHapusPenilaianMedis').removeClass('d-none');
                });
            }).fail((err) => {
                swal.close();
                alertErrorAjax(err);
            });
        }

        // Hapus Asesmen Medis
        function hapusPenilaianMedisRalan() {
            const no_rawat = $('#medis_no_rawat').val() || currentNoRawatMedisRalan;
            if (!no_rawat) return;

            Swal.fire({
                title: 'Hapus Penilaian Medis?',
                text: 'Data penilaian awal medis rawat jalan akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus data...');
                    $.ajax({
                        url: `{{ url('/penilaian/medis/ralan') }}`,
                        type: 'DELETE',
                        data: {
                            no_rawat: no_rawat,
                            _token: "{{ csrf_token() }}"
                        }
                    }).done(() => {
                        swal.close();
                        alertSuccessAjax('Penilaian medis berhasil dihapus.').then(() => {
                            modalPenilaianMedisRalan.modal('hide');
                        });
                    }).fail((err) => {
                        swal.close();
                        alertErrorAjax(err);
                    });
                }
            });
        }

        function printPenilaianMedisRalan() {
            const no_rawat = $('#medis_no_rawat').val() || currentNoRawatMedisRalan;
            if (!no_rawat) return;
            modalCetakPenilaianMedis.modal('show');
            modalCetakPenilaianMedis.find('#print_medis').attr('src', `{{ url('/penilaian/medis/ralan/print') }}?no_rawat=${no_rawat}`);
        }

        modalPenilaianMedisRalan.on('hidden.bs.modal', () => {
            $('#formPenilaianMedisRalan').trigger('reset');
        });
    </script>
@endpush

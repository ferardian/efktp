<div class="modal modal-blur fade" id="modalPenilaianAwalKeperawatanRanap" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3 shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title m-0 text-white font-weight-bold"><i class="ti ti-report-medical me-2"></i>Penilaian Awal Keperawatan Umum - Rawat Inap</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Sleek Glassmorphism Header Bar for Patient Overview -->
            <div class="px-4 py-3 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-md bg-blue-lt rounded-circle"><i class="ti ti-user font-size-1-5"></i></span>
                    <div>
                        <div class="font-weight-bold text-dark" id="lbl_nm_pasien">-</div>
                        <div class="small text-muted" id="lbl_no_rawat">-</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <div>
                        <div class="small text-muted">No. Rekam Medis</div>
                        <div class="font-weight-bold text-dark" id="lbl_no_rm">-</div>
                    </div>
                    <div>
                        <div class="small text-muted">Tanggal Lahir / Umur</div>
                        <div class="font-weight-bold text-dark" id="lbl_tgl_lahir">-</div>
                    </div>
                    <div>
                        <div class="small text-muted">DPJP Registrasi</div>
                        <div class="font-weight-bold text-dark" id="lbl_dpjp">-</div>
                    </div>
                </div>
            </div>

            <div class="modal-body p-0">
                <!-- Status Alert Bar if already saved -->
                <div class="alert alert-success d-none m-3 border-0" role="alert" id="alertPenilaianRanap">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-check font-size-1-5 me-2"></i>
                        </div>
                        <div>
                            <h4 class="alert-title m-0">Asesmen ini sudah pernah disimpan!</h4>
                            <div class="text-secondary small">Terakhir diperbarui pada: <b id="tgl_penilaian_ranap">-</b> oleh <b id="user_penilaian_ranap">-</b></div>
                        </div>
                    </div>
                </div>

                <!-- Beautiful Tabs Navigation -->
                <div class="card border-0">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs card-header-tabs m-0 border-bottom-0 bg-light" id="tabsPenilaianRanap" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-riwayat" class="nav-link active py-3 px-4 text-dark font-weight-bold" data-bs-toggle="tab" role="tab"><i class="ti ti-info-circle me-1"></i> Info & Riwayat</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-fisik" class="nav-link py-3 px-4 text-dark font-weight-bold" data-bs-toggle="tab" role="tab"><i class="ti ti-activity me-1"></i> Pemeriksaan Fisik</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-pola" class="nav-link py-3 px-4 text-dark font-weight-bold" data-bs-toggle="tab" role="tab"><i class="ti ti-run me-1"></i> Pola & Fungsi</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-psiko" class="nav-link py-3 px-4 text-dark font-weight-bold" data-bs-toggle="tab" role="tab"><i class="ti ti-brain me-1"></i> Psiko & Edukasi</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-nyeri" class="nav-link py-3 px-4 text-dark font-weight-bold" data-bs-toggle="tab" role="tab"><i class="ti ti-alert-triangle me-1"></i> Nyeri & Jatuh</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-gizi" class="nav-link py-3 px-4 text-dark font-weight-bold" data-bs-toggle="tab" role="tab"><i class="ti ti-salad me-1"></i> Gizi & Rencana</a>
                            </li>
                        </ul>
                    </div>
                    
                    <form id="formPenilaianAwalKeperawatanRanap" class="tab-content card-body p-4">
                        <input type="hidden" name="no_rawat" id="ranap_no_rawat">
                        <input type="hidden" name="tanggal" id="ranap_tanggal">

                        <!-- ==================== TAB 1: INFO & RIWAYAT ==================== -->
                        <div class="tab-pane fade show active" id="tab-riwayat" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-writing"></i> 1. Metode Anamnesis & Kedatangan</h4>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Anamnesis</label>
                                                <select class="form-select" name="informasi" id="ranap_informasi">
                                                    <option value="Autoanamnesis">Autoanamnesis (Sendiri)</option>
                                                    <option value="Alloanamnesis">Alloanamnesis (Keluarga/Pengantar)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Hubungan / Nama Pengantar</label>
                                                <input type="text" class="form-control" name="ket_informasi" placeholder="Misal: Ibu Kandung" value="-">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Tiba di Ruang Rawat Dengan</label>
                                                <select class="form-select" name="tiba_diruang_rawat" id="ranap_tiba_diruang_rawat">
                                                    <option value="Jalan Tanpa Bantuan">Jalan Tanpa Bantuan</option>
                                                    <option value="Kursi Roda">Kursi Roda</option>
                                                    <option value="Brankar">Brankar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Kasus</label>
                                                <select class="form-select" name="kasus_trauma">
                                                    <option value="Non Trauma">Kasus Non Trauma</option>
                                                    <option value="Trauma">Kasus Trauma</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Cara Masuk</label>
                                                <select class="form-select" name="cara_masuk">
                                                    <option value="Poli">Rawat Jalan</option>
                                                    <option value="IGD">UGD</option>
                                                    <option value="Lain-lain">Rujukan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-history"></i> 2. Riwayat Kesehatan & Alergi</h4>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Hamil / Sedang Menyusui ?</label>
                                                <select class="form-select" name="riwayat_kehamilan" id="ranap_riwayat_kehamilan">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jika Ya, Perkiraan Usia Hamil</label>
                                                <input type="text" class="form-control" name="riwayat_kehamilan_perkiraan" id="ranap_riwayat_kehamilan_perkiraan" placeholder="Misal: 12 Minggu / Menyusui" value="-">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Riwayat Alergi</label>
                                                <input type="text" class="form-control" name="riwayat_alergi" id="ranap_riwayat_alergi" placeholder="Obat, makanan, debu, dll" value="-">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Riwayat Transfusi Darah</label>
                                                <input type="text" class="form-control" name="riwayat_tranfusi" placeholder="Jika ada, sebutkan gol. darah & reaksi" value="-">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Alat Bantu yang Dipakai Pasien</label>
                                                <input type="text" class="form-control" name="alat_bantu_dipakai" placeholder="Misal: Kacamata, Gigi tiruan, Alat bantu dengar" value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 rounded-2 shadow-sm p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-notes"></i> 3. Riwayat Penyakit & Pengobatan</h4>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold">Riwayat Penyakit Sekarang (RPS)</label>
                                                <textarea class="form-control" name="rps" rows="3" placeholder="Deskripsikan keluhan utama dan kronologis penyakit saat ini...">-</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold">Riwayat Penyakit Dahulu (RPD)</label>
                                                <textarea class="form-control" name="rpd" rows="3" placeholder="Deskripsikan riwayat penyakit sistemik yang pernah diderita...">-</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold">Riwayat Penyakit Keluarga (RPK)</label>
                                                <textarea class="form-control" name="rpk" rows="3" placeholder="Deskripsikan riwayat penyakit keturunan dalam keluarga...">-</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold">Riwayat Pembedahan / Operasi</label>
                                                <textarea class="form-control" name="riwayat_pembedahan" rows="2" placeholder="Sebutkan jenis operasi dan tanggal jika ada...">-</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold">Riwayat Dirawat di Rumah Sakit</label>
                                                <textarea class="form-control" name="riwayat_dirawat_dirs" rows="2" placeholder="Sebutkan alasan dirawat dan tahun dirawat jika ada...">-</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold">Riwayat Penggunaan Obat Sering Dikonsumsi</label>
                                                <textarea class="form-control" name="rpo" rows="2" placeholder="Sebutkan obat-obat yang sering dikonsumsi saat ini...">-</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card bg-light border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-run"></i> 4. Kebiasaan / Gaya Hidup Sehari-hari</h4>
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label class="form-label">Merokok?</label>
                                                <select class="form-select" name="riwayat_merokok" id="ranap_riwayat_merokok">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jumlah Batang / Hari</label>
                                                <input type="number" class="form-control" name="riwayat_merokok_jumlah" id="ranap_riwayat_merokok_jumlah" value="0" min="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Minum Alkohol?</label>
                                                <select class="form-select" name="riwayat_alkohol" id="ranap_riwayat_alkohol">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Jumlah Gelas / Hari</label>
                                                <input type="number" class="form-control" name="riwayat_alkohol_jumlah" id="ranap_riwayat_alkohol_jumlah" value="0" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Kebiasaan Minum Obat Tidur / Penenang / Zat Lain</label>
                                                <select class="form-select" name="riwayat_narkoba">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Rutin Olahraga?</label>
                                                <select class="form-select" name="riwayat_olahraga">
                                                    <option value="Ya">Ya</option>
                                                    <option value="Tidak">Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 2: PEMERIKSAAN FISIK ==================== -->
                        <div class="tab-pane fade" id="tab-fisik" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="font-weight-bold text-primary m-0"><i class="ti ti-activity"></i> 1. Tanda Vital & Parameter Umum</h4>
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnCopyFromCppt"><i class="ti ti-copy me-1"></i> Salin dari CPPT Terakhir</button>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-2">
                                                <label class="form-label">Mental / Kesadaran</label>
                                                <select class="form-select" name="pemeriksaan_mental">
                                                    <option value="Compos Mentis">Compos Mentis</option>
                                                    <option value="Apatis">Apatis</option>
                                                    <option value="Somnolen">Somnolen</option>
                                                    <option value="Sopor">Sopor</option>
                                                    <option value="Koma">Koma</option>
                                                    <option value="Delirium">Delirium</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Keadaan Umum</label>
                                                <select class="form-select" name="pemeriksaan_keadaan_umum">
                                                    <option value="Baik">Baik</option>
                                                    <option value="Sedang">Sedang</option>
                                                    <option value="Kurang">Kurang</option>
                                                    <option value="Jelek">Jelek</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">GCS (E, V, M)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_gcs" placeholder="Misal: 15 / E4V5M6" value="15">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Tekanan Darah (mmHg)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_td" id="ranap_pemeriksaan_td" placeholder="120/80" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Nadi (x/menit)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_nadi" id="ranap_pemeriksaan_nadi" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Respirasi (x/menit)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_rr" id="ranap_pemeriksaan_rr" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Suhu (°C)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_suhu" id="ranap_pemeriksaan_suhu" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">SpO2 (%)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_spo2" id="ranap_pemeriksaan_spo2" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Berat Badan (Kg)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_bb" id="ranap_pemeriksaan_bb" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Tinggi Badan (cm)</label>
                                                <input type="text" class="form-control" name="pemeriksaan_tb" id="ranap_pemeriksaan_tb" value="-">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">BMI (Kg/m²)</label>
                                                <input type="text" class="form-control bg-light" name="pemeriksaan_bmi" id="ranap_pemeriksaan_bmi" readonly value="-">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <span class="badge w-100 py-2 fs-3 d-none" id="ranap_badge_bmi">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow-sm p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti- stethoscope"></i> 2. Pemeriksaan Sistem Tubuh (Head-to-Toe)</h4>
                                        <div class="row g-3">
                                            <!-- Kepala -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Susunan Kepala</label>
                                                    <select class="form-select mb-2" name="pemeriksaan_susunan_kepala">
                                                        <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                        <option value="Hydrocephalus">Hydrocephalus</option>
                                                        <option value="Hematoma">Hematoma</option>
                                                        <option value="Lain-lain">Lain-lain</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pemeriksaan_susunan_kepala_keterangan" placeholder="Keterangan jika abnormal" value="-">
                                                </div>
                                            </div>
                                            <!-- Wajah -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Susunan Wajah</label>
                                                    <select class="form-select mb-2" name="pemeriksaan_susunan_wajah">
                                                        <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                        <option value="Asimetris">Asimetris</option>
                                                        <option value="Kelainan Kongenital">Kelainan Kongenital</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pemeriksaan_susunan_wajah_keterangan" placeholder="Keterangan jika abnormal" value="-">
                                                </div>
                                            </div>
                                            <!-- Kejang -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Kejang</label>
                                                    <select class="form-select mb-2" name="pemeriksaan_susunan_kejang">
                                                        <option value="TAK">Tidak Ada (TAK)</option>
                                                        <option value="Kuat">Kuat</option>
                                                        <option value="Ada">Ada</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pemeriksaan_susunan_kejang_keterangan" placeholder="Keterangan jika ada" value="-">
                                                </div>
                                            </div>
                                            <!-- Leher, Sensorik -->
                                            <div class="col-md-3">
                                                <label class="form-label">Leher</label>
                                                <select class="form-select" name="pemeriksaan_susunan_leher">
                                                    <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                    <option value="Kaku Kuduk">Kaku Kuduk</option>
                                                    <option value="Pembesaran Thyroid">Pembesaran Thyroid</option>
                                                    <option value="Pembesaran KGB">Pembesaran KGB</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Sensorik Kepala</label>
                                                <select class="form-select" name="pemeriksaan_susunan_sensorik">
                                                    <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                    <option value="Sakit Nyeri">Sakit Nyeri</option>
                                                    <option value="Rasa kebas">Rasa Kebas</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Denyut Nadi (Kardio)</label>
                                                <select class="form-select" name="pemeriksaan_kardiovaskuler_denyut_nadi">
                                                    <option value="Teratur">Teratur</option>
                                                    <option value="Tidak Teratur">Tidak Teratur</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Pulsasi Kardio</label>
                                                <select class="form-select" name="pemeriksaan_kardiovaskuler_pulsasi">
                                                    <option value="Kuat">Kuat</option>
                                                    <option value="Lemah">Lemah</option>
                                                    <option value="Lain-lain">Lain-lain</option>
                                                </select>
                                            </div>
                                            <!-- Sirkulasi -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Sirkulasi Kardiovaskuler</label>
                                                    <select class="form-select mb-2" name="pemeriksaan_kardiovaskuler_sirkulasi">
                                                        <option value="Akral Hangat">Akral Hangat</option>
                                                        <option value="Akral Dingin">Akral Dingin</option>
                                                        <option value="Edema">Edema</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pemeriksaan_kardiovaskuler_sirkulasi_keterangan" placeholder="Keterangan jika abnormal" value="-">
                                                </div>
                                            </div>
                                            <!-- Respirasi -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Jenis Pernafasan</label>
                                                    <select class="form-select mb-2" name="pemeriksaan_respirasi_jenis_pernafasan">
                                                        <option value="Pernafasan Dada">Pernafasan Dada</option>
                                                        <option value="Alat Bantu Pernafasaan">Alat Bantu Pernafasaan</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pemeriksaan_respirasi_jenis_pernafasan_keterangan" placeholder="Keterangan jenis pernafasan" value="-">
                                                </div>
                                            </div>
                                            <!-- Respirasi Detail -->
                                            <div class="col-md-2">
                                                <label class="form-label">Pola Nafas</label>
                                                <select class="form-select" name="pemeriksaan_respirasi_pola_nafas">
                                                    <option value="Normal">Normal</option>
                                                    <option value="Bradipnea">Bradipnea</option>
                                                    <option value="Tachipnea">Tachipnea</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Retraksi Dada</label>
                                                <select class="form-select" name="pemeriksaan_respirasi_retraksi">
                                                    <option value="Tidak Ada">Tidak Ada</option>
                                                    <option value="Ringan">Ringan</option>
                                                    <option value="Berat">Berat</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Suara Nafas</label>
                                                <select class="form-select" name="pemeriksaan_respirasi_suara_nafas">
                                                    <option value="Vesikuler">Vesikuler</option>
                                                    <option value="Wheezing">Wheezing</option>
                                                    <option value="Rhonki">Rhonki</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Volume Pernafasan</label>
                                                <select class="form-select" name="pemeriksaan_respirasi_volume_pernafasan">
                                                    <option value="Normal">Normal</option>
                                                    <option value="Hiperventilasi">Hiperventilasi</option>
                                                    <option value="Hipoventilasi">Hipoventilasi</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Irama Pernafasan</label>
                                                <select class="form-select" name="pemeriksaan_respirasi_irama_nafas">
                                                    <option value="Teratur">Teratur</option>
                                                    <option value="Tidak Teratur">Tidak Teratur</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Batuk?</label>
                                                <select class="form-select" name="pemeriksaan_respirasi_batuk">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya : Produktif">Ya : Produktif</option>
                                                    <option value="Ya : Non Produktif">Ya : Non Produktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow-sm p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-meat"></i> 3. Gastrointestinal, Neurologi, Integumen & Muskuloskeletal</h4>
                                        <div class="row g-3">
                                            <!-- Gastrointestinal -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3">
                                                    <h5 class="font-weight-bold mb-2 text-secondary">Gastrointestinal</h5>
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Mulut</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_gastrointestinal_mulut">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Stomatitis">Stomatitis</option>
                                                                <option value="Mukosa Kering">Mukosa Kering</option>
                                                                <option value="Bibir Pucat">Bibir Pucat</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_gastrointestinal_mulut_keterangan" placeholder="Ket mulut" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Lidah</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_gastrointestinal_lidah">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Kotor">Kotor</option>
                                                                <option value="Gerak Asimetris">Gerak Asimetris</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_gastrointestinal_lidah_keterangan" placeholder="Ket lidah" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Gigi</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_gastrointestinal_gigi">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Karies">Karies</option>
                                                                <option value="Goyang">Goyang</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_gastrointestinal_gigi_keterangan" placeholder="Ket gigi" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Tenggorokan</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_gastrointestinal_tenggorokan">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Gangguan Menelan">Gangguan Menelan</option>
                                                                <option value="Sakit Menelan">Sakit Menelan</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_gastrointestinal_tenggorokan_keterangan" placeholder="Ket tenggorokan" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Abdomen</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_gastrointestinal_abdomen">
                                                                <option value="Supel">Supel</option>
                                                                <option value="Asictes">Ascites</option>
                                                                <option value=" Tegang">Tegang</option>
                                                                <option value="Nyeri Tekan/Lepas">Nyeri Tekan/Lepas</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_gastrointestinal_abdomen_keterangan" placeholder="Ket abdomen" value="-">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Peristaltik</label>
                                                            <select class="form-select" name="pemeriksaan_gastrointestinal_peistatik_usus">
                                                                <option value="TAK">TAK (Normal)</option>
                                                                <option value="Tidak Ada Bising Usus">Tidak Ada Bising Usus</option>
                                                                <option value="Hiperistaltik">Hiperistaltik</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Anus</label>
                                                            <select class="form-select" name="pemeriksaan_gastrointestinal_anus">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Atresia Ani">Atresia Ani</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Neurologi -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 text-secondary">
                                                    <h5 class="font-weight-bold mb-2 text-secondary">Neurologi (Saraf & Indra)</h5>
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <label class="form-label text-dark">Penglihatan</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_neurologi_pengelihatan">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Ada Kelainan">Ada Kelainan</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_neurologi_pengelihatan_keterangan" placeholder="Ket penglihatan" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-dark">Bicara</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_neurologi_bicara">
                                                                <option value="Jelas">Jelas</option>
                                                                <option value="Tidak Jelas">Tidak Jelas</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_neurologi_bicara_keterangan" placeholder="Ket bicara" value="-">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label text-dark">Alat Bantu Lihat</label>
                                                            <select class="form-select" name="pemeriksaan_neurologi_alat_bantu_penglihatan">
                                                                <option value="Tidak">Tidak</option>
                                                                <option value="Kacamata">Kacamata</option>
                                                                <option value="Lensa Kontak">Lensa Kontak</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label text-dark">Pendengaran</label>
                                                            <select class="form-select" name="pemeriksaan_neurologi_pendengaran">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Berdengung">Berdengung</option>
                                                                <option value="Nyeri">Nyeri</option>
                                                                <option value="Tuli">Tuli</option>
                                                                <option value="Keluar Cairan">Keluar Cairan</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label text-dark">Sensibilitas</label>
                                                            <select class="form-select" name="pemeriksaan_neurologi_sensorik">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Sakit Nyeri">Sakit Nyeri</option>
                                                                <option value="Rasa Kebas">Rasa Kebas</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-dark">Motorik Saraf</label>
                                                            <select class="form-select" name="pemeriksaan_neurologi_motorik">
                                                                <option value="TAK">TAK (Tidak Ada Kelainan)</option>
                                                                <option value="Hemiparese">Hemiparese</option>
                                                                <option value="Tetraparese">Tetraparese</option>
                                                                <option value="Tremor">Tremor</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label text-dark">Kekuatan Otot</label>
                                                            <select class="form-select" name="pemeriksaan_neurologi_kekuatan_otot">
                                                                <option value="Kuat">Kuat</option>
                                                                <option value="Lemah">Lemah</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Integumen (Kulit) -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3">
                                                    <h5 class="font-weight-bold mb-2 text-secondary">Integument (Kulit)</h5>
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Warna Kulit</label>
                                                            <select class="form-select" name="pemeriksaan_integument_warnakulit">
                                                                <option value="Normal">Normal</option>
                                                                <option value="Pucat">Pucat</option>
                                                                <option value="Sianosis">Sianosis</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Turgor</label>
                                                            <select class="form-select" name="pemeriksaan_integument_turgor">
                                                                <option value="Baik">Baik</option>
                                                                <option value="Sedang">Sedang</option>
                                                                <option value="Buruk">Buruk</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Integritas Kulit</label>
                                                            <select class="form-select" name="pemeriksaan_integument_kulit">
                                                                <option value="Normal">Normal (Utuh)</option>
                                                                <option value="Rash/Kemerahan">Rash/Kemerahan</option>
                                                                <option value="Luka">Luka</option>
                                                                <option value="Memar">Memar</option>
                                                                <option value="Ptekie">Ptekie</option>
                                                                <option value="Bula">Bula</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Resiko Dekubitus</label>
                                                            <select class="form-select" name="pemeriksaan_integument_dekubitas">
                                                                <option value="Tidak Ada">Tidak Ada Risiko</option>
                                                                <option value="Usia > 65 tahun">Usia > 65 tahun</option>
                                                                <option value="Obesitas">Obesitas</option>
                                                                <option value="Imobilisasi">Imobilisasi</option>
                                                                <option value="Paraplegi/Vegetative State">Paraplegi/Vegetative State</option>
                                                                <option value="Dirawat Di HCU">Dirawat Di HCU</option>
                                                                <option value="Penyakit Kronis (DM, CHF, CKD)">Penyakit Kronis (DM, CHF, CKD)</option>
                                                                <option value="Inkontinentia Uri/Alvi">Inkontinentia Uri/Alvi</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Muskuloskeletal -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3">
                                                    <h5 class="font-weight-bold mb-2 text-secondary">Muskuloskeletal (Gerak)</h5>
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Gerak Sendi</label>
                                                            <select class="form-select" name="pemeriksaan_muskuloskletal_pergerakan_sendi">
                                                                <option value="Bebas">Bebas</option>
                                                                <option value="Terbatas">Terbatas</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Kekuatan Otot</label>
                                                            <select class="form-select" name="pemeriksaan_muskuloskletal_kekauatan_otot">
                                                                <option value="Baik">Baik (Kuat)</option>
                                                                <option value="Lemah">Lemah</option>
                                                                <option value="Tremor">Tremor</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Fraktur?</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_muskuloskletal_fraktur">
                                                                <option value="Tidak Ada">Tidak</option>
                                                                <option value="Ada">Ya</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_muskuloskletal_fraktur_keterangan" placeholder="Keterangan fraktur" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Nyeri Sendi?</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_muskuloskletal_nyeri_sendi">
                                                                <option value="Tidak Ada">Tidak</option>
                                                                <option value="Ada">Ya</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_muskuloskletal_nyeri_sendi_keterangan" placeholder="Keterangan lokasi nyeri sendi" value="-">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Oedema (Bengkak)?</label>
                                                            <select class="form-select mb-1" name="pemeriksaan_muskuloskletal_oedema">
                                                                <option value="Tidak Ada">Tidak</option>
                                                                <option value="Ada">Ya</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm" name="pemeriksaan_muskuloskletal_oedema_keterangan" placeholder="Keterangan lokasi oedema" value="-">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card bg-light border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-toilet-paper"></i> 4. Eliminasi (BAB & BAK)</h4>
                                        <div class="row g-3">
                                            <!-- BAB -->
                                            <div class="col-md-6 border-end">
                                                <h5 class="font-weight-bold text-secondary mb-2">Eliminasi Alvi (BAB)</h5>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Frekuensi BAB</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="pemeriksaan_eliminasi_bab_frekuensi_jumlah" value="1" min="0">
                                                            <select class="form-select" name="pemeriksaan_eliminasi_bab_frekuensi_durasi">
                                                                <option value="x/hari">x/hari</option>
                                                                <option value="x/minggu">x/minggu</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Konsistensi</label>
                                                        <select class="form-select" name="pemeriksaan_eliminasi_bab_konsistensi">
                                                            <option value="Lunak">Lunak</option>
                                                            <option value="Keras">Keras</option>
                                                            <option value="Cair">Cair</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Warna</label>
                                                        <select class="form-select" name="pemeriksaan_eliminasi_bab_warna">
                                                            <option value="Kuning">Kuning</option>
                                                            <option value="Coklat">Coklat</option>
                                                            <option value="Hitam">Hitam</option>
                                                            <option value="Merah">Merah</option>
                                                            <option value="Lain-lain">Lain-lain</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- BAK -->
                                            <div class="col-md-6">
                                                <h5 class="font-weight-bold text-secondary mb-2">Eliminasi Uri (BAK)</h5>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Frekuensi BAK</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" name="pemeriksaan_eliminasi_bak_frekuensi_jumlah" value="4" min="0">
                                                            <select class="form-select" name="pemeriksaan_eliminasi_bak_frekuensi_durasi">
                                                                <option value="x/hari">x/hari</option>
                                                                <option value="x/minggu">x/minggu</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Warna</label>
                                                        <select class="form-select" name="pemeriksaan_eliminasi_bak_warna">
                                                            <option value="Kuning Jernih">Kuning Jernih</option>
                                                            <option value="Kuning Pekat">Kuning Pekat</option>
                                                            <option value="Merah">Merah</option>
                                                            <option value="Lain-lain">Lain-lain</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Lain-lain BAK</label>
                                                        <input type="text" class="form-control" name="pemeriksaan_eliminasi_bak_lainlain" placeholder="Misal: Terpasang Kateter" value="-">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 3: POLA & FUNGSI ==================== -->
                        <div class="tab-pane fade" id="tab-pola" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-archive"></i> 1. Kemandirian Aktivitas Sehari-hari (ADL Barthel Index)</h4>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Makan & Minum</label>
                                                <select class="form-select" name="pola_aktifitas_makanminum">
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="Dibantu Sebagian">Dibantu Sebagian</option>
                                                    <option value="Dibantu Total">Dibantu Total</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Mandi</label>
                                                <select class="form-select" name="pola_aktifitas_mandi">
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="Dibantu Sebagian">Dibantu Sebagian</option>
                                                    <option value="Dibantu Total">Dibantu Total</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Eliminasi (BAB/BAK)</label>
                                                <select class="form-select" name="pola_aktifitas_eliminasi">
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="Dibantu Sebagian">Dibantu Sebagian</option>
                                                    <option value="Dibantu Total">Dibantu Total</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Berpakaian</label>
                                                <select class="form-select" name="pola_aktifitas_berpakaian">
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="Dibantu Sebagian">Dibantu Sebagian</option>
                                                    <option value="Dibantu Total">Dibantu Total</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Berpindah / Mobilisasi</label>
                                                <select class="form-select" name="pola_aktifitas_berpindah">
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="Dibantu Sebagian">Dibantu Sebagian</option>
                                                    <option value="Dibantu Total">Dibantu Total</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-cookie"></i> 2. Nutrisi & Tidur Umum</h4>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label">Frekuensi Makan</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="pola_nutrisi_frekuesi_makan" value="3">
                                                    <span class="input-group-text">x/hari</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porsi Makan</label>
                                                <select class="form-select" name="pola_nutrisi_porsi_makan">
                                                    <option value="1 Porsi Habis">1 Porsi Habis</option>
                                                    <option value="1/2 Porsi">1/2 Porsi</option>
                                                    <option value="Hanya 2-3 Sendok">Hanya 2-3 Sendok</option>
                                                    <option value="Tidak Mau Makan">Tidak Mau Makan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Jenis Makanan</label>
                                                <input type="text" class="form-control" name="pola_nutrisi_jenis_makanan" placeholder="Nasi, bubur, susu, dll" value="Nasi/Bubur">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Lama Tidur (Jam/Hari)</label>
                                                <input type="number" class="form-control" name="pola_tidur_lama_tidur" value="8" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Ada Gangguan Tidur?</label>
                                                <select class="form-select" name="pola_tidur_gangguan">
                                                    <option value="Tidak Ada">Tidak Ada</option>
                                                    <option value="Ada">Ada</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card border-0 shadow-sm p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-settings"></i> 3. Pengkajian Fungsi Tubuh Lebih Lanjut</h4>
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label font-weight-bold">Kemampuan Sehari-hari</label>
                                                <select class="form-select" name="pengkajian_fungsi_kemampuan_sehari">
                                                    <option value="Mandiri">Mandiri</option>
                                                    <option value="Dibantu Sebagian">Dibantu Sebagian</option>
                                                    <option value="Dibantu Total">Dibantu Total</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label font-weight-bold">Aktivitas</label>
                                                <select class="form-select" name="pengkajian_fungsi_aktifitas">
                                                    <option value="Biasa">Biasa</option>
                                                    <option value="Kurang">Kurang / Lemah</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label font-weight-bold">Ambulasi</label>
                                                <select class="form-select" name="pengkajian_fungsi_ambulasi">
                                                    <option value="Tanpa Bantuan">Tanpa Bantuan</option>
                                                    <option value="Dengan Bantuan">Dengan Bantuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label font-weight-bold">Kesimpulan Gangguan Fungsi</label>
                                                <select class="form-select" name="pengkajian_fungsi_kesimpulan">
                                                    <option value="Tidak Ada">Tidak Ada Gangguan</option>
                                                    <option value="Ada">Ada Gangguan Fungsi</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Berjalan -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Kemampuan Berjalan</label>
                                                    <select class="form-select mb-1" name="pengkajian_fungsi_berjalan">
                                                        <option value="Tanpa Bantuan">Tanpa Bantuan</option>
                                                        <option value="Dengan Bantuan">Dengan Bantuan</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pengkajian_fungsi_berjalan_keterangan" placeholder="Keterangan jalan" value="-">
                                                </div>
                                            </div>
                                            <!-- Ekstremitas Atas -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Ekstremitas Atas</label>
                                                    <select class="form-select mb-1" name="pengkajian_fungsi_ekstrimitas_atas">
                                                        <option value="Normal">Normal</option>
                                                        <option value="Abnormal">Abnormal</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pengkajian_fungsi_ekstrimitas_atas_keterangan" placeholder="Ket Ekstremitas Atas" value="-">
                                                </div>
                                            </div>
                                            <!-- Ekstremitas Bawah -->
                                            <div class="col-md-4">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Ekstremitas Bawah</label>
                                                    <select class="form-select mb-1" name="pengkajian_fungsi_ekstrimitas_bawah">
                                                        <option value="Normal">Normal</option>
                                                        <option value="Abnormal">Abnormal</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pengkajian_fungsi_ekstrimitas_bawah_keterangan" placeholder="Ket Ekstremitas Bawah" value="-">
                                                </div>
                                            </div>
                                            <!-- Menggenggam -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Kemampuan Menggenggam</label>
                                                    <select class="form-select mb-1" name="pengkajian_fungsi_menggenggam">
                                                        <option value="Normal">Normal</option>
                                                        <option value="Abnormal">Abnormal</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pengkajian_fungsi_menggenggam_keterangan" placeholder="Keterangan kekuatan menggenggam" value="-">
                                                </div>
                                            </div>
                                            <!-- Koordinasi -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-2">
                                                    <label class="form-label font-weight-bold">Kemampuan Koordinasi Gerak</label>
                                                    <select class="form-select mb-1" name="pengkajian_fungsi_koordinasi">
                                                        <option value="Normal">Normal</option>
                                                        <option value="Abnormal">Abnormal</option>
                                                    </select>
                                                    <input type="text" class="form-control form-control-sm" name="pengkajian_fungsi_koordinasi_keterangan" placeholder="Keterangan koordinasi" value="-">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 4: PSIKO & EDUKASI ==================== -->
                        <div class="tab-pane fade" id="tab-psiko" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-face-suprise"></i> 1. Kondisi Psikologis & Perilaku</h4>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Status Psikologis</label>
                                                <select class="form-select" name="riwayat_psiko_kondisi_psiko">
                                                    <option value="Tidak Ada Masalah">Tidak Ada Masalah (Tenang)</option>
                                                    <option value="Cemas">Cemas</option>
                                                    <option value="Takut">Takut</option>
                                                    <option value="Depresi">Depresi (Sedih)</option>
                                                    <option value="Marah">Marah</option>
                                                    <option value="Cepat Lelah">Cepat Lelah</option>
                                                    <option value="Gelisah">Gelisah</option>
                                                    <option value="Sulit Tidur">Sulit Tidur</option>
                                                    <option value="Lain-lain">Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Riwayat Gangguan Jiwa dahulu?</label>
                                                <select class="form-select" name="riwayat_psiko_gangguan_jiwa">
                                                    <option value="Tidak">Tidak Ada</option>
                                                    <option value="Ya">Ada</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Perilaku Khusus</label>
                                                <select class="form-select" name="riwayat_psiko_perilaku">
                                                    <option value="Tidak Ada Masalah">Tidak Ada Masalah (Biasa)</option>
                                                    <option value="Perilaku Kekerasan">Perilaku Kekerasan</option>
                                                    <option value="Gangguan Efek">Gangguan Efek</option>
                                                    <option value="Gangguan Memori">Gangguan Memori</option>
                                                    <option value="Halusinasi">Halusinasi</option>
                                                    <option value="Kecenderungan Percobaan Bunuh Diri">Kecenderungan Percobaan Bunuh Diri</option>
                                                    <option value="Lain-lain">Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Keterangan Perilaku</label>
                                                <input type="text" class="form-control" name="riwayat_psiko_perilaku_keterangan" value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-users"></i> 2. Sosial & Spiritual Pasien</h4>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Hubungan dengan Keluarga</label>
                                                <select class="form-select" name="riwayat_psiko_hubungan_keluarga">
                                                    <option value="Harmonis">Harmonis (Baik)</option>
                                                    <option value="Kurang Harmonis">Kurang Harmonis</option>
                                                    <option value="Tidak Harmonis">Tidak Harmonis (Tidak Baik)</option>
                                                    <option value="Konflik Besar">Konflik Besar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Pendidikan Terakhir PJ</label>
                                                <select class="form-select" name="riwayat_psiko_pendidikan_pj">
                                                    <option value="-">-</option>
                                                    <option value="TS">Tidak Sekolah (TS)</option>
                                                    <option value="TK">TK</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA">SMA</option>
                                                    <option value="SLTA/SEDERAJAT">SLTA/Sederajat</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="D4">D4</option>
                                                    <option value="S1">S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tinggal Bersama Siapa?</label>
                                                <select class="form-select" name="riwayat_psiko_tinggal">
                                                    <option value="Keluarga">Keluarga</option>
                                                    <option value="Sendiri">Sendiri</option>
                                                    <option value="Orang Tua">Orang Tua</option>
                                                    <option value="Suami/Istri">Suami/Istri</option>
                                                    <option value="Lain-lain">Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Detail Tempat Tinggal</label>
                                                <input type="text" class="form-control" name="riwayat_psiko_tinggal_keterangan" value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card border-0 shadow-sm p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-help-circle"></i> 3. Nilai Kepercayaan & Kebutuhan Edukasi</h4>
                                        <div class="row g-3">
                                            <div class="col-md-6 border-end">
                                                <label class="form-label font-weight-bold">Nilai-nilai Kepercayaan & Budaya Khusus yang Perlu Diperhatikan</label>
                                                <select class="form-select mb-2" name="riwayat_psiko_nilai_kepercayaan">
                                                    <option value="Tidak Ada">Tidak Ada</option>
                                                    <option value="Ada">Ada</option>
                                                </select>
                                                <textarea class="form-control" name="riwayat_psiko_nilai_kepercayaan_keterangan" rows="2" placeholder="Sebutkan nilai budaya/kepercayaan pasien yang mempengaruhi pelayanan medis jika ada...">-</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label font-weight-bold">Kebutuhan Edukasi Diberikan Kepada</label>
                                                <select class="form-select mb-2" name="riwayat_psiko_edukasi_diberikan">
                                                    <option value="Pasien">Pasien</option>
                                                    <option value="Keluarga">Keluarga</option>
                                                </select>
                                                <textarea class="form-control" name="riwayat_psiko_edukasi_diberikan_keterangan" rows="2" placeholder="Sebutkan kendala bahasa/pendengaran/edukasi khusus jika ada...">-</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 5: NYERI & JATUH ==================== -->
                        <div class="tab-pane fade" id="tab-nyeri" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-flame"></i> 1. Skrining & Pengkajian Nyeri Komprehensif</h4>
                                        <div class="row g-2">
                                            <div class="col-md-2">
                                                <label class="form-label font-weight-bold">Keluhan Nyeri?</label>
                                                <select class="form-select" name="penilaian_nyeri" id="ranap_penilaian_nyeri">
                                                    <option value="Tidak Ada Nyeri">Tidak Ada Nyeri</option>
                                                    <option value="Nyeri Akut">Nyeri Akut</option>
                                                    <option value="Nyeri Kronis">Nyeri Kronis</option>
                                                </select>
                                            </div>
                                            <div class="col-md-10" id="ranap_nyeri_details_container">
                                                <div class="row g-2">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Penyebab Nyeri (P)</label>
                                                        <div class="input-group">
                                                            <select class="form-select" name="penilaian_nyeri_penyebab">
                                                                <option value="Proses Penyakit">Proses Penyakit</option>
                                                                <option value="Benturan">Benturan</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control" name="penilaian_nyeri_ket_penyebab" placeholder="Detail penyebab" value="-">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Kualitas Nyeri (Q)</label>
                                                        <div class="input-group">
                                                            <select class="form-select" name="penilaian_nyeri_kualitas">
                                                                <option value="Seperti Tertusuk">Seperti Tertusuk</option>
                                                                <option value="Berdenyut">Berdenyut</option>
                                                                <option value="Teriris">Teriris</option>
                                                                <option value="Tertindih">Tertindih</option>
                                                                <option value="Tertiban">Tertiban</option>
                                                                <option value="Lain-lain">Lain-lain</option>
                                                            </select>
                                                            <input type="text" class="form-control" name="penilaian_nyeri_ket_kualitas" placeholder="Detail kualitas" value="-">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Lokasi / Radiasi (R)</label>
                                                        <div class="input-group">
                                                            <select class="form-select" name="penilaian_nyeri_menyebar">
                                                                <option value="Tidak">Tidak Menyebar</option>
                                                                <option value="Ya">Menyebar</option>
                                                            </select>
                                                            <input type="text" class="form-control w-50" name="penilaian_nyeri_lokasi" placeholder="Lokasi Nyeri" value="-">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Skala Nyeri (S) : <b id="lbl_skala_nyeri" class="text-danger">0</b></label>
                                                        <input type="range" class="form-range" name="penilaian_nyeri_skala" id="ranap_penilaian_nyeri_skala" min="0" max="10" value="0">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Durasi / Waktu (T)</label>
                                                        <input type="text" class="form-control" name="penilaian_nyeri_waktu" placeholder="Misal: Hilang Timbul / 5 mnt" value="-">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Nyeri Hilang Bila</label>
                                                        <div class="input-group">
                                                            <select class="form-select" name="penilaian_nyeri_hilang">
                                                                <option value="Istirahat">Istirahat</option>
                                                                <option value="Medengar Musik">Mendengar Musik</option>
                                                                <option value="Minum Obat">Minum Obat</option>
                                                            </select>
                                                            <input type="text" class="form-control" name="penilaian_nyeri_ket_hilang" placeholder="Ket hilang" value="-">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Diberitahukan Dokter?</label>
                                                        <div class="input-group">
                                                            <select class="form-select" name="penilaian_nyeri_diberitahukan_dokter">
                                                                <option value="Tidak">Tidak</option>
                                                                <option value="Ya">Ya</option>
                                                            </select>
                                                            <input type="text" class="form-control" name="penilaian_nyeri_jam_diberitahukan_dokter" placeholder="Jam dberitahu" value="-">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dynamic Fall Risk Split -->
                                <div class="col-12 mt-2">
                                    <div class="d-flex align-items-center justify-content-between p-2 bg-light border rounded mb-3">
                                        <div class="font-weight-bold text-dark"><i class="ti ti-chart-arrows"></i> Pilih Kategori Skala Risiko Jatuh:</div>
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="kategoriResikoJatuh" id="optMorse" value="morse" checked>
                                            <label class="btn btn-outline-primary" for="optMorse">Morse (Pasien Dewasa)</label>

                                            <input type="radio" class="btn-check" name="kategoriResikoJatuh" id="optSydney" value="sydney">
                                            <label class="btn btn-outline-primary" for="optSydney">Sydney / Humpty Dumpty (Pasien Anak)</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- MORSE FALL RISK SECTION -->
                                <div class="col-12" id="secMorse">
                                    <div class="card border-0 shadow-sm p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="font-weight-bold text-primary m-0"><i class="ti ti-man"></i> 2. Skala Risiko Jatuh Morse (Dewasa)</h4>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="fs-3 font-weight-bold">Skor Total Morse:</div>
                                                <input type="text" class="form-control text-center font-weight-bold fs-2 text-danger bg-light" name="penilaian_jatuhmorse_totalnilai" id="morse_total" style="width: 70px;" readonly value="0">
                                                <span class="badge py-2 px-3 fs-3" id="badge_morse_result">Resiko Rendah</span>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped">
                                                <thead class="bg-light text-center font-weight-bold">
                                                    <tr>
                                                        <th style="width: 5%;">No</th>
                                                        <th style="width: 55%;">Faktor Risiko</th>
                                                        <th style="width: 30%;">Skala Pilihan</th>
                                                        <th style="width: 10%;">Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Morse 1 -->
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td>Riwayat Jatuh (baru-baru ini / dalam 3 bulan terakhir)</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-morse" name="penilaian_jatuhmorse_skala1" id="m1">
                                                                <option value="Tidak" data-score="0">Tidak (Skor: 0)</option>
                                                                <option value="Ya" data-score="25">Ya (Skor: 25)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhmorse_nilai1" id="m1_val" readonly value="0"></td>
                                                    </tr>
                                                    <!-- Morse 2 -->
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td>Diagnosis Sekunder (Diagnosis medis lebih dari satu)</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-morse" name="penilaian_jatuhmorse_skala2" id="m2">
                                                                <option value="Tidak" data-score="0">Tidak (Skor: 0)</option>
                                                                <option value="Ya" data-score="15">Ya (Skor: 15)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhmorse_nilai2" id="m2_val" readonly value="0"></td>
                                                    </tr>
                                                    <!-- Morse 3 -->
                                                    <tr>
                                                        <td class="text-center">3</td>
                                                        <td>Alat Bantu Ambulasi / Berjalan</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-morse" name="penilaian_jatuhmorse_skala3" id="m3">
                                                                <option value="Tidak Ada/Kursi Roda/Perawat/Tirah Baring" data-score="0">Tidak Ada / Kursi Roda / Perawat / Tirah Baring (Skor: 0)</option>
                                                                <option value="Tongkat/Alat Penopang" data-score="15">Tongkat / Alat Penopang (Skor: 15)</option>
                                                                <option value="Berpegangan Pada Perabot" data-score="30">Berpegangan Pada Perabot (Skor: 30)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhmorse_nilai3" id="m3_val" readonly value="0"></td>
                                                    </tr>
                                                    <!-- Morse 4 -->
                                                    <tr>
                                                        <td class="text-center">4</td>
                                                        <td>Terpasang Infus intravena / IV line / Heparin lock</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-morse" name="penilaian_jatuhmorse_skala4" id="m4">
                                                                <option value="Tidak" data-score="0">Tidak (Skor: 0)</option>
                                                                <option value="Ya" data-score="20">Ya (Skor: 20)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhmorse_nilai4" id="m4_val" readonly value="0"></td>
                                                    </tr>
                                                    <!-- Morse 5 -->
                                                    <tr>
                                                        <td class="text-center">5</td>
                                                        <td>Gaya Berjalan / Cara Berpindah / Transfer</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-morse" name="penilaian_jatuhmorse_skala5" id="m5">
                                                                <option value="Normal/Tirah Baring/Imobilisasi" data-score="0">Normal / Tirah Baring / Imobilisasi (Skor: 0)</option>
                                                                <option value="Lemah" data-score="10">Lemah / Langkah Pendek, Lambat, Membungkuk (Skor: 10)</option>
                                                                <option value="Terganggu" data-score="20">Terganggu / Langkah Goyang, Tidak Stabil (Skor: 20)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhmorse_nilai5" id="m5_val" readonly value="0"></td>
                                                    </tr>
                                                    <!-- Morse 6 -->
                                                    <tr>
                                                        <td class="text-center">6</td>
                                                        <td>Status Mental</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-morse" name="penilaian_jatuhmorse_skala6" id="m6">
                                                                <option value="Sadar Akan Kemampuan Diri Sendiri" data-score="0">Sadar Akan Kemampuan Diri Sendiri (Skor: 0)</option>
                                                                <option value="Sering Lupa Akan Keterbatasan Yang Dimiliki" data-score="15">Sering Lupa Akan Keterbatasan Yang Dimiliki (Skor: 15)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhmorse_nilai6" id="m6_val" readonly value="0"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- SYDNEY FALL RISK SECTION -->
                                <div class="col-12 d-none" id="secSydney">
                                    <div class="card border-0 shadow-sm p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="font-weight-bold text-primary m-0"><i class="ti ti-baby"></i> 3. Skala Humpty Dumpty / Sydney (Anak)</h4>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="fs-3 font-weight-bold">Skor Total Sydney:</div>
                                                <input type="text" class="form-control text-center font-weight-bold fs-2 text-danger bg-light" name="penilaian_jatuhsydney_totalnilai" id="sydney_total" style="width: 70px;" readonly value="0">
                                                <span class="badge py-2 px-3 fs-3" id="badge_sydney_result">Resiko Rendah</span>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped">
                                                <thead class="bg-light text-center font-weight-bold">
                                                    <tr>
                                                        <th style="width: 5%;">No</th>
                                                        <th style="width: 55%;">Faktor Risiko Humpty Dumpty</th>
                                                        <th style="width: 30%;">Skala Pilihan</th>
                                                        <th style="width: 10%;">Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Sydney 1 -->
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td>Umur Pasien</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala1" id="s1">
                                                                <option value="Tidak" data-score="4">Di bawah 3 tahun (Skor: 4)</option>
                                                                <option value="Ya" data-score="3">3 - 7 tahun (Skor: 3)</option>
                                                                <option value="Tidak" data-score="2" selected>7 - 12 tahun (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1">>= 12 tahun (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai1" id="s1_val" readonly value="2"></td>
                                                    </tr>
                                                    <!-- Sydney 2 -->
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td>Jenis Kelamin</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala2" id="s2">
                                                                <option value="Ya" data-score="2" selected>Laki-laki (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1">Perempuan (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai2" id="s2_val" readonly value="2"></td>
                                                    </tr>
                                                    <!-- Sydney 3 -->
                                                    <tr>
                                                        <td class="text-center">3</td>
                                                        <td>Diagnosis medis kelainan saraf / psikologi</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala3" id="s3">
                                                                <option value="Ya" data-score="4">Kelainan Saraf / Kejiwaan Berat (Skor: 4)</option>
                                                                <option value="Tidak" data-score="3">Gangguan Oksigenasi / Nafas (Skor: 3)</option>
                                                                <option value="Tidak" data-score="2">Gangguan Perilaku / Depresi (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1" selected>Diagnosis Lain (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai3" id="s3_val" readonly value="1"></td>
                                                    </tr>
                                                    <!-- Sydney 4 -->
                                                    <tr>
                                                        <td class="text-center">4</td>
                                                        <td>Gangguan Kognitif / Orientasi</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala4" id="s4">
                                                                <option value="Tidak" data-score="3">Tidak menyadari keterbatasan (Skor: 3)</option>
                                                                <option value="Ya" data-score="2">Lupa akan keterbatasan (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1" selected>Orientasi baik / Menyadari kemampuan (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai4" id="s4_val" readonly value="1"></td>
                                                    </tr>
                                                    <!-- Sydney 5 -->
                                                    <tr>
                                                        <td class="text-center">5</td>
                                                        <td>Faktor Lingkungan / Riwayat Jatuh</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala5" id="s5">
                                                                <option value="Tidak" data-score="4">Riwayat jatuh / bayi ditaruh tempat tidur dewasa (Skor: 4)</option>
                                                                <option value="Ya" data-score="3">Menggunakan alat bantu jalan / box bayi khusus (Skor: 3)</option>
                                                                <option value="Tidak" data-score="2">Pasien diletakkan di tempat tidur standar (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1" selected>Pasien rawat jalan / area bermain (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai5" id="s5_val" readonly value="1"></td>
                                                    </tr>
                                                    <!-- Sydney 6 -->
                                                    <tr>
                                                        <td class="text-center">6</td>
                                                        <td>Respon terhadap pembedahan / Sedasi / Anestesi</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala6" id="s6">
                                                                <option value="Ya" data-score="3">Dalam 24 Jam Pasca Bedah / Sedasi (Skor: 3)</option>
                                                                <option value="Tidak" data-score="2">Dalam 48 Jam Pasca Bedah (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1" selected>>= 48 jam / Tanpa pembedahan (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai6" id="s6_val" readonly value="1"></td>
                                                    </tr>
                                                    <!-- Sydney 7 -->
                                                    <tr>
                                                        <td class="text-center">7</td>
                                                        <td>Penggunaan Obat-obatan (Penenang, Diuretik, Narkotik, dll)</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala7" id="s7">
                                                                <option value="Ya" data-score="3">Bermacam obat (Sedatif, Diuretik, Barbiturat, Laksatif) (Skor: 3)</option>
                                                                <option value="Tidak" data-score="2">Salah satu obat di atas (Skor: 2)</option>
                                                                <option value="Tidak" data-score="1" selected>Obat lain / tanpa pengobatan (Skor: 1)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai7" id="s7_val" readonly value="1"></td>
                                                    </tr>
                                                    <!-- Sydney 8 to 11 placeholders mapped to Khanza subscales -->
                                                    <tr>
                                                        <td class="text-center">8</td>
                                                        <td>Mobilisasi & Keseimbangan</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala8" id="s8">
                                                                <option value="Tidak" data-score="0">Baik / Mandiri (Skor: 0)</option>
                                                                <option value="Ya" data-score="2">Tidak Seimbang / Diseret (Skor: 2)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai8" id="s8_val" readonly value="0"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">9</td>
                                                        <td>Kebiasaan Aktivitas Motorik</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala9" id="s9">
                                                                <option value="Tidak" data-score="0">Tenang / Kooperatif (Skor: 0)</option>
                                                                <option value="Ya" data-score="2">Hiperaktif / Suka manjat (Skor: 2)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai9" id="s9_val" readonly value="0"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">10</td>
                                                        <td>Status Fisiologis Sensorik</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala10" id="s10">
                                                                <option value="Tidak" data-score="0">Normal (Skor: 0)</option>
                                                                <option value="Ya" data-score="2">Gangguan Penglihatan / Pendengaran (Skor: 2)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai10" id="s10_val" readonly value="0"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">11</td>
                                                        <td>Status Pengawasan Orang Tua</td>
                                                        <td>
                                                            <select class="form-select form-select-sm calculate-sydney" name="penilaian_jatuhsydney_skala11" id="s11">
                                                                <option value="Tidak" data-score="0">Didampingi Penuh (Skor: 0)</option>
                                                                <option value="Ya" data-score="2">Kurang Pengawasan / Sering Ditinggal (Skor: 2)</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control form-control-sm text-center bg-light" name="penilaian_jatuhsydney_nilai11" id="s11_val" readonly value="0"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 6: GIZI & RENCANA ==================== -->
                        <div class="tab-pane fade" id="tab-gizi" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="card border-0 bg-muted-lt p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="font-weight-bold text-primary m-0"><i class="ti ti-salad"></i> 1. Skrining Gizi Dewasa / Malnutrition Screening Tool (MST)</h4>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="fs-3 font-weight-bold">Skor Total Gizi:</div>
                                                <input type="text" class="form-control text-center font-weight-bold fs-2 text-danger bg-light" name="nilai_total_gizi" id="gizi_total" style="width: 70px;" readonly value="0">
                                                <span class="badge py-2 px-3 fs-3" id="badge_gizi_result">Resiko Rendah</span>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6 border-end">
                                                <label class="form-label font-weight-bold">A. Apakah ada penurunan berat badan yang tidak diinginkan dalam 6 bulan terakhir?</label>
                                                <select class="form-select calculate-gizi mb-2" name="skrining_gizi1" id="ranap_sg1">
                                                    <option value="Tidak ada penurunan berat badan" data-score="0">Tidak ada penurunan berat badan (Skor: 0)</option>
                                                    <option value="Tidak yakin/ tidak tahu/ terasa baju lebih longgar" data-score="2">Tidak yakin / tidak tahu / terasa baju longgar (Skor: 2)</option>
                                                    <option value="Ya 1-5 kg" data-score="1">Ya, penurunan 1 - 5 Kg (Skor: 1)</option>
                                                    <option value="Ya 6-10 kg" data-score="2">Ya, penurunan 6 - 10 Kg (Skor: 2)</option>
                                                    <option value="Ya 11-15 kg" data-score="3">Ya, penurunan 11 - 15 Kg (Skor: 3)</option>
                                                    <option value="Ya > 15 kg" data-score="4">Ya, penurunan > 15 Kg (Skor: 4)</option>
                                                </select>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="small text-muted">Nilai Parameter A:</span>
                                                    <input type="text" class="form-control form-control-sm text-center bg-light" style="width: 50px;" name="nilai_gizi1" id="ranap_n1" readonly value="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label font-weight-bold">B. Apakah asupan makan berkurang karena tidak nafsu makan / kesulitan makan?</label>
                                                <select class="form-select calculate-gizi mb-2" name="skrining_gizi2" id="ranap_sg2">
                                                    <option value="Tidak" data-score="0">Tidak (Skor: 0)</option>
                                                    <option value="Ya" data-score="1">Ya (Skor: 1)</option>
                                                </select>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="small text-muted">Nilai Parameter B:</span>
                                                    <input type="text" class="form-control form-control-sm text-center bg-light" style="width: 50px;" name="nilai_gizi2" id="ranap_n2" readonly value="0">
                                                </div>
                                            </div>
                                            
                                            <!-- Diagnosis Khusus -->
                                            <div class="col-md-6 border-end pt-2">
                                                <label class="form-label font-weight-bold">Pasien dengan diagnosis khusus (Penyakit DM, Ginjal, Kanker, Paru, dll)?</label>
                                                <select class="form-select mb-2" name="skrining_gizi_diagnosa_khusus">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm" name="skrining_gizi_ket_diagnosa_khusus" placeholder="Tuliskan nama diagnosa khusus jika Ya" value="-">
                                            </div>
                                            <!-- Dietisen -->
                                            <div class="col-md-6 pt-2">
                                                <label class="form-label font-weight-bold">Sudah dibaca dan diketahui oleh Dietisen?</label>
                                                <select class="form-select mb-2" name="skrining_gizi_diketahui_dietisen">
                                                    <option value="Tidak">Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                                <input type="text" class="form-control form-control-sm" name="skrining_gizi_jam_diketahui_dietisen" placeholder="Isi jam dibaca dietisen, misal: 10:00" value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="card border-0 shadow-sm p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-notes-medical"></i> 2. Rencana Tindakan Keperawatan</h4>
                                        <div class="row g-2">
                                            <div class="col-md-12">
                                                <label class="form-label">Rencana Keperawatan Lainnya / Tindakan Awal</label>
                                                <textarea class="form-control" name="rencana" rows="3" placeholder="Tuliskan rencana implementasi asuhan keperawatan awal...">-</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-users-group"></i> 3. Tanda Tangan & DPJP Pengkaji</h4>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold text-dark">NIP Pengkaji 1 (Perawat Utama)</label>
                                                <select class="form-select select2-ranap" name="nip1" id="ranap_nip1" style="width: 100%;"></select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold text-dark">NIP Pengkaji 2 (Perawat Kedua)</label>
                                                <select class="form-select select2-ranap" name="nip2" id="ranap_nip2" style="width: 100%;"></select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label font-weight-bold text-dark">Dokter DPJP Pasien</label>
                                                <select class="form-select select2-ranap" name="kd_dokter" id="ranap_kd_dokter" style="width: 100%;"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer bg-light py-3 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i> Batal</button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="btnCetakRanap" onclick="printPenilaianAwalKeperawatanRanap()"><i class="ti ti-printer me-1"></i> Cetak Asesmen</button>
                    <button type="button" class="btn btn-success" onclick="simpanPenilaianAwalKeperawatanRanap()"><i class="ti ti-device-floppy me-1"></i> Simpan Penilaian</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modalCetakPenilaianRanap" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title m-0 text-white font-weight-bold"><i class="ti ti-printer me-2"></i> Cetak Asesmen Keperawatan Rawat Inap</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="printFrameRanap" type="" width="100%" height="650" style="border:0;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        var modalPenilaianAwalKeperawatanRanap = $('#modalPenilaianAwalKeperawatanRanap');
        var modalCetakPenilaianRanap = $('#modalCetakPenilaianRanap');
        
        $(document).ready(function() {
            // Setup select2 with wrappers and templates on modal shown to prevent rendering sizing bugs
            modalPenilaianAwalKeperawatanRanap.on('shown.bs.modal', function () {
                // Initialize Select2 dynamically on search triggers
                selectPetugas($('#ranap_nip1'), modalPenilaianAwalKeperawatanRanap);
                selectPetugas($('#ranap_nip2'), modalPenilaianAwalKeperawatanRanap);
                selectDokter($('#ranap_kd_dokter'), modalPenilaianAwalKeperawatanRanap);
            });

            // Set up show/hide handlers for contextual questions
            $('#ranap_informasi').on('change', function() {
                const isAllo = $(this).val() === 'Alloanamnesis';
                modalPenilaianAwalKeperawatanRanap.find('input[name="ket_informasi"]').closest('.col-md-6').toggle(isAllo);
            });

            $('#ranap_riwayat_kehamilan').on('change', function() {
                const isHamil = $(this).val() === 'Ya';
                $('#ranap_riwayat_kehamilan_perkiraan').closest('.col-md-6').toggle(isHamil);
            });

            $('#ranap_riwayat_merokok').on('change', function() {
                const isRokok = $(this).val() === 'Ya';
                $('#ranap_riwayat_merokok_jumlah').closest('.col-md-3').toggle(isRokok);
            });

            $('#ranap_riwayat_alkohol').on('change', function() {
                const isAlkohol = $(this).val() === 'Ya';
                $('#ranap_riwayat_alkohol_jumlah').closest('.col-md-3').toggle(isAlkohol);
            });

            $('#ranap_penilaian_nyeri').on('change', function() {
                const isNyeri = $(this).val() !== 'Tidak Ada Nyeri';
                $('#ranap_nyeri_details_container').toggle(isNyeri);
            });

            // Live calculator for Nyeri Scale
            $('#ranap_penilaian_nyeri_skala').on('input', function() {
                $('#lbl_skala_nyeri').text($(this).val());
            });

            // Morse scale dynamic calculations
            $('.calculate-morse').on('change', function() {
                calculateMorseScore();
            });

            // Sydney scale dynamic calculations
            $('.calculate-sydney').on('change', function() {
                calculateSydneyScore();
            });

            // Nutrition MST calculations
            $('.calculate-gizi').on('change', function() {
                calculateGiziScore();
            });

            // BMI calculations live on weight/height changes
            $('#ranap_pemeriksaan_bb, #ranap_pemeriksaan_tb').on('input', function() {
                calculateLiveBmi();
            });

            // Toggle Morse & Sydney views
            $('input[name="kategoriResikoJatuh"]').on('change', function() {
                const value = $(this).val();
                if (value === 'morse') {
                    $('#secMorse').removeClass('d-none');
                    $('#secSydney').addClass('d-none');
                } else {
                    $('#secMorse').addClass('d-none');
                    $('#secSydney').removeClass('d-none');
                }
            });
        });

        // Dynamic BMI calculator
        function calculateLiveBmi() {
            const bb = parseFloat($('#ranap_pemeriksaan_bb').val());
            const tb = parseFloat($('#ranap_pemeriksaan_tb').val());
            if (!isNaN(bb) && !isNaN(tb) && tb > 0) {
                const heightM = tb / 100;
                const bmi = (bb / (heightM * heightM)).toFixed(2);
                $('#ranap_pemeriksaan_bmi').val(bmi);
                
                // Display beautiful status badges
                const badge = $('#ranap_badge_bmi').removeClass('d-none bg-success bg-warning bg-danger bg-info');
                if (bmi < 18.5) {
                    badge.addClass('bg-info').text('Kurus (Underweight)');
                } else if (bmi >= 18.5 && bmi < 25) {
                    badge.addClass('bg-success').text('Normal (Ideal)');
                } else if (bmi >= 25 && bmi < 30) {
                    badge.addClass('bg-warning').text('Kelebihan BB (Overweight)');
                } else {
                    badge.addClass('bg-danger').text('Obesitas');
                }
            } else {
                $('#ranap_pemeriksaan_bmi').val('-');
                $('#ranap_badge_bmi').addClass('d-none');
            }
        }

        // Morse risk score accumulator
        function calculateMorseScore() {
            let total = 0;
            const items = ['#m1', '#m2', '#m3', '#m4', '#m5', '#m6'];
            items.forEach(function(selector) {
                const select = $(selector);
                const score = parseInt(select.find('option:selected').data('score')) || 0;
                $(selector + '_val').val(score);
                total += score;
            });
            $('#morse_total').val(total);

            const badge = $('#badge_morse_result').removeClass('bg-success bg-warning bg-danger');
            if (total <= 24) {
                badge.addClass('bg-success').text('Resiko Rendah');
            } else if (total >= 25 && total <= 44) {
                badge.addClass('bg-warning').text('Resiko Sedang');
            } else {
                badge.addClass('bg-danger').text('Resiko Tinggi');
            }
        }

        // Sydney risk score accumulator
        function calculateSydneyScore() {
            let total = 0;
            const items = ['#s1', '#s2', '#s3', '#s4', '#s5', '#s6', '#s7', '#s8', '#s9', '#s10', '#s11'];
            items.forEach(function(selector) {
                const select = $(selector);
                const score = parseInt(select.find('option:selected').data('score')) || 0;
                $(selector + '_val').val(score);
                total += score;
            });
            $('#sydney_total').val(total);

            const badge = $('#badge_sydney_result').removeClass('bg-success bg-warning bg-danger');
            if (total <= 7) {
                badge.addClass('bg-success').text('Resiko Rendah (Skor: ' + total + ')');
            } else if (total >= 8 && total <= 11) {
                badge.addClass('bg-warning').text('Resiko Sedang (Skor: ' + total + ')');
            } else {
                badge.addClass('bg-danger').text('Resiko Tinggi (Skor: ' + total + ')');
            }
        }

        // MST Nutrition score accumulator
        function calculateGiziScore() {
            const score1 = parseInt($('#ranap_sg1 option:selected').data('score')) || 0;
            const score2 = parseInt($('#ranap_sg2 option:selected').data('score')) || 0;
            $('#ranap_n1').val(score1);
            $('#ranap_n2').val(score2);
            
            const total = score1 + score2;
            $('#gizi_total').val(total);

            const badge = $('#badge_gizi_result').removeClass('bg-success bg-danger bg-warning');
            if (total >= 2) {
                badge.addClass('bg-danger').text('Beresiko Malnutrisi (Rujuk Dietisen!)');
            } else {
                badge.addClass('bg-success').text('Resiko Rendah / Tidak Beresiko');
            }
        }

        // SALIN TTV DARI CPPT TERAKHIR
        $('#btnCopyFromCppt').on('click', function() {
            const no_rawat = $('#ranap_no_rawat').val();
            if (!no_rawat) return;
            
            loadingAjax('Mengambil data CPPT terakhir...');
            getCpptRanap(no_rawat).done((response) => {
                Swal.close();
                if (response.length) {
                    // Get latest CPPT exam
                    const cppt = response[0];
                    $('#ranap_pemeriksaan_td').val(cppt.suhu || '-'); // Some CPPT might map differently
                    $('#ranap_pemeriksaan_td').val(cppt.tensi || '-');
                    $('#ranap_pemeriksaan_nadi').val(cppt.nadi || '-');
                    $('#ranap_pemeriksaan_rr').val(cppt.respirasi || '-');
                    $('#ranap_pemeriksaan_suhu').val(cppt.suhu_tubuh || '-');
                    $('#ranap_pemeriksaan_spo2').val(cppt.spo2 || '-');
                    $('#ranap_pemeriksaan_bb').val(cppt.berat || '-');
                    $('#ranap_pemeriksaan_tb').val(cppt.tinggi || '-');
                    calculateLiveBmi();
                    alertSuccessAjax('Tanda vital berhasil disalin dari CPPT!');
                } else {
                    Swal.fire('Info', 'Belum ada data pemeriksaan CPPT untuk menyalin tanda vital.', 'info');
                }
            }).fail((xhr) => {
                Swal.close();
                alertErrorAjax(xhr);
            });
        });

        // MASTER LOADER FOR PENILAIAN AWAL KEPERAWATAN RANAP
        function penilaianAwalKeperawatanRanap(no_rawat) {
            // First load patient general info
            getRegDetail(no_rawat).done((response) => {
                $('#ranap_no_rawat').val(response.no_rawat);
                $('#ranap_tanggal').val("{{ date('Y-m-d H:i:s') }}");
                
                // Set patient labels
                $('#lbl_nm_pasien').text(response.pasien.nm_pasien + ' (' + response.pasien.jk + ')');
                $('#lbl_no_rawat').text(response.no_rawat);
                $('#lbl_no_rm').text(response.no_rkm_medis);
                $('#lbl_tgl_lahir').text(splitTanggal(response.pasien.tgl_lahir) + ' / ' + response.umurdaftar + ' ' + response.sttsumur);
                $('#lbl_dpjp').text(response.dokter.nm_dokter);

                // Auto-fill trigger rules in fields
                modalPenilaianAwalKeperawatanRanap.find('input[name="riwayat_alergi"]').val(response.pasien.riwayat_alergi || '-');
                modalPenilaianAwalKeperawatanRanap.find('input[name="alat_bantu_dipakai"]').val(response.pasien.alat_bantu || '-');
                
                // Open modal
                modalPenilaianAwalKeperawatanRanap.modal('show');
                
                // Clear any leftover forms
                $('#formPenilaianAwalKeperawatanRanap').trigger('reset');
                $('#alertPenilaianRanap').addClass('d-none');
                $('#btnCetakRanap').addClass('d-none');

                // Prepopulate DPJP doctor and current nurse as defaults
                let defaultDokter = new Option(response.dokter.nm_dokter, response.kd_dokter, true, true);
                $('#ranap_kd_dokter').append(defaultDokter).trigger('change');

                @if(session()->get('pegawai'))
                    let defaultNurse = new Option("{{ session()->get('pegawai')->nama }}", "{{ session()->get('pegawai')->nik }}", true, true);
                    $('#ranap_nip1').append(defaultNurse).trigger('change');
                @endif

                // Get existing assessment from database
                getPenilaianAwalKeperawatanRanap(no_rawat).done((result) => {
                    if (result && Object.keys(result).length > 0) {
                        console.log('GET RANAP ASSESSMENT == ', result);
                        $('#alertPenilaianRanap').removeClass('d-none');
                        $('#tgl_penilaian_ranap').text(result.tanggal);
                        $('#user_penilaian_ranap').text(result.pegawai1 ? result.pegawai1.nama : '-');
                        $('#btnCetakRanap').removeClass('d-none');

                        // Loop through result and set form fields automatically
                        Object.keys(result).forEach(function(key) {
                            const field = $('#formPenilaianAwalKeperawatanRanap').find('[name="' + key + '"]');
                            if (field.length) {
                                if (field.is('select') && field.hasClass('select2-ranap')) {
                                    // Handle Select2 mapping
                                    if (key === 'nip1' && result.pegawai1) {
                                        let opt = new Option(result.pegawai1.nama, result.nip1, true, true);
                                        $('#ranap_nip1').append(opt).trigger('change');
                                    } else if (key === 'nip2' && result.pegawai2) {
                                        let opt = new Option(result.pegawai2.nama, result.nip2, true, true);
                                        $('#ranap_nip2').append(opt).trigger('change');
                                    } else if (key === 'kd_dokter' && result.dokter) {
                                        let opt = new Option(result.dokter.nm_dokter, result.kd_dokter, true, true);
                                        $('#ranap_kd_dokter').append(opt).trigger('change');
                                    }
                                } else {
                                    field.val(result[key]).change();
                                }
                            }
                        });

                        // Recalculate states
                        calculateLiveBmi();
                        calculateMorseScore();
                        calculateSydneyScore();
                        calculateGiziScore();
                    } else {
                        // If no assessment, try to grab most recent vitals from CPPT as defaults
                        getCpptRanap(no_rawat).done((cpptRes) => {
                            if (cpptRes && cpptRes.length > 0) {
                                const latest = cpptRes[0];
                                $('#ranap_pemeriksaan_td').val(latest.tensi || '-');
                                $('#ranap_pemeriksaan_nadi').val(latest.nadi || '-');
                                $('#ranap_pemeriksaan_rr').val(latest.respirasi || '-');
                                $('#ranap_pemeriksaan_suhu').val(latest.suhu_tubuh || '-');
                                $('#ranap_pemeriksaan_spo2').val(latest.spo2 || '-');
                                $('#ranap_pemeriksaan_bb').val(latest.berat || '-');
                                $('#ranap_pemeriksaan_tb').val(latest.tinggi || '-');
                                calculateLiveBmi();
                            }
                        });
                    }
                });
            }).fail((xhr) => {
                alertErrorAjax(xhr);
            });
        }

        // GET ASSESSMENT AJAX
        function getPenilaianAwalKeperawatanRanap(no_rawat) {
            return $.get(`{{ url('/penilaian/awal/keperawatan/ranap') }}`, { no_rawat: no_rawat });
        }

        // SAVE ASSESSMENT AJAX
        function simpanPenilaianAwalKeperawatanRanap() {
            // Client-side Validation for Mandatory FK Fields (NIP Pengkaji 1 & Dokter DPJP)
            const nip1 = $('#ranap_nip1').val();
            const kd_dokter = $('#ranap_kd_dokter').val();

            if (!nip1 || nip1 === '') {
                Swal.fire({
                    title: 'Validasi Gagal',
                    text: 'NIP Pengkaji 1 (Perawat Utama) wajib diisi sebelum menyimpan! Silakan lengkapi di tab "Gizi & Rencana".',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Lengkapi Sekarang'
                }).then(() => {
                    // Automatically switch to the "Gizi & Rencana" tab
                    const triggerEl = document.querySelector('#tabsPenilaianRanap a[href="#tab-gizi"]');
                    const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
                    tabInstance.show();
                    // Programmatically open the Select2 dropdown for Nurse 1
                    setTimeout(() => {
                        $('#ranap_nip1').select2('open');
                    }, 300);
                });
                return;
            }

            if (!kd_dokter || kd_dokter === '') {
                Swal.fire({
                    title: 'Validasi Gagal',
                    text: 'Dokter DPJP Pasien wajib diisi sebelum menyimpan! Silakan lengkapi di tab "Gizi & Rencana".',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Lengkapi Sekarang'
                }).then(() => {
                    // Automatically switch to the "Gizi & Rencana" tab
                    const triggerEl = document.querySelector('#tabsPenilaianRanap a[href="#tab-gizi"]');
                    const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
                    tabInstance.show();
                    // Programmatically open the Select2 dropdown for DPJP
                    setTimeout(() => {
                        $('#ranap_kd_dokter').select2('open');
                    }, 300);
                });
                return;
            }

            // Construct payload from all inputs, selects, and textareas
            const data = getDataForm('formPenilaianAwalKeperawatanRanap', ['input', 'select', 'textarea']);
            
            // Append CSRF token
            data['_token'] = $('meta[name="csrf-token"]').attr('content');

            // Fallback empty/null nip2 to '-' to prevent any database integrity issues
            if (!data['nip2'] || data['nip2'] === '') {
                data['nip2'] = '-';
            }

            loadingAjax('Sedang menyimpan penilaian awal keperawatan...');
            $.post(`{{ url('/penilaian/awal/keperawatan/ranap') }}`, data).done((response) => {
                Swal.close();
                if (response === 'SUKSES') {
                    alertSuccessAjax('Penilaian awal keperawatan rawat inap berhasil disimpan!');
                    $('#alertPenilaianRanap').removeClass('d-none');
                    $('#tgl_penilaian_ranap').text(new Date().toLocaleString('id-ID'));
                    $('#btnCetakRanap').removeClass('d-none');
                } else {
                    Swal.fire('Gagal', 'Terjadi kendala saat menyimpan data.', 'error');
                }
            }).fail((xhr) => {
                Swal.close();
                alertErrorAjax(xhr);
            });
        }

        // PRINT ASSESSMENT
        function printPenilaianAwalKeperawatanRanap() {
            const no_rawat = $('#ranap_no_rawat').val();
            if (!no_rawat) return;
            
            modalCetakPenilaianRanap.modal('show');
            $('#printFrameRanap').attr('src', `{{ url('/penilaian/awal/keperawatan/ranap/print') }}?no_rawat=${no_rawat}`);
        }

        // Clean form states when closing modal
        modalPenilaianAwalKeperawatanRanap.on('hidden.bs.modal', function () {
            $('#formPenilaianAwalKeperawatanRanap').trigger('reset');
            $('#ranap_nip1').val(null).trigger('change');
            $('#ranap_nip2').val(null).trigger('change');
            $('#ranap_kd_dokter').val(null).trigger('change');
        });
    </script>
@endpush

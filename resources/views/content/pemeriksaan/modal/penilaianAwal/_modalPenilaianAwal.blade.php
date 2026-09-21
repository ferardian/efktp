<div class="modal modal-blur fade" id="modalPenilaianAwalKeperawatan" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3 shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title m-0 text-white font-weight-bold">
                    <i class="ti ti-report-medical me-2"></i>Penilaian Awal Keperawatan Rawat Jalan Umum
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Patient Overview Header -->
            <div class="px-4 py-3 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-md bg-blue-lt rounded-circle"><i class="ti ti-user font-size-1-5"></i></span>
                    <div>
                        <div class="font-weight-bold text-dark" id="ralan_lbl_nm_pasien">-</div>
                        <div class="small text-muted" id="ralan_lbl_no_rawat">-</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <div>
                        <div class="small text-muted">No. Rekam Medis</div>
                        <div class="font-weight-bold text-dark" id="ralan_lbl_no_rm">-</div>
                    </div>
                    <div>
                        <div class="small text-muted">Tanggal Lahir / Umur</div>
                        <div class="font-weight-bold text-dark" id="ralan_lbl_tgl_lahir">-</div>
                    </div>
                    <div>
                        <div class="small text-muted">Agama / Bahasa</div>
                        <div class="font-weight-bold text-dark" id="ralan_lbl_agama_bahasa">-</div>
                    </div>
                </div>
            </div>

            <div class="modal-body p-0">
                <!-- Status Alert Bar if already saved -->
                <div class="alert alert-success d-none m-3 border-0" role="alert" id="alertPenilaian">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-circle-check font-size-1-5 me-2"></i>
                        <div>
                            <h4 class="alert-title m-0">Asesmen ini sudah pernah disimpan!</h4>
                            <div class="text-secondary small">Terakhir diperbarui pada: <b id="tgl_penilaian">-</b> oleh <b id="user_penilaian">-</b></div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="card border-0">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs card-header-tabs m-0 border-bottom-0 bg-light" id="tabsPenilaianRalan" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ralan-umum" class="nav-link active py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-activity me-1"></i> I. Keadaan Umum & TTV
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ralan-nutrisi" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-scale me-1"></i> II & III. Nutrisi & Fungsional
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ralan-psiko" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-users me-1"></i> IV. Psikosial & Budaya
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ralan-jatuh" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-alert-triangle me-1"></i> V. Resiko Jatuh
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ralan-gizi-nyeri" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-heart-rate-monitor me-1"></i> VI & VII. Gizi & Nyeri
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-ralan-asuhan" class="nav-link py-3 px-3 text-dark font-weight-bold" data-bs-toggle="tab" role="tab">
                                    <i class="ti ti-clipboard-list me-1"></i> VIII. Masalah & Rencana
                                </a>
                            </li>
                        </ul>
                    </div>

                    <form action="" id="formPenilaianAwalKeperawatan" class="tab-content card-body p-4">
                        <input type="hidden" id="no_rawat" name="no_rawat">
                        <input type="hidden" id="tanggal" name="tanggal">
                        <input type="hidden" id="nip" name="nip" value="{{ session()->get('pegawai')->nik ?? '' }}">

                        <!-- ==================== TAB 1: KEADAAN UMUM & RIWAYAT ==================== -->
                        <div class="tab-pane fade show active" id="tab-ralan-umum" role="tabpanel">
                            <div class="row g-3">
                                <!-- Data Registrasi & Petugas -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <div class="row g-2">
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <label for="informasi" class="form-label font-weight-bold">Metode Anamnesis</label>
                                                <select class="form-select" name="informasi" id="informasi">
                                                    <option value="Autoanamnesis" selected>Autoanamnesis (Sendiri)</option>
                                                    <option value="Alloanamnesis">Alloanamnesis (Keluarga / Pengantar)</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <label for="petugas" class="form-label font-weight-bold">Petugas / Perawat</label>
                                                <input type="text" class="form-control bg-white" id="petugas" name="petugas" readonly value="{{ session()->get('pegawai')->nama ?? '' }}">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <label for="pekerjaan" class="form-label font-weight-bold">Pekerjaan Pasien</label>
                                                <input type="text" class="form-control bg-white" id="pekerjaan" name="pekerjaan" readonly value="-">
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <label for="pendidikan" class="form-label font-weight-bold">Pendidikan Pasien</label>
                                                <input type="text" class="form-control bg-white" id="pendidikan" name="pendidikan" readonly value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanda-tanda Vital -->
                                <div class="col-12">
                                    <h4 class="font-weight-bold text-primary mb-2">
                                        <i class="ti ti-heartbeat"></i> 1. Tanda-Tanda Vital & Kesadaran
                                        <button type="button" class="btn btn-outline-primary btn-sm ms-2" id="btnSalinTtv" onclick="salinTtvPemeriksaan()" title="Salin dari TTV Pemeriksaan Rawat Jalan">
                                            <i class="ti ti-copy me-1"></i> Salin TTV Terakhir
                                        </button>
                                    </h4>
                                    <div class="row g-2">
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for="td" class="form-label">Tensi (TD)</label>
                                            <div class="input-group input-group-flat">
                                                <input type="text" class="form-control" id="td" name="td" value="-">
                                                <span class="input-group-text">mmHg</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for="nadi" class="form-label">Nadi</label>
                                            <div class="input-group input-group-flat">
                                                <input type="text" class="form-control" id="nadi" name="nadi" value="-">
                                                <span class="input-group-text">x/mnt</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for="rr" class="form-label">Respirasi (RR)</label>
                                            <div class="input-group input-group-flat">
                                                <input type="text" class="form-control" id="rr" name="rr" value="-">
                                                <span class="input-group-text">x/mnt</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6">
                                            <label for="suhu" class="form-label">Suhu</label>
                                            <div class="input-group input-group-flat">
                                                <input type="text" class="form-control" id="suhu" name="suhu" value="-">
                                                <span class="input-group-text">°C</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-8 col-sm-12">
                                            <label for="gcs" class="form-label">GCS (E, V, M)</label>
                                            <input type="text" class="form-control" id="gcs" name="gcs" placeholder="Misal: E4V5M6 / 15" value="-">
                                        </div>
                                    </div>
                                </div>

                                <!-- Riwayat Kesehatan -->
                                <div class="col-12">
                                    <h4 class="font-weight-bold text-primary mb-2"><i class="ti ti-notes"></i> 2. Riwayat Kesehatan</h4>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label for="keluhan_utama" class="form-label font-weight-bold">Keluhan Utama</label>
                                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3">-</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="rpd" class="form-label font-weight-bold">Riwayat Penyakit Dahulu (RPD)</label>
                                            <textarea class="form-control" id="rpd" name="rpd" rows="3">-</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rpk" class="form-label font-weight-bold">Riwayat Penyakit Keluarga (RPK)</label>
                                            <textarea class="form-control" id="rpk" name="rpk" rows="2">-</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rpo" class="form-label font-weight-bold">Riwayat Penggunaan Obat (RPO)</label>
                                            <textarea class="form-control" id="rpo" name="rpo" rows="2">-</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="alergi" class="form-label font-weight-bold">Riwayat Alergi</label>
                                            <textarea class="form-control" id="alergi" name="alergi" rows="2">-</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 2: NUTRISI & FUNGSIONAL ==================== -->
                        <div class="tab-pane fade" id="tab-ralan-nutrisi" role="tabpanel">
                            <div class="row g-4">
                                <!-- II. Status Nutrisi -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-scale"></i> II. Status Nutrisi</h4>
                                        <div class="row g-3 align-items-center">
                                            <div class="col-lg-3 col-md-6">
                                                <label for="bb" class="form-label font-weight-bold">Berat Badan (BB)</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="bb" name="bb" value="-" onkeyup="hitungBmiRalan()">
                                                    <span class="input-group-text">Kg</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="tb" class="form-label font-weight-bold">Tinggi Badan (TB)</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control" id="tb" name="tb" value="-" onkeyup="hitungBmiRalan()">
                                                    <span class="input-group-text">cm</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label for="bmi" class="form-label font-weight-bold">Body Mass Index (BMI)</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" class="form-control bg-white font-weight-bold" id="bmi" name="bmi" readonly value="-">
                                                    <span class="input-group-text">Kg/m²</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <label class="form-label font-weight-bold">Kategori BMI</label>
                                                <div id="badge_bmi" class="badge bg-secondary p-2 d-block text-white font-weight-bold">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- IV. Fungsional -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-wheelchair"></i> IV. Status Fungsional</h4>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label font-weight-bold">Alat Bantu</label>
                                                <div class="input-group">
                                                    <select class="form-select" id="alat_bantu" name="alat_bantu" style="max-width: 140px;">
                                                        <option value="Tidak" selected>Tidak</option>
                                                        <option value="Ya">Ya</option>
                                                    </select>
                                                    <input type="text" class="form-control" id="ket_bantu" name="ket_bantu" placeholder="Keterangan alat bantu..." value="-">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label font-weight-bold">Prothesa (Alat Tiruan)</label>
                                                <div class="input-group">
                                                    <select class="form-select" id="prothesa" name="prothesa" style="max-width: 140px;">
                                                        <option value="Tidak" selected>Tidak</option>
                                                        <option value="Ya">Ya</option>
                                                    </select>
                                                    <input type="text" class="form-control" id="ket_pro" name="ket_pro" placeholder="Keterangan prothesa..." value="-">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cacat_fisik" class="form-label font-weight-bold">Cacat Fisik</label>
                                                <input type="text" class="form-control bg-white" id="cacat_fisik" name="cacat_fisik" readonly value="-">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="adl" class="form-label font-weight-bold">Aktivitas Kehidupan Sehari-hari (ADL)</label>
                                                <select class="form-select" id="adl" name="adl">
                                                    <option value="Mandiri" selected>Mandiri</option>
                                                    <option value="Dibantu">Dibantu</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 3: PSIKOSOSIAL, SPIRITUAL & BUDAYA ==================== -->
                        <div class="tab-pane fade" id="tab-ralan-psiko" role="tabpanel">
                            <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-users"></i> V. Riwayat Psiko-Sosial, Spiritual & Budaya</h4>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="status_psiko" class="form-label font-weight-bold">Status Psikologis</label>
                                        <select class="form-select" name="status_psiko" id="status_psiko">
                                            <option value="Tenang" selected>Tenang</option>
                                            <option value="Takut">Takut</option>
                                            <option value="Cemas">Cemas</option>
                                            <option value="Depresi">Depresi</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="ket_psiko" class="form-label">Keterangan Status Psikologis</label>
                                        <input type="text" class="form-control" id="ket_psiko" name="ket_psiko" value="-">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="hub_keluarga" class="form-label font-weight-bold">Hubungan Dengan Anggota Keluarga</label>
                                        <select class="form-select" name="hub_keluarga" id="hub_keluarga">
                                            <option value="Baik" selected>Baik</option>
                                            <option value="Tidak Baik">Tidak Baik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tinggal_dengan" class="form-label font-weight-bold">Tinggal Dengan</label>
                                        <select class="form-select" name="tinggal_dengan" id="tinggal_dengan">
                                            <option value="Sendiri" selected>Sendiri</option>
                                            <option value="Orang Tua">Orang Tua</option>
                                            <option value="Suami / Istri">Suami / Istri</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ket_tinggal" class="form-label">Keterangan Tinggal</label>
                                        <input type="text" class="form-control" id="ket_tinggal" name="ket_tinggal" value="-">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="ekonomi" class="form-label font-weight-bold">Status Sosial & Ekonomi</label>
                                        <select class="form-select" name="ekonomi" id="ekonomi">
                                            <option value="Baik" selected>Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Kurang">Kurang</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="budaya" class="form-label font-weight-bold">Kepercayaan / Budaya / Nilai Khusus</label>
                                        <select class="form-select" name="budaya" id="budaya">
                                            <option value="Tidak Ada" selected>Tidak Ada</option>
                                            <option value="Ada">Ada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ket_budaya" class="form-label">Keterangan Budaya / Nilai Khusus</label>
                                        <input type="text" class="form-control" id="ket_budaya" name="ket_budaya" value="-">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="edukasi" class="form-label font-weight-bold">Edukasi Diberikan Kepada</label>
                                        <select class="form-select" name="edukasi" id="edukasi">
                                            <option value="Pasien" selected>Pasien</option>
                                            <option value="Keluarga">Keluarga</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label for="ket_edukasi" class="form-label">Keterangan Edukasi</label>
                                        <input type="text" class="form-control" id="ket_edukasi" name="ket_edukasi" value="-">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 4: PENILAIAN RESIKO JATUH ==================== -->
                        <div class="tab-pane fade" id="tab-ralan-jatuh" role="tabpanel">
                            <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-alert-triangle"></i> VI. Penilaian Resiko Jatuh (Get Up & Go)</h4>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="card p-3 border">
                                            <h5 class="font-weight-bold text-dark mb-3">a. Cara Berjalan</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">1. Tidak seimbang / sempoyongan / limbung :</label>
                                                    <select class="form-select" name="berjalan_a" id="berjalan_a" onchange="hitungResikoJatuhRalan()">
                                                        <option value="Tidak" selected>Tidak</option>
                                                        <option value="Ya">Ya</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">2. Jalan dengan menggunakan alat bantu (kruk, tripot, kursi roda, orang lain) :</label>
                                                    <select class="form-select" name="berjalan_b" id="berjalan_b" onchange="hitungResikoJatuhRalan()">
                                                        <option value="Tidak" selected>Tidak</option>
                                                        <option value="Ya">Ya</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <h5 class="font-weight-bold text-dark mt-3 mb-2">b. Menopang</h5>
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label class="form-label">Menopang saat akan duduk, tampak memegang pinggiran kursi atau meja / benda lain sebagai penopang :</label>
                                                    <select class="form-select" name="berjalan_c" id="berjalan_c" onchange="hitungResikoJatuhRalan()">
                                                        <option value="Tidak" selected>Tidak</option>
                                                        <option value="Ya">Ya</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="hasil" class="form-label font-weight-bold text-primary">Hasil Penilaian Resiko Jatuh</label>
                                        <select class="form-select font-weight-bold" name="hasil" id="hasil">
                                            <option value="Tidak beresiko (tidak ditemukan a dan b)" selected>Tidak beresiko (tidak ditemukan a dan b)</option>
                                            <option value="Resiko rendah (ditemukan a/b)">Resiko rendah (ditemukan a/b)</option>
                                            <option value="Resiko tinggi (ditemukan a dan b)">Resiko tinggi (ditemukan a dan b)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="lapor" class="form-label font-weight-bold">Dilaporkan Kepada Dokter?</label>
                                        <select class="form-select" name="lapor" id="lapor">
                                            <option value="Tidak" selected>Tidak</option>
                                            <option value="Ya">Ya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="ket_lapor" class="form-label font-weight-bold">Jam Dilaporkan</label>
                                        <input type="text" class="form-control" name="ket_lapor" id="ket_lapor" placeholder="Misal: 10:30 WIB" value="-">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 5: SKRINING GIZI & NYERI ==================== -->
                        <div class="tab-pane fade" id="tab-ralan-gizi-nyeri" role="tabpanel">
                            <div class="row g-4">
                                <!-- VII. Skrining Gizi -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-salad"></i> VII. Skrining Gizi (Malnutrition Screening Tool)</h4>
                                        <div class="row g-3 align-items-center">
                                            <div class="col-md-8">
                                                <label class="form-label font-weight-bold">1. Apakah ada penurunan berat badan dalam kurun waktu 6 bulan terakhir?</label>
                                                <select name="sg1" id="sg1" class="form-select">
                                                    <option data-nilai="0" value="Tidak" selected>Tidak (Skor: 0)</option>
                                                    <option data-nilai="2" value="Tidak Yakin">Tidak Yakin (Skor: 2)</option>
                                                    <option data-nilai="1" value="Ya, 1-5 Kg">Ya, 1-5 Kg (Skor: 1)</option>
                                                    <option data-nilai="2" value="Ya, 6-10 Kg">Ya, 6-10 Kg (Skor: 2)</option>
                                                    <option data-nilai="3" value="Ya, 11-15 Kg">Ya, 11-15 Kg (Skor: 3)</option>
                                                    <option data-nilai="4" value="Ya, >15 Kg">Ya, >15 Kg (Skor: 4)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nilai Skor 1</label>
                                                <input type="text" class="form-control bg-white font-weight-bold" id="nilai1" name="nilai1" readonly value="0">
                                            </div>

                                            <div class="col-md-8">
                                                <label class="form-label font-weight-bold">2. Apakah ada penurunan nafsu makan?</label>
                                                <select name="sg2" id="sg2" class="form-select">
                                                    <option data-nilai="0" value="Tidak" selected>Tidak (Skor: 0)</option>
                                                    <option data-nilai="1" value="Ya">Ya (Skor: 1)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nilai Skor 2</label>
                                                <input type="text" class="form-control bg-white font-weight-bold" id="nilai2" name="nilai2" readonly value="0">
                                            </div>

                                            <div class="col-12 mt-2">
                                                <div class="d-flex align-items-center justify-content-between p-3 bg-white border rounded">
                                                    <span class="font-weight-bold text-dark">Total Skor Skrining Gizi (Bila skor &ge; 2, lakukan konsultasi gizi):</span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="text" class="form-control text-center font-weight-bold fs-3 text-primary" style="width: 80px;" id="total_hasil" name="total_hasil" readonly value="0">
                                                        <span id="badge_gizi" class="badge bg-success">Resiko Rendah</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- VIII. Penilaian Tingkat Nyeri -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <h4 class="font-weight-bold text-primary mb-3"><i class="ti ti-activity"></i> VIII. Penilaian Tingkat Nyeri (PQRST)</h4>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="nyeri" class="form-label font-weight-bold text-danger">Status Nyeri</label>
                                                <select class="form-select font-weight-bold" name="nyeri" id="nyeri" onchange="togglePanelNyeri()">
                                                    <option value="Tidak Ada Nyeri" selected>Tidak Ada Nyeri</option>
                                                    <option value="Nyeri Akut">Nyeri Akut</option>
                                                    <option value="Nyeri Kronis">Nyeri Kronis</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="skala_nyeri" class="form-label font-weight-bold">Skala Nyeri (0 - 10)</label>
                                                <select class="form-select font-weight-bold" name="skala_nyeri" id="skala_nyeri">
                                                    <option value="0" selected>0 - Tidak Nyeri</option>
                                                    <option value="1">1 - Sangat Ringan</option>
                                                    <option value="2">2 - Ringan</option>
                                                    <option value="3">3 - Cukup Nyaman</option>
                                                    <option value="4">4 - Nyeri Sedang</option>
                                                    <option value="5">5 - Nyeri Cukup Kuat</option>
                                                    <option value="6">6 - Nyeri Kuat</option>
                                                    <option value="7">7 - Nyeri Hebat</option>
                                                    <option value="8">8 - Nyeri Sangat Hebat</option>
                                                    <option value="9">9 - Nyeri Luar Biasa</option>
                                                    <option value="10">10 - Nyeri Tak Tertahankan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="lokasi" class="form-label font-weight-bold">Lokasi Nyeri</label>
                                                <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Misal: Kepala / Ulu Hati" value="-">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="provokes" class="form-label">Penyebab Nyeri (Provokes)</label>
                                                <select class="form-select" name="provokes" id="provokes">
                                                    <option value="Proses Penyakit">Proses Penyakit</option>
                                                    <option value="Benturan">Benturan</option>
                                                    <option value="Lain-lain" selected>Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="ket_provokes" class="form-label">Keterangan Penyebab Nyeri</label>
                                                <input type="text" class="form-control" id="ket_provokes" name="ket_provokes" value="-">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="quality" class="form-label">Kualitas Nyeri (Quality)</label>
                                                <select class="form-select" name="quality" id="quality">
                                                    <option value="Seperti Tertusuk">Seperti Tertusuk</option>
                                                    <option value="Berdenyut">Berdenyut</option>
                                                    <option value="Teriris">Teriris</option>
                                                    <option value="Tertindih">Tertindih</option>
                                                    <option value="Tertiban">Tertiban</option>
                                                    <option value="Lain-lain" selected>Lain-lain</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="ket_quality" class="form-label">Keterangan Kualitas Nyeri</label>
                                                <input type="text" class="form-control" id="ket_quality" name="ket_quality" value="-">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="menyebar" class="form-label">Nyeri Menyebar?</label>
                                                <select class="form-select" name="menyebar" id="menyebar">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="durasi" class="form-label">Durasi Nyeri</label>
                                                <input type="text" class="form-control" id="durasi" name="durasi" placeholder="Misal: 15 menit" value="-">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="nyeri_hilang" class="form-label">Nyeri Hilang Dengan</label>
                                                <select class="form-select" name="nyeri_hilang" id="nyeri_hilang">
                                                    <option value="Istirahat" selected>Istirahat</option>
                                                    <option value="Medengar Musik">Medengar Musik</option>
                                                    <option value="Minum Obat">Minum Obat</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="pada_dokter" class="form-label">Diberitahukan Pada Dokter?</label>
                                                <select class="form-select" name="pada_dokter" id="pada_dokter">
                                                    <option value="Tidak" selected>Tidak</option>
                                                    <option value="Ya">Ya</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="ket_dokter" class="form-label">Jam Diberitahukan</label>
                                                <input type="text" class="form-control" id="ket_dokter" name="ket_dokter" placeholder="Misal: 09:15 WIB" value="-">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB 6: MASALAH & RENCANA KEPERAWATAN ==================== -->
                        <div class="tab-pane fade" id="tab-ralan-asuhan" role="tabpanel">
                            <div class="row g-4">
                                <!-- Masalah Keperawatan -->
                                <div class="col-lg-6 col-12">
                                    <div class="card border rounded-2 h-100">
                                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                            <h4 class="font-weight-bold text-dark m-0"><i class="ti ti-check-box text-primary me-1"></i> Masalah Keperawatan</h4>
                                            <span class="badge bg-blue-lt" id="count_masalah">0 dipilih</span>
                                        </div>
                                        <div class="p-2 border-bottom">
                                            <input type="text" class="form-control form-control-sm" id="cari_masalah" placeholder="Filter masalah keperawatan..." onkeyup="filterMasalahKeperawatan()">
                                        </div>
                                        <div class="card-body p-2" style="max-height: 280px; overflow-y: auto;" id="container_masalah_keperawatan">
                                            <div class="text-muted small p-2 text-center">Memuat data masalah keperawatan...</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rencana Keperawatan -->
                                <div class="col-lg-6 col-12">
                                    <div class="card border rounded-2 h-100">
                                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                            <h4 class="font-weight-bold text-dark m-0"><i class="ti ti-list-check text-success me-1"></i> Rencana Keperawatan</h4>
                                            <span class="badge bg-green-lt" id="count_rencana">0 dipilih</span>
                                        </div>
                                        <div class="p-2 border-bottom">
                                            <input type="text" class="form-control form-control-sm" id="cari_rencana" placeholder="Filter rencana keperawatan..." onkeyup="filterRencanaKeperawatan()">
                                        </div>
                                        <div class="card-body p-2" style="max-height: 280px; overflow-y: auto;" id="container_rencana_keperawatan">
                                            <div class="text-muted small p-2 text-center">Memuat data rencana keperawatan...</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tindakan / Catatan Rencana Tambahan -->
                                <div class="col-12">
                                    <div class="card bg-muted-lt border-0 rounded-2 p-3">
                                        <label for="rencana" class="form-label font-weight-bold text-primary">
                                            <i class="ti ti-edit me-1"></i> Tindakan / Rencana Keperawatan Tambahan (Manual Text)
                                        </label>
                                        <textarea class="form-control" name="rencana" id="rencana" rows="4" placeholder="Tuliskan tindakan dan rencana asuhan keperawatan tambahan...">-</textarea>
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
                    <button type="button" class="btn btn-outline-danger d-none" id="btnHapusPenilaian" onclick="hapusPenilaianAwalKeperawatan()">
                        <i class="ti ti-trash me-1"></i> Hapus Asesmen
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary d-none" id="btnCetak" onclick="printPenilaianAwalKeperawatan()">
                        <i class="ti ti-printer me-1"></i> Cetak PDF
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="btnSimpanPenilaian" onclick="simpanPenilaianAwalKeperawatan()">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Penilaian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak PDF -->
<div class="modal modal-blur fade" id="modalCetakPenilaian" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title m-0 text-white"><i class="ti ti-printer me-2"></i>Cetak Penilaian Awal Keperawatan Rawat Jalan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="print" type="" width="100%" height="650" class="border-0"></iframe>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        var modalPenilaianAwalKeperawatan = $('#modalPenilaianAwalKeperawatan');
        var modalCetakPenilaian = $('#modalCetakPenilaian');
        var masterMasalahData = [];
        var masterRencanaData = [];
        var isMasterLoaded = false;

        // Load master masalah & rencana keperawatan once
        function loadMasterMasalahRencana(callback) {
            if (isMasterLoaded) {
                if (callback) callback();
                return;
            }
            $.get(`{{ url('/penilaian/awal/keperawatan/ralan/master') }}`).done((res) => {
                masterMasalahData = res.masalah || [];
                masterRencanaData = res.rencana || [];
                isMasterLoaded = true;
                renderMasterCheckboxes([], []);
                if (callback) callback();
            }).fail(() => {
                isMasterLoaded = true;
                renderMasterCheckboxes([], []);
                if (callback) callback();
            });
        }

        function renderMasterCheckboxes(selectedMasalah, selectedRencana) {
            const containerMasalah = $('#container_masalah_keperawatan');
            const containerRencana = $('#container_rencana_keperawatan');

            // Render Masalah
            if (masterMasalahData.length === 0) {
                containerMasalah.html('<div class="text-muted small p-2">Tidak ada master masalah keperawatan.</div>');
            } else {
                let htmlM = '<div class="list-group list-group-flush">';
                masterMasalahData.forEach((m) => {
                    const isChecked = selectedMasalah.includes(m.kode_masalah) ? 'checked' : '';
                    htmlM += `
                        <label class="list-group-item py-2 px-2 d-flex align-items-center gap-2 item-masalah" data-text="${m.nama_masalah.toLowerCase()}">
                            <input class="form-check-input m-0 check-masalah" type="checkbox" name="kode_masalah[]" value="${m.kode_masalah}" ${isChecked} onchange="updateCountsMasalahRencana()">
                            <span class="small font-weight-bold">[${m.kode_masalah}]</span>
                            <span class="small flex-fill">${m.nama_masalah}</span>
                        </label>
                    `;
                });
                htmlM += '</div>';
                containerMasalah.html(htmlM);
            }

            // Render Rencana
            if (masterRencanaData.length === 0) {
                containerRencana.html('<div class="text-muted small p-2">Tidak ada master rencana keperawatan.</div>');
            } else {
                let htmlR = '<div class="list-group list-group-flush">';
                masterRencanaData.forEach((r) => {
                    const isChecked = selectedRencana.includes(r.kode_rencana) ? 'checked' : '';
                    htmlR += `
                        <label class="list-group-item py-2 px-2 d-flex align-items-center gap-2 item-rencana" data-text="${r.rencana_keperawatan.toLowerCase()}">
                            <input class="form-check-input m-0 check-rencana" type="checkbox" name="kode_rencana[]" value="${r.kode_rencana}" ${isChecked} onchange="updateCountsMasalahRencana()">
                            <span class="small font-weight-bold">[${r.kode_rencana}]</span>
                            <span class="small flex-fill">${r.rencana_keperawatan}</span>
                        </label>
                    `;
                });
                htmlR += '</div>';
                containerRencana.html(htmlR);
            }

            updateCountsMasalahRencana();
        }

        function updateCountsMasalahRencana() {
            const countM = $('.check-masalah:checked').length;
            const countR = $('.check-rencana:checked').length;
            $('#count_masalah').text(`${countM} dipilih`);
            $('#count_rencana').text(`${countR} dipilih`);
        }

        function filterMasalahKeperawatan() {
            const query = $('#cari_masalah').val().toLowerCase();
            $('.item-masalah').each(function () {
                const text = $(this).data('text') || '';
                $(this).toggle(text.indexOf(query) > -1);
            });
        }

        function filterRencanaKeperawatan() {
            const query = $('#cari_rencana').val().toLowerCase();
            $('.item-rencana').each(function () {
                const text = $(this).data('text') || '';
                $(this).toggle(text.indexOf(query) > -1);
            });
        }

        // Hitung BMI Otomatis
        function hitungBmiRalan() {
            const bb = parseFloat($('#bb').val()) || 0;
            const tb = parseFloat($('#tb').val()) || 0;
            const badge = $('#badge_bmi');

            if (bb > 0 && tb > 0) {
                const tbMeter = tb / 100;
                const bmiVal = (bb / (tbMeter * tbMeter)).toFixed(1);
                $('#bmi').val(bmiVal);

                if (bmiVal < 18.5) {
                    badge.removeClass().addClass('badge bg-warning text-white p-2 d-block').text('Kurus (<18.5)');
                } else if (bmiVal <= 22.9) {
                    badge.removeClass().addClass('badge bg-success text-white p-2 d-block').text('Normal (18.5-22.9)');
                } else if (bmiVal <= 24.9) {
                    badge.removeClass().addClass('badge bg-info text-white p-2 d-block').text('Kelebihan BB (23-24.9)');
                } else {
                    badge.removeClass().addClass('badge bg-danger text-white p-2 d-block').text('Obesitas (≥25)');
                }
            } else {
                $('#bmi').val('-');
                badge.removeClass().addClass('badge bg-secondary p-2 d-block text-white').text('-');
            }
        }

        // Kalkulasi Resiko Jatuh Otomatis (Get Up & Go)
        function hitungResikoJatuhRalan() {
            const a = $('#berjalan_a').val();
            const b = $('#berjalan_b').val();

            if (a === 'Ya' && b === 'Ya') {
                $('#hasil').val('Resiko tinggi (ditemukan a dan b)');
            } else if (a === 'Ya' || b === 'Ya') {
                $('#hasil').val('Resiko rendah (ditemukan a/b)');
            } else {
                $('#hasil').val('Tidak beresiko (tidak ditemukan a dan b)');
            }
        }

        // Toggle Asesmen Nyeri Form
        function togglePanelNyeri() {
            const nyeriVal = $('#nyeri').val();
            const isTidakNyeri = (nyeriVal === 'Tidak Ada Nyeri');
            if (isTidakNyeri) {
                $('#skala_nyeri').val('0');
            }
        }

        // Listener Skrining Gizi MST
        $('#sg1').on('change', function () {
            const n1 = $(this).find('option:selected').data('nilai') || 0;
            $('#nilai1').val(n1);
            updateTotalGizi();
        });

        $('#sg2').on('change', function () {
            const n2 = $(this).find('option:selected').data('nilai') || 0;
            $('#nilai2').val(n2);
            updateTotalGizi();
        });

        function updateTotalGizi() {
            const n1 = parseInt($('#nilai1').val()) || 0;
            const n2 = parseInt($('#nilai2').val()) || 0;
            const total = n1 + n2;
            $('#total_hasil').val(total);

            const badge = $('#badge_gizi');
            if (total >= 2) {
                badge.removeClass().addClass('badge bg-danger').text('Resiko Malnutrisi (≥ 2)');
            } else {
                badge.removeClass().addClass('badge bg-success').text('Resiko Rendah');
            }
        }

        // Salin data TTV dari pemeriksaan ralan bila perlu
        function salinTtvPemeriksaan() {
            const no_rawat = $('#no_rawat').val();
            if (!no_rawat) return;

            getPemeriksaanRalan(no_rawat).done((response) => {
                if (response && response.length > 0) {
                    const item = response[0];
                    if (item.tensi && item.tensi !== '0/0' && item.tensi !== '-') $('#td').val(item.tensi);
                    if (item.nadi && item.nadi !== '0' && item.nadi !== '-') $('#nadi').val(item.nadi);
                    if (item.respirasi && item.respirasi !== '0' && item.respirasi !== '-') $('#rr').val(item.respirasi);
                    if (item.suhu_tubuh && item.suhu_tubuh !== '0' && item.suhu_tubuh !== '-') $('#suhu').val(item.suhu_tubuh);
                    if (item.gcs && item.gcs !== '-') $('#gcs').val(item.gcs);
                    if (item.berat && item.berat !== '0' && item.berat !== '-') $('#bb').val(item.berat);
                    if (item.tinggi && item.tinggi !== '0' && item.tinggi !== '-') $('#tb').val(item.tinggi);
                    if (item.keluhan && item.keluhan !== '-') $('#keluhan_utama').val(item.keluhan);
                    if (item.alergi && item.alergi !== '-') $('#alergi').val(item.alergi);
                    hitungBmiRalan();
                    Swal.fire({
                        icon: 'info',
                        title: 'TTV Disalin',
                        text: 'Tanda-tanda vital berhasil disalin dari riwayat pemeriksaan poli.',
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

        // Open Modal Penilaian Awal Keperawatan
        function penilaianAwalKeperawatan(no_rawat) {
            // Reset tab to first tab
            $('#tabsPenilaianRalan a[href="#tab-ralan-umum"]').tab('show');
            $('#formPenilaianAwalKeperawatan').trigger('reset');
            $('#btnHapusPenilaian').addClass('d-none');
            $('#btnCetak').addClass('d-none');
            $('#alertPenilaian').addClass('d-none');
            $('#cari_masalah').val('');
            $('#cari_rencana').val('');

            loadMasterMasalahRencana(() => {
                // Fetch Patient Registration Details
                getRegDetail(no_rawat).done((reg) => {
                    $('#no_rawat').val(reg.no_rawat);
                    $('#ralan_lbl_no_rawat').text(reg.no_rawat);
                    $('#ralan_lbl_no_rm').text(reg.no_rkm_medis);
                    $('#ralan_lbl_nm_pasien').text(`${reg.pasien.nm_pasien} (${reg.pasien.jk === 'L' ? 'Laki-Laki' : 'Perempuan'})`);
                    $('#ralan_lbl_tgl_lahir').text(`${splitTanggal(reg.pasien.tgl_lahir)} / ${reg.umurdaftar} ${reg.sttsumur}`);

                    const agama = reg.pasien.agama || '-';
                    const bahasa = (reg.pasien.bahasa_pasien && reg.pasien.bahasa_pasien.nama_bahasa) ? reg.pasien.bahasa_pasien.nama_bahasa : '-';
                    $('#ralan_lbl_agama_bahasa').text(`${agama} / ${bahasa}`);

                    $('#pekerjaan').val(reg.pasien.pekerjaan || '-');
                    $('#pendidikan').val(reg.pasien.pnd || '-');
                    $('#cacat_fisik').val((reg.pasien.cacat_fisik && reg.pasien.cacat_fisik.nama_cacat) ? reg.pasien.cacat_fisik.nama_cacat : (reg.pasien.cacat_fisik || '-'));

                    // Show Modal
                    modalPenilaianAwalKeperawatan.modal('show');

                    // Check if assessment already exists
                    getPenilaianAwalKeperawatan(no_rawat).done((res) => {
                        if (res && res.no_rawat) {
                            // Populate existing record
                            $('#alertPenilaian').removeClass('d-none');
                            $('#tgl_penilaian').text(res.tanggal || '-');
                            $('#user_penilaian').text((res.pegawai && res.pegawai.nama) ? res.pegawai.nama : ((res.petugas && res.petugas.nama) ? res.petugas.nama : res.nip));
                            $('#btnCetak').removeClass('d-none');
                            $('#btnHapusPenilaian').removeClass('d-none');

                            // Populate fields
                            const form = $('#formPenilaianAwalKeperawatan');
                            $.each(res, function (key, val) {
                                if (key !== 'kode_masalah' && key !== 'kode_rencana') {
                                    const el = form.find(`[name="${key}"]`);
                                    if (el.length > 0 && val !== null) {
                                        el.val(val);
                                    }
                                }
                            });

                            // Set checked for Masalah & Rencana
                            const selMasalah = (res.masalah || []).map(m => m.kode_masalah);
                            const selRencana = (res.rencana_keperawatan || []).map(r => r.kode_rencana);
                            renderMasterCheckboxes(selMasalah, selRencana);

                            hitungBmiRalan();
                            updateTotalGizi();
                            togglePanelNyeri();
                        } else {
                            // New assessment: pre-fill from pemeriksaan_ralan if available
                            $('#btnCetak').addClass('d-none');
                            $('#btnHapusPenilaian').addClass('d-none');
                            $('#alertPenilaian').addClass('d-none');
                            renderMasterCheckboxes([], []);

                            getPemeriksaanRalan(no_rawat).done((pem) => {
                                if (pem && pem.length > 0) {
                                    const last = pem[0];
                                    if (last.keluhan && last.keluhan !== '-') $('#keluhan_utama').val(last.keluhan);
                                    if (last.tensi && last.tensi !== '0/0' && last.tensi !== '-') $('#td').val(last.tensi);
                                    if (last.nadi && last.nadi !== '0' && last.nadi !== '-') $('#nadi').val(last.nadi);
                                    if (last.respirasi && last.respirasi !== '0' && last.respirasi !== '-') $('#rr').val(last.respirasi);
                                    if (last.suhu_tubuh && last.suhu_tubuh !== '0' && last.suhu_tubuh !== '-') $('#suhu').val(last.suhu_tubuh);
                                    if (last.gcs && last.gcs !== '-') $('#gcs').val(last.gcs);
                                    if (last.alergi && last.alergi !== '-') $('#alergi').val(last.alergi);
                                    if (last.berat && last.berat !== '0' && last.berat !== '-') $('#bb').val(last.berat);
                                    if (last.tinggi && last.tinggi !== '0' && last.tinggi !== '-') $('#tb').val(last.tinggi);
                                    hitungBmiRalan();
                                }
                            });
                        }
                    });
                });
            });
        }

        // Simpan Asesmen
        function simpanPenilaianAwalKeperawatan() {
            const form = $('#formPenilaianAwalKeperawatan');
            const data = getDataForm('formPenilaianAwalKeperawatan', ['input', 'select', 'textarea']);

            // Gather selected Masalah & Rencana checkboxes
            const selectedMasalah = [];
            $('.check-masalah:checked').each(function () {
                selectedMasalah.push($(this).val());
            });
            data['kode_masalah'] = selectedMasalah;

            const selectedRencana = [];
            $('.check-rencana:checked').each(function () {
                selectedRencana.push($(this).val());
            });
            data['kode_rencana'] = selectedRencana;

            // Enforce mandatory nip
            data['nip'] = $('#nip').val() || '{{ session()->get("pegawai")->nik ?? "" }}';

            loadingAjax('Menyimpan penilaian awal keperawatan...');
            $.post(`{{ url('/penilaian/awal/keperawatan/ralan') }}`, data).done((response) => {
                swal.close();
                alertSuccessAjax('Penilaian awal keperawatan berhasil disimpan.').then(() => {
                    $('#alertPenilaian').removeClass('d-none');
                    $('#tgl_penilaian').text("{{ date('Y-m-d H:i:s') }}");
                    $('#btnCetak').removeClass('d-none');
                    $('#btnHapusPenilaian').removeClass('d-none');
                });
            }).fail((err) => {
                swal.close();
                alertErrorAjax(err);
            });
        }

        // Hapus Asesmen
        function hapusPenilaianAwalKeperawatan() {
            const no_rawat = $('#no_rawat').val();
            if (!no_rawat) return;

            Swal.fire({
                title: 'Hapus Penilaian Keperawatan?',
                text: 'Data penilaian awal keperawatan beserta masalah & rencana asuhan akan dihapus permanen.',
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
                        url: `{{ url('/penilaian/awal/keperawatan/ralan') }}`,
                        type: 'DELETE',
                        data: {
                            no_rawat: no_rawat,
                            _token: "{{ csrf_token() }}"
                        }
                    }).done(() => {
                        swal.close();
                        alertSuccessAjax('Penilaian awal keperawatan berhasil dihapus.').then(() => {
                            modalPenilaianAwalKeperawatan.modal('hide');
                        });
                    }).fail((err) => {
                        swal.close();
                        alertErrorAjax(err);
                    });
                }
            });
        }

        function getPenilaianAwalKeperawatan(no_rawat) {
            return $.get(`{{ url('/penilaian/awal/keperawatan/ralan') }}`, { no_rawat: no_rawat });
        }

        function printPenilaianAwalKeperawatan() {
            const no_rawat = $('#no_rawat').val();
            if (!no_rawat) return;
            modalCetakPenilaian.modal('show');
            modalCetakPenilaian.find('#print').attr('src', `{{ url('/penilaian/awal/keperawatan/ralan/print') }}?no_rawat=${no_rawat}`);
        }

        modalPenilaianAwalKeperawatan.on('hidden.bs.modal', () => {
            $('#formPenilaianAwalKeperawatan').trigger('reset');
        });
    </script>
@endpush

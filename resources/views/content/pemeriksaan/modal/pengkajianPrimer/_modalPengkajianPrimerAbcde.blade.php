<div class="modal modal-blur fade" id="modalPengkajianPrimerAbcde" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3 shadow-lg border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white py-3 border-bottom border-danger border-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="ti ti-ambulance fs-2"></i>
                    </div>
                    <div>
                        <h4 class="modal-title m-0 text-white fw-bold">Protokol Pengkajian Primer A-B-C-D-E</h4>
                        <small class="text-white-50">Survei Primer Gawat Darurat: Airway, Breathing, Circulation, Disability, Exposure</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Patient Banner -->
                <div class="bg-light p-3 border-bottom">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-3 col-sm-6">
                            <span class="text-muted small d-block">No. Rawat</span>
                            <span class="fw-bold fs-3 text-primary" id="primer_lbl_no_rawat">-</span>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="text-muted small d-block">Pasien / No. RM</span>
                            <span class="fw-bold text-dark d-block text-truncate" id="primer_lbl_nm_pasien">-</span>
                            <span class="badge bg-secondary-lt" id="primer_lbl_no_rm">-</span>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="text-muted small d-block">Tgl. Lahir / Umur</span>
                            <span class="fw-bold text-dark" id="primer_lbl_tgl_lahir">-</span>
                        </div>
                        <div class="col-md-3 col-sm-6 text-md-end">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="salinTtvKePrimer()">
                                <i class="ti ti-copy me-1"></i> Salin TTV Pemeriksaan
                            </button>
                        </div>
                    </div>

                    <!-- Alert Saved Status -->
                    <div class="alert alert-success d-none py-2 px-3 mt-2 mb-0 d-flex justify-content-between align-items-center" id="alertPrimerAbcde">
                        <div class="small">
                            <i class="ti ti-check me-1"></i> Pengkajian Primer tersimpan pada: <strong id="primer_lbl_tgl_simpan">-</strong> oleh <strong id="primer_lbl_petugas">-</strong>
                        </div>
                        <span class="badge bg-success">Tersimpan</span>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="bg-white border-bottom px-3 pt-2">
                    <ul class="nav nav-pills nav-fill" id="tabsPrimerAbcde" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#tab-primer-a" class="nav-link active py-2 fw-bold" data-bs-toggle="tab" role="tab">
                                <span class="badge bg-danger me-1">A</span> Airway
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-primer-b" class="nav-link py-2 fw-bold" data-bs-toggle="tab" role="tab">
                                <span class="badge bg-primary me-1">B</span> Breathing
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-primer-c" class="nav-link py-2 fw-bold" data-bs-toggle="tab" role="tab">
                                <span class="badge bg-danger me-1" style="background-color: #c2185b !important;">C</span> Circulation
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-primer-d" class="nav-link py-2 fw-bold" data-bs-toggle="tab" role="tab">
                                <span class="badge bg-warning text-dark me-1">D</span> Disability
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-primer-e" class="nav-link py-2 fw-bold" data-bs-toggle="tab" role="tab">
                                <span class="badge bg-success me-1">E</span> Exposure
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#tab-primer-kesimpulan" class="nav-link py-2 fw-bold" data-bs-toggle="tab" role="tab">
                                <span class="badge bg-dark me-1"><i class="ti ti-clipboard-check"></i></span> Kesimpulan & RTL
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Form Content -->
                <form id="formPengkajianPrimerAbcde" class="tab-content p-4">
                    <input type="hidden" id="primer_no_rawat" name="no_rawat">
                    <input type="hidden" id="primer_tgl_pengkajian" name="tgl_pengkajian">
                    <input type="hidden" id="primer_nip" name="nip" value="{{ session()->get('pegawai')->nik ?? '' }}">

                    <!-- ==================== TAB A: AIRWAY ==================== -->
                    <div class="tab-pane fade show active" id="tab-primer-a" role="tabpanel">
                        <div class="card border-0 bg-danger-lt p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger fs-3 me-2 px-3 py-2">A</span>
                                <div>
                                    <h4 class="m-0 text-danger fw-bold">Airway (Jalan Napas & Kontrol Servikal)</h4>
                                    <small class="text-muted">Fokus: Memeriksa apakah jalan napas bebas/tersumbat. Jaga kestabilan tulang leher (cervical spine protection) pada pasien trauma.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Evaluasi Airway -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-stethoscope me-1 text-danger"></i> 1. Evaluasi Klinis Jalan Napas</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kondisi Jalan Napas</label>
                                        <select class="form-select" name="airway_kondisi" id="primer_airway_kondisi">
                                            <option value="Bebas" selected>Bebas / Paten</option>
                                            <option value="Obstruksi Parsial">Obstruksi Parsial</option>
                                            <option value="Obstruksi Total">Obstruksi Total</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kemampuan Berbicara Pasien</label>
                                        <select class="form-select" name="airway_bicara" id="primer_airway_bicara">
                                            <option value="Bisa Berbicara" selected>Bisa Berbicara (Jalan napas umumnya aman)</option>
                                            <option value="Tidak Bisa Berbicara">Tidak Bisa Berbicara</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Suara Napas Tambahan</label>
                                        <select class="form-select" name="airway_suara" id="primer_airway_suara">
                                            <option value="Normal / Bebas" selected>Normal / Bebas (Tidak ada suara tambahan)</option>
                                            <option value="Snoring (Mengorok)">Snoring (Mengorok - Obstruksi lidah jatuh)</option>
                                            <option value="Gargling (Kumur/Cairan)">Gargling (Kumur - Cairan/darah di jalan napas)</option>
                                            <option value="Stridor">Stridor (Edema laring / Obstruksi parsial trakea)</option>
                                            <option value="Benda Asing">Benda Asing Terlihat</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label fw-bold">Kecurigaan Cedera Leher (Trauma Cervical)</label>
                                        <select class="form-select" name="airway_cervical" id="primer_airway_cervical">
                                            <option value="Tidak Ada Cedera" selected>Tidak Ada Cedera</option>
                                            <option value="Curiga Fraktur / Cedera Leher (Trauma)">Curiga Fraktur / Cedera Leher (Trauma multitrauma / cedera kepala)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tindakan Airway -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-tools me-1 text-danger"></i> 2. Tindakan / Intervensi Segera</h5>
                                    <p class="small text-muted mb-2">Centang semua tindakan yang telah atau sedang dilakukan pada pasien:</p>
                                    
                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="airway_tindakan[]" value="Head Tilt - Chin Lift">
                                            <span class="form-check-label fw-bold">Head Tilt - Chin Lift</span>
                                            <small class="text-muted d-block ms-3">Hanya jika TIDAK dicurigai cedera leher</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="airway_tindakan[]" value="Jaw Thrust Maneuver">
                                            <span class="form-check-label fw-bold">Jaw Thrust Maneuver</span>
                                            <small class="text-muted d-block ms-3">Wajib jika dicurigai fraktur/cedera servikal</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="airway_tindakan[]" value="Pemasangan Cervical Collar (Neck Collar)">
                                            <span class="form-check-label fw-bold">Pemasangan Cervical Collar (Neck Collar)</span>
                                            <small class="text-muted d-block ms-3">Imobilisasi kestabilan tulang leher</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="airway_tindakan[]" value="Suctioning Cairan / Darah">
                                            <span class="form-check-label fw-bold">Suctioning Cairan / Darah</span>
                                            <small class="text-muted d-block ms-3">Membersihkan cairan/lendir/darah di orofaring</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="airway_tindakan[]" value="Pemasangan OPA (Guedel) / NPA">
                                            <span class="form-check-label fw-bold">Pemasangan OPA (Oropharyngeal Airway / Guedel) / NPA</span>
                                            <small class="text-muted d-block ms-3">Mencegah pangkal lidah menyumbat jalan napas</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="airway_tindakan[]" value="Intubasi Endotrakeal (ETT)">
                                            <span class="form-check-label fw-bold">Intubasi Endotrakeal (ETT)</span>
                                            <small class="text-muted d-block ms-3">Definitive airway untuk jalan napas terancam / GCS &le; 8</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TAB B: BREATHING ==================== -->
                    <div class="tab-pane fade" id="tab-primer-b" role="tabpanel">
                        <div class="card border-0 bg-primary-lt p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary fs-3 me-2 px-3 py-2">B</span>
                                <div>
                                    <h4 class="m-0 text-primary fw-bold">Breathing (Pernapasan & Oksigenasi)</h4>
                                    <small class="text-muted">Fokus: Menilai kecukupan napas, frekuensi napas, pergerakan dada, dan pemberian oksigen jika diperlukan.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Evaluasi Breathing -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-activity me-1 text-primary"></i> 1. Evaluasi Klinis (Look, Listen, Feel)</h5>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Frekuensi Napas (RR)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control fw-bold" name="breathing_rr" id="primer_breathing_rr" value="20">
                                                <span class="input-group-text">x/menit</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Saturasi Oksigen (SpO₂)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control fw-bold" name="breathing_spo2" id="primer_breathing_spo2" value="98">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Gerakan Dinding Dada (Look)</label>
                                        <select class="form-select" name="breathing_gerakan" id="primer_breathing_gerakan">
                                            <option value="Simetris" selected>Simetris (Normal)</option>
                                            <option value="Asimetris">Asimetris (Pneumotoraks / Hemotoraks)</option>
                                            <option value="Retraksi Dinding Dada">Retraksi Dinding Dada (Interkostal / Suprasternal)</option>
                                            <option value="Flail Chest">Flail Chest (Gerakan paradoksal)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Suara Napas (Listen)</label>
                                        <select class="form-select" name="breathing_suara" id="primer_breathing_suara">
                                            <option value="Vesikuler (Normal)" selected>Vesikuler (Normal bilateral)</option>
                                            <option value="Wheezing (Mengi)">Wheezing (Mengi / Asma / PPOK)</option>
                                            <option value="Ronkhi (Basah)">Ronkhi (Basah / Edema Paru / Infeksi)</option>
                                            <option value="Menurun / Hilang">Menurun / Hilang pada satu sisi (Tension Pneumothorax)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label fw-bold">Embusan Napas (Feel)</label>
                                        <select class="form-select" name="breathing_embusan" id="primer_breathing_embusan">
                                            <option value="Kuat & Terasa" selected>Kuat & Terasa (Adekuat)</option>
                                            <option value="Lemah / Dangkal">Lemah / Dangkal (Hipoventilasi)</option>
                                            <option value="Tidak Terasa">Tidak Terasa (Apnea / Henti Napas)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tindakan Breathing -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-tools me-1 text-primary"></i> 2. Tindakan / Terapi Oksigen</h5>
                                    <p class="small text-muted mb-2">Centang bantuan oksigen / ventilasi yang diberikan:</p>

                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="breathing_tindakan[]" value="Room Air (Tanpa Bantuan Oksigen)">
                                            <span class="form-check-label fw-bold">Udara Ruangan (Room Air)</span>
                                            <small class="text-muted d-block ms-3">SpO₂ &ge; 95% dan pernapasan adekuat</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="breathing_tindakan[]" value="Nasal Cannula (2 - 4 Lpm)">
                                            <span class="form-check-label fw-bold">Nasal Cannula (2 - 4 Lpm)</span>
                                            <small class="text-muted d-block ms-3">Hipoksia ringan (FiO₂ ~28-36%)</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="breathing_tindakan[]" value="Simple Mask (6 - 8 Lpm)">
                                            <span class="form-check-label fw-bold">Simple Face Mask (6 - 8 Lpm)</span>
                                            <small class="text-muted d-block ms-3">Hipoksia sedang (FiO₂ ~40-60%)</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="breathing_tindakan[]" value="Non-Rebreathing Mask / NRM (10 - 15 Lpm)">
                                            <span class="form-check-label fw-bold">Non-Rebreathing Mask / NRM (10 - 15 Lpm)</span>
                                            <small class="text-muted d-block ms-3">Distres napas berat / Trauma berat (FiO₂ ~80-100%)</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="breathing_tindakan[]" value="Ventilasi Tekanan Positif (Bag Valve Mask / BVM)">
                                            <span class="form-check-label fw-bold">Ventilasi Tekanan Positif (Bag Valve Mask / BVM)</span>
                                            <small class="text-muted d-block ms-3">Jika pasien hipoventilasi / apnea</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="breathing_tindakan[]" value="Needle Decompression / Kasa 3 Sisi">
                                            <span class="form-check-label fw-bold">Dekompresi Jarum / Kasa 3 Sisi</span>
                                            <small class="text-muted d-block ms-3">Tindakan darurat Tension / Open Pneumothorax</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TAB C: CIRCULATION ==================== -->
                    <div class="tab-pane fade" id="tab-primer-c" role="tabpanel">
                        <div class="card border-0 p-3 mb-3 text-white" style="background-color: #880e4f;">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-danger fs-3 me-2 px-3 py-2">C</span>
                                <div>
                                    <h4 class="m-0 text-white fw-bold">Circulation (Sirkulasi Darah & Jantung)</h4>
                                    <small class="text-white-50">Fokus: Menilai denyut nadi, tekanan darah, warna kulit, suhu akral, serta menghentikan perdarahan hebat jika ada.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Evaluasi Circulation -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-heart-rate-monitor me-1 text-danger"></i> 1. Status Hemodinamik & Perdarahan</h5>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Denyut Nadi / HR</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control fw-bold" name="circulation_hr" id="primer_circulation_hr" value="80">
                                                <span class="input-group-text">bpm</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Tekanan Darah (TD)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control fw-bold" name="circulation_td" id="primer_circulation_td" value="120/80">
                                                <span class="input-group-text">mmHg</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Karakter Denyut Nadi</label>
                                        <select class="form-select" name="circulation_nadi" id="primer_circulation_nadi">
                                            <option value="Kuat & Teratur" selected>Kuat & Teratur (Normal)</option>
                                            <option value="Lemah & Cepat">Lemah & Cepat (Tanda Syok / Hipovolemia)</option>
                                            <option value="Tidak Teraba / Henti Jantung">Tidak Teraba (Henti Jantung / Cardiac Arrest)</option>
                                            <option value="Irreguler / Tidak Teratur">Irreguler / Aritmia</option>
                                        </select>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">CRT (Capillary Refill)</label>
                                            <select class="form-select" name="circulation_crt" id="primer_circulation_crt">
                                                <option value="< 2 Detik (Normal)" selected>&lt; 2 Detik (Normal)</option>
                                                <option value="> 2 Detik (Melambat)">&gt; 2 Detik (Perfusi Menurun / Syok)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Akral & Warna Kulit</label>
                                            <select class="form-select" name="circulation_akral" id="primer_circulation_akral">
                                                <option value="Hangat Kering Merah" selected>Hangat, Kering, Merah</option>
                                                <option value="Dingin Basah Pucat (Syok)">Dingin, Basah, Pucat (Syok)</option>
                                                <option value="Sianosis (Kebiruan)">Sianosis (Kebiruan)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Perdarahan Luar Aktif</label>
                                            <select class="form-select" name="circulation_perdarahan" id="primer_circulation_perdarahan">
                                                <option value="Tidak Ada Perdarahan" selected>Tidak Ada Perdarahan</option>
                                                <option value="Perdarahan Ringan / Terkontrol">Perdarahan Ringan / Terkontrol</option>
                                                <option value="Perdarahan Aktif Masif">Perdarahan Aktif Masif</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Lokasi Perdarahan</label>
                                            <input type="text" class="form-control" name="circulation_lokasi_perdarahan" id="primer_circulation_lokasi_perdarahan" placeholder="Tuliskan lokasi luka..." value="-">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tindakan Circulation -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-tools me-1 text-danger"></i> 2. Tindakan Sirkulasi & Resusitasi Cairan</h5>
                                    <p class="small text-muted mb-2">Centang intervensi sirkulasi yang dilakukan:</p>

                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="circulation_tindakan[]" value="Balut Tekan / Direct Pressure">
                                            <span class="form-check-label fw-bold">Balut Tekan / Direct Pressure</span>
                                            <small class="text-muted d-block ms-3">Menghentikan perdarahan eksternal aktif secara langsung</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="circulation_tindakan[]" value="Pemasangan Tourniquet">
                                            <span class="form-check-label fw-bold">Pemasangan Tourniquet</span>
                                            <small class="text-muted d-block ms-3">Perdarahan ekstremitas masif yang gagal dengan balut tekan</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="circulation_tindakan[]" value="Pemasangan Akses IV Line Jarum Besar (18G / 16G)">
                                            <span class="form-check-label fw-bold">Pemasangan Akses IV Line Jarum Besar (18G / 16G)</span>
                                            <small class="text-muted d-block ms-3">2 jalur infus bila pasien dalam kondisi syok</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="circulation_tindakan[]" value="Resusitasi Cairan Kristaloid Hangat (RL / NaCl 0.9%)">
                                            <span class="form-check-label fw-bold">Resusitasi Cairan Kristaloid Hangat (RL / NaCl 0.9%)</span>
                                            <small class="text-muted d-block ms-3">Loading cairan pada syok hipovolemik</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="circulation_tindakan[]" value="Resusitasi Jantung Paru (RJP / CPR) & Defibrilasi">
                                            <span class="form-check-label fw-bold">Resusitasi Jantung Paru (RJP / CPR) & Defibrilasi</span>
                                            <small class="text-muted d-block ms-3">Dilakukan segera jika pasien henti jantung</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TAB D: DISABILITY ==================== -->
                    <div class="tab-pane fade" id="tab-primer-d" role="tabpanel">
                        <div class="card border-0 bg-warning-lt p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning text-dark fs-3 me-2 px-3 py-2">D</span>
                                <div>
                                    <h4 class="m-0 text-dark fw-bold">Disability (Status Neurologis / Kesadaran)</h4>
                                    <small class="text-muted">Fokus: Menilai tingkat kesadaran menggunakan skala Glasgow Coma Scale (GCS) atau metode AVPU, serta memeriksa refleks pupil.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Evaluasi AVPU & GCS -->
                            <div class="col-lg-7">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-brain me-1 text-warning"></i> 1. Metode AVPU & GCS Calculator</h5>

                                    <!-- Quick AVPU Cards -->
                                    <label class="form-label fw-bold mb-2">Metode AVPU (Cepat):</label>
                                    <div class="row g-2 mb-3">
                                        <div class="col-3">
                                            <button type="button" class="btn btn-outline-success w-100 p-2 text-start btn-avpu active" data-avpu="Alert (Sadar Penuh)" onclick="selectAvpu('Alert (Sadar Penuh)', 4, 5, 6)">
                                                <div class="fw-bold fs-3">A</div>
                                                <small style="font-size: 10px;">Alert (Sadar Penuh)</small>
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <button type="button" class="btn btn-outline-info w-100 p-2 text-start btn-avpu" data-avpu="Verbal (Respons Suara)" onclick="selectAvpu('Verbal (Respons Suara)', 3, 4, 5)">
                                                <div class="fw-bold fs-3">V</div>
                                                <small style="font-size: 10px;">Verbal (Suara)</small>
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <button type="button" class="btn btn-outline-warning w-100 p-2 text-start btn-avpu" data-avpu="Pain (Respons Nyeri)" onclick="selectAvpu('Pain (Respons Nyeri)', 2, 2, 4)">
                                                <div class="fw-bold fs-3">P</div>
                                                <small style="font-size: 10px;">Pain (Nyeri)</small>
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <button type="button" class="btn btn-outline-danger w-100 p-2 text-start btn-avpu" data-avpu="Unresponsive (Tidak Merespons)" onclick="selectAvpu('Unresponsive (Tidak Merespons)', 1, 1, 1)">
                                                <div class="fw-bold fs-3">U</div>
                                                <small style="font-size: 10px;">Unresponsive</small>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="disability_avpu" id="primer_disability_avpu" value="Alert (Sadar Penuh)">

                                    <!-- GCS Controls -->
                                    <label class="form-label fw-bold mb-2">Glasgow Coma Scale (GCS):</label>
                                    <div class="row g-2 align-items-center bg-light p-2 rounded mb-3">
                                        <div class="col-md-3">
                                            <label class="small text-muted d-block">Eye (Mata)</label>
                                            <select class="form-select form-select-sm" name="disability_gcs_e" id="primer_disability_gcs_e" onchange="hitungGcsPrimer()">
                                                <option value="4" selected>4 - Spontan</option>
                                                <option value="3">3 - Perintah Suara</option>
                                                <option value="2">2 - Rangsang Nyeri</option>
                                                <option value="1">1 - Tidak Ada</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted d-block">Verbal (Suara)</label>
                                            <select class="form-select form-select-sm" name="disability_gcs_v" id="primer_disability_gcs_v" onchange="hitungGcsPrimer()">
                                                <option value="5" selected>5 - Terorientasi</option>
                                                <option value="4">4 - Bingung</option>
                                                <option value="3">3 - Kata Tidak Tepat</option>
                                                <option value="2">2 - Suara Mengerang</option>
                                                <option value="1">1 - Tidak Ada</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted d-block">Motorik (Gerakan)</label>
                                            <select class="form-select form-select-sm" name="disability_gcs_m" id="primer_disability_gcs_m" onchange="hitungGcsPrimer()">
                                                <option value="6" selected>6 - Mengikuti Perintah</option>
                                                <option value="5">5 - Melokalisir Nyeri</option>
                                                <option value="4">4 - Menghindar Nyeri</option>
                                                <option value="3">3 - Fleksi Abnormal</option>
                                                <option value="2">2 - Ekstensi Abnormal</option>
                                                <option value="1">1 - Tidak Ada</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <span class="small text-muted d-block">Total GCS</span>
                                            <span class="fs-2 fw-bold text-primary" id="primer_lbl_gcs_total">15</span>
                                            <input type="hidden" name="disability_gcs_total" id="primer_disability_gcs_total" value="15">
                                        </div>
                                    </div>

                                    <!-- Pupil & Refleks Cahaya -->
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Kondisi Pupil</label>
                                            <select class="form-select form-select-sm" name="disability_pupil" id="primer_disability_pupil">
                                                <option value="Isokor (Normal)" selected>Isokor (Normal)</option>
                                                <option value="Anisokor">Anisokor</option>
                                                <option value="Pinpoint">Pinpoint</option>
                                                <option value="Midriasis Maksimal">Midriasis Maksimal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Diameter Pupil</label>
                                            <input type="text" class="form-control form-select-sm" name="disability_pupil_diameter" id="primer_disability_pupil_diameter" value="3mm / 3mm">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Refleks Cahaya</label>
                                            <select class="form-select form-select-sm" name="disability_refleks_cahaya" id="primer_disability_refleks_cahaya">
                                                <option value="+/+ (Keduanya Positif)" selected>+/+ (Keduanya Positif)</option>
                                                <option value="+/- (Kanan Positif, Kiri Negatif)">+/- (Kanan Positif, Kiri Negatif)</option>
                                                <option value="-/+ (Kanan Negatif, Kiri Positif)">-/+ (Kanan Negatif, Kiri Positif)</option>
                                                <option value="-/- (Keduanya Negatif)">-/- (Keduanya Negatif)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tindakan Disability -->
                            <div class="col-lg-5">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-tools me-1 text-warning"></i> 2. Tindakan Neurologis</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Gula Darah Sewaktu (GDS Rapid)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="disability_gds" id="primer_disability_gds" placeholder="Hasil GDS..." value="-">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                        <small class="text-muted">Singkirkan hipoglikemia pada penurunan kesadaran</small>
                                    </div>

                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="disability_tindakan[]" value="Elevasi Kepala 30 Derajat">
                                            <span class="form-check-label fw-bold">Elevasi Kepala 30 Derajat</span>
                                            <small class="text-muted d-block ms-3">Jika TIK meningkat tanpa adanya syok/fraktur servikal</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="disability_tindakan[]" value="Pemberian Dextrose 40% (Hipoglikemia)">
                                            <span class="form-check-label fw-bold">Koreksi Dekstrosa 40% (D40)</span>
                                            <small class="text-muted d-block ms-3">Jika terbukti hipoglikemia (&lt; 70 mg/dL)</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="disability_tindakan[]" value="Pemberian Antikonvulsan (Anti Kejang)">
                                            <span class="form-check-label fw-bold">Pemberian Antikonvulsan (Anti Kejang)</span>
                                            <small class="text-muted d-block ms-3">Diazepam IV / Midazolam jika kejang aktif</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TAB E: EXPOSURE ==================== -->
                    <div class="tab-pane fade" id="tab-primer-e" role="tabpanel">
                        <div class="card border-0 bg-success-lt p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success fs-3 me-2 px-3 py-2">E</span>
                                <div>
                                    <h4 class="m-0 text-success fw-bold">Exposure & Environment (Paparan & Cegah Hipotermia)</h4>
                                    <small class="text-muted">Fokus: Membuka pakaian pasien untuk memeriksa cedera tersembunyi, sekaligus menjaga suhu tubuh agar tidak terjadi hipotermia.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Evaluasi Exposure -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-eye me-1 text-success"></i> 1. Pemeriksaan Cedera Tubuh (Head-to-Toe)</h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status Cedera Luar Tersembunyi</label>
                                        <select class="form-select" name="exposure_cedera" id="primer_exposure_cedera">
                                            <option value="Tidak Ada Cedera Luar" selected>Tidak Ada Cedera Luar</option>
                                            <option value="Terdapat Cedera / Jejas Tersembunyi">Terdapat Cedera / Jejas Tersembunyi</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Deskripsi Cedera (DCAP-BTLS / Jejas / Fraktur / Luka Bakar)</label>
                                        <textarea class="form-control" name="exposure_deskripsi_cedera" id="primer_exposure_deskripsi_cedera" rows="3" placeholder="Tuliskan temuan cedera, luka lecet, laserasi, jejas dada/abdomen, atau patah tulang...">-</textarea>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Suhu Tubuh</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control fw-bold" name="exposure_suhu" id="primer_exposure_suhu" value="36.5">
                                                <span class="input-group-text">°C</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Resiko Hipotermia</label>
                                            <select class="form-select" name="exposure_hipotermia" id="primer_exposure_hipotermia">
                                                <option value="Tidak Ada" selected>Tidak Ada (Suhu Normal)</option>
                                                <option value="Hipotermia Ringan (32 - 35°C)">Hipotermia Ringan (32 - 35°C)</option>
                                                <option value="Hipotermia Sedang (28 - 32°C)">Hipotermia Sedang (28 - 32°C)</option>
                                                <option value="Hipotermia Berat (< 28°C)">Hipotermia Berat (&lt; 28°C)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tindakan Exposure -->
                            <div class="col-lg-6">
                                <div class="card p-3 border h-100">
                                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="ti ti-tools me-1 text-success"></i> 2. Tindakan Pencegahan & Perawatan</h5>
                                    <p class="small text-muted mb-2">Centang tindakan lingkungan dan proteksi pasien:</p>

                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="exposure_tindakan[]" value="Pelepasan Pakaian & Pemeriksaan Seluruh Tubuh">
                                            <span class="form-check-label fw-bold">Pelepasan Pakaian & Pemeriksaan Seluruh Tubuh</span>
                                            <small class="text-muted d-block ms-3">Membuka pakaian pasien secara hati-hati / digunting</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="exposure_tindakan[]" value="Pemberian Selimut Hangat / Blanket Warmer">
                                            <span class="form-check-label fw-bold">Pemberian Selimut Hangat / Blanket Warmer</span>
                                            <small class="text-muted d-block ms-3">Segera selimuti pasien pasca pemeriksaan untuk cegah hipotermia</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="exposure_tindakan[]" value="Log Roll Maneuver (Pemeriksaan Punggung)">
                                            <span class="form-check-label fw-bold">Log Roll Maneuver (Pemeriksaan Spina & Punggung)</span>
                                            <small class="text-muted d-block ms-3">Dilakukan minimal 3-4 petugas dengan menjaga in-line immobilization</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="exposure_tindakan[]" value="Imobilisasi Fraktur (Bidai / Spalk / Sling)">
                                            <span class="form-check-label fw-bold">Imobilisasi Fraktur (Bidai / Spalk / Sling)</span>
                                            <small class="text-muted d-block ms-3">Fiksasi area tulang yang dicurigai patah</small>
                                        </label>

                                        <label class="form-check p-2 border rounded bg-white">
                                            <input class="form-check-input" type="checkbox" name="exposure_tindakan[]" value="Perawatan & Penutupan Luka Bersih">
                                            <span class="form-check-label fw-bold">Perawatan & Penutupan Luka Bersih</span>
                                            <small class="text-muted d-block ms-3">Irigasi NaCl & pembalutan kasa steril</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== TAB RINGKASAN & TINDAK LANJUT ==================== -->
                    <div class="tab-pane fade" id="tab-primer-kesimpulan" role="tabpanel">
                        <div class="card border-0 bg-dark text-white p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger fs-3 me-2 px-3 py-2"><i class="ti ti-check"></i></span>
                                <div>
                                    <h4 class="m-0 text-white fw-bold">Kesimpulan Pengkajian Primer & Rencana Tindak Lanjut</h4>
                                    <small class="text-white-50">Menentukan status stabilitas akhir pasien pasca resusitasi primer dan rencana penanganan lanjutan.</small>
                                </div>
                            </div>
                        </div>

                        <div class="card p-3 border mb-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-danger">Status Pasien Pasca Resusitasi Primer</label>
                                    <select class="form-select form-select-lg fw-bold" name="status_pasien" id="primer_status_pasien">
                                        <option value="Stabil" selected class="text-success">Stabil (Jalan napas paten, hemodinamik normal)</option>
                                        <option value="Potensial Kritis" class="text-warning">Potensial Kritis (Perlu observasi ketat)</option>
                                        <option value="Kritis / Tidak Stabil" class="text-danger">Kritis / Tidak Stabil (Masih membutuhkan intervensi aktif)</option>
                                        <option value="Mengancam Nyawa (Life-Threatening)" class="text-danger">Mengancam Nyawa (Life-Threatening / Resusitasi Berjalan)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-primary">Rencana Tindak Lanjut Pasien</label>
                                    <select class="form-select form-select-lg fw-bold" name="rencana_tindak_lanjut" id="primer_rencana_tindak_lanjut">
                                        <option value="Observasi UGD" selected>Observasi di UGD (Klinik)</option>
                                        <option value="Pindah Rawat Inap">Pindah ke Ruang Rawat Inap</option>
                                        <option value="Pindah ICU / HCU">Pindah ke Ruang Intensif (ICU / HCU)</option>
                                        <option value="Kamar Operasi Cito (OK)">Kamar Operasi Cito (Emergency Surgery)</option>
                                        <option value="Rujuk ke Faskes Lain">Rujuk ke Rumah Sakit / Faskes Lanjutan</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Catatan Khusus / Perburukan Klinis / Instruksi Dokter</label>
                                    <textarea class="form-control" name="catatan_tambahan" id="primer_catatan_tambahan" rows="3" placeholder="Tuliskan catatan khusus terkait respons pasien terhadap tindakan resusitasi primer...">-</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light p-3 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-danger d-none" id="btnHapusPrimer" onclick="hapusPengkajianPrimer()">
                        <i class="ti ti-trash me-1"></i> Hapus Pengkajian
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary d-none" id="btnCetakPrimer" onclick="cetakPengkajianPrimer()">
                        <i class="ti ti-printer me-1"></i> Cetak PDF
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="btnSimpanPrimer" onclick="simpanPengkajianPrimer()">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Pengkajian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nested Modal Cetak PDF -->
<div class="modal modal-blur fade" id="modalCetakPrimerAbcde" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-danger text-white py-2">
                <h5 class="modal-title m-0 text-white"><i class="ti ti-printer me-2"></i>Cetak Pengkajian Primer A-B-C-D-E</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="iframe_print_primer" width="100%" height="650" class="border-0"></iframe>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        var modalPengkajianPrimerAbcde = $('#modalPengkajianPrimerAbcde');
        var modalCetakPrimerAbcde = $('#modalCetakPrimerAbcde');
        var currentNoRawatPrimer = '';

        // AVPU Quick Card Selector
        function selectAvpu(avpuText, e, v, m) {
            $('#primer_disability_avpu').val(avpuText);
            $('.btn-avpu').removeClass('active');
            $(`.btn-avpu[data-avpu="${avpuText}"]`).addClass('active');

            if (e !== undefined) $('#primer_disability_gcs_e').val(e);
            if (v !== undefined) $('#primer_disability_gcs_v').val(v);
            if (m !== undefined) $('#primer_disability_gcs_m').val(m);
            hitungGcsPrimer();
        }

        // Kalkulasi GCS Otomatis
        function hitungGcsPrimer() {
            const e = parseInt($('#primer_disability_gcs_e').val()) || 0;
            const v = parseInt($('#primer_disability_gcs_v').val()) || 0;
            const m = parseInt($('#primer_disability_gcs_m').val()) || 0;
            const total = e + v + m;

            $('#primer_disability_gcs_total').val(total);
            $('#primer_lbl_gcs_total').text(total);

            if (total === 15) {
                $('#primer_disability_avpu').val('Alert (Sadar Penuh)');
                $('.btn-avpu').removeClass('active');
                $('.btn-avpu[data-avpu="Alert (Sadar Penuh)"]').addClass('active');
            } else if (total >= 12) {
                $('#primer_disability_avpu').val('Verbal (Respons Suara)');
                $('.btn-avpu').removeClass('active');
                $('.btn-avpu[data-avpu="Verbal (Respons Suara)"]').addClass('active');
            } else if (total >= 8) {
                $('#primer_disability_avpu').val('Pain (Respons Nyeri)');
                $('.btn-avpu').removeClass('active');
                $('.btn-avpu[data-avpu="Pain (Respons Nyeri)"]').addClass('active');
            } else {
                $('#primer_disability_avpu').val('Unresponsive (Tidak Merespons)');
                $('.btn-avpu').removeClass('active');
                $('.btn-avpu[data-avpu="Unresponsive (Tidak Merespons)"]').addClass('active');
            }
        }

        // Salin TTV dari Pemeriksaan Poli / Triase
        function salinTtvKePrimer() {
            const no_rawat = $('#primer_no_rawat').val() || currentNoRawatPrimer;
            if (!no_rawat) return;

            getPemeriksaanRalan(no_rawat).done((response) => {
                if (response && response.length > 0) {
                    const item = response[0];
                    if (item.tensi && item.tensi !== '0/0' && item.tensi !== '-') $('#primer_circulation_td').val(item.tensi);
                    if (item.nadi && item.nadi !== '0' && item.nadi !== '-') $('#primer_circulation_hr').val(item.nadi);
                    if (item.respirasi && item.respirasi !== '0' && item.respirasi !== '-') $('#primer_breathing_rr').val(item.respirasi);
                    if (item.suhu_tubuh && item.suhu_tubuh !== '0' && item.suhu_tubuh !== '-') $('#primer_exposure_suhu').val(item.suhu_tubuh);
                    if (item.spo2 && item.spo2 !== '0' && item.spo2 !== '-') $('#primer_breathing_spo2').val(item.spo2);
                    if (item.gcs && item.gcs !== '-') {
                        const parts = item.gcs.split(',');
                        if (parts.length === 3) {
                            $('#primer_disability_gcs_e').val(parts[0].trim());
                            $('#primer_disability_gcs_v').val(parts[1].trim());
                            $('#primer_disability_gcs_m').val(parts[2].trim());
                            hitungGcsPrimer();
                        }
                    }

                    Swal.fire({
                        icon: 'info',
                        title: 'TTV Disalin',
                        text: 'Tanda vital (TD, HR, RR, Suhu, SpO2) berhasil disalin dari riwayat pemeriksaan pasien.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Informasi', 'Belum ada data TTV pada riwayat pemeriksaan poli.', 'info');
                }
            });
        }

        // Buka Modal Pengkajian Primer ABCDE
        function openPengkajianPrimer(no_rawat) {
            currentNoRawatPrimer = no_rawat;
            $('#tabsPrimerAbcde a[href="#tab-primer-a"]').tab('show');
            $('#formPengkajianPrimerAbcde').trigger('reset');
            $('#primer_no_rawat').val(no_rawat);
            $('#btnHapusPrimer').addClass('d-none');
            $('#btnCetakPrimer').addClass('d-none');
            $('#alertPrimerAbcde').addClass('d-none');
            $('.btn-avpu').removeClass('active');
            $('.btn-avpu[data-avpu="Alert (Sadar Penuh)"]').addClass('active');

            // Fetch Reg Detail
            getRegDetail(no_rawat).done((reg) => {
                currentNoRawatPrimer = reg.no_rawat;
                $('#primer_no_rawat').val(reg.no_rawat);
                $('#primer_lbl_no_rawat').text(reg.no_rawat);
                $('#primer_lbl_no_rm').text(reg.no_rkm_medis);
                $('#primer_lbl_nm_pasien').text(`${reg.pasien.nm_pasien} (${reg.pasien.jk === 'L' ? 'Laki-Laki' : 'Perempuan'})`);
                $('#primer_lbl_tgl_lahir').text(`${splitTanggal(reg.pasien.tgl_lahir)} / ${reg.umurdaftar} ${reg.sttsumur}`);

                modalPengkajianPrimerAbcde.modal('show');

                // Check existing record
                $.get(`{{ url('/rm/pengkajian/primer') }}`, { no_rawat: no_rawat }).done((res) => {
                    if (res && res.no_rawat) {
                        $('#alertPrimerAbcde').removeClass('d-none');
                        $('#primer_lbl_tgl_simpan').text(res.tgl_pengkajian || '-');
                        $('#primer_lbl_petugas').text((res.pegawai && res.pegawai.nama) ? res.pegawai.nama : ((res.petugas && res.petugas.nama) ? res.petugas.nama : res.nip));
                        $('#btnCetakPrimer').removeClass('d-none');
                        $('#btnHapusPrimer').removeClass('d-none');

                        const form = $('#formPengkajianPrimerAbcde');
                        $.each(res, function (key, val) {
                            if (val !== null) {
                                // Checkbox arrays
                                if (key.endsWith('_tindakan')) {
                                    let arr = val;
                                    if (typeof val === 'string') {
                                        try { arr = JSON.parse(val); } catch(e) { arr = []; }
                                    }
                                    if (Array.isArray(arr)) {
                                        arr.forEach(item => {
                                            form.find(`input[name="${key}[]"][value="${item}"]`).prop('checked', true);
                                        });
                                    }
                                } else {
                                    const el = form.find(`[name="${key}"]`);
                                    if (el.length > 0) {
                                        el.val(val);
                                    }
                                }
                            }
                        });

                        $('#primer_no_rawat').val(res.no_rawat || no_rawat);
                        hitungGcsPrimer();

                        if (res.disability_avpu) {
                            $('.btn-avpu').removeClass('active');
                            $(`.btn-avpu[data-avpu="${res.disability_avpu}"]`).addClass('active');
                        }
                    } else {
                        $('#btnCetakPrimer').addClass('d-none');
                        $('#btnHapusPrimer').addClass('d-none');
                        $('#alertPrimerAbcde').addClass('d-none');
                        $('#primer_no_rawat').val(no_rawat);

                        // Auto-fill from pemeriksaan_ralan if available
                        salinTtvKePrimer();
                    }
                });
            });
        }

        // Simpan Pengkajian Primer
        function simpanPengkajianPrimer() {
            const form = $('#formPengkajianPrimerAbcde');
            const data = getDataForm('formPengkajianPrimerAbcde', ['input', 'select', 'textarea']);

            data['no_rawat'] = $('#primer_no_rawat').val() || currentNoRawatPrimer;
            data['nip'] = $('#primer_nip').val() || '{{ session()->get("pegawai")->nik ?? "" }}';

            if (!data['no_rawat']) {
                Swal.fire('Perhatian', 'No. Rawat tidak ditemukan. Silakan buka kembali form.', 'warning');
                return;
            }

            // Gather checkbox arrays
            ['airway_tindakan', 'breathing_tindakan', 'circulation_tindakan', 'disability_tindakan', 'exposure_tindakan'].forEach(f => {
                const checkedVals = [];
                form.find(`input[name="${f}[]"]:checked`).each(function () {
                    checkedVals.push($(this).val());
                });
                data[f] = checkedVals;
            });

            loadingAjax('Menyimpan Pengkajian Primer A-B-C-D-E...');
            $.post(`{{ url('/rm/pengkajian/primer') }}`, data).done((response) => {
                swal.close();
                alertSuccessAjax('Pengkajian Primer A-B-C-D-E berhasil disimpan.').then(() => {
                    $('#alertPrimerAbcde').removeClass('d-none');
                    $('#primer_lbl_tgl_simpan').text("{{ date('Y-m-d H:i:s') }}");
                    $('#btnCetakPrimer').removeClass('d-none');
                    $('#btnHapusPrimer').removeClass('d-none');
                });
            }).fail((err) => {
                swal.close();
                alertErrorAjax(err);
            });
        }

        // Hapus Pengkajian Primer
        function hapusPengkajianPrimer() {
            const no_rawat = $('#primer_no_rawat').val() || currentNoRawatPrimer;
            if (!no_rawat) return;

            Swal.fire({
                title: 'Hapus Pengkajian Primer?',
                text: 'Data protokol pengkajian primer A-B-C-D-E akan dihapus permanen.',
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
                        url: `{{ url('/rm/pengkajian/primer') }}`,
                        type: 'DELETE',
                        data: {
                            no_rawat: no_rawat,
                            _token: "{{ csrf_token() }}"
                        }
                    }).done(() => {
                        swal.close();
                        alertSuccessAjax('Data pengkajian primer berhasil dihapus.').then(() => {
                            modalPengkajianPrimerAbcde.modal('hide');
                        });
                    }).fail((err) => {
                        swal.close();
                        alertErrorAjax(err);
                    });
                }
            });
        }

        // Cetak PDF Pengkajian Primer
        function cetakPengkajianPrimer() {
            const no_rawat = $('#primer_no_rawat').val() || currentNoRawatPrimer;
            if (!no_rawat) return;
            modalCetakPrimerAbcde.modal('show');
            modalCetakPrimerAbcde.find('#iframe_print_primer').attr('src', `{{ url('/rm/pengkajian/primer/print') }}?no_rawat=${no_rawat}`);
        }
    </script>
@endpush

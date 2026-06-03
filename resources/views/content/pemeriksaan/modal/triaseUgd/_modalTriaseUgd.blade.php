<div class="modal modal-blur fade" id="modalTriaseUgd" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title m-0 text-white"><i class="ti ti-heartbeat me-2"></i>Asesmen Triase Pasien UGD (Klinik)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formTriaseUgd">
                    {{-- IDENTITAS --}}
                    <fieldset class="form-fieldset p-3 bg-light rounded mb-3">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <label for="triase_no_rawat" class="form-label fw-bold">No. Rawat</label>
                                <input type="text" class="form-control bg-white" id="triase_no_rawat" name="no_rawat" readonly>
                            </div>
                            <div class="col-lg-6 col-md-5 col-sm-12">
                                <label for="triase_nm_pasien" class="form-label fw-bold">Pasien</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-white w-25" id="triase_no_rkm_medis" name="no_rkm_medis" readonly>
                                    <input type="text" class="form-control bg-white w-75" id="triase_nm_pasien" name="nm_pasien" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <label for="triase_nip" class="form-label fw-bold">Petugas Triase</label>
                                <select class="form-select bg-white" name="nip" id="triase_nip">
                                    <option value="">Pilih Petugas</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    {{-- WAKTU & KELUHAN --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Waktu Kontak Awal</label>
                            <input type="datetime-local" class="form-control" name="tgl_triase" id="triase_tgl_triase" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status Rujukan</label>
                            <select class="form-select" name="rujukan" id="triase_rujukan" onchange="toggleRujukanInput()">
                                <option value="Tidak">Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-none" id="rujukan_dari_container">
                            <label class="form-label fw-bold">Rujukan Dari</label>
                            <input type="text" class="form-control" name="rujukan_dari" id="triase_rujukan_dari" placeholder="Nama Faskes Rujukan">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Keluhan Utama</label>
                            <textarea class="form-control" name="keluhan_utama" id="triase_keluhan_utama" rows="2" placeholder="Tuliskan keluhan utama pasien..."></textarea>
                        </div>
                    </div>

                    {{-- SURVEY PRIMER --}}
                    <div class="card mb-3">
                        <div class="card-header bg-dark text-white p-2">
                            <h6 class="card-title m-0 text-white"><i class="ti ti-search me-1"></i>Survey Primer & Kategori Triase</h6>
                        </div>
                        <div class="card-body p-2" id="survey_primer_container">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center align-middle m-0" style="table-layout: fixed; min-width: 800px;">
                                    <thead>
                                        <tr style="height: 45px;">
                                            <th class="bg-danger text-white w-25" style="font-size: 13px;">KATEGORI 1<br><span class="fw-bold">RESUSITASI</span></th>
                                            <th class="bg-danger text-white w-25" style="font-size: 13px;">KATEGORI 2<br><span class="fw-bold">EMERGENSI</span></th>
                                            <th class="bg-warning text-dark w-25" style="font-size: 13px;">KATEGORI 3<br><span class="fw-bold">URGENSI</span></th>
                                            <th class="bg-success text-white w-25" style="font-size: 13px;">KATEGORI 4<br><span class="fw-bold">NON URGENSI</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- RESPON AWAL --}}
                                        <tr>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">RESPON AWAL</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][respon][]" value="tidak_ada_respon" id="r_k1_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k1_1">Tidak ada respon</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][respon][]" value="merespon_nyeri" id="r_k1_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k1_2">Merespon nyeri</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][respon][]" value="kejang" id="r_k1_3"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k1_3">Kejang</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">RESPON AWAL</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][respon][]" value="merespon_suara" id="r_k2_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k2_1">Merespon suara</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fffbeb !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #b06000; border-bottom: 1px solid rgba(176, 96, 0, 0.2); padding-bottom: 2px;">RESPON AWAL</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][respon][]" value="sadar" id="r_k3_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k3_1">Sadar</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][respon][]" value="ku_lemah" id="r_k3_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k3_2">K/U Lemah</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #f0fbf4 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #137333; border-bottom: 1px solid rgba(19, 115, 51, 0.2); padding-bottom: 2px;">RESPON AWAL</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="4" name="survey_primer[k4][respon][]" value="sadar" id="r_k4_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k4_1">Sadar</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="4" name="survey_primer[k4][respon][]" value="ku_baik" id="r_k4_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="r_k4_2">K/U Baik</label></div>
                                            </td>
                                        </tr>
                                        {{-- JALAN NAFAS --}}
                                        <tr>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">JALAN NAFAS</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][nafas][]" value="obstruksi" id="n_k1_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="n_k1_1">Obstruksi</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">JALAN NAFAS</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][nafas][]" value="ancaman_obstruksi" id="n_k2_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="n_k2_1">Ancaman obstruksi</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fffbeb !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #b06000; border-bottom: 1px solid rgba(176, 96, 0, 0.2); padding-bottom: 2px;">JALAN NAFAS</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][nafas][]" value="bebas" id="n_k3_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="n_k3_1">Bebas</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #f0fbf4 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #137333; border-bottom: 1px solid rgba(19, 115, 51, 0.2); padding-bottom: 2px;">JALAN NAFAS</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="4" name="survey_primer[k4][nafas][]" value="bebas" id="n_k4_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="n_k4_1">Bebas</label></div>
                                            </td>
                                        </tr>
                                        {{-- PERNAFASAN --}}
                                        <tr>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">PERNAFASAN</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][pernafasan][]" value="henti_nafas" id="p_k1_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k1_1">Henti nafas</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][pernafasan][]" value="sesak_nafas_berat" id="p_k1_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k1_2">Sesak nafas berat</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][pernafasan][]" value="rr_kurang_10" id="p_k1_3"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k1_3">RR &lt; 10/mnt</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][pernafasan][]" value="rr_lebih_32" id="p_k1_4"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k1_4">RR &gt; 32/mnt</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][pernafasan][]" value="sianosis" id="p_k1_5"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k1_5">Sianosis</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">PERNAFASAN</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][pernafasan][]" value="sesak_nafas" id="p_k2_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k2_1">Sesak nafas</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][pernafasan][]" value="frek_nafas_lebih_32" id="p_k2_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k2_2">Frek. nafas &gt; 32/mnt</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fffbeb !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #b06000; border-bottom: 1px solid rgba(176, 96, 0, 0.2); padding-bottom: 2px;">PERNAFASAN</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][pernafasan][]" value="sesak_nafas" id="p_k3_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k3_1">Sesak nafas</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][pernafasan][]" value="frek_nafas_lebih_32" id="p_k3_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k3_2">Frek. nafas &gt; 32/mnt</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][pernafasan][]" value="normal" id="p_k3_3"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k3_3">Normal</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #f0fbf4 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #137333; border-bottom: 1px solid rgba(19, 115, 51, 0.2); padding-bottom: 2px;">PERNAFASAN</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="4" name="survey_primer[k4][pernafasan][]" value="normal" id="p_k4_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="p_k4_1">Normal</label></div>
                                            </td>
                                        </tr>
                                        {{-- SIRKULASI --}}
                                        <tr>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">SIRKULASI</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][sirkulasi][]" value="henti_jantung" id="s_k1_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k1_1">Henti jantung</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][sirkulasi][]" value="nadi_lemah" id="s_k1_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k1_2">Nadi lemah</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][sirkulasi][]" value="akral_dingin" id="s_k1_3"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k1_3">Akral dingin</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="1" name="survey_primer[k1][sirkulasi][]" value="crt_lebih_2" id="s_k1_4"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k1_4">CRT &gt; 2detik</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">SIRKULASI</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][sirkulasi][]" value="nadi_irreguler" id="s_k2_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k2_1">Nadi irreguler</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fffbeb !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #b06000; border-bottom: 1px solid rgba(176, 96, 0, 0.2); padding-bottom: 2px;">SIRKULASI</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][sirkulasi][]" value="nadi_kuat" id="s_k3_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k3_1">Nadi kuat</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #f0fbf4 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #137333; border-bottom: 1px solid rgba(19, 115, 51, 0.2); padding-bottom: 2px;">SIRKULASI</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="4" name="survey_primer[k4][sirkulasi][]" value="nadi_kuat" id="s_k4_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="s_k4_1">Nadi kuat</label></div>
                                            </td>
                                        </tr>
                                        {{-- PERHATIAN / PREDIKSI TIND --}}
                                        <tr>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;"></td>
                                            <td class="text-start p-2 align-top" style="background-color: #fdf2f2 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #c5221f; border-bottom: 1px solid rgba(197, 34, 31, 0.2); padding-bottom: 2px;">PERHATIAN!!!</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][tindakan][]" value="resiko_perburukan" id="t_k2_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k2_1">Resiko perburukan</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][tindakan][]" value="nyeri_berat" id="t_k2_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k2_2">Nyeri berat</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="2" name="survey_primer[k2][tindakan][]" value="gg_psikis_berat" id="t_k2_3"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k2_3">Gg. Psikis berat</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #fffbeb !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #b06000; border-bottom: 1px solid rgba(176, 96, 0, 0.2); padding-bottom: 2px;">PREDIKSI TIND. DI UGD</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][tindakan][]" value="ada_lebih_2_tanda" id="t_k3_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k3_1">Ada &ge; 2 tanda</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][tindakan][]" value="problem_kompleks" id="t_k3_2"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k3_2">Problem kompleks</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="3" name="survey_primer[k3][tindakan][]" value="klinis_stabil" id="t_k3_3"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k3_3">Klinis stabil</label></div>
                                            </td>
                                            <td class="text-start p-2 align-top" style="background-color: #f0fbf4 !important;">
                                                <div class="fw-bold mb-1" style="font-size: 10px; color: #137333; border-bottom: 1px solid rgba(19, 115, 51, 0.2); padding-bottom: 2px;">PREDIKSI TIND. DI UGD</div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" data-kategori="4" name="survey_primer[k4][tindakan][]" value="tidak_ada" id="t_k4_1"><label class="form-check-label fw-bold text-dark" style="font-size: 12px; cursor: pointer;" for="t_k4_1">Tidak ada</label></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-2 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold me-2">Keputusan Kategori Triase (Auto):</span>
                            </div>
                            <div style="width: 200px;">
                                <select class="form-select fw-bold text-center" name="skala_triase" id="triase_skala_triase" required>
                                    <option value="">Belum Ditentukan</option>
                                    <option value="Kategori 1" class="text-danger fw-bold">KATEGORI 1 (Resusitasi)</option>
                                    <option value="Kategori 2" class="text-danger fw-bold">KATEGORI 2 (Emergensi)</option>
                                    <option value="Kategori 3" class="text-warning fw-bold">KATEGORI 3 (Urgensi)</option>
                                    <option value="Kategori 4" class="text-success fw-bold">KATEGORI 4 (Non Urgensi)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ASESMEN NYERI, RESIKO JATUH & LUKA --}}
                    <div class="row g-3 mb-3">
                        {{-- ASESMEN NYERI & RESIKO JATUH (LEFT COLUMN) --}}
                        <div class="col-lg-7">
                            {{-- SKALA NYERI --}}
                            <div class="card mb-3 border-info">
                                <div class="card-header bg-info text-white p-2">
                                    <h6 class="card-title m-0 text-white"><i class="ti ti-mood-empty me-1"></i>Asesmen Skala Nyeri (0 - 10)</h6>
                                </div>
                                <div class="card-body p-3">
                                    <input type="hidden" name="skala_nyeri" id="triase_skala_nyeri" value="0">
                                    
                                    {{-- Hybrid Slider & Clickable Faces Layout --}}
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-8 col-sm-12">
                                            <label class="form-label small fw-bold mb-1">Tarik / Seret Skala (0 - 10):</label>
                                            <input type="range" class="form-range w-100" min="0" max="10" step="1" value="0" id="triase_skala_nyeri_slider" style="cursor: pointer;">
                                        </div>
                                        <div class="col-md-4 col-sm-12 text-center">
                                            <div class="bg-light border rounded py-1 px-2">
                                                <span class="fs-2 fw-bold text-success" id="pain_score_value">0</span>
                                                <div class="small fw-bold text-success" id="pain_score_desc" style="font-size: 10px; line-height: 1.1;">Tidak Nyeri</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between text-center gap-1 w-100">
                                        <div class="pain-face-card border rounded py-2 px-1" data-score="0" style="cursor: pointer; flex: 1 1 0px; min-width: 0; background: #e8f5e9; border-color: #2e7d32 !important;">
                                            <div style="font-size: 24px;">😊</div>
                                            <div class="fw-bold text-success">0</div>
                                            <small class="d-block text-wrap" style="font-size: 9px; line-height: 1.1;">Tidak Nyeri</small>
                                        </div>
                                        <div class="pain-face-card border rounded py-2 px-1" data-score="2" style="cursor: pointer; flex: 1 1 0px; min-width: 0;">
                                            <div style="font-size: 24px;">🙂</div>
                                            <div class="fw-bold text-success">2</div>
                                            <small class="d-block text-wrap" style="font-size: 9px; line-height: 1.1;">Ringan</small>
                                        </div>
                                        <div class="pain-face-card border rounded py-2 px-1" data-score="4" style="cursor: pointer; flex: 1 1 0px; min-width: 0;">
                                            <div style="font-size: 24px;">😐</div>
                                            <div class="fw-bold text-warning">4</div>
                                            <small class="d-block text-wrap" style="font-size: 9px; line-height: 1.1;">Sedang</small>
                                        </div>
                                        <div class="pain-face-card border rounded py-2 px-1" data-score="6" style="cursor: pointer; flex: 1 1 0px; min-width: 0;">
                                            <div style="font-size: 24px;">🙁</div>
                                            <div class="fw-bold text-warning">6</div>
                                            <small class="d-block text-wrap" style="font-size: 9px; line-height: 1.1;">Berat</small>
                                        </div>
                                        <div class="pain-face-card border rounded py-2 px-1" data-score="8" style="cursor: pointer; flex: 1 1 0px; min-width: 0;">
                                            <div style="font-size: 24px;">😢</div>
                                            <div class="fw-bold text-danger">8</div>
                                            <small class="d-block text-wrap" style="font-size: 9px; line-height: 1.1;">Sgt Berat</small>
                                        </div>
                                        <div class="pain-face-card border rounded py-2 px-1" data-score="10" style="cursor: pointer; flex: 1 1 0px; min-width: 0;">
                                            <div style="font-size: 24px;">😭</div>
                                            <div class="fw-bold text-danger">10</div>
                                            <small class="d-block text-wrap" style="font-size: 9px; line-height: 1.1;">Tidak Tertahankan</small>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Karakteristik Nyeri</label>
                                            <select class="form-select form-select-sm" name="nyeri_tipe" id="triase_nyeri_tipe">
                                                <option value="">- Tipe -</option>
                                                <option value="AKUT">AKUT (Baru/Tiba-tiba)</option>
                                                <option value="KRONIK">KRONIK (Menahun/Lama)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Lokasi Nyeri</label>
                                            <input type="text" class="form-control form-control-sm" name="nyeri_lokasi" id="triase_nyeri_lokasi" placeholder="Misal: Perut kanan bawah">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Durasi Nyeri</label>
                                            <input type="text" class="form-control form-control-sm" name="nyeri_durasi" id="triase_nyeri_durasi" placeholder="Misal: 3 jam">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RESIKO JATUH --}}
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark p-2">
                                    <h6 class="card-title m-0"><i class="ti ti-walk me-1"></i>Asesmen Resiko Jatuh</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Kategori Resiko</label>
                                            <select class="form-select" name="resiko_jatuh" id="triase_resiko_jatuh">
                                                <option value="Resiko rendah/ tidak beresiko">Resiko rendah / tidak beresiko</option>
                                                <option value="Resiko Sedang">Resiko Sedang</option>
                                                <option value="Resiko Tinggi">Resiko Tinggi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Skor Resiko Jatuh</label>
                                            <input type="number" class="form-control" name="resiko_jatuh_skor" id="triase_resiko_jatuh_skor" placeholder="Skor total">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- INTERACTIVE BODY MAP & LUKA (RIGHT COLUMN) --}}
                        <div class="col-lg-5">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white p-2">
                                    <h6 class="card-title m-0 text-white"><i class="ti ti-activity me-1"></i>Luka / Perdarahan (Body Map)</h6>
                                </div>
                                <div class="card-body p-2 text-center">
                                    <div class="position-relative border bg-white rounded" style="width: 320px; height: 320px; margin: 0 auto; overflow: hidden;">
                                        <img id="bodyMapImage" src="{{ asset('img/body_map.png') }}" style="width: 320px; height: 320px; object-fit: contain; pointer-events: none;" class="position-absolute start-50 top-50 translate-middle">
                                        <canvas id="bodyMapCanvas" width="320" height="320" class="position-absolute start-0 top-0" style="cursor: crosshair; z-index: 10;"></canvas>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 px-2">
                                        <small class="text-muted text-start" style="font-size: 10px;">Klik pada gambar untuk menandai pin lokasi luka.</small>
                                        <button type="button" class="btn btn-xs btn-outline-danger" onclick="clearBodyMap()"><i class="ti ti-trash me-1"></i>Reset</button>
                                    </div>
                                    <input type="hidden" name="body_map_points" id="triase_body_map_points">

                                    <div class="mt-3">
                                        <label class="form-label small fw-bold text-start d-block">Deskripsi Luka / Perdarahan</label>
                                        <textarea class="form-control form-control-sm" name="luka_perdarahan" id="triase_luka_perdarahan" rows="2" placeholder="Detail luka (misal: Luka robek 2cm)..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TINDAK LANJUT --}}
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white p-2">
                            <h6 class="card-title m-0 text-white"><i class="ti ti-direction me-1"></i>Rencana Tindak Lanjut / Tujuan Pelayanan</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tujuan / Tindak Lanjut</label>
                                    <select class="form-select" name="rencana_tindak_lanjut" id="triase_rencana_tindak_lanjut" onchange="toggleRujukTujuanInput()">
                                        <option value="Kamar Tindakan UGD">Kamar Tindakan UGD</option>
                                        <option value="Kamar jenazah/ dipulangkan segera">Kamar jenazah / dipulangkan segera</option>
                                        <option value="Poned">Poned</option>
                                        <option value="Rujuk">Rujuk ke RS / Faskes Lain</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-none" id="rujuk_tujuan_container">
                                    <label class="form-label fw-bold">Rumah Sakit Rujukan</label>
                                    <input type="text" class="form-control" name="rujuk_tujuan" id="triase_rujuk_tujuan" placeholder="Nama Rumah Sakit Tujuan Rujukan">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Keputusan Jam Pelayanan</label>
                                    <input type="time" class="form-control" name="keputusan_jam" id="triase_keputusan_jam">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-danger d-none" id="btn-hapus-triase" onclick="hapusTriaseUgd()">
                        <i class="ti ti-trash me-1"></i> Hapus
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-info d-none" id="btn-cetak-triase" onclick="cetakTriaseUgd()">
                        <i class="ti ti-printer me-1"></i> Cetak
                    </button>
                    <button type="button" class="btn btn-primary" onclick="simpanTriaseUgd()">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Triase UGD
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pain-face-card.active {
        background: #ffe0b2 !important;
        border-color: #f57c00 !important;
        transform: scale(1.05);
        transition: all 0.2s ease-in-out;
    }
</style>

@push('script')
<script>
    const modalTriaseUgd = $('#modalTriaseUgd');
    const uCanvas = document.getElementById('bodyMapCanvas');
    const uCtx = uCanvas.getContext('2d');
    let uPoints = [];

    // Initialize interactive canvas markers
    uCanvas.addEventListener('click', function(e) {
        const rect = uCanvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        uPoints.push({ x: Math.round(x), y: Math.round(y) });
        drawBodyMapPoints();
        $('#triase_body_map_points').val(JSON.stringify(uPoints));
    });

    function drawBodyMapPoints() {
        uCtx.clearRect(0, 0, uCanvas.width, uCanvas.height);
        uPoints.forEach((pt, index) => {
            // Draw red circle indicator
            uCtx.beginPath();
            uCtx.arc(pt.x, pt.y, 8, 0, 2 * Math.PI);
            uCtx.fillStyle = '#dc3545';
            uCtx.fill();
            uCtx.strokeStyle = '#ffffff';
            uCtx.lineWidth = 1.5;
            uCtx.stroke();
            
            // Draw point number
            uCtx.fillStyle = '#ffffff';
            uCtx.font = 'bold 9px Arial';
            uCtx.textAlign = 'center';
            uCtx.textBaseline = 'middle';
            uCtx.fillText(index + 1, pt.x, pt.y);
        });
    }

    function clearBodyMap() {
        uPoints = [];
        uCtx.clearRect(0, 0, uCanvas.width, uCanvas.height);
        $('#triase_body_map_points').val('');
    }

    // Toggle input rujukan
    function toggleRujukanInput() {
        if ($('#triase_rujukan').val() === 'Ya') {
            $('#rujukan_dari_container').removeClass('d-none');
        } else {
            $('#rujukan_dari_container').addClass('d-none');
            $('#triase_rujukan_dari').val('');
        }
    }

    // Toggle input rujuk tujuan
    function toggleRujukTujuanInput() {
        if ($('#triase_rencana_tindak_lanjut').val() === 'Rujuk') {
            $('#rujuk_tujuan_container').removeClass('d-none');
        } else {
            $('#rujuk_tujuan_container').addClass('d-none');
            $('#triase_rujuk_tujuan').val('');
        }
    }

    // Hybrid Pain Scale Logic
    function updatePainScore(val) {
        // Set the hidden input value
        $('#triase_skala_nyeri').val(val);
        $('#triase_skala_nyeri_slider').val(val);
        $('#pain_score_value').text(val);
        
        // Determine description, color, and closest face card mapping
        let desc = 'Tidak Nyeri';
        let colorClass = 'text-success';
        let closestScore = 0;
        
        if (val === 0) {
            desc = 'Tidak Nyeri';
            colorClass = 'text-success';
            closestScore = 0;
        } else if (val >= 1 && val <= 3) {
            desc = 'Ringan';
            colorClass = 'text-success';
            closestScore = 2;
        } else if (val >= 4 && val <= 5) {
            desc = 'Sedang';
            colorClass = 'text-warning';
            closestScore = 4;
        } else if (val >= 6 && val <= 7) {
            desc = 'Berat';
            colorClass = 'text-warning';
            closestScore = 6;
        } else if (val >= 8 && val <= 9) {
            desc = 'Sgt Berat';
            colorClass = 'text-danger';
            closestScore = 8;
        } else if (val === 10) {
            desc = 'Tidak Tertahankan';
            colorClass = 'text-danger';
            closestScore = 10;
        }
        
        $('#pain_score_value').removeClass('text-success text-warning text-danger').addClass(colorClass);
        $('#pain_score_desc').text(desc).removeClass('text-success text-warning text-danger').addClass(colorClass);
        
        // Style the active card dynamically
        $('.pain-face-card').removeClass('active').css('background', '').css('border-color', '');
        
        let activeCard = $(`.pain-face-card[data-score="${closestScore}"]`);
        activeCard.addClass('active');
        if (closestScore === 0 || closestScore === 2) {
            activeCard.css('background', '#e8f5e9').css('border-color', '#2e7d32');
        } else if (closestScore === 4 || closestScore === 6) {
            activeCard.css('background', '#fff8e1').css('border-color', '#f57f17');
        } else if (closestScore === 8 || closestScore === 10) {
            activeCard.css('background', '#ffebee').css('border-color', '#c62828');
        }
    }

    // Slider Event
    $('#triase_skala_nyeri_slider').on('input change', function() {
        updatePainScore(parseInt($(this).val()));
    });

    // Pain Faces Interaction
    $('.pain-face-card').on('click', function() {
        const score = parseInt($(this).data('score'));
        updatePainScore(score);
    });

    // Auto-triage calculation based on checkboxes checked
    $('#survey_primer_container').on('change', 'input[type="checkbox"]', function() {
        let maxKategori = 0;
        $('#survey_primer_container input[type="checkbox"]:checked').each(function() {
            const kat = parseInt($(this).data('kategori'));
            if (kat > maxKategori) {
                maxKategori = kat;
            }
        });

        if (maxKategori > 0) {
            $('#triase_skala_triase').val(`Kategori ${maxKategori}`).change();
        } else {
            $('#triase_skala_triase').val('').change();
        }
    });

    // Fetch Petugas lists
    function getPetugasTriase() {
        return $.get("{{ url('/petugas/data') }}");
    }

    // Open Triage Modal
    function openTriaseUgd(no_rawat) {
        // Reset form
        $('#formTriaseUgd').trigger('reset');
        clearBodyMap();
        updatePainScore(0);
        $('#rujukan_dari_container').addClass('d-none');
        $('#rujuk_tujuan_container').addClass('d-none');

        // Load patient details
        getRegDetail(no_rawat).done((response) => {
            $('#triase_no_rawat').val(response.no_rawat);
            $('#triase_no_rkm_medis').val(response.no_rkm_medis);
            $('#triase_nm_pasien').val(response.pasien.nm_pasien);

            // Populate Petugas select
            getPetugasTriase().done((petugases) => {
                let html = '<option value="">Pilih Petugas</option>';
                petugases.forEach(p => {
                    html += `<option value="${p.nip}">${p.nama}</option>`;
                });
                $('#triase_nip').html(html);

                // Default NIP to currently logged in staff if any
                const loggedInNik = "{{ session()->has('pegawai') ? session()->get('pegawai')->nik : '' }}";
                if (loggedInNik) {
                    $('#triase_nip').val(loggedInNik).trigger('change');
                }
            });

            // Fetch existing data
            $.get("{{ url('/rm/triase/ugd') }}", { no_rawat: no_rawat }).done((res) => {
                if (res) {
                    $('#btn-hapus-triase').removeClass('d-none');
                    $('#btn-cetak-triase').removeClass('d-none');
                    $('#triase_tgl_triase').val(res.tgl_triase ? res.tgl_triase.replace(' ', 'T').substring(0, 16) : '');
                    $('#triase_rujukan').val(res.rujukan).change();
                    $('#triase_rujukan_dari').val(res.rujukan_dari);
                    $('#triase_keluhan_utama').val(res.keluhan_utama);
                    $('#triase_skala_triase').val(res.skala_triase).change();
                    $('#triase_resiko_jatuh').val(res.resiko_jatuh).change();
                    $('#triase_resiko_jatuh_skor').val(res.resiko_jatuh_skor);
                    $('#triase_luka_perdarahan').val(res.luka_perdarahan);
                    $('#triase_rencana_tindak_lanjut').val(res.rencana_tindak_lanjut).change();
                    $('#triase_rujuk_tujuan').val(res.rujuk_tujuan);
                    $('#triase_keputusan_jam').val(res.keputusan_jam ? res.keputusan_jam.substring(0, 5) : '');
                    
                    if (res.nip) {
                        $('#triase_nip').val(res.nip).trigger('change');
                    }

                    // Pain Scale
                    if (res.skala_nyeri !== null && res.skala_nyeri !== undefined) {
                        updatePainScore(parseInt(res.skala_nyeri));
                    } else {
                        updatePainScore(0);
                    }
                    $('#triase_nyeri_tipe').val(res.nyeri_tipe);
                    $('#triase_nyeri_lokasi').val(res.nyeri_lokasi);
                    $('#triase_nyeri_durasi').val(res.nyeri_durasi);

                    // Body Map Points
                    if (res.body_map_points) {
                        uPoints = Array.isArray(res.body_map_points) ? res.body_map_points : JSON.parse(res.body_map_points);
                        drawBodyMapPoints();
                        $('#triase_body_map_points').val(JSON.stringify(uPoints));
                    }

                    // Survey Primer checkboxes
                    if (res.survey_primer) {
                        const survey = typeof res.survey_primer === 'string' ? JSON.parse(res.survey_primer) : res.survey_primer;
                        
                        // Loop through all keys and set checkboxes checked
                        Object.keys(survey).forEach(kat => {
                            Object.keys(survey[kat]).forEach(sub => {
                                const vals = survey[kat][sub];
                                if (Array.isArray(vals)) {
                                    vals.forEach(val => {
                                        $(`#survey_primer_container input[name="survey_primer[${kat}][${sub}][]"][value="${val}"]`).prop('checked', true);
                                    });
                                }
                            });
                        });
                    }
                } else {
                    $('#btn-hapus-triase').addClass('d-none');
                    $('#btn-cetak-triase').addClass('d-none');
                    // Set default current time for new triage
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    $('#triase_tgl_triase').val(now.toISOString().slice(0, 16));
                    
                    const timeString = now.toTimeString().slice(0, 5);
                    $('#triase_keputusan_jam').val(timeString);
                }
            });

            modalTriaseUgd.modal('show');
        });
    }

    // Save Data
    function simpanTriaseUgd() {
        const no_rawat = $('#triase_no_rawat').val();
        if (!no_rawat) {
            Swal.fire('Error', 'Nomor rawat tidak teridentifikasi.', 'error');
            return;
        }

        loadingAjax('Menyimpan data triase UGD...');

        const data = $('#formTriaseUgd').serializeArray();
        
        $.post("{{ url('/rm/triase/ugd') }}", data).done((response) => {
            alertSuccessAjax();
            if (document.activeElement) {
                document.activeElement.blur();
            }
            modalTriaseUgd.modal('hide');

            // Change color of CPPT button on registration table
            const row = $(`.rows-registrasi[data-id="${no_rawat}"]`);
            const btnCppt = row.find('button[onclick^="showCpptRalan"]');
            if (btnCppt.length) {
                btnCppt.removeClass('btn-outline-primary').addClass('btn-success');
            }
        }).fail((err) => {
            alertErrorAjax(err);
        });
    }

    function cetakTriaseUgd() {
        const no_rawat = $('#triase_no_rawat').val();
        if (!no_rawat) {
            Swal.fire('Error', 'Nomor rawat tidak teridentifikasi.', 'error');
            return;
        }
        window.open("{{ url('/rm/triase/ugd/print') }}?no_rawat=" + encodeURIComponent(no_rawat), '_blank');
    }

    function hapusTriaseUgd() {
        const no_rawat = $('#triase_no_rawat').val();
        if (!no_rawat) {
            Swal.fire('Error', 'Nomor rawat tidak teridentifikasi.', 'error');
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data triase UGD untuk nomor rawat ' + no_rawat + ' akan dihapus permanently!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Menghapus data triase UGD...');
                $.post("{{ url('/rm/triase/ugd/delete') }}", {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    no_rawat: no_rawat
                }).done((response) => {
                    alertSuccessAjax('Data triase UGD berhasil dihapus').then(() => {
                        if (document.activeElement) {
                            document.activeElement.blur();
                        }
                        modalTriaseUgd.modal('hide');
                        // Reset CPPT button color if exists
                        const row = $(`.rows-registrasi[data-id="${no_rawat}"]`);
                        const btnCppt = row.find('button[onclick^="showCpptRalan"]');
                        if (btnCppt.length) {
                            btnCppt.removeClass('btn-success').addClass('btn-outline-primary');
                        }
                    });
                }).fail((err) => {
                    alertErrorAjax(err);
                });
            }
        });
    }
</script>
@endpush

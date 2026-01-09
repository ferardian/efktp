<div class="row">
    <div class="col-12">
        <form id="formHasilUsg">
            <div class="row" id="infoPasienUsg">
                <div class="col-md-12 col-xl-2 col-lg-2">
                    <div class="mb-1">
                        <label class="form-label">No. Rawat</label>
                        <input value="-" type="text" class="form-control" name="no_rawat" readonly>
                    </div>
                </div>

                <div class="col-md-12 col-xl-2 col-lg-3">
                    <div class="mb-1">
                        <label class="form-label">Pasien</label>
                        <div class="input-group mb-2">
                            <input value="-" type="text" class="form-control" name="no_rkm_medis" readonly>
                            <input value="-" type="text" class="form-control w-50" name="nm_pasien" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-xl-2 col-lg-2">
                    <div class="mb-1">
                        <label class="form-label">Tgl. Lahir / Umur</label>
                        <input value="-" type="text" class="form-control" name="tgl_lahir" readonly>
                    </div>
                </div>

                <div class="col-md-12 col-xl-2 col-lg-3">
                    <div class="mb-1">
                        <label class="form-label">Alamat</label>
                        <input value="-" type="text" class="form-control" name="alamat" readonly>
                    </div>
                </div>

                <div class="col-md-12 col-xl-2 col-lg-3">
                    <div class="mb-1">
                        <label for="png_jawab">Keluarga</label>
                        <div class="input-group">
                            <input class="form-control" type="text" id="hubunganpj" name="hubunganpj" readonly>
                            <input class="form-control w-25" type="text" id="p_jawab" name="p_jawab" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-xl-2 col-lg-2">
                    <div class="mb-1">
                        <label class="form-label">Pembiayaan</label>
                        <div class="input-group mb-2">
                            <input value="-" type="text" class="form-control" name="pembiayaan" readonly>
                            <input value="-" type="text" class="form-control w-50" name="no_peserta" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OBSTETRI -->
            <div class="col-lg-12 col-xl-12">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-fieldset">
                            <div class="row mb-3">
                                <div class="col-xl-9 col-lg-9 col-md-12">
                                    <label for="kd_dokter">Dokter</label>
                                    <div class="input-group">
                                        <input class="form-control" id="kd_dokter" name="kd_dokter" />
                                        <input class="form-control w-50" id="nm_dokter" name="nm_dokter" />
                                        <button type="button" class="btn btn-danger btn-delete-usg" disabled>
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>
                            <h5 class="mb-3">OBSTETRI</h5>
                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="janin" class="form-label required">JANIN</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="janin" name="janin">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Tunggal, Intraurterin">Tunggal, Intraurterin</option>
                                        <option value="Tunggal, Ekstraurterin">Tunggal, Ekstraurterin</option>
                                        <option value="Kembar, Intraurterin">Kembar, Intraurterin</option>
                                        <option value="Kembar, Ekstraurterin">Kembar, Ekstraurterin</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="presentasi" class="form-label">Presentasi</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="presentasi" name="presentasi">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Kepala">Kepala</option>
                                        <option value="Melintang">Melintang</option>
                                        <option value="Bokong">Bokong</option>
                                        <option value="Oblique">Oblique</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="letak_punggung" class="form-label">Letak
                                        Punggung</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="letak_punggung" name="letak_punggung">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Kanan">Kanan</option>
                                        <option value="Kiri">Kiri</option>
                                        <option value="Atas">Atas</option>
                                        <option value="Bawah">Bawah</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="djj" class="form-label required">DJJ</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="DJJ" name="DJJ">
                                        <span class="input-group-text">x/mnt</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-2 col-lg-2">
                                    <label for="TBJ" class="form-label">TBJ</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="TBJ" name="TBJ">
                                        <span class="input-group-text">gram</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4 col-lg-4">
                                    <label class="form-label">Jenis Kelamin</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin"
                                            id="jenis_kelaminL" value="L">
                                        <label class="form-check-label" for="jenis_kelaminL">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin"
                                            id="jenis_kelaminP" value="P">
                                        <label class="form-check-label" for="jenis_kelaminP">Perempuan</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin"
                                            id="jenis_kelamin_x" value="-">
                                        <label class="form-check-label" for="jenis_kelamin_x">Belum Jelas</label>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="letak_plasenta" class="form-label">Letak
                                        Plasenta</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="letak_plasenta" name="letak_plasenta">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Corpus Bawah">Corpus Bawah</option>
                                        <option value="SBR">SBR</option>
                                        <option value="Fundus">Fundus</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="ketuban" class="form-label required">Air Ketuban</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="ketuban" name="ketuban">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Cukup">Cukup</option>
                                        <option value="Kurang">Kurang</option>
                                        <option value="Sedikit">Sedikit</option>
                                        <option value="Polihidromnion">Polihidromnion</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-2 col-lg-3">
                                    <label for="HPHT" class="form-label">HPHT</label>
                                    <input type="date" class="form-control" id="HPHT" name="HPHT">
                                </div>
                                <div class="col-md-6 col-xl-2 col-lg-3">
                                    <label for="HPL" class="form-label">HPL</label>
                                    <input type="date" class="form-control" id="HPL" name="HPL">
                                </div>
                                <div class="col-md-6 col-xl-2 col-lg-2 ">
                                    <label for="umur_kehamilan" class="form-label">Umur
                                        Kehamilan</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="umur_kehamilan"
                                            name="umur_kehamilan"
                                            value="">
                                        <button type="button" class="btn btn-primary" id="btnHitungUmurHamil">
                                            <i class="ti ti-reload"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="kelainan_kongenital" class="form-label">Kelainan
                                        Kongenital</label>
                                    <input type="text" class="form-control" id="kelainan_kongenital"
                                        name="kelainan_kongenital">
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="lain_lain" class="form-label">Lain-lain</label>
                                    <input type="text" class="form-control" id="lain_lain" name="lain_lain">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pemeriksaan_fisik_tambahan" class="form-label">Pemeriksaan Fisik
                                    Tambahan</label>
                                <textarea class="form-control" id="pemeriksaan_fisik_tambahan"
                                    name="pemeriksaan_fisik_tambahan"
                                    rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-fieldset  d-none" id="formUsgKembar">
                            <h5 class="mb-3">JANIN 2</h5>
                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="presentasi2" class="form-label">Presentasi</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="presentasi2" name="presentasi2">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Kepala">Kepala</option>
                                        <option value="Melintang">Melintang</option>
                                        <option value="Bokong">Bokong</option>
                                        <option value="Oblique">Oblique</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="letak_punggung2" class="form-label">Letak Punggung</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="letak_punggung2" name="letak_punggung2">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Kanan">Kanan</option>
                                        <option value="Kiri">Kiri</option>
                                        <option value="Atas">Atas</option>
                                        <option value="Bawah">Bawah</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="DJJ2" class="form-label">DJJ</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="DJJ2" name="DJJ2">
                                        <span class="input-group-text">x/mnt</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-4 col-lg-4">
                                    <label class="form-label">Jenis Kelamin</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin2"
                                            id="jenis_kelaminL2" value="L">
                                        <label class="form-check-label" for="jenis_kelaminL2">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin2"
                                            id="jenis_kelaminP2" value="P">
                                        <label class="form-check-label" for="jenis_kelaminP2">Perempuan</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin2"
                                            id="jenis_kelamin_x2" value="-" checked>
                                        <label class="form-check-label" for="jenis_kelamin_x2">Belum Jelas</label>
                                    </div>

                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="letak_plasenta2" class="form-label">Letak Plasenta</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="letak_plasenta2" name="letak_plasenta2">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Corpus Bawah">Corpus Bawah</option>
                                        <option value="SBR">SBR</option>
                                        <option value="Fundus">Fundus</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="ketuban2" class="form-label required">Air Ketuban</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt"
                                        id="ketuban2" name="ketuban2">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Cukup">Cukup</option>
                                        <option value="Kurang">Kurang</option>
                                        <option value="Sedikit">Sedikit</option>
                                        <option value="Polihidromnion">Polihidromnion</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-2 col-lg-2">
                                    <label for="umur_kehamilan2" class="form-label">Usia Kehamilan</label>
                                    <input type="text" class="form-control" id="umur_kehamilan2"
                                        name="umur_kehamilan2"
                                        value="">

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="tbj" class="form-label">TBJ</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="TBJ2" name="TBJ2">
                                        <span class="input-group-text">gram</span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="HPL2" class="form-label">HPL</label>
                                    <input type="date" class="form-control" id="HPL2" name="HPL2">
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="kelainan_kongenital2" class="form-label">Kelainan
                                        Kongenital</label>
                                    <input type="text" class="form-control" id="kelainan_kongenital"
                                        name="kelainan_kongenital2">
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="lain_lain2" class="form-label">Lain-lain</label>
                                    <input type="text" class="form-control" id="lain_lain2" name="lain_lain2">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pemeriksaan_fisik_tambahan2" class="form-label">Pemeriksaan Fisik
                                    Tambahan</label>
                                <textarea class="form-control" id="pemeriksaan_fisik_tambahan2"
                                    name="pemeriksaan_fisik_tambahan2"
                                    rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6 col-xl-6 col-sm-12 ">
                        <div class="form-fieldset" id="fieldsetGynecologi">
                            <h5 class="mb-3">GYNECOLOGI</h5>
                            <div class="row mb-3">
                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="GS" class="form-label">GS</label>
                                    <select class="form-select-2" data-dropdown-parent="#modalCppt" id="GS"
                                        name="GS">
                                        <option value="" selected disabled>::Pilih::</option>
                                        <option value="Tunggal, Intrauterin">Tunggal, Intrauterin</option>
                                        <option value="Ganda, Intrauterin">Ganda, Intrauterin</option>
                                        <option value="Tunggal, Ekstrauterin">Tunggal, Ekstrauterin</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="umur_kehamilan_gs" class="form-label">Usia
                                        Kehamilan</label>
                                    <input type="text" class="form-control" id="umur_kehamilan_gs" value=""
                                        name="umur_kehamilan_gs">

                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="fetalpole" class="form-label">Fetal Pole</label>
                                    <input type="text" class="form-control" id="fetalpole" name="fetalpole"
                                        value="">
                                </div>

                                <div class="col-md-6 col-xl-3 col-lg-3">
                                    <label for="pulsasi" class="form-label">Pulsasi</label>
                                    <input type="text" class="form-control" id="pulsasi" name="pulsasi"
                                        value="">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="lain" class="form-label">Lain</label>
                                <input type="text" class="form-control" id="lain" name="lain" autocomplete="">
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">Riwayat USG</div>
                                <div class="accordion accordion-tabs" id="riwayatUsgContainer"
                                    style="max-height: 40vh;overflow:auto">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/template">
    <div class="accordion-item">
        <div class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse-2-tabs"
                    aria-expanded="false">
                How can I customize Tabler components to fit my design needs?
            </button>
        </div>
        <div id="collapse-2-tabs" class="accordion-collapse collapse"
             data-bs-parent="#riwayatUsgContainer" style="">
            <div class="accordion-body">
                <h5 class="">OBSTETRI</h5>
                <div class="datagrid grid-3" id="">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Tgl. Pemeriksaan</div>
                        <span class="datagrid-content tgl_pereiksa">4 Oktober 2025
                            08:19:47
                        </span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">No. Rawat</div>
                        <span class="datagrid-content no_rawat" id="">
                        </span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Poliklinik</div>
                        <span class="datagrid-content poliklinik"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Janin</div>
                        <span class="datagrid-content janin">000004</span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Presentasi</div>
                        <span class="datagrid-content presentasi">Presentasi</span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Letak Punggung</div>
                        <span class="datagrid-content letak_punggung"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">DJJ</div>
                        <span class="datagrid-content DJJ"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Jenis Kelamin</div>
                        <span class="datagrid-content jenis_kelamin"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Letak Plasenta</div>
                        <span class="datagrid-content letak_plasenta"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Air Ketuban</div>
                        <span class="datagrid-content ketuban"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">HPHT</div>
                        <span class="datagrid-content HPHT"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">HPL</div>
                        <span class="datagrid-content HPL"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Umur Kehamilan</div>
                        <span class="datagrid-content umur_kehamilan"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Kelainan Kongenital</div>
                        <span class="datagrid-content kelainan_kongenital"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Lain-lain</div>
                        <span class="datagrid-content lain_lain"></span>
                    </div>
                    <div class="datagrid-item span-3">
                        <div class="datagrid-title">Pemeriksaan Fisik Tambahan</div>
                        <span class="datagrid-content pemeriksaan_fisik_tambahan"></span>
                    </div>

                </div>
                <div class="datagrid grid-3 hasilKembar d-none mt-3">
                    <!-- Grid khusus jika janin kembar -->
                    <div class="datagrid-item">
                        <div class="datagrid-title">DJJ</div>
                        <span class="datagrid-content DJJ2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Letak Punggung</div>
                        <span class="datagrid-content letak_punggung2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Letak Plasenta</div>
                        <span class="datagrid-content letak_plasenta2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Jenis Kelamin</div>
                        <span class="datagrid-content jenis_kelamin2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">TBJ 2</div>
                        <span class="datagrid-content TBJ2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Kelainan Kongenital</div>
                        <span class="datagrid-content kelainan_kongenital2"></span>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">HPL</div>
                        <span class="datagrid-content HPL2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Umur Kehamilan</div>
                        <span class="datagrid-content umur_kehamilan2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Kelainan Kongenital</div>
                        <span class="datagrid-content kelainan_kongenital2"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Lain-lain</div>
                        <span class="datagrid-content lain_lain2"></span>
                    </div>
                    <div class="datagrid-item span-2">
                        <div class="datagrid-title">Pemeriksaan Fisik Tambahan</div>
                        <span class="datagrid-content pemeriksaan_fisik_tambahan2"></span>
                    </div>
                </div>
                <h5 class="mt-3">GYNECOLOGY</h5>
                <div class="datagrid grid-3 ">
                    <div class="datagrid-item">
                        <div class="datagrid-title">GS</div>
                        <span class="datagrid-content GS"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Umur Kehamilan</div>
                        <span class="datagrid-content umur_kehamilan_gs"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Fetalpole</div>
                        <span class="datagrid-content fetalpole"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Pulsasi</div>
                        <span class="datagrid-content pulsasi"></span>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Lain-lain</div>
                        <span class="datagrid-content lain"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>


@push('script')
    <script>
        const targetHasilUsg = $('#modalCppt').find('a[href="#tabsHasilUsg"]');
        const btnCreateHasilUsg = $('#btnCreateHasilUsg');
        targetHasilUsg.on('shown.bs.tab', (e) => {
            const infoPasienUsg = $('#infoPasienUsg')
            const no_rawat = formCpptRajal.find('input[name=no_rawat]').val();
            $('#btnCreateHasilUsg').removeClass('d-none')
            $('#btnSimpanCppt').addClass('d-none')
            $('#btnCreateTindakan').addClass('d-none')
            getRegDetail(no_rawat).done((response) => {
                setHasilUsg(no_rawat)
                const {
                    pasien
                } = response;
                riwayatHasilUsg(response.no_rkm_medis)

                infoPasienUsg.find('input[name=no_rawat]').val(no_rawat)
                infoPasienUsg.find('input[name=nm_pasien]').val(pasien.nm_pasien);
                infoPasienUsg.find('input[name=no_rkm_medis]').val(response.no_rkm_medis);
                infoPasienUsg.find('input[name=alamat]').val(`${pasien.alamat}, ${pasien.kel.nm_kel}, ${pasien.kec.nm_kec}`);
                infoPasienUsg.find('input[name=pembiayaan]').val(response.penjab.png_jawab)
                infoPasienUsg.find('input[name=no_peserta]').val(pasien.no_peserta)
                infoPasienUsg.find('input[name=p_jawab]').val(response.p_jawab)
                infoPasienUsg.find('input[name=hubunganpj]').val(response.hubunganpj)

                const umurdaftar = formatUmurDaftar(hitungUmurDaftar(pasien.tgl_lahir, response.tgl_registrasi))
                infoPasienUsg.find('input[name=tgl_lahir]').val(`${formatTanggal(pasien.tgl_lahir)} / ${umurdaftar}`);

                formHasilUsg.find('input[name=kd_dokter]').val(response.kd_dokter);
                formHasilUsg.find('input[name=nm_dokter]').val(response.dokter.nm_dokter);
            })


        })
        targetHasilUsg.on('hidden.bs.tab', (e) => {
            formHasilUsg.trigger('reset');
        })

        function setHasilUsg(no_rawat) {
            $.get(`{{ url('/hasil-usg') }}`, {
                no_rawat: no_rawat
            }).done((response) => {
                const data = response.data
                const formUsgKembar = $('#formUsgKembar')
                const btnDeleteUsg = $('.btn-delete-usg')


                if (data === null) {
                    btnCreateHasilUsg.addClass('btn-success').removeClass('btn-primary')
                    btnDeleteUsg.prop('disabled', true).removeAttr('onclick')

                    return false;

                }
                btnDeleteUsg.prop('disabled', false).attr('onclick', `deleteHasilUsg('${no_rawat}')`)

                btnCreateHasilUsg.removeClass('btn-success').addClass('btn-primary')
                formHasilUsg.find('select[name=janin]').val(data.janin).change()
                formHasilUsg.find('select[name=presentasi]').val(data.presentasi).change()
                formHasilUsg.find('select[name=letak_punggung]').val(data.letak_punggung).change()
                formHasilUsg.find('select[name=letak_plasenta').val(data.letak_plasenta).change()
                formHasilUsg.find('select[name=ketuban').val(data.ketuban).change()

                formHasilUsg.find('input[name=DJJ]').val(data.DJJ)
                formHasilUsg.find(`input[name=jenis_kelamin][value="${data.jenis_kelamin}"`).prop('checked', true)
                formHasilUsg.find('input[name=umur_kehamilan').val(data.umur_kehamilan)
                formHasilUsg.find('input[name=HPHT').val(data.HPHT)
                formHasilUsg.find('input[name=HPL').val(data.HPL)
                formHasilUsg.find('input[name=TBJ').val(data.TBJ)
                formHasilUsg.find('input[name=kelainan_kongenital').val(data.kelainan_kongenital)
                formHasilUsg.find('input[name=lain_lain').val(data.lain_lain)
                formHasilUsg.find('textarea[name=pemeriksaan_fisik_tambahan').val(data.pemeriksaan_fisik_tambahan)

                formHasilUsg.find('select[name=GS]').val(data.GS).change()
                formHasilUsg.find('input[name=umur_kehamilan_gs]').val(data.umur_kehamilan_gs)
                formHasilUsg.find('input[name=fetalpole]').val(data.fetalpole)
                formHasilUsg.find('input[name=pulsasi]').val(data.pulsasi)
                formHasilUsg.find('input[name=lain]').val(data.lain)


                if (data.janin.includes('Kembar')) {
                    formUsgKembar.removeClass('d-none')
                    formHasilUsg.find('select[name=presentasi2]').val(data.presentasi2).change()
                    formHasilUsg.find('select[name=letak_punggung2]').val(data.letak_punggung2).change()
                    formHasilUsg.find('select[name=letak_plasenta2').val(data.letak_plasenta2).change()
                    formHasilUsg.find('select[name=ketuban2').val(data.ketuban2).change()

                    formHasilUsg.find('input[name=DJJ2]').val(data.DJJ)
                    formHasilUsg.find(`input[name=jenis_kelamin2][value="${data.jenis_kelamin2}"`).prop('checked', true).change()
                    formHasilUsg.find('input[name=umur_kehamilan2').val(data.umur_kehamilan2)
                    formHasilUsg.find('input[name=HPL2').val(data.HPL2)
                    formHasilUsg.find('input[name=TBJ2').val(data.TBJ2)
                    formHasilUsg.find('input[name=kelainan_kongenital2').val(data.kelainan_kongenital2)
                    formHasilUsg.find('input[name=lain_lain2').val(data.lain_lain2)
                    formHasilUsg.find('textarea[name=pemeriksaan_fisik_tambahan2').val(data.pemeriksaan_fisik_tambahan2)
                }

            })
        }

        const formHasilUsg = $('#formHasilUsg')
        const selectJanin = formHasilUsg.find('#janin')

        selectJanin.on('select2:select', (e) => {
            const value = e.params.data.id
            const isKembar = value.includes('Kembar')
            const formUsgKembar = $('#formUsgKembar')

            if (isKembar) {
                formUsgKembar.removeClass('d-none')
            } else {
                formUsgKembar.addClass('d-none')
                formUsgKembar.find('input').val("")
                formUsgKembar.find('textarea').val("")
                formUsgKembar.find('select').val("")
            }

        })

        btnCreateHasilUsg.on('click', (e) => {
            const no_rawat = formHasilUsg.find('input[name=no_rawat]').val()
            const data = getDataForm('formHasilUsg', ['input', 'textarea', 'select']);

            data['jenis_kelamin'] = formHasilUsg.find('input[type=radio][name=jenis_kelamin]:checked').val()
            data['jenis_kelamin2'] = formHasilUsg.find('input[type=radio][name=jenis_kelamin2]:checked').val()

            const isKembar = data['janin']?.includes('Kembar')

            if (!isKembar) {
                data['jenis_kelamin2'] = null
            }

            $.post(`{{ url('/hasil-usg') }}`, data).done((response) => {
                showToast('Hasil USG berhasil')
                setHasilUsg(no_rawat)
                riwayatHasilUsg(data['no_rkm_medis'])
            }).fail((result) => {
                showToast(result.responseJSON.message, 'error', 10000)
            })
        })

        formHasilUsg.find('input[name=HPHT]').on('change', (e) => {
            const tglHpht = e.currentTarget.value
            const hpht = hitungHPL(tglHpht);
            const umurKehamilan = hitungUsiaKehamilan(tglHpht)
            const janin = formHasilUsg.find('select[name=janin]').val();

            formHasilUsg.find('input[name=HPL]').val(hpht)
            formHasilUsg.find('input[name=umur_kehamilan]').val(umurKehamilan)
            formHasilUsg.find('input[name=umur_kehamilan_gs]').val(umurKehamilan)

            if (janin && janin.includes('Kembar')) {
                formHasilUsg.find('input[name=umur_kehamilan2]').val(umurKehamilan)
                formHasilUsg.find('input[name=HPL2]').val(hpht)
            } else {
                formHasilUsg.find('input[name=HPL2]').val(null)
                formHasilUsg.find('input[name=umur_kehamilan2]').val(null)
            }
        })

        function riwayatHasilUsg(no_rkm_medis) {
            $.get(`riwayat-hasil-usg/${no_rkm_medis}`)
                .done((response) => {
                    showToast('Sukses Memuat Riwayat USG');
                    const {
                        data
                    } = response

                    // pastikan response adalah array
                    if (!Array.isArray(data) || data.length === 0) {
                        $('#riwayatUsgContainer').html('<div class="text-muted p-3">Tidak ada data riwayat USG</div>');
                        return;
                    }

                    // ambil isi template
                    const template = $('script[type="text/template"]').html();
                    const container = $('#riwayatUsgContainer');
                    container.empty(); // bersihkan isi sebelumnya


                    data.forEach((item, index) => {
                        // clone template
                        let html = $(template);

                        // update id collapse agar unik
                        const collapseId = `collapse-${index + 1}-tabs`;
                        html.find('.accordion-button')
                            .attr('data-bs-target', `#${collapseId}`)
                            .attr('aria-controls', collapseId)
                            .text(`${formatTanggal(item.tgl_periksa)} — ${item.dokter.nm_dokter ?? 'Tanpa Dokter'}`);
                        html.find('.accordion-collapse').attr('id', collapseId);

                        // isi setiap field dari data
                        html.find('.tgl_pereiksa').text(formatTanggal(item.tgl_periksa) ?? '-');
                        html.find('.no_rawat').text(item.no_rawat ?? '-');
                        html.find('.poliklinik').text(item.reg_periksa.poliklinik.nm_poli ?? '-');
                        html.find('.janin').text(item.janin ?? '-');
                        html.find('.presentasi').text(item.presentasi ?? '-');
                        html.find('.letak_punggung').text(item.letak_punggung ?? '-');
                        html.find('.DJJ').text(item.DJJ ?? '-');
                        html.find('.jenis_kelamin').text(item.jenis_kelamin ?? '-');
                        html.find('.letak_plasenta').text(item.letak_plasenta ?? '-');
                        html.find('.ketuban').text(item.ketuban ?? '-');
                        html.find('.HPHT').text(item.HPHT ? formatTanggal(item.HPHT) : '-');
                        html.find('.HPL').text(item.HPL ? formatTanggal(item.HPL) : '-');
                        html.find('.umur_kehamilan').text(item.umur_kehamilan ?? '-');
                        html.find('.kelainan_kongenital').text(item.kelainan_kongenital ?? '-');
                        html.find('.lain_lain').text(item.lain_lain ?? '-');
                        html.find('.pemeriksaan_fisik_tambahan').text(item.pemeriksaan_fisik_tambahan ?? '-');

                        // bagian GS dan lainnya
                        html.find('.GS').first().text(item.GS ?? '-');
                        html.find('.umur_kehamilan_gs').text(item.umur_kehamilan_gs ?? '-');
                        html.find('.fetalpole').text(item.fetalpole ?? '-');
                        html.find('.pulsasi').text(item.pulsasi ?? '-');
                        html.find('.lain').text(item.lain ?? '-');

                        // jika janin kembar → tampilkan grid khusus
                        if (item.janin && item.janin.toLowerCase().includes('kembar')) {
                            html.find('.hasilKembar').removeClass('d-none');
                            html.find('.presentasi2').text(item.presentasi2 ?? '-');
                            html.find('.letak_punggung2').text(item.letak_punggung2 ?? '-');
                            html.find('.DJJ2').text(item.DJJ2 ?? '-');
                            html.find('.jenis_kelamin2').text(item.jenis_kelamin2 ?? '-');
                            html.find('.letak_plasenta2').text(item.letak_plasenta2 ?? '-');
                            html.find('.ketuban2').text(item.ketuban2 ?? '-');
                            html.find('.HPL2').text(item.HPL2 ? formatTanggal(item.HPL2) : '-');
                            html.find('.umur_kehamilan2').text(item.umur_kehamilan2 ?? '-');
                            html.find('.kelainan_kongenital2').text(item.kelainan_kongenital2 ?? '-');
                            html.find('.lain_lain2').text(item.lain_lain2 ?? '-');
                            html.find('.pemeriksaan_fisik_tambahan2').text(item.pemeriksaan_fisik_tambahan2 ?? '-');
                        } else {
                            html.find('.hasilKembar').addClass('d-none');
                        }


                        // append ke container utama
                        container.append(html);
                    });
                })
                .fail((result) => {
                    showToast('Gagal', 'error');
                    console.log('ERROR ===', result);
                });
        }

        function deleteHasilUsg(no_rawat) {

            Swal.fire({
                title: 'Hapus Hasil USG!',
                icon: 'warning',
                text: 'Hasil yang dihapus tidak dapat dikembalikan lagi, yakin hapus ? ',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-trash"></i> Hapus',
                cancelButtonText: '<i class="ti ti-x"></i> Batal',
                confirmButtonColor: "#d63939",
                cancelButtonColor: "#206bc4"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/efktp/hasil-usg`,
                        data: {
                            no_rawat: no_rawat
                        },
                        method: 'DELETE'
                    }).done((response) => {
                        const no_rkm_medis = formHasilUsg.find('input[name=no_rkm_medis]').val()
                        setHasilUsg(no_rawat)
                        riwayatHasilUsg(no_rkm_medis)
                        showToast('Berhasil')
                    }).fail((result) => {
                        showToast(result.responseJson.message, 'error')
                    })
                } else {
                    showToast('Membatalkan proses hapus')
                }
            })


        }

        formHasilUsg.find('#btnHitungUmurHamil').on('click', () => {
            const HPHT = formHasilUsg.find('input[name=HPHT]').val();
            const HPL = formHasilUsg.find('input[name=HPL]').val();
            const umurHamil = hitungUsiaKehamilan(HPHT, HPL);
            formHasilUsg.find('input[name=umur_kehamilan]').val(umurHamil);

            const janin = formHasilUsg.find('select[name=janin]').val();

            if (janin && janin.includes('Kembar')) {
                formHasilUsg.find('input[name=umur_kehamilan2]').val(umurKehamilan)
                formHasilUsg.find('input[name=HPL2]').val(hpht)
            } else {
                formHasilUsg.find('input[name=HPL2]').val(null)
                formHasilUsg.find('input[name=umur_kehamilan2]').val(null)
            }
        })
    </script>
@endpush

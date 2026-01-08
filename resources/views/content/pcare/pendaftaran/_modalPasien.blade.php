<div class="modal modal-blur fade" id="modalPasien" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Data Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <div class="card border-0">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#pane1" id="tabs1" class="nav-link" data-bs-toggle="tab"><i class="ti ti-user me-2"></i> Form Pasien</a>
                            </li>
                            <li class="nav-item">
                                <a href="#pane2" id="tabs2" class="nav-link" data-bs-toggle="tab"><i class="ti ti-list me-2"></i> Data Pasien</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="pane1">
                                <h4>Form Input Pasien</h4>
                                <form action="" id="formPasien">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <fieldset class="form-fieldset">
                                                <div class="row gy-1">
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <label class="form-label required">No. Rekam Medis</label>
                                                        <div class="input-group">
                                                            <input type="text" name="no_rkm_medis" id="no_rkm_medis" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" autocomplete="off" value="-" disabled>
                                                            <input type="hidden" name="sttsForm">
                                                            <input type="hidden" name="noUrut">
                                                            <span class="input-group-text">
                                                                <input class="form-check-input m-0" type="checkbox" checked id="checkNoRm" name="checkNoRm" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                                        <label class="form-label required">Nama Pasien</label>
                                                        <input type="text" name="nm_pasien" id="nm_pasien" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" autocomplete="off" value="-">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="form-label required">Jenis Kelamin</label>
                                                        <select name="jk" id="jk" class="form-select form-select-2" data-dropdown-parent="#modalPasien" style="width: 100%">
                                                            <option value="L">Laki-laki</option>
                                                            <option value="P">Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <label class="form-label required">Golongan Darah</label>
                                                        <select name="gol_darah" id="gol_darah" class="form-select form-select-2" data-dropdown-parent="#modalPasien" style="width: 100%">
                                                            <option value="-" selected>-</option>
                                                            <option value="A">A</option>
                                                            <option value="B">B</option>
                                                            <option value="AO">AO</option>
                                                            <option value="o">O</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <label class="form-label required">Tempat Lahir</label>
                                                        <input type="text" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="tmp_lahir" id="tmp_lahir" autocomplete="off" value="-">
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                                        <label class="form-label required">Tgl Lahir</label>
                                                        <input type="text" class="form-control filterTangal" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="tgl_lahir" id="tgl_lahir" autocomplete="off" value="{{ date('d-m-Y') }}">
                                                    </div>
                                                    <div class="col-lg-5 col-md-12 col-sm-12">
                                                        <label class="form-label">Umur</label>
                                                        <input type="text" value="-" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="umur" id="umur" placeholder="">
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label class="form-label">Nama Ibu</label>
                                                        <input type="text" value="-" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="nm_ibu" id="nm_ibu" placeholder="">
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label class="form-label">Penanggung Jawab</label>
                                                        <select class="form-select form-select-2" name="keluarga" id="keluarga" data-dropdown-parent="#modalPasien" style="width: 100%">
                                                            <option value="AYAH">AYAH</option>
                                                            <option value="IBU">IBU</option>
                                                            <option value="SAUDARA">SAUDARA</option>
                                                            <option value="ISTRI">ISTRI</option>
                                                            <option value="SUAMI">SUAMI</option>
                                                            <option value="ANAK">ANAK</option>
                                                            <option value="DIRI SENDIRI" selected>DIRI SENDIRI</option>
                                                            <option value="LAIN-LAIN">LAIN-LAIN</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label class="form-label">Nama P.J.</label>
                                                        <input type="text" value="-" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="namakeluarga" id="namakeluarga" placeholder="">
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label class="form-label">Pekerjaan</label>
                                                        <input type="text" value="-" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" name="pekerjaanpj" id="pekerjaanpj" placeholder="">
                                                    </div>
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <label class="form-label">Suku/Bangsa</label>
                                                        <select class="form-select" name="suku_bangsa" id="suku_bangsa" style="width:100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <label class="form-label">Bahasa</label>
                                                        <select class="form-select" name="bahasa_pasien" id="bahasa_pasien" style="width:100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <label class="form-label">Cacat Fisik</label>
                                                        <select class="form-select" name="cacat_fisik" id="cacat_fisik" style="width:100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label class="form-label">Agama</label>
                                                        <select class="form-select form-select-2" name="agama" id="agama" data-dropdown-parent="#modalPasien" style="width:100%">
                                                            <option value="-" selected>-</option>
                                                            <option value="ISLAM">ISLAM</option>
                                                            <option value="KATOLIK">KATOLIK</option>
                                                            <option value="KRISTEN">KRISTEN</option>
                                                            <option value="PROTESTAN">PROTESTAN</option>
                                                            <option value="HINDU">HINDU</option>
                                                            <option value="BUDHA">BUDHA</option>
                                                            <option value="KONGHU CHU">KONGHU CHU</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label class="form-label">Status Menikah</label>
                                                        <select class="form-select form-select-2" name="stts_nikah" id="stts_nikah" data-dropdown-parent="#modalPasien" style="width:100%">
                                                            <option value="BELUM MENIKAH" selected>BELUM MENIKAH</option>
                                                            <option value="MENIKAH">MENIKAH</option>
                                                            <option value="JANDA">JANDA</option>
                                                            <option value="DUDHA">DUDA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <fieldset class="form-fieldset">
                                                <div class="row gy-1">
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <label for="kd_pj" class="form-label">Asuransi</label>
                                                        <select name="kd_pj" id="kd_pj" class="form-select" style="width:100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <label for="no_peserta" class="form-label">No Kartu</label>
                                                        <div class="input-group">
                                                            <input name="no_peserta" id="no_peserta" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-" />
                                                            <button type="button" class="btn btn-indigo" onclick="getPesertaPasien('noKartu')"><i class="ti ti-search"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="no_tlp" class="form-label">No Telp/Hp.</label>
                                                        <input name="no_tlp" id="no_tlp" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" style="width:100%" value='-' />
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="email" class="form-label">E-Mail</label>
                                                        <input name="email" id="email" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" style="width:100%" value="-" />
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="pnd" class="form-label">Pendidikan</label>
                                                        <select name="pnd" id="pnd" class="form-select">
                                                            <option value="-" selected>-</option>
                                                            <option value="TK">TK</option>
                                                            <option value="SD">SD</option>
                                                            <option value="SMA">SMA</option>
                                                            <option value="SLTA/SEDERAJAT">SLTA/SEDERAJAT</option>
                                                            <option value="D1">D1</option>
                                                            <option value="D2">D2</option>
                                                            <option value="D3">D3</option>
                                                            <option value="D4">D4</option>
                                                            <option value="S1">S1</option>
                                                            <option value="S2">S2</option>
                                                            <option value="S3">S3</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="kd_pj" class="form-label">Pekerjaan</label>
                                                        <input name="pekerjaan" id="pekerjaan" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" style="width:100%" value='-' />
                                                    </div>
                                                    <div class="col-xl-5 col-md-6 col-sm-12">
                                                        <label for="no_ktp" class="form-label">No. KTP/SIM</label>
                                                        <input name="no_ktp" id="no_ktp" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-" />
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-sm-12">
                                                        <label for="tgl_daftar" class="form-label">Tgl. Daftar</label>
                                                        <input name="tgl_daftar" id="tgl_daftar" class="form-control filterTanggal" value="{{ date('d-m-Y') }}" />
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <label for="alamat" class="form-label">Alamat</label>
                                                        <input name="alamat" id="alamat" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-" />
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <label for="kd_kel" class="form-label">Kelurahan/Desa</label>
                                                        <select name="kd_kel" id="kd_kel" class="form-select" style="width: 100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="kd_kec" class="form-label">Kecamatan</label>
                                                        <select name="kd_kec" id="kd_kec" class="form-select" style="width: 100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="kd_kab" class="form-label">Kabupaten</label>
                                                        <select name="kd_kab" id="kd_kab" class="form-select" style="width: 100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <label for="kd_prop" class="form-label">Propinsi</label>
                                                        <select name="kd_prop" id="kd_prop" class="form-select" style="width: 100%">
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                                        <label class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="checkPj" name="checkPj">
                                                            <span class="form-check-label">Alamat PJ</span>
                                                        </label>
                                                        <input name="alamatpj" id="alamatpj" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-" />
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <label class="form-label" for="perusahaan_pasien">Instansi/Peruhasaan</label>
                                                        <select name="perusahaan_pasien" id="perusahaan_pasien" class="form-select" style="width:100%"></select>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <label class="form-label" for="nip">NIP/NIK</label>
                                                        <input name="nip" id="nip" class="form-control" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-" />
                                                    </div>

                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="pane2">
                                <div class="row">
                                    <div class="col">
                                        <div class="table-responsive" style="height: 70vh;">
                                            <table class="table table-sm table-striped table-hover nowrap" id="tbPasien" style="width: 100%"></table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                {{-- <div class="row">
                    <div class="col">
                        <label for="lengthTbPasien">Baris</label>
                        <select class="form-select w-auto" id="lengthTbPasien" data-dropdown-parent="#modalPasien">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="150">150</option>
                        </select>
                    </div>

                </div> --}}

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning" id="btnResetPasien" onclick="resetFormRegistrasi()">
                        <i class="ti ti-reload me-2"></i>Baru
                    </button>
                    <button type="button" class="btn btn-success" id="btnSimpanPasien" onclick="createPasienBaru()">
                        <i class="ti ti-device-floppy me-2"></i>Simpan
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@include('content.registrasi._modalRegistrasiPasien')
@include('content.registrasi._modalPesertaBpjs')
@push('script')
    <script>
        let formPasien = $('#formPasien');
        let modalPasien = $('#modalPasien');
        let sukuBangsa = formPasien.find('select[name=suku_bangsa]');
        let bahasaPasien = formPasien.find('select[name=bahasa_pasien]');
        let cacatFisik = formPasien.find('select[name=cacat_fisik]');
        let penjab = formPasien.find('select[name=kd_pj]');
        let kelurahan = formPasien.find('select[name=kd_kel]');
        let kecamatan = formPasien.find('select[name=kd_kec]');
        let kabupaten = formPasien.find('select[name=kd_kab]');
        let propinsi = formPasien.find('select[name=kd_prop]');
        let perusahaan = formPasien.find('select[name=perusahaan_pasien]');
        let checkPj = formPasien.find('input[id=checkPj]');
        let checkNoRm = formPasien.find('input[id=checkNoRm]');
        let tglLahir = formPasien.find('input[name=tgl_lahir]');
        let tabFormPasien = $('#pane1');
        let tabTablePasien = $('#pane2');
        // const lengthTbPasien = $('#lengthTbPasien')

        // lengthTbPasien.select2();

        // lengthTbPasien.on('select2:select', function(e) {
        //     const length = e.params.data.id;
        //     renderTbPasien({
        //         length: length,
        //     });

        // })

        function switchTab(tabId) {
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('show active');

            // Tambahkan kelas 'active' pada tab dan tab-pane yang diinginkan
            $('#' + tabId).addClass('active');
            $('#pane' + tabId.slice(-1)).addClass('show active');
        }

        function setUmurDaftar(tglLahir) {
            let umurArray = hitungUmur(tglLahir).split(';');
            const umur = umurArray.map((val, index) => {
                if (val > 0) {
                    return {
                        value: val,
                        index: index
                    };
                }
            }).find(element => element !== undefined)
            if (!umur) {
                return '0 Hr';
            } else {
                return `${umur.value} ${umur.index == 0 ? 'Th' : umur.index == 1 ? 'Bl' : 'Hr'}`;
            }
        }


        function isExistPasien(data) {
            $.get(`/efktp/pasien/exist`, data)
        }

        function createPasienBaru() {
            const data = getDataForm('formPasien', ['input', 'select']);
            loadingAjax();
            $.post(`/efktp/pasien`, data).done((response) => {
                if (response) {
                    alertSuccessAjax('Berhasil').then(() => {
                        renderTbPasien();
                        document.getElementById('formPasien').reset();
                        if (data.checkNoRm) {
                            $.get(`/efktp/set/norm`).done((response) => {
                                formPasien.find('input[name=no_rkm_medis]').val(response)
                            })
                        }
                        resetSelect();
                        if (formPasien.find('input[name=sttsForm]').val() == 'bridging') {
                            regPoliBpjs(data);
                            renderPendaftaranPcare(start = '', length = '')
                        }
                        formPasien.find('input').removeClass('is-valid')
                        formPasien.find('select').removeClass('is-valid')
                        switchTab('tabs2')
                    })
                }
            }).fail((request) => {
                alertErrorAjax(request)
            })
        }

        function regPoliBpjs(data) {
            loadingAjax('Sedang menyiapkan data pendaftaran di Pcare');
            const selectKdPj = formRegistrasiPoli.find('select[name=kd_pj]');
            $.get(`/efktp/bridging/pcare/pendaftaran/nourut/${data.noUrut}`).done((pendaftaran) => {
                if (pendaftaran.metaData.code == 200) {
                    loadingAjax().close();
                    const response = pendaftaran.response;
                    periksaPendaftaran.removeClass('d-none');
                    formRegistrasiPoli.find('input[name=nm_poli_pcare]').val(response.poli.nmPoli)
                    formRegistrasiPoli.find('input[name=bridging]').val('bridging')
                    formRegistrasiPoli.find('input[name=kd_poli_pcare]').val(response.poli.kdPoli)
                    formRegistrasiPoli.find('input[name=tkp]').val(response.tkp.nmTkp == 'RJTP' ? 'RAWAT JALAN' : 'RAWAT INAP')
                    formRegistrasiPoli.find('input[name=kdTkp]').val(response.tkp.kdTkp)
                    formRegistrasiPoli.find('input[name=keluhan]').val(response.keluhan ? response.keluhan : '-')
                    formRegistrasiPoli.find('input[name=sistole]').val(response.sistole)
                    formRegistrasiPoli.find('input[name=diastole]').val(response.diastole)
                    formRegistrasiPoli.find('input[name=tinggi]').val(response.tinggiBadan)
                    formRegistrasiPoli.find('input[name=berat]').val(response.beratBadan)
                    formRegistrasiPoli.find('input[name=respirasi]').val(response.respRate)
                    formRegistrasiPoli.find('input[name=nadi]').val(response.heartRate)
                    formRegistrasiPoli.find('input[name=lingkar_perut]').val(response.lingkarPerut)
                    formRegistrasiPoli.find('input[name=no_peserta]').val(response.peserta.noKartu)
                    formRegistrasiPoli.find('input[name=no_rkm_medis]').val(data.no_rkm_medis)
                    formRegistrasiPoli.find('input[name=nm_pasien]').val(data.nm_pasien)
                    formRegistrasiPoli.find('input[name=umurdaftar]').val(data.umurdaftar)
                    formRegistrasiPoli.find('input[name=namakeluarga]').val(data.namakeluarga)
                    formRegistrasiPoli.find('input[name=keluarga]').val(data.keluarga)
                    formRegistrasiPoli.find('input[name=alamatpj]').val(data.alamatpj)
                    formRegistrasiPoli.find('input[name=status]').val('Baru')
                    formRegistrasiPoli.find('input[name=noUrut]').val(data.noUrut)
                    formRegistrasiPoli.find('input[name=umurdaftar]').val(setUmurDaftar(splitTanggal(response.peserta.tglLahir)))

                    // SET PENJAB
                    $.get(`/efktp/penjab`, {
                        nama: 'BPJS',
                    }).done((response) => {
                        selectPenjab(selectKdPj, modalRegistrasi);
                        const bpjs = new Option(`${response.kd_pj} - ${response.png_jawab}`, `${response.kd_pj}`, true, true);
                        selectKdPj.append(bpjs).trigger('change');
                    })

                    refreshTime();

                    setNoRawat().done((response) => {
                        formRegistrasiPoli.find('input[name=no_rawat]').val(response)
                    });
                    setNoReg().done((response) => {
                        formRegistrasiPoli.find('input[name=no_reg]').val(response)
                    });
                    // // SET POLIKLINIK
                    // $.get(`/efktp/mapping/pcare/poliklinik`, {
                    //     kdPoliPcare: response.poli.kdPoli
                    // }).done((resultPoli) => {

                    //     selectPoliklinik(formRegistrasiPoli.find('select[name=kd_poli]'), modalRegistrasi);
                    //     const poli = new Option(`${resultPoli.poliklinik.kd_poli} - ${resultPoli.poliklinik.nm_poli}`, `${resultPoli.poliklinik.kd_poli}`, true, true);
                    //     formRegistrasiPoli.find('select[name=kd_poli]').append(poli).trigger('change');
                    //     formRegistrasiPoli.find('input[name=kd_poli_pcare]').val(response.poli.kdPoli)
                    // }).fail((error) => {
                    //     alertErrorAjax(error)
                    // })

                    // // GET DOKTER
                    // $.get(`/efktp/bridging/pcare/dokter`).done((respDokter) => {
                    //     const dokter = respDokter.response.list
                    //     const kdDokterPcare = dokter.map((dr, index) => {
                    //         if (index == 0) {
                    //             return dr.kdDokter;
                    //         }
                    //     }).join('')

                    //     $.get(`/efktp/mapping/pcare/dokter`, {
                    //         kdDokterPcare: kdDokterPcare
                    //     }).done((resDokter) => {
                    //         loadingAjax().close();
                    //         selectDokter(formRegistrasiPoli.find('select[name=kd_dokter]'), modalRegistrasi);
                    //         const dokter = new Option(`${resDokter.kd_dokter} - ${resDokter.nm_dokter_pcare}`, `${resDokter.kd_dokter}`, true, true);
                    //         formRegistrasiPoli.find('select[name=kd_dokter]').append(dokter).trigger('change');
                    //         formRegistrasiPoli.find('input[name=kd_dokter_pcare]').val(kdDokterPcare);
                    //     }).fail((error) => {
                    //         alertErrorAjax(error)
                    //     })
                    // }).fail((erorr) => {
                    //     alertErrorAjax(error)
                    // })
                    modalRegistrasi.modal('show')
                } else {
                    alertErrorBpjs(response)
                }
            })
        }

        tglLahir.on('change', (e) => {
            var tanggal = splitTanggal(e.currentTarget.value);
            const umur = hitungUmur(tanggal)
            const textTglLahir = `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`
            formPasien.find('input[name=umur]').val(textTglLahir)
        })

        function resetSelect() {
            formPasien.find('input[name=checkNoRm]').attr('disabled', false);
            selectSukuBangsa(sukuBangsa, modalPasien, '-');
            selectBahasaPasien(bahasaPasien, modalPasien, 'INDONESIA');
            selectCacatFisik(cacatFisik, modalPasien, 'TIDAK ADA');
            selectPenjab(penjab, modalPasien);

            const noPeserta = formPasien.find('input[name=no_peserta]').val()
            if (noPeserta.length == 13) {
                const bpjs = new Option('BPJ - BPJS', 'BPJS', true, true);
                formPasien.find('select[name=kd_pj]').append(bpjs).trigger('change');
            }
            selectKelurahan(kelurahan, modalPasien);
            selectKecamatan(kecamatan, modalPasien);
            selectKabupaten(kabupaten, modalPasien);
            selectPropinsi(propinsi, modalPasien);
            selectPerusahaan(perusahaan, modalPasien);
        }

        modalPasien.on('shown.bs.modal', (e) => {
            const inputNoRm = formPasien.find('input[name=no_rkm_medis]');
            const inputNoPeserta = formPasien.find('input[name=no_peserta]');
            const inputUmur = formPasien.find('input[name=umur]');
            const umur = hitungUmur(splitTanggal(tglLahir.val()))
            const textTglLahir = `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`


            switchTab('tabs1');
            renderTbPasien();
            resetSelect();
            $.get(`/efktp/set/norm`).done((response) => {
                formPasien.find('input[name=no_rkm_medis]').val(response)
            })
            inputUmur.val(textTglLahir)

            $('#switchPendaftaranPcare').prop('checked', false);
            if (inputNoPeserta.val() === '-') {
                const optPj = new Option('---', '-', true, true)
                formPasien.find('select[name=kd_pj]').append(optPj).trigger('change')
            }
        })

        modalPasien.on('hidden.bs.modal', (e) => {
            $('#switchPendaftaranPcare').prop('checked', false);
            resetSelect();
        })

        checkPj.on('change', (e) => {
            const isChecked = $(e.currentTarget).is(':checked');
            let alamatpj = '';
            if (isChecked) {
                const alamatText = formPasien.find(`input[name=alamat]`).val()
                const propText = propinsi.find(`option:selected`).text()
                const kabText = kabupaten.find(`option:selected`).text()
                const kecText = kecamatan.find(`option:selected`).text()
                const kelText = kelurahan.find(`option:selected`).text()
                alamatpj = `${alamatText}, ${kelText}, ${kecText}, ${kabText}, ${propText}`
            } else {
                alamatpj = `-`
            }
            formPasien.find('input[name=alamatpj]').val(alamatpj)

        })
        checkNoRm.on('change', (e) => {
            const isChecked = $(e.currentTarget).is(':checked');
            const noRkmMedis = formPasien.find('input[name=no_rkm_medis]');
            if (isChecked) {
                $.get(`/efktp/set/norm`).done((response) => {
                    noRkmMedis.attr('disabled', true);
                    noRkmMedis.val(response)
                })
            } else {
                noRkmMedis.attr('disabled', false);
            }
        })

        sukuBangsa.on('select2:select', (e) => {
            const selectedSuku = e.currentTarget.value;
            $.post(`/efktp/suku`, {
                suku: selectedSuku,
            }).done((response) => {
                if (response.message == 'SUKSES') {
                    toast();
                    const option = new Option(selectedSuku, response.id, true, true);
                    sukuBangsa.append(option).trigger('change');
                }
            })
        });

        bahasaPasien.on('select2:select', (e) => {
            const selectBahasa = e.currentTarget.value;
            $.post(`/efktp/bahasa`, {
                bahasa: selectBahasa,
            }).done((response) => {
                if (response.message == 'SUKSES') {
                    toast();
                    const option = new Option(selectBahasa, response.id, true, true);
                    bahasaPasien.append(option).trigger('change');
                }
            })
        })
        kelurahan.on('select2:select', (e) => {
            const nmKelurahan = e.currentTarget.value.toUpperCase();
            $.post(`/efktp/kelurahan`, {
                kelurahan: nmKelurahan,
            }).done((response) => {
                if (response.message == 'SUKSES') {
                    toast();
                    const option = new Option(nmKelurahan, response.id, true, true);
                    kelurahan.append(option).trigger('change');
                }
            })
        })
        kecamatan.on('select2:select', (e) => {
            const nmKecamatan = e.currentTarget.value.toUpperCase();
            $.post(`/efktp/kecamatan`, {
                kecamatan: nmKecamatan,
            }).done((response) => {
                if (response.message == 'SUKSES') {
                    toast();
                    const option = new Option(nmKecamatan, response.id, true, true);
                    kecamatan.append(option).trigger('change');
                }
            })
        })
        kabupaten.on('select2:select', (e) => {
            const nmKabupaten = e.currentTarget.value.toUpperCase();
            $.post(`/efktp/kabupaten`, {
                kabupaten: nmKabupaten,
            }).done((response) => {
                if (response.message == 'SUKSES') {
                    toast();
                    const option = new Option(nmKabupaten, response.id, true, true);
                    kabupaten.append(option).trigger('change');
                }
            })
        })
        propinsi.on('select2:select', (e) => {
            const nmPropinsi = e.currentTarget.value.toUpperCase();
            $.post(`/efktp/propinsi`, {
                propinsi: nmPropinsi,
            }).done((response) => {
                if (response.message == 'SUKSES') {
                    toast();
                    const option = new Option(nmPropinsi, response.id, true, true);
                    propinsi.append(option).trigger('change');
                }
            })
        })

        function renderTbPasien(...args) {
            const tbReferensi = new DataTable('#tbPasien', {
                responsive: true,
                // autoWidth: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                fixedHeader: true,
                scrollY: '50vh',
                pageLength: 50,
                scrollX: true,
                columnDefs: [{
                        name: "no_rkm_medis",
                        targets: 0
                    },
                    {
                        name: "nm_pasien",
                        targets: 1
                    },
                    {
                        orderable: false,
                        targets: [2, 3, 4, 5, 6, 7, 8]
                    },
                ],
                ajax: {
                    url: `/efktp/pasien`,
                    data: {
                        datatable: true,
                        tglRegistrasi: args['tgl_registrasi'] ? args['tgl_registrasi'] : '',
                        // length: args['length'] ? args['length'] : 10
                    }
                },
                createdRow: (row, data, index) => {
                    $(row).addClass('rows-pasien');
                    $(row).attr('data-id', data.no_rkm_medis);
                    asuransi = data.penjab.png_jawab.includes('BPJS') ? 'BPJS' : data.penjab.png_jawab;
                    $(row).attr('data-poli', asuransi);
                    $(row).attr('data-peserta', data.no_peserta);
                },
                columns: [{
                        title: '',
                        data: 'no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return `<button class="btn btn-primary btn-sm" onclick="registrasiPoli('${data}')"><i class="ti ti-plus"></i></button>
                            <button class="btn btn-yellow btn-sm" onclick="editPasien('${data}')"><i class="ti ti-pencil"></i></button>`;
                        }

                    }, {
                        title: 'No RM.',
                        data: 'no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Nama',
                        data: 'nm_pasien',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'JK',
                        data: 'jk',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Asuransi',
                        data: 'penjab',
                        render: (data, type, row, meta) => {
                            return asuransi = data.png_jawab.includes('BPJS') ? `<span class='badge badge-pill text-bg-green'>BPJS</span>` :
                                `<span class='badge badge-pill text-bg-orange'>${data.png_jawab}</span>`;
                        }

                    },
                    {
                        title: 'No. Peserta',
                        data: 'no_peserta',
                        render: (data, type, row, meta) => {
                            return data;
                        }

                    },
                    {
                        title: 'NIK',
                        data: 'no_ktp',
                        render: (data, type, row, meta) => {
                            return data;
                        }

                    },
                    {
                        title: 'PJ',
                        data: 'namakeluarga',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Tgl. Lahir',
                        data: 'tgl_lahir',
                        render: (data, type, row, meta) => {
                            return splitTanggal(data);
                        }
                    },
                    {
                        title: 'Umur',
                        data: 'umur',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Alamat',
                        data: 'alamat',
                        render: (data, type, row, meta) => {
                            return `${data}`;
                        }

                    },
                ],
            })
        }

        function resetFormRegistrasi() {
            resetSelect();
            formPasien.find('input[name=checkNoRm]').attr('disabled', false);
            $('#formPasien').trigger('reset');
            $.get(`/efktp/set/norm`).done((response) => {
                formPasien.find('input[name=no_rkm_medis]').val(response)
                formPasien.find('input').removeClass('is-valid')
                formPasien.find('select').removeClass('is-valid')
            })
        }

        function editPasien(no_rkm_medis) {
            $.get(`/efktp/pasien`, {
                no_rkm_medis: no_rkm_medis,
            }).done((response) => {
                formPasien.find('input[name=no_rkm_medis]').val(no_rkm_medis);
                formPasien.find('input[name=checkNoRm]').attr('disabled', true);
                formPasien.find('input[name=nm_pasien]').val(response.nm_pasien);
                formPasien.find('select[name=jk]').val(response.jk).change();
                formPasien.find('select[name=gol_darah]').val(response.gol_darah).change();
                formPasien.find('input[name=tmp_lahir]').val(response.tmp_lahir);
                formPasien.find('input[name=tgl_lahir]').val(splitTanggal(response.tgl_lahir));
                formPasien.find('input[name=umur]').val(response.umur);
                formPasien.find('input[name=nm_ibu]').val(response.nm_ibu);
                formPasien.find('select[name=keluarga]').val(response.keluarga);
                formPasien.find('input[name=pekerjaan]').val(response.pekerjaan);
                formPasien.find('input[name=namakeluarga]').val(response.namakeluarga);
                formPasien.find('input[name=pekerjaanpj]').val(response.pekerjaanpj);
                formPasien.find('select[name=agama]').val(response.agama).change();
                formPasien.find('select[name=stts_nikah]').val(response.stts_nikah).change();
                formPasien.find('input[name=no_peserta]').val(response.no_peserta);
                formPasien.find('input[name=no_ktp]').val(response.no_ktp);
                formPasien.find('input[name=no_tlp]').val(response.no_tlp);
                formPasien.find('input[name=email]').val(response.email);
                formPasien.find('select[name=pnd]').val(response.pnd).change();
                formPasien.find('input[name=alamat]').val(response.alamat);
                formPasien.find('input[name=alamatpj]').val(`${response.alamatpj}, ${response.kecamatanpj}, ${response.kabupatenpj}, ${response.propinsipj}`);
                formPasien.find('input[name=nip]').val(response.nip);

                const suku = new Option(`${response.suku_bangsa.nama_suku_bangsa}`, `${response.suku_bangsa.id}`, true, true);
                formPasien.find('select[name=suku_bangsa]').append(suku).trigger('change');

                const bahasa = new Option(`${response.bahasa_pasien.nama_bahasa}`, `${response.bahasa_pasien.id}`, true, true);
                formPasien.find('select[name=bahasa_pasien]').append(bahasa).trigger('change');

                const cacat = new Option(`${response.cacat_fisik.nama_cacat}`, `${response.cacat_fisik.id}`, true, true);
                formPasien.find('select[name=cacat_fisik]').append(cacat).trigger('change');

                const penjab = new Option(`${response.penjab.png_jawab}`, `${response.penjab.kd_pj}`, true, true);
                formPasien.find('select[name=kd_pj]').append(penjab).trigger('change');

                const kel = new Option(`${response.kel.nm_kel}`, `${response.kel.kd_kel}`, true, true);
                kelurahan.append(kel).trigger('change');

                const kec = new Option(`${response.kec.nm_kec}`, `${response.kec.kd_kec}`, true, true);
                kecamatan.append(kec).trigger('change');

                const kab = new Option(`${response.kab.nm_kab}`, `${response.kab.kd_kab}`, true, true);
                kabupaten.append(kab).trigger('change');

                const prop = new Option(`${response.prop.nm_prop}`, `${response.prop.kd_prop}`, true, true);
                propinsi.append(prop).trigger('change');

                const inst = new Option(`${response.perusahaan_pasien.nama_perusahaan}`, `${response.perusahaan_pasien.kode_perusahaan}`, true, true);
                perusahaan.append(inst).trigger('change');

                switchTab('tabs1')
            })
        }

        function getPesertaPasien(findBy) {

            const noKartu = findBy == 'noKartu' ? formPasien.find('#no_peserta').val() : formPasien.find('#no_ktp').val();
            loadingAjax();
            $.get(`/efktp/bridging/pcare/peserta/${noKartu}`).done((response) => {
                if (response.metaData.code == 200) {
                    loadingAjax().close();
                    const result = response.response;
                    $.get(`/efktp/setting/ppk`).done((kode) => {
                        if (kode !== result.kdProviderPst.kdProvider) {
                            Swal.fire({
                                title: "Peringatan ?",
                                html: "Pasien tidak terdaftar sebagai peserta Anda, tetap lanjutkan ?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "Iya, Lanjutkan",
                                cancelButtonText: "Tidak, Batalkan"
                            }).then((res) => {
                                if (!res.isConfirmed) {
                                    resetFormRegistrasi();
                                    return true;
                                }
                            });
                        }
                        const umur = hitungUmur(splitTanggal(result.tglLahir));
                        const textUmur = `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`
                        formPasien.find('#nm_pasien').val(result.nama).addClass('is-valid');
                        formPasien.find('#no_ktp').val(result.noKTP).addClass('is-valid');
                        formPasien.find('#umur').val(textUmur).addClass('is-valid');
                        formPasien.find('#no_tlp').val(result.noHP).addClass('is-valid');
                        formPasien.find('#tgl_lahir').val(result.tglLahir).addClass('is-valid');
                        formPasien.find('#jk').val(result.sex).change().addClass('is-valid');
                        const bpjs = new Option('BPJ - BPJS', 'BPJS', true, true);
                        formPasien.find('#kd_pj').append(bpjs).trigger('change');
                    })

                } else {
                    alertErrorBpjs(response);
                }
            });
        }
    </script>
@endpush

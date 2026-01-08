<div class="modal modal-blur fade" id="modalRegistrasi" tabindex="-1" aria-modal="false" role="dialog"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document"
         style="width:860px;max-width:100%">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Registrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrasiPoli">
                    <fieldset class="form-fieldset">
                        <div class="row gy-2">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="row gy-2">
                                    <div class="col-xl-5 col-md-12 col-sm-12">
                                        <label class="form-label required">No. Reg</label>
                                        <div class="input-group">
                                            <input type="text" name="no_reg" id="no_reg" class="form-control"
                                                   onfocus="return removeZero(this)" onblur="isEmpty(this)" disabled="">
                                            <span class="input-group-text">
                                                <input class="form-check-input m-0" type="checkbox" checked=""
                                                       id="checkNoReg" name="checkNoReg">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-xl-7 col-md-12 col-sm-12">
                                        <label for="no_rawat" class="form-label">No. Rawat</label>
                                        <input type="text" class="form-control" name="no_rawat" id="no_rawat"/>
                                    </div>
                                    <div class="col-xl-6 col-md-12 col-sm-12">
                                        <label for="tgl_registrasi" class="form-label">Tgl Registrasi</label>
                                        <input type="text" class="form-control filterTangal" name="tgl_registrasi"
                                               id="tgl_registrasi" value="{{ date('d-m-Y') }}"/>
                                    </div>
                                    <div class="col-xl-6 col-md-12 col-sm-12">
                                        <label for="jam_reg" class="form-label">Jam</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control jam" name="jam_reg" id="jam_reg"
                                                   value="{{ date('H:i:s') }}" readonly/>
                                            <span class="input-group-text">
                                                <input class="form-check-input m-0 checkJam" type="checkbox" checked=""
                                                       id="" name="checkjam">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <label for="kd_dokter" class="form-label">Dokter</label>
                                        <select class="form-select" name="kd_dokter" id="kd_dokter"
                                                data-parent="#modalRegistrasi" style="width: 100%"></select>
                                        <input type="hidden" name="kd_dokter_pcare">
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <label for="kd_poli" class="form-label">Poliklinik/Unit</label>
                                        <select class="form-select" name="kd_poli" id="kd_poli"
                                                data-parent="#modalRegistrasi" style="width: 100%"></select>
                                        <input type="hidden" name="kd_poli_pcare">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="row gy-2">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <label for="no_rkm_medis">No. RM</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="no_rkm_medis"
                                                   id="no_rkm_medis" value="" readonly/>
                                            <input type="text" class="form-control w-50" name="nm_pasien" id="nm_pasien"
                                                   value="" readonly/>
                                            <input type="text" class="form-control" name="umurdaftar" id="umurdaftar"
                                                   value="" readonly/>
                                            <input type="hidden" name="umur" id="umur" value=""/>
                                            <input type="hidden" name="tgl_lahir" id="tgl_lahir" value=""/>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12 col-sm-12">
                                        <label for="namakeluarga">KK/Penanggung</label>
                                        <input type="text" class="form-control" name="p_jawab" id="p_jawab"
                                               value=""/>
                                    </div>
                                    <div class="col-xl-6 col-md-12 col-sm-12">
                                        <label for="keluarga">Hubungan</label>
                                        <input type="text" class="form-control" name="hubunganpj" id="hubunganpj"
                                               value=""/>
                                    </div>
                                    <div class="col-xl-8 col-md-12 col-sm-12">
                                        <label for="alamatpj">Alamat P.J.</label>
                                        <input type="text" class="form-control" name="alamatpj" id="alamatpj" value=""/>
                                    </div>
                                    <div class="col-xl-4 col-md-12 col-sm-12">
                                        <label for="alamatpj">Status</label>
                                        <input type="text" class="form-control" name="status" id="status" value=""/>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <label for="kd_pj">Asuransi/Pembiayaan</label>
                                        <div class="input-group">
                                            <select type="text" class="form-control" name="kd_pj" id="kd_pj" value="-"
                                                    style="width:50%"></select>
                                            <input type="text" class="form-control w-50" name="no_peserta"
                                                   id="no_peserta" value="-"/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </fieldset>
                    <div class="mb-2">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked="" id="switchPendaftaranPcare">
                            <span class="form-check-label">Kirim Pendaftaran ke Pcare </span>
                        </label>
                    </div>
                    <fieldset class="form-fieldset d-none" id="periksaPendaftaran">
                        <div class="row gy-2">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Poli Tujuan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="kd_poli_pcare" id="kd_poli_pcare"
                                           readonly>
                                    <input type="text" class="form-control w-50" name="nm_poli_pcare" id="nm_poli_pcare"
                                           readonly>
                                </div>
                                <input type="hidden" name="kd_poli_rs">
                                <input type="hidden" name="bridging">
                                <input type="hidden" name="noUrut">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label for="form-label">TKP</label>
                                <div class="input-group">
                                    <select class="form-select form-select-2" name="kdTkp" style="width: 100%"
                                            data-dropdown-parent='#modalRegistrasi'>
                                        <option value="10">10 - RJTP</option>
                                        <option value="20">20 - RITP</option>
                                        <option value="50">50 - Promotif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Kunjungan</label>
                                <input type="text" class="form-control" name="kunjunganSakit" value="Kunjungan Sakit"
                                       readonly>
                            </div>
                            <div class="col-xl-6 col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label">Keluhan</label>
                                <input type="text" class="form-control" name="keluhan" onfocus="return removeZero(this)"
                                       onblur="isEmpty(this)" value="-">
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Tensi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" class="form-control text-end" name="sistole"
                                               onfocus="return removeZero(this)" onblur="isEmpty(this)" value="0">
                                        <span class="input-group-text" style="width:10px;padding:2px;">
                                            /
                                        </span>
                                        <input type="text" class="form-control text-end" name="diastole"
                                               onfocus="return removeZero(this)" onblur="isEmpty(this)" value="0">
                                        <span class="input-group-text">
                                            mmHg
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Suhu</label>
                                    <div class="input-group input-group-flat">
                                        <input onfocus="return removeZero(this)" onblur="isEmpty(this)"
                                               oninput="return hanyaAngka(this)" type="text"
                                               class="form-control text-end" name="suhu_tubuh" value="0">
                                        <span class="input-group-text">
                                            °C
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Tinggi</label>
                                    <div class="input-group input-group-flat">
                                        <input onfocus="return removeZero(this)" onblur="isEmpty(this)"
                                               oninput="return hanyaAngka(this)" type="text"
                                               class="form-control text-end" name="tinggi" value="0">
                                        <span class="input-group-text">
                                            cm
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Berat</label>
                                    <div class="input-group input-group-flat">
                                        <input onfocus="return removeZero(this)" onblur="isEmpty(this)"
                                               oninput="return hanyaAngka(this)" type="text"
                                               class="form-control text-end" name="berat" value="0">
                                        <span class="input-group-text">
                                            Kg
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Respirasi</label>
                                    <div class="input-group input-group-flat">
                                        <input onfocus="return removeZero(this)" onblur="isEmpty(this)"
                                               oninput="return hanyaAngka(this)" type="text"
                                               class="form-control text-end" name="respirasi" value="0">
                                        <span class="input-group-text">
                                            x/mnt
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Nadi (/mnt)</label>
                                    <div class="input-group input-group-flat">
                                        <input onfocus="return removeZero(this)" onblur="isEmpty(this)"
                                               oninput="return hanyaAngka(this)" type="text"
                                               class="form-control text-end" name="nadi" value="0">
                                        <span class="input-group-text">
                                            x/mnt
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                <div class="mb-1">
                                    <label class="form-label">Lingkar Perut</label>
                                    <div class="input-group input-group-flat">
                                        <input onfocus="return removeZero(this)" onblur="isEmpty(this)"
                                               oninput="return hanyaAngka(this)" type="text"
                                               class="form-control text-end" name="lingkar_perut" value="0">
                                        <span class="input-group-text">
                                            cm
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar
                </button>
                <button type="button" class="btn btn-success" id="btnSimpanReg" onclick="createRegPeriksa()"><i
                            class="ti ti-device-floppy me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        let modalRegistrasi = $('#modalRegistrasi')
        let formRegistrasiPoli = $('#formRegistrasiPoli')
        let kd_dokter = formRegistrasiPoli.find('select[name=kd_dokter]')
        let kd_poli = formRegistrasiPoli.find('select[name=kd_poli]')
        let kd_pj = formRegistrasiPoli.find('select[name=kd_pj]')
        let tglReg = formRegistrasiPoli.find('input[name=tgl_registrasi]')
        let noReg = formRegistrasiPoli.find('input[name=no_reg]')
        let checkNoReg = formRegistrasiPoli.find('input[name=checkNoReg]')
        let selectPoliklinikReg = formRegistrasiPoli.find('select[name=kd_poli]')
        let btnSimpanReg = $('#btnSimpanReg')
        let periksaPendaftaran = $('#periksaPendaftaran')
        let timeDisplay = $('.jam');
        let checkJam = $('.checkJam');
        let setTime = '';
        const switchPendaftaranPcare = $('#switchPendaftaranPcare');


        switchPendaftaranPcare.on('change', (e) => {
            const isChecked = e.target.checked

            if (isChecked) {
                periksaPendaftaran.removeClass('d-none')
                selectMappingDokterPcare(kd_dokter, modalRegistrasi)
            } else {
                periksaPendaftaran.addClass('d-none');
                selectDokter(kd_dokter, modalRegistrasi)
            }


        })

        modalRegistrasi.on('shown.bs.modal', () => {
            selectDokter(kd_dokter, modalRegistrasi)
            selectPoliklinik(kd_poli, modalRegistrasi)
            selectPenjab(kd_pj, modalRegistrasi);
        });

        modalRegistrasi.on('hidden.bs.modal', () => {
            btnSimpanReg.removeAttr('onclick').attr('onclick', 'createRegPeriksa()')
            formRegistrasiPoli.trigger('reset');
            periksaPendaftaran.hasClass('d-none') ?
                periksaPendaftaran.removeClass('d-none') :
                periksaPendaftaran.addClass('d-none');

        })

        function refreshTime() {
            setTime = setInterval(() => {
                const dateString = new Date().toLocaleString("id-ID", {
                    timeZone: "Asia/Jakarta"
                });
                const formattedString = dateString.replace(",", "-");
                let splitarray = formattedString.split(" ");
                let splitarraytime = splitarray[1].split(".");
                const jam = splitarraytime[0] + ':' + splitarraytime[1] + ':' + splitarraytime[2]; // time
                timeDisplay.val(jam)
            }, 1000);

            return setTime;

        }

        function setNoRawat(tgl_registrasi = '') {
            return $.get(`/efktp/registrasi/set/norawat`, {
                tgl_registrasi: tgl_registrasi
            })
        }

        tglReg.on('change', (e) => {
            const isEdit = modalRegistrasi.find('.modal-title').html().includes('Ubah')
            if (!isEdit) {
                const tgl = e.currentTarget.value;
                setNoRawat(tgl).done((response) => {
                    formRegistrasiPoli.find('input[name=no_rawat]').val(response)
                })
            }
        })

        function createPendaftaranPcare(data) {
            $.post(`/efktp/bridging/pcare/pendaftaran`, data).done((resPendaftaran) => {

                if (resPendaftaran.metaData.code === 201 && resPendaftaran.metaData.message === 'CREATED') {
                    data['noUrut'] = resPendaftaran.response.message;
                    $.post(`/efktp/pcare/pendaftaran`, data).fail((error) => {
                        alertErrorAjax(error)
                    })
                    alertSuccessAjax("Berhasil mendaftarkan pasien di PCare").then(() => {
                        if (modalRegistrasi.length) {
                            modalRegistrasi.modal('hide');
                        }

                        if (modalPasien.length) {
                            modalPasien.modal('hide');
                        }
                    });
                } else {
                    alertErrorBpjs(resPendaftaran).then((result) => {
                        $.post(`/efktp/registrasi/delete`, {
                            no_rawat: data.no_rawat
                        }).done((response) => {
                            showToast('Menghapus data pasien dari registrasi ')
                            if (tabelRegistrasi.length) {
                                loadTabelRegistrasi()
                            } else if (tabelPcarePendaftaran.length) {
                                loadTbPcarePendaftaran()
                            }
                        }).fail((error) => {
                            alertErrorAjax(error)
                        })
                    })


                }
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function createBridgingPendaftaranPcare(data) {
            data['tensi'] = `${data.sistole}/${data.diastole}`
            data['nip'] = data.kd_dokter
            data['spo2'] = '98'
            data['alergi'] = '-'
            data['rtl'] = '-'
            data['penilaian'] = '-'
            data['gcs'] = '15'
            data['instruksi'] = '-'
            data['kesadaran'] = 'Compos Mentis'
            data['pemeriksaan'] = '-'
            $.post(`/efktp/pemeriksaan/ralan/create`, data).done((response) => {
                if (!data.bridging) {
                    loadingAjax('Memeriksa kepesertaan pasien');
                    checkPesertaPcare(data)
                } else {
                    $.post(`/efktp/pcare/pendaftaran`, data).fail((error) => {
                        alertErrorAjax(error)
                    }).done(() => {
                        alertSuccessAjax('Berhasil menyimpan data pendaftaran').then(() => {
                            if ($('#tbPendaftaranPcare').length > 0) {
                                renderPendaftaranPcare();
                                loadTbPcarePendaftaran(tglAwal, tglAkhir)
                            }
                            if (tabelRegistrasi.length) {
                                loadTabelRegistrasi(tglAwal, tglAkhir, selectStatusLayan.val(), selectDokterPoli.val())
                            }
                        })

                        modalRegistrasi.modal('hide')

                    })
                }

            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function checkPesertaPcare(data) {
            $.get(`./bridging/pcare/peserta/${data.no_peserta}`).done((result) => {
                $.get(`/efktp/setting/ppk`).done((kode) => {
                    data['kdProviderPeserta'] = result.response.kdProviderPst.kdProvider;
                    if (kode !== data['kdProviderPeserta']) {
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
                            loadingAjax()
                            if (res.isConfirmed) {
                                createPendaftaranPcare(data)
                            } else {
                                resetFormRegistrasi();
                                loadingAjax().close();
                            }
                        });
                    } else {
                        createPendaftaranPcare(data)
                    }
                })
            })
        }

        function createRegPeriksa() {

            const data = getDataForm('formRegistrasiPoli', ['input', 'select']);
            const tglAwal = $('#tglAwal').val();
            const tglAkhir = $('#tglAkhir').val();
            const checkPendaftaranPcare = switchPendaftaranPcare.is(':checked')

            if (data.kd_dokter === '-' || data.kd_dokter === '') {
                return Swal.fire({
                    title: "Peringatan",
                    html: `Anda belum memilih dokter`,
                    icon: 'warning',
                })
            }
            $.post(`/efktp/registrasi`, data).done((response) => {
                showToast('Berhasil melakukan registrasi')
                if (tabelRegistrasi.length) {
                    loadTabelRegistrasi(tglAwal, tglAkhir, selectStatusLayan.val(), selectDokterPoli.val())
                }
                if ((data.no_peserta !== '-' || data.no_peserta.length > 1) && checkPendaftaranPcare) {
                    createBridgingPendaftaranPcare(data)
                } else {
                    modalRegistrasi.modal('hide');
                    modalPasien.modal('hide');
                }
            }).fail((error, status, code) => {
                if (error.status !== 500) {
                    const errorMessage = {
                        status: error.status,
                        statusText: code,
                        responseJSON: error.responseJSON,
                    }
                    alertErrorAjax(errorMessage)
                } else {
                    alertErrorAjax(error)
                }
            });
        }

        checkJam.on('change', (e) => {
            const isChecked = $(e.currentTarget).is(':checked')
            if (!isChecked) {
                clearInterval(setTime)
                timeDisplay.removeAttr('readonly')
            } else {
                refreshTime()
                timeDisplay.attr('readonly', 'true')
            }
        })

        kd_poli.on('change', (e) => {
            const kdPoli = e.currentTarget.value
            const tgl = splitTanggal(tglReg.val());
            setNoReg({
                tgl_registrasi: tgl,
                kd_poli: kdPoli,
                kd_dokter: kd_dokter.val(),
            }).done((response) => {
                noReg.val(response)
            })
        })

        kd_dokter.on('change', (e) => {
            const kdDokter = e.currentTarget.value
            const tgl = splitTanggal(tglReg.val());
            setNoReg({
                tgl_registrasi: tgl,
                kd_poli: kd_poli.val(),
                kd_dokter: kdDokter,
            }).done((response) => {
                noReg.val(response)
            })
        })

        tglReg.on('change', (e) => {
            const tgl = splitTanggal(e.currentTarget.value)
            setNoReg({
                tgl_registrasi: tgl,
                kd_poli: kd_poli.val(),
                kd_dokter: kd_dokter.val(),
            }).done((response) => {
                noReg.val(response)
            })
        })

        function setNoReg(data = {}) {
            const setNoReg = $.get(`/efktp/registrasi/set/noreg`, {
                tgl_registrasi: data.tgl_registrasi,
                kd_poli: data.kd_poli,
                kd_dokter: data.kd_dokter,
            })
            return setNoReg;

        }

        function registrasiPoli(no_rkm_medis = '', noUrut = '') {
            $.get(`/efktp/pasien`, {
                no_rkm_medis: no_rkm_medis,
            }).done((response) => {
                formRegistrasiPoli.find('input').prop('disabled', false)
                if (Object.values(response.reg_periksa).length) {
                    formRegistrasiPoli.find('input[name=status]').val("Lama")
                } else {
                    formRegistrasiPoli.find('input[name=status]').val("Baru")
                }

                console.log('RESPINSE ===', response)
                formRegistrasiPoli.find('input[name=nm_pasien]').val(response.nm_pasien)
                formRegistrasiPoli.find('input[name=no_rkm_medis]').val(no_rkm_medis)
                formRegistrasiPoli.find('input[name=hubunganpj]').val(response.keluarga)
                formRegistrasiPoli.find('input[name=p_jawab]').val(response.namakeluarga)
                formRegistrasiPoli.find('input[name=alamatpj]').val(response.alamatpj)
                formRegistrasiPoli.find('input[name=no_peserta]').val(response.penjab.png_jawab.includes('BPJS') ? response.no_peserta : '-')
                formRegistrasiPoli.find('input[name=tgl_lahir]').val(response.tgl_lahir);
                formRegistrasiPoli.find('input[name=umur]').val(setUmur(response.tgl_lahir));

                refreshTime();
                setNoRawat().done((response) => {
                    formRegistrasiPoli.find('input[name=no_rawat]').val(response)
                });
                setNoReg().done((response) => {
                    formRegistrasiPoli.find('input[name=no_reg]').val(response)
                });


                if (!isDokter) {
                    const optionDokter = new Option('-', '-', true, true);
                    kd_dokter.append(optionDokter).trigger('change');
                } else {
                    const optionDokter = new Option(`${isDokter.kd_dokter} - ${isDokter.nm_dokter}`, isDokter.kd_dokter, true, true);
                    kd_dokter.append(optionDokter).trigger('change');
                }


                const optPoli = new Option(`U0009 - Poliklinik Umum`, `U0009`, true, true);
                const pj = new Option(`${response.kd_pj} - ${response.penjab.png_jawab}`, response.kd_pj, true, true);
                formRegistrasiPoli.find('input[name=umurdaftar]').val(setUmurDaftar(response.tgl_lahir))

                formRegistrasiPoli.find('select[name=kd_pj]').append(pj).trigger('change');
                formRegistrasiPoli.find('select[name=kd_poli]').append(optPoli).trigger('change');

                if (noUrut) {
                    periksaPendaftaran.removeClass('d-none');
                    response['noUrut'] = noUrut;
                    const kdPoli = formRegistrasiPoli.find('select[name=kd_poli]').val();
                    formRegistrasiPoli.find('input[name=bridging]').val(true);
                    formRegistrasiPoli.find('input[name=noUrut]').val(noUrut);
                    setMappingPoliPcare(kdPoli)
                    selectMappingDokterPcare(kd_dokter, modalRegistrasi)
                    setMappingDokterPcare(kd_dokter.val())
                } else if (response.penjab.png_jawab.includes('BPJS')) {
                    periksaPendaftaran.removeClass('d-none');
                    const kdPoli = formRegistrasiPoli.find('select[name=kd_poli]').val();
                    setMappingPoliPcare(kdPoli)
                    selectMappingDokterPcare(kd_dokter, modalRegistrasi)
                    setMappingDokterPcare(kd_dokter.val())
                } else {
                    periksaPendaftaran.addClass('d-none');
                }
            })
            modalRegistrasi.find('.modal-title').html('Registrasi Poliklinik')
            modalRegistrasi.modal('show')

        }

        function setUmur(tgl_lahir) {
            const umur = hitungUmur(tgl_lahir);
            return `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`
        }

        function formatUmurDaftar(umur) {
            return `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`
        }

        selectPoliklinikReg.on('select2:select', (e) => {
            $.get(`/efktp/mapping/pcare/poliklinik`, {
                kdPoli: e.currentTarget.value
            }).done((response) => {
                formRegistrasiPoli.find('input[name=kd_poli_pcare]').val(response.kd_poli_pcare)
                formRegistrasiPoli.find('input[name=nm_poli_pcare]').val(response.nm_poli_pcare)
            })
        })

        function setMappingPoliPcare(kdPoli) {
            $.get(`/efktp/mapping/pcare/poliklinik`, {
                kdPoli: kdPoli,
            }).done((resultPoli) => {
                formRegistrasiPoli.find('input[name=kd_poli_pcare]').val(resultPoli.kd_poli_pcare)
                formRegistrasiPoli.find('input[name=nm_poli_pcare]').val(resultPoli.nm_poli_pcare)
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function setMappingDokterPcare(kdDokter) {
            loadingAjax('Sedang mengambil data dokter Pcare')
            $.get(`/efktp/mapping/pcare/dokter`, {
                kdDokter: kdDokter
            }).done((resDokter) => {
                let mappingDokterPcare;
                if (Object.keys(resDokter).length) {
                    mappingDokterPcare = new Option(`${resDokter.kd_dokter} - ${resDokter.nm_dokter_pcare}`, `${resDokter.kd_dokter}`, true, true);
                } else {
                    mappingDokterPcare = new Option(`-`, `-`, true, true);
                }
                formRegistrasiPoli.find('select[name=kd_dokter]').append(mappingDokterPcare).trigger('change');
                formRegistrasiPoli.find('input[name=kd_dokter_pcare]').val(resDokter.kd_dokter_pcare);
                loadingAjax().close();
            }).fail((error) => {
                alertErrorBpjs(error)
            })
        }
    </script>
@endpush

<div class="modal modal-blur fade" id="modalPenilaianAwalKeperawatan" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Penilaian Awal Keperawatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <div class="alert alert-success d-none" role="alert" id="alertPenilaian">
                    <h4 class="alert-title">Sudah dilakukan penilaian awal pada</h4>
                    <div class="text-secondary">
                        <p id="tgl_penilaian" class="m-0"></p>
                    </div>
                </div>
                <form action="" id="formPenilaianAwalKeperawatan">
                    <fieldset class="form-fieldset">
                        <div class="row gy-2">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label for="no_rawat" class="form-label">No. Rawat</label>
                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="pasien" class="form-label">Pasien</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="no_rkm_medis" name="no_rkm_medis" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly>
                                    <input type="text" class="form-control w-50" id="pasien" name="pasien" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label for="tgl_lahir" class="form-label">Tgl. Lahir</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label for="informasi" class="form-label">Petugas</label>
                                <input type="text" class="form-control" id="petugas" name="petugas" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly value="{{ session()->get('pegawai')->nama }}">
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label for="informasi" class="form-label">Anamnesa</label>
                                <select class="form-select" name="informasi" id="informasi">
                                    <option value="Autoanamnesis">Autoanamnesis</option>
                                    <option value="Alloanamnesis">Alloanamnesis</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="separator mt-2">1. Keadaan Umum <a href="javascript:void(0)" onclick="" id="btnPemeriksaanRalan">
                            <i class="ti ti-search ms-2" font-size: 1em;></i>
                        </a></div>
                    <div class="row gy-2">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <label for="keluhan" class="form-label">Keluhan</label>
                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" onfocus="return removeZero(this)" onblur="isEmpty(this)">-</textarea>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <label for="rpo" class="form-label">Obat yang sering dikonsumsi</label>
                            <textarea class="form-control" id="rpo" name="rpo" onfocus="return removeZero(this)" onblur="isEmpty(this)">-</textarea>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="td" class="form-label">Tensi</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="td" name="td" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">mmHg</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="nadi" class="form-label">Nadi</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="nadi" name="nadi" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">x/mnt</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="rr" class="form-label">Respirasi</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="rr" name="rr" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">x/mnt</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="suhu" class="form-label">Suhu</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="suhu" name="suhu" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="alergi" class="form-label">Alergi</label>
                            <input type="text" class="form-control" id="alergi" name="alergi" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="alergi" class="form-label">Asesmen Nyeri</label>
                            <select name="nyeri" id="nyeri" class="form-select">
                                <option value="Tidak" selected>Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="separator mt-2">2. Status Nutrisi</div>
                    <div class="row gy-2">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="bb" class="form-label">BB</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="bb" name="bb" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="tb" class="form-label">TB</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="tb" name="tb" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="bmi" class="form-label">BMI</label>
                            <div class="input-group input-group-flat">
                                <input type="text" class="form-control" id="bmi" name="bmi" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                                <span class="input-group-text">Kg/m²</span>
                            </div>
                        </div>
                    </div>
                    <div class="separator mt-2">3. Psikososial & Ekonomi</div>
                    <div class="row gy-2">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="status_psiko" class="form-label">Status Psikologis</label>
                            <select class="form-select" name="status_psiko" id="status_psiko">
                                <option value="Tenang" selected>Tenang</option>
                                <option value="Cemas">Cemas</option>
                                <option value="Takut">Takut</option>
                                <option value="Sedih">Sedih</option>
                                <option value="Depresi">Depresi</option>
                                <option value="Marah">Marah</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="hub_keluarga" class="form-label">Hubungan pasien dengan anggota keluarga</label>
                            <select class="form-select" name="hub_keluarga" id="hub_keluarga">
                                <option value="Baik" selected>Baik</option>
                                <option value="Tidak Baik">Tidak Baik</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="ekonomi" class="form-label">Status Ekonomi</label>
                            <select class="form-select" name="ekonomi" id="ekonomi">
                                <option value="Baik" selected>Baik</option>
                                <option value="Cukup">Cukup</option>
                                <option value="Kurang">Kurang</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly value="-">
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="pendidikan" class="form-label">Pendidikan</label>
                            <input type="text" class="form-control" id="pendidikan" name="pendidikan" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly value="-">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label for="ket_budaya" class="form-label">Kepercayaan/Budaya/Nilai kepercayaan yang diperhatikan </label>
                            <textarea class="form-control" id="ket_budaya" name="ket_budaya" onfocus="return removeZero(this)" onblur="isEmpty(this)">-</textarea>
                        </div>

                    </div>
                    <div class="separator mt-2">4. Fungsional</div>
                    <div class="row gy-2">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="alat_bantu" class="form-label">Alat Bantu</label>
                            <div class="input-group">
                                <select class="form-select" id="alat_bantu" name="alat_bantu">
                                    <option value="Tidak Ada" selected>Tidak Ada</option>
                                    <option value="Ya">Ya</option>
                                </select>
                                <input type="text" class="form-control w-25" id="ket_bantu" name="ket_bantu" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="-">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="cacat_fisik" class="form-label">Cacat Fisik</label>
                            <input type="text" class="form-control" id="cacat_fisik" name="cacat_fisik" onfocus="return removeZero(this)" onblur="isEmpty(this)" readonly value="-">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="adl" class="form-label">ADL</label>
                            <select class="form-select" id="adl" name="adl">
                                <option value="Mandiri" selected>Mandiri</option>
                                <option value="Dibantu">Dibantu</option>
                            </select>
                        </div>
                    </div>
                    <div class="separator mt-2">5. Skrining Gizi</div>
                    <div class="row gy-2 mt-2">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <label for="sg1" class="form-label mt-3">Apakah ada penurunan berat badan dalam kurun waktu 6 bulan terakhir ?</label>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <select name="sg1" id="sg1" class="form-select">
                                <option data-nilai="0" value="Tidak" selected>Tidak</option>
                                <option data-nilai="2" value="Tidak Yakin">Tidak Yakin</option>
                                <option data-nilai="1" value="Ya, 1-5 Kg">Ya, 1-5 Kg</option>
                                <option data-nilai="2" value="Ya, 6-10 Kg">Ya, 6-10 Kg</option>
                                <option data-nilai="3" value="Ya, 11-15 Kg">Ya, 11-15 Kg</option>
                                <option data-nilai="4" value="Ya, >15 Kg">Ya, 11-15 Kg</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <input type="text" class="form-control" id="n1" name="n1" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="0">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <label for="sg2" class="form-label mt-3">Apakah ada penurunan nafsu makan ?</label>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <select name="sg2" id="sg2" class="form-select">
                                <option data-nilai="0" value="Tidak" selected>Tidak</option>
                                <option data-nilai="1" value="Ya">Ya</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <input type="text" class="form-control" id="n2" name="n2" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="0">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 d-flex ms-auto">
                            <input type="text" class="form-control" id="total_hasil" name="total_hasil" onfocus="return removeZero(this)" onblur="isEmpty(this)" value="0">
                        </div>
                    </div>
                    <div class="separator mt-2">5. Penilaian Resiko Jatuh</div>
                    <div class="row gy-2">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <label for="resiko_jatuh" class="form-label">Penilaian resiko jatuh Get Up and Go</label>
                            <select name="resiko_jatuh" id="resiko_jatuh" class="form-select">
                                <option value="Tidak" selected>Tidak</option>
                                <option value="Ya">Ya</option>
                            </select>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnCetak" onclick="printPenilaianAwalKeperawatan()"><i class="ti ti-printer me-1"></i> Cetak</button>
                <button type="button" class="btn btn-success" onclick="simpanPenilaianAwalKeperawatan()"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modalCetakPenilaian" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Penilaian Awal Keperawatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <iframe id="print" type="" width="100%" height="600"></iframe>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var modalPenilaianAwalKeperawatan = $('#modalPenilaianAwalKeperawatan')
        var modalCetakPenilaian = $('#modalCetakPenilaian')

        function penilaianAwalKeperawatan(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                modalPenilaianAwalKeperawatan.find('input[name="no_rawat"]').val(response.no_rawat);
                modalPenilaianAwalKeperawatan.find('input[name="no_rkm_medis"]').val(response.no_rkm_medis);
                modalPenilaianAwalKeperawatan.find('input[name="pasien"]').val(`${response.pasien.nm_pasien} (${response.pasien.jk})`);
                modalPenilaianAwalKeperawatan.find('input[name="tgl_lahir"]').val(`${splitTanggal(response.pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`);
                modalPenilaianAwalKeperawatan.find('input[name="pekerjaan"]').val(response.pasien.pekerjaan);
                modalPenilaianAwalKeperawatan.find('input[name="pendidikan"]').val(response.pasien.pnd);
                modalPenilaianAwalKeperawatan.find('input[name="cacat_fisik"]').val(response.pasien.cacat_fisik.nama_cacat);
                modalPenilaianAwalKeperawatan.modal('show');

            })
            getPenilaianAwalKeperawatan(no_rawat).done((response) => {
                if (Object.values(response).length) {
                    console.log('GET PENILAIAN == ', response);
                    modalPenilaianAwalKeperawatan.find("#keluhan_utama").val(response.keluhan_utama)
                    $("#alertPenilaian").removeClass('d-none')
                    $("#tgl_penilaian").html(response.tanggal)
                    modalPenilaianAwalKeperawatan.find("#btnCetak").removeClass('d-none')

                    modalPenilaianAwalKeperawatan.find("#td").val(response.td)
                    modalPenilaianAwalKeperawatan.find("#petugas").val(response.pegawai.nama)
                    modalPenilaianAwalKeperawatan.find("#nadi").val(response.nadi)
                    modalPenilaianAwalKeperawatan.find("#rr").val(response.rr)
                    modalPenilaianAwalKeperawatan.find("#suhu").val(response.suhu)
                    modalPenilaianAwalKeperawatan.find("#alergi").val(response.alergi)
                    modalPenilaianAwalKeperawatan.find("#bb").val(response.bb)
                    modalPenilaianAwalKeperawatan.find("#tb").val(response.tb)
                    modalPenilaianAwalKeperawatan.find("#bmi").val(hitungBmi(response.bb, response.tb))
                    modalPenilaianAwalKeperawatan.find("#status_psiko").val(response.status_psiko).change();
                    modalPenilaianAwalKeperawatan.find("#hub_keluarga").val(response.hub_keluarga).change();
                    modalPenilaianAwalKeperawatan.find("#ekonomi").val(response.ekonomi).change();
                    modalPenilaianAwalKeperawatan.find("#ket_budaya").val(response.ket_budaya);
                    modalPenilaianAwalKeperawatan.find("#alat_bantu").val(response.alat_bantu).change();
                    modalPenilaianAwalKeperawatan.find("#ket_bantu").val(response.ket_bantu);
                    modalPenilaianAwalKeperawatan.find("#adl").val(response.adl);
                    modalPenilaianAwalKeperawatan.find("#sg1").val(response.sg1).change();
                    modalPenilaianAwalKeperawatan.find("#sg2").val(response.sg2).change();
                    modalPenilaianAwalKeperawatan.find("#n1").val(response.nilai1);
                    modalPenilaianAwalKeperawatan.find("#n2").val(response.nilai2);
                    modalPenilaianAwalKeperawatan.find("#total_hasil").val(response.total_hasil);
                } else {
                    $("#alertPenilaian").addClass('d-none')
                    getPemeriksaanRalan(no_rawat).done((response) => {
                        modalPenilaianAwalKeperawatan.find("#btnCetak").addClass('d-none')
                        $("#tgl_penilaian").html('')
                        if (response.length) {
                            response.map((item) => {
                                modalPenilaianAwalKeperawatan.find("#keluhan_utama").val(item.keluhan)
                                modalPenilaianAwalKeperawatan.find("#td").val(item.tensi)
                                modalPenilaianAwalKeperawatan.find("#nadi").val(item.nadi)
                                modalPenilaianAwalKeperawatan.find("#rr").val(item.respirasi)
                                modalPenilaianAwalKeperawatan.find("#suhu").val(item.suhu_tubuh)
                                modalPenilaianAwalKeperawatan.find("#alergi").val(item.alergi)
                                modalPenilaianAwalKeperawatan.find("#bb").val(item.berat)
                                modalPenilaianAwalKeperawatan.find("#tb").val(item.tinggi)
                                modalPenilaianAwalKeperawatan.find("#bmi").val(hitungBmi(item.berat, item.tinggi))
                            })
                        }
                    });
                }
            })
        }

        $('#sg1').on('change', (e) => {
            const selectedOption = $('#sg1 option:selected');
            const dataNilai = selectedOption.data('nilai');
            $('#n1').val(dataNilai);
            const totalHasil = parseInt($('#n2').val()) + parseInt($('#n1').val());
            $('#total_hasil').val(totalHasil);
        })

        $('#sg2').on('change', (e) => {
            const selectedOption = $('#sg2 option:selected');
            console.log(selectedOption);
            const dataNilai = selectedOption.data('nilai');
            $('#n2').val(dataNilai);
            const totalHasil = parseInt($('#n2').val()) + parseInt($('#n1').val());
            $('#total_hasil').val(totalHasil);
        })



        function simpanPenilaianAwalKeperawatan() {
            const data = getDataForm('formPenilaianAwalKeperawatan', ['input', 'select', 'textarea']);
            $.post(`/efktp/penilaian/awal/keperawatan/ralan`, data).done((response) => {
                const tanggal = new Date()
                $('#alertPenilaian').removeClass('d-none')
                $('#alertPenilaian').find('#tgl_penilaian').html("{{ date('Y-m-d H:i:s') }}")
                modalPenilaianAwalKeperawatan.find('#btnCetak').removeClass('d-none');
                alertSuccessAjax();
            }).fail((request) => {
                alertErrorAjax(request)
            })
        }

        function getPenilaianAwalKeperawatan(no_rawat) {
            const penilaian = $.get(`/efktp/penilaian/awal/keperawatan/ralan`, {
                no_rawat: no_rawat
            })
            return penilaian;
        }
        $('#bmi').on('focus', () => {
            const bb = modalPenilaianAwalKeperawatan.find('#bb').val();
            const tb = modalPenilaianAwalKeperawatan.find('#tb').val();
            $('#bmi').val(hitungBmi(bb, tb))
        })

        function printPenilaianAwalKeperawatan() {
            const no_rawat = modalPenilaianAwalKeperawatan.find('#no_rawat').val();
            getPenilaianAwalKeperawatan(no_rawat).done((response) => {
                modalCetakPenilaian.modal('show')
                modalCetakPenilaian.find('#print').attr('src', `/efktp/penilaian/awal/keperawatan/ralan/print?no_rawat=${no_rawat}`);

            })
        }
        modalPenilaianAwalKeperawatan.on('hidden.bs.modal', () => {
            $('#formPenilaianAwalKeperawatan').trigger('reset')
        })
    </script>
@endpush

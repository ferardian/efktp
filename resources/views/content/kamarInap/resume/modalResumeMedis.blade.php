<div class="modal modal-blur fade" id="modalResumeMedis" tabindex="-1" aria-modal="false" role="dialog"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Resume Medis Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <form action="" id="formResumeMedis">
                    <div class="row gy-2">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="row gy-2">
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-3">
                                    <label for="pasien">No. Rawat</label>
                                    <input type="text" class="form-control" id="no_rawat" name="no_rawat" readonly />
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-5">
                                    <label for="pasien">Pasien</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="no_rkm_medis" name="no_rkm_medis" readonly />
                                        <input type="text" class="form-control w-50" id="pasien" name="pasien" readonly />
                                    </div>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-4">
                                    <label for="pasien">Tgl. Lahir</label>
                                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" readonly />
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="row gy-2">
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="dokter">Dokter</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            id="kd_dokter" name="kd_dokter" readonly>
                                        <input type="text" class="form-control w-50"
                                            id="dokter" name="dokter" readonly>
                                    </div>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="kamar">Kamar Pembiayaan</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kd_kamar"
                                            name="kd_kamar" readonly />
                                        <input type="text" class="form-control w-50" id="kamar"
                                            name="kamar" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="row gy-2">
                                <div class="mb-2 col-sm-12 col-md-4 col-lg-2">
                                    <label for="tgl_masuk">Tgl. Masuk</label>
                                    <input type="text" class="form-control"
                                        id="tgl_masuk" name="tgl_masuk" readonly />
                                </div>
                                <div class="mb-2 col-sm-12 col-md-4 col-lg-2">
                                    <label for="tgl_masuk">Tgl. Keluar</label>
                                    <input type="text" class="form-control"
                                        id="tgl_keluar" name="tgl_keluar" readonly />
                                </div>
                                <div class="mb-2 col-sm-12 col-md-4 col-lg-3">
                                    <label for="diagnosa_awal">Diagnosa Awal</label>
                                    <input type="text" class="form-control"
                                        id="diagnosa_awal" name="diagnosa_awal" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-" readonly />
                                </div>
                                <div class="mb-2 col-sm-12 col-md-4 col-lg-3">
                                    <label for="alasan">Indikasi Rawat</label>
                                    <input type="text" class="form-control"
                                        id="alasan" name="alasan" onfocus="removeZero(this)" onblur="isEmpty(this)"
                                        value="-" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 col-sm-12 col-md-12 col-lg-7">
                            <div class="separator m-2">1. Riwayat Kesehatan</div>
                            <div class="row gy-2">
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="keluhan">Keluhan Utama <a href="javascript:void(0)" id="srcKeluhan"
                                            onclick=" listKeluhanRanap('keluhan')"><i
                                                class="ti ti-search"></i></a></label>
                                    <textarea class="form-control" name="keluhan_utama" id="keluhan" cols="30"
                                        rows="10" onfocus="removeZero(this)" onblur="isEmpty(this)">-</textarea>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="pemeriksaan_fisik">Pemeriksaan Fisik <a href="javascript:void(0)"
                                            id="srcPemeriksaan"
                                            onclick="listObjektifRanap()"><i
                                                class="ti ti-search"></i></a></label>
                                    <textarea class="form-control" name="pemeriksaan_fisik" id="pemeriksaan_fisik" cols="30"
                                        rows="10" onfocus="removeZero(this)" onblur="isEmpty(this)">-</textarea>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="pemeriksaan_penunjang" id="srcRadiologi"
                                        onclick="listHasilRadiologi('2024/01/07/000515', '063263', 'IGDK')">Pemeriksaan
                                        Radiologi Terpenting <a href="javascript:void(0)" id="srcRadiologi"
                                            onclick="listHasilRadiologi('2024/01/07/000515', '063263', 'IGDK')"><i
                                                class="ti ti-search"></i></a></label>
                                    <textarea class="form-control" name="pemeriksaan_penunjang" id="pemeriksaan_penunjang"
                                        cols="30" rows="10" onfocus="removeZero(this)"
                                        onblur="isEmpty(this)">-</textarea>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="hasil_lanorat">Pemeriksaan Laborat Terpenting <a href="javascript:void(0)"
                                            id="srcLab"
                                            onclick="listHasilLab('2024/01/07/000515', '063263', 'IGDK')"><i
                                                class="ti ti-search"></i></a></label>
                                    <textarea class="form-control" name="hasil_laborat" id="hasil_laborat" cols="30"
                                        rows="10" onfocus="removeZero(this)" onblur="isEmpty(this)">-</textarea>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="tindakan_dan_operasi">Tindakan/Operasi Selama Perawatan </label>
                                    <textarea class="form-control" name="tindakan_dan_operasi" id="tindakan_dan_operasi"
                                        cols="30" rows="10" onfocus="removeZero(this)"
                                        onblur="isEmpty(this)">-</textarea>
                                </div>
                                <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                    <label for="obat_di_rs">Obat-obatan Selama Perwatan <a href="javascript:void(0)"
                                            id="srcObat"
                                            onclick="listRiwayatPemeriksaan('2024/01/07/000515', 'obat')"><i
                                                class="ti ti-search"></i></a></label>
                                    <textarea class="form-control" name="obat_di_rs" id="obat_di_rs" cols="30" rows="10"
                                        onfocus="removeZero(this)" onblur="isEmpty(this)">-</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-5">
                            <div class="separator m-2">2. Diagnosa Akhir</div>
                            <div class="row gy-2">
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="diagnosa_utama" class="mt-2">Diagnosa Utama</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectPenyakitDiagnosa" id="diagnosa_utama" name="diagnosa_utama" data-target="kd_diagnosa_utama" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="kd_diagnosa_utama" id="kd_diagnosa_utama" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="diagnosa_sekunder" class="mt-2">Diagnosa sekunder 1</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectPenyakitDiagnosa" id="diagnosa_sekunder" name="diagnosa_sekunder" data-target="kd_diagnosa_sekunder" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control" name="kd_diagnosa_sekunder" id="kd_diagnosa_sekunder" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>

                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="diagnosa_sekunder2" class="mt-2">Diagnosa sekunder 2</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectPenyakitDiagnosa" id="diagnosa_sekunder2" name="diagnosa_sekunder2" data-target="kd_diagnosa_sekunder2" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control" name="kd_diagnosa_sekunder2" id="kd_diagnosa_sekunder2" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="diagnosa_sekunder3" class="mt-2">Diagnosa sekunder 3</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectPenyakitDiagnosa" id="diagnosa_sekunder3" name="diagnosa_sekunder3" data-target="kd_diagnosa_sekunder3" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input class="form-control" name="kd_diagnosa_sekunder3" id="kd_diagnosa_sekunder3" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>

                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="diagnosa_sekunder4" class="mt-2">Diagnosa sekunder 4</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectPenyakitDiagnosa" id="diagnosa_sekunder4" name="diagnosa_sekunder4" data-target="kd_diagnosa_sekunder4" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control" name="kd_diagnosa_sekunder4" id="kd_diagnosa_sekunder4" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>
                            </div>
                            <div class="separator m-2">3. Prosedur</div>
                            <div class="row gy-2">
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="prosedur_utama" class="mt-2">Prosedur Utama</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectProsedurResume" id="prosedur_utama" name="prosedur_utama" data-target="kd_prosedur_utama" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control " name="kd_prosedur_utama" id="kd_prosedur_utama" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>

                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="prosedur_sekunder" class="mt-2">Prosedur Sekunder</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectProsedurResume" id="prosedur_sekunder" name="prosedur_sekunder" data-target="kd_prosedur_sekunder" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control " name="kd_prosedur_sekunder" id="kd_prosedur_sekunder" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>

                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="prosedur_sekunder2" class="mt-2">Prosedur Sekunder 2</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectProsedurResume" id="prosedur_sekunder2" name="prosedur_sekunder2" data-target="kd_prosedur_sekunder2" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control " name="kd_prosedur_sekunder2" id="kd_prosedur_sekunder2" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>

                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <label for="prosedur_sekunder3" class="mt-2">Prosedur Sekunder 3</label>
                                </div>
                                <div class="col-sm-12 col-md-7 col-lg-6 mb-2">
                                    <select class="form-select selectProsedurResume" id="prosedur_sekunder3" name="prosedur_sekunder3" data-target="kd_prosedur_sekunder3" style="width: 100%"></select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <input type="text" class="form-control " name="kd_prosedur_sekunder3" id="kd_prosedur_sekunder3" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                </div>
                            </div>

                            <div class="row gy-2">
                                <div class="separator mt-2 mb-2"></div>
                                <div class="mb-2 col-sm-12 col-md-12 col-lg-12">
                                    <label for="edukasi">Instruksi/Anjuran dan Edukasi (Follow up)</label>
                                    <textarea class="form-control" name="edukasi" id="edukasi" cols="30" rows="5"
                                        onfocus="removeZero(this)" onblur="isEmpty(this)">-</textarea>
                                </div>
                                <div class="row gy-2">
                                    <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                        <label for="keadaan">Kondisi Pulang</label>
                                        <div class="input-group">
                                            <select class="form-select" name="keadaan" id="keadaan">
                                                <option value="Membaik">Membaik</option>
                                                <option value="Sembuh">Sembuh</option>
                                                <option value="Keadaan Khusus">Keadaan Khusus</option>
                                                <option value="Meninggal">Meninggal</option>
                                            </select>
                                            <label for="ket_keadaan"></label>
                                            <select class="form-select" name="ket_keadaan" id="ket_keadaan">
                                                <option value="SANAM">SANAM</option>
                                                <option value="BONAM">BONAM</option>
                                                <option value="MALAM">MALAM</option>
                                                <option value="BUBIA">BUBIA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-sm-12 col-md-6 col-lg-6">
                                        <label for="cara_keluar">Cara Keluar</label>
                                        <div class="input-group">
                                            <select class="form-select w-25" name="cara_keluar" id="cara_keluar">
                                                <option value="Atas Izin Dokter">Atas Izin Dokter</option>
                                                <option value="Pindah RS">Pindah RS</option>
                                                <option value="Pulang Atas Permintaan Sendiri">Pulang Atas Permintaan Sendiri
                                                </option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                            <label for="ket_keluar"></label>
                                            <input class="form-control" name="ket_keluar" id="ket_keluar" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gy-2">
                                <div class="mb-2 col-sm-12 col-md-12 col-lg-7">
                                    <label for="dilanjutkan">Dilanjutkan</label>
                                    <div class="input-group">
                                        <select class="form-select" name="dilanjutkan" id="dilanjutkan">
                                            <option value="Kembali Ke RS">Kembali Ke RS</option>
                                            <option value="RS Lain">RS Lain</option>
                                            <option value="Dokter Luar">Dokter Luar</option>
                                            <option value="Puskesmes">Puskesmas</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <label for="ket_dilanjutkan"></label>
                                        <input class="form-control w-25" name="ket_dilanjutkan" id="ket_dilanjutkan" onfocus="removeZero(this)" onblur="isEmpty(this)" value="-">
                                    </div>
                                </div>

                                <div class="mb-2 col-sm-12 col-md-6 col-lg-3">
                                    <label for="tgl_kontrol">Tanggal Kontrol</label>
                                    <div class="input-group">
                                        <input class="form-control filterTangal" name="kontrol" id="kontrol" onfocus="removeZero(this)" onblur="isEmpty(this)">
                                    </div>
                                </div>

                            </div>
                            <div class="row gy-2">
                                <div class="mb-2 col-sm-12 col-md-12 col-lg-12">
                                    <label for="obat_pulang">Obat Pulang <a href="javascript:void(0)" id="srcObatPulang" onclick="listRiwayatPemeriksaan('2024/01/07/000515', 'obatpulang')"><i class="ti ti-search"></i></a></label>
                                    <textarea class="form-control" name="obat_pulang" id="obat_pulang" cols="30" rows="5"
                                        onfocus="removeZero(this)" onblur="isEmpty(this)">-</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
                <button type="button" class="btn btn-success" onclick="createResumeMedis()"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>

@include('content.kamarInap.resume.sub.listPemeriksaanRanap')
@push('script')
    <script>
        let modalResumeMedis = $('#modalResumeMedis');
        let formResumeMedis = $('#formResumeMedis');
        let selectPenyakitDiagnosa = $('.selectPenyakitDiagnosa');
        let selectProsedurResume = $('.selectProsedurResume');

        function resumeMedis(no_rawat) {

            getRegDetail(no_rawat).done((response) => {
                formResumeMedis.find('input[name="no_rawat"]').val(response.no_rawat)
                formResumeMedis.find('input[name="no_rkm_medis"]').val(response.no_rkm_medis)
                formResumeMedis.find('input[name="pasien"]').val(`${response.pasien.nm_pasien} (${response.pasien.jk})`);
                formResumeMedis.find('input[name="tgl_lahir"]').val(`${formatTanggal(response.pasien.tgl_lahir)} (${response.umurdaftar} ${response.sttsumur})`);
                formResumeMedis.find('input[name="kd_dokter"]').val(response.kd_dokter);
                formResumeMedis.find('input[name="dokter"]').val(response.dokter.nm_dokter);
                formResumeMedis.find('input[name="kd_kamar"]').val(response.kamar_inap.kd_kamar);
                formResumeMedis.find('input[name="kamar"]').val(response.kamar_inap.kamar.bangsal.nm_bangsal);
                formResumeMedis.find('input[name="tgl_masuk"]').val(`${formatTanggal(response.kamar_inap.tgl_masuk)} ${response.kamar_inap.jam_masuk} `);
                formResumeMedis.find('input[name="tgl_keluar"]').val(`${response.kamar_inap.tgl_keluar != '0000-00-00' ? `${formatTanggal(response.kamar_inap.tgl_keluar)} ${response.kamar_inap.jam_keluar}` : '-'}`);
                formResumeMedis.find('input[name="diagnosa_awal"]').val(response.kamar_inap.diagnosa_awal);
                modalResumeMedis.modal('show');

            })

            $.get(`/efktp/resume/medis`, {
                no_rawat: no_rawat,
            }).done((response) => {
                const option = new Option('-', '-', true, true);
                selectPenyakitDiagnosa.append(option).trigger('change');
                const penyakit = new Option('-', '-', true, true);
                selectProsedurResume.append(penyakit).trigger('change');
                if (Object.keys(response).length) {
                    setResponseToForm(response, formResumeMedis, 'input', 'textarea', 'select');
                    $('.selectPenyakitDiagnosa').each((index, element) => {
                        const diagnosa = new Option(`${response[element.name]}`, `${response[element.name]}`, true, true);
                        formResumeMedis.find(`select[name="${element.name}"]`).append(diagnosa).trigger('change');
                    })
                    $('.selectProsedurResume').each((index, element) => {
                        const prosedur = new Option(`${response[element.name]}`, `${response[element.name]}`, true, true);
                        formResumeMedis.find(`select[name="${element.name}"]`).append(prosedur).trigger('change');
                    })
                    formResumeMedis.find('input[name="kontrol"]').val(splitTanggal(response.kontrol.split(' ')[0]))
                }
            })
        }

        function setResponseToForm(response, formId, ...element) {
            element.forEach((el, index) => {
                Object.keys(response).map((key, index) => {
                    const findElement = formId.find(`${el}[name="${key}"]`)
                    findElement.val(response[`${key}`]);
                });
            })
        }

        function getDiagnosaRanap(params) {
            $.get(`/efktp/penyakit/get`).done((response) => {})
        }

        function createResumeMedis() {
            const data = getDataForm('formResumeMedis', ['input', 'textarea', 'select']);
            $.post(`/efktp/resume/medis`, data).done((response) => {
                alertSuccessAjax();
                modalResumeMedis.modal('hide');
            })
        }

        modalResumeMedis.on('shown.bs.modal', () => {
            // const option = new Option('-', '-', true, true);
            // selectPenyakitDiagnosa.append(option).trigger('change');
            // const penyakit = new Option('-', '-', true, true);
            // selectProsedurResume.append(penyakit).trigger('change');

        })

        modalResumeMedis.on('hidden.bs.modal', () => {

            formResumeMedis.trigger('reset');


        })

        selectPenyakitDiagnosa.select2({
            dropdownParent: modalResumeMedis,
            delay: 0,
            tags: true,
            allowClear: true,
            placeholder: 'Pilih / Isikan Diagnosa',
            scrollAfterSelect: true,
            ajax: {
                url: `/efktp/penyakit/get`,
                dataType: 'JSON',

                data: (params) => {
                    const query = {
                        penyakit: params.term
                    }
                    return query
                },
                processResults: (data) => {
                    return {
                        results: data.map((item) => {
                            const items = {
                                id: item.nm_penyakit,
                                value: item.kd_penyakit,
                                text: `${item.kd_penyakit} - ${item.nm_penyakit}`,
                                detail: item
                            }
                            return items;
                        })
                    }
                }

            },
            cache: false,

        }).on('select2:select', (e) => {
            const kdDiagnosa = e.params.data.id === e.params.data.text ? '-' : e.params.data.value;
            $(`#${e.currentTarget.dataset.target}`).val(kdDiagnosa)
        }).on('select2:unselecting', (e) => {
            const option = new Option('-', '-', true, true);
            e.currentTarget.append(option).trigger('change');
            $(`#${e.currentTarget.dataset.target}`).val('-')
        });

        selectProsedurResume.select2({
            dropdownParent: modalResumeMedis,
            delay: 0,
            tags: true,
            allowClear: true,
            placeholder: 'Pilih / Isikan Diagnosa',
            scrollAfterSelect: true,
            ajax: {
                url: `/efktp/tindakan/get`,
                dataType: 'JSON',

                data: (params) => {
                    const query = {
                        kode: params.term
                    }
                    return query
                },
                processResults: (data) => {
                    return {
                        results: data.map((item) => {
                            const items = {
                                id: item.deskripsi_pendek,
                                value: item.kode,
                                text: `${item.kode} - ${item.deskripsi_pendek}`,
                                detail: item
                            }
                            return items;
                        })
                    }
                }

            },
            cache: true,
        }).on('select2:select', (e) => {
            const penyakit = e.params.data.id === e.params.data.text ? '-' : e.params.data.value;
            $(`#${e.currentTarget.dataset.target}`).val(penyakit)
        }).on('select2:unselecting', (e) => {
            const option = new Option('-', '-', true, true);
            e.currentTarget.append(option).trigger('change');
            $(`#${e.currentTarget.dataset.target}`).val('-')
        });
    </script>
@endpush

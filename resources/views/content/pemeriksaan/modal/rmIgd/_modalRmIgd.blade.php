<div class="modal modal-blur fade" id="modalRmIgd" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">RM Gawat Darurat (Triase & Asesmen Medis IGD)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formRmIgd">
                    <fieldset class="form-fieldset">
                        <div class="row gy-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="no_rawat" class="form-label">No. Rawat</label>
                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" readonly>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="pasien" class="form-label">Pasien</label>
                                <div class="input-group">
                                    <input type="text" class="form-control w-25" id="no_rkm_medis" name="no_rkm_medis" readonly>
                                    <input type="text" class="form-control w-50" id="nm_pasien" name="nm_pasien" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="kd_dokter" class="form-label">Dokter</label>
                                <select class="form-select" name="kd_dokter" id="kd_dokter">
                                    <option value="">Pilih Dokter</option>
                                    {{-- Will be populated via JS --}}
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    {{-- SECTION 1: TRIASE --}}
                    <div class="separator mt-3 text-primary font-weight-bold">I. TRIASE</div>
                    <div class="row gy-2 mt-1">
                        <div class="col-md-3">
                            <label class="form-label">Macam Kasus</label>
                            <select class="form-select" name="kode_kasus" id="kode_kasus">
                                {{-- Will be populated via JS --}}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Keluhan Utama (Triase)</label>
                            <textarea class="form-control" name="keluhan_utama_triase" id="keluhan_utama_triase" rows="2">-</textarea>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tanda Vital (Triase)</label>
                            <textarea class="form-control" name="tanda_vital" id="tanda_vital" rows="2">-</textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">Skala Triase</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="bg-danger text-white">SKALA 1</th>
                                            <th class="bg-warning text-dark">SKALA 2</th>
                                            <th class="bg-warning text-dark">SKALA 3</th>
                                            <th class="bg-success text-white">SKALA 4</th>
                                            <th class="bg-info text-white">SKALA 5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><div id="container-skala1" class="text-start p-1 small"></div></td>
                                            <td><div id="container-skala2" class="text-start p-1 small"></div></td>
                                            <td><div id="container-skala3" class="text-start p-1 small"></div></td>
                                            <td><div id="container-skala4" class="text-start p-1 small"></div></td>
                                            <td><div id="container-skala5" class="text-start p-1 small"></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: ASESMEN MEDIS --}}
                    <div class="separator mt-4 text-primary font-weight-bold">II. ASESMEN MEDIS IGD</div>
                    
                    <div class="row gy-2 mt-1">
                        <div class="col-lg-2">
                            <label class="form-label">Anamnesis</label>
                            <select class="form-select" name="anamnesis" id="anamnesis">
                                <option value="Autoanamnesis">Autoanamnesis</option>
                                <option value="Alloanamnesis">Alloanamnesis</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Hubungan</label>
                            <input type="text" class="form-control" name="hubungan" id="hubungan" value="-">
                        </div>
                        <div class="col-lg-8">
                            <label class="form-label">Keluhan Utama (Medis)</label>
                            <input type="text" class="form-control" name="keluhan_utama" id="keluhan_utama" value="-">
                        </div>
                    </div>

                    <div class="row gy-2 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">RPS</label>
                            <textarea class="form-control" name="rps" id="rps" rows="2">-</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">RPD</label>
                            <textarea class="form-control" name="rpd" id="rpd" rows="2">-</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">RPK</label>
                            <textarea class="form-control" name="rpk" id="rpk" rows="2">-</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">RPO & Alergi</label>
                            <textarea class="form-control" name="rpo_alergi" id="rpo_alergi" rows="2">-</textarea>
                        </div>
                    </div>

                    <div class="separator mt-3 small">Pemeriksaan Fisik</div>
                    <div class="row gy-2 mt-1">
                        <div class="col-md-2">
                            <label class="form-label">Kesadaran</label>
                            <select class="form-select" name="kesadaran" id="kesadaran">
                                <option value="Compos Mentis">Compos Mentis</option>
                                <option value="Apatis">Apatis</option>
                                <option value="Somnolen">Somnolen</option>
                                <option value="Sopor">Sopor</option>
                                <option value="Koma">Koma</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">GCS</label>
                            <input type="text" class="form-control" name="gcs" id="gcs" placeholder="E,V,M">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">TD</label>
                            <input type="text" class="form-control" name="td" id="td">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Nadi</label>
                            <input type="text" class="form-control" name="nadi" id="nadi">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">RR</label>
                            <input type="text" class="form-control" name="rr" id="rr">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Suhu</label>
                            <input type="text" class="form-control" name="suhu" id="suhu">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">SpO2</label>
                            <input type="text" class="form-control" name="spo2" id="spo2">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">BB/TB</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="bb" id="bb" placeholder="BB">
                                <input type="text" class="form-control" name="tb" id="tb" placeholder="TB">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Keadaan</label>
                            <input type="text" class="form-control" name="keadaan" id="keadaan" value="Baik">
                        </div>
                    </div>

                    <div class="row gy-2 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Kepala / Mata / Gigi</label>
                            <textarea class="form-control" name="kepala_mata_gigi" id="kepala_mata_gigi" rows="2">Normal</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Leher / Thoraks</label>
                            <textarea class="form-control" name="leher_thoraks" id="leher_thoraks" rows="2">Normal</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Abdomen / Genital</label>
                            <textarea class="form-control" name="abdomen_genital" id="abdomen_genital" rows="2">Normal</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ekstremitas</label>
                            <textarea class="form-control" name="ekstremitas" id="ekstremitas" rows="2">Normal</textarea>
                        </div>
                    </div>

                    <div class="row gy-2 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Keterangan Fisik</label>
                            <textarea class="form-control" name="ket_fisik" id="ket_fisik" rows="2">-</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Lokalis</label>
                            <textarea class="form-control" name="ket_lokalis" id="ket_lokalis" rows="2">-</textarea>
                        </div>
                    </div>

                    <div class="row gy-2 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Diagnosis</label>
                            <textarea class="form-control" name="diagnosis" id="diagnosis" rows="3">-</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tata Laksana</label>
                            <textarea class="form-control" name="tata" id="tata" rows="3">-</textarea>
                        </div>
                    </div>

                    <div class="row gy-2 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">EKG</label>
                            <input type="text" class="form-control" name="ekg" id="ekg" value="-">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Radiologi</label>
                            <input type="text" class="form-control" name="rad" id="rad" value="-">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Laboratorium</label>
                            <input type="text" class="form-control" name="lab" id="lab" value="-">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary ms-auto" onclick="simpanRmIgd()">
                    <i class="ti ti-device-floppy me-1"></i> Simpan RM IGD
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    var modalRmIgd = $('#modalRmIgd');

    function getDokter() {
        return $.get("{{ url('/dokter/get') }}");
    }

    function openRmIgd(no_rawat) {
        // Reset form
        $('#formRmIgd').trigger('reset');
        
        // Load data pasien & existing RM IGD
        getRegDetail(no_rawat).done((response) => {
            modalRmIgd.find('input[name="no_rawat"]').val(response.no_rawat);
            modalRmIgd.find('input[name="no_rkm_medis"]').val(response.no_rkm_medis);
            modalRmIgd.find('input[name="nm_pasien"]').val(response.pasien.nm_pasien);
            
            // Populate Dokter select
            getDokter().done((dokters) => {
                let html = '<option value="">Pilih Dokter</option>';
                dokters.forEach(d => {
                    let selected = (d.kd_dokter == response.kd_dokter) ? 'selected' : '';
                    html += `<option value="${d.kd_dokter}" ${selected}>${d.nm_dokter}</option>`;
                });
                modalRmIgd.find('#kd_dokter').html(html);
                if (modalRmIgd.find('#kd_dokter').hasClass('select2-hidden-accessible')) {
                    modalRmIgd.find('#kd_dokter').trigger('change');
                }
            }).fail((err) => {
                console.error("Failed to load doctors", err);
            });

            // Load existing data
            $.get("{{ url('/rm/igd') }}", { no_rawat: no_rawat }).done((res) => {
                console.log("RM IGD Data:", res);

                // Populate Macam Kasus
                if (res.master_kasus) {
                    let htmlKasus = '';
                    res.master_kasus.forEach(k => {
                        let selected = (res.triase && k.kode_kasus == res.triase.kode_kasus) ? 'selected' : '';
                        htmlKasus += `<option value="${k.kode_kasus}" ${selected}>${k.macam_kasus}</option>`;
                    });
                    modalRmIgd.find('#kode_kasus').html(htmlKasus);
                }

                if (res.medis) {
                    modalRmIgd.find('#anamnesis').val(res.medis.anamnesis || 'Autoanamnesis').change();
                    modalRmIgd.find('#hubungan').val(res.medis.hubungan || '-');
                    modalRmIgd.find('#keluhan_utama').val(res.medis.keluhan_utama || '-');
                    modalRmIgd.find('#rps').val(res.medis.rps || '-');
                    modalRmIgd.find('#rpd').val(res.medis.rpd || '-');
                    modalRmIgd.find('#rpk').val(res.medis.rpk || '-');
                    modalRmIgd.find('#rpo_alergi').val(`${res.medis.rpo || '-'} / ${res.medis.alergi || '-'}`);
                    modalRmIgd.find('#kesadaran').val(res.medis.kesadaran || 'Compos Mentis').change();
                    modalRmIgd.find('#gcs').val(res.medis.gcs || '-');
                    modalRmIgd.find('#td').val(res.medis.td || '-');
                    modalRmIgd.find('#nadi').val(res.medis.nadi || '-');
                    modalRmIgd.find('#rr').val(res.medis.rr || '-');
                    modalRmIgd.find('#suhu').val(res.medis.suhu || '-');
                    modalRmIgd.find('#spo2').val(res.medis.spo2 || '-');
                    modalRmIgd.find('#bb').val(res.medis.bb || '-');
                    modalRmIgd.find('#tb').val(res.medis.tb || '-');
                    modalRmIgd.find('#keadaan').val(res.medis.keadaan || 'Baik');
                    modalRmIgd.find('#kepala_mata_gigi').val(`${res.medis.kepala || 'Normal'} / ${res.medis.mata || 'Normal'} / ${res.medis.gigi || 'Normal'}`);
                    modalRmIgd.find('#leher_thoraks').val(`${res.medis.leher || 'Normal'} / ${res.medis.thoraks || 'Normal'}`);
                    modalRmIgd.find('#abdomen_genital').val(`${res.medis.abdomen || 'Normal'} / ${res.medis.genital || 'Normal'}`);
                    modalRmIgd.find('#ekstremitas').val(res.medis.ekstremitas || 'Normal');
                    modalRmIgd.find('#ket_fisik').val(res.medis.ket_fisik || '-');
                    modalRmIgd.find('#ket_lokalis').val(res.medis.ket_lokalis || '-');
                    modalRmIgd.find('#diagnosis').val(res.medis.diagnosis || '-');
                    modalRmIgd.find('#tata').val(res.medis.tata || '-');
                    modalRmIgd.find('#ekg').val(res.medis.ekg || '-');
                    modalRmIgd.find('#rad').val(res.medis.rad || '-');
                    modalRmIgd.find('#lab').val(res.medis.lab || '-');
                }
                
                if (res.triase) {
                    modalRmIgd.find('#keluhan_utama_triase').val(res.triase.primer ? res.triase.primer.keluhan_utama : '-');
                    modalRmIgd.find('#tanda_vital').val(res.triase.primer ? res.triase.primer.tanda_vital : '-');
                }

                // Render Triage Scales
                for (let i = 1; i <= 5; i++) {
                    let container = modalRmIgd.find(`#container-skala${i}`);
                    container.empty();
                    
                    if (res.master_pemeriksaan && res[`master_skala${i}`]) {
                        res.master_pemeriksaan.forEach(p => {
                            let masterSkalas = res[`master_skala${i}`].filter(s => s.kode_pemeriksaan == p.kode_pemeriksaan);
                            if (masterSkalas.length > 0) {
                                let groupHtml = `<div class="mb-1" style="border-bottom: 1px solid #eee; padding-bottom: 2px;">
                                                <div class="text-uppercase fw-bold text-start" style="font-size: 10px; color: #333; padding-left: 5px;">${p.nama_pemeriksaan}</div>`;
                                masterSkalas.forEach(s => {
                                    let isChecked = false;
                                    if (res[`skala${i}`]) {
                                        isChecked = res[`skala${i}`].some(ds => ds[`kode_skala${i}`] == s[`kode_skala${i}`]);
                                    }
                                    groupHtml += `
                                        <div class="form-check" style="margin-bottom: 4px; text-align: left; padding-left: 25px;">
                                            <input class="form-check-input" type="checkbox" name="skala${i}[]" value="${s[`kode_skala${i}`]}" id="skala${i}_${s[`kode_skala${i}`]}" ${isChecked ? 'checked' : ''} style="width: 14px; height: 14px; margin-left: -20px;">
                                            <label class="form-check-label" for="skala${i}_${s[`kode_skala${i}`]}" style="font-size: 11px; line-height: 1.4; color: #000;">
                                                ${s[`pengkajian_skala${i}`]}
                                            </label>
                                        </div>
                                    `;
                                });
                                groupHtml += `</div>`;
                                container.append(groupHtml);
                            }
                        });
                    }
                }
            });

            // Delimiter otomatis Tekanan Darah
            modalRmIgd.find('#td').on('keyup', function(e) {
                let val = $(this).val().replace(/[^0-9/]/g, '');
                if (val.length == 3 && e.keyCode != 8 && !val.includes('/')) {
                    $(this).val(val + '/');
                }
            });

            modalRmIgd.modal('show');
        });
    }

    function simpanRmIgd() {
        let data = $('#formRmIgd').serializeArray();
        // Custom logic to split combined fields if needed before sending
        // For simplicity, I'll let the controller handle it or just send as is
        
        $.post("{{ url('/rm/igd') }}", data).done((response) => {
            alertSuccessAjax();
            modalRmIgd.modal('hide');

            // Otomatis ubah warna tombol CPPT di tabel registrasi
            const no_rawat = modalRmIgd.find('input[name="no_rawat"]').val();
            const row = $(`.rows-registrasi[data-id="${no_rawat}"]`);
            const btnCppt = row.find('button[onclick^="showCpptRalan"]');
            if (btnCppt.length) {
                btnCppt.removeClass('btn-outline-primary').addClass('btn-success');
            }
        }).fail((err) => {
            alertErrorAjax(err);
        });
    }
</script>
@endpush

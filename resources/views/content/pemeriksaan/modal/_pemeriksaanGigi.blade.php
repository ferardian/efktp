<div class="modal modal-blur fade" id="modalPemeriksaanGigi" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Pemeriksaan Gigi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="">
                <div class="card border-0">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tabs-home-ex2" class="nav-link active" data-bs-toggle="tab">
                                    <i class="ti ti-users me-2"></i> Form Pemeriksaan</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tabs-profile-ex2" class="nav-link" data-bs-toggle="tab">
                                    <i class="ti ti-list me-2"></i> Riwayat</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" id="tabs-home-ex2">
                                @include('content.pemeriksaan.modal.gigi._periksaGigi')
                            </div>
                            <div class="tab-pane" id="tabs-profile-ex2">
                                @include('content.pemeriksaan.modal.gigi._riwayatPeriksaGigi')
                            </div>
                            <div class="tab-pane" id="tabs-settings-ex2">
                                <h4>Settings tab</h4>
                                <div>Donec ac vitae diam amet vel leo egestas consequat rhoncus in luctus amet, facilisi sit mauris accumsan nibh habitant senectus</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="simpanPemeriksaanGigi()"><i class="ti ti-device-floppy"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        function simpanPemeriksaanGigi() {
            const data = getDataForm('formPemeriksaanGigi', ['input', 'select']);
            $.post(`/efktp/pemeriksaan/gigi`,
                data
            ).done((response) => {
                alertSuccessAjax('Berhasil').then(() => {
                    loadRiwayatGigi(data['no_rkm_medis']);
                    setStatusLayan(data['no_rawat'], 'Sudah');
                    loadTabelRegistrasi(tglAwal, tglAkhir, statusLocal, dokterLocal.kd_dokter)
                    $('#modalPemeriksaanGigi').modal('hide');
                });
            })

        }

        function pemeriksaanGigi(no_rawat) {
            formPemeriksaanGigi.trigger('reset');
            $.get(`/efktp/registrasi/get/detail`, {
                no_rawat: no_rawat
            }).done((response) => {
                formPemeriksaanGigi.find('input[name="no_rawat"]').val(no_rawat)
                formPemeriksaanGigi.find('input[name="no_rkm_medis"]').val(response.no_rkm_medis)
                formPemeriksaanGigi.find('input[name="nm_pasien"]').val(`${response.pasien.nm_pasien} (${response.pasien.jk})`)
                formPemeriksaanGigi.find('input[name="tgl_lahir"]').val(`${splitTanggal(response.pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`)
                formPemeriksaanGigi.find('input[name="dokter"]').val(response.dokter.nm_dokter)
                formPemeriksaanGigi.find('input[name="kd_dokter"]').val(response.kd_dokter)
                loadRiwayatGigi(response.no_rkm_medis)
            })

            $.get(`/efktp/pemeriksaan/gigi`, {
                no_rawat: no_rawat,
            }).done((response) => {
                if (Object.values(response).length) {
                    formPemeriksaanGigi.find('select[name="oklusi"]').val(response.oklusi).change();
                    formPemeriksaanGigi.find('select[name="palatinus"]').val(response.palatinus).change();
                    formPemeriksaanGigi.find('select[name="mandibularis"]').val(response.mandibularis).change();
                    formPemeriksaanGigi.find('select[name="palatium"]').val(response.palatium).change();
                    formPemeriksaanGigi.find('select[name="diastema"]').val(response.diastema).change();
                    formPemeriksaanGigi.find('input[name="ket_diastema"]').val(response.ket_diastema);
                    formPemeriksaanGigi.find('select[name="anomali"]').val(response.anomali).change();
                    formPemeriksaanGigi.find('input[name="ket_anomali"]').val(response.ket_anomali);
                    formPemeriksaanGigi.find('input[name="lainnya"]').val(response.lainnya);
                    formPemeriksaanGigi.find('input[name="d"]').val(response.d);
                    formPemeriksaanGigi.find('input[name="m"]').val(response.m);
                    formPemeriksaanGigi.find('input[name="f"]').val(response.f);
                }
            })

            renderHasilGigi(no_rawat);
            loadHasilPemeriksaanGigi(no_rawat);
            $('#modalPemeriksaanGigi').modal('show');
        }

        function renderHasilGigi(no_rawat) {
            $('.tb-odonto').find('td').html('');
            $.get(`/efktp/pemeriksaan/gigi/hasil`, {
                no_rawat: no_rawat
            }).done((response) => {
                response.map((item) => {
                    $(`#gigi${item.posisi_gigi}`).html(symbolGigi(item.hasil))
                })
            })
        }
    </script>
@endpush

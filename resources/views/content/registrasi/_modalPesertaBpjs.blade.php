<div class="modal modal-blur fade" id="modalPesertaBpjs" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Detail Peserta BPJS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPeserta">
                    <div class="row gy-2">
                        <div class="col-xl-2 col-md-12 col-sm-12">
                            <label for="aktif" class="form-label">Status</label>
                            <input type="text" name="aktif" id="aktif" class="form-control" readonly>
                        </div>
                        <div class="col-xl-3 col-md-12 col-sm-12">
                            <label for="noKartu" class="form-label">No Kartu</label>
                            <input type="text" name="noKartu" id="noKartu" class="form-control" readonly>
                        </div>
                        <div class="col-xl-3 col-md-12 col-sm-12">
                            <label for="noKTP" class="form-label">No. KTP</label>
                            <input type="text" class="form-control" name="noKTP" id="noKTP" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" id="nama" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="tglLahir" class="form-label">Tgl. Lahir</label>
                            <input type="text" class="form-control" name="tglLahir" id="tglLahir" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="umur" class="form-label">Umur</label>
                            <input type="text" class="form-control" name="umur" id="umur" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="noHP" class="form-label">No. HP</label>
                            <input type="text" class="form-control" name="noHP" id="noHP" readonly />
                        </div>
                        <div class="col-xl-6 col-md-12 col-sm-12">
                            <label for="kdProvider" class="form-label">Provider</label>
                            <input type="text" class="form-control" name="nmProvider" id="nmProvider" readonly />
                        </div>
                        <div class="col-xl-6 col-md-12 col-sm-12">
                            <label for="nmProviderGigi" class="form-label">Provider Gigi</label>
                            <input type="text" class="form-control" name="nmProviderGigi" id="nmProviderGigi" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="hubunganKeluarga" class="form-label">Hubungan Keluarga</label>
                            <input type="text" class="form-control" name="hubunganKeluarga" id="hubunganKeluarga" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="kelas" class="form-label">Kelas</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="kodeKelas" id="kodeKelas" readonly />
                                <input type="text" class="form-control w-75" name="nmKelas" id="nmKelas" readonly />
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="jenis" class="form-label">Jenis Peserta</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="kodeJenis" id="kodeJenis" readonly />
                                <input type="text" class="form-control w-75" name="nmJenis" id="nmJenis" readonly />
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="tglMulaiAktif" class="form-label">Tgl. Mulai Aktif</label>
                            <input type="text" class="form-control" name="tglMulaiAktif" id="tglMulaiAktif" readonly />
                        </div>
                        <div class="col-xl-4 col-md-12 col-sm-12">
                            <label for="tglAkhirBerlaku" class="form-label">Tgl. Akhir Berlaku</label>
                            <input type="text" class="form-control" name="tglAkhirBerlaku" id="tglAkhirBerlaku" readonly />
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        const formPesrta = $('#formPeserta');
        const modalPesertaBpjs = $('#modalPesertaBpjs');

        function getPeserta(no_peserta) {
            loadingAjax();
            $.get(`/efktp/bridging/pcare/peserta/${no_peserta}`).done((response) => {
                if (response.metaData.code == 200) {
                    const result = response.response;
                    const umur = hitungUmur(splitTanggal(result.tglLahir));
                    const textUmur = `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`
                    const isActive = result.aktif ? 'is-valid' : '';
                    formPesrta.find('#aktif').val(result.ketAktif).addClass(isActive);
                    formPesrta.find('#noKartu').val(result.noKartu);
                    formPesrta.find('#noKTP').val(result.noKTP);
                    formPesrta.find('#nama').val(`${result.nama} (${result.sex})`);
                    formPesrta.find('#tglLahir').val(result.tglLahir);
                    formPesrta.find('#umur').val(textUmur);
                    formPesrta.find('#noHP').val(result.noHP);
                    formPesrta.find('#nmProvider').val(result.kdProviderPst.nmProvider);
                    formPesrta.find('#nmProviderGigi').val(`${result.kdProviderGigi.nmProvider ? result.kdProviderGigi.nmProvider : '-'}`);
                    formPesrta.find('#hubunganKeluarga').val(result.hubunganKeluarga);
                    formPesrta.find('#kodeKelas').val(result.jnsKelas.kode);
                    formPesrta.find('#nmKelas').val(result.jnsKelas.nama);
                    formPesrta.find('#kodeJenis').val(result.jnsPeserta.kode);
                    formPesrta.find('#nmJenis').val(result.jnsPeserta.nama);
                    formPesrta.find('#tglAkhirBerlaku').val(result.tglAkhirBerlaku);
                    formPesrta.find('#tglMulaiAktif').val(result.tglMulaiAktif);
                    modalPesertaBpjs.modal('show');
                    loadingAjax().close();
                } else {
                    const error = {
                        status: response.metaData.code,
                        statusText: 'Nomor peserta tidak ditemukan',
                        responseJSON: response.metaData.message,
                    }
                    alertErrorAjax(error);
                }
            })
        }
    </script>
@endpush

<div class="modal modal-blur fade" id="modalSuratSakit" tabindex="-1" aria-modal="false" role="dialog"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Surat Sakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0">
                    <div class="card-body p-0">
                        @include('content.registrasi.suratSakit._formSuratSakit')
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @include('content.registrasi.suratSakit._tbSuratSakit')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnSimpanSuratSakit"><i
                            class="ti ti-device-floppy me-2"></i>Simpan
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modalCetakSuratSakit" tabindex="-1" aria-modal="false" role="dialog"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Cetak :: Surat Sehat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="print" type="" width="100%" height="600"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar
                </button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        let modalSuratSakit = $('#modalSuratSakit');
        let formSuratSakit = $('#formSuratSakit');
        let formFilterSakit = $('#formFilterSakit');
        let tanggalAwal = formSuratSakit.find('input[name=tanggalawal]');
        let tanggalAkhir = formSuratSakit.find('input[name=tanggalakhir]');

        const modalCetakSuratSakit = $('#modalCetakSuratSakit');

        modalSuratSakit.on('shown.bs.modal', (e) => {
            const lamaSakit = setLamaSakit(tanggalAwal.val(), tanggalAkhir.val());
            formSuratSakit.find('input[name=lama]').val(lamaSakit)
            setNoSuratSakit().done((response) => {
                formSuratSakit.find('input[name=no_surat]').val(response)
            }).fail((error) => {
                alertErrorAjax(error)
            })
            loadSuratSakit();
        });

        function suratSakit(no_rawat) {
            $.get(`/efktp/registrasi/get/detail`, {
                no_rawat: no_rawat
            }).done((response) => {
                const diagnosa = response.diagnosa.map((dx) => {
                    return dx.penyakit.ciri_ciri
                }).join(';')
                formSuratSakit.find('input[name=no_rawat]').val(no_rawat)
                formSuratSakit.find('input[name=diagnosa]').val(diagnosa)
                formSuratSakit.find('input[name=pekerjaan]').val(response.pasien.pekerjaan)
                formSuratSakit.find('input[name=pasien]').val(`${response.no_rkm_medis} - ${response.pasien.nm_pasien} (${response.umurdaftar} ${response.sttsumur}) `)
                modalSuratSakit.modal('show')
            }).fail((error) => {
                alertErrorAjax(error)
            })

        }

        tanggalAwal.on('change', (e) => {
            const awal = e.currentTarget.value;
            const akhir = tanggalAkhir.val();
            const lamaSakit = setLamaSakit(awal, akhir);
            formSuratSakit.find('input[name=lama]').val(lamaSakit)

            setNoSuratSakit(awal).done((response) => {
                formSuratSakit.find('input[name=no_surat]').val(response)
            })

        })
        tanggalAkhir.on('change', (e) => {
            const akhir = e.currentTarget.value;
            const awal = tanggalAwal.val();
            const lamaSakit = setLamaSakit(awal, akhir);
            if (lamaSakit < 1) {
                alertError('Tanggal akhir tidak boleh mundur');
                return false;
            }
            formSuratSakit.find('input[name=lama]').val(lamaSakit)
        })

        function cetakSuratSakit(no_surat) {
            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            modalCetakSuratSakit.find('#print').on('load', (e) => {
                if (e.currentTarget.src) {
                    toast('Berhasil');
                }

            })
            modalCetakSuratSakit.modal('show');
            modalCetakSuratSakit.find('#print').removeAttr('src').attr('src', `/efktp/surat/sakit/print/${no_surat}`)
        }
    </script>
@endpush

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
                <h5 class="modal-title m-0">Cetak :: Surat Sakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <!-- Toolbar Opsi Diagnosa -->
                <div class="bg-light p-2 mb-2 rounded border d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold small text-muted"><i class="ti ti-settings me-1"></i>Opsi Diagnosa:</span>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="opt_diagnosa_surat" id="opt_diag_manual" value="manual" autocomplete="off" checked>
                            <label class="btn btn-outline-warning" for="opt_diag_manual" title="Kosongkan keterangan diagnosa untuk ditulis manual oleh dokter">
                                <i class="ti ti-pencil me-1"></i> Kosongkan (Manual Dokter)
                            </label>

                            <input type="radio" class="btn-check" name="opt_diagnosa_surat" id="opt_diag_ada" value="ada" autocomplete="off">
                            <label class="btn btn-outline-primary" for="opt_diag_ada" title="Cetak dengan teks diagnosa">
                                <i class="ti ti-file-text me-1"></i> Dengan Diagnosa
                            </label>
                        </div>
                    </div>
                    <div class="text-muted small">
                        <span id="labelKetDiagnosa" class="badge bg-warning-lt py-1 px-2">
                            <i class="ti ti-pencil me-1"></i>Diagnosa dikosongkan untuk diisi manual dokter
                        </span>
                    </div>
                </div>

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
        let activeNoSuratSakit = '';

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
            $.get(`{{ url('/registrasi/get/detail') }}`, {
                no_rawat: no_rawat
            }).done((response) => {
                const diagnosa = response.diagnosa.map((dx) => {
                    return dx.penyakit.nm_penyakit
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

        function updateLabelKetDiagnosa(mode) {
            const label = $('#labelKetDiagnosa');
            if (mode === 'manual' || mode === '0') {
                label.attr('class', 'badge bg-warning-lt py-1 px-2').html('<i class="ti ti-pencil me-1"></i>Diagnosa dikosongkan untuk diisi manual dokter');
            } else if (mode === 'ada' || mode === '1') {
                label.attr('class', 'badge bg-primary-lt py-1 px-2').html('<i class="ti ti-file-text me-1"></i>Mencetak nama diagnosa pada surat');
            }
        }

        $('input[name="opt_diagnosa_surat"]').on('change', function() {
            const mode = $(this).val();
            updateLabelKetDiagnosa(mode);
            if (activeNoSuratSakit) {
                loadIframeSuratSakit(activeNoSuratSakit, mode);
            }
        });

        function loadIframeSuratSakit(no_surat, mode) {
            const url = `{{ url('/surat/sakit/print') }}?no_surat=${encodeURIComponent(no_surat)}&diagnosa=${mode}`;
            modalCetakSuratSakit.find('#print').removeAttr('src').attr('src', url);
        }

        function cetakSuratSakit(no_surat, defaultMode = '') {
            activeNoSuratSakit = no_surat;

            // Jika mode ditentukan dari klik (misal dari menu dropdown), set radio button
            if (defaultMode) {
                $(`input[name="opt_diagnosa_surat"][value="${defaultMode}"]`).prop('checked', true);
            }
            const mode = $('input[name="opt_diagnosa_surat"]:checked').val() || 'manual';
            updateLabelKetDiagnosa(mode);

            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            modalCetakSuratSakit.find('#print').off('load').on('load', (e) => {
                Swal.close();
                if (e.currentTarget.src) {
                    toast('Berhasil');
                }
            });
            modalCetakSuratSakit.modal('show');
            loadIframeSuratSakit(no_surat, mode);
        }
    </script>
@endpush

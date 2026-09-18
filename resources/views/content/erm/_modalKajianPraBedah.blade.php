<div class="modal modal-blur fade" id="modalKajianPraBedah" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="ti ti-stethoscope me-2 fs-2"></i>
                    Kajian Pra Bedah — Asesmen Pre-Operasi oleh Dokter Operator
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <!-- Kolom Form Utama -->
                    <div class="col-lg-8 col-md-12">
                        <!-- Card Banner Pasien & Smart TTV Lookup -->
                        <div class="card card-sm mb-3 border-primary-subtle shadow-sm">
                            <div class="card-body p-2 bg-light-subtle">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small mb-0">No. Rawat / No. RM</label>
                                        <div class="fw-bold fs-4 text-primary" id="pb_display_no_rawat">-</div>
                                        <div class="text-muted small" id="pb_display_no_rkm_medis">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-0">Pasien</label>
                                        <div class="fw-bold fs-4 text-dark" id="pb_display_nm_pasien">-</div>
                                        <div class="text-muted small" id="pb_display_umur_jk">-</div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnSyncTtvPraBedah" title="Tarik data TTV & Diagnosa terakhir">
                                            <i class="ti ti-bolt me-1 text-warning"></i> ⚡ Tarik TTV & Diagnosa Terkini
                                        </button>
                                        <div class="small text-muted mt-1" id="pb_ttv_preview_badge">TTV: -</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="formKajianPraBedah">
                            <input type="hidden" name="no_rawat" id="pb_no_rawat">
                            <input type="hidden" name="tanggal_lama" id="pb_tanggal_lama">

                            <div class="card mb-3 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label required small">Tanggal & Waktu Asesmen</label>
                                            <input type="datetime-local" class="form-control form-control-sm" name="tanggal" id="pb_tanggal" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label required small">Dokter Spesialis Bedah / Operator</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm w-25" name="kd_dokter" id="pb_kd_dokter" readonly required>
                                                <input type="text" class="form-control form-control-sm w-75" name="nm_dokter" id="pb_nm_dokter" readonly placeholder="Nama Dokter">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required small">1. Ringkasan Riwayat Klinik Pasien</label>
                                        <textarea class="form-control form-control-sm" name="ringkasan_klinik" id="pb_ringkasan_klinik" rows="3" placeholder="Keluhan utama, riwayat penyakit saat ini, dan perjalanan penyakit..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required small">2. Pemeriksaan Fisik Terkait Bedah & TTV</label>
                                        <textarea class="form-control form-control-sm" name="pemeriksaan_fisik" id="pb_pemeriksaan_fisik" rows="3" placeholder="Keadaan umum, TTV (TD, Nadi, RR, Suhu), status lokalis organ yang akan dioperasi..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">3. Pemeriksaan Diagnostik & Penunjang (Laborat, Radiologi, USG, EKG)</label>
                                        <textarea class="form-control form-control-sm" name="pemeriksaan_diagnostik" id="pb_pemeriksaan_diagnostik" rows="2" placeholder="Hasil Hb, Leukosit, Trombosit, PT/APTT, Rontgen Thorax, dll..."></textarea>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label required small">4. Diagnosa Pre-Operasi</label>
                                            <textarea class="form-control form-control-sm" name="diagnosa_pre_operasi" id="pb_diagnosa_pre_operasi" rows="2" placeholder="Diagnosa pra bedah..." required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required small">5. Rencana Tindakan Bedah / Operasi</label>
                                            <textarea class="form-control form-control-sm" name="rencana_tindakan_bedah" id="pb_rencana_tindakan_bedah" rows="2" placeholder="Rencana prosedur pembedahan..." required></textarea>
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label small">6. Hal-hal yang Perlu Dipersiapkan</label>
                                            <textarea class="form-control form-control-sm" name="hal_hal_yang_perludi_persiapkan" id="pb_hal_hal_yang_perludi_persiapkan" rows="2" placeholder="Sedia darah/PRC, ICU, alat implant, puasa, dll..."></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">7. Terapi Pre-Operasi</label>
                                            <textarea class="form-control form-control-sm" name="terapi_pre_operasi" id="pb_terapi_pre_operasi" rows="2" placeholder="Antibiotik profilaksis, cairan infus, premedikasi..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Tutup
                                </button>
                                <div class="gap-2 d-flex">
                                    <button type="button" class="btn btn-outline-success d-none" id="btnCetakPraBedah">
                                        <i class="ti ti-printer me-1"></i> Cetak Asesmen Pra Bedah
                                    </button>
                                    <button type="button" class="btn btn-danger d-none" id="btnHapusPraBedah">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btnSimpanPraBedah">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Kajian Pra Bedah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Kolom Riwayat Kajian Pra Bedah -->
                    <div class="col-lg-4 col-md-12">
                        <div class="card shadow-sm sticky-top" style="top: 10px;">
                            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                <strong class="text-secondary"><i class="ti ti-history me-1"></i> Riwayat Pra Bedah</strong>
                                <button type="button" class="btn btn-xs btn-outline-primary" id="btnTambahBaruPraBedah">
                                    <i class="ti ti-plus me-1"></i> Buat Baru
                                </button>
                            </div>
                            <div class="card-body p-2" id="containerRiwayatPraBedah" style="max-height: 75vh; overflow-y: auto;">
                                <div class="text-center text-muted p-3">Memuat riwayat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function () {
        const modalKajianPraBedah = $('#modalKajianPraBedah');
        const formKajianPraBedah = $('#formKajianPraBedah');

        function setNowDateTimePraBedah() {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            $('#pb_tanggal').val(now.toISOString().slice(0, 16));
        }

        function resetFormPraBedah() {
            formKajianPraBedah.trigger('reset');
            setNowDateTimePraBedah();
            $('#pb_tanggal_lama').val('');
            $('#btnCetakPraBedah').addClass('d-none');
            $('#btnHapusPraBedah').addClass('d-none');
        }

        window.bukaKajianPraBedah = function (no_rawat) {
            resetFormPraBedah();
            $('#pb_no_rawat').val(no_rawat);
            modalKajianPraBedah.modal('show');

            getRegDetail(no_rawat).done((res) => {
                const { pasien, dokter } = res;
                $('#pb_display_no_rawat').text(no_rawat);
                $('#pb_display_no_rkm_medis').text(res.no_rkm_medis);
                $('#pb_display_nm_pasien').text(pasien?.nm_pasien || '-');
                $('#pb_display_umur_jk').text(`${formatTanggal(pasien?.tgl_lahir)} / ${pasien?.jk === 'L' ? 'Laki-laki' : 'Perempuan'}`);

                $('#pb_kd_dokter').val(res.kd_dokter);
                $('#pb_nm_dokter').val(dokter?.nm_dokter || '-');
            });

            syncTtvPraBedah(no_rawat, false);
            muatRiwayatPraBedah(no_rawat);
        };

        function syncTtvPraBedah(no_rawat, showNotification = true) {
            $.get(`{{ url('/pemeriksaan/ttv/latest') }}/${encodeURIComponent(no_rawat)}`)
                .done((res) => {
                    if (res.success && res.data) {
                        const ttv = res.data;
                        const badge = `TD: ${ttv.td} | HR: ${ttv.nadi}x/m | RR: ${ttv.rr}x/m | SpO2: ${ttv.spo2}% | T: ${ttv.suhu}°C`;
                        $('#pb_ttv_preview_badge').html(`<span class="badge bg-green-lt text-dark border">${badge}</span>`);

                        if (showNotification) {
                            if (ttv.keluhan && ttv.keluhan !== '-') {
                                $('#pb_ringkasan_klinik').val(`Keluhan Utama: ${ttv.keluhan}\nRiwayat Alergi: ${ttv.alergi}`).addClass('border-primary bg-primary-lt');
                            }
                            $('#pb_pemeriksaan_fisik').val(`TTV: TD ${ttv.td} mmHg, HR ${ttv.nadi} x/mnt, RR ${ttv.rr} x/mnt, Suhu ${ttv.suhu} °C, SpO2 ${ttv.spo2}%, TB ${ttv.tb} cm, BB ${ttv.bb} kg.\nStatus Lokalis: ${ttv.pemeriksaan}`).addClass('border-primary bg-primary-lt');

                            if (ttv.penilaian && ttv.penilaian !== '-') {
                                $('#pb_diagnosa_pre_operasi').val(ttv.penilaian).addClass('border-primary bg-primary-lt');
                            }

                            setTimeout(() => {
                                $('#pb_ringkasan_klinik, #pb_pemeriksaan_fisik, #pb_diagnosa_pre_operasi').removeClass('border-primary bg-primary-lt');
                            }, 2000);

                            showToast('TTV & Data Klinis Terkini Berhasil Ditarik!', 'success');
                        }
                    } else {
                        $('#pb_ttv_preview_badge').html('<span class="text-muted small">Belum ada catatan TTV sebelumnya</span>');
                        if (showNotification) showToast('Belum ada data TTV sebelumnya.', 'info');
                    }
                });
        }

        $('#btnSyncTtvPraBedah').on('click', () => {
            const no_rawat = $('#pb_no_rawat').val();
            if (no_rawat) syncTtvPraBedah(no_rawat, true);
        });

        function muatRiwayatPraBedah(no_rawat) {
            const container = $('#containerRiwayatPraBedah');
            container.html('<div class="text-center text-muted p-3"><div class="spinner-border spinner-border-sm me-1"></div> Memuat riwayat...</div>');

            $.get(`{{ url('/erm/pra-bedah/list') }}/${encodeURIComponent(no_rawat)}`)
                .done((res) => {
                    if (!res.success || !res.data || res.data.length === 0) {
                        container.html('<div class="text-muted text-center p-3 small"><i class="ti ti-notes-off me-1"></i> Belum ada kajian pra bedah untuk kunjungan ini.</div>');
                        return;
                    }

                    let html = '<div class="list-group list-group-flush">';
                    res.data.forEach((item) => {
                        html += `
                            <div class="list-group-item list-group-item-action p-2 cursor-pointer border rounded mb-2 shadow-2xs item-riwayat-pb" data-tanggal="${item.tanggal}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-primary">${item.rencana_tindakan_bedah || 'Tindakan Bedah'}</div>
                                        <div class="small text-muted"><i class="ti ti-calendar me-1"></i> ${item.tanggal}</div>
                                        <div class="small text-muted"><i class="ti ti-user me-1"></i> Dr: <strong>${item.dokter?.nm_dokter || '-'}</strong></div>
                                    </div>
                                    <span class="badge bg-blue text-blue-fg text-nowrap">Pra Bedah</span>
                                </div>
                                <div class="mt-2 d-flex justify-content-end gap-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-cetak-pb-item" data-tanggal="${item.tanggal}">
                                        <i class="ti ti-printer me-1"></i> Cetak
                                    </button>
                                    <button type="button" class="btn btn-xs btn-primary btn-pilih-pb" data-tanggal="${item.tanggal}">
                                        <i class="ti ti-eye me-1"></i> Buka
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.html(html);

                    container.find('.item-riwayat-pb, .btn-pilih-pb').on('click', function (e) {
                        e.stopPropagation();
                        const tgl = $(this).data('tanggal');
                        bukaDetailPraBedah(tgl);
                    });

                    container.find('.btn-cetak-pb-item').on('click', function (e) {
                        e.stopPropagation();
                        const tgl = $(this).data('tanggal');
                        const noRawat = $('#pb_no_rawat').val();
                        window.open(`{{ url('/erm/pra-bedah/print') }}?no_rawat=${encodeURIComponent(noRawat)}&tanggal=${encodeURIComponent(tgl)}`, '_blank');
                    });
                })
                .fail(() => {
                    container.html('<div class="text-danger text-center p-3 small">Gagal memuat riwayat</div>');
                });
        }

        function bukaDetailPraBedah(tanggal) {
            const no_rawat = $('#pb_no_rawat').val();
            $.get(`{{ url('/erm/pra-bedah/first') }}`, { no_rawat: no_rawat, tanggal: tanggal })
                .done((res) => {
                    if (!res.success || !res.data) return;
                    const d = res.data;
                    resetFormPraBedah();

                    $('#pb_no_rawat').val(d.no_rawat);
                    $('#pb_tanggal_lama').val(d.tanggal);
                    $('#pb_tanggal').val(d.tanggal.replace(' ', 'T').slice(0, 16));
                    $('#pb_kd_dokter').val(d.kd_dokter);
                    $('#pb_nm_dokter').val(d.dokter?.nm_dokter || '-');

                    $('#pb_ringkasan_klinik').val(d.ringkasan_klinik);
                    $('#pb_pemeriksaan_fisik').val(d.pemeriksaan_fisik);
                    $('#pb_pemeriksaan_diagnostik').val(d.pemeriksaan_diagnostik);
                    $('#pb_diagnosa_pre_operasi').val(d.diagnosa_pre_operasi);
                    $('#pb_rencana_tindakan_bedah').val(d.rencana_tindakan_bedah);
                    $('#pb_hal_hal_yang_perludi_persiapkan').val(d.hal_hal_yang_perludi_persiapkan);
                    $('#pb_terapi_pre_operasi').val(d.terapi_pre_operasi);

                    $('#btnCetakPraBedah').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pra-bedah/print') }}?no_rawat=${encodeURIComponent(d.no_rawat)}&tanggal=${encodeURIComponent(d.tanggal)}', '_blank')`);
                    $('#btnHapusPraBedah').removeClass('d-none');
                });
        }

        $('#btnTambahBaruPraBedah').on('click', () => {
            const no_rawat = $('#pb_no_rawat').val();
            resetFormPraBedah();
            $('#pb_no_rawat').val(no_rawat);
            getRegDetail(no_rawat).done((res) => {
                $('#pb_kd_dokter').val(res.kd_dokter);
                $('#pb_nm_dokter').val(res.dokter?.nm_dokter || '-');
            });
            showToast('Form Kajian Pra Bedah baru siap diisi', 'info');
        });

        $('#btnSimpanPraBedah').on('click', () => {
            const no_rawat = $('#pb_no_rawat').val();
            if (!no_rawat) {
                showToast('Nomor rawat tidak valid', 'error');
                return;
            }

            const diagnosa = $('#pb_diagnosa_pre_operasi').val().trim();
            const rencana = $('#pb_rencana_tindakan_bedah').val().trim();
            if (!diagnosa || !rencana) {
                showToast('Diagnosa dan Rencana Tindakan Bedah wajib diisi!', 'warning');
                return;
            }

            const formData = formKajianPraBedah.serialize();
            $('#btnSimpanPraBedah').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/erm/pra-bedah') }}`, formData)
                .done((res) => {
                    $('#btnSimpanPraBedah').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Kajian Pra Bedah');
                    if (res.success) {
                        showToast(res.message || 'Kajian Pra Bedah berhasil disimpan!', 'success');
                        $('#pb_tanggal_lama').val(res.data.tanggal);
                        $('#btnCetakPraBedah').removeClass('d-none').attr('onclick', `window.open('{{ url('/erm/pra-bedah/print') }}?no_rawat=${encodeURIComponent(res.data.no_rawat)}&tanggal=${encodeURIComponent(res.data.tanggal)}', '_blank')`);
                        $('#btnHapusPraBedah').removeClass('d-none');
                        muatRiwayatPraBedah(no_rawat);
                    }
                })
                .fail((err) => {
                    $('#btnSimpanPraBedah').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Kajian Pra Bedah');
                    showToast(err.responseJSON?.message || 'Gagal menyimpan data!', 'error');
                });
        });

        $('#btnHapusPraBedah').on('click', () => {
            const no_rawat = $('#pb_no_rawat').val();
            const tanggal = $('#pb_tanggal_lama').val();
            if (!no_rawat || !tanggal) return;

            Swal.fire({
                title: 'Hapus Kajian Pra Bedah?',
                text: 'Data asesmen pra bedah ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/erm/pra-bedah/delete') }}`, { no_rawat: no_rawat, tanggal: tanggal })
                        .done((res) => {
                            if (res.success) {
                                showToast('Kajian Pra Bedah berhasil dihapus', 'success');
                                resetFormPraBedah();
                                $('#pb_no_rawat').val(no_rawat);
                                muatRiwayatPraBedah(no_rawat);
                            }
                        });
                }
            });
        });
    });
</script>
@endpush

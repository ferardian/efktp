<div class="modal modal-blur fade" id="modalValidasiResep" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title m-0 text-white"><i class="ti ti-checklist me-2"></i>Validasi & Adjust Resep Obat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formValidasiResep">
                    <input type="hidden" name="no_resep" id="val_no_resep">
                    <input type="hidden" name="no_rawat" id="val_no_rawat">

                    <!-- Info Header -->
                    <div class="row g-2 mb-3 bg-light p-2 rounded border">
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-0 small">No. Resep / Rawat</label>
                            <div class="fw-bold" id="lbl_no_resep_rawat">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-0 small">Pasien / No. RM</label>
                            <div class="fw-bold text-primary" id="lbl_pasien_rm">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-0 small">Dokter Peresep / Poli</label>
                            <div class="fw-bold" id="lbl_dokter_poli">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted mb-0 small">Depo Farmasi / Penjab</label>
                            <div class="fw-bold text-success" id="lbl_depo_penjab">-</div>
                        </div>
                    </div>

                    <!-- Non-Racik Section -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="m-0 font-weight-bold text-dark"><i class="ti ti-pill me-1 text-primary"></i> Obat Non-Racikan</h5>
                        </div>
                        <div class="table-responsive border rounded">
                            <table class="table table-sm table-hover align-middle mb-0" id="tbValidasiNonRacik">
                                <thead class="table-light">
                                    <tr>
                                        <th width="12%">Kode</th>
                                        <th width="33%">Nama Obat</th>
                                        <th width="10%" class="text-center">Stok Depo</th>
                                        <th width="10%" class="text-center">Qty Dokter</th>
                                        <th width="15%" class="text-center">Qty Validasi</th>
                                        <th width="20%">Aturan Pakai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Memuat data obat...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Racikan Section -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="m-0 font-weight-bold text-dark"><i class="ti ti-flask me-1 text-primary"></i> Obat Racikan</h5>
                        </div>
                        <div id="containerValidasiRacikan">
                            <div class="text-center text-muted border p-3 rounded">Tidak ada obat racikan</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i> Batal</button>
                <button type="button" class="btn btn-success" id="btnProsesValidasiResep" onclick="submitValidasiResep()"><i class="ti ti-check me-1"></i> Proses Validasi & Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        const modalValidasiResep = $('#modalValidasiResep');

        function showModalValidasiResep(no_resep) {
            loadingAjax('Mengambil detail resep & stok depo...');
            $.get(`{{ url('/resep/detail-validation') }}`, { no_resep: no_resep })
                .done((response) => {
                    loadingAjax().close();
                    const resep = response.resep;
                    if (!resep) {
                        return alertErrorAjax('Resep tidak ditemukan');
                    }

                    $('#val_no_resep').val(resep.no_resep);
                    $('#val_no_rawat').val(resep.no_rawat);

                    const nmPasien = resep.reg_periksa?.pasien?.nm_pasien || '-';
                    const noRm = resep.reg_periksa?.no_rkm_medis || '-';
                    const nmDokter = resep.dokter?.nm_dokter || '-';
                    const nmPoli = resep.reg_periksa?.poliklinik?.nm_poli || '-';
                    const nmPenjab = resep.reg_periksa?.penjab?.png_jawab || '-';

                    $('#lbl_no_resep_rawat').html(`${resep.no_resep} <br><small class="text-muted">${resep.no_rawat}</small>`);
                    $('#lbl_pasien_rm').html(`${nmPasien} <br><small class="text-muted">RM: ${noRm}</small>`);
                    $('#lbl_dokter_poli').html(`${nmDokter} <br><small class="text-muted">${nmPoli}</small>`);
                    $('#lbl_depo_penjab').html(`${response.nm_bangsal} <br><small class="text-muted">${nmPenjab}</small>`);

                    // Render Non-Racikan
                    renderTableValidasiNonRacik(resep.resep_dokter);

                    // Render Racikan
                    renderContainerValidasiRacik(resep.resep_racikan);

                    modalValidasiResep.modal('show');
                })
                .fail((error) => {
                    loadingAjax().close();
                    alertErrorAjax(error);
                });
        }

        function renderTableValidasiNonRacik(items) {
            const tbody = $('#tbValidasiNonRacik tbody');
            tbody.empty();

            if (!items || !items.length) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada obat non-racikan</td></tr>');
                return;
            }

            items.forEach((item, index) => {
                const obat = item.obat || {};
                const satuan = obat.satuan?.satuan || 'unit';
                const stok = parseFloat(item.stok || 0);
                const qtyDr = parseFloat(item.jml || 0);
                const isStokCukup = stok >= qtyDr;
                const badgeStokClass = isStokCukup ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';

                const tr = `
                    <tr data-kode-brng="${item.kode_brng}">
                        <td><span class="badge bg-secondary-subtle text-dark border">${item.kode_brng}</span></td>
                        <td>
                            <div class="fw-bold text-dark">${obat.nama_brng || item.kode_brng}</div>
                            <small class="text-muted">Satuan: ${satuan}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                        </td>
                        <td class="text-center fw-bold text-primary">${qtyDr}</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" class="form-control text-center fw-bold val-qty-nonracik" data-kode="${item.kode_brng}" value="${qtyDr}">
                                <span class="input-group-text">${satuan}</span>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm val-aturan-nonracik" data-kode="${item.kode_brng}" value="${item.aturan_pakai || ''}" placeholder="Aturan pakai...">
                        </td>
                    </tr>
                `;
                tbody.append(tr);
            });
        }

        function renderContainerValidasiRacik(racikans) {
            const container = $('#containerValidasiRacikan');
            container.empty();

            if (!racikans || !racikans.length) {
                container.html('<div class="text-center text-muted border p-3 rounded bg-light">Tidak ada obat racikan</div>');
                return;
            }

            racikans.forEach((racik, rIdx) => {
                const metode = racik.metode?.nm_racik || 'Racikan';
                let detailRows = '';

                if (racik.detail && racik.detail.length) {
                    racik.detail.forEach((d) => {
                        const obat = d.obat || {};
                        const satuan = obat.satuan?.satuan || 'unit';
                        const stok = parseFloat(d.stok || 0);
                        const qtyDr = parseFloat(d.jml || 0);
                        const isStokCukup = stok >= qtyDr;
                        const badgeStokClass = isStokCukup ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';

                        detailRows += `
                            <tr data-no-racik="${racik.no_racik}" data-kode-brng="${d.kode_brng}">
                                <td><span class="badge bg-secondary-subtle text-dark border">${d.kode_brng}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">${obat.nama_brng || d.kode_brng}</div>
                                    <small class="text-muted">Satuan: ${satuan}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                                </td>
                                <td class="text-center fw-bold text-primary">${qtyDr}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0" class="form-control text-center fw-bold val-qty-racik-detail" data-no-racik="${racik.no_racik}" data-kode="${d.kode_brng}" value="${qtyDr}">
                                        <span class="input-group-text">${satuan}</span>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    detailRows = '<tr><td colspan="5" class="text-center text-muted">Tidak ada detail bahan racikan</td></tr>';
                }

                const card = `
                    <div class="card mb-3 border rounded shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                            <div>
                                <span class="badge bg-primary me-2">Racik #${racik.no_racik}</span>
                                <strong>${racik.nama_racik}</strong> (${metode})
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted">Jumlah Racikan (Dokter):</span>
                                <input type="number" step="1" min="1" class="form-control form-control-sm text-center fw-bold val-qty-dr-racik" style="width: 80px;" data-no-racik="${racik.no_racik}" value="${racik.jml_dr}">
                                <input type="text" class="form-control form-control-sm val-aturan-racik" style="width: 220px;" data-no-racik="${racik.no_racik}" value="${racik.aturan_pakai || ''}" placeholder="Aturan pakai racikan...">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="15%">Kode Bahan</th>
                                        <th width="40%">Nama Bahan Obat</th>
                                        <th width="15%" class="text-center">Stok Depo</th>
                                        <th width="15%" class="text-center">Qty Dokter</th>
                                        <th width="15%" class="text-center">Qty Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${detailRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
                container.append(card);
            });
        }

        function submitValidasiResep() {
            const no_resep = $('#val_no_resep').val();
            if (!no_resep) return;

            const items_non_racik = [];
            $('#tbValidasiNonRacik tbody tr').each(function () {
                const kode = $(this).data('kode-brng');
                if (kode) {
                    const jml = parseFloat($(this).find('.val-qty-nonracik').val() || 0);
                    const aturan = $(this).find('.val-aturan-nonracik').val() || '';
                    items_non_racik.push({
                        kode_brng: kode,
                        jml: jml,
                        aturan_pakai: aturan
                    });
                }
            });

            const items_racik_detail = [];
            $('#containerValidasiRacikan tbody tr').each(function () {
                const no_racik = $(this).data('no-racik');
                const kode = $(this).data('kode-brng');
                if (no_racik && kode) {
                    const jml = parseFloat($(this).find('.val-qty-racik-detail').val() || 0);
                    items_racik_detail.push({
                        no_racik: no_racik,
                        kode_brng: kode,
                        jml: jml
                    });
                }
            });

            const items_racik = [];
            $('#containerValidasiRacikan .card').each(function () {
                const inputQtyDr = $(this).find('.val-qty-dr-racik');
                const inputAturan = $(this).find('.val-aturan-racik');
                const no_racik = inputQtyDr.data('no-racik');
                if (no_racik) {
                    items_racik.push({
                        no_racik: no_racik,
                        jml_dr: parseFloat(inputQtyDr.val() || 1),
                        aturan_pakai: inputAturan.val() || ''
                    });
                }
            });

            Swal.fire({
                title: 'Konfirmasi Validasi Obat',
                text: 'Apakah Anda yakin ingin memproses validasi & penyesuaian resep ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2fb344',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Validasi SEKARANG!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Sedang memproses validasi & memotong stok obat...');
                    $.post(`{{ url('/resep/validate-adjust') }}`, {
                        no_resep: no_resep,
                        items_non_racik: items_non_racik,
                        items_racik_detail: items_racik_detail,
                        items_racik: items_racik
                    }).done((res) => {
                        loadingAjax().close();
                        modalValidasiResep.modal('hide');
                        alertSuccessAjax('Berhasil memvalidasi dan meng-adjust resep obat!');
                        
                        // Refresh datatable
                        const tgl_awal = $('#tgl_awal').val();
                        const tgl_akhir = $('#tgl_akhir').val();
                        if (typeof tbResepObat === 'function') {
                            tbResepObat(tgl_awal, tgl_akhir);
                        }
                    }).fail((error) => {
                        loadingAjax().close();
                        alertErrorAjax(error);
                    });
                }
            });
        }
    </script>
@endpush

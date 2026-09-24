<div class="modal modal-blur fade" id="modalValidasiResep" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <style>
        #modalValidasiResep .btn, #modalValidasiResep .badge,
        #modalLookupObatValidasi .btn, #modalLookupObatValidasi .badge {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            gap: 0.25rem !important;
        }
        #modalValidasiResep .btn i, #modalValidasiResep .badge i,
        #modalLookupObatValidasi .btn i, #modalLookupObatValidasi .badge i {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 0 !important;
            font-size: 1.15em !important;
            margin: 0 !important;
        }
        #modalValidasiResep .btn span, #modalValidasiResep .badge span,
        #modalLookupObatValidasi .btn span, #modalLookupObatValidasi .badge span {
            display: inline-block !important;
            line-height: 1 !important;
        }
    </style>
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title m-0 text-white d-inline-flex align-items-center gap-2"><i class="ti ti-checklist"></i><span>Validasi & Adjust Resep Obat</span></h5>
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="m-0 font-weight-bold text-dark"><i class="ti ti-pill me-1 text-primary"></i> Obat Non-Racikan</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" onclick="openLookupObatValidasi('tambah')">
                                <i class="ti ti-plus"></i><span>Tambah Obat</span>
                            </button>
                        </div>
                        <div class="table-responsive border rounded">
                            <table class="table table-sm table-hover align-middle mb-0" id="tbValidasiNonRacik">
                                <thead class="table-light">
                                    <tr>
                                        <th width="10%">Kode</th>
                                        <th width="32%">Nama Obat</th>
                                        <th width="12%" class="text-center">Stok Depo</th>
                                        <th width="8%" class="text-center">Qty Dokter</th>
                                        <th width="15%" class="text-center">Qty Validasi</th>
                                        <th width="15%">Aturan Pakai</th>
                                        <th width="8%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Memuat data obat...</td>
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
                <button type="button" class="btn btn-secondary d-inline-flex align-items-center gap-1" data-bs-dismiss="modal"><i class="ti ti-x"></i><span>Batal</span></button>
                <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1" id="btnProsesValidasiResep" onclick="submitValidasiResep()"><i class="ti ti-check"></i><span>Proses Validasi & Simpan</span></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lookup Cari & Pilih Obat (Substitusi / Tambah) -->
<div class="modal modal-blur fade" id="modalLookupObatValidasi" tabindex="-1" style="z-index: 1065;" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border shadow-lg">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title m-0 text-white" id="titleLookupObat">
                    <i class="ti ti-search me-1"></i> Cari & Pilih Obat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-2">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="txtCariObatLookup" placeholder="Ketik nama atau kode obat..." autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 text-end d-flex align-items-center justify-content-end">
                        <span class="badge bg-blue-lt px-2 py-1">
                            <i class="ti ti-building-warehouse me-1"></i> Depo: <strong id="lblDepoLookup">Apotek</strong>
                        </span>
                    </div>
                </div>

                <div class="border rounded overflow-auto" style="max-height: 380px;">
                    <table class="table table-sm table-hover align-middle mb-0" id="tbHasilLookupObat">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="15%">Kode</th>
                                <th width="42%">Nama Obat</th>
                                <th width="15%" class="text-center">Satuan</th>
                                <th width="15%" class="text-center">Stok Depo</th>
                                <th width="13%" class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Ketik nama obat pada kolom pencarian di atas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        const modalValidasiResep = $('#modalValidasiResep');
        const modalLookupObatValidasi = $('#modalLookupObatValidasi');

        let currentKdBangsal = 'AP';
        let currentNmBangsal = 'Apotek';
        let lookupTimer = null;
        let lookupContext = {
            mode: 'tambah', // 'tambah', 'substitusi', 'tambah_racik', 'substitusi_racik'
            targetRow: null,
            noRacik: null
        };

        // Stacked modal fix: pastikan parent modal tetap bisa di-scroll saat lookup ditutup
        modalLookupObatValidasi.on('hidden.bs.modal', function () {
            if (modalValidasiResep.hasClass('show')) {
                $('body').addClass('modal-open');
            }
        });

        modalLookupObatValidasi.on('shown.bs.modal', function () {
            $('#txtCariObatLookup').focus().select();
        });

        // Live search lookup obat
        $('#txtCariObatLookup').on('input', function () {
            const query = $(this).val().trim();
            clearTimeout(lookupTimer);
            lookupTimer = setTimeout(() => {
                fetchLookupObat(query);
            }, 300);
        });

        function showModalValidasiResep(no_resep) {
            loadingAjax('Mengambil detail resep & stok depo...');
            $.get(`{{ url('/resep/detail-validation') }}`, { no_resep: no_resep })
                .done((response) => {
                    loadingAjax().close();
                    const resep = response.resep;
                    if (!resep) {
                        return alertErrorAjax('Resep tidak ditemukan');
                    }

                    currentKdBangsal = response.kd_bangsal || 'AP';
                    currentNmBangsal = response.bangsal_name || response.nm_bangsal || 'Apotek';
                    $('#lblDepoLookup').text(currentNmBangsal);

                    $('#val_no_resep').val(resep.no_resep);
                    $('#val_no_rawat').val(resep.no_rawat);

                    const nmPasien = resep.reg_periksa?.pasien?.nm_pasien || '-';
                    const noRm = resep.reg_periksa?.no_rkm_medis || '-';
                    const nmDokter = resep.dokter?.nm_dokter || '-';
                    const nmPenjab = resep.reg_periksa?.penjab?.png_jawab || '-';

                    let ruangPoliHtml = '';
                    if (response.is_ranap) {
                        ruangPoliHtml = `<span class="badge bg-purple-lt text-purple me-1"><i class="ti ti-bed"></i> Ranap</span> ${response.kamar_info || 'Rawat Inap'}`;
                    } else {
                        const nmPoli = resep.reg_periksa?.poliklinik?.nm_poli || '-';
                        ruangPoliHtml = `<span class="badge bg-blue-lt text-blue me-1"><i class="ti ti-walk"></i> Ralan</span> ${nmPoli}`;
                    }

                    $('#lbl_no_resep_rawat').html(`${resep.no_resep} <br><small class="text-muted">${resep.no_rawat}</small>`);
                    $('#lbl_pasien_rm').html(`${nmPasien} <br><small class="text-muted">RM: ${noRm}</small>`);
                    $('#lbl_dokter_poli').html(`${nmDokter} <br><small class="text-muted">${ruangPoliHtml}</small>`);
                    $('#lbl_depo_penjab').html(`${currentNmBangsal} <br><small class="text-muted">${nmPenjab}</small>`);

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
                tbody.append('<tr id="rowEmptyNonRacik"><td colspan="7" class="text-center text-muted py-3">Tidak ada obat non-racikan</td></tr>');
                return;
            }

            items.forEach((item) => {
                const obat = item.obat || {};
                const satuan = obat.satuan?.satuan || 'unit';
                const stok = parseFloat(item.stok || 0);
                const qtyDr = parseFloat(item.jml || 0);
                const isStokCukup = stok >= qtyDr;
                const badgeStokClass = isStokCukup ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';
                const namaDisplay = typeof formatNamaObatWithGolongan === 'function' ? formatNamaObatWithGolongan(obat.nama_brng || item.kode_brng, obat.golongan, obat.kode_golongan) : (obat.nama_brng || item.kode_brng);
                const rawNama = obat.nama_brng || item.kode_brng;

                const tr = `
                    <tr data-kode-brng="${item.kode_brng}" 
                        data-original-kode="${item.kode_brng}"
                        data-original-nama="${encodeURIComponent(rawNama)}"
                        data-original-satuan="${satuan}"
                        data-original-stok="${stok}">
                        <td><span class="badge bg-secondary-subtle text-dark border cell-kode-brng">${item.kode_brng}</span></td>
                        <td class="cell-nama-obat">
                            <div class="fw-bold">${namaDisplay}</div>
                            <small class="text-muted">Satuan: <span class="cell-satuan-text">${satuan}</span></small>
                        </td>
                        <td class="text-center cell-stok-depo">
                            <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                        </td>
                        <td class="text-center fw-bold text-primary cell-qty-dokter">${qtyDr}</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" class="form-control text-center fw-bold val-qty-nonracik" data-kode="${item.kode_brng}" value="${qtyDr}">
                                <span class="input-group-text cell-satuan-addon">${satuan}</span>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm val-aturan-nonracik" data-kode="${item.kode_brng}" value="${item.aturan_pakai || ''}" placeholder="Aturan pakai...">
                        </td>
                        <td class="text-center cell-aksi">
                            <button type="button" class="btn btn-xs btn-outline-warning d-inline-flex align-items-center gap-1" onclick="openLookupObatValidasi('substitusi', this)" title="Ganti/Substitusi Obat">
                                <i class="ti ti-arrows-exchange"></i><span>Ganti</span>
                            </button>
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

            racikans.forEach((racik) => {
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
                        const dNamaDisplay = typeof formatNamaObatWithGolongan === 'function' ? formatNamaObatWithGolongan(obat.nama_brng || d.kode_brng, obat.golongan, obat.kode_golongan) : (obat.nama_brng || d.kode_brng);
                        const rawNama = obat.nama_brng || d.kode_brng;

                        detailRows += `
                            <tr data-no-racik="${racik.no_racik}" 
                                data-kode-brng="${d.kode_brng}"
                                data-original-kode="${d.kode_brng}"
                                data-original-nama="${encodeURIComponent(rawNama)}"
                                data-original-satuan="${satuan}"
                                data-original-stok="${stok}">
                                <td><span class="badge bg-secondary-subtle text-dark border cell-kode-brng">${d.kode_brng}</span></td>
                                <td class="cell-nama-obat">
                                    <div class="fw-bold">${dNamaDisplay}</div>
                                    <small class="text-muted">Satuan: <span class="cell-satuan-text">${satuan}</span></small>
                                </td>
                                <td class="text-center cell-stok-depo">
                                    <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                                </td>
                                <td class="text-center fw-bold text-primary cell-qty-dokter">${qtyDr}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0" class="form-control text-center fw-bold val-qty-racik-detail" data-no-racik="${racik.no_racik}" data-kode="${d.kode_brng}" value="${qtyDr}">
                                        <span class="input-group-text cell-satuan-addon">${satuan}</span>
                                    </div>
                                </td>
                                <td class="text-center cell-aksi">
                                    <button type="button" class="btn btn-xs btn-outline-warning d-inline-flex align-items-center gap-1" onclick="openLookupObatValidasi('substitusi_racik', this, '${racik.no_racik}')" title="Ganti Bahan">
                                        <i class="ti ti-arrows-exchange"></i><span>Ganti</span>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    detailRows = '<tr class="row-empty-racik-detail"><td colspan="6" class="text-center text-muted">Tidak ada detail bahan racikan</td></tr>';
                }

                const card = `
                    <div class="card mb-3 border rounded shadow-sm" id="cardRacik_${racik.no_racik}">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
                            <div>
                                <span class="badge bg-primary me-2">Racik #${racik.no_racik}</span>
                                <strong>${racik.nama_racik}</strong> (${metode})
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted">Jml Racik:</span>
                                <input type="number" step="1" min="1" class="form-control form-control-sm text-center fw-bold val-qty-dr-racik" style="width: 75px;" data-no-racik="${racik.no_racik}" value="${racik.jml_dr}">
                                <input type="text" class="form-control form-control-sm val-aturan-racik" style="width: 200px;" data-no-racik="${racik.no_racik}" value="${racik.aturan_pakai || ''}" placeholder="Aturan pakai...">
                                <button type="button" class="btn btn-xs btn-outline-primary d-inline-flex align-items-center gap-1" onclick="openLookupObatValidasi('tambah_racik', null, '${racik.no_racik}')">
                                    <i class="ti ti-plus"></i><span>Tambah Bahan</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-hover align-middle mb-0 tb-detail-racik">
                                <thead class="table-light">
                                    <tr>
                                        <th width="12%">Kode Bahan</th>
                                        <th width="38%">Nama Bahan Obat</th>
                                        <th width="12%" class="text-center">Stok Depo</th>
                                        <th width="10%" class="text-center">Qty Dokter</th>
                                        <th width="16%" class="text-center">Qty Validasi</th>
                                        <th width="12%" class="text-center">Aksi</th>
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

        // ==========================================
        // FITUR LOOKUP OBAT (TAMBAH & SUBSTITUSI)
        // ==========================================

        function openLookupObatValidasi(mode, element = null, noRacik = null) {
            lookupContext.mode = mode;
            lookupContext.noRacik = noRacik;
            lookupContext.targetRow = element ? $(element).closest('tr') : null;

            let title = '<i class="ti ti-search me-1"></i> Cari & Pilih Obat';
            if (mode === 'tambah') {
                title = '<i class="ti ti-plus me-1 text-primary"></i> Tambah Obat Non-Racikan ke Resep';
            } else if (mode === 'substitusi') {
                const origNama = decodeURIComponent(lookupContext.targetRow.data('original-nama') || '');
                title = `<i class="ti ti-arrows-exchange me-1 text-warning"></i> Substitusi Obat: <strong>${origNama}</strong>`;
            } else if (mode === 'tambah_racik') {
                title = `<i class="ti ti-plus me-1 text-primary"></i> Tambah Bahan Racikan #${noRacik}`;
            } else if (mode === 'substitusi_racik') {
                const origNama = decodeURIComponent(lookupContext.targetRow.data('original-nama') || '');
                title = `<i class="ti ti-arrows-exchange me-1 text-warning"></i> Substitusi Bahan Racik: <strong>${origNama}</strong>`;
            }

            $('#titleLookupObat').html(title);
            $('#txtCariObatLookup').val('');
            $('#tbHasilLookupObat tbody').html('<tr><td colspan="5" class="text-center text-muted py-4">Ketik nama atau kode obat pada kolom pencarian</td></tr>');
            
            modalLookupObatValidasi.modal('show');
            // Ambil 15 obat pertama sebagai referensi cepat
            fetchLookupObat('');
        }

        function fetchLookupObat(query) {
            const tbody = $('#tbHasilLookupObat tbody');
            tbody.html('<tr><td colspan="5" class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Mencari obat di depo...</td></tr>');

            $.get(`{{ url('/penjualan/search-obat') }}`, {
                term: query,
                kd_bangsal: currentKdBangsal
            }).done((items) => {
                tbody.empty();
                if (!items || !items.length) {
                    tbody.html('<tr><td colspan="5" class="text-center text-muted py-3">Obat tidak ditemukan di depo ini</td></tr>');
                    return;
                }

                items.forEach((item) => {
                    const stok = parseFloat(item.stok || 0);
                    const isStokCukup = stok > 0;
                    const badgeStokClass = isStokCukup ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';

                    const itemJson = JSON.stringify(item).replace(/'/g, "&apos;");
                    const tr = `
                        <tr>
                            <td><span class="badge bg-secondary-subtle text-dark border">${item.kode_brng}</span></td>
                            <td>
                                <div class="fw-bold">${item.nama_brng}</div>
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark">${item.satuan || '-'}</span></td>
                            <td class="text-center"><span class="badge ${badgeStokClass} px-2 py-1">${stok} ${item.satuan || ''}</span></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm ${isStokCukup ? 'btn-primary' : 'btn-outline-secondary'} btn-pilih-obat-lookup"
                                    data-item='${itemJson}' onclick="pilihObatLookup(this)">
                                    <i class="ti ti-check me-1"></i> Pilih
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(tr);
                });
            }).fail(() => {
                tbody.html('<tr><td colspan="5" class="text-center text-danger py-3">Gagal mengambil data obat</td></tr>');
            });
        }

        function pilihObatLookup(btn) {
            const item = $(btn).data('item');
            if (!item) return;

            const stok = parseFloat(item.stok || 0);
            const satuan = item.satuan || 'unit';
            const badgeStokClass = stok > 0 ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';

            if (lookupContext.mode === 'tambah') {
                // Hapus empty state jika ada
                $('#rowEmptyNonRacik').remove();

                const tr = `
                    <tr data-kode-brng="${item.kode_brng}" data-is-new="1" style="background-color: #f8fffa;">
                        <td><span class="badge bg-success-subtle text-success border border-success cell-kode-brng">${item.kode_brng}</span></td>
                        <td class="cell-nama-obat">
                            <div class="fw-bold">${item.nama_brng} <span class="badge bg-success text-white ms-1" style="font-size: 9px;"><i class="ti ti-plus me-1"></i>Tambahan</span></div>
                            <small class="text-muted">Satuan: <span class="cell-satuan-text">${satuan}</span></small>
                        </td>
                        <td class="text-center cell-stok-depo">
                            <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                        </td>
                        <td class="text-center text-muted cell-qty-dokter">-</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" class="form-control text-center fw-bold val-qty-nonracik" data-kode="${item.kode_brng}" value="1">
                                <span class="input-group-text cell-satuan-addon">${satuan}</span>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm val-aturan-nonracik" data-kode="${item.kode_brng}" value="3 x 1" placeholder="Aturan pakai...">
                        </td>
                        <td class="text-center cell-aksi">
                            <button type="button" class="btn btn-xs btn-outline-danger" onclick="hapusBarisObat(this)" title="Hapus Obat Tambahan">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#tbValidasiNonRacik tbody').append(tr);

            } else if (lookupContext.mode === 'substitusi') {
                const tr = lookupContext.targetRow;
                const origKode = tr.data('original-kode');
                const origNama = decodeURIComponent(tr.data('original-nama'));

                tr.attr('data-kode-brng', item.kode_brng);
                tr.attr('data-kode-asal', origKode);
                tr.css('background-color', '#fffdf5');

                tr.find('.cell-kode-brng')
                    .removeClass('bg-secondary-subtle text-dark')
                    .addClass('bg-warning-subtle text-warning border-warning')
                    .text(item.kode_brng);

                tr.find('.cell-nama-obat').html(`
                    <div class="fw-bold">${item.nama_brng}</div>
                    <div class="small text-warning fw-bold mt-1">
                        <i class="ti ti-arrows-exchange me-1"></i>Substitusi dari: <span class="text-muted text-decoration-line-through">${origNama}</span>
                    </div>
                    <small class="text-muted">Satuan: <span class="cell-satuan-text">${satuan}</span></small>
                `);

                tr.find('.cell-stok-depo').html(`
                    <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                `);

                tr.find('.cell-satuan-addon').text(satuan);
                tr.find('.val-qty-nonracik').attr('data-kode', item.kode_brng);
                tr.find('.val-aturan-nonracik').attr('data-kode', item.kode_brng);

                tr.find('.cell-aksi').html(`
                    <button type="button" class="btn btn-xs btn-outline-secondary d-inline-flex align-items-center gap-1" onclick="batalSubstitusiNonRacik(this)" title="Kembalikan ke Obat Dokter Asli">
                        <i class="ti ti-rotate-2"></i><span>Batal</span>
                    </button>
                `);

            } else if (lookupContext.mode === 'tambah_racik') {
                const card = $(`#cardRacik_${lookupContext.noRacik}`);
                const tbody = card.find('.tb-detail-racik tbody');
                tbody.find('.row-empty-racik-detail').remove();

                const tr = `
                    <tr data-no-racik="${lookupContext.noRacik}" data-kode-brng="${item.kode_brng}" data-is-new="1" style="background-color: #f8fffa;">
                        <td><span class="badge bg-success-subtle text-success border border-success cell-kode-brng">${item.kode_brng}</span></td>
                        <td class="cell-nama-obat">
                            <div class="fw-bold">${item.nama_brng} <span class="badge bg-success text-white ms-1" style="font-size: 9px;"><i class="ti ti-plus me-1"></i>Tambahan</span></div>
                            <small class="text-muted">Satuan: <span class="cell-satuan-text">${satuan}</span></small>
                        </td>
                        <td class="text-center cell-stok-depo">
                            <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                        </td>
                        <td class="text-center text-muted cell-qty-dokter">-</td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" min="0" class="form-control text-center fw-bold val-qty-racik-detail" data-no-racik="${lookupContext.noRacik}" data-kode="${item.kode_brng}" value="1">
                                <span class="input-group-text cell-satuan-addon">${satuan}</span>
                            </div>
                        </td>
                        <td class="text-center cell-aksi">
                            <button type="button" class="btn btn-xs btn-outline-danger" onclick="hapusBarisObat(this)" title="Hapus Bahan Tambahan">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(tr);

            } else if (lookupContext.mode === 'substitusi_racik') {
                const tr = lookupContext.targetRow;
                const origKode = tr.data('original-kode');
                const origNama = decodeURIComponent(tr.data('original-nama'));

                tr.attr('data-kode-brng', item.kode_brng);
                tr.attr('data-kode-asal', origKode);
                tr.css('background-color', '#fffdf5');

                tr.find('.cell-kode-brng')
                    .removeClass('bg-secondary-subtle text-dark')
                    .addClass('bg-warning-subtle text-warning border-warning')
                    .text(item.kode_brng);

                tr.find('.cell-nama-obat').html(`
                    <div class="fw-bold">${item.nama_brng}</div>
                    <div class="small text-warning fw-bold mt-1">
                        <i class="ti ti-arrows-exchange me-1"></i>Substitusi dari: <span class="text-muted text-decoration-line-through">${origNama}</span>
                    </div>
                    <small class="text-muted">Satuan: <span class="cell-satuan-text">${satuan}</span></small>
                `);

                tr.find('.cell-stok-depo').html(`
                    <span class="badge ${badgeStokClass} px-2 py-1">${stok} ${satuan}</span>
                `);

                tr.find('.cell-satuan-addon').text(satuan);
                tr.find('.val-qty-racik-detail').attr('data-kode', item.kode_brng);

                tr.find('.cell-aksi').html(`
                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="batalSubstitusiBahanRacik(this, '${lookupContext.noRacik}')" title="Kembalikan Bahan Asli">
                        <i class="ti ti-rotate-2 me-1"></i> Batal
                    </button>
                `);
            }

            modalLookupObatValidasi.modal('hide');
        }

        function batalSubstitusiNonRacik(btn) {
            const tr = $(btn).closest('tr');
            const origKode = tr.data('original-kode');
            const origNama = decodeURIComponent(tr.data('original-nama'));
            const origSatuan = tr.data('original-satuan');
            const origStok = parseFloat(tr.data('original-stok') || 0);
            const isStokCukup = origStok > 0;
            const badgeStokClass = isStokCukup ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';

            tr.attr('data-kode-brng', origKode);
            tr.removeAttr('data-kode-asal');
            tr.css('background-color', '');

            tr.find('.cell-kode-brng')
                .removeClass('bg-warning-subtle text-warning border-warning')
                .addClass('bg-secondary-subtle text-dark')
                .text(origKode);

            tr.find('.cell-nama-obat').html(`
                <div class="fw-bold">${origNama}</div>
                <small class="text-muted">Satuan: <span class="cell-satuan-text">${origSatuan}</span></small>
            `);

            tr.find('.cell-stok-depo').html(`
                <span class="badge ${badgeStokClass} px-2 py-1">${origStok} ${origSatuan}</span>
            `);

            tr.find('.cell-satuan-addon').text(origSatuan);
            tr.find('.val-qty-nonracik').attr('data-kode', origKode);
            tr.find('.val-aturan-nonracik').attr('data-kode', origKode);

            tr.find('.cell-aksi').html(`
                <button type="button" class="btn btn-xs btn-outline-warning" onclick="openLookupObatValidasi('substitusi', this)" title="Ganti/Substitusi Obat">
                    <i class="ti ti-arrows-exchange me-1"></i> Ganti
                </button>
            `);
        }

        function batalSubstitusiBahanRacik(btn, noRacik) {
            const tr = $(btn).closest('tr');
            const origKode = tr.data('original-kode');
            const origNama = decodeURIComponent(tr.data('original-nama'));
            const origSatuan = tr.data('original-satuan');
            const origStok = parseFloat(tr.data('original-stok') || 0);
            const isStokCukup = origStok > 0;
            const badgeStokClass = isStokCukup ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger';

            tr.attr('data-kode-brng', origKode);
            tr.removeAttr('data-kode-asal');
            tr.css('background-color', '');

            tr.find('.cell-kode-brng')
                .removeClass('bg-warning-subtle text-warning border-warning')
                .addClass('bg-secondary-subtle text-dark')
                .text(origKode);

            tr.find('.cell-nama-obat').html(`
                <div class="fw-bold">${origNama}</div>
                <small class="text-muted">Satuan: <span class="cell-satuan-text">${origSatuan}</span></small>
            `);

            tr.find('.cell-stok-depo').html(`
                <span class="badge ${badgeStokClass} px-2 py-1">${origStok} ${origSatuan}</span>
            `);

            tr.find('.cell-satuan-addon').text(origSatuan);
            tr.find('.val-qty-racik-detail').attr('data-kode', origKode);

            tr.find('.cell-aksi').html(`
                <button type="button" class="btn btn-xs btn-outline-warning" onclick="openLookupObatValidasi('substitusi_racik', this, '${noRacik}')" title="Ganti Bahan">
                    <i class="ti ti-arrows-exchange me-1"></i> Ganti
                </button>
            `);
        }

        function hapusBarisObat(btn) {
            const tr = $(btn).closest('tr');
            tr.fadeOut(200, function () {
                $(this).remove();
            });
        }

        // ==========================================
        // SUBMIT VALIDASI & ADJUST
        // ==========================================

        function submitValidasiResep() {
            const no_resep = $('#val_no_resep').val();
            if (!no_resep) return;

            const items_non_racik = [];
            $('#tbValidasiNonRacik tbody tr').each(function () {
                const kode = $(this).attr('data-kode-brng');
                const kodeAsal = $(this).attr('data-kode-asal') || null;
                const isNew = $(this).attr('data-is-new') ? 1 : 0;
                if (kode) {
                    const jml = parseFloat($(this).find('.val-qty-nonracik').val() || 0);
                    const aturan = $(this).find('.val-aturan-nonracik').val() || '';
                    items_non_racik.push({
                        kode_brng: kode,
                        kode_brng_asal: kodeAsal,
                        is_new: isNew,
                        jml: jml,
                        aturan_pakai: aturan
                    });
                }
            });

            const items_racik_detail = [];
            $('#containerValidasiRacikan tbody tr').each(function () {
                const no_racik = $(this).attr('data-no-racik');
                const kode = $(this).attr('data-kode-brng');
                const kodeAsal = $(this).attr('data-kode-asal') || null;
                const isNew = $(this).attr('data-is-new') ? 1 : 0;
                if (no_racik && kode) {
                    const jml = parseFloat($(this).find('.val-qty-racik-detail').val() || 0);
                    items_racik_detail.push({
                        no_racik: no_racik,
                        kode_brng: kode,
                        kode_brng_asal: kodeAsal,
                        is_new: isNew,
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
                text: 'Apakah Anda yakin ingin memproses validasi, penambahan & substitusi obat pada resep ini?',
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

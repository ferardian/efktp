<!-- Modal Validasi Permintaan Stok Obat Pasien (UDD) -->
<div class="modal modal-blur fade" id="modalValidasiUdd" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title m-0 text-white d-inline-flex align-items-center gap-2">
                    <i class="ti ti-clock-check"></i>
                    <span>Validasi Permintaan Stok Obat Ranap (UDD / 24 Jam)</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" id="val_udd_no_permintaan">
                <input type="hidden" id="val_udd_no_rawat">

                <!-- Header Info Pasien & Depo -->
                <div class="row g-2 mb-3 bg-light p-2 rounded border">
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label text-muted mb-0 small">No. Permintaan / Rawat</label>
                        <div class="fw-bold" id="lbl_udd_no_permintaan_rawat">-</div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label text-muted mb-0 small">Pasien / No. RM</label>
                        <div class="fw-bold text-primary" id="lbl_udd_pasien_rm">-</div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label text-muted mb-0 small">Ruang / Kamar Inap</label>
                        <div class="fw-bold text-purple" id="lbl_udd_kamar_kelas">-</div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label text-muted mb-0 small">Depo Farmasi / Penjab</label>
                        <div class="fw-bold text-success" id="lbl_udd_depo_penjab">-</div>
                    </div>
                </div>

                <!-- Tabel Item Obat & Jadwal Jam -->
                <div class="table-responsive border rounded mb-2">
                    <table class="table table-sm table-hover align-middle mb-0" id="tbValidasiItemUdd">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">Kode</th>
                                <th width="28%">Nama Obat / BHP</th>
                                <th width="10%" class="text-center">Stok Depo</th>
                                <th width="8%" class="text-center">Qty Dosis</th>
                                <th width="12%" class="text-end">Harga (Kelas)</th>
                                <th width="12%" class="text-end">Subtotal</th>
                                <th width="20%">Jadwal Jam & Aturan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Memuat rincian obat...</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Estimasi Total Biaya:</td>
                                <td class="text-end fw-bold text-primary" id="lbl_udd_total_biaya">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-0">
                    <i class="ti ti-info-circle fs-3"></i>
                    <div>
                        Validasi akan memotong stok secara <strong>FIFO</strong> dari depo farmasi aktif dan menyerahkannya sebagai <strong>Stok Obat Pasien (UDD)</strong> di ruangan rawat inap. Biaya obat akan masuk ke billing kamar inap saat obat <strong>diberikan oleh perawat</strong> sesuai jadwal jam pemberian.
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary d-inline-flex align-items-center gap-1" data-bs-dismiss="modal">
                    <i class="ti ti-x"></i><span>Tutup</span>
                </button>
                <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1" id="btnSubmitValidasiUdd" onclick="prosesValidasiUdd()">
                    <i class="ti ti-check"></i><span>Validasi & Potong Stok</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    const modalValidasiUdd = $('#modalValidasiUdd');

    function openModalValidasiUdd() {
        const el = document.getElementById('modalValidasiUdd');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        } else if ($.fn.modal) {
            $('#modalValidasiUdd').modal('show');
        }
    }

    function closeModalValidasiUdd() {
        const el = document.getElementById('modalValidasiUdd');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).hide();
        } else if ($.fn.modal) {
            $('#modalValidasiUdd').modal('hide');
        }
    }

    function showModalValidasiUdd(no_permintaan) {
        loadingAjax('Mengambil detail permintaan UDD...');
        $.get(`{{ url('/permintaan-stok-obat/detail') }}`, { no_permintaan: no_permintaan })
            .done((response) => {
                loadingAjax().close();
                const header = response.header;
                const kamar = response.kamar;
                const depo = response.depo;
                const items = response.items;

                if (!header) {
                    return Swal.fire('Error', 'Data permintaan tidak ditemukan', 'error');
                }

                $('#val_udd_no_permintaan').val(header.no_permintaan);
                $('#val_udd_no_rawat').val(header.no_rawat);

                $('#lbl_udd_no_permintaan_rawat').html(`${header.no_permintaan}<br><small class="text-muted">${header.no_rawat}</small>`);
                $('#lbl_udd_pasien_rm').html(`${header.nm_pasien}<br><small class="text-muted">RM: ${header.no_rkm_medis}</small>`);
                
                const kamarText = kamar ? `${kamar.kd_kamar} - ${kamar.nm_bangsal} (${kamar.kelas})` : 'Rawat Inap';
                $('#lbl_udd_kamar_kelas').html(`<span class="badge bg-purple-lt text-purple me-1"><i class="ti ti-bed"></i> Ranap</span><br><small class="text-dark">${kamarText}</small>`);
                
                $('#lbl_udd_depo_penjab').html(`${depo.nm_bangsal}<br><small class="text-muted">${header.png_jawab || '-'}</small>`);

                // Render Items
                const tbody = $('#tbValidasiItemUdd tbody');
                tbody.empty();

                let totalBiaya = 0;
                let anyStokKurang = false;

                if (!items || items.length === 0) {
                    tbody.html('<tr><td colspan="7" class="text-center text-muted py-3">Tidak ada obat di dalam permintaan ini</td></tr>');
                    $('#btnSubmitValidasiUdd').prop('disabled', true);
                } else {
                    items.forEach(item => {
                        const stok = parseFloat(item.stok_depo || 0);
                        const qty = parseFloat(item.jml || 0);
                        const isCukup = stok >= qty;
                        if (!isCukup) anyStokKurang = true;

                        const badgeStok = isCukup
                            ? `<span class="badge bg-success-subtle text-success border border-success">${stok} ${item.satuan || ''}</span>`
                            : `<span class="badge bg-danger text-white">${stok} (Kurang)</span>`;

                        totalBiaya += parseFloat(item.total || 0);

                        // Jadwal jam badges
                        let jamBadges = [];
                        if (item.jadwal_jam && item.jadwal_jam.length) {
                            item.jadwal_jam.forEach(j => {
                                jamBadges.push(`<span class="badge bg-blue-lt px-1 py-0 me-1" style="font-size:0.7rem;">${j}</span>`);
                            });
                        }
                        const jamHtml = jamBadges.length ? `<div class="d-flex flex-wrap gap-1 mb-1">${jamBadges.join('')}</div>` : '';
                        const aturanHtml = item.aturan_pakai ? `<small class="text-muted d-block fst-italic">${item.aturan_pakai}</small>` : '';

                        tbody.append(`
                            <tr>
                                <td><code>${item.kode_brng}</code></td>
                                <td><span class="fw-bold text-dark">${item.nama_brng}</span></td>
                                <td class="text-center">${badgeStok}</td>
                                <td class="text-center fw-bold text-dark">${qty} <small class="text-muted">${item.satuan || ''}</small></td>
                                <td class="text-end small">Rp ${new Intl.NumberFormat('id-ID').format(item.biaya_obat)}</td>
                                <td class="text-end fw-bold text-dark small">Rp ${new Intl.NumberFormat('id-ID').format(item.total)}</td>
                                <td>${jamHtml}${aturanHtml}</td>
                            </tr>
                        `);
                    });

                    if (header.status === 'Sudah') {
                        $('#btnSubmitValidasiUdd').prop('disabled', true).html('<i class="ti ti-check me-1"></i> Sudah Divalidasi');
                    } else if (anyStokKurang) {
                        $('#btnSubmitValidasiUdd').prop('disabled', true).html('<i class="ti ti-alert-triangle me-1"></i> Stok Tidak Cukup');
                    } else {
                        $('#btnSubmitValidasiUdd').prop('disabled', false).html('<i class="ti ti-check me-1"></i> Validasi & Potong Stok');
                    }
                }

                $('#lbl_udd_total_biaya').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalBiaya));
                openModalValidasiUdd();
            })
            .fail((err) => {
                loadingAjax().close();
                alertErrorAjax(err);
            });
    }

    function prosesValidasiUdd() {
        const no_permintaan = $('#val_udd_no_permintaan').val();
        if (!no_permintaan) return;

        Swal.fire({
            title: 'Validasi Permintaan UDD?',
            html: `Apakah Anda yakin ingin memvalidasi permintaan <strong>${no_permintaan}</strong>?<br><br><span class="text-success">Stok obat akan dipotong dan biaya masuk ke tagihan kamar inap pasien.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Validasi SEKARANG',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Memproses validasi & memotong stok...');
                $.post(`{{ url('/permintaan-stok-obat/validasi') }}`, {
                    _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                    no_permintaan: no_permintaan
                }).done((res) => {
                    loadingAjax().close();
                    closeModalValidasiUdd();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    if (typeof reloadActiveTable === 'function') {
                        reloadActiveTable();
                    }
                }).fail((err) => {
                    loadingAjax().close();
                    alertErrorAjax(err);
                });
            }
        });
    }

    function batalValidasiUdd(no_permintaan) {
        Swal.fire({
            title: 'Batal Validasi UDD?',
            html: `Apakah Anda yakin ingin <b>membatalkan validasi</b> permintaan <strong>${no_permintaan}</strong>?<br><br><span class="text-danger">Stok obat akan dikembalikan ke gudang/depo dan tagihan dihapus dari kamar inap.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan Validasi',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Memproses pembatalan validasi...');
                $.post(`{{ url('/permintaan-stok-obat/batal-validasi') }}`, {
                    _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                    no_permintaan: no_permintaan
                }).done((res) => {
                    loadingAjax().close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    if (typeof reloadActiveTable === 'function') {
                        reloadActiveTable();
                    }
                }).fail((err) => {
                    loadingAjax().close();
                    alertErrorAjax(err);
                });
            }
        });
    }
</script>
@endpush

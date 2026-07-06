<div class="card shadow-none border-0 bg-transparent">
    <div class="card-body p-0">
        <div class="alert alert-info border-start border-4 border-info mb-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="ti ti-info-circle fs-2 me-2"></i>
                <div>
                    <h4 class="mb-0 d-flex align-items-center">Rincian Billing Berjalan <span id="billing_status_badge"></span></h4>
                    <p class="mb-0 small">Estimasi total biaya selama pemeriksaan berlangsung</p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="form-check form-switch me-3 mb-0">
                    <input class="form-check-input" type="checkbox" id="printShowObatDetail" checked>
                    <label class="form-check-label small fw-bold text-dark mb-0" for="printShowObatDetail">Detail Obat</label>
                </div>
                <button type="button" class="btn btn-sm btn-success me-2" id="btnOpenCloseBilling" onclick="showCloseBillingModal()">
                    <i class="ti ti-lock me-1"></i> Simpan & Tutup Billing
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-printer me-1"></i> Cetak Billing
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakBilling('80')">Ukuran 80mm</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakBilling('58')">Ukuran 58mm</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light border-0 mb-3">
            <div class="card-body p-2">
                <div class="row g-2 small text-dark">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 fw-bold">No. Nota</div>
                            <div class="col-8" id="billing_no_nota">: -</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Poliklinik</div>
                            <div class="col-8" id="billing_poli">: -</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Tgl. Registrasi</div>
                            <div class="col-8" id="billing_tgl">: -</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 fw-bold">No. R.M.</div>
                            <div class="col-8" id="billing_no_rm">: -</div>
                        </div>
                        <div class="row">
                            <div class="col-4 fw-bold">Nama Pasien</div>
                            <div class="col-8" id="billing_pasien">: -</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0" id="tabelBillingRalan">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="py-2 px-3">Deskripsi Item / Layanan</th>
                        <th class="py-2 px-3 text-center" width="60">Qty</th>
                        <th class="py-2 px-3 text-end" width="120">Tarif (Rp)</th>
                        <th class="py-2 px-3 text-end" width="140">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr class="bg-primary text-white">
                        <th colspan="3" class="py-2 px-3 fw-bold text-uppercase">TOTAL ESTIMASI BIAYA SELURUHNYA</th>
                        <th class="py-2 px-3 text-end fw-bold fs-3" id="grandTotalBilling">Rp. 0</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4 border-top pt-4" id="sectionResepUnvalidated" style="display:none;">
            <div class="alert alert-warning border-start border-4 border-warning mb-3 d-flex align-items-center">
                <i class="ti ti-prescription fs-2 me-2 text-warning"></i>
                <div>
                    <h4 class="mb-0 text-warning">Resep Dokter Belum Validasi</h4>
                    <p class="mb-0 small text-muted">Daftar resep yang belum divalidasi ke dalam billing & pemotongan stok</p>
                </div>
            </div>

            <div class="card border-warning mb-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 align-middle" id="tabelResepUnvalidated">
                            <thead class="bg-warning-lt text-dark">
                                <tr>
                                    <th class="py-2 px-3 text-center" style="width: 150px;">No. Resep</th>
                                    <th class="py-2 px-3">Obat / Alkes</th>
                                    <th class="py-2 px-3 text-center" style="width: 100px;">Jumlah</th>
                                    <th class="py-2 px-3" style="width: 180px;">Aturan Pakai</th>
                                    <th class="py-2 px-3 text-center" style="width: 120px;">Status Stok</th>
                                    <th class="py-2 px-3 text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <div class="card bg-orange-lt border-0">
                <div class="card-body p-2 d-flex align-items-center">
                    <i class="ti ti-alert-triangle text-orange me-2 fs-3"></i>
                    <span class="small text-orange fw-500">Catatan: Nilai ini adalah estimasi sementara dan dapat berubah sesuai dengan penambahan tindakan atau obat selama pemeriksaan.</span>
                </div>
            </div>
        </div>

        <!-- Modal Close Billing -->
        <div class="modal fade" id="modalCloseBilling" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white"><i class="ti ti-lock me-2"></i> Tutup & Simpan Billing</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-dark">
                        <form id="formCloseBilling">
                            <input type="hidden" id="cb_no_rawat">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Rawat</label>
                                <input type="text" class="form-control bg-light" id="cb_no_rawat_display" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Pasien</label>
                                <input type="text" class="form-control bg-light" id="cb_nama_pasien" readonly>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold text-success">Total Tagihan (Rp)</label>
                                    <input type="text" class="form-control bg-light fw-bold text-success" id="cb_total_tagihan" readonly>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Tgl. Bayar</label>
                                    <input type="date" class="form-control" id="cb_tgl_bayar" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold text-warning">Potongan / Diskon (Rp)</label>
                                    <input type="number" class="form-control" id="cb_potongan" value="0" min="0" oninput="calculateCloseBillingAmounts()">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Tambahan Biaya (Rp)</label>
                                    <input type="number" class="form-control" id="cb_tambahan" value="0" min="0" oninput="calculateCloseBillingAmounts()">
                                </div>
                            </div>

                            <div class="mb-3 border-top pt-3">
                                <label class="form-label fw-bold text-dark">Metode Pembayaran (Cash/Transfer/Card)</label>
                                <div id="cb_payments_container">
                                    <!-- Dynamic Payments list -->
                                    <div class="payment-row row g-2 mb-2 align-items-center">
                                        <div class="col-7">
                                            <select class="form-select select-akun-bayar" onchange="calculateCloseBillingAmounts()">
                                                <option value="">-- Pilih Akun Pembayaran --</option>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <input type="number" class="form-control input-besar-bayar" value="0" min="0" placeholder="Besar Bayar" oninput="calculateCloseBillingAmounts()">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addCloseBillingPaymentRow()">
                                    <i class="ti ti-plus me-1"></i> Tambah Metode Bayar
                                </button>
                            </div>

                            <div class="mb-3 border-top pt-3" id="cb_piutang_section" style="display:none;">
                                <div class="alert alert-warning py-2 mb-2 small text-dark">
                                    <i class="ti ti-alert-triangle me-1 text-warning"></i> Sisa tagihan belum lunas akan dibebankan sebagai piutang.
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label fw-bold text-danger">Sisa Piutang (Rp)</label>
                                        <input type="text" class="form-control bg-light fw-bold text-danger" id="cb_sisa_piutang" readonly value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Akun Piutang</label>
                                        <select class="form-select" id="cb_kd_rek_piutang">
                                            <option value="">-- Pilih Akun Piutang --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success fw-bold" onclick="submitCloseBilling()">
                            <i class="ti ti-device-floppy me-1"></i> Simpan & Selesaikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function loadBillingRalan(no_rawat) {
        loadUnvalidatedResep(no_rawat);
        const tbody = $('#tabelBillingRalan tbody');
        const tfoot = $('#grandTotalBilling');
        
        tbody.html('<tr><td colspan="4" class="text-center p-4"><div class="spinner-border spinner-border-sm text-secondary me-2"></div> Sedang menghitung rincian billing...</td></tr>');
        tfoot.text('Rp. 0');

        $.get(`{{ url('/billing/ralan') }}`, { no_rawat: no_rawat })
            .done((response) => {
                // Populate Header
                $('#billing_no_nota').text(': ' + response.no_rawat);
                $('#billing_no_rm').text(': ' + response.no_rm);
                $('#billing_pasien').text(': ' + response.pasien);
                $('#billing_poli').text(': ' + response.poli);
                $('#billing_tgl').text(': ' + formatTanggal(response.tgl_perawatan));

                // Populate Payment Status Badge and Button text/style
                const badge = $('#billing_status_badge');
                const btn = $('#btnOpenCloseBilling');
                 if (response.status_bayar === 'Sudah Bayar') {
                    badge.html(`<span class="badge bg-success-lt text-success ms-2 px-2 py-1 align-middle d-inline-flex align-items-center" style="font-size: 11px; font-weight: 600; text-transform: none; gap: 4px; line-height: 1.2;"><i class="ti ti-lock"></i> Terkunci (Sudah Bayar)</span>`);
                    btn.html(`<i class="ti ti-lock-open me-1"></i> Update & Tutup Billing`).removeClass('btn-success').addClass('btn-warning');
                } else {
                    badge.html(`<span class="badge bg-warning-lt text-warning ms-2 px-2 py-1 align-middle d-inline-flex align-items-center" style="font-size: 11px; font-weight: 600; text-transform: none; gap: 4px; line-height: 1.2;"><i class="ti ti-lock-open"></i> Belum Bayar</span>`);
                    btn.html(`<i class="ti ti-lock me-1"></i> Simpan & Tutup Billing`).removeClass('btn-warning').addClass('btn-success');
                }

                let html = '';
                response.categories.forEach(cat => {
                    if (cat.items.length > 0) {
                        // Category Header
                        html += `<tr class="bg-light fw-bold">
                                    <td colspan="3" class="px-3 text-primary text-uppercase" style="font-size: 0.75rem;">${cat.label}</td>
                                    <td class="px-3 text-end text-primary">${new Intl.NumberFormat('id-ID').format(cat.total)}</td>
                                 </tr>`;
                        
                        // Category Items
                        cat.items.forEach(item => {
                            const isNegative = item.subtotal < 0;
                            html += `
                                <tr>
                                    <td class="px-3 ps-4 small ${isNegative ? 'text-danger' : ''}">${item.item}</td>
                                    <td class="px-3 text-center small">${item.qty}</td>
                                    <td class="px-3 text-end small">${new Intl.NumberFormat('id-ID').format(item.tarif)}</td>
                                    <td class="px-3 text-end small fw-500 ${isNegative ? 'text-danger' : ''}">
                                        ${new Intl.NumberFormat('id-ID').format(item.subtotal)}
                                    </td>
                                </tr>`;
                        });
                    }
                });
                
                if (html === '') {
                    html = '<tr><td colspan="4" class="text-center p-4 text-muted">Belum ada rincian biaya</td></tr>';
                }
                
                tbody.html(html);
                tfoot.text('Rp. ' + new Intl.NumberFormat('id-ID').format(response.grand_total));
            })
            .fail((err) => {
                tbody.html('<tr><td colspan="4" class="text-center text-danger p-4"><i class="ti ti-x"></i> Gagal memuat rincian billing</td></tr>');
            });
    }

    function cetakBilling(size) {
        const formCpptRajal = $('#formCpptRajal');
        const no_rawat = formCpptRajal.find('input[name=no_rawat]').val() || $('#billing_no_nota').text().replace(': ', '').trim();
        if (no_rawat && no_rawat !== '-') {
            const show_obat = $('#printShowObatDetail').is(':checked') ? 1 : 0;
            window.open(`{{ url('/billing/print') }}?no_rawat=${no_rawat}&size=${size}&show_obat=${show_obat}`, '_blank');
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Opps...',
                text: 'Nomor rawat tidak valid!'
            });
        }
    }

    function loadUnvalidatedResep(no_rawat) {
        const container = $('#sectionResepUnvalidated');
        const tbody = $('#tabelResepUnvalidated tbody');
        
        tbody.html('<tr><td colspan="6" class="text-center p-4"><div class="spinner-border spinner-border-sm text-secondary me-2"></div> Memuat data resep...</td></tr>');
        container.hide();

        $.get(`{{ url('/resep/unvalidated') }}`, { no_rawat: no_rawat })
            .done((response) => {
                if (response.resep && response.resep.length > 0) {
                    container.show();
                    let html = '';
                    
                    response.resep.forEach(resep => {
                        let drugs = [];
                        
                        resep.resep_dokter.forEach(rd => {
                            const isEnough = rd.stok >= rd.jml;
                            const stockStatus = isEnough 
                                ? `<span class="badge bg-success-lt fw-bold">Tersedia (${new Intl.NumberFormat('id-ID').format(rd.stok)})</span>`
                                : `<span class="badge bg-danger-lt fw-bold">Kurang (${new Intl.NumberFormat('id-ID').format(rd.stok)})</span>`;
                            drugs.push({
                                name: rd.obat ? rd.obat.nama_brng : 'Obat tidak ditemukan',
                                qty: `${new Intl.NumberFormat('id-ID').format(rd.jml)} ${rd.obat && rd.obat.satuan ? rd.obat.satuan.satuan : ''}`,
                                aturan: rd.aturan_pakai || '-',
                                stockStatus: stockStatus
                            });
                        });

                        resep.resep_racikan.forEach(rr => {
                            drugs.push({
                                name: `<strong>Racikan: ${rr.nama_racik}</strong>`,
                                qty: `${new Intl.NumberFormat('id-ID').format(rr.jml_dr)} Bks/Porsi`,
                                aturan: rr.aturan_pakai || '-',
                                stockStatus: '-'
                            });
                            
                            rr.detail.forEach(rrd => {
                                const isEnough = rrd.stok >= rrd.jml;
                                const stockStatus = isEnough 
                                    ? `<span class="badge bg-success-lt fw-bold">Tersedia (${new Intl.NumberFormat('id-ID').format(rrd.stok)})</span>`
                                    : `<span class="badge bg-danger-lt fw-bold">Kurang (${new Intl.NumberFormat('id-ID').format(rrd.stok)})</span>`;
                                drugs.push({
                                    name: `<span class="ms-3 text-muted">— ${rrd.obat ? rrd.obat.nama_brng : 'Obat tidak ditemukan'}</span>`,
                                    qty: `${new Intl.NumberFormat('id-ID').format(rrd.jml)} ${rrd.obat && rrd.obat.satuan ? rrd.obat.satuan.satuan : ''}`,
                                    aturan: '-',
                                    stockStatus: stockStatus
                                });
                            });
                        });

                        const rowSpan = drugs.length;
                        const actionBtn = `<button class="btn btn-sm btn-success fw-bold" onclick="validateResep('${resep.no_resep}')">
                                            <i class="ti ti-check me-1"></i> Validasi
                                           </button>`;
                        
                        if (rowSpan === 0) {
                            html += `<tr>
                                        <td class="px-3 py-2 fw-bold text-dark text-center">${resep.no_resep}<br><small class="text-muted">${formatTanggal(resep.tgl_peresepan)} ${resep.jam_peresepan}</small></td>
                                        <td colspan="4" class="text-center text-muted p-3">Tidak ada item obat</td>
                                        <td class="text-center px-3 py-2">${actionBtn}</td>
                                     </tr>`;
                        } else {
                            drugs.forEach((drug, index) => {
                                if (index === 0) {
                                    html += `<tr>
                                                <td rowspan="${rowSpan}" class="px-3 py-2 fw-bold text-dark text-center align-middle border-end">
                                                    <span class="fs-3 text-primary">${resep.no_resep}</span><br>
                                                    <small class="text-muted d-block mt-1">${formatTanggal(resep.tgl_peresepan)} ${resep.jam_peresepan}</small>
                                                    <span class="badge bg-blue-lt mt-2 d-inline-block text-wrap" style="max-width: 130px;">${resep.dokter ? resep.dokter.nm_dokter : '-'}</span>
                                                </td>
                                                <td class="px-3 py-2 small align-middle">${drug.name}</td>
                                                <td class="px-3 py-2 text-center small align-middle">${drug.qty}</td>
                                                <td class="px-3 py-2 small align-middle">${drug.aturan}</td>
                                                <td class="px-3 py-2 text-center small align-middle">${drug.stockStatus}</td>
                                                <td rowspan="${rowSpan}" class="text-center px-3 py-2 align-middle border-start">${actionBtn}</td>
                                             </tr>`;
                                } else {
                                    html += `<tr>
                                                <td class="px-3 py-2 small align-middle">${drug.name}</td>
                                                <td class="px-3 py-2 text-center small align-middle">${drug.qty}</td>
                                                <td class="px-3 py-2 small align-middle">${drug.aturan}</td>
                                                <td class="px-3 py-2 text-center small align-middle">${drug.stockStatus}</td>
                                             </tr>`;
                                }
                            });
                        }
                    });
                    
                    tbody.html(html);
                } else {
                    container.hide();
                    tbody.empty();
                }
            })
            .fail(() => {
                container.show();
                tbody.html('<tr><td colspan="6" class="text-center text-danger p-4"><i class="ti ti-x"></i> Gagal memuat resep yang belum divalidasi</td></tr>');
            });
    }

    function validateResep(no_resep) {
        Swal.fire({
            title: 'Validasi Resep Dokter?',
            text: 'Resep akan divalidasi langsung oleh dokter. Stok depo apotek akan dipotong, dan biaya obat akan dimasukkan ke dalam billing.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            cancelButtonColor: '#d63939',
            confirmButtonText: 'Ya, Validasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Validasi...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post(`{{ url('/resep/validate') }}`, {
                    _token: '{{ csrf_token() }}',
                    no_resep: no_resep
                })
                .done((response) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Resep Berhasil Divalidasi',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    const no_rawat = formCpptRajal.find('input[name=no_rawat]').val() || $('#billing_no_nota').text().replace(': ', '').trim();
                    loadBillingRalan(no_rawat);
                })
                .fail((err) => {
                    const errMsg = err.responseJSON ? err.responseJSON.message : 'Terjadi kesalahan sistem';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Validasi',
                        text: errMsg
                    });
                });
            }
        });
    }

    let accountsData = null;

    function showCloseBillingModal() {
        const formCpptRajal = $('#formCpptRajal');
        const no_rawat = formCpptRajal.find('input[name=no_rawat]').val() || $('#billing_no_nota').text().replace(': ', '').trim();
        if (!no_rawat || no_rawat === '-') {
            Swal.fire({
                icon: 'warning',
                title: 'Opps...',
                text: 'Nomor rawat tidak valid!'
            });
            return;
        }

        const nama_pasien = $('#billing_pasien').text().replace(': ', '').trim();
        const raw_total = parseFloat($('#grandTotalBilling').text().replace('Rp.', '').replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;

        $('#cb_no_rawat').val(no_rawat);
        $('#cb_no_rawat_display').val(no_rawat);
        $('#cb_nama_pasien').val(nama_pasien);
        $('#cb_total_tagihan').val(new Intl.NumberFormat('id-ID').format(raw_total));
        $('#cb_total_tagihan').data('raw-value', raw_total);
        $('#cb_potongan').val(0);
        $('#cb_tambahan').val(0);

        // Reset payments container to a single row
        $('#cb_payments_container').html(`
            <div class="payment-row row g-2 mb-2 align-items-center">
                <div class="col-7">
                    <select class="form-select select-akun-bayar" onchange="calculateCloseBillingAmounts()">
                        <option value="">-- Pilih Akun Pembayaran --</option>
                    </select>
                </div>
                <div class="col-5">
                    <input type="number" class="form-control input-besar-bayar" value="${raw_total}" min="0" placeholder="Besar Bayar" oninput="calculateCloseBillingAmounts()">
                </div>
            </div>
        `);

        // Fetch Accounts
        $.get("{{ url('/billing/accounts') }}", { no_rawat: no_rawat })
            .done((response) => {
                accountsData = response;
                
                // Populate Akun Bayar dropdowns
                populateAkunBayarSelects();
                
                // Populate Akun Piutang
                const piutangSelect = $('#cb_kd_rek_piutang');
                piutangSelect.empty().append('<option value="">-- Pilih Akun Piutang --</option>');
                response.akun_piutang.forEach(acc => {
                    const selected = response.default_piutang && response.default_piutang.kd_rek === acc.kd_rek ? 'selected' : '';
                    piutangSelect.append(`<option value="${acc.kd_rek}" ${selected}>${acc.nama_bayar} (${acc.kd_rek})</option>`);
                });

                calculateCloseBillingAmounts();
                $('#modalCloseBilling').modal('show');
            })
            .fail((xhr) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Akun',
                    text: xhr.responseJSON?.message || 'Gagal memuat daftar akun pembayaran'
                });
            });
    }

    function populateAkunBayarSelects() {
        if (!accountsData) return;
        $('.select-akun-bayar').each(function() {
            const select = $(this);
            const currentVal = select.val();
            select.empty().append('<option value="">-- Pilih Akun Pembayaran --</option>');
            accountsData.akun_bayar.forEach(acc => {
                select.append(`<option value="${acc.nama_bayar}">${acc.nama_bayar} (${acc.kd_rek})</option>`);
            });
            if (currentVal) {
                select.val(currentVal);
            }
        });
    }

    function addCloseBillingPaymentRow() {
        const row = $(`
            <div class="payment-row row g-2 mb-2 align-items-center">
                <div class="col-7">
                    <select class="form-select select-akun-bayar" onchange="calculateCloseBillingAmounts()">
                        <option value="">-- Pilih Akun Pembayaran --</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" class="form-control input-besar-bayar" value="0" min="0" placeholder="Besar Bayar" oninput="calculateCloseBillingAmounts()">
                </div>
                <div class="col-1 text-center">
                    <a href="javascript:void(0)" class="text-danger" onclick="$(this).closest('.payment-row').remove(); calculateCloseBillingAmounts();">
                        <i class="ti ti-trash fs-3"></i>
                    </a>
                </div>
            </div>
        `);
        $('#cb_payments_container').append(row);
        populateAkunBayarSelects();
    }

    function calculateCloseBillingAmounts() {
        const raw_total = parseFloat($('#cb_total_tagihan').data('raw-value')) || 0;
        const potongan = parseFloat($('#cb_potongan').val()) || 0;
        const tambahan = parseFloat($('#cb_tambahan').val()) || 0;
        
        const net_total = (raw_total + tambahan) - potongan;
        
        let total_bayar = 0;
        $('.payment-row').each(function() {
            const besar = parseFloat($(this).find('.input-besar-bayar').val()) || 0;
            total_bayar += besar;
        });

        const sisa = net_total - total_bayar;
        if (sisa > 0) {
            $('#cb_piutang_section').show();
            $('#cb_sisa_piutang').val(new Intl.NumberFormat('id-ID').format(sisa));
        } else {
            $('#cb_piutang_section').hide();
            $('#cb_sisa_piutang').val(0);
        }
    }

    function submitCloseBilling() {
        const no_rawat = $('#cb_no_rawat').val();
        const tgl_bayar = $('#cb_tgl_bayar').val();
        const potongan = parseFloat($('#cb_potongan').val()) || 0;
        const tambahan = parseFloat($('#cb_tambahan').val()) || 0;
        
        const raw_total = parseFloat($('#cb_total_tagihan').data('raw-value')) || 0;
        const net_total = (raw_total + tambahan) - potongan;

        let payments = [];
        let total_bayar = 0;
        let missingAccount = false;

        $('.payment-row').each(function() {
            const nama = $(this).find('.select-akun-bayar').val();
            const besar = parseFloat($(this).find('.input-besar-bayar').val()) || 0;
            if (besar > 0) {
                if (!nama) {
                    missingAccount = true;
                }
                payments.push({
                    nama_bayar: nama,
                    besar_bayar: besar
                });
                total_bayar += besar;
            }
        });

        if (missingAccount) {
            Swal.fire({
                icon: 'warning',
                title: 'Akun Pembayaran Kosong',
                text: 'Harap pilih akun pembayaran untuk nilai bayar yang diinput!'
            });
            return;
        }

        const sisa = net_total - total_bayar;
        let kd_rek_piutang = '';
        if (sisa > 0) {
            kd_rek_piutang = $('#cb_kd_rek_piutang').val();
            if (!kd_rek_piutang) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Akun Piutang Kosong',
                    text: 'Sisa tagihan belum lunas. Harap pilih akun piutang!'
                });
                return;
            }
        }

        Swal.fire({
            title: 'Selesaikan & Tutup Billing?',
            text: 'Tindakan ini akan mengunci rincian biaya pasien dan memposting jurnal keuangan!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2fb344',
            confirmButtonText: 'Ya, Simpan & Tutup',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan transaksi...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ url('/billing/close') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        no_rawat: no_rawat,
                        tgl_bayar: tgl_bayar,
                        potongan: potongan,
                        tambahan: tambahan,
                        payments: payments,
                        kd_rek_piutang: kd_rek_piutang
                    },
                    success: (response) => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#modalCloseBilling').modal('hide');
                        loadBillingRalan(no_rawat);
                    },
                    error: (xhr) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan sistem'
                        });
                    }
                });
            }
        });
    }

    $(document).ready(() => {
        // Move modal to body to prevent nested modal backdrop and tab conflicts
        $('#modalCloseBilling').appendTo('body');
    });
</script>
@endpush

<div class="card shadow-none border-0 bg-transparent">
    <div class="card-body p-0">
        <div class="alert alert-info border-start border-4 border-info mb-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="ti ti-info-circle fs-2 me-2"></i>
                <div>
                    <h4 class="mb-0">Rincian Billing Berjalan</h4>
                    <p class="mb-0 small">Estimasi total biaya selama pemeriksaan berlangsung</p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="form-check form-switch me-3 mb-0">
                    <input class="form-check-input" type="checkbox" id="printShowObatDetail" checked>
                    <label class="form-check-label small fw-bold text-dark mb-0" for="printShowObatDetail">Detail Obat</label>
                </div>
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
</script>
@endpush

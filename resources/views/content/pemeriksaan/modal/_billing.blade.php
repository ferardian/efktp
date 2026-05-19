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
            window.open(`{{ url('/billing/print') }}?no_rawat=${no_rawat}&size=${size}`, '_blank');
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Opps...',
                text: 'Nomor rawat tidak valid!'
            });
        }
    }
</script>
@endpush

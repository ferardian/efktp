@extends('layout')

@section('body')
    <div class="container-fluid h-100 p-0">
        <div class="card shadow-sm border-0">
            <!-- Header Filter Bar Top -->
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-teal-lt text-teal me-3 rounded-circle" style="width:40px; height:40px;">
                        <i class="ti ti-microscope fs-2"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 fw-bold text-dark">Daftar Pemeriksaan Laboratorium PK</h4>
                        <small class="text-muted">Riwayat hasil pemeriksaan laboratorium patologi klinis (Ralan &amp; Ranap)</small>
                    </div>
                </div>

                <!-- Filter Controls Toolbar Top -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="input-group input-group-sm shadow-xs" style="max-width: 330px; height: 32px;">
                        <span class="input-group-text bg-light text-muted border-end-0 py-0" style="font-size: 11px; height: 32px;"><i class="ti ti-calendar me-1"></i> Periode</span>
                        <input type="text" class="form-control filterTangal bg-white text-center py-0" id="tglFilterAwal" placeholder="Awal" style="height: 32px; font-size: 11px;" />
                        <span class="input-group-text bg-light text-muted py-0" style="font-size: 11px; height: 32px;">s.d.</span>
                        <input type="text" class="form-control filterTangal bg-white text-center py-0" id="tglFilterAkhir" placeholder="Akhir" style="height: 32px; font-size: 11px;" />
                    </div>

                    <div class="shadow-xs" style="width: 140px; height: 32px;">
                        <select class="form-select form-select-sm py-0" id="selectStatusLanjut" style="height: 32px; font-size: 11px;">
                            <option value="" selected>-- Semua --</option>
                            <option value="ralan">Rawat Jalan</option>
                            <option value="ranap">Rawat Inap</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm shadow-xs px-3 d-inline-flex align-items-center justify-content-center m-0" id="btnFilterSearch" style="height: 32px; font-size: 11px;">
                        <i class="ti ti-search me-1"></i> Filter
                    </button>

                    <button type="button" class="btn btn-outline-secondary btn-sm shadow-xs p-0 d-inline-flex align-items-center justify-content-center m-0" id="btnRefreshLab" title="Refresh Data" style="height: 32px; width: 32px; font-size: 11px;">
                        <i class="ti ti-refresh"></i>
                    </button>

                    <a href="{{ url('/lab/permintaan') }}" class="btn btn-outline-primary btn-sm shadow-xs px-2 d-inline-flex align-items-center justify-content-center m-0" title="Buka Daftar Permintaan Lab PK" style="height: 32px; font-size: 11px;">
                        <i class="ti ti-flask me-1"></i> Permintaan Lab PK
                    </a>
                </div>
            </div>

            <!-- Card Body Table -->
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle" id="tablePeriksaLab" style="font-size:11px; width:100%;">
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('content.laboratorium.modal._modalHasilPeriksaLab')
@endsection

@push('script')
    <script>
        const tglFilterAwal = $('#tglFilterAwal');
        const tglFilterAkhir = $('#tglFilterAkhir');

        $(document).ready(() => {
            tglFilterAwal.val(tglAwal);
            tglFilterAkhir.val(tglAkhir);

            loadTablePeriksa(getActiveFilter());
        });

        $('#btnFilterSearch').on('click', () => {
            loadTablePeriksa(getActiveFilter());
        });

        $('#selectStatusLanjut').on('change', () => {
            loadTablePeriksa(getActiveFilter());
        });

        $('#btnRefreshLab').on('click', () => {
            loadTablePeriksa(getActiveFilter());
        });

        function getActiveFilter() {
            return {
                tgl_periksa: [tglFilterAwal.val(), tglFilterAkhir.val()],
                status: $('#selectStatusLanjut').val()
            };
        }

        function loadTablePeriksa(keyword = null) {
            if (!keyword) keyword = getActiveFilter();
            const table = new DataTable('#tablePeriksaLab', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '60vh',
                scrollX: true,
                ajax: {
                    url: `{{ url('/lab/periksa/list') }}`,
                    data: {
                        dataTable: keyword,
                    },
                    dataSrc: 'data',
                },
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        title: 'Tgl Periksa',
                        data: 'tgl_periksa',
                        render: (data, type, row) => `<strong>${formatTanggal(data)}</strong><br><span class="text-muted small">${row.jam || ''}</span>`,
                    },
                    {
                        title: 'No. Rawat & Pasien',
                        data: 'nm_pasien',
                        render: (data, type, row) => `<span class="text-muted small">${row.no_rawat} &bull; RM ${row.no_rkm_medis}</span><br/><strong>${data || '-'}</strong> (${row.jk || '-'})`,
                    },
                    {
                        title: 'Umur',
                        data: 'umurdaftar',
                        render: (data, type, row) => data ? `${data} ${row.sttsumur || ''}` : '-',
                    },
                    {
                        title: 'Unit / Poli',
                        data: 'nm_poli',
                        render: (data, type, row) => {
                            const badgeStatus = (row.status || '').toLowerCase() === 'ranap'
                                ? `<span class="badge bg-purple-lt text-purple me-1">Ranap</span>`
                                : `<span class="badge bg-azure-lt text-azure me-1">Ralan</span>`;
                            return `${badgeStatus}${data || '-'}`;
                        },
                    },
                    {
                        title: 'Pemeriksaan',
                        data: 'paket_list',
                        render: (data) => {
                            if (!Array.isArray(data) || !data.length) return '-';
                            return data.map(p => `<span class="badge bg-teal-lt text-teal me-1 mb-1">${p}</span>`).join('');
                        },
                    },
                    {
                        title: 'Dokter PJ / Perujuk',
                        data: 'nm_dokter_pj',
                        render: (data, type, row) => `<strong>PJ:</strong> ${data || '-'}<br><span class="text-muted small">Perujuk: ${row.nm_dokter_perujuk || '-'}</span>`,
                    },
                    {
                        title: 'Petugas Lab',
                        data: 'nm_petugas',
                        render: (data) => data || '-',
                    },
                    {
                        title: 'Pembiayaan',
                        data: 'png_jawab',
                        render: (data) => {
                            if (!data || data === '-') return '-';
                            return data.includes('BPJS')
                                ? `<span class="badge text-bg-success px-2 py-1 d-inline-flex align-items-center">${data.toUpperCase()}</span>`
                                : `<span class="badge text-bg-primary px-2 py-1 d-inline-flex align-items-center">${data.toUpperCase()}</span>`;
                        },
                    },
                    {
                        title: 'Aksi',
                        data: null,
                        orderable: false,
                        render: (data, type, row) => {
                            const cetakUrl = `{{ url('/lab/periksa/cetak') }}?no_rawat=${encodeURIComponent(row.no_rawat)}&tgl_periksa=${encodeURIComponent(row.tgl_periksa)}&jam=${encodeURIComponent(row.jam || '')}`;
                            return `
                                <div class="d-flex align-items-center gap-1">
                                    <button class="btn btn-sm btn-outline-info shadow-xs px-2 py-1 d-inline-flex align-items-center justify-content-center" onclick="showHasilPermintaanLab('${row.no_rawat}', '${row.tgl_periksa}')" title="Lihat Detail Hasil Lab">
                                        <i class="ti ti-eye me-1"></i> Hasil
                                    </button>
                                    <a href="${cetakUrl}" target="_blank" class="btn btn-sm btn-danger shadow-xs px-2 py-1 d-inline-flex align-items-center justify-content-center" title="Cetak PDF Hasil Lab">
                                        <i class="ti ti-printer me-1"></i> PDF
                                    </a>
                                </div>
                            `;
                        },
                    }
                ]
            })
            .on('click', 'td.dt-control', function(e) {
                const tr = e.target.closest('tr');
                const row = table.row(tr);
                const result = row.data();

                if (row.child.isShown()) {
                    row.child.hide();
                } else {
                    $.get(`{{ url('/lab/periksa/get') }}`, {
                        no_rawat: result.no_rawat,
                        tgl: result.tgl_periksa
                    }).done((response) => {
                        const list = response?.data?.result || [];
                        if (!list.length) {
                            row.child('<div class="p-2 text-muted small fst-italic">Belum ada detail item pemeriksaan.</div>').show();
                            return;
                        }

                        const detailHtml = list.map(paket => {
                            const items = paket.detail || [];
                            if (!items.length) {
                                return `<tr><td class="fw-bold bg-light">${paket.jenis?.nm_perawatan || '-'}</td><td colspan="4" class="text-muted fst-italic">Tidak ada sub-item detail</td></tr>`;
                            }
                            return items.map((d, idx) => `
                                <tr>
                                    ${idx === 0 ? `<td rowspan="${items.length}" class="align-middle fw-bold bg-light">${paket.jenis?.nm_perawatan || '-'}</td>` : ''}
                                    <td>${d.template?.nama || '-'}</td>
                                    <td class="fw-semibold text-center">${d.nilai || '-'} ${d.template?.satuan || ''}</td>
                                    <td class="text-center">${d.nilai_rujukan || '-'}</td>
                                    <td class="text-center">${d.keterangan || '-'}</td>
                                </tr>
                            `).join('');
                        }).join('');

                        const child = `
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr class="bg-light text-center">
                                        <th width="22%">Paket Pemeriksaan</th>
                                        <th width="30%">Parameter</th>
                                        <th width="16%">Hasil</th>
                                        <th width="18%">Nilai Rujukan</th>
                                        <th width="14%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>${detailHtml}</tbody>
                            </table>
                        `;
                        row.child(child).show();
                    });
                }
            });
        }
    </script>
@endpush

@extends('layout')

@section('body')
    <div class="container-fluid h-100 p-0">
        <div class="card shadow-sm border-0">
            <!-- Header Filter Bar Top -->
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-primary-lt text-primary me-3 rounded-circle" style="width:40px; height:40px;">
                        <i class="ti ti-flask fs-2"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 fw-bold text-dark">Daftar Permintaan & Processing Laboratorium</h4>
                        <small class="text-muted">Kelola sampel, input hasil lab, dan pengakuan jurnal akuntansi</small>
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
                            <option value="">-- Semua --</option>
                            <option value="ralan" selected>Rawat Jalan</option>
                            <option value="ranap">Rawat Inap</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm shadow-xs px-3 d-inline-flex align-items-center justify-content-center m-0" id="btnFilterSearch" style="height: 32px; font-size: 11px;">
                        <i class="ti ti-search me-1"></i> Filter
                    </button>

                    <button type="button" class="btn btn-outline-secondary btn-sm shadow-xs p-0 d-inline-flex align-items-center justify-content-center m-0" id="btnRefreshLab" title="Refresh Data" style="height: 32px; width: 32px; font-size: 11px;">
                        <i class="ti ti-refresh"></i>
                    </button>
                </div>
            </div>

            <!-- Card Body Table -->
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle" id="tablePermintaanLab" style="font-size:11px; width:100%;">
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pengambilan Sampel -->
    <div class="modal modal-blur fade" id="modalSampelLab" tabindex="-1" role="dialog" aria-labelledby="modalSampelLab" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-white py-2">
                    <h5 class="modal-title mb-0"><i class="ti ti-test-pipe me-1"></i> Pengambilan Sampel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <input type="hidden" id="sampelNoorder">
                    <div class="mb-3">
                        <label class="form-label required small fw-bold">Tgl Sampel</label>
                        <input type="date" class="form-control form-control-sm" id="sampelTgl" value="{{ date('Y-m-d') }}" />
                    </div>
                    <div class="mb-2">
                        <label class="form-label required small fw-bold">Jam Sampel</label>
                        <input type="time" class="form-control form-control-sm" id="sampelJam" value="{{ date('H:i:s') }}" step="1" />
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btnSimpanSampelLab">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Sampel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('content.laboratorium.modal._modalInputHasilLab')
    @include('content.laboratorium.modal._modalHasilPeriksaLab')
@endsection

@push('script')
    <script>
        const tglFilterAwal = $('#tglFilterAwal');
        const tglFilterAkhir = $('#tglFilterAkhir');

        $(document).ready(() => {
            tglFilterAwal.val(tglAwal);
            tglFilterAkhir.val(tglAkhir);

            loadTablePermintaan({
                tgl_permintaan: [tglAwal, tglAkhir],
                status: $('#selectStatusLanjut').val()
            });
        });

        $('#btnFilterSearch').on('click', () => {
            loadTablePermintaan({
                tgl_permintaan: [tglFilterAwal.val(), tglFilterAkhir.val()],
                status: $('#selectStatusLanjut').val()
            });
        });

        $('#selectStatusLanjut').on('change', () => {
            loadTablePermintaan({
                tgl_permintaan: [tglFilterAwal.val(), tglFilterAkhir.val()],
                status: $('#selectStatusLanjut').val()
            });
        });

        $('#btnRefreshLab').on('click', () => {
            loadTablePermintaan({
                tgl_permintaan: [tglFilterAwal.val(), tglFilterAkhir.val()],
                status: $('#selectStatusLanjut').val()
            });
        });

        function getActiveFilter() {
            return {
                tgl_permintaan: [tglFilterAwal.val(), tglFilterAkhir.val()],
                status: $('#selectStatusLanjut').val()
            };
        }

        function loadTablePermintaan(keyword = null) {
            if (!keyword) keyword = getActiveFilter();
            const table = new DataTable('#tablePermintaanLab', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '60vh',
                scrollX: true,
                ajax: {
                    url: `{{ url('/lab/permintaan/get') }}`,
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
                        title: 'No. Order',
                        data: 'noorder',
                        render: (data) => `<strong class="text-primary">${data}</strong>`,
                    },
                    {
                        title: 'Tgl Permintaan',
                        data: 'tgl_permintaan',
                        render: (data, type, row) => `${formatTanggal(data)} ${row.jam_permintaan || ''}`,
                    },
                    {
                        title: 'Pasien',
                        data: 'pasien',
                        render: (data, type, row) => `<span class="text-muted small">${row.no_rawat}</span><br/> <strong>${data?.nm_pasien || '-'}</strong> (${data?.jk || '-'})`,
                    },
                    {
                        title: 'Umur',
                        data: 'registrasi',
                        render: (data) => data ? `${data.umurdaftar} ${data.sttsumur}` : '-',
                    },
                    {
                        title: 'Poliklinik',
                        data: 'poliklinik.nm_poli',
                        render: (data) => data || '-',
                    },
                    {
                        title: 'Perujuk',
                        data: 'perujuk.nm_dokter',
                        render: (data) => data || '-',
                    },
                    {
                        title: 'Sampel',
                        data: 'tgl_sampel',
                        render: (data, type, row) => {
                            if (data && data !== '0000-00-00' && data !== '') {
                                return `<span class="badge bg-success-lt text-success px-2 py-1 d-inline-flex align-items-center gap-1" title="Sampel Diambil"><i class="ti ti-check"></i><span>${formatTanggal(data)} ${row.jam_sampel || ''}</span></span>`;
                            }
                            return `<span class="badge bg-warning-lt text-warning px-2 py-1 d-inline-flex align-items-center gap-1"><i class="ti ti-clock"></i><span>Belum Sampel</span></span>`;
                        },
                    },
                    {
                        title: 'Hasil',
                        data: 'tgl_hasil',
                        render: (data, type, row) => {
                            if (data && data !== '0000-00-00' && data !== '') {
                                return `<span class="badge bg-primary-lt text-primary px-2 py-1 d-inline-flex align-items-center gap-1" title="Hasil Selesai"><i class="ti ti-file-check"></i><span>${formatTanggal(data)} ${row.jam_hasil || ''}</span></span>`;
                            }
                            return `<span class="badge bg-secondary-lt text-secondary px-2 py-1 d-inline-flex align-items-center gap-1"><i class="ti ti-hourglass-empty"></i><span>Belum Hasil</span></span>`;
                        },
                    },
                    {
                        title: 'Pembiayaan',
                        data: 'penjab.nama',
                        render: (data) => {
                            if (!data) return '-';
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
                            const isSampelDone = row.tgl_sampel && row.tgl_sampel !== '0000-00-00' && row.tgl_sampel !== '';
                            const isHasilDone = row.tgl_hasil && row.tgl_hasil !== '0000-00-00' && row.tgl_hasil !== '';

                            return `
                                <div class="d-flex align-items-center gap-1">
                                    <button class="btn btn-sm ${isSampelDone ? 'btn-outline-secondary' : 'btn-warning text-dark'} shadow-xs px-2 py-1 d-inline-flex align-items-center justify-content-center" onclick="openModalSampelLab('${row.noorder}', '${row.tgl_sampel || ''}', '${row.jam_sampel || ''}')" title="Pengambilan Sampel">
                                        <i class="ti ti-test-pipe me-1"></i> Sampel
                                    </button>
                                    <button class="btn btn-sm ${isHasilDone ? 'btn-outline-success' : 'btn-primary'} shadow-xs px-2 py-1 d-inline-flex align-items-center justify-content-center" onclick="openModalInputHasilLab('${row.noorder}')" title="Input / Edit Hasil Lab">
                                        <i class="ti ti-edit me-1"></i> ${isHasilDone ? 'Edit Hasil' : 'Input Hasil'}
                                    </button>
                                    ${isHasilDone ? `
                                        <button class="btn btn-sm btn-outline-info shadow-xs px-2 py-1 d-inline-flex align-items-center justify-content-center" onclick="showHasilPermintaanLab('${row.no_rawat}', '${row.tgl_hasil}')" title="Lihat Hasil Lab">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    ` : ''}
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
                    getDetailItemPermintaanLab(result.noorder).done((response) => {
                        const { data } = response;
                        const groupedData = data.reduce((acc, value) => {
                            const { item, jenis } = value;
                            const { nm_perawatan } = jenis;
                            if (!acc[nm_perawatan]) {
                                acc[nm_perawatan] = [];
                            }
                            acc[nm_perawatan].push(item);
                            return acc;
                        }, {});

                        const detail = Object.keys(groupedData).map(nm_perawatan => {
                            const items = groupedData[nm_perawatan];
                            return items.map((item, index) => `
                                <tr>
                                    ${index === 0 ? `<td rowspan="${items.length}" class="text-center align-middle fw-bold bg-light">${nm_perawatan}</td>` : ''}
                                    <td>${item.nama}</td>
                                    <td>${setNilaiRujukan(item, result.pasien?.jk, result.registrasi?.umurdaftar)}</td>
                                </tr>
                            `).join('');
                        }).join('');

                        const child = `
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr class="bg-light text-center">
                                        <th width="30%">Paket Pemeriksaan</th>
                                        <th width="40%">Item Detail</th>
                                        <th width="30%">Nilai Rujukan Standar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${detail}
                                </tbody>
                            </table>
                        `;
                        row.child(child).show();
                    });
                }
            });
        }

        function getDetailItemPermintaanLab(noorder) {
            return $.get(`{{ url('/lab/permintaan/detail') }}/${noorder}`);
        }

        function setNilaiRujukan(item, jk, umur) {
            let rujukan = '';
            switch (jk) {
                case 'L':
                    rujukan = (umur < 12) ? `${item.la} ${item.satuan}` : `${item.ld} ${item.satuan}`;
                    break;
                case 'P':
                    rujukan = (umur < 12) ? `${item.pa} ${item.satuan}` : `${item.pd} ${item.satuan}`;
                    break;
                default:
                    rujukan = '-';
            }
            return rujukan;
        }

        function openModalSampelLab(noorder, tglSampel, jamSampel) {
            $('#sampelNoorder').val(noorder);
            const nowTgl = tglSampel && tglSampel !== '0000-00-00' ? tglSampel : "{{ date('Y-m-d') }}";
            const nowJam = jamSampel && jamSampel !== '00:00:00' ? jamSampel : "{{ date('H:i:s') }}";
            $('#sampelTgl').val(nowTgl);
            $('#sampelJam').val(nowJam);
            $('#modalSampelLab').modal('show');
        }

        $('#btnSimpanSampelLab').on('click', function() {
            const noorder = $('#sampelNoorder').val();
            const tgl_sampel = $('#sampelTgl').val();
            const jam_sampel = $('#sampelJam').val();

            if (!noorder || !tgl_sampel || !jam_sampel) {
                Swal.fire('Peringatan', 'Lengkapi tanggal dan jam sampel', 'warning');
                return;
            }

            if (document.activeElement) document.activeElement.blur();

            loadingAjax('Saving sample timestamp...');
            $.post(`{{ url('/lab/permintaan/sampel') }}`, {
                noorder: noorder,
                tgl_sampel: tgl_sampel,
                jam_sampel: jam_sampel,
            }).done((res) => {
                Swal.close();
                if (res.status) {
                    $('#modalSampelLab').modal('hide');
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        loadTablePermintaan();
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan sampel', 'error');
                }
            }).fail((err) => {
                Swal.close();
                alertErrorAjax(err);
            });
        });
    </script>
@endpush

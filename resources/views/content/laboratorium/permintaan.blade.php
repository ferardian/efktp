@extends('layout')

@section('body')
    <div class="containet-xl h-100">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-flask me-2"></i>Daftar Permintaan & Processing Laboratorium</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                        <table class="table table-sm table-hover align-middle" id="tablePermintaanLab" style="font-size:11px; width:100%;">

                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row g-2 align-items-center">
                    <div class="col-xl-4 col-lg-5 col-md-12 col-sm-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Periode</span>
                            <input type="text" class="form-control filterTangal" id="tglFilterAwal" />
                            <span class="input-group-text">s.d.</span>
                            <input type="text" class="form-control filterTangal" id="tglFilterAkhir" />
                            <button type="button" class="btn btn-primary" id="btnFilterSearch"><i class="ti ti-search me-1"></i> Cari</button>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12">
                        <select class="form-select form-select-sm" id="selectStatusLanjut">
                            <option value="">-- Semua Status --</option>
                            <option value="ralan" selected>Rawat Jalan</option>
                            <option value="ranap">Rawat Inap</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pengambilan Sampel -->
    <div class="modal modal-blur fade" id="modalSampelLab" tabindex="-1" role="dialog" aria-labelledby="modalSampelLab" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="ti ti-test-pipe me-1"></i> Pengambilan Sampel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="sampelNoorder">
                    <div class="mb-2">
                        <label class="form-label required">Tgl Sampel</label>
                        <input type="date" class="form-control form-control-sm" id="sampelTgl" value="{{ date('Y-m-d') }}" />
                    </div>
                    <div class="mb-2">
                        <label class="form-label required">Jam Sampel</label>
                        <input type="time" class="form-control form-control-sm" id="sampelJam" value="{{ date('H:i:s') }}" step="1" />
                    </div>
                </div>
                <div class="modal-footer">
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
            });
        });

        $('#btnFilterSearch').on('click', () => {
            loadTablePermintaan({
                tgl_permintaan: [tglFilterAwal.val(), tglFilterAkhir.val()],
            });
        });

        function loadTablePermintaan(keyword = {}) {
            const table = new DataTable('#tablePermintaanLab', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '55vh',
                scrollX: true,
                ajax: {
                    url: `{{ url('/lab/permintaan/get') }}`,
                    data: {
                        dataTable: keyword,
                    },
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
                        render: (data) => `<strong>${data}</strong>`,
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
                                return `<span class="badge bg-success-lt text-success" title="Sampel Diambil"><i class="ti ti-check me-1"></i>${formatTanggal(data)} ${row.jam_sampel || ''}</span>`;
                            }
                            return `<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock me-1"></i>Belum Sampel</span>`;
                        },
                    },
                    {
                        title: 'Hasil',
                        data: 'tgl_hasil',
                        render: (data, type, row) => {
                            if (data && data !== '0000-00-00' && data !== '') {
                                return `<span class="badge bg-primary-lt text-primary" title="Hasil Selesai"><i class="ti ti-file-check me-1"></i>${formatTanggal(data)} ${row.jam_hasil || ''}</span>`;
                            }
                            return `<span class="badge bg-secondary-lt text-secondary"><i class="ti ti-hourglass-empty me-1"></i>Belum Hasil</span>`;
                        },
                    },
                    {
                        title: 'Pembiayaan',
                        data: 'penjab.nama',
                        render: (data) => {
                            if (!data) return '-';
                            return data.includes('BPJS') 
                                ? `<span class="badge text-bg-success">${data.toUpperCase()}</span>`
                                : `<span class="badge text-bg-primary">${data.toUpperCase()}</span>`;
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn ${isSampelDone ? 'btn-outline-secondary' : 'btn-outline-warning'} btn-sm" onclick="openModalSampelLab('${row.noorder}', '${row.tgl_sampel || ''}', '${row.jam_sampel || ''}')" title="Pengambilan Sampel">
                                        <i class="ti ti-test-pipe me-1"></i> Sampel
                                    </button>
                                    <button class="btn ${isHasilDone ? 'btn-outline-success' : 'btn-primary'} btn-sm" onclick="openModalInputHasilLab('${row.noorder}')" title="Input / Edit Hasil Lab">
                                        <i class="ti ti-edit me-1"></i> ${isHasilDone ? 'Edit Hasil' : 'Input Hasil'}
                                    </button>
                                    ${isHasilDone ? `
                                        <button class="btn btn-outline-info btn-sm" onclick="showHasilPermintaanLab('${row.no_rawat}', '${row.tgl_hasil}')" title="Lihat Hasil Lab">
                                            <i class="ti ti-eye me-1"></i> Lihat
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

            loadingAjax('Saving sample timestamp...');
            $.post(`{{ url('/lab/permintaan/sampel') }}`, {
                noorder: noorder,
                tgl_sampel: tgl_sampel,
                jam_sampel: jam_sampel,
            }).done((res) => {
                Swal.close();
                if (res.status) {
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        $('#modalSampelLab').modal('hide');
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

@extends('layout')

@section('body')
    <div class="containet-xl h-100">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                        <table class="table table-sm table-hover" id="tablePermintaanLab" style="font-size:11px;">

                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                        <div class="input-group">
                            <input type="text" class="form-control filterTangal" id="tglFilterAwal" />
                            <span class="input-group-text">s.d.</span>
                            <input type="text" class="form-control filterTangal" id="tglFilterAkhir" />
                            <button type="button" class="btn btn-secondary"><i class="ti ti-search"></i></button>
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12">
                        <select class="form-select form-select-2">
                            <option value="ralan">Rawat Jalan</option>
                            <option value="ranap">Rawat Inap</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal modal-blur fade" id="modalSampelLab" tabindex="-1" role="dialog" aria-labelledby="modalSampelLab" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Pengambilan Sampel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Client name</label>
                                <input type="datetime-local" class="form-control" id="tglSampelLab" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <a href="#" class="btn btn-link bth-danger link-secondary" data-bs-dismiss="modal"> Cancel </a> --}}
                    <a href="#" class="btn btn-primary btn-sm ms-auto" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Simpan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const tglFilterAwal = $('#tglFilterAwal')
        const tglFilterAkhir = $('#tglFilterAkhir')
        $(document).ready(() => {

            tglFilterAwal.val(tglAwal)
            tglFilterAkhir.val(tglAkhir)
            loadTablePermintaan({
                tgl_permintaan: ["2024-01-01", "{{ date('Y-m-d') }}"],
            });
        })

        // $('#tglSampelLab').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm:ss',
        //     locale: 'id'
        // })


        $('#tglSampelLab').on('change', (e) => {
            console.log(e)
        })

        function loadTablePermintaan(keyword = {}) {
            const table = new DataTable('#tablePermintaanLab', {
                    responsive: true,
                    stateSave: true,
                    serverSide: false,
                    destroy: true,
                    processing: true,
                    scrollY: '50vh',
                    scrollX: true,
                    ajax: {
                        url: `/efktp/lab/permintaan/get`,
                        data: {
                            dataTable: keyword,
                        },
                    },
                    createdRow: (row) => {
                        // console.log(row)
                    },
                    columns: [{
                            className: 'dt-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        }, {
                            title: 'No. Order',
                            data: 'noorder',
                            render: (data, type, row, meta) => {
                                return data;
                            },
                        },
                        {
                            title: 'Tanggal',
                            data: 'tgl_permintaan',
                            render: (data, type, row, meta) => {
                                return `${formatTanggal(data)} ${row.jam_permintaan}`;
                            },
                        },
                        {
                            title: 'Pasien',
                            data: 'pasien',
                            render: (data, type, row, meta) => {
                                return `<span class="text-muted">${row.no_rawat}</span><br/> ${data.nm_pasien} (${data.jk})`
                            },
                        },
                        {
                            title: 'Umur',
                            data: 'registrasi',
                            render: (data, type, row, meta) => {
                                return `${data.umurdaftar} ${data.sttsumur}`
                            },
                        },
                        {
                            title: 'Poliklinik',
                            data: 'poliklinik.nm_poli',
                            render: (data, type, row, meta) => {
                                return data
                            },
                        },
                        {
                            title: 'Perujuk',
                            data: 'perujuk.nm_dokter',
                            render: (data, type, row, meta) => {
                                return data
                            },
                        },
                        {
                            title: 'Diagnosa',
                            data: 'diagnosa_klinis',
                            render: (data, type, row, meta) => {
                                return data
                            },
                        },
                        {
                            title: 'Informasi',
                            data: 'informasi_tambahan',
                            render: (data, type, row, meta) => {
                                return data
                            },
                        },
                        {
                            title: 'Pembiayaan',
                            data: 'penjab.nama',
                            render: (data, type, row, meta) => {
                                let status = '';
                                if (data.includes('BPJS')) {
                                    status = `<span class="badge text-bg-success">${data.toUpperCase()}</span>`
                                } else {
                                    status = `<span class="badge text-bg-primary">${data.toUpperCase()}</span>`
                                }
                                return status;
                            },
                        },
                        {
                            title: 'Status',
                            data: 'status',
                            render: (data, type, row, meta) => {
                                let status = '';
                                if (data === 'ralan') {
                                    status = `<span class="badge text-bg-warning">${data.toUpperCase()}</span>`
                                } else {
                                    status = `<span class="badge text-bg-purple">${data.toUpperCase()}</span>`
                                }
                                return status;
                            },
                        },
                        {
                            title: '',
                            data: null,
                            orderable: false,
                            render: (data, type, row, meta) => {
                                return `
                                    <button class="btn btn-sm btn-primary" onclick="insertSampelLab('${row.noorder}')"><i class="ti ti-pencil"></i></button>
                                    <button class="btn btn-sm btn-success"><i class="ti ti-check"></i></button>
                                `
                            },
                        }
                    ]
                })
                .on('click', 'td.dt-control', function(e) {
                    const tr = e.target.closest('tr');
                    const row = table.row(tr);
                    const result = row.data()

                    if (row.child.isShown()) {
                        row.child.hide();
                    } else {
                        getDetailItemPermintaanLab(result.noorder).done((response) => {
                            const {
                                data
                            } = response;
                            const groupedData = data.reduce((acc, value) => {
                                const {
                                    item,
                                    jenis
                                } = value;
                                const {
                                    nm_perawatan
                                } = jenis;
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
                                            ${index === 0 ? `<td rowspan="${items.length}" class="text-center">${nm_perawatan}</td>` : ''}
                                            <td>${item.nama}</td>
                                            <td>${setNilaiRujukan(item, result.pasien.jk, result.registrasi.umurdaftar)}</td>

                                        </tr>
                                    `).join('');
                            }).join('');


                            const child =
                                `<table class="table table-sm table-bordered">
                                    <thead >
                                        <tr>
                                               <th class="text-center" width="20%">Pemeriksaan</th>
                                               <th class="text-center">Item</th>
                                               <th class="text-center">Nilai Rujukan</th>

                                    </thead>
                                         ${detail}
                            </table>`
                            row.child(child).show();
                        })
                    }
                });

        }

        function getDetailItemPermintaanLab(noorder) {
            return $.get(`/efktp/lab/permintaan/detail/${noorder}`)
        }

        function setNilaiRujukan(item, jk, umur) {
            let rujukan = '';
            switch (jk) {
                case 'L':
                    if (umur < 12) {
                        rujukan = `${item.la} ${item.satuan}`;
                    } else {
                        rujukan = `${item.ld} ${item.satuan}`;
                    }
                    break;
                case 'P':
                    if (umur < 12) {
                        rujukan = `${item.pa} ${item.satuan}`;
                    } else {
                        rujukan = `${item.pd} ${item.satuan}`;
                    }
                    break;
                default:
                    rujukan = '';
            }

            return rujukan;
        }

        const insertSampelLab = (noorder) => {
            $('#modalSampelLab').modal('show')
        }
    </script>
@endpush

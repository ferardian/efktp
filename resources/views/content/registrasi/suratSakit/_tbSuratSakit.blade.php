<div class="table-responsive">
    <table class="table table-sm table-hover table-striped nowrap" id="tbSuratSakit" width="100%"></table>
</div>
<div class="form" id="formFilterSakit">
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="input-group">
                <input class="form-control filterTangal" name="tgl_pertama" id="tgl_pertama"
                       value="{{ date('d-m-Y') }}"/>
                <span class="input-group-text">
                    <label for="">s.d</label>
                </span>
                <input class="form-control filterTangal" name="tgl_kedua" id="tgl_kedua" value="{{ date('d-m-Y') }}"/>
                <button class="btn btn-secondary" id="btnFilterSuratSakit"><i class="ti ti-search"></i></button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        $('#btnFilterSuratSakit').on('click', (e) => {
            const pertama = formFilterSakit.find('input[name=tgl_pertama]').val()
            const kedua = formFilterSakit.find('input[name=tgl_kedua]').val()
            loadSuratSakit(pertama, kedua)

        })

        function loadSuratSakit(tglAwal = '', tglAkhir = '') {
            const tabelSuratSakit = new DataTable('#tbSuratSakit', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '25vh',
                scrollX: true,
                ajax: {
                    url: `{{ url('surat/sakit') }}`,
                    data: {
                        dataTable: true,
                        tgl_pertama: tglAwal,
                        tgl_kedua: tglAkhir,
                    },
                },
                columns: [{
                    title: 'No. Surat',
                    data: 'no_surat',
                    render: (data, type, row, meta) => {
                        return data
                    },
                },
                    {
                        title: 'No. Rawat',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'No. RM',
                        data: 'reg_periksa.no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'Nama',
                        data: 'reg_periksa.pasien.nm_pasien',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'Umur',
                        data: 'reg_periksa',
                        render: (data, type, row, meta) => {
                            return `${data.umurdaftar} ${data.sttsumur}`
                        },
                    },
                    {
                        title: 'Tgl Awal',
                        data: 'tanggalawal',
                        render: (data, type, row, meta) => {
                            return splitTanggal(data)
                        },
                    }, {
                        title: 'Tgl Akhir',
                        data: 'tanggalakhir',
                        render: (data, type, row, meta) => {
                            return splitTanggal(data)
                        },
                    },
                    {
                        title: 'Lama Sakit',
                        data: 'lamasakit',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: '',
                        data: 'no_surat',
                        render: (data, type, row, meta) => {
                            return `<div class="d-inline-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteSuratSakit('${data}')" title="Hapus"><i class="ti ti-trash"></i></button>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-success" onclick="cetakSuratSakit('${data}')" title="Cetak Surat"><i class="ti ti-printer"></i></button>
                                    <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" title="Pilih Opsi Cetak"></button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li><h6 class="dropdown-header py-1 text-muted">Cetak dengan QR Barcode:</h6></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakSuratSakit('${data}', 'manual', '1')"><i class="ti ti-qrcode text-success me-2"></i>QR Barcode + Diagnosa Manual</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakSuratSakit('${data}', 'ada', '1')"><i class="ti ti-qrcode text-primary me-2"></i>QR Barcode + Dengan Diagnosa</a></li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li><h6 class="dropdown-header py-1 text-muted">Cetak Tanpa Barcode (Manual):</h6></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakSuratSakit('${data}', 'manual', '0')"><i class="ti ti-signature text-secondary me-2"></i>Tanpa Barcode + Diagnosa Manual</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="cetakSuratSakit('${data}', 'ada', '0')"><i class="ti ti-signature text-secondary me-2"></i>Tanpa Barcode + Dengan Diagnosa</a></li>
                                    </ul>
                                </div>
                            </div>`;
                        },
                    },

                ]
            })
        }

        function deleteSuratSakit(no_surat) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('surat/sakit/delete') }}/${no_surat}`).done((response) => {
                        alertSuccessAjax().then(() => {
                            loadSuratSakit(tglAwal, tglAkhir)
                            setNoSuratSakit().done((response) => {
                                formSuratSakit.find('input[name=no_surat]').val(response)
                            })
                        })
                    })
                }
            })
        }
    </script>
@endpush

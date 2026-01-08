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
                    url: `/efktp/surat/sakit`,
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
                            return `<button type="button" class="btn btn-sm btn-danger" onclick="deleteSuratSakit('${data}')"><i class="ti ti-trash"></i></button>
<button type="button" class="btn btn-sm btn-success" onclick="cetakSuratSakit('${data}')"><i class="ti ti-printer"></i></button>
<!--                            <a href="/efktp/surat/sakit/print/${data}" class="btn btn-sm btn-success" target="_blank"><i class="ti ti-printer"></i></a>-->
                            `;
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
                    $.post(`/efktp/surat/sakit/delete/${no_surat}`).done((response) => {
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

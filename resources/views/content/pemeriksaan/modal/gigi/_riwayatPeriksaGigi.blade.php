<div class="row">
    <div class="col">
        <table class="table table-sm" id="tbRiwayatGigi" width="100%">

        </table>
    </div>
</div>


@push('script')
    <script>
        var riwayatGigi = '';

        function loadRiwayatGigi(no_rkm_medis) {
            riwayatGigi = new DataTable('#tbRiwayatGigi', {
                responsive: true,
                stateSave: true,
                destroy: true,
                processing: true,
                lengthChange: false,
                searching: false,
                info: false,
                ajax: {
                    url: `/efktp/pemeriksaan/gigi/riwayat`,
                    data: {
                        dataTable: true,
                        no_rkm_medis: no_rkm_medis,
                    },
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    }, {
                        title: 'No. Rawat',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Nama',
                        data: 'pasien.nm_pasien',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    }, {
                        title: 'Umur',
                        data: 'umurdaftar',
                        render: (data, type, row, meta) => {
                            return `${data} ${row.sttsumur}`;
                        }
                    }, {
                        title: 'Dokter Gigi',
                        data: 'gigi.dokter.nm_dokter',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                ]
            })
        }

        $('#tbRiwayatGigi').on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let datatable = $('#tbRiwayatGigi').DataTable();
            let row = datatable.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            } else {
                // Open this row
                const data = row.data();
                row.child(formatChildGigi(data)).show();
            }
        });

        function formatChildGigi(data) {
            const hasilGigi = data.gigi.hasil;
            const tbOdonto = $('.odonto-riwayat').html();
            let hasil = '<div class="row gy-2">'
            hasil += hasilGigi.map((items) => {
                return `<div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card">
                        <div class="card-status-top bg-danger"></div>
                        <div class="card-body">Posisi : ${items.posisi_gigi}
                        <br/>
                        Hasil : ${items.hasil} (${symbolGigi(items.hasil, 16)})
                        <br/>
                        Dx : ${items.kd_penyakit} - ${items.diagnosa.nm_penyakit}
                        <br/>
                        Px : ${items.kd_tindakan} - ${items.tindakan.deskripsi_pendek}
                        </div>
                        </div></div>`
            }).join(' ')
            hasil += '</div>'

            return (hasil);

        }
    </script>
@endpush

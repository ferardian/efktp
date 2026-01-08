<div class="modal modal-blur fade" id="modallistKeluhanRanap" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive"></div>
                <table class="table table-sm table-striped table-hover nowrap" id="tabelPemeriksaanRanap" width="100%">

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnResetCpptRanap" class="btn btn-warning d-none"><i class="ti ti-reload me-1"></i>Baru</button>
                <button type="button" id="btnSalinCpptRanap" class="btn btn-primary d-none"><i class="ti ti-copy me-1"></i> Copy</button>
                <button type="button" id="btnSimpanCpptRanap" class="btn btn-success" onclick="createCpptRanap()"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        let modallistKeluhanRanap = $('#modallistKeluhanRanap')


        function listKeluhanRanap() {
            var no_rawat = formResumeMedis.find('input[name="no_rawat"]').val();
            var dpjp = formResumeMedis.find('input[name="kd_dokter"]').val();
            getCpptRanap(no_rawat).done((response) => {
                const data = response.map((item) => {
                    return {
                        no_rawat: no_rawat,
                        kategori: 'keluhan',
                        hasil: item[`keluhan`],
                        pegawai: item.pegawai.nama,
                        tanggal: `${splitTanggal(item.tgl_perawatan)} ${item.jam_rawat}`,
                    }
                })
                tabelPemeriksaan(data)
                modallistKeluhanRanap.modal('show')
            })

        }

        function listObjektifRanap() {
            var no_rawat = formResumeMedis.find('input[name="no_rawat"]').val();
            var dpjp = formResumeMedis.find('input[name="kd_dokter"]').val();
            getCpptRanap(no_rawat).done((response) => {
                const data = response.map((item) => {
                    return {
                        no_rawat: no_rawat,
                        kategori: 'pemeriksaan_fisik',
                        hasil: `TTV; Suhu : ${item.suhu_tubuh} °C; TD : ${item.tensi} mmHg; Nadi : ${item.nadi} x/m; RR : ${item.respirasi} x/m`,
                        pegawai: item.pegawai.nama,
                        tanggal: `${splitTanggal(item.tgl_perawatan)} ${item.jam_rawat}`,
                    }
                })
                tabelPemeriksaan(data)
                modallistKeluhanRanap.modal('show')
            })
        }


        function tabelPemeriksaan(data) {
            $('#tabelPemeriksaanRanap').DataTable({
                responsive: true,
                serverSide: false,
                autoWidth: true,
                destroy: true,
                processing: true,
                data: data,
                scrollY: '40vh',
                scrollX: true,
                createdRow: (row, data, index) => {
                    $(row).attr('data-id', index).attr('onclick', `setTextPeriksa(this)`)
                        .attr('data-target', `${data.kategori}`).attr('data-bs-dismiss', 'modal');
                },
                columns: [

                    {
                        title: 'tanggal',
                        data: 'tanggal',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Hasil',
                        data: 'hasil',
                        render: (data, type, row, meta) => {
                            return stringPemeriksaan(data);
                        },
                    },
                    {
                        title: 'Petugas/Dokter',
                        data: 'pegawai',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: '',
                        data: '',
                        render: (data, type, row, meta) => {
                            return `<button type="button" class="btn btn-sm btn-primary" style="width: 100%" data-target="${row.kategori}"> <i class="ti ti-copy"></i></button>`;
                        },
                    },
                ],

            })
        }

        function setTextPeriksa(e) {
            const text = $(e).find('td')[1].innerHTML;
            const element = $(`textarea[id='${e.dataset.target}']`);
            let value = element.val() !== '-' ? element.val().replaceAll('<br>', '').replaceAll('&lt;', '<').replaceAll('&gt;', '>') + ';\n' : '';
            value += text.replaceAll('&lt;', '<').replaceAll('&gt;', '>').replaceAll('<br>', '');
            element.val(value)
        }
    </script>
@endpush

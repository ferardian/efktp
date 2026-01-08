<div class="modal modal-blur fade" id="modalHasilPeriksaLab" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modalPermintaanLab modal-lg modal-dialog-cetered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Pemeriksaan Laboratorium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-2" id="cardPasienLab">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="">No. Rawat</label>
                                <input type="text" class="form-control" name="no_rawat" id="no_rawat" readonly>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="">Pasien</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="no_rkm_medis" id="no_rkm_medis" readonly>
                                    <input type="text" class="form-control w-50" name="nm_pasien" id="nm_pasien" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="">Tgl Lahir</label>
                                <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-responsive table-sm table-bordered table-hover" id="tableHasilPeriksaLab">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Pemeriksaan</th>
                            <th>Hasil</th>
                            <th>Nilai Rujukan</th>
                            <th>Ket</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        const modalHasilPeriksaLab = $('#modalHasilPeriksaLab')
        const tableHasilPeriksaLab = $('#tableHasilPeriksaLab');
        const cardPasienLab = $('#cardPasienLab');

        function showHasilPermintaanLab() {
            modalHasilPeriksaLab.modal('show');

            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien
                } = response;
                cardPasienLab.find('#no_rawat').val(no_rawat)
                cardPasienLab.find('#no_rkm_medis').val(response.no_rkm_medis)
                cardPasienLab.find('#nm_pasien').val(`${pasien.nm_pasien} (${pasien.jk})`)
                cardPasienLab.find('#tgl_lahir').val(`${splitTanggal(pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`)
            })

            $.get(`/efktp/lab/periksa/get`, {
                no_rawat: no_rawat,
                tgl: tgl
            }).done((response) => {
                const {
                    data
                } = response;

                if (data.count === 0) {
                    const row = `<tr><td colspan="6">Tidak ditemukan hasil</td></tr>`;
                    tableHasilPeriksaLab.find('tbody').empty().append(row);
                } else {
                    renderItemHasilPeriksaLab(data.result)
                }

            })
        }

        function renderItemHasilPeriksaLab(data) {
            console.log('data', data);
            tableHasilPeriksaLab.find('tbody').empty();

            const content = data.map((item, index) => {
                const sub = item.detail ? renderSubItemHasilPeriksaLab(item.detail) : ''
                return `<tr class="bg-muted-lt">
                        <td></td>
                        <td><strong>${item.jenis?.nm_perawatan}</strong></td>
                        <td class="text-center">${splitTanggal(item.tgl_periksa)} ${item.jam}</td>
                        <td colspan=2 class="text-center">${item.pegawai.nama}</td>
                    </tr>${sub}`
            });

            tableHasilPeriksaLab.find('tbody').append(content)

        }

        function renderSubItemHasilPeriksaLab(data) {
            return data.map((item, index) => {
                return `<tr class="${setColorItemLab(item.keterangan)}">
                        <td class="text-end">${index+1}</td>
                        <td><span class="ms-2">${item.template.nama}</span></td>
                        <td class="text-end">${item.nilai} ${item.template.satuan}</td>
                        <td class="text-end">${item.nilai_rujukan} ${item.template.satuan}</td>
                        <td class="text-center">${item.keterangan}</td>
                    </tr>`
            })
        }

        function setColorItemLab(ket) {
            switch (ket.toUpperCase()) {
                case 'L':
                    return 'bg-blue-lt'
                    break;
                case 'H':
                    return 'bg-red-lt'
                    break;
                default:
                    break;
            }

        }

        function getPeriksaLab(no_rawat) {
            return $.get(`/efktp/lab/periksa/get`, {
                no_rawat: no_rawat,
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function showPeriksaLab(no_rawat) {
            getPeriksaLab(no_rawat).done((response) => {
                const {
                    data
                } = response;

                if (data.count === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Informasi',
                        html: `Belum terdapat hasil pemeriksaan lab, silahkan hubungi petugas yang bersangkutan`

                    })
                } else {
                    modalHasilPeriksaLab.modal('show');
                    renderItemHasilPeriksaLab(data.result)
                }
            })
            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien
                } = response;
                cardPasienLab.find('#no_rawat').val(no_rawat)
                cardPasienLab.find('#no_rkm_medis').val(response.no_rkm_medis)
                cardPasienLab.find('#nm_pasien').val(`${pasien.nm_pasien} (${pasien.jk})`)
                cardPasienLab.find('#tgl_lahir').val(`${splitTanggal(pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`)
            })
        }
    </script>
@endpush

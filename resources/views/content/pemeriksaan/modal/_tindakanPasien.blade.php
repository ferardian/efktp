<div class="modal modal-blur fade" id="modalTindakanPasien" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prosdur/Tindakan Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="tbTindakanPasien" class="table table-responsive">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th>Kode ICD 9</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-sm btn-primary" id="btnTambahBarisTindakan">Tambah Tindakan</button>
                <button class="btn btn-sm btn-warning" id="btnCopyTindakan">Copy ke Instruksi</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success me-2" id="btnSimpanTindakan">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var tbTindakanPasien = $('#tbTindakanPasien');
        var bodyTindakanPasien = tbTindakanPasien.find('tbody');

        function tindakanPasien(no_rawat) {
            getTindakanPasien(no_rawat).done((response) => {
                bodyTindakanPasien.empty()
                if (response.length) {
                    const px = response.map((tindakan, index) => {
                        return `<tr id="rowPx${index+1}">
                            <td>${tindakan.prioritas}</td>
                            <td>${tindakan.kode} - ${tindakan.icd9.deskripsi_pendek}</td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" id="btnHapusTindakan" onclick="hapustindakanPasien('${no_rawat}', '${tindakan.kode}')"><i class="ti ti-trash-x"></i> Hapus</button></td>
                        </tr>`
                    });
                    bodyTindakanPasien.append(px)
                }
            })
            $('#modalTindakanPasien').modal('show')
            $(`#modalTindakanPasien #btnSimpanTindakan`).attr('onclick', `simpanTindakanPasien('${no_rawat}')`)
            $(`#modalTindakanPasien #btnCopyTindakan`).attr('onclick', `tulisInstruksi('${no_rawat}')`)
        }

        function simpanTindakanPasien(no_rawat) {
            let data = new Array();
            const row = bodyTindakanPasien.find('tr')
            for (let index = 1; index <= row.length; index++) {
                const findSelect = $(`#rowPx${index}`).find('select')
                if (findSelect.length) {
                    const dataDiagnosa = {
                        no_rawat: no_rawat,
                        kode: $(`#kode${index}`).val(),
                        prioritas: $(`#prioritasPx${index}`).html(),
                    }

                    data.push(dataDiagnosa)

                    const isEmpty = Object.values(dataDiagnosa).filter((item) => {
                        return item == null;
                    }).length
                    if (isEmpty) {
                        const errorMsg = {
                            status: 422,
                            statusText: 'Pastikan tidak ada kolom yang kosong'
                        }
                        alertErrorAjax(errorMsg)
                        return false;
                    }

                }
            }
            $.post(`/efktp/prosedur/pasien/create`, {
                data
            }).done((response) => {
                tindakanPasien(no_rawat);
                tulisInstruksi(no_rawat);
                $('#modalTindakanPasien').modal('hide')
            })

        }

        function hapusBarisTindakan(id) {
            $(`#rowPx${id}`).remove();
        }

        function hapustindakanPasien(no_rawat, kode) {

            Swal.fire({
                title: "Yakin hapus ?",
                html: "Anda tidak bisa mengembalikan data ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/prosedur/pasien/delete`, {
                        no_rawat: no_rawat,
                        kode: kode,
                    }).done((response) => {
                        tindakanPasien(no_rawat);
                        tulisInstruksi(no_rawat);
                    })
                }
            });

        }

        function tulisInstruksi(no_rawat) {
            getTindakanPasien(no_rawat).done((response) => {
                if (response.length) {
                    const px = response.map((prosedur, index) => {
                        return `${prosedur.prioritas}. ${prosedur.icd9.deskripsi_pendek}`
                    }).join(`\n`)
                    $('#formCpptRajal textarea[name=instruksi]').val(px)
                }

            })
        }


        $('#btnTambahBarisTindakan').on('click', (e) => {
            e.preventDefault();
            let rowCount = tbTindakanPasien.find('tr').length
            const addRow = `<tr id="rowPx${rowCount}">
                <td id="prioritasPx${rowCount}">${rowCount}</td>
                <td><select class="form-control" name="kode[]" id="kode${rowCount}" style="width:100%"/></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger" id="btnHapusTindakan" onclick="hapusBarisTindakan('${rowCount}')"><i class="ti ti-trash-x"></i> Hapus</button></td>
            </tr>`;

            bodyTindakanPasien.append(addRow);
            const select = $(`#kode${rowCount}`)
            selectTindakan(select, $('#modalTindakanPasien')).on('select2:select', (e) => {
                e.preventDefault();
                const data = e.params.data
            })
        })

        function getTindakanPasien(no_rawat) {
            const tindakan = $.get(`/efktp/prosedur/pasien/get`, {
                no_rawat: no_rawat
            })
            return tindakan
        }
    </script>
@endpush

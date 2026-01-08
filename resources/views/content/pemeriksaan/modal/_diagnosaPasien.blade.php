<div class="modal modal-blur fade" id="modalDiagnosaPasien" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Diagnosa Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="diagnosa/pasien" id="formDiagnosaPasien" method="post">
                    <table id="tbInfoPasienDiagnosa" class="table table-responsive">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Kode ICD 10</th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </form>
                <button class="btn btn-sm btn-primary" id="btnTambahBarisDiagnosa">Tambah Diagnosa</button>
                <button class="btn btn-sm btn-warning" id="btnCopyBarisDiagnosa">Copy ke Asesmen</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success me-2" id="btnSimpanDiagnosa">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var tbInfoPasienDiagnosa = $('#tbInfoPasienDiagnosa');
        var bodyInfoDiagnosa = tbInfoPasienDiagnosa.find('tbody');

        function diagnosaPasien(no_rawat) {
            getDiagnosaPasien(no_rawat).done((diagnosas) => {
                bodyInfoDiagnosa.empty()
                if (diagnosas.length) {
                    const dx = diagnosas.map((diagnosa, index) => {
                        return `<tr id="rowDx${index+1}">
                            <td>${diagnosa.prioritas}</td>
                            <td>${diagnosa.kd_penyakit} - ${diagnosa.penyakit.nm_penyakit}</td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" id="btnHapusDiagnosa" onclick="hapusDiagnosaPasien('${no_rawat}', '${diagnosa.kd_penyakit}')"><i class="ti ti-trash-x"></i> Hapus</button></td>
                        </tr>`
                    });

                    bodyInfoDiagnosa.append(dx)
                }
            })
            $('#modalDiagnosaPasien').modal('show')
            $(`#modalDiagnosaPasien #btnSimpanDiagnosa`).attr('onclick', `simpanDiagnosaPasien('${no_rawat}')`)
            $(`#modalDiagnosaPasien #btnCopyBarisDiagnosa`).attr('onclick', `tulisAsesmen('${no_rawat}')`)
        }

        function simpanDiagnosaPasien(no_rawat) {
            let data = new Array();

            const row = bodyInfoDiagnosa.find('tr')
            for (let index = 1; index <= row.length; index++) {
                const findSelect = $(`#rowDx${index}`).find('select')
                if (findSelect.length) {
                    const dataDiagnosa = {
                        no_rawat: no_rawat,
                        kd_penyakit: $(`#kd_penyakit${index}`).val(),
                        prioritas: $(`#prioritas${index}`).html(),
                    }

                    data.push(dataDiagnosa)
                    const isEmpty = Object.values(dataDiagnosa).filter((item) => {
                        return item == null || item == '';
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
            $.post(`/efktp/diagnosa/pasien/create`, {
                data
            }).done((response) => {
                diagnosaPasien(no_rawat);
                tulisAsesmen(no_rawat);
                $('#modalDiagnosaPasien').modal('hide')
            })

        }

        function hapusBarisDiagnosa(id) {
            $(`#rowDx${id}`).remove();
        }

        function hapusDiagnosaPasien(no_rawat, kd_penyakit) {
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
                    $.post(`/efktp/diagnosa/pasien/delete`, {
                        no_rawat: no_rawat,
                        kd_penyakit: kd_penyakit,
                    }).done((response) => {
                        diagnosaPasien(no_rawat);
                        tulisAsesmen(no_rawat);
                    })
                }
            });
        }



        function tulisAsesmen(no_rawat) {
            getDiagnosaPasien(no_rawat).done((response) => {
                if (response.length) {
                    const dx = response.map((diagnosa, index) => {
                        return `${diagnosa.prioritas}. ${diagnosa.penyakit.nm_penyakit}`
                    }).join(`\n`)
                    $('#formCpptRajal textarea[name=penilaian]').val(dx)
                }

            })
        }

        function selectDiagnosa(element, parrent) {
            const select2 = element.select2({
                dropdownParent: parrent,
                delay: 0,
                scrollAfterSelect: true,
                ajax: {
                    url: `/efktp/penyakit/get`,
                    dataType: 'JSON',

                    data: (params) => {
                        const query = {
                            penyakit: params.term
                        }
                        return query
                    },
                    processResults: (data) => {
                        return {
                            results: data.map((item) => {
                                const items = {
                                    id: item.kd_penyakit,
                                    text: `${item.kd_penyakit} - ${item.nm_penyakit}`,
                                    detail: item
                                }
                                return items;
                            })
                        }
                    }

                },
                cache: true

            });

            return select2;
        }

        $('#btnTambahBarisDiagnosa').on('click', (e) => {
            e.preventDefault();
            const tbDiagnosa = $('#tbInfoPasienDiagnosa');
            let rowCount = tbDiagnosa.find('tr').length
            const addRow = `<tr id="rowDx${rowCount}">
                <td id="prioritas${rowCount}">${rowCount}</td>
                <td><select class="form-control" name="kd_penyakit[]" id="kd_penyakit${rowCount}" style="width:100%"/></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger" id="btnHapusDiagnosa" onclick="hapusBarisDiagnosa('${rowCount}')"><i class="ti ti-trash-x"></i> Hapus</button></td>
            </tr>`;

            tbDiagnosa.append(addRow);
            const select = $(`#kd_penyakit${rowCount}`)
            selectDiagnosa(select, $('#modalDiagnosaPasien')).on('select2:select', (e) => {
                e.preventDefault();
                const data = e.params.data
                $(`#nm_penyakit${rowCount}`).val(data.nm_penyakit)
            })
        })

        function getDiagnosaPasien(no_rawat) {
            const diagnosa = $.get(`/efktp/diagnosa/pasien/get`, {
                no_rawat: no_rawat
            })
            return diagnosa
        }
    </script>
@endpush

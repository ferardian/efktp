<div class="table-responsive mb-2" style="height:350px;overflow-y:auto">
    <input type="hidden" id="no_resep" name="no_resep">
    <table class="table table-sm d-none mb-2 table-bordered" id="tabelResepUmum">
        <thead>
        <tr class="text-center">
            <th width="30%">Obat</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Aturan Pakai</th>
            <th>Subtotal</th>
            <th width="10%"></th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <button type="button" class="btn btn-sm btn-primary d-none" id="btnTambahObat">Tambah Obat</button>
    <button type="button" class="btn btn-sm btn-success d-none" id="btnSimpanResep">Simpan</button>
</div>
@push('script')
    <script>
        var btnTambahObat = $('#btnTambahObat')
        var btnSimpanResep = $('#btnSimpanResep')
        var tabelResepUmum = $('#tabelResepUmum')
        var bodyResepUmum = tabelResepUmum.find('tbody')

        function getResepDokter(no_resep) {
            const resepDokter = $.get(`/efktp/resep/dokter/get`, {
                no_resep: no_resep
            })
            return resepDokter;
        }

        function deleteResepDokter(no_resep, kode_brng) {
            const resepDokter = $.post(`/efktp/resep/dokter/delete`, {
                no_resep: no_resep,
                kode_brng: kode_brng,
            })
            return resepDokter
        }

        function setResepDokter(no_resep) {
            getResepDokter(no_resep).done((reseps) => {

                $('input[name=no_resep]').val(no_resep)
                bodyResepUmum.empty()
                if (reseps.length) {
                    tabelResepUmum.removeClass('d-none')
                    btnSimpanObat.removeClass('d-none')
                    btnTambahObat.removeClass('d-none')
                    const rowObat = reseps.map((resepDokter, index) => {
                        const numb = parseInt(index) + 1
                        const subTotal = resepDokter.jml * resepDokter.obat.ralan
                        return `<tr id="row${numb}">
                            <td id="obatUmum${numb}">${resepDokter.obat.nama_brng}</td>
                            <td id="harga${numb}" class="text-end">${formatCurrency(resepDokter.obat.ralan)}</td>
                            <td id="jmlUmum${numb}">${resepDokter.jml} ${resepDokter.obat.satuan?.satuan}</td>
                            <td id="aturanUmum${numb}">${resepDokter.aturan_pakai}</td>
                            <td id="subTotal${numb}" class="text-end">${formatCurrency(subTotal)}</td>
                            <td id="aksi${numb}">
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-yellow" onclick="editObatDokter(${numb}, '${resepDokter.kode_brng}')" title="Edit Obat">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusObatDokter(${no_resep}, '${resepDokter.kode_brng}')" title="Hapus Obat">
                                        <i class="ti ti-trash-x"></i>
                                    </button>    
                                </div>
                                
                            </td>
                        </tr>`;
                    }).join('');

                    bodyResepUmum.html(rowObat);

                    // hitung sub total di akhir row obat umum
                    const subTotalResepUmum = bodyResepUmum.find('tr').toArray().reduce((total, row) => {
                        const subTotalRow = $(row).find('td#subTotal' + $(row).attr('id').replace('row', '')).text().replace(/[^\d]/g, '');
                        return total + parseInt(subTotalRow)
                    }, 0);
                    const rowTotalObatUmum = `<tr id="rowTotalObatUmum"><td colspan="4" class="text-end"> Total</td><td class="text-end">${formatCurrency(subTotalResepUmum)}</td><td></td></tr>`;
                    bodyResepUmum.append(rowTotalObatUmum);


                }

            })
        }

        function hapusObatDokter(no_resep, kode_brng) {
            Swal.fire({
                title: "Yakin hapus obat ini ?",
                html: "Anda tidak bisa mengembalikan obat ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteResepDokter(no_resep, kode_brng).done((response) => {
                        showToast('Berhasil hapus obat')
                        // alertSuccessAjax().then(() => {
                        // });
                        tulisPlan(no_resep)
                        setResepDokter(no_resep)
                    }).fail((request) => {
                        alertErrorAjax(request)
                    });
                }
            });
        }

        function tambahBarisObat(tabel) {
            let rowCount = tabel.find('tr').length
            const addRow = `<tr id="row${rowCount}">
                <td><select class="form-control" name="nm_obat[]" id="kdObat${rowCount}" data-id="${rowCount}" style="width:100%"></select></td>
                <td class="text-end harga${rowCount}"></td>
                <td>
                    <input type="hidden" name="rowNext" id="rowNext" value="${rowCount + 1}"/>
                    <input type="hidden" name="kode_brng[]" id="kdObat${rowCount}Val"/>
                    <input type="text" class="form-control" name="jumlah[]" id="jmlObat${rowCount}"/>
                </td>
                <td><input class="form-control form-control-sm" name="aturan_pakai[]" id="aturan${rowCount}"/></td>
                <td class="text-end subTotal${rowCount}"></td>
                <td>
                    <i class="ti ti-device-floppy text-success" style="font-size:20px" data-id="row${rowCount}" onclick="createResepDokter()"></i>
                    <i class="ti ti-square-rounded-x text-danger" style="font-size:20px" data-id="row${rowCount}" onclick="hapusBarisObat('${rowCount}')"></i>
                </td>
            </tr>`;
            // tambah baris diatas row subTotal
            const rowTotalObatUmum = bodyResepUmum.find('#rowTotalObatUmum')
            tabel.append(addRow);
            bodyResepUmum.detach(rowTotalObatUmum).append(rowTotalObatUmum);

            const idElement = $(`#kdObat${rowCount}`);
            selectDataBarang(idElement, $('#modalCppt')).on('select2:select', (e) => {
                e.preventDefault();
                const kodeBarang = e.params.data.id;
                const targetId = e.currentTarget.id;
                const elementTargetId = $(`#${targetId}Val`)
                const subTotalObat = e.params.data.detail.ralan * 1
                $(`.harga${rowCount}`).text(formatCurrency(e.params.data.detail.ralan))
                $(`.subTotal${rowCount}`).text(formatCurrency(subTotalObat))
                $(`#jmlObat${rowCount}`).val(1);
                elementTargetId.val(kodeBarang)
            })

            $(`#jmlObat${rowCount}`).on('input', (e) => {
                const subTotal = $(`.harga${rowCount}`).text().replace(/[^\d]/g, '') * e.target.value
                $(`.subTotal${rowCount}`).text(formatCurrency(subTotal))
            })

        }

        function editObatDokter(id, kd_obat) {
            const row = bodyResepUmum.find(`#row${id}`)

            const colObat = row.find(`#obatUmum${id}`)
            const colJml = row.find(`#jmlUmum${id}`)
            const colAturan = row.find(`#aturanUmum${id}`)
            const colAksi = row.find(`#aksi${id}`)
            const colNoResep = row.find(`#noResep${id}`)

            const jml = colJml.html().split(" ")[0];
            const aturan = colAturan.html();
            colJml.empty().html(`<input type="hidden" name="kode_brng" id="kdObat${id}Val" value="${kd_obat}"/><input type="text" class="form-control" name="jml" id="jmlObat${id}" value="${jml}"/>`)
            colAturan.empty().html(`<input type="text" class="form-control" name="aturan" id="aturan${id}" value="${aturan}"/>`)
            colAksi.empty().append(`
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="simpanUbah(${id}, '${kd_obat}')" title="Simpan Perubahan">
                    <i class="ti ti-pencil"></i>
                </button>
                <button type="button" class="ms-1 btn btn-sm btn-outline-danger" onclick="hapusObatDokter(${colNoResep.val()}, '${kd_obat}')" title="Hapus Obat">
                    <i class="ti ti-trash-x"></i>
                </button>
            `)
            $(`#jmlObat${id}`).on('input', (e) => {
                const harga = $(`#harga${id}`).text().replace(/[^\d]/g, '')
                const subTotal = harga * e.target.value
                $(`#subTotal${id}`).text(formatCurrency(subTotal))
            })
        }

        function simpanUbah(id, kd_obat) {
            const row = bodyResepUmum.find(`#row${id}`)

            const data = {
                kode_brng: kd_obat,
                no_resep: $('#no_resep').val(),
                jml: $(`#jmlObat${id}`).val(),
                aturan_pakai: $(`#aturan${id}`).val()
            }

            $.post('resep/dokter/update', data).done((response) => {
                setResepDokter(data.no_resep)
                tulisPlan(data.no_resep)
            }).fail((request) => {
                alertErrorAjax(request)
            })


        }

        function tulisPlan(no_resep) {
            getResep({
                no_resep: no_resep
            }).done((response) => {
                let textPlan = `RESEP : \n`
                if (response.resep_dokter.length) {
                    response.resep_dokter.map((rd) => {

                        textPlan += `${rd.obat.nama_brng} : ${rd.jml} ${rd.obat.satuan?.satuan} aturan ${rd.aturan_pakai};\n`
                    })
                }
                if (response.resep_racikan.length) {
                    response.resep_racikan.map((rr) => {
                        textPlan += `${rr.no_racik}. ${rr.nama_racik} : ${rr.jml_dr} ${rr.metode.nm_racik} aturan ${rr.aturan_pakai} \n`
                        if (rr.detail.length) {
                            rr.detail.map((detail) => {
                                textPlan += `---${detail.obat.nama_brng} : dosis ${detail.kandungan} mg ;\n`
                            })
                        }
                    })
                }

                $('#formCpptRajal textarea[name=rtl]').val(textPlan)
            })
        }

        function createResepDokter() {
            const rowCount = $('#tabelResepUmum').find('tr').length
            const noResep = $(`#no_resep`).val();
            let dataObat = [];
            for (let index = 1; index < rowCount; index++) {
                const findInput = $(`#row${index}`).find('input');
                if (findInput.length) {
                    const kodeBrng = $(`#kdObat${index}Val`).val();
                    const jml = $(`#jmlObat${index}`).val();
                    const aturanPakai = $(`#aturan${index}`).val();
                    const obat = {
                        'no_resep': noResep,
                        'kode_brng': $(`#kdObat${index}Val`).val(),
                        'jml': $(`#jmlObat${index}`).val(),
                        'aturan_pakai': $(`#aturan${index}`).val(),
                    }

                    const isEmpty = Object.values(obat).filter((item) => {
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
                    dataObat.push(obat)
                }
            }

            $.post(`/efktp/resep/dokter/create`, {
                dataObat
            }).done((response) => {
                const no_rawat = $('#formCpptRajal input[name=no_rawat]').val()
                $('#btnCetakResep').attr('onclick', `cetakResep('${no_rawat}')`)
                tulisPlan(noResep)
                setResepDokter(noResep)
            })
        }

        $('#btnSimpanResep').on('click', (e) => {
            e.preventDefault();
            const rowCount = $('#tabelResepUmum').find('tr').length
            const noResep = $(`#no_resep`).val();
            let dataObat = [];
            for (let index = 1; index < rowCount; index++) {
                const findInput = $(`#row${index}`).find('input');
                if (findInput.length) {
                    const kodeBrng = $(`#kdObat${index}Val`).val();
                    const jml = $(`#jmlObat${index}`).val();
                    const aturanPakai = $(`#aturan${index}`).val();
                    const obat = {
                        'no_resep': noResep,
                        'kode_brng': $(`#kdObat${index}Val`).val(),
                        'jml': $(`#jmlObat${index}`).val(),
                        'aturan_pakai': $(`#aturan${index}`).val(),
                    }

                    const isEmpty = Object.values(obat).filter((item) => {
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
                    dataObat.push(obat)
                }
            }

            $.post(`/efktp/resep/dokter/create`, {
                dataObat
            }).done((response) => {
                const no_rawat = $('#formCpptRajal input[name=no_rawat]').val()
                $('#btnCetakResep').attr('onclick', `cetakResep('${no_rawat}')`)
                tulisPlan(noResep)
                setResepDokter(noResep)
            })
        })

        function hapusBarisObat(id) {
            const nextId = parseInt(id) + parseInt(1);
            $('#row' + nextId).attr('id', `row${id}`).find('i').attr('onclick', `hapusBarisObat(${id})`);
            $('#row' + id).remove();
        }

        $('#btnTambahObat').on('click', () => {
            tambahBarisObat(tabelResepUmum);
        })
    </script>
@endpush

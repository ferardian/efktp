<div class="modal modal-blur fade" id="modalDetailRacikan" tabindex="-1" aria-modal="false" role="dialog"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Detail Racikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="">
                <div class="row form-fieldset">
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12">
                        <label for="no_resep">Nomor Resep</label>
                        <input type="hidden" name="no_racik" readonly/>
                        <input type="text" class="form-control" name="no_resep" readonly/>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12">
                        <label for="nm_racik">Nama Racik</label>
                        <input type="text" class="form-control" name="nama_racik"/>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12">
                        <label for="nm_racik">Metode</label>
                        <select class="form-select" name="metode"></select>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12">
                        <label for="jml_dr">Jumlah Racikan</label>
                        <input type="text" class="form-control" name="jml_dr" readonly/>
                    </div>
                    <div class="col-xl-4 col-lg-2 col-md-4 col-sm-12">
                        <label for="aturan_pakai">Aturan Pakai</label>
                        <input type="text" class="form-control" name="aturan_pakai"/>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <input type="hidden" id="nextIdDetail">
                    <table class="table table-bordered table-sm" id="tabelObatRacikan">
                        <thead>
                        <tr class="text-center">
                            <th width="20%">Nama Obat</th>
                            <th>Sediaan</th>
                            <th width="15%">P1/P2</th>
                            <th>Dosis</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>SubTotal</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-primary" id="btnTambahObatRacikan"><i
                            class="ti ti-plus"></i> Tambah Obat
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="simpanDetailRacikan()"><i
                            class="ti ti-device-floppy"></i> Simpan Resep
                </button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var tabelObatRacikan = $('#tabelObatRacikan')
        var bodyObatRacikan = tabelObatRacikan.find('tbody');

        $('#modalDetailRacikan').on('hidden.modal.bs', () => {
            bodyObatRacikan.empty()
        })

        $('#btnTambahObatRacikan').on('click', (e) => {
            const nextIdDetail = $('#nextIdDetail').val()
            const rowCount = nextIdDetail ? parseInt(nextIdDetail) + 1 : tabelObatRacikan.find('tr').length

            const row = `<tr id="rowDetailRacikan${rowCount}" class="rowDetailRacikan">
                    <td><select class="form-control" id="obat${rowCount}" name="kd_obat[]" style="width:100%"></select></td>
                    <td><input class="form-control" id="kapasitas${rowCount}" data-id="${rowCount}" name="kapasitas[]" ></td>
                    <td>
                        <div class="input-group input-group-flat">
                            <input class="form-control" id="p1${rowCount}" data-id="${rowCount}" name="p1[]" value="1" oninput="hitungPembagi(${rowCount})">
                            <span class="input-group-text">/</span>
                            <input class="form-control" id="p2${rowCount}" data-id="${rowCount}" name="p2[]" value="1" oninput="hitungPembagi(${rowCount})"></td>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-flat">
                            <input class="form-control" id="dosis${rowCount}" data-id="${rowCount}" data-dosis="" name="dosis[]" onchange="hitungDosis(${rowCount})">
                            <span class="input-group-text">
                                mg
                            </span>
                        </div>
                    </td>
                    <td>
                        <input class="form-control text-end" id="hargaObatRacikan${rowCount}" data-id="${rowCount}" data-harga-obat="" name="harga[]" readonly>
                    </td>
                    <td><input class="form-control" id="jml${rowCount}" data-id="${rowCount}" name="jml[]" data-jml="" oninput="hitungDosisByJumlah(${rowCount}, this)"></td>
                    <td>  
                        <input class="form-control text-end" id="subTotalRacikan${rowCount}" data-id="${rowCount}" data-subTotalRacikan="" name="subTotalRacikan[]" readonly>
                    </td>
                    <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisDetailObat(${rowCount})"><i class="ti ti-trash-x"></i> Hapus</button></td>
                </tr>`;

            bodyObatRacikan.append(row)


            $('#nextIdDetail').val(rowCount)
            const selectObat = $(`#obat${rowCount}`);
            selectDataBarang(selectObat, $('#modalDetailRacikan')).on('select2:select', (e) => {
                e.preventDefault();
                const jml_dr = $('#modalDetailRacikan').find('input[name=jml_dr]').val()
                const data = e.params.data.detail
                $(`#kapasitas${rowCount}`).val(data.kapasitas)
                $(`#dosis${rowCount}`).val(formatFloat(data.kapasitas)).data('dosis', data.kapasitas)
                $(`#jml${rowCount}`).val(formatFloat(jml_dr)).data('jml', jml_dr).trigger('change')

                $(`#hargaObatRacikan${rowCount}`).val(formatCurrency(data.ralan)).attr('data-harga-obat', data.ralan)
            })
            const rowTotalDetailRacikan = $('#rowTotalDetailRacikan')
            bodyObatRacikan.detach(rowTotalDetailRacikan).append(rowTotalDetailRacikan)
        })

        function hitungSubTotalObatRacikan(index) {
            const harga = $(`#hargaObatRacikan${index}`).attr('data-harga-obat')
            const jml = $(`#jml${index}`).val().replace(',', '.');

            const subTotal = parseFloat(harga) * parseFloat(jml).toFixed(1);


            $(`#subTotalRacikan${index}`).val(formatCurrency(subTotal))
                .attr('data-subTotalRacikan', subTotal);
            hitungTotalRacikan()
        }

        function hitungDosis(id) {

            const jml_dr = $('#modalDetailRacikan').find('input[name=jml_dr]').val();
            const kapasitas = $(`#kapasitas${id}`).val();
            const dosis = $(`#dosis${id}`).val().replace(',', '.');
            // $(`#dosis${id}`).val(dosis).data('dosis', dataDosis);

            const p1 = $(`#p1${id}`).val();
            const p2 = $(`#p2${id}`).val();
            if (parseInt(dosis) > parseInt(kapasitas)) {
                Swal.fire(
                    'Ada yang salah !',
                    'Dosis tidak boleh lebih besar dari kapasitas obat',
                    'warning'
                ).then(() => {
                    $(`#dosis${id}`).val(0);
                });
            } else {
                const jml_obat = (parseFloat(dosis) * parseFloat(jml_dr)) / parseFloat(kapasitas)

                $(`#p1${id}`).val(1);
                $(`#p2${id}`).val(1);
                $(`#jml${id}`).val(formatFloat(jml_obat)).data('jml', jml_obat);
            }
            hitungSubTotalObatRacikan(id)
            setTotalRacikan()


        }

        function hitungDosisByJumlah(id, element) {
            const jml_dr = $('#modalDetailRacikan').find('input[name=jml_dr]').val().replace(',', '.');
            const kapasitas = $(`#kapasitas${id}`).val().replace(',', '.');
            let jml = $(`#jml${id}`).val().replace(',', '.'); // ambil dari value textbox

            if (parseFloat(jml) > parseFloat(jml_dr)) {
                Swal.fire(
                    'Ada yang salah !',
                    'Jumlah obat tidak boleh lebih besar dari jumlah racikan',
                    'warning'
                ).then(() => {
                    $(`#jml${id}`).val(jml_dr);

                });
            } else {
                const dosis = (parseFloat(kapasitas) * parseFloat(jml)) / parseFloat(jml_dr);

                $(`#dosis${id}`).val(formatFloat(dosis)).data('dosis', dosis);


                // simpan kembali dengan koma (agar tetap nyaman dibaca user)
                $(`#jml${id}`).val(element.value);
                $(`#jml${id}`).data('jml', jml);

                $(`#p1${id}`).val(1);
                $(`#p2${id}`).val(1);
            }
            hitungSubTotalObatRacikan(id);
            setTotalRacikan();
        }

        function hitungPembagi(id) {

            const jml_dr = $('#modalDetailRacikan').find('input[name=jml_dr]').val();
            const kapasitas = $(`#kapasitas${id}`).val();
            const p1 = $(`#p1${id}`).val();
            const p2 = $(`#p2${id}`).val();

            if (p1 != 0 && p2 != 0) {
                const dosis = parseFloat(kapasitas) * (parseFloat(p1) / parseFloat(p2));
                const jml_obat = (parseFloat(dosis) * parseFloat(jml_dr)) / parseFloat(kapasitas)
                $(`#dosis${id}`).val(formatFloat(dosis)).data('dosis', dosis);
                $(`#jml${id}`).val(formatFloat(jml_obat)).data('jml', jml_obat);
            }
            hitungSubTotalObatRacikan(id)
            setTotalRacikan()

        }

        function setRacikanDetail(no_racik, no_resep) {
            getDetailRacikan(no_racik, no_resep).done((response) => {
                console.log('RESPONSE DETAIL RACIKAN ===', response);

                bodyObatRacikan.empty();
                if (response.length) {
                    const detail = response.map((item, index) => {

                        const indexNum = index + parseInt(1)
                        const row = `<tr id="rowDetailRacikan${indexNum}" class="rowDetailRacikan">
                                    <td>
                                        <select class="form-control" id="obat${indexNum}" name="kd_obat[]" style="width:100%">
                                            <option value="${item.kode_brng}">${item.obat.nama_brng}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" id="kapasitas${indexNum}" data-id="${indexNum}" data-kapasitas="${item.obat.kapasitas}" name="kapasitas[]" value="${formatFloat(item.obat.kapasitas)}">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-flat">
                                            <input class="form-control" id="p1${indexNum}" data-id="${indexNum}" name="p1[]" value="${item.p1}" onkeyup="hitungPembagi(${indexNum})">
                                            <span class="input-group-text">/</span>
                                            <input class="form-control" id="p2${indexNum}" data-id="${indexNum}" name="p2[]" value="${item.p2}" onkeyup="hitungPembagi(${indexNum})">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-flat">
                                            <input class="form-control" id="dosis${indexNum}" data-id="${indexNum}" name="dosis[]" data-dosis="${item.kandungan}" onkeyup="hitungDosis(${indexNum})" value="${formatFloat(item.kandungan)}">
                                            <span class="input-group-text">
                                                mg
                                            </span>
                                        </div>
                                    </td>
                                    <td><input class="form-control text-end" id="hargaObatRacikan${indexNum}" data-id="${indexNum}" data-harga-obat="${item.obat.ralan}" name="harga[]" readonly value="${formatCurrency(item.obat.ralan)}"></td>
                                    <td><input class="form-control" id="jml${indexNum}" data-id="${indexNum}" data-jml="${item.jml.toFixed(1)}" name="jml[]" value="${formatFloat(item.jml)}"></td>
                                    <td><input class="form-control text-end" id="subTotalRacikan${indexNum}" data-id="${indexNum}" data-subTotalRacikan="" name="subTotalRacikan[]" readonly></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusBarisDetailObat(${indexNum})"><i class="ti ti-trash-x"></i> Hapus</button></td>
                            </tr>
                            `
                        bodyObatRacikan.append(row)

                        hitungSubTotalObatRacikan(indexNum);
                        const selectObat = $(`#obat${indexNum}`)
                        const val = selectObat.val(`${item.kode_brng}`).trigger('change')
                        selectDataBarang(selectObat, $('#modalDetailRacikan')).on('select2:select', (e) => {
                            e.preventDefault();
                            const jml_dr = $('#modalDetailRacikan').find('input[name=jml_dr]').val()
                            const data = e.params.data.detail
                            $(`#kapasitas${index}`).val(data.kapasitas)
                            $(`#dosis${index}`).val(data.kapasitas)
                            $(`#jml${index}`).val(formatFloat(jml_dr)).data('jml', jml_dr).trigger('change')
                            $(`#hargaObatRacikan${index}`).val(formatCurrency(data.ralan)).attr('data-harga-obat', data.ralan)
                        })
                    })
                    $('#nextIdDetail').val(detail.length + 1)

                }
                const rowTotalDetailRacikan = `
                            <tr id="rowTotalDetailRacikan">
                                <td colspan="6" class="text-end"><strong>Total Racikan</strong></td>
                                <td class="text-end"><strong id="totalRacikan">${hitungTotalRacikan()}</strong></td>
                                <td></td>
                            </tr>
                        `
                bodyObatRacikan.append(rowTotalDetailRacikan)

            })
        }

        function hitungTotalRacikan() {
            let total = 0;
            tabelObatRacikan.find('tbody').find('tr[class=rowDetailRacikan]').each((i, row) => {
                const index = $(row).find('input[name="subTotalRacikan[]"]').data('id')
                const subTotal = $(`#subTotalRacikan${index}`).attr('data-subTotalRacikan')
                total += parseFloat(subTotal)
            })

            return formatCurrency(total);
        }

        function setTotalRacikan() {
            console.log('hitung total racikan', hitungTotalRacikan());

            $('#totalRacikan').text(hitungTotalRacikan())
        }

        function getDetailRacikan(no_racik, no_resep) {
            const getDetail = $.get(`{{ url('/resep/racikan/detail/get') }}`, {
                no_racik: no_racik,
                no_resep: no_resep,
            })
            return getDetail
        }

        function createDetailRacikan(no_resep, no_racik, aturan_pakai, data) {
            const detailRacikan = $.post(`{{ url('/resep/racikan/detail/create') }}`, {
                no_resep: no_resep,
                no_racik: no_racik,
                aturan_pakai: aturan_pakai,
                data: data,
            }).done(() => {
                editRacikan(no_racik, no_resep)
            })
            return detailRacikan
        }

        function deleteDetailRacikan(no_resep, no_racik, obat = '') {
            const hapus = $.post('resep/racikan/detail/delete', {
                no_resep: no_resep,
                no_racik: no_racik,
                obat: obat
            })
            return hapus;
        }

        function hapusBarisDetailObat(id) {
            const tabelObatRacikan = $('#tabelObatRacikan').find('tbody')
            const nextId = parseInt(id) + parseInt(1)
            const rowId = $(`#rowDetailRacikan${nextId}`).attr('id', `rowDetailRacikan${id}`);

            rowId.find('button').attr('onclick', `hapusBarisDetailObat('${id}')`);
            $(`#rowDetailRacikan${id}`).remove()
            const selectObat = rowId.find('select').attr('id', `obat${id}`)
        }

        function simpanDetailRacikan() {
            const jumlahRow = $('#nextIdDetail').val()
            const noResep = $('#modalDetailRacikan').find('input[name=no_resep]').val();
            const noRacik = $('#modalDetailRacikan').find('input[name=no_racik]').val();
            const nm_racik = $('#modalDetailRacikan').find('input[name=nama_racik]').val();
            const aturan_pakai = $('#modalDetailRacikan').find('input[name=aturan_pakai]').val();
            const kd_dokter = $('#formCpptRajal').find('input[name=nip]').val();
            let dataObat = [];
            for (let index = 0; index <= jumlahRow; index++) {
                const findRow = $(`#rowDetailRacikan${index}`)
                if (findRow.length) {
                    const kode_brng = $(`#obat${index}`).val();
                    const p1 = $(`#p1${index}`).val();
                    const p2 = $(`#p2${index}`).val();
                    const kandungan = $(`#dosis${index}`).val().replace(',', '.');
                    const jml = $(`#jml${index}`).val().replace(',', '.');
                    const obatRacikan = {
                        no_resep: noResep,
                        no_racik: noRacik,
                        kode_brng: kode_brng,
                        p1: p1,
                        p2: p2,
                        kandungan: kandungan,
                        jml: jml,
                    }
                    const isEmpty = Object.values(obatRacikan).filter((item) => {
                        return item == null || item == ''
                    }).length

                    if (isEmpty) {
                        const errorMsg = {
                            status: 422,
                            statusText: 'Pastikan tidak ada kolom yang kosong'
                        }
                        alertErrorAjax(errorMsg)
                        return false;
                    }

                    dataObat.push(obatRacikan)
                }

            }
            createDetailRacikan(noResep, noRacik, aturan_pakai, dataObat).done((response) => {
                // $('#modalDetailRacikan').modal('hide')
                setResepRacikan(noResep)
                tulisPlan(noResep)
                $.get(`{{ url('/resep/racikan/template/get') }}`, {
                    nm_racik: nm_racik
                }).done((response) => {
                    if (!Object.values(response).length) {
                        Swal.fire({
                            title: "Racikan belum ada template, ",
                            html: "Buatkan template untuk racikan ini ?",
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Iya, Buat",
                            cancelButtonText: "Tidak"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post(`{{ url('/resep/racikan/template/create') }}`, {
                                    kd_dokter: kd_dokter,
                                    nm_racik: nm_racik,
                                    obat: dataObat,
                                }).done((resTemplate) => {
                                    alertSuccessAjax('Template racikan dibuat')
                                })
                            }
                        });
                    }
                })
            })
        }
    </script>
@endpush

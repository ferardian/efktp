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
        var bodyResepUmum = $('#tabelResepUmum').find('tbody')

        function getResepDokter(no_resep) {
            const resepDokter = $.get(`{{ url('/resep/dokter/get') }}`, {
                no_resep: no_resep
            })
            return resepDokter;
        }

        function deleteResepDokter(no_resep, kode_brng) {
            const resepDokter = $.post(`{{ url('/resep/dokter/delete') }}`, {
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
                    btnSimpanResep.removeClass('d-none')
                    btnTambahObat.removeClass('d-none')
                    const rowObat = reseps.map((resepDokter, index) => {
                        const numb = parseInt(index) + 1
                        const subTotal = resepDokter.jml * resepDokter.obat.ralan
                        const namaObatDisplay = typeof formatNamaObatWithGolongan === 'function'
                            ? formatNamaObatWithGolongan(resepDokter.obat.nama_brng, resepDokter.obat.golongan, resepDokter.obat.kode_golongan)
                            : resepDokter.obat.nama_brng;
                        return `<tr id="row${numb}">
                            <td id="obatUmum${numb}">${namaObatDisplay}</td>
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

        let counterBarisObat = 0;

        function tambahBarisObat(tabel) {
            counterBarisObat++;
            const uid = 'new_' + Date.now() + '_' + counterBarisObat;
            const addRow = `<tr id="row_${uid}" class="row-input-obat" data-uid="${uid}">
                <td><select class="form-control select-nm-obat" name="nm_obat[]" id="kdObat_${uid}" style="width:100%"></select></td>
                <td class="text-end col-harga"></td>
                <td>
                    <input type="hidden" class="input-kd-obat" name="kode_brng[]" id="kdObatVal_${uid}"/>
                    <input type="number" step="any" min="0.01" class="form-control input-jml-obat text-end" name="jumlah[]" id="jmlObat_${uid}" value="1"/>
                </td>
                <td><select class="form-control form-control-sm select-aturan-pakai" name="aturan_pakai[]" id="aturan_${uid}" style="width:100%"></select></td>
                <td class="text-end col-subtotal"></td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <i class="ti ti-device-floppy text-success btn-simpan-baris" style="font-size:20px; cursor:pointer;" title="Simpan baris ini"></i>
                        <i class="ti ti-square-rounded-x text-danger btn-hapus-baris" style="font-size:20px; cursor:pointer;" title="Hapus baris ini"></i>
                    </div>
                </td>
            </tr>`;
            const newRow = $(addRow);
            const rowTotalObatUmum = bodyResepUmum.find('#rowTotalObatUmum');
            if (rowTotalObatUmum.length) {
                rowTotalObatUmum.before(newRow);
            } else {
                bodyResepUmum.append(newRow);
            }

            const modalActive = $('#modalCpptRanap').hasClass('show') ? $('#modalCpptRanap') : ($('#modalCppt').hasClass('show') ? $('#modalCppt') : $('body'));
            const selectObat = newRow.find('.select-nm-obat');
            const inputKdObat = newRow.find('.input-kd-obat');
            const colHarga = newRow.find('.col-harga');
            const inputJml = newRow.find('.input-jml-obat');
            const colSubtotal = newRow.find('.col-subtotal');
            const selectAturan = newRow.find('.select-aturan-pakai');

            // Inisialisasi Select2 pencarian obat
            selectDataBarang(selectObat, modalActive).on('select2:select', (e) => {
                const kodeBarang = e.params.data.id;
                const harga = e.params.data.detail ? (parseFloat(e.params.data.detail.ralan) || 0) : 0;
                inputKdObat.val(kodeBarang);
                colHarga.text(formatCurrency(harga));
                if (!inputJml.val() || parseFloat(inputJml.val()) <= 0) {
                    inputJml.val(1);
                }
                const qty = parseFloat(inputJml.val()) || 1;
                colSubtotal.text(formatCurrency(harga * qty));
            });

            // Inisialisasi Select2 aturan pakai
            selectAturan.select2({
                dropdownParent: modalActive,
                tags: true,
                ajax: {
                    url: `{{ url('/resep/dokter/aturan-pakai') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { keyword: params.term };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.aturan,
                                text: item.aturan
                            }))
                        };
                    },
                    cache: true
                },
                placeholder: 'Ketik Aturan Pakai'
            }).on('select2:select', function(e) {
                const aturan = e.params.data.id;
                if (aturan) {
                    $.post(`{{ url('/resep/dokter/aturan-pakai') }}`, {
                        _token: '{{ csrf_token() }}',
                        aturan: aturan
                    });
                }
            });

            // Update subtotal saat jumlah diubah
            inputJml.on('input', function() {
                const harga = parseFloat(colHarga.text().replace(/[^\d]/g, '')) || 0;
                const qty = parseFloat($(this).val()) || 0;
                colSubtotal.text(formatCurrency(harga * qty));
            });

            // Tombol hapus baris
            newRow.find('.btn-hapus-baris').on('click', function() {
                newRow.remove();
            });

            // Tombol simpan satu baris
            newRow.find('.btn-simpan-baris').on('click', function() {
                simpanSatuBarisObat(newRow);
            });
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
            colAturan.empty().html(`<select class="form-control form-control-sm" name="aturan" id="aturan${id}" style="width:100%"></select>`)
            const modalActive = $('#modalCpptRanap').hasClass('show') ? $('#modalCpptRanap') : ($('#modalCppt').hasClass('show') ? $('#modalCppt') : $('body'));
            const aturanSelect = $(`#aturan${id}`);
            aturanSelect.select2({
                dropdownParent: modalActive,
                tags: true,
                ajax: {
                    url: `{{ url('/resep/dokter/aturan-pakai') }}`,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { keyword: params.term };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.aturan,
                                text: item.aturan
                            }))
                        };
                    },
                    cache: true
                },
                placeholder: 'Ketik Aturan Pakai'
            }).on('select2:select', function(e) {
                const aturanValue = e.params.data.id;
                if (aturanValue) {
                    $.post(`{{ url('/resep/dokter/aturan-pakai') }}`, {
                        _token: '{{ csrf_token() }}',
                        aturan: aturanValue
                    });
                }
            });
            if (aturan) {
                aturanSelect.append(new Option(aturan, aturan, true, true)).trigger('change');
            }
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

            $.post(`{{ url('/resep/dokter/update') }}`, data).done((response) => {
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
                        const gol = typeof getGolonganObatBadge === 'function' ? getGolonganObatBadge(rd.obat?.golongan, rd.obat?.kode_golongan) : null;
                        const prefix = gol ? `[${gol.label}] ` : '';
                        textPlan += `${prefix}${rd.obat.nama_brng} : ${rd.jml} ${rd.obat.satuan?.satuan} aturan ${rd.aturan_pakai};\n`
                    })
                }
                if (response.resep_racikan.length) {
                    response.resep_racikan.map((rr) => {
                        textPlan += `${rr.no_racik}. ${rr.nama_racik} : ${rr.jml_dr} ${rr.metode.nm_racik} aturan ${rr.aturan_pakai} \n`
                        if (rr.detail.length) {
                            rr.detail.map((detail) => {
                                const gol = typeof getGolonganObatBadge === 'function' ? getGolonganObatBadge(detail.obat?.golongan, detail.obat?.kode_golongan) : null;
                                const prefix = gol ? `[${gol.label}] ` : '';
                                textPlan += `---${prefix}${detail.obat.nama_brng} : dosis ${detail.kandungan} mg ;\n`
                            })
                        }
                    })
                }

                const formActive = $('#modalCpptRanap').length && $('#modalCpptRanap').hasClass('show') ? $('#formCpptRanap') : $('#formCpptRajal');
                formActive.find('textarea[name=rtl]').val(textPlan)
            })
        }

        function simpanSatuBarisObat(row) {
            const noResep = $('input[name="no_resep"]').filter(function() { return $(this).val(); }).val() || $('#no_resep').val();
            if (!noResep) {
                return Swal.fire('Peringatan', 'Nomor resep belum dibuat. Silakan klik tombol "Buat Resep" terlebih dahulu.', 'warning');
            }

            const kodeBrng = row.find('.input-kd-obat').val();
            const namaObat = row.find('.select-nm-obat option:selected').text() || 'Obat';
            const jml = row.find('.input-jml-obat').val();
            const aturanPakai = row.find('.select-aturan-pakai').val();

            if (!kodeBrng || kodeBrng.trim() === '') {
                return Swal.fire('Data Belum Lengkap', 'Pilih obat dari daftar dropdown terlebih dahulu.', 'warning');
            }
            if (!jml || parseFloat(jml) <= 0) {
                return Swal.fire('Data Belum Lengkap', `Masukkan jumlah yang valid untuk obat ${namaObat}.`, 'warning');
            }
            if (!aturanPakai || aturanPakai.trim() === '') {
                return Swal.fire('Data Belum Lengkap', `Pilih atau ketik aturan pakai untuk obat ${namaObat}.`, 'warning');
            }

            const dataObat = [{
                no_resep: noResep,
                kode_brng: kodeBrng.trim(),
                jml: jml,
                aturan_pakai: aturanPakai.trim()
            }];

            const iconFloppy = row.find('.btn-simpan-baris');
            iconFloppy.removeClass('ti-device-floppy text-success').addClass('spinner-border spinner-border-sm text-primary');

            $.post(`{{ url('/resep/dokter/create') }}`, {
                dataObat
            }).done((response) => {
                showToast(`Berhasil menyimpan ${namaObat}`);
                const formActive = $('#modalCpptRanap').length && $('#modalCpptRanap').hasClass('show') ? $('#formCpptRanap') : $('#formCpptRajal');
                const no_rawat = formActive.find('input[name=no_rawat]').val();
                $('#btnCetakResep').attr('onclick', `cetakResep('${no_rawat}')`);
                tulisPlan(noResep);
                setResepDokter(noResep);
            }).fail((request) => {
                alertErrorAjax(request);
                iconFloppy.removeClass('spinner-border spinner-border-sm text-primary').addClass('ti-device-floppy text-success');
            });
        }

        function createResepDokter() {
            simpanSemuaResepDokter();
        }

        function simpanSemuaResepDokter() {
            const noResep = $('input[name="no_resep"]').filter(function() { return $(this).val(); }).val() || $('#no_resep').val();
            if (!noResep) {
                return Swal.fire('Peringatan', 'Nomor resep belum dibuat. Silakan klik tombol "Buat Resep" terlebih dahulu.', 'warning');
            }

            const inputRows = bodyResepUmum.find('tr.row-input-obat');
            if (inputRows.length === 0) {
                return Swal.fire('Informasi', 'Tidak ada baris obat baru yang perlu disimpan.', 'info');
            }

            let dataObat = [];
            let validasiError = null;

            inputRows.each(function(index) {
                const row = $(this);
                const barisKe = index + 1;
                const kodeBrng = row.find('.input-kd-obat').val();
                const namaObat = row.find('.select-nm-obat option:selected').text() || `Baris ke-${barisKe}`;
                const jml = row.find('.input-jml-obat').val();
                const aturanPakai = row.find('.select-aturan-pakai').val();

                if (!kodeBrng || kodeBrng.trim() === '') {
                    validasiError = `Obat pada baris ke-${barisKe} belum dipilih dari daftar obat. Pastikan Anda mengklik nama obat dari daftar dropdown.`;
                    return false;
                }

                if (!jml || parseFloat(jml) <= 0) {
                    validasiError = `Jumlah obat pada baris ke-${barisKe} (${namaObat}) belum diisi dengan benar.`;
                    return false;
                }

                if (!aturanPakai || aturanPakai.trim() === '') {
                    validasiError = `Aturan pakai pada baris ke-${barisKe} (${namaObat}) belum dipilih atau belum diisi.`;
                    return false;
                }

                dataObat.push({
                    no_resep: noResep,
                    kode_brng: kodeBrng.trim(),
                    jml: jml,
                    aturan_pakai: aturanPakai.trim()
                });
            });

            if (validasiError) {
                return Swal.fire({
                    title: 'Data Belum Lengkap',
                    html: `<div class="text-danger fw-semibold text-start p-2 bg-danger-lt rounded">${validasiError}</div>`,
                    icon: 'warning'
                });
            }

            const btnSimpan = $('#btnSimpanResep');
            btnSimpan.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/resep/dokter/create') }}`, {
                dataObat
            }).done((response) => {
                showToast('Berhasil menyimpan resep obat');
                const formActive = $('#modalCpptRanap').length && $('#modalCpptRanap').hasClass('show') ? $('#formCpptRanap') : $('#formCpptRajal');
                const no_rawat = formActive.find('input[name=no_rawat]').val();
                $('#btnCetakResep').attr('onclick', `cetakResep('${no_rawat}')`);
                tulisPlan(noResep);
                setResepDokter(noResep);
            }).fail((request) => {
                alertErrorAjax(request);
            }).always(() => {
                btnSimpan.prop('disabled', false).html('Simpan');
            });
        }

        $('#btnSimpanResep').on('click', (e) => {
            e.preventDefault();
            simpanSemuaResepDokter();
        });

        function hapusBarisObat(id) {
            $(`#row_${id}`).remove();
            $(`#row${id}`).remove();
            $(`#${id}`).remove();
        }

        $('#btnTambahObat').on('click', () => {
            tambahBarisObat(tabelResepUmum);
        });
    </script>
@endpush

<div class="modal modal-blur fade" id="modalPermintaanLab" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modalPermintaanLab modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permintaan Pemeriksaan Laboratorium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formPermintaanLab">
                    <fieldset class="form-fieldset">
                        <div class="row gy-2 mb-2">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <label for="noorder" class="form-label">No. Permintaan</label>
                                <input type="text" class="form-control" name="noorder" id="noorder" />
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <label for="no_rawat" class="form-label">No. Rawat</label>
                                <input type="text" class="form-control" name="no_rawat" id="no_rawat" readonly />
                                <input type="hidden" name="status" id="status" />
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <label for="pasien" class="form-label">Pasien</label>
                                <div class="input-group">
                                    <input type="input" class="form-control form-control-sm" id="no_rkm_medis" name="no_rkm_medis" readonly />
                                    <input type="input" class="form-control form-control-sm w-50" id="nm_pasien" name="nm_pasien" readonly />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <label for="pasien" class="form-label">Tgl. Lahir/Umur</label>
                                <input type="input" class="form-control form-control-sm" id="tgl_lahir" name="tgl_lahir" readonly />
                            </div>
                        </div>
                        <div class="row gy-2 mb-2">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <label for="dokter" class="form-label">Dokter</label>
                                <div class="input-group">
                                    <input type="input" class="form-control form-control-sm" id="kd_dokter" name="kd_dokter" readonly />
                                    <input type="input" class="form-control form-control-sm w-50" id="nm_dokter" name="nm_dokter" readonly />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <label for="poliklinik" class="form-label">Poliklinik</label>
                                <div class="input-group">
                                    <input type="input" class="form-control form-control-sm" id="kd_poli" name="kd_poli" readonly />
                                    <input type="input" class="form-control form-control-sm w-50" id="nm_poli" name="nm_poli" readonly />
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-3 col-sm-12">
                                <label for="status" class="form-label">Status</label>
                                <input type="input" class="form-control form-control-sm" id="status" name="status" readonly />
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <label for="diagnosa" class="form-label">Diagnosa</label>
                                <input type="input" class="form-control form-control-sm" id="diagnosa" name="diagnosa" readonly />
                            </div>
                        </div>
                        <div class="row gy-2 mb-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="diagnosa_klinis" class="form-label">Indikasi/Klinis</label>
                                <input type="text" class="form-control" name="diagnosa_klinis" id="diagnosa_klinis" value="-" onfocus="removeZero(this)" onblur="isEmpty(this)" />
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="informasi_tambahan" class="form-label">Informasi Tambahan</label>
                                <input type="text" class="form-control" name="informasi_tambahan" id="informasi_tambahan" value="-" onfocus="removeZero(this)" onblur="isEmpty(this)" />
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label for="pemeriksaan" class="form-label">Pemeriksaan Lab</label>
                                <select name="pemeriksaan" id="pemeriksaan" class="form-select" multiple data-dropdown-parent="#formPermintaanLab" style="width:100%"></select>
                            </div>
                        </div>
                    </fieldset>
                    <table class="table table-responsive table-bordered" id="tablePermintaanLab">
                        <thead>
                            <tr class="table-secondary">
                                <th width="2%"><input type="checkbox" name="chekcItemLaborat" id="chekcItemLaborat" /></th>
                                <th>Pemeriksaan</th>
                                <th>Satuan</th>
                                <th>Nilai Rujukan</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <button type="button" class="btn btn-primary" id="btndataDetailPermintaan"> <i class="ti ti-eye me-2"></i>History Permintaan</button>
                    <button type="button" class="btn btn-success" id="btnKirimPermintaan" onclick="createPermintaanLab()"> <i class="ti ti-device-floppy me-2"></i>Kirim Permintaan</button>
                    <table class="table table-responsive table-bordered table-striped d-none mt-2 table-hover" id="tableHasilPermintaan">
                        <thead>
                            <tr class="table-secondary">
                                <th width="2%"></th>
                                <th>No. Order</th>
                                <th>Tanggal & Jam</th>
                                <th>Informasi Tambahan</th>
                                <th>Diagnosa Klinis</th>
                                <th>Tgl & Jam Sample</th>
                                <th>Tgl & Jam Hasil</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@include('content.laboratorium.modal._modalHasilPeriksaLab')

@push('script')
    <script>
        const formPermintaanLab = $('#formPermintaanLab')
        const formSoapPoli = $('#formSoapPoli')
        const selectJenisPeriksaLab = formPermintaanLab.find('#pemeriksaan')
        const tablePermintaanLab = $('#tablePermintaanLab');
        const tableHasilPermintaan = $('#tableHasilPermintaan');
        const modalPermintaanLab = $('#modalPermintaanLab')

        modalPermintaanLab.on('hidden.bs.modal', () => {
            const isVisibleHasil = tableHasilPermintaan.hasClass('d-none');
            tableHasilPermintaan.find('tbody').empty();
            if (!isVisibleHasil) {
                tableHasilPermintaan.addClass('d-none');
                formPermintaanLab.find('#btndataDetailPermintaan').removeClass('btn-danger').addClass('btn-primary').html(`<i class="ti ti-eye me-2"></i>History Permintaan`);
            }
        })

        function permintaanLab(no_rawat) {
            modalPermintaanLab.modal('show')
            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien,
                    dokter,
                    poliklinik,
                    diagnosa
                } = response;
                formPermintaanLab.find('#no_rawat').val(no_rawat);
                formPermintaanLab.find('#no_rkm_medis').val(response.no_rkm_medis);
                formPermintaanLab.find('#nm_pasien').val(`${pasien.nm_pasien} (${pasien.jk})`);
                formPermintaanLab.find('#tgl_lahir').val(`${formatTanggal(pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`);
                formPermintaanLab.find('#kd_dokter').val(response.kd_dokter)
                formPermintaanLab.find('#nm_dokter').val(dokter.nm_dokter)
                formPermintaanLab.find('#status').val(response.status_lanjut)
                formPermintaanLab.find('#kd_poli').val(response.kd_poli)
                formPermintaanLab.find('#nm_poli').val(poliklinik.nm_poli)
                formPermintaanLab.find('#btndataDetailPermintaan').attr('onclick', `getPermintaanLab('${no_rawat}')`)

                const diagnosaPasien = diagnosa.map((item) => {
                    return item.kd_penyakit
                }).join(';')

                formPermintaanLab.find('#diagnosa').val(diagnosaPasien)
            });
            getNomorPermintaan();
        }

        function getPermintaanLab(no_rawat) {
            const isVisible = tableHasilPermintaan.hasClass('d-none')
            if (isVisible) {
                $.get(`/efktp/lab/permintaan/get`, {
                    no_rawat: no_rawat
                }).done((response) => {
                    let contentPermintaan = '';
                    tableHasilPermintaan.find('tbody').empty();
                    if (Object.values(response).length) {
                        const permintaan = response.map((item, index) => {
                            return `<tr>
                            <td>${index+1}</td>
                            <td>${item.noorder} <a href="javascript:void(0)" onclick="deletePermintaanLab('${item.noorder}')" title="Hapus permintaan" class="text-red"><i class="ti ti-trash"></i></a> ${isGetHasilLab(item)}</td>
                            <td>${splitTanggal(item.tgl_permintaan)} ${item.jam_permintaan}</td>
                            <td>${item.informasi_tambahan}</td>
                            <td>${item.diagnosa_klinis}</td>
                            <td>${splitTanggal(item.tgl_sampel)} ${item.jam_sampel}</td>
                            <td>${splitTanggal(item.tgl_hasil)} ${item.jam_hasil}</td>
                            </tr>${getPermintaanPeriksa(item.pemeriksaan)}`
                        }).join('');
                        contentPermintaan = permintaan;
                    } else {
                        contentPermintaan = `<tr><td colspan=7 class="text-center text-danger"><strong>Tidak ada permintaan lab</strong></td></tr>`
                    }
                    formPermintaanLab.find('#btndataDetailPermintaan').removeClass('btn-primary').addClass('btn-danger').html(`<i class="ti ti-eye-off me-2"></i>Sembunyikan`)
                    tableHasilPermintaan.find('tbody').append(contentPermintaan)
                }).fail((error) => {
                    alertErrorAjax(error)
                })
            } else {
                formPermintaanLab.find('#btndataDetailPermintaan').removeClass('btn-danger').addClass('btn-primary').html(`<i class="ti ti-eye me-2"></i>History Permintaan`)
            }
            tableHasilPermintaan.toggleClass('d-none');
        }

        function isGetHasilLab(item) {

            if (item.tgl_hasil !== '0000-00-00') {
                return `<a href="javascript:void(0)" onclick="showHasilPermintaanLab('${item.no_rawat}', '${item.tgl_hasil}')" title="Lihat Hasil" class="text-success"><i class="ti ti-eye"></i></a>`
            }
            return '';
        }

        function getPermintaanPeriksa(data) {
            return data.map((item) => {
                return `<tr>
                        <td></td>
                        <td colspan=6><strong>${item.jenis.nm_perawatan}</strong> : ${getDetailPermintaan(item.detail)}</td>
                    </tr>`
            }).join('');
        }

        function getDetailPermintaan(data) {
            return data.filter((val, index) => {
                return val.item.nama.trim() !== '';
            }).map(val => {
                return `<span class="badge bg-primary">${val.item.nama}</span>`;
            }).join(' | ');
        }

        function getNomorPermintaan() {
            return $.get(`/efktp/lab/permintaan/noorder`).done((response) => {
                formPermintaanLab.find('#noorder').val(response)
            })
        }

        selectJenisPeriksaLab.select2({
            tags: false,
            ajax: {
                url: `/efktp/lab/jenis/get`,
                dataType: 'JSON',

                data: (params) => {
                    const query = {
                        nm_perawatan: params.term
                    }
                    return query
                },
                processResults: (data) => {
                    return {
                        results: data.map((item) => {
                            const items = {
                                id: item.kd_jenis_prw,
                                text: `${item.nm_perawatan}`,
                            }
                            return items;
                        })
                    }
                }

            },
        });

        selectJenisPeriksaLab.on('select2:select', (e) => {
            const data = selectJenisPeriksaLab.val();
            $.get(`/efktp/lab/jenis/template/get`, {
                kode: data
            }).done((response) => {
                let subPemeriksaan = '';
                let pemeriksaan = '';

                response.forEach((item) => {
                    pemeriksaan = response.map((item) => {

                        return `<tr>
                        <td><input type="checkbox" class="form-check checkJenisPemeriksaan" name="${item.kd_jenis_prw}" id="${item.kd_jenis_prw}" onclick="checkJenisPemeriksaan(this)"/></td>
                        <td colspan=3><b>${item.nm_perawatan}</b></td>
                        </tr>${setTemplatePemeriksaan(item)}`
                    });
                })
                tablePermintaanLab.find('tbody').empty().append(pemeriksaan).append(subPemeriksaan)
                if ($('#chekcItemLaborat').prop('checked')) {
                    $('input[type=checkbox]').each((index, e) => {
                        $(e).prop('checked', true);
                    })
                }
            })
        })

        selectJenisPeriksaLab.on('select2:unselect', (e) => {
            const data = selectJenisPeriksaLab.val();
            if (data.length) {
                $.get(`/efktp/lab/jenis/template/get`, {
                    kode: data
                }).done((response) => {
                    let subPemeriksaan = '';
                    let pemeriksaan = '';

                    response.forEach((item) => {
                        pemeriksaan = response.map((item) => {
                            return `<tr>
                            <td><input type="checkbox" class="form-check checkJenisPemeriksaan" name="${item.kd_jenis_prw}" id="${item.kd_jenis_prw}" /></td>
                            <td colspan=3><b>${item.nm_perawatan}</b></td>
                            </tr>${setTemplatePemeriksaan(item)}`
                        });
                    })

                    tablePermintaanLab.find('tbody').empty().append(pemeriksaan).append(subPemeriksaan)
                    if ($('#p').prop('checked')) {
                        $('input[type=checkbox]').each((index, e) => {
                            $(e).prop('checked', true);
                        })
                    }
                })
            } else {
                tablePermintaanLab.find('tbody').empty()
            }
        })


        function setTemplatePemeriksaan(data) {
            const {
                template
            } = data;
            return template.map((i) => {
                if (i.Pemeriksaan.length) {
                    return `<tr>
                    <td><input class="form-checkbox itemPemeriksaanLab" type="checkbox" name="${i.id_template}" id="${i.id_template}" data-parent="${i.kd_jenis_prw}" /></td>
                    <td><span class="ms-4">${i.Pemeriksaan}</span></td>
                    <td>${i.satuan}</td>
                    <td><b>LD</b> : ${i.nilai_rujukan_ld} ${i.satuan}, <b>LA</b> : ${i.nilai_rujukan_la} ${i.satuan}, <b>PD</b> : ${i.nilai_rujukan_pd} ${i.satuan}, <b>PA</b> : ${i.nilai_rujukan_pa} ${i.satuan} </td>
                </tr>`

                } else {
                    return `<tr>
                    <td><input class="form-checkbox itemPemeriksaanLab" type="checkbox" name="${i.id_template}" id="${i.id_template}" data-parent="${i.kd_jenis_prw}" /></td>
                    <td><span class="ms-4">${data.nm_perawatan}</span></td>
                    <td>${i.satuan}</td>
                    <td></td>
                </tr>`
                }
            })
        }

        $('#chekcItemLaborat').on('click', (e) => {
            const isCheck = $(e.currentTarget).prop('checked')
            tablePermintaanLab.find('input[type=checkbox]').each((index, el) => {
                if (isCheck) {
                    $(el).prop('checked', true)
                } else {
                    $(el).prop('checked', false)
                }
            })
        })

        function checkJenisPemeriksaan(el) {
            const isCheck = $(el).prop('checked');
            tablePermintaanLab.find('input[type=checkbox]').each((index, e) => {
                if (e.dataset.parent == el.id) {
                    if (isCheck) {
                        $(e).prop('checked', true)
                    } else {
                        $(e).prop('checked', false)
                    }
                }
            })
        }

        function createPermintaanLab() {
            const data = getDataForm('formPermintaanLab', ['input']);
            const dataDetailPermintaan = [];
            const dataPemeriksaan = [];
            $('.itemPemeriksaanLab').each((index, e) => {
                const element = $(e);
                const item = element.prop('checked')
                if (item) {
                    const noorder = data.noorder;
                    const id = element.attr('id');
                    const kd_jenis_prw = element.data('parent');
                    const stts_bayar = 'Belum';

                    const exists = dataPemeriksaan.find(entry =>
                        entry.kd_jenis_prw === kd_jenis_prw
                    );

                    if (!exists) {
                        dataPemeriksaan.push({
                            noorder: noorder,
                            kd_jenis_prw: kd_jenis_prw,
                            stts_bayar: stts_bayar
                        });
                    }

                    dataDetailPermintaan.push({
                        noorder: noorder,
                        id_template: id,
                        kd_jenis_prw: kd_jenis_prw,
                        stts_bayar: stts_bayar,
                    });
                }
            });

            $('.checkJenisPemeriksaan').each((index, e) => {
                const element = $(e);
                if (element.prop('checked')) {

                    const noorder = data.noorder;
                    const kd_jenis_prw = element.attr('id');
                    const stts_bayar = 'Belum';

                    const exists = dataPemeriksaan.find(entry =>
                        entry.kd_jenis_prw === kd_jenis_prw
                    );

                    if (!exists) {
                        dataPemeriksaan.push({
                            noorder: noorder,
                            kd_jenis_prw: kd_jenis_prw,
                            stts_bayar: stts_bayar
                        });
                    }
                }
            });


            if (dataDetailPermintaan.length) {
                $.post(`/efktp/lab/permintaan`, data).done((response) => {
                    dataPemeriksaan.forEach(item => {
                        item.noorder = response.data
                    });
                    dataDetailPermintaan.forEach(item => {
                        item.noorder = response.data
                    });
                    createPermintaanPemeriksaanLab(dataPemeriksaan).done(() => {
                        createDetailPermintaanLab(dataDetailPermintaan)
                    });

                }).fail((error) => {
                    alertErrorAjax(error)
                })
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: `Ooppss...`,
                    html: `Anda belum memilih item pemeriksaan, Pilih salah satu atau lebih item pemeriksaan`,
                })
            }
        }

        function createPermintaanPemeriksaanLab(data) {
            return $.post(`/efktp/lab/permintaan/pemeriksaan`, {
                data: data,
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function createDetailPermintaanLab(data) {
            return $.post(`/efktp/lab/permintaan/detail`, {
                data: data,
            }).done((response) => {
                toast('Permintaan lab dibuat');
                const no_rawat = formPermintaanLab.find('#no_rawat').val();
                getPermintaanLab(no_rawat);

                if (tableHasilPermintaan.hasClass('d-none')) {
                    getPermintaanLab(no_rawat)
                } else {
                    tableHasilPermintaan.addClass('d-none')
                    getPermintaanLab(no_rawat)
                }

                tablePermintaanLab.find('tbody').empty();
                tablePermintaanLab.find('input[type=checkbox]').prop('checked', false)
                formPermintaanLab.find('#informasi_tambahan').val('-')
                formPermintaanLab.find('#diagnosa_klinis').val('-')
                selectJenisPeriksaLab.val("").trigger('change');
                getNomorPermintaan();
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function deletePermintaanLab(noorder) {
            Swal.fire({
                title: "Yakin hapus data ini ?",
                html: "Data permintaan lab akan di hapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/lab/permintaan/delete/${noorder}`)
                        .done((response) => {
                            toast('Permintaan lab di hapus');
                            const no_rawat = formPermintaanLab.find('#no_rawat').val();
                            getPermintaanLab(no_rawat);

                            if (tableHasilPermintaan.hasClass('d-none')) {
                                getPermintaanLab(no_rawat)
                            } else {
                                tableHasilPermintaan.addClass('d-none')
                                getPermintaanLab(no_rawat)
                            }
                            getNomorPermintaan();
                        }).fail((error) => {
                            alertErrorAjax(error)
                        })
                }
            })
        }
    </script>
@endpush

<div class="modal modal-blur fade" id="modalPendaftaranPcare" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Pendaftaran PCARE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gy-2">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="input-group" id="filterIndex">
                            <span class="input-group-text">Index</span>
                            <input type="number" class="form-control" id="start" name="start" placeholder="Baris Awal">
                            <span class="input-group-text">Limit</span>
                            <input type="number" class="form-control" id="limit" name="limit" placeholder="Jumlah Baris">
                            <button type="button" class="btn btn-primary" id="btnFilterPendaftaran"><i class="ti ti-search"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 col-sm-12">
                        <div class="input-group" id="filterNoUrut">
                            <span class="input-group-text">No Urut</span>
                            <input type="text" class="form-control" id="noUrut" name="noUrut">
                            <button type="button" class="btn btn-primary" id="btnFilterUrutPendaftaran"><i class="ti ti-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tbPendaftaranPcare" class="table table-sm table-stripped" width="100%"></table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('content.pcare.pendaftaran._modalPasien')
@push('script')
    <script>
        var start = $('#filterIndex').find('input[name="start"]').val();
        var limit = $('#filterIndex').find('input[name="limit"]').val();

        function getNokaPasien(noKartu) {
            const pasien = $.get(`/efktp/pasien/get/nokartu/${noKartu}`)
            return pasien;
        }

        function createPasienBpjs(noKartu, noUrut = '') {
            loadingAjax('Mengambil data pasien BPJS');
            $.get(`/efktp/bridging/pcare/peserta/${noKartu}`).done((result) => {
                if (result.metaData.code === 200) {
                    $.get(`/efktp/pasien/get/nokartu/${noKartu}`).done((response) => {
                        if (!isObjectEmpty(response)) {
                            $('#modalPasien').modal('show')
                            $.get(`/efktp/setting/ppk`).done((kode) => {
                                loadingAjax().close();
                                const {
                                    kdProviderPst,
                                    kdProviderGigi
                                } = result.response
                                if (kode.toUpperCase() !== kdProviderPst.kdProvider || kode.toUpperCase !== kdProviderGigi.kdProvider)
                                    Swal.fire({
                                        title: "Peringatan ?",
                                        html: "Pasien tidak terdaftar sebagai peserta Anda, tetap lanjutkan ?",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        showConfirmButton: true,
                                        confirmButtonColor: "#3085d6",
                                        cancelButtonColor: "#d33",
                                        confirmButtonText: "Iya, Lanjutkan",
                                        cancelButtonText: "Tidak, Batalkan"
                                    }).then((res) => {
                                        if (res.isDenied) {
                                            $('#modalPasien').modal('hide')
                                            resetFormRegistrasi();
                                            return true;
                                        } else {
                                            toast('Peserta belum memiliki nomor rekam medis')
                                        }
                                    });
                            });
                            const umur = hitungUmur(splitTanggal(result.response.tglLahir));
                            const contentUmur = `${umur.split(';')[0]} Th ${umur.split(';')[1]} Bl ${umur.split(';')[2]} Hr`
                            formPasien.find('input[name=nm_pasien]').addClass('is-valid').val(result.response.nama)
                            formPasien.find('select[name=jk]').addClass('is-valid').val(result.response.sex).change()
                            formPasien.find('select[name=gol_darah]').addClass('is-valid').val('-')
                            formPasien.find('input[name=tgl_lahir]').addClass('is-valid').val(result.response.tglLahir)
                            formPasien.find('input[name=umur]').addClass('is-valid').val(contentUmur)
                            formPasien.find('input[name=no_ktp]').addClass('is-valid').val(result.response.noKTP)
                            formPasien.find('input[name=no_tlp]').addClass('is-valid').val(result.response.noHP)
                            formPasien.find('input[name=no_peserta]').addClass('is-valid').val(noKartu)
                            formPasien.find('input[name=sttsForm]').addClass('is-valid').val('bridging')
                            formPasien.find('input[name=noUrut]').addClass('is-valid').val(noUrut)
                            formPasien.find('input[name=no_peserta]').addClass('is-valid').val(noKartu)

                            $.get(`/efktp/penjab`, {
                                nama: 'BPJS',
                            }).done((response) => {
                                const bpjs = new Option(`${response.kd_pj} - ${response.png_jawab}`, `${response.kd_pj}`, true, true);
                                formPasien.find('select[name=kd_pj]').append(bpjs).trigger('change');
                            })
                            loadingAjax().close();
                        } else {
                            formRegistrasiPoli.find('input[name=bridging]').val(true)
                        }
                    })

                } else {
                    alertErrorBpjs(result);
                }

            }).fail((error) => {
                alertErrorBpjs(erorr);
            })
        }


        function renderPendaftaranPcare(start = '', length = '') {
            var startInput = $('#filterIndex').find('input[name="start"]').val();
            var limitInput = $('#filterIndex').find('input[name="limit"]').val();

            var customStart = start ? start - 1 : 0;
            var customLength = length ? length : 15;

            $('#tbPendaftaranPcare').empty();
            const tbReferensi = new DataTable('#tbPendaftaranPcare', {
                autoWidth: true,
                serverSide: true,
                destroy: true,
                processing: true,
                searching: false,
                lengthChange: false,
                deferRender: true,
                responsive: true,
                scroller: true,
                scrollCollapse: true,
                scrollY: 600,
                colReorder: true,
                "preDrawCallback": function(settings) {
                    // Modify the custom start value before drawing the table
                    customStart = startInput ? startInput : settings._iDisplayStart;
                },
                "drawCallback": function(settings) {
                    var maxCustomLength = limitInput ? limitInput : 15;
                    if (settings.json.metaData.code == 500) {
                        const error = {
                            status: settings.json.metaData.code,
                            statusText: '',
                            responseJSON: settings.json.metaData.message
                        }
                        alertErrorAjax(error);
                        return false;
                    }
                    var totalCount = settings.json.response?.count;
                    customLength = Math.min(maxCustomLength, Math.ceil(totalCount / maxCustomLength) * maxCustomLength);

                },
                ajax: {
                    url: `/efktp/bridging/pcare/pendaftaran`,
                    dataSrc: 'response.list',
                    data: (q) => {
                        q.start = customStart;
                        q.length = customLength;
                        return q;
                    },
                },
                createdRow: (row, data, index) => {
                    const noKartu = data.peserta.noKartu;
                    getNokaPasien(noKartu).done((response) => {
                        if (response.no_peserta) {
                            if (response.reg_periksa.length) {
                                $(row).addClass('bg-green-lt').css('cursor', 'pointer')
                                return false;
                            }
                            $(row).addClass('bg-yellow-lt').css('cursor', 'pointer')
                            $(row).attr('onclick', `registrasiPoli('${response.no_rkm_medis}', '${data.noUrut}')`)
                        } else {
                            $(row).attr('onclick', `createPasienBpjs('${noKartu}', '${data.noUrut}')`)
                            $(row).addClass('bg-red-lt').css('cursor', 'pointer')
                        }
                        $(row).find(`#btnPeserta${noKartu}`).removeClass('d-none')
                    })
                },
                columns: [{
                        title: 'No Urut',
                        data: 'noUrut',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'No. Kartu',
                        data: 'peserta.noKartu',
                        render: (data, type, row, meta) => {
                            return `<span id='${data}'>${data}</span>`;
                        }
                    },
                    {
                        title: 'Nama',
                        data: 'peserta.nama',
                        render: (data, type, row, meta) => {
                            return `${data} (${row.peserta.sex})`;
                        }
                    },
                    {
                        title: 'Umur',
                        data: 'peserta.tglLahir',
                        render: (data, type, row, meta) => {
                            return `${hitungUmur(splitTanggal(data)).split(';')[0]} Th`;
                        }
                    },
                    {
                        title: 'Tgl Lahir',
                        data: 'peserta.tglLahir',
                        render: (data, type, row, meta) => {
                            return `${data} (${row.peserta.sex})`;
                        }
                    },
                    {
                        title: 'Poli',
                        data: 'poli.nmPoli',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: '',
                        data: 'peserta.noKartu',
                        render: (data, type, row, meta) => {
                            return `<button type="button" class="btn btn-sm btn-success d-none" onclick="createPasienBpjs('${data}')" id="btnPeserta${data}"><i class="ti ti-http-get"></i></button>`;
                        }
                    },
                ],
                infoCallback: (settings, start) => {
                    return `Total menampilkan ${customLength} pendaftaran ${settings.json?.response?.count} pasien`;
                }
            })
        }
        $('#btnFilterPendaftaran').on('click', () => {
            const start = $('#filterIndex').find('input[name="start"]').val();
            const limit = $('#filterIndex').find('input[name="limit"]').val();
            renderPendaftaranPcare(start, limit);
        })

        $('#btnFilterUrutPendaftaran').on('click', () => {
            const noUrut = $('#filterNoUrut').find('input[name="noUrut"]').val();
            const tbPendaftaranPcare = $('#tbPendaftaranPcare')
            if (noUrut) {
                $.get(`/efktp/bridging/pcare/pendaftaran/nourut/${noUrut}`).done((response) => {
                    if (response.response) {
                        data = response.response;
                        tbPendaftaranPcare.empty();
                        const html = `<tbody width="100%"><tr id="${data.peserta.noKartu}">
                                <td width="11%">${data.noUrut}</td>
                                <td width="16%">${data.peserta.noKartu}</td>
                                <td width="32%">${data.peserta.nama}</td>
                                <td width="9%">${hitungUmur(splitTanggal(data.peserta.tglLahir)).split(';')[0]} Th</td>
                                <td width="14%">${data.peserta.tglLahir}</td>
                                <td width="12%">${data.poli.nmPoli}</td>
                                <td width=""><button type="button" class="btn btn-sm btn-success d-none" onclick="createPasienBpjs('${data.peserta.noKartu}')" id="btnPeserta${data.peserta.noKartu}"><i class="ti ti-http-get"></i></button></td>
                            </tr></tbody>`;
                        tbPendaftaranPcare.append(html)

                        getNokaPasien(data.peserta.noKartu).done((result) => {
                            const noKartu = data.peserta.noKartu;
                            if (Object.keys(result).length) {
                                if (result.reg_periksa.length) {
                                    $(`#${noKartu}`).addClass('bg-green-lt').css('cursor', 'pointer')
                                    return false;
                                }
                                $(`#${noKartu}`).addClass('bg-yellow-lt').css('cursor', 'pointer')
                                $(`#${noKartu}`).attr('onclick', `registrasiPoli('${result.no_rkm_medis}', '${data.noUrut}')`)
                            } else {
                                $(`#${noKartu}`).attr('onclick', `createPasienBpjs('${noKartu}', '${data.noUrut}')`)
                                $(`#${noKartu}`).addClass('bg-red-lt').css('cursor', 'pointer')
                            }
                            $(`#${noKartu}`).find(`#btnPeserta${noKartu}`).removeClass('d-none')
                        })

                    } else {
                        const error = {
                            status: response.metaData.code,
                            statusText: response.metaData.message,
                            responseJSON: 'Nomor pendaftaran tidak ditemukan',
                        }
                        alertErrorAjax(error);
                    }
                })
            }
        })
    </script>
@endpush

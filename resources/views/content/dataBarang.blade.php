@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div id="table-default" class="table-responsive">
                    <table class="table table-striped table-hover nowrap" id="tabelBarangObat" width="100%">
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row d-none-sm d-none-md">
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="input-group">
                            <input class="form-control filterTangal" placeholder="Select a date" id="tglAwal"
                                   name="tglAwal" value="{{ date('d-m-Y') }}">
                            <span class="input-group-text">s.d</span>
                            <input class="form-control filterTangal" placeholder="Select a date" id="tglAkhir"
                                   name="tglAkhir" value="{{ date('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <select class="form-select form-select-2" style="width: 100%" id="selectPulang">
                            <option value="Semua" selected>Semua</option>
                            <option value="Belum Pulang">Belum Pulang</option>
                            <option value="Pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <button class="btn btn-primary" type="submit" id="btnFilterRanap"><i
                                    class="ti ti-search me-2"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@include('content.farmasi.obat._mappingObatPcare')
@push('script')
    <script>
        const tabelBarangObat = $('#tabelBarangObat')

        $(document).ready(() => {
            renderTabelBarang();
        })

        function renderTabelBarang() {
            tabelBarangObat.DataTable({
                processing: true,
                serverSide: true,
                scrollY: setTableHeight(),
                scrollX: true,
                ajax: {
                    url: `/efktp/barang/get`,
                    type: 'get',
                    data: {
                        dataTable: true,
                    }
                },
                columns: [{
                    data: 'kode_brng',
                    name: 'kode_brng',
                    title: 'Kode',
                    render: (data, type, row, meta) => {
                        return data;

                    }

                },
                    {
                        data: 'nama_brng',
                        name: 'nama_brng',
                        title: 'Nama Obat/Barang',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'kapasitas',
                        name: 'kapasitas',
                        title: 'Dosis',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'satuan.satuan',
                        name: 'satuan.satuan',
                        title: 'Satuan',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'letak_barang',
                        name: 'letak_barang',
                        title: 'Kandungan',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'jenis.nama',
                        name: 'jenis.nama',
                        title: 'Jenis',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'kategori.nama',
                        name: 'kategori.nama',
                        title: 'Kategori',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'golongan.nama',
                        name: 'golongan.nama',
                        title: 'Golongan',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        data: 'industri.nama_industri',
                        name: 'industri.nama_industri',
                        title: 'Industri',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },

                    {
                        data: 'mapping.nama_brng_pcare',
                        name: 'mapping.nama_brng_pcare',
                        title: 'Mapping',
                        width: '15%',
                        render: (data, type, row, meta) => {
                            const mappingObatPcareElementId = `mappingObatPcare${row.kode_brng}`;
                            const btnObatElementId = `btnObat${row.kode_brng}`;
                            const keyword = data ? row.mapping.nama_brng_pcare.split('/')[0] : row.nama_brng.substring(0, 5);

                            const labelMapping = data ?
                                `<div id="${btnObatElementId}"><span class="me-2">${row.mapping.nama_brng_pcare}</span>
                                    <a href="javascript:void(0)" class="text-primary" onclick="setMappingObatPcare('${row.kode_brng}', '${keyword}')"><i class="ti ti-pencil"></i></a>
                                    <a href="javascript:void(0)" class="text-danger" onclick="deleteObatPcareMapping('${row.kode_brng}')"><i class="ti ti-x"></i></a>
                                </div>` :
                                `<button type="button" class="btn btn-sm btn-warning" id="${btnObatElementId}" onclick="setMappingObatPcare('${row.kode_brng}', '${keyword}')"><i class="ti ti-search me-2"></i> Cari Referensi</button>`;

                            return `
                                <div id="labelMapping${row.kode_brng}">
                                    ${labelMapping}
                                </div>
                                <div class="input-group d-none" id="${mappingObatPcareElementId}">
                                    <select class="form-select form-select-2" id="selectMappingObatPcare${row.kode_brng}" style="width: 80%;" data-dropdown-parent="body"></select>
                                    <button class="btn btn-primary btn-sm" type="button" id="btnCariObat${row.kode_brng}" onclick="createObatPcareMapping('${row.kode_brng}')"><i class="ti ti-device-floppy"></i></button>
                                    <button class="btn btn-danger btn-sm" type="button" id="btnCancelObat${row.kode_brng}" onclick="cancelObatPcareMapping('${row.kode_brng}')"><i class="ti ti-x"></i></button>
                                </div>
                            `;
                        }
                    },
                ],
                drawCallback: function () {
                    // Initialize Select2 on newly created select elements
                    $('.form-select-2').select2({
                        width: 'resolve', // You can adjust this option based on your requirements
                    });
                }
            })
        }


        function setMappingObatPcare(kode_brng, keyword) {
            const select = $(`#selectMappingObatPcare${kode_brng}`);
            const inputMapping = $(`#mappingObatPcare${kode_brng}`);
            const btnObat = $(`#btnObat${kode_brng}`);

            if (inputMapping.hasClass('d-none')) {
                inputMapping.removeClass('d-none');
                btnObat.addClass('d-none');

                $.get(`/efktp/bridging/pcare/obat/${keyword}`).done((data) => {
                    const {
                        metaData,
                        response
                    } = data;

                    if (metaData.code == 200) {
                        const options = response.list.map((item) => {
                            return `<option value="${item.kdObat}">${item.nmObat}</option>`;
                        });

                        select.empty().append(options); // Append the new options
                        select.select2({
                            allowClear: true,
                            placeholder: 'Pilih Obat'
                        }).on('select2:clearing', (e) => {
                            getObatPcare(kode_brng)
                        });
                    } else {
                        getObatPcare(kode_brng);
                    }
                }).fail((error) => {
                    alertErrorAjax(error)
                });

            } else {
                inputMapping.addClass('d-none');
                select.select2('destroy');
                getObatPcare(kode_brng);
                btnObat.removeClass('d-none');
            }
        }

        function cancelObatPcareMapping(kode_brng) {
            const select = $(`#selectMappingObatPcare${kode_brng}`);
            if (select.data('select2')) {
                select.select2('destroy');
                $(`#mappingObatPcare${kode_brng}`).addClass('d-none');
                $(`#btnObat${kode_brng}`).removeClass('d-none');
            }
        }

        function getObatPcare(kode_brng) {
            const select = $(`#selectMappingObatPcare${kode_brng}`);
            select.select2({
                width: 'resolve',
                ajax: {
                    url: (params) => {
                        const keyword = params.term || 'A';
                        return `/efktp/bridging/pcare/obat/${keyword}`;
                    },
                    dataType: 'json',
                    delay: 200,
                    processResults: function (data) {
                        return {
                            results: data.response.list.map(function (item) {
                                return {
                                    id: item.kdObat,
                                    text: `${item.nmObat}`
                                };
                            })
                        };
                    },
                    language: {
                        noResults: function () {
                            return "No matching medicines found"; // Custom no-results message
                        }
                    },
                }
            });

        }

        function createObatPcareMapping(kodeBrng) {
            const select = $(`#selectMappingObatPcare${kodeBrng}`);
            const mappingContainer = $(`#mappingObatPcare${kodeBrng}`);
            const labelContainer = $(`#labelMapping${kodeBrng}`);
            const selectedObat = select.select2('data');
            const kodeObat = selectedObat[0].id;
            const namaObat = selectedObat[0].text;

            $.post(`/efktp/mapping/pcare/obat`, {
                kode_brng: kodeBrng,
                kode: kodeObat,
                nama: namaObat
            }).done((response) => {
                toast('response')
                mappingContainer.addClass('d-none');
                select.select2('destroy');
                labelContainer.empty().html(`
                    <div id="btnObat${kodeBrng}">
                        <span class="me-2">${namaObat}</span>
                        <a href="javascript:void(0)" class="text-primary" onclick="setMappingObatPcare('${kodeBrng}', '${namaObat.split('/')[0]}')"><i class="ti ti-pencil"></i></a>
                        <a href="javascript:void(0)" class="text-danger" onclick="deleteObatPcareMapping('${kodeBrng}')"><i class="ti ti-x"></i></a>
                    </div>
                `);
            }).fail((error) => {
                alertErrorAjax(error);
            });
        }

        function deleteObatPcareMapping(kodeBrng) {
            Swal.fire({
                title: "Yakin hapus data ini ?",
                html: "Data mapping obat Pcare akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/mapping/pcare/obat/delete/${kodeBrng}`, {
                        _token: "{{ csrf_token() }}"
                    }).done((response) => {
                        toast('Menghapus data mapping obat Pcare ')
                        $(`#labelMapping${kodeBrng}`).empty().html(`
                            <button type="button" class="btn btn-sm btn-warning" id="btnObat${kodeBrng}" onclick="setMappingObatPcare('${kodeBrng}', '${kodeBrng}')"><i class="ti ti-search me-2"></i> Cari Referensi</button>`);
                    }).fail((error) => {
                    });
                }
            })
        }
    </script>
@endpush

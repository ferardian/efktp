@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="table-default" class="table-responsive">
                            <table class="table table-sm table-striped table-hover nowrap" id="tabelPcarePendaftaran" width="100%">
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row d-none-sm d-none-md">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <form action="registrasi/get" method="post" id="formFilterTanggal">
                                    <div class="input-group">
                                        <input class="form-control filterTangal" placeholder="Select a date" id="tglAwal" name="tglAwal" value="{{ date('d-m-Y') }}">
                                        <span class="input-group-text">
                                            s.d
                                        </span>
                                        <input class="form-control filterTangal" placeholder="Select a date" id="tglAkhir" name="tglAkhir" value="{{ date('d-m-Y') }}">
                                        <button class="btn w-5 btn-secondary" type="submit" onclick="">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="10" height="10" viewBox="-5 -5 24 30" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                <path d="M21 21l-6 -6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                <button type="button" class="btn btn-success" onclick="showPendaftaranPcare()"> Bridging</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('content.pcare.pendaftaran._modalPendaftaran')
    @include('content.pemeriksaan.modalCppt')
@endsection
@push('script')
    <script>
        $(document).ready(() => {

            let tglAwal = localStorage.getItem('tglAwal') ? localStorage.getItem('tglAwal') : tanggal;
            let tglAkhir = localStorage.getItem('tglAkhir') ? localStorage.getItem('tglAkhir') : tanggal;


            $('#tglAwal').val(tglAwal)
            $('#tglAkhir').val(tglAkhir)

            loadTbPcarePendaftaran(tglAwal, tglAkhir)

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        })

        function showPendaftaranPcare() {
            renderPendaftaranPcare();
            $('#modalPendaftaranPcare').modal('show')
        }

        $('#formFilterTanggal').on('submit', (e) => {
            e.preventDefault();
            const tglAwal = $('#formFilterTanggal input[name=tglAwal]').val();
            const tglAkhir = $('#formFilterTanggal input[name=tglAkhir]').val();
            localStorage.setItem('tglAwal', tglAwal)
            localStorage.setItem('tglAkhir', tglAkhir)
            loadTbPcarePendaftaran(tglAwal, tglAkhir);
        })

        function loadTbPcarePendaftaran(tglAwal = '', tglAkhir = '') {
            const tbPcarePendaftaran = new DataTable('#tabelPcarePendaftaran', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '52vh',
                scrollX: true,
                ajax: {
                    url: 'pendaftaran/get',
                    data: {
                        datatable: true,
                        tgl_awal: tglAwal,
                        tgl_akhir: tglAkhir,
                    },
                },
                createdRow: (row, data, index) => {
                    $('td', row).eq(1).css('text-align', 'center')
                    $(row).addClass('rowPendaftaranPcare').attr('data-id', data.no_rawat)
                        .attr('data-peserta', data.noKartu);
                },
                columnDefs: [{
                        width: '6%',
                        targets: 10
                    },
                    {
                        width: '5%',
                        targets: 0
                    },
                    {
                        width: '14%',
                        targets: 6
                    }
                ],
                columns: [{
                        title: '',
                        data: 'reg_periksa',
                        render: (data, type, row, meta) => {
                            let attr = 'javascript:void(0)';
                            let target = '';
                            let action = '';

                            btnStatusLayanan = ''
                            if (data.stts === 'Belum') {
                                btnStatusLayanan = 'btn-primary'
                                action = `setPanggilBpjs('${data.no_rawat}')`
                            } else if (data.stts === 'Berkas Diterima' || data.stts === 'Dirawat') {
                                btnStatusLayanan = 'btn-purple'
                                action = `setBelumBpjs('${data.no_rawat}')`
                                data.stts = 'Diperiksa';
                            } else if (data.stts === 'Batal') {
                                btnStatusLayanan = 'btn-danger'
                            } else if (data.stts === 'Sudah') {
                                btnStatusLayanan = 'btn-success'
                            }

                            button = `<a href="${attr}" ${target}  class="btn btn-sm ${btnStatusLayanan}" onclick="${action}" style="width:100%" >${data.stts.toUpperCase()}</a>`


                            return button;
                        },
                    },
                    {
                        title: 'No. Urut',
                        data: 'noUrut',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Tgl Daftar',
                        data: 'tglDaftar',
                        render: (data, type, row, meta) => {
                            return splitTanggal(data)
                        },
                    },
                    {
                        title: 'Jam',
                        data: 'reg_periksa.jam_reg',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'No. Peserta',
                        data: 'noKartu',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'No. RM',
                        data: 'reg_periksa.no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Nama',
                        data: 'nm_pasien',
                        render: (data, type, row, meta) => {

                            return `<span class="text-muted" style="font-size:9px;font-style:italic">${row.reg_periksa.no_rawat} <br/></span>
                            ${data}`;
                        },
                    },
                    {
                        title: 'Alamat',
                        data: 'reg_periksa.pasien.',
                        render: (data, type, row, meta) => {
                            return `${data.alamat}, ${data.kel.nm_kel}, ${data.kec.nm_kec} `;
                        },
                    },
                    {
                        title: 'Poli',
                        data: 'nmPoli',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Kunjungan',
                        data: 'kunjSakit',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Keluhan',
                        data: 'keluhan',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: '',
                        data: 'noUrut',
                        render: (data, type, row, meta) => {
                            let classHidden = ''
                            if (row.kunjungan) {
                                classHidden = Object.keys(row.kunjungan).length ? 'd-none' : '';
                            }

                            const btnCppt = row.kunjungan ? `btn-success` : `btn-outline-primary`;
                            const parameter = {
                                noUrut: row.noUrut,
                                noKartu: row.noKartu,
                                tglDaftar: splitTanggal(row.tglDaftar),
                                kdPoli: row.kdPoli,
                                no_rawat: row.reg_periksa.no_rawat
                            }
                            return `<button type="button" class="btn btn-sm btn-danger ${classHidden}" onclick='deletePendaftaran(${JSON.stringify(parameter)})'><i class="ti ti-trash"></i> Hapus</button>
                            <button type="button" class="btn btn-sm ${btnCppt}" onclick="showCpptRalan('${row.reg_periksa.no_rawat}')"><i class="ti ti-pencil"></i> CPPT</button>`;
                        },
                    },


                ]
            })
        }

        function printKunjungan(noKunjungan) {
            $.get(`kunjungan/print/${noKunjungan}`).done((response) => {})
        }

        function deletePendaftaran(parameter) {

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
                    loadingAjax('Sedang data pendaftaran di Pcare');
                    $.post(`/efktp/bridging/pcare/pendaftaran/delete`, parameter).done((response) => {
                        alertSuccessAjax('Berhasil menghapus data di Pcare').then(() => {
                            loadingAjax('Menghapus data internal');
                            $.post(`/efktp/pcare/pendaftaran/delete`, {
                                'noUrut': parameter.noUrut,
                                'no_rawat': parameter.no_rawat,
                            }).done((response) => [
                                alertSuccessAjax('Berhasil').then(() => {
                                    loadTbPcarePendaftaran(tglAwal, tglAkhir);
                                })
                            ])
                        })
                    })
                }
            });
        }

        function setPanggilBpjs(no_rawat) {
            setStatusLayan(no_rawat, 'Berkas Diterima').done((response) => {
                loadTbPcarePendaftaran(tglAwal, tglAkhir);
            });
        }

        function setBelumBpjs(no_rawat) {
            setStatusLayan(no_rawat, 'Belum').done((response) => {
                loadTbPcarePendaftaran(tglAwal, tglAkhir);
            });
        }
    </script>
@endpush

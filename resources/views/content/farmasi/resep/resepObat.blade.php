@extends('layout')

@section('body')
    <div class="container-fluid h-100">
        <div class="card">
            <div class="card-body">
                <div id="table-default" class="table-responsive">
                    <table class="table nowrap table-sm table-striped table-hover" id="tbResepObat"
                           width="100%"></table>
                </div>
            </div>
            <div class="card-footer">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="input-group">
                        <input class="form-control filterTangal" placeholder="Select a date" id="tgl_awal"
                               name="tgl_awal" value="{{ date('d-m-Y') }}">
                        <span class="input-group-text">
                                    s.d
                                </span>
                        <input class="form-control filterTangal" placeholder="Select a date" id="tgl_akhir"
                               name="tgl_akhir" value="{{ date('d-m-Y') }}">
                        <button class="btn w-5 btn-secondary" type="button" id="btnFilterTanggal">
                            <i class="ti ti-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('content.farmasi.resep._modalDetailResep')
@endsection
@push('script')
    <script>
        // const tglAwalResep = localStorage.getItem('tglAwalResep');
        // const tglAkhirResep = localStorage.getItem('tglAkhirResep');

        $(document).ready(() => {
            const tglAwalResep = $('#tgl_awal').val();
            const tglAkhirResep = $('#tgl_akhir').val();
            tbResepObat(tglAwalResep, tglAkhirResep);

            setInterval(() => {
                tbResepObat(tglAwalResep, tglAkhirResep);
            }, 30000);
        })

        function tbResepObat(tgl_awal = '', tgl_akhir = '') {
            console.log(setTableHeight())
            const tabel = new DataTable('#tbResepObat', {
                responsive: true,
                autoWidth: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                fixedHeader: true,
                scrollY: setTableHeight(),
                pageLength: 50,
                scrollX: true,
                ajax: {
                    url: `/efktp/resep/get`,
                    data: {
                        dataTable: true,
                        tgl_awal: tgl_awal,
                        tgl_akhir: tgl_akhir,
                    },
                },
                columns: [{
                    title: '',
                    data: 'no_resep',
                    render: (data, type, row, meta) => {

                        let colorBtn, displayPanggil = '',
                            displaySelesai = '';

                        if (isAvailableTime(row.jam_penyerahan)) {
                            colorBtn = `btn-success`
                            display = `d-none`

                        } else {
                            colorBtn = `btn-danger`
                            display = ``
                        }

                        if (row.jam === '00:00:00') {
                            displayPanggil = 'd-none';
                            displaySelesai = 'd-none';
                        }

                        return `<button class="btn btn-sm ${colorBtn}" onclick="showDetailResep('${data}')"><i class="ti ti-search"></i>Lihat</button>
                            <button class="btn btn-sm btn-success ${display} ${displayPanggil}" onclick="panggilResepPasien('${data}', '${row.reg_periksa.pasien.nm_pasien}')"><i class="ti ti-phone"></i>Panggil</button>
                            <button class="btn btn-sm btn-primary ${display} ${displaySelesai}" onclick="setPenyerahanResep('${data}')"><i class="ti ti-send"></i>Selesai</button>`;
                    },
                },
                    {
                        title: 'NO RESEP',
                        data: 'no_resep',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'NO. RAWAT',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'NAMA',
                        data: 'reg_periksa.pasien.nm_pasien',
                        render: (data, type, row, meta) => {
                            return `${row.reg_periksa.no_rkm_medis} - ${data}`;
                        },
                    },
                    {
                        title: 'POLIKLINIK',
                        data: 'reg_periksa.poliklinik.nm_poli',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'DOKTER',
                        data: 'reg_periksa.dokter.nm_dokter',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Asuransi',
                        data: 'reg_periksa.penjab.png_jawab',
                        render: (data, type, row, meta) => {
                            return setTextPenjab(data);
                        },
                    },
                    {
                        title: 'DIBUAT',
                        data: 'tgl_peresepan',
                        render: (data, type, row, meta) => {
                            return `${isAvaliableDate(data)} ${isAvailableTime(row.jam_peresepan)}`;
                        },
                    },
                    {
                        title: 'VALIDASI',
                        data: 'tgl_perawatan',
                        render: (data, type, row, meta) => {
                            return `${isAvaliableDate(data)} ${isAvailableTime(row.jam)}`;
                        },
                    },
                    {
                        title: 'PENYERAHAN',
                        data: 'tgl_penyerahan',
                        render: (data, type, row, meta) => {
                            return `${isAvaliableDate(data)} ${isAvailableTime(row.jam_penyerahan)}`;
                        },
                    },
                ],
                initComplete: () => {
                    showToast('Memuat hasil resep obat');
                }
            })
        }

        $('#btnFilterTanggal').on('click', (e) => {
            tgl_awal = $('#tgl_awal').val();
            tgl_akhir = $('#tgl_akhir').val();

            localStorage.setItem('tglAwalResep', tgl_awal);
            localStorage.setItem('tglAkhirResep', tgl_akhir);

            tbResepObat(tgl_awal, tgl_akhir);
        })

        function isAvaliableDate(tanggal) {
            const listTanggal = ['0000-00-00', '00-00-00', ''];
            const tgl = listTanggal.includes(tanggal) ? "-" : tanggal;
            return tgl;
        }

        function isAvailableTime(jam) {
            const listJam = ['00:00:00', ''];
            const jams = listJam.includes(jam) ? "" : jam;
            return jams;
        }

        function showDetailResep(no_resep) {
            $.get(`/efktp/farmasi/resep/get`, {
                no_resep: no_resep
            }).done((response) => {
                if (response.resep_dokter.length || response.resep_racikan.length) {
                    const resepDokter = response.resep_dokter.map((item, index) => {
                        return `<li>${item.obat.nama_brng} @${item.jml} ${item.obat.satuan.satuan} S.${item.aturan_pakai}</li>`;
                    }).join('')

                    const resepRacikan = response.resep_racikan.map((item, index) => {
                        const detail = item.detail.map((subItem) => {
                            return `<li>${subItem.obat.nama_brng} @${subItem.jml} ${subItem.obat.satuan.satuan}</li>`;
                        }).join('');
                        return `<li>${item.metode.nm_racik} ${item.nama_racik} @${item.jml_dr} S.${item.aturan_pakai}<ul>${detail}</ul></li>`;
                    }).join('')
                    $('#olUmum').html(resepDokter);
                    $('#olRacik').html(resepRacikan);
                    modalDetailResep.modal('show')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak ada obat didalam nomor resep ini',
                    })
                }
            })
        }

        function setPenyerahanResep(no_resep) {
            $.post(`/efktp/farmasi/resep/set/penyerahan`, {
                no_resep: no_resep
            }).done((response) => {
                localStorage.removeItem('no_resep');
                localStorage.removeItem('nm_pasien');
                localStorage.setItem('panggil', 'done')
                toast('Obat telah selesai');
                tgl_awal = $('#tgl_awal').val();
                tgl_akhir = $('#tgl_akhir').val();
                tbResepObat(tgl_awal, tgl_akhir);
            })
        }

        function panggilResepPasien(no_resep, nm_pasien) {
            localStorage.setItem('no_resep', no_resep);
            localStorage.setItem('nm_pasien', nm_pasien);
            localStorage.setItem('panggil', 'yes')
            $.get(`/efktp/resep/get`, {
                no_resep: no_resep
            }).done((response) => {
                localStorage.setItem('resepPoliklinik', response.reg_periksa.poliklinik.nm_poli);
                localStorage.setItem('resepDokter', response.reg_periksa.dokter.nm_dokter);
            })
        }
    </script>
@endpush

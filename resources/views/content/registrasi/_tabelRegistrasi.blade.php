<div id="table-default" class="table-responsive">
    <table class="table table-sm table-striped table-hover nowrap" id="tabelRegistrasi" width="100%">
    </table>
</div>

{{-- MODAL --}}

@include('content.pemeriksaan.modalCppt')

@include('content.pcare.pendaftaran._modalPasien')
@push('script')
    <script type="" src="{{asset('public/libs/list.js/dist/list.min.js')}}"></script>
    <script>
        const formFilterRegistrasi = $('#formFilterRegistrasi')
        const inputTglAwal = $('#tglAwal')
        const inputTglAkhir = $('#tglAkhir')
        const selectFilterDokter = formFilterRegistrasi.find('select[name="dokter"]');
        const selectFilterStts = formFilterRegistrasi.find('select[name="stts"]');

        const dokterLocal = localStorage.getItem('dokter') ? JSON.parse(localStorage.getItem('dokter')) : isDokter;
        const statusLocal = localStorage.getItem('stts') ? localStorage.getItem('stts') : '';

        $(document).ready(() => {
            isObjectEmpty(isDokter) ? localStorage.setItem('dokter', !isObjectEmpty(dokterLocal) ? JSON.stringify(dokterLocal) : isDokter) : '';
            let optDokter = !isObjectEmpty(dokterLocal) ? new Option(dokterLocal.nm_dokter, dokterLocal.kd_dokter, true, true) : "";
            let optStts = statusLocal ? new Option(statusLocal, statusLocal, true, true) : "";
            selectDokter(selectFilterDokter, formFilterRegistrasi)
            selectFilterStts.append(optStts)
            selectFilterDokter.append(optDokter)
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), selectFilterDokter.val());
        })

        selectFilterDokter.on('change', (e) => {
            e.preventDefault();
            const nmDokter = e.currentTarget.options[e.currentTarget.selectedIndex].text
            let dokter = JSON.stringify({
                kd_dokter: e.currentTarget.value,
                nm_dokter: nmDokter
            });
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), e.currentTarget.value);
            if (!e.currentTarget.value) {
                dokter = '';
            }
            localStorage.setItem('dokter', dokter);
        })

        selectFilterStts.on('change', (e) => {
            e.preventDefault();
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), e.currentTarget.value, selectFilterDokter.val());
            let stts = e.currentTarget.value;
            if (!e.currentTarget.value) {
                stts = '';
            }
            localStorage.setItem('stts', stts);
        })

        $('#btnFilterRegistrasi').on('click', (e) => {
            e.preventDefault();
            const tglAwal = $('#formFilterRegistrasi input[name=tglAwal]').val()
            const tglAkhir = $('#formFilterRegistrasi input[name=tglAkhir]').val()
            localStorage.setItem('tglAwal', tglAwal)
            localStorage.setItem('tglAkhir', tglAkhir)
            loadTabelRegistrasi(tglAwal, tglAkhir, selectFilterStts.val(), selectFilterDokter.val());
        })

        function loadTabelRegistrasi(tglAwal = '', tglAkhir = '', stts = '', dokter = '') {
            console.log(setTableHeight())
            const tabelRegistrasi = new DataTable('#tabelRegistrasi', {
                responsive: true,
                // autoWidth: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                fixedHeader: true,
                scrollY: setTableHeight(),
                pageLength: 50,
                scrollX: true,
                ajax: {
                    url: 'registrasi/get',
                    data: {
                        dataTable: true,
                        tglAwal: tglAwal,
                        tglAkhir: tglAkhir,
                        stts: stts,
                        dokter: dokter,
                    },
                },
                createdRow: (row, data, index) => {
                    $(row).addClass('rows-registrasi')
                        .attr('data-id', data.no_rawat)
                        .attr('data-penjab', data.penjab.png_jawab.includes('BPJS') ? 'BPJS' : 'UMUM')
                        .attr('data-poli', data.kd_poli)
                        .attr('data-no_rkm_medis', data.no_rkm_medis)
                        .attr('data-noPeserta', data.pasien?.no_peserta);
                },
                columnDefs: [{
                    orderable: false,
                    targets: [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                }],
                columns: [{
                    title: '',
                    data: 'no_rawat',
                    render: (data, type, row, meta) => {
                        let attr = 'javascript:void(0)';
                        let target = '';
                        let action = '';
                        if (row.stts === 'Belum') {
                            btnStatusLayanan = 'btn-primary'
                            action = `setPanggil('${data}', this)`
                        } else if (row.stts === 'Berkas Diterima') {
                            btnStatusLayanan = 'btn-purple'
                            action = `setBelum('${data}', this)`
                            row.stts = 'PANGGIL';
                        } else if (row.stts === 'Dirawat') {
                            btnStatusLayanan = 'btn-cyan'
                            action = `setBelum('${data}', this)`
                            row.stts = 'DIPERIKSA';
                        } else if (row.stts === 'Batal') {
                            btnStatusLayanan = 'btn-danger'
                        } else if (row.stts === 'Sudah') {
                            btnStatusLayanan = 'btn-success'
                        } else if (row.stts === 'Dirujuk') {
                            btnStatusLayanan = 'btn-warning'
                            if (row.pcare_rujuk_subspesialis) {
                                attr = `pcare/kunjungan/rujuk/subspesialis/print/${row.pcare_rujuk_subspesialis.noKunjungan}`
                                target = 'target="_blank"';
                            }
                        }

                        button = `<a href="${attr}" ${target}  class="btn btn-sm ${btnStatusLayanan}" onclick="${action}" style="width:100%" id="btnStatusLayanan${formatNoRawat(row.no_rawat)}">${row.stts.toUpperCase()}</a>`


                        return button;
                    }
                },
                    {
                        title: '',
                        render: (data, type, row, meta) => {
                            let classBtnPemerisksaan = 'btn-outline-primary'
                            if (row.pemeriksaan_ralan) {
                                classBtnPemerisksaan = 'btn-success'
                            }
                            return `<button type="button" class="btn btn-sm ${classBtnPemerisksaan}" onclick="showCpptRalan('${row.no_rawat}')"><i class="ti ti-file-pencil"></i> CPPT</button>`;
                        },
                    },

                    {
                        title: 'No',
                        render: (data, type, row, meta) => {
                            return `<span style="cursor:pointer" onclick="buktiRegister('${row.no_rawat}')">${row.no_reg}</span>    `;
                        }
                    },

                    {
                        title: 'Poli',
                        data: 'poliklinik.nm_poli',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Dokter',
                        data: 'dokter.nm_dokter',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Tanggal',
                        render: (data, type, row, meta) => {
                            return splitTanggal(row.tgl_registrasi);
                        },
                    },
                    {
                        title: 'Jam',
                        data: 'jam_reg',
                        render: (data, type, row, meta) => {
                            return row.jam_reg;
                        },
                    },

                    {
                        title: 'No. RM',
                        data: 'no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    }, {
                        title: 'Pasien',
                        data: 'pasien',
                        render: (data, type, row, meta) => {
                            return `<span class="text-muted" style="font-style:italic">${row.no_rawat}</span> <br/> ${data?.nm_pasien} (${data?.jk})`;
                        }
                    },
                    {
                        title: 'Umur',
                        data: 'umurdaftar',
                        render: (data, type, row, meta) => {
                            return `${row.umurdaftar} ${row.sttsumur}`;
                        },
                    },
                    {
                        title: 'Alamat',
                        render: (data, type, row, meta) => {
                            return `${row.pasien?.alamat}, ${row.pasien?.kel.nm_kel}`;
                        },
                    },
                    {
                        title: 'Alergi',
                        data: 'pasien',
                        render: (data, type, row, meta) => {
                            if (data) {
                                const alergi = data?.alergi.map((val) => {
                                    return val.alergi
                                }).join(', ')
                                return `<span class="text-red">${alergi}</span>`;

                            }
                            return '';
                        },
                    },
                    {
                        title: 'status',
                        data: 'stts_daftar',
                        render: (data, type, row, meta) => {
                            return `<span class="badge ${data.toUpperCase() === 'LAMA' ? 'badge-outline text-primary' : 'badge-outline text-orange'}">${data}`;
                        },
                    },
                    {
                        title: 'Penjab',
                        data: 'penjab.png_jawab',
                        render: (data, type, row, meta) => {
                            return setTextPenjab(data);
                        },
                    },


                ]
            })
        }

        function formatNoRawat(no_rawat) {
            return no_rawat.replace(/\//g, "");
        }

        function symbolGigi(hasil, size = '') {
            switch (hasil) {
                case 'Tumpatan':
                    return `<i class="ti ti-circle-filled" style="font-size:${size ? size : 35}px"></i>`
                    break;
                case 'Erupsi':
                    return `<i class="ti ti-arrows-horizontal" style="font-size:${size ? size : 35}px"></i>`
                    break;
                case 'Hilang':
                    return `<i class="ti ti-x" style="font-size:${size ? size : 35}px"></i>`
                    break;
                case 'Sisa Akar':
                    return `<i class="ti ti-letter-v" style="font-size:${size ? size : 35}px"></i>`;
                    break;
                case 'Karies':
                    return `<i class="ti ti-circle" style="font-size:${size ? size : 35}px"></i>`;
                    break;
                case 'Goyang':
                    return `<i class="ti ti-currency-euro" style="font-size:${size ? size : 35}px"></i>`;
                    break;

                default:
                    break;
            }
        }

        function setPanggil(no_rawat, element) {
            setStatusLayan(no_rawat, 'Berkas Diterima')
        }

        function setBelum(no_rawat, element) {
            setStatusLayan(no_rawat, 'Belum').done((response) => {
                if (element) {
                    $(element).removeClass('btn-purple').addClass('btn-primary').text('BELUM').attr('onclick', `setPanggil('${no_rawat}', this)`);
                }
            });
        }
    </script>
@endpush()

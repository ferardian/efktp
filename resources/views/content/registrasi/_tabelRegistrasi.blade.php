<div id="table-default" class="table-responsive">
    <table class="table table-sm table-striped table-hover nowrap" id="tabelRegistrasi" width="100%">
    </table>
</div>

{{-- MODAL --}}

@include('content.pemeriksaan.modalCppt')

@include('content.pcare.pendaftaran._modalPasien')
@push('script')
    <script type="" src="{{asset('libs/list.js/dist/list.min.js')}}"></script>
    <script>
        const formFilterRegistrasi = $('#formFilterRegistrasi')
        const inputTglAwal = $('#tglAwal')
        const inputTglAkhir = $('#tglAkhir')
        const selectFilterDokter = formFilterRegistrasi.find('select[name="dokter"]');
        const selectFilterPoli = formFilterRegistrasi.find('select[name="poli"]');
        const selectFilterStts = formFilterRegistrasi.find('select[name="stts"]');

        const dokterLocal = localStorage.getItem('dokter') ? JSON.parse(localStorage.getItem('dokter')) : isDokter;
        const poliLocal = localStorage.getItem('poli') ? JSON.parse(localStorage.getItem('poli')) : "";
        const statusLocal = localStorage.getItem('stts') ? localStorage.getItem('stts') : '';

        $(document).ready(() => {
            isObjectEmpty(isDokter) ? localStorage.setItem('dokter', !isObjectEmpty(dokterLocal) ? JSON.stringify(dokterLocal) : isDokter) : '';
            let optDokter = !isObjectEmpty(dokterLocal) ? new Option(dokterLocal.nm_dokter, dokterLocal.kd_dokter, true, true) : "";
            let optPoli = !isObjectEmpty(poliLocal) ? new Option(poliLocal.nm_poli, poliLocal.kd_poli, true, true) : "";
            let optStts = statusLocal ? new Option(statusLocal, statusLocal, true, true) : "";
            selectDokter(selectFilterDokter, formFilterRegistrasi)
            selectPoliklinik(selectFilterPoli, formFilterRegistrasi)
            selectFilterStts.append(optStts)
            selectFilterDokter.append(optDokter)
            selectFilterPoli.append(optPoli)
            changeStatusColor(selectFilterStts.val())
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), selectFilterDokter.val(), selectFilterPoli.val());
        })

        selectFilterDokter.on('change', (e) => {
            e.preventDefault();
            const nmDokter = e.currentTarget.options[e.currentTarget.selectedIndex].text
            let dokter = JSON.stringify({
                kd_dokter: e.currentTarget.value,
                nm_dokter: nmDokter
            });
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), e.currentTarget.value, selectFilterPoli.val());
            if (!e.currentTarget.value) {
                dokter = '';
            }
            localStorage.setItem('dokter', dokter);
        })

        selectFilterPoli.on('change', (e) => {
            e.preventDefault();
            const nmPoli = e.currentTarget.options[e.currentTarget.selectedIndex].text
            let poli = JSON.stringify({
                kd_poli: e.currentTarget.value,
                nm_poli: nmPoli
            });
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), selectFilterStts.val(), selectFilterDokter.val(), e.currentTarget.value);
            if (!e.currentTarget.value) {
                poli = '';
            }
            localStorage.setItem('poli', poli);
        })

        selectFilterStts.on('change', (e) => {
            e.preventDefault();
            const stts = e.currentTarget.value;
            changeStatusColor(stts)
            loadTabelRegistrasi(inputTglAwal.val(), inputTglAkhir.val(), stts, selectFilterDokter.val(), selectFilterPoli.val());
            localStorage.setItem('stts', stts ? stts : '');
        })

        function changeStatusColor(status) {
            const select2Selection = selectFilterStts.next().find('.select2-selection');
            let bgColor = '';
            let textColor = '';

            switch (status) {
                case 'Sudah':
                    bgColor = '#2fb344'; // Green
                    textColor = '#ffffff';
                    break;
                case 'Batal':
                    bgColor = '#d63939'; // Red
                    textColor = '#ffffff';
                    break;
                case 'Dirujuk':
                    bgColor = '#f76707'; // Orange
                    textColor = '#ffffff';
                    break;
                case 'Belum':
                    bgColor = '#206bc4'; // Blue
                    textColor = '#ffffff';
                    break;
                default:
                    bgColor = '';
                    textColor = '';
                    break;
            }

            if (bgColor) {
                select2Selection.css({
                    'background-color': bgColor,
                    'color': textColor
                });
                select2Selection.find('.select2-selection__rendered').css('color', textColor);
            } else {
                select2Selection.css({
                    'background-color': '',
                    'color': ''
                });
                select2Selection.find('.select2-selection__rendered').css('color', '');
            }
        }

        $('#btnFilterRegistrasi').on('click', (e) => {
            e.preventDefault();
            const tglAwal = $('#formFilterRegistrasi input[name=tglAwal]').val()
            const tglAkhir = $('#formFilterRegistrasi input[name=tglAkhir]').val()
            localStorage.setItem('tglAwal', tglAwal)
            localStorage.setItem('tglAkhir', tglAkhir)
            loadTabelRegistrasi(tglAwal, tglAkhir, selectFilterStts.val(), selectFilterDokter.val(), selectFilterPoli.val());
        })

        function loadTabelRegistrasi(tglAwal = '', tglAkhir = '', stts = '', dokter = '', poli = '') {
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
                        poli: poli,
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
                order: [[2, 'asc']],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
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
                            const p = row.pemeriksaan_ralan;
                            const hasPemeriksaan = p && (
                                (p.keluhan && p.keluhan !== '-' && p.keluhan.trim() !== '') ||
                                (p.pemeriksaan && p.pemeriksaan !== '-' && p.pemeriksaan.trim() !== '') ||
                                (p.penilaian && p.penilaian !== '-' && p.penilaian.trim() !== '') ||
                                (parseFloat(p.suhu_tubuh) > 0) ||
                                (p.tensi && p.tensi !== '0/0' && p.tensi !== '0' && p.tensi !== '-' && p.tensi.trim() !== '') ||
                                (parseFloat(p.nadi) > 0) ||
                                (parseFloat(p.berat) > 0) ||
                                (parseFloat(p.tinggi) > 0) ||
                                (parseFloat(p.respirasi) > 0)
                            );
                            let classBtnPemerisksaan = hasPemeriksaan ? 'btn-success' : 'btn-outline-primary';

                            const icCount = Number(row.persetujuan_penolakan_tindakan_count || (row.persetujuan_penolakan_tindakan ? row.persetujuan_penolakan_tindakan.length : 0));
                            const pbCount = Number(row.penilaian_pre_operasi_count || (row.penilaian_pre_operasi ? row.penilaian_pre_operasi.length : 0));
                            const paCount = Number(row.penilaian_pre_anestesi_count || (row.penilaian_pre_anestesi ? row.penilaian_pre_anestesi.length : 0));
                            const maCount = Number(row.laporan_anestesi_count || (row.laporan_anestesi ? row.laporan_anestesi.length : 0));
                            const signinCount = Number(row.bukti_anestesi_signin_count || (row.bukti_anestesi_signin ? row.bukti_anestesi_signin.length : 0));
                            const ppCount = Number(row.perencanaan_pemulangan_count || (row.perencanaan_pemulangan ? row.perencanaan_pemulangan.length : 0));

                            const hasIc = icCount > 0;
                            const hasPb = pbCount > 0;
                            const hasPa = paCount > 0;
                            const hasMa = maCount > 0;
                            const hasSignin = signinCount > 0;
                            const hasPp = ppCount > 0;

                            const totalErmCount = icCount + pbCount + paCount + maCount + signinCount + ppCount;
                            const hasErm = totalErmCount > 0;

                            let btnErmClass = 'btn-outline-secondary';
                            let badgeBtn = '';
                            if (hasErm) {
                                btnErmClass = 'btn-outline-success border-success';
                                badgeBtn = `<span class="badge bg-success text-white rounded-pill px-1 py-0 ms-1" style="font-size: 0.65rem;" title="${totalErmCount} Dokumen ERM Terisi">${totalErmCount}</span>`;
                            }

                            return `<div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm ${classBtnPemerisksaan}" onclick="showCpptRalan('${row.no_rawat}')" title="Buka CPPT"><i class="ti ti-file-pencil"></i> CPPT</button>
                                <div class="dropdown">
                                    <button class="btn btn-sm ${btnErmClass} dropdown-toggle px-2 d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu ERM ${hasErm ? '(' + totalErmCount + ' Dokumen)' : ''}">
                                        <i class="ti ti-clipboard-text"></i>${badgeBtn}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="z-index: 1055;">
                                        <li><h6 class="dropdown-header text-uppercase py-1"><i class="ti ti-heart-rate-monitor me-1"></i> Form Medis & ERM</h6></li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center" href="javascript:void(0)" onclick="penilaianMedisRalan('${row.no_rawat}')">
                                                <span><i class="ti ti-stethoscope text-teal me-2"></i> Penilaian Awal Medis Umum</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center" href="javascript:void(0)" onclick="penilaianAwalKeperawatan('${row.no_rawat}')">
                                                <span><i class="ti ti-clipboard-check text-primary me-2"></i> Penilaian Awal Keperawatan</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center" href="javascript:void(0)" onclick="openPengkajianPrimer('${row.no_rawat}')">
                                                <span><i class="ti ti-ambulance text-danger me-2"></i> Pengkajian Primer A-B-C-D-E</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center ${hasIc ? 'fw-bold text-success' : ''}" href="javascript:void(0)" onclick="bukaInformedConsent('${row.no_rawat}')">
                                                <span><i class="ti ti-file-certificate ${hasIc ? 'text-success' : 'text-primary'} me-2"></i> Informed Consent</span>
                                                ${hasIc ? `<span class="badge bg-success text-white rounded-pill ms-2 px-1 py-0" style="font-size: 0.7rem;"><i class="ti ti-check me-1" style="font-size: 0.65rem;"></i>${icCount}</span>` : ''}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center ${hasPb ? 'fw-bold text-success' : ''}" href="javascript:void(0)" onclick="bukaKajianPraBedah('${row.no_rawat}')">
                                                <span><i class="ti ti-cut ${hasPb ? 'text-success' : 'text-danger'} me-2"></i> Kajian Pra Bedah</span>
                                                ${hasPb ? `<span class="badge bg-success text-white rounded-pill ms-2 px-1 py-0" style="font-size: 0.7rem;"><i class="ti ti-check me-1" style="font-size: 0.65rem;"></i>${pbCount}</span>` : ''}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center ${hasPa ? 'fw-bold text-success' : ''}" href="javascript:void(0)" onclick="bukaKajianPraAnestesi('${row.no_rawat}')">
                                                <span><i class="ti ti-needle ${hasPa ? 'text-success' : 'text-info'} me-2"></i> Kajian Pra Anestesi</span>
                                                ${hasPa ? `<span class="badge bg-success text-white rounded-pill ms-2 px-1 py-0" style="font-size: 0.7rem;"><i class="ti ti-check me-1" style="font-size: 0.65rem;"></i>${paCount}</span>` : ''}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center ${hasMa ? 'fw-bold text-success' : ''}" href="javascript:void(0)" onclick="bukaMonitoringAnestesi('${row.no_rawat}')">
                                                <span><i class="ti ti-activity-heartbeat ${hasMa ? 'text-success' : 'text-warning'} me-2"></i> Monitoring Anestesi</span>
                                                ${hasMa ? `<span class="badge bg-success text-white rounded-pill ms-2 px-1 py-0" style="font-size: 0.7rem;"><i class="ti ti-check me-1" style="font-size: 0.65rem;"></i>${maCount}</span>` : ''}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center ${hasSignin ? 'fw-bold text-success' : ''}" href="javascript:void(0)" onclick="bukaPemantauanAnestesiBedah('${row.no_rawat}')">
                                                <span><i class="ti ti-report-medical ${hasSignin ? 'text-success' : 'text-indigo'} me-2"></i> Sign In & Pemantauan Fisiologi</span>
                                                ${hasSignin ? `<span class="badge bg-success text-white rounded-pill ms-2 px-1 py-0" style="font-size: 0.7rem;"><i class="ti ti-check me-1" style="font-size: 0.65rem;"></i>${signinCount}</span>` : ''}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item py-1 d-flex justify-content-between align-items-center ${hasPp ? 'fw-bold text-success' : ''}" href="javascript:void(0)" onclick="bukaPerencanaanPemulangan('${row.no_rawat}')">
                                                <span><i class="ti ti-door-exit ${hasPp ? 'text-success' : 'text-teal'} me-2"></i> Perencanaan Pemulangan</span>
                                                ${hasPp ? `<span class="badge bg-success text-white rounded-pill ms-2 px-1 py-0" style="font-size: 0.7rem;"><i class="ti ti-check me-1" style="font-size: 0.65rem;"></i>${ppCount}</span>` : ''}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>`;
                        },
                    },

                    {
                        title: 'No',
                        data: 'no_reg',
                        name: 'no_reg',
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

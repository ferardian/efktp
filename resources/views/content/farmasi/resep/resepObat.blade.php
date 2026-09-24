@extends('layout')

@section('body')
    <style>
        #tbResepObat .btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            gap: 0.25rem !important;
        }
        #tbResepObat .btn i {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 0 !important;
            font-size: 1.15em !important;
            margin: 0 !important;
        }
        #tbResepObat .btn span {
            display: inline-block !important;
            line-height: 1 !important;
        }

        /* Pill Tabs Style for Filter Status Rawat */
        #tabStatusRawat {
            background-color: #f1f5f9;
            padding: 4px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            display: inline-flex;
            gap: 4px;
        }
        #tabStatusRawat .nav-item {
            display: inline-flex;
        }
        #tabStatusRawat .nav-link {
            border-radius: 6px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            background-color: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        #tabStatusRawat .nav-link:hover {
            color: #0f172a;
            background-color: rgba(255, 255, 255, 0.7);
        }
        #tabStatusRawat .nav-link.active {
            background-color: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
        }
        #tabStatusRawat .nav-link[data-status="semua"].active {
            background-color: #1e293b;
            color: #ffffff;
            border-color: #1e293b;
            box-shadow: 0 2px 4px rgba(30, 41, 59, 0.2);
        }
        #tabStatusRawat .nav-link[data-status="ralan"].active {
            background-color: #0054a6;
            color: #ffffff;
            border-color: #0054a6;
            box-shadow: 0 2px 4px rgba(0, 84, 166, 0.25);
        }
        #tabStatusRawat .nav-link[data-status="ranap"].active {
            background-color: #6f42c1;
            color: #ffffff;
            border-color: #6f42c1;
            box-shadow: 0 2px 4px rgba(111, 66, 193, 0.25);
        }
        #tabStatusRawat .nav-link[data-status="udd"].active {
            background-color: #0ca678;
            color: #ffffff;
            border-color: #0ca678;
            box-shadow: 0 2px 4px rgba(12, 166, 120, 0.25);
        }
        #tabStatusRawat .nav-link i {
            font-size: 1.1em;
        }
    </style>
    <div class="container-fluid h-100">
        <div class="card">
            <div class="card-header py-2 px-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h4 class="card-title m-0 text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-prescription text-primary"></i> Resep & UDD Obat
                    </h4>
                    <ul class="nav nav-pills" id="tabStatusRawat" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-semua" data-status="semua" href="javascript:void(0);">
                                <i class="ti ti-layout-grid"></i><span>Semua Resep</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-ralan" data-status="ralan" href="javascript:void(0);">
                                <i class="ti ti-walk"></i><span>Rawat Jalan</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-ranap" data-status="ranap" href="javascript:void(0);">
                                <i class="ti ti-bed"></i><span>Rawat Inap</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-udd" data-status="udd" href="javascript:void(0);">
                                <i class="ti ti-clock-check"></i><span>Permintaan UDD Ranap</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="table-default" class="table-responsive">
                    <table class="table nowrap table-sm table-striped table-hover" id="tbResepObat" width="100%"></table>
                    <table class="table nowrap table-sm table-striped table-hover d-none" id="tbUddRanap" width="100%"></table>
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
    @include('content.farmasi.resep._modalValidasiResep')
    @include('content.farmasi.resep._modalValidasiUdd')
@endsection
@push('script')
    <script>
        let currentStatusRawat = 'semua';

        $(document).ready(() => {
            const tglAwalResep = $('#tgl_awal').val();
            const tglAkhirResep = $('#tgl_akhir').val();
            reloadActiveTable();

            $('#tabStatusRawat .nav-link').on('click', function (e) {
                e.preventDefault();
                $('#tabStatusRawat .nav-link').removeClass('active');
                $(this).addClass('active');
                currentStatusRawat = $(this).data('status');
                reloadActiveTable();
            });

            setInterval(() => {
                reloadActiveTable();
            }, 30000);
        })

        function reloadActiveTable() {
            const tglAwal = $('#tgl_awal').val();
            const tglAkhir = $('#tgl_akhir').val();
            if (currentStatusRawat === 'udd') {
                $('#tbResepObat').addClass('d-none');
                $('#tbResepObat_wrapper').addClass('d-none');
                $('#tbUddRanap').removeClass('d-none');
                $('#tbUddRanap_wrapper').removeClass('d-none');
                tbUddRanap(tglAwal, tglAkhir);
            } else {
                $('#tbUddRanap').addClass('d-none');
                $('#tbUddRanap_wrapper').addClass('d-none');
                $('#tbResepObat').removeClass('d-none');
                $('#tbResepObat_wrapper').removeClass('d-none');
                tbResepObat(tglAwal, tglAkhir, currentStatusRawat);
            }
        }

        function tbResepObat(tgl_awal = '', tgl_akhir = '', status_rawat = currentStatusRawat) {
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
                    url: `{{ url('/resep/get') }}`,
                    data: {
                        dataTable: true,
                        tgl_awal: tgl_awal,
                        tgl_akhir: tgl_akhir,
                        status_rawat: status_rawat,
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

                        let btnValidasi = '';
                        let btnBatalValidasi = '';
                        if (row.jam === '00:00:00') {
                            displayPanggil = 'd-none';
                            displaySelesai = 'd-none';
                            btnValidasi = `<button class="btn btn-sm btn-warning" onclick="showModalValidasiResep('${data}')"><i class="ti ti-checklist"></i><span>Validasi</span></button>`;
                        } else if (row.jam_penyerahan === '00:00:00') {
                            // Sudah divalidasi, belum diserahkan — tampilkan tombol Batal Validasi
                            btnBatalValidasi = `<button class="btn btn-sm btn-outline-danger" onclick="batalValidasiResep('${data}')"><i class="ti ti-x"></i><span>Batal Validasi</span></button>`;
                        }

                        // Untuk pasien Ranap, sembunyikan tombol Panggil (karena panggil apotek adalah antrian rawat jalan)
                        if (row.status === 'ranap') {
                            displayPanggil = 'd-none';
                        }

                        return `<div class="d-inline-flex align-items-center gap-1 flex-wrap">
                            <button class="btn btn-sm ${colorBtn}" onclick="showDetailResep('${data}')"><i class="ti ti-search"></i><span>Lihat</span></button>
                            ${btnValidasi}
                            ${btnBatalValidasi}
                            <button class="btn btn-sm btn-success ${display} ${displayPanggil}" onclick="panggilResepPasien('${data}', '${row.reg_periksa.pasien.nm_pasien}')"><i class="ti ti-phone"></i><span>Panggil</span></button>
                            <button class="btn btn-sm btn-primary ${display} ${displaySelesai}" onclick="setPenyerahanResep('${data}')"><i class="ti ti-send"></i><span>Selesai</span></button>
                        </div>`;
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
                        title: 'RUANG / POLI',
                        data: 'status',
                        render: (data, type, row, meta) => {
                            if (row.status === 'ranap') {
                                const ki = Array.isArray(row.reg_periksa?.kamar_inap)
                                    ? row.reg_periksa.kamar_inap[0]
                                    : row.reg_periksa?.kamar_inap;
                                const kamar = ki?.kamar;
                                if (kamar) {
                                    const bangsal = kamar.bangsal?.nm_bangsal ?? '';
                                    const kelas = kamar.kelas ? `(${kamar.kelas})` : '';
                                    return `<div><span class="badge bg-purple-lt text-purple me-1"><i class="ti ti-bed"></i> Ranap</span> <strong>${kamar.kd_kamar}</strong> <small class="text-muted d-block">${bangsal} ${kelas}</small></div>`;
                                }
                                return `<span class="badge bg-purple-lt text-purple"><i class="ti ti-bed"></i> Rawat Inap</span>`;
                            } else {
                                const poli = row.reg_periksa?.poliklinik?.nm_poli ?? '-';
                                return `<div><span class="badge bg-blue-lt text-blue me-1"><i class="ti ti-walk"></i> Ralan</span> ${poli}</div>`;
                            }
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

        function tbUddRanap(tgl_awal = '', tgl_akhir = '') {
            const tabel = new DataTable('#tbUddRanap', {
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
                    url: `{{ url('/permintaan-stok-obat/get') }}`,
                    data: {
                        dataTable: true,
                        tgl_awal: tgl_awal,
                        tgl_akhir: tgl_akhir,
                    },
                },
                columns: [
                    {
                        title: '',
                        data: 'no_permintaan',
                        render: (data, type, row, meta) => {
                            let btnValidasi = '';
                            let btnBatalValidasi = '';
                            if (row.status === 'Belum') {
                                btnValidasi = `<button class="btn btn-sm btn-warning" onclick="showModalValidasiUdd('${data}')"><i class="ti ti-checklist"></i><span>Validasi</span></button>`;
                            } else {
                                btnValidasi = `<button class="btn btn-sm btn-info" onclick="showModalValidasiUdd('${data}')"><i class="ti ti-search"></i><span>Lihat</span></button>`;
                                btnBatalValidasi = `<button class="btn btn-sm btn-outline-danger" onclick="batalValidasiUdd('${data}')"><i class="ti ti-x"></i><span>Batal Validasi</span></button>`;
                            }

                            return `<div class="d-inline-flex align-items-center gap-1 flex-wrap">
                                ${btnValidasi}
                                ${btnBatalValidasi}
                            </div>`;
                        },
                    },
                    {
                        title: 'STATUS',
                        data: 'status',
                        render: (data, type, row, meta) => {
                            if (data === 'Sudah') {
                                return `<span class="badge bg-success-lt text-success"><i class="ti ti-check"></i> Sudah Validasi</span>`;
                            }
                            return `<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock"></i> Belum Validasi</span>`;
                        }
                    },
                    {
                        title: 'NO PERMINTAAN',
                        data: 'no_permintaan',
                        render: (data, type, row, meta) => {
                            return `<strong>${data}</strong>`;
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
                        title: 'PASIEN',
                        data: 'nm_pasien',
                        render: (data, type, row, meta) => {
                            return `${row.no_rkm_medis} - ${data}`;
                        },
                    },
                    {
                        title: 'RUANG / KAMAR',
                        data: 'kd_kamar',
                        render: (data, type, row, meta) => {
                            const kamar = data ? data : '-';
                            const bangsal = row.nm_bangsal || '';
                            const kelas = row.kelas ? `(${row.kelas})` : '';
                            return `<div><span class="badge bg-purple-lt text-purple me-1"><i class="ti ti-bed"></i> Ranap</span> <strong>${kamar}</strong> <small class="text-muted d-block">${bangsal} ${kelas}</small></div>`;
                        },
                    },
                    {
                        title: 'DOKTER DPJP',
                        data: 'nm_dokter',
                        render: (data, type, row, meta) => {
                            return data || row.kd_dokter;
                        },
                    },
                    {
                        title: 'RINCIAN OBAT (UDD)',
                        data: 'items',
                        render: (data, type, row, meta) => {
                            if (!data || !data.length) {
                                return `<span class="text-muted">-</span>`;
                            }
                            const itemsList = data.map(it => {
                                let jamList = [];
                                for (let i = 0; i < 24; i++) {
                                    const col = 'jam' + String(i).padStart(2, '0');
                                    if (it[col] === 'true') {
                                        jamList.push(String(i).padStart(2, '0') + ':00');
                                    }
                                }
                                const jamBadges = jamList.length ? ` <span class="badge bg-blue-lt px-1 py-0" style="font-size:0.7rem;">${jamList.join(', ')}</span>` : '';
                                const aturan = it.aturan_pakai ? ` <small class="text-muted">(${it.aturan_pakai})</small>` : '';
                                return `<div>• <strong>${it.nama_brng}</strong> <span class="badge bg-secondary-lt">${it.jml} ${it.satuan || ''}</span>${aturan}${jamBadges}</div>`;
                            }).join('');
                            return `<div class="small">${itemsList}</div>`;
                        }
                    },
                    {
                        title: 'WAKTU PERMINTAAN',
                        data: 'tgl_permintaan',
                        render: (data, type, row, meta) => {
                            return `${isAvaliableDate(data)} ${isAvailableTime(row.jam)}`;
                        },
                    },
                    {
                        title: 'WAKTU VALIDASI',
                        data: 'tgl_validasi',
                        render: (data, type, row, meta) => {
                            return `${isAvaliableDate(data)} ${isAvailableTime(row.jam_validasi)}`;
                        },
                    },
                ],
                initComplete: () => {
                    showToast('Memuat permintaan UDD Ranap');
                }
            });
        }

        $('#btnFilterTanggal').on('click', (e) => {
            tgl_awal = $('#tgl_awal').val();
            tgl_akhir = $('#tgl_akhir').val();

            localStorage.setItem('tglAwalResep', tgl_awal);
            localStorage.setItem('tglAkhirResep', tgl_akhir);

            reloadActiveTable();
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
            $.get(`{{ url('/farmasi/resep/get') }}`, {
                no_resep: no_resep
            }).done((response) => {
                if (response.resep_dokter.length || response.resep_racikan.length) {
                    const resepDokter = response.resep_dokter.map((item, index) => {
                        const namaHtml = typeof formatNamaObatWithGolongan === 'function'
                            ? formatNamaObatWithGolongan(item.obat.nama_brng, item.obat.golongan, item.obat.kode_golongan)
                            : item.obat.nama_brng;
                        return `<li>${namaHtml} @${item.jml} ${item.obat.satuan.satuan} S.${item.aturan_pakai}</li>`;
                    }).join('')

                    const resepRacikan = response.resep_racikan.map((item, index) => {
                        const detail = item.detail.map((subItem) => {
                            const subNamaHtml = typeof formatNamaObatWithGolongan === 'function'
                                ? formatNamaObatWithGolongan(subItem.obat.nama_brng, subItem.obat.golongan, subItem.obat.kode_golongan)
                                : subItem.obat.nama_brng;
                            return `<li>${subNamaHtml} @${subItem.jml} ${subItem.obat.satuan.satuan}</li>`;
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
            $.post(`{{ url('/farmasi/resep/set/penyerahan') }}`, {
                no_resep: no_resep
            }).done((response) => {
                localStorage.removeItem('no_resep');
                localStorage.removeItem('nm_pasien');
                localStorage.setItem('panggil', 'done')
                toast('Obat telah selesai');
                reloadActiveTable();
            })
        }

        function batalValidasiResep(no_resep) {
            Swal.fire({
                title: 'Batal Validasi Resep?',
                html: `Apakah Anda yakin ingin <b>membatalkan validasi</b> resep <b>${no_resep}</b>?<br><br>
                       <span class="text-danger">Stok obat akan dikembalikan ke gudang dan jurnal reversal akan dibuat.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-x"></i> Ya, Batalkan Validasi',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Memproses pembatalan validasi...');
                    $.post(`{{ url('/farmasi/resep/batal-validasi') }}`, {
                        no_resep: no_resep,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }).done((response) => {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            reloadActiveTable();
                        });
                    }).fail((xhr) => {
                        Swal.close();
                        const msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan, silakan coba lagi.';
                        Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                    });
                }
            });
        }

        function panggilResepPasien(no_resep, nm_pasien) {
            localStorage.setItem('no_resep', no_resep);
            localStorage.setItem('nm_pasien', nm_pasien);
            localStorage.setItem('panggil', 'yes')
            $.get(`{{ url('/resep/get') }}`, {
                no_resep: no_resep
            }).done((response) => {
                localStorage.setItem('resepPoliklinik', response.reg_periksa.poliklinik.nm_poli);
                localStorage.setItem('resepDokter', response.reg_periksa.dokter.nm_dokter);
            })
        }
    </script>
@endpush

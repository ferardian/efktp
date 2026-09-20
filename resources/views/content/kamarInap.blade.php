@extends('layout')
@push('style')
    <style>
        #tabelKamarInap {
            width: 100% !important;
        }
        #tabelKamarInap thead th {
            text-align: center !important;
        }
        #tabelKamarInap tbody td {
            vertical-align: middle !important;
        }
    </style>
@endpush

@section('body')
    <div class="container-xl h-100">
        <div class="card">
            <div class="card-body">
                <div id="table-default" class="table-responsive">
                    <table class="table table-sm table-striped table-hover nowrap" id="tabelKamarInap" width="100%">
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row d-none-sm d-none-md align-items-center">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="filterType" id="filterBelumPulang" value="Belum Pulang" checked>
                            <label class="btn btn-outline-primary" for="filterBelumPulang">Belum Pulang</label>

                            <input type="radio" class="btn-check" name="filterType" id="filterMasuk" value="Masuk">
                            <label class="btn btn-outline-primary" for="filterMasuk">Masuk</label>

                            <input type="radio" class="btn-check" name="filterType" id="filterPulang" value="Pulang">
                            <label class="btn btn-outline-primary" for="filterPulang">Pulang</label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12" id="dateRangeFilter" style="display: none;">
                        <div class="input-group">
                            <input class="form-control filterTangal" placeholder="Select a date" id="tglAwal"
                                   name="tglAwal" value="{{ date('d-m-Y') }}">
                            <span class="input-group-text">s.d</span>
                            <input class="form-control filterTangal" placeholder="Select a date" id="tglAkhir"
                                   name="tglAkhir" value="{{ date('d-m-Y') }}">
                        </div>
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
    @include('content.kamarInap.cppt.modalCppt')
    @include('content.kamarInap.resume.modalResumeMedis')
    @include('content.kamarInap._modalPulang')
    @include('content.registrasi._modalSuratSakit')
    @include('content.registrasi._modalRiwayat')
    @include('content.laboratorium.modal._modalPermintaanLab')
    @include('content.kamarInap.penilaianAwal._modalPenilaianAwalKeperawatanRanap')
    @include('content.erm._modalInformedConsent')
    @include('content.erm._modalKajianPraBedah')
    @include('content.erm._modalKajianPraAnestesi')
    @include('content.erm._modalMonitoringAnestesi')
    @include('content.erm._modalPerencanaanPemulangan')
    @include('content.erm._modalPemantauanAnestesiBedah')
@endsection
@push('script')
    <script>
        $(document).ready(() => {
            var tglAwal = localStorage.getItem('tglAwalRanap') ? localStorage.getItem('tglAwalRanap') : tanggal;
            var tglAkhir = localStorage.getItem('tglAkhirRanap') ? localStorage.getItem('tglAkhirRanap') : tanggal;
            
            // Always default to Belum Pulang on page load
            var filterType = 'Belum Pulang';

            $('#tglAwal').val(tglAwal)
            $('#tglAkhir').val(tglAkhir)
            
            // Set radio button to Belum Pulang
            $(`input[name="filterType"][value="Belum Pulang"]`).prop('checked', true);

            // Hide date range by default
            toggleDateRange(filterType);

            // Load table with Belum Pulang filter
            loadTbKamarInap('', '', 'Belum Pulang');
        })

        // Handle filter type change
        $('input[name="filterType"]').on('change', function() {
            const filterType = $(this).val();
            toggleDateRange(filterType);
            // Don't save to localStorage - always reset to Belum Pulang on page load
        });

        function toggleDateRange(filterType) {
            if (filterType === 'Belum Pulang') {
                $('#dateRangeFilter').hide();
            } else {
                $('#dateRangeFilter').show();
            }
        }

        function getCpptRanap(no_rawat, tgl_perawatan = '', jam_rawat = '') {
            const pemeriksaan = $.get(`{{ url('/pemeriksaan/ranap') }}`, {
                no_rawat: no_rawat,
                tgl_perawatan: tgl_perawatan,
                jam_rawat: jam_rawat,
            })
            return pemeriksaan;
        }


        $('#btnFilterRanap').on('click', () => {
            const filterType = $('input[name="filterType"]:checked').val();
            let tglAwal = '';
            let tglAkhir = '';
            
            if (filterType !== 'Belum Pulang') {
                tglAwal = $('#tglAwal').val();
                tglAkhir = $('#tglAkhir').val();
                localStorage.setItem('tglAwalRanap', tglAwal);
                localStorage.setItem('tglAkhirRanap', tglAkhir);
            }

            loadTbKamarInap(tglAwal, tglAkhir, filterType);
        });


        function loadTbKamarInap(tglAwal = '', tglAkhir = '', stts_pulang = '') {
            const tabelRegistrasi = new DataTable('#tabelKamarInap', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: setTableHeight(),
                scrollX: true,
                autoWidth: true,
                ajax: {
                    url: `{{ url('/kamar/inap/get') }}`,
                    data: {
                        dataTable: true,
                        tglAwal: tglAwal,
                        tglAkhir: tglAkhir,
                        pulang: stts_pulang,
                    },
                },
                drawCallback: function() {
                    this.api().columns.adjust();
                },
                createdRow: (row, data, index) => {
                    $(row).addClass('tableKamarInap')
                        .attr('data-id', data.no_rawat)
                        .attr('data-kd_kamar', data.kd_kamar)
                        .attr('data-tgl_masuk', data.tgl_masuk)
                        .attr('data-jam_masuk', data.jam_masuk)
                        .attr('data-stts_pulang', data.stts_pulang)
                        .attr('data-no_rkm_medis', data.reg_periksa.no_rkm_medis);
                },
                columns: [{
                    title: '',
                    data: 'no_rawat',
                    render: (data, type, row, meta) => {
                        const icCount = Number(row.reg_periksa?.persetujuan_penolakan_tindakan_count || (row.reg_periksa?.persetujuan_penolakan_tindakan ? row.reg_periksa.persetujuan_penolakan_tindakan.length : 0));
                        const pbCount = Number(row.reg_periksa?.penilaian_pre_operasi_count || (row.reg_periksa?.penilaian_pre_operasi ? row.reg_periksa.penilaian_pre_operasi.length : 0));
                        const paCount = Number(row.reg_periksa?.penilaian_pre_anestesi_count || (row.reg_periksa?.penilaian_pre_anestesi ? row.reg_periksa.penilaian_pre_anestesi.length : 0));
                        const maCount = Number(row.reg_periksa?.laporan_anestesi_count || (row.reg_periksa?.laporan_anestesi ? row.reg_periksa.laporan_anestesi.length : 0));
                        const signinCount = Number(row.reg_periksa?.bukti_anestesi_signin_count || (row.reg_periksa?.bukti_anestesi_signin ? row.reg_periksa.bukti_anestesi_signin.length : 0));
                        const ppCount = Number(row.reg_periksa?.perencanaan_pemulangan_count || (row.reg_periksa?.perencanaan_pemulangan ? row.reg_periksa.perencanaan_pemulangan.length : 0));

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

                        let btn = `<div class="d-flex align-items-center gap-1">
                                    <button class="btn btn-success btn-sm" type="button" onclick="cpptRanap('${data}')" title="CPPT"><i class="ti ti-pencil"></i></button>
                                    <button class="btn btn-primary btn-sm" type="button" onclick="riwayat('${row.reg_periksa.no_rkm_medis}')" title="Riwayat Perawatan"><i class="ti ti-folder-open"></i></button>
                                    <div class="dropdown">
                                        <button class="btn btn-sm ${btnErmClass} dropdown-toggle px-2 d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu ERM & Dokumen Klinis ${hasErm ? '(' + totalErmCount + ' Dokumen)' : ''}">
                                            <i class="ti ti-notes"></i>${badgeBtn}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="z-index: 1055;">
                                            <li><h6 class="dropdown-header text-uppercase py-1"><i class="ti ti-heart-rate-monitor me-1"></i> Form Medis & ERM</h6></li>
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
                                            <li><a class="dropdown-item py-1" href="javascript:void(0)" onclick="penilaianAwalKeperawatanRanap('${row.no_rawat}')"><i class="ti ti-clipboard-check text-success me-2"></i> Kajian Awal Keperawatan</a></li>
                                        </ul>
                                    </div>
                                </div>`;
                        
                        return btn;
                    }
                },
                    {
                        title: 'No. Rawat',
                        data: 'no_rawat',
                        render: (data, type, row, meta) => {
                            if (!row.reg_periksa.pasien) {
                                alertErrorAjax({
                                    title: 'Error',
                                    status: 404,
                                    statusText: `Gagal memuat pasien ${row.no_rawat} dengan No. RM ${row.reg_periksa.no_rkm_medis}, periksa kembali data registrasi`
                                })
                            }
                            return data;
                        }
                    },
                    {
                        title: 'No. RM',
                        data: 'reg_periksa.no_rkm_medis',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Nama',
                        data: 'reg_periksa.pasien.nm_pasien',
                        render: (data, type, row, meta) => {
                            return `${data} (${row.reg_periksa.pasien.jk})`;
                        }
                    },
                    {
                        title: 'Umur',
                        data: 'reg_periksa',
                        render: (data, type, row, meta) => {
                            return `${data.umurdaftar} ${data.sttsumur}`;
                        }
                    },
                    {
                        title: 'Dokter',
                        data: 'reg_periksa.dokter.nm_dokter',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Dx Awal',
                        data: 'diagnosa_awal',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Dx Akhir',
                        data: 'diagnosa_akhir',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Kamar',
                        data: 'kamar.bangsal.nm_bangsal',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },

                    {
                        title: 'Lama',
                        data: 'lama',
                        render: (data, type, row, meta) => {
                            return `${data} Hari`;
                        }
                    },
                    {
                        title: 'Asuransi',
                        data: 'reg_periksa.penjab.png_jawab',
                        render: (data, type, row, meta) => {
                            return setTextPenjab(data);
                        }
                    },
                    {
                        title: 'Status',
                        data: 'stts_pulang',
                        render: (data, type, row, meta) => {
                            return data;
                        }
                    },
                    {
                        title: 'Tgl. Masuk',
                        data: 'tgl_masuk',
                        render: (data, type, row, meta) => {
                            return `${splitTanggal(data)} ${row.jam_masuk}`;
                        }
                    },
                    {
                        title: 'Tgl. Keluar',
                        data: 'tgl_keluar',
                        render: (data, type, row, meta) => {
                            return `${splitTanggal(data)} ${row.jam_keluar}`;
                        }
                    },

                ]
            })
        }
    </script>
@endpush

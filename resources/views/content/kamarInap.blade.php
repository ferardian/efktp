@extends('layout')

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
                        <select class="form-select" id="selectPulang">
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
    @include('content.kamarInap.cppt.modalCppt')
    @include('content.kamarInap.resume.modalResumeMedis')
    @include('content.registrasi._modalSuratSakit')
    @include('content.registrasi._modalRiwayat')
    @include('content.laboratorium.modal._modalPermintaanLab')
@endsection
@push('script')
    <script>
        $(document).ready(() => {
            loadTbKamarInap();
            var tglAwal = localStorage.getItem('tglAwalRanap') ? localStorage.getItem('tglAwalRanap') : tanggal;
            var tglAkhir = localStorage.getItem('tglAkhirRanap') ? localStorage.getItem('tglAkhirRanap') : tanggal;

            $('#tglAwal').val(tglAwal)
            $('#tglAkhir').val(tglAkhir)

            loadTbKamarInap(tglAwal, tglAkhir)


        })

        function getCpptRanap(no_rawat, tgl_perawatan = '', jam_rawat = '') {
            const pemeriksaan = $.get(`/efktp/pemeriksaan/ranap`, {
                no_rawat: no_rawat,
                tgl_perawatan: tgl_perawatan,
                jam_rawat: jam_rawat,
            })
            return pemeriksaan;
        }


        $('#btnFilterRanap').on('click', () => {
            tglAwal = $('#tglAwal').val();
            tglAkhir = $('#tglAkhir').val();
            pulang = $('#selectPulang').val();

            loadTbKamarInap(tglAwal, tglAkhir, pulang);

            localStorage.setItem('tglAwalRanap', tglAwal);
            localStorage.setItem('tglAkhirRanap', tglAkhir);
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
                ajax: {
                    url: `/efktp/kamar/inap/get`,
                    data: {
                        dataTable: true,
                        tglAwal: tglAwal,
                        tglAkhir: tglAkhir,
                        pulang: stts_pulang,
                    },
                },
                createdRow: (row, data, index) => {
                    $(row).addClass('tableKamarInap')
                        .attr('data-id', data.no_rawat)
                        .attr('data-no_rawat', data.kd_poli)
                        .attr('data-no_rkm_medis', data.reg_periksa.no_rkm_medis);
                },
                columns: [{
                    title: '',
                    data: 'no_rawat',
                    render: (data, type, row, meta) => {
                        return `<button class="btn btn-success btn-sm" type="button" onclick="cpptRanap('${data}')"><i class="ti ti-pencil"></i></button>
                                    <button class="btn btn-primary btn-sm"><i class="ti ti-list"></i></button>`;
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

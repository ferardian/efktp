@extends('layout')

@section('body')
    <div class="container-fluid h-100">
        <div class="row row-cards">
            <!-- Filter Card -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header py-2 bg-light">
                        <h3 class="card-title text-primary"><i class="ti ti-filter me-1"></i> Filter Rekapitulasi Resep Obat</h3>
                    </div>
                    <div class="card-body py-3">
                        <form id="formFilterRekap">
                            <div class="row">
                                <!-- Row 1 -->
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <label class="form-label font-weight-medium text-muted">Tanggal Awal</label>
                                    <div class="input-group input-group-flat">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar text-muted"></i></span>
                                        <input type="text" class="form-control filterTangal border-start-0" id="tgl_awal" name="tgl_awal" value="{{ date('d-m-Y') }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <label class="form-label font-weight-medium text-muted">Tanggal Akhir</label>
                                    <div class="input-group input-group-flat">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar text-muted"></i></span>
                                        <input type="text" class="form-control filterTangal border-start-0" id="tgl_akhir" name="tgl_akhir" value="{{ date('d-m-Y') }}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <label class="form-label font-weight-medium text-muted">Poliklinik</label>
                                    <select class="form-select form-select-2" id="kd_poli" name="kd_poli">
                                        <option value="">- Semua Poliklinik -</option>
                                        @foreach ($poliklinik as $poli)
                                            <option value="{{ $poli->kd_poli }}">{{ $poli->nm_poli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <label class="form-label font-weight-medium text-muted">Dokter</label>
                                    <select class="form-select form-select-2" id="kd_dokter" name="kd_dokter">
                                        <option value="">- Semua Dokter -</option>
                                        @foreach ($dokter as $dr)
                                            <option value="{{ $dr->kd_dokter }}">{{ $dr->nm_dokter }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Row 2 -->
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <label class="form-label font-weight-medium text-muted">Status Validasi</label>
                                    <select class="form-select" id="status_validasi" name="status_validasi">
                                        <option value="semua">Semua Resep</option>
                                        <option value="belum">Belum Validasi</option>
                                        <option value="sudah">Sudah Validasi</option>
                                    </select>
                                </div>
                                <div class="col-md-9 col-sm-6 mb-3 d-flex align-items-end justify-content-start gap-2">
                                    <button type="button" class="btn btn-primary" id="btnFilter" style="height: 35px; min-width: 140px;">
                                        <i class="ti ti-search me-1"></i> Tampilkan Data
                                    </button>
                                    <button type="button" class="btn btn-success" id="btnExportExcel" style="height: 35px;">
                                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                                    </button>
                                    <button type="button" class="btn btn-danger" id="btnExportPdf" style="height: 35px;">
                                        <i class="ti ti-file-text me-1"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table nowrap table-sm table-striped table-hover align-middle" id="tbRekapResep" width="100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi select2
            $('.form-select-2').select2({
                width: '100%'
            });

            loadRekapResep();

            $('#btnFilter').on('click', function() {
                loadRekapResep();
            });

            // Action Export Excel
            $('#btnExportExcel').on('click', function() {
                const tgl_awal = $('#tgl_awal').val();
                const tgl_akhir = $('#tgl_akhir').val();
                const kd_poli = $('#kd_poli').val();
                const kd_dokter = $('#kd_dokter').val();
                const status_validasi = $('#status_validasi').val();

                Swal.fire({
                    title: 'Memproses Data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.get(`{{ url('/farmasi/resep/rekap/data') }}`, {
                    tgl_awal: tgl_awal,
                    tgl_akhir: tgl_akhir,
                    kd_poli: kd_poli,
                    kd_dokter: kd_dokter,
                    status_validasi: status_validasi,
                    length: -1
                }).done(function(response) {
                    Swal.close();
                    const data = response.data;
                    if (!data || data.length === 0) {
                        Swal.fire('Informasi', 'Tidak ada data untuk diekspor', 'info');
                        return;
                    }

                    const poliName = $('#kd_poli option:selected').text();
                    const dokterName = $('#kd_dokter option:selected').text();
                    const statusName = $('#status_validasi option:selected').text();

                    const wsData = [
                        ["REKAPITULASI RESEP OBAT"],
                        [],
                        ["Periode", `: ${tgl_awal} s.d ${tgl_akhir}`],
                        ["Poliklinik", `: ${poliName}`],
                        ["Dokter", `: ${dokterName}`],
                        ["Status Validasi", `: ${statusName}`],
                        [],
                        ["No", "Kode Obat", "Nama Obat", "Satuan", "Total Qty"]
                    ];

                    data.forEach((item, index) => {
                        wsData.push([
                            index + 1,
                            item.kode_brng,
                            item.nama_brng,
                            item.satuan ? item.satuan : '-',
                            parseFloat(item.total_qty)
                        ]);
                    });

                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.aoa_to_sheet(wsData);

                    // Auto-adjust column widths
                    const max_cols = wsData[wsData.length - 1].length;
                    const wscols = [];
                    for (let i = 0; i < max_cols; i++) {
                        let max_len = 10;
                        wsData.forEach(row => {
                            if (row[i] !== undefined && row[i] !== null) {
                                max_len = Math.max(max_len, row[i].toString().length);
                            }
                        });
                        wscols.push({ wch: max_len + 2 });
                    }
                    ws['!cols'] = wscols;

                    XLSX.utils.book_append_sheet(wb, ws, "Rekap Resep");
                    XLSX.writeFile(wb, `Rekap_Resep_Obat_${tgl_awal}_sd_${tgl_akhir}.xlsx`);
                }).fail(function() {
                    Swal.close();
                    Swal.fire('Error', 'Gagal memproses data ekspor', 'error');
                });
            });

            // Action Export PDF
            $('#btnExportPdf').on('click', function() {
                const tgl_awal = $('#tgl_awal').val();
                const tgl_akhir = $('#tgl_akhir').val();
                const kd_poli = $('#kd_poli').val();
                const kd_dokter = $('#kd_dokter').val();
                const status_validasi = $('#status_validasi').val();

                const query = $.param({
                    tgl_awal: tgl_awal,
                    tgl_akhir: tgl_akhir,
                    kd_poli: kd_poli,
                    kd_dokter: kd_dokter,
                    status_validasi: status_validasi
                });

                window.open(`{{ url('/farmasi/resep/rekap/pdf') }}?${query}`, '_blank');
            });
        });

        function loadRekapResep() {
            const tgl_awal = $('#tgl_awal').val();
            const tgl_akhir = $('#tgl_akhir').val();
            const kd_poli = $('#kd_poli').val();
            const kd_dokter = $('#kd_dokter').val();
            const status_validasi = $('#status_validasi').val();

            $('#tbRekapResep').DataTable({
                responsive: true,
                autoWidth: true,
                serverSide: true,
                destroy: true,
                processing: true,
                scrollY: '450px',
                scrollX: true,
                pageLength: 50,
                ajax: {
                    url: `{{ url('/farmasi/resep/rekap/data') }}`,
                    data: {
                        tgl_awal: tgl_awal,
                        tgl_akhir: tgl_akhir,
                        kd_poli: kd_poli,
                        kd_dokter: kd_dokter,
                        status_validasi: status_validasi,
                    },
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: 'No',
                        orderable: false,
                        searchable: false,
                        width: '5%',
                        className: 'text-center'
                    },
                    {
                        data: 'kode_brng',
                        name: 'kode_brng',
                        title: 'Kode Obat',
                        width: '15%',
                        className: 'text-start',
                        render: function(data) {
                            return `<code class="text-secondary font-weight-medium" style="font-size: 11px; padding: 2px 4px; background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 3px;">${data}</code>`;
                        }
                    },
                    {
                        data: 'nama_brng',
                        name: 'nama_brng',
                        title: 'Nama Obat',
                        width: '50%',
                        className: 'text-start',
                        render: function(data) {
                            return `<span class="font-weight-bold text-dark" style="font-size: 11.5px;">${data}</span>`;
                        }
                    },
                    {
                        data: 'satuan',
                        name: 'satuan',
                        title: 'Satuan',
                        width: '15%',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge bg-gray-100 text-secondary py-1 px-2 border" style="font-size: 10px; font-weight: 500;">${data ? data : '-'}</span>`;
                        }
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty',
                        title: 'Total Qty',
                        width: '15%',
                        className: 'text-end',
                        render: function(data, type, row) {
                            let val = parseFloat(data);
                            let formattedVal = Number.isInteger(val) ? val : val.toFixed(2);
                            return `<span class="badge bg-blue-lt px-3 py-1 font-weight-bold" style="font-size: 11.5px; border-radius: 4px;">${formattedVal}</span>`;
                        }
                    }
                ]
            });
        }
    </script>
@endpush

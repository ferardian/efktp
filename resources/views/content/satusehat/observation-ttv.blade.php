@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sinkronisasi Observation TTV - Satu Sehat</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 d-flex align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" id="tgl_awal" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tgl_akhir" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" id="btn-filter">
                                    <i class="ti ti-search"></i> Tampilkan
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-vcenter card-table" id="table-ttv">
                                <thead>
                                    <tr>
                                        <th>No. Rawat</th>
                                        <th>Tanggal/Jam</th>
                                        <th>Pasien</th>
                                        <th>Suhu</th>
                                        <th>Tensi</th>
                                        <th>Nadi</th>
                                        <th>Resp</th>
                                        <th>TB/BB</th>
                                        <th>SpO2</th>
                                        <th>GCS</th>
                                        <th>LP</th>
                                        <th>Petugas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="13" class="text-center">Silakan klik Tampilkan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#btn-filter').click(function() {
                loadData();
            });

            function loadData() {
                const tgl_awal = $('#tgl_awal').val();
                const tgl_akhir = $('#tgl_akhir').val();

                $('#table-ttv tbody').html('<tr><td colspan="13" class="text-center">Memuat data...</td></tr>');

                $.get("{{ route('satusehat.observation-ttv.data') }}", {
                    tgl_awal: tgl_awal,
                    tgl_akhir: tgl_akhir
                }, function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            const badge = (id) => id ? `<span class="badge bg-success" title="${id}">Synced</span>` : '<span class="badge bg-secondary">No</span>';
                            
                            html += `<tr>
                                <td>${item.no_rawat}</td>
                                <td>${item.tgl_perawatan}<br><small>${item.jam_rawat}</small></td>
                                <td>${item.nm_pasien}<br><small class="text-muted">${item.ktp_pasien || '-'}</small></td>
                                <td class="text-center">${item.suhu_tubuh || '-'}<br>${badge(item.id_suhu)}</td>
                                <td class="text-center">${item.tensi || '-'}<br>${badge(item.id_tensi)}</td>
                                <td class="text-center">${item.nadi || '-'}<br>${badge(item.id_nadi)}</td>
                                <td class="text-center">${item.respirasi || '-'}<br>${badge(item.id_respirasi)}</td>
                                <td class="text-center">${item.tinggi || '-'}/${item.berat || '-'}<br>${badge(item.id_tb)} ${badge(item.id_bb)}</td>
                                <td class="text-center">${item.spo2 || '-'}<br>${badge(item.id_spo2)}</td>
                                <td class="text-center">${item.gcs || '-'}<br>${badge(item.id_gcs)}</td>
                                <td class="text-center">${item.lingkar_perut || '-'}<br>${badge(item.id_lp)}</td>
                                <td>${item.nama_petugas}<br><small class="text-muted">${item.ktp_petugas || '-'}</small></td>
                                <td>
                                    ${item.id_encounter ? 
                                        `<button class="btn btn-sm btn-info btn-sync" data-no="${item.no_rawat}" data-tgl="${item.tgl_perawatan}" data-jam="${item.jam_rawat}">
                                            <i class="ti ti-refresh"></i> Sync All
                                        </button>` : 
                                        '<span class="text-danger">Encounter Belum Sync</span>'
                                    }
                                </td>
                            </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="13" class="text-center">Data tidak ditemukan</td></tr>';
                    }
                    $('#table-ttv tbody').html(html);
                });
            }

            $(document).on('click', '.btn-sync', function() {
                const btn = $(this);
                const no_rawat = btn.data('no');
                const tgl = btn.data('tgl');
                const jam = btn.data('jam');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.post("{{ route('satusehat.observation-ttv.sync') }}", {
                    _token: "{{ csrf_token() }}",
                    no_rawat: no_rawat,
                    tgl_perawatan: tgl,
                    jam_rawat: jam
                }, function(res) {
                    if (res.status) {
                        Swal.fire('Sukses', res.message, 'success');
                        loadData();
                    } else {
                        Swal.fire('Eror', res.message, 'error');
                        btn.prop('disabled', false).html('<i class="ti ti-refresh"></i> Sync All');
                    }
                }).fail(function(err) {
                    Swal.fire('Eror', 'Terjadi kesalahan sistem', 'error');
                    btn.prop('disabled', false).html('<i class="ti ti-refresh"></i> Sync All');
                });
            });
        });
    </script>
@endpush

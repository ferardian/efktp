@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Satu Sehat - Condition (Diagnosa)</h4>
                        <div class="d-flex gap-2">
                            <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                            <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                            <button class="btn btn-primary" id="btn-filter">Filter</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table-condition">
                                <thead>
                                    <tr>
                                        <th>No. Rawat</th>
                                        <th>Tgl. Registrasi</th>
                                        <th>Pasien</th>
                                        <th>Kode Penyakit</th>
                                        <th>Nama Penyakit</th>
                                        <th>Status Diagnosa</th>
                                        <th>ID Encounter</th>
                                        <th>ID Condition</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
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
            function loadData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                $.ajax({
                    url: '/satusehat/condition/data',
                    type: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        if (response.status) {
                            let html = '';
                            response.data.forEach(item => {
                                html += `
                                    <tr>
                                        <td>${item.no_rawat}</td>
                                        <td>${item.tgl_registrasi}</td>
                                        <td>
                                            ${item.nm_pasien}<br>
                                            <small class="text-muted">${item.ktp_pasien || '-'}</small>
                                        </td>
                                        <td>${item.kd_penyakit}</td>
                                        <td>${item.nm_penyakit}</td>
                                        <td>${item.status_diagnosa}</td>
                                        <td>
                                            ${item.id_encounter ? `<span class="badge bg-success">${item.id_encounter}</span>` : '<span class="badge bg-danger">Belum Sync</span>'}
                                        </td>
                                        <td>
                                            ${item.id_condition ? `<span class="badge bg-success">${item.id_condition}</span>` : '<span class="badge bg-danger">Belum Sync</span>'}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-sync" 
                                                data-no-rawat="${item.no_rawat}"
                                                data-kd-penyakit="${item.kd_penyakit}"
                                                data-status-diagnosa="${item.status_diagnosa}"
                                                ${!item.id_encounter ? 'disabled' : ''}>
                                                <i class="fas fa-sync"></i> Sync
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                            $('#table-condition tbody').html(html);
                        }
                    }
                });
            }

            loadData();

            $('#btn-filter').on('click', function() {
                loadData();
            });

            $(document).on('click', '.btn-sync', function() {
                const btn = $(this);
                const noRawat = btn.data('no-rawat');
                const kdPenyakit = btn.data('kd-penyakit');
                const statusDiagnosa = btn.data('status-diagnosa');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                $.ajax({
                    url: '/satusehat/condition/sync',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        no_rawat: noRawat,
                        kd_penyakit: kdPenyakit,
                        status: statusDiagnosa
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire('Sukses', response.message, 'success');
                            loadData();
                        } else {
                            Swal.fire('Eror', response.message, 'error');
                            btn.prop('disabled', false).html('<i class="fas fa-sync"></i> Sync');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Eror', 'Terjadi kesalahan pada server', 'error');
                        btn.prop('disabled', false).html('<i class="fas fa-sync"></i> Sync');
                    }
                });
            });
        });
    </script>
@endpush

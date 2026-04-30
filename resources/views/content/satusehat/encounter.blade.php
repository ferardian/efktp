@extends('layout')

@section('body')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Satu Sehat Encounter
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <div class="row g-2 align-items-center w-100">
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="start-date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="end-date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="search" placeholder="Cari No.Rawat / Nama Pasien...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btn-cari">
                                <i class="ti ti-search me-2"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="tableEncounter">
                        <thead>
                            <tr>
                                <th>No.Rawat</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Unit/Poli</th>
                                <th>ID Encounter</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-encounter">
                            <tr>
                                <td colspan="8" class="text-center">Silakan klik cari untuk memuat data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#btn-cari').on('click', function() {
                loadEncounterData();
            });

            $('#search').on('keypress', function(e) {
                if (e.which == 13) loadEncounterData();
            });
        });

        function loadEncounterData() {
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();
            const search = $('#search').val();

            $('#tbody-encounter').html('<tr><td colspan="8" class="text-center">Memuat data...</td></tr>');

            $.get("{{ url('satusehat/encounter/data') }}", {
                start_date: startDate,
                end_date: endDate,
                search: search
            }, function(response) {
                if (response.status) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>';
                    } else {
                        response.data.forEach(item => {
                            const badge = item.id_encounter 
                                ? '<span class="badge bg-success">Terkirim</span>' 
                                : '<span class="badge bg-warning">Belum Terkirim</span>';
                            
                            const btnSync = `<button class="btn btn-sm btn-outline-primary" onclick="syncEncounter('${item.no_rawat}')">
                                <i class="ti ti-refresh me-1"></i> Sync
                            </button>`;

                            html += `
                                <tr>
                                    <td>${item.no_rawat}</td>
                                    <td>${item.tgl_registrasi} ${item.jam_reg}</td>
                                    <td>
                                        <strong>${item.nm_pasien}</strong><br>
                                        <small class="text-muted">NIK: ${item.ktp_pasien || '-'}</small>
                                    </td>
                                    <td>
                                        ${item.nama_dokter}<br>
                                        <small class="text-muted">NIK: ${item.ktp_dokter || '-'}</small>
                                    </td>
                                    <td>
                                        ${item.nm_poli}<br>
                                        <small class="text-muted">Loc: ${item.id_lokasi_satusehat || '-'}</small>
                                    </td>
                                    <td>${item.id_encounter || '-'}</td>
                                    <td>${badge}</td>
                                    <td>${btnSync}</td>
                                </tr>
                            `;
                        });
                    }
                    $('#tbody-encounter').html(html);
                }
            });
        }

        function syncEncounter(noRawat) {
            Swal.fire({
                title: 'Sync Encounter?',
                text: "Kirim data kunjungan ini ke Satu Sehat",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim!',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.post("{{ url('satusehat/encounter/sync') }}", {
                        _token: "{{ csrf_token() }}",
                        no_rawat: noRawat
                    }).then(response => {
                        if (!response.status) {
                            throw new Error(response.message || 'Gagal sinkronisasi');
                        }
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(`Error: ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Berhasil!', 'Data Encounter telah disinkronkan.', 'success');
                    loadEncounterData();
                }
            });
        }
    </script>
@endpush

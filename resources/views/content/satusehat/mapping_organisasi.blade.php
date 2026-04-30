@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Mapping Organisasi Satu Sehat
                    </h2>
                    <div class="text-muted mt-1">Sinkronisasi Departemen SIMRS ke ID Organisasi Satu Sehat</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="tableMappingOrganisasi">
                        <thead>
                            <tr>
                                <th>Kode Dept</th>
                                <th>Nama Departemen</th>
                                <th>ID Organisasi Satu Sehat</th>
                                <th class="w-1">Status</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyMappingOrganisasi">
                            <tr>
                                <td colspan="5" class="text-center">Memuat data...</td>
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
            loadData();
        });

        function loadData() {
            $.get("{{ url('satusehat/mapping/organisasi/data') }}", function(response) {
                if (response.status) {
                    let html = '';
                    response.data.forEach(item => {
                        let statusBadge = item.id_organisasi_satusehat 
                            ? '<span class="badge bg-success">Termapping</span>' 
                            : '<span class="badge bg-warning">Belum Mapping</span>';
                        
                        html += `
                            <tr>
                                <td>${item.dep_id}</td>
                                <td>${item.nama}</td>
                                <td class="text-muted">${item.id_organisasi_satusehat || '-'}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="syncMapping('${item.dep_id}')">
                                        <i class="ti ti-refresh me-1"></i> Sync
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tbodyMappingOrganisasi').html(html);
                }
            });
        }

        function syncMapping(dep_id) {
            Swal.fire({
                title: 'Sinkronisasi ke Satu Sehat?',
                text: "Data departemen akan dikirim ke server Satu Sehat Kemenkes.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sinkronkan!',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.post("{{ url('satusehat/mapping/organisasi') }}", {
                        dep_id: dep_id
                    }).then(response => {
                        if (!response.status) {
                            throw new Error(response.message || 'Gagal sinkronisasi');
                        }
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Berhasil!',
                        'Data berhasil disinkronkan ke Satu Sehat.',
                        'success'
                    );
                    loadData();
                }
            });
        }
    </script>
@endpush

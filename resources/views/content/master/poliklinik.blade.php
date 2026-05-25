@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-3">
            <!-- Left Side: Table List -->
            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h4 class="card-title mb-0">Master Poliklinik</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Status</label>
                                <select class="form-select form-select-sm" id="filter_status" onchange="$('#tbPoli').DataTable().ajax.reload()">
                                    <option value="">Semua</option>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="d-flex gap-2 mb-3 bg-light p-2 rounded-2 align-items-center">
                            <span class="small fw-semibold text-muted ms-1"><i class="ti ti-settings me-1"></i> Aksi Massal:</span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkDeletePoli()">
                                <i class="ti ti-trash me-1"></i> Non-aktifkan Terpilih
                            </button>
                            <button type="button" class="btn btn-sm btn-danger ms-auto" onclick="deactivateAllPoli()">
                                <i class="ti ti-alert-triangle me-1"></i> Non-aktifkan Semua
                            </button>
                        </div>

                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbPoli">
                                <thead>
                                    <tr>
                                        <th width="30px"><input type="checkbox" class="form-check-input" id="checkAllPoli"></th>
                                        <th>Kode</th>
                                        <th>Nama Poliklinik</th>
                                        <th>Registrasi Baru</th>
                                        <th>Registrasi Lama</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary shadow-sm" id="btnCreatePoli" onclick="resetFormPoli()">
                                    <i class="ti ti-plus me-2"></i> Tambah Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="col-lg-5 col-xl-5 col-md-12 col-sm-12">
                <form id="formPoli">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3" id="formTitle">Form Data Poliklinik</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Kode Poliklinik</label>
                                <input type="text" class="form-control" id="kd_poli" name="kd_poli" placeholder="Contoh: UMU, GIG, KIA" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Poliklinik</label>
                                <input type="text" class="form-control" id="nm_poli" name="nm_poli" placeholder="Contoh: Poli Umum" required>
                            </div>

                            <div class="mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Registrasi Baru (IDR)</label>
                                        <input type="number" class="form-control" id="registrasi" name="registrasi" value="0" min="0" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Registrasi Lama (IDR)</label>
                                        <input type="number" class="form-control" id="registrasilama" name="registrasilama" value="0" min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Poliklinik</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>

                            <div class="mb-2 mt-4">
                                <button type="button" class="btn btn-success w-100 shadow-sm" id="btnSimpanPoli" onclick="simpanPoli()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Poliklinik
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(() => {
            renderTablePoli();
        });

        // Format currency helper
        function formatRupiah(number) {
            const val = parseFloat(number);
            if (isNaN(val)) return 'Rp0';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
        }

        function renderTablePoli() {
            $('#tbPoli').DataTable({
                responsive: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/poliklinik/data') }}",
                    type: "GET",
                    data: function(d) {
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        width: '30px',
                        render: (data, type, row) => {
                            return `<input type="checkbox" class="form-check-input row-select" value="${row.kd_poli}">`;
                        }
                    },
                    { data: 'kd_poli', name: 'kd_poli' },
                    { data: 'nm_poli', name: 'nm_poli' },
                    { 
                        data: 'registrasi', 
                        name: 'registrasi',
                        render: (data) => formatRupiah(data)
                    },
                    { 
                        data: 'registrasilama', 
                        name: 'registrasilama',
                        render: (data) => formatRupiah(data)
                    },
                    { 
                        data: 'status', 
                        name: 'status',
                        render: (data) => data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (data) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-icon btn-warning text-white" onclick="editPoli('${data.kd_poli}')" title="Edit Poliklinik">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-danger" onclick="deletePoli('${data.kd_poli}', '${data.nm_poli}')" title="Hapus Poliklinik">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        }

        function resetFormPoli() {
            $('#formPoli').trigger('reset');
            $('#kd_poli').prop('readonly', false);
            $('#formTitle').text('Form Data Poliklinik');
            $('#btnSimpanPoli').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan Poliklinik');
        }

        function simpanPoli() {
            const kd_poli = $('#kd_poli').val();
            const isEdit = $('#formTitle').text().includes('Edit');
            const url = isEdit ? `{{ url('/poliklinik') }}/${kd_poli}` : "{{ url('/poliklinik') }}";
            const method = isEdit ? 'PUT' : 'POST';

            if (!kd_poli || !$('#nm_poli').val()) {
                showToast('Mohon lengkapi seluruh field formulir yang bertanda wajib.', 'warning');
                return;
            }

            loadingAjax('Sedang memproses data poliklinik...');
            
            $.ajax({
                url: url,
                type: method,
                data: $('#formPoli').serialize(),
                success: (response) => {
                    showToast(response.message);
                    renderTablePoli();
                    resetFormPoli();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal memproses data poliklinik', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function editPoli(kd_poli) {
            loadingAjax('Mengambil rincian data poliklinik...');
            $.get("{{ url('/poliklinik/data') }}", { search: { value: kd_poli } }).done((res) => {
                Swal.close();
                if (res.data && res.data.length > 0) {
                    const data = res.data.find(x => x.kd_poli === kd_poli) || res.data[0];
                    $('#kd_poli').val(data.kd_poli).prop('readonly', true);
                    $('#nm_poli').val(data.nm_poli);
                    $('#registrasi').val(data.registrasi);
                    $('#registrasilama').val(data.registrasilama);
                    $('#status').val(data.status);

                    $('#formTitle').text('Edit Data Poliklinik');
                    $('#btnSimpanPoli').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Perbarui Poliklinik');
                } else {
                    showToast('Gagal memuat rincian poliklinik', 'error');
                }
            });
        }

        function deletePoli(kd_poli, nm_poli) {
            Swal.fire({
                title: 'Hapus Poliklinik?',
                html: `Apakah Anda yakin ingin menghapus poliklinik <b>${nm_poli}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus poliklinik...');
                    $.ajax({
                        url: `{{ url('/poliklinik') }}/${kd_poli}`,
                        type: 'DELETE',
                        success: (response) => {
                            showToast(response.message);
                            renderTablePoli();
                        },
                        error: (xhr) => {
                            showToast('Gagal menghapus poliklinik', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

        // Select/deselect all row checkboxes when header checkbox is clicked
        $('#checkAllPoli').on('change', function() {
            $('.row-select').prop('checked', this.checked);
        });

        // If any row checkbox is unchecked, uncheck the header checkbox
        $(document).on('change', '.row-select', function() {
            if ($('.row-select:checked').length === $('.row-select').length && $('.row-select').length > 0) {
                $('#checkAllPoli').prop('checked', true);
            } else {
                $('#checkAllPoli').prop('checked', false);
            }
        });

        function bulkDeletePoli() {
            const selectedIds = [];
            $('.row-select:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showToast('Mohon pilih minimal satu poliklinik untuk dinonaktifkan.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Non-aktifkan Poliklinik Terpilih?',
                html: `Apakah Anda yakin ingin menonaktifkan/menghapus <b>${selectedIds.length}</b> poliklinik terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Memproses tindakan massal...');
                    $.ajax({
                        url: "{{ url('/poliklinik/bulk-delete') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedIds
                        },
                        success: (response) => {
                            showToast(response.message);
                            $('#checkAllPoli').prop('checked', false);
                            $('#tbPoli').DataTable().ajax.reload();
                        },
                        error: (xhr) => {
                            showToast('Gagal memproses tindakan massal', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

        function deactivateAllPoli() {
            Swal.fire({
                title: 'Non-aktifkan Seluruh Poliklinik?',
                html: 'Apakah Anda yakin ingin menonaktifkan <b>seluruh</b> poliklinik aktif di database?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Non-aktifkan Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Memproses penonaktifan semua poliklinik...');
                    $.ajax({
                        url: "{{ url('/poliklinik/deactivate-all') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: (response) => {
                            showToast(response.message);
                            $('#checkAllPoli').prop('checked', false);
                            $('#tbPoli').DataTable().ajax.reload();
                        },
                        error: (xhr) => {
                            showToast('Gagal menonaktifkan seluruh poliklinik', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }
    </script>
@endpush

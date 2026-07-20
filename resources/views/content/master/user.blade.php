@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-2">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbUser">
                                <thead>
                                    <tr>
                                        <th>ID User</th>
                                        <th>Nama Pegawai</th>
                                        <th>Jabatan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary" onclick="resetFormUser()">
                            <i class="ti ti-plus me-2"></i> Tambah User Baru
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12">
                <form id="formUser" autocomplete="off">
                    <div class="card" style="max-height: 85vh; overflow: hidden;">
                        <div class="card-body d-flex flex-column" style="overflow: hidden;">
                            <h5 class="card-title">Form Data User & Hak Akses</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">ID User (NIK)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="username" name="username" placeholder="NIK Pegawai">
                                            <button class="btn btn-outline-primary" type="button" onclick="showLookupPegawai()">
                                                <i class="ti ti-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Password</label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="new-password">
                                            <span class="input-group-text">
                                                <a href="javascript:void(0)" class="link-secondary" id="btnTogglePassword">
                                                    <i class="ti ti-eye" id="iconPassword"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @if(config('app.enable_menu_role'))
                                <div class="col-12 mt-2">
                                    <div class="mb-2">
                                        <label class="form-label">Role Akses</label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="">-- Gunakan Fallback Sistem (Otomatis) --</option>
                                            <option value="admin">Admin</option>
                                            <option value="dokter">Dokter</option>
                                            <option value="apoteker">Apoteker/Farmasi</option>
                                            <option value="petugas">Petugas/Umum</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <hr class="my-2">
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Hak Akses Modul</h6>
                                <div class="input-group input-group-sm w-50">
                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    <input type="text" id="searchPermission" class="form-control" placeholder="Cari hak akses...">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllPermissions(true)">Pilih Semua</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAllPermissions(false)">Batal Semua</button>
                            </div>

                            <div id="permissionContainer" class="flex-grow-1 overflow-auto border rounded p-2 bg-light" style="min-height: 300px;">
                                <div class="row g-2" id="permissionList">
                                    <!-- Dynamic Permissions Loading -->
                                    <div class="col-12 text-center p-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <p class="mt-2 text-muted">Memuat hak akses...</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-success w-100" id="btnSimpanUser" onclick="simpanUser()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Data User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Lookup Pegawai -->
    <div class="modal modal-blur fade" id="modalLookupPegawai" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Dokter / Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" id="keywordLookup" class="form-control" placeholder="Ketik Nama atau NIK...">
                        <button class="btn btn-primary" type="button" onclick="searchLookupPegawai()">
                            <i class="ti ti-search me-1"></i> Cari
                        </button>
                    </div>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover table-sm" id="tableLookupPegawai">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit/Dept</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="listLookupPegawai">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let allColumns = [];

        $(document).ready(() => {
            renderTableUser();
            loadPermissions();

            $('#searchPermission').on('keyup', function() {
                const val = $(this).val().toLowerCase();
                $('#permissionList .permission-item').each(function() {
                    const text = $(this).find('label').text().toLowerCase();
                    const name = $(this).find('input').attr('name').toLowerCase();
                    $(this).toggle(text.includes(val) || name.includes(val));
                });
            });

            $('#btnTogglePassword').on('click', function() {
                togglePassword();
            });
        });

        function renderTableUser() {
            $('#tbUser').DataTable({
                responsive: true,
                serverSide: false,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/user/data') }}",
                    dataSrc: ""
                },
                columns: [
                    { data: 'username' },
                    { data: 'nama' },
                    { data: 'jabatan' },
                    {
                        data: null,
                        render: (data) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning" onclick="editUser('${data.username}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser('${data.username}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        }

        function loadPermissions() {
            $.get("{{ url('/user/columns') }}").done((cols) => {
                allColumns = cols;
                let html = '';
                cols.forEach(col => {
                    const label = col.replace(/_/g, ' ').toUpperCase();
                    html += `
                        <div class="col-md-6 permission-item">
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox" name="${col}" id="check_${col}" value="true">
                                <label class="form-check-label fs-6" for="check_${col}" style="cursor: pointer;">
                                    ${label}
                                </label>
                            </div>
                        </div>
                    `;
                });
                $('#permissionList').html(html);
            });
        }

        function toggleAllPermissions(checked) {
            $('.permission-checkbox:visible').prop('checked', checked);
        }

        function resetFormUser() {
            $('#formUser').trigger('reset');
            $('#username').prop('readonly', false);
            $('#role').val('');
            $('#btnSimpanUser').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan Data User');
            $('.permission-checkbox').prop('checked', false);
        }

        function simpanUser() {
            const username = $('#username').val();
            const isEdit = $('#username').prop('readonly');
            const url = isEdit ? `{{ url('/user') }}/${username}` : "{{ url('/user') }}";
            const method = isEdit ? 'PUT' : 'POST';

            if (!username) {
                showToast('ID User (NIK) wajib diisi', 'warning');
                return;
            }

            loadingAjax('Sedang memproses data user...');
            const data = $('#formUser').serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: (response) => {
                    showToast(response.message);
                    renderTableUser();
                    if (!isEdit) resetFormUser();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal memproses data user', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function editUser(username) {
            loadingAjax('Mengambil data user...');
            $.get("{{ url('/user/get') }}", { username: username }).done((data) => {
                resetFormUser();
                $('#username').val(data.username).prop('readonly', true);
                // Password intentionally left blank for security, only update if filled
                
                if (data.role) {
                    $('#role').val(data.role);
                } else {
                    $('#role').val('');
                }
                
                // Set permissions
                allColumns.forEach(col => {
                    if (data[col] === 'true') {
                        $(`#check_${col}`).prop('checked', true);
                    }
                });

                $('#btnSimpanUser').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Update Data User');
                Swal.close();
            });
        }

        function deleteUser(username) {
            Swal.fire({
                title: 'Hapus User?',
                text: `Apakah Anda yakin ingin menghapus user ${username}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus data...');
                    $.ajax({
                        url: `{{ url('/user') }}/${username}`,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: (response) => {
                            showToast(response.message);
                            renderTableUser();
                        },
                        error: (xhr) => {
                            showToast('Gagal menghapus data user', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

        // LOOKUP PEGAWAI
        function showLookupPegawai() {
            $('#modalLookupPegawai').modal('show');
            $('#keywordLookup').val('').focus();
            $('#listLookupPegawai').html('<tr><td colspan="5" class="text-center text-muted">Silakan ketik nama atau NIK untuk mencari</td></tr>');
        }

        $('#keywordLookup').on('keypress', function(e) {
            if (e.which == 13) searchLookupPegawai();
        });

        function searchLookupPegawai() {
            const keyword = $('#keywordLookup').val();
            if (keyword.length < 3) {
                showToast('Ketik minimal 3 karakter', 'warning');
                return;
            }

            $('#listLookupPegawai').html('<tr><td colspan="5" class="text-center"><div class="spinner-border spinner-border-sm text-primary"></div> Mencari...</td></tr>');

            $.get("{{ url('/pegawai/search') }}", { keyword: keyword }).done((data) => {
                let html = '';
                if (data.length > 0) {
                    data.forEach(p => {
                        html += `
                            <tr>
                                <td>${p.nik}</td>
                                <td>${p.nama}</td>
                                <td>${p.jbtn}</td>
                                <td>${p.departemen ? p.departemen.nm_dep : '-'}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary" onclick="pickPegawai('${p.nik}')">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center text-danger">Data tidak ditemukan</td></tr>';
                }
                $('#listLookupPegawai').html(html);
            });
        }

        function pickPegawai(nik) {
            $('#username').val(nik);
            $('#modalLookupPegawai').modal('hide');
            $('#password').focus();
        }

        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('iconPassword');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                pwd.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }
    </script>
@endpush

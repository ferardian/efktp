@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-3">
            <!-- Pilihan Role (Kiri) -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom bg-light">
                            <h3 class="card-title mb-0">Pilih Role Akses</h3>
                            <p class="text-muted small mb-0">Pilih role untuk mengatur hak akses menu</p>
                        </div>
                        <div class="list-group list-group-flush" id="roleList">
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 active" data-role="admin">
                                <span class="avatar avatar-sm bg-primary-lt"><i class="ti ti-shield"></i></span>
                                <div>
                                    <div class="font-weight-medium">Admin</div>
                                    <span class="text-muted small">Akses penuh sistem</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3" data-role="dokter">
                                <span class="avatar avatar-sm bg-success-lt"><i class="ti ti-user-check"></i></span>
                                <div>
                                    <div class="font-weight-medium">Dokter</div>
                                    <span class="text-muted small">Praktisi Medis / Pelayanan</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3" data-role="apoteker">
                                <span class="avatar avatar-sm bg-warning-lt"><i class="ti ti-pill"></i></span>
                                <div>
                                    <div class="font-weight-medium">Apoteker / Farmasi</div>
                                    <span class="text-muted small">Pelayanan Obat & Opname</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3" data-role="petugas">
                                <span class="avatar avatar-sm bg-info-lt"><i class="ti ti-users"></i></span>
                                <div>
                                    <div class="font-weight-medium">Petugas / Umum</div>
                                    <span class="text-muted small">Pendaftaran & Administrasi</span>
                                </div>
                            </a>
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3" data-role="owner">
                                <span class="avatar avatar-sm bg-danger-lt"><i class="ti ti-crown"></i></span>
                                <div>
                                    <div class="font-weight-medium">Owner</div>
                                    <span class="text-muted small">Pemilik / Pemantauan</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Menu Checkbox (Kanan) -->
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <form id="formMenuRole">
                    @csrf
                    <input type="hidden" id="selectedRole" name="role" value="admin">
                    <div class="card shadow-sm" style="max-height: 85vh; overflow: hidden;">
                        <div class="card-body d-flex flex-column p-0" style="overflow: hidden;">
                            <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h3 class="card-title mb-0" id="permissionTitle">Hak Akses Menu: Admin</h3>
                                    <p class="text-muted small mb-0">Centang menu yang ingin diaktifkan untuk role ini</p>
                                </div>
                                <div class="input-group input-group-sm w-100 w-md-auto" style="max-width: 250px;">
                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    <input type="text" id="searchMenu" class="form-control" placeholder="Cari nama menu...">
                                </div>
                            </div>

                            <div class="p-3 border-bottom d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllMenus(true)">Centang Semua</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAllMenus(false)">Hapus Semua</button>
                            </div>

                            <!-- Menu Tree list -->
                            <div class="flex-grow-1 overflow-auto p-4" id="menuTreeContainer" style="min-height: 350px;">
                                <div class="text-center p-5" id="loaderState">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">Memuat struktur menu...</p>
                                </div>
                                <div id="menuTreeList" class="d-none">
                                    <!-- Dynamic menu lists will load here -->
                                </div>
                            </div>

                            <div class="p-3 border-top bg-light">
                                <button type="button" class="btn btn-success w-100 py-2 fs-4" onclick="savePermissions()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Hak Akses Menu
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
        let currentRole = 'admin';

        $(document).ready(() => {
            // Load initial permissions for Admin
            loadRolePermissions(currentRole);

            // Handle Role Selection Click
            $('#roleList a').on('click', function() {
                $('#roleList a').removeClass('active');
                $(this).addClass('active');
                
                const role = $(this).data('role');
                currentRole = role;
                $('#selectedRole').val(role);
                
                // Update Card Title
                const roleName = $(this).find('.font-weight-medium').text();
                $('#permissionTitle').text('Hak Akses Menu: ' + roleName);
                
                loadRolePermissions(role);
            });

            // Live filter menu search
            $('#searchMenu').on('keyup', function() {
                const query = $(this).val().toLowerCase();
                if (query.trim() === '') {
                    $('.menu-item-row').show();
                    return;
                }
                
                $('.menu-item-row').each(function() {
                    const text = $(this).find('.menu-label-text').text().toLowerCase();
                    const isMatched = text.includes(query);
                    $(this).toggle(isMatched);
                });
            });
        });

        function loadRolePermissions(role) {
            $('#loaderState').removeClass('d-none');
            $('#menuTreeList').addClass('d-none');
            
            $.get("{{ url('/master/menu/permissions') }}", { role: role })
                .done((response) => {
                    const menus = response.menus;
                    const assigned = response.assigned;
                    
                    let html = '';
                    
                    // Group by position
                    const positions = {
                        'navbar': 'MENU NAVIGASI ATAS (NAVBAR)',
                        'sidebar': 'MENU NAVIGASI SAMPING (OFFCANVAS/SIDEBAR)'
                    };
                    
                    for (const [pos, title] of Object.entries(positions)) {
                        const posMenus = menus.filter(m => m.position === pos);
                        if (posMenus.length === 0) continue;
                        
                        html += `
                            <div class="mb-4">
                                <div class="subheader mb-2 text-primary font-weight-bold fs-5">${title}</div>
                                <div class="border rounded bg-white">
                        `;
                        
                        posMenus.forEach(menu => {
                            html += renderMenuNode(menu, assigned, 0);
                        });
                        
                        html += `
                                </div>
                            </div>
                        `;
                    }
                    
                    $('#menuTreeList').html(html);
                    $('#loaderState').addClass('d-none');
                    $('#menuTreeList').removeClass('d-none');

                    // Register checkbox change handler to toggle child checkboxes
                    registerParentChildHandlers();
                })
                .fail(() => {
                    showToast('Gagal memuat data menu hak akses', 'error');
                });
        }

        // Recursive helper to render hierarchical menus with indentation
        function renderMenuNode(menu, assigned, depth) {
            const isChecked = assigned.includes(menu.id) ? 'checked' : '';
            const paddingLeft = depth * 24 + 16;
            const hasSub = menu.submenus && menu.submenus.length > 0;
            const borderBottom = hasSub ? '' : 'border-bottom';
            const textClass = depth === 0 ? 'font-weight-bold' : '';
            
            let html = `
                <div class="menu-item-row p-3 ${borderBottom} d-flex align-items-center justify-content-between" style="padding-left: ${paddingLeft}px !important;">
                    <div class="form-check mb-0">
                        <input class="form-check-input menu-checkbox" type="checkbox" name="menu_ids[]" 
                               id="menu_${menu.id}" value="${menu.id}" ${isChecked} 
                               data-id="${menu.id}" data-parent="${menu.parent_id || ''}">
                        <label class="form-check-label ${textClass} menu-label-text" for="menu_${menu.id}" style="cursor: pointer;">
                            ${menu.icon ? `<span class="me-2 text-muted">${menu.icon}</span>` : ''}
                            ${menu.name}
                        </label>
                    </div>
                    <div class="text-muted small">
                        ${menu.url ? `<span class="badge badge-outline text-muted">${menu.url}</span>` : '<span class="badge badge-outline text-warning">Dropdown Group</span>'}
                    </div>
                </div>
            `;
            
            if (hasSub) {
                menu.submenus.forEach(sub => {
                    html += renderMenuNode(sub, assigned, depth + 1);
                });
            }
            
            return html;
        }

        function registerParentChildHandlers() {
            // When a checkbox changes, check/uncheck its children recursively
            $('.menu-checkbox').on('change', function() {
                const menuId = $(this).data('id');
                const isChecked = $(this).is(':checked');
                
                // If checking a child, make sure parent is checked
                if (isChecked) {
                    checkParent($(this));
                } else {
                    // If unchecking a parent, uncheck all children
                    uncheckChildren(menuId);
                }
            });
        }

        function checkParent(element) {
            const parentId = element.data('parent');
            if (parentId) {
                const parentElement = $(`.menu-checkbox[data-id="${parentId}"]`);
                parentElement.prop('checked', true);
                // Recursively check grand-parents
                checkParent(parentElement);
            }
        }

        function uncheckChildren(parentId) {
            $(`.menu-checkbox[data-parent="${parentId}"]`).each(function() {
                $(this).prop('checked', false);
                // Recursively uncheck child's children
                uncheckChildren($(this).data('id'));
            });
        }

        function toggleAllMenus(checked) {
            $('.menu-checkbox:visible').prop('checked', checked);
        }

        function savePermissions() {
            loadingAjax('Sedang menyimpan hak akses menu...');
            const data = $('#formMenuRole').serialize();
            
            $.post("{{ url('/master/menu/permissions') }}", data)
                .done((response) => {
                    showToast(response.message);
                })
                .fail((xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal menyimpan hak akses menu', 'error');
                })
                .always(() => {
                    Swal.close();
                });
        }
    </script>
@endpush

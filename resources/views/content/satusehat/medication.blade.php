@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Mapping Obat (Medication) Satu Sehat
                    </h2>
                    <div class="text-muted mt-1">Sinkronisasi Data Obat SIMRS ke KFA Kemenkes</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <select id="filterStatus" class="form-select form-select-sm d-inline-block w-auto" onchange="loadLocalDrugs(1)">
                        <option value="all">Semua Obat</option>
                        <option value="mapped">Sudah Mapping</option>
                        <option value="unmapped">Belum Mapping</option>
                    </select>
                </div>
                <div class="d-flex">
                    <input type="text" id="searchKeyword" class="form-control form-control-sm me-2" placeholder="Cari obat lokal..." onkeyup="if(event.keyCode == 13) loadLocalDrugs(1)">
                    <button class="btn btn-sm btn-primary" onclick="loadLocalDrugs(1)">Cari</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table" id="tableLocalDrugs">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Obat (Lokal)</th>
                                <th>Jenis</th>
                                <th>Pabrik</th>
                                <th>Mapping KFA</th>
                                <th>Status</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-local-drugs">
                            <tr><td colspan="7" class="text-center">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center" id="pagination-container">
                <!-- Pagination will be rendered here -->
            </div>
        </div>
    </div>

    <!-- Modal KFA Search -->
    <div class="modal modal-blur fade" id="modalKfaSearch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Obat di KFA (Kemenkes)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="hidden" id="kfa-local-kode-brng">
                            <label class="form-label">Nama Obat Lokal: <strong id="kfa-local-nama-brng"></strong></label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-10">
                            <input type="text" class="form-control" id="kfaSearchKeyword" placeholder="Ketik nama obat atau kode KFA lalu Enter..." onkeyup="if(event.keyCode == 13) searchKfa(1)">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-primary w-100" onclick="searchKfa(1)">Cari KFA</button>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-vcenter table-sm card-table">
                            <thead>
                                <tr>
                                    <th>Kode KFA</th>
                                    <th>Nama KFA</th>
                                    <th>Bentuk Sediaan</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-kfa-results">
                                <tr><td colspan="4" class="text-center text-muted">Ketik kata kunci untuk mencari.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-center" id="kfa-pagination">
                        <!-- KFA Pagination -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let currentLocalPage = 1;

        $(document).ready(function() {
            loadLocalDrugs(1);
        });

        function loadLocalDrugs(page) {
            currentLocalPage = page;
            let keyword = $('#searchKeyword').val();
            let statusMap = $('#filterStatus').val();
            let container = $('#tbody-local-drugs');
            
            container.html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"></div><br>Memuat...</td></tr>');

            $.get("{{ url('satusehat/medication/lokal') }}", { page: page, limit: 15, keyword: keyword, status_map: statusMap }, function(response) {
                if (response.success) {
                    let html = '';
                    if (response.data.data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center">Tidak ada data obat.</td></tr>';
                    } else {
                        response.data.data.forEach(item => {
                            let kfaInfo = '-';
                            let statusBadge = '<span class="badge bg-warning">Belum Mapping</span>';
                            let actionBtn = `<button class="btn btn-sm btn-ghost-primary" onclick="openKfaModal('${item.kode_brng}', '${item.nama_brng.replace(/'/g, "\\'")}')">Mapping</button>`;

                            if (item.is_mapped == 1) {
                                statusBadge = '<span class="badge bg-success">Termapping</span>';
                                kfaInfo = `<strong>${item.obat_code}</strong><br><small class="text-muted">${item.obat_display}</small>`;
                                
                                if (item.is_synced == 1) {
                                    kfaInfo += `<br><small class="text-info"><i class="ti ti-id-badge"></i> ID FHIR: ${item.id_medication}</small>`;
                                }
                                
                                let syncBtnIcon = item.is_synced == 1 ? '<i class="ti ti-check"></i>' : '<i class="ti ti-cloud-upload"></i>';
                                let syncBtnTitle = item.is_synced == 1 ? 'Update ke SatuSehat' : 'Kirim ke SatuSehat';
                                let syncBtnClass = item.is_synced == 1 ? 'btn-outline-success' : 'btn-outline-info';

                                actionBtn = `
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="openKfaModal('${item.kode_brng}', '${item.nama_brng.replace(/'/g, "\\'")}')"><i class="ti ti-edit"></i></button>
                                        <button class="btn btn-sm ${syncBtnClass}" title="${syncBtnTitle}" onclick="syncMedication('${item.kode_brng}')">${syncBtnIcon}</button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMapping('${item.kode_brng}')"><i class="ti ti-trash"></i></button>
                                    </div>
                                `;
                            }

                            html += `
                                <tr>
                                    <td>${item.kode_brng}</td>
                                    <td><strong>${item.nama_brng}</strong></td>
                                    <td>${item.nm_jns || '-'}</td>
                                    <td>${item.nama_industri || '-'}</td>
                                    <td>${kfaInfo}</td>
                                    <td>${statusBadge}</td>
                                    <td>${actionBtn}</td>
                                </tr>
                            `;
                        });
                    }
                    container.html(html);
                    renderPagination(response.data);
                }
            }).fail(function() {
                container.html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>');
            });
        }

        function renderPagination(data) {
            let container = $('#pagination-container');
            if (data.total === 0) {
                container.html('<p class="m-0 text-muted">Menampilkan 0 data</p>');
                return;
            }

            let html = `<p class="m-0 text-muted">Menampilkan <span>${data.from}</span> ke <span>${data.to}</span> dari <span>${data.total}</span> data</p>`;
            html += `<ul class="pagination m-0 ms-auto">`;
            
            // Prev
            if (data.prev_page_url) {
                html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="loadLocalDrugs(${data.current_page - 1})"><i class="ti ti-chevron-left"></i></a></li>`;
            } else {
                html += `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="ti ti-chevron-left"></i></a></li>`;
            }

            // Next
            if (data.next_page_url) {
                html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="loadLocalDrugs(${data.current_page + 1})"><i class="ti ti-chevron-right"></i></a></li>`;
            } else {
                html += `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="ti ti-chevron-right"></i></a></li>`;
            }
            
            html += `</ul>`;
            container.html(html);
        }

        function openKfaModal(kodeBrng, namaBrng) {
            $('#kfa-local-kode-brng').val(kodeBrng);
            $('#kfa-local-nama-brng').text(namaBrng);
            $('#kfaSearchKeyword').val(namaBrng); // Auto fill search
            $('#tbody-kfa-results').html('<tr><td colspan="4" class="text-center text-muted">Tekan Cari KFA untuk mulai pencarian.</td></tr>');
            $('#kfa-pagination').html('');
            $('#modalKfaSearch').modal('show');
            searchKfa(1);
        }

        function searchKfa(page) {
            let keyword = $('#kfaSearchKeyword').val();
            if (!keyword) return;

            let container = $('#tbody-kfa-results');
            container.html('<tr><td colspan="4" class="text-center"><div class="spinner-border spinner-border-sm text-primary"></div> Mencari di KFA...</td></tr>');

            $.get("{{ url('satusehat/medication/kfa') }}", { keyword: keyword, page: page, size: 20 }, function(response) {
                if (response.success && response.data && response.data.items) {
                    let items = response.data.items.data ? response.data.items.data : (Array.isArray(response.data.items) ? response.data.items : []);
                    let html = '';
                    
                    if (!Array.isArray(items) || items.length === 0) {
                        html = '<tr><td colspan="4" class="text-center text-warning">Tidak ditemukan produk KFA dengan kata kunci tersebut.</td></tr>';
                    } else {
                        items.forEach(item => {
                            let itemStr = JSON.stringify(item).replace(/'/g, "&apos;").replace(/"/g, "&quot;");
                            let code = item.kfa_code || item.kfa_poa_code || '';
                            let display = item.name || item.kfa_poa_display || '';
                            let form = item.dosage_form ? item.dosage_form.name : '-';
                            
                            html += `
                                <tr>
                                    <td><span class="badge bg-blue-lt">${code}</span></td>
                                    <td><strong>${display}</strong><br><small class="text-muted">${item.manufacturer || ''}</small></td>
                                    <td>${form}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="selectKfaItem('${code}', '${display.replace(/'/g, "\\'")}', '${itemStr}')">Pilih</button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    container.html(html);
                    
                    // Simple pagination for KFA
                    let kfaPagin = '';
                    if (page > 1) {
                        kfaPagin += `<button class="btn btn-sm btn-outline-secondary me-2" onclick="searchKfa(${page - 1})">Sebelumnya</button>`;
                    }
                    if (items.length === 20) {
                        kfaPagin += `<button class="btn btn-sm btn-outline-secondary" onclick="searchKfa(${page + 1})">Selanjutnya</button>`;
                    }
                    $('#kfa-pagination').html(kfaPagin);
                    
                } else {
                    container.html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data dari KFA.</td></tr>');
                }
            }).fail(function() {
                container.html('<tr><td colspan="4" class="text-center text-danger">Terjadi kesalahan saat menghubungi API KFA.</td></tr>');
            });
        }

        function selectKfaItem(code, display, rawItemStr) {
            let item = JSON.parse(rawItemStr);
            let localKode = $('#kfa-local-kode-brng').val();
            
            // Extract numerator / denominator if exists (for complex mapping)
            let numeratorCode = null;
            let numeratorSystem = null;
            let denominatorCode = null;
            let denominatorSystem = null;
            
            if (item.active_ingredients && item.active_ingredients.length > 0) {
                let ai = item.active_ingredients[0];
                if (ai.numerator && ai.numerator.code) {
                    numeratorCode = ai.numerator.code;
                    numeratorSystem = ai.numerator.system || 'http://unitsofmeasure.org';
                }
                if (ai.denominator && ai.denominator.code) {
                    denominatorCode = ai.denominator.code;
                    denominatorSystem = ai.denominator.system || 'http://unitsofmeasure.org';
                }
            }

            let payload = {
                _token: '{{ csrf_token() }}',
                kode_brng: localKode,
                obat_code: code,
                obat_system: 'http://sys-ids.kemkes.go.id/kfa',
                obat_display: display,
                form_code: item.dosage_form ? item.dosage_form.code : null,
                form_system: 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
                form_display: item.dosage_form ? item.dosage_form.name : null,
                route_code: null, // Usually from route of administration mapping
                route_system: null,
                route_display: null,
                numerator_code: numeratorCode,
                numerator_system: numeratorSystem,
                denominator_code: denominatorCode,
                denominator_system: denominatorSystem
            };

            Swal.fire({
                title: 'Konfirmasi Mapping',
                html: `Map obat lokal ke <strong>${display}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax();
                    $.post("{{ url('satusehat/medication/mapping') }}", payload, function(res) {
                        Swal.close();
                        if (res.success) {
                            Swal.fire('Berhasil', res.message, 'success');
                            $('#modalKfaSearch').modal('hide');
                            loadLocalDrugs(currentLocalPage);
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }).fail(function() {
                        Swal.close();
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    });
                }
            });
        }

        function deleteMapping(kodeBrng) {
            Swal.fire({
                title: 'Hapus Mapping?',
                text: "Data mapping obat ini akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax();
                    $.ajax({
                        url: `{{ url('satusehat/medication/mapping') }}/${kodeBrng}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            Swal.close();
                            if (res.success) {
                                Swal.fire('Terhapus!', res.message, 'success');
                                loadLocalDrugs(currentLocalPage);
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire('Error!', 'Gagal menghapus mapping.', 'error');
                        }
                    });
                }
            });
        }

        function syncMedication(kodeBrng) {
            Swal.fire({
                title: 'Kirim ke SatuSehat?',
                text: "Data master obat ini akan dikirim ke server SatuSehat (resource Medication).",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax();
                    $.post("{{ url('satusehat/medication/sync') }}", {
                        _token: '{{ csrf_token() }}',
                        kode_brng: kodeBrng
                    }, function(res) {
                        Swal.close();
                        if (res.success) {
                            Swal.fire('Berhasil', res.message, 'success');
                            loadLocalDrugs(currentLocalPage);
                        } else {
                            Swal.fire('Gagal', res.message || 'Terjadi kesalahan sistem', 'error');
                        }
                    }).fail(function(err) {
                        Swal.close();
                        let errMsg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Terjadi kesalahan sistem saat mengirim data';
                        Swal.fire('Error', errMsg, 'error');
                    });
                }
            });
        }
    </script>
@endpush

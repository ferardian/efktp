@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-teal-lt text-teal rounded-circle" style="width: 38px; height: 38px;">
                            <i class="ti ti-dna fs-2"></i>
                        </div>
                        <div>
                            <h2 class="page-title mb-0">Mapping Laboratorium (LOINC & SNOMED CT) Satu Sehat</h2>
                            <div class="text-muted small">Standardisasi terminologi parameter laboratorium klinik ke kode LOINC & SNOMED CT Kemenkes</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('satusehat.servicerequest-lab.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-send me-1"></i> Ke Pengiriman ServiceRequest
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <select id="filterStatus" class="form-select form-select-sm" style="width: 160px;" onchange="loadMappingData()">
                        <option value="all">Semua Status</option>
                        <option value="mapped">Sudah Mapping</option>
                        <option value="unmapped">Belum Mapping</option>
                    </select>

                    <div class="input-group input-group-sm" style="width: 280px;">
                        <input type="text" id="searchKeyword" class="form-control" placeholder="Cari nama/paket/kode..." onkeyup="if(event.keyCode == 13) loadMappingData()">
                        <button class="btn btn-primary" onclick="loadMappingData()">
                            <i class="ti ti-search"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-green-lt" id="badgeCountMapped">0 Termapping</span>
                    <span class="badge bg-red-lt" id="badgeCountUnmapped">0 Belum</span>
                    <button class="btn btn-outline-secondary btn-sm" onclick="loadMappingData()" title="Refresh Data">
                        <i class="ti ti-refresh"></i>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-vcenter table-hover card-table mb-0" id="tableMappingLab">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th style="width: 60px;" class="text-center">ID</th>
                                <th>Paket Tarif Lab</th>
                                <th>Parameter / Sub-Pemeriksaan</th>
                                <th>Satuan</th>
                                <th>Standar LOINC</th>
                                <th>Spesimen (SNOMED)</th>
                                <th class="text-center" style="width: 120px;">Status</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyMappingLab">
                            <tr><td colspan="8" class="text-center py-4 text-muted">Memuat data mapping...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Mapping Lab -->
    <div class="modal modal-blur fade" id="modalFormMapping" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-teal text-white py-2">
                    <h5 class="modal-title mb-0"><i class="ti ti-adjustments-horizontal me-1"></i> Mapping Parameter Lab ke SatuSehat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formMappingLab">
                    <div class="modal-body p-3">
                        <input type="hidden" id="map_id_template" name="id_template">

                        <div class="alert alert-light border py-2 px-3 mb-3">
                            <div class="text-muted small">Parameter Lab Lokal:</div>
                            <h4 class="mb-0 text-dark" id="map_pemeriksaan_nama">-</h4>
                            <div class="text-muted small" id="map_paket_nama">-</div>
                        </div>

                        <!-- Presets Spesimen Cepat -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1">Preset Cepat Spesimen (SNOMED):</label>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119297000', 'Blood specimen')">
                                    🩸 Darah / Whole Blood
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119364003', 'Serum specimen')">
                                    🧪 Serum
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119361006', 'Plasma specimen')">
                                    💧 Plasma
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('122575003', 'Urine specimen')">
                                    🚽 Urin
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119339001', 'Stool specimen')">
                                    💩 Feses
                                </button>
                            </div>
                        </div>

                        <hr class="my-2 text-muted">

                        <h6 class="fw-bold text-teal mb-2"><i class="ti ti-code me-1"></i> Standar LOINC (Pemeriksaan)</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="form-label small required">Kode LOINC</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="map_code" name="code" placeholder="Contoh: 718-7" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small required">System Code</label>
                                <input type="text" class="form-control form-control-sm font-monospace bg-light" id="map_system" name="system" value="http://loinc.org" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small required">Nama Display Resmi LOINC</label>
                            <input type="text" class="form-control form-control-sm" id="map_display" name="display" placeholder="Contoh: Hemoglobin [Mass/volume] in Blood" required>
                        </div>

                        <h6 class="fw-bold text-teal mb-2"><i class="ti ti-test-pipe me-1"></i> Standar SNOMED CT (Spesimen)</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="form-label small required">Kode Spesimen</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="map_sampel_code" name="sampel_code" placeholder="Contoh: 119297000" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small required">System Spesimen</label>
                                <input type="text" class="form-control form-control-sm font-monospace bg-light" id="map_sampel_system" name="sampel_system" value="http://snomed.info/sct" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small required">Nama Display Spesimen</label>
                            <input type="text" class="form-control form-control-sm" id="map_sampel_display" name="sampel_display" placeholder="Contoh: Blood specimen" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-teal btn-sm" id="btnSimpanMapping">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Mapping
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let mappingDataStore = [];

        $(document).ready(function() {
            loadMappingData();

            $('#formMappingLab').on('submit', function(e) {
                e.preventDefault();
                simpanMapping();
            });
        });

        function loadMappingData() {
            const status = $('#filterStatus').val();
            const search = $('#searchKeyword').val();

            $('#tbodyMappingLab').html('<tr><td colspan="8" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Memuat data mapping...</td></tr>');

            $.get(`{{ route('satusehat.mapping.lab.data') }}`, {
                status: status,
                search: search
            }).done(function(res) {
                if (res.success) {
                    mappingDataStore = res.data;
                    renderMappingTable(res.data);
                } else {
                    $('#tbodyMappingLab').html(`<tr><td colspan="8" class="text-center py-4 text-danger">${res.message}</td></tr>`);
                }
            }).fail(function(err) {
                $('#tbodyMappingLab').html('<tr><td colspan="8" class="text-center py-4 text-danger">Gagal memuat data dari server</td></tr>');
            });
        }

        function renderMappingTable(data) {
            let html = '';
            let mappedCount = 0;
            let unmappedCount = 0;

            if (!data || data.length === 0) {
                $('#tbodyMappingLab').html('<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data ditemukan</td></tr>');
                $('#badgeCountMapped').text('0 Termapping');
                $('#badgeCountUnmapped').text('0 Belum');
                return;
            }

            data.forEach(function(item) {
                const isMapped = item.satu_sehat_mapping_lab && item.satu_sehat_mapping_lab.code;
                if (isMapped) mappedCount++; else unmappedCount++;

                const paketNama = item.jenis_perawatan ? item.jenis_perawatan.nm_perawatan : item.kd_jenis_prw;
                const mapInfo = item.satu_sehat_mapping_lab || {};

                html += `
                    <tr>
                        <td class="text-center text-muted font-monospace">${item.id_template}</td>
                        <td>
                            <div class="fw-bold text-dark">${paketNama}</div>
                            <small class="text-muted font-monospace">${item.kd_jenis_prw}</small>
                        </td>
                        <td>
                            <span class="fw-semibold text-primary">${item.Pemeriksaan}</span>
                        </td>
                        <td><small class="badge bg-light text-dark">${item.satuan || '-'}</small></td>
                        <td>
                            ${isMapped ? `
                                <div><code class="fw-bold text-dark">${mapInfo.code}</code></div>
                                <small class="text-muted">${mapInfo.display || '-'}</small>
                            ` : `<span class="text-muted fst-italic">- Belum Dimapping -</span>`}
                        </td>
                        <td>
                            ${isMapped && mapInfo.sampel_code ? `
                                <div><code class="text-dark">${mapInfo.sampel_code}</code></div>
                                <small class="text-muted">${mapInfo.sampel_display || '-'}</small>
                            ` : `<span class="text-muted fst-italic">-</span>`}
                        </td>
                        <td class="text-center">
                            ${isMapped
                                ? `<span class="badge bg-success-lt"><i class="ti ti-check me-1"></i>Mapped</span>`
                                : `<span class="badge bg-warning-lt"><i class="ti ti-alert-triangle me-1"></i>Belum</span>`
                            }
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="openModalMapping(${item.id_template})" title="Edit Mapping">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                ${isMapped ? `
                                    <button type="button" class="btn btn-outline-danger" onclick="hapusMapping(${item.id_template}, '${item.Pemeriksaan}')" title="Hapus Mapping">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#tbodyMappingLab').html(html);
            $('#badgeCountMapped').text(`${mappedCount} Termapping`);
            $('#badgeCountUnmapped').text(`${unmappedCount} Belum`);
        }

        function openModalMapping(id_template) {
            const item = mappingDataStore.find(x => x.id_template === id_template);
            if (!item) return;

            $('#map_id_template').val(item.id_template);
            $('#map_pemeriksaan_nama').text(item.Pemeriksaan);
            $('#map_paket_nama').text(item.jenis_perawatan ? item.jenis_perawatan.nm_perawatan : item.kd_jenis_prw);

            const map = item.satu_sehat_mapping_lab || {};
            $('#map_code').val(map.code || '');
            $('#map_system').val(map.system || 'http://loinc.org');
            $('#map_display').val(map.display || item.Pemeriksaan);
            $('#map_sampel_code').val(map.sampel_code || '119297000');
            $('#map_sampel_system').val(map.sampel_system || 'http://snomed.info/sct');
            $('#map_sampel_display').val(map.sampel_display || 'Blood specimen');

            $('#modalFormMapping').modal('show');
        }

        function applySpecimenPreset(code, display) {
            $('#map_sampel_code').val(code);
            $('#map_sampel_system').val('http://snomed.info/sct');
            $('#map_sampel_display').val(display);
        }

        function simpanMapping() {
            const payload = {
                id_template: $('#map_id_template').val(),
                code: $('#map_code').val(),
                system: $('#map_system').val(),
                display: $('#map_display').val(),
                sampel_code: $('#map_sampel_code').val(),
                sampel_system: $('#map_sampel_system').val(),
                sampel_display: $('#map_sampel_display').val(),
            };

            $('#btnSimpanMapping').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

            $.post(`{{ route('satusehat.mapping.lab.save') }}`, payload)
                .done(function(res) {
                    $('#btnSimpanMapping').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Mapping');
                    if (res.success) {
                        $('#modalFormMapping').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadMappingData();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }).fail(function(err) {
                    $('#btnSimpanMapping').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Mapping');
                    const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Terjadi kesalahan sistem';
                    Swal.fire('Error', msg, 'error');
                });
        }

        function hapusMapping(id_template, nama) {
            Swal.fire({
                title: 'Hapus Mapping?',
                text: `Hapus mapping kode LOINC untuk "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('satusehat/mapping/lab') }}/${id_template}`,
                        type: 'DELETE',
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadMappingData();
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        },
                        error: function(err) {
                            Swal.fire('Error', 'Gagal menghapus data mapping', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush

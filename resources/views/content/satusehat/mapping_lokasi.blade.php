@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Mapping Lokasi Satu Sehat
                    </h2>
                    <div class="text-muted mt-1">Sinkronisasi Unit/Ruangan SIMRS ke ID Lokasi Satu Sehat</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-ralan" class="nav-link active" data-bs-toggle="tab" onclick="loadLocationData('ralan')">Ralan (Poli)</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-ranap" class="nav-link" data-bs-toggle="tab" onclick="loadLocationData('ranap')">Ranap (Kamar)</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-lab" class="nav-link" data-bs-toggle="tab" onclick="loadLocationData('lab')">Lab</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-rad" class="nav-link" data-bs-toggle="tab" onclick="loadLocationData('rad')">Radiologi</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-ok" class="nav-link" data-bs-toggle="tab" onclick="loadLocationData('ok')">OK</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-depo" class="nav-link" data-bs-toggle="tab" onclick="loadLocationData('depo')">Depo Farmasi</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tab-ralan">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="tableRalan">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Unit</th>
                                        <th>ID Lokasi</th>
                                        <th>Koordinat (L,L,A)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-location" id="tbody-ralan">
                                    <tr><td colspan="5" class="text-center">Memuat data...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Other tabs will use the same structure, but I'll dynamically load into their respective containers if needed, 
                         or just reuse one container if I want to be more efficient. Let's use separate ones for clarity. -->
                    <div class="tab-pane" id="tab-ranap">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="tableRanap">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Unit</th>
                                        <th>ID Lokasi</th>
                                        <th>Koordinat (L,L,A)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-location" id="tbody-ranap"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-lab">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="tableLab">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Unit</th>
                                        <th>ID Lokasi</th>
                                        <th>Koordinat (L,L,A)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-location" id="tbody-lab"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-rad">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="tableRad">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Unit</th>
                                        <th>ID Lokasi</th>
                                        <th>Koordinat (L,L,A)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-location" id="tbody-rad"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-ok">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="tableOk">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Unit</th>
                                        <th>ID Lokasi</th>
                                        <th>Koordinat (L,L,A)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-location" id="tbody-ok"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-depo">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table" id="tableDepo">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Unit</th>
                                        <th>ID Lokasi</th>
                                        <th>Koordinat (L,L,A)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-location" id="tbody-depo"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Mapping -->
    <div class="modal modal-blur fade" id="modalMapLocation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mapping Lokasi ke Satu Sehat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formMapLocation">
                    <div class="modal-body">
                        <input type="hidden" name="type" id="map-type">
                        <input type="hidden" name="id" id="map-id">
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Unit/Ruang</label>
                            <input type="text" class="form-control" id="map-name" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Organisasi Parent (Menaungi Unit ini)</label>
                            <select class="form-select" name="organization_id" id="map-org-id" required>
                                <option value="">-- Pilih Organisasi --</option>
                            </select>
                            <small class="text-muted">Pilih departemen yang menaungi unit/ruangan ini.</small>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" id="map-long" value="0" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" class="form-control" name="latitude" id="map-lat" value="0" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Altitude</label>
                                    <input type="text" class="form-control" name="altitude" id="map-alt" value="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Sinkronkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            loadLocationData('ralan');
            loadOrganizations();

            $('#formMapLocation').on('submit', function(e) {
                e.preventDefault();
                submitMapping();
            });
        });

        function loadLocationData(type) {
            let container = $(`#tbody-${type}`);
            container.html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');

            $.get("{{ url('satusehat/mapping/lokasi/data') }}", { type: type }, function(response) {
                if (response.status) {
                    let html = '';
                    if (response.data.length === 0) {
                        html = '<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>';
                    } else {
                        response.data.forEach(item => {
                            let statusBadge = item.id_lokasi_satusehat 
                                ? `<span class="badge bg-success" title="${item.id_lokasi_satusehat}">Termapping</span>` 
                                : '<span class="badge bg-warning">Belum Mapping</span>';
                            
                            let coord = (item.longitude || '0') + ', ' + (item.latitude || '0') + ', ' + (item.altitude || '0');
                            
                            html += `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.nama}</td>
                                    <td class="text-muted small">${item.id_lokasi_satusehat || '-'}</td>
                                    <td class="small">${coord}</td>
                                    <td>
                                        <button class="btn btn-sm btn-ghost-primary" onclick="openMapModal('${type}', '${item.id}', '${item.nama}', '${item.id_organisasi_satusehat || ''}', '${item.longitude || '0'}', '${item.latitude || '0'}', '${item.altitude || '0'}')">
                                            <i class="ti ti-settings me-1"></i> Map
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    container.html(html);
                }
            });
        }

        function loadOrganizations() {
            $.get("{{ url('satusehat/mapping/organisasi/all') }}", function(response) {
                if (response.status) {
                    let html = '<option value="">-- Pilih Organisasi --</option>';
                    response.data.forEach(item => {
                        html += `<option value="${item.id}">${item.nama} (${item.id})</option>`;
                    });
                    $('#map-org-id').html(html);
                }
            });
        }

        function openMapModal(type, id, name, orgId, long, lat, alt) {
            $('#map-type').val(type);
            $('#map-id').val(id);
            $('#map-name').val(name);
            $('#map-org-id').val(orgId);
            $('#map-long').val(long);
            $('#map-lat').val(lat);
            $('#map-alt').val(alt);
            $('#modalMapLocation').modal('show');
        }

        function submitMapping() {
            let formData = $('#formMapLocation').serialize();
            let type = $('#map-type').val();

            loadingAjax();
            $.post("{{ url('satusehat/mapping/lokasi') }}", formData, function(response) {
                loadingAjax().close();
                if (response.status) {
                    Swal.fire('Berhasil!', response.message, 'success');
                    $('#modalMapLocation').modal('hide');
                    loadLocationData(type);
                } else {
                    Swal.fire('Gagal!', response.message || 'Terjadi kesalahan', 'error');
                }
            }).fail(function(err) {
                loadingAjax().close();
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
            });
        }
    </script>
@endpush

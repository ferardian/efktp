    <ul class="nav nav-tabs" id="pcareTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="akun-tab" data-bs-toggle="tab" data-bs-target="#akun" type="button" role="tab" aria-controls="akun" aria-selected="true">Akun</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="dokter-tab" data-bs-toggle="tab" data-bs-target="#dokter" type="button" role="tab" aria-controls="dokter" aria-selected="false">Mapping Dokter</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="poliklinik-tab" data-bs-toggle="tab" data-bs-target="#poliklinik" type="button" role="tab" aria-controls="poliklinik" aria-selected="false">Mapping Poliklinik</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="pcareTabContent">
        <div class="tab-pane fade show active" id="akun" role="tabpanel" aria-labelledby="akun-tab">
            <form id="formSettingPcare" name="formSettingPcare">
                <div class="mb-3 row">
                    <label class="col-3 col-form-label required">Username Pcare</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="password" class="form-control" name="user" id="user" placeholder="Username Pcare" value="{{ $data ? $data->user : '' }}">
                            <button class="btn btn-outline-secondary" type="button" id="btnShowUserPcare" data-target="#user" onclick="toggleSettingPcare(event)"><i class="ti ti-eye-off"></i></button>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-3 col-form-label required">Password Pcare</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password Pcare" value="{{ $data ? $data->password : '' }}" formnovalidate>
                            <button class="btn btn-outline-secondary" type="button" id="btnShowPassPcare" data-target="#password" onclick="toggleSettingPcare(event)"><i class="ti ti-eye-off"></i></button>
                        </div>
                        <span class="invalid-feedback" id="errorPassword"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-3 col-form-label required">Username I-Care</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="password" class="form-control" name="userIcare" id="userIcare" placeholder="Username Icare" value="{{ $data ? $data->userIcare : '' }}">
                            <button class="btn btn-outline-secondary" type="button" id="btnShowUserIcare" data-target="#userIcare" onclick="toggleSettingPcare(event)"><i class="ti ti-eye-off"></i></button>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-3 col-form-label required">Password I-Care</label>
                    <div class="col">
                        <div class="input-group">
                            <input type="password" class="form-control" name="passwordIcare" id="passwordIcare" placeholder="Password Icare" value="{{ $data ? $data->passwordIcare : '' }}" formnovalidate>
                            <button class="btn btn-outline-secondary" type="button" id="btnShowPassIcare" data-target="#passwordIcare" onclick="toggleSettingPcare(event)"><i class="ti ti-eye-off"></i></button>
                        </div>
                        <span class="invalid-feedback" id="errorPassword"></span>
                        <span class="badge bg-primary mt-2" id="badgeLastUpdate"> </span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-success" id="btnSettingPcare" onclick="setSettingPcare()"><i class="ti ti-device-floppy"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="dokter" role="tabpanel" aria-labelledby="dokter-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tableMappingDokter" width="100%">
                    <thead>
                        <tr>
                            <th>Kode Dokter (RS)</th>
                            <th>Nama Dokter (RS)</th>
                            <th>Kode Dokter (PCare)</th>
                            <th>Nama Dokter (PCare)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="poliklinik" role="tabpanel" aria-labelledby="poliklinik-tab">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tableMappingPoliklinik" width="100%">
                    <thead>
                        <tr>
                            <th>Kode Poli (RS)</th>
                            <th>Nama Poli (RS)</th>
                            <th>Kode Poli (PCare)</th>
                            <th>Nama Poli (PCare)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Cari Dokter PCare -->
    <div class="modal fade" id="modalCariDokterPcare" role="dialog" aria-labelledby="modalCariDokterPcareLabel" aria-hidden="true" style="overflow:hidden;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCariDokterPcareLabel">Cari Dokter PCare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Limit Data</label>
                        <div class="col-sm-3">
                             <input type="number" class="form-control" id="limitDokterPcare" value="10">
                        </div>
                         <label class="col-sm-2 col-form-label">Mulai Data</label>
                        <div class="col-sm-3">
                             <input type="number" class="form-control" id="startDokterPcare" value="0">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary" onclick="cariDokterPcare()"><i class="ti ti-search"></i> Cari</button>
                        </div>
                    </div>
                    <form id="formMappingDokter">
                        <input type="hidden" id="kdDokterMap" name="kdDokter">
                         <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped" id="tableDokterPcare">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Pilih</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cari Poliklinik PCare -->
    <div class="modal fade" id="modalCariPoliklinikPcare" role="dialog" aria-labelledby="modalCariPoliklinikPcareLabel" aria-hidden="true" style="overflow:hidden;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCariPoliklinikPcareLabel">Cari Poliklinik PCare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Limit Data</label>
                        <div class="col-sm-3">
                             <input type="number" class="form-control" id="limitPoliklinikPcare" value="10">
                        </div>
                         <label class="col-sm-2 col-form-label">Mulai Data</label>
                        <div class="col-sm-3">
                             <input type="number" class="form-control" id="startPoliklinikPcare" value="0">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary" onclick="cariPoliklinikPcare()"><i class="ti ti-search"></i> Cari</button>
                        </div>
                    </div>
                    <form id="formMappingPoliklinik">
                        <input type="hidden" id="kdPoliMap" name="kdPoliRs">
                         <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped" id="tablePoliklinikPcare">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Pilih</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script>
        const formSettingPcare = $('#formSettingPcare')
        const badgeLastUpdate = $('#badgeLastUpdate')
        const modalCariDokterPcare = $('#modalCariDokterPcare');

        function setSettingPcare() {
            const data = getDataForm('formSettingPcare', 'input');
            $.post(`{{ url('/setting/pcare') }}`, {
                user: data.user,
                password: data.password,
                userIcare: data.userIcare,
                passwordIcare: data.passwordIcare,
            }).done((response) => {
                alertSuccessAjax('Berhasil mengubah akun pcare');
                badgeLastUpdate.html(`Terakhir diubah ${new Date(response.data.created_at)}`);
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function getSettingPcare() {
            $.get(`{{ url('/setting/pcare') }}`).done((response) => {
                const {
                    data
                } = response
                formSettingPcare.find('#user').val(data.user)
                formSettingPcare.find('#password').val(data.password)
                formSettingPcare.find('#userIcare').val(data.userIcare)
                formSettingPcare.find('#passwordIcare').val(data.passwordIcare)
                badgeLastUpdate.html(`Terakhir diubah ${new Date(data.created_at)}`);
            })
        }

        const tableMappingDokter = $('#tableMappingDokter').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('mapping/pcare/dokter/table') }}",
                data: function(d) {
                    
                }
            },
            columns: [
                { data: 'kd_dokter', name: 'kd_dokter' },
                { data: 'nm_dokter', name: 'nm_dokter' },
                { 
                    data: 'maping', 
                    name: 'maping.kd_dokter_pcare', 
                    render: function(data, type, row) {
                        return data ? data.kd_dokter_pcare : '<span class="badge bg-warning">Belum Mapping</span>';
                    }
                },
                { 
                    data: 'maping', 
                    name: 'maping.nm_dokter_pcare',
                    render: function(data, type, row) {
                        return data ? data.nm_dokter_pcare : '-';
                    }
                },
                {
                    data: 'kd_dokter',
                    name: 'kd_dokter',
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-info" onclick="openModalMapping('${data}')"><i class="ti ti-link"></i> Mapping</button>`;
                    }
                }
            ]
        });

        function openModalMapping(kdDokter) {
            $('#kdDokterMap').val(kdDokter);
            $('#tableDokterPcare tbody').empty(); // Clear previous results
            modalCariDokterPcare.modal('show');
        }


        function cariDokterPcare() {
            const start = $('#startDokterPcare').val();
            const limit = $('#limitDokterPcare').val();
            
            const tbody = $('#tableDokterPcare tbody');
            tbody.html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');

            $.get(`{{ url('/bridging/pcare/dokter') }}/${start}/${limit}`).done((response) => {
                tbody.empty();
                const list = response.list || []; // Assuming response params match what backend sends (check json structure)
                
                // If response is straight array or has different structure, adjust here. 
                // Based on BPJS bridging, usually it's response.list for paginated data if wrapped.
                // Assuming controller returns default Pcare response wrapper.
                
                // Let's assume the controller returns the raw decoded JSON from BPJS service or wrapper.
                // In Bridging\Dokter.php: return $bpjs->index($start, $limit); 
                // The library likely returns an array or object. Let's inspect typical structure or iterate safely.
                 
                // Usually: { metaData: {...}, response: { list: [...] } } or similar.
                // If `response` contains `list`, iterate it.
                
                let dataList = [];
                if(response.list){
                    dataList = response.list;
                } else if(response.response && response.response.list){
                     dataList = response.response.list;
                } else if (Array.isArray(response)) {
                    dataList = response;
                }

                if (dataList.length === 0) {
                     tbody.html('<tr><td colspan="3" class="text-center">Tidak ada data ditemukan</td></tr>');
                     return;
                }

                dataList.forEach(dok => {
                    tbody.append(`
                        <tr>
                            <td>${dok.kdDokter}</td>
                            <td>${dok.nmDokter}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" 
                                    onclick="simpanMapping('${dok.kdDokter}', '${dok.nmDokter}')">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    `);
                });

            }).fail((err) => {
                 tbody.html(`<tr><td colspan="3" class="text-center text-danger">Error: ${err.statusText}</td></tr>`);
            });
        }

        function simpanMapping(kdDokterPcare, nmDokterPcare) {
            const kdDokter = $('#kdDokterMap').val();
            
            $.post(`{{ url('mapping/pcare/dokter') }}`, {
                kdDokter: kdDokter,
                kdDokterPcare: kdDokterPcare,
                nmDokterPcare: nmDokterPcare,
                _token: "{{ csrf_token() }}" // Ensure CSRF token if needed, or rely on global setup
            }).done((res) => {
                alertSuccessAjax('Berhasil mapping dokter');
                modalCariDokterPcare.modal('hide');
                tableMappingDokter.ajax.reload();
            }).fail((err) => {
                alertErrorAjax(err);
            });
        }

        const tableMappingPoliklinik = $('#tableMappingPoliklinik').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('mapping/pcare/poliklinik/table') }}",
                data: function(d) {
                    
                }
            },
            columns: [
                { data: 'kd_poli', name: 'kd_poli' },
                { data: 'nm_poli', name: 'nm_poli' },
                { 
                    data: 'maping', 
                    name: 'maping.kd_poli_pcare', 
                    render: function(data, type, row) {
                        return data ? data.kd_poli_pcare : '<span class="badge bg-warning">Belum Mapping</span>';
                    }
                },
                { 
                    data: 'maping', 
                    name: 'maping.nm_poli_pcare',
                    render: function(data, type, row) {
                        return data ? data.nm_poli_pcare : '-';
                    }
                },
                {
                    data: 'kd_poli',
                    name: 'kd_poli',
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-info" onclick="openModalMappingPoli('${data}')"><i class="ti ti-link"></i> Mapping</button>`;
                    }
                }
            ]
        });

        const modalCariPoliklinikPcare = $('#modalCariPoliklinikPcare');

        function openModalMappingPoli(kdPoli) {
            $('#kdPoliMap').val(kdPoli);
            $('#tablePoliklinikPcare tbody').empty();
            modalCariPoliklinikPcare.modal('show');
        }

        function cariPoliklinikPcare() {
            const start = $('#startPoliklinikPcare').val();
            const limit = $('#limitPoliklinikPcare').val();
            
            const tbody = $('#tablePoliklinikPcare tbody');
            tbody.html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');

            $.get(`{{ url('/bridging/pcare/fktp/poli') }}/${start}/${limit}`).done((response) => {
                tbody.empty();
                
                let dataList = [];
                if(response.list){
                    dataList = response.list;
                } else if(response.response && response.response.list){
                     dataList = response.response.list;
                } else if (Array.isArray(response)) {
                    dataList = response;
                }

                if (dataList.length === 0) {
                     tbody.html('<tr><td colspan="3" class="text-center">Tidak ada data ditemukan</td></tr>');
                     return;
                }

                dataList.forEach(poli => {
                    tbody.append(`
                        <tr>
                            <td>${poli.kdPoli}</td>
                            <td>${poli.nmPoli}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" 
                                    onclick="simpanMappingPoli('${poli.kdPoli}', '${poli.nmPoli}')">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    `);
                });

            }).fail((err) => {
                 tbody.html(`<tr><td colspan="3" class="text-center text-danger">Error: ${err.statusText}</td></tr>`);
            });
        }

        function simpanMappingPoli(kdPoliPcare, nmPoliPcare) {
            const kdPoliRs = $('#kdPoliMap').val();
            
            $.post(`{{ url('mapping/pcare/poliklinik') }}`, {
                kdPoliRs: kdPoliRs,
                kdPoliPcare: kdPoliPcare,
                nmPoliPcare: nmPoliPcare,
                _token: "{{ csrf_token() }}"
            }).done((res) => {
                alertSuccessAjax('Berhasil mapping poliklinik');
                modalCariPoliklinikPcare.modal('hide');
                tableMappingPoliklinik.ajax.reload();
            }).fail((err) => {
                alertErrorAjax(err);
            });
        }

    </script>
@endpush

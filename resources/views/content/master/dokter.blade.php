@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-2">
            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbDokter">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Dokter</th>
                                        <th>No. KTP</th>
                                        <th>J.K</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary" id="btnCreateDokter" onclick="resetFormDokter()">
                                    <i class="ti ti-plus me-2"></i> Tambah Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xl-5 col-md-12 col-sm-12">
                <form id="formDokter">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Data Dokter</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Kode Dokter (NIK)</label>
                                        <input type="text" class="form-control" id="kd_dokter" name="kd_dokter" placeholder="Kode/NIK">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">No. KTP</label>
                                        <input type="text" class="form-control" id="no_ktp" name="no_ktp">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama Dokter</label>
                                <input type="text" class="form-control" id="nm_dokter" name="nm_dokter">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" id="jk" name="jk">
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="1">Aktif</option>
                                            <option value="0">Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No. Telp</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" id="almt_tgl" name="almt_tgl" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Ijin Praktek</label>
                                <input type="text" class="form-control" id="no_ijn_praktek" name="no_ijn_praktek">
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-success w-100" id="btnSimpanDokter" onclick="simpanDokter()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Data Dokter
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
            renderTableDokter();
        });

        function renderTableDokter() {
            $('#tbDokter').DataTable({
                responsive: true,
                stateSave: true,
                serverSide: false, // Set to true if data is huge, currently standard get() returns all
                destroy: true,
                processing: true,
                scrollY: setTableHeight(),
                ajax: {
                    url: "{{ url('/dokter/get') }}",
                    dataSrc: ""
                },
                columns: [{
                        data: 'kd_dokter',
                        name: 'kd_dokter'
                    },
                    {
                        data: 'nm_dokter',
                        name: 'nm_dokter'
                    },
                    {
                        data: 'pegawai.no_ktp',
                        name: 'pegawai.no_ktp',
                        defaultContent: '-'
                    },
                    {
                        data: 'jk',
                        name: 'jk',
                        render: (data) => data == 'L' ? 'Laki-laki' : 'Perempuan'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: (data) => data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>'
                    },
                    {
                        data: null,
                        render: (data) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning" onclick="editDokter('${data.kd_dokter}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteDokter('${data.kd_dokter}', '${data.nm_dokter}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        }

        function resetFormDokter() {
            $('#formDokter').trigger('reset');
            $('#kd_dokter').prop('readonly', false);
            $('#btnSimpanDokter').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan Data Dokter');
        }

        function simpanDokter() {
            const kd_dokter = $('#kd_dokter').val();
            const isEdit = $('#kd_dokter').prop('readonly');
            const url = isEdit ? `{{ url('/dokter') }}/${kd_dokter}` : "{{ url('/dokter') }}";
            const method = isEdit ? 'PUT' : 'POST';

            const data = $('#formDokter').serialize();

            if (!$('#kd_dokter').val() || !$('#nm_dokter').val() || !$('#no_ktp').val()) {
                showToast('Mohon lengkapi data dokter (Kode, Nama, No. KTP)', 'warning');
                return;
            }

            loadingAjax('Sedang memproses data dokter...');
            
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: (response) => {
                    showToast(response.message);
                    renderTableDokter();
                    resetFormDokter();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal memproses data dokter', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function editDokter(kd_dokter) {
            loadingAjax('Mengambil data dokter...');
            $.get("{{ url('/dokter/get') }}", { kd_dokter: kd_dokter }).done((data) => {
                $('#kd_dokter').val(data.kd_dokter).prop('readonly', true);
                $('#nm_dokter').val(data.nm_dokter);
                $('#no_ktp').val(data.pegawai ? data.pegawai.no_ktp : '');
                $('#jk').val(data.jk);
                $('#status').val(data.status);
                $('#no_telp').val(data.no_telp);
                $('#almt_tgl').val(data.almt_tgl);
                $('#no_ijn_praktek').val(data.no_ijn_praktek);

                $('#btnSimpanDokter').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Update Data Dokter');
                Swal.close();
            });
        }

        function deleteDokter(kd_dokter, nm_dokter) {
            Swal.fire({
                title: 'Hapus Dokter?',
                html: `Apakah Anda yakin ingin menghapus <b>${nm_dokter}</b>?<br><small class="text-danger">Tindakan ini juga akan menghapus data pegawai terkait!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus data...');
                    $.ajax({
                        url: `{{ url('/dokter') }}/${kd_dokter}`,
                        type: 'DELETE',
                        success: (response) => {
                            showToast(response.message);
                            renderTableDokter();
                        },
                        error: (xhr) => {
                            showToast('Gagal menghapus data dokter', 'error');
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

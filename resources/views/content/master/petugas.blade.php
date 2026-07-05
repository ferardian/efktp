@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-2">
            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbPetugas">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama Petugas</th>
                                        <th>Jabatan</th>
                                        <th>J.K</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary" id="btnCreatePetugas" onclick="resetFormPetugas()">
                            <i class="ti ti-plus me-2"></i> Tambah Petugas
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xl-5 col-md-12 col-sm-12">
                <form id="formPetugas">
                    @csrf
                    <div class="card" style="max-height: 85vh; overflow-y: auto;">
                        <div class="card-body">
                            <h5 class="card-title">Form Data Petugas</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">NIP</label>
                                        <input type="text" class="form-control" id="nip" name="nip" placeholder="NIP/NIK">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Nama Petugas</label>
                                        <input type="text" class="form-control" id="nama" name="nama">
                                    </div>
                                </div>
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
                                        <label class="form-label">Jabatan</label>
                                        <select class="form-select" id="kd_jbtn" name="kd_jbtn">
                                            @foreach($jabatan as $j)
                                                <option value="{{ $j->kd_jbtn }}">{{ $j->nm_jbtn }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Tgl Lahir</label>
                                        <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label class="form-label">Gol. Darah</label>
                                        <select class="form-select" id="gol_darah" name="gol_darah">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                            <option value="-">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label class="form-label">Agama</label>
                                        <select class="form-select" id="agama" name="agama">
                                            <option value="ISLAM">ISLAM</option>
                                            <option value="KRISTEN">KRISTEN</option>
                                            <option value="KATOLIK">KATOLIK</option>
                                            <option value="HINDU">HINDU</option>
                                            <option value="BUDHA">BUDHA</option>
                                            <option value="KONG HU CHU">KONG HU CHU</option>
                                            <option value="-">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
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
                                <label class="form-label">Status Nikah</label>
                                <select class="form-select" id="stts_nikah" name="stts_nikah">
                                    <option value="MENIKAH">MENIKAH</option>
                                    <option value="BELUM MENIKAH">BELUM MENIKAH</option>
                                    <option value="JANDA">JANDA</option>
                                    <option value="DUDHA">DUDHA</option>
                                    <option value="JOMBLO">JOMBLO</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No. Telp</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp">
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success w-100" id="btnSimpanPetugas" onclick="simpanPetugas()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Data Petugas
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
            renderTablePetugas();
        });

        function renderTablePetugas() {
            $('#tbPetugas').DataTable({
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/petugas/data') }}",
                    dataSrc: ""
                },
                columns: [
                    { data: 'nip' },
                    { data: 'nama' },
                    { data: 'jabatan.nm_jbtn', defaultContent: '-' },
                    { 
                        data: 'jk', 
                        render: (data) => data == 'L' ? 'Laki-laki' : 'Perempuan'
                    },
                    { 
                        data: 'status',
                        render: (data) => data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>'
                    },
                    {
                        data: null,
                        render: (data) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning" onclick="editPetugas('${data.nip}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deletePetugas('${data.nip}', '${data.nama}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        }

        function resetFormPetugas() {
            $('#formPetugas').trigger('reset');
            $('#nip').prop('readonly', false);
            $('#btnSimpanPetugas').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan Data Petugas');
        }

        function simpanPetugas() {
            const nip = $('#nip').val();
            const isEdit = $('#nip').prop('readonly');
            const url = isEdit ? `{{ url('/petugas') }}/${nip}` : "{{ url('/petugas') }}";
            const method = isEdit ? 'PUT' : 'POST';

            if (!$('#nip').val() || !$('#nama').val()) {
                showToast('NIP dan Nama wajib diisi', 'warning');
                return;
            }

            loadingAjax('Sedang memproses data petugas...');
            
            $.ajax({
                url: url,
                type: method,
                data: $('#formPetugas').serialize(),
                success: (response) => {
                    showToast(response.message);
                    renderTablePetugas();
                    if(!isEdit) resetFormPetugas();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal memproses data petugas', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function editPetugas(nip) {
            loadingAjax('Mengambil data petugas...');
            $.get("{{ url('/petugas/get') }}", { nip: nip }).done((data) => {
                $('#nip').val(data.nip).prop('readonly', true);
                $('#nama').val(data.nama);
                $('#jk').val(data.jk);
                $('#kd_jbtn').val(data.kd_jbtn);
                $('#tmp_lahir').val(data.tmp_lahir);
                $('#tgl_lahir').val(data.tgl_lahir);
                $('#gol_darah').val(data.gol_darah);
                $('#agama').val(data.agama);
                $('#stts_nikah').val(data.stts_nikah);
                $('#alamat').val(data.alamat);
                $('#no_telp').val(data.no_telp);
                $('#status').val(data.status);

                $('#btnSimpanPetugas').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Update Data Petugas');
                Swal.close();
            });
        }

        function deletePetugas(nip, nama) {
            Swal.fire({
                title: 'Hapus Petugas?',
                html: `Apakah Anda yakin ingin menghapus <b>${nama}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus data...');
                    $.ajax({
                        url: `{{ url('/petugas') }}/${nip}`,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: (response) => {
                            showToast(response.message);
                            renderTablePetugas();
                        },
                        error: (xhr) => {
                            showToast('Gagal menghapus data petugas', 'error');
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

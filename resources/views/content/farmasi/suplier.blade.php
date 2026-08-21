@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title text-primary fw-bold mb-0">
                        <i class="ti ti-truck me-2 text-primary fs-2"></i> Data Supplier / Distributor Obat & BHP
                    </h3>
                    <div class="text-muted small mt-1">Kelola data distributor, alamat, nomor telepon, dan informasi rekening supplier</div>
                </div>
                <button type="button" class="btn btn-primary" onclick="openModalTambahSuplier()">
                    <i class="ti ti-plus me-1"></i> Tambah Supplier Baru
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle nowrap w-100" id="tbSuplier">
                        <thead>
                            <tr>
                                <th style="width: 10%">Kode</th>
                                <th style="width: 25%">Nama Supplier</th>
                                <th style="width: 25%">Alamat & Kota</th>
                                <th style="width: 15%">No. Telepon</th>
                                <th style="width: 15%">Bank & Rekening</th>
                                <th class="text-center" style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah/Edit Supplier -->
    <div class="modal fade" id="modalSuplier" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modal_suplier_title"><i class="ti ti-truck me-2 text-white"></i> Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formSuplier">
                    @csrf
                    <input type="hidden" id="is_edit_suplier" value="0">
                    <div class="modal-body p-4">
                        <div class="row row-cards">
                            <div class="col-md-4">
                                <label class="form-label required">Kode Supplier</label>
                                <div class="input-group">
                                    <input type="text" class="form-control font-monospace fw-bold" name="kode_suplier" id="kode_suplier" placeholder="S0001" maxlength="5" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="generateKodeSuplier()" title="Generate Kode Otomatis">
                                        <i class="ti ti-refresh"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label required">Nama Supplier / PT</label>
                                <input type="text" class="form-control" name="nama_suplier" id="nama_suplier" placeholder="PT. Kimia Farma Trading & Distribution" maxlength="50" required>
                            </div>
                            <div class="col-md-8 mt-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Jl. Sudirman No. 123" maxlength="50">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">Kota / Kabupaten</label>
                                <input type="text" class="form-control" name="kota" id="kota" placeholder="Jakarta Pusat" maxlength="20">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">No. Telepon / HP</label>
                                <input type="text" class="form-control" name="no_telp" id="no_telp" placeholder="021-12345678" maxlength="13">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">Nama Bank</label>
                                <input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Bank Mandiri / BCA" maxlength="30">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label">Nomor Rekening</label>
                                <input type="text" class="form-control" name="rekening" id="rekening" placeholder="123-00-0123456-7" maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_suplier">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Data Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(() => {
            renderTableSuplier();

            $('#formSuplier').on('submit', function (e) {
                e.preventDefault();
                saveSuplier();
            });
        });

        function renderTableSuplier() {
            $('#tbSuplier').DataTable({
                responsive: true,
                serverSide: false,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/suplier/data') }}",
                    dataSrc: ""
                },
                columns: [
                    { 
                        data: 'kode_suplier',
                        render: (data) => `<span class="badge bg-blue-lt font-monospace px-2 py-1 fs-4">${data}</span>`
                    },
                    { 
                        data: 'nama_suplier',
                        render: (data) => `<div class="fw-bold text-dark fs-3">${data}</div>`
                    },
                    { 
                        data: null,
                        render: (row) => {
                            let text = row.alamat || '-';
                            if (row.kota) text += `, ${row.kota}`;
                            return `<div class="text-secondary"><i class="ti ti-map-pin me-1 text-muted"></i>${text}</div>`;
                        }
                    },
                    { 
                        data: 'no_telp',
                        render: (data) => data ? `<a href="tel:${data}" class="text-decoration-none text-dark"><i class="ti ti-phone me-1 text-success"></i>${data}</a>` : '-'
                    },
                    { 
                        data: null,
                        render: (row) => {
                            if (!row.nama_bank && !row.rekening) return '-';
                            return `<div class="badge bg-light text-dark border px-2 py-1 text-start"><i class="ti ti-building-bank me-1 text-primary"></i>${row.nama_bank || ''} - ${row.rekening || ''}</div>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: (data) => {
                            return `
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-warning" onclick="editSuplier('${data.kode_suplier}')" title="Edit Supplier">
                                        <i class="ti ti-pencil me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteSuplier('${data.kode_suplier}', '${data.nama_suplier}')" title="Hapus Supplier">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });
        }

        function generateKodeSuplier() {
            if ($('#is_edit_suplier').val() === '1') return;
            $.get("{{ url('/suplier/get-next-kode') }}").done((res) => {
                if (res && res.kode_suplier) {
                    $('#kode_suplier').val(res.kode_suplier);
                }
            });
        }

        function openModalTambahSuplier() {
            $('#formSuplier').trigger('reset');
            $('#is_edit_suplier').val('0');
            $('#kode_suplier').prop('readonly', false);
            $('#modal_suplier_title').html('<i class="ti ti-truck me-2 text-white"></i> Tambah Supplier Baru');
            $('#btn_save_suplier').html('<i class="ti ti-device-floppy me-1"></i> Simpan Data Supplier');
            generateKodeSuplier();
            $('#modalSuplier').modal('show');
        }

        function editSuplier(kode_suplier) {
            loadingAjax('Memuat data supplier...');
            $.get(`{{ url('/suplier/show') }}/${kode_suplier}`)
                .done((data) => {
                    Swal.close();
                    if (!data) return;

                    $('#formSuplier').trigger('reset');
                    $('#is_edit_suplier').val('1');
                    $('#kode_suplier').val(data.kode_suplier).prop('readonly', true);
                    $('#nama_suplier').val(data.nama_suplier);
                    $('#alamat').val(data.alamat || '');
                    $('#kota').val(data.kota || '');
                    $('#no_telp').val(data.no_telp || '');
                    $('#nama_bank').val(data.nama_bank || '');
                    $('#rekening').val(data.rekening || '');

                    $('#modal_suplier_title').html('<i class="ti ti-pencil me-2 text-white"></i> Edit Data Supplier (' + data.kode_suplier + ')');
                    $('#btn_save_suplier').html('<i class="ti ti-device-floppy me-1"></i> Update Data Supplier');
                    $('#modalSuplier').modal('show');
                })
                .fail((xhr) => {
                    Swal.close();
                    showToast('Gagal memuat data supplier: ' + (xhr.responseJSON?.message || 'Error'), 'error');
                });
        }

        function saveSuplier() {
            const isEdit = $('#is_edit_suplier').val() === '1';
            const data = {
                _token: "{{ csrf_token() }}",
                is_edit: $('#is_edit_suplier').val(),
                kode_suplier: $('#kode_suplier').val(),
                nama_suplier: $('#nama_suplier').val(),
                alamat: $('#alamat').val(),
                kota: $('#kota').val(),
                no_telp: $('#no_telp').val(),
                nama_bank: $('#nama_bank').val(),
                rekening: $('#rekening').val()
            };

            loadingAjax(isEdit ? 'Meng-update data supplier...' : 'Menyimpan supplier baru...');

            $.ajax({
                url: "{{ url('/suplier/store') }}",
                type: 'POST',
                data: data,
                success: (response) => {
                    showToast(response.message);
                    $('#modalSuplier').modal('hide');
                    renderTableSuplier();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON?.message || 'Gagal menyimpan supplier', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function deleteSuplier(kode_suplier, nama_suplier) {
            Swal.fire({
                title: 'Hapus Supplier?',
                html: `Apakah Anda yakin ingin menghapus data supplier <b>${nama_suplier}</b> (${kode_suplier})?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus data supplier...');
                    $.ajax({
                        url: `{{ url('/suplier/delete') }}/${kode_suplier}`,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: (response) => {
                            showToast(response.message);
                            renderTableSuplier();
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON?.message || 'Gagal menghapus supplier', 'error');
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

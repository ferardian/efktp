@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-3">
            <!-- Left Side: Table List -->
            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h4 class="card-title mb-0">Master Tarif Rawat Jalan</h4>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Kategori</label>
                                <select class="form-select form-select-sm" id="filter_kategori" onchange="$('#tbTarif').DataTable().ajax.reload()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->kd_kategori }}">{{ $k->nm_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Penjamin (Cara Bayar)</label>
                                <select class="form-select form-select-sm" id="filter_pj" onchange="$('#tbTarif').DataTable().ajax.reload()">
                                    <option value="">Semua Penjamin</option>
                                    @foreach($penjab as $pj)
                                        <option value="{{ $pj->kd_pj }}">{{ $pj->png_jawab }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Poliklinik</label>
                                <select class="form-select form-select-sm" id="filter_poli" onchange="$('#tbTarif').DataTable().ajax.reload()">
                                    <option value="">Semua Poliklinik</option>
                                    @foreach($poliklinik as $pl)
                                        <option value="{{ $pl->kd_poli }}">{{ $pl->nm_poli }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Status</label>
                                <select class="form-select form-select-sm" id="filter_status" onchange="$('#tbTarif').DataTable().ajax.reload()">
                                    <option value="">Semua</option>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="d-flex gap-2 mb-3 bg-light p-2 rounded-2 align-items-center">
                            <span class="small fw-semibold text-muted ms-1"><i class="ti ti-settings me-1"></i> Aksi Massal:</span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkDeleteTarif()">
                                <i class="ti ti-trash me-1"></i> Non-aktifkan Terpilih
                            </button>
                            <button type="button" class="btn btn-sm btn-danger ms-auto" onclick="deactivateAllTarif()">
                                <i class="ti ti-alert-triangle me-1"></i> Non-aktifkan Semua
                            </button>
                        </div>

                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbTarif">
                                <thead>
                                    <tr>
                                        <th width="30px"><input type="checkbox" class="form-check-input" id="checkAllTarif"></th>
                                        <th>Kode</th>
                                        <th>Nama Perawatan</th>
                                        <th>Kategori</th>
                                        <th>Cara Bayar</th>
                                        <th>Poliklinik</th>
                                        <th>Total (Dr)</th>
                                        <th>Total (Pr)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary shadow-sm" id="btnCreateTarif" onclick="resetFormTarif()">
                                    <i class="ti ti-plus me-2"></i> Tambah Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="col-lg-5 col-xl-5 col-md-12 col-sm-12">
                <form id="formTarif">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3" id="formTitle">Form Data Tarif Rawat Jalan</h5>
                            
                            <!-- Basic Information Section -->
                            <div class="mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Kode Tindakan</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light" id="kd_jenis_prw" name="kd_jenis_prw" placeholder="Memuat kode..." readonly required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="generateKode()" id="btnGenKode" title="Generate ulang kode baru">
                                                <i class="ti ti-reload"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status Tarif</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="1">Aktif</option>
                                            <option value="0">Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Tindakan/Perawatan</label>
                                <input type="text" class="form-control" id="nm_perawatan" name="nm_perawatan" placeholder="Contoh: Konsultasi Dokter Umum" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Perawatan</label>
                                <select class="form-select" id="kd_kategori" name="kd_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->kd_kategori }}">{{ $k->nm_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Penjamin (Cara Bayar)</label>
                                        <select class="form-select" id="kd_pj" name="kd_pj" required>
                                            <option value="">Pilih Penjamin</option>
                                            @foreach($penjab as $pj)
                                                <option value="{{ $pj->kd_pj }}">{{ $pj->png_jawab }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Poliklinik</label>
                                        <select class="form-select" id="kd_poli" name="kd_poli" required>
                                            <option value="">Pilih Poliklinik</option>
                                            @foreach($poliklinik as $pl)
                                                <option value="{{ $pl->kd_poli }}">{{ $pl->nm_poli }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 text-muted">
                            <h6 class="text-secondary mb-3"><i class="ti ti-calculator me-1"></i> Rincian Biaya/Tarif (IDR)</h6>

                            <!-- Costs Input Fields -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Jasa Sarana/Material</label>
                                    <input type="number" class="form-control fee-input" id="material" name="material" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Bahan Habis Pakai (BHP)</label>
                                    <input type="number" class="form-control fee-input" id="bhp" name="bhp" value="0" min="0">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Jasa Medis Dokter</label>
                                    <input type="number" class="form-control fee-input" id="tarif_tindakandr" name="tarif_tindakandr" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Jasa Medis Perawat</label>
                                    <input type="number" class="form-control fee-input" id="tarif_tindakanpr" name="tarif_tindakanpr" value="0" min="0">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small text-muted">KSO</label>
                                    <input type="number" class="form-control fee-input" id="kso" name="kso" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Manajemen / Jasa RS</label>
                                    <input type="number" class="form-control fee-input" id="menejemen" name="menejemen" value="0" min="0">
                                </div>
                            </div>

                            <!-- Calculated Totals (Disabled) -->
                            <div class="bg-light p-3 rounded-3 border mb-3">
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-6">
                                        <span class="small fw-semibold text-muted">Total Tarif Dokter</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <input type="text" class="form-control form-control-sm text-end fw-bold text-primary bg-white border-0" id="total_byrdr" value="0" readonly>
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-6">
                                        <span class="small fw-semibold text-muted">Total Tarif Perawat</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <input type="text" class="form-control form-control-sm text-end fw-bold text-success bg-white border-0" id="total_byrpr" value="0" readonly>
                                    </div>
                                </div>
                                <div class="row g-2 align-items-center">
                                    <div class="col-6">
                                        <span class="small fw-semibold text-muted">Total Tarif Dr & Pr</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <input type="text" class="form-control form-control-sm text-end fw-bold text-dark bg-white border-0" id="total_byrdrpr" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <button type="button" class="btn btn-success w-100 shadow-sm" id="btnSimpanTarif" onclick="simpanTarif()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Tarif Baru
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
            renderTableTarif();
            generateKode();

            // Set up auto calculation listeners on input typing
            $('.fee-input').on('input', function() {
                if ($(this).val() < 0) {
                    $(this).val(0);
                }
                calculateTotals();
            });
        });

        // Autocomplete/Generate next code from DB
        function generateKode() {
            if ($('#kd_jenis_prw').prop('readonly') && $('#kd_jenis_prw').val() !== '' && $('#formTitle').text().includes('Edit')) {
                // Do not overwrite code during editing mode
                return;
            }
            $.get("{{ url('/master/tarif-ralan/get-next-kode') }}", (data) => {
                $('#kd_jenis_prw').val(data.next_kode);
                $('#kd_jenis_prw').prop('readonly', false); // Allow override if needed, but prefilled
            });
        }

        // Auto calculate totals based on inputs
        function calculateTotals() {
            const material = parseFloat($('#material').val()) || 0;
            const bhp = parseFloat($('#bhp').val()) || 0;
            const dr = parseFloat($('#tarif_tindakandr').val()) || 0;
            const pr = parseFloat($('#tarif_tindakanpr').val()) || 0;
            const kso = parseFloat($('#kso').val()) || 0;
            const menejemen = parseFloat($('#menejemen').val()) || 0;

            const totalDr = material + bhp + dr + kso + menejemen;
            const totalPr = material + bhp + pr + kso + menejemen;
            const totalDrPr = material + bhp + dr + pr + kso + menejemen;

            $('#total_byrdr').val(formatRupiah(totalDr));
            $('#total_byrpr').val(formatRupiah(totalPr));
            $('#total_byrdrpr').val(formatRupiah(totalDrPr));
        }

        // Format currency helper
        function formatRupiah(number) {
            const val = parseFloat(number);
            if (isNaN(val)) return 'Rp0';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
        }

        function renderTableTarif() {
            $('#tbTarif').DataTable({
                responsive: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                ajax: {
                    url: "{{ url('/jenis-perawatan/table') }}",
                    type: "GET",
                    data: function(d) {
                        d.kd_kategori = $('#filter_kategori').val();
                        d.kd_pj = $('#filter_pj').val();
                        d.kd_poli = $('#filter_poli').val();
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
                            return `<input type="checkbox" class="form-check-input row-select" value="${row.kd_jenis_prw}">`;
                        }
                    },
                    { data: 'kd_jenis_prw', name: 'kd_jenis_prw' },
                    { data: 'nm_perawatan', name: 'nm_perawatan' },
                    { 
                        data: 'kategori.nm_kategori', 
                        name: 'kategori.nm_kategori',
                        defaultContent: '-'
                    },
                    { 
                        data: 'penjab.png_jawab', 
                        name: 'penjab.png_jawab',
                        defaultContent: '-'
                    },
                    { 
                        data: 'poliklinik.nm_poli', 
                        name: 'poliklinik.nm_poli',
                        defaultContent: '-'
                    },
                    { 
                        data: 'total_byrdr', 
                        name: 'total_byrdr',
                        render: (data) => formatRupiah(data)
                    },
                    { 
                        data: 'total_byrpr', 
                        name: 'total_byrpr',
                        render: (data) => formatRupiah(data)
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (data) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-icon btn-warning text-white" onclick="editTarif('${data.kd_jenis_prw}')" title="Edit Tarif">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-danger" onclick="deleteTarif('${data.kd_jenis_prw}', '${data.nm_perawatan}')" title="Hapus Tarif">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        }

        function resetFormTarif() {
            $('#formTarif').trigger('reset');
            $('#kd_jenis_prw').prop('readonly', false);
            $('#formTitle').text('Form Data Tarif Rawat Jalan');
            $('#btnSimpanTarif').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan Tarif Baru');
            generateKode();
            calculateTotals();
        }

        function simpanTarif() {
            const kd_jenis_prw = $('#kd_jenis_prw').val();
            const isEdit = $('#formTitle').text().includes('Edit');
            const url = isEdit ? `{{ url('/master/tarif-ralan') }}/${kd_jenis_prw}` : "{{ url('/master/tarif-ralan') }}";
            const method = isEdit ? 'PUT' : 'POST';

            if (!kd_jenis_prw || !$('#nm_perawatan').val() || !$('#kd_kategori').val() || !$('#kd_pj').val() || !$('#kd_poli').val()) {
                showToast('Mohon lengkapi seluruh field formulir yang bertanda wajib.', 'warning');
                return;
            }

            loadingAjax('Sedang memproses data tarif...');
            
            $.ajax({
                url: url,
                type: method,
                data: $('#formTarif').serialize(),
                success: (response) => {
                    showToast(response.message);
                    renderTableTarif();
                    resetFormTarif();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal memproses data tarif', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function editTarif(kd_jenis_prw) {
            loadingAjax('Mengambil rincian data tarif...');
            // Fetch complete row details through DataTables API (state) or make call
            // We can fetch by searching for matching data row or just drawing from API, but since DataTable table is loaded serverSide, we will fetch from our local instance if possible or API
            // Let's call dataTable route with specific key to get item
            $.get("{{ url('/jenis-perawatan/table') }}", { search: { value: kd_jenis_prw } }).done((res) => {
                Swal.close();
                if (res.data && res.data.length > 0) {
                    const data = res.data.find(x => x.kd_jenis_prw === kd_jenis_prw) || res.data[0];
                    $('#kd_jenis_prw').val(data.kd_jenis_prw).prop('readonly', true);
                    $('#nm_perawatan').val(data.nm_perawatan);
                    $('#kd_kategori').val(data.kd_kategori);
                    $('#kd_pj').val(data.kd_pj);
                    $('#kd_poli').val(data.kd_poli);
                    $('#status').val(data.status);

                    $('#material').val(data.material);
                    $('#bhp').val(data.bhp);
                    $('#tarif_tindakandr').val(data.tarif_tindakandr);
                    $('#tarif_tindakanpr').val(data.tarif_tindakanpr);
                    $('#kso').val(data.kso);
                    $('#menejemen').val(data.menejemen);

                    $('#formTitle').text('Edit Data Tarif Rawat Jalan');
                    $('#btnSimpanTarif').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Perbarui Tarif');
                    calculateTotals();
                } else {
                    showToast('Gagal memuat rincian tarif', 'error');
                }
            });
        }

        function deleteTarif(kd_jenis_prw, nm_perawatan) {
            Swal.fire({
                title: 'Hapus Tarif Rawat Jalan?',
                html: `Apakah Anda yakin ingin menghapus tarif untuk <b>${nm_perawatan}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus tarif...');
                    $.ajax({
                        url: `{{ url('/master/tarif-ralan') }}/${kd_jenis_prw}`,
                        type: 'DELETE',
                        success: (response) => {
                            showToast(response.message);
                            renderTableTarif();
                        },
                        error: (xhr) => {
                            showToast('Gagal menghapus tarif', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }

        // Select/deselect all row checkboxes when header checkbox is clicked
        $('#checkAllTarif').on('change', function() {
            $('.row-select').prop('checked', this.checked);
        });

        // If any row checkbox is unchecked, uncheck the header checkbox
        $(document).on('change', '.row-select', function() {
            if ($('.row-select:checked').length === $('.row-select').length && $('.row-select').length > 0) {
                $('#checkAllTarif').prop('checked', true);
            } else {
                $('#checkAllTarif').prop('checked', false);
            }
        });

        function bulkDeleteTarif() {
            const selectedIds = [];
            $('.row-select:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showToast('Mohon pilih minimal satu tarif untuk dinonaktifkan.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Non-aktifkan Tarif Terpilih?',
                html: `Apakah Anda yakin ingin menonaktifkan/menghapus <b>${selectedIds.length}</b> tarif terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Memproses tindakan massal...');
                    $.ajax({
                        url: "{{ url('/master/tarif-ralan/bulk-delete') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: selectedIds
                        },
                        success: (response) => {
                            showToast(response.message);
                            $('#checkAllTarif').prop('checked', false);
                            $('#tbTarif').DataTable().ajax.reload();
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

        function deactivateAllTarif() {
            Swal.fire({
                title: 'Non-aktifkan Seluruh Tarif?',
                html: 'Apakah Anda yakin ingin menonaktifkan <b>seluruh</b> tarif rawat jalan aktif di database?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Non-aktifkan Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Memproses penonaktifan semua tarif...');
                    $.ajax({
                        url: "{{ url('/master/tarif-ralan/deactivate-all') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: (response) => {
                            showToast(response.message);
                            $('#checkAllTarif').prop('checked', false);
                            $('#tbTarif').DataTable().ajax.reload();
                        },
                        error: (xhr) => {
                            showToast('Gagal menonaktifkan seluruh tarif', 'error');
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

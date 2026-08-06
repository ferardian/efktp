@extends('layout')

@section('body')
<div class="container-xl">
    <!-- Header Title & Action -->
    <div class="page-header d-print-none mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title text-primary">
                    <i class="ti ti-users-group me-2"></i> Kegiatan & Club Prolanis BPJS PCare
                </h2>
                <div class="text-muted mt-1">Kelola data Club Prolanis, Edukasi Kelompok, dan Peserta Kegiatan yang terintegrasi dengan BPJS PCare.</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button class="btn btn-primary" onclick="openModalTambahKegiatan()">
                    <i class="ti ti-plus me-1"></i> Tambah Kegiatan
                </button>
            </div>
        </div>
    </div>

    <!-- Stat Cards Summary -->
    <div class="row row-cards mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <i class="ti ti-building-community icon"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium" id="statClub">0</div>
                            <div class="text-muted">Club Prolanis Active</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar">
                                <i class="ti ti-calendar-event icon"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium" id="statKegiatan">0</div>
                            <div class="text-muted">Kegiatan Bulan Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-warning text-white avatar">
                                <i class="ti ti-user-check icon"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium" id="statPeserta">0</div>
                            <div class="text-muted">Total Peserta Edukasi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-info text-white avatar">
                                <i class="ti ti-cash icon"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium" id="statBiaya">Rp 0</div>
                            <div class="text-muted">Estimasi Biaya Kegiatan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="card shadow-sm">
        <div class="card-header border-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tab-kegiatan" class="nav-item active" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <i class="ti ti-calendar-event me-1"></i> Kegiatan Kelompok
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tab-club" class="nav-item" data-bs-toggle="tab" aria-selected="false" role="tab" onclick="loadClubProlanis()">
                        <i class="ti ti-building-community me-1"></i> Club Prolanis
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <!-- TAB 1: KEGIATAN KELOMPOK -->
                <div class="tab-pane active show" id="tab-kegiatan" role="tabpanel">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label text-muted small mb-1">Filter Tanggal / Bulan Pelayanan:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="text" class="form-control datepicker" id="filterBulanKegiatan" value="{{ date('d-m-Y') }}">
                                <button class="btn btn-outline-primary" onclick="loadKegiatanKelompok()">
                                    <i class="ti ti-search me-1"></i> Cari Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter table-bordered" id="tabelKegiatanKelompok" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Edukasi</th>
                                    <th>Nama Club</th>
                                    <th>Kelompok / Program</th>
                                    <th>Kegiatan</th>
                                    <th>Tgl Pelayanan</th>
                                    <th>Materi & Pembicara</th>
                                    <th>Lokasi</th>
                                    <th>Biaya</th>
                                    <th class="text-center" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm me-2 text-primary"></div> Memuat data kegiatan kelompok dari BPJS...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: CLUB PROLANIS -->
                <div class="tab-pane" id="tab-club" role="tabpanel">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label text-muted small mb-1">Pilih Jenis Program Prolanis:</label>
                            <select class="form-select" id="filterKdProgram" onchange="loadClubProlanis()">
                                <option value="01" selected>01 - Diabetes Melitus (DM)</option>
                                <option value="02">02 - Hipertensi (HT)</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter table-bordered" id="tabelClubProlanis" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Club ID</th>
                                    <th>Nama Club</th>
                                    <th>Program</th>
                                    <th>Ketua & Kontak</th>
                                    <th>Alamat</th>
                                    <th>Tanggal Mulai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Silahkan pilih tab untuk memuat data club.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KEGIATAN KELOMPOK -->
<div class="modal modal-blur fade" id="modalTambahKegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-plus me-1 text-primary"></i> Tambah Kegiatan Kelompok Prolanis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahKegiatan">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Kelompok / Program</label>
                            <select class="form-select" name="kdKelompok" id="formKdKelompok" required onchange="updateClubDropdown()">
                                <option value="01">01 - Diabetes Melitus</option>
                                <option value="02">02 - Hipertensi</option>
                                <option value="03">03 - Asthma</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Club Prolanis <span class="text-danger">*</span></label>
                            <input type="hidden" id="clubInputMode" value="select">
                            <div id="clubSelectContainer">
                                <select class="form-select" id="formClubIdSelect">
                                    <option value="">-- Memuat Club Prolanis --</option>
                                </select>
                            </div>
                            <input type="text" class="form-control d-none" id="formManualClubId" placeholder="Ketik ID / Nama Club (misal: 34)">
                            <input type="hidden" name="clubId" id="formFinalClubId">
                            <input type="hidden" name="namaClub" id="formNamaClub">
                            <small class="form-hint text-muted" id="clubHintText">Diambil otomatis dari data Club BPJS</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jenis Kegiatan</label>
                            <select class="form-select" name="kdKegiatan" required>
                                <option value="01">01 - Senam</option>
                                <option value="10">10 - Penyuluhan</option>
                                <option value="11" selected>11 - Penyuluhan dan Senam</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Pelayanan</label>
                            <input type="text" class="form-control datepicker" name="tglPelayanan" id="formTglPelayanan" value="{{ date('d-m-Y') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Materi Edukasi</label>
                            <input type="text" class="form-control" name="materi" placeholder="Contoh: Edukasi Pola Makan DM" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Narasumber / Pembicara</label>
                            <input type="text" class="form-control" name="pembicara" placeholder="Contoh: dr. Ryan Ardana Putra" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Lokasi Kegiatan</label>
                            <input type="text" class="form-control" name="lokasi" placeholder="Contoh: Aula FKTP / Lapangan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Biaya Kegiatan (Rp)</label>
                            <input type="number" class="form-control" name="biaya" placeholder="0" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" name="keterangan" rows="2" placeholder="Catatan kegiatan opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan Kegiatan BPJS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PESERTA KEGIATAN -->
<div class="modal modal-blur fade" id="modalPesertaKegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-users me-1 text-primary"></i> Peserta Kegiatan: <span id="labelEduId" class="badge bg-primary-lt"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Info Summary Kegiatan -->
                <div class="card card-sm mb-3 bg-light border-0">
                    <div class="card-body">
                        <div class="row text-muted small">
                            <div class="col-md-3"><strong>Club:</strong> <span id="infoNamaClub">-</span></div>
                            <div class="col-md-3"><strong>Kegiatan:</strong> <span id="infoNmKegiatan">-</span></div>
                            <div class="col-md-3"><strong>Tgl Pelayanan:</strong> <span id="infoTglPelayanan">-</span></div>
                            <div class="col-md-3"><strong>Pembicara:</strong> <span id="infoPembicara">-</span></div>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah Peserta -->
                <form id="formTambahPeserta" class="row g-2 mb-3 align-items-center">
                    <input type="hidden" id="pesertaEduId" name="eduId">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-id-badge-2"></i></span>
                            <input type="text" class="form-control" id="inputNoKartuPeserta" name="noKartu" placeholder="Ketik No. Kartu BPJS Peserta (13 digit)..." required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="inputNamaPeserta" name="nama" placeholder="Nama Peserta (Opsional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100"><i class="ti ti-user-plus me-1"></i> Tambah</button>
                    </div>
                </form>

                <!-- Tabel Peserta -->
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter table-bordered" id="tabelPesertaKegiatan" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>No. Kartu BPJS</th>
                                <th>Nama Peserta</th>
                                <th>Jenis Kelamin</th>
                                <th>Tgl Lahir</th>
                                <th>No. HP</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Silahkan buka detail kegiatan untuk melihat peserta.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let listClubCache = [];

    $(document).ready(function() {
        if ($.fn.datepicker) {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        }
        loadKegiatanKelompok();
        loadClubProlanis();
    });

    // 1. LOAD KEGIATAN KELOMPOK
    function loadKegiatanKelompok() {
        const bulan = $('#filterBulanKegiatan').val() || '{{ date("d-m-Y") }}';
        const tbody = $('#tabelKegiatanKelompok tbody');
        
        tbody.html(`<tr><td colspan="9" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2 text-primary"></div> Memuat kegiatan kelompok BPJS (${bulan})...</td></tr>`);

        $.get(`{{ url('/bridging/pcare/kelompok/kegiatan') }}/${bulan}`).done(function(res) {
            const list = res?.response?.list || [];
            let html = '';
            let totalBiaya = 0;
            let totalPeserta = 0;

            if (list.length > 0) {
                list.forEach(item => {
                    totalBiaya += parseInt(item.biaya || 0);
                    const club = item.clubProl || {};
                    const keg = item.kegiatan || {};
                    const kel = item.kelompok || {};

                    html += `
                        <tr>
                            <td><strong class="text-primary">${item.eduId}</strong></td>
                            <td>${club.nama || '-'}</td>
                            <td><span class="badge bg-blue-lt">${kel.nama || '-'} (${kel.kode || '-'})</span></td>
                            <td><span class="badge bg-green-lt">${keg.nama || '-'} (${keg.kode || '-'})</span></td>
                            <td>${item.tglPelayanan || '-'}</td>
                            <td>
                                <div><strong>${item.materi || '-'}</strong></div>
                                <small class="text-muted"><i class="ti ti-user me-1"></i>${item.pembicara || '-'}</small>
                            </td>
                            <td>${item.lokasi || '-'}</td>
                            <td>Rp ${parseInt(item.biaya || 0).toLocaleString('id-ID')}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-info btn-icon" title="Lihat Peserta" onclick='openModalPeserta(${JSON.stringify(item)})'>
                                        <i class="ti ti-users"></i>
                                    </button>
                                    <button class="btn btn-danger btn-icon" title="Hapus Kegiatan" onclick="hapusKegiatan('${item.eduId}')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                $('#statKegiatan').text(list.length);
                $('#statBiaya').text(`Rp ${totalBiaya.toLocaleString('id-ID')}`);
            } else {
                html = `<tr><td colspan="9" class="text-center py-4 text-muted"><i class="ti ti-alert-circle me-1"></i> Tidak ada kegiatan kelompok BPJS pada tanggal/bulan ${bulan}.</td></tr>`;
                $('#statKegiatan').text(0);
                $('#statBiaya').text('Rp 0');
            }
            tbody.html(html);
        }).fail(function(err) {
            tbody.html(`<tr><td colspan="9" class="text-center py-4 text-danger">Gagal terhubung ke BPJS PCare.</td></tr>`);
        });
    }

    // 2. LOAD CLUB PROLANIS
    function loadClubProlanis() {
        const kdProgram = $('#filterKdProgram').val() || '01';
        const tbody = $('#tabelClubProlanis tbody');

        tbody.html(`<tr><td colspan="6" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2 text-primary"></div> Memuat daftar club prolanis...</td></tr>`);

        $.get(`{{ url('/bridging/pcare/kelompok/club') }}/${kdProgram}`).done(function(res) {
            const list = res?.response?.list || [];
            listClubCache = list;
            let html = '';

            if (list.length > 0) {
                list.forEach(item => {
                    const jns = item.jnsKelompok || {};
                    html += `
                        <tr>
                            <td><strong class="text-primary">${item.clubId}</strong></td>
                            <td><strong>${item.nama || '-'}</strong></td>
                            <td><span class="badge bg-purple-lt">${jns.nmProgram || '-'}</span></td>
                            <td>
                                <div><strong>${item.ketua_nama || '-'}</strong></div>
                                <small class="text-muted"><i class="ti ti-phone me-1"></i>${item.ketua_noHP || '-'}</small>
                            </td>
                            <td>${item.alamat || '-'}</td>
                            <td>${item.tglMulai || '-'}</td>
                        </tr>
                    `;
                });
                $('#statClub').text(list.length);
            } else {
                html = `<tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada Club Prolanis terdaftar untuk program ini.</td></tr>`;
                $('#statClub').text(0);
            }
            tbody.html(html);
            updateClubDropdown();
        });
    }

    // 3. UPDATE DROPDOWN CLUB DI MODAL TAMBAH
    function updateClubDropdown() {
        const kdKelompok = $('#formKdKelompok').val() || '01';
        const selectContainer = $('#clubSelectContainer');
        const select = $('#formClubIdSelect');
        const manualInput = $('#formManualClubId');
        const hintText = $('#clubHintText');

        select.empty().append('<option value="">-- Memuat Club Prolanis --</option>');
        hintText.text('Mencari data Club Prolanis dari BPJS...');

        $.get(`{{ url('/bridging/pcare/kelompok/club') }}/${kdKelompok}`).done(function(res) {
            const list = res?.response?.list || [];

            if (list.length > 0) {
                $('#clubInputMode').val('select');
                selectContainer.removeClass('d-none');
                manualInput.addClass('d-none').prop('required', false);
                select.empty();

                list.forEach(item => {
                    select.append(`<option value="${item.clubId}" data-nama="${item.nama}">${item.clubId} - ${item.nama}</option>`);
                });

                const firstOpt = select.find('option:first');
                $('#formFinalClubId').val(firstOpt.val());
                $('#formNamaClub').val(firstOpt.data('nama') || '');
                hintText.text('Otomatis diambil dari data Club Prolanis BPJS');
            } else {
                // Jika BPJS mengembalikan list kosong (belum terdaftar club di BPJS): ijinkan input manual ID
                $('#clubInputMode').val('manual');
                selectContainer.addClass('d-none');
                manualInput.removeClass('d-none').prop('required', true);
                hintText.html('<span class="text-warning"><i class="ti ti-alert-triangle me-1"></i>Belum ada Club terdaftar di BPJS untuk program ini. Silahkan isi ID Club secara manual.</span>');
                $('#formNamaClub').val('Club Prolanis Manual');
                $('#formFinalClubId').val(manualInput.val());
            }
        }).fail(function() {
            $('#clubInputMode').val('manual');
            selectContainer.addClass('d-none');
            manualInput.removeClass('d-none').prop('required', true);
            hintText.html('<span class="text-warning"><i class="ti ti-alert-triangle me-1"></i>Gagal mengambil data Club. Silahkan isi ID Club secara manual.</span>');
            $('#formNamaClub').val('Club Prolanis Manual');
            $('#formFinalClubId').val(manualInput.val());
        });

        select.off('change').on('change', function() {
            const opt = $(this).find('option:selected');
            $('#formFinalClubId').val(opt.val());
            $('#formNamaClub').val(opt.data('nama') || '');
        });

        manualInput.off('input').on('input', function() {
            $('#formFinalClubId').val($(this).val());
        });
    }

    // 4. OPEN MODAL TAMBAH KEGIATAN
    function openModalTambahKegiatan() {
        $('#formTambahKegiatan')[0].reset();
        $('#modalTambahKegiatan').modal('show');
        updateClubDropdown();
    }

    // 5. SUBMIT FORM TAMBAH KEGIATAN
    $('#formTambahKegiatan').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();

        loadingAjax('Mengirim data kegiatan kelompok ke BPJS PCare...');
        $.post(`{{ url('/bridging/pcare/kelompok/kegiatan') }}`, data).done(function(res) {
            Swal.close();
            const code = res?.metaData?.code || res?.metadata?.code || 500;
            if (code == 201 || code == 200) {
                showToast('Berhasil menambahkan kegiatan kelompok BPJS!');
                $('#modalTambahKegiatan').modal('hide');
                loadKegiatanKelompok();
            } else {
                alertErrorBpjs(res);
            }
        }).fail(function(err) {
            Swal.close();
            alertErrorAjax(err);
        });
    });

    // 6. OPEN MODAL PESERTA KEGIATAN
    function openModalPeserta(item) {
        $('#labelEduId').text(item.eduId);
        $('#pesertaEduId').val(item.eduId);
        $('#infoNamaClub').text(item.clubProl?.nama || '-');
        $('#infoNmKegiatan').text(item.kegiatan?.nama || '-');
        $('#infoTglPelayanan').text(item.tglPelayanan || '-');
        $('#infoPembicara').text(item.pembicara || '-');
        
        $('#modalPesertaKegiatan').modal('show');
        loadPesertaKegiatan(item.eduId);
    }

    // 7. LOAD PESERTA KEGIATAN
    function loadPesertaKegiatan(eduId) {
        const tbody = $('#tabelPesertaKegiatan tbody');
        tbody.html(`<tr><td colspan="6" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2 text-primary"></div> Memuat daftar peserta kegiatan...</td></tr>`);

        $.get(`{{ url('/bridging/pcare/kelompok/peserta') }}/${eduId}`).done(function(res) {
            const list = res?.response?.list || [];
            let html = '';

            if (list.length > 0) {
                list.forEach(item => {
                    const pst = item.peserta || {};
                    html += `
                        <tr>
                            <td><strong class="text-primary">${pst.noKartu || '-'}</strong></td>
                            <td><strong>${pst.nama || '-'}</strong></td>
                            <td>${pst.sex == 'L' ? '<span class="badge bg-blue-lt">Laki-laki</span>' : '<span class="badge bg-pink-lt">Perempuan</span>'}</td>
                            <td>${pst.tglLahir || '-'}</td>
                            <td>${pst.noHP || '-'}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger btn-icon" title="Hapus Peserta" onclick="hapusPeserta('${eduId}', '${pst.noKartu}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('#statPeserta').text(list.length);
            } else {
                html = `<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada peserta terdaftar dalam kegiatan ini.</td></tr>`;
            }
            tbody.html(html);
        });
    }

    // 8. SUBMIT TAMBAH PESERTA
    $('#formTambahPeserta').on('submit', function(e) {
        e.preventDefault();
        const eduId = $('#pesertaEduId').val();
        const data = $(this).serialize();

        loadingAjax('Menambahkan peserta ke BPJS...');
        $.post(`{{ url('/bridging/pcare/kelompok/peserta') }}`, data).done(function(res) {
            Swal.close();
            const code = res?.metaData?.code || res?.metadata?.code || 500;
            if (code == 201 || code == 200) {
                showToast('Berhasil menambahkan peserta kegiatan!');
                $('#inputNoKartuPeserta').val('');
                $('#inputNamaPeserta').val('');
                loadPesertaKegiatan(eduId);
            } else {
                alertErrorBpjs(res);
            }
        }).fail(function(err) {
            Swal.close();
            alertErrorAjax(err);
        });
    });

    // 9. HAPUS KEGIATAN
    function hapusKegiatan(eduId) {
        Swal.fire({
            title: 'Hapus Kegiatan BPJS?',
            text: `Kegiatan dengan ID Edukasi ${eduId} akan dihapus dari BPJS PCare dan sistem lokal.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Menghapus kegiatan dari BPJS...');
                $.ajax({
                    url: `{{ url('/bridging/pcare/kelompok/kegiatan') }}/${eduId}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.close();
                        const code = res?.metaData?.code || res?.metadata?.code || 500;
                        if (code == 200 || code == 201) {
                            showToast('Kegiatan kelompok berhasil dihapus!');
                            loadKegiatanKelompok();
                        } else {
                            alertErrorBpjs(res);
                        }
                    },
                    error: function(err) {
                        Swal.close();
                        alertErrorAjax(err);
                    }
                });
            }
        });
    }

    // 10. HAPUS PESERTA
    function hapusPeserta(eduId, noKartu) {
        Swal.fire({
            title: 'Hapus Peserta Kegiatan?',
            text: `Peserta ${noKartu} akan dihapus dari kegiatan kelompok ini.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                loadingAjax('Menghapus peserta dari BPJS...');
                $.ajax({
                    url: `{{ url('/bridging/pcare/kelompok/peserta') }}/${eduId}/${noKartu}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        Swal.close();
                        const code = res?.metaData?.code || res?.metadata?.code || 500;
                        if (code == 200 || code == 201) {
                            showToast('Peserta berhasil dihapus!');
                            loadPesertaKegiatan(eduId);
                        } else {
                            alertErrorBpjs(res);
                        }
                    },
                    error: function(err) {
                        Swal.close();
                        alertErrorAjax(err);
                    }
                });
            }
        });
    }
</script>
@endpush

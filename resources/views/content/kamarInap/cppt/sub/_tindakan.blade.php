<form id="formTindakanRanap">
    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">Pelaksana</label>
            <div class="form-selectgroup">
                <label class="form-selectgroup-item">
                    <input type="radio" name="pelaksana_type" value="dr" class="form-selectgroup-input" checked>
                    <span class="form-selectgroup-label">Dokter</span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pelaksana_type" value="pr" class="form-selectgroup-input">
                    <span class="form-selectgroup-label">Petugas</span>
                </label>
                <label class="form-selectgroup-item">
                    <input type="radio" name="pelaksana_type" value="drpr" class="form-selectgroup-input">
                    <span class="form-selectgroup-label">Dokter & Petugas</span>
                </label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">Pilih Tindakan / Perawatan</label>
            <select class="form-select" name="kd_jenis_prw" id="selectTindakanRanap" style="width: 100%"></select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6" id="container_select_dokter">
            <label class="form-label">Dokter</label>
            <select class="form-select" name="kd_dokter" id="selectDokterTindakan" style="width: 100%"></select>
        </div>
        <div class="col-md-6" id="container_select_petugas" style="display: none;">
            <label class="form-label">Petugas</label>
            <select class="form-select" name="nip" id="selectPetugasTindakan" style="width: 100%"></select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Tanggal</label>
            <input type="text" class="form-control filterTangal" name="tgl_perawatan" id="tgl_tindakan" value="{{ date('d-m-Y') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Jam</label>
            <input type="text" class="form-control" name="jam_rawat" id="jam_tindakan" value="{{ date('H:i:s') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">&nbsp;</label>
            <button type="button" class="btn btn-primary w-100" id="btnSimpanTindakanRanap">
                <i class="ti ti-device-floppy me-2"></i> Simpan
            </button>
        </div>
    </div>
</form>

<hr class="my-3">
<div class="card bg-light-lt border-0 shadow-none mb-3">
    <div class="card-body p-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label mb-1 text-dark" style="font-size: 0.75rem; font-weight: 600;">Rentang Tanggal Riwayat</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control filterTanggal text-dark" id="tgl_awal_tindakan" autocomplete="off" placeholder="Tgl Awal" style="color: #000 !important;">
                    <span class="input-group-text text-dark">s.d</span>
                    <input type="text" class="form-control filterTanggal text-dark" id="tgl_akhir_tindakan" autocomplete="off" placeholder="Tgl Akhir" style="color: #000 !important;">
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-info btn-sm w-100" id="btnTampilkanRiwayatTindakan">
                    <i class="ti ti-filter me-1"></i> Tampilkan
                </button>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted small">Riwayat tindakan pasien</div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm table-striped table-hover" id="tabelRiwayatTindakan" width="100%">
        <thead>
            <tr>
                <th>Tgl & Jam</th>
                <th>Tindakan</th>
                <th>Pelaksana</th>
                <th>Nama Pelaksana</th>
                <th>Tarif</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

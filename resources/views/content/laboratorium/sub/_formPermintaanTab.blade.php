<form action="" id="formPermintaanLabTab">
    <fieldset class="form-fieldset">
        <div class="row gy-2 mb-2">
            <div class="col-lg-2 col-md-2 col-sm-12">
                <label for="noorder" class="form-label">No. Permintaan</label>
                <input type="text" class="form-control" name="noorder" id="noorderTab" />
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="no_rawat" class="form-label">No. Rawat</label>
                <input type="text" class="form-control" name="no_rawat" id="no_rawatTab" readonly />
                <input type="hidden" name="status" id="statusTab" />
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <label for="pasien" class="form-label">Pasien</label>
                <div class="input-group">
                    <input type="input" class="form-control form-control-sm" id="no_rkm_medisTab" name="no_rkm_medis" readonly />
                    <input type="input" class="form-control form-control-sm w-50" id="nm_pasienTab" name="nm_pasien" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="pasien" class="form-label">Tgl. Lahir/Umur</label>
                <input type="input" class="form-control form-control-sm" id="tgl_lahirTab" name="tgl_lahir" readonly />
            </div>
        </div>
        <div class="row gy-2 mb-2">
            <div class="col-lg-3 col-md-3 col-sm-12">
                <label for="dokter" class="form-label">Dokter</label>
                <div class="input-group">
                    <input type="input" class="form-control form-control-sm" id="kd_dokterTab" name="kd_dokter" readonly />
                    <input type="input" class="form-control form-control-sm w-50" id="nm_dokterTab" name="nm_dokter" readonly />
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <label for="poliklinik" class="form-label">Poliklinik</label>
                <div class="input-group">
                    <input type="input" class="form-control form-control-sm" id="kd_poliTab" name="kd_poli" readonly />
                    <input type="input" class="form-control form-control-sm w-50" id="nm_poliTab" name="nm_poli" readonly />
                </div>
            </div>
            <div class="col-lg-1 col-md-3 col-sm-12">
                <label for="status" class="form-label">Status</label>
                <input type="input" class="form-control form-control-sm" id="status_lanjutTab" name="status_lanjut" readonly />
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="diagnosa" class="form-label">Diagnosa</label>
                <input type="input" class="form-control form-control-sm" id="diagnosaTab" name="diagnosa" readonly />
            </div>
        </div>
        <div class="row gy-2 mb-2">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="diagnosa_klinis" class="form-label">Indikasi/Klinis</label>
                <input type="text" class="form-control" name="diagnosa_klinis" id="diagnosa_klinisTab" value="-" onfocus="removeZero(this)" onblur="isEmpty(this)" />
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="informasi_tambahan" class="form-label">Informasi Tambahan</label>
                <input type="text" class="form-control" name="informasi_tambahan" id="informasi_tambahanTab" value="-" onfocus="removeZero(this)" onblur="isEmpty(this)" />
            </div>
            <div class="col-md-12 col-sm-12">
                <label for="pemeriksaan" class="form-label">Pemeriksaan Lab</label>
                <select name="pemeriksaan" id="pemeriksaanTab" class="form-select" multiple style="width:100%"></select>
            </div>
        </div>
    </fieldset>
    <table class="table table-responsive table-bordered" id="tablePermintaanLabTab">
        <thead>
            <tr class="table-secondary">
                <th width="2%"><input type="checkbox" name="chekcItemLaborat" id="chekcItemLaboratTab" /></th>
                <th>Pemeriksaan</th>
                <th>Satuan</th>
                <th>Nilai Rujukan</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <table class="table table-responsive table-bordered table-striped d-none mt-2 table-hover" id="tableHasilPermintaanTab">
        <thead>
            <tr class="table-secondary">
                <th width="2%"></th>
                <th>No. Order</th>
                <th>Tanggal & Jam</th>
                <th>Informasi Tambahan</th>
                <th>Diagnosa Klinis</th>
                <th>Tgl & Jam Sample</th>
                <th>Tgl & Jam Hasil</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</form>

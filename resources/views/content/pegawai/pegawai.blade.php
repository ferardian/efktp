<form action="" id="formPegawai">
    <div class="row gy-2">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="nip" class="form-label mt-2">
                NIP (Nomor Induk Pegawai)
            </label>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12">
            <input type="text" class="form-control" id="nip" name="nip">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="nama" class="form-label mt-2">
                Nama
            </label>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12">
            <input type="text" class="form-control" id="nama" name="nama">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="nama" class="form-label mt-2">
                Jenis Kelamin
            </label>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12">
            <select name="jk" id="jk" class="form-select form-select-2">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="nama" class="form-label mt-2">
                Jabatan
            </label>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12">
            <select name="jnj_jabatan" id="jnj_jabatan" class="form-select form-select-2">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="nama" class="form-label mt-2">
                Jenjang Jabatan
            </label>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-12">
            <select name="jnj_jabatan" id="jnj_jabatan" class="form-select form-select-2">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

    </div>
</form>
@push('script')
    <script>
        let formPegawai = $('#formPegawai')
    </script>
@endpush

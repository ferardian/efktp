<h2 class="mb-4">Akun Pcare</h2>
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
@push('script')
    <script>
        const formSettingPcare = $('#formSettingPcare')
        const badgeLastUpdate = $('#badgeLastUpdate')

        function setSettingPcare() {
            const data = getDataForm('formSettingPcare', 'input');
            $.post(`/efktp/setting/pcare`, {
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
            $.get(`/efktp/setting/pcare`).done((response) => {
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
    </script>
@endpush

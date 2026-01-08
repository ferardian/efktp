@extends('layout')

@section('body')
    <div class="container">
        {{--        @if (session('status')) --}}
        {{--            <div class="alert alert-success alert-dismissible" role="alert"> --}}
        {{--                <div class="d-flex"> --}}
        {{--                    <div> --}}
        {{--                        <i class="ti ti-checklist"></i> --}}
        {{--                    </div> --}}
        {{--                    <div> --}}
        {{--                        <h4 class="alert-title">{{ session('status.title') }}</h4> --}}
        {{--                        <div class="text-secondary">{{ session('status.message') }}</div> --}}
        {{--                    </div> --}}
        {{--                </div> --}}
        {{--                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a> --}}
        {{--            </div> --}}
        {{--        @endif --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pegawai</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        @include('content.pegawai.pegawai')
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success" id="btnSettingPcare"><i class="ti ti-device-floppy"></i> Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // function validatePassword(password) {
        //     const hasUppercase = /[A-Z]/.test(password);
        //     const hasSymbol = /[!@#$%^&*()_+[\]{}|;:,.<>?]/.test(password);
        //     const hasNumber = /\d/.test(password);

        //     // Check if all criteria are met
        //     const isValid = hasUppercase && hasSymbol && hasNumber;

        //     return isValid;
        // }
        // $('#formPegawai').submit((e) => {

        //     const data = getDataForm('formPegawai', 'input')
        //     const isValidate = validatePassword(data.passPcare);

        //     if (!isValidate) {
        //         $('#formPegawai input[name=passPcare]').addClass('is-invalid')
        //         $('#errorPassword').html('Password harus terdapat huruf kapital [A-a], angka [0-9], dan symbol')
        //         return false
        //     }
        //     alertSuccessAjax();
        //     $('#formPegawai input[name=passPcare]').removeClass('is-invalid')
        // })
    </script>
@endpush

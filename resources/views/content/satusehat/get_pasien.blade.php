@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Cari Pasien Satu Sehat
                    </h2>
                    <div class="text-muted mt-1">Cek ketersediaan data pasien di server Satu Sehat Kemenkes menggunakan NIK</div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="inputNik" placeholder="Masukkan NIK Pasien (16 digit)..." maxlength="16">
                            <button class="btn btn-primary" type="button" id="btnCariPasien">
                                <i class="ti ti-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>

                <div id="resultContainer" class="mt-4 d-none">
                    <div class="alert alert-info d-none" id="alertInfo">
                        <i class="ti ti-info-circle me-1"></i> <span id="alertMessage"></span>
                    </div>
                    
                    <h5>Hasil Pencarian:</h5>
                    <pre id="jsonResult" class="bg-dark text-white p-3 border rounded" style="max-height: 500px; overflow: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#btnCariPasien').on('click', function() {
                let nik = $('#inputNik').val();
                if (nik.length !== 16) {
                    Swal.fire('Peringatan', 'NIK harus 16 digit!', 'warning');
                    return;
                }
                searchPatient(nik);
            });

            $('#inputNik').on('keypress', function(e) {
                if (e.which == 13) {
                    $('#btnCariPasien').click();
                }
            });
        });

        function searchPatient(nik) {
            loadingAjax();
            $('#resultContainer').addClass('d-none');
            $('#alertInfo').addClass('d-none');

            $.get("{{ url('satusehat/pasien/data') }}", { nik: nik }, function(response) {
                loadingAjax().close();
                $('#resultContainer').removeClass('d-none');
                
                if (response.status) {
                    let data = response.data;
                    $('#jsonResult').text(JSON.stringify(data, null, 4));
                    
                    if (data.total > 0) {
                        $('#alertMessage').text('Pasien ditemukan di Satu Sehat.');
                        $('#alertInfo').removeClass('d-none alert-danger').addClass('alert-info');
                    } else {
                        $('#alertMessage').text('Pasien tidak ditemukan atau belum terdaftar di Satu Sehat.');
                        $('#alertInfo').removeClass('d-none alert-info').addClass('alert-danger');
                    }
                } else {
                    $('#jsonResult').text(JSON.stringify(response.data, null, 4));
                    $('#alertMessage').text('Terjadi kesalahan saat memanggil API Satu Sehat.');
                    $('#alertInfo').removeClass('d-none alert-info').addClass('alert-danger');
                }
            }).fail(function(err) {
                loadingAjax().close();
                Swal.fire('Error', 'Gagal memanggil API internal.', 'error');
            });
        }
    </script>
@endpush

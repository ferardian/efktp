@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @include('content.registrasi._tabelRegistrasi')
            </div>
            <div class="card-footer">
                <form action="registrasi/get" method="get" id="formFilterRegistrasi">
                    <div class="row d-none-sm d-none-md gy-2">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="input-group">
                                <input class="form-control filterTangal" placeholder="Select a date" id="tglAwal"
                                       name="tglAwal" value="{{ date('d-m-Y') }}">
                                <span class="input-group-text">
                                    s.d
                                </span>
                                <input class="form-control filterTangal" placeholder="Select a date" id="tglAkhir"
                                       name="tglAkhir" value="{{ date('d-m-Y') }}">
                                <button class="btn w-5 btn-secondary" type="button" id="btnFilterRegistrasi"><i
                                            class="ti ti-search"></i></button>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12">
                            <select class="form-select" id="dokter" name="dokter" style="width: 100%"></select>
                        </div>
                        <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                            <select class="form-select form-select-2" id="stts" name="stts" style="width: 100%">
                                <option value="" selected>-</option>
                                <option value="Belum">Belum</option>
                                <option value="Sudah">Sudah</option>
                                <option value="Batal">Batal</option>
                                <option value="Dirujuk">Dirujuk</option>
                            </select>
                        </div>
                        <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                            <button type="button" class="btn btn-primary w-100" data-bs-target='#modalPasien'
                                    data-bs-toggle="modal"><i class="ti ti-users me-2"></i>Pasien
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('content.registrasi._modalRujukanInternal')
    @include('content.registrasi._modalRujukanEksternal')
    @include('content.registrasi._modalSuratSakit')
    @include('content.registrasi._modalSuratSehat')
    @include('content.registrasi._modalObatPcare')
    @include('content.registrasi._modalUploadPenunjang')
    @include('content.pemeriksaan.modal.penilaianAwal._modalPenilaianAwal')
    @include('content.pemeriksaan.modal.penilaianAwal._modalSkriningJatuh')
    @include('content.registrasi._modalRiwayat')
    @include('content.registrasi._modalBuktiRegister')
    @include('content.laboratorium.modal._modalPermintaanLab')
@endsection
@push('script')
    <script>
        //Script Reistrasi
        let selectStatusLayan = formFilterRegistrasi.find('select[name="stts"'); //get data from selection filter stts
        let selectDokterPoli = formFilterRegistrasi.find('select[name="dokter"'); //get data from selection filter stts


        selectDokterPoli.on('select2:unselect', function (e) {
            // console.log('LOERERERER');
        });

        function ubahRegistrasi(no_rawat) {
            modalRegistrasi.modal('show');
            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien,
                    penjab,
                    dokter,
                    poliklinik
                } = response;
                checkJam.prop('checked', false).trigger('change')
                checkNoReg.prop('checked', false).trigger('change')
                formRegistrasiPoli.find('input[name="no_reg"]').prop('disabled', false).val(response.no_reg)
                formRegistrasiPoli.find('input[name="no_rawat"]').val(response.no_rawat)
                formRegistrasiPoli.find('input[name="no_rkm_medis"]').val(response.no_rkm_medis)
                formRegistrasiPoli.find('input[name="nm_pasien"]').val(pasien.nm_pasien)

                formRegistrasiPoli.find('input[name="umurdaftar"]').val(`${setUmurDaftar(pasien.tgl_lahir)}`)
                formRegistrasiPoli.find('input[name="no_peserta"]').val(pasien.no_peserta)
                formRegistrasiPoli.find('input[name=hubunganpj]').val(response.hubunganpj)
                formRegistrasiPoli.find('input[name=p_jawab]').val(response.p_jawab)

                formRegistrasiPoli.find('input[name=alamatpj]').val(response.almt_pj)
                formRegistrasiPoli.find('input[name=tgl_registrasi]').val(splitTanggal(response.tgl_registrasi))

                const umurPasien = hitungUmurDaftar(pasien.tgl_lahir, response.tgl_registrasi)
                const formatUmur = formatUmurDaftar(umurPasien)

                formRegistrasiPoli.find('input[name=tgl_lahir]').val(pasien.tgl_lahir)
                formRegistrasiPoli.find('input[name=umur]').val(formatUmur)

                formRegistrasiPoli.find('input[name=status]').val(response.stts_daftar)
                formRegistrasiPoli.find('input[name=no_peserta]').prop('disabled', false)
                modalRegistrasi.find('.modal-title').html('Ubah Data Registrasi')

                const pj = new Option(`${response.kd_pj} - ${penjab?.png_jawab}`, response.kd_pj, true, true);
                const dpjp = new Option(`${response.kd_dokter} - ${dokter?.nm_dokter}`, response.kd_dokter, true, true);
                const poli = new Option(`${response.kd_poli} - ${poliklinik?.nm_poli}`, response.kd_poli, true, true);
                formRegistrasiPoli.find('select[name=kd_pj]').append(pj)
                formRegistrasiPoli.find('select[name=kd_dokter]').append(dpjp)
                formRegistrasiPoli.find('select[name=kd_poli]').append(poli);

                if (penjab.png_jawab.includes('BPJS')) {
                    periksaPendaftaran.removeClass('d-none')
                    periksaPendaftaran.find('input').prop('disabled', false)
                    $.get(`/efktp/mapping/pcare/poliklinik`, {
                        kdPoli: poliklinik.kd_poli
                    }).done((response) => {
                        formRegistrasiPoli.find('input[name=kd_poli_pcare]').val(response.kd_poli_pcare)
                        formRegistrasiPoli.find('input[name=nm_poli_pcare]').val(response.nm_poli_pcare)
                    })

                    getPemeriksaanRalan(no_rawat, dokter.kd_dokter).done((response) => {
                        if (Object.values(response).length) {
                            formRegistrasiPoli.find('input[name=keluhan]').val(response.keluhan)
                            formRegistrasiPoli.find('input[name=sistole]').val(response.tensi?.split('/')[0])
                            formRegistrasiPoli.find('input[name=diastole]').val(response.tensi?.split('/')[1])
                            formRegistrasiPoli.find('input[name=suhu_tubuh]').val(response.suhu_tubuh)
                            formRegistrasiPoli.find('input[name=tinggi]').val(response.tinggi)
                            formRegistrasiPoli.find('input[name=berat]').val(response.berat)
                            formRegistrasiPoli.find('input[name=respirasi]').val(response.respirasi)
                            formRegistrasiPoli.find('input[name=nadi]').val(response.nadi)
                            formRegistrasiPoli.find('input[name=lingkar_perut]').val(response.lingkar_perut)

                        }
                    })
                } else {
                    switchPendaftaranPcare.attr('checked', false)
                    periksaPendaftaran.addClass('d-none')
                    formRegistrasiPoli.find('input[name=no_peserta]').val('-')
                }

                btnSimpanReg.removeAttr('onclick').attr('onclick', 'updateRegPeriksa()')
            });
        }

        function updateRegPeriksa() {
            const data = getDataForm('formRegistrasiPoli', ['input', 'select']);
            swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan mengubah data registrasi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah Data!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/registrasi/update`, {
                        no_rawat: data['no_rawat'],
                        no_rkm_medis: data['no_rkm_medis'],
                        kd_pj: data['kd_pj'],
                        kd_dokter: data['kd_dokter'],
                        no_reg: data['no_reg'],
                        kd_poli: data['kd_poli'],
                        p_jawab: data['p_jawab'],
                        hubunganpj: data['hubunganpj'],
                        tgl_registrasi: data['tgl_registrasi'],
                        jam_reg: data['jam_reg'],
                        umurdaftar: data['umurdaftar'],
                        umur: data['umur'],
                    }).done((response) => {
                        showToast('Berhasil Mengubah Data Registrasi', 'success', 5000)
                        loadTabelRegistrasi(tglAwal, tglAkhir, selectFilterStts.val(), selectFilterDokter.val())
                        const isCheckedPendaftaranPcare = switchPendaftaranPcare.is(':checked');
                        if ((data.no_peserta !== '-' || data.no_peserta.length > 1) && isCheckedPendaftaranPcare) {
                            createBridgingPendaftaranPcare(data)
                        }
                        modalRegistrasi.modal('hide')

                    }).fail((error, status, code) => {
                        if (error.status !== 500) {
                            const errorMessage = {
                                status: error.status,
                                statusText: code,
                                responseJSON: error.responseJSON.message,
                            }
                            alertErrorAjax(errorMessage)

                        } else {
                            alertErrorAjax(error)
                        }
                    })
                }
            })

        }
    </script>
@endpush

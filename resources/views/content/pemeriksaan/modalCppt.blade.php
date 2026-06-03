<div class="modal modal-blur fade" id="modalCppt" tabindex="-1" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="modal-dialog modalCppt modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pemeriksaan / CPPT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 <ul class="nav nav-tabs " data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-cppt" class="nav-link active" data-bs-toggle="tab">CPPT</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-tindakan" class="nav-link" data-bs-toggle="tab">Tindakan Dokter</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabsHasilUsg" class="nav-link" data-bs-toggle="tab">USG Kehamilan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-permintaan-lab" class="nav-link" data-bs-toggle="tab">Permintaan Lab</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-hasil-lab" class="nav-link" data-bs-toggle="tab">Hasil Lab</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-mcu" class="nav-link" data-bs-toggle="tab">MCU</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-billing" class="nav-link" data-bs-toggle="tab">Billing</a>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade" id="tabs-cppt">
                        <div>
                            <div class="row gy-2">
                                <div class="col-xl-6 col-lg-6 col-sm-12 col-md-6">
                                    @include('content.pemeriksaan.modal._form')
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-12 col-md-6">
                                    @include('content.pemeriksaan.modal._riwayat')
                                </div>
                                <div class="col-xl-6 col-lg-6 col-sm-12 col-md-6">
                                    @include('content.pemeriksaan.modal._tabResep')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-tindakan">
                        <div>
                            @include('content.pemeriksaan.modal._tindakanPemeriksaan')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabsHasilUsg">
                        @include('content.pemeriksaan.modal._hasilUsg')
                    </div>
                    <div class="tab-pane fade" id="tabs-permintaan-lab">
                        @include('content.laboratorium.sub._formPermintaanTab')
                    </div>
                    <div class="tab-pane fade" id="tabs-hasil-lab">
                        @include('content.laboratorium.sub._hasilPeriksaTab')
                    </div>
                    <div class="tab-pane fade" id="tabs-mcu">
                        @include('content.pemeriksaan.modal._mcu')
                    </div>
                    <div class="tab-pane fade" id="tabs-billing">
                        @include('content.pemeriksaan.modal._billing')
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger d-none me-auto" id="btnHapusMcu" onclick="hapusMcu()">
                    <i class="ti ti-trash me-1"></i> Hapus MCU
                </button>
                <button type="button" class="btn btn-success" onclick="simpanPemeriksaanRalan()" id="btnSimpanCppt">
                    <i class="ti ti-device-floppy me-1"></i> Simpan CPPT
                </button>
                {{-- button tindakan pemeriksaan --}}
                <button class="btn btn-success" type="button" onclick=" createTindakanDokter()" id="btnCreateTindakan">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Tindakan
                </button>
                {{-- button hasil usg --}}
                <button type="button" class="btn btn-success d-none" onclick="" id="btnCreateHasilUsg">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Hasil USG
                </button>
                {{-- button permintaan lab --}}
                <button type="button" class="btn btn-primary d-none" id="btndataDetailPermintaanTab">
                    <i class="ti ti-eye me-1"></i> History Permintaan
                </button>
                <button type="button" class="btn btn-success d-none" id="btnKirimPermintaanTab" onclick="createPermintaanLabTab()">
                    <i class="ti ti-device-floppy me-1"></i> Kirim Permintaan
                </button>
                <button type="button" class="btn btn-success d-none" id="btnSimpanMcu" onclick="simpanMcu()">
                    <i class="ti ti-device-floppy me-1"></i> Simpan MCU
                </button>
                <button type="button" class="btn btn-primary d-none" id="btnCetakMcu" onclick="cetakMcu()">
                    <i class="ti ti-printer me-1"></i> Cetak MCU
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Keluar
                </button>
            </div>
        </div>
    </div>
</div>
@include('content.pemeriksaan.modal._pemeriksaanGigi')
@include('content.pemeriksaan.modal._diagnosaPasien')
@include('content.pemeriksaan.modal._tindakanPasien')
@include('content.pemeriksaan.modal._modalEditRacikan')
@include('content.pemeriksaan.modal._modalCetakResep')
@include('content.pemeriksaan.modal._modalKunjunganPcare')
@include('content.pemeriksaan.modal._modalReferensiSpesialis')
@include('content.pemeriksaan.modal._modalReferensiSubSpesialis')
@include('content.pemeriksaan.modal._modalReferensiSpesialisKhusus')
@include('content.pemeriksaan.modal._modalReferensiRujukan')
@include('content.pemeriksaan.modal._modalReferensiPoliFktp')
@include('content.pemeriksaan.modal._modalReferensiTacc')

@push('script')
    <script>
        var tabObat = $('#tabObat');
        const modalCppt = $('#modalCppt');
        const targetTabsCppt = modalCppt.find('a[href="#tabs-cppt"]');
        const targetTabsTindakan = modalCppt.find('a[href="#tabs-tindakan"]');

        var formCpptRajal = $('#formCpptRajal');
        var btnTambahResep = $();
        var btnTambahObat = $();
        var btnTambahRacikan = $();
        var btnSimpanResep = $();
        var btnSimpanRacikan = $();
        var btnCetakResep = $();
        var tabelResepUmum = $();
        var tabelResepRacikan = $();

        const targetTabsPermintaanLab = modalCppt.find('a[href="#tabs-permintaan-lab"]');
        const targetTabsHasilLab = modalCppt.find('a[href="#tabs-hasil-lab"]');
        const targetTabsMcu = modalCppt.find('a[href="#tabs-mcu"]');
        const targetTabsBilling = modalCppt.find('a[href="#tabs-billing"]');

        $(document).ready(() => {
            targetTabsPermintaanLab.on('shown.bs.tab', (e) => {
                const no_rawat = modalCppt.find('input[name="no_rawat"]').val();
                permintaanLabTab(no_rawat);

                $('#btnSimpanCppt').addClass('d-none')
                $('#btnCreateTindakan').addClass('d-none')
                $('#btnCreateHasilUsg').addClass('d-none')
                $('#btndataDetailPermintaanTab').removeClass('d-none')
                $('#btnKirimPermintaanTab').removeClass('d-none')
                $('#btnSimpanMcu').addClass('d-none')
            });

            targetTabsHasilLab.on('shown.bs.tab', (e) => {
                const no_rawat = modalCppt.find('input[name="no_rawat"]').val();
                showPeriksaLabTab(no_rawat);

                $('#btnSimpanCppt').addClass('d-none')
                $('#btnCreateTindakan').addClass('d-none')
                $('#btnCreateHasilUsg').addClass('d-none')
                $('#btndataDetailPermintaanTab').addClass('d-none')
                $('#btnKirimPermintaanTab').addClass('d-none')
                $('#btnSimpanMcu').addClass('d-none')
            });

            targetTabsMcu.on('shown.bs.tab', (e) => {
                const no_rawat = modalCppt.find('input[name="no_rawat"]').val();
                loadMcu(no_rawat);

                $('#btnSimpanCppt').addClass('d-none')
                $('#btnCreateTindakan').addClass('d-none')
                $('#btnCreateHasilUsg').addClass('d-none')
                $('#btndataDetailPermintaanTab').addClass('d-none')
                $('#btnKirimPermintaanTab').addClass('d-none')
                $('#btnSimpanMcu').removeClass('d-none')
            });
            btnTambahResep = $('#btnTambahResep')
            btnTambahObat = $('#btnTambahObat')
            btnTambahRacikan = $('#btnTambahRacikan')
            btnSimpanResep = $('#btnSimpanResep')
            btnSimpanRacikan = $('#btnSimpanRacikan')
            btnCetakResep = $('#btnCetakResep')
            tabelResepUmum = $('#tabelResepUmum')
            tabelResepRacikan = $('#tabelResepRacikan')

            $(document).off('click', '#btnTambahResep').on('click', '#btnTambahResep', function () {
                const noRawat = $(this).data('no-rawat');
                const action = $(this).data('action');
                if (action === 'tambah') {
                    tambahResep(noRawat);
                } else {
                    hapusResep(noRawat);
                }
            });
        })

        modalCppt.on('hidden.bs.modal', (e) => {
            // $('.modal-backdrop').remove();
            $(e.currentTarget).find('#formCpptRajal').find('input, textarea').val('-')
            tabelResepUmum.find('tbody').empty()
            tabelResepRacikan.find('tbody').empty()
            $('.tindakan-check').prop('checked', false);
            $('#formHasilUsg').find('input[type=text], input[type=date] , textarea').val('');
            $('#formHasilUsg').find('select').val('').trigger('change');
            $('#formMcu').find('input[type=text], textarea').val('-');
            $('#formMcu').find('select').each(function() {
                $(this).val($(this).find('option:first').val());
            });
            if (!targetTabsCppt.hasClass('active')) {
                targetTabsCppt.tab('show');
            }

        })

        targetTabsCppt.on('shown.bs.tab', (e) => {
            $('#btnSimpanCppt').removeClass('d-none')
            $('#btnCreateTindakan').addClass('d-none')
            $('#btnCreateHasilUsg').addClass('d-none')
            $('#btndataDetailPermintaanTab').addClass('d-none')
            $('#btnKirimPermintaanTab').addClass('d-none')
            $('#btnSimpanMcu').addClass('d-none')
        });

        targetTabsTindakan.on('shown.bs.tab', function(event) {
            const no_rawat = formCpptRajal.find('input[name=no_rawat]').val();
            const nm_pasien = formCpptRajal.find('input[name=nm_pasien]').val();
            const no_rkm_medis = formCpptRajal.find('input[name=no_rkm_medis]').val();
            const kd_dokter = formCpptRajal.find('input[name=nip]').val();
            const nm_dokter = formCpptRajal.find('input[name=nm_dokter]').val();

            formTindakanDokter.find('#no_rawat').val(no_rawat);
            formTindakanDokter.find('#nm_pasien').val(nm_pasien);
            formTindakanDokter.find('#no_rkm_medis').val(no_rkm_medis);
            formTindakanDokter.find('#kd_dokter').val(kd_dokter);
            formTindakanDokter.find('#nm_dokter').val(nm_dokter);
            
            tableTindakanDokter()
            formTindakanDokter.find('#kd_petugas').val(null).trigger('change');
            getTindakanDilakukan(no_rawat)

            $('#btnSimpanCppt').addClass('d-none')
            $('#btnCreateHasilUsg').addClass('d-none')
            $('#btndataDetailPermintaanTab').addClass('d-none')
            $('#btnKirimPermintaanTab').addClass('d-none')
            $('#btnCreateTindakan').removeClass('d-none')
            $('#btnSimpanMcu').addClass('d-none')
        });

        modalCppt.find('a[href="#tabsHasilUsg"]').on('shown.bs.tab', function() {
            $('#btnSimpanCppt').addClass('d-none')
            $('#btnCreateTindakan').addClass('d-none')
            $('#btndataDetailPermintaanTab').addClass('d-none')
            $('#btnKirimPermintaanTab').addClass('d-none')
            $('#btnCreateHasilUsg').removeClass('d-none')
            $('#btnSimpanMcu').addClass('d-none')
        });

        targetTabsBilling.on('shown.bs.tab', function() {
            const no_rawat = formCpptRajal.find('input[name=no_rawat]').val();
            loadBillingRalan(no_rawat);

            $('#btnSimpanCppt').addClass('d-none')
            $('#btnCreateTindakan').addClass('d-none')
            $('#btnCreateHasilUsg').addClass('d-none')
            $('#btndataDetailPermintaanTab').addClass('d-none')
            $('#btnKirimPermintaanTab').addClass('d-none')
            $('#btnSimpanMcu').addClass('d-none')
        });

        modalCppt.on('shown.bs.modal', (e) => {
            switcTab(tabObat)

            if (window.openMcuTabOnShow) {
                targetTabsMcu.tab('show');
                loadMcu(window.mcuNoRawatOnShow);
                window.openMcuTabOnShow = false;
                window.mcuNoRawatOnShow = null;
            } else if (!targetTabsCppt.hasClass('active')) {
                targetTabsCppt.tab('show');
            }
        })

        modalCppt.find('a[data-bs-toggle="tab"]').on('shown.bs.tab', (e) => {
            const target = $(e.target).attr('href');
            if (target !== '#tabs-mcu') {
                $('#btnHapusMcu').addClass('d-none');
                $('#btnCetakMcu').addClass('d-none');
            }
        });

        modalCppt.on('hidden.bs.modal', (e) => {
            $('#btnHapusMcu').addClass('d-none');
            $('#btnCetakMcu').addClass('d-none');
        });

        function showMcuModal(no_rawat) {
            window.openMcuTabOnShow = true;
            window.mcuNoRawatOnShow = no_rawat;
            showCpptRalan(no_rawat);
        }

        function showCpptRalan(no_rawat) {

            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien,
                    pemeriksaan_ralan,
                    dokter,
                    poliklinik
                } = response;
                const umurDaftar = hitungUmurDaftar(pasien.tgl_lahir, response.tgl_registrasi)
                const alamat = `${pasien.alamat}, ${pasien.kel.nm_kel}, ${pasien.kec.nm_kec}`

                formCpptRajal.find('input[name=tgl_reg]').val(formatTanggal(response.tgl_registrasi))
                formCpptRajal.find('input[name=no_rawat]').val(no_rawat)
                formCpptRajal.find('input[name=png_jawab').val(response.p_jawab ?? '-')
                formCpptRajal.find('input[name=stts]').val(response.stts)
                formCpptRajal.find('input[name=no_rkm_medis]').val(response.no_rkm_medis)
                formCpptRajal.find('input[name=nm_pasien]').val(`${pasien.nm_pasien} / ${pasien.jk}`)
                formCpptRajal.find('input[name=tgl_lahir]').val(`${formatTanggal(pasien.tgl_lahir)} / ${formatUmurDaftar(umurDaftar)}`)
                formCpptRajal.find('input[name=keluarga]').val(`${pasien.keluarga} : ${pasien.namakeluarga}`)
                formCpptRajal.find('input[name=alamat]').val(`${alamat}`)
                formCpptRajal.find('input[name=nip]').val(`${response.kd_dokter}`)
                formCpptRajal.find('input[name=nm_dokter]').val(`${dokter.nm_dokter}`)
                formCpptRajal.find('input[name=pembiayaan]').val(setTextPenjab(response.penjab.png_jawab, false))
                formCpptRajal.find('input[name=no_peserta]').val(`${pasien.no_peserta}`)
                formCpptRajal.find('input[name=kd_poli]').val(`${response.kd_poli}`)
                formCpptRajal.find('input[name=nm_poli]').val(`${poliklinik.nm_poli}`)
                formCpptRajal.find('input[name=kd_poli_pcare]').val(`${poliklinik.maping?.kd_poli_pcare}`)
                formKunjunganPcare.find('input[name=tgl_daftar]').val(`${splitTanggal(response.tgl_registrasi)}`)
                formKunjunganPcare.find('input[name=nm_poli_pcare]').val(`${poliklinik.maping?.nm_poli_pcare}`)
                formKunjunganPcare.find('input[name=kd_dokter_pcare]').val(`${dokter.maping?.kd_dokter_pcare}`)
                btnTambahResep.data('no-rawat', no_rawat)
                $('#btnDiagnosaPasien').attr('onclick', `diagnosaPasien('${no_rawat}')`);
                $('#btnTindakanPasien').attr('onclick', `tindakanPasien('${no_rawat}')`);
                setRiwayat(response.no_rkm_medis)
                setResepPasien(no_rawat)
                if (pasien.alergi.length) {
                    const alergi = pasien.alergi;
                    inputAlergi.empty()
                    alergi.forEach((resAlergi) => {
                        const optionAlergi = new Option(resAlergi.alergi, resAlergi.alergi, true, true);
                        inputAlergi.append(optionAlergi).trigger('change');
                    });
                    selectAlergi(inputAlergi, formCpptRajal)
                } else {
                    inputAlergi.empty()
                    selectAlergi(inputAlergi, formCpptRajal)
                }

                // if (response.kd_dokter === "{{ session()->get('pegawai')->nik }}") {
                //     setStatusLayan(no_rawat, 'Dirawat')
                // }


                if (pemeriksaan_ralan) {
                    Object.keys(pemeriksaan_ralan).map((key, index) => {
                        const select = formCpptRajal.find(`select[name=${key}]`);
                        const input = formCpptRajal.find(`input[name=${key}]`);
                        const textarea = formCpptRajal.find(`textarea[name=${key}]`);

                        if (textarea.length) {
                            textarea.val(pemeriksaan_ralan[key] ? pemeriksaan_ralan[key] : '-')
                        } else {
                            textarea.val('0')
                        }

                        if (input.length) {
                            const periksa = key === 'nip' ? response.kd_dokter : pemeriksaan_ralan[key]
                            input.val(periksa ? periksa : '0')
                        } else {
                            input.val('-')
                        }
                        if (select.length) {
                            select.find(`option:contains("${pemeriksaan_ralan[key]}")`).attr('selected', 'selected')
                        }
                    })
                }
            })
            $('#modalCppt').modal('show')

            if ($('#modalCppt').hasClass('show')) {
                if (window.openMcuTabOnShow) {
                    targetTabsMcu.tab('show');
                    loadMcu(no_rawat);
                    window.openMcuTabOnShow = false;
                } else if (targetTabsMcu.hasClass('active')) {
                    loadMcu(no_rawat);
                }
            }
        }

        function setResepPasien(no_rawat) {
            getResep({
                no_rawat: no_rawat,
                status: 'ralan'
            }).done((response) => {
                if (Object.keys(response).length) {
                    setButtonResep(response.no_resep, no_rawat)
                    renderResepObat(no_rawat)
                } else {
                    setButtonResep(null, no_rawat)
                    tabelResepUmum.find('tbody').empty()
                    tabelResepRacikan.find('tbody').empty()
                }
            })
        }

        function renderResepObat(no_rawat) {
            getResep({
                no_rawat: no_rawat,
                status: 'ralan'
            }).done((resep) => {
                if (resep.length) {
                    resep.map((res) => {
                        const {
                            resep_racikan,
                            resep_dokter
                        } = res;
                        btnTambahResep.data('no-rawat', no_rawat)
                        $(`#no_resep`).val(res.no_resep);
                        if (resep_dokter.length)
                            setResepDokter(res.no_resep);
                        if (resep_racikan.length)
                            setResepRacikan(res.no_resep)
                    })
                    setButtonResep(resep[0].no_resep, no_rawat)
                } else {
                    setButtonResep(null, no_rawat)
                }
            });
        }

        function setButtonResep(noResep, noRawat) {
            btnTambahResep.data('no-rawat', noRawat);
            if (noResep) {
                btnTambahResep.removeClass('btn-primary').addClass('btn-danger');
                btnTambahResep.data('action', 'hapus');
                btnTambahResep.text('Hapus Resep')
                btnCetakResep.attr('onclick', `cetakResep('${noRawat}')`)
                btnCetakResep.removeClass('d-none');

                tabelResepUmum.removeClass('d-none')
                tabelResepRacikan.removeClass('d-none')
                btnSimpanResep.removeClass('d-none')
                btnSimpanRacikan.removeClass('d-none')
                btnTambahObat.removeClass('d-none')
                btnTambahRacikan.removeClass('d-none')
            } else {
                btnTambahResep.removeClass('btn-danger').addClass('btn-primary');
                btnTambahResep.data('action', 'tambah');
                btnTambahResep.text('Tambah Resep')
                btnCetakResep.addClass('d-none');

                tabelResepUmum.addClass('d-none')
                tabelResepRacikan.addClass('d-none')
                btnSimpanResep.addClass('d-none')
                btnSimpanRacikan.addClass('d-none')
                btnTambahObat.addClass('d-none')
                btnTambahRacikan.addClass('d-none')
            }
        }

        const formPermintaanLabTab = $('#formPermintaanLabTab');
        const selectJenisPeriksaLabTab = formPermintaanLabTab.find('#pemeriksaanTab');
        const tablePermintaanLabTab = $('#tablePermintaanLabTab');
        const tableHasilPermintaanTab = $('#tableHasilPermintaanTab');

        function permintaanLabTab(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien,
                    dokter,
                    poliklinik,
                    diagnosa
                } = response;
                formPermintaanLabTab.find('#no_rawatTab').val(no_rawat);
                formPermintaanLabTab.find('#no_rkm_medisTab').val(response.no_rkm_medis);
                formPermintaanLabTab.find('#nm_pasienTab').val(`${pasien.nm_pasien} (${pasien.jk})`);
                formPermintaanLabTab.find('#tgl_lahirTab').val(`${formatTanggal(pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`);
                formPermintaanLabTab.find('#kd_dokterTab').val(response.kd_dokter)
                formPermintaanLabTab.find('#nm_dokterTab').val(dokter.nm_dokter)
                formPermintaanLabTab.find('#status_lanjutTab').val(response.status_lanjut)
                formPermintaanLabTab.find('#statusTab').val(response.status_lanjut)
                formPermintaanLabTab.find('#kd_poliTab').val(response.kd_poli)
                formPermintaanLabTab.find('#nm_poliTab').val(poliklinik.nm_poli)

                const diagnosaPasien = diagnosa.map((item) => {
                    return item.kd_penyakit
                }).join(';')

                formPermintaanLabTab.find('#diagnosaTab').val(diagnosaPasien)
                getPermintaanLabTab(no_rawat);
            });
            getNomorPermintaanTab();
        }

        function getNomorPermintaanTab() {
            return $.get(`{{ url('/lab/permintaan/noorder') }}`).done((response) => {
                formPermintaanLabTab.find('#noorderTab').val(response)
            })
        }

        function getPermintaanLabTab(no_rawat) {
            $.get(`{{ url('/lab/permintaan/get') }}`, {
                no_rawat: no_rawat
            }).done((response) => {
                let contentPermintaan = '';
                tableHasilPermintaanTab.find('tbody').empty();
                if (Object.values(response).length) {
                    const permintaan = response.map((item, index) => {
                        return `<tr>
                        <td>${index+1}</td>
                        <td>${item.noorder} <a href="javascript:void(0)" onclick="deletePermintaanLabTab('${item.noorder}')" title="Hapus permintaan" class="text-red"><i class="ti ti-trash"></i></a> ${isGetHasilLabTab(item)}</td>
                        <td>${splitTanggal(item.tgl_permintaan)} ${item.jam_permintaan}</td>
                        <td>${item.informasi_tambahan}</td>
                        <td>${item.diagnosa_klinis}</td>
                        <td>${splitTanggal(item.tgl_sampel)} ${item.jam_sampel}</td>
                        <td>${splitTanggal(item.tgl_hasil)} ${item.jam_hasil}</td>
                        </tr>${getPermintaanPeriksa(item.pemeriksaan)}`
                    }).join('');
                    contentPermintaan = permintaan;
                    tableHasilPermintaanTab.removeClass('d-none');
                } else {
                    contentPermintaan = `<tr><td colspan=7 class="text-center text-danger"><strong>Tidak ada permintaan lab</strong></td></tr>`
                    tableHasilPermintaanTab.addClass('d-none');
                }
                tableHasilPermintaanTab.find('tbody').append(contentPermintaan)
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }

        function isGetHasilLabTab(item) {
            if (item.tgl_hasil !== '0000-00-00') {
                return `<a href="javascript:void(0)" onclick="showHasilPermintaanLabTab('${item.no_rawat}', '${item.tgl_hasil}')" title="Lihat Hasil" class="text-success"><i class="ti ti-eye"></i></a>`
            }
            return '';
        }

        function deletePermintaanLabTab(noorder) {
            Swal.fire({
                title: "Yakin hapus data ini ?",
                html: "Data permintaan lab akan di hapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('/lab/permintaan/delete') }}/${noorder}`)
                        .done((response) => {
                            toast('Permintaan lab di hapus');
                            const no_rawat = formPermintaanLabTab.find('#no_rawatTab').val();
                            getPermintaanLabTab(no_rawat);
                            getNomorPermintaanTab();
                        }).fail((error) => {
                            alertErrorAjax(error)
                        })
                }
            })
        }

        function createPermintaanLabTab() {
            const data = getDataForm('formPermintaanLabTab', ['input']);
            const dataDetailPermintaan = [];
            const dataPemeriksaan = [];
            tablePermintaanLabTab.find('.itemPemeriksaanLab').each((index, e) => {
                const element = $(e);
                const item = element.prop('checked')
                if (item) {
                    const noorder = data.noorder;
                    const id = element.attr('name');
                    const kd_jenis_prw = element.data('parent');
                    const stts_bayar = 'Belum';

                    const exists = dataPemeriksaan.find(entry =>
                        entry.kd_jenis_prw === kd_jenis_prw
                    );

                    if (!exists) {
                        dataPemeriksaan.push({
                            noorder: noorder,
                            kd_jenis_prw: kd_jenis_prw,
                            stts_bayar: stts_bayar
                        });
                    }

                    dataDetailPermintaan.push({
                        noorder: noorder,
                        id_template: id,
                        kd_jenis_prw: kd_jenis_prw,
                        stts_bayar: stts_bayar,
                    });
                }
            });

            tablePermintaanLabTab.find('.checkJenisPemeriksaanTab').each((index, e) => {
                const element = $(e);
                if (element.prop('checked')) {

                    const noorder = data.noorder;
                    const kd_jenis_prw = element.attr('name');
                    const stts_bayar = 'Belum';

                    const exists = dataPemeriksaan.find(entry =>
                        entry.kd_jenis_prw === kd_jenis_prw
                    );

                    if (!exists) {
                        dataPemeriksaan.push({
                            noorder: noorder,
                            kd_jenis_prw: kd_jenis_prw,
                            stts_bayar: stts_bayar
                        });
                    }
                }
            });

            if (dataDetailPermintaan.length) {
                $.post(`{{ url('/lab/permintaan') }}`, data).done((response) => {
                    dataPemeriksaan.forEach(item => {
                        item.noorder = response.data
                    });
                    dataDetailPermintaan.forEach(item => {
                        item.noorder = response.data
                    });
                    createPermintaanPemeriksaanLab(dataPemeriksaan).done(() => {
                        createDetailPermintaanLabTab(dataDetailPermintaan)
                    });

                }).fail((error) => {
                    alertErrorAjax(error)
                })
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: `Ooppss...`,
                    html: `Anda belum memilih item pemeriksaan, Pilih salah satu atau lebih item pemeriksaan`,
                })
            }
        }

        function createDetailPermintaanLabTab(data) {
            return $.post(`{{ url('/lab/permintaan/detail') }}`, {
                data: data,
            }).done((response) => {
                toast('Permintaan lab dibuat');
                const no_rawat = formPermintaanLabTab.find('#no_rawatTab').val();
                getPermintaanLabTab(no_rawat);

                tablePermintaanLabTab.find('tbody').empty();
                tablePermintaanLabTab.find('input[type=checkbox]').prop('checked', false)
                formPermintaanLabTab.find('#informasi_tambahanTab').val('-')
                formPermintaanLabTab.find('#diagnosa_klinisTab').val('-')
                $('#pemeriksaanTab').val("").trigger('change');
                getNomorPermintaanTab();
            }).fail((error) => {
                alertErrorAjax(error)
            })
        }
        $('#pemeriksaanTab').select2({
            tags: false,
            dropdownParent: $('#modalCppt'),
            ajax: {
                url: `{{ url('/lab/jenis/get') }}`,
                dataType: 'json',
                data: (params) => {
                    const query = {
                        nm_perawatan: params.term
                    }
                    return query
                },
                processResults: (data) => {
                    return {
                        results: data.map((item) => {
                            return {
                                id: item.kd_jenis_prw,
                                text: `(${item.kd_jenis_prw}) ${item.nm_perawatan}`,
                            }
                        })
                    }
                }
            },
        });

        $('#pemeriksaanTab').on('select2:select', (e) => {
            const data = $('#pemeriksaanTab').val();
            $.get(`{{ url('/lab/jenis/template/get') }}`, {
                kode: data
            }).done((response) => {
                let pemeriksaan = response.map((item) => {
                    return `<tr>
                    <td><input type="checkbox" class="form-check checkJenisPemeriksaanTab" name="${item.kd_jenis_prw}" id="${item.kd_jenis_prw}" onclick="checkJenisPemeriksaanTab(this)"/></td>
                    <td colspan=3><b>${item.nm_perawatan}</b></td>
                    </tr>${setTemplatePemeriksaanTab(item)}`
                });
                tablePermintaanLabTab.find('tbody').empty().append(pemeriksaan)
            })
        })

        $('#pemeriksaanTab').on('select2:unselect', (e) => {
            const data = $('#pemeriksaanTab').val();
            if (data.length) {
                $.get(`{{ url('/lab/jenis/template/get') }}`, {
                    kode: data
                }).done((response) => {
                    let pemeriksaan = response.map((item) => {
                        return `<tr>
                        <td><input type="checkbox" class="form-check checkJenisPemeriksaanTab" name="${item.kd_jenis_prw}" id="${item.kd_jenis_prw}" onclick="checkJenisPemeriksaanTab(this)"/></td>
                        <td colspan=3><b>${item.nm_perawatan}</b></td>
                        </tr>${setTemplatePemeriksaanTab(item)}`
                    });
                    tablePermintaanLabTab.find('tbody').empty().append(pemeriksaan)
                })
            } else {
                tablePermintaanLabTab.find('tbody').empty()
            }
        })

        function setTemplatePemeriksaanTab(data) {
            const {
                template
            } = data;
            return template.map((i) => {
                if (i.Pemeriksaan.length) {
                    return `<tr>
                    <td><input class="form-checkbox itemPemeriksaanLab" type="checkbox" name="${i.id_template}" id="${i.id_template}" data-parent="${i.kd_jenis_prw}" /></td>
                    <td><span class="ms-4">${i.Pemeriksaan}</span></td>
                    <td>${i.satuan}</td>
                    <td><b>LD</b> : ${i.nilai_rujukan_ld} ${i.satuan}, <b>LA</b> : ${i.nilai_rujukan_la} ${i.satuan}, <b>PD</b> : ${i.nilai_rujukan_pd} ${i.satuan}, <b>PA</b> : ${i.nilai_rujukan_pa} ${i.satuan} </td>
                </tr>`

                } else {
                    return `<tr>
                    <td><input class="form-checkbox itemPemeriksaanLab" type="checkbox" name="${i.id_template}" id="${i.id_template}" data-parent="${i.kd_jenis_prw}" /></td>
                    <td><span class="ms-4">${data.nm_perawatan}</span></td>
                    <td>${i.satuan}</td>
                    <td></td>
                </tr>`
                }
            })
        }

        function checkJenisPemeriksaanTab(el) {
            const isCheck = $(el).prop('checked');
            tablePermintaanLabTab.find('input[type=checkbox]').each((index, e) => {
                if (e.dataset.parent == el.id) {
                    $(e).prop('checked', isCheck)
                }
            })
        }

        const tableHasilPeriksaLabTab = $('#tableHasilPeriksaLabTab');
        function showPeriksaLabTab(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                const {
                    pasien
                } = response;
                $('#no_rawatResultTab').val(no_rawat)
                $('#no_rkm_medisResultTab').val(response.no_rkm_medis)
                $('#nm_pasienResultTab').val(`${pasien.nm_pasien} (${pasien.jk})`)
                $('#tgl_lahirResultTab').val(`${splitTanggal(pasien.tgl_lahir)} / ${response.umurdaftar} ${response.sttsumur}`)
            })

            $.get(`{{ url('/lab/periksa/get') }}`, {
                no_rawat: no_rawat,
            }).done((response) => {
                const {
                    data
                } = response;
                if (data.count === 0) {
                    const row = `<tr><td colspan="5" class="text-center text-danger">Tidak ditemukan hasil</td></tr>`;
                    tableHasilPeriksaLabTab.find('tbody').empty().append(row);
                } else {
                    renderItemHasilPeriksaLabTab(data.result)
                }
            })
        }

        function renderItemHasilPeriksaLabTab(data) {
            tableHasilPeriksaLabTab.find('tbody').empty();
            const content = data.map((item, index) => {
                const sub = item.detail ? renderSubItemHasilPeriksaLab(item.detail) : ''
                return `<tr class="bg-muted-lt">
                        <td></td>
                        <td><strong>${item.jenis?.nm_perawatan}</strong></td>
                        <td class="text-center">${splitTanggal(item.tgl_periksa)} ${item.jam}</td>
                        <td colspan=2 class="text-center">${item.pegawai.nama}</td>
                    </tr>${sub}`
            });
            tableHasilPeriksaLabTab.find('tbody').append(content)
        }

        function renderSubItemHasilPeriksaLab(data) {
            return data.map((item, index) => {
                return `<tr class="${setColorItemLab(item.keterangan)}">
                        <td class="text-end">${index+1}</td>
                        <td><span class="ms-2">${item.template.nama}</span></td>
                        <td class="text-end">${item.nilai} ${item.template.satuan}</td>
                        <td class="text-end">${item.nilai_rujukan} ${item.template.satuan}</td>
                        <td class="text-center">${item.keterangan}</td>
                    </tr>`
            }).join('')
        }

        function setColorItemLab(ket) {
            switch (ket.toUpperCase()) {
                case 'L':
                    return 'bg-blue-lt'
                    break;
                case 'H':
                    return 'bg-red-lt'
                    break;
                default:
                    return '';
                    break;
            }
        }

        function showHasilPermintaanLabTab(no_rawat, tgl) {
            targetTabsHasilLab.tab('show');
            showPeriksaLabTab(no_rawat);
        }
    </script>
    @stack('scriptTindakan')
@endpush
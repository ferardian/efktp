<div class="modal modal-blur fade" id="modalCpptRanap" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modalCpptRanap modal-fullscreen modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pemeriksaan / CPPT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row gy-2">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-pemeriksaan" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                                            <i class="ti ti-notes me-1"></i> Pemeriksaan
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-resep" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-pill me-1"></i> Resep Obat
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-tindakan-ranap" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-list me-1"></i> Tindakan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-3">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="tabs-pemeriksaan" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._form')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-resep" role="tabpanel">
                                        @include('content.pemeriksaan.modal._tabResep')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-tindakan-ranap" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._tindakan')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        @include('content.kamarInap.cppt.sub._riwayat')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnResetCpptRanap" class="btn btn-warning d-none"><i class="ti ti-reload me-1"></i>Baru</button>
                <button type="button" id="btnSalinCpptRanap" class="btn btn-primary d-none"><i class="ti ti-copy me-1"></i> Copy</button>
                <button type="button" id="btnSimpanCpptRanap" class="btn btn-success" onclick="createCpptRanap()"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var modalCpptRanap = $('#modalCpptRanap')
        var formCpptRanap = $('#formCpptRanap');
        var alergi = $();
        var pegawai = $();
        var checkJam = $();
        var nip = "{{ session()->get('pegawai')->nik }}";
        var nmPegawai = "{{ session()->get('pegawai')->nama }}";
        var btnTambahResep = $();
        var btnTambahObat = $();
        var btnTambahRacikan = $();
        var btnSimpanResep = $();
        var btnSimpanRacikan = $();
        var btnCetakResep = $();
        var tabelResepUmum = $();
        var tabelResepRacikan = $();

        $(document).ready(() => {
            alergi = formCpptRanap.find('#alergi');
            pegawai = formCpptRanap.find('#nip');
            checkJam = formCpptRanap.find('#checkJam');

            btnTambahResep = $('#btnTambahResep')
            btnTambahObat = $('#btnTambahObat')
            btnTambahRacikan = $('#btnTambahRacikan')
            btnSimpanResep = $('#btnSimpanResep')
            btnSimpanRacikan = $('#btnSimpanRacikan')
            btnCetakResep = $('#btnCetakResep')
            tabelResepUmum = $('#tabelResepUmum')
            tabelResepRacikan = $('#tabelResepRacikan')

            if (typeof selectPegawai === 'function') {
                selectPegawai(pegawai, formCpptRanap);
            }
            if (typeof selectAlergi === 'function') {
                selectAlergi(alergi, formCpptRanap);
            }

            $(document).off('click', '#btnTambahResep').on('click', '#btnTambahResep', function() {
                const noRawat = $(this).data('no-rawat');
                const action = $(this).data('action');
                if (action === 'tambah') {
                    tambahResep(noRawat);
                } else {
                    hapusResep(noRawat);
                }
            });

            // Tindakan Scripts
            const selectTindakanRanap = $('#selectTindakanRanap');
            const selectDokterTindakan = $('#selectDokterTindakan');
            const selectPetugasTindakan = $('#selectPetugasTindakan');

            selectJnsPerawatanInap(selectTindakanRanap, modalCpptRanap, 'dr');
            selectDokter(selectDokterTindakan, modalCpptRanap);
            selectPetugas(selectPetugasTindakan, modalCpptRanap);

            $('input[name="pelaksana_type"]').on('change', function() {
                const val = $(this).val();
                selectJnsPerawatanInap(selectTindakanRanap, modalCpptRanap, val);
                selectTindakanRanap.val(null).trigger('change');
                if (val === 'dr') {
                    $('#container_select_dokter').show();
                    $('#container_select_petugas').hide();
                } else if (val === 'pr') {
                    $('#container_select_dokter').hide();
                    $('#container_select_petugas').show();
                } else {
                    $('#container_select_dokter').show();
                    $('#container_select_petugas').show();
                }
            });

            $('#btnSimpanTindakanRanap').on('click', () => {
                const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
                const data = {
                    no_rawat: noRawat,
                    no_rkm_medis: formCpptRanap.find('input[name="no_rkm_medis"]').val(),
                    nm_pasien: formCpptRanap.find('input[name="nm_pasien"]').val(),
                    kd_jenis_prw: selectTindakanRanap.val(),
                    tgl_perawatan: splitTanggal($('#tgl_tindakan').val()),
                    jam_rawat: $('#jam_tindakan').val(),
                    pelaksana: $('input[name="pelaksana_type"]:checked').val(),
                };

                if (data.pelaksana === 'dr' || data.pelaksana === 'drpr') {
                    data.kd_dokter = selectDokterTindakan.val();
                }
                if (data.pelaksana === 'pr' || data.pelaksana === 'drpr') {
                    data.nip = selectPetugasTindakan.val();
                }

                if (!data.kd_jenis_prw) {
                    return alertError('Pilih tindakan terlebih dahulu');
                }

                $.post(`{{ url('pemeriksaan/tindakan-ranap/create') }}`, data)
                    .done((response) => {
                        toast(response);
                        loadRiwayatTindakan(data.no_rawat);
                        selectTindakanRanap.val(null).trigger('change');
                    })
                    .fail((err) => {
                        alertErrorAjax(err);
                    });
            });

            $('#btnTampilkanRiwayatTindakan').on('click', () => {
                loadRiwayatTindakan(formCpptRanap.find('input[name="no_rawat"]').val());
            });

            $('.filterTanggal').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayBtn: true,
                todayHighlight: true,
                language: "id",
            });

            // Force sub-tab 'Umum' when main 'Resep' tab is shown
            $('a[href="#tabs-resep"]').on('shown.bs.tab', function() {
                $('#tabObat a[href="#tabsResepUmum"]').tab('show');
            });
        })

        modalCpptRanap.on('hidden.bs.modal', () => {
            $('#listRiwayat').empty()
        });

        modalCpptRanap.on('hide.bs.modal', () => {
            modalCpptRanap.find(':focus').blur();
        });

        modalCpptRanap.on('shown.bs.modal', () => {
            alergi.addClass('bg-red')
            checkboxTimer(checkJam)
        });


        function runningTime() {
            const waktu = new Date();
            jam = waktu.getHours() >= 10 ? waktu.getHours() : '0' + waktu.getHours();
            menit = waktu.getMinutes() >= 10 ? waktu.getMinutes() : '0' + waktu.getMinutes();
            detik = waktu.getSeconds() >= 10 ? waktu.getSeconds() : '0' + waktu.getSeconds();
            return textJam = `${jam}:${menit}:${detik}`;
        }

        function checkboxTimer(element) {
            const cek = element.is(':checked')
            if (cek) {
                clearInterval(jamSekarang)
            } else {
                const target = element.data('target')
                jamSekarang = setInterval(() => {
                    $(`#${target}`).val(runningTime())
                }, 1000);
            }
        }

        checkJam.on('change', () => {
            checkboxTimer(checkJam)
        })

        function setInfoPasien(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                const umurdaftar = hitungUmurDaftar(response.pasien.tgl_lahir, response.tgl_registrasi)
                formCpptRanap.find('input[name=no_rawat]').val(response.no_rawat)
                formCpptRanap.find('input[name=no_rkm_medis]').val(response.no_rkm_medis)
                formCpptRanap.find('input[name=nm_pasien]').val(`${response.pasien.nm_pasien} / ${response.pasien.jk == 'L' ? 'Laki-laki' : 'Perempuan'}`)
                formCpptRanap.find('input[name=tgl_lahir]').val(`${formatTanggal(response.pasien.tgl_lahir)} / ${umurdaftar}`)
                formCpptRanap.find('input[name=pembiayaan]').val(`${setTextPenjab(response.penjab.png_jawab, false)}`)
                formCpptRanap.find('input[name=kamar]').val(`${response.kamar_inap.kd_kamar} / ${response.kamar_inap.kamar.bangsal.nm_bangsal}`)

                const setPetugas = new Option(nmPegawai, nip, true, true);
                pegawai.append(setPetugas).trigger('change');

                setSelectAlergi(response.pasien.alergi, alergi)
            })
        }

        function cpptRanap(no_rawat) {
            setInfoPasien(no_rawat);
            setRiwayatRanap(no_rawat);
            if (typeof setResepPasien === 'function') {
                setResepPasien(no_rawat);
            }

            // Reset active tab to Pemeriksaan
            modalCpptRanap.find('a[href="#tabs-pemeriksaan"]').tab('show');
            $('#tabObat a[href="#tabsResepUmum"]').tab('show');

            // Init Tindakan Tab Data
            $('#tgl_awal_tindakan').val(tanggal);
            $('#tgl_akhir_tindakan').val(tanggal);
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            $('#jam_tindakan').val(`${jam}:${menit}:${detik}`);
            $('#selectTindakanRanap').val(null).trigger('change');
            loadRiwayatTindakan(no_rawat);

            modalCpptRanap.modal('show');
        }

        function tindakanRanap(no_rawat) {
            cpptRanap(no_rawat);
            modalCpptRanap.find('a[href="#tabs-tindakan-ranap"]').tab('show');
        }

        function loadRiwayatTindakan(no_rawat) {
            $('#tabelRiwayatTindakan').DataTable({
                responsive: true,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ url('pemeriksaan/tindakan-ranap/get') }}`,
                    data: {
                        no_rawat: no_rawat,
                        dataTable: true,
                        tgl_awal: splitTanggal($('#tgl_awal_tindakan').val()),
                        tgl_akhir: splitTanggal($('#tgl_akhir_tindakan').val()),
                    }
                },
                columns: [{
                        data: 'tgl_perawatan',
                        render: (data, type, row) => `${splitTanggal(data)} ${row.jam_rawat}`
                    },
                    {
                        data: 'tindakan.nm_perawatan'
                    },
                    {
                        data: 'pelaksana',
                        render: (data) => {
                            let badge = 'bg-blue';
                            if (data === 'Petugas') badge = 'bg-orange';
                            if (data === 'Dokter & Petugas') badge = 'bg-purple';
                            return `<span class="badge ${badge}">${data}</span>`;
                        }
                    },
                    {
                        data: 'nama_pelaksana'
                    },
                    {
                        data: null,
                        render: (data, type, row) => {
                            const nm_perawatan = row.tindakan.nm_perawatan.replace(/'/g, "\\'");
                            return `<button class="btn btn-danger btn-sm" onclick="hapusTindakanRanap('${row.no_rawat}', '${row.kd_jenis_prw}', '${row.tgl_perawatan}', '${row.jam_rawat}', '${row.pelaksana}', '${nm_perawatan}')">
                                    <i class="ti ti-trash"></i>
                                </button>`;
                        }
                    }
                ]
            });
        }

        function hapusTindakanRanap(no_rawat, kd_jenis_prw, tgl_perawatan, jam_rawat, pelaksana, nm_perawatan) {
            const tglJam = `${splitTanggal(tgl_perawatan)} ${jam_rawat}`;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: `Tindakan: <b>${nm_perawatan}</b><br>Waktu: <span class="text-danger">${tglJam}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ url('pemeriksaan/tindakan-ranap/delete') }}`, {
                        no_rawat: no_rawat,
                        kd_jenis_prw: kd_jenis_prw,
                        tgl_perawatan: tgl_perawatan,
                        jam_rawat: jam_rawat,
                        pelaksana: pelaksana
                    }).done((response) => {
                        toast(response);
                        loadRiwayatTindakan(no_rawat);
                    }).fail((err) => {
                        alertErrorAjax(err);
                    });
                }
            });
        }

        function setSelectAlergi(alergiPasien, element) {
            element.empty()
            if (alergiPasien.length) {
                alergiPasien.forEach((resAlergi) => {
                    const optionAlergi = new Option(resAlergi.alergi, resAlergi.alergi, true, true);
                    element.append(optionAlergi);
                });
                element.trigger('change');
            }
        }

        function createCpptRanap() {
            const data = getDataForm('formCpptRanap', ['input', 'select', 'textarea']);
            delete data[""];
            data['alergi'] = alergi.val() ? alergi.val().map((item) => item).join(', ') : '';
            $.post(`pemeriksaan/ranap`, data).done((response) => {
                alertSuccessAjax().then(() => {
                    createAlergi({
                        no_rkm_medis: data['no_rkm_medis'],
                        alergi: alergi.val() ? alergi.val() : [],
                    }).done(() => {
                        formCpptRanap.trigger('reset');
                        setRiwayatRanap(data['no_rawat']);
                        setInfoPasien(data['no_rawat']);
                    });
                });
            }).fail((error) => {
                alertErrorAjax(error);
            })
        }

        function updateCpptRanap(...params) {
            const data = getDataForm('formCpptRanap', ['input', 'select', 'textarea']);
            data['alergi'] = alergi.val() ? alergi.val().map((item) => item).join(', ') : '';
            $.post(`pemeriksaan/ranap/update`, data).done((response) => {
                alertSuccessAjax().then(() => {
                    createAlergi({
                        no_rkm_medis: data['no_rkm_medis'],
                        alergi: alergi.val() ? alergi.val() : [],
                    }).done(() => {
                        formCpptRanap.trigger('reset');
                        setRiwayatRanap(data['no_rawat']);
                        setInfoPasien(data['no_rawat']);
                    })
                    $('#btnSimpanCpptRanap').attr('onclick', 'createCpptRanap()');
                    $('#btnResetCpptRanap').addClass('d-none');
                    $('#btnSalinCpptRanap').addClass('d-none');

                });
            }).fail((error) => {
                alertErrorAjax(error);
            })
        }
        $('#btnResetCpptRanap').on('click', () => {
            $('#btnSimpanCpptRanap').attr('onclick', 'createCpptRanap()');
            $('#btnResetCpptRanap').addClass('d-none');
            $('#btnSalinCpptRanap').addClass('d-none');
            const no_rawat = formCpptRanap.find('input[name="no_rawat"]').val();
            formCpptRanap.trigger('reset');
            checkJam.prop('checked', false).trigger('change');
            setInfoPasien(no_rawat);

        })

        $('#btnSalinCpptRanap').on('click', () => {
            createCpptRanap();
            checkJam.prop('checked', false).trigger('change');
            $('#btnResetCpptRanap').addClass('d-none');
            $('#btnSalinCpptRanap').addClass('d-none');
            $('#btnSimpanCpptRanap').attr('onclick', 'createCpptRanap()');
        })

        function getResep(data) {
            const resep = $.get(`{{ url('/resep/get') }}`, data)
            return resep
        }

        function setResepPasien(no_rawat) {
            getResep({
                no_rawat: no_rawat,
                status: 'ranap'
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
                status: 'ranap'
            }).done((resep) => {
                if (resep.length) {
                    resep.map((res) => {
                        const {
                            resep_racikan,
                            resep_dokter
                        } = res;
                        btnTambahResep.attr('onclick', `hapusResep('${no_rawat}')`)
                        $(`#no_resep`).val(res.no_resep);
                        if (resep_dokter.length)
                            setResepDokter(res.no_resep);
                        if (resep_racikan.length)
                            setResepRacikan(res.no_resep)
                    })
                    btnTambahResep.removeClass('btn-primary').addClass('btn-danger');
                    btnTambahResep.text('Hapus Resep')
                    btnCetakResep.attr('onclick', `cetakResep('${no_rawat}')`)
                    tabelResepUmum.removeClass('d-none')
                    tabelResepRacikan.removeClass('d-none')
                    btnSimpanResep.removeClass('d-none')
                    btnSimpanRacikan.removeClass('d-none')
                    btnTambahObat.removeClass('d-none')
                    btnTambahRacikan.removeClass('d-none')
                    btnCetakResep.removeClass('d-none')
                } else {
                    btnTambahResep.removeClass('btn-danger').addClass('btn-primary');
                    btnTambahResep.text('Tambah Resep')
                    btnCetakResep.removeAttr('onclick')
                    tabelResepUmum.addClass('d-none')
                    tabelResepRacikan.addClass('d-none')
                    btnSimpanResep.addClass('d-none')
                    btnSimpanRacikan.addClass('d-none')
                    btnTambahObat.addClass('d-none')
                    btnTambahRacikan.addClass('d-none')
                    btnCetakResep.addClass('d-none')
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

                btnSimpanResep.removeClass('d-none')
                btnTambahObat.removeClass('d-none')
                tabelResepUmum.removeClass('d-none')
                btnTambahRacikan.removeClass('d-none')
                tabelResepRacikan.removeClass('d-none')
                btnSimpanRacikan.removeClass('d-none')
            } else {
                btnTambahResep.removeClass('btn-danger').addClass('btn-primary');
                btnTambahResep.data('action', 'tambah');
                btnTambahResep.text('Buat Resep')
                btnCetakResep.addClass('d-none');

                btnSimpanResep.addClass('d-none')
                btnTambahObat.addClass('d-none')
                tabelResepUmum.addClass('d-none')
                btnTambahRacikan.addClass('d-none')
                tabelResepRacikan.addClass('d-none')
                btnSimpanRacikan.addClass('d-none')
            }
        }
    </script>
@endpush

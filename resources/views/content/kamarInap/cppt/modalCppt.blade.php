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
                                        <a href="#tabs-pemberian-obat" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-first-aid-kit me-1"></i> Beri Obat & BHP
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-resep" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-pill me-1"></i> Resep Obat
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-permintaan-udd" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-clock-check me-1"></i> Permintaan UDD
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-tindakan-ranap" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-list me-1"></i> Tindakan
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-permintaan-lab" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-flask me-1"></i> Permintaan Lab
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-hasil-lab" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-report-medical me-1"></i> Hasil Lab
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a href="#tabs-billing-ranap" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                            <i class="ti ti-receipt me-1"></i> Estimasi Biaya
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-3">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="tabs-pemeriksaan" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._form')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-pemberian-obat" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._pemberianObat')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-resep" role="tabpanel">
                                        @include('content.pemeriksaan.modal._tabResep')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-permintaan-udd" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._permintaanUdd')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-tindakan-ranap" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._tindakan')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-permintaan-lab" role="tabpanel">
                                        @include('content.laboratorium.sub._formPermintaanTab')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-hasil-lab" role="tabpanel">
                                        @include('content.laboratorium.sub._hasilPeriksaTab')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-billing-ranap" role="tabpanel">
                                        @include('content.kamarInap.cppt.sub._billing')
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
                {{-- button permintaan lab --}}
                <button type="button" class="btn btn-primary d-none" id="btndataDetailPermintaanTab">
                    <i class="ti ti-eye me-1"></i> History Permintaan
                </button>
                <button type="button" class="btn btn-success d-none" id="btnKirimPermintaanTab" onclick="createPermintaanLabTab()">
                    <i class="ti ti-device-floppy me-1"></i> Kirim Permintaan
                </button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-1"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@include('content.kamarInap.cppt.sub._modalBeriObatUdd')
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

            // Tab shown event listeners to control button visibility and data load
            $('a[href="#tabs-pemeriksaan"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').removeClass('d-none');
                const isEdit = $('#btnSimpanCpptRanap').attr('onclick') && $('#btnSimpanCpptRanap').attr('onclick').includes('updateCpptRanap');
                if (isEdit) {
                    $('#btnResetCpptRanap').removeClass('d-none');
                    $('#btnSalinCpptRanap').removeClass('d-none');
                } else {
                    $('#btnResetCpptRanap').addClass('d-none');
                    $('#btnSalinCpptRanap').addClass('d-none');
                }
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');
            });

            // Force sub-tab 'Umum' when main 'Resep' tab is shown
            $('a[href="#tabs-resep"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');

                $('#tabObat a[href="#tabsResepUmum"]').tab('show');
            });

            // Load BHP & Pemberian Obat Ranap when tab is shown
            $('a[href="#tabs-pemberian-obat"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');

                const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
                loadJadwalUddPasien(noRawat);
                loadPemberianObatRanap(noRawat);
            });

            $('a[href="#tabs-permintaan-udd"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');
            });

            $('a[href="#tabs-tindakan-ranap"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');
            });

            // Load billing data when billing tab is shown
            $('a[href="#tabs-billing-ranap"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');

                const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
                loadBillingRanap(noRawat);
            });

            $('a[href="#tabs-permintaan-lab"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').removeClass('d-none');
                $('#btndataDetailPermintaanTab').removeClass('d-none');

                const no_rawat = formCpptRanap.find('input[name="no_rawat"]').val();
                permintaanLabTab(no_rawat);
            });

            $('a[href="#tabs-hasil-lab"]').on('shown.bs.tab', function() {
                $('#btnSimpanCpptRanap').addClass('d-none');
                $('#btnResetCpptRanap').addClass('d-none');
                $('#btnSalinCpptRanap').addClass('d-none');
                $('#btnKirimPermintaanTab').addClass('d-none');
                $('#btndataDetailPermintaanTab').addClass('d-none');

                const no_rawat = formCpptRanap.find('input[name="no_rawat"]').val();
                showPeriksaLabTab(no_rawat);
            });
        })

        modalCpptRanap.on('hidden.bs.modal', () => {
            $('#listRiwayat').empty()
            const targetTabsPemeriksaan = modalCpptRanap.find('a[href="#tabs-pemeriksaan"]');
            if (!targetTabsPemeriksaan.hasClass('active')) {
                targetTabsPemeriksaan.tab('show');
            }
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
                        data: 'biaya_rawat',
                        render: (data) => `Rp. ${new Intl.NumberFormat('id-ID').format(data)}`
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
                formPermintaanLabTab.find('#status_lanjutTab').val('Ranap')
                formPermintaanLabTab.find('#statusTab').val('Ranap')
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
            dropdownParent: $('#modalCpptRanap'),
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
            modalCpptRanap.find('a[href="#tabs-hasil-lab"]').tab('show');
            showPeriksaLabTab(no_rawat);
        }

        // ==========================================
        // PEMBERIAN OBAT & BHP RANAP (DlgPemberianObat Khanza)
        // ==========================================
        let currentPatientKelas = '';
        let selectedBhpData = null;

        // Inisialisasi Select2 untuk pencarian Obat & BHP
        $('#selectBarangBhp').select2({
            dropdownParent: $('#modalCpptRanap'),
            placeholder: 'Ketik nama obat/BHP (infus, spuit, abocath, verban)...',
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: `{{ url('/kamar-inap/pemberian-obat/cari-barang') }}`,
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term,
                        kd_bangsal: $('#selectBangsalBhp').val() || 'AP',
                        kelas: currentPatientKelas
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#selectBarangBhp').on('select2:select', function(e) {
            selectedBhpData = e.params.data;
            if (selectedBhpData) {
                $('#infoNamaBarang').text(selectedBhpData.nama_brng);
                $('#infoSatuanBarang').text(selectedBhpData.kode_sat);
                $('#infoStokBarang').text(selectedBhpData.stok);
                $('#infoHargaBarang').text('Rp ' + new Intl.NumberFormat('id-ID').format(selectedBhpData.biaya_obat));
                $('#infoBarangTerpilih').removeClass('d-none');
                $('#jml_bhp').focus();
            }
        });

        $('#selectBarangBhp').on('select2:clear', function() {
            selectedBhpData = null;
            $('#infoBarangTerpilih').addClass('d-none');
        });

        $('#selectBangsalBhp').on('change', function() {
            if ($('#selectBarangBhp').val()) {
                $('#selectBarangBhp').val(null).trigger('change');
                selectedBhpData = null;
                $('#infoBarangTerpilih').addClass('d-none');
            }
        });

        function loadPemberianObatRanap(noRawat) {
            if (!noRawat) return;
            const tbody = $('#tabelRiwayatPemberianObat tbody');
            tbody.html('<tr><td colspan="7" class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data pemberian obat & BHP...</td></tr>');

            $.get(`{{ url('/kamar-inap/pemberian-obat/data') }}`, { no_rawat: noRawat })
                .done((res) => {
                    currentPatientKelas = res.kamar?.kelas || '';
                    
                    // Populate bangsal dropdown if empty
                    const selectBangsal = $('#selectBangsalBhp');
                    if (selectBangsal.children().length === 0 && res.bangsal_list) {
                        res.bangsal_list.forEach(b => {
                            const isSelected = b.kd_bangsal === res.default_bangsal ? 'selected' : '';
                            selectBangsal.append(`<option value="${b.kd_bangsal}" ${isSelected}>${b.nm_bangsal} (${b.kd_bangsal})</option>`);
                        });
                    } else if (res.default_bangsal) {
                        selectBangsal.val(res.default_bangsal);
                    }

                    // Handle status billing terkunci
                    if (res.is_locked) {
                        $('#bannerBhpLocked').removeClass('d-none');
                        $('#formPemberianObatRanap input, #formPemberianObatRanap select, #btnSimpanBhpRanap').prop('disabled', true);
                    } else {
                        $('#bannerBhpLocked').addClass('d-none');
                        $('#formPemberianObatRanap input, #formPemberianObatRanap select, #btnSimpanBhpRanap').prop('disabled', false);
                    }

                    // Render rows
                    tbody.empty();
                    if (!res.data || res.data.length === 0) {
                        tbody.html('<tr><td colspan="7" class="text-center text-muted py-3">Belum ada obat atau BHP yang diberikan langsung</td></tr>');
                    } else {
                        res.data.forEach(item => {
                            const btnHapus = res.is_locked 
                                ? `<span class="badge bg-secondary-lt" title="Billing terkunci"><i class="ti ti-lock"></i></span>`
                                : `<button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus pemberian" onclick="hapusPemberianObatRanap('${item.no_rawat}', '${item.kode_brng}', '${item.tgl_perawatan}', '${item.jam}', '${item.no_batch || ''}', '${item.no_faktur || ''}', '${item.nama_brng.replace(/'/g, "\\'")}')"><i class="ti ti-trash"></i></button>`;

                            tbody.append(`
                                <tr>
                                    <td><small class="text-dark">${splitTanggal(item.tgl_perawatan)}</small><br><small class="text-muted">${item.jam}</small></td>
                                    <td><span class="fw-semibold text-dark">${item.nama_brng}</span></td>
                                    <td><small class="badge bg-blue-lt">${item.nm_bangsal || item.kd_bangsal}</small></td>
                                    <td class="text-end small">Rp ${new Intl.NumberFormat('id-ID').format(item.biaya_obat)}</td>
                                    <td class="text-center fw-bold">${item.jml} <small class="text-muted">${item.kode_sat || ''}</small></td>
                                    <td class="text-end fw-bold text-dark small">Rp ${new Intl.NumberFormat('id-ID').format(item.total)}</td>
                                    <td class="text-center">${btnHapus}</td>
                                </tr>
                            `);
                        });
                    }

                    $('#totalBiayaBhpRanap').text('Rp ' + new Intl.NumberFormat('id-ID').format(res.total_biaya || 0));
                })
                .fail((err) => {
                    tbody.html('<tr><td colspan="7" class="text-center text-danger py-3">Gagal memuat data: ' + (err.responseJSON?.message || err.statusText) + '</td></tr>');
                });
        }

        $('#btnSimpanBhpRanap').on('click', function() {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            const kodeBrng = $('#selectBarangBhp').val();
            const kdBangsal = $('#selectBangsalBhp').val();
            const jml = parseFloat($('#jml_bhp').val());
            const tgl = splitTanggal($('#tgl_bhp').val());
            const jam = $('#jam_bhp').val();

            if (!noRawat) {
                return alertError('No. Rawat tidak valid');
            }
            if (!kodeBrng) {
                return alertError('Pilih obat atau BHP medis terlebih dahulu');
            }
            if (!kdBangsal) {
                return alertError('Pilih asal depo/bangsal stok');
            }
            if (!jml || jml <= 0) {
                return alertError('Masukkan jumlah (Qty) yang valid');
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/kamar-inap/pemberian-obat/simpan') }}`, {
                no_rawat: noRawat,
                kode_brng: kodeBrng,
                kd_bangsal: kdBangsal,
                jml: jml,
                tgl_perawatan: tgl,
                jam: jam,
                biaya_obat: selectedBhpData ? selectedBhpData.biaya_obat : 0,
            })
            .done((res) => {
                toast(res.message);
                $('#selectBarangBhp').val(null).trigger('change');
                $('#jml_bhp').val('');
                selectedBhpData = null;
                $('#infoBarangTerpilih').addClass('d-none');
                loadPemberianObatRanap(noRawat);
            })
            .fail((err) => {
                alertErrorAjax(err);
            })
            .always(() => {
                btn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i> Beri Obat / BHP');
            });
        });

        $('#btnRefreshBhpRanap').on('click', function() {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            loadPemberianObatRanap(noRawat);
        });

        function hapusPemberianObatRanap(noRawat, kodeBrng, tgl, jam, batch, faktur, namaBrng) {
            Swal.fire({
                title: 'Hapus Pemberian BHP/Obat?',
                html: `Apakah Anda yakin ingin membatalkan pemberian <strong>${namaBrng}</strong>?<br><small class="text-muted">Stok akan dikembalikan ke gudang/bangsal.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan & Kembalikan Stok',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/kamar-inap/pemberian-obat/hapus') }}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                            no_rawat: noRawat,
                            kode_brng: kodeBrng,
                            tgl_perawatan: tgl,
                            jam: jam,
                            no_batch: batch,
                            no_faktur: faktur
                        },
                        success: function(res) {
                            toast(res.message);
                            loadPemberianObatRanap(noRawat);
                        },
                        error: function(err) {
                            alertErrorAjax(err);
                        }
                    });
                }
            });
        }

        // ==========================================
        // JADWAL & PELAKSANAAN PEMBERIAN OBAT UDD (MAR)
        // ==========================================
        function loadJadwalUddPasien(noRawat) {
            if (!noRawat) return;
            const tbody = $('#tbJadwalUddRuangan tbody');
            tbody.html('<tr><td colspan="3" class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm me-2"></div>Memuat jadwal & stok obat UDD ruangan...</td></tr>');

            $.get(`{{ url('/permintaan-stok-obat/stok-pasien') }}`, { no_rawat: noRawat })
                .done((data) => {
                    tbody.empty();
                    if (!data || data.length === 0) {
                        tbody.html('<tr><td colspan="3" class="text-center text-muted py-3">Belum ada stok obat UDD di ruangan untuk pasien ini. Silakan buat permintaan di tab Permintaan UDD jika dibutuhkan.</td></tr>');
                        return;
                    }

                    data.forEach(item => {
                        let jadwalHtml = [];
                        if (item.jadwal && item.jadwal.length) {
                            item.jadwal.forEach(j => {
                                if (j.sudah_diberikan) {
                                    jadwalHtml.push(`
                                        <div class="btn-group btn-group-sm me-2 mb-1">
                                            <button type="button" class="btn btn-sm btn-${j.status_class} py-1 px-2" title="Diberikan pada ${j.tgl_riil} jam ${j.jam_riil}">
                                                <i class="ti ti-check me-1"></i><strong>Jam ${j.jam_jadwal}</strong>
                                                <span class="badge bg-white text-${j.status_class} ms-1" style="font-size:0.65rem;">${j.status_waktu} (${j.jam_riil})</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-${j.status_class} py-1 px-2" title="Batalkan pemberian jam ini" onclick="batalPemberianUddPasien('${item.kode_brng}', '${j.tgl_riil}', '${j.jam_riil}')">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    `);
                                } else {
                                    jadwalHtml.push(`
                                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 me-2 mb-1" onclick="berikanObatUddPasien('${item.kode_brng}', '${item.nama_brng.replace(/'/g, "\\'")}', '${j.jam_jadwal}', '${(item.aturan_pakai || '').replace(/'/g, "\\'")}', ${item.sisa_stok}, '${item.satuan || ''}')">
                                            <i class="ti ti-pill me-1"></i>Berikan Jam ${j.jam_jadwal}
                                        </button>
                                    `);
                                }
                            });
                        } else {
                            jadwalHtml.push('<span class="text-muted small fst-italic">Tidak ada jam terjadwal</span>');
                        }

                        const sisaBadge = item.sisa_stok > 0
                            ? `<span class="badge bg-success-lt text-success fw-bold">${item.sisa_stok} ${item.satuan}</span>`
                            : `<span class="badge bg-secondary-lt text-secondary">Habis</span>`;

                        tbody.append(`
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">${item.nama_brng}</div>
                                    <small class="text-muted"><i class="ti ti-prescription me-1"></i>${item.aturan_pakai || '-'}</small>
                                </td>
                                <td class="text-center">
                                    <div>${sisaBadge}</div>
                                    <small class="text-muted" style="font-size:0.75rem;">Total: ${item.total_stok} ${item.satuan}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap align-items-center">
                                        ${jadwalHtml.join('')}
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                })
                .fail((err) => {
                    tbody.html('<tr><td colspan="3" class="text-center text-danger py-3">Gagal memuat jadwal UDD: ' + (err.responseJSON?.message || err.statusText) + '</td></tr>');
                });
        }

        $('#btnRefreshStokUddPasien').on('click', function() {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            loadJadwalUddPasien(noRawat);
        });

        function berikanObatUddPasien(kodeBrng, namaBrng, jamJadwal, aturanPakai, sisaStok = 1, satuan = 'tab') {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            const nowTime = new Date().toTimeString().split(' ')[0];
            const defaultJml = sisaStok >= 1 ? 1 : sisaStok;

            // Isi data ke modal
            $('#beriUddKodeBrng').val(kodeBrng);
            $('#beriUddJamJadwal').val(jamJadwal);
            $('#beriUddMaxStok').val(sisaStok);
            $('#beriUddNamaBrng').text(namaBrng);
            $('#beriUddDisplayJadwal').text(jamJadwal + ' WIB');
            $('#beriUddAturan').text(aturanPakai || '-');
            $('#beriUddSisaStokText').text(sisaStok);
            $('#beriUddSatuanBadge').text(satuan);
            $('#beriUddSatuanAddon').text(satuan);
            $('#beriUddJml').val(defaultJml).attr('max', sisaStok);
            $('#beriUddJamRiil').val(nowTime);
            $('#beriUddCatatanAturan').val(aturanPakai || '');
            $('#alertQtyMax').addClass('d-none');
            $('#btnSubmitBeriUdd').prop('disabled', false);

            // Set preset buttons
            $('.btn-preset-qty').removeClass('active btn-primary').addClass('btn-outline-secondary');
            $(`.btn-preset-qty[data-qty="${defaultJml}"]`).addClass('active btn-primary').removeClass('btn-outline-secondary');

            // Hitung status kepatuhan waktu
            updateKepatuhanJam(jamJadwal, nowTime);

            // Buka Modal
            $('#modalBeriObatUdd').modal('show');
        }

        // Live calculation status kepatuhan jam
        function updateKepatuhanJam(jamJadwal, jamRiil) {
            if (!jamJadwal || !jamRiil) return;
            const jParts = jamJadwal.split(':');
            const rParts = jamRiil.split(':');
            const jMin = parseInt(jParts[0]) * 60 + parseInt(jParts[1] || 0);
            const rMin = parseInt(rParts[0]) * 60 + parseInt(rParts[1] || 0);
            const diff = rMin - jMin;

            const badge = $('#badgePreviewKepatuhan');
            if (Math.abs(diff) <= 30) {
                badge.html('<i class="ti ti-check text-success me-1"></i><span class="text-success fw-bold">Tepat Waktu</span>');
            } else if (diff > 30) {
                badge.html(`<i class="ti ti-alert-triangle text-warning me-1"></i><span class="text-warning fw-bold">Terlambat ${diff} mnt</span>`);
            } else {
                badge.html(`<i class="ti ti-clock-forward text-info me-1"></i><span class="text-info fw-bold">Lebih Awal ${Math.abs(diff)} mnt</span>`);
            }
        }

        // Tangani focus trap Bootstrap saat modal UDD tampil
        $('#modalBeriObatUdd').on('show.bs.modal', function () {
            $(document).off('focusin.bs.modal');
            setTimeout(() => {
                $('.modal-backdrop:last').css('z-index', 1060);
            }, 10);
        });

        $('#modalBeriObatUdd').on('shown.bs.modal', function () {
            $('#beriUddJml').trigger('focus').select();
        });

        // Preset Qty Buttons
        $(document).on('click', '.btn-preset-qty', function () {
            const val = parseFloat($(this).data('qty'));
            const max = parseFloat($('#beriUddMaxStok').val() || 9999);
            const finalVal = Math.min(val, max);
            $('#beriUddJml').val(finalVal).trigger('input');
            $('.btn-preset-qty').removeClass('active btn-primary').addClass('btn-outline-secondary');
            $(this).addClass('active btn-primary').removeClass('btn-outline-secondary');
        });

        // Minus Button
        $('#btnMinusQty').on('click', function () {
            let cur = parseFloat($('#beriUddJml').val()) || 1;
            let step = cur > 1 ? 1 : 0.5;
            let next = Math.max(0.1, cur - step);
            $('#beriUddJml').val(next).trigger('input');
        });

        // Plus Button
        $('#btnPlusQty').on('click', function () {
            let cur = parseFloat($('#beriUddJml').val()) || 0;
            let max = parseFloat($('#beriUddMaxStok').val()) || 9999;
            let step = cur >= 1 ? 1 : 0.5;
            let next = Math.min(max, cur + step);
            $('#beriUddJml').val(next).trigger('input');
        });

        // Validasi real-time input Qty
        $('#beriUddJml').on('input', function () {
            const cur = parseFloat($(this).val()) || 0;
            const max = parseFloat($('#beriUddMaxStok').val()) || 9999;
            if (cur > max) {
                $('#alertQtyMax').removeClass('d-none');
                $('#btnSubmitBeriUdd').prop('disabled', true);
            } else if (cur <= 0) {
                $('#btnSubmitBeriUdd').prop('disabled', true);
            } else {
                $('#alertQtyMax').addClass('d-none');
                $('#btnSubmitBeriUdd').prop('disabled', false);
            }
        });

        // Waktu sekarang button
        $('#btnSetWaktuSekarang').on('click', function () {
            const now = new Date().toTimeString().split(' ')[0];
            $('#beriUddJamRiil').val(now);
            updateKepatuhanJam($('#beriUddJamJadwal').val(), now);
        });

        // Real-time jam riil input change
        $('#beriUddJamRiil').on('input', function () {
            updateKepatuhanJam($('#beriUddJamJadwal').val(), $(this).val());
        });

        // Submit form pemberian UDD
        $('#formBeriObatUdd').on('submit', function (e) {
            e.preventDefault();
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            const kodeBrng = $('#beriUddKodeBrng').val();
            const jamJadwal = $('#beriUddJamJadwal').val();
            const jamRiil = $('#beriUddJamRiil').val();
            const jml = parseFloat($('#beriUddJml').val());
            const aturanPakai = $('#beriUddCatatanAturan').val();
            const maxStok = parseFloat($('#beriUddMaxStok').val() || 9999);

            if (!jml || jml <= 0) {
                return Swal.fire('Perhatian', 'Jumlah obat yang diberikan harus lebih besar dari 0', 'warning');
            }
            if (jml > maxStok) {
                return Swal.fire('Perhatian', `Jumlah (${jml}) melebihi sisa stok di ruangan (${maxStok})`, 'warning');
            }
            if (!jamRiil) {
                return Swal.fire('Perhatian', 'Jam riil pemberian tidak boleh kosong', 'warning');
            }

            const btnSubmit = $('#btnSubmitBeriUdd');
            btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.post(`{{ url('/permintaan-stok-obat/berikan-obat') }}`, {
                _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                no_rawat: noRawat,
                kode_brng: kodeBrng,
                jam_jadwal: jamJadwal,
                jam_riil: jamRiil,
                jml: jml,
                aturan_pakai: aturanPakai
            }).done((res) => {
                $('#modalBeriObatUdd').modal('hide');
                toast(res.message);
                loadJadwalUddPasien(noRawat);
                loadPemberianObatRanap(noRawat);
            }).fail((err) => {
                alertErrorAjax(err);
            }).always(() => {
                btnSubmit.prop('disabled', false).html('<i class="ti ti-check me-1"></i> Simpan & Berikan Obat');
            });
        });

        function batalPemberianUddPasien(kodeBrng, tglRiil, jamRiil) {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();

            Swal.fire({
                title: 'Batalkan Pemberian Obat?',
                html: `Apakah Anda yakin ingin membatalkan pemberian obat ini?<br><small class="text-danger">Tagihan obat jam ini akan dihapus dari billing rawat inap.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Membatalkan pemberian...');
                    $.post(`{{ url('/permintaan-stok-obat/batal-beri-obat') }}`, {
                        _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                        no_rawat: noRawat,
                        kode_brng: kodeBrng,
                        tgl_perawatan: tglRiil,
                        jam: jamRiil
                    }).done((res) => {
                        loadingAjax().close();
                        toast(res.message);
                        loadJadwalUddPasien(noRawat);
                        loadPemberianObatRanap(noRawat);
                    }).fail((err) => {
                        loadingAjax().close();
                        alertErrorAjax(err);
                    });
                }
            });
        }
    </script>
@endpush

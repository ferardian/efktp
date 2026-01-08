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
                        @include('content.kamarInap.cppt.sub._form')
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        @include('content.kamarInap.cppt.sub._riwayat')
                    </div>
                    {{-- <div class="col-xl-6 col-lg-6">
                        @include('content.pemeriksaan.modal._tabResep')
                    </div> --}}
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
        var alergi = formCpptRanap.find('#alergi');
        var pegawai = formCpptRanap.find('#nip');
        var checkJam = formCpptRanap.find('#checkJam');
        var nip = "{{ session()->get('pegawai')->nik }}";
        var nmPegawai = "{{ session()->get('pegawai')->nama }}";
        var inputAlergi = formCpptRanap.find('#alergi');

        modalCpptRanap.on('hidden.bs.modal', () => {
            $('#listRiwayat').empty()
            selectAlergi(alergi, formCpptRanap);
        });

        modalCpptRanap.on('shown.bs.modal', () => {
            alergi.addClass('bg-red')
            checkboxTimer(checkJam)
            selectAlergi(alergi, formCpptRanap);
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
                selectPegawai(pegawai, formCpptRanap);
                const setPetugas = new Option(nmPegawai, nip, true, true);
                pegawai.append(setPetugas).trigger('change');
                setSelectAlergi(response.pasien.alergi, inputAlergi)
            })
        }

        function cpptRanap(no_rawat) {
            setInfoPasien(no_rawat);
            setRiwayatRanap(no_rawat);
            modalCpptRanap.modal('show');
        }

        function setSelectAlergi(alergi, element) {
            if (alergi.length) {
                element.empty()
                alergi.forEach((resAlergi) => {
                    const optionAlergi = new Option(resAlergi.alergi, resAlergi.alergi, true, true);
                    element.append(optionAlergi).trigger('change');
                });
            } else {
                element.empty()
            }
        }

        function createCpptRanap() {
            const data = getDataForm('formCpptRanap', ['input', 'select', 'textarea']);
            delete data[""];
            data['alergi'] = data['alergi'].map((item) => item).join(', ')
            $.post(`pemeriksaan/ranap`, data).done((response) => {
                alertSuccessAjax().then(() => {
                    createAlergi({
                        no_rkm_medis: data['no_rkm_medis'],
                        alergi: inputAlergi.val(),
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
            data['alergi'] = inputAlergi.val().map((item) => item).join(', ')
            $.post(`pemeriksaan/ranap/update`, data).done((response) => {
                alertSuccessAjax().then(() => {
                    createAlergi({
                        no_rkm_medis: data['no_rkm_medis'],
                        alergi: inputAlergi.val(),
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
    </script>
@endpush

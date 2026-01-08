<div class="card">
    <div class="card-status-top bg-success"></div>
    {{-- <div class="card-header">
    </div> --}}

    <div class="card-body">
        <p class="card-title m-1">Riwayat Pemeriksaan</p>
        <div class="row mb-2">
            <div class="col-lg-5 col-md-12 col-sm-12">
                <div class="input-group">
                    <input type="text" class="form-control filterTangal" id="tglCppt1" name="tglCppt1" value="{{ date('d-m-Y') }}">
                    <span class="input-group-text">s.d</span>
                    <input type="text" class="form-control filterTangal" id="tglCppt2" name="tglCppt2" value="{{ date('d-m-Y') }}">
                    <button type="button" class="btn btn-secondary" id="btnFilterCppt" name="btnFilterCppt" onclick="filterCpptRanap()"><i class="ti ti-search me-1"></i></button>
                </div>
            </div>
        </div>
        <div class="accordion" id="listRiwayat" style="height:56vh;overflow:auto">

        </div>
    </div>
</div>

@push('script')
    <script>
        var tglCppt1 = $('#tglCppt1');
        var tglCppt2 = $('#tglCppt2');


        function filterCpptRanap() {
            $.get(`/efktp/pemeriksaan/ranap`, {
                no_rawat: formCpptRanap.find('input[name="no_rawat"]').val(),
                tglCppt1: tglCppt1.val(),
                tglCppt2: tglCppt2.val(),
            }).done((response) => {
                $('#listRiwayat').empty();
                const pemeriksaan = response.map((values, index) => {
                    return `<div class="accordion-item">
                                <h2 class="accordion-header" id="heading-${index}">
                                    <button class="accordion-button ${values.pegawai.dokter ? 'bg-green-lt' : 'bg-orange-lt'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="true" aria-controls="collapse-${index}">
                                        ${formatTanggal(values.tgl_perawatan)} ${values.jam_rawat} : ${values.pegawai.nama}
                                    </button>
                                </h2>
                                <div id="collapse-${index}" class="accordion-collapse collapse" data-bs-parent="#listRiwayat" data-id="${no_rawat}" data-tanggal="${values.tgl_perawatan}" data-pegawai="${values.nip}" data-jam="${values.jam_rawat}">
                                    <div class="accordion-body pt-0">

                                    </div>
                                </div>
                            </div>`
                })
                $('#listRiwayat').append(pemeriksaan)
            })
        }

        $('#listRiwayat').on('show.bs.collapse', function(e) {
            const id = e.target.id;
            const no_rawat = $(`#${id}`).data('id');
            const tanggal = $(`#${id}`).data('tanggal');
            const nip = $(`#${id}`).data('nip');
            const jam = $(`#${id}`).data('jam');
            const body = $(`#${id}`).find('.accordion-body')
            const isShow = $(`#${id}`).hasClass('collapse')

            if (isShow) {
                body.empty()
                getCpptRanap(no_rawat, tanggal, jam).done((response) => {
                    // response.map((response) => {
                    const ttv = `<div class="card mb-1">
                            <div class="ribbon bg-red">TTV</div>
                            <div class="card-body card-text">
                                <div class="row gy-2">
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Suhu : ${response.suhu_tubuh} °C
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Tinggi : ${response.tinggi} cm
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Berat : ${response.berat} Kg
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Tensi : ${response.tensi} mmHg
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Respirasi : ${response.respirasi} x/mnt
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Nadi : ${response.nadi} x/mnt
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        SpO² : ${response.spo2} %
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        GCS : ${response.gcs} (E,V,M)
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        Kesadaran : ${response.kesadaran}
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 text-danger">
                                        Alergi : ${response.alergi}
                                    </div>
                                </div>
                            </div>
                        </div>`
                    const pemeriksaan = `<div class="card mb-1">
                            <div class="ribbon bg-red">SOAP</div>
                            <div class="card-body card-text">
                                <div class="row gy-2">
                                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                        <strong>Subjek </strong>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                         : ${stringPemeriksaan(response.keluhan)}
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                        <strong>Objek </strong>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                         : ${stringPemeriksaan(response.pemeriksaan)}
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                       <strong> Asesmen </strong>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                         : ${stringPemeriksaan(response.penilaian)}
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                        <strong>Plan </strong>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                         : ${stringPemeriksaan(response.rtl)}
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                        <strong>Instruksi </strong>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                         : ${stringPemeriksaan(response.instruksi)}
                                    </div>

                                </div>
                                <button class="btn btn-sm btn-primary mt-3" type="button" onclick="setCpptRanap('${response.no_rawat}', '${response.tgl_perawatan}', '${response.jam_rawat}', '${response.nip}')">
                                    <i class="ti ti-pencil me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger mt-3 ms-1" type="button" onclick="deleteCpptRanap('${response.no_rawat}', '${response.tgl_perawatan}', '${response.jam_rawat}', '${response.nip}')">
                                    <i class="ti ti-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </div>`
                    body.append([ttv, pemeriksaan]).hide().fadeIn()

                })
            }
        });

        function setRiwayatRanap(no_rawat) {
            $('#listRiwayat').empty();
            getCpptRanap(no_rawat).done((response) => {
                const pemeriksaan = response.map((values, index) => {
                    return `<div class="accordion-item">
                                <h2 class="accordion-header" id="heading-${index}">
                                    <button class="accordion-button ${values.pegawai.dokter ? 'bg-green-lt' : 'bg-orange-lt'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="true" aria-controls="collapse-${index}">
                                        ${formatTanggal(values.tgl_perawatan)} ${values.jam_rawat} : ${values.pegawai.nama}
                                    </button>
                                </h2>
                                <div id="collapse-${index}" class="accordion-collapse collapse" data-bs-parent="#listRiwayat" data-id="${no_rawat}" data-tanggal="${values.tgl_perawatan}" data-pegawai="${values.nip}" data-jam="${values.jam_rawat}">
                                    <div class="accordion-body">

                                    </div>
                                </div>
                            </div>`
                })
                $('#listRiwayat').append(pemeriksaan)
            })
        }

        function setRiwayat(no_rkm_medis) {
            $('#listRiwayat').empty()
            $.get(`/efktp/pasien/riwayat`, {
                no_rkm_medis: no_rkm_medis
            }).done((response) => {
                const regPeriksa = response.reg_periksa.map((regPeriksa, index) => {
                    const diagnosa = regPeriksa.diagnosa.map((diagnosa) => {
                        if (diagnosa.prioritas == 1) {
                            return `${diagnosa.kd_penyakit} ${diagnosa.penyakit.nm_penyakit}`
                        }
                    }).join('')
                    return `<div class="accordion-item">
                                <h2 class="accordion-header" id="heading-${index}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="true" aria-controls="collapse-${index}">
                                        ${formatTanggal(regPeriksa.tgl_registrasi)} : ${diagnosa}
                                    </button>
                                </h2>
                                <div id="collapse-${index}" class="accordion-collapse collapse" data-bs-parent="#listRiwayat" data-id="${regPeriksa.no_rawat}">
                                    <div class="accordion-body pt-0">

                                    </div>
                                </div>
                            </div>`
                })
                $('#listRiwayat').append(regPeriksa)
            })
        }

        function deleteCpptRanap(...params) {
            Swal.fire({
                title: "Yakin ?",
                html: "Data akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/pemeriksaan/ranap/delete`, {
                        no_rawat: params[0],
                        tgl_perawatan: params[1],
                        jam_rawat: params[2],
                        nip: params[3]
                    }).done((response) => {
                        toast();
                        setRiwayatRanap(params[0]);
                    }).fail((error) => {
                        alertErrorAjax(error);
                    })
                }
            });
        }

        function setCpptRanap(...params) {
            getCpptRanap(params[0], params[1], params[2]).done((response) => {
                const setPetugas = new Option(response.pegawai.nama, response.nip, true, true);
                pegawai.append(setPetugas).trigger('change');
                formCpptRanap.find('input[name="tgl_perawatan"').val(splitTanggal(response.tgl_perawatan));
                formCpptRanap.find('input[name="tgl_perawatan_awal"').val(splitTanggal(response.tgl_perawatan));
                formCpptRanap.find('input[name="checkJam"').prop('checked', true).trigger('change');
                formCpptRanap.find('input[name="jam_rawat"]').val(response.jam_rawat);
                formCpptRanap.find('input[name="jam_rawat_awal"]').val(response.jam_rawat);
                formCpptRanap.find('textarea[name="keluhan"]').val(response.keluhan)
                formCpptRanap.find('textarea[name="pemeriksaan"]').val(response.pemeriksaan)
                formCpptRanap.find('textarea[name="penilaian"]').val(response.penilaian)
                formCpptRanap.find('textarea[name="rtl"]').val(response.rtl)
                formCpptRanap.find('textarea[name="instruksi"]').val(response.instruksi)
                formCpptRanap.find('input[name="suhu_tubuh"]').val(response.suhu_tubuh)
                formCpptRanap.find('input[name="tinggi"]').val(response.tinggi)
                formCpptRanap.find('input[name="berat"]').val(response.berat)
                formCpptRanap.find('input[name="tensi"]').val(response.tensi)
                formCpptRanap.find('input[name="respirasi"]').val(response.respirasi)
                formCpptRanap.find('input[name="nadi"]').val(response.nadi)
                formCpptRanap.find('input[name="spo2"]').val(response.spo2)
                formCpptRanap.find('input[name="gcs"]').val(response.gcs)
                formCpptRanap.find(`select[name="kesadaran"] option:contains("${response.kesadaran}")`).prop('selected', true)
                setSelectAlergi(response.reg_periksa.pasien.alergi, inputAlergi)
                $('#btnResetCpptRanap').removeClass('d-none');
                $('#btnSalinCpptRanap').removeClass('d-none');
                $('#btnSimpanCpptRanap').attr('onclick', `updateCpptRanap('${response.no_rawat}', '${response.tgl_perawatan}', '${response.jam_rawat}', '${response.nip}')`);

            })
        }
    </script>
@endpush

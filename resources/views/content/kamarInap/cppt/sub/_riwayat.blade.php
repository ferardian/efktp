<div class="card">
    <div class="card-status-top bg-success"></div>
    {{-- <div class="card-header">
    </div> --}}

    <div class="card-body">
        <div class="accordion mb-3" id="infoMasuk">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-info-masuk">
                    <button class="accordion-button bg-blue-lt" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-info-masuk" aria-expanded="false">
                        <i class="ti ti-info-circle me-1"></i> Informasi Masuk (Triage & Asmed IGD)
                    </button>
                </h2>
                <div id="collapse-info-masuk" class="accordion-collapse collapse" data-bs-parent="#infoMasuk">
                    <div class="accordion-body p-2" id="contentInfoMasuk">
                        <div class="text-center">
                            <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
        <div class="accordion" id="listRiwayat" style="height:35vh;overflow:auto">

        </div>
    </div>
</div>

@push('script')
    <script>
        var tglCppt1 = $('#tglCppt1');
        var tglCppt2 = $('#tglCppt2');


        function filterCpptRanap() {
            $.get(`{{ url('/pemeriksaan/ranap') }}`, {
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
                    let htmlInfoMasuk = '';
                    if (response.reg_periksa && (response.reg_periksa.triase_igd || response.reg_periksa.penilaian_medis_igd)) {
                        htmlInfoMasuk = '<div class="card mb-1 shadow-none border-info"><div class="card-body p-2"><h4 class="card-title text-info mb-2"><i class="ti ti-info-circle"></i> INFORMASI MASUK (IGD)</h4>';
                        
                        if (response.reg_periksa.triase_igd) {
                            let scales = '';
                            const triase = response.reg_periksa.triase_igd;
                            [1, 2, 3, 4, 5].forEach(i => {
                                if (triase['skala' + i] && triase['skala' + i].length) {
                                    triase['skala' + i].forEach(s => {
                                        if (s.master) {
                                            const colors = ['', 'bg-red', 'bg-orange', 'bg-yellow text-dark', 'bg-green', 'bg-blue'];
                                            scales += `<span class="badge ${colors[i]} me-1 mb-1">S${i}: ${s.master['pengkajian_skala' + i]}</span> `;
                                        }
                                    });
                                }
                            });
                            htmlInfoMasuk += `
                                <div class="mb-2 p-2 border-start border-3 border-danger bg-light">
                                    <h5 class="text-danger mb-1 small"><i class="ti ti-heart-rate-monitor"></i> TRIAGE</h5>
                                    <div class="small"><b>Keluhan:</b> ${triase.keluhan_utama || '-'}</div>
                                    <div class="small"><b>Skala:</b> ${scales || '-'}</div>
                                </div>`;
                        }

                        if (response.reg_periksa.penilaian_medis_igd) {
                            const asmed = response.reg_periksa.penilaian_medis_igd;
                            htmlInfoMasuk += `
                                <div class="mb-0 p-2 border-start border-3 border-primary bg-light">
                                    <h5 class="text-primary mb-1 small"><i class="ti ti-stethoscope"></i> ASMED IGD</h5>
                                    <div class="row g-2 mb-1 small">
                                        <div class="col-12"><b>Anamnesis:</b> ${asmed.anamnesis} (${asmed.hubungan})</div>
                                        <div class="col-12"><b>Keluhan Utama:</b> ${asmed.keluhan_utama || '-'}</div>
                                        <div class="col-6"><b>RPS:</b> ${asmed.rps || '-'}</div>
                                        <div class="col-6"><b>RPD:</b> ${asmed.rpd || '-'}</div>
                                        <div class="col-6"><b>RPK:</b> ${asmed.rpk || '-'}</div>
                                        <div class="col-6"><b>RPO:</b> ${asmed.rpo || '-'}</div>
                                        <div class="col-12 border-top pt-1 mt-1"><b>Pemeriksaan Fisik:</b> ${asmed.ket_fisik || '-'}</div>
                                        <div class="col-12"><b>Diagnosis:</b> <span class="text-primary font-weight-bold">${asmed.diagnosis || '-'}</span></div>
                                        <div class="col-12"><b>Tata Laksana:</b> ${asmed.tata || '-'}</div>
                                    </div>
                                    <div class="row g-2 mt-1 small border-top pt-1">
                                        <div class="col-3"><b>TD:</b> ${asmed.td || '-'}</div>
                                        <div class="col-3"><b>Nadi:</b> ${asmed.nadi || '-'}</div>
                                        <div class="col-3"><b>RR:</b> ${asmed.rr || '-'}</div>
                                        <div class="col-3"><b>Suhu:</b> ${asmed.suhu || '-'}</div>
                                    </div>
                                </div>`;
                        }
                        htmlInfoMasuk += '</div></div>';
                    }

                    body.append([ttv, pemeriksaan, htmlInfoMasuk]).hide().fadeIn()

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
            $.get(`{{ url('/pasien/riwayat') }}`, {
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
                    $.post(`{{ url('/pemeriksaan/ranap/delete') }}`, {
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

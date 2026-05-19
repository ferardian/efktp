<div class="card" style="min-height: 85vh;">
    <div class="card-status-top bg-success"></div>
    <div class="card-header p-2">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tabs-riwayat-soap" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                    <i class="ti ti-history me-1"></i> Riwayat & SOAP
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabs-grafik-vital" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                    <i class="ti ti-chart-line me-1"></i> Grafik Perkembangan
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade active show" id="tabs-riwayat-soap" role="tabpanel">
        <div class="accordion mb-3" id="infoMasuk">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-info-masuk">
                    <button class="accordion-button bg-blue-lt" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-info-masuk" aria-expanded="false">
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
                    <input type="text" class="form-control filterTangal" id="tglCppt1" name="tglCppt1"
                        value="{{ date('d-m-Y') }}">
                    <span class="input-group-text">s.d</span>
                    <input type="text" class="form-control filterTangal" id="tglCppt2" name="tglCppt2"
                        value="{{ date('d-m-Y') }}">
                    <button type="button" class="btn btn-secondary" id="btnFilterCppt" name="btnFilterCppt"
                        onclick="filterCpptRanap()"><i class="ti ti-search me-1"></i></button>
                </div>
            </div>
        </div>
        <div class="accordion" id="listRiwayat" style="height: 75vh; overflow-y: auto; overflow-x: hidden;">

        </div>
    </div>

    <div class="tab-pane fade" id="tabs-grafik-vital" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title m-0">Grafik Tanda Vital Harian</h4>
            <span class="badge bg-green-lt"><i class="ti ti-pulse me-1"></i>Vital Signs</span>
        </div>
        <div class="card p-2 bg-light shadow-none border">
            <div id="chart-vital-signs" style="min-height: 400px; width: 100%;"></div>
        </div>
    </div>
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
                $('#listRiwayat').append(pemeriksaan);
                renderVitalSignsChart(response);
            })
        }

        $('#listRiwayat').on('show.bs.collapse', function (e) {
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
            loadInfoMasuk(no_rawat);
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
                $('#listRiwayat').append(pemeriksaan);
                renderVitalSignsChart(response);
            })
        }

        function loadInfoMasuk(no_rawat) {
            const container = $('#contentInfoMasuk');
            container.html('<div class="text-center"><div class="spinner-border spinner-border-sm text-secondary"></div></div>');

            getRegDetail(no_rawat).done((response) => {
                let html = '';
                if (response.triase_igd || response.penilaian_medis_igd || response.triase_ugd) {
                    if (response.triase_igd) {
                        let scales = '';
                        const triase = response.triase_igd;
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
                        html += `
                            <div class="mb-2 p-2 border-start border-3 border-danger bg-light rounded shadow-sm">
                                <h5 class="text-danger mb-1"><i class="ti ti-heart-rate-monitor"></i> TRIAGE IGD</h5>
                                <div class="small"><b>Keluhan Utama:</b> ${triase.keluhan_utama || '-'}</div>
                                <div class="mt-1">${scales || '-'}</div>
                            </div>`;
                    }

                    if (response.triase_ugd) {
                        const triaseUgd = response.triase_ugd;
                        
                        let catBadge = '';
                        if (triaseUgd.skala_triase === 'Kategori 1') {
                            catBadge = '<span class="badge bg-red text-white">Kategori 1 (Resusitasi)</span>';
                        } else if (triaseUgd.skala_triase === 'Kategori 2') {
                            catBadge = '<span class="badge bg-orange text-white">Kategori 2 (Emergensi)</span>';
                        } else if (triaseUgd.skala_triase === 'Kategori 3') {
                            catBadge = '<span class="badge bg-warning text-dark">Kategori 3 (Urgen)</span>';
                        } else if (triaseUgd.skala_triase === 'Kategori 4') {
                            catBadge = '<span class="badge bg-success text-white">Kategori 4 (Non Urgen)</span>';
                        } else {
                            catBadge = `<span class="badge bg-secondary text-white">${triaseUgd.skala_triase || '-'}</span>`;
                        }

                        let primerText = '';
                        if (triaseUgd.survey_primer) {
                            let survey = triaseUgd.survey_primer;
                            if (typeof survey === 'string') {
                                try {
                                    survey = JSON.parse(survey);
                                } catch(e) {}
                            }
                            
                            let values = [];
                            function collectValues(obj) {
                                if (Array.isArray(obj)) {
                                    obj.forEach(val => {
                                        if (typeof val === 'string') {
                                            let formatted = val.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                                            if (formatted.toLowerCase() === 'ku baik') formatted = 'K/U Baik';
                                            if (formatted.toLowerCase() === 'ku lemah') formatted = 'K/U Lemah';
                                            values.push(formatted);
                                        }
                                    });
                                } else if (typeof obj === 'object' && obj !== null) {
                                    Object.values(obj).forEach(collectValues);
                                } else if (typeof obj === 'string') {
                                    let formatted = obj.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                                    if (formatted.toLowerCase() === 'ku baik') formatted = 'K/U Baik';
                                    if (formatted.toLowerCase() === 'ku lemah') formatted = 'K/U Lemah';
                                    values.push(formatted);
                                }
                            }
                            collectValues(survey);
                            primerText = values.length ? values.join(', ') : '-';
                        } else {
                            primerText = '-';
                        }

                        let nyeriDetails = '-';
                        if (triaseUgd.skala_nyeri !== null && triaseUgd.skala_nyeri !== undefined) {
                            nyeriDetails = `<b>Skor Nyeri:</b> <span class="badge bg-secondary">${triaseUgd.skala_nyeri}/10</span>`;
                            if (triaseUgd.nyeri_tipe) nyeriDetails += ` (${triaseUgd.nyeri_tipe})`;
                            if (triaseUgd.nyeri_lokasi) nyeriDetails += `, <b>Lokasi:</b> ${triaseUgd.nyeri_lokasi}`;
                            if (triaseUgd.nyeri_durasi) nyeriDetails += `, <b>Durasi:</b> ${triaseUgd.nyeri_durasi}`;
                        }

                        let jatuhDetails = '-';
                        if (triaseUgd.resiko_jatuh) {
                            jatuhDetails = `<b>${triaseUgd.resiko_jatuh}</b>`;
                            if (triaseUgd.resiko_jatuh_skor !== null) jatuhDetails += ` (Skor: ${triaseUgd.resiko_jatuh_skor})`;
                        }

                        let bodyMapHtml = '';
                        let points = triaseUgd.body_map_points;
                        if (typeof points === 'string') {
                            try {
                                points = JSON.parse(points);
                            } catch (e) {
                                points = null;
                            }
                        }
                        const hasPoints = Array.isArray(points) && points.length > 0;
                        const hasLuka = triaseUgd.luka_perdarahan && triaseUgd.luka_perdarahan.trim().length > 0;
                        
                        if (hasPoints || hasLuka) {
                            let markers = '';
                            if (hasPoints) {
                                points.forEach((pt, index) => {
                                    markers += `<span class="position-absolute badge rounded-pill bg-danger border border-white d-flex align-items-center justify-content-center" style="width: 14px; height: 14px; font-size: 8px; padding: 0; left: ${pt.x * 0.5}px; top: ${pt.y * 0.5}px; transform: translate(-50%, -50%); z-index: 20;">${index + 1}</span>`;
                                });
                            }

                            bodyMapHtml = `
                                <div class="col-12 border-top pt-2 mt-2">
                                    <div class="row g-2 align-items-center">
                                        ${hasPoints ? `
                                        <div class="col-sm-4 text-center">
                                            <div class="position-relative" style="width: 160px; height: 160px; margin: 0 auto; background: #fff; border: 1px solid #dee2e6; border-radius: 4px; overflow: hidden;">
                                                <img src="{{ asset('img/body_map.png') }}" style="width: 100%; height: 100%; object-fit: contain;">
                                                ${markers}
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size: 9px;">Peta Lokasi Luka</small>
                                        </div>` : ''}
                                        <div class="${hasPoints ? 'col-sm-8' : 'col-12'}">
                                            <div class="small"><b>Keterangan Luka & Perdarahan:</b></div>
                                            <div class="text-muted small p-2 bg-white border rounded" style="min-height: 50px; font-size: 11px;">
                                                ${triaseUgd.luka_perdarahan || '<i>Tidak ada rincian luka/perdarahan</i>'}
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        }

                        const petugasNama = triaseUgd.petugas ? triaseUgd.petugas.nama : '-';

                        html += `
                            <div class="mb-2 p-2 border-start border-3 border-danger bg-light rounded shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="text-danger mb-1"><i class="ti ti-activity-heartbeat"></i> TRIAGE IGD KLINIK</h5>
                                    <div>${catBadge}</div>
                                </div>
                                <div class="row g-2 small">
                                    <div class="col-12"><b>Keluhan Utama:</b> ${triaseUgd.keluhan_utama || '-'}</div>
                                    <div class="col-12"><b>Survey Primer:</b> <span class="text-muted">${primerText}</span></div>
                                    <div class="col-6"><b>Asesmen Nyeri:</b><br>${nyeriDetails}</div>
                                    <div class="col-6"><b>Resiko Jatuh:</b><br>${jatuhDetails}</div>
                                    ${bodyMapHtml}
                                    <div class="col-12 border-top pt-1 mt-1 d-flex justify-content-between text-muted" style="font-size: 10px;">
                                        <span><b>Tgl Triase:</b> ${formatTanggal(triaseUgd.tgl_triase.substring(0, 10))} ${triaseUgd.tgl_triase.substring(11, 16)}</span>
                                        <span><b>Petugas:</b> ${petugasNama}</span>
                                    </div>
                                </div>
                            </div>`;
                    }

                    if (response.penilaian_medis_igd) {
                        const asmed = response.penilaian_medis_igd;
                        html += `
                            <div class="mb-0 p-2 border-start border-3 border-primary bg-light rounded shadow-sm">
                                <h5 class="text-primary mb-1"><i class="ti ti-stethoscope"></i> ASMED IGD</h5>
                                <div class="row g-2 mb-1 small">
                                    <div class="col-12"><b>Anamnesis:</b> ${asmed.anamnesis} (${asmed.hubungan})</div>
                                    <div class="col-12"><b>Keluhan Utama:</b> ${asmed.keluhan_utama || '-'}</div>
                                    <div class="col-6"><b>RPS:</b> ${asmed.rps || '-'}</div>
                                    <div class="col-6"><b>RPD:</b> ${asmed.rpd || '-'}</div>
                                    <div class="col-6"><b>RPK:</b> ${asmed.rpk || '-'}</div>
                                    <div class="col-6"><b>RPO:</b> ${asmed.rpo || '-'}</div>
                                    <div class="col-12 border-top pt-1 mt-1"><b>Pemeriksaan Fisik:</b> ${asmed.ket_fisik || '-'}</div>
                                    <div class="col-12"><b>Diagnosis:</b> <span class="text-primary fw-bold">${asmed.diagnosis || '-'}</span></div>
                                    <div class="col-12"><b>Tata Laksana:</b> ${asmed.tata || '-'}</div>
                                </div>
                                <div class="row g-2 mt-1 small border-top pt-1 text-center">
                                    <div class="col-3"><b>TD:</b><br>${asmed.td || '-'}</div>
                                    <div class="col-3"><b>Nadi:</b><br>${asmed.nadi || '-'}</div>
                                    <div class="col-3"><b>RR:</b><br>${asmed.rr || '-'}</div>
                                    <div class="col-3"><b>Suhu:</b><br>${asmed.suhu || '-'}</div>
                                </div>
                            </div>`;
                    }
                    // Auto expand if data exists
                    $('#collapse-info-masuk').addClass('show');
                    $('.accordion-button[data-bs-target="#collapse-info-masuk"]').removeClass('collapsed').attr('aria-expanded', 'true');
                } else {
                    html = '<div class="text-center p-3 text-muted"><i>Tidak ada data Triage/Asmed IGD untuk pendaftaran ini</i></div>';
                }
                container.html(html);
            }).fail(() => {
                container.html('<div class="text-center text-danger p-2">Gagal memuat data informasi masuk</div>');
            });
        }

        function setRiwayat(no_rkm_medis) {
            $('#listRiwayat').empty()
            // Reset info masuk
            $('#contentInfoMasuk').html('<div class="text-center p-3 text-muted"><i>Pilih riwayat untuk melihat informasi masuk</i></div>');

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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="false" onclick="loadInfoMasuk('${regPeriksa.no_rawat}')">
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
                formCpptRanap.find('input[name="tgl_perawatan"]').val(splitTanggal(response.tgl_perawatan));
                formCpptRanap.find('input[name="tgl_perawatan_awal"]').val(splitTanggal(response.tgl_perawatan));
                formCpptRanap.find('input[name="checkJam"]').prop('checked', true).trigger('change');
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
                setSelectAlergi(response.reg_periksa.pasien.alergi, alergi)
                $('#btnResetCpptRanap').removeClass('d-none');
                $('#btnSalinCpptRanap').removeClass('d-none');
                $('#btnSimpanCpptRanap').attr('onclick', `updateCpptRanap('${response.no_rawat}', '${response.tgl_perawatan}', '${response.jam_rawat}', '${response.nip}')`);

            })
        }

        var vitalChartInstance = null;

        function renderVitalSignsChart(data) {
            if (!data || !data.length) {
                $('#chart-vital-signs').html('<div class="text-center p-5 text-muted"><i>Tidak ada data pemeriksaan tanda vital untuk pasien ini</i></div>');
                if (vitalChartInstance) {
                    vitalChartInstance.destroy();
                    vitalChartInstance = null;
                }
                return;
            }

            const sortedData = data.slice().reverse();

            const categories = [];
            const tempSeries = [];
            const pulseSeries = [];
            const respSeries = [];
            const spo2Series = [];
            const sysSeries = [];
            const diaSeries = [];

            sortedData.forEach(item => {
                const formattedTime = formatTanggal(item.tgl_perawatan) + ' ' + item.jam_rawat.substring(0, 5);
                categories.push(formattedTime);

                tempSeries.push(item.suhu_tubuh && item.suhu_tubuh !== '-' && item.suhu_tubuh !== '' ? parseFloat(item.suhu_tubuh.replace(',', '.')) : null);
                pulseSeries.push(item.nadi && item.nadi !== '-' && item.nadi !== '' ? parseInt(item.nadi) : null);
                respSeries.push(item.respirasi && item.respirasi !== '-' && item.respirasi !== '' ? parseInt(item.respirasi) : null);
                spo2Series.push(item.spo2 && item.spo2 !== '-' && item.spo2 !== '' ? parseInt(item.spo2) : null);

                let systolic = null;
                let diastolic = null;
                if (item.tensi && item.tensi !== '-' && item.tensi !== '' && item.tensi.includes('/')) {
                    const parts = item.tensi.split('/');
                    const sysVal = parseInt(parts[0]);
                    const diaVal = parseInt(parts[1]);
                    if (!isNaN(sysVal)) systolic = sysVal;
                    if (!isNaN(diaVal)) diastolic = diaVal;
                }
                sysSeries.push(systolic);
                diaSeries.push(diastolic);
            });

            const checkData = [...tempSeries, ...pulseSeries, ...respSeries, ...spo2Series, ...sysSeries, ...diaSeries].some(v => v !== null);
            if (!checkData) {
                $('#chart-vital-signs').html('<div class="text-center p-5 text-muted"><i>Belum ada pencatatan tanda vital (Suhu, Nadi, Tensi, dll) untuk pasien ini</i></div>');
                if (vitalChartInstance) {
                    vitalChartInstance.destroy();
                    vitalChartInstance = null;
                }
                return;
            }

            const options = {
                series: [
                    {
                        name: 'Suhu (°C)',
                        data: tempSeries
                    },
                    {
                        name: 'Nadi (x/mnt)',
                        data: pulseSeries
                    },
                    {
                        name: 'Sistole (mmHg)',
                        data: sysSeries
                    },
                    {
                        name: 'Diastole (mmHg)',
                        data: diaSeries
                    },
                    {
                        name: 'Respirasi (x/mnt)',
                        data: respSeries
                    },
                    {
                        name: 'SpO2 (%)',
                        data: spo2Series
                    }
                ],
                chart: {
                    type: 'line',
                    height: 400,
                    zoom: {
                        enabled: true
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    },
                    fontFamily: 'inherit'
                },
                colors: [
                    '#d63939', // Suhu - Red
                    '#206bc4', // Nadi - Blue
                    '#4299e1', // Sistole - Light Blue
                    '#90cdf4', // Diastole - Very Light Blue
                    '#2fb344', // Respirasi - Green
                    '#f59f00'  // SpO2 - Orange/Yellow
                ],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [3, 3, 2, 2, 3, 3],
                    curve: 'smooth',
                    dashArray: [0, 0, 0, 4, 0, 0]
                },
                grid: {
                    borderColor: '#f1f1f1',
                    row: {
                        colors: ['transparent', 'transparent'],
                        opacity: 0.5
                    }
                },
                markers: {
                    size: 4,
                    hover: {
                        sizeOffset: 2
                    }
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Nilai Klinis'
                    },
                    min: 10,
                    max: function(max) {
                        return Math.max(max + 10, 200);
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined" && y !== null) {
                                return y;
                            }
                            return "-";
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center'
                }
            };

            $('#chart-vital-signs').empty();
            if (vitalChartInstance) {
                vitalChartInstance.destroy();
            }
            vitalChartInstance = new ApexCharts(document.querySelector("#chart-vital-signs"), options);
            vitalChartInstance.render();
        }

        $(document).on('shown.bs.tab', 'a[href="#tabs-grafik-vital"]', function () {
            window.dispatchEvent(new Event('resize'));
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('libs/apexcharts/dist/apexcharts.css') }}">
    <style>
        #chart-vital-signs {
            width: 100% !important;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('libs/apexcharts/dist/apexcharts.min.js') }}"></script>
@endpush
<div class="card">
    <div class="card-status-top bg-success"></div>
    <div class="card-body">
        <h5 class="card-title">RIWAYAT PERIKSA</h5>
        <div class="accordion" id="listRiwayat" style="max-height: 72vh;overflow:scroll">

        </div>
    </div>
</div>
@push('script')
    <script>
        function setButtonResep(noResep) {
            const noRawat = formCpptRajal.find('input[name=no_rawat]').val();
            if (noResep) {
                btnTambahResep.removeClass('btn-primary').addClass('btn-danger');
                btnTambahResep.attr('onclick', `hapusResep('${noRawat}')`)
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
                btnTambahResep.attr('onclick', `tambahResep('${noRawat}')`)
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

        function setRiwayatPemeriksaan(no_rawat, nip) {
            getPemeriksaanRalan(no_rawat, nip).done((response) => {
                Object.keys(response).map((key, index) => {
                    const select = formCpptRajal.find(`select[name=${key}]`)
                    const input = formCpptRajal.find(`input[name=${key}]`)
                    const textarea = formCpptRajal.find(`textarea[name=${key}]`)

                    if (textarea.length) {
                        textarea.val() === '-' || textarea.val() === '0' ? textarea.val(response[key]) : ''
                    } else {
                        textarea.val('-')
                    }

                    if (input.length) {
                        if (key != 'no_rawat') {
                            input.val() === '-' || input.val() === '0' ? input.val(response[key]) : ''
                        }
                    } else {
                        input.val('-')
                    }

                    if (select.length) {
                        select.find(`option:contains("${response[key]}")`).attr('selected', 'selected')
                    }
                })

            })
        }

        function setRiwayatResep(no_rawat) {
            const inptNoResep = $('#no_resep');
            const nip = formCpptRajal.find('input[name=nip]').val();
            const inptNoRawat = formCpptRajal.find('input[name=no_rawat]')
            getResep({
                no_rawat: no_rawat,
            }).done((reseps) => {
                console.log('RESPONSE ===', reseps)
                if (reseps.length) {
                    Swal.fire({
                        title: "Terdapat resep pada pemeriksaan ini",
                        html: "apakah anda akan menggunakan resep yang sama ?",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Iya, Salin",
                        cancelButtonText: "Tidak"
                    }).then((result) => {
                        const no_rawat = inptNoRawat.val();
                        if (result.isConfirmed) {
                            let no_resep = inptNoResep.val();
                            if (!no_resep) {
                                createResepObat(no_rawat, 'ralan', nip).done((response) => {
                                    setButtonResep(response.no_resep);
                                    createCopyResep(response.no_resep, reseps)
                                    inptNoResep.val(response.no_resep)
                                }).fail((request) => {
                                    alertErrorAjax(request);
                                })
                            } else {
                                createCopyResep(no_resep, reseps)
                                // createCopyResep(no_resep, reseps).done((response) => {
                                // })
                                // inptNoResep.val(response.no_resep)
                            }
                        }
                    });
                }
            });
        }

        function createCopyResep(no_resep, data) {
            console.log('DATA ===', data);

            data.forEach((resep) => {
                const dataObat = resep.resep_dokter.map((item) => ({
                    no_resep: no_resep,
                    kode_brng: item.kode_brng,
                    jml: item.jml,
                    aturan_pakai: item.aturan_pakai,
                }));

                console.log('dataObat', dataObat);

                const dataResepRacik = resep.resep_racikan.map((item) => {
                    return {
                        no_resep: no_resep,
                        no_racik: item.no_racik,
                        nama_racik: item.nama_racik,
                        jml_dr: item.jml_dr,
                        aturan_pakai: item.aturan_pakai,
                        kd_racik: item.kd_racik,
                        keterangan: '-',
                        detail: item.detail
                    }
                });

                $.post(`{{ url('/resep/dokter/create') }}`, {
                    dataObat
                }).done((response) => {
                    setResepDokter(no_resep);
                    showToast('Resep umum berhasil ditambah');
                }).fail((error) => {
                    alertErrorAjax(error);
                });

                createResepRacikan(dataResepRacik).done((responseRacik) => {
                    const detail = dataResepRacik.map((item) => {
                        const detail = item.detail.map((subitem) =>
                            ({
                                no_resep: no_resep,
                                kandungan: subitem.kandungan ? subitem.kandungan : 0,
                                kode_brng: subitem.kode_brng,
                                jml: subitem.jml,
                                no_racik: subitem.no_racik,
                                p1: subitem.p1,
                                p2: subitem.p2,
                            })
                        )
                        createDetailRacikan(no_resep, item.no_racik, detail).done((responseDetail) => {
                            setResepRacikan(no_resep);
                            showToast('Resep racikan berhasil ditambah');
                        }).fail((error) => {
                            alertErrorAjax(error);
                        });
                    })
                }).fail((error) => {
                    alertErrorAjax(error);
                });
            });
        }


        function setRiwayatDiagnosaPasien(noRawatNow, no_rawat) {
            getDiagnosaPasien(noRawatNow).done((response) => {
                if (response.length >= 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan Diagnosa',
                        html: `Sudah terdapat diagnosa <span class="text-danger">${response.map((item) => item.kd_penyakit).join(', ')}</span> pada pemeriksaan ini, anda mau mengganti diagnosa ?`,
                        showConfirmButton: true,
                        confirmButtonText: 'Ya, Ganti',
                        showCancelButton: true,
                        cancelButtonText: 'Tidak',
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            getDiagnosaPasien(no_rawat).done((resDiagnosa) => {
                                if (resDiagnosa.length) {
                                    const diagnosa = resDiagnosa.map((dx) => {
                                        return {
                                            no_rawat: noRawatNow,
                                            kd_penyakit: dx.kd_penyakit,
                                            prioritas: dx.prioritas,
                                        }
                                    })
                                    $.post(`{{ url('/diagnosa/pasien/update') }}`, {
                                        data: diagnosa
                                    }).fail((request) => {
                                        alertErrorAjax(request)
                                    }).done((response) => {
                                        toast('Berhasil mengganti diagnosa');
                                        tulisAsesmen(noRawatNow)
                                    })
                                }

                            })

                        }

                    })
                    return true;

                }

                getDiagnosaPasien(no_rawat).done((resDiagnosa) => {
                    if (resDiagnosa.length) {
                        const diagnosa = resDiagnosa.map((dx) => {
                            return {
                                no_rawat: noRawatNow,
                                kd_penyakit: dx.kd_penyakit,
                                prioritas: dx.prioritas,
                            }
                        })
                        // copy diagnosa
                        $.post(`{{ url('/diagnosa/pasien/create') }}`, {
                            data: diagnosa
                        }).fail((request) => {
                            alertErrorAjax(request)
                        })
                    }

                })
            })
        }

        function setRiwayatTindakanPasien(noRawatNow, no_rawat) {
            getTindakanPasien(no_rawat).done((response) => {
                if (response.length >= 1) {
                    Swal.fire({
                        title: 'Peringatan Tindakan/Prosedur ?',
                        html: `Sudah terdapat tindakan/prosedur <span class="text-danger">${response.map((item) => item.kode)}</span>, apakah anda ingin menggantinya ?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Ganti",
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            getTindakanPasien(no_rawat).done((resTindakan) => {
                                if (resTindakan.length) {
                                    const tindakan = resTindakan.map((px) => {
                                        return {
                                            no_rawat: noRawatNow,
                                            kode: px.kode,
                                            prioritas: px.prioritas
                                        }
                                    });

                                    $.post(`{{ url('/prosedur/pasien/update') }}`, {
                                        data: tindakan
                                    }).fail((request) => {
                                        alertErrorAjax(request)
                                    }).done((response) => {
                                        toast('Berhasil mengganti prosedur');
                                        tulisInstruksi(noRawatNow)
                                    })
                                }
                            })
                        }
                    })
                    return false;
                }
                getTindakanPasien(no_rawat).done((resTindakan) => {
                    if (resTindakan.length) {
                        const tindakan = resTindakan.map((px) => {
                            return {
                                no_rawat: noRawatNow,
                                kode: px.kode,
                                prioritas: px.prioritas
                            }
                        });

                        $.post(`{{ url('/prosedur/pasien/create') }}`, {
                            data: tindakan
                        }).fail((request) => {
                            alertErrorAjax(request)
                        })
                    }
                })
            })
        }


        function salinCppt(no_rawat, nip) {
            const formCpptRajal = $('#formCpptRajal');
            let noResep = $('#no_resep').val();
            const noRawat = formCpptRajal.find('input[name=no_rawat]').val();
            const dokter = formCpptRajal.find('input[name=nip]').val();

            // set pemeriksaan
            setRiwayatPemeriksaan(no_rawat, nip)

            // copy diagnosa◘
            setRiwayatDiagnosaPasien(noRawat, no_rawat)

            // copy tindakan/prosedur
            setRiwayatTindakanPasien(noRawat, no_rawat)


            //copy resep
            setRiwayatResep(no_rawat)


        }

        $('#listRiwayat').on('show.bs.collapse', function (e) {
            const id = e.target.id;
            const no_rawat = $(`#${id}`).data('id');
            const body = $(`#${id}`).find('.accordion-body')
            const isShow = $(`#${id}`).hasClass('collapse')

            if (isShow) {
                body.empty()
                getPemeriksaanRalan(no_rawat).done((response) => {
                    console.log('RESPONSE ===', response)
                    response.map((result) => {

                        const keterangan = `<div class="card mb-1">
                                                <div class="card-body card-text">
                                                    <div class="row gy-2">
                                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                                               <strong>No. Rawat </strong>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                                             : ${result.no_rawat}
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                                            <strong>Tgl Periksa </strong>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                                             : ${formatTanggal(result.tgl_perawatan)} ${result.jam_rawat}
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                                            <strong>Petugas/Dokter </strong>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                                             : ${result.rujuk_internal ? result.rujuk_internal.dokter.nm_dokter : result.pegawai.nama}
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                                            <strong>Poliklinik</strong>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                                             : ${result.rujuk_internal ? result.rujuk_internal.poliklinik.nm_poli : result.reg_periksa.poliklinik.nm_poli}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`

                        const ttv = `<div class="card mb-1">
                                <div class="ribbon bg-red">TTV</div>
                                <div class="card-body card-text">
                                    <div class="row gy-2">
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Suhu : ${result.suhu_tubuh} °C
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Tinggi : ${result.tinggi} cm
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Berat : ${result.berat} Kg
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Tensi : ${result.tensi} mmHg
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Respirasi : ${result.respirasi} x/mnt
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Nadi : ${result.nadi} x/mnt
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            SpO² : ${result.spo2} %
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            GCS : ${result.gcs} (E,V,M)
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Kesadaran : ${result.kesadaran}
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Alergi : ${result.alergi}
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                            Lingkar Perut : ${result.lingkar_perut} cm
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
                                             : ${stringPemeriksaan(result.keluhan)}
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <strong>Objek </strong>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                             : ${stringPemeriksaan(result.pemeriksaan)}
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                           <strong> Asesmen </strong>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                             : ${stringPemeriksaan(result.penilaian)}
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <strong>Plan </strong>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                             : ${stringPemeriksaan(result.rtl)}
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <strong>Instruksi </strong>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                             : ${stringPemeriksaan(result.instruksi)}
                                        </div>
    
                                    </div>
                                    <button class="btn btn-sm btn-primary mt-3" type="button" onclick="salinCppt('${result.no_rawat}', '${result.nip}')">
                                        <i class="ti ti-copy me-1"></i> Copy CPPT
                                    </button>
                                    <button class="btn btn-sm btn-success mt-3" type="button" onclick="modalUploadPenunjang('${result.no_rawat}')">
                                        <i class="ti ti-eye me-1"></i> Berkas Upload
                                    </button>
                                    ${result.resep.length ? 'zzzz' : 'xxxxx'}

                                </div>
                            </div>`
                        let htmlInfoMasuk = '';
                        if (result.reg_periksa && (result.reg_periksa.triase_igd || result.reg_periksa.penilaian_medis_igd)) {
                            htmlInfoMasuk = '<div class="card mb-1 shadow-none border-info"><div class="card-body p-2"><h4 class="card-title text-info mb-2"><i class="ti ti-info-circle"></i> INFORMASI MASUK (IGD)</h4>';
                            
                            if (result.reg_periksa.triase_igd) {
                                let scales = '';
                                const triase = result.reg_periksa.triase_igd;
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

                            if (result.reg_periksa.penilaian_medis_igd) {
                                const asmed = result.reg_periksa.penilaian_medis_igd;
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

                        let htmlLab = '';
                        if (result.reg_periksa && result.reg_periksa.periksa_lab && result.reg_periksa.periksa_lab.length) {
                            htmlLab = '<div class="card mb-1 shadow-none border-success"><div class="card-body p-2"><h4 class="card-title text-success mb-2"><i class="ti ti-flask"></i> HASIL LABORATORIUM</h4>';
                            result.reg_periksa.periksa_lab.forEach(lab => {
                                htmlLab += `<div class="mb-2 p-2 border-start border-3 border-success bg-light">
                                    <h5 class="text-success mb-1 small">
                                        <i class="ti ti-test-pipe"></i> ${lab.jenis ? lab.jenis.nm_perawatan : 'Pemeriksaan Lab'}
                                        <span class="text-muted float-end small">${formatTanggal(lab.tgl_periksa)} ${lab.jam}</span>
                                    </h5>`;
                                if (lab.detail && lab.detail.length) {
                                    htmlLab += `<table class="table table-sm table-bordered mb-0 small">
                                        <thead>
                                            <tr class="bg-light text-center">
                                                <th>Pemeriksaan</th>
                                                <th>Hasil</th>
                                                <th>Satuan</th>
                                                <th>Nilai Rujukan</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                                    lab.detail.forEach(det => {
                                        const keterangan = det.keterangan.toUpperCase();
                                        let rowClass = '';
                                        let textClass = '';
                                        if (keterangan === 'H') {
                                            rowClass = 'bg-red-lt fw-bold';
                                            textClass = 'text-danger';
                                        } else if (keterangan === 'L') {
                                            rowClass = 'bg-blue-lt fw-bold';
                                            textClass = 'text-primary';
                                        }

                                        htmlLab += `<tr class="${rowClass}">
                                            <td>${det.template ? det.template.Pemeriksaan : '-'}</td>
                                            <td class="text-center ${textClass}">${det.nilai}</td>
                                            <td class="text-center">${det.template ? det.template.satuan : '-'}</td>
                                            <td class="text-center">${det.nilai_rujukan}</td>
                                        </tr>`;
                                    });
                                    htmlLab += `</tbody></table>`;
                                }
                                htmlLab += `</div>`;
                            });
                            htmlLab += '</div></div>';
                        }

                        body.append([keterangan, ttv, pemeriksaan, htmlInfoMasuk, htmlLab]).hide().fadeIn()
                    })
                    // }
                })
            }
        });

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
                    const nmPoli = regPeriksa.poliklinik ? regPeriksa.poliklinik.nm_poli : '-';
                    return `<div class="accordion-item">
                                <h2 class="accordion-header" id="heading-${index}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="true" aria-controls="collapse-${index}">
                                        ${formatTanggal(regPeriksa.tgl_registrasi)} [${nmPoli}] : ${diagnosa}
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
    </script>
@endpush

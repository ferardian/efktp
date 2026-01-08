// AJAX
function getRegPeriksa(startDate = '', endDate = '') {
    return $.get('registrasi/get', {
        startDate: startDate,
        endDate: endDate
    })
}


// AJAX
function formatTanggal(tanggal) {
    tgl = tanggal.split(' ')[0]
    let t = new Date(tgl);
    let bulan = '';
    switch ((t.getMonth() + 1)) {
        case 1:
            bulan = "Januari";
            break;
        case 2:
            bulan = "Februari";
            break;
        case 3:
            bulan = "Maret";
            break;
        case 4:
            bulan = "April";
            break;
        case 5:
            bulan = "Mei";
            break;
        case 6:
            bulan = "Juni";
            break;
        case 7:
            bulan = "Juli";
            break;
        case 8:
            bulan = "Agustus";
            break;
        case 9:
            bulan = "September";
            break;
        case 10:
            bulan = "Oktober";
            break;
        case 11:
            bulan = "November";
            break;
        case 12:
            bulan = "Desember";
            break;
        default:
            break;
    }
    return `${t.getDate()} ${bulan} ${t.getFullYear()}${tanggal.split(' ')[1] ? tanggal.split(' ')[1] : ''}`;
}

function formatBulan(intBulan) {
    switch (intBulan) {
        case 1:
            bulan = "Januari";
            break;
        case 2:
            bulan = "Februari";
            break;
        case 3:
            bulan = "Maret";
            break;
        case 4:
            bulan = "April";
            break;
        case 5:
            bulan = "Mei";
            break;
        case 6:
            bulan = "Juni";
            break;
        case 7:
            bulan = "Juli";
            break;
        case 8:
            bulan = "Agustus";
            break;
        case 9:
            bulan = "September";
            break;
        case 10:
            bulan = "Oktober";
            break;
        case 11:
            bulan = "November";
            break;
        case 12:
            bulan = "Desember";
            break;
        default:
            break;
    }
    return bulan;
}

// HITUNG UMUR
function hitungUmur(tgl_lahir) {
    sekarang = new Date();
    hari = new Date(sekarang.getFullYear(), sekarang.getMonth(), sekarang.getDate());

    var tahunSekarang = sekarang.getFullYear();
    var bulanSekarang = sekarang.getMonth();
    var tanggalSekarang = sekarang.getDate();

    splitTgl = tgl_lahir.split('-');
    lahir = new Date(splitTgl[0], splitTgl[1] - 1, splitTgl[2]);


    tahunLahir = lahir.getFullYear();
    bulanLahir = lahir.getMonth();
    tanggalLahir = lahir.getDate();

    umurTahun = tahunSekarang - tahunLahir;
    if (bulanSekarang >= bulanLahir) {
        umurBulan = bulanSekarang - bulanLahir;
    } else {
        umurTahun--;
        umurBulan = 12 + bulanSekarang - bulanLahir;
    }

    if (tanggalSekarang >= tanggalLahir) {
        umurTanggal = tanggalSekarang - tanggalLahir;
    } else {
        umurBulan--;
        if (bulanSekarang == '1') {
            if (bulanSekarang % 4 == 0) {
                jmlHari = 29;
            } else {
                jmlHari = 28;
            }
        } else if (bulanSekarang == '0' && bulanSekarang == '2' && bulanSekarang == '4' && bulanSekarang == '6' &&
            bulanSekarang == '8' && bulanSekarang == '9') {
            jmlHari = 31;
        } else {
            jmlHari = 30;
        }
        umurTanggal = jmlHari + tanggalSekarang - tanggalLahir;
    }

    return umurTahun + ';' + umurBulan + ';' + umurTanggal;
}

function hitungUmurDaftar(tgl_lahir, tgl_registrasi) {
    sekarang = new Date(tgl_registrasi);
    hari = new Date(sekarang.getFullYear(), sekarang.getMonth(), sekarang.getDate());

    var tahunSekarang = sekarang.getFullYear();
    var bulanSekarang = sekarang.getMonth();
    var tanggalSekarang = sekarang.getDate();

    splitTgl = tgl_lahir.split('-');
    lahir = new Date(splitTgl[0], splitTgl[1] - 1, splitTgl[2]);


    tahunLahir = lahir.getFullYear();
    bulanLahir = lahir.getMonth();
    tanggalLahir = lahir.getDate();

    umurTahun = tahunSekarang - tahunLahir;
    if (bulanSekarang >= bulanLahir) {
        umurBulan = bulanSekarang - bulanLahir;
    } else {
        umurTahun--;
        umurBulan = 12 + bulanSekarang - bulanLahir;
    }

    if (tanggalSekarang >= tanggalLahir) {
        umurTanggal = tanggalSekarang - tanggalLahir;
    } else {
        umurBulan--;
        if (bulanSekarang == '1') {
            if (bulanSekarang % 4 == 0) {
                jmlHari = 29;
            } else {
                jmlHari = 28;
            }
        } else if (bulanSekarang == '0' && bulanSekarang == '2' && bulanSekarang == '4' && bulanSekarang == '6' &&
            bulanSekarang == '8' && bulanSekarang == '9') {
            jmlHari = 31;
        } else {
            jmlHari = 30;
        }
        umurTanggal = jmlHari + tanggalSekarang - tanggalLahir;
    }

    return umurTahun + ';' + umurBulan + ';' + umurTanggal;
}

function setHari(hari) {
    let d = '';
    switch (hari) {
        case 0:
            d = 'Minggu';
            break;
        case 1:
            d = 'Senin';
            break;
        case 2:
            d = 'Selasa';
            break;
        case 3:
            d = 'Rabu';
            break;
        case 4:
            d = 'Kamis';
            break;
        case 5:
            d = 'Jumat';
            break;
        case 6:
            d = 'Sabtu';
            break;
        default:
            d = 'Minggu';
            break;
    }
    return d;
}

function getDataForm(form, element, except = []) {
    let data = {};

    // cek apakah element array atau text
    const isArray = Array.isArray(element);

    // form data
    if (isArray) {
        // ambil dari element
        for (let index = 0; index < element.length; index++) {
            const e = element[index];
            $(`#${form} ${e}`).each((index, el) => {
                keys = $(el).prop('name');
                data[keys] = $(el).val();
            })
        }

    } else {
        // ambil dari satu jenis element
        $(`#${form} ${element}`).each((index, el) => {
            keys = $(el).prop('name');
            data[keys] = $(el).val();
        })
    }


    // remove items on array data
    for (let i = 0; i < except.length; i++) {
        cek = data.hasOwnProperty(except[i])
        if (cek) {
            delete data[except[i]]
        }
    }

    return data;
}

function stringPemeriksaan(value) {
    if (value) {
        const arrValue = value.split('\n');
        let string = '';
        for (let index = 0; index < arrValue.length; index++) {
            string += `${arrValue[index]}<br/>`;
        }
        return string

    }
    return '';
}

function removeZero(input) {
    if (input.value == '-' || input.value == '0') {
        $(input).val('');
    }
}

function isEmpty(input) {
    if (input.value == '') {
        $(input).val('-');
    }
}

function isEmptyNumber(input) {
    if (input.value == '' || input.value == '-' || input.value == '0') {
        $(input).val('0');
    }
    return false;
}

function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function splitTanggal(tanggal) {
    let arrTgl = tanggal.split('-');
    let txtTanggal = arrTgl[2] + '-' + arrTgl[1] + '-' + arrTgl[0];
    return txtTanggal;
}

function hitungBmi(bb, tb) {
    if (bb.length & tb.length) {
        const tbMeter = parseFloat(tb) / 100;
        const bmi = parseFloat(bb) / parseFloat(tbMeter * tbMeter)
        return bmi.toFixed(2);
    }
    return '0';
}

function setTextPenjab(penjab, badge = true) {
    if (badge) {
        return penjab.includes('BPJS') ? `<span class='badge badge-pill text-bg-green'>BPJS</span>` :
            `<span class='badge badge-pill text-bg-orange'>${penjab}</span>`;
    }
    return penjab.includes('BPJS') ? 'BPJS' : penjab;

}


// ALERT
function alertSuccessAjax(message) {
    return Swal.fire({
        title: 'Berhasil',
        html: message,
        showConfirmButton: false,
        icon: 'success',
        timer: 1200,
    })

}

function alertErrorAjax(request) {
    const errorMsg = Array.isArray(request.responseJSON) ? request.responseJSON[2] : request.responseJSON;
    Swal.fire(
        'Gagal',
        'Terjadi kesalahan<br>  Error Code : ' + request.status + ', ' + request.statusText + '<br/> <p class="text-danger" p-0>' + errorMsg + '</span>',
        'error'
    );
}

function alertErrorBpjs({ metaData, response }) {
    const message = metaData.message;
    const errors = Array.isArray(response) ? response.map(
        ({ field, message }) => `${field} : ${message}`
    ).join('<br>') : response ?? '';
    return Swal.fire({
        title: 'Pesan dari BPJS',
        html: `<small>${metaData.code} ${message}</small> <br/><strong class="text-danger">${errors}</strong>`,
        icon: 'error',
    });
}

function alertSessionExpired(requestStatus) {
    if (requestStatus == 401) {
        Swal.fire({
            title: 'Sesi login berakhir !',
            icon: 'info',
            text: 'Silahkan login kembali ',
            showConfirmButton: true,
            confirmButtonText: 'OK',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/efktp';
            }
        })
    }
}

function loadingAjax(html = '') {
    const loading = Swal.fire({
        title: "Mohon tunggu!",
        html: html.length ? html : "Sedang mengambil data",
        timerProgressBar: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },

    });

    return loading;
}

function alertError(msg = '') {
    const message = msg ? msg : 'Terjadi Kesalahan';
    Swal.fire(
        'Gagal',
        `${message}`,
        'error'
    );
}

function toast(message = '', type = '') {
    const textMessage = message ? message : 'Berhasil'
    const typeIcon = type ? type : 'success'
    Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        icon: typeIcon,
        title: `${textMessage}`,
    });

}

function dateDiff(date1, date2) {
    const parseDate1 = new Date(date1)
    const parseDate2 = new Date(date2)
    const diff = parseDate1.getTime() - parseDate2.getTime();
    const dateDiffs = diff / (1000 * 60 * 60 * 24)
    return dateDiffs.toFixed(1);
}

function getSukuBangsa(suku) {
    return bangsa = $.get(`/efktp/suku`, {
        suku: suku,
    });
}

function selectSukuBangsa(element, parrent, initVal = '-') {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        tags: true,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/suku`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    suku: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.id,
                            text: `${item.nama_suku_bangsa}`,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    getSukuBangsa(initVal).done((response) => {
        response.map((sk) => {
            const option = new Option(sk.nama_suku_bangsa, sk.id, true, true);
            element.append(option).trigger('change');
        })
    })
    return select2;
}

function selectAlergi(element, parent) {
    const select2 = element.select2({
        dropdownParent: parent,
        delay: 2,
        tags: true,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/pasien/alergi`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    alergi: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.alergi,
                            text: item.alergi,
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });

    return select2;
}

function getBahasa(bahasa) {
    return bahasa = $.get(`/efktp/bahasa`, {
        bahasa: bahasa,
    });
}

const isObjectEmpty = (objectName) => {
    return (
        objectName.length === 0 &&
        objectName &&
        Object.keys(objectName).length === 0 &&
        objectName.constructor === Object
    );
}


function selectBahasaPasien(element, parrent, initVal = '-') {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        tags: true,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/bahasa`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    bahasa: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.id,
                            text: `${item.nama_bahasa}`,
                            detail: item
                        }
                        return items;
                    })
                }
            },
        },
        cache: true,
    });

    getBahasa(initVal).done((response) => {
        response.map((bhs) => {
            const option = new Option(bhs.nama_bahasa, bhs.id, true, true);
            element.append(option).trigger('change');
        })
    })
    return select2;
}

function getCacatFisik(cacat) {
    return bahasa = $.get(`/efktp/cacat`, {
        cacat: cacat,
    });
}


function selectCacatFisik(element, parrent, initVal = '-') {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        tags: true,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/cacat`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    cacat: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.id,
                            text: item.nama_cacat,
                            detail: item
                        }
                        return items;
                    })
                }
            },
        },
        cache: true,
    });

    getCacatFisik(initVal).done((response) => {
        response.map((cct) => {
            const option = new Option(cct.nama_cacat, cct.id, true, true);
            element.append(option).trigger('change');
        })
    })
    return select2;
}

function selectPenjab(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        tags: false,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/penjab`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    penjab: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_pj,
                            text: `${item.kd_pj} - ${item.png_jawab}`,
                            detail: item
                        }
                        return items;
                    })
                }
            },
        },
        cache: true,
    }).on('select2:unselecting', (e) => {
        const option = new Option('-', '-', true, true);
        $(e.currentTarget).append(option).trigger('change');
    });
    return select2;
}

function selectDataBarang(element, parrent) {
    return element.select2({
        dropdownParent: parrent,
        delay: 250, // biar lebih optimal
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/barang/get`,
            dataType: 'JSON',
            data: (params) => ({
                barang: params.term
            }),
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        return {
                            id: item.kode_brng,
                            text: item.nama_brng, // tetap untuk value utama
                            detail: item // semua detail barang masuk
                        }
                    })
                }
            }
        },
        templateResult: (data) => {
            if (data.loading) {
                return data.text;
            }
            let stokAP = '';
            if (data.detail && Array.isArray(data.detail.gudang_barang)) {
                const ap = data.detail.gudang_barang.find(g => g.kd_bangsal === 'AP');
                stokAP = ap ? ap.stok : 0;
            }

            return $(`
            <div class="select2-result-repository clearfix">
                <div class="select2-result-repository__meta">
                    <div class="select2-result-repository__title fw-bold">${data.text}</div>
                    <div class="select2-result-repository__description text-muted">
                        <small>Stok : ${stokAP} ${data.detail.satuan.satuan}</small>
                    </div>
                </div>
            </div>
        `);

        },
        templateSelection: (data) => {
            if (!data.id) return data.text;

            const ap = data.detail?.gudang_barang?.find(g => g.kd_bangsal === 'AP');
            const stokAP = ap ? ap.stok : 0;

            return `${data.text} (${stokAP})`;
        },
    })
}

function selectDokter(element, parrent) {
    return element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        allowClear: true,
        tags: true,
        placeholder: 'Pilin dokter',
        ajax: {
            url: `/efktp/dokter/get`,
            dataType: 'JSON',

            data: (params) => {
                return {
                    dokter: params.term
                }
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        return {
                            id: item.kd_dokter,
                            text: item.nm_dokter,
                            detail: item
                        }

                    })
                }
            }

        },
        cache: true

    }).on('select2:unselecting', (e) => {
        const option = new Option('', '', true, true);
        $(e.currentTarget).append(option).trigger('change');
    });

}

function selectMappingDokterPcare(element, parrent) {
    return element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        allowClear: true,
        tags: false,
        placeholder: 'Pilin dokter',
        ajax: {
            url: `efktp /mapping/pcare / dokter`,
            dataType: 'JSON',

            data: (params) => {
                return {
                    dokter: params.term
                }
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        return {
                            id: item.kd_dokter_pcare,
                            text: item.nm_dokter,
                            detail: item
                        }

                    })
                }
            }

        },
        cache: true

    }).on('select2:unselecting', (e) => {
        const option = new Option('-', '-', true, true);
        $(e.currentTarget).append(option).trigger('change');
    });

}

function selectKelurahan(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        tags: true,
        ajax: {
            url: `/efktp/kelurahan`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    kelurahan: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_kel,
                            text: item.nm_kel,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    const option = new Option('-', 1, true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectKecamatan(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        tags: true,
        ajax: {
            url: `/efktp/kecamatan`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    kecamatan: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_kec,
                            text: item.nm_kec,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    const option = new Option('-', 1, true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectKabupaten(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        tags: true,
        ajax: {
            url: `/efktp/kabupaten`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    kabupaten: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_kab,
                            text: item.nm_kab,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    const option = new Option('-', 1, true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectPropinsi(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        tags: true,
        ajax: {
            url: `/efktp/propinsi`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    propinsi: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_prop,
                            text: item.nm_prop,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    const option = new Option('-', 1, true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectPerusahaan(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        tags: true,
        ajax: {
            url: `/efktp/perusahaan`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    perusahaan: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kode_perusahaan,
                            text: item.nama_perusahaan,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    const option = new Option('-', '-', true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectPoliklinik(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        width: '100%',
        delay: 2,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/poliklinik`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    poli: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_poli,
                            text: `${item.kd_poli} - ${item.nm_poli}`,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    return select2;
}

function selectPegawai(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/pegawai`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    pegawai: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.nik,
                            text: item.nama,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: true

    });
    const option = new Option('-', '-', true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectPenyakit(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/penyakit/get`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    penyakit: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_penyakit,
                            text: `${item.kd_penyakit} - ${item.nm_penyakit}`,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: false,

    });
    const option = new Option('-', '-', true, true);
    element.append(option).trigger('change');
    return select2;
}

function selectTindakan(element, parrent) {
    const select2 = element.select2({
        dropdownParent: parrent,
        delay: 2,
        scrollAfterSelect: true,
        ajax: {
            url: `/efktp/tindakan/get`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    kode: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kode,
                            text: `${item.kode} - ${item.deskripsi_pendek}`,
                            detail: item
                        }
                        return items;
                    })
                }
            }

        },
        cache: false

    });

    const option = new Option('-', '-', true, true);
    element.append(option).trigger('change');
    return select2;

}

function selectJabatan(element, parent) {

}

// CONTEXT MENU
$.contextMenu({
    selector: '.rows-registrasi',
    events: {
        hide: (element, event) => {
            $(element.selector).removeClass('text-red')
        }
    },
    build: (element, event) => {
        const no_rawat = element.data('id');
        const poli = element.data('poli');
        const no_rkm_medis = element.data('no_rkm_medis');
        const noPeserta = element.data('nopeserta');
        const dokterPcare = element.data('dokter_pcare');
        const penjab = element.data('penjab');
        element.addClass('text-red')
        return {
            items: {
                "cppt": {
                    name: "CPPT",
                    icon: "fas fa-stethoscope",
                    callback: function (item, option, e, x, y) {
                        showCpptRalan(`${no_rawat}`)
                    }

                },
                "upload": {
                    name: "Upload Penunjang",
                    icon: "fas fa-arrow-up-from-bracket",
                    callback: function (item, option, e, x, y) {
                        modalUploadPenunjang(`${no_rawat}`)
                    }

                },
                "buktiRegister": {
                    name: "Bukti Register",
                    icon: "fas fa-file",
                    callback: function (item, option, e, x, y) {
                        buktiRegister(`${no_rawat}`)
                    }

                },
                "Rujuk": {
                    name: "Rujuk",
                    items: {
                        "RujukInternal": {
                            name: "Rujuk Internal",
                            icon: "fas fa-tag",
                            callback: (item) => {
                                rujukInternal(`${no_rawat}`);
                            },

                        },

                        "rujukKeluar": {
                            name: "Rujuk Keluar",
                            icon: "fas fa-tag",
                            callback: (item) => {
                                rujukEksternal(`${no_rawat}`);
                            }
                        },
                    }
                },
                "PeriksaGigi": {
                    name: "Pemeriksaan Gigi",
                    icon: "fas fa-tooth",
                    callback: (item, opt) => {
                        pemeriksaanGigi(`${no_rawat}`);
                    }
                },
                "Tindakan & Pemeriksaan": {
                    name: "Tindakan & Pemeriksaan",
                    items: {
                        "PemeriksaanLab": {
                            name: "Laboratorium",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                showPeriksaLab(`${no_rawat}`)
                            }
                        },
                        "PemeriksaanRadiologi": {
                            name: "Radiologi",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                // permintaanRadiologi(`${no_rawat}`);
                            }
                        },
                    }
                },
                "Surat": {
                    name: 'Surat',
                    items: {
                        "SuratSehat": {
                            name: "Ketetrangan Sehat",
                            icon: "fas fa-tag",
                            callback: (item) => {
                                suratSehat(`${no_rawat}`);
                            }
                        },
                        "SuratSakit": {
                            name: "Keterangan Sakit",
                            icon: "fas fa-tag",
                            callback: (item) => {
                                suratSakit(`${no_rawat}`);
                            }
                        },

                    }
                },
                // "Pcare": {
                //     name: 'Pcare',
                //     items: {
                //         // "resepPcare": {
                //         //     name: "Resep Obat Pcare",
                //         //     icon: "fas fa-pills",
                //         //     disabled: () => {
                //         //         // return true;
                //         //     },
                //         //     callback: (item, opt) => {
                //         //         obatPcare(`${no_rawat}`);
                //         //     }
                //         // },
                //         "kunjungan": {
                //             name: "Kunjungan Pcare",
                //             icon: "fas fa-list",
                //             disabled: () => {
                //                 // return true;
                //             },
                //             callback: (item, opt) => {
                //                 penilaianAwalKeperawatan(`${no_rawat}`);
                //             }
                //         },
                //     },
                // },
                "Permintaan": {
                    name: "Permintaan",
                    items: {
                        "PemeriksaanLab": {
                            name: "Laboratorium",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                permintaanLab(`${no_rawat}`);
                            }
                        },
                        "PemeriksaanRadiologi": {
                            name: "Radiologi",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                // permintaanRadiologi(`${no_rawat}`);
                            }
                        },
                    }
                },
                "PenilaianAwal": {
                    name: "Penilaian Awal",
                    items: {
                        "Keperawatan": {
                            name: "Keperawatan Umum",
                            icon: "fas fa-tag",
                            disabled: () => {
                                // return true;
                            },
                            callback: (item, opt) => {
                                penilaianAwalKeperawatan(`${no_rawat}`);
                            }
                        },
                        "SkriningResikoJatuh": {
                            name: "Skrining Resiko Jatuh",
                            icon: "fas fa-tag",

                            callback: (item, opt) => {
                                skriningResikoJatuh(`${no_rawat}`);
                            }
                        },
                    }
                },
                "setStatus": {
                    name: "Set Status",
                    items: {
                        "Sudah": {
                            name: 'Sudah',
                            icon: "fas fa-tag",
                            callback: () => {
                                setStatusLayan(no_rawat, 'Sudah')
                            }
                        },
                        "Belum": {
                            name: 'Belum',
                            icon: "fas fa-tag",
                            callback: () => {
                                setStatusLayan(no_rawat, 'Belum')
                            }
                        },
                        "Batal": {
                            name: 'Batal',
                            icon: "fas fa-tag",
                            callback: () => {
                                setStatusLayan(no_rawat, 'Batal')
                            }
                        },
                        "Dirujuk": {
                            name: 'Dirujuk',
                            icon: "fas fa-tag",
                            callback: () => {
                                setStatusLayan(no_rawat, 'Dirujuk')
                            }
                        },
                    }
                },
                "riwayat": {
                    name: "Riwayat Kunjungan",
                    icon: 'fa-regular fa-folder-open',
                    callback: function (item, option, e, x, y) {
                        riwayat(`${no_rkm_medis}`)
                    }

                },
                "icare": {
                    name: "Riwayat ICare",
                    icon: 'fa-regular fa-info',
                    disabled: () => {
                        return penjab == 'BPJS' ? false : true;
                    },
                    callback: function (item, option, e, x, y) {
                        riwayatIcare(`${noPeserta}`)
                    }

                },
                "ubah": {
                    name: 'Ubah Data',
                    icon: "fas fa-pencil",
                    callback: () => {
                        ubahRegistrasi(no_rawat)
                    }
                },

                // "hapusRegistrasi": {
                //     name: "Hapus & Ubah Data",
                //     items : {
                //         "ubah" : {
                //             name : 'Ubah',
                //             icon : "fas fa-pencil",
                //             callback : ()=>{
                //                 ubahRegistrasi(no_rawat)
                //             }
                //         },
                //         "hapus" : {
                //             name : 'Hapus',
                //             icon : "fas fa-trash",
                //             callback : ()=>{
                //                 hapusRegistrasi(no_rawat)
                //             }
                //         },
                //     }
                // },
            }
        }
    }
});
$.contextMenu({
    events: {
        hide: (element, event) => {
            $(element.selector).removeClass('text-red')
        }
    },

    selector: '.rows-rujuk',
    build: (element, event) => {
        const no_rawat = element.data('id');
        element.addClass('text-red')
        return {
            items: {
                "cppt": {
                    name: "CPPT",
                    icon: "",
                    callback: function (item, option, e, x, y) {
                        modalCpptRujuk(`${no_rawat}`)
                    }

                },
            }
        }
    }
});

$.contextMenu({
    events: {
        hide: (element, event) => {
            $(element.selector).removeClass('text-red')
        }
    },
    selector: '.rowPendaftaranPcare',
    build: (element, event) => {
        element.addClass('text-red')
        const no_rawat = element.data('id');
        const no_peserta = element.data('peserta');
        return {
            items: {
                "cppt": {
                    name: "CPPT",
                    icon: 'fa-regular fa-stethoscope',
                    callback: function (item, option, e, x, y) {
                        showCpptRalan(`${no_rawat}`)
                    }

                },
                "icare": {
                    name: "Riwayat ICare",
                    icon: 'fa-regular fa-info',
                    callback: function (item, option, e, x, y) {
                        riwayatIcare(`${no_peserta}`)
                    }

                },
            }
        }
    }
});
$.contextMenu({
    selector: '.rows-pasien',
    build: (element, event) => {
        element.addClass('text-red')
        const no_rkm_medis = element.data('id');
        const poli = element.data('poli');
        const peserta = element.data('peserta');
        return {
            items: {
                "registrasi": {
                    name: "Registrasi",
                    icon: 'fa-regular fa-address-card',
                    callback: function (item, option, e, x, y) {
                        registrasiPoli(`${no_rkm_medis}`)
                    }

                },
                "edit": {
                    name: "Edit",
                    icon: 'fas fa-edit',
                    callback: function (item, option, e, x, y) {
                        editPasien(`${no_rkm_medis}`)
                    }

                },
                "riwayat": {
                    name: "Riwayat Kunjungan",
                    icon: 'fa-regular fa-folder-open',
                    callback: function (item, option, e, x, y) {
                        riwayat(`${no_rkm_medis}`)
                    }

                },
                "cekBpjs": {
                    name: "Cek Peserta BPJS",
                    icon: 'fas fa-circle-question',
                    disabled: () => {
                        return poli == 'BPJS' ? false : true;
                    },

                    callback: function (item, option, e, x, y) {
                        getPeserta(`${peserta}`)
                    }
                }
            }
        }
    }
});

$.contextMenu({
    selector: '.tableKamarInap',
    events: {
        hide: (element, event) => {
            $(element.selector).removeClass('text-red')
        }
    },
    build: (element, event) => {
        const no_rawat = element.data('id');
        const no_rkm_medis = element.data('no_rkm_medis');
        element.addClass('text-red')
        return {
            items: {
                "pemeriksaan": {
                    name: "CPPT",
                    icon: 'fa-regular fa-stethoscope',
                    callback: function (item, option, e, x, y) {
                        cpptRanap(`${no_rawat}`)
                    }

                },
                "Tindakan & Pemeriksaan": {
                    name: "Tindakan & Pemeriksaan",
                    items: {
                        "PemeriksaanLab": {
                            name: "Laboratorium",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                showPeriksaLab(`${no_rawat}`)
                            }
                        },
                        "PemeriksaanRadiologi": {
                            name: "Radiologi",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                // permintaanRadiologi(`${no_rawat}`);
                            }
                        },
                    }
                },
                "Obat": {
                    name: "Obat",
                    // icon: 'fa-regular fa-pills',
                    // callback: function (item, option, e, x, y) {
                    //     cpptRanap(`${no_rawat}`)
                    // }
                    items: {
                        "Resep Obat": {
                            name: "Resep Obat",
                            icon: 'fa-regular fa-tablets',
                            callback: function (item, option, e, x, y) {
                                // cpptRanap(`${no_rawat}`)
                            }

                        },
                        "Pemberian Obat": {
                            name: "Pemberian Obat",
                            icon: 'fa-regular fa-tablets',
                            callback: function (item, option, e, x, y) {
                                // cpptRanap(`${no_rawat}`)
                            }

                        },


                    }

                },
                "penilaianAwal": {
                    name: "Penilaian Awal",
                    items: {
                        "Keperawatan Umum": {
                            name: "Keperawatan Umum",
                            icon: 'fa-regular fa-pencil',
                            callback: function (item, option, e, x, y) {
                                // cpptRanap(`${no_rawat}`)
                            }

                        },
                        "Medis Umum": {
                            name: "Medis Umum",
                            icon: 'fa-regular fa-pencil',
                            callback: function (item, option, e, x, y) {
                                // cpptRanap(`${no_rawat}`)
                            }

                        },
                        "Triase": {
                            name: "Triase",
                            icon: 'fa-regular fa-pencil',
                            callback: function (item, option, e, x, y) {
                                // cpptRanap(`${no_rawat}`)
                            }

                        },
                    },

                },
                "Permintaan": {
                    name: "Permintaan",
                    items: {
                        "PemeriksaanLab": {
                            name: "Laboratorium",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                permintaanLab(`${no_rawat}`);
                            }
                        },
                        "PemeriksaanRadiologi": {
                            name: "Radiologi",
                            icon: "fas fa-tag",
                            callback: (item, opt) => {
                                // permintaanRadiologi(`${no_rawat}`);
                            }
                        },
                    }
                },
                "Resume Medis": {
                    name: "Resume Medis",
                    icon: 'fa-regular fa-file',
                    callback: function (item, option, e, x, y) {
                        resumeMedis(`${no_rawat}`)
                    }

                },
                "Riwayat Kunjungan": {
                    name: "Riwayat Kunjungan",
                    icon: 'fa-regular fa-folder-open',
                    callback: function (item, option, e, x, y) {
                        riwayat(`${no_rkm_medis}`)
                    }

                },
            }
        }
    }
});

function hitungHPL(tanggalHPHT) {
    let date = new Date(tanggalHPHT);
    let hplDate = new Date(date);
    hplDate.setDate(hplDate.getDate() + 7);
    hplDate.setMonth(hplDate.getMonth() - 3);

    // Jika hasilnya mundur (lebih kecil dari HPHT), tambah 1 tahun
    if (hplDate < date) {
        hplDate.setFullYear(hplDate.getFullYear() + 1);
    }

    const year = hplDate.getFullYear();
    const month = String(hplDate.getMonth() + 1).padStart(2, '0');
    const day = String(hplDate.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}


function hitungUsiaKehamilan(hpht) {
    const start = new Date(hpht);
    const today = new Date();

    const diffTime = today - start;

    if (diffTime < 0) {
        return "0w0d"; // kalau HPHT di masa depan
    }

    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    const weeks = Math.floor(diffDays / 7);
    const days = diffDays % 7;

    return `${weeks}w${days}d`;
}

function selectMetode(element, parrent) {
    element.select2({
        dropdownParent: parrent,
        delay: 1,
        tags: true,
        ajax: {
            url: `/efktp/metode/racik/get`,
            dataType: 'JSON',

            data: (params) => {
                const query = {
                    racik: params.term
                }
                return query
            },
            processResults: (data) => {
                return {
                    results: data.map((item) => {
                        const items = {
                            id: item.kd_racik,
                            text: item.nm_racik,
                        }
                        return items;
                    })
                }
            }

        },
        cache: true
    }).on('select2:select', (e) => {
        e.preventDefault();
    })
    element.val('R01').trigger('change')
}

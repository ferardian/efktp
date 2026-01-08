<div class="modal modal-blur fade" id="modalSuratSehat" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Surat Sehat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <fieldset class="form-fieldset p-1">
                    @include('content.registrasi.suratSehat._formSuratSehat')
                </fieldset>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="table-responsive mt-2">
                            <table class="table table-sm nowrap table-striped" id="tbSuratSehat" width="100%"></table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form" id="formFilterSehat">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="input-group">
                                        <input class="form-control filterTangal" name="tgl_pertama" id="tgl_pertama" value="{{ date('d-m-Y') }}" />
                                        <span class="input-group-text">
                                            <label for="">s.d</label>
                                        </span>
                                        <input class="form-control filterTangal" name="tgl_kedua" id="tgl_kedua" value="{{ date('d-m-Y') }}" />
                                        <button class="btn btn-secondary" id="btnFilterSuratSehat"><i class="ti ti-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
                <button type="button" class="btn btn-success" id="btnSimpanSuratSehat"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modalCetakSuratSehat" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Cetak :: Surat Sehat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="print" type="" width="100%" height="500"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        let modalSuratSehat = $('#modalSuratSehat');
        let modalCetakSuratSehat = $('#modalCetakSuratSehat');
        let formSuratSehat = $('#formSuratSehat ');
        let tglSuratSehat1 = localStorage.getItem('tglSuratSehat1') ? localStorage.getItem('tglSuratSehat1') : tanggal;
        let tglSuratSehat2 = localStorage.getItem('tglSuratSehat2') ? localStorage.getItem('tglSuratSehat2') : tanggal;

        modalSuratSehat.on('hidden.bs.modal', () => {
            formSuratSehat.trigger('reset');
        })
        modalCetakSuratSehat.on('hidden.bs.modal', () => {
            modalCetakSuratSehat.find('#print').attr('src', '');
        })

        function loadSuratSehat(tglAwal = '', tglAkhir = '') {
            const tabelRegistrasi = new DataTable('#tbSuratSehat', {
                responsive: true,
                stateSave: true,
                serverSide: false,
                destroy: true,
                processing: true,
                scrollY: '13vh',
                scrollX: true,
                ajax: {
                    url: `/efktp/surat/sehat`,
                    data: {
                        dataTable: true,
                        tgl_pertama: tglAwal,
                        tgl_kedua: tglAkhir,
                    },
                },
                columns: [{
                        title: 'No. Surat',
                        data: 'no_surat',
                        render: (data, type, row, meta) => {
                            return `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="setSuratSehat('${data}')">${data}</button>`
                        },
                    },
                    {
                        title: 'Nama',
                        data: 'reg_periksa.pasien.nm_pasien',
                        render: (data, type, row, meta) => {
                            return `${data} (${row.reg_periksa.umurdaftar} ${row.reg_periksa.sttsumur})`
                        },
                    },
                    {
                        title: 'Tanggal',
                        data: 'tanggalsurat',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'BB',
                        data: 'berat',
                        render: (data, type, row, meta) => {
                            return `${data} Kg`
                        },
                    },
                    {
                        title: 'TB',
                        data: 'tinggi',
                        render: (data, type, row, meta) => {
                            return `${data} cm`
                        },
                    },
                    {
                        title: 'TENSI',
                        data: 'tensi',
                        render: (data, type, row, meta) => {
                            return `${data} mmHg`
                        },
                    },
                    {
                        title: 'Buta Warna',
                        data: 'butawarna',
                        render: (data, type, row, meta) => {
                            return data
                        },
                    },
                    {
                        title: 'Kesimpulan',
                        data: 'kesimpulan',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: 'Keperluan',
                        data: 'keperluan',
                        render: (data, type, row, meta) => {
                            return data;
                        },
                    },
                    {
                        title: '',
                        data: 'no_surat',
                        render: (data, type, row, meta) => {
                            return `<button type="button" class="btn btn-sm btn-danger" onclick="deleteSuratSehat('${data}')"><i class="ti ti-trash"></i></button>
                        <button type="button" class="btn btn-sm btn-success" onclick="cetakSuratSehat('${data}')"><i class="ti ti-printer"></i></button>
                        `;
                        },
                    },

                ]
            })
        }

        function suratSehat(no_rawat) {
            getRegDetail(no_rawat).done((response) => {
                setNoSuratSehat().done((no) => {
                    formSuratSehat.find('#no_surat').val(no)
                });
                formSuratSehat.find('#no_rawat').val(no_rawat)
                formSuratSehat.find('#pasien').val(`${response.pasien.nm_pasien} (${response.pasien.jk})`)
                formSuratSehat.find('#tgl_lahir').val(`${splitTanggal(response.pasien.tgl_lahir)} (${response.umurdaftar} ${response.sttsumur})`)
                formSuratSehat.find('#alamat').val(`${response.pasien.alamat}, ${response.pasien.kel.nm_kel}, ${response.pasien.kec.nm_kec}`)
            });
            getPemeriksaanRalan(no_rawat).done((response) => {
                if (response) {
                    response.map((item, index) => {
                        formSuratSehat.find('#berat').val(item.berat)
                        formSuratSehat.find('#tinggi').val(item.tinggi)
                        formSuratSehat.find('#suhu').val(item.suhu_tubuh)
                        formSuratSehat.find('#tensi').val(item.tensi)
                    })
                }
            })
            modalSuratSehat.modal('show');

            loadSuratSehat(tglAwal, tglAkhir);
        }

        function cetakSuratSehat(no_surat) {
            Swal.fire({
                title: "Tunggu",
                html: "Sedang mengambil data...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            modalCetakSuratSehat.find('#print').on('load', (e) => {
                if (e.currentTarget.src) {
                    toast('Berhasil');
                }

            })
            modalCetakSuratSehat.modal('show');
            modalCetakSuratSehat.find('#print').removeAttr('src').attr('src', `/efktp/surat/sehat/print/${no_surat}`)
        }

        function setNoSuratSehat(tgl_surat = '') {
            const tanggal = $.get(`/efktp/surat/sehat/setnomor`, {
                tgl_surat: tgl_surat,
            })

            return tanggal;
        }

        function setSuratSehat(no_surat) {
            $.get(`/efktp/surat/sehat/${no_surat}`).done((response) => {
                const regPeriksa = response.reg_periksa;
                const pasien = regPeriksa.pasien;

                formSuratSehat.find('#no_rawat').val(regPeriksa.no_rawat)
                formSuratSehat.find('#no_surat').val(no_surat)
                formSuratSehat.find('#pasien').val(`${pasien.nm_pasien} (${pasien.jk})`)
                formSuratSehat.find('#tgl_lahir').val(`${splitTanggal(pasien.tgl_lahir)} (${regPeriksa.umurdaftar} ${regPeriksa.sttsumur})`)
                formSuratSehat.find('#alamat').val(`${pasien.alamat}, ${pasien.kel.nm_kel}, ${pasien.kec.nm_kec}`)
                formSuratSehat.find('#berat').val(response.berat);
                formSuratSehat.find('#tinggi').val(response.tinggi);
                formSuratSehat.find('#tensi').val(response.tensi);
                formSuratSehat.find('#suhu').val(response.suhu);
                formSuratSehat.find('#butawarna').val(response.butawarna).change();
                formSuratSehat.find('#keperluan').val(response.keperluan);
                formSuratSehat.find('#kesimpulan').val(response.kesimpulan).change();
            })
        }

        formSuratSehat.find('#tanggalsurat').on('change', (e) => {
            const tanggal = splitTanggal(e.currentTarget.value);
            setNoSuratSehat(tanggal).done((no) => {
                formSuratSehat.find('#no_surat').val(no);
            })
        })

        $('#btnSimpanSuratSehat').on('click', (e) => {
            e.preventDefault();
            const data = getDataForm('formSuratSehat', ['input', 'select']);
            $.post(`/efktp/surat/sehat`, data).done((response) => {
                alertSuccessAjax().then(() => {
                    const tgl1 = localStorage.getItem('tglSuratSehat1')
                    const tgl2 = localStorage.getItem('tglSuratSehat2')
                    loadSuratSehat(tgl1, tgl2);
                });
            })
        });

        function deleteSuratSehat(no_surat) {
            Swal.fire({
                title: "Yakin hapus ?",
                html: "Anda tidak bisa mengembalikan data ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/efktp/surat/sehat/delete/${no_surat}`).done((response) => {
                        alertSuccessAjax().then(() => {
                            const tgl1 = localStorage.getItem('tglSuratSehat1')
                            const tgl2 = localStorage.getItem('tglSuratSehat2')
                            loadSuratSehat(tgl1, tgl2);
                        })
                    })
                }
            });

        }
        $('#btnFilterSuratSehat').on('click', () => {
            const tglAwal = splitTanggal(modalSuratSehat.find('#tgl_pertama').val());
            const tglAkhir = splitTanggal(modalSuratSehat.find('#tgl_kedua').val());
            localStorage.setItem('tglSuratSehat1', tglAwal)
            localStorage.setItem('tglSuratSehat2', tglAkhir)
            loadSuratSehat(tglAwal, tglAkhir);
        })
    </script>
@endpush

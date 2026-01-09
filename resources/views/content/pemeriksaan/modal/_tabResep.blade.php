<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist" id="tabObat">
            <li class="nav-item" role="presentation">
                <a href="#tabsResepUmum" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">Umum</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabsResepRacikan" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                    tabindex="-1">Racikan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabsResepPaketan" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab"
                    tabindex="-1">Paket Obat</a>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <a href="#tabsRiwayatResep" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Riwayat</a>
            </li> --}}
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content">
            <div class="tab-pane fade active show" id="tabsResepUmum" role="tabpanel">
                @include('content.pemeriksaan.modal._resepUmum')
            </div>
            <div class="tab-pane fade" id="tabsResepRacikan" role="tabpanel">
                @includeIf('content.pemeriksaan.modal._resepRacikan')
            </div>
            <div class="tab-pane fade" id="tabsResepPaketan" role="tabpanel" style="">
                <table class="table table-sm mb-2 table-bordered w-100" id="tbResepPaketan">
                </table>
            </div>

            {{-- <div class="tab-pane fade" id="tabsRiwayatResep" role="tabpanel">
                <h4>Activity tab</h4>
                <div>Donec ac vitae diam amet vel leo egestas consequat rhoncus in luctus amet, facilisi sit mauris accumsan nibh habitant senectus</div>
            </div> --}}
        </div>
    </div>
    <div class="card-footer p-2">
        <button type="button" class="btn btn-sm btn-primary" id="btnTambahResep">Buat Resep</button>
        <button type="button" class="btn btn-sm btn-info d-none" id="btnCetakResep"><i class="ti ti-printer me-1"></i>
            Cetak
            Resep
        </button>

    </div>
</div>

@push('script')
    <script>
        function createResepObat(no_rawat, status, kd_dokter) {
            const resep = $.post(`{{ url('/resep/create') }}`, {
                no_rawat: no_rawat,
                kd_dokter: kd_dokter,
                status: status,
            });

            return resep;
        }

        function getResep(data) {
            const resep = $.get(`{{ url('/resep/get') }}`, data)
            return resep
        }


        function deleteResep(no_rawat) {
            const resepDokter = $.post(`{{ url('/resep/delete') }}`, {
                no_rawat: no_rawat
            })
            return resepDokter;
        }


        function tambahResep(no_rawat) {

            // createResepDokter()


            tabelResepUmum.removeClass('d-none')
            tabelResepRacikan.removeClass('d-none')
            btnSimpanObat.removeClass('d-none')
            btnSimpanRacikan.removeClass('d-none')
            btnCetakResep.removeClass('d-none')
            btnTambahObat.removeClass('d-none')
            btnTambahRacikan.removeClass('d-none')

            // tambahBarisObat(tabelResepUmum);
            const dokter = $('#nip').val()
            createResepObat(no_rawat, 'ralan', dokter).done((response) => {
                $('#no_resep').val(response.no_resep)
                btnTambahResep.removeClass('btn-primary').addClass('btn-danger');
                btnTambahResep.attr('onclick', `hapusResep('${no_rawat}')`)
                btnTambahResep.text('Hapus Resep')
            }).fail((request) => {
                alertErrorAjax(request)
            })


            // tabelResepUmum.removeClass('d-none')
            // tabelResepRacikan.removeClass('d-none')
            // btnSimpanObat.removeClass('d-none')
            // btnSimpanRacikan.removeClass('d-none')
            // btnCetakResep.removeClass('d-none')
            // btnTambahObat.removeClass('d-none')
            // btnTambahRacikan.removeClass('d-none')

            // // tambahBarisObat(tabelResepUmum);
            // const dokter = $('#nip').val()
            // createResepObat(no_rawat, 'ralan', dokter).done((response) => {
            //     $('#no_resep').val(response.no_resep)
            //     btnTambahResep.removeClass('btn-primary').addClass('btn-danger');
            //     btnTambahResep.attr('onclick', `hapusResep('${no_rawat}')`)
            //     btnTambahResep.text('Hapus Resep')
            // }).fail((request) => {
            //     alertErrorAjax(request)
            // })

        }

        function hapusResep(no_rawat) {
            const no_resep = $('input[name=no_resep]')
            Swal.fire({
                title: "Yakin hapus obat ini ?",
                html: "Anda tidak bisa mengembalikan obat ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iya, Yakin",
                cancelButtonText: "Tidak, Batalkan"
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteResep(no_rawat).done((response) => {

                        formCpptRajal.find('textarea[name=rtl]').val('-')
                        no_resep.val('');
                        btnTambahResep.removeClass('btn-danger').addClass('btn-primary');
                        btnTambahResep.text('Buat Resep')
                        btnTambahResep.attr('onclick', `tambahResep('${no_rawat}')`)

                        btnSimpanObat.addClass('d-none')
                        btnTambahObat.addClass('d-none')
                        tabelResepUmum.addClass('d-none')
                        tabelResepUmum.find('tbody').empty();

                        btnTambahRacikan.addClass('d-none')
                        tabelResepRacikan.addClass('d-none')
                        btnSimpanRacikan.addClass('d-none')
                        tabelResepRacikan.find('tbody').empty();
                        btnCetakResep.addClass('d-none')
                        showToast('Berhasil menghapus resep')
                    }).fail((request) => {
                        alertErrorAjax(request)
                    })
                }
            });

        }

        $('#modalCppt').find('a[href="#tabsResepPaketan"]')
            .on('shown.bs.tab', (e) => {
                const tableResepPaketan = new DataTable('#tbResepPaketan', {
                    responsive: true,
                    stateSave: true,
                    serverSide: true,
                    destroy: true,
                    processing: true,
                    ajax: {
                        url: "{{ url('/paket-obat/datatable') }}",
                    },
                    columns: [{
                        title: 'Paket',
                        data: 'nama',
                        width: '15%',
                        render: (data, type, row) => {
                            return `<a href="javascript:void(0)" class="badge bg-azure-lt" onclick="setPaketToResep(${row.id})">
                                ${data}
                            </a>`
                        }

                    }, {
                        title: "Poliklinik",
                        data: 'poliklinik.nm_poli',
                        width: '20%'
                    }, {
                        title: 'Umum',
                        data: 'umum',
                        render: (data, type, row) => {
                            if (!data) {
                                return;
                            }
                            return data.map((item, index) => {
                                return `<span class="badge bg-purple-lt">
                                    ${item.databarang.nama_brng}
                                </span>`
                            }).join(', ');
                        }
                    }, {
                        title: 'Racikan',
                        data: 'racikan',
                        render: (data, type, row) => {
                            if (!data) {
                                return;
                            }
                            return data.map((item, index) => {
                                return `<span class="badge bg-orange-lt">${item.template.nm_racik}</span>`
                            }).join(', ')
                        }
                    }]

                })
            })

        function setPaketToResep(id) {
            $.get(`paket-obat/${id}`).done((response) => {
                const {
                    data
                } = response
                const umumKosong = !data.umum || data.umum.length === 0;
                const racikanKosong = !data.racikan || data.racikan.length === 0;

                if (umumKosong && racikanKosong) {
                    console.log('Tidak ada data umum maupun racikan, skip...');
                    return; // berhenti di sini
                }
                const obatUmum = data.umum.map((item, index) => {
                    return {
                        'kode_brng': item.kode_brng,
                        'jml': item.jumlah,
                        'aturan_pakai': item.aturan_pakai
                    }
                })

                const obatRacik = data.racikan.map((item, index) => {
                    return {
                        'nama_racik': item.template.nm_racik,
                        'jml_dr': item.jumlah,
                        'aturan_pakai': item.aturan_pakai,
                        'kd_racik': item.kd_racik,
                        'detail': item.template.detail.map((items, index) => {
                            return {
                                'kode_brng': items.kode_brng,
                                'kandungan': items.barang.kapasitas,
                                'jml': item.jumlah,
                                'p1': 1,
                                'p2': 1,
                            }
                        })
                    }
                })

                Swal.fire({
                    'title': `Guankan Paket ?`,
                    'icon': 'info',
                    'html': `Anda akan gunakan paket ${data.nama}`,
                    'showConfirmButton': true,
                    'showCancelButton': true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const no_rawat = formCpptRajal.find('input[name=no_rawat]').val();
                        const kd_dokter = formCpptRajal.find('input[name=nip]').val();

                        $.post('{{ url('/resep/create-form-paket') }}', {
                            no_rawat: no_rawat,
                            kd_dokter: kd_dokter,
                            status: 'ralan',
                            umum: obatUmum,
                            racikan: obatRacik
                        }).done((response) => {
                            showToast('Success');
                            tulisPlan(response.data.no_resep);
                            setResepPasien(no_rawat)
                        }).fail((xhr) => {
                            showToast(xhr.responseJSON.message, 'error')
                        })
                    }
                })

            })
        }
    </script>
@endpush

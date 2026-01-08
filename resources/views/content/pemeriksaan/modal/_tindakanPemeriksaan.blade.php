<div id="tindakanPemeriksaan">
    <form action="" id="formTindakanDokter">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <label for="no_rawat" class="form-label">No. Rawat</label>
                        <input class="form-control" id="no_rawat" name="no_rawat" />
                    </div>
                    <div class="col-md-4">
                        <label for="nm_pasien" class="form-label">Pasien</label>
                        <div class="input-group">
                            <input class="form-control" id="no_rkm_medis" name="no_rkm_medis" />
                            <input class="form-control w-50" id="nm_pasien" name="nm_pasien" />
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="nm_dokter" class="form-label">Dokter</label>
                        <div class="input-group">
                            <input class="form-control" id="kd_dokter" name="kd_dokter" readonly />
                            <input class="form-control w-50" id="nm_dokter" name="nm_dokter" readonly />

                        </div>
                    </div>
                    <div class="col-md-12 mt-2">

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="tabelTindakanDokter">

                            </table>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title h5">Tindakan Dilakukan</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered" id="tabelTindakanDilakukan">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Tgl. Perawatan</th>
                                    <th>Jam</th>
                                    <th>Perawatan</th>
                                    <th>Dokter</th>
                                    <th>Biaya</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <button type="button" class="btn btn-danger" onclick="deleteTindakanDokter()">
                            <i class="ti ti-trash"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@push('style')
    <style>
        #tabel-tindakan {
            table-layout: fixed;
            width: 100%;
            white-space: nowrap;
        }

        #tabel-tindakan th,
        #tabel-tindakan td {
            text-overflow: ellipsis;
            overflow: hidden;
        }
    </style>
@endpush
@push('scriptTindakan')
    <script>
        const tabsTindakan = modalCppt.find('#tabs-tindakan');
        const formTindakanDokter = $('#formTindakanDokter');
        // is tabsTindakan active get data from datapasien & data tindakanPasien
        targetTabsTindakan.on('shown.bs.tab', function(event) {
            const no_rawat = formCpptRajal.find('input[name=no_rawat]').val();
            const nm_pasien = formCpptRajal.find('input[name=nm_pasien]').val();
            const no_rkm_medis = formCpptRajal.find('input[name=no_rkm_medis]').val();
            const kd_dokter = formCpptRajal.find('input[name=nip]').val();
            const nm_dokter = formCpptRajal.find('input[name=nm_dokter]').val();

            formTindakanDokter.find('#no_rawat').val(no_rawat);
            formTindakanDokter.find('#nm_pasien').val(nm_pasien);
            formTindakanDokter.find('#no_rkm_medis').val(no_rkm_medis);
            formTindakanDokter.find('#kd_dokter').val(kd_dokter);
            formTindakanDokter.find('#nm_dokter').val(nm_dokter);
            tableTindakanDokter()
            getTindakanDilakukan(no_rawat)

            $('#btnSimpanCppt').addClass('d-none')
            $('#btnCreateHasilUsg').addClass('d-none')
            $('#btnCreateTindakan').removeClass('d-none')

        });

        // global
        let selectedRows = []; // array id yang dicentang (urut sesuai centang)
        let selectedDataCache = {}; // cache data lengkap per id
        let lastRequestStart = 0; // simpan start dari request terakhir
        let diskonValues = {
            persen: {}, // {kd_jenis_prw: value}
            rupiah: {} // {kd_jenis_prw: value}
        };

        function tableTindakanDokter() {
            // simpan referensi table ke variable supaya bisa dipakai di event handler
            const table = new DataTable('#tabelTindakanDokter', {
                responsive: true,
                serverSide: true,
                processing: true,
                destroy: true,
                autoWidth: false,
                lengthChange: false,
                ajax: {
                    url: '/efktp/jenis-perawatan/table',
                    type: 'GET',
                    // tangkap request params sebelum dikirim
                    data: function(d) {
                        lastRequestStart = d.start || 0;
                        return d;
                    },
                    // intercept response dari server
                    dataSrc: function(json) {
                        // update cache untuk selected ids yang mungkin ada di response ini
                        json.data.forEach(d => {
                            if (selectedRows.includes(d.kd_jenis_prw)) {
                                selectedDataCache[d.kd_jenis_prw] = d;
                            }
                        });

                        // jika request halaman pertama (start === 0) => gabungkan selected rows di depan
                        if (lastRequestStart === 0) {
                            // buat array checkedData berdasarkan urutan selectedRows
                            const checkedData = selectedRows.map(id => selectedDataCache[id]).filter(Boolean);

                            // hindari duplikat: kumpulkan id yang sudah ada di checkedData
                            const checkedIds = new Set(checkedData.map(d => d.kd_jenis_prw));

                            // sisanya dari response yang bukan selected
                            const otherData = json.data.filter(d => !checkedIds.has(d.kd_jenis_prw));

                            // gabungkan: selected dulu, lalu data lainnya
                            return [...checkedData, ...otherData];
                        } else {
                            // bukan halaman pertama => jangan tampilkan item yg sudah dipindah ke halaman 1
                            return json.data.filter(d => !selectedRows.includes(d.kd_jenis_prw));
                        }
                    },
                    complete: function() {
                        $('#tabelTindakanDokter tbody').width(w - 5); // -- - THIS IS THE FIX
                        $('#tabelTindakanDokter').width(w + 5);
                    }
                },

                columnDefs: [{
                        targets: [0],
                        orderable: false
                    },
                    {
                        targets: [4],
                        className: 'text-end'
                    }
                ],

                columns: [{
                        name: 'kd_jenis_prw',
                        data: 'kd_jenis_prw',
                        title: '',
                        render: function(data, type, row, meta) {
                            // jangan gunakan onchange inline (kita pakai delegated handler)
                            const checked = selectedRows.includes(data) ? 'checked' : '';
                            return `<input type="checkbox" class="form-check-input tindakan-check" data-id="${data}" ${checked}>`;
                        }
                    },
                    {
                        data: 'kd_jenis_prw',
                        title: 'Kode',
                    },
                    {
                        data: 'nm_perawatan',
                        title: 'Nama Tindakan'
                    },
                    {
                        data: 'kategori.nm_kategori',
                        title: 'Kategori'
                    },
                    {
                        data: 'total_byrdr',
                        title: 'Biaya',
                        render: function(data, type, row, meta) {
                            return formatCurrency(data);
                        }
                    },
                    {
                        data: '',
                        title: 'Diskon (%)',
                        width: '10%',
                        render: function(data, type, row, meta) {
                            const checked = selectedRows.includes(row.kd_jenis_prw);
                            const disabled = checked ? '' : 'disabled';
                            const val = diskonValues.persen[row.kd_jenis_prw] ?? "0";

                            return `<input class="form-control w-100 diskonPersen" 
                 type="number" 
                 name="diskonPersen[${row.kd_jenis_prw}]" 
                 value="${val}" ${disabled} 
                 data-id="${row.kd_jenis_prw}"
                 data-tarif="${row.total_byrdr}">`;
                        }
                    },
                    {
                        data: '',
                        title: 'Diskon (Rp.)',
                        width: '10%',
                        render: function(data, type, row, meta) {
                            const checked = selectedRows.includes(row.kd_jenis_prw);
                            const disabled = checked ? '' : 'disabled';
                            const rawVal = diskonValues.rupiah[row.kd_jenis_prw] ?? "0";
                            const formattedVal = rawVal === "0" ? "0" : formatRupiah(rawVal.toString());

                            return `<input class="form-control w-100 diskonRupiah" 
                 type="text" 
                 name="diskonRupiah[${row.kd_jenis_prw}]" 
                 value="${formattedVal}" ${disabled} 
                 data-id="${row.kd_jenis_prw}"
                 data-tarif="${row.total_byrdr}">`;
                        }
                    }


                ],
                initComplete: function(setting, json) {
                    const api = this.api();
                    api.columns.adjust().draw();
                }
            });

            // delegated handler untuk checkbox (satu handler untuk seluruh table)
            $('#tabelTindakanDokter').off('change', '.tindakan-check').on('change', '.tindakan-check', function() {
                const id = $(this).data('id');
                const $tr = $(this).closest('tr');
                const rowApi = table.row($tr);
                const rowData = rowApi.data(); // ambil data baris sekarang (penting untuk cache)

                if (this.checked) {
                    if (!selectedRows.includes(id)) {
                        selectedRows.push(id);
                    }
                    // simpan ke cache segera supaya saat kita draw halaman 1, data tersedia
                    if (rowData) selectedDataCache[id] = rowData;
                } else {
                    // uncheck -> hapus dari selected + cache
                    selectedRows = selectedRows.filter(x => x !== id);
                    delete selectedDataCache[id];
                }

                // pindah ke halaman pertama, lalu redraw (false supaya tidak kehilangan state paging)
                table.page(0).draw(false);
            });


            return table;
        }

        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Checkbox → aktifkan / nonaktifkan input
        $(document).on("change", ".tindakan-check", function() {
            const id = $(this).data("id");
            const isChecked = $(this).is(":checked");

            $(`.diskonPersen[data-id="${id}"], .diskonRupiah[data-id="${id}"]`)
                .prop("disabled", !isChecked);
        });

        // Fokus → kosongkan kalau 0
        $(document).on("focus", ".diskonRupiah, .diskonPersen", function() {
            if ($(this).val() === "0") $(this).val("");
        });

        $(document).on("blur", ".diskonPersen", function() {
            let val = $(this).val().replace(/[^0-9,]/g, "").replace(",", ".");
            let num = parseFloat(val);

            if (isNaN(num) || num === 0) {
                $(this).val("0");
            } else {
                // tampilkan dengan koma sebagai desimal
                // let str = num.toString().replace(".", ",");
                $(this).val(num.toString());
            }
        });


        // Blur rupiah → kembalikan ke 0 kalau kosong, kalau ada format titik
        $(document).on("blur", ".diskonRupiah", function() {
            let val = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(val === "" || val === "0" ? "0" : formatRupiah(val));
        });

        // Input rupiah → update persen + cache
        $(document).on("input", ".diskonRupiah", function() {
            const id = $(this).data("id");
            const tarif = parseFloat($(this).data("tarif")) || 0;
            let val = $(this).val().replace(/[^0-9]/g, "");

            const rupiah = parseFloat(val) || 0;
            const persen = tarif > 0 ? ((rupiah / tarif) * 100).toFixed(2) : 0;

            // tampilkan kembali dengan format ribuan
            $(this).val(rupiah > 0 ? formatRupiah(rupiah.toString()) : "0");

            // update field persen di baris yang sama
            $(this).closest("tr").find(".diskonPersen").val(persen);

            // simpan ke cache
            diskonValues.rupiah[id] = rupiah;
            diskonValues.persen[id] = persen;
        });

        // Input persen → update rupiah + cache
        $(document).on("input", ".diskonPersen", function() {
            const id = $(this).data("id");
            const tarif = parseFloat($(this).data("tarif")) || 0;
            let persen = parseFloat($(this).val()) || 0;

            const rupiah = Math.round((persen / 100) * tarif);

            // update field rupiah di baris yang sama dengan format ribuan
            $(this).closest("tr").find(".diskonRupiah")
                .val(rupiah > 0 ? formatRupiah(rupiah.toString()) : "0");

            // simpan ke cache
            diskonValues.persen[id] = persen;
            diskonValues.rupiah[id] = rupiah;
        });


        function createTindakanDokter() {
            const no_rawat = formTindakanDokter.find('#no_rawat').val();
            const kd_dokter = formTindakanDokter.find('#kd_dokter').val();
            const nm_pasien = formTindakanDokter.find('#nm_pasien').val();
            const no_rkm_medis = formTindakanDokter.find('#no_rkm_medis').val();


            const selectedData = selectedRows
                .map(id => {
                    const d = selectedDataCache[id];
                    if (!d) return null;

                    return {
                        ...d,
                        diskonPersen: diskonValues.persen[id] ?? "0",
                        diskonRupiah: diskonValues.rupiah[id] ?? "0"
                    };
                })
                .filter(Boolean);
            $.post('/efktp/pemeriksaan/tindakan-dokter', {
                no_rawat,
                kd_dokter,
                nm_pasien,
                no_rkm_medis,
                tindakan: selectedData
            }).done((response) => {
                getTindakanDilakukan(no_rawat);
                showToast('Berhasil menambahkan tindakan');
                $('.tindakan-check').prop('checked', false);
            });
        }


        function getTindakanDilakukan(no_rawat) {
            $.get(`/efktp/pemeriksaan/tindakan-dokter/get`, {
                no_rawat: no_rawat
            }).done((response) => {
                const tbody = modalCppt.find('#tabelTindakanDilakukan tbody');
                tbody.empty();
                response.data.forEach((item, index) => {
                    const row = `<tr>
                        <td><input type="checkbox" class="form-check-input tindakan-hasil" name="kode_tindakan[]" id="tindakan${index}" value="${item.kd_jenis_prw}" data-tgl="${item.tgl_perawatan}" data-jam="${item.jam_rawat}" data-rawat="${item.no_rawat}"/></td>
                        <td>${splitTanggal(item.tgl_perawatan)}</td>
                        <td>${item.jam_rawat}</td>
                        <td>${item.tindakan.nm_perawatan}</td>
                        <td>${item.dokter.nm_dokter}</td>
                        <td class="text-end">${formatCurrency(item.biaya_rawat)}</td>
                    </tr>`;
                    tbody.append(row);
                });
            })
        }

        function deleteTindakanDokter() {
            const no_rawat = formTindakanDokter.find('#no_rawat').val();
            const kd_dokter = formTindakanDokter.find('#kd_dokter').val();
            const nm_pasien = formTindakanDokter.find('#nm_pasien').val();
            const no_rkm_medis = formTindakanDokter.find('#no_rkm_medis').val();


            Swal.fire({
                title: 'Yakin ?',
                html: `Anda akan menghapus data tindakan ini ?`,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#3085d6',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',

            }).then((result) => {
                if (result.isConfirmed) {
                    const checkedTindakan = $('.tindakan-hasil:checked').map(function() {
                        const $this = $(this);
                        return {
                            kd_jenis_prw: $this.val(),
                            no_rawat: $this.data('rawat'),
                            jam_rawat: $this.data('jam'),
                            tgl_perawatan: $this.data('tgl'),
                        };
                    }).get();

                    $.ajax({
                        url: `/efktp/pemeriksaan/tindakan-dokter/delete`,
                        method: 'DELETE',
                        data: {
                            no_rawat: no_rawat,
                            no_rawat: no_rawat,
                            kd_dokter: kd_dokter,
                            nm_pasien: nm_pasien,
                            no_rkm_medis: no_rkm_medis,
                            tindakan: checkedTindakan

                        }
                    }).done((response) => {
                        getTindakanDilakukan(no_rawat)
                        showToast('Berhasil Hapus Tindakan')
                    })
                }
            })


        }
    </script>
@endpush

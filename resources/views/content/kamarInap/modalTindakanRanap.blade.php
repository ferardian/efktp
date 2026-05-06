<div class="modal modal-blur fade" id="modalTindakanRanap" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Tindakan Rawat Inap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTindakanRanap">
                    <input type="hidden" name="no_rawat" id="tindakan_no_rawat">
                    <input type="hidden" name="no_rkm_medis" id="tindakan_no_rkm_medis">
                    <input type="hidden" name="nm_pasien" id="tindakan_nm_pasien">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Pelaksana</label>
                            <div class="form-selectgroup">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="pelaksana_type" value="dr" class="form-selectgroup-input" checked>
                                    <span class="form-selectgroup-label">Dokter</span>
                                </label>
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="pelaksana_type" value="pr" class="form-selectgroup-input">
                                    <span class="form-selectgroup-label">Petugas</span>
                                </label>
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="pelaksana_type" value="drpr" class="form-selectgroup-input">
                                    <span class="form-selectgroup-label">Dokter & Petugas</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih Tindakan / Perawatan</label>
                            <select class="form-select" name="kd_jenis_prw" id="selectTindakanRanap" style="width: 100%"></select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6" id="container_select_dokter">
                            <label class="form-label">Dokter</label>
                            <select class="form-select" name="kd_dokter" id="selectDokterTindakan" style="width: 100%"></select>
                        </div>
                        <div class="col-md-6" id="container_select_petugas" style="display: none;">
                            <label class="form-label">Petugas</label>
                            <select class="form-select" name="nip" id="selectPetugasTindakan" style="width: 100%"></select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="text" class="form-control filterTangal" name="tgl_perawatan" id="tgl_tindakan" value="{{ date('d-m-Y') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam</label>
                            <input type="text" class="form-control" name="jam_rawat" id="jam_tindakan" value="{{ date('H:i:s') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" id="btnSimpanTindakanRanap">
                                <i class="ti ti-device-floppy me-2"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-3">
                <div class="card bg-light-lt border-0 shadow-none mb-3">
                    <div class="card-body p-2">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label mb-1 text-dark" style="font-size: 0.75rem; font-weight: 600;">Rentang Tanggal Riwayat</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control filterTanggal text-dark" id="tgl_awal_tindakan" autocomplete="off" placeholder="Tgl Awal" style="color: #000 !important;">
                                    <span class="input-group-text text-dark">s.d</span>
                                    <input type="text" class="form-control filterTanggal text-dark" id="tgl_akhir_tindakan" autocomplete="off" placeholder="Tgl Akhir" style="color: #000 !important;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-info btn-sm w-100" onclick="loadRiwayatTindakan($('#tindakan_no_rawat').val())">
                                    <i class="ti ti-filter me-1"></i> Tampilkan
                                </button>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="text-muted small">Menampilkan riwayat tindakan pasien</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover" id="tabelRiwayatTindakan" width="100%">
                        <thead>
                            <tr>
                                <th>Tgl & Jam</th>
                                <th>Tindakan</th>
                                <th>Pelaksana</th>
                                <th>Nama Pelaksana</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    const modalTindakanRanap = $('#modalTindakanRanap');
    const formTindakanRanap = $('#formTindakanRanap');
    const selectTindakanRanap = $('#selectTindakanRanap');
    const selectDokterTindakan = $('#selectDokterTindakan');
    const selectPetugasTindakan = $('#selectPetugasTindakan');

    function openModalTindakan(no_rawat, no_rkm_medis = '', nm_pasien = '') {
        $('#tindakan_no_rawat').val(no_rawat);
        $('#tindakan_no_rkm_medis').val(no_rkm_medis);
        $('#tindakan_nm_pasien').val(nm_pasien);

        // Set default date for filter
        $('#tgl_awal_tindakan').val(tanggal);
        $('#tgl_akhir_tindakan').val(tanggal);

        modalTindakanRanap.modal('show');
        loadRiwayatTindakan(no_rawat);
        
        // Reset form
        const now = new Date();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        $('#jam_tindakan').val(`${jam}:${menit}:${detik}`);
        selectTindakanRanap.val(null).trigger('change');
    }

    $(document).ready(() => {
        $('.filterTanggal').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayBtn: true,
            todayHighlight: true,
            language: "id",
        });

        selectJnsPerawatanInap(selectTindakanRanap, modalTindakanRanap, 'dr'); // Default dr as checked
        selectDokter(selectDokterTindakan, modalTindakanRanap);
        selectPetugas(selectPetugasTindakan, modalTindakanRanap);

        $('input[name="pelaksana_type"]').on('change', function() {
            const val = $(this).val();
            
            // Re-init Select2 with pelaksana filter
            selectJnsPerawatanInap(selectTindakanRanap, modalTindakanRanap, val);
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
    });

    $('#btnSimpanTindakanRanap').on('click', () => {
        const data = {
            no_rawat: $('#tindakan_no_rawat').val(),
            no_rkm_medis: $('#tindakan_no_rkm_medis').val(),
            nm_pasien: $('#tindakan_nm_pasien').val(),
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
            columns: [
                {
                    data: 'tgl_perawatan',
                    render: (data, type, row) => `${splitTanggal(data)} ${row.jam_rawat}`
                },
                { data: 'tindakan.nm_perawatan' },
                {
                    data: 'pelaksana',
                    render: (data) => {
                        let badge = 'bg-blue';
                        if (data === 'Petugas') badge = 'bg-orange';
                        if (data === 'Dokter & Petugas') badge = 'bg-purple';
                        return `<span class="badge ${badge}">${data}</span>`;
                    }
                },
                { data: 'nama_pelaksana' },
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
</script>
@endpush

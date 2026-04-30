@extends('layout')

@section('body')
    <div class="container-xl">
        <div class="row gy-2">
            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="table-default" class="table-responsive">
                            <table class="table table-hover table-striped w-100 fs-5" id="tbJadwal">
                                <thead>
                                    <tr>
                                        <th>Dokter</th>
                                        <th>Hari</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Poliklinik</th>
                                        <th>Kuota</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary" id="btnCreateJadwal" onclick="resetFormJadwal()">
                                    <i class="ti ti-plus me-2"></i> Buat Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xl-5 col-md-12 col-sm-12">
                <form id="formJadwal">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Jadwal Praktek</h5>
                            <div class="mb-2">
                                <label class="form-label">Dokter</label>
                                <select class="form-select-2" id="kd_dokter" name="kd_dokter" style="width:100%"></select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Hari Kerja</label>
                                <select class="form-select" id="hari_kerja" name="hari_kerja">
                                    <option value="SENIN">SENIN</option>
                                    <option value="SELASA">SELASA</option>
                                    <option value="RABU">RABU</option>
                                    <option value="KAMIS">KAMIS</option>
                                    <option value="JUMAT">JUMAT</option>
                                    <option value="SABTU">SABTU</option>
                                    <option value="AKHAD">AKHAD</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Jam Mulai</label>
                                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" step="1">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Jam Selesai</label>
                                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" step="1">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Poliklinik</label>
                                <select class="form-select-2" id="kd_poli" name="kd_poli" style="width:100%"></select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kuota</label>
                                <input type="number" class="form-control" id="kuota" name="kuota" value="0">
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-success w-100" id="btnSimpanJadwal" onclick="simpanJadwal()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Jadwal
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(() => {
            renderTableJadwal();
            selectDokter($('#kd_dokter'), $('body'));
            selectPoliklinik($('#kd_poli'), $('body'));
        });

        function renderTableJadwal() {
            $('#tbJadwal').DataTable({
                responsive: true,
                stateSave: true,
                serverSide: true,
                destroy: true,
                processing: true,
                scrollY: setTableHeight(),
                ajax: {
                    url: "{{ url('/master/jadwal/get') }}"
                },
                columns: [
                    { data: 'dokter.nm_dokter', name: 'dokter.nm_dokter' },
                    { data: 'hari_kerja', name: 'hari_kerja' },
                    { data: 'jam_mulai', name: 'jam_mulai' },
                    { data: 'jam_selesai', name: 'jam_selesai' },
                    { data: 'poliklinik.nm_poli', name: 'poliklinik.nm_poli' },
                    { data: 'kuota', name: 'kuota' },
                    {
                        data: null,
                        render: (data) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning" onclick="editJadwal('${data.kd_dokter}', '${data.hari_kerja}', '${data.jam_mulai}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteJadwal('${data.kd_dokter}', '${data.hari_kerja}', '${data.jam_mulai}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ]
            });
        }

        function resetFormJadwal() {
            $('#formJadwal').trigger('reset');
            $('#kd_dokter').val('').trigger('change');
            $('#kd_poli').val('').trigger('change');
            $('#btnSimpanJadwal').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan Jadwal');
            
            // Re-enable fields that might have been disabled
            $('#kd_dokter').prop('disabled', false);
            $('#hari_kerja').prop('disabled', false);
            $('#jam_mulai').prop('disabled', false);
        }

        function simpanJadwal() {
            const data = {
                kd_dokter: $('#kd_dokter').val(),
                hari_kerja: $('#hari_kerja').val(),
                jam_mulai: $('#jam_mulai').val(),
                jam_selesai: $('#jam_selesai').val(),
                kd_poli: $('#kd_poli').val(),
                kuota: $('#kuota').val(),
            };

            if (!data.kd_dokter || !data.hari_kerja || !data.jam_mulai || !data.kd_poli) {
                showToast('Mohon lengkapi data jadwal', 'warning');
                return;
            }

            loadingAjax('Sedang menyimpan jadwal...');
            $.post("{{ url('/master/jadwal') }}", data).done((response) => {
                showToast(response.message);
                renderTableJadwal();
                resetFormJadwal();
            }).fail((xhr) => {
                showToast(xhr.responseJSON.message || 'Gagal menyimpan jadwal', 'error');
            }).always(() => {
                Swal.close();
            });
        }

        function editJadwal(kd_dokter, hari_kerja, jam_mulai) {
            // Get data from table or re-fetch (simpler to just fetch or use current row data)
            const table = $('#tbJadwal').DataTable();
            const data = table.rows().data().toArray().find(i => i.kd_dokter == kd_dokter && i.hari_kerja == hari_kerja && i.jam_mulai == jam_mulai);
            
            if (data) {
                // Populate form
                const optDokter = new Option(data.dokter.nm_dokter, data.kd_dokter, true, true);
                $('#kd_dokter').append(optDokter).trigger('change');
                
                $('#hari_kerja').val(data.hari_kerja);
                $('#jam_mulai').val(data.jam_mulai);
                $('#jam_selesai').val(data.jam_selesai);
                
                const optPoli = new Option(data.poliklinik.nm_poli, data.kd_poli, true, true);
                $('#kd_poli').append(optPoli).trigger('change');
                
                $('#kuota').val(data.kuota);

                $('#btnSimpanJadwal').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Update Jadwal');
                
                // Composite keys usually should not be changed in updateOrCreate if they are identifying the record
                // But since we use updateOrCreate, if they change it will create a new one. 
                // In SIMRS Khanza, these are the PK, so changing them means a new record.
            }
        }

        function deleteJadwal(kd_dokter, hari_kerja, jam_mulai) {
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Data jadwal praktek ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus jadwal...');
                    $.post("{{ url('/master/jadwal/delete') }}", {
                        kd_dokter: kd_dokter,
                        hari_kerja: hari_kerja,
                        jam_mulai: jam_mulai
                    }).done((response) => {
                        showToast(response.message);
                        renderTableJadwal();
                    }).fail((xhr) => {
                        showToast('Gagal menghapus jadwal', 'error');
                    }).always(() => {
                        Swal.close();
                    });
                }
            });
        }
    </script>
@endpush

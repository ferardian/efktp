<div class="modal modal-blur fade" id="modalKamarInap" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Kamar Inap Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKamarInap" action="javascript:void(0)">
                <div class="modal-body">
                    <div class="row row-cards">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">No. Rawat</label>
                                <input type="text" class="form-control" name="no_rawat" readonly placeholder="No. Rawat">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Pasien</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="no_rkm_medis" readonly placeholder="RM">
                                    <input type="text" class="form-control w-50" name="nm_pasien" readonly placeholder="Nama Pasien">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Kamar / Bangsal</label>
                                <select class="form-select" name="kd_kamar" id="selectKamar" style="width: 100%"></select>
                            </div>
                        </div>
                         <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Tarif Kamar</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" name="trf_kamar" readonly value="0">
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Status Pulang</label>
                                <input type="text" class="form-control" name="stts_pulang" value="-" readonly>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Diagnosa Awal Masuk</label>
                                <textarea class="form-control" name="diagnosa_awal" rows="3" placeholder="Masukan diagnosa awal"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Masuk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    <input type="text" class="form-control" name="tgl_masuk" id="tglMasuk" value="{{ date('d-m-Y') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Jam Masuk</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                    <input type="text" class="form-control" name="jam_masuk" id="jamMasuk" value="{{ date('H:i:s') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Lama (Hari)</label>
                                 <input type="number" class="form-control" name="lama" value="1" min="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary ms-auto" id="btnSimpanKamarInap">
                        <i class="ti ti-device-floppy me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script')
<script>
    const modalKamarInap = $('#modalKamarInap');
    const formKamarInap = $('#formKamarInap');
    const selectKamar = $('#selectKamar');

    function kamarInap(no_rawat) {
        modalKamarInap.modal('show');
        // Reset form
        formKamarInap[0].reset();
        selectKamar.val('').trigger('change');
        
        // Fetch Patient Data
        $.get(`{{ url('registrasi/get/detail') }}`, { no_rawat: no_rawat }).done((response) => {
             formKamarInap.find('input[name="no_rawat"]').val(response.no_rawat);
             formKamarInap.find('input[name="no_rkm_medis"]').val(response.no_rkm_medis);
             formKamarInap.find('input[name="nm_pasien"]').val(response.pasien.nm_pasien);
        }).fail((error) => {
            alertErrorAjax(error);
        });
    }

    $(document).ready(function() {
        $('#tglMasuk').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        selectKamar.select2({
            dropdownParent: modalKamarInap,
            placeholder: 'Cari Kamar Kosong...',
            ajax: {
                url: `{{ url('kamar/ketersediaan') }}`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: `${item.kd_kamar} - ${item.bangsal.nm_bangsal} (${item.kelas}) - Rp ${formatCurrency(item.trf_kamar)}`,
                                id: item.kd_kamar,
                                trf_kamar: item.trf_kamar
                            }
                        })
                    };
                },
                cache: true
            }
        });

        selectKamar.on('select2:select', function (e) {
            var data = e.params.data;
            formKamarInap.find('input[name="trf_kamar"]').val(formatCurrency(data.trf_kamar).replace('Rp', '').trim());
        });

        $('#btnSimpanKamarInap').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Data?',
                text: "Pastikan data yang diinput sudah benar",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = getDataForm('formKamarInap', ['input', 'select', 'textarea']);
                    // Unformat currency for backend
                    // data.trf_kamar = data.trf_kamar.replace(/[^0-9]/g, ''); 
                    
                     $.post(`{{ url('kamar/inap/create') }}`, data)
                    .done((response) => {
                        modalKamarInap.modal('hide');
                        showToast('Berhasil Menyimpan Data Kamar Inap');
                        if (typeof loadTabelRegistrasi === 'function') {
                           loadTabelRegistrasi(); // Refresh table if needed
                        }
                    })
                    .fail((error) => {
                        alertErrorAjax(error);
                    });
                }
            })
        });
    });
</script>
@endpush

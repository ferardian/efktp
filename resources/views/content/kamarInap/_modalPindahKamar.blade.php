<div class="modal modal-blur fade" id="modalPindahKamar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="ti ti-arrows-exchange me-2"></i>Pindah Kamar Inap Pasien</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPindahKamar">
                    <input type="hidden" name="no_rawat" id="pindah_no_rawat">
                    <input type="hidden" name="kd_kamar_lama" id="pindah_kd_kamar_lama">
                    <input type="hidden" name="tgl_masuk_lama" id="pindah_tgl_masuk_lama">
                    <input type="hidden" name="jam_masuk_lama" id="pindah_jam_masuk_lama">

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">No. Rawat</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="pindah_display_no_rawat" readonly>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Nama Pasien</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="pindah_display_nm_pasien" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Masuk Kamar Asal</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="pindah_display_masuk" readonly>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Kamar Asal</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="pindah_display_kamar_lama" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tarif Kamar Asal</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="pindah_display_trf_lama" readonly>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold required">Tgl. Pindah</label>
                            <input type="text" class="form-control form-control-sm tgl_pindah" name="tgl_pindah" id="pindah_tgl_pindah" value="{{ date('d-m-Y') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold required">Jam Pindah</label>
                            <input type="text" class="form-control form-control-sm" name="jam_pindah" id="pindah_jam_pindah" value="{{ date('H:i:s') }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold required">Kamar Tujuan (Kosong)</label>
                            <select class="form-select form-select-sm" name="kd_kamar_baru" id="pindah_kd_kamar_baru" style="width: 100%;"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tarif Kamar Baru</label>
                            <input type="number" class="form-control form-control-sm" name="trf_kamar_baru" id="pindah_trf_kamar_baru" min="0" step="any">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-2">Pilihan Perhitungan Pindah Kamar</label>
                        <div class="border rounded p-2 bg-light-lt">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mode" id="pindah_mode_1" value="1">
                                <label class="form-check-label small" for="pindah_mode_1">
                                    1. Kamar inap sebelumnya dihapus dan pasien dihitung menginap mulai saat ini (kamar lama dihapus dari billing)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mode" id="pindah_mode_2" value="2">
                                <label class="form-check-label small" for="pindah_mode_2">
                                    2. Kamar inap sebelumnya diganti kamarnya dengan kamar baru dan harga kamar menyesuaikan harga baru
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mode" id="pindah_mode_3" value="3" checked>
                                <label class="form-check-label small fw-semibold text-primary" for="pindah_mode_3">
                                    3. Kamar inap sebelumnya distatuskan pindah, lama inap dihitung dan pasien dimasukkan ke kamar inap yang baru
                                </label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="mode" id="pindah_mode_4" value="4">
                                <label class="form-check-label small" for="pindah_mode_4">
                                    4. Seperti nomor 3, kamar inap sebelumnya mengikuti harga tertinggi
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanPindahKamar" onclick="simpanPindahKamar()">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Pindah Kamar
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(() => {
        $('.tgl_pindah').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#pindah_kd_kamar_baru').select2({
            dropdownParent: $('#modalPindahKamar'),
            placeholder: 'Cari kamar kosong...',
            allowClear: true,
            ajax: {
                url: '{{ url("/kamar/ketersediaan") }}',
                dataType: 'json',
                delay: 250,
                data: (params) => ({
                    search: params.term || '',
                    only_kosong: 1
                }),
                processResults: (data) => ({
                    results: (data || []).map(item => ({
                        id: item.kd_kamar,
                        text: `${item.kd_kamar} - ${item.bangsal?.nm_bangsal || '-'} (${item.kelas || '-'}) | Rp ${Number(item.trf_kamar || 0).toLocaleString('id-ID')}`,
                        trf_kamar: item.trf_kamar || 0
                    }))
                })
            }
        }).on('select2:select', function (e) {
            const trf = e.params?.data?.trf_kamar ?? 0;
            $('#pindah_trf_kamar_baru').val(trf);
        }).on('select2:clear', function () {
            $('#pindah_trf_kamar_baru').val('');
        });
    });

    function pindahKamarPasien(no_rawat, kd_kamar, tgl_masuk, jam_masuk) {
        $.get('{{ url("/kamar/inap/detail") }}', {
            no_rawat: no_rawat,
            kd_kamar: kd_kamar,
            tgl_masuk: tgl_masuk,
            jam_masuk: jam_masuk
        }).done((response) => {
            if (!response) {
                Swal.fire('Gagal', 'Data kamar inap tidak ditemukan', 'error');
                return;
            }
            $('#pindah_no_rawat').val(response.no_rawat);
            $('#pindah_kd_kamar_lama').val(response.kd_kamar);
            $('#pindah_tgl_masuk_lama').val(response.tgl_masuk);
            $('#pindah_jam_masuk_lama').val(response.jam_masuk);

            $('#pindah_display_no_rawat').val(response.no_rawat);
            $('#pindah_display_nm_pasien').val(response.reg_periksa?.pasien?.nm_pasien || '-');
            $('#pindah_display_masuk').val(`${splitTanggal(response.tgl_masuk)} ${response.jam_masuk}`);
            $('#pindah_display_kamar_lama').val(`${response.kd_kamar} - ${response.kamar?.bangsal?.nm_bangsal || '-'} (${response.kamar?.kelas || '-'})`);
            $('#pindah_display_trf_lama').val(`Rp ${Number(response.trf_kamar || 0).toLocaleString('id-ID')}`);

            $('#pindah_tgl_pindah').val('{{ date("d-m-Y") }}');
            $('#pindah_jam_pindah').val('{{ date("H:i:s") }}');
            $('#pindah_kd_kamar_baru').val(null).trigger('change');
            $('#pindah_trf_kamar_baru').val('');
            $('#pindah_mode_3').prop('checked', true);

            $('#modalPindahKamar').modal('show');
        });
    }

    function simpanPindahKamar() {
        const kdKamarBaru = $('#pindah_kd_kamar_baru').val();
        if (!kdKamarBaru) {
            Swal.fire('Peringatan', 'Pilih kamar tujuan yang kosong terlebih dahulu', 'warning');
            return;
        }

        const payload = {
            _token: '{{ csrf_token() }}',
            ...Object.fromEntries(new URLSearchParams($('#formPindahKamar').serialize()))
        };

        $('#btnSimpanPindahKamar').prop('disabled', true);
        $.post('{{ url("/kamar/inap/pindah") }}', payload)
            .done((response) => {
                $('#modalPindahKamar').modal('hide');
                alertSuccessAjax('Berhasil', response.message).then(() => {
                    loadTbKamarInap('', '', $('#selectPulang').val() || 'Belum Pulang');
                });
            })
            .fail((error) => {
                alertErrorAjax(error.responseJSON || { message: 'Gagal memindahkan kamar pasien' });
            })
            .always(() => {
                $('#btnSimpanPindahKamar').prop('disabled', false);
            });
    }
</script>
@endpush

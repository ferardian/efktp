<div class="modal modal-blur fade" id="modalPulang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pulangkan Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPulang">
                    <input type="hidden" name="no_rawat" id="pulang_no_rawat">
                    <input type="hidden" name="kd_kamar" id="pulang_kd_kamar">
                    <input type="hidden" name="tgl_masuk" id="pulang_tgl_masuk">
                    <input type="hidden" name="jam_masuk" id="pulang_jam_masuk">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No. Rawat</label>
                            <input type="text" class="form-control" id="pulang_display_no_rawat" readonly disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" id="pulang_display_nm_pasien" readonly disabled>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tgl. Keluar</label>
                            <input type="text" class="form-control tgl_keluar" name="tgl_keluar" id="pulang_tgl_keluar" value="{{ date('d-m-Y') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jam Keluar</label>
                            <input type="text" class="form-control" name="jam_keluar" id="pulang_jam_keluar" value="{{ date('H:i:s') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status Pulang</label>
                        <select class="form-select" name="stts_pulang" id="pulang_stts_pulang">
                            <option value="Atas Persetujuan Dokter">Atas Persetujuan Dokter</option>
                            <option value="Sembuh">Sembuh</option>
                            <option value="Membaik">Membaik</option>
                            <option value="Rujuk">Rujuk</option>
                            <option value="APS">APS</option>
                            <option value="Meninggal">Meninggal</option>
                            <option value="Pulang Paksa">Pulang Paksa</option>
                            <option value="Sehat">Sehat</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Diagnosa Akhir</label>
                        <textarea class="form-control" name="diagnosa_akhir" id="pulang_diagnosa_akhir" rows="3">-</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanPulang()">Simpan & Pulangkan</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function pulangkanPasien(no_rawat, kd_kamar, tgl_masuk, jam_masuk) {
        $.get('{{ url("/kamar/inap/detail") }}', {
            no_rawat: no_rawat,
            kd_kamar: kd_kamar,
            tgl_masuk: tgl_masuk,
            jam_masuk: jam_masuk
        }).done((response) => {
            $('#pulang_no_rawat').val(response.no_rawat);
            $('#pulang_kd_kamar').val(response.kd_kamar);
            $('#pulang_tgl_masuk').val(response.tgl_masuk);
            $('#pulang_jam_masuk').val(response.jam_masuk);
            $('#pulang_display_no_rawat').val(response.no_rawat);
            $('#pulang_display_nm_pasien').val(response.reg_periksa.pasien.nm_pasien);
            
            $('#pulang_tgl_keluar').val('{{ date("d-m-Y") }}');
            $('#pulang_jam_keluar').val('{{ date("H:i:s") }}');
            
            $('#modalPulang').modal('show');
        });
    }
    
    $('.tgl_keluar').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });

    function simpanPulang() {
        const data = $('#formPulang').serialize();
        $.post('{{ url("/kamar/inap/pulangkan") }}', {
            _token: '{{ csrf_token() }}',
            ...Object.fromEntries(new URLSearchParams(data))
        }).done((response) => {
            $('#modalPulang').modal('hide');
            alertSuccessAjax('Berhasil', response.message).then(() => {
                loadTbKamarInap('', '', 'Belum Pulang');
            });
        }).fail((error) => {
            alertErrorAjax(error.responseJSON);
        });
    }
</script>
@endpush

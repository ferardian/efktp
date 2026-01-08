<div class="modal modal-blur fade" id="modalObatPcare" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title m-0">Obat Pcare</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui maxime quae a provident odit inventore deserunt possimus sunt adipisci, nam eum sint corrupti quasi, ex harum excepturi iusto nesciunt tenetur.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="ti ti-x me-2"></i>Keluar</button>
                <button type="button" class="btn btn-success" id="btnSimpanSuratSehat"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        var modalObatPcare = $('#modalObatPcare')

        function obatPcare(noKunjungan, no_resep) {
            modalObatPcare.modal('show');
            $.get(`/efktp/resep/get`, {
                no_resep: no_resep
            }).done((response) => {
                if (response.resep_dokter.length) {
                    const obatDokter = response.resep_dokter.map((items) => {
                        return {
                            'noKunjungan': noKunjungan,
                            'kdObat': items.obat.mapping_obat.kode_brng_pcare,
                            'signa1': items.aturan_pakai.split(' x ')[0],
                            'signa2': items.aturan_pakai.split(' x ')[1],
                            'racikan': 0,
                            'kdRacikan': null,
                            'obatDPHO': 1,
                            'jmlObat': items.jml,
                            'jmlPermintaan': 0,
                            'nmObatNonDPHO': items.obat.nama_brng,
                        };
                    });
                    console.log('OBAT ===', obatDokter);
                    $.post(`/efktp/bridging/pcare/obat`, {
                        data: obatDokter
                    }).done((response) => {
                        console.log('RESPONSE ==', response);
                    })
                    // console.log('RESPONSE ==', obatDokter);
                    // console.log('RESEP ==', response);
                }
            })
        }
    </script>
@endpush

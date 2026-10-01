<div class="modal modal-blur fade" id="modalCetakEtiket" tabindex="-1" aria-modal="false" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header bg-light py-2">
                <div>
                    <h5 class="modal-title m-0 d-flex align-items-center gap-2">
                        <i class="ti ti-printer text-primary fs-3"></i>
                        <span>Cetak Etiket Obat</span>
                        <span class="badge bg-primary-lt" id="badgeNoResepEtiket">-</span>
                    </h5>
                    <small class="text-muted" id="subTitleEtiketPasien">-</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3 align-items-center">
                    <!-- Pilihan Ukuran Label -->
                    <div class="col-md-7">
                        <label class="form-label fw-bold mb-1 d-flex align-items-center gap-1">
                            <i class="ti ti-dimensions text-secondary"></i> Pilih Ukuran Label:
                        </label>
                        <div class="btn-group w-100" role="group" id="groupUkuranEtiket">
                            <input type="radio" class="btn-check" name="etiket_ukuran" id="ukuran_8x6" value="8x6" autocomplete="off">
                            <label class="btn btn-sm btn-outline-primary" for="ukuran_8x6" title="8 x 6 cm (Stiker Besar)">8 x 6 cm</label>

                            <input type="radio" class="btn-check" name="etiket_ukuran" id="ukuran_8x5" value="8x5" autocomplete="off" checked>
                            <label class="btn btn-sm btn-outline-primary" for="ukuran_8x5" title="8 x 5 cm (Standar)">8 x 5 cm</label>

                            <input type="radio" class="btn-check" name="etiket_ukuran" id="ukuran_7x5" value="7x5" autocomplete="off">
                            <label class="btn btn-sm btn-outline-primary" for="ukuran_7x5" title="7 x 5 cm (Sedang)">7 x 5 cm</label>

                            <input type="radio" class="btn-check" name="etiket_ukuran" id="ukuran_5x3" value="5x3" autocomplete="off">
                            <label class="btn btn-sm btn-outline-primary" for="ukuran_5x3" title="5 x 3 cm (Kecil / Klip)">5 x 3 cm</label>
                        </div>
                    </div>

                    <!-- Pilihan Item Obat -->
                    <div class="col-md-5">
                        <label class="form-label fw-bold mb-1 d-flex align-items-center gap-1">
                            <i class="ti ti-pill text-secondary"></i> Pilih Item Obat:
                        </label>
                        <select class="form-select form-select-sm" id="selectEtiketItem">
                            <option value="all">Semua Obat & Racikan</option>
                        </select>
                    </div>
                </div>

                <!-- Preview Area -->
                <div class="position-relative border rounded-2 overflow-hidden bg-light" style="height: 440px;">
                    <div id="loadingEtiketPreview" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-white bg-opacity-75" style="z-index: 5;">
                        <div class="spinner-border text-primary" role="status"></div>
                        <span class="mt-2 text-muted small">Memuat pratinjau etiket...</span>
                    </div>
                    <iframe id="iframeEtiketPreview" class="w-100 h-100 border-0" src="about:blank"></iframe>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between py-2">
                <div class="text-muted small">
                    <i class="ti ti-info-circle me-1"></i>Format PDF per-label untuk thermal printer
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Tutup
                    </button>
                    <a id="btnBukaTabEtiket" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-external-link me-1"></i>Tab Baru
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" onclick="printEtiketDirect()">
                        <i class="ti ti-printer me-1"></i>Cetak Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    const modalCetakEtiketElement = $('#modalCetakEtiket');
    let currentEtiketNoResep = '';

    function modalCetakEtiket(no_resep) {
        if (!no_resep) return;
        currentEtiketNoResep = no_resep;
        $('#badgeNoResepEtiket').text(no_resep);
        $('#subTitleEtiketPasien').text('Memuat data pasien...');
        
        // Reset item selector
        const selectItem = $('#selectEtiketItem');
        selectItem.html('<option value="all">Semua Obat & Racikan</option>');
        
        // Load data resep untuk dropdown pilihan obat
        $.get(`{{ url('/farmasi/resep/get') }}`, {
            no_resep: no_resep
        }).done((response) => {
            if (response && response.reg_periksa && response.reg_periksa.pasien) {
                const pas = response.reg_periksa.pasien;
                $('#subTitleEtiketPasien').text(`Pasien: ${pas.nm_pasien} (RM: ${pas.no_rkm_medis})`);
            }

            // Populate non-racik
            if (response.resep_dokter && response.resep_dokter.length > 0) {
                const optGroupUmum = $('<optgroup label="Obat Non-Racik"></optgroup>');
                response.resep_dokter.forEach((item) => {
                    const nama = item.obat ? item.obat.nama_brng : item.kode_brng;
                    optGroupUmum.append(`<option value="umum_${item.kode_brng}">${nama} (${item.jml} ${item.obat && item.obat.satuan ? item.obat.satuan.satuan : 'TAB'})</option>`);
                });
                selectItem.append(optGroupUmum);
            }

            // Populate racikan
            if (response.resep_racikan && response.resep_racikan.length > 0) {
                const optGroupRacik = $('<optgroup label="Obat Racikan"></optgroup>');
                response.resep_racikan.forEach((item) => {
                    const metode = item.metode ? item.metode.nm_racik : 'Racik';
                    optGroupRacik.append(`<option value="racik_${item.no_racik}">${metode} ${item.nama_racik} (${item.jml_dr} Bks)</option>`);
                });
                selectItem.append(optGroupRacik);
            }

            loadEtiketPdfPreview();
        }).fail(() => {
            loadEtiketPdfPreview();
        });

        modalCetakEtiketElement.modal('show');
    }

    function getSelectedUkuranEtiket() {
        return $('input[name="etiket_ukuran"]:checked').val() || '8x5';
    }

    function buildEtiketUrl() {
        const ukuran = getSelectedUkuranEtiket();
        const itemVal = $('#selectEtiketItem').val() || 'all';
        let url = `{{ url('/farmasi/resep/etiket') }}/${encodeURIComponent(currentEtiketNoResep)}?ukuran=${ukuran}`;

        if (itemVal !== 'all') {
            const parts = itemVal.split('_');
            if (parts.length >= 2) {
                url += `&item_type=${parts[0]}&item_key=${encodeURIComponent(parts.slice(1).join('_'))}`;
            }
        }
        return url;
    }

    function loadEtiketPdfPreview() {
        if (!currentEtiketNoResep) return;
        $('#loadingEtiketPreview').removeClass('d-none');
        const url = buildEtiketUrl();
        $('#btnBukaTabEtiket').attr('href', url);

        const iframe = document.getElementById('iframeEtiketPreview');
        iframe.onload = function() {
            $('#loadingEtiketPreview').addClass('d-none');
        };
        iframe.src = url;
    }

    // Change handlers
    $('input[name="etiket_ukuran"]').on('change', function() {
        loadEtiketPdfPreview();
    });

    $('#selectEtiketItem').on('change', function() {
        loadEtiketPdfPreview();
    });

    function printEtiketDirect() {
        const iframe = document.getElementById('iframeEtiketPreview');
        if (iframe && iframe.contentWindow) {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } catch (e) {
                // Fallback jika iframe print dicegah oleh browser
                window.open(buildEtiketUrl(), '_blank');
            }
        } else {
            window.open(buildEtiketUrl(), '_blank');
        }
    }

    function openModalEtiketFromDetail() {
        if (typeof currentDetailNoResep !== 'undefined' && currentDetailNoResep) {
            modalCetakEtiket(currentDetailNoResep);
        } else {
            const activeNoResep = $('#modalDetailResep').data('no_resep');
            if (activeNoResep) {
                modalCetakEtiket(activeNoResep);
            }
        }
    }

    modalCetakEtiketElement.on('hidden.bs.modal', function () {
        $('#iframeEtiketPreview').attr('src', 'about:blank');
    });
</script>
@endpush

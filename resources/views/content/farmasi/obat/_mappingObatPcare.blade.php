<div class="modal modal-blur fade" id="modalMappingObatPcare" tabindex="-1" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bold d-flex align-items-center text-white">
                    <i class="ti ti-link me-2 fs-2"></i> Mapping Obat BPJS PCare
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Info Obat SIMRS Card -->
                <div class="card bg-blue-lt border-blue mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="avatar bg-primary text-white rounded p-2 flex-shrink-0" style="width: 45px; height: 45px;">
                                <i class="ti ti-pill fs-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-uppercase text-muted fw-bold small mb-1">Obat SIMRS (Lokal)</div>
                                <h3 class="mb-1 text-primary fw-bold" id="modalMappingNamaObatSimrs">-</h3>
                                <div class="d-flex flex-wrap gap-3 small text-secondary mt-2">
                                    <div><i class="ti ti-barcode me-1 text-primary"></i> Kode: <strong id="modalMappingKodeObatSimrs" class="text-dark">-</strong></div>
                                    <div><i class="ti ti-scale me-1 text-primary"></i> Dosis: <strong id="modalMappingDosisObatSimrs" class="text-dark">-</strong></div>
                                    <div><i class="ti ti-box me-1 text-primary"></i> Satuan: <strong id="modalMappingSatuanObatSimrs" class="text-dark">-</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Mapping Currently -->
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1">Status Mapping PCare Saat Ini</label>
                    <div id="modalMappingCurrentStatus" class="p-2 rounded bg-light border">
                        <span class="badge bg-secondary-lt"><i class="ti ti-info-circle me-1"></i> Belum Di-mapping</span>
                    </div>
                </div>

                <!-- Form Search PCare Obat DPHO -->
                <form id="formMappingObatPcare" onsubmit="return false;">
                    @csrf
                    <input type="hidden" id="modalMappingInputKodeBrng">
                    
                    <div class="mb-2">
                        <label class="form-label required fw-bold text-dark">PILIH / CARI OBAT PCARE (DPHO BPJS)</label>
                        <select class="form-select" id="selectModalObatPcare" style="width: 100%;"></select>
                        <div class="form-text text-muted mt-1">
                            <i class="ti ti-info-circle me-1"></i> Ketik nama obat atau zat aktif untuk mencari referensi DPHO BPJS PCare.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger d-none" id="btnModalHapusMapping" onclick="executeDeleteMappingObatPcare()">
                    <i class="ti ti-trash me-1"></i> Hapus Mapping
                </button>
                <button type="button" class="btn btn-primary" id="btnModalSimpanMapping" onclick="executeSimpanMappingObatPcare()">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Mapping
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        const modalMappingObatPcare = $('#modalMappingObatPcare');

        function openModalMappingObatPcare(kodeBrng, namaBrng, kapasitas, satuan, currentPcareKode, currentPcareNama) {
            $('#modalMappingInputKodeBrng').val(kodeBrng);
            $('#modalMappingNamaObatSimrs').text(namaBrng || '-');
            $('#modalMappingKodeObatSimrs').text(kodeBrng || '-');
            $('#modalMappingDosisObatSimrs').text((kapasitas && kapasitas != '0') ? kapasitas : '-');
            $('#modalMappingSatuanObatSimrs').text(satuan || '-');

            const statusContainer = $('#modalMappingCurrentStatus');
            const btnHapus = $('#btnModalHapusMapping');

            if (currentPcareKode && currentPcareKode !== 'null' && currentPcareKode !== '') {
                statusContainer.html(`
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-success me-2"><i class="ti ti-check me-1"></i> Mapped</span>
                            <strong class="text-dark">${currentPcareNama}</strong> <span class="text-muted">(${currentPcareKode})</span>
                        </div>
                    </div>
                `);
                btnHapus.removeClass('d-none');
            } else {
                statusContainer.html(`<span class="badge bg-secondary-lt"><i class="ti ti-alert-circle me-1"></i> Belum Di-mapping ke BPJS PCare</span>`);
                btnHapus.addClass('d-none');
            }

            const select = $('#selectModalObatPcare');
            select.empty();

            if (currentPcareKode && currentPcareNama) {
                select.append(new Option(`${currentPcareNama} (${currentPcareKode})`, currentPcareKode, true, true));
            }

            select.select2({
                dropdownParent: $('#modalMappingObatPcare'),
                width: '100%',
                placeholder: 'Ketik nama obat / DPHO BPJS...',
                allowClear: true,
                ajax: {
                    url: (params) => {
                        const keyword = params.term || (namaBrng || '').split(' ')[0].substring(0, 5) || 'a';
                        return `{{ url('/bridging/pcare/obat') }}/${keyword}`;
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        if (!data || !data.response || !data.response.list) {
                            return { results: [] };
                        }
                        return {
                            results: data.response.list.map(function (item) {
                                return {
                                    id: item.kdObat,
                                    text: `${item.nmObat} (${item.kdObat})`
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Initial auto-search if not mapped yet
            if (!currentPcareKode) {
                const searchKeyword = (namaBrng || '').split(' ')[0].substring(0, 5) || 'a';
                $.get(`{{ url('/bridging/pcare/obat') }}/${searchKeyword}`).done((data) => {
                    if (data && data.response && data.response.list) {
                        const options = data.response.list.map(item => `<option value="${item.kdObat}">${item.nmObat} (${item.kdObat})</option>`);
                        select.empty().append('<option value="">-- Pilih Obat DPHO PCare --</option>').append(options).trigger('change');
                    }
                });
            }

            modalMappingObatPcare.modal('show');
        }

        function executeSimpanMappingObatPcare() {
            const kodeBrng = $('#modalMappingInputKodeBrng').val();
            const select = $('#selectModalObatPcare');
            const selectedVal = select.val();
            const selectedData = select.select2('data');

            if (!selectedVal || !selectedData || !selectedData.length || !selectedData[0].id) {
                showToast('Silahkan pilih obat PCare terlebih dahulu', 'warning');
                return;
            }

            const kodeObatPcare = selectedData[0].id;
            const namaObatPcare = selectedData[0].text;

            loadingAjax('Menyimpan mapping obat PCare...');
            $.post(`{{ url('/mapping/pcare/obat') }}`, {
                kode_brng: kodeBrng,
                kode: kodeObatPcare,
                nama: namaObatPcare,
                _token: '{{ csrf_token() }}'
            }).done((res) => {
                Swal.close();
                showToast('Berhasil menyimpan mapping obat PCare', 'success');
                modalMappingObatPcare.modal('hide');
                if (typeof tabelBarangObat !== 'undefined') {
                    tabelBarangObat.DataTable().ajax.reload(null, false);
                }
            }).fail((err) => {
                Swal.close();
                alertErrorAjax(err);
            });
        }

        function executeDeleteMappingObatPcare() {
            const kodeBrng = $('#modalMappingInputKodeBrng').val();
            const namaObat = $('#modalMappingNamaObatSimrs').text();

            Swal.fire({
                title: 'Hapus Mapping?',
                html: `Yakin ingin menghapus mapping PCare untuk obat <b>${namaObat}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus Mapping',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus mapping...');
                    $.post(`{{ url('/mapping/pcare/obat/delete') }}/${kodeBrng}`, {
                        _token: '{{ csrf_token() }}'
                    }).done((res) => {
                        Swal.close();
                        showToast('Mapping berhasil dihapus', 'success');
                        modalMappingObatPcare.modal('hide');
                        if (typeof tabelBarangObat !== 'undefined') {
                            tabelBarangObat.DataTable().ajax.reload(null, false);
                        }
                    }).fail((err) => {
                        Swal.close();
                        alertErrorAjax(err);
                    });
                }
            });
        }
    </script>
@endpush

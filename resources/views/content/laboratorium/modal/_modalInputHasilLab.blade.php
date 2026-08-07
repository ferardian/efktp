<div class="modal modal-blur fade" id="modalInputHasilLab" tabindex="-1" role="dialog" aria-labelledby="modalInputHasilLab" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title mb-0"><i class="ti ti-report-medical me-2"></i>Input Hasil Pemeriksaan Laboratorium</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <form id="formInputHasilLab">
                    <input type="hidden" name="noorder" id="inputLabNoorder">
                    <input type="hidden" name="no_rawat" id="inputLabNoRawat">

                    <!-- Info Pasien -->
                    <div class="card mb-3 bg-muted-lt">
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label mb-0 fw-bold">No. Order / Rawat</label>
                                    <span id="labelOrderRawat" class="text-primary fw-bold d-block"></span>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label mb-0 fw-bold">Pasien</label>
                                    <span id="labelNamaPasien" class="d-block"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label mb-0 fw-bold">Poliklinik / Perujuk</label>
                                    <span id="labelPoliPerujuk" class="d-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Officer & Datetime selections -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label required">Tgl Hasil</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_hasil" id="inputTglHasil" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Jam Hasil</label>
                            <input type="time" class="form-control form-control-sm" name="jam_hasil" id="inputJamHasil" value="{{ date('H:i:s') }}" step="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Dokter Penanggung Jawab</label>
                            <select class="form-select form-select-sm" name="kd_dokter" id="selectDokterLab" required>
                                <option value="">-- Pilih Dokter --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Petugas Lab</label>
                            <select class="form-select form-select-sm" name="nip" id="selectPetugasLab" required>
                                <option value="">-- Pilih Petugas --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Items table -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover align-middle mb-0" id="tableItemInputHasil">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th width="20%">Paket Periksa</th>
                                    <th width="25%">Item Detail</th>
                                    <th width="22%">Hasil</th>
                                    <th width="18%">Nilai Rujukan</th>
                                    <th width="15%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Memuat item pemeriksaan...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-success btn-sm" id="btnSimpanHasilLab">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Hasil & Posting Jurnal
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    const modalInputHasilLab = $('#modalInputHasilLab');
    const formInputHasilLab = $('#formInputHasilLab');
    const tableItemInputHasil = $('#tableItemInputHasil');

    function openModalInputHasilLab(noorder) {
        modalInputHasilLab.modal('show');
        tableItemInputHasil.find('tbody').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat item...</td></tr>');
        
        $.get(`{{ url('/lab/permintaan/form-hasil') }}/${noorder}`).done((res) => {
            if (!res.status) {
                alertErrorAjax(res.message || 'Gagal memuat data');
                return;
            }

            const { permintaan, details, dokters, petugas } = res;
            $('#inputLabNoorder').val(permintaan.noorder);
            $('#inputLabNoRawat').val(permintaan.no_rawat);

            $('#labelOrderRawat').text(`${permintaan.noorder} / ${permintaan.no_rawat}`);
            $('#labelNamaPasien').text(`${permintaan.pasien?.nm_pasien} (${permintaan.pasien?.jk})`);
            $('#labelPoliPerujuk').text(`${permintaan.poliklinik?.nm_poli || '-'} / ${permintaan.perujuk?.nm_dokter || '-'}`);

            // Populate Dokter select
            let optDokter = '<option value="">-- Pilih Dokter --</option>';
            dokters.forEach((d) => {
                const selected = (d.kd_dokter === permintaan.dokter_perujuk) ? 'selected' : '';
                optDokter += `<option value="${d.kd_dokter}" ${selected}>${d.nm_dokter}</option>`;
            });
            $('#selectDokterLab').html(optDokter);

            // Populate Petugas select
            let optPetugas = '<option value="">-- Pilih Petugas --</option>';
            petugas.forEach((p) => {
                optPetugas += `<option value="${p.nip}">${p.nama} (${p.nip})</option>`;
            });
            $('#selectPetugasLab').html(optPetugas);

            // Group items by kd_jenis_prw / nm_perawatan
            const grouped = details.reduce((acc, item) => {
                const key = item.nm_perawatan;
                if (!acc[key]) acc[key] = [];
                acc[key].push(item);
                return acc;
            }, {});

            let tbodyHtml = '';
            Object.keys(grouped).forEach((nmPerawatan) => {
                const items = grouped[nmPerawatan];
                items.forEach((item, index) => {
                    const rowClass = index === 0 ? 'border-top' : '';
                    tbodyHtml += `
                        <tr class="${rowClass}">
                            ${index === 0 ? `<td rowspan="${items.length}" class="align-middle fw-bold bg-light">${nmPerawatan}</td>` : ''}
                            <td class="align-middle">
                                <div class="fw-semibold">${item.item_nama}</div>
                                <input type="hidden" class="item-kd-prw" value="${item.kd_jenis_prw}">
                                <input type="hidden" class="item-id-template" value="${item.id_template}">
                                <input type="hidden" class="item-nilai-rujukan" value="${item.nilai_rujukan}">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm item-nilai" value="${item.nilai_existing || ''}" placeholder="Masukkan Nilai">
                            </td>
                            <td class="align-middle small text-muted text-center">${item.nilai_rujukan}</td>
                            <td>
                                <select class="form-select form-select-sm item-keterangan">
                                    <option value="-" ${item.keterangan_existing === '-' ? 'selected' : ''}>- (Normal)</option>
                                    <option value="L" ${item.keterangan_existing === 'L' ? 'selected' : ''}>L (Low / Rendah)</option>
                                    <option value="H" ${item.keterangan_existing === 'H' ? 'selected' : ''}>H (High / Tinggi)</option>
                                </select>
                            </td>
                        </tr>
                    `;
                });
            });

            if (Object.keys(grouped).length === 0) {
                tbodyHtml = '<tr><td colspan="5" class="text-center text-muted py-3">Belum ada template detail permintaan.</td></tr>';
            }

            tableItemInputHasil.find('tbody').html(tbodyHtml);
        }).fail((err) => {
            alertErrorAjax(err);
        });
    }

    $('#btnSimpanHasilLab').on('click', function() {
        const noorder = $('#inputLabNoorder').val();
        const no_rawat = $('#inputLabNoRawat').val();
        const tgl_hasil = $('#inputTglHasil').val();
        const jam_hasil = $('#inputJamHasil').val();
        const kd_dokter = $('#selectDokterLab').val();
        const nip = $('#selectPetugasLab').val();

        if (!kd_dokter) {
            Swal.fire('Peringatan', 'Silahkan pilih Dokter Penanggung Jawab', 'warning');
            return;
        }
        if (!nip) {
            Swal.fire('Peringatan', 'Silahkan pilih Petugas Lab', 'warning');
            return;
        }

        const detail_hasil = [];
        tableItemInputHasil.find('tbody tr').each(function() {
            const kd_jenis_prw = $(this).find('.item-kd-prw').val();
            const id_template = $(this).find('.item-id-template').val();
            const nilai_rujukan = $(this).find('.item-nilai-rujukan').val();
            const nilai = $(this).find('.item-nilai').val();
            const keterangan = $(this).find('.item-keterangan').val();

            if (kd_jenis_prw && id_template) {
                detail_hasil.push({
                    kd_jenis_prw: kd_jenis_prw,
                    id_template: id_template,
                    nilai_rujukan: nilai_rujukan,
                    nilai: nilai || '',
                    keterangan: keterangan || '-'
                });
            }
        });

        if (detail_hasil.length === 0) {
            Swal.fire('Peringatan', 'Tidak ada item hasil laboratorium untuk disimpan.', 'warning');
            return;
        }

        const payload = {
            noorder: noorder,
            no_rawat: no_rawat,
            tgl_hasil: tgl_hasil,
            jam_hasil: jam_hasil,
            kd_dokter: kd_dokter,
            nip: nip,
            detail_hasil: detail_hasil
        };

        if (document.activeElement) document.activeElement.blur();

        loadingAjax('Saving lab results & posting journal entries...');
        $.ajax({
            url: `{{ url('/lab/permintaan/hasil') }}`,
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                Swal.close();
                if (res.status) {
                    modalInputHasilLab.modal('hide');
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        if (typeof loadTablePermintaan === 'function') {
                            loadTablePermintaan();
                        }
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan hasil lab', 'error');
                }
            },
            error: function(err) {
                Swal.close();
                alertErrorAjax(err);
            }
        });
    });
</script>
@endpush

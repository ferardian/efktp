<div class="row g-3">
    <!-- Form Tambah Permintaan UDD -->
    <div class="col-12">
        <div class="card border shadow-sm">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 text-dark d-flex align-items-center gap-2">
                    <i class="ti ti-clock-check text-primary"></i>
                    <span>Form Permintaan Obat Harian (UDD / 24 Jam)</span>
                </h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnRefreshUddRanap">
                    <i class="ti ti-refresh me-1"></i> Refresh Data
                </button>
            </div>
            <div class="card-body p-3">
                <form id="formPermintaanUddRanap">
                    <div class="row g-2 mb-2">
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label required small mb-1 fw-bold">Pilih Obat / BHP</label>
                            <select class="form-select" id="selectBarangUdd" style="width: 100%"></select>
                            <div id="infoStokObatUdd" class="small text-muted mt-1 d-none d-flex gap-2 align-items-center">
                                <span class="badge bg-blue-lt"><i class="ti ti-info-circle me-1"></i> Satuan: <span id="lblSatuanUdd">-</span></span>
                                <span class="badge bg-success-lt"><i class="ti ti-box me-1"></i> Stok: <span id="lblStokUdd">-</span></span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label required small mb-1 fw-bold">Qty (Jml Dosis 24 Jam)</label>
                            <input type="number" class="form-control text-center fw-bold" id="jml_udd" min="1" step="any" placeholder="0">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label small mb-1 fw-bold">Aturan Pakai</label>
                            <input type="text" class="form-control" id="aturan_pakai_udd" placeholder="Contoh: 3 x 1 tablet sesudah makan">
                        </div>
                    </div>

                    <!-- Jadwal Jam Pemberian Obat (24 Jam) -->
                    <!-- Jadwal Jam Pemberian Obat (24 Jam) -->
                    <div class="border rounded p-2 mb-3 bg-light-subtle">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                            <label class="form-label small fw-bold m-0 text-dark">
                                <i class="ti ti-clock me-1 text-primary"></i> Jadwal Jam Pemberian Obat ke Pasien (24 Jam):
                            </label>
                            <!-- Quick Presets -->
                            <div class="d-inline-flex gap-1 flex-wrap align-items-center">
                                <span class="small text-muted me-1 fw-semibold">Preset Cepat:</span>
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" onclick="setPresetJamUdd([6, 14, 22], '3 x 1')">
                                    <i class="ti ti-clock me-1"></i>3x1 (Jam 06:00, 14:00, 22:00)
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" onclick="setPresetJamUdd([6, 18], '2 x 1')">
                                    <i class="ti ti-clock me-1"></i>2x1 (Jam 06:00, 18:00)
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" onclick="setPresetJamUdd([6], '1 x 1 Pagi')">
                                    <i class="ti ti-sun me-1 text-warning"></i>1x1 Pagi (Jam 06:00)
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" onclick="setPresetJamUdd([22], '1 x 1 Malam')">
                                    <i class="ti ti-moon me-1 text-primary"></i>1x1 Malam (Jam 22:00)
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" onclick="setPresetJamUdd([6, 12, 18, 0], '4 x 1')">
                                    <i class="ti ti-clock me-1"></i>4x1 (Tiap 6 Jam: 06, 12, 18, 24)
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" onclick="clearPresetJamUdd()">
                                    <i class="ti ti-rotate me-1"></i>Reset Jam
                                </button>
                            </div>
                        </div>

                        <div class="small text-muted mb-2">
                            <i class="ti ti-info-circle me-1 text-info"></i>
                            Pilih jam pemberian obat kepada pasien rawat inap (sesuai shift dinas perawat). Klik tombol preset cepat di atas atau centang kotak jam secara manual:
                        </div>

                        <!-- 24 Hours Checkboxes Grid -->
                        <div class="d-flex flex-wrap gap-1" id="gridJamUdd">
                            @for ($h = 0; $h < 24; $h++)
                                @php $jamStr = str_pad($h, 2, '0', STR_PAD_LEFT); @endphp
                                <label class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center py-1 px-2 mb-0" style="font-size: 0.75rem;">
                                    <input type="checkbox" class="form-check-input me-1 check-jam-udd" value="jam{{ $jamStr }}" id="chk_jam_{{ $jamStr }}">
                                    <span>Jam {{ $jamStr }}:00</span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-success d-inline-flex align-items-center gap-1" id="btnSimpanUddRanap">
                            <i class="ti ti-send"></i><span>Kirim Permintaan UDD ke Farmasi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Permintaan UDD Pasien Ini -->
    <div class="col-12">
        <div class="card border shadow-sm">
            <div class="card-header bg-light py-2">
                <h5 class="card-title m-0 text-dark d-flex align-items-center gap-2">
                    <i class="ti ti-list-check text-primary"></i>
                    <span>Riwayat Permintaan Obat UDD Pasien Ini</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover align-middle mb-0" id="tbDaftarUddRanap">
                        <thead class="table-light">
                            <tr>
                                <th width="12%">No. Permintaan</th>
                                <th width="12%">Waktu</th>
                                <th width="26%">Nama Obat</th>
                                <th width="8%" class="text-center">Jml</th>
                                <th width="15%">Aturan Pakai</th>
                                <th width="15%">Jadwal Jam</th>
                                <th width="8%" class="text-center">Status</th>
                                <th width="4%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Memuat riwayat permintaan UDD...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    var selectBarangUdd = $();

    $(document).ready(function() {
        selectBarangUdd = $('#selectBarangUdd');

        // Initialize Select2 Barang
        selectBarangUdd.select2({
            placeholder: 'Ketik nama / kode obat...',
            minimumInputLength: 1,
            allowClear: true,
            dropdownParent: $('#modalCpptRanap'),
            ajax: {
                url: `{{ url('/barang/cari') }}`,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        kd_bangsal: 'AP'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.kode_brng,
                                text: item.text || (item.nama_brng + ' [' + (item.kode_sat || '') + '] - Stok: ' + (item.stok ?? 0)),
                                nama_brng: item.nama_brng,
                                kode_sat: item.kode_sat,
                                satuan: item.kode_sat || '',
                                stok: item.stok ?? 0
                            };
                        })
                    };
                },
                cache: true
            }
        });

        selectBarangUdd.on('select2:select', function(e) {
            const data = e.params.data;
            $('#lblSatuanUdd').text(data.satuan || '-');
            $('#lblStokUdd').text(data.stok ?? '-');
            $('#infoStokObatUdd').removeClass('d-none');
            $('#jml_udd').focus();
        });

        selectBarangUdd.on('select2:clear', function() {
            $('#infoStokObatUdd').addClass('d-none');
        });

        // Trigger load saat tab UDD dibuka
        $('a[href="#tabs-permintaan-udd"]').on('shown.bs.tab', function() {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            if (noRawat && noRawat !== '-') {
                loadPermintaanUddRanap(noRawat);
            }
        });

        // Refresh Button
        $('#btnRefreshUddRanap').on('click', function() {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            if (noRawat && noRawat !== '-') {
                loadPermintaanUddRanap(noRawat);
            }
        });

        // Simpan Permintaan UDD
        $('#btnSimpanUddRanap').on('click', function() {
            const noRawat = formCpptRanap.find('input[name="no_rawat"]').val();
            const kodeBrng = selectBarangUdd.val();
            const jml = parseFloat($('#jml_udd').val());
            const aturanPakai = $('#aturan_pakai_udd').val();
            const kdDokter = formCpptRanap.find('[name="nip"]').val() || '';

            if (!noRawat || noRawat === '-') {
                return Swal.fire('Perhatian', 'No. Rawat pasien tidak valid', 'warning');
            }
            if (!kodeBrng) {
                return Swal.fire('Perhatian', 'Pilih obat terlebih dahulu', 'warning');
            }
            if (!jml || jml <= 0) {
                return Swal.fire('Perhatian', 'Masukkan jumlah obat dosis 24 jam yang valid', 'warning');
            }

            // Kumpulkan jam
            const jamValues = {};
            let hasCheckedJam = false;
            $('.check-jam-udd').each(function() {
                const key = $(this).val();
                const isChecked = $(this).is(':checked');
                jamValues[key] = isChecked ? 'true' : 'false';
                if (isChecked) hasCheckedJam = true;
            });

            if (!hasCheckedJam) {
                return Swal.fire('Perhatian', 'Pilih minimal satu jam pemberian obat pada jadwal 24 jam', 'warning');
            }

            const item = Object.assign({
                kode_brng: kodeBrng,
                jml: jml,
                aturan_pakai: aturanPakai
            }, jamValues);

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...');

            $.post(`{{ url('/permintaan-stok-obat/simpan') }}`, {
                _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                no_rawat: noRawat,
                kd_dokter: kdDokter,
                items: [item]
            }).done((res) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reset form
                selectBarangUdd.val(null).trigger('change');
                $('#jml_udd').val('');
                $('#aturan_pakai_udd').val('');
                clearPresetJamUdd();
                loadPermintaanUddRanap(noRawat);
            }).fail((err) => {
                alertErrorAjax(err);
            }).always(() => {
                btn.prop('disabled', false).html('<i class="ti ti-send me-1"></i> Kirim Permintaan UDD ke Farmasi');
            });
        });
    });

    // Preset helper
    function setPresetJamUdd(hoursArray, aturanText) {
        clearPresetJamUdd();
        hoursArray.forEach(h => {
            const jamStr = String(h).padStart(2, '0');
            const chk = $(`#chk_jam_${jamStr}`);
            chk.prop('checked', true);
            chk.closest('label').removeClass('btn-outline-secondary').addClass('btn-primary active text-white');
        });
        if (aturanText && !$('#aturan_pakai_udd').val()) {
            $('#aturan_pakai_udd').val(aturanText);
        }
    }

    function clearPresetJamUdd() {
        $('.check-jam-udd').prop('checked', false);
        $('.check-jam-udd').closest('label').removeClass('btn-primary active text-white').addClass('btn-outline-secondary');
    }

    // Toggle styling on manual checkbox change
    $(document).on('change', '.check-jam-udd', function() {
        if ($(this).is(':checked')) {
            $(this).closest('label').removeClass('btn-outline-secondary').addClass('btn-primary active text-white');
        } else {
            $(this).closest('label').removeClass('btn-primary active text-white').addClass('btn-outline-secondary');
        }
    });

    // Load data tabel riwayat UDD pasien
    function loadPermintaanUddRanap(noRawat) {
        const tbody = $('#tbDaftarUddRanap tbody');
        tbody.html('<tr><td colspan="8" class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm me-2"></div>Memuat data permintaan UDD...</td></tr>');

        $.get(`{{ url('/permintaan-stok-obat/get') }}`, { no_rawat: noRawat })
            .done((data) => {
                tbody.empty();
                if (!data || data.length === 0) {
                    tbody.html('<tr><td colspan="8" class="text-center text-muted py-3">Belum ada riwayat permintaan obat UDD untuk pasien ini</td></tr>');
                    return;
                }

                data.forEach(p => {
                    const statusBadge = p.status === 'Sudah'
                        ? `<span class="badge bg-success-lt text-success" title="Divalidasi: ${p.tgl_validasi} ${p.jam_validasi}"><i class="ti ti-check"></i> Sudah Valid</span>`
                        : `<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock"></i> Menunggu</span>`;

                    const btnHapus = p.status === 'Sudah'
                        ? `<span class="badge bg-secondary-lt" title="Sudah divalidasi farmasi"><i class="ti ti-lock"></i></span>`
                        : `<button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus Permintaan" onclick="hapusPermintaanUdd('${p.no_permintaan}', '${noRawat}')"><i class="ti ti-trash"></i></button>`;

                    const items = p.items || [];
                    if (items.length === 0) {
                        tbody.append(`
                            <tr>
                                <td><strong>${p.no_permintaan}</strong></td>
                                <td><small>${p.tgl_permintaan}</small><br><small class="text-muted">${p.jam}</small></td>
                                <td colspan="4" class="text-muted fst-italic">Tidak ada rincian item obat</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">${btnHapus}</td>
                            </tr>
                        `);
                    } else {
                        items.forEach((item, idx) => {
                            // Format badges jam
                            let jamBadges = [];
                            for (let i = 0; i < 24; i++) {
                                const col = 'jam' + String(i).padStart(2, '0');
                                if (item[col] === 'true') {
                                    jamBadges.push(`<span class="badge bg-blue-lt px-1 py-0 me-1" style="font-size:0.7rem;">${String(i).padStart(2, '0')}:00</span>`);
                                }
                            }
                            const jamHtml = jamBadges.length ? jamBadges.join('') : '<small class="text-muted">-</small>';

                            tbody.append(`
                                <tr>
                                    <td>${idx === 0 ? `<strong>${p.no_permintaan}</strong>` : ''}</td>
                                    <td>${idx === 0 ? `<small>${p.tgl_permintaan}</small><br><small class="text-muted">${p.jam}</small>` : ''}</td>
                                    <td><span class="fw-bold text-dark">${item.nama_brng}</span></td>
                                    <td class="text-center fw-bold">${item.jml} <small class="text-muted">${item.satuan || ''}</small></td>
                                    <td><small class="text-muted">${item.aturan_pakai || '-'}</small></td>
                                    <td><div class="d-flex flex-wrap gap-1">${jamHtml}</div></td>
                                    <td class="text-center">${idx === 0 ? statusBadge : ''}</td>
                                    <td class="text-center">${idx === 0 ? btnHapus : ''}</td>
                                </tr>
                            `);
                        });
                    }
                });
            })
            .fail((err) => {
                tbody.html('<tr><td colspan="8" class="text-center text-danger py-3">Gagal memuat data: ' + (err.responseJSON?.message || err.statusText) + '</td></tr>');
            });
    }

    function hapusPermintaanUdd(noPermintaan, noRawat) {
        Swal.fire({
            title: 'Hapus Permintaan UDD?',
            html: `Apakah Anda yakin ingin membatalkan & menghapus nomor permintaan <strong>${noPermintaan}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/permintaan-stok-obat/hapus') }}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
                        no_permintaan: noPermintaan
                    },
                    success: function(res) {
                        toast(res.message);
                        loadPermintaanUddRanap(noRawat);
                    },
                    error: function(err) {
                        alertErrorAjax(err);
                    }
                });
            }
        });
    }
</script>
@endpush

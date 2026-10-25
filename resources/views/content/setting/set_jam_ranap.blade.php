@extends('layout')

@section('body')
    <div class="container-xl">
        <!-- Page Header -->
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title text-primary">
                        <i class="ti ti-clock-cog me-2"></i> Pengaturan Jam Minimal & Pengakuan Hari Rawat Inap
                    </h2>
                    <div class="text-muted mt-1 small">
                        Konfigurasi aturan pengakuan durasi 1 hari rawat inap dan batasan minimal jam rawat inap sesuai standar SIMRS Khanza (<code>set_jam_minimal</code>).
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ url('kamar/inap') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali ke Data Ranap
                    </a>
                </div>
            </div>
        </div>

        <div class="row row-cards">
            <!-- Form Pengaturan -->
            <div class="col-lg-7 col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h4 class="card-title mb-0">Form Konfigurasi Pengakuan Hari</h4>
                    </div>
                    <div class="card-body">
                        <form id="formSetJamRanap">
                            @csrf
                            
                            <!-- Jam Minimal Dihitung di Kamar -->
                            <div class="mb-3">
                                <label class="form-label required fw-bold">Jam Minimal Dihitung di Kamar (Jam)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg" id="lamajam" name="lamajam" 
                                           value="{{ $setting->lamajam ?? 24 }}" min="1" max="72" required>
                                    <span class="input-group-text bg-light fw-bold">Jam</span>
                                </div>
                                <small class="form-hint text-muted mt-1">
                                    Berapa jam minimal pasien harus dirawat sebelum diakui <b>1 hari</b> sewa kamar. <br>
                                    <span class="text-primary fw-semibold"><i class="ti ti-info-circle me-1"></i>Rekomendasi: 24 Jam</span> (Pasien baru diakui 1 hari setelah genap dirawat 24 jam).
                                </small>
                            </div>

                            <!-- Hitung Hari dari Awal Masuk -->
                            <div class="mb-4">
                                <label class="form-label required fw-bold">Hitung Hari dari Awal Masuk (Hari Awal)</label>
                                <div class="row g-2 align-items-stretch">
                                    <div class="col-md-6 d-flex">
                                        <label class="form-selectgroup-item w-100 h-100">
                                            <input type="radio" name="hariawal" value="No" class="form-selectgroup-input" 
                                                   {{ ($setting->hariawal ?? 'No') == 'No' ? 'checked' : '' }}>
                                            <span class="form-selectgroup-label d-flex align-items-center p-3 h-100 w-100">
                                                <i class="ti ti-hourglass-low fs-2 text-primary me-2 flex-shrink-0"></i>
                                                <span class="text-start">
                                                    <span class="font-weight-bold d-block text-dark">No (Setelah Jam Minimal)</span>
                                                    <span class="small text-muted">Sebelum <span class="badge-jam">24</span> jam = 0 Hari. Genap <span class="badge-jam">24</span> jam = 1 Hari.</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 d-flex">
                                        <label class="form-selectgroup-item w-100 h-100">
                                            <input type="radio" name="hariawal" value="Yes" class="form-selectgroup-input" 
                                                   {{ ($setting->hariawal ?? 'No') == 'Yes' ? 'checked' : '' }}>
                                            <span class="form-selectgroup-label d-flex align-items-center p-3 h-100 w-100">
                                                <i class="ti ti-calendar-plus fs-2 text-success me-2 flex-shrink-0"></i>
                                                <span class="text-start">
                                                    <span class="font-weight-bold d-block text-dark">Yes (Langsung Dihitung)</span>
                                                    <span class="small text-muted">Hari pertama langsung diakui 1 Hari sejak menit pertama masuk.</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3 text-muted">

                            <!-- Parameter Tambahan Khanza -->
                            <h5 class="text-secondary mb-3"><i class="ti ti-settings me-1"></i> Opsi Parameter Lanjutan</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Wajibkan Diagnosa Akhir Saat Pulang</label>
                                    <select class="form-select" name="diagnosaakhir" id="diagnosaakhir">
                                        <option value="Yes" {{ ($setting->diagnosaakhir ?? 'Yes') == 'Yes' ? 'selected' : '' }}>Yes (Wajib diisi)</option>
                                        <option value="No" {{ ($setting->diagnosaakhir ?? 'Yes') == 'No' ? 'selected' : '' }}>No (Boleh kosong)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Biaya Ranap Gabung Bayi (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="bayi" id="bayi" 
                                               value="{{ $setting->bayi ?? 0 }}" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Fee Perujuk Ranap (IDR)</label>
                                    <input type="number" class="form-control" name="feeperujuk" id="feeperujuk" 
                                           value="{{ $setting->feeperujuk ?? 0 }}" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ijinkan User Merubah Status Kamar</label>
                                    <select class="form-select" name="ubah_status_kamar" id="ubah_status_kamar">
                                        <option value="Yes" {{ ($setting->ubah_status_kamar ?? 'Yes') == 'Yes' ? 'selected' : '' }}>Yes (Boleh diubah)</option>
                                        <option value="No" {{ ($setting->ubah_status_kamar ?? 'Yes') == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary px-4 shadow-sm" id="btnSimpanSetting" onclick="simpanSetting()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan Perubahan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Simulasi & Panduan Aturan -->
            <div class="col-lg-5 col-md-12">
                <!-- Simulasi Interaktif -->
                <div class="card shadow-sm border-0 mb-3 bg-primary-subtle border-primary-subtle">
                    <div class="card-body">
                        <h4 class="card-title text-primary d-flex align-items-center">
                            <i class="ti ti-calculator me-2"></i> Simulasi Pengakuan Hari Real-Time
                        </h4>
                        <p class="small text-muted mb-3">
                            Uji coba perhitungan durasi rawat inap berdasarkan jam minimal dan skema yang Anda pilih:
                        </p>

                        <div class="bg-white p-3 rounded-3 border mb-3">
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-0">Tgl & Jam Masuk:</label>
                                    <div class="fw-bold fs-5 text-dark" id="sim_masuk">Hari ini 10:00</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-0">Tgl & Jam Sekarang/Keluar:</label>
                                    <div class="fw-bold fs-5 text-dark" id="sim_keluar">Hari ini 16:00 (6 Jam)</div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold text-secondary">Hasil Pengakuan Lama Rawat:</span>
                                <span class="badge bg-primary text-white fs-4 px-3 py-1" id="sim_hasil">0 Hari</span>
                            </div>
                        </div>

                        <div class="small text-muted">
                            <div class="fw-semibold text-dark mb-1"><i class="ti ti-bulb me-1 text-warning"></i>Contoh Skenario Perhitungan:</div>
                            <ul class="ps-3 mb-0">
                                <li><b>0 s.d <span class="badge-jam">24</span> Jam</b> (kurang dari batas jam) &rarr; <span class="badge bg-secondary-lt">0 Hari</span> (belum diakui 1 hari).</li>
                                <li><b>Genap <span class="badge-jam">24</span> s.d 47 Jam</b> &rarr; <span class="badge bg-success-lt">1 Hari</span>.</li>
                                <li><b>Genap 48 s.d 71 Jam</b> &rarr; <span class="badge bg-success-lt">2 Hari</span>, dst.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Informasi Teknis Sinkronisasi -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="card-title text-secondary">
                            <i class="ti ti-check-double me-2 text-success"></i> Dampak Integrasi Sistem
                        </h4>
                        <div class="text-muted small">
                            Aturan pengakuan jam rawat inap ini terhubung langsung secara otomatis ke:
                            <ol class="ps-3 mt-2 mb-0">
                                <li class="mb-1"><b>Tabel Pasien Rawat Inap:</b> Kolom <i>Lama</i> untuk pasien yang masih dirawat (Belum Pulang) diperbarui dinamis mengikuti jam berjalan.</li>
                                <li class="mb-1"><b>Formulir Pemulangan Pasien:</b> Lama rawat akhir langsung terisi otomatis sesuai selisih jam masuk dan keluar.</li>
                                <li class="mb-1"><b>Kasir Rawat Inap & Billing:</b> Tagihan tarif sewa kamar inap dikalikan durasi hari yang valid sesuai aturan ini.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            updateSimulation();

            $('#lamajam, input[name="hariawal"]').on('input change', function() {
                updateSimulation();
            });
        });

        function updateSimulation() {
            const jam = parseInt($('#lamajam').val()) || 24;
            const hariawal = $('input[name="hariawal"]:checked').val() || 'No';

            $('.badge-jam').text(jam);

            // Skenario simulasi durasi 6 jam
            let hasilHari = 0;
            if (hariawal === 'Yes') {
                hasilHari = 1; // Hari awal langsung 1
            } else {
                if (6 < jam) {
                    hasilHari = 0;
                } else {
                    hasilHari = Math.floor(6 / jam);
                }
            }

            $('#sim_hasil').text(`${hasilHari} Hari`);
            if (hasilHari === 0) {
                $('#sim_hasil').removeClass('bg-success').addClass('bg-secondary');
            } else {
                $('#sim_hasil').removeClass('bg-secondary').addClass('bg-success');
            }
        }

        function simpanSetting() {
            const lamajam = $('#lamajam').val();
            if (!lamajam || lamajam < 1) {
                showToast('Jam minimal harus diisi dan lebih dari 0', 'warning');
                return;
            }

            loadingAjax('Menyimpan pengaturan jam minimal...');

            $.ajax({
                url: "{{ url('/setting/jam-ranap') }}",
                type: "POST",
                data: $('#formSetJamRanap').serialize(),
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    showToast(response.message || 'Pengaturan berhasil diperbarui');
                    updateSimulation();
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Gagal menyimpan pengaturan', 'error');
                },
                complete: function() {
                    Swal.close();
                }
            });
        }
    </script>
@endpush

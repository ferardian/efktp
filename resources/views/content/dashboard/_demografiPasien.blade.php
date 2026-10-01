<div class="card h-100">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h4 class="card-title m-0 d-flex align-items-center">
            <i class="ti ti-users text-azure me-2 fs-2"></i> Karakteristik Pasien
        </h4>
        <span class="badge bg-azure-lt" id="badgeTotalDemografi">0 Pasien</span>
    </div>
    <div class="card-body p-3 position-relative d-flex flex-column justify-content-around" style="min-height: 280px;">
        <div id="loadingDemografi" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="text-muted small mt-1">Memuat data...</div>
        </div>

        {{-- 1. PASIEN BARU VS LAMA --}}
        <div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-bold text-uppercase">Tipe Pasien</span>
                <span class="small text-muted" id="labelTotalDaftar">0 Pasien</span>
            </div>
            <div class="row g-2 mb-2 text-center">
                <div class="col-6">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted small">
                            <i class="ti ti-user-plus text-success me-1"></i> Pasien Baru
                        </div>
                        <div class="h2 mb-0 mt-1 text-success fw-bold" id="valPasienBaru">0</div>
                        <span class="badge bg-success-lt small" id="persenPasienBaru">0%</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted small">
                            <i class="ti ti-user-check text-info me-1"></i> Pasien Lama
                        </div>
                        <div class="h2 mb-0 mt-1 text-info fw-bold" id="valPasienLama">0</div>
                        <span class="badge bg-info-lt small" id="persenPasienLama">0%</span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-success" id="barPasienBaru" role="progressbar" style="width: 0%" title="Baru"></div>
                <div class="progress-bar bg-info" id="barPasienLama" role="progressbar" style="width: 0%" title="Lama"></div>
            </div>
        </div>

        <hr class="my-2 border-dashed">

        {{-- 2. GENDER (LAKI-LAKI VS PEREMPUAN) --}}
        <div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-bold text-uppercase">Jenis Kelamin</span>
                <span class="small text-muted" id="labelTotalGender">0 Pasien</span>
            </div>
            <div class="row g-2 mb-2 text-center">
                <div class="col-6">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted small">
                            <i class="ti ti-gender-male text-primary me-1"></i> Laki-laki
                        </div>
                        <div class="h2 mb-0 mt-1 text-primary fw-bold" id="valGenderLaki">0</div>
                        <span class="badge bg-primary-lt small" id="persenGenderLaki">0%</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted small">
                            <i class="ti ti-gender-female text-pink me-1"></i> Perempuan
                        </div>
                        <div class="h2 mb-0 mt-1 text-pink fw-bold" id="valGenderPerempuan">0</div>
                        <span class="badge bg-pink-lt small" id="persenGenderPerempuan">0%</span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-primary" id="barGenderLaki" role="progressbar" style="width: 0%" title="Laki-laki"></div>
                <div class="progress-bar bg-pink" id="barGenderPerempuan" role="progressbar" style="width: 0%" title="Perempuan"></div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        function renderDemografiPasien(data) {
            const sd = data.status_daftar || {};
            const gd = data.gender || {};

            // Pasien Baru vs Lama
            $('#valPasienBaru').text(sd.baru || 0);
            $('#valPasienLama').text(sd.lama || 0);
            $('#persenPasienBaru').text(`${sd.persen_baru || 0}%`);
            $('#persenPasienLama').text(`${sd.persen_lama || 0}%`);
            $('#barPasienBaru').css('width', `${sd.persen_baru || 0}%`);
            $('#barPasienLama').css('width', `${sd.persen_lama || 0}%`);
            $('#labelTotalDaftar').text(`${sd.total || 0} Pasien`);

            // Gender
            $('#valGenderLaki').text(gd.laki || 0);
            $('#valGenderPerempuan').text(gd.perempuan || 0);
            $('#persenGenderLaki').text(`${gd.persen_laki || 0}%`);
            $('#persenGenderPerempuan').text(`${gd.persen_perempuan || 0}%`);
            $('#barGenderLaki').css('width', `${gd.persen_laki || 0}%`);
            $('#barGenderPerempuan').css('width', `${gd.persen_perempuan || 0}%`);
            $('#labelTotalGender').text(`${gd.total || 0} Pasien`);

            $('#badgeTotalDemografi').text(`${sd.total || 0} Pasien`);
        }

        function dataDemografiPasien(tgl1 = '', tgl2 = '') {
            $('#loadingDemografi').removeClass('d-none');
            $('#loadingPoli').removeClass('d-none');

            $.get(`{{ url('/dashboard/demografi') }}`, {
                tgl1: tgl1,
                tgl2: tgl2,
            }).done((response) => {
                $('#loadingDemografi').addClass('d-none');
                $('#loadingPoli').addClass('d-none');
                renderDemografiPasien(response);
                
                // Juga render grafik kunjungan poli dari data yang sama
                if (typeof renderGrafikPoli === 'function') {
                    renderGrafikPoli(response.poli || { labels: [], counts: [] });
                }
            }).fail(() => {
                $('#loadingDemografi').addClass('d-none');
                $('#loadingPoli').addClass('d-none');
            });
        }
    </script>
@endpush

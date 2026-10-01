@extends('layout')

@section('body')
    <div class="container-xl">
        {{-- WELCOME HEADER & QUICK ACTIONS (CLEAN NATIVE STYLE) --}}
        <div class="card mb-3">
            <div class="card-body py-3 px-3">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-12 mb-2 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="text-muted small mb-1">
                                    <i class="ti ti-user me-1 text-primary"></i> Selamat Datang : <strong>{{ session()->get('pegawai')->nama ?? 'Petugas' }}</strong>
                                </div>
                                <h2 class="mb-0 fw-bold text-dark">{{ $data->nama_instansi }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 text-lg-end">
                        <div class="btn-list justify-content-lg-end">
                            <a href="{{ url('/registrasi') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-user-plus me-1"></i> Registrasi
                            </a>
                            <a href="{{ url('/kasir/ralan') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-cash me-1"></i> Kasir Ralan
                            </a>
                            <a href="{{ url('/ranap') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-bed me-1"></i> Rawat Inap
                            </a>
                            <a href="{{ url('/farmasi/resep') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-pill me-1"></i> Resep Obat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI STATS CARDS ROW (5 CARDS DENGAN TINGGI SERAGAM) --}}
        <div class="row row-cards mb-3 align-items-stretch">
            {{-- 1. TOTAL KUNJUNGAN --}}
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm h-100 overflow-hidden">
                    <div class="card-status-top bg-primary"></div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <i class="ti ti-users fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="subheader mb-0">Kunjungan</div>
                                    <span class="badge bg-green-lt small py-0 px-1" title="Real-time hari ini">
                                        <span class="badge-dot bg-green me-1"></span>Live
                                    </span>
                                </div>
                                <div class="h1 mb-0 mt-1" id="totalKunjungan">0</div>
                                <div class="text-muted small mt-1">Total pasien terdaftar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. PEMBIAYAAN UMUM --}}
            <div class="col-sm-6 col-lg-2">
                <div class="card card-sm h-100 overflow-hidden">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-yellow text-white avatar">
                                    <i class="ti ti-wallet fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="subheader mb-0">Umum</div>
                                    <span class="badge bg-yellow-lt small py-0 px-1">Mandiri</span>
                                </div>
                                <div class="h1 mb-0 mt-1" id="totalUmum">0</div>
                                <div class="text-muted small mt-1">Bayar mandiri</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. PEMBIAYAAN BPJS --}}
            <div class="col-sm-6 col-lg-2">
                <div class="card card-sm h-100 overflow-hidden">
                    <div class="card-status-top bg-green"></div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar">
                                    <i class="ti ti-shield-heart fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="subheader mb-0">BPJS</div>
                                    <span class="badge bg-green-lt small py-0 px-1">JKN/KIS</span>
                                </div>
                                <div class="h1 mb-0 mt-1" id="totalBpjs">0</div>
                                <div class="text-muted small mt-1">Peserta BPJS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. STATUS PELAYANAN --}}
            <div class="col-sm-6 col-lg-2">
                <div class="card card-sm h-100 overflow-hidden">
                    <div class="card-status-top bg-red"></div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-red text-white avatar">
                                    <i class="ti ti-stethoscope fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="subheader mb-0">Pelayanan</div>
                                    <span class="badge bg-red-lt small py-0 px-1">Ralan</span>
                                </div>
                                <div class="h1 mb-0 mt-1">
                                    <span class="text-success" id="totalDiperiksa">0</span>
                                    <span class="text-muted fs-4">/</span>
                                    <span class="text-warning" id="totalMenunggu">0</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    <span class="text-success fw-bold">Selesai</span> / <span class="text-warning fw-bold">Antre</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. KETERISIAN BED RAWAT INAP (BOR) --}}
            <div class="col-sm-12 col-lg-3">
                <div class="card card-sm h-100 overflow-hidden">
                    <div class="card-status-top bg-indigo"></div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-indigo text-white avatar">
                                    <i class="ti ti-bed fs-2"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="subheader mb-0">Rawat Inap</div>
                                    <span class="badge bg-indigo-lt small py-0 px-1">BOR {{ $bedStats['bor'] ?? 0 }}%</span>
                                </div>
                                <div class="h1 mb-0 mt-1">
                                    {{ $bedStats['isi'] ?? 0 }} <span class="fs-4 text-muted">/ {{ $bedStats['total'] ?? 0 }} Terisi</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    <span class="text-success fw-bold">{{ $bedStats['kosong'] ?? 0 }}</span> Bed siap pakai
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- OPERATIONAL LIVE INSIGHTS (ANTREAN POLI & PASIEN TERAKHIR) --}}
        <div class="row row-cards mb-3">
            {{-- A. RINGKASAN ANTREAN POLIKLINIK --}}
            <div class="col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h4 class="card-title m-0 d-flex align-items-center">
                            <i class="ti ti-heart-rate-monitor text-primary me-2 fs-2"></i> Status Antrean Poli Hari Ini
                        </h4>
                        <span class="badge bg-primary-lt">{{ count($poliQueue ?? []) }} Poli Aktif</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Poliklinik</th>
                                    <th class="text-center">Menunggu</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($poliQueue ?? [] as $pq)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $pq->nm_poli }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($pq->menunggu > 0)
                                                <span class="badge bg-warning text-white px-2">{{ $pq->menunggu }} Antre</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($pq->selesai > 0)
                                                <span class="badge bg-success-lt px-2">{{ $pq->selesai }} Selesai</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ $pq->total }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="ti ti-info-circle me-1"></i> Belum ada antrean poli hari ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- D. 5 PENDAFTARAN PASIEN TERAKHIR --}}
            <div class="col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h4 class="card-title m-0 d-flex align-items-center">
                            <i class="ti ti-activity text-teal me-2 fs-2"></i> Pasien Terdaftar Terakhir
                        </h4>
                        <a href="{{ url('/registrasi') }}" class="btn btn-sm btn-link p-0 text-decoration-none">
                            Lihat Semua <i class="ti ti-chevron-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-hover">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Poli / Dokter</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPatients ?? [] as $rp)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $rp->nm_pasien }}</div>
                                            <div class="text-muted small">RM: {{ $rp->no_rkm_medis }} &bull; {{ substr($rp->jam_reg, 0, 5) }}</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">{{ $rp->nm_poli }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 140px;">{{ $rp->nm_dokter ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if($rp->stts === 'Sudah')
                                                <span class="badge bg-success-lt">Sudah Periksa</span>
                                            @elseif($rp->stts === 'Belum')
                                                <span class="badge bg-warning-lt">Menunggu</span>
                                            @elseif($rp->stts === 'Dirujuk')
                                                <span class="badge bg-info-lt">Dirujuk</span>
                                            @else
                                                <span class="badge bg-secondary-lt">{{ $rp->stts }}</span>
                                            @endif
                                            <div class="mt-1">
                                                @if($rp->status_bayar === 'Sudah Bayar')
                                                    <span class="badge bg-green text-white" style="font-size: 8px;">Lunas</span>
                                                @else
                                                    <span class="badge bg-danger text-white" style="font-size: 8px;">Belum Bayar</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            <i class="ti ti-info-circle me-1"></i> Belum ada pasien terdaftar hari ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- GLOBAL UNIFIED FILTER BAR FOR CHARTS --}}
        <div class="card mb-3">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center justify-content-between g-2">
                    <div class="col-xl-5 col-lg-6 col-md-12 d-flex align-items-center flex-wrap gap-2">
                        <span class="text-muted small fw-bold d-flex align-items-center">
                            <i class="ti ti-adjustments-horizontal text-primary me-1 fs-3"></i> Periode Analisis:
                        </span>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-period active" data-type="today">Hari Ini</button>
                            <button type="button" class="btn btn-outline-primary btn-period" data-type="7days">7 Hari</button>
                            <button type="button" class="btn btn-outline-primary btn-period" data-type="month">Bulan Ini</button>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="d-flex align-items-center justify-content-lg-end gap-2">
                            <div class="input-group input-group-sm" style="max-width: 320px;">
                                <span class="input-group-text bg-light text-muted"><i class="ti ti-calendar"></i></span>
                                <input type="text" class="form-control filterTangal text-center" id="tglGlobal1" value="{{ date('d-m-Y') }}" placeholder="Tgl Mulai">
                                <span class="input-group-text bg-light text-muted">s.d.</span>
                                <input type="text" class="form-control filterTangal text-center" id="tglGlobal2" value="{{ date('d-m-Y') }}" placeholder="Tgl Akhir">
                                <button class="btn btn-primary" id="btnApplyGlobalFilter" title="Terapkan Filter">
                                    <i class="ti ti-search me-1"></i> Terapkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHARTS ROW (10 BESAR PENYAKIT, KELURAHAN, KECAMATAN) --}}
        <div class="row row-cards mb-3">
            <div class="col-lg-4 col-md-12">
                @include('content.dashboard._grafikPenyakit')
            </div>
            <div class="col-lg-4 col-md-12">
                @include('content.dashboard._grafikKelurahan')
            </div>
            <div class="col-lg-4 col-md-12">
                @include('content.dashboard._grafikKecamatan')
            </div>
        </div>

        {{-- ANNUAL TREND CHART --}}
        <div class="row row-cards mb-4">
            <div class="col-12">
                @include('content.dashboard._grafikTahunan')
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
    <script>
        $(document).ready(() => {
            setCardKunjungan();
            
            // Initial load for all charts (Hari Ini)
            const todayFormatted = $('#tglGlobal1').val();
            loadAllDashboardCharts(splitTanggal(todayFormatted), splitTanggal(todayFormatted));
            
            // Tahunan
            dataGrafikTahunan();
        });

        function loadAllDashboardCharts(tgl1, tgl2) {
            dataGrafikDiagnosa(tgl1, tgl2);
            dataGrafikKelurahan(tgl1, tgl2);
            dataGrafikKecamatan(tgl1, tgl2);
        }

        function setCardKunjungan() {
            getRegPeriksa().done((response) => {
                const totalKunjungan = response ? response.length : 0;

                // Deteksi dinamis: Cek jika nama penjamin mengandung 'BPJS' atau kode penjamin BPJS
                const isBpjs = (item) => {
                    const pjName = (item.penjab?.png_jawab || '').toUpperCase();
                    const pjCode = (item.kd_pj || '').toUpperCase();
                    return pjName.includes('BPJS') || ['BPJ', 'A65', 'A28'].includes(pjCode);
                };

                const totalBpjs = response.filter(isBpjs).length;
                // Pasien Umum mencakup semua pasien non-BPJS secara universal
                const totalUmum = totalKunjungan - totalBpjs;

                const totalDiperiksa = response.filter((item) => item.stts === 'Sudah').length;
                const totalMenunggu = response.filter((item) => item.stts === 'Belum').length;
                const totalDirujuk = response.filter((item) => item.stts === 'Dirujuk').length;

                $('#totalKunjungan').html(totalKunjungan);
                $('#totalBpjs').html(totalBpjs);
                $('#totalUmum').html(totalUmum);
                $('#totalDiperiksa').html(totalDiperiksa);
                $('#totalMenunggu').html(totalMenunggu);
            });
        }

        // Global Period Quick Buttons
        $('.btn-period').on('click', function() {
            $('.btn-period').removeClass('active');
            $(this).addClass('active');

            const period = $(this).data('type');
            const now = new Date();
            let d1 = new Date();
            let d2 = new Date();

            if (period === 'today') {
                // today
            } else if (period === '7days') {
                d1.setDate(now.getDate() - 6);
            } else if (period === 'month') {
                d1 = new Date(now.getFullYear(), now.getMonth(), 1);
            }

            const formatDate = (d) => {
                const day = String(d.getDate()).padStart(2, '0');
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const year = d.getFullYear();
                return `${day}-${month}-${year}`;
            };

            const tgl1Text = formatDate(d1);
            const tgl2Text = formatDate(d2);

            $('#tglGlobal1').val(tgl1Text);
            $('#tglGlobal2').val(tgl2Text);

            loadAllDashboardCharts(splitTanggal(tgl1Text), splitTanggal(tgl2Text));
        });

        // Apply Custom Date Range Button
        $('#btnApplyGlobalFilter').on('click', function() {
            $('.btn-period').removeClass('active');
            const tgl1 = $('#tglGlobal1').val();
            const tgl2 = $('#tglGlobal2').val();

            if (!tgl1 || !tgl2) return;
            loadAllDashboardCharts(splitTanggal(tgl1), splitTanggal(tgl2));
        });
    </script>
@endpush

<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
        <h4 class="card-title m-0 d-flex align-items-center">
            <i class="ti ti-chart-line text-primary me-2 fs-2"></i>
            Trend Kunjungan Pasien Bulanan &mdash; Th. <span id="titleGrafikTahun" class="badge bg-primary-lt ms-2 fs-4">{{ date('Y') }}</span>
        </h4>
        <div class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="width: 140px;">
                <input type="number" class="form-control" id="tahunGrafik" value="{{ date('Y') }}" min="2020" max="2035">
                <button class="btn btn-primary" id="btnFilterTahun" title="Filter Tahun">
                    <i class="ti ti-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-3 position-relative" style="min-height: 280px;">
        <div id="loadingTahunan" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="text-muted small mt-1">Memuat tren tahunan...</div>
        </div>
        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
            <canvas id="grafikTahunan"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script>
        var ctxGrafikTahunan = $('#grafikTahunan');
        var grafikTahunan = null;
        const titleGrafikTahun = $('#titleGrafikTahun');

        function renderGrafikTahunan(data) {
            if (grafikTahunan) {
                grafikTahunan.destroy();
            }

            // Create gradient fill for smooth line
            var ctx = ctxGrafikTahunan[0].getContext('2d');
            var gradient = ctx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(32, 107, 196, 0.35)');
            gradient.addColorStop(1, 'rgba(32, 107, 196, 0.01)');

            grafikTahunan = new Chart(ctxGrafikTahunan, {
                type: 'line',
                data: {
                    labels: data.label,
                    datasets: [{
                        label: 'Total Kunjungan',
                        data: data.list,
                        borderColor: '#206bc4',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#206bc4',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#206bc4',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { size: 10 }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            ticks: {
                                font: { size: 10, weight: '500' }
                            },
                            grid: {
                                color: '#f8fafc'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 10,
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            callbacks: {
                                label: (context) => {
                                    return ` Pasien Terdaftar: ${context.raw} orang`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function dataGrafikTahunan(tahun = '') {
            $('#loadingTahunan').removeClass('d-none');

            $.get(`{{ url('/registrasi/grafik/tahun') }}`, {
                tahun: tahun,
            }).done((response) => {
                $('#loadingTahunan').addClass('d-none');
                
                // Pastikan 12 bulan terwakili jika ingin tren penuh, atau mapping dari response
                const data = {
                    'list': response.map((item) => item.jumlah),
                    'label': response.map((item) => formatBulan(item.bulan)),
                    'title': response.map((item) => formatBulan(item.bulan)),
                    'tahun': response.length > 0 && response[0].tahun ? response[0].tahun : ($('#tahunGrafik').val() || new Date().getFullYear()),
                };

                titleGrafikTahun.html(data.tahun);
                renderGrafikTahunan(data);
            }).fail(() => {
                $('#loadingTahunan').addClass('d-none');
            });
        }

        $('#btnFilterTahun').on('click', () => {
            const tahun = $('#tahunGrafik').val();
            dataGrafikTahunan(tahun);
        });

        $('#tahunGrafik').on('keypress', (e) => {
            if (e.which === 13) {
                $('#btnFilterTahun').click();
            }
        });
    </script>
@endpush

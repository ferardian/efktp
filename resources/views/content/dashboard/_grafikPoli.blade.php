<div class="card h-100">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h4 class="card-title m-0 d-flex align-items-center">
            <i class="ti ti-building-hospital text-purple me-2 fs-2"></i> Kunjungan per Poli
        </h4>
        <span class="badge bg-purple-lt" id="badgePoliCount">0 Poli</span>
    </div>
    <div class="card-body p-3 position-relative" style="min-height: 280px;">
        <div id="loadingPoli" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="text-muted small mt-1">Memuat data poli...</div>
        </div>
        <div id="emptyPoli" class="position-absolute top-50 start-50 translate-middle text-center text-muted d-none w-100">
            <i class="ti ti-hospital fs-1 d-block mb-1 text-secondary opacity-50"></i>
            <span class="small">Tidak ada kunjungan poli pada periode ini</span>
        </div>
        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
            <canvas id="grafikPoli"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script>
        var ctxGrafikPoli = $('#grafikPoli');
        var grafikPoli = null;

        function renderGrafikPoli(poliData) {
            if (grafikPoli) {
                grafikPoli.destroy();
            }

            const labels = poliData.labels || [];
            const counts = poliData.counts || [];

            if (labels.length === 0) {
                $('#emptyPoli').removeClass('d-none');
                $('#badgePoliCount').text('0 Poli');
                return;
            } else {
                $('#emptyPoli').addClass('d-none');
                $('#badgePoliCount').text(`${labels.length} Poli Aktif`);
            }

            const poliColors = [
                '#8b5cf6', '#3b82f6', '#06b6d4', '#10b981',
                '#f59e0b', '#fb923c', '#f43f5e', '#64748b'
            ];

            grafikPoli = new Chart(ctxGrafikPoli, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: poliColors.slice(0, labels.length),
                        borderRadius: 6,
                        maxBarThickness: 22,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { size: 10 }
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        y: {
                            ticks: {
                                font: { size: 10, weight: '500' }
                            },
                            grid: {
                                display: false
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
                                title: (items) => {
                                    return `Poliklinik: ${labels[items[0].dataIndex]}`;
                                },
                                label: (context) => {
                                    return ` Total Kunjungan: ${context.raw} pasien`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush

<div class="card h-100">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h4 class="card-title m-0 d-flex align-items-center">
            <i class="ti ti-virus text-danger me-2 fs-2"></i> 10 Besar Penyakit
        </h4>
        <span class="badge bg-red-lt" id="badgePenyakitCount">Top ICD-10</span>
    </div>
    <div class="card-body p-3 position-relative" style="min-height: 280px;">
        <div id="loadingPenyakit" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="text-muted small mt-1">Memuat data...</div>
        </div>
        <div id="emptyPenyakit" class="position-absolute top-50 start-50 translate-middle text-center text-muted d-none w-100">
            <i class="ti ti-clipboard-x fs-1 d-block mb-1 text-secondary opacity-50"></i>
            <span class="small">Tidak ada data diagnosa pada periode ini</span>
        </div>
        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
            <canvas id="grafikDiagnosa"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script>
        var ctxGrafikDiagnosa = $('#grafikDiagnosa');
        var grafikDiagnosa = null;

        function renderGrafikDiagnosa(data) {
            if (grafikDiagnosa) {
                grafikDiagnosa.destroy();
            }

            if (!data.list || data.list.length === 0) {
                $('#emptyPenyakit').removeClass('d-none');
                $('#badgePenyakitCount').text('0 Diagnosa');
                return;
            } else {
                $('#emptyPenyakit').addClass('d-none');
                $('#badgePenyakitCount').text(`${data.list.length} Diagnosa`);
            }

            const modernPalette = [
                '#f43f5e', '#fb923c', '#f59e0b', '#10b981', 
                '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899', 
                '#14b8a6', '#6366f1'
            ];

            grafikDiagnosa = new Chart(ctxGrafikDiagnosa, {
                type: 'bar',
                data: {
                    labels: data.label,
                    datasets: [{
                        data: data.list,
                        backgroundColor: modernPalette.slice(0, data.list.length),
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
                                    const idx = items[0].dataIndex;
                                    return `${data.label[idx]} - ${data.title[idx] || ''}`;
                                },
                                label: (context) => {
                                    return ` Total Pasien: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function dataGrafikDiagnosa(tgl1 = '', tgl2 = '') {
            $('#loadingPenyakit').removeClass('d-none');
            $('#emptyPenyakit').addClass('d-none');

            $.get(`{{ url('/diagnosa/pasien/grafik') }}`, {
                tglDiagnosa1: tgl1,
                tglDiagnosa2: tgl2,
            }).done((response) => {
                $('#loadingPenyakit').addClass('d-none');
                const data = {
                    'list': response.map((item) => item.count),
                    'label': response.map((item) => item.kd_penyakit),
                    'title': response.map((item) => item.penyakit ? item.penyakit.nm_penyakit : item.kd_penyakit),
                };
                renderGrafikDiagnosa(data);
            }).fail(() => {
                $('#loadingPenyakit').addClass('d-none');
                $('#emptyPenyakit').removeClass('d-none');
            });
        }
    </script>
@endpush

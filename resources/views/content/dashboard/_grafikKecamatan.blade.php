<div class="card h-100 shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h4 class="card-title m-0 d-flex align-items-center">
            <i class="ti ti-map-2 text-teal me-2 fs-2"></i> Sebaran Kecamatan
        </h4>
        <span class="badge bg-teal-lt" id="badgeKecamatanCount">Top Wilayah</span>
    </div>
    <div class="card-body p-3 position-relative" style="min-height: 280px;">
        <div id="loadingKecamatan" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="text-muted small mt-1">Memuat data...</div>
        </div>
        <div id="emptyKecamatan" class="position-absolute top-50 start-50 translate-middle text-center text-muted d-none w-100">
            <i class="ti ti-map-off fs-1 d-block mb-1 text-secondary opacity-50"></i>
            <span class="small">Tidak ada data kecamatan pada periode ini</span>
        </div>
        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
            <canvas id="grafikKecamatan"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script>
        var ctxGrafikKecamatan = $('#grafikKecamatan');
        var grafikKecamatan = null;

        function renderGrafikKecamatan(data) {
            if (grafikKecamatan) {
                grafikKecamatan.destroy();
            }

            if (!data.list || data.list.length === 0) {
                $('#emptyKecamatan').removeClass('d-none');
                $('#badgeKecamatanCount').text('0 Wilayah');
                return;
            } else {
                $('#emptyKecamatan').addClass('d-none');
                $('#badgeKecamatanCount').text(`${data.list.length} Kecamatan`);
            }

            const modernPalette = [
                '#14b8a6', '#06b6d4', '#3b82f6', '#8b5cf6',
                '#f59e0b', '#fb923c', '#f43f5e', '#64748b',
                '#10b981', '#6366f1'
            ];

            grafikKecamatan = new Chart(ctxGrafikKecamatan, {
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
                                    return `Kecamatan: ${data.label[idx]}`;
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

        function dataGrafikKecamatan(tgl1 = '', tgl2 = '') {
            $('#loadingKecamatan').removeClass('d-none');
            $('#emptyKecamatan').addClass('d-none');

            $.get(`{{ url('/registrasi/kecamatan') }}`, {
                tgl1: tgl1,
                tgl2: tgl2,
            }).done((response) => {
                $('#loadingKecamatan').addClass('d-none');
                const fullLabels = Object.keys(response).map((lbl) => lbl ? lbl.trim() : 'Lainnya');
                const data = {
                    'list': Object.values(response),
                    'label': fullLabels,
                };
                renderGrafikKecamatan(data);
            }).fail(() => {
                $('#loadingKecamatan').addClass('d-none');
                $('#emptyKecamatan').removeClass('d-none');
            });
        }
    </script>
@endpush

<div class="card h-100 shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h4 class="card-title m-0 d-flex align-items-center">
            <i class="ti ti-map-pin text-primary me-2 fs-2"></i> Sebaran Kelurahan
        </h4>
        <span class="badge bg-blue-lt" id="badgeKelurahanCount">Top Wilayah</span>
    </div>
    <div class="card-body p-3 position-relative" style="min-height: 280px;">
        <div id="loadingKelurahan" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
            <div class="text-muted small mt-1">Memuat data...</div>
        </div>
        <div id="emptyKelurahan" class="position-absolute top-50 start-50 translate-middle text-center text-muted d-none w-100">
            <i class="ti ti-map-off fs-1 d-block mb-1 text-secondary opacity-50"></i>
            <span class="small">Tidak ada data kelurahan pada periode ini</span>
        </div>
        <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
            <canvas id="grafikKelurahan"></canvas>
        </div>
    </div>
</div>

@push('script')
    <script>
        var ctxGrafikKelurahan = $('#grafikKelurahan');
        var grafikKelurahan = null;

        function renderGrafikKelurahan(data) {
            if (grafikKelurahan) {
                grafikKelurahan.destroy();
            }

            if (!data.list || data.list.length === 0) {
                $('#emptyKelurahan').removeClass('d-none');
                $('#badgeKelurahanCount').text('0 Wilayah');
                return;
            } else {
                $('#emptyKelurahan').addClass('d-none');
                $('#badgeKelurahanCount').text(`${data.list.length} Kelurahan`);
            }

            const modernPalette = [
                '#3b82f6', '#06b6d4', '#10b981', '#f59e0b',
                '#8b5cf6', '#ec4899', '#f43f5e', '#64748b',
                '#14b8a6', '#6366f1'
            ];

            grafikKelurahan = new Chart(ctxGrafikKelurahan, {
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
                                    return `Kelurahan: ${data.label[idx]}`;
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

        function dataGrafikKelurahan(tgl1 = '', tgl2 = '') {
            $('#loadingKelurahan').removeClass('d-none');
            $('#emptyKelurahan').addClass('d-none');

            $.get(`{{ url('/registrasi/kelurahan') }}`, {
                tgl1: tgl1,
                tgl2: tgl2,
            }).done((response) => {
                $('#loadingKelurahan').addClass('d-none');
                const fullLabels = Object.keys(response).map((lbl) => lbl ? lbl.trim() : 'Lainnya');
                const data = {
                    'list': Object.values(response),
                    'label': fullLabels,
                };
                renderGrafikKelurahan(data);
            }).fail(() => {
                $('#loadingKelurahan').addClass('d-none');
                $('#emptyKelurahan').removeClass('d-none');
            });
        }
    </script>
@endpush

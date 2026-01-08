<div>
    <div class="card">
        <div class="card-header">
            <h4 class="m-0">Grafik Kunjungan Th. <span id="titleGrafikTahun"></span></h4>
        </div>
        <div class="card-body h-30">
            <canvas id="grafikTahunan" style="max-height:40vh"></canvas>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="input-group">
                        <input type="text" class="form-control filterTahun" id="tahunGrafik" value="{{ date('Y') }}">
                        <button class="btn btn-indigo" id="btnFilterTahun"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        var ctxGrafikTahunan = $('#grafikTahunan');
        var grafikTahunan = '';
        const titleGrafikTahun = $('#titleGrafikTahun');


        function renderGrafikTahunan(data) {
            grafikTahunan = new Chart(ctxGrafikTahunan, {
                type: 'bar',
                data: {
                    labels: data.label,
                    datasets: [{
                        data: data.list,
                        borderWidth: 1,
                        backgroundColor: 'rgba(201, 203, 100)',

                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const labelHover = data.title[context.dataIndex]
                                    const countHover = data.list[context.dataIndex]
                                    return ` ${countHover}`;
                                },
                            },
                        },
                    },
                }
            });
        }

        function dataGrafikTahunan(tahun = '') {
            $.get(`/efktp/registrasi/grafik/tahun`, {
                tahun: tahun,
            }).done((response) => {
                const data = {
                    'list': response.map((item) => item.jumlah),
                    'label': response.map((item) => formatBulan(item.bulan)),
                    'title': response.map((item) => formatBulan(item.bulan)),
                    'tahun': response.map((item) => item.tahun)[0],
                }
                titleGrafikTahun.html(data.tahun)
                renderGrafikTahunan(data);
            })
        }

        $('#btnFilterTahun').on('click', () => {
            const tahun = $('#tahunGrafik').val()
            grafikTahunan.destroy();
            dataGrafikTahunan(tahun);
        })
    </script>
@endpush

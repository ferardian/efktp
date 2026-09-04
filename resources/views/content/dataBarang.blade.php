@extends('layout')

@section('body')
    <style>
        /* Sticky Column untuk Checkbox, Kode, & Nama Obat */
        #tabelBarangObat th:nth-child(1), #tabelBarangObat td:nth-child(1) {
            position: sticky !important;
            left: 0 !important;
            z-index: 2 !important;
            background-color: #ffffff !important;
        }
        #tabelBarangObat th:nth-child(2), #tabelBarangObat td:nth-child(2) {
            position: sticky !important;
            left: 32px !important;
            z-index: 2 !important;
            background-color: #ffffff !important;
        }
        #tabelBarangObat th:nth-child(3), #tabelBarangObat td:nth-child(3) {
            position: sticky !important;
            left: 110px !important;
            z-index: 2 !important;
            background-color: #ffffff !important;
            box-shadow: 4px 0 6px -2px rgba(0, 0, 0, 0.12) !important;
        }
        #tabelBarangObat thead th:nth-child(1),
        #tabelBarangObat thead th:nth-child(2),
        #tabelBarangObat thead th:nth-child(3) {
            background-color: #f8fafc !important;
            z-index: 3 !important;
        }
        #tabelBarangObat tbody tr:nth-child(even) td:nth-child(1),
        #tabelBarangObat tbody tr:nth-child(even) td:nth-child(2),
        #tabelBarangObat tbody tr:nth-child(even) td:nth-child(3) {
            background-color: #f8fafc !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row gy-2">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <div class="card" style="height: calc(100vh - 170px); display: flex; flex-direction: column;">
                    <div class="card-header d-flex justify-content-between align-items-center py-2 bg-light-lt">
                        <h3 class="card-title text-primary"><i class="ti ti-pill me-2"></i> Daftar Obat / Barang</h3>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" id="filter_status" style="width: 140px;">
                                <option value="1" selected>Status: Aktif</option>
                                <option value="0">Status: Non-Aktif</option>
                                <option value="semua">Status: Semua</option>
                            </select>
                            <button type="button" class="btn btn-success btn-sm" onclick="exportDataBarang('excel')" title="Export Excel (.xls)">
                                <i class="ti ti-file-spreadsheet me-1"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="exportDataBarang('pdf')" title="Export PDF">
                                <i class="ti ti-file-text me-1"></i> PDF
                            </button>
                            <button type="button" class="btn btn-danger btn-sm d-none" id="btnBatchDeactivate" onclick="batchDeactivateBarang()">
                                <i class="ti ti-trash-off me-1"></i> Non-aktifkan Terpilih
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="flex: 1; min-height: 0; overflow: hidden;">
                        <div id="table-default" class="table-responsive h-100" style="overflow-y: auto;">
                            <table class="table table-striped table-hover nowrap" id="tabelBarangObat" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%"><input type="checkbox" id="checkAllBarang" class="form-check-input"></th>
                                        <th>Kode</th>
                                        <th>Nama Obat/Barang</th>
                                        <th>Dosis</th>
                                        <th>Satuan</th>
                                        <th>Stok</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Ralan</th>
                                        <th>Kandungan</th>
                                        <th>Jenis</th>
                                        <th>Kategori</th>
                                        <th>Golongan</th>
                                        <th>Industri</th>
                                        <th width="15%">Mapping PCare</th>
                                        <th>Status</th>
                                        <th width="8%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <form id="formBarangObat">
                    @csrf
                    <div class="card" style="height: calc(100vh - 170px); overflow-y: auto;">
                        <div class="card-body">
                            <h5 class="card-title">Form Data Obat / Barang</h5>
                            
                            <div class="mb-2">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" id="kode_brng" name="kode_brng" placeholder="Contoh: 00001">
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label">Nama Barang/Obat</label>
                                <input type="text" class="form-control" id="nama_brng" name="nama_brng">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Kapasitas / Dosis</label>
                                        <input type="text" class="form-control" id="kapasitas" name="kapasitas">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Isi</label>
                                        <input type="number" class="form-control" id="isi" name="isi" value="1">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Satuan Kecil</label>
                                        <select class="form-select" id="kode_sat" name="kode_sat">
                                            @foreach($satuan as $s)
                                                <option value="{{ $s->kode_sat }}">{{ $s->satuan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Satuan Besar</label>
                                        <select class="form-select" id="kode_satbesar" name="kode_satbesar">
                                            @foreach($satuan as $s)
                                                <option value="{{ $s->kode_sat }}">{{ $s->satuan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Kandungan / Letak</label>
                                <input type="text" class="form-control" id="letak_barang" name="letak_barang">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Jenis</label>
                                        <select class="form-select" id="kdjns" name="kdjns">
                                            @foreach($jenis as $j)
                                                <option value="{{ $j->kdjns }}">{{ $j->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-select" id="kode_kategori" name="kode_kategori">
                                            @foreach($kategori as $k)
                                                <option value="{{ $k->kode }}">{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Golongan</label>
                                        <select class="form-select" id="kode_golongan" name="kode_golongan">
                                            @foreach($golongan as $g)
                                                <option value="{{ $g->kode }}">{{ $g->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Industri / Produsen</label>
                                        <select class="form-select" id="kode_industri" name="kode_industri">
                                            @foreach($industri as $i)
                                                <option value="{{ $i->kode_industri }}">{{ $i->nama_industri }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Stok Minimal</label>
                                        <input type="number" class="form-control" id="stokminimal" name="stokminimal" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="1">Aktif</option>
                                            <option value="0">Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Expire Date</label>
                                        <input type="date" class="form-control" id="expire" name="expire" value="1900-01-01">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Harga Dasar</label>
                                        <input type="number" class="form-control" id="dasar" name="dasar" value="0">
                                    </div>
                                </div>
                            </div>

                            <h6 class="mt-3 mb-2 text-primary border-bottom pb-1">Harga Jual & Beli</h6>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Harga Beli</label>
                                        <input type="number" class="form-control" id="h_beli" name="h_beli" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Harga Ralan</label>
                                        <input type="number" class="form-control" id="ralan" name="ralan" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Kelas 1</label>
                                        <input type="number" class="form-control" id="kelas1" name="kelas1" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Kelas 2</label>
                                        <input type="number" class="form-control" id="kelas2" name="kelas2" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Kelas 3</label>
                                        <input type="number" class="form-control" id="kelas3" name="kelas3" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">Utama</label>
                                        <input type="number" class="form-control" id="utama" name="utama" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">VIP</label>
                                        <input type="number" class="form-control" id="vip" name="vip" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label class="form-label">VVIP</label>
                                        <input type="number" class="form-control" id="vvip" name="vvip" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label class="form-label">Karyawan</label>
                                        <input type="number" class="form-control" id="karyawan" name="karyawan" value="0">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label class="form-label">Jual Bebas</label>
                                        <input type="number" class="form-control" id="jualbebas" name="jualbebas" value="0">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <label class="form-label">Beli Luar</label>
                                        <input type="number" class="form-control" id="beliluar" name="beliluar" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <button type="button" class="btn btn-secondary w-50" onclick="resetFormBarang()">
                                    <i class="ti ti-plus me-2"></i> Tambah Baru
                                </button>
                                <button type="button" class="btn btn-success w-50" id="btnSimpanBarang" onclick="simpanBarang()">
                                    <i class="ti ti-device-floppy me-2"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@include('content.farmasi.obat._mappingObatPcare')
@push('script')
    <script>
        const tabelBarangObat = $('#tabelBarangObat')

        $(document).ready(() => {
            renderTabelBarang();
            resetFormBarang();

            // Reload table on status filter change
            $('#filter_status').on('change', () => {
                if (typeof selectedBarangCodes !== 'undefined') {
                    selectedBarangCodes.clear();
                    updateBatchButton();
                }
                $('#checkAllBarang').prop('checked', false);
                tabelBarangObat.DataTable().ajax.reload();
            });
        })

        function exportDataBarang(type) {
            const status = $('#filter_status').val();
            let search = '';
            if ($.fn.DataTable.isDataTable('#tabelBarangObat')) {
                search = $('#tabelBarangObat').DataTable().search();
            }
            const params = $.param({ status: status, search: search });
            if (type === 'excel') {
                window.open(`{{ url('/farmasi/obat/export-excel') }}?${params}`, '_blank');
            } else if (type === 'pdf') {
                window.open(`{{ url('/farmasi/obat/export-pdf') }}?${params}`, '_blank');
            }
        }

        function renderTabelBarang() {
            tabelBarangObat.DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                scrollY: 'calc(100vh - 380px)',
                scrollX: true,
                ajax: {
                    url: `{{ url('/barang/get') }}`,
                    type: 'get',
                    data: function (d) {
                        d.dataTable = true;
                        d.allData = true;
                        d.status = $('#filter_status').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        width: '3%',
                        defaultContent: '',
                        render: (data, type, row) => {
                            const isChecked = typeof selectedBarangCodes !== 'undefined' && selectedBarangCodes.has(row.kode_brng) ? 'checked' : '';
                            return `<input type="checkbox" class="form-check-input check-barang" value="${row.kode_brng}" ${isChecked}>`;
                        }
                    },
                    {
                        data: 'kode_brng',
                        name: 'kode_brng',
                        defaultContent: '-',
                        render: (data) => data ?? '-'
                    },
                    {
                        data: 'nama_brng',
                        name: 'nama_brng',
                        defaultContent: '-',
                        render: (data) => data ?? '-'
                    },
                    {
                        data: 'kapasitas',
                        name: 'kapasitas',
                        defaultContent: '-',
                        render: (data) => (data && data != '0') ? data : '-'
                    },
                    {
                        data: 'satuan.satuan',
                        name: 'satuan.satuan',
                        defaultContent: '-',
                        render: (data, type, row) => row.satuan?.satuan ?? '-'
                    },
                    {
                        data: 'gudang_barang',
                        name: 'gudang_barang',
                        defaultContent: '0',
                        render: (data, type, row) => {
                            let total = 0;
                            let details = [];
                            if (data && Array.isArray(data)) {
                                data.forEach(g => {
                                    const nm = g.lokasi ? g.lokasi.nm_bangsal : g.kd_bangsal;
                                    const stokVal = parseFloat(g.stok) || 0;
                                    total += stokVal;
                                    if (stokVal > 0) {
                                        details.push(`${nm}: ${g.stok}`);
                                    }
                                });
                            }
                            const tooltipText = details.length > 0 ? details.join('<br>') : 'Kosong';
                            return `<span class="badge bg-teal-lt" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="${tooltipText}">${total}</span>`;
                        }
                    },
                    {
                        data: 'h_beli',
                        name: 'h_beli',
                        defaultContent: '0',
                        className: 'text-end',
                        render: (data) => {
                            const val = parseFloat(data) || 0;
                            return `<span class="text-secondary fw-medium">Rp ${val.toLocaleString('id-ID')}</span>`;
                        }
                    },
                    {
                        data: 'ralan',
                        name: 'ralan',
                        defaultContent: '0',
                        className: 'text-end',
                        render: (data, type, row) => {
                            const val = parseFloat(data) || 0;
                            const formatRp = (n) => 'Rp ' + (parseFloat(n) || 0).toLocaleString('id-ID');
                            const safeNama = String(row.nama_brng || '-').replace(/"/g, '&quot;');
                            
                            const details = `
                                <div style="min-width: 190px; font-size: 11px; text-align: left;" class="p-1">
                                    <div class="fw-bold border-bottom border-secondary pb-1 mb-1 text-info text-wrap">${safeNama}</div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Dasar:</span><span class="fw-bold">${formatRp(row.dasar)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>H. Beli:</span><span class="fw-bold">${formatRp(row.h_beli)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light text-success"><span>Ralan:</span><span class="fw-bold text-success">${formatRp(row.ralan)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Kelas 1:</span><span>${formatRp(row.kelas1)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Kelas 2:</span><span>${formatRp(row.kelas2)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Kelas 3:</span><span>${formatRp(row.kelas3)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Utama:</span><span>${formatRp(row.utama)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>VIP:</span><span>${formatRp(row.vip)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>VVIP:</span><span>${formatRp(row.vvip)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Jual Bebas:</span><span>${formatRp(row.jualbebas)}</span></div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-light"><span>Karyawan:</span><span>${formatRp(row.karyawan)}</span></div>
                                    <div class="d-flex justify-content-between py-1"><span>Beli Luar:</span><span>${formatRp(row.beliluar)}</span></div>
                                </div>
                            `;
                            
                            const safeDetails = details.replace(/"/g, '&quot;');
                            return `
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span class="fw-semibold text-success">Rp ${val.toLocaleString('id-ID')}</span>
                                    <i class="ti ti-info-circle text-info cursor-pointer" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-html="true" 
                                       data-bs-placement="top" 
                                       title="${safeDetails}"></i>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'letak_barang',
                        name: 'letak_barang',
                        defaultContent: '-',
                        render: (data) => (data && data !== '-') ? data : '-'
                    },
                    {
                        data: 'jenis.nama',
                        name: 'jenis.nama',
                        defaultContent: '-',
                        render: (data, type, row) => row.jenis?.nama ?? '-'
                    },
                    {
                        data: 'kategori.nama',
                        name: 'kategori.nama',
                        defaultContent: '-',
                        render: (data, type, row) => row.kategori?.nama ?? '-'
                    },
                    {
                        data: 'golongan.nama',
                        name: 'golongan.nama',
                        defaultContent: '-',
                        render: (data, type, row) => row.golongan?.nama ?? '-'
                    },
                    {
                        data: 'industri.nama_industri',
                        name: 'industri.nama_industri',
                        defaultContent: '-',
                        render: (data, type, row) => row.industri?.nama_industri ?? '-'
                    },
                    {
                        data: 'mapping.nama_brng_pcare',
                        name: 'mapping.nama_brng_pcare',
                        defaultContent: '-',
                        width: '16%',
                        render: (data, type, row) => {
                            const isMapped = row.mapping && row.mapping.nama_brng_pcare;
                            const pcareNama = isMapped ? row.mapping.nama_brng_pcare : '';
                            const pcareKode = isMapped ? row.mapping.kode_brng_pcare : '';

                            const safeNama = String(row.nama_brng || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const safePcareNama = String(pcareNama || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const safeKapasitas = String(row.kapasitas || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            const safeSatuan = String(row.satuan?.satuan || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');

                            if (isMapped) {
                                return `
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="badge bg-success-lt text-wrap text-start small me-1 px-2 py-1" style="max-width: 180px;">
                                            <i class="ti ti-check me-1 text-success"></i> ${pcareNama}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-icon btn-ghost-primary" 
                                                onclick="openModalMappingObatPcare('${row.kode_brng}', '${safeNama}', '${safeKapasitas}', '${safeSatuan}', '${pcareKode}', '${safePcareNama}')"
                                                title="Edit Mapping PCare">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                `;
                            }

                            return `
                                <button type="button" class="btn btn-sm btn-warning shadow-xs py-1 px-2 d-inline-flex align-items-center" 
                                        onclick="openModalMappingObatPcare('${row.kode_brng}', '${safeNama}', '${safeKapasitas}', '${safeSatuan}', '', '')">
                                    <i class="ti ti-link me-1"></i> Mapping PCare
                                </button>
                            `;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        defaultContent: '1',
                        render: (data) => data == '1' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        defaultContent: '',
                        render: (data, type, row) => {
                            return `<div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning" onclick="editBarang('${row.kode_brng}')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBarang('${row.kode_brng}', '${row.nama_brng}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ],
                drawCallback: function () {
                    // Initialize Select2 on newly created select elements
                    $('.form-select-2').select2({
                        width: 'resolve', // You can adjust this option based on your requirements
                    });

                    // Sync checkboxes state
                    if (typeof selectedBarangCodes !== 'undefined') {
                        let allChecked = $('.check-barang').length > 0;
                        $('.check-barang').each(function() {
                            const val = $(this).val();
                            if (selectedBarangCodes.has(val)) {
                                $(this).prop('checked', true);
                            } else {
                                $(this).prop('checked', false);
                                allChecked = false;
                            }
                        });
                        $('#checkAllBarang').prop('checked', allChecked);
                    }

                    // Initialize tooltips on table draw
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger: 'hover',
                        html: true,
                        sanitize: false
                    });
                }
            })
        }

        function resetFormBarang() {
            $('#formBarangObat').trigger('reset');
            $('#kode_brng').prop('readonly', false);
            $('#btnSimpanBarang').removeClass('btn-warning').addClass('btn-success').html('<i class="ti ti-device-floppy me-2"></i> Simpan');
            
            $.get("{{ url('/barang/get-next-kode') }}").done((response) => {
                $('#kode_brng').val(response.next_kode);
            });
        }

        function simpanBarang() {
            const kode_brng = $('#kode_brng').val();
            const isEdit = $('#kode_brng').prop('readonly');
            const url = isEdit ? `{{ url('/barang/update') }}/${kode_brng}` : "{{ url('/barang/store') }}";
            const method = isEdit ? 'PUT' : 'POST';

            if (!$('#kode_brng').val() || !$('#nama_brng').val()) {
                showToast('Kode dan Nama Barang wajib diisi', 'warning');
                return;
            }

            loadingAjax('Sedang memproses data obat...');

            $.ajax({
                url: url,
                type: method,
                data: $('#formBarangObat').serialize(),
                success: (response) => {
                    showToast(response.message);
                    tabelBarangObat.DataTable().ajax.reload(null, false);
                    if (!isEdit) resetFormBarang();
                },
                error: (xhr) => {
                    showToast(xhr.responseJSON.message || 'Gagal memproses data obat', 'error');
                },
                complete: () => {
                    Swal.close();
                }
            });
        }

        function editBarang(kode_brng) {
            loadingAjax('Mengambil data obat...');
            $.get(`{{ url('/barang/detail') }}/${kode_brng}`).done((data) => {
                $('#kode_brng').val(data.kode_brng).prop('readonly', true);
                $('#nama_brng').val(data.nama_brng);
                $('#kapasitas').val(data.kapasitas);
                $('#isi').val(data.isi);
                $('#kode_sat').val(data.kode_sat);
                $('#kode_satbesar').val(data.kode_satbesar);
                $('#letak_barang').val(data.letak_barang);
                $('#kdjns').val(data.kdjns);
                $('#kode_kategori').val(data.kode_kategori);
                $('#kode_golongan').val(data.kode_golongan);
                $('#kode_industri').val(data.kode_industri);
                $('#stokminimal').val(data.stokminimal);
                $('#status').val(data.status);
                $('#expire').val(data.expire);
                $('#dasar').val(data.dasar);
                $('#h_beli').val(data.h_beli);
                $('#ralan').val(data.ralan);
                $('#kelas1').val(data.kelas1);
                $('#kelas2').val(data.kelas2);
                $('#kelas3').val(data.kelas3);
                $('#utama').val(data.utama);
                $('#vip').val(data.vip);
                $('#vvip').val(data.vvip);
                $('#karyawan').val(data.karyawan);
                $('#jualbebas').val(data.jualbebas);
                $('#beliluar').val(data.beliluar);

                $('#btnSimpanBarang').removeClass('btn-success').addClass('btn-warning').html('<i class="ti ti-pencil me-2"></i> Update');
                Swal.close();
            }).fail((xhr) => {
                showToast('Gagal mengambil data obat', 'error');
                Swal.close();
            });
        }

        function deleteBarang(kode_brng, nama_brng) {
            Swal.fire({
                title: 'Hapus Data Obat?',
                html: `Apakah Anda yakin ingin menghapus <b>${nama_brng}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax('Menghapus data...');
                    $.ajax({
                        url: `{{ url('/barang/delete') }}/${kode_brng}`,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: (response) => {
                            showToast(response.message);
                            tabelBarangObat.DataTable().ajax.reload(null, false);
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON.message || 'Gagal menghapus data obat', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }




        // Batch selection logic
        const selectedBarangCodes = new Set();

        function updateBatchButton() {
            const btn = $('#btnBatchDeactivate');
            const statusFilter = $('#filter_status').val();

            if (selectedBarangCodes.size > 0) {
                btn.removeClass('d-none');
                if (statusFilter === '0') {
                    // Non-Aktif: show Activate button (green success)
                    btn.removeClass('btn-danger').addClass('btn-success');
                    btn.html(`<i class="ti ti-circle-check me-1"></i> Aktifkan Terpilih (${selectedBarangCodes.size})`);
                } else {
                    // Aktif / Semua: show Deactivate button (red danger)
                    btn.removeClass('btn-success').addClass('btn-danger');
                    btn.html(`<i class="ti ti-trash-off me-1"></i> Non-aktifkan Terpilih (${selectedBarangCodes.size})`);
                }
            } else {
                btn.addClass('d-none');
            }
        }

        $(document).on('change', '.check-barang', function() {
            const code = $(this).val();
            if ($(this).is(':checked')) {
                selectedBarangCodes.add(code);
            } else {
                selectedBarangCodes.delete(code);
            }
            
            const allCheckedOnPage = $('.check-barang:checked').length === $('.check-barang').length;
            $('#checkAllBarang').prop('checked', allCheckedOnPage);
            updateBatchButton();
        });

        $(document).on('change', '#checkAllBarang', function() {
            const isChecked = $(this).is(':checked');
            $('.check-barang').each(function() {
                const code = $(this).val();
                $(this).prop('checked', isChecked);
                if (isChecked) {
                    selectedBarangCodes.add(code);
                } else {
                    selectedBarangCodes.delete(code);
                }
            });
            updateBatchButton();
        });

        function batchDeactivateBarang() {
            if (selectedBarangCodes.size === 0) return;

            const statusFilter = $('#filter_status').val();
            const targetStatus = statusFilter === '0' ? '1' : '0';
            const actionText = targetStatus === '1' ? 'mengaktifkan kembali' : 'menonaktifkan';
            const btnColor = targetStatus === '1' ? '#2fb344' : '#d33';
            const confirmText = targetStatus === '1' ? 'Ya, Aktifkan!' : 'Ya, Non-aktifkan!';
            const titleText = targetStatus === '1' ? 'Aktifkan Data Terpilih?' : 'Non-aktifkan Data Terpilih?';

            Swal.fire({
                title: titleText,
                html: `Apakah Anda yakin ingin ${actionText} <b>${selectedBarangCodes.size}</b> data obat terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: btnColor,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadingAjax(targetStatus === '1' ? 'Mengaktifkan data...' : 'Menonaktifkan data...');
                    $.ajax({
                        url: "{{ url('/barang/batch-status') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_brng: Array.from(selectedBarangCodes),
                            status: targetStatus
                        },
                        success: (response) => {
                            showToast(response.message);
                            selectedBarangCodes.clear();
                            updateBatchButton();
                            $('#checkAllBarang').prop('checked', false);
                            tabelBarangObat.DataTable().ajax.reload(null, false);
                        },
                        error: (xhr) => {
                            showToast(xhr.responseJSON.message || 'Gagal memperbarui status data obat', 'error');
                        },
                        complete: () => {
                            Swal.close();
                        }
                    });
                }
            });
        }
    </script>
@endpush

@push('style')
    <style>
        .form-select, .form-control {
            color: #232e3c !important;
        }
        .tooltip-inner {
            text-align: left !important;
        }
    </style>
@endpush

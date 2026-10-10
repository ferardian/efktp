@extends('layout')

@section('body')
    <div class="container-fluid">
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-teal-lt text-teal rounded-circle" style="width: 38px; height: 38px;">
                            <i class="ti ti-dna fs-2"></i>
                        </div>
                        <div>
                            <h2 class="page-title mb-0">Mapping Laboratorium (LOINC & SNOMED CT) Satu Sehat</h2>
                            <div class="text-muted small">Standardisasi terminologi parameter laboratorium klinik ke kode LOINC & SNOMED CT Kemenkes</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('satusehat.servicerequest-lab.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-send me-1"></i> Ke Pengiriman ServiceRequest
                    </a>
                </div>
            </div>
        </div>

@push('style')
    <style>
        .filter-segmented-group {
            background-color: #f1f5f9;
            padding: 3px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 2px;
            border: 1px solid #e2e8f0;
        }

        .filter-segmented-group .btn-filter-status {
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 11.5px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            line-height: 1.4;
            white-space: nowrap;
        }

        .filter-segmented-group .btn-filter-status:hover {
            color: #1e293b;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .filter-segmented-group .btn-filter-status.active {
            background-color: #ffffff;
            color: #0f172a;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .search-box-filter {
            position: relative;
            min-width: 260px;
            max-width: 340px;
        }

        .search-box-filter .form-control {
            border-radius: 6px;
            font-size: 11.5px;
            padding-left: 32px;
            padding-right: 30px;
            height: 31px;
            background-color: #ffffff;
            border-color: #e2e8f0;
            transition: all 0.2s ease;
        }

        .search-box-filter .form-control:focus {
            border-color: #206bc4;
            box-shadow: 0 0 0 3px rgba(32, 107, 196, 0.12);
        }

        .search-box-filter .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            font-size: 13px;
        }

        .search-box-filter .clear-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 13px;
            padding: 2px;
            display: none;
        }

        .search-box-filter .clear-icon:hover {
            color: #475569;
        }
    </style>
@endpush

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2 px-3 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Segmented Status Tabs -->
                    <div class="filter-segmented-group">
                        <button type="button" class="btn-filter-status active" data-status="all" onclick="setStatusFilter('all')">
                            Semua <span class="badge bg-secondary-lt ms-1" id="badgeCountAll">0</span>
                        </button>
                        <button type="button" class="btn-filter-status" data-status="mapped" onclick="setStatusFilter('mapped')">
                            <i class="ti ti-circle-check text-success me-1"></i> Termapping <span class="badge bg-success-lt ms-1" id="badgeCountMapped">0</span>
                        </button>
                        <button type="button" class="btn-filter-status" data-status="unmapped" onclick="setStatusFilter('unmapped')">
                            <i class="ti ti-alert-triangle text-warning me-1"></i> Belum <span class="badge bg-warning-lt ms-1" id="badgeCountUnmapped">0</span>
                        </button>
                    </div>

                    <input type="hidden" id="filterStatus" value="all">

                    <!-- Modern Search Input with Icon -->
                    <div class="search-box-filter">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" id="searchKeyword" class="form-control" placeholder="Cari nama parameter, paket, kode..." autocomplete="off">
                        <i class="ti ti-x clear-icon" id="btnClearSearch" onclick="clearSearch()" title="Hapus pencarian"></i>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted" style="font-size: 11px;" id="labelSummaryCount"></span>
                    <button class="btn btn-outline-secondary btn-sm shadow-xs" onclick="loadMappingData()" title="Segarkan Data Mapping">
                        <i class="ti ti-refresh me-1"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-vcenter table-hover card-table mb-0" id="tableMappingLab">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th style="width: 60px;" class="text-center">ID</th>
                                <th>Paket Tarif Lab</th>
                                <th>Parameter / Sub-Pemeriksaan</th>
                                <th>Satuan</th>
                                <th>Standar LOINC</th>
                                <th>Spesimen (SNOMED)</th>
                                <th class="text-center" style="width: 120px;">Status</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyMappingLab">
                            <tr><td colspan="8" class="text-center py-4 text-muted">Memuat data mapping...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Mapping Lab -->
    <div class="modal modal-blur fade" id="modalFormMapping" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-teal text-white py-2">
                    <h5 class="modal-title mb-0"><i class="ti ti-adjustments-horizontal me-1"></i> Mapping Parameter Lab ke SatuSehat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formMappingLab">
                    <div class="modal-body p-3">
                        <input type="hidden" id="map_id_template" name="id_template">

                        <div class="alert alert-light border py-2 px-3 mb-2">
                            <div class="text-muted small">Parameter Lab Lokal:</div>
                            <h4 class="mb-0 text-dark" id="map_pemeriksaan_nama">-</h4>
                            <div class="text-muted small" id="map_paket_nama">-</div>
                        </div>

                        <!-- Box Rekomendasi Cepat SatuSehat -->
                        <div id="boxRekomendasiLoinc" class="mb-2 p-2 bg-teal-lt rounded-2 border border-teal-subtle" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div class="fw-bold text-teal small">
                                    <i class="ti ti-bulb me-1"></i> Rekomendasi Kode SatuSehat:
                                </div>
                                <span class="badge bg-teal text-white" style="font-size: 10px;">Otomatis Ditemukan</span>
                            </div>
                            <div id="listRekomendasiLoinc" class="d-flex flex-column gap-1">
                                <!-- Rekomendasi dinamis -->
                            </div>
                        </div>

                        <!-- Pencarian Kamus Cepat LOINC -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small text-muted fw-semibold"><i class="ti ti-book me-1"></i> Kamus LOINC Populer FKTP</span>
                                <a href="javascript:void(0)" class="small text-teal text-decoration-none fw-semibold" id="btnToggleKamus" onclick="toggleKamusLoinc()">
                                    <i class="ti ti-search me-1"></i> Cari dari Kamus
                                </a>
                            </div>
                            <div id="boxKamusLoinc" style="display: none;" class="p-2 bg-light border rounded-2 mb-2">
                                <input type="text" class="form-control form-control-sm mb-2" id="searchKamusInput" placeholder="Ketik nama pemeriksaan (misal: Hb, Gula, Asam Urat, Kolesterol)..." oninput="filterKamusLoinc()">
                                <div id="listKamusResults" class="d-flex flex-column gap-1" style="max-height: 160px; overflow-y: auto;">
                                    <!-- Hasil pencarian kamus -->
                                </div>
                            </div>
                        </div>

                        <!-- Presets Spesimen Cepat -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1">Preset Cepat Spesimen (SNOMED):</label>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119297000', 'Blood specimen')">
                                    🩸 Darah / Whole Blood
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119364003', 'Serum specimen')">
                                    🧪 Serum
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119361006', 'Plasma specimen')">
                                    💧 Plasma
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('122575003', 'Urine specimen')">
                                    🚽 Urin
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="applySpecimenPreset('119339001', 'Stool specimen')">
                                    💩 Feses
                                </button>
                            </div>
                        </div>

                        <hr class="my-2 text-muted">

                        <h6 class="fw-bold text-teal mb-2"><i class="ti ti-code me-1"></i> Standar LOINC (Pemeriksaan)</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="form-label small required">Kode LOINC</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="map_code" name="code" placeholder="Contoh: 718-7" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small required">System Code</label>
                                <input type="text" class="form-control form-control-sm font-monospace bg-light" id="map_system" name="system" value="http://loinc.org" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small required">Nama Display Resmi LOINC</label>
                            <input type="text" class="form-control form-control-sm" id="map_display" name="display" placeholder="Contoh: Hemoglobin [Mass/volume] in Blood" required>
                        </div>

                        <h6 class="fw-bold text-teal mb-2"><i class="ti ti-test-pipe me-1"></i> Standar SNOMED CT (Spesimen)</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="form-label small required">Kode Spesimen</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="map_sampel_code" name="sampel_code" placeholder="Contoh: 119297000" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small required">System Spesimen</label>
                                <input type="text" class="form-control form-control-sm font-monospace bg-light" id="map_sampel_system" name="sampel_system" value="http://snomed.info/sct" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small required">Nama Display Spesimen</label>
                            <input type="text" class="form-control form-control-sm" id="map_sampel_display" name="sampel_display" placeholder="Contoh: Blood specimen" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-teal btn-sm" id="btnSimpanMapping">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Mapping
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const LOINC_DICTIONARY = [
            // --- Hematologi ---
            {
                name: 'Hemoglobin (Hb)',
                keywords: ['hemoglobin', 'hb', 'haemoglobin'],
                code: '718-7',
                display: 'Hemoglobin [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Leukosit (WBC)',
                keywords: ['leukosit', 'leukocyte', 'wbc', 'sel darah putih'],
                code: '6690-2',
                display: 'Leukocytes [#/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Trombosit (Platelet)',
                keywords: ['trombosit', 'thrombocyte', 'platelet', 'plt'],
                code: '777-3',
                display: 'Platelets [#/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Hematokrit (Ht / PCV)',
                keywords: ['hematokrit', 'haematocrit', 'ht', 'pcv'],
                code: '4544-3',
                display: 'Hematocrit [Volume Fraction] of Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Eritrosit (RBC)',
                keywords: ['eritrosit', 'erythrocyte', 'rbc', 'sel darah merah'],
                code: '789-8',
                display: 'Erythrocytes [#/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Laju Endap Darah (LED)',
                keywords: ['laju endap darah', 'led', 'esr', 'westergren', 'sedimentasi'],
                code: '4537-7',
                display: 'Erythrocyte sedimentation rate by Westergren method',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Golongan Darah (ABO)',
                keywords: ['golongan darah', 'goldar', 'abo'],
                code: '883-9',
                display: 'ABO group [Type] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Rhesus Faktor (Rh)',
                keywords: ['rhesus', 'faktor rhesus', 'rh'],
                code: '10331-7',
                display: 'Rh [Type] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'MCV (Mean Corpuscular Volume)',
                keywords: ['mcv'],
                code: '30428-7',
                display: 'MCV [Entitic volume] by Automated count',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'MCH (Mean Corpuscular Hemoglobin)',
                keywords: ['mch'],
                code: '28539-5',
                display: 'MCH [Entitic mass] by Automated count',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'MCHC',
                keywords: ['mchc'],
                code: '28540-3',
                display: 'MCHC [Mass/volume] by Automated count',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Eosinofil',
                keywords: ['eosinofil', 'eosinophil', 'eos'],
                code: '711-2',
                display: 'Eosinophils/100 leukocytes in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Basofil',
                keywords: ['basofil', 'basophil', 'baso'],
                code: '704-7',
                display: 'Basophils/100 leukocytes in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Neutrofil Batang (Stab)',
                keywords: ['batang', 'neutrofil batang', 'stab'],
                code: '764-1',
                display: 'Band form neutrophils/100 leukocytes in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Neutrofil Segmen',
                keywords: ['segmen', 'neutrofil segmen', 'polimorf'],
                code: '763-3',
                display: 'Neutrophils/100 leukocytes in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Limfosit',
                keywords: ['limfosit', 'lymphocyte', 'limfo'],
                code: '736-9',
                display: 'Lymphocytes/100 leukocytes in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Monosit',
                keywords: ['monosit', 'monocyte', 'mono'],
                code: '5905-5',
                display: 'Monocytes/100 leukocytes in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },

            // --- Kimia Darah / Metabolisme ---
            {
                name: 'Gula Darah Sewaktu (GDS)',
                keywords: ['gula darah sewaktu', 'gds', 'glukosa sewaktu', 'glukosa acak', 'gula sewaktu'],
                code: '2345-7',
                display: 'Glucose [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Gula Darah Puasa (GDP)',
                keywords: ['gula darah puasa', 'gdp', 'glukosa puasa'],
                code: '1558-6',
                display: 'Fasting glucose [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Gula Darah 2 Jam PP (GD2JPP)',
                keywords: ['gula darah 2 jam pp', 'gd2jpp', 'glukosa 2 jam pp', 'post prandial'],
                code: '1514-9',
                display: 'Glucose 2 hours post meal [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'HbA1c (Hemoglobin A1c)',
                keywords: ['hba1c', 'a1c', 'glycated hemoglobin'],
                code: '4548-4',
                display: 'Hemoglobin A1c/Hemoglobin.total in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Kolesterol Total',
                keywords: ['kolesterol total', 'cholesterol', 'kolesterol', 'chole'],
                code: '2093-3',
                display: 'Cholesterol [Mass/volume] in Serum or Plasma',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Trigliserida',
                keywords: ['trigliserida', 'trigliserid', 'triglyceride', 'tg'],
                code: '2571-8',
                display: 'Triglyceride [Mass/volume] in Serum or Plasma',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'HDL Kolesterol',
                keywords: ['hdl', 'kolesterol hdl'],
                code: '2085-9',
                display: 'Cholesterol in HDL [Mass/volume] in Serum or Plasma',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'LDL Kolesterol',
                keywords: ['ldl', 'kolesterol ldl'],
                code: '2089-1',
                display: 'Cholesterol in LDL [Mass/volume] in Serum or Plasma',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Asam Urat (Uric Acid)',
                keywords: ['asam urat', 'uric acid', 'urat'],
                code: '3084-1',
                display: 'Urate [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'SGOT (AST)',
                keywords: ['sgot', 'ast', 'aspartate'],
                code: '1920-8',
                display: 'Aspartate aminotransferase [Enzymatic activity/volume] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'SGPT (ALT)',
                keywords: ['sgpt', 'alt', 'alanine'],
                code: '1742-6',
                display: 'Alanine aminotransferase [Enzymatic activity/volume] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Ureum / Urea Nitrogen',
                keywords: ['ureum', 'urea', 'bun', 'urea nitrogen'],
                code: '3094-0',
                display: 'Urea nitrogen [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },
            {
                name: 'Kreatinin (Creatinine)',
                keywords: ['kreatinin', 'creatinine', 'cr'],
                code: '2160-0',
                display: 'Creatinine [Mass/volume] in Blood',
                specimen: { code: '119297000', display: 'Blood specimen' }
            },

            // --- Urinalisis ---
            {
                name: 'Protein Urin (Albumin Urin)',
                keywords: ['protein urin', 'albumin urin', 'proteinuria'],
                code: '2888-6',
                display: 'Protein [Mass/volume] in Urine',
                specimen: { code: '122575003', display: 'Urine specimen' }
            },
            {
                name: 'Glukosa Urin (Reduksi Urin)',
                keywords: ['glukosa urin', 'reduksi urin', 'gula urin'],
                code: '2349-9',
                display: 'Glucose [Mass/volume] in Urine',
                specimen: { code: '122575003', display: 'Urine specimen' }
            },
            {
                name: 'Tes Kehamilan (PP Test / Plano Test)',
                keywords: ['tes kehamilan', 'pp test', 'plano test', 'tes hamil', 'hcg', 'gravida'],
                code: '2106-3',
                display: 'Choriogonadotropin (HCG) in Urine by Rapid test',
                specimen: { code: '122575003', display: 'Urine specimen' }
            },
            {
                name: 'pH Urin',
                keywords: ['ph urin', 'ph'],
                code: '2756-5',
                display: 'pH of Urine',
                specimen: { code: '122575003', display: 'Urine specimen' }
            },
            {
                name: 'Berat Jenis Urin (BJ)',
                keywords: ['berat jenis', 'bj urin', 'specific gravity'],
                code: '2965-2',
                display: 'Specific gravity of Urine',
                specimen: { code: '122575003', display: 'Urine specimen' }
            },
            {
                name: 'Sedimen Urin',
                keywords: ['sedimen', 'sedimen urin', 'epitel'],
                code: '58448-2',
                display: 'Urinalysis microscopic panel - Urine',
                specimen: { code: '122575003', display: 'Urine specimen' }
            },

            // --- Serologi, Imunologi & Infeksi ---
            {
                name: 'Widal Salmonella (Tipoid)',
                keywords: ['widal', 'salmonella', 'tipes', 'thypoid', 'typhoid'],
                code: '23826-1',
                display: 'Salmonella sp antibody [Titer] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'HBsAg Rapid Test',
                keywords: ['hbsag', 'hepatitis b'],
                code: '5196-1',
                display: 'Hepatitis B virus surface Ag [Presence] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Anti HCV Rapid Test',
                keywords: ['anti hcv', 'hcv', 'hepatitis c'],
                code: '13955-0',
                display: 'Hepatitis C virus antibody [Presence] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'HIV Rapid Test',
                keywords: ['hiv', 'anti hiv'],
                code: '75622-1',
                display: 'HIV 1 and 2 antibodies [Presence] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Sifilis / TP Rapid (VDRL)',
                keywords: ['sifilis', 'syphilis', 'vdrl', 'tpha', 'tp rapid'],
                code: '24111-7',
                display: 'Treponema pallidum antibody [Presence] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Dengue NS1 Antigen',
                keywords: ['ns1', 'dengue ns1', 'dbd ns1'],
                code: '69438-0',
                display: 'Dengue virus NS1 Ag [Presence] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'Dengue IgG / IgM Rapid',
                keywords: ['dengue igg', 'dengue igm', 'dbd rapid'],
                code: '41738-6',
                display: 'Dengue virus IgG and IgM [Presence] in Serum',
                specimen: { code: '119364003', display: 'Serum specimen' }
            },
            {
                name: 'BTA Sputum (TB)',
                keywords: ['bta', 'tb', 'tbc', 'sputum bta', 'tahan asam'],
                code: '11545-1',
                display: 'Microscopic observation [Identifier] in Sputum by Acid fast stain',
                specimen: { code: '119334006', display: 'Sputum specimen' }
            },
            {
                name: 'Feses Rutin (Telur Cacing / Amoeba)',
                keywords: ['feses', 'tinja', 'feses rutin', 'cacing', 'amoeba'],
                code: '10701-1',
                display: 'Parasites and Ova [Identifier] in Stool by Concentration',
                specimen: { code: '119339001', display: 'Stool specimen' }
            }
        ];

        let mappingDataStore = [];
        let searchTimer = null;

        $(document).ready(function() {
            loadMappingData();

            $('#searchKeyword').on('input', function() {
                const val = $(this).val();
                if (val.length > 0) {
                    $('#btnClearSearch').show();
                } else {
                    $('#btnClearSearch').hide();
                }
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    loadMappingData();
                }, 350);
            });

            $('#searchKeyword').on('keydown', function(e) {
                if (e.which === 13) {
                    clearTimeout(searchTimer);
                    loadMappingData();
                }
            });

            $('#formMappingLab').on('submit', function(e) {
                e.preventDefault();
                simpanMapping();
            });
        });

        function setStatusFilter(status) {
            $('#filterStatus').val(status);
            $('.filter-segmented-group .btn-filter-status').removeClass('active');
            $(`.filter-segmented-group .btn-filter-status[data-status="${status}"]`).addClass('active');
            loadMappingData();
        }

        function clearSearch() {
            $('#searchKeyword').val('');
            $('#btnClearSearch').hide();
            loadMappingData();
        }

        function loadMappingData() {
            const status = $('#filterStatus').val();
            const search = $('#searchKeyword').val();

            $('#tbodyMappingLab').html('<tr><td colspan="8" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Memuat data mapping...</td></tr>');

            $.get(`{{ route('satusehat.mapping.lab.data') }}`, {
                status: status,
                search: search
            }).done(function(res) {
                if (res.success) {
                    mappingDataStore = res.data;
                    if (res.counts) {
                        $('#badgeCountAll').text(res.counts.all);
                        $('#badgeCountMapped').text(res.counts.mapped);
                        $('#badgeCountUnmapped').text(res.counts.unmapped);
                    }
                    renderMappingTable(res.data);
                } else {
                    $('#tbodyMappingLab').html(`<tr><td colspan="8" class="text-center py-4 text-danger">${res.message}</td></tr>`);
                }
            }).fail(function(err) {
                $('#tbodyMappingLab').html('<tr><td colspan="8" class="text-center py-4 text-danger">Gagal memuat data dari server</td></tr>');
            });
        }

        function renderMappingTable(data) {
            let html = '';
            let mappedCount = 0;
            let unmappedCount = 0;

            if (!data || data.length === 0) {
                $('#tbodyMappingLab').html('<tr><td colspan="8" class="text-center py-4 text-muted"><i class="ti ti-info-circle me-1"></i> Tidak ada data parameter lab yang sesuai</td></tr>');
                $('#labelSummaryCount').text('0 parameter');
                return;
            }

            $('#labelSummaryCount').text(`${data.length} parameter ditemukan`);

            data.forEach(function(item) {
                const isMapped = item.satu_sehat_mapping_lab && item.satu_sehat_mapping_lab.code;
                if (isMapped) mappedCount++; else unmappedCount++;

                const paketNama = item.jenis_perawatan ? item.jenis_perawatan.nm_perawatan : item.kd_jenis_prw;
                const mapInfo = item.satu_sehat_mapping_lab || {};

                html += `
                    <tr>
                        <td class="text-center text-muted font-monospace">${item.id_template}</td>
                        <td>
                            <div class="fw-bold text-dark">${paketNama}</div>
                            <small class="text-muted font-monospace">${item.kd_jenis_prw}</small>
                        </td>
                        <td>
                            <span class="fw-semibold text-primary">${item.Pemeriksaan}</span>
                        </td>
                        <td><small class="badge bg-light text-dark">${item.satuan || '-'}</small></td>
                        <td>
                            ${isMapped ? `
                                <div><code class="fw-bold text-dark">${mapInfo.code}</code></div>
                                <small class="text-muted">${mapInfo.display || '-'}</small>
                            ` : `<span class="text-muted fst-italic">- Belum Dimapping -</span>`}
                        </td>
                        <td>
                            ${isMapped && mapInfo.sampel_code ? `
                                <div><code class="text-dark">${mapInfo.sampel_code}</code></div>
                                <small class="text-muted">${mapInfo.sampel_display || '-'}</small>
                            ` : `<span class="text-muted fst-italic">-</span>`}
                        </td>
                        <td class="text-center">
                            ${isMapped
                                ? `<span class="badge bg-success-lt"><i class="ti ti-check me-1"></i>Mapped</span>`
                                : `<span class="badge bg-warning-lt"><i class="ti ti-alert-triangle me-1"></i>Belum</span>`
                            }
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" onclick="openModalMapping(${item.id_template})" title="Edit Mapping">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                ${isMapped ? `
                                    <button type="button" class="btn btn-outline-danger" onclick="hapusMapping(${item.id_template}, '${item.Pemeriksaan}')" title="Hapus Mapping">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });

            $('#tbodyMappingLab').html(html);
        }

        function findRecommendations(pemeriksaan, paket) {
            const rawText = ((pemeriksaan || '') + ' ' + (paket || '')).toLowerCase();
            const matches = [];

            LOINC_DICTIONARY.forEach((entry, idx) => {
                const isMatched = entry.keywords.some(kw => {
                    const cleanKw = kw.toLowerCase();
                    // Match whole word or exact token match if keyword length <= 3 (like 'hb', 'led', 'ht')
                    if (cleanKw.length <= 3) {
                        const regex = new RegExp(`(^|[^a-z0-9])${cleanKw}([^a-z0-9]|$)`, 'i');
                        return regex.test(rawText);
                    }
                    return rawText.includes(cleanKw);
                });

                if (isMatched) {
                    matches.push({ ...entry, index: idx });
                }
            });

            return matches;
        }

        function openModalMapping(id_template) {
            const item = mappingDataStore.find(x => x.id_template === id_template);
            if (!item) return;

            $('#map_id_template').val(item.id_template);
            $('#map_pemeriksaan_nama').text(item.Pemeriksaan);
            const paketNama = item.jenis_perawatan ? item.jenis_perawatan.nm_perawatan : item.kd_jenis_prw;
            $('#map_paket_nama').text(paketNama);

            const map = item.satu_sehat_mapping_lab || {};
            $('#map_code').val(map.code || '');
            $('#map_system').val(map.system || 'http://loinc.org');
            $('#map_display').val(map.display || item.Pemeriksaan);
            $('#map_sampel_code').val(map.sampel_code || '119297000');
            $('#map_sampel_system').val(map.sampel_system || 'http://snomed.info/sct');
            $('#map_sampel_display').val(map.sampel_display || 'Blood specimen');

            // Reset box kamus
            $('#boxKamusLoinc').hide();

            // Cari rekomendasi LOINC berdasarkan nama parameter lokal
            const recs = findRecommendations(item.Pemeriksaan, paketNama);
            if (recs.length > 0) {
                let recHtml = '';
                recs.forEach(rec => {
                    recHtml += `
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded border shadow-xs">
                            <div class="me-2">
                                <div class="fw-bold text-dark" style="font-size: 11.5px;">
                                    <span class="badge bg-teal text-white font-monospace me-1">${rec.code}</span> ${rec.name}
                                </div>
                                <div class="text-muted" style="font-size: 10.5px;">
                                    ${rec.display} &bull; <span class="text-teal">${rec.specimen.display}</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-teal btn-xs py-1 px-2 text-nowrap shadow-xs" onclick="applyLoincByIndex(${rec.index})">
                                <i class="ti ti-check me-1"></i> Pakai Ini
                            </button>
                        </div>
                    `;
                });
                $('#listRekomendasiLoinc').html(recHtml);
                $('#boxRekomendasiLoinc').show();
            } else {
                $('#boxRekomendasiLoinc').hide();
            }

            $('#modalFormMapping').modal('show');
        }

        function applyLoincByIndex(idx) {
            const entry = LOINC_DICTIONARY[idx];
            if (!entry) return;

            $('#map_code').val(entry.code);
            $('#map_system').val('http://loinc.org');
            $('#map_display').val(entry.display);

            if (entry.specimen) {
                $('#map_sampel_code').val(entry.specimen.code);
                $('#map_sampel_system').val('http://snomed.info/sct');
                $('#map_sampel_display').val(entry.specimen.display);
            }

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Rekomendasi "${entry.name}" (${entry.code}) berhasil diterapkan`,
                showConfirmButton: false,
                timer: 1600
            });
        }

        function toggleKamusLoinc() {
            const box = $('#boxKamusLoinc');
            if (box.is(':visible')) {
                box.slideUp(150);
            } else {
                box.slideDown(150);
                $('#searchKamusInput').val('').focus();
                filterKamusLoinc();
            }
        }

        function filterKamusLoinc() {
            const query = ($('#searchKamusInput').val() || '').toLowerCase().trim();
            let results = LOINC_DICTIONARY;
            if (query) {
                results = LOINC_DICTIONARY.filter(item => {
                    return item.name.toLowerCase().includes(query) ||
                           item.code.toLowerCase().includes(query) ||
                           item.keywords.some(k => k.toLowerCase().includes(query));
                });
            }

            if (results.length === 0) {
                $('#listKamusResults').html('<div class="text-muted small p-2 text-center">Tidak ada kode LOINC yang cocok di kamus</div>');
                return;
            }

            let html = '';
            results.forEach((rec) => {
                const originalIndex = LOINC_DICTIONARY.indexOf(rec);
                html += `
                    <div class="d-flex align-items-center justify-content-between bg-white p-1 px-2 rounded border shadow-xs">
                        <div>
                            <span class="badge bg-teal-lt font-monospace me-1">${rec.code}</span>
                            <strong class="text-dark" style="font-size: 11px;">${rec.name}</strong>
                            <div class="text-muted" style="font-size: 10px;">${rec.display} &bull; ${rec.specimen.display}</div>
                        </div>
                        <button type="button" class="btn btn-outline-teal btn-xs py-0 px-2 text-nowrap ms-2" onclick="applyLoincByIndex(${originalIndex})">
                            Pilih
                        </button>
                    </div>
                `;
            });
            $('#listKamusResults').html(html);
        }

        function applySpecimenPreset(code, display) {
            $('#map_sampel_code').val(code);
            $('#map_sampel_system').val('http://snomed.info/sct');
            $('#map_sampel_display').val(display);
        }

        function simpanMapping() {
            const payload = {
                id_template: $('#map_id_template').val(),
                code: $('#map_code').val(),
                system: $('#map_system').val(),
                display: $('#map_display').val(),
                sampel_code: $('#map_sampel_code').val(),
                sampel_system: $('#map_sampel_system').val(),
                sampel_display: $('#map_sampel_display').val(),
            };

            $('#btnSimpanMapping').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

            $.post(`{{ route('satusehat.mapping.lab.save') }}`, payload)
                .done(function(res) {
                    $('#btnSimpanMapping').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Mapping');
                    if (res.success) {
                        $('#modalFormMapping').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadMappingData();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }).fail(function(err) {
                    $('#btnSimpanMapping').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Simpan Mapping');
                    const msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Terjadi kesalahan sistem';
                    Swal.fire('Error', msg, 'error');
                });
        }

        function hapusMapping(id_template, nama) {
            Swal.fire({
                title: 'Hapus Mapping?',
                text: `Hapus mapping kode LOINC untuk "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('satusehat/mapping/lab') }}/${id_template}`,
                        type: 'DELETE',
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadMappingData();
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        },
                        error: function(err) {
                            Swal.fire('Error', 'Gagal menghapus data mapping', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush

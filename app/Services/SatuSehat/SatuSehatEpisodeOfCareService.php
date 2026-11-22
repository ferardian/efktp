<?php

namespace App\Services\SatuSehat;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SatuSehatEpisodeOfCareService
{
    protected SatuSehatService $ss;
    protected string $orgId;

    public function __construct(?SatuSehatService $ss = null)
    {
        $this->ss    = $ss ?? app(SatuSehatService::class);
        $this->orgId = (string) config('satusehat.org_id');
        $this->ensureTablesExist();
    }

    public function getOrgId(): string
    {
        return $this->orgId;
    }

    /**
     * Pastikan tabel satu_sehat_episode_of_care tersedia di database
     */
    public function ensureTablesExist(): void
    {
        try {
            $schema = DB::getSchemaBuilder();
            if (!$schema->hasTable('satu_sehat_episode_of_care')) {
                $schema->create('satu_sehat_episode_of_care', function ($table) {
                    $table->bigIncrements('id');
                    $table->string('no_rawat', 17)->nullable();
                    $table->string('no_rkm_medis', 15);
                    $table->string('tipe', 20)->default('ANC'); // ANC, TB, DM, HT, UMUM
                    $table->string('status', 20)->default('active'); // active, finished, cancelled
                    $table->string('id_episode_of_care', 64)->nullable();
                    $table->string('id_condition_primary', 64)->nullable();
                    $table->date('tgl_mulai');
                    $table->date('tgl_selesai')->nullable();
                    $table->timestamps();

                    $table->index(['no_rkm_medis', 'tipe', 'status'], 'idx_eoc_rm_tipe_status');
                    $table->index('no_rawat', 'idx_eoc_no_rawat');
                });
            }
        } catch (\Throwable $e) {
            Log::channel('satusehat')->warning("[SatuSehat] Auto ensure table episode_of_care failed: " . $e->getMessage());
        }
    }

    /**
     * Lookup Patient IHS ID with 24h caching
     */
    public function getPatientId(?string $nik): ?string
    {
        if (empty($nik)) {
            return null;
        }

        try {
            return Cache::remember("satusehat_patient_{$nik}", 86400, function () use ($nik) {
                return $this->fetchPatientIdDirectly($nik);
            });
        } catch (\Throwable $e) {
            return $this->fetchPatientIdDirectly($nik);
        }
    }

    private function fetchPatientIdDirectly(string $nik): ?string
    {
        $result = $this->ss->get('Patient', [
            'identifier' => "https://fhir.kemkes.go.id/id/nik|{$nik}"
        ]);
        return data_get($result, 'data.entry.0.resource.id');
    }

    /**
     * Lookup Practitioner IHS ID with 24h caching
     */
    public function getPractitionerId(?string $nik): ?string
    {
        if (empty($nik)) {
            return null;
        }

        try {
            return Cache::remember("satusehat_practitioner_{$nik}", 86400, function () use ($nik) {
                return $this->fetchPractitionerIdDirectly($nik);
            });
        } catch (\Throwable $e) {
            return $this->fetchPractitionerIdDirectly($nik);
        }
    }

    private function fetchPractitionerIdDirectly(string $nik): ?string
    {
        $result = $this->ss->get('Practitioner', [
            'identifier' => "https://fhir.kemkes.go.id/id/nik|{$nik}"
        ]);
        return data_get($result, 'data.entry.0.resource.id');
    }

    /**
     * Deteksi tipe EpisodeOfCare berdasarkan kode diagnosis ICD-10
     */
    public function detectEpisodeType(string $icd10): string
    {
        $icd10 = strtoupper(trim($icd10));

        if (str_starts_with($icd10, 'Z34') || str_starts_with($icd10, 'Z35') || str_starts_with($icd10, 'O')) {
            return 'ANC';
        }
        if (str_starts_with($icd10, 'A15') || str_starts_with($icd10, 'A16') || str_starts_with($icd10, 'A17') || str_starts_with($icd10, 'A18') || str_starts_with($icd10, 'A19')) {
            return 'TB';
        }
        if (str_starts_with($icd10, 'E10') || str_starts_with($icd10, 'E11') || str_starts_with($icd10, 'E12') || str_starts_with($icd10, 'E13') || str_starts_with($icd10, 'E14')) {
            return 'DM';
        }
        if (str_starts_with($icd10, 'I10') || str_starts_with($icd10, 'I11') || str_starts_with($icd10, 'I12') || str_starts_with($icd10, 'I13') || str_starts_with($icd10, 'I15')) {
            return 'HT';
        }

        return 'UMUM';
    }

    /**
     * Dapatkan metadata episode berdasarkan tipe
     */
    public function getEpisodeMetadata(string $tipe): array
    {
        return match ($tipe) {
            'ANC' => [
                'code'    => 'ANC',
                'display' => 'Antenatal Care',
                'system'  => 'http://terminology.kemkes.go.id/CodeSystem/episodeofcare-type',
                'label'   => 'Pemeriksaan Kehamilan (ANC)'
            ],
            'TB' => [
                'code'    => 'TB',
                'display' => 'Tuberculosis Program',
                'system'  => 'http://terminology.kemkes.go.id/CodeSystem/episodeofcare-type',
                'label'   => 'Program Tuberkulosis (TB)'
            ],
            'DM' => [
                'code'    => 'DM',
                'display' => 'Diabetes Mellitus Care',
                'system'  => 'http://terminology.kemkes.go.id/CodeSystem/episodeofcare-type',
                'label'   => 'Pengelolaan Diabetes Mellitus'
            ],
            'HT' => [
                'code'    => 'HT',
                'display' => 'Hypertension Care',
                'system'  => 'http://terminology.kemkes.go.id/CodeSystem/episodeofcare-type',
                'label'   => 'Pengelolaan Hipertensi'
            ],
            default => [
                'code'    => 'UMUM',
                'display' => 'General Episode of Care',
                'system'  => 'http://terminology.kemkes.go.id/CodeSystem/episodeofcare-type',
                'label'   => 'Episode Perawatan Umum'
            ]
        };
    }

    /**
     * Ambil active episode pasien
     */
    public function getActiveEpisode(string $noRkmMedis, string $tipe = 'ANC'): ?string
    {
        $row = DB::table('satu_sehat_episode_of_care')
            ->where('no_rkm_medis', $noRkmMedis)
            ->where('tipe', $tipe)
            ->where('status', 'active')
            ->first();

        return $row ? $row->id_episode_of_care : null;
    }

    /**
     * Ambil daftar data EpisodeOfCare
     */
    public function getEpisodeOfCareList(array $params = []): array
    {
        $tglAwal  = $params['tgl_awal'] ?? date('Y-m-d');
        $tglAkhir = $params['tgl_akhir'] ?? $tglAwal;
        $search   = $params['search'] ?? null;
        $tipe     = $params['tipe'] ?? 'all';     // all, ANC, TB, DM, HT, UMUM
        $status   = $params['status'] ?? 'all';   // all, synced, unsent

        $query = DB::table('diagnosa_pasien AS dp')
            ->join('reg_periksa AS rp', 'rp.no_rawat', '=', 'dp.no_rawat')
            ->join('pasien AS p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('penyakit AS py', 'py.kd_penyakit', '=', 'dp.kd_penyakit')
            ->leftJoin('pegawai AS peg', 'peg.nik', '=', 'rp.kd_dokter')
            ->leftJoin('satu_sehat_encounter AS se', 'se.no_rawat', '=', 'dp.no_rawat')
            ->leftJoin('satu_sehat_condition AS sc', function ($join) {
                $join->on('sc.no_rawat', '=', 'dp.no_rawat')
                     ->on('sc.kd_penyakit', '=', 'dp.kd_penyakit');
            })
            ->leftJoin('satu_sehat_episode_of_care AS sec', function ($join) {
                $join->on('sec.no_rkm_medis', '=', 'p.no_rkm_medis');
            })
            ->whereBetween('rp.tgl_registrasi', [$tglAwal, $tglAkhir])
            ->where(function ($q) {
                // Trigger episode diagnosa: ANC, TB, DM, HT
                $q->where('dp.kd_penyakit', 'LIKE', 'Z34%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'Z35%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'O%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'A15%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'A16%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'A17%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'A18%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'A19%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'E10%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'E11%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'I10%')
                  ->orWhere('dp.kd_penyakit', 'LIKE', 'I11%');
            })
            ->select([
                'dp.no_rawat',
                'dp.kd_penyakit',
                'dp.status AS status_diagnosa',
                'dp.prioritas',
                'py.nm_penyakit',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.no_rkm_medis',
                'rp.status_lanjut',
                'p.nm_pasien',
                'p.no_ktp AS ktp_pasien',
                'peg.nama AS nm_dokter',
                'peg.no_ktp AS ktp_dokter',
                'se.id_encounter',
                'sc.id_condition',
                'sec.id_episode_of_care',
                'sec.tipe AS eoc_tipe',
                'sec.status AS eoc_status',
                'sec.tgl_mulai AS eoc_tgl_mulai',
            ])
            ->distinct();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('dp.no_rawat', 'LIKE', "%{$search}%")
                  ->orWhere('p.nm_pasien', 'LIKE', "%{$search}%")
                  ->orWhere('p.no_rkm_medis', 'LIKE', "%{$search}%")
                  ->orWhere('dp.kd_penyakit', 'LIKE', "%{$search}%")
                  ->orWhere('py.nm_penyakit', 'LIKE', "%{$search}%")
                  ->orWhere('peg.nama', 'LIKE', "%{$search}%");
            });
        }

        $rows = $query->orderByDesc('rp.tgl_registrasi')
            ->orderByDesc('rp.jam_reg')
            ->get();

        return $rows->map(function ($item) use ($tipe, $status) {
            $computedTipe = $this->detectEpisodeType($item->kd_penyakit);
            $meta = $this->getEpisodeMetadata($computedTipe);

            $item->tipe_program = $computedTipe;
            $item->tipe_display = $meta['display'];
            $item->tipe_label   = $meta['label'];

            return (array) $item;
        })
        ->filter(function ($item) use ($tipe, $status) {
            if ($tipe !== 'all' && $item['tipe_program'] !== $tipe) {
                return false;
            }
            if ($status === 'synced' && empty($item['id_episode_of_care'])) {
                return false;
            }
            if ($status === 'unsent' && !empty($item['id_episode_of_care'])) {
                return false;
            }
            return true;
        })
        ->values()
        ->toArray();
    }

    /**
     * Kirim data EpisodeOfCare tunggal
     */
    public function syncSingleEpisode(string $noRawat, string $kdPenyakit): array
    {
        $row = DB::table('diagnosa_pasien AS dp')
            ->join('reg_periksa AS rp', 'rp.no_rawat', '=', 'dp.no_rawat')
            ->join('pasien AS p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('penyakit AS py', 'py.kd_penyakit', '=', 'dp.kd_penyakit')
            ->leftJoin('pegawai AS peg', 'peg.nik', '=', 'rp.kd_dokter')
            ->leftJoin('satu_sehat_encounter AS se', 'se.no_rawat', '=', 'dp.no_rawat')
            ->leftJoin('satu_sehat_condition AS sc', function ($join) {
                $join->on('sc.no_rawat', '=', 'dp.no_rawat')
                     ->on('sc.kd_penyakit', '=', 'dp.kd_penyakit');
            })
            ->leftJoin('satu_sehat_episode_of_care AS sec', 'sec.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->where('dp.no_rawat', $noRawat)
            ->where('dp.kd_penyakit', $kdPenyakit)
            ->select([
                'dp.no_rawat', 'dp.kd_penyakit', 'py.nm_penyakit',
                'rp.tgl_registrasi', 'rp.no_rkm_medis',
                'p.nm_pasien', 'p.no_ktp AS ktp_pasien',
                'peg.nama AS nm_dokter', 'peg.no_ktp AS ktp_dokter',
                'se.id_encounter', 'sc.id_condition',
                'sec.id_episode_of_care', 'sec.status AS eoc_status'
            ])
            ->first();

        if (!$row) {
            return ['success' => false, 'message' => 'Data diagnosa pasien tidak ditemukan'];
        }

        if (empty($row->id_condition)) {
            return ['success' => false, 'message' => 'Condition SatuSehat belum tersinkronisasi untuk diagnosa ini. Harap sync Condition terlebih dahulu.'];
        }

        if (empty($row->ktp_pasien)) {
            return ['success' => false, 'message' => 'NIK Pasien kosong atau tidak valid'];
        }

        if (empty($row->ktp_dokter)) {
            return ['success' => false, 'message' => 'NIK Dokter pemeriksa kosong atau tidak valid'];
        }

        $tipe = $this->detectEpisodeType($row->kd_penyakit);

        // Cek apakah pasien sudah memiliki EpisodeOfCare aktif untuk tipe ini
        $activeId = $this->getActiveEpisode($row->no_rkm_medis, $tipe);
        if ($activeId) {
            return [
                'success'            => true,
                'id_episode_of_care' => $activeId,
                'message'            => "Pasien sudah memiliki EpisodeOfCare {$tipe} aktif ({$activeId})"
            ];
        }

        try {
            $idPasien  = $this->getPatientId($row->ktp_pasien);
            $idDokter  = $this->getPractitionerId($row->ktp_dokter);

            if (!$idPasien) {
                return ['success' => false, 'message' => 'IHS Pasien tidak ditemukan di platform SatuSehat'];
            }
            if (!$idDokter) {
                return ['success' => false, 'message' => 'IHS Dokter tidak ditemukan di platform SatuSehat'];
            }

            $meta = $this->getEpisodeMetadata($tipe);
            $namaDisplay = trim(explode(',', $row->nm_pasien ?? '')[0]);
            $tglMulai    = date('Y-m-d\TH:i:sP', strtotime($row->tgl_registrasi));
            $identifierVal = "{$tipe}-{$row->no_rkm_medis}-" . date('Y', strtotime($row->tgl_registrasi));

            $payload = [
                'resourceType' => 'EpisodeOfCare',
                'identifier'   => [[
                    'system' => "http://sys-ids.kemkes.go.id/episode-of-care/{$this->orgId}",
                    'value'  => $identifierVal,
                ]],
                'status' => 'active',
                'type'   => [[
                    'coding' => [[
                        'system'  => $meta['system'],
                        'code'    => $meta['code'],
                        'display' => $meta['display'],
                    ]],
                ]],
                'diagnosis' => [[
                    'condition' => [
                        'reference' => "Condition/{$row->id_condition}",
                        'display'   => $row->nm_penyakit ?? $meta['display'],
                    ],
                    'role' => [
                        'coding' => [[
                            'system'  => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
                            'code'    => 'DD',
                            'display' => 'Discharged Diagnosis',
                        ]],
                    ],
                    'rank' => 1,
                ]],
                'patient' => [
                    'reference' => "Patient/{$idPasien}",
                    'display'   => $namaDisplay,
                ],
                'managingOrganization' => [
                    'reference' => "Organization/{$this->orgId}",
                ],
                'period' => [
                    'start' => $tglMulai,
                ],
                'careManager' => [
                    'reference' => "Practitioner/{$idDokter}",
                    'display'   => $row->nm_dokter ?? '',
                ],
            ];

            $response = $this->ss->post('EpisodeOfCare', $payload);
            $episodeId = data_get($response, 'data.id') ?? data_get($response, 'id');

            if ($episodeId) {
                DB::table('satu_sehat_episode_of_care')->updateOrInsert(
                    [
                        'no_rkm_medis' => $row->no_rkm_medis,
                        'tipe'         => $tipe,
                        'status'       => 'active',
                    ],
                    [
                        'no_rawat'             => $row->no_rawat,
                        'id_episode_of_care'   => $episodeId,
                        'id_condition_primary' => $row->id_condition,
                        'tgl_mulai'            => date('Y-m-d', strtotime($row->tgl_registrasi)),
                        'updated_at'           => now(),
                    ]
                );

                return [
                    'success'            => true,
                    'id_episode_of_care' => $episodeId,
                    'message'            => "EpisodeOfCare {$tipe} berhasil disinkronkan"
                ];
            }

            // Penanganan bila sudah terdaftar di SatuSehat (Duplicate recovery)
            $errorMsg  = data_get($response, 'data.issue.0.details.text') ?? data_get($response, 'message') ?? 'Gagal membuat EpisodeOfCare';
            if (str_contains(strtolower($errorMsg), 'duplicate') || str_contains(strtolower($errorMsg), 'already exists')) {
                $search = $this->ss->get("EpisodeOfCare?patient=Patient/{$idPasien}&status=active");
                $existingId = data_get($search, 'data.entry.0.resource.id') ?? data_get($search, 'entry.0.resource.id');
                if ($existingId) {
                    DB::table('satu_sehat_episode_of_care')->updateOrInsert(
                        [
                            'no_rkm_medis' => $row->no_rkm_medis,
                            'tipe'         => $tipe,
                            'status'       => 'active',
                        ],
                        [
                            'no_rawat'             => $row->no_rawat,
                            'id_episode_of_care'   => $existingId,
                            'id_condition_primary' => $row->id_condition,
                            'tgl_mulai'            => date('Y-m-d', strtotime($row->tgl_registrasi)),
                            'updated_at'           => now(),
                        ]
                    );

                    return [
                        'success'            => true,
                        'id_episode_of_care' => $existingId,
                        'message'            => "EpisodeOfCare {$tipe} berhasil dipulihkan dari SatuSehat"
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $errorMsg,
                'details' => $response
            ];
        } catch (\Throwable $e) {
            Log::channel('satusehat')->error("[SatuSehat] Exception EpisodeOfCare: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sinkronisasi massal (Batch Sync)
     */
    public function syncBatchEpisode(string $tglAwal, string $tglAkhir, array $keys = []): array
    {
        $results = ['success' => 0, 'error' => 0, 'skip' => 0, 'details' => []];

        if (!empty($keys)) {
            $items = $keys;
        } else {
            $items = $this->getEpisodeOfCareList([
                'tgl_awal'  => $tglAwal,
                'tgl_akhir' => $tglAkhir,
                'status'    => 'unsent'
            ]);
        }

        foreach ($items as $item) {
            $obj = (object) $item;

            if (empty($obj->id_condition)) {
                $results['skip']++;
                $results['details'][] = [
                    'no_rawat' => $obj->no_rawat,
                    'status'   => 'skip',
                    'message'  => 'Condition belum disinkronkan'
                ];
                continue;
            }

            $res = $this->syncSingleEpisode($obj->no_rawat, $obj->kd_penyakit);

            if ($res['success']) {
                $results['success']++;
                $results['details'][] = [
                    'no_rawat'           => $obj->no_rawat,
                    'status'             => 'success',
                    'id_episode_of_care' => $res['id_episode_of_care']
                ];
            } else {
                $results['error']++;
                $results['details'][] = [
                    'no_rawat' => $obj->no_rawat,
                    'status'   => 'error',
                    'message'  => $res['message']
                ];
            }
        }

        return $results;
    }
}

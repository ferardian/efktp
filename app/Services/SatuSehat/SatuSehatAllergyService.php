<?php

namespace App\Services\SatuSehat;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SatuSehatAllergyService
{
    protected SatuSehatService $ss;
    protected string $orgId;

    public function __construct(?SatuSehatService $ss = null)
    {
        $this->ss    = $ss ?? app(SatuSehatService::class);
        $this->orgId = (string) config('satusehat.org_id');
    }

    public function getOrgId(): string
    {
        return $this->orgId;
    }

    /**
     * Daftar kata kunci yang bukan merupakan alergi valid (negatif/disangkal/kosong)
     */
    public static function getNegativeKeywords(): array
    {
        return [
            '', '-', '--', '---', '.', '..', 'tidak ada', 'tidak', 'tdk ada', 'tdk',
            'disangkal', 'disnagkal', 'nihil', 'belum tahu', 'belum ada', 'none', 'no',
            'tidak ada alergi', 'tdk ada alergi', 'negatif', 'ta'
        ];
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
            Log::channel('satusehat')->warning("[SatuSehat] Cache failed for patient NIK:{$nik}: " . $e->getMessage());
            return $this->fetchPatientIdDirectly($nik);
        }
    }

    private function fetchPatientIdDirectly(string $nik): ?string
    {
        $result = $this->ss->get('Patient', [
            'identifier' => "https://fhir.kemkes.go.id/id/nik|{$nik}"
        ]);

        $id = data_get($result, 'data.entry.0.resource.id');
        if ($id) {
            Log::channel('satusehat')->info("[SatuSehat] Patient NIK:{$nik} -> ID:{$id}");
        } else {
            Log::channel('satusehat')->warning("[SatuSehat] Patient NIK:{$nik} tidak ditemukan di SatuSehat");
        }
        return $id;
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
            Log::channel('satusehat')->warning("[SatuSehat] Cache failed for practitioner NIK:{$nik}: " . $e->getMessage());
            return $this->fetchPractitionerIdDirectly($nik);
        }
    }

    private function fetchPractitionerIdDirectly(string $nik): ?string
    {
        $result = $this->ss->get('Practitioner', [
            'identifier' => "https://fhir.kemkes.go.id/id/nik|{$nik}"
        ]);

        $id = data_get($result, 'data.entry.0.resource.id');
        if ($id) {
            Log::channel('satusehat')->info("[SatuSehat] Practitioner NIK:{$nik} -> ID:{$id}");
        } else {
            Log::channel('satusehat')->warning("[SatuSehat] Practitioner NIK:{$nik} tidak ditemukan di SatuSehat");
        }
        return $id;
    }

    /**
     * Query data alergi gabungan dari SOAP Ralan, SOAP Ranap, dan IGD
     */
    public function getAllergyList(array $params = []): array
    {
        $tglAwal  = $params['tgl_awal'] ?? date('Y-m-d');
        $tglAkhir = $params['tgl_akhir'] ?? $tglAwal;
        $search   = $params['search'] ?? null;
        $status   = $params['status'] ?? 'all'; // all, synced, unsent

        $negativeList = self::getNegativeKeywords();
        $inNegatives  = "'" . implode("','", $negativeList) . "'";

        // Cek apakah tabel penilaian_medis_igd ada
        $hasIgd = DB::getSchemaBuilder()->hasTable('penilaian_medis_igd');

        $unionParts = [
            "SELECT no_rawat, tgl_perawatan, jam_rawat, 'Ralan (SOAP)' AS sumber, TRIM(alergi) AS alergi_text, nip
             FROM pemeriksaan_ralan
             WHERE alergi IS NOT NULL AND TRIM(LOWER(alergi)) NOT IN ({$inNegatives})",
            "SELECT no_rawat, tgl_perawatan, jam_rawat, 'Ranap (SOAP)' AS sumber, TRIM(alergi) AS alergi_text, nip
             FROM pemeriksaan_ranap
             WHERE alergi IS NOT NULL AND TRIM(LOWER(alergi)) NOT IN ({$inNegatives})"
        ];

        if ($hasIgd) {
            $unionParts[] = "SELECT no_rawat, DATE(tanggal) AS tgl_perawatan, TIME(tanggal) AS jam_rawat, 'IGD (Asesmen)' AS sumber, TRIM(alergi) AS alergi_text, kd_dokter AS nip
                             FROM penilaian_medis_igd
                             WHERE alergi IS NOT NULL AND TRIM(LOWER(alergi)) NOT IN ({$inNegatives})";
        }

        $unionSql = implode(" UNION ALL ", $unionParts);

        $builder = DB::table(DB::raw("({$unionSql}) AS raw_allergy"))
            ->join('reg_periksa AS rp', 'rp.no_rawat', '=', 'raw_allergy.no_rawat')
            ->join('pasien AS p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftJoin('pegawai AS peg', 'peg.nik', '=', 'raw_allergy.nip')
            ->leftJoin('satu_sehat_encounter AS se', 'se.no_rawat', '=', 'raw_allergy.no_rawat')
            ->leftJoin('satu_sehat_allergy AS ssa', function ($join) {
                $join->on('ssa.no_rawat', '=', 'raw_allergy.no_rawat')
                    ->on('ssa.tgl_perawatan', '=', 'raw_allergy.tgl_perawatan')
                    ->on('ssa.jam_rawat', '=', 'raw_allergy.jam_rawat')
                    ->on('ssa.alergi_text', '=', 'raw_allergy.alergi_text');
            })
            ->whereBetween('raw_allergy.tgl_perawatan', [$tglAwal, $tglAkhir])
            ->select([
                'raw_allergy.no_rawat',
                'raw_allergy.tgl_perawatan',
                'raw_allergy.jam_rawat',
                'raw_allergy.sumber',
                'raw_allergy.alergi_text',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.no_rkm_medis',
                'rp.status_lanjut',
                'p.nm_pasien',
                'p.no_ktp',
                'se.id_encounter',
                'peg.nama AS nm_petugas',
                'peg.no_ktp AS ktppraktisi',
                'ssa.id_allergy',
                'ssa.updated_at AS sync_updated_at',
            ]);

        if (!empty($search)) {
            $builder->where(function ($q) use ($search) {
                $q->where('raw_allergy.no_rawat', 'LIKE', "%{$search}%")
                    ->orWhere('p.nm_pasien', 'LIKE', "%{$search}%")
                    ->orWhere('rp.no_rkm_medis', 'LIKE', "%{$search}%")
                    ->orWhere('raw_allergy.alergi_text', 'LIKE', "%{$search}%")
                    ->orWhere('peg.nama', 'LIKE', "%{$search}%");
            });
        }

        if ($status === 'synced') {
            $builder->whereNotNull('ssa.id_allergy');
        } elseif ($status === 'unsent') {
            $builder->whereNull('ssa.id_allergy');
        }

        $builder->orderBy('raw_allergy.tgl_perawatan', 'DESC')
            ->orderBy('raw_allergy.jam_rawat', 'DESC');

        $rows = $builder->get();

        // Ambil mapping untuk mencocokkan tiap record
        $allMappings = DB::table('satu_sehat_mapping_alergi')->get();

        return $rows->map(function ($item) use ($allMappings) {
            $matched = $this->findMatchInCollection($item->alergi_text, $allMappings);

            if ($matched) {
                $item->mapping_id      = $matched->id;
                $item->category        = $matched->category;
                $item->mapping_code    = $matched->code;
                $item->mapping_system  = $matched->system;
                $item->mapping_display = $matched->display;
                $item->is_mapped       = true;
            } else {
                // Fallback otomatis jika belum dimapping manual
                $fallback = $this->guessFallbackMapping($item->alergi_text);
                $item->mapping_id      = null;
                $item->category        = $fallback['category'];
                $item->mapping_code    = $fallback['code'];
                $item->mapping_system  = $fallback['system'];
                $item->mapping_display = $fallback['display'];
                $item->is_mapped       = false; // Belum manual mapping, tapi ada fallback
            }

            return (array) $item;
        })->toArray();
    }

    /**
     * Mencocokkan teks alergi dengan koleksi mapping terminologi
     */
    protected function findMatchInCollection(string $alergiText, $allMappings): ?object
    {
        $clean = strtolower(trim($alergiText));

        // 1. Exact match
        foreach ($allMappings as $map) {
            if (strtolower(trim($map->keyword)) === $clean) {
                return $map;
            }
        }

        // 2. Partial match
        foreach ($allMappings as $map) {
            $kw = strtolower(trim($map->keyword));
            if (!empty($kw) && (str_contains($clean, $kw) || str_contains($kw, $clean))) {
                return $map;
            }
        }

        return null;
    }

    /**
     * Guess fallback category and SNOMED code jika belum di-mapping manual
     */
    public function guessFallbackMapping(string $text): array
    {
        $lower = strtolower(trim($text));

        // Obat-obatan
        $medKeywords = ['obat', 'penisilin', 'amox', 'amoksisilin', 'paracetamol', 'pct', 'asam mefenamat', 'antalgin', 'sulfa', 'aspirin', 'ibuprofen', 'ampisilin', 'cefixime', 'ciprofloxacin', 'antibiotik'];
        foreach ($medKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return [
                    'category' => 'medication',
                    'code'     => '416098002',
                    'system'   => 'http://snomed.info/sct',
                    'display'  => 'Allergy to drug (finding)',
                ];
            }
        }

        // Lingkungan
        $envKeywords = ['debu', 'dust', 'dingin', 'cuaca', 'panas', 'serbuk', 'bulu', 'asap', 'matahari'];
        foreach ($envKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return [
                    'category' => 'environment',
                    'code'     => '426232007',
                    'system'   => 'http://snomed.info/sct',
                    'display'  => 'Environmental allergy (finding)',
                ];
            }
        }

        // Default: Food Allergy
        return [
            'category' => 'food',
            'code'     => '235719002',
            'system'   => 'http://snomed.info/sct',
            'display'  => 'Food allergy (finding)',
        ];
    }

    /**
     * Ambil data spesifik alergi untuk sinkronisasi realtime
     */
    public function getSpecificAllergy(string $noRawat, string $tglPerawatan, string $jamRawat, string $alergiText): ?object
    {
        $negativeList = self::getNegativeKeywords();
        $inNegatives  = "'" . implode("','", $negativeList) . "'";

        $hasIgd = DB::getSchemaBuilder()->hasTable('penilaian_medis_igd');

        $unionParts = [
            "SELECT no_rawat, tgl_perawatan, jam_rawat, 'Ralan (SOAP)' AS sumber, TRIM(alergi) AS alergi_text, nip
             FROM pemeriksaan_ralan
             WHERE alergi IS NOT NULL AND TRIM(LOWER(alergi)) NOT IN ({$inNegatives})",
            "SELECT no_rawat, tgl_perawatan, jam_rawat, 'Ranap (SOAP)' AS sumber, TRIM(alergi) AS alergi_text, nip
             FROM pemeriksaan_ranap
             WHERE alergi IS NOT NULL AND TRIM(LOWER(alergi)) NOT IN ({$inNegatives})"
        ];

        if ($hasIgd) {
            $unionParts[] = "SELECT no_rawat, DATE(tanggal) AS tgl_perawatan, TIME(tanggal) AS jam_rawat, 'IGD (Asesmen)' AS sumber, TRIM(alergi) AS alergi_text, kd_dokter AS nip
                             FROM penilaian_medis_igd
                             WHERE alergi IS NOT NULL AND TRIM(LOWER(alergi)) NOT IN ({$inNegatives})";
        }

        $unionSql = implode(" UNION ALL ", $unionParts);

        return DB::table(DB::raw("({$unionSql}) AS raw_allergy"))
            ->join('reg_periksa AS rp', 'rp.no_rawat', '=', 'raw_allergy.no_rawat')
            ->join('pasien AS p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftJoin('pegawai AS peg', 'peg.nik', '=', 'raw_allergy.nip')
            ->leftJoin('satu_sehat_encounter AS se', 'se.no_rawat', '=', 'raw_allergy.no_rawat')
            ->leftJoin('satu_sehat_allergy AS ssa', function ($join) {
                $join->on('ssa.no_rawat', '=', 'raw_allergy.no_rawat')
                    ->on('ssa.tgl_perawatan', '=', 'raw_allergy.tgl_perawatan')
                    ->on('ssa.jam_rawat', '=', 'raw_allergy.jam_rawat')
                    ->on('ssa.alergi_text', '=', 'raw_allergy.alergi_text');
            })
            ->where('raw_allergy.no_rawat', $noRawat)
            ->where('raw_allergy.tgl_perawatan', $tglPerawatan)
            ->where('raw_allergy.jam_rawat', $jamRawat)
            ->where('raw_allergy.alergi_text', $alergiText)
            ->select([
                'raw_allergy.no_rawat',
                'raw_allergy.tgl_perawatan',
                'raw_allergy.jam_rawat',
                'raw_allergy.sumber',
                'raw_allergy.alergi_text',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.no_rkm_medis',
                'rp.status_lanjut',
                'p.nm_pasien',
                'p.no_ktp',
                'se.id_encounter',
                'peg.nama AS nm_petugas',
                'peg.no_ktp AS ktppraktisi',
                'ssa.id_allergy',
            ])
            ->first();
    }

    /**
     * Kirim data alergi tunggal ke SatuSehat
     */
    public function syncSingleAllergy(string $noRawat, string $tglPerawatan, string $jamRawat, string $alergiText): array
    {
        $row = $this->getSpecificAllergy($noRawat, $tglPerawatan, $jamRawat, $alergiText);

        if (!$row) {
            return [
                'success' => false,
                'message' => 'Catatan alergi tidak ditemukan di database'
            ];
        }

        if (empty($row->id_encounter)) {
            return [
                'success' => false,
                'message' => 'Encounter kunjungan ini belum tersinkronisasi ke SatuSehat'
            ];
        }

        if (empty($row->no_ktp)) {
            return [
                'success' => false,
                'message' => 'NIK Pasien tidak valid / kosong'
            ];
        }

        // Resolusi NIK praktisi: jika kosong dari nip, cari dokter reg_periksa
        $nikPraktisi = $row->ktppraktisi;
        $nmPraktisi  = $row->nm_petugas;
        if (empty($nikPraktisi)) {
            $regDoc = DB::table('reg_periksa AS rp')
                ->join('pegawai AS peg', 'peg.nik', '=', 'rp.kd_dokter')
                ->where('rp.no_rawat', $row->no_rawat)
                ->select('peg.no_ktp', 'peg.nama')
                ->first();
            if ($regDoc) {
                $nikPraktisi = $regDoc->no_ktp;
                $nmPraktisi  = $regDoc->nama;
            }
        }

        if (empty($nikPraktisi)) {
            return [
                'success' => false,
                'message' => 'NIK Dokter/Petugas pemeriksa tidak valid / kosong'
            ];
        }

        // Cek Mapping
        $allMappings = DB::table('satu_sehat_mapping_alergi')->get();
        $mappingObj  = $this->findMatchInCollection($row->alergi_text, $allMappings);

        if ($mappingObj) {
            $mapping = [
                'category' => $mappingObj->category,
                'code'     => $mappingObj->code,
                'system'   => $mappingObj->system,
                'display'  => $mappingObj->display,
            ];
        } else {
            $mapping = $this->guessFallbackMapping($row->alergi_text);
        }

        try {
            $idPasien   = $this->getPatientId($row->no_ktp);
            $idPraktisi = $this->getPractitionerId($nikPraktisi);

            if (!$idPasien) {
                return [
                    'success' => false,
                    'message' => 'IHS Pasien tidak ditemukan di platform SatuSehat'
                ];
            }

            if (!$idPraktisi) {
                return [
                    'success' => false,
                    'message' => 'IHS Dokter/Petugas tidak ditemukan di platform SatuSehat'
                ];
            }

            $payload = $this->buildPayload($row, (object) $mapping, $idPasien, $idPraktisi, $nmPraktisi, $row->id_allergy);

            if (!empty($row->id_allergy)) {
                $response = $this->ss->put("AllergyIntolerance/{$row->id_allergy}", $payload);
                $isUpdate = true;
            } else {
                $response = $this->ss->post('AllergyIntolerance', $payload);
                $isUpdate = false;
            }

            $statusCode = data_get($response, 'status');
            $idAllergy  = data_get($response, 'data.id') ?? $row->id_allergy;

            if ($idAllergy && ($statusCode === 200 || $statusCode === 201)) {
                DB::table('satu_sehat_allergy')->updateOrInsert(
                    [
                        'no_rawat'      => $row->no_rawat,
                        'tgl_perawatan' => $row->tgl_perawatan,
                        'jam_rawat'     => $row->jam_rawat,
                        'alergi_text'   => $row->alergi_text,
                    ],
                    [
                        'id_allergy' => $idAllergy,
                        'updated_at' => now(),
                    ]
                );

                return [
                    'success'    => true,
                    'id_allergy' => $idAllergy,
                    'message'    => $isUpdate ? 'Data AllergyIntolerance berhasil diperbarui' : 'Data AllergyIntolerance berhasil dikirim',
                    'data'       => $response['data'] ?? []
                ];
            }

            $msg = data_get($response, 'data.issue.0.details.text')
                ?? data_get($response, 'message')
                ?? 'Gagal mengirim AllergyIntolerance ke SatuSehat';

            return [
                'success' => false,
                'message' => $msg,
                'details' => $response
            ];
        } catch (\Throwable $e) {
            Log::channel('satusehat')->error("[SatuSehat] Gagal sync AllergyIntolerance: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sinkronisasi Alergi massal (Batch Sync)
     */
    public function syncBatchAllergy(string $tglAwal, string $tglAkhir, array $keys = []): array
    {
        $results = ['success' => 0, 'error' => 0, 'skip' => 0, 'details' => []];

        if (!empty($keys)) {
            $items = $keys;
        } else {
            $items = $this->getAllergyList([
                'tgl_awal'  => $tglAwal,
                'tgl_akhir' => $tglAkhir,
                'status'    => 'unsent'
            ]);
        }

        foreach ($items as $item) {
            $obj = (object) $item;

            if (empty($obj->id_encounter)) {
                $results['skip']++;
                $results['details'][] = [
                    'no_rawat' => $obj->no_rawat,
                    'status'   => 'skip',
                    'message'  => 'Encounter belum tersinkronisasi'
                ];
                continue;
            }

            $res = $this->syncSingleAllergy(
                $obj->no_rawat,
                $obj->tgl_perawatan,
                $obj->jam_rawat,
                $obj->alergi_text
            );

            if ($res['success']) {
                $results['success']++;
                $results['details'][] = [
                    'no_rawat'   => $obj->no_rawat,
                    'status'     => 'success',
                    'id_allergy' => $res['id_allergy']
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

    /**
     * Membentuk payload FHIR R4 AllergyIntolerance
     */
    protected function buildPayload(object $row, object $mapping, string $idPasien, string $idPraktisi, ?string $nmPraktisi = null, ?string $idAllergy = null): array
    {
        $namaPasien   = trim(explode(',', $row->nm_pasien)[0]);
        $dateIso      = "{$row->tgl_perawatan}T{$row->jam_rawat}+07:00";
        $noRawatClean = str_replace(['/', '-'], '', $row->no_rawat);
        $tglClean     = str_replace('-', '', $row->tgl_perawatan);
        $jamClean     = str_replace(':', '', $row->jam_rawat);

        $payload = [
            'resourceType' => 'AllergyIntolerance',
            'identifier'   => [
                [
                    'system' => "http://sys-ids.kemkes.go.id/allergy/{$this->orgId}",
                    'use'    => 'official',
                    'value'  => "ALG-{$noRawatClean}-{$tglClean}-{$jamClean}",
                ],
            ],
            'clinicalStatus' => [
                'coding' => [
                    [
                        'system'  => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical',
                        'code'    => 'active',
                        'display' => 'Active',
                    ],
                ],
            ],
            'verificationStatus' => [
                'coding' => [
                    [
                        'system'  => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification',
                        'code'    => 'confirmed',
                        'display' => 'Confirmed',
                    ],
                ],
            ],
            'category' => [
                $mapping->category ?? 'food',
            ],
            'code' => [
                'coding' => [
                    [
                        'system'  => $mapping->system ?? 'http://snomed.info/sct',
                        'code'    => $mapping->code,
                        'display' => $mapping->display,
                    ],
                ],
                'text' => $row->alergi_text,
            ],
            'patient' => [
                'reference' => "Patient/{$idPasien}",
                'display'   => $namaPasien,
            ],
            'encounter' => [
                'reference' => "Encounter/{$row->id_encounter}",
            ],
            'recordedDate' => $dateIso,
            'recorder' => [
                'reference' => "Practitioner/{$idPraktisi}",
                'display'   => $nmPraktisi ?? $row->nm_petugas ?? 'Dokter Pemeriksa',
            ],
        ];

        if (!empty($idAllergy)) {
            $payload['id'] = $idAllergy;
        }

        return $payload;
    }
}

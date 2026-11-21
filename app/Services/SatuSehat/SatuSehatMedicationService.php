<?php

namespace App\Services\SatuSehat;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SatuSehatMedicationService
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
     * Helper to parse error message from SatuSehat response
     */
    public function parseIssues($res): string
    {
        if (is_array($res)) {
            if (isset($res['data']['issue']) && is_array($res['data']['issue'])) {
                $issues = [];
                foreach ($res['data']['issue'] as $issue) {
                    $issues[] = $issue['diagnostics'] ?? $issue['details']['text'] ?? json_encode($issue);
                }
                return implode('; ', $issues);
            }
            if (isset($res['message'])) {
                return (string) $res['message'];
            }
        }
        return 'Terjadi kesalahan saat menghubungkan ke SATU SEHAT';
    }

    /**
     * Parse aturan pakai string into frequency and dose
     * e.g., "3 x 1", "3x1", "2 x 1/2", "1 x 1 tab"
     */
    public function parseAturanPakai(?string $aturan): array
    {
        $frekuensi = 1.0;
        $dosis     = 1.0;

        if (empty($aturan)) {
            return ['frekuensi' => 1, 'dosis' => 1];
        }

        $str = strtolower(trim($aturan));
        if (str_contains($str, 'x')) {
            $parts = explode('x', $str);
            $f_val = preg_replace('/[^0-9.]/', '', $parts[0] ?? '');
            $d_val = preg_replace('/[^0-9.]/', '', $parts[1] ?? '');

            if (!empty($f_val) && is_numeric($f_val)) {
                $frekuensi = (float) $f_val;
            }
            if (!empty($d_val) && is_numeric($d_val)) {
                $dosis = (float) $d_val;
            }
        }

        return [
            'frekuensi' => $frekuensi > 0 ? (int)$frekuensi : 1,
            'dosis'     => $dosis > 0 ? $dosis : 1
        ];
    }

    // =========================================================================
    // MEDICATION REQUEST (RESEP OBAT)
    // =========================================================================

    /**
     * Get Medication Request list (Regular & Racikan)
     */
    public function getMedicationRequestList(array $params = []): array
    {
        $tglAwal  = $params['tgl_awal'] ?? date('Y-m-d');
        $tglAkhir = $params['tgl_akhir'] ?? $tglAwal;
        $search   = $params['search'] ?? null;
        $status   = $params['status'] ?? 'all'; // all, synced, unsent

        // 1. Regular Prescriptions
        $queryRegular = DB::table('resep_obat as ro')
            ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ro.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('pegawai as pg', 'pg.nik', '=', 'ro.kd_dokter')
            ->join('resep_dokter as rd', 'rd.no_resep', '=', 'ro.no_resep')
            ->join('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
            ->leftJoin('satu_sehat_encounter as sse', 'sse.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'rd.kode_brng')
            ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'rd.kode_brng')
            ->leftJoin('satu_sehat_medicationrequest as smr', function ($join) {
                $join->on('smr.no_resep', '=', 'rd.no_resep')
                     ->on('smr.kode_brng', '=', 'rd.kode_brng');
            })
            ->whereBetween('ro.tgl_peresepan', [$tglAwal, $tglAkhir])
            ->select(
                'ro.no_resep',
                'ro.tgl_peresepan',
                'ro.jam_peresepan',
                'rp.no_rawat',
                'rp.status_lanjut',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'p.no_ktp as ktp_pasien',
                'pg.nama as nm_dokter',
                'pg.no_ktp as ktp_dokter',
                'rd.kode_brng',
                'db.nama_brng',
                'rd.jml',
                'rd.aturan_pakai',
                DB::raw("'regular' as jenis_resep"),
                DB::raw("NULL as no_racik"),
                'sse.id_encounter',
                'sm.id_medication',
                'smo.obat_display',
                'smo.route_code',
                'smo.route_system',
                'smo.route_display',
                'smo.denominator_code',
                'smo.denominator_system',
                'smr.id_medicationrequest'
            );

        if (!empty($search)) {
            $queryRegular->where(function ($q) use ($search) {
                $q->where('ro.no_resep', 'like', "%{$search}%")
                  ->orWhere('rp.no_rawat', 'like', "%{$search}%")
                  ->orWhere('p.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('db.nama_brng', 'like', "%{$search}%");
            });
        }

        $regularRows = $queryRegular->get()->toArray();

        // 2. Racikan Prescriptions
        $queryRacikan = DB::table('resep_obat as ro')
            ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ro.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('pegawai as pg', 'pg.nik', '=', 'ro.kd_dokter')
            ->join('resep_dokter_racikan as rdr', 'rdr.no_resep', '=', 'ro.no_resep')
            ->join('resep_dokter_racikan_detail as rdrd', function ($join) {
                $join->on('rdrd.no_resep', '=', 'rdr.no_resep')
                     ->on('rdrd.no_racik', '=', 'rdr.no_racik');
            })
            ->join('databarang as db', 'db.kode_brng', '=', 'rdrd.kode_brng')
            ->leftJoin('satu_sehat_encounter as sse', 'sse.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'rdrd.kode_brng')
            ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'rdrd.kode_brng')
            ->leftJoin('satu_sehat_medicationrequest_racikan as smrr', function ($join) {
                $join->on('smrr.no_resep', '=', 'rdrd.no_resep')
                     ->on('smrr.kode_brng', '=', 'rdrd.kode_brng')
                     ->on('smrr.no_racik', '=', 'rdrd.no_racik');
            })
            ->whereBetween('ro.tgl_peresepan', [$tglAwal, $tglAkhir])
            ->select(
                'ro.no_resep',
                'ro.tgl_peresepan',
                'ro.jam_peresepan',
                'rp.no_rawat',
                'rp.status_lanjut',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'p.no_ktp as ktp_pasien',
                'pg.nama as nm_dokter',
                'pg.no_ktp as ktp_dokter',
                'rdrd.kode_brng',
                'db.nama_brng',
                'rdr.jml_dr as jml',
                'rdr.aturan_pakai',
                DB::raw("'racikan' as jenis_resep"),
                'rdrd.no_racik',
                'sse.id_encounter',
                'sm.id_medication',
                'smo.obat_display',
                'smo.route_code',
                'smo.route_system',
                'smo.route_display',
                'smo.denominator_code',
                'smo.denominator_system',
                'smrr.id_medicationrequest'
            );

        if (!empty($search)) {
            $queryRacikan->where(function ($q) use ($search) {
                $q->where('ro.no_resep', 'like', "%{$search}%")
                  ->orWhere('rp.no_rawat', 'like', "%{$search}%")
                  ->orWhere('p.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('db.nama_brng', 'like', "%{$search}%");
            });
        }

        $racikanRows = $queryRacikan->get()->toArray();

        // Merge both
        $merged = array_merge($regularRows, $racikanRows);

        // Sort by tgl_peresepan desc, jam_peresepan desc, no_resep desc
        usort($merged, function ($a, $b) {
            $dtA = $a->tgl_peresepan . ' ' . $a->jam_peresepan;
            $dtB = $b->tgl_peresepan . ' ' . $b->jam_peresepan;
            return strcmp($dtB, $dtA);
        });

        // Filter status if requested
        if ($status === 'synced') {
            $merged = array_values(array_filter($merged, fn($item) => !empty($item->id_medicationrequest)));
        } elseif ($status === 'unsent') {
            $merged = array_values(array_filter($merged, fn($item) => empty($item->id_medicationrequest)));
        }

        return $merged;
    }

    /**
     * Send single MedicationRequest to SATU SEHAT
     */
    public function syncMedicationRequest(string $noResep, string $kodeBrng, ?string $noRacik = null): array
    {
        $isRacikan = !empty($noRacik);

        if (!$isRacikan) {
            $row = DB::table('resep_obat as ro')
                ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ro.no_rawat')
                ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
                ->join('pegawai as pg', 'pg.nik', '=', 'ro.kd_dokter')
                ->join('resep_dokter as rd', 'rd.no_resep', '=', 'ro.no_resep')
                ->join('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
                ->leftJoin('satu_sehat_encounter as sse', 'sse.no_rawat', '=', 'rp.no_rawat')
                ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'rd.kode_brng')
                ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'rd.kode_brng')
                ->where('ro.no_resep', $noResep)
                ->where('rd.kode_brng', $kodeBrng)
                ->select(
                    'ro.no_resep', 'ro.tgl_peresepan', 'ro.jam_peresepan',
                    'rp.no_rawat', 'rp.status_lanjut',
                    'p.nm_pasien', 'p.no_ktp as ktp_pasien',
                    'pg.nama as nm_dokter', 'pg.no_ktp as ktp_dokter',
                    'rd.kode_brng', 'db.nama_brng', 'rd.jml', 'rd.aturan_pakai',
                    'sse.id_encounter', 'sm.id_medication', 'smo.obat_display',
                    'smo.route_code', 'smo.route_system', 'smo.route_display',
                    'smo.denominator_code', 'smo.denominator_system'
                )
                ->first();
        } else {
            $row = DB::table('resep_obat as ro')
                ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ro.no_rawat')
                ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
                ->join('pegawai as pg', 'pg.nik', '=', 'ro.kd_dokter')
                ->join('resep_dokter_racikan as rdr', 'rdr.no_resep', '=', 'ro.no_resep')
                ->join('resep_dokter_racikan_detail as rdrd', function ($join) {
                    $join->on('rdrd.no_resep', '=', 'rdr.no_resep')
                         ->on('rdrd.no_racik', '=', 'rdr.no_racik');
                })
                ->join('databarang as db', 'db.kode_brng', '=', 'rdrd.kode_brng')
                ->leftJoin('satu_sehat_encounter as sse', 'sse.no_rawat', '=', 'rp.no_rawat')
                ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'rdrd.kode_brng')
                ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'rdrd.kode_brng')
                ->where('ro.no_resep', $noResep)
                ->where('rdrd.kode_brng', $kodeBrng)
                ->where('rdrd.no_racik', $noRacik)
                ->select(
                    'ro.no_resep', 'ro.tgl_peresepan', 'ro.jam_peresepan',
                    'rp.no_rawat', 'rp.status_lanjut',
                    'p.nm_pasien', 'p.no_ktp as ktp_pasien',
                    'pg.nama as nm_dokter', 'pg.no_ktp as ktp_dokter',
                    'rdrd.kode_brng', 'db.nama_brng', 'rdr.jml_dr as jml', 'rdr.aturan_pakai',
                    'rdrd.no_racik',
                    'sse.id_encounter', 'sm.id_medication', 'smo.obat_display',
                    'smo.route_code', 'smo.route_system', 'smo.route_display',
                    'smo.denominator_code', 'smo.denominator_system'
                )
                ->first();
        }

        if (!$row) {
            return ['success' => false, 'message' => "Data resep {$noResep} ({$kodeBrng}) tidak ditemukan."];
        }

        // Validasi Pre-requisite
        if (empty($row->ktp_pasien)) {
            return ['success' => false, 'message' => "NIK pasien {$row->nm_pasien} masih kosong."];
        }
        if (empty($row->ktp_dokter)) {
            return ['success' => false, 'message' => "NIK dokter {$row->nm_dokter} masih kosong."];
        }
        if (empty($row->id_encounter)) {
            return ['success' => false, 'message' => "Encounter pasien {$row->no_rawat} belum tersinkronisasi ke SATU SEHAT."];
        }
        if (empty($row->id_medication)) {
            return ['success' => false, 'message' => "Obat '{$row->nama_brng}' ({$kodeBrng}) belum disinkronkan ke Medication KFA SATU SEHAT."];
        }

        $patientIhs = $this->getPatientId($row->ktp_pasien);
        if (!$patientIhs) {
            return ['success' => false, 'message' => "IHS Pasien tidak ditemukan untuk NIK {$row->ktp_pasien}."];
        }

        $practitionerIhs = $this->getPractitionerId($row->ktp_dokter);
        if (!$practitionerIhs) {
            return ['success' => false, 'message' => "IHS Dokter tidak ditemukan untuk NIK {$row->ktp_dokter}."];
        }

        // Parsing aturan pakai
        $parsed = $this->parseAturanPakai($row->aturan_pakai);
        $frekuensi = $parsed['frekuensi'];
        $dosis     = $parsed['dosis'];

        $authoredOn = "{$row->tgl_peresepan}T{$row->jam_peresepan}+07:00";
        $categoryCode = ($row->status_lanjut === 'Ralan') ? 'outpatient' : 'inpatient';
        $categoryDisplay = ($row->status_lanjut === 'Ralan') ? 'Outpatient' : 'Inpatient';

        // Route & unit mapping fallbacks
        $routeCode    = !empty($row->route_code) ? $row->route_code : 'O';
        if ($routeCode === 'PO') {
            $routeCode = 'O';
        }
        $routeSystem  = !empty($row->route_system) ? rtrim($row->route_system, '/') : 'http://www.whocc.no/atc';
        $routeDisplay = !empty($row->route_display) ? $row->route_display : 'Oral';

        $unitCode   = !empty($row->denominator_code) ? $row->denominator_code : 'TAB';
        $unitSystem = !empty($row->denominator_system) ? rtrim($row->denominator_system, '/') : 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm';

        $itemIdentifier = $kodeBrng . ($isRacikan ? "-R{$row->no_racik}" : '');
        $obatDisplay    = !empty($row->obat_display) ? $row->obat_display : $row->nama_brng;

        $payload = [
            'resourceType' => 'MedicationRequest',
            'identifier'   => [
                [
                    'system' => "http://sys-ids.kemkes.go.id/prescription/{$this->orgId}",
                    'use'    => 'official',
                    'value'  => (string)$row->no_resep,
                ],
                [
                    'system' => "http://sys-ids.kemkes.go.id/prescription-item/{$this->orgId}",
                    'use'    => 'official',
                    'value'  => (string)$itemIdentifier,
                ]
            ],
            'status'   => 'completed',
            'intent'   => 'order',
            'category' => [
                [
                    'coding' => [
                        [
                            'system'  => 'http://terminology.hl7.org/CodeSystem/medicationrequest-category',
                            'code'    => $categoryCode,
                            'display' => $categoryDisplay
                        ]
                    ]
                ]
            ],
            'medicationReference' => [
                'reference' => 'Medication/' . $row->id_medication,
                'display'   => $obatDisplay
            ],
            'subject' => [
                'reference' => 'Patient/' . $patientIhs,
                'display'   => $row->nm_pasien
            ],
            'encounter' => [
                'reference' => 'Encounter/' . $row->id_encounter
            ],
            'authoredOn' => $authoredOn,
            'requester'  => [
                'reference' => 'Practitioner/' . $practitionerIhs,
                'display'   => $row->nm_dokter
            ],
            'dosageInstruction' => [
                [
                    'sequence'           => 1,
                    'patientInstruction' => !empty($row->aturan_pakai) ? $row->aturan_pakai : 'Sesuai anjuran resep dokter',
                    'timing'             => [
                        'repeat' => [
                            'frequency'  => (int)$frekuensi,
                            'period'     => 1,
                            'periodUnit' => 'd'
                        ]
                    ],
                    'route' => [
                        'coding' => [
                            [
                                'system'  => $routeSystem,
                                'code'    => $routeCode,
                                'display' => $routeDisplay
                            ]
                        ]
                    ],
                    'doseAndRate' => [
                        [
                            'doseQuantity' => [
                                'value'  => (float)$dosis,
                                'unit'   => $unitCode,
                                'system' => $unitSystem,
                                'code'   => $unitCode
                            ]
                        ]
                    ]
                ]
            ],
            'dispenseRequest' => [
                'quantity' => [
                    'value'  => (float)$row->jml,
                    'unit'   => $unitCode,
                    'system' => $unitSystem,
                    'code'   => $unitCode
                ],
                'performer' => [
                    'reference' => 'Organization/' . $this->orgId
                ]
            ]
        ];

        $res = $this->ss->post('MedicationRequest', $payload);

        if ($res['status'] && isset($res['data']['id'])) {
            $idMedicationRequest = $res['data']['id'];

            if (!$isRacikan) {
                DB::table('satu_sehat_medicationrequest')->updateOrInsert(
                    ['no_resep' => $noResep, 'kode_brng' => $kodeBrng],
                    ['id_medicationrequest' => $idMedicationRequest]
                );
            } else {
                DB::table('satu_sehat_medicationrequest_racikan')->updateOrInsert(
                    ['no_resep' => $noResep, 'kode_brng' => $kodeBrng, 'no_racik' => $noRacik],
                    ['id_medicationrequest' => $idMedicationRequest]
                );
            }

            Log::channel('satusehat')->info("[SatuSehat] MedicationRequest {$noResep} {$kodeBrng} berhasil: ID {$idMedicationRequest}");

            return [
                'success' => true,
                'message' => 'MedicationRequest berhasil disinkronkan',
                'id'      => $idMedicationRequest
            ];
        }

        $errMsg = $this->parseIssues($res);
        Log::channel('satusehat')->error("[SatuSehat] MedicationRequest {$noResep} {$kodeBrng} gagal: {$errMsg}");

        return [
            'success' => false,
            'message' => $errMsg
        ];
    }

    /**
     * Batch sync MedicationRequest
     */
    public function sendBatchMedicationRequest(string $tglAwal, string $tglAkhir, array $keys = []): array
    {
        $list = $this->getMedicationRequestList([
            'tgl_awal'  => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'status'    => 'unsent'
        ]);

        if (!empty($keys)) {
            $list = array_filter($list, function ($item) use ($keys) {
                $k = $item->no_resep . '|' . $item->kode_brng . '|' . ($item->no_racik ?? '');
                return in_array($k, $keys);
            });
        }

        $success = 0;
        $error   = 0;
        $skip    = 0;
        $messages = [];

        foreach ($list as $item) {
            // Check readiness
            if (empty($item->id_encounter) || empty($item->id_medication) || empty($item->ktp_pasien) || empty($item->ktp_dokter)) {
                $skip++;
                continue;
            }

            $res = $this->syncMedicationRequest($item->no_resep, $item->kode_brng, $item->no_racik);
            if ($res['success']) {
                $success++;
            } else {
                $error++;
                $messages[] = "{$item->no_resep} ({$item->nama_brng}): {$res['message']}";
            }
        }

        return [
            'total'    => count($list),
            'success'  => $success,
            'error'    => $error,
            'skip'     => $skip,
            'messages' => $messages
        ];
    }

    // =========================================================================
    // MEDICATION DISPENSE (PENYERAHAN OBAT)
    // =========================================================================

    /**
     * Get Medication Dispense list
     */
    public function getMedicationDispenseList(array $params = []): array
    {
        $tglAwal  = $params['tgl_awal'] ?? date('Y-m-d');
        $tglAkhir = $params['tgl_akhir'] ?? $tglAwal;
        $search   = $params['search'] ?? null;
        $status   = $params['status'] ?? 'all'; // all, synced, unsent

        $query = DB::table('detail_pemberian_obat as dpo')
            ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'dpo.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('databarang as db', 'db.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('resep_obat as ro', 'ro.no_rawat', '=', 'dpo.no_rawat')
            ->leftJoin('pegawai as pg', 'pg.nik', '=', 'ro.kd_dokter')
            ->leftJoin('aturan_pakai as ap', function ($join) {
                $join->on('ap.no_rawat', '=', 'dpo.no_rawat')
                     ->on('ap.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                     ->on('ap.jam', '=', 'dpo.jam')
                     ->on('ap.kode_brng', '=', 'dpo.kode_brng');
            })
            ->leftJoin('bangsal as bg', 'bg.kd_bangsal', '=', 'dpo.kd_bangsal')
            ->leftJoin('satu_sehat_mapping_lokasi_depo_farmasi as smld', 'smld.kd_bangsal', '=', 'bg.kd_bangsal')
            ->leftJoin('satu_sehat_encounter as sse', 'sse.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('satu_sehat_medicationrequest as smr', function ($join) {
                $join->on('smr.no_resep', '=', 'ro.no_resep')
                     ->on('smr.kode_brng', '=', 'dpo.kode_brng');
            })
            ->leftJoin('satu_sehat_medicationrequest_racikan as smrr', function ($join) {
                $join->on('smrr.no_resep', '=', 'ro.no_resep')
                     ->on('smrr.kode_brng', '=', 'dpo.kode_brng');
            })
            ->leftJoin('satu_sehat_medicationdispense as smd', function ($join) {
                $join->on('smd.no_rawat', '=', 'dpo.no_rawat')
                     ->on('smd.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                     ->on('smd.jam', '=', 'dpo.jam')
                     ->on('smd.kode_brng', '=', 'dpo.kode_brng')
                     ->on('smd.no_batch', '=', 'dpo.no_batch')
                     ->on('smd.no_faktur', '=', 'dpo.no_faktur');
            })
            ->whereBetween('dpo.tgl_perawatan', [$tglAwal, $tglAkhir])
            ->select(
                'dpo.no_rawat',
                'dpo.tgl_perawatan',
                'dpo.jam',
                'dpo.kode_brng',
                'dpo.jml',
                'dpo.kd_bangsal',
                'dpo.no_batch',
                'dpo.no_faktur',
                'db.nama_brng',
                'rp.status_lanjut',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'p.no_ktp as ktp_pasien',
                'ro.no_resep',
                'ro.tgl_peresepan',
                'ro.jam_peresepan',
                'pg.nama as nm_dokter',
                'pg.no_ktp as ktp_dokter',
                'ap.aturan as aturan_pakai',
                'bg.nm_bangsal',
                'smld.id_lokasi_satusehat',
                'sse.id_encounter',
                'sm.id_medication',
                'smo.obat_display',
                'smo.route_code',
                'smo.route_system',
                'smo.route_display',
                'smo.denominator_code',
                'smo.denominator_system',
                DB::raw('COALESCE(smr.id_medicationrequest, smrr.id_medicationrequest) as id_medicationrequest'),
                'smd.id_medicationdispanse'
            );

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('dpo.no_rawat', 'like', "%{$search}%")
                  ->orWhere('p.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('db.nama_brng', 'like', "%{$search}%")
                  ->orWhere('ro.no_resep', 'like', "%{$search}%");
            });
        }

        if ($status === 'synced') {
            $query->whereNotNull('smd.id_medicationdispanse');
        } elseif ($status === 'unsent') {
            $query->whereNull('smd.id_medicationdispanse');
        }

        return $query->orderBy('dpo.tgl_perawatan', 'desc')
            ->orderBy('dpo.jam', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Send single MedicationDispense to SATU SEHAT
     */
    public function syncMedicationDispense(string $noRawat, string $tglPerawatan, string $jam, string $kodeBrng, string $noBatch = '', string $noFaktur = ''): array
    {
        $row = DB::table('detail_pemberian_obat as dpo')
            ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'dpo.no_rawat')
            ->join('pasien as p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('databarang as db', 'db.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('resep_obat as ro', 'ro.no_rawat', '=', 'dpo.no_rawat')
            ->leftJoin('pegawai as pg', 'pg.nik', '=', 'ro.kd_dokter')
            ->leftJoin('aturan_pakai as ap', function ($join) {
                $join->on('ap.no_rawat', '=', 'dpo.no_rawat')
                     ->on('ap.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                     ->on('ap.jam', '=', 'dpo.jam')
                     ->on('ap.kode_brng', '=', 'dpo.kode_brng');
            })
            ->leftJoin('bangsal as bg', 'bg.kd_bangsal', '=', 'dpo.kd_bangsal')
            ->leftJoin('satu_sehat_mapping_lokasi_depo_farmasi as smld', 'smld.kd_bangsal', '=', 'bg.kd_bangsal')
            ->leftJoin('satu_sehat_encounter as sse', 'sse.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('satu_sehat_medicationrequest as smr', function ($join) {
                $join->on('smr.no_resep', '=', 'ro.no_resep')
                     ->on('smr.kode_brng', '=', 'dpo.kode_brng');
            })
            ->leftJoin('satu_sehat_medicationrequest_racikan as smrr', function ($join) {
                $join->on('smrr.no_resep', '=', 'ro.no_resep')
                     ->on('smrr.kode_brng', '=', 'dpo.kode_brng');
            })
            ->where('dpo.no_rawat', $noRawat)
            ->where('dpo.tgl_perawatan', $tglPerawatan)
            ->where('dpo.jam', $jam)
            ->where('dpo.kode_brng', $kodeBrng)
            ->where('dpo.no_batch', $noBatch)
            ->where('dpo.no_faktur', $noFaktur)
            ->select(
                'dpo.no_rawat', 'dpo.tgl_perawatan', 'dpo.jam', 'dpo.kode_brng', 'dpo.jml',
                'dpo.kd_bangsal', 'dpo.no_batch', 'dpo.no_faktur',
                'db.nama_brng', 'rp.status_lanjut',
                'p.nm_pasien', 'p.no_ktp as ktp_pasien',
                'ro.no_resep', 'ro.tgl_peresepan', 'ro.jam_peresepan',
                'pg.nama as nm_dokter', 'pg.no_ktp as ktp_dokter',
                'ap.aturan as aturan_pakai',
                'bg.nm_bangsal', 'smld.id_lokasi_satusehat',
                'sse.id_encounter', 'sm.id_medication', 'smo.obat_display',
                'smo.route_code', 'smo.route_system', 'smo.route_display',
                'smo.denominator_code', 'smo.denominator_system',
                DB::raw('COALESCE(smr.id_medicationrequest, smrr.id_medicationrequest) as id_medicationrequest')
            )
            ->first();

        if (!$row) {
            return ['success' => false, 'message' => "Detail pemberian obat {$noRawat} ({$kodeBrng}) tidak ditemukan."];
        }

        // Validasi Pre-requisite
        if (empty($row->ktp_pasien)) {
            return ['success' => false, 'message' => "NIK pasien {$row->nm_pasien} masih kosong."];
        }
        if (empty($row->ktp_dokter)) {
            return ['success' => false, 'message' => "NIK dokter {$row->nm_dokter} masih kosong."];
        }
        if (empty($row->id_encounter)) {
            return ['success' => false, 'message' => "Encounter pasien {$row->no_rawat} belum tersinkronisasi ke SATU SEHAT."];
        }
        if (empty($row->id_medication)) {
            return ['success' => false, 'message' => "Obat '{$row->nama_brng}' ({$kodeBrng}) belum disinkronkan ke Medication KFA SATU SEHAT."];
        }

        // Jika MedicationRequest belum ada, coba auto-sync MedicationRequest terlebih dahulu
        $idMedicationRequest = $row->id_medicationrequest;
        if (empty($idMedicationRequest) && !empty($row->no_resep)) {
            $syncReq = $this->syncMedicationRequest($row->no_resep, $row->kode_brng);
            if ($syncReq['success']) {
                $idMedicationRequest = $syncReq['id'];
            } else {
                return ['success' => false, 'message' => "MedicationRequest belum tersinkronisasi: " . $syncReq['message']];
            }
        }

        if (empty($idMedicationRequest)) {
            return ['success' => false, 'message' => "MedicationDispense memerlukan ID MedicationRequest yang sudah tersinkronisasi."];
        }

        $patientIhs = $this->getPatientId($row->ktp_pasien);
        if (!$patientIhs) {
            return ['success' => false, 'message' => "IHS Pasien tidak ditemukan untuk NIK {$row->ktp_pasien}."];
        }

        $practitionerIhs = $this->getPractitionerId($row->ktp_dokter);
        if (!$practitionerIhs) {
            return ['success' => false, 'message' => "IHS Dokter tidak ditemukan untuk NIK {$row->ktp_dokter}."];
        }

        // Parsing aturan pakai
        $parsed = $this->parseAturanPakai($row->aturan_pakai);
        $frekuensi = $parsed['frekuensi'];
        $dosis     = $parsed['dosis'];

        $whenHandedOver = "{$row->tgl_perawatan}T{$row->jam}+07:00";
        $whenPrepared   = (!empty($row->tgl_peresepan) && !empty($row->jam_peresepan))
            ? "{$row->tgl_peresepan}T{$row->jam_peresepan}+07:00"
            : $whenHandedOver;

        $categoryCode    = ($row->status_lanjut === 'Ralan') ? 'outpatient' : 'inpatient';
        $categoryDisplay = ($row->status_lanjut === 'Ralan') ? 'Outpatient' : 'Inpatient';

        // Lokasi Depo Farmasi
        $idLokasi = $row->id_lokasi_satusehat;
        if (empty($idLokasi)) {
            $defaultLokasi = DB::table('satu_sehat_mapping_lokasi_depo_farmasi')->first();
            $idLokasi = $defaultLokasi->id_lokasi_satusehat ?? null;
        }

        // Route & unit mapping fallbacks
        $routeCode    = !empty($row->route_code) ? $row->route_code : 'O';
        if ($routeCode === 'PO') {
            $routeCode = 'O';
        }
        $routeSystem  = !empty($row->route_system) ? rtrim($row->route_system, '/') : 'http://www.whocc.no/atc';
        $routeDisplay = !empty($row->route_display) ? $row->route_display : 'Oral';

        $unitCode   = !empty($row->denominator_code) ? $row->denominator_code : 'TAB';
        $unitSystem = !empty($row->denominator_system) ? rtrim($row->denominator_system, '/') : 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm';
        $obatDisplay = !empty($row->obat_display) ? $row->obat_display : $row->nama_brng;

        $payload = [
            'resourceType' => 'MedicationDispense',
            'identifier'   => [
                [
                    'system' => "http://sys-ids.kemkes.go.id/prescription/{$this->orgId}",
                    'use'    => 'official',
                    'value'  => (string)($row->no_resep ?: $row->no_rawat),
                ],
                [
                    'system' => "http://sys-ids.kemkes.go.id/prescription-item/{$this->orgId}",
                    'use'    => 'official',
                    'value'  => (string)$row->kode_brng,
                ]
            ],
            'status'   => 'completed',
            'category' => [
                'coding' => [
                    [
                        'system'  => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
                        'code'    => $categoryCode,
                        'display' => $categoryDisplay
                    ]
                ]
            ],
            'medicationReference' => [
                'reference' => 'Medication/' . $row->id_medication,
                'display'   => $obatDisplay
            ],
            'subject' => [
                'reference' => 'Patient/' . $patientIhs,
                'display'   => $row->nm_pasien
            ],
            'context' => [
                'reference' => 'Encounter/' . $row->id_encounter
            ],
            'performer' => [
                [
                    'actor' => [
                        'reference' => 'Practitioner/' . $practitionerIhs,
                        'display'   => $row->nm_dokter
                    ]
                ]
            ],
            'authorizingPrescription' => [
                [
                    'reference' => 'MedicationRequest/' . $idMedicationRequest
                ]
            ],
            'quantity' => [
                'value'  => (float)$row->jml,
                'unit'   => $unitCode,
                'system' => $unitSystem,
                'code'   => $unitCode
            ],
            'whenPrepared'   => $whenPrepared,
            'whenHandedOver' => $whenHandedOver,
            'dosageInstruction' => [
                [
                    'sequence'           => 1,
                    'patientInstruction' => !empty($row->aturan_pakai) ? $row->aturan_pakai : 'Sesuai anjuran resep dokter',
                    'timing'             => [
                        'repeat' => [
                            'frequency'  => (int)$frekuensi,
                            'period'     => 1,
                            'periodUnit' => 'd'
                        ]
                    ],
                    'route' => [
                        'coding' => [
                            [
                                'system'  => $routeSystem,
                                'code'    => $routeCode,
                                'display' => $routeDisplay
                            ]
                        ]
                    ],
                    'doseAndRate' => [
                        [
                            'doseQuantity' => [
                                'value'  => (float)$dosis,
                                'unit'   => $unitCode,
                                'system' => $unitSystem,
                                'code'   => $unitCode
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if (!empty($idLokasi)) {
            $payload['location'] = [
                'reference' => 'Location/' . $idLokasi,
                'display'   => !empty($row->nm_bangsal) ? $row->nm_bangsal : 'Depo Farmasi'
            ];
        }

        $res = $this->ss->post('MedicationDispense', $payload);

        if ($res['status'] && isset($res['data']['id'])) {
            $idMedicationDispense = $res['data']['id'];

            DB::table('satu_sehat_medicationdispense')->updateOrInsert(
                [
                    'no_rawat'      => $noRawat,
                    'tgl_perawatan' => $tglPerawatan,
                    'jam'           => $jam,
                    'kode_brng'     => $kodeBrng,
                    'no_batch'      => (string)($noBatch ?? ''),
                    'no_faktur'     => (string)($noFaktur ?? '')
                ],
                [
                    'id_medicationdispanse' => $idMedicationDispense
                ]
            );

            Log::channel('satusehat')->info("[SatuSehat] MedicationDispense {$noRawat} {$kodeBrng} berhasil: ID {$idMedicationDispense}");

            return [
                'success' => true,
                'message' => 'MedicationDispense berhasil disinkronkan',
                'id'      => $idMedicationDispense
            ];
        }

        $errMsg = $this->parseIssues($res);
        Log::channel('satusehat')->error("[SatuSehat] MedicationDispense {$noRawat} {$kodeBrng} gagal: {$errMsg}");

        return [
            'success' => false,
            'message' => $errMsg
        ];
    }

    /**
     * Batch sync MedicationDispense
     */
    public function sendBatchMedicationDispense(string $tglAwal, string $tglAkhir, array $keys = []): array
    {
        $list = $this->getMedicationDispenseList([
            'tgl_awal'  => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'status'    => 'unsent'
        ]);

        if (!empty($keys)) {
            $list = array_filter($list, function ($item) use ($keys) {
                $k = $item->no_rawat . '|' . $item->tgl_perawatan . '|' . $item->jam . '|' . $item->kode_brng . '|' . ($item->no_batch ?? '') . '|' . ($item->no_faktur ?? '');
                return in_array($k, $keys);
            });
        }

        $success = 0;
        $error   = 0;
        $skip    = 0;
        $messages = [];

        foreach ($list as $item) {
            // Check basic requirements
            if (empty($item->id_encounter) || empty($item->id_medication) || empty($item->ktp_pasien) || empty($item->ktp_dokter)) {
                $skip++;
                continue;
            }

            $res = $this->syncMedicationDispense(
                $item->no_rawat,
                $item->tgl_perawatan,
                $item->jam,
                $item->kode_brng,
                (string)($item->no_batch ?? ''),
                (string)($item->no_faktur ?? '')
            );

            if ($res['success']) {
                $success++;
            } else {
                $error++;
                $messages[] = "{$item->no_rawat} ({$item->nama_brng}): {$res['message']}";
            }
        }

        return [
            'total'    => count($list),
            'success'  => $success,
            'error'    => $error,
            'skip'     => $skip,
            'messages' => $messages
        ];
    }
}

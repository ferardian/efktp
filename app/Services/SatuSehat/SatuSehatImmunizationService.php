<?php

namespace App\Services\SatuSehat;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SatuSehatImmunizationService
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
     * Parse nomor dosis dari string aturan pakai
     */
    public function parseDoseNumber(?string $aturan): int
    {
        if (empty($aturan)) {
            return 1;
        }

        if (preg_match('/(?:dosis|ke)?\s*(\d+)/i', $aturan, $matches)) {
            $num = intval($matches[1]);
            return $num > 0 ? $num : 1;
        }

        return 1;
    }

    /**
     * Resolve Location UUID
     */
    protected function resolveLocationId($row): ?string
    {
        if (!empty($row->id_lokasi_ralan)) {
            return $row->id_lokasi_ralan;
        }

        if (!empty($row->id_lokasi_depo)) {
            return $row->id_lokasi_depo;
        }

        return DB::table('satu_sehat_mapping_lokasi_ralan')->value('id_lokasi_satusehat');
    }

    /**
     * Ambil data pemberian vaksin / imunisasi
     */
    public function getImmunizationList(array $params = []): array
    {
        $tglAwal  = $params['tgl_awal'] ?? date('Y-m-d');
        $tglAkhir = $params['tgl_akhir'] ?? $tglAwal;
        $search   = $params['search'] ?? null;
        $status   = $params['status'] ?? 'all'; // all, synced, unsent

        $builder = DB::table('detail_pemberian_obat AS dpo')
            ->join('databarang AS db', 'db.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('satu_sehat_mapping_vaksin AS mv', 'mv.kode_brng', '=', 'dpo.kode_brng')
            ->join('reg_periksa AS rp', 'rp.no_rawat', '=', 'dpo.no_rawat')
            ->join('pasien AS p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftJoin('pegawai AS peg', 'peg.nik', '=', 'rp.kd_dokter')
            ->leftJoin('satu_sehat_encounter AS se', 'se.no_rawat', '=', 'dpo.no_rawat')
            ->leftJoin('poliklinik AS poli', 'poli.kd_poli', '=', 'rp.kd_poli')
            ->leftJoin('satu_sehat_mapping_lokasi_ralan AS mlr', 'mlr.kd_poli', '=', 'rp.kd_poli')
            ->leftJoin('bangsal AS bg', 'bg.kd_bangsal', '=', 'dpo.kd_bangsal')
            ->leftJoin('satu_sehat_mapping_lokasi_depo_farmasi AS smld', 'smld.kd_bangsal', '=', 'bg.kd_bangsal')
            ->leftJoin('aturan_pakai AS ap', function ($join) {
                $join->on('ap.no_rawat', '=', 'dpo.no_rawat')
                    ->on('ap.kode_brng', '=', 'dpo.kode_brng')
                    ->on('ap.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                    ->on('ap.jam', '=', 'dpo.jam');
            })
            ->leftJoin('satu_sehat_immunization AS ssi', function ($join) {
                $join->on('ssi.no_rawat', '=', 'dpo.no_rawat')
                    ->on('ssi.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                    ->on('ssi.jam', '=', 'dpo.jam')
                    ->on('ssi.kode_brng', '=', 'dpo.kode_brng')
                    ->on('ssi.no_batch', '=', 'dpo.no_batch')
                    ->on('ssi.no_faktur', '=', 'dpo.no_faktur');
            })
            // Filter: barang harus ada di mapping vaksin ATAU barang termasuk kategori/nama vaksin
            ->where(function ($q) {
                $q->whereNotNull('mv.kode_brng')
                  ->orWhere('db.nama_brng', 'LIKE', '%vaksin%')
                  ->orWhere('db.nama_brng', 'LIKE', '%vaccine%')
                  ->orWhere('db.nama_brng', 'LIKE', '%imunisasi%')
                  ->orWhere('db.nama_brng', 'LIKE', '%bcg%')
                  ->orWhere('db.nama_brng', 'LIKE', '%dpt%')
                  ->orWhere('db.nama_brng', 'LIKE', '%polio%')
                  ->orWhere('db.nama_brng', 'LIKE', '%campak%')
                  ->orWhere('db.nama_brng', 'LIKE', '%hepatitis%')
                  ->orWhere('db.nama_brng', 'LIKE', '%rotavirus%')
                  ->orWhere('db.nama_brng', 'LIKE', '%influenza%')
                  ->orWhere('db.nama_brng', 'LIKE', '%varisela%')
                  ->orWhere('db.nama_brng', 'LIKE', '%pcv%')
                  ->orWhere('db.nama_brng', 'LIKE', '%ipv%')
                  ->orWhere('db.nama_brng', 'LIKE', '%mr %')
                  ->orWhere('db.nama_brng', 'LIKE', '%mmr%');
            })
            ->whereBetween('dpo.tgl_perawatan', [$tglAwal, $tglAkhir])
            ->select([
                'dpo.no_rawat',
                'dpo.tgl_perawatan',
                'dpo.jam',
                'dpo.kode_brng',
                'dpo.no_batch',
                'dpo.no_faktur',
                'dpo.jml',
                'dpo.status AS status_rawat',
                'db.nama_brng',
                'mv.vaksin_code',
                'mv.vaksin_system',
                'mv.vaksin_display',
                'mv.route_code',
                'mv.route_system',
                'mv.route_display',
                'mv.dose_quantity_code',
                'mv.dose_quantity_system',
                'mv.dose_quantity_unit',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.no_rkm_medis',
                'rp.status_lanjut',
                'p.nm_pasien',
                'p.no_ktp as ktp_pasien',
                'se.id_encounter',
                'poli.nm_poli',
                'mlr.id_lokasi_satusehat AS id_lokasi_ralan',
                'smld.id_lokasi_satusehat AS id_lokasi_depo',
                'peg.nama AS nm_dokter',
                'peg.no_ktp AS ktp_dokter',
                'ap.aturan AS aturan_pakai',
                'ssi.id_immunization',
            ]);

        if (!empty($search)) {
            $builder->where(function ($q) use ($search) {
                $q->where('dpo.no_rawat', 'LIKE', "%{$search}%")
                  ->orWhere('p.nm_pasien', 'LIKE', "%{$search}%")
                  ->orWhere('rp.no_rkm_medis', 'LIKE', "%{$search}%")
                  ->orWhere('db.nama_brng', 'LIKE', "%{$search}%")
                  ->orWhere('mv.vaksin_display', 'LIKE', "%{$search}%")
                  ->orWhere('peg.nama', 'LIKE', "%{$search}%");
            });
        }

        if ($status === 'synced') {
            $builder->whereNotNull('ssi.id_immunization');
        } elseif ($status === 'unsent') {
            $builder->whereNull('ssi.id_immunization');
        }

        return $builder->orderBy('dpo.tgl_perawatan', 'DESC')
            ->orderBy('dpo.jam', 'DESC')
            ->get()
            ->toArray();
    }

    /**
     * Send single Immunization to SATU SEHAT
     */
    public function syncImmunization(string $noRawat, string $tglPerawatan, string $jam, string $kodeBrng, ?string $noBatch = '', ?string $noFaktur = '', array $customOverrides = []): array
    {
        $row = DB::table('detail_pemberian_obat AS dpo')
            ->join('databarang AS db', 'db.kode_brng', '=', 'dpo.kode_brng')
            ->leftJoin('satu_sehat_mapping_vaksin AS mv', 'mv.kode_brng', '=', 'dpo.kode_brng')
            ->join('reg_periksa AS rp', 'rp.no_rawat', '=', 'dpo.no_rawat')
            ->join('pasien AS p', 'p.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->leftJoin('pegawai AS peg', 'peg.nik', '=', 'rp.kd_dokter')
            ->leftJoin('satu_sehat_encounter AS se', 'se.no_rawat', '=', 'dpo.no_rawat')
            ->leftJoin('poliklinik AS poli', 'poli.kd_poli', '=', 'rp.kd_poli')
            ->leftJoin('satu_sehat_mapping_lokasi_ralan AS mlr', 'mlr.kd_poli', '=', 'rp.kd_poli')
            ->leftJoin('bangsal AS bg', 'bg.kd_bangsal', '=', 'dpo.kd_bangsal')
            ->leftJoin('satu_sehat_mapping_lokasi_depo_farmasi AS smld', 'smld.kd_bangsal', '=', 'bg.kd_bangsal')
            ->leftJoin('aturan_pakai AS ap', function ($join) {
                $join->on('ap.no_rawat', '=', 'dpo.no_rawat')
                    ->on('ap.kode_brng', '=', 'dpo.kode_brng')
                    ->on('ap.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                    ->on('ap.jam', '=', 'dpo.jam');
            })
            ->leftJoin('satu_sehat_immunization AS ssi', function ($join) {
                $join->on('ssi.no_rawat', '=', 'dpo.no_rawat')
                    ->on('ssi.tgl_perawatan', '=', 'dpo.tgl_perawatan')
                    ->on('ssi.jam', '=', 'dpo.jam')
                    ->on('ssi.kode_brng', '=', 'dpo.kode_brng')
                    ->on('ssi.no_batch', '=', 'dpo.no_batch')
                    ->on('ssi.no_faktur', '=', 'dpo.no_faktur');
            })
            ->where('dpo.no_rawat', $noRawat)
            ->where('dpo.tgl_perawatan', $tglPerawatan)
            ->where('dpo.jam', $jam)
            ->where('dpo.kode_brng', $kodeBrng)
            ->where('dpo.no_batch', $noBatch ?? '')
            ->where('dpo.no_faktur', $noFaktur ?? '')
            ->select([
                'dpo.no_rawat',
                'dpo.tgl_perawatan',
                'dpo.jam',
                'dpo.kode_brng',
                'dpo.no_batch',
                'dpo.no_faktur',
                'dpo.jml',
                'dpo.status AS status_rawat',
                'db.nama_brng',
                'mv.vaksin_code',
                'mv.vaksin_system',
                'mv.vaksin_display',
                'mv.route_code',
                'mv.route_system',
                'mv.route_display',
                'mv.dose_quantity_code',
                'mv.dose_quantity_system',
                'mv.dose_quantity_unit',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.no_rkm_medis',
                'rp.status_lanjut',
                'p.nm_pasien',
                'p.no_ktp as ktp_pasien',
                'se.id_encounter',
                'poli.nm_poli',
                'mlr.id_lokasi_satusehat AS id_lokasi_ralan',
                'smld.id_lokasi_satusehat AS id_lokasi_depo',
                'peg.nama AS nm_dokter',
                'peg.no_ktp AS ktp_dokter',
                'ap.aturan AS aturan_pakai',
                'ssi.id_immunization',
            ])
            ->first();

        if (!$row) {
            return ['success' => false, 'message' => "Data pemberian imunisasi {$noRawat} ({$kodeBrng}) tidak ditemukan."];
        }

        if (empty($row->id_encounter)) {
            return ['success' => false, 'message' => "Encounter pasien {$noRawat} belum tersinkronisasi ke SATU SEHAT."];
        }

        if (empty($row->ktp_pasien)) {
            return ['success' => false, 'message' => "NIK Pasien {$row->nm_pasien} masih kosong."];
        }

        if (empty($row->ktp_dokter)) {
            return ['success' => false, 'message' => "NIK Dokter pemeriksa masih kosong."];
        }

        $idPasien   = $this->getPatientId($row->ktp_pasien);
        $idPraktisi = $this->getPractitionerId($row->ktp_dokter);
        $idLokasi   = $this->resolveLocationId($row);

        if (!$idPasien) {
            return ['success' => false, 'message' => "IHS Pasien tidak ditemukan untuk NIK {$row->ktp_pasien}."];
        }

        if (!$idPraktisi) {
            return ['success' => false, 'message' => "IHS Dokter tidak ditemukan untuk NIK {$row->ktp_dokter}."];
        }

        // Vaccine Coding (KFA / CVX)
        $system  = !empty($row->vaksin_system) ? $row->vaksin_system : 'http://sys-ids.kemkes.go.id/kfa';
        $code    = !empty($row->vaksin_code) ? trim($row->vaksin_code) : 'VG45';
        $display = !empty($row->vaksin_display) ? $row->vaksin_display : $row->nama_brng;

        // Route Coding
        $routeCode    = !empty($row->route_code) ? trim($row->route_code) : 'inj.intramuscular';
        $routeSystem  = !empty($row->route_system) ? trim($row->route_system) : 'http://www.whocc.no/atc';
        $routeDisplay = !empty($row->route_display) ? trim($row->route_display) : 'Injection Intramuscular';

        // Dose Quantity
        $doseVal  = floatval($row->jml ?: 1);
        $doseUnit = !empty($row->dose_quantity_unit) ? $row->dose_quantity_unit : 'mL';
        $doseCode = !empty($row->dose_quantity_code) ? $row->dose_quantity_code : 'ml';
        $doseSys  = !empty($row->dose_quantity_system) ? $row->dose_quantity_system : 'http://unitsofmeasure.org';

        // Lot Number
        $lotNumber = !empty($row->no_batch) && trim($row->no_batch) !== '' ? trim($row->no_batch) : '-';
        if (!empty($customOverrides['lot_number'])) {
            $lotNumber = trim($customOverrides['lot_number']);
        }

        // Dose Number
        $doseNumber = $this->parseDoseNumber($row->aturan_pakai);
        if (!empty($customOverrides['dose_number'])) {
            $doseNumber = intval($customOverrides['dose_number']);
        }

        // Reason Code
        $reasonCode    = $customOverrides['reason_code'] ?? 'IM-Dasar';
        $reasonDisplay = $customOverrides['reason_display'] ?? 'Imunisasi Program Rutin Dasar';

        $occurrenceTime = Carbon::parse("{$row->tgl_perawatan} {$row->jam}")->format('Y-m-d\TH:i:sP');
        $recordedDate   = Carbon::parse($row->tgl_perawatan)->format('Y-m-d');

        $payload = [
            'resourceType' => 'Immunization',
            'status'       => 'completed',
            'vaccineCode'  => [
                'coding' => [
                    [
                        'system'  => $system,
                        'code'    => $code,
                        'display' => $display,
                    ]
                ]
            ],
            'patient' => [
                'reference' => "Patient/{$idPasien}",
                'display'   => $row->nm_pasien,
            ],
            'encounter' => [
                'reference' => "Encounter/{$row->id_encounter}",
            ],
            'occurrenceDateTime' => $occurrenceTime,
            'recorded'           => $recordedDate,
            'primarySource'      => true,
            'lotNumber'          => $lotNumber,
            'route' => [
                'coding' => [
                    [
                        'system'  => $routeSystem,
                        'code'    => $routeCode,
                        'display' => $routeDisplay,
                    ]
                ]
            ],
            'doseQuantity' => [
                'value'  => $doseVal,
                'unit'   => $doseUnit,
                'system' => $doseSys,
                'code'   => $doseCode,
            ],
            'performer' => [
                [
                    'function' => [
                        'coding' => [
                            [
                                'system'  => 'http://terminology.hl7.org/CodeSystem/v2-0443',
                                'code'    => 'AP',
                                'display' => 'Administering Provider'
                            ]
                        ]
                    ],
                    'actor' => [
                        'reference' => "Practitioner/{$idPraktisi}",
                        'display'   => $row->nm_dokter
                    ]
                ]
            ],
            'reasonCode' => [
                [
                    'coding' => [
                        [
                            'system'  => 'http://terminology.kemkes.go.id/CodeSystem/immunization-reason',
                            'code'    => $reasonCode,
                            'display' => $reasonDisplay,
                        ]
                    ]
                ]
            ],
            'protocolApplied' => [
                [
                    'doseNumberPositiveInt' => $doseNumber > 0 ? $doseNumber : 1,
                ]
            ]
        ];

        if (!empty($idLokasi)) {
            $payload['location'] = [
                'reference' => "Location/{$idLokasi}",
                'display'   => $row->nm_poli ?: 'Ruang Pelayanan',
            ];
        }

        if (!empty($row->id_immunization)) {
            $payload['id'] = $row->id_immunization;
            $res = $this->ss->put("Immunization/{$row->id_immunization}", $payload);
        } else {
            $res = $this->ss->post('Immunization', $payload);
        }

        if ($res['status'] && isset($res['data']['id'])) {
            $immId = $res['data']['id'];

            DB::table('satu_sehat_immunization')->updateOrInsert(
                [
                    'no_rawat'      => $row->no_rawat,
                    'tgl_perawatan' => $row->tgl_perawatan,
                    'jam'           => $row->jam,
                    'kode_brng'     => $row->kode_brng,
                    'no_batch'      => (string)($row->no_batch ?? ''),
                    'no_faktur'     => (string)($row->no_faktur ?? ''),
                ],
                [
                    'id_immunization' => $immId,
                ]
            );

            Log::channel('satusehat')->info("[SatuSehat] Immunization {$row->no_rawat} {$row->kode_brng} berhasil: ID {$immId}");

            return [
                'success' => true,
                'message' => 'Data Imunisasi berhasil disinkronkan ke SatuSehat',
                'id'      => $immId
            ];
        }

        $errMsg = 'Gagal sinkronisasi ke SATU SEHAT';
        if (isset($res['data']['issue'][0]['diagnostics'])) {
            $errMsg = $res['data']['issue'][0]['diagnostics'];
        } elseif (isset($res['message'])) {
            $errMsg = $res['message'];
        }

        Log::channel('satusehat')->error("[SatuSehat] Immunization {$row->no_rawat} {$row->kode_brng} gagal: {$errMsg}");

        return [
            'success' => false,
            'message' => $errMsg
        ];
    }

    /**
     * Batch Sync Immunization
     */
    public function syncBatchImmunization(string $tglAwal, string $tglAkhir, array $keys = []): array
    {
        $list = $this->getImmunizationList([
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
            if (empty($item->id_encounter) || empty($item->ktp_pasien) || empty($item->ktp_dokter)) {
                $skip++;
                continue;
            }

            $res = $this->syncImmunization(
                $item->no_rawat,
                $item->tgl_perawatan,
                $item->jam,
                $item->kode_brng,
                $item->no_batch ?? '',
                $item->no_faktur ?? ''
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

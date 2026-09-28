<?php

namespace App\Services\SatuSehat;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SatuSehatServiceRequestLabService
{
    protected SatuSehatService $ss;
    protected string $orgId;

    public function __construct(?SatuSehatService $satuSehat = null)
    {
        $this->ss    = $satuSehat ?? new SatuSehatService();
        $this->orgId = (string) config('satusehat.org_id');
    }

    /**
     * Get Organization ID
     */
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
     * Base query for lab requests
     */
    public function getBaseQuery()
    {
        return DB::table('permintaan_lab as pr')
            ->join('reg_periksa as rp', 'pr.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->leftJoin('dokter as d', 'pr.dokter_perujuk', '=', 'd.kd_dokter')
            ->leftJoin('pegawai as pg', 'd.kd_dokter', '=', 'pg.nik')
            ->leftJoin('satu_sehat_encounter as se', 'se.no_rawat', '=', 'rp.no_rawat')
            ->join(DB::raw('(
                SELECT noorder, kd_jenis_prw, id_template FROM permintaan_detail_permintaan_lab
                UNION
                SELECT ppl.noorder, ppl.kd_jenis_prw, tl.id_template
                FROM permintaan_pemeriksaan_lab ppl
                JOIN template_laboratorium tl ON tl.kd_jenis_prw = ppl.kd_jenis_prw
                WHERE NOT EXISTS (
                    SELECT 1 FROM permintaan_detail_permintaan_lab d WHERE d.noorder = ppl.noorder
                )
            ) as items'), 'items.noorder', '=', 'pr.noorder')
            ->join('template_laboratorium as tl', 'tl.id_template', '=', 'items.id_template')
            ->join('jns_perawatan_lab as jpl', 'jpl.kd_jenis_prw', '=', 'items.kd_jenis_prw')
            ->leftJoin('satu_sehat_mapping_lab as sml', 'sml.id_template', '=', 'tl.id_template')
            ->leftJoin('satu_sehat_servicerequest_lab as ssl', function ($join) {
                $join->on('ssl.noorder', '=', 'pr.noorder')
                     ->on('ssl.kd_jenis_prw', '=', 'items.kd_jenis_prw')
                     ->on('ssl.id_template', '=', 'items.id_template');
            })
            ->select(
                'pr.no_rawat',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'p.no_ktp',
                'pr.dokter_perujuk',
                'd.nm_dokter',
                'pg.no_ktp as ktp_dokter',
                'se.id_encounter',
                'pr.noorder',
                'pr.tgl_permintaan',
                'pr.jam_permintaan',
                'pr.diagnosa_klinis',
                'items.kd_jenis_prw',
                'jpl.nm_perawatan',
                'items.id_template',
                'tl.Pemeriksaan',
                'sml.code',
                'sml.system',
                'sml.display',
                'sml.sampel_code',
                'sml.sampel_system',
                'sml.sampel_display',
                'ssl.id_servicerequest'
            );
    }

    /**
     * Get list of lab service requests with filters
     */
    public function getListServiceRequestLab(array $params = []): array
    {
        $query = $this->getBaseQuery();

        if (!empty($params['tgl_awal']) && !empty($params['tgl_akhir'])) {
            $query->whereBetween('pr.tgl_permintaan', [$params['tgl_awal'], $params['tgl_akhir']]);
        }

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('pr.noorder', 'like', "%{$search}%")
                  ->orWhere('p.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('rp.no_rawat', 'like', "%{$search}%")
                  ->orWhere('tl.Pemeriksaan', 'like', "%{$search}%")
                  ->orWhere('jpl.nm_perawatan', 'like', "%{$search}%")
                  ->orWhere('sml.code', 'like', "%{$search}%");
            });
        }

        if (!empty($params['status'])) {
            if ($params['status'] === 'sent') {
                $query->whereNotNull('ssl.id_servicerequest');
            } elseif ($params['status'] === 'unsent') {
                $query->whereNull('ssl.id_servicerequest');
            }
        }

        $query->orderBy('pr.tgl_permintaan', 'desc')
              ->orderBy('pr.jam_permintaan', 'desc')
              ->orderBy('pr.noorder', 'desc');

        return $query->get()->toArray();
    }

    /**
     * Send single ServiceRequest to SatuSehat
     */
    public function send(array $data): array
    {
        $logId = "[ServiceRequestLab {$data['no_rawat']} | {$data['noorder']} | {$data['id_template']}]";

        if (empty($data['no_ktp'])) {
            return ['success' => false, 'message' => 'NIK Pasien kosong'];
        }

        if (empty($data['ktp_dokter'])) {
            return ['success' => false, 'message' => 'NIK Dokter Perujuk kosong'];
        }

        if (empty($data['id_encounter'])) {
            return ['success' => false, 'message' => 'Encounter belum dikirim ke SatuSehat'];
        }

        if (empty($data['code'])) {
            return ['success' => false, 'message' => 'Pemeriksaan belum dimapping ke LOINC'];
        }

        try {
            $idPasien = $this->getPatientId($data['no_ktp']);
            if (!$idPasien) {
                return ['success' => false, 'message' => 'NIK Pasien (' . $data['no_ktp'] . ') tidak ditemukan di SatuSehat'];
            }

            $idDokter = $this->getPractitionerId($data['ktp_dokter']);
            if (!$idDokter) {
                return ['success' => false, 'message' => 'NIK Dokter (' . $data['ktp_dokter'] . ') tidak ditemukan di SatuSehat'];
            }

            $codeSystem  = !empty($data['system']) ? $data['system'] : 'http://loinc.org';
            $codeDisplay = !empty($data['display']) ? $data['display'] : $data['Pemeriksaan'];

            $payload = [
                'resourceType' => 'ServiceRequest',
                'identifier' => [
                    [
                        'system' => "http://sys-ids.kemkes.go.id/servicerequest/{$this->orgId}",
                        'value'  => $data['noorder'] . '.' . $data['id_template']
                    ]
                ],
                'status' => 'active',
                'intent' => 'order',
                'category' => [
                    [
                        'coding' => [
                            [
                                'system'  => 'http://snomed.info/sct',
                                'code'    => '108252007',
                                'display' => 'Laboratory procedure'
                            ]
                        ]
                    ]
                ],
                'code' => [
                    'coding' => [
                        [
                            'system'  => $codeSystem,
                            'code'    => (string) $data['code'],
                            'display' => $codeDisplay
                        ]
                    ],
                    'text' => $data['Pemeriksaan']
                ],
                'subject' => [
                    'reference' => "Patient/{$idPasien}"
                ],
                'encounter' => [
                    'reference' => "Encounter/{$data['id_encounter']}",
                    'display'   => "Permintaan {$data['Pemeriksaan']} atas nama pasien {$data['nm_pasien']} No.RM {$data['no_rkm_medis']} No.Rawat {$data['no_rawat']}, pada tanggal {$data['tgl_permintaan']} {$data['jam_permintaan']}"
                ],
                'authoredOn' => "{$data['tgl_permintaan']}T{$data['jam_permintaan']}+07:00",
                'requester' => [
                    'reference' => "Practitioner/{$idDokter}",
                    'display'   => trim(explode(',', $data['nm_dokter'] ?? '')[0])
                ],
                'performer' => [
                    [
                        'reference' => "Organization/{$this->orgId}",
                        'display'   => 'Ruang Laborat/Petugas Laborat'
                    ]
                ],
                'reasonCode' => [
                    [
                        'text' => (!empty($data['diagnosa_klinis']) && $data['diagnosa_klinis'] !== '-')
                            ? $data['diagnosa_klinis']
                            : 'Permintaan pemeriksaan laboratorium klinis'
                    ]
                ]
            ];

            // PUT if already exists, else POST
            if (!empty($data['id_servicerequest'])) {
                $endpoint = "ServiceRequest/{$data['id_servicerequest']}";
                $payload['id'] = $data['id_servicerequest'];
                $response = $this->ss->put($endpoint, $payload);
            } else {
                $endpoint = 'ServiceRequest';
                $response = $this->ss->post($endpoint, $payload);
            }

            $serviceRequestId = data_get($response, 'data.id');

            if ($serviceRequestId) {
                DB::table('satu_sehat_servicerequest_lab')->updateOrInsert(
                    [
                        'noorder'      => $data['noorder'],
                        'kd_jenis_prw' => $data['kd_jenis_prw'],
                        'id_template'  => $data['id_template']
                    ],
                    [
                        'id_servicerequest' => $serviceRequestId
                    ]
                );

                return [
                    'success'           => true,
                    'message'           => 'Berhasil kirim ServiceRequest ke SatuSehat',
                    'id_servicerequest' => $serviceRequestId,
                    'payload'           => $payload,
                    'response'          => $response
                ];
            } else {
                $errMsg = data_get($response, 'message')
                    ?? data_get($response, 'data.issue.0.details.text')
                    ?? 'Gagal mendapatkan ID ServiceRequest dari SatuSehat';

                Log::channel('satusehat')->error("{$logId} Failed: " . json_encode($response));

                return [
                    'success'  => false,
                    'message'  => $errMsg,
                    'payload'  => $payload,
                    'response' => $response
                ];
            }
        } catch (\Exception $e) {
            Log::channel('satusehat')->error("{$logId} Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Batch send lab requests to SatuSehat
     */
    public function sendBatch(string $tglAwal, string $tglAkhir, array $selectedKeys = []): array
    {
        $query = $this->getBaseQuery()
            ->whereBetween('pr.tgl_permintaan', [$tglAwal, $tglAkhir])
            ->whereNotNull('sml.code')
            ->whereNotNull('se.id_encounter');

        if (!empty($selectedKeys)) {
            // $selectedKeys items are in format: "noorder_kd_jenis_prw_id_template"
            $query->where(function ($q) use ($selectedKeys) {
                foreach ($selectedKeys as $key) {
                    $parts = explode('___', $key);
                    if (count($parts) === 3) {
                        $q->orWhere(function ($sub) use ($parts) {
                            $sub->where('pr.noorder', $parts[0])
                                ->where('items.kd_jenis_prw', $parts[1])
                                ->where('items.id_template', $parts[2]);
                        });
                    }
                }
            });
        } else {
            // Default only unsent
            $query->whereNull('ssl.id_servicerequest');
        }

        $requests = $query->get();

        $success = 0;
        $error   = 0;
        $details = [];

        foreach ($requests as $req) {
            $res = $this->send((array) $req);
            if ($res['success']) {
                $success++;
            } else {
                $error++;
            }
            $details[] = [
                'noorder'     => $req->noorder,
                'id_template' => $req->id_template,
                'pemeriksaan' => $req->Pemeriksaan,
                'success'     => $res['success'],
                'message'     => $res['message']
            ];

            // Throttle to respect Kemkes rate limit (~200 req/min)
            usleep(350000); // 350ms
        }

        return [
            'total'   => count($requests),
            'success' => $success,
            'error'   => $error,
            'details' => $details
        ];
    }
}

<?php

namespace App\Services\SatuSehat;

use App\Models\Departemen;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Exception;

class SatuSehatService extends SatuSehatClient
{
    /**
     * Map a local department to a Satu Sehat Organization
     *
     * @param string $depId
     * @return array
     */
    public function mapOrganization(string $depId): array
    {
        $dept = Departemen::where('dep_id', $depId)->first();
        if (!$dept) {
            return ['status' => false, 'message' => 'Departemen tidak ditemukan'];
        }

        $setting = Setting::first();
        if (!$setting) {
            return ['status' => false, 'message' => 'Pengaturan rumah sakit belum diisi'];
        }

        $payload = [
            'resourceType' => 'Organization',
            'active'       => true,
            'identifier'   => [
                [
                    'use'    => 'official',
                    'system' => 'https://fhir.kemkes.go.id/id/organization',
                    'value'  => $dept->nama,
                ]
            ],
            'type' => [
                [
                    'coding' => [
                        [
                            'system'  => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code'    => 'dept',
                            'display' => 'Hospital Department',
                        ]
                    ]
                ]
            ],
            'name'    => $dept->nama,
            'telecom' => [
                ['system' => 'phone', 'value' => $setting->kontak, 'use' => 'work'],
                ['system' => 'email', 'value' => $setting->email, 'use' => 'work'],
                ['system' => 'url', 'value' => 'www.' . $setting->email, 'use' => 'work'],
            ],
            'address' => [
                [
                    'use'        => 'work',
                    'type'       => 'both',
                    'line'       => [$setting->alamat_instansi],
                    'city'       => $setting->kabupaten,
                    'postalCode' => config('satusehat.postal_code'),
                    'country'    => 'ID',
                    'extension'  => [
                        [
                            'url'       => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                            'extension' => [
                                ['url' => 'province', 'valueCode' => config('satusehat.province_code')],
                                ['url' => 'city', 'valueCode' => config('satusehat.city_code')],
                                ['url' => 'district', 'valueCode' => config('satusehat.district_code')],
                                ['url' => 'village', 'valueCode' => config('satusehat.village_code')],
                            ]
                        ]
                    ]
                ]
            ],
            'partOf' => [
                'reference' => 'Organization/' . config('satusehat.org_id')
            ]
        ];

        // Check if already mapped
        $mapping = DB::table('satu_sehat_mapping_departemen')->where('dep_id', $depId)->first();

        if ($mapping && $mapping->id_organisasi_satusehat) {
            // Update existing
            $payload['id'] = $mapping->id_organisasi_satusehat;
            $result = $this->put('Organization/' . $mapping->id_organisasi_satusehat, $payload);
        } else {
            // Create new
            $result = $this->post('Organization', $payload);
        }

        if ($result['status']) {
            $idSatuSehat = $result['data']['id'];
            
            DB::table('satu_sehat_mapping_departemen')->updateOrInsert(
                ['dep_id' => $depId],
                ['id_organisasi_satusehat' => $idSatuSehat]
            );

            return ['status' => true, 'message' => 'Mapping berhasil', 'id' => $idSatuSehat];
        }

        return $result;
    }

    /**
     * Get all departments with their mapping status
     */
    public function getMappingStatus(): array
    {
        return DB::table('departemen')
            ->leftJoin('satu_sehat_mapping_departemen', 'departemen.dep_id', '=', 'satu_sehat_mapping_departemen.dep_id')
            ->select('departemen.dep_id', 'departemen.nama', 'satu_sehat_mapping_departemen.id_organisasi_satusehat')
            ->get()
            ->toArray();
    }

    /**
     * Get patient data by NIK from Satu Sehat
     *
     * @param string $nik
     * @return array
     */
    public function getPatient(string $nik): array
    {
        $identifier = "https://fhir.kemkes.go.id/id/nik|{$nik}";
        return $this->get('Patient', ['identifier' => $identifier]);
    }

    /**
     * Get location mapping status
     *
     * @param string $type (ralan, ranap, lab, labmb, labpa, ok, rad)
     * @return array
     */
    public function getLocationMappingStatus(string $type): array
    {
        $query = null;
        switch ($type) {
            case 'ralan':
                $query = DB::table('poliklinik')
                    ->leftJoin('satu_sehat_mapping_lokasi_ralan', 'poliklinik.kd_poli', '=', 'satu_sehat_mapping_lokasi_ralan.kd_poli')
                    ->select(
                        'poliklinik.kd_poli as id',
                        'poliklinik.nm_poli as nama',
                        'satu_sehat_mapping_lokasi_ralan.id_lokasi_satusehat',
                        'satu_sehat_mapping_lokasi_ralan.id_organisasi_satusehat',
                        'satu_sehat_mapping_lokasi_ralan.longitude',
                        'satu_sehat_mapping_lokasi_ralan.latitude',
                        'satu_sehat_mapping_lokasi_ralan.altittude as altitude'
                    );
                break;
            case 'ranap':
                $query = DB::table('kamar')
                    ->leftJoin('satu_sehat_mapping_lokasi_ranap', 'kamar.kd_kamar', '=', 'satu_sehat_mapping_lokasi_ranap.kd_kamar')
                    ->select(
                        'kamar.kd_kamar as id',
                        'kamar.kd_kamar as nama', // Usually room number is name
                        'satu_sehat_mapping_lokasi_ranap.id_lokasi_satusehat',
                        'satu_sehat_mapping_lokasi_ranap.id_organisasi_satusehat',
                        'satu_sehat_mapping_lokasi_ranap.longitude',
                        'satu_sehat_mapping_lokasi_ranap.latitude',
                        'satu_sehat_mapping_lokasi_ranap.altittude as altitude'
                    );
                break;
            case 'depo':
                $query = DB::table('bangsal')
                    ->leftJoin('satu_sehat_mapping_lokasi_depo_farmasi', 'bangsal.kd_bangsal', '=', 'satu_sehat_mapping_lokasi_depo_farmasi.kd_bangsal')
                    ->select(
                        'bangsal.kd_bangsal as id',
                        'bangsal.nm_bangsal as nama',
                        'satu_sehat_mapping_lokasi_depo_farmasi.id_lokasi_satusehat',
                        'satu_sehat_mapping_lokasi_depo_farmasi.id_organisasi_satusehat',
                        'satu_sehat_mapping_lokasi_depo_farmasi.longitude',
                        'satu_sehat_mapping_lokasi_depo_farmasi.latitude',
                        'satu_sehat_mapping_lokasi_depo_farmasi.altittude as altitude'
                    );
                break;
            case 'lab':
            case 'labmb':
            case 'labpa':
            case 'ok':
            case 'rad':
                $tableName = "satu_sehat_mapping_lokasi_ruang{$type}";
                if ($type == 'lab') $tableName = "satu_sehat_mapping_lokasi_ruanglab";
                
                $query = DB::table($tableName)
                    ->select(
                        'id_lokasi_satusehat as id',
                        DB::raw("'Ruang " . strtoupper($type) . "' as nama"),
                        'id_lokasi_satusehat',
                        'id_organisasi_satusehat',
                        'longitude',
                        'latitude',
                        'altittude as altitude'
                    );
                break;
        }

        return $query ? $query->get()->toArray() : [];
    }

    /**
     * Map a location to Satu Sehat
     *
     * @param string $type
     * @param string $id (local id)
     * @param array $extraData (longitude, latitude, altitude, organization_id)
     * @return array
     */
    public function mapLocation(string $type, string $id, array $extraData): array
    {
        // 1. Prepare data based on type
        $name = "";
        $identifierValue = $id;
        $tableName = "";
        $idField = "";

        switch ($type) {
            case 'ralan':
                $item = DB::table('poliklinik')->where('kd_poli', $id)->first();
                $name = $item->nm_poli;
                $tableName = 'satu_sehat_mapping_lokasi_ralan';
                $idField = 'kd_poli';
                break;
            case 'ranap':
                $item = DB::table('kamar')->where('kd_kamar', $id)->first();
                $name = "Kamar " . $item->kd_kamar;
                $tableName = 'satu_sehat_mapping_lokasi_ranap';
                $idField = 'kd_kamar';
                break;
            case 'depo':
                $item = DB::table('bangsal')->where('kd_bangsal', $id)->first();
                $name = $item->nm_bangsal;
                $tableName = 'satu_sehat_mapping_lokasi_depo_farmasi';
                $idField = 'kd_bangsal';
                break;
            // Ruang specific usually don't have local table in some Khanza versions, 
            // but the user provided mapping tables that seem to act as the primary record.
            default:
                $tableName = "satu_sehat_mapping_lokasi_ruang{$type}";
                if ($type == 'lab') $tableName = "satu_sehat_mapping_lokasi_ruanglab";
                $name = "Ruang " . strtoupper($type);
                $idField = 'id_lokasi_satusehat';
                break;
        }

        $payload = [
            'resourceType' => 'Location',
            'status'       => 'active',
            'name'         => $name,
            'mode'         => 'instance',
            'identifier'   => [
                [
                    'use'    => 'official',
                    'system' => 'https://fhir.kemkes.go.id/id/location',
                    'value'  => $identifierValue
                ]
            ],
            'type' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                            'code'   => $type == 'ranap' ? 'HOSP' : 'AMB', // Example
                            'display'=> $type == 'ranap' ? 'Hospital' : 'Ambulatory'
                        ]
                    ]
                ]
            ],
            'physicalType' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                        'code'   => 'ro',
                        'display'=> 'Room'
                    ]
                ]
            ],
            'managingOrganization' => [
                'reference' => 'Organization/' . $extraData['organization_id']
            ],
            'position' => [
                'longitude' => (float) $extraData['longitude'],
                'latitude'  => (float) $extraData['latitude'],
                'altitude'  => (float) $extraData['altitude']
            ]
        ];

        // Check if already mapped
        $mapping = DB::table($tableName)->where($idField, $id)->first();

        if ($mapping && $mapping->id_lokasi_satusehat) {
            $result = $this->put('Location/' . $mapping->id_lokasi_satusehat, $payload);
        } else {
            $result = $this->post('Location', $payload);
        }

        if ($result['status']) {
            $idLokasi = $result['data']['id'];
            DB::table($tableName)->updateOrInsert(
                [$idField => $id],
                [
                    'id_lokasi_satusehat'     => $idLokasi,
                    'id_organisasi_satusehat' => $extraData['organization_id'],
                    'longitude'               => $extraData['longitude'],
                    'latitude'                => $extraData['latitude'],
                    'altittude'               => $extraData['altitude'],
                ]
            );
            return ['status' => true, 'message' => 'Mapping Lokasi berhasil', 'id' => $idLokasi];
        }

        return $result;
    }

    /**
     * Get Encounter list from reg_periksa
     *
     * @param string|null $search
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getEncounterList($search = null, $startDate = null, $endDate = null): array
    {
        $query = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('pegawai', 'reg_periksa.kd_dokter', '=', 'pegawai.nik')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->leftJoin('satu_sehat_mapping_lokasi_ralan', 'poliklinik.kd_poli', '=', 'satu_sehat_mapping_lokasi_ralan.kd_poli')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->select(
                'reg_periksa.no_rawat',
                'reg_periksa.tgl_registrasi',
                'reg_periksa.jam_reg',
                'pasien.nm_pasien',
                'pasien.no_ktp as ktp_pasien',
                'pegawai.nama as nama_dokter',
                'pegawai.no_ktp as ktp_dokter',
                'poliklinik.nm_poli',
                'satu_sehat_mapping_lokasi_ralan.id_lokasi_satusehat',
                'reg_periksa.status_lanjut',
                'satu_sehat_encounter.id_encounter'
            );

        if ($startDate && $endDate) {
            $query->whereBetween('reg_periksa.tgl_registrasi', [$startDate, $endDate]);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('reg_periksa.no_rawat', 'like', "%{$search}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('pasien.no_rkm_medis', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('reg_periksa.tgl_registrasi', 'desc')
            ->orderBy('reg_periksa.jam_reg', 'desc')
            ->limit(100)
            ->get()
            ->toArray();
    }

    /**
     * Sync Encounter to Satu Sehat
     *
     * @param string $no_rawat
     * @return array
     */
    public function syncEncounter(string $no_rawat): array
    {
        $data = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('pegawai', 'reg_periksa.kd_dokter', '=', 'pegawai.nik')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->leftJoin('satu_sehat_mapping_lokasi_ralan', 'poliklinik.kd_poli', '=', 'satu_sehat_mapping_lokasi_ralan.kd_poli')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->select(
                'reg_periksa.*',
                'pasien.nm_pasien',
                'pasien.no_ktp as ktp_pasien',
                'pegawai.nama as nama_dokter',
                'pegawai.no_ktp as ktp_dokter',
                'poliklinik.nm_poli',
                'satu_sehat_mapping_lokasi_ralan.id_lokasi_satusehat',
                'satu_sehat_encounter.id_encounter'
            )
            ->where('reg_periksa.no_rawat', $no_rawat)
            ->first();

        if (!$data) {
            return ['status' => false, 'message' => 'Data registrasi tidak ditemukan'];
        }

        if (!$data->ktp_pasien) {
            return ['status' => false, 'message' => 'NIK Pasien belum diisi'];
        }

        if (!$data->ktp_dokter) {
            return ['status' => false, 'message' => 'NIK Dokter belum diisi'];
        }

        if (!$data->id_lokasi_satusehat) {
            return ['status' => false, 'message' => 'Lokasi Poli belum dimapping ke Satu Sehat'];
        }

        // 1. Get Patient ID from IHS
        $patientRes = $this->getPatient($data->ktp_pasien);
        if (!$patientRes['status'] || empty($patientRes['data']['entry'])) {
            return ['status' => false, 'message' => 'ID IHS Pasien tidak ditemukan'];
        }
        $patientIhsId = $patientRes['data']['entry'][0]['resource']['id'];

        // 2. Get Practitioner ID from IHS
        $practitionerRes = $this->get('Practitioner', ['identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $data->ktp_dokter]);
        if (!$practitionerRes['status'] || empty($practitionerRes['data']['entry'])) {
            return ['status' => false, 'message' => 'ID IHS Dokter tidak ditemukan'];
        }
        $practitionerIhsId = $practitionerRes['data']['entry'][0]['resource']['id'];

        // 3. Build Payload
        $payload = [
            'resourceType' => 'Encounter',
            'status'       => 'arrived',
            'class'        => [
                'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code'    => $data->status_lanjut == 'Ralan' ? 'AMB' : 'IMP',
                'display' => $data->status_lanjut == 'Ralan' ? 'ambulatory' : 'inpatient encounter'
            ],
            'subject' => [
                'reference' => 'Patient/' . $patientIhsId
            ],
            'participant' => [
                [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                                    'code'    => 'ATND',
                                    'display' => 'attender'
                                ]
                            ]
                        ]
                    ],
                    'individual' => [
                        'reference' => 'Practitioner/' . $practitionerIhsId
                    ]
                ]
            ],
            'statusHistory' => [
                [
                    'status' => 'arrived',
                    'period' => [
                        'start' => $data->tgl_registrasi . 'T' . $data->jam_reg . '+07:00'
                    ]
                ]
            ],
            'period' => [
                'start' => $data->tgl_registrasi . 'T' . $data->jam_reg . '+07:00'
            ],
            'location' => [
                [
                    'location' => [
                        'reference' => 'Location/' . $data->id_lokasi_satusehat
                    ]
                ]
            ],
            'serviceProvider' => [
                'reference' => 'Organization/' . config('satusehat.org_id')
            ],
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/encounter/' . config('satusehat.org_id'),
                    'value'  => $data->no_rawat
                ]
            ]
        ];

        if ($data->id_encounter) {
            $idEncounter = trim($data->id_encounter);
            $payload['id'] = $idEncounter;
            $result = $this->put('Encounter/' . $idEncounter, $payload);
        } else {
            $result = $this->post('Encounter', $payload);
        }

        if ($result['status']) {
            $idEncounter = $result['data']['id'];
            DB::table('satu_sehat_encounter')->updateOrInsert(
                ['no_rawat' => $no_rawat],
                ['id_encounter' => $idEncounter]
            );
            return ['status' => true, 'message' => 'Sync Encounter berhasil', 'id' => $idEncounter];
        }

        return $result;
    }

    /**
     * Get Condition list (Diagnosa)
     *
     * @param string|null $search
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getConditionList($search = null, $startDate = null, $endDate = null): array
    {
        $query = DB::table('diagnosa_pasien')
            ->join('reg_periksa', 'diagnosa_pasien.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->leftJoin('satu_sehat_condition', function($join) {
                $join->on('diagnosa_pasien.no_rawat', '=', 'satu_sehat_condition.no_rawat')
                     ->on('diagnosa_pasien.kd_penyakit', '=', 'satu_sehat_condition.kd_penyakit')
                     ->on('diagnosa_pasien.status', '=', 'satu_sehat_condition.status');
            })
            ->select(
                'diagnosa_pasien.no_rawat',
                'reg_periksa.tgl_registrasi',
                'pasien.nm_pasien',
                'pasien.no_ktp as ktp_pasien',
                'diagnosa_pasien.kd_penyakit',
                'penyakit.nm_penyakit',
                'diagnosa_pasien.status as status_ralan_ranap',
                'diagnosa_pasien.prioritas as status_diagnosa',
                'satu_sehat_encounter.id_encounter',
                'satu_sehat_condition.id_condition'
            );

        if ($startDate && $endDate) {
            $query->whereBetween('reg_periksa.tgl_registrasi', [$startDate, $endDate]);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('diagnosa_pasien.no_rawat', 'like', "%{$search}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$search}%")
                  ->orWhere('penyakit.nm_penyakit', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('reg_periksa.tgl_registrasi', 'desc')
            ->limit(100)
            ->get()
            ->toArray();
    }

    /**
     * Sync Condition to Satu Sehat and finish Encounter
     *
     * @param string $no_rawat
     * @param string $kd_penyakit
     * @param string $status (1/2 or Utama/Tambahan)
     * @return array
     */
    public function syncCondition(string $no_rawat, string $kd_penyakit, string $status): array
    {
        $data = DB::table('diagnosa_pasien')
            ->join('reg_periksa', 'diagnosa_pasien.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->join('pegawai', 'reg_periksa.kd_dokter', '=', 'pegawai.nik')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->leftJoin('satu_sehat_mapping_lokasi_ralan', 'poliklinik.kd_poli', '=', 'satu_sehat_mapping_lokasi_ralan.kd_poli')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->leftJoin('satu_sehat_condition', function($join) {
                $join->on('diagnosa_pasien.no_rawat', '=', 'satu_sehat_condition.no_rawat')
                     ->on('diagnosa_pasien.kd_penyakit', '=', 'satu_sehat_condition.kd_penyakit')
                     ->on('diagnosa_pasien.status', '=', 'satu_sehat_condition.status');
            })
            ->select(
                'diagnosa_pasien.no_rawat',
                'diagnosa_pasien.kd_penyakit',
                'diagnosa_pasien.status',
                'diagnosa_pasien.prioritas',
                'pasien.nm_pasien',
                'pasien.no_ktp as ktp_pasien',
                'penyakit.nm_penyakit',
                'pegawai.no_ktp as ktp_dokter',
                'pegawai.nama as nama_dokter',
                'poliklinik.nm_poli',
                'satu_sehat_mapping_lokasi_ralan.id_lokasi_satusehat',
                'reg_periksa.tgl_registrasi',
                'reg_periksa.jam_reg',
                'reg_periksa.status_lanjut',
                'satu_sehat_encounter.id_encounter',
                'satu_sehat_condition.id_condition'
            )
            ->where('diagnosa_pasien.no_rawat', $no_rawat)
            ->where('diagnosa_pasien.kd_penyakit', $kd_penyakit)
            ->where('diagnosa_pasien.prioritas', $status) // status di sini adalah prioritas (1/2) dari UI
            ->first();

        if (!$data) {
            return ['status' => false, 'message' => 'Data diagnosa tidak ditemukan'];
        }

        if (!$data->id_encounter) {
            return ['status' => false, 'message' => 'Encounter belum disinkronkan. Silakan sync Encounter terlebih dahulu.'];
        }

        // 1. Get Patient ID
        $patientRes = $this->getPatient($data->ktp_pasien);
        if (!$patientRes['status'] || empty($patientRes['data']['entry'])) {
            return ['status' => false, 'message' => 'ID IHS Pasien tidak ditemukan'];
        }
        $patientIhsId = $patientRes['data']['entry'][0]['resource']['id'];

        // 2. Sync Condition
        $condPayload = [
            'resourceType'   => 'Condition',
            'clinicalStatus' => [
                'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical', 'code' => 'active', 'display' => 'Active']]
            ],
            'category' => [
                ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/condition-category', 'code' => 'encounter-diagnosis', 'display' => 'Encounter Diagnosis']]]
            ],
            'code' => [
                'coding' => [['system' => 'http://hl7.org/fhir/sid/icd-10', 'code' => $data->kd_penyakit, 'display' => $data->nm_penyakit]]
            ],
            'subject' => ['reference' => 'Patient/' . $patientIhsId, 'display' => $data->nm_pasien],
            'encounter' => ['reference' => 'Encounter/' . $data->id_encounter]
        ];

        if ($data->id_condition) {
            $idCondition = trim($data->id_condition);
            $condPayload['id'] = $idCondition;
            $condRes = $this->put('Condition/' . $idCondition, $condPayload);
        } else {
            $condRes = $this->post('Condition', $condPayload);
        }

        if (!$condRes['status']) {
            return $condRes;
        }

        $idCondition = $condRes['data']['id'];
        DB::table('satu_sehat_condition')->updateOrInsert(
            [
                'no_rawat'    => $no_rawat,
                'kd_penyakit' => $kd_penyakit,
                'status'      => $data->status // Pastikan ini 'Ralan' atau 'Ranap' sesuai tabel diagnosa_pasien
            ],
            ['id_condition' => $idCondition]
        );

        // 3. Finish Encounter
        // Get Practitioner ID
        $practitionerRes = $this->get('Practitioner', ['identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $data->ktp_dokter]);
        if (!$practitionerRes['status'] || empty($practitionerRes['data']['entry'])) {
            return ['status' => true, 'message' => 'Sync Condition berhasil, tapi Gagal Finish Encounter: ID IHS Dokter tidak ditemukan'];
        }
        $practitionerIhsId = $practitionerRes['data']['entry'][0]['resource']['id'];

        // Ambil waktu selesai/pulang
        $tglKeluar = $data->tgl_registrasi;
        $jamKeluar = $data->jam_reg;

        // Coba cari waktu pemeriksaan ralan
        $pemeriksaan = DB::table('pemeriksaan_ralan')->where('no_rawat', $no_rawat)->first();
        if ($pemeriksaan) {
            $tglKeluar = $pemeriksaan->tgl_perawatan;
            $jamKeluar = $pemeriksaan->jam_rawat;
        } else {
            // Coba cari waktu keluar ranap
            $kamarInap = DB::table('kamar_inap')->where('no_rawat', $no_rawat)->orderBy('tgl_keluar', 'desc')->first();
            if ($kamarInap && $kamarInap->tgl_keluar != '0000-00-00') {
                $tglKeluar = $kamarInap->tgl_keluar;
                $jamKeluar = $kamarInap->jam_keluar;
            }
        }

        $idEncounter = trim($data->id_encounter);
        $encPayload = [
            'resourceType' => 'Encounter',
            'id'           => $idEncounter,
            'status'       => 'finished',
            'class'        => [
                'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code'    => $data->status_lanjut == 'Ralan' ? 'AMB' : 'IMP',
                'display' => $data->status_lanjut == 'Ralan' ? 'ambulatory' : 'inpatient encounter'
            ],
            'subject' => ['reference' => 'Patient/' . $patientIhsId, 'display' => $data->nm_pasien],
            'participant' => [
                [
                    'type' => [['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'code' => 'ATND', 'display' => 'attender']]]],
                    'individual' => ['reference' => 'Practitioner/' . $practitionerIhsId, 'display' => $data->nama_dokter]
                ]
            ],
            'period' => [
                'start' => $data->tgl_registrasi . 'T' . $data->jam_reg . '+07:00',
                'end'   => $tglKeluar . 'T' . $jamKeluar . '+07:00'
            ],
            'location' => [
                [
                    'location' => [
                        'reference' => 'Location/' . $data->id_lokasi_satusehat,
                        'display'   => $data->nm_poli ?? ''
                    ]
                ]
            ],
            'diagnosis' => [
                [
                    'condition' => ['reference' => 'Condition/' . $idCondition, 'display' => $data->nm_penyakit],
                    'use' => [
                        'coding' => [
                            [
                                'system'  => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
                                'code'    => 'DD',
                                'display' => 'Discharge diagnosis'
                            ]
                        ]
                    ],
                    'rank' => 1
                ]
            ],
            'statusHistory' => [
                [
                    'status' => 'arrived',
                    'period' => [
                        'start' => $data->tgl_registrasi . 'T' . $data->jam_reg . '+07:00',
                        'end'   => $data->tgl_registrasi . 'T' . $data->jam_reg . '+07:00'
                    ]
                ],
                [
                    'status' => 'in-progress',
                    'period' => [
                        'start' => $tglKeluar . 'T' . $jamKeluar . '+07:00',
                        'end'   => $tglKeluar . 'T' . $jamKeluar . '+07:00'
                    ]
                ],
                [
                    'status' => 'finished',
                    'period' => [
                        'start' => $tglKeluar . 'T' . $jamKeluar . '+07:00',
                        'end'   => $tglKeluar . 'T' . $jamKeluar . '+07:00'
                    ]
                ]
            ],
            'serviceProvider' => ['reference' => 'Organization/' . config('satusehat.org_id')],
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/encounter/' . config('satusehat.org_id'),
                    'value'  => $data->no_rawat
                ]
            ]
        ];

        $encRes = $this->put('Encounter/' . $idEncounter, $encPayload);

        if ($encRes['status']) {
            return ['status' => true, 'message' => 'Sync Condition & Finish Encounter berhasil'];
        }

        return ['status' => true, 'message' => 'Sync Condition berhasil, tapi Gagal Finish Encounter: ' . ($encRes['message'] ?? 'Unknown error')];
    }
    /**
     * Get Observation TTV List
     */
    public function getObservationTTVList($startDate, $endDate)
    {
        $columns = [
            'p.no_rawat',
            'p.tgl_perawatan',
            'p.jam_rawat',
            'p.suhu_tubuh',
            'p.tensi',
            'p.nadi',
            'p.respirasi',
            'p.tinggi',
            'p.berat',
            'p.spo2',
            'p.gcs',
            'p.kesadaran',
            'p.nip',
            'pasien.nm_pasien',
            'pasien.no_ktp as ktp_pasien',
            'pegawai.nama as nama_petugas',
            'pegawai.no_ktp as ktp_petugas',
            'reg_periksa.status_lanjut',
            'satu_sehat_encounter.id_encounter',
            'satu_sehat_observationttvsuhu.id_observation as id_suhu',
            'satu_sehat_observationttvtensi.id_observation as id_tensi',
            'satu_sehat_observationttvnadi.id_observation as id_nadi',
            'satu_sehat_observationttvrespirasi.id_observation as id_respirasi',
            'satu_sehat_observationttvbb.id_observation as id_bb',
            'satu_sehat_observationttvtb.id_observation as id_tb',
            'satu_sehat_observationttvspo2.id_observation as id_spo2',
            'satu_sehat_observationttvgcs.id_observation as id_gcs',
            'satu_sehat_observationttvlp.id_observation as id_lp'
        ];

        $ralanColumns = $columns;
        $ralanColumns[12] = 'p.lingkar_perut';
        // Add nip back
        $ralanColumns[] = 'p.nip';

        $ralan = DB::table('pemeriksaan_ralan as p')
            ->join('reg_periksa', 'p.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('pegawai', 'p.nip', '=', 'pegawai.nik')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->leftJoin('satu_sehat_observationttvsuhu', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvsuhu.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvsuhu.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvsuhu.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvtensi', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvtensi.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvtensi.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvtensi.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvnadi', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvnadi.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvnadi.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvnadi.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvrespirasi', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvrespirasi.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvrespirasi.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvrespirasi.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvbb', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvbb.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvbb.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvbb.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvtb', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvtb.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvtb.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvtb.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvspo2', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvspo2.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvspo2.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvspo2.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvgcs', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvgcs.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvgcs.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvgcs.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvlp', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvlp.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvlp.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvlp.jam_rawat');
            })
            ->select([
                'p.no_rawat', 'p.tgl_perawatan', 'p.jam_rawat', 'p.suhu_tubuh', 'p.tensi', 'p.nadi', 'p.respirasi', 'p.tinggi', 'p.berat', 'p.spo2', 'p.gcs', 'p.kesadaran', 'p.lingkar_perut', 'p.nip',
                'pasien.nm_pasien', 'pasien.no_ktp as ktp_pasien', 'pegawai.nama as nama_petugas', 'pegawai.no_ktp as ktp_petugas', 'reg_periksa.status_lanjut', 'satu_sehat_encounter.id_encounter',
                'satu_sehat_observationttvsuhu.id_observation as id_suhu', 'satu_sehat_observationttvtensi.id_observation as id_tensi', 'satu_sehat_observationttvnadi.id_observation as id_nadi',
                'satu_sehat_observationttvrespirasi.id_observation as id_respirasi', 'satu_sehat_observationttvbb.id_observation as id_bb', 'satu_sehat_observationttvtb.id_observation as id_tb',
                'satu_sehat_observationttvspo2.id_observation as id_spo2', 'satu_sehat_observationttvgcs.id_observation as id_gcs', 'satu_sehat_observationttvlp.id_observation as id_lp'
            ])
            ->whereBetween('p.tgl_perawatan', [$startDate, $endDate]);

        $ranap = DB::table('pemeriksaan_ranap as p')
            ->join('reg_periksa', 'p.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('pegawai', 'p.nip', '=', 'pegawai.nik')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->leftJoin('satu_sehat_observationttvsuhu', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvsuhu.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvsuhu.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvsuhu.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvtensi', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvtensi.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvtensi.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvtensi.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvnadi', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvnadi.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvnadi.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvnadi.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvrespirasi', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvrespirasi.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvrespirasi.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvrespirasi.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvbb', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvbb.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvbb.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvbb.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvtb', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvtb.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvtb.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvtb.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvspo2', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvspo2.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvspo2.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvspo2.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvgcs', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvgcs.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvgcs.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvgcs.jam_rawat');
            })
            ->leftJoin('satu_sehat_observationttvlp', function ($join) {
                $join->on('p.no_rawat', '=', 'satu_sehat_observationttvlp.no_rawat')
                    ->on('p.tgl_perawatan', '=', 'satu_sehat_observationttvlp.tgl_perawatan')
                    ->on('p.jam_rawat', '=', 'satu_sehat_observationttvlp.jam_rawat');
            })
            ->select([
                'p.no_rawat', 'p.tgl_perawatan', 'p.jam_rawat', 'p.suhu_tubuh', 'p.tensi', 'p.nadi', 'p.respirasi', 'p.tinggi', 'p.berat', 'p.spo2', 'p.gcs', 'p.kesadaran', DB::raw("'' as lingkar_perut"), 'p.nip',
                'pasien.nm_pasien', 'pasien.no_ktp as ktp_pasien', 'pegawai.nama as nama_petugas', 'pegawai.no_ktp as ktp_petugas', 'reg_periksa.status_lanjut', 'satu_sehat_encounter.id_encounter',
                'satu_sehat_observationttvsuhu.id_observation as id_suhu', 'satu_sehat_observationttvtensi.id_observation as id_tensi', 'satu_sehat_observationttvnadi.id_observation as id_nadi',
                'satu_sehat_observationttvrespirasi.id_observation as id_respirasi', 'satu_sehat_observationttvbb.id_observation as id_bb', 'satu_sehat_observationttvtb.id_observation as id_tb',
                'satu_sehat_observationttvspo2.id_observation as id_spo2', 'satu_sehat_observationttvgcs.id_observation as id_gcs', 'satu_sehat_observationttvlp.id_observation as id_lp'
            ])
            ->whereBetween('p.tgl_perawatan', [$startDate, $endDate]);

        return $ralan->union($ranap)->orderBy('tgl_perawatan', 'desc')->orderBy('jam_rawat', 'desc')->get();
    }

    /**
     * Sync Observation TTV (Batch All Parameters)
     */
    public function syncObservationTTV($no_rawat, $tgl_perawatan, $jam_rawat)
    {
        // 1. Get Data from Ralan or Ranap
        $data = DB::table('pemeriksaan_ralan')
            ->join('reg_periksa', 'pemeriksaan_ralan.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('pegawai', 'pemeriksaan_ralan.nip', '=', 'pegawai.nik')
            ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
            ->where('pemeriksaan_ralan.no_rawat', $no_rawat)
            ->where('pemeriksaan_ralan.tgl_perawatan', $tgl_perawatan)
            ->where('pemeriksaan_ralan.jam_rawat', $jam_rawat)
            ->select('pemeriksaan_ralan.*', 'pasien.no_ktp as ktp_pasien', 'pasien.nm_pasien', 'pegawai.no_ktp as ktp_petugas', 'pegawai.nama as nama_petugas', 'satu_sehat_encounter.id_encounter')
            ->first();

        if (!$data) {
            $data = DB::table('pemeriksaan_ranap')
                ->join('reg_periksa', 'pemeriksaan_ranap.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('pegawai', 'pemeriksaan_ranap.nip', '=', 'pegawai.nik')
                ->leftJoin('satu_sehat_encounter', 'reg_periksa.no_rawat', '=', 'satu_sehat_encounter.no_rawat')
                ->where('pemeriksaan_ranap.no_rawat', $no_rawat)
                ->where('pemeriksaan_ranap.tgl_perawatan', $tgl_perawatan)
                ->where('pemeriksaan_ranap.jam_rawat', $jam_rawat)
                ->select('pemeriksaan_ranap.*', 'pasien.no_ktp as ktp_pasien', 'pasien.nm_pasien', 'pegawai.no_ktp as ktp_petugas', 'pegawai.nama as nama_petugas', 'satu_sehat_encounter.id_encounter')
                ->first();
        }

        if (!$data) return ['status' => false, 'message' => 'Data pemeriksaan tidak ditemukan'];
        if (!$data->id_encounter) return ['status' => false, 'message' => 'Encounter belum disinkronkan'];

        // Get Patient & Practitioner ID
        $patientRes = $this->getPatient($data->ktp_pasien);
        if (!$patientRes['status'] || empty($patientRes['data']['entry'])) return ['status' => false, 'message' => 'ID IHS Pasien tidak ditemukan'];
        $patientIhsId = $patientRes['data']['entry'][0]['resource']['id'];

        $practitionerRes = $this->get('Practitioner', ['identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $data->ktp_petugas]);
        if (!$practitionerRes['status'] || empty($practitionerRes['data']['entry'])) return ['status' => false, 'message' => 'ID IHS Petugas tidak ditemukan'];
        $practitionerIhsId = $practitionerRes['data']['entry'][0]['resource']['id'];

        $results = [];

        // Definition of Observations
        $definitions = [
            'suhu' => [
                'field' => 'suhu_tubuh',
                'loinc' => '8310-5',
                'display' => 'Body temperature',
                'unit' => 'degree Celsius',
                'code' => 'Cel',
                'table' => 'satu_sehat_observationttvsuhu'
            ],
            'nadi' => [
                'field' => 'nadi',
                'loinc' => '8867-4',
                'display' => 'Heart rate',
                'unit' => 'beats/minute',
                'code' => '/min',
                'table' => 'satu_sehat_observationttvnadi'
            ],
            'respirasi' => [
                'field' => 'respirasi',
                'loinc' => '9279-1',
                'display' => 'Respiratory rate',
                'unit' => 'breaths/minute',
                'code' => '/min',
                'table' => 'satu_sehat_observationttvrespirasi'
            ],
            'tb' => [
                'field' => 'tinggi',
                'loinc' => '8302-2',
                'display' => 'Body height',
                'unit' => 'cm',
                'code' => 'cm',
                'table' => 'satu_sehat_observationttvtb'
            ],
            'bb' => [
                'field' => 'berat',
                'loinc' => '29463-7',
                'display' => 'Body weight',
                'unit' => 'kg',
                'code' => 'kg',
                'table' => 'satu_sehat_observationttvbb'
            ],
            'spo2' => [
                'field' => 'spo2',
                'loinc' => '2708-6',
                'display' => 'Oxygen saturation in Arterial blood',
                'unit' => '%',
                'code' => '%',
                'table' => 'satu_sehat_observationttvspo2'
            ],
            'gcs' => [
                'field' => 'gcs',
                'loinc' => '28334-1',
                'display' => 'Glasgow coma scale score',
                'unit' => 'score',
                'code' => '{score}',
                'table' => 'satu_sehat_observationttvgcs'
            ],
            'lp' => [
                'field' => 'lingkar_perut',
                'loinc' => '56033-4',
                'display' => 'Abdominal circumference',
                'unit' => 'cm',
                'code' => 'cm',
                'table' => 'satu_sehat_observationttvlp'
            ]
        ];

        foreach ($definitions as $key => $def) {
            $val = $data->{$def['field']};
            if ($val && $val != '-' && $val != '0') {
                $payload = $this->buildObservationPayload($patientIhsId, $practitionerIhsId, $data->id_encounter, $data->nm_pasien, $data->tgl_perawatan, $data->jam_rawat, $def, $val);
                
                // Check if already synced
                $existing = DB::table($def['table'])
                    ->where('no_rawat', $no_rawat)
                    ->where('tgl_perawatan', $tgl_perawatan)
                    ->where('jam_rawat', $jam_rawat)
                    ->first();

                if ($existing) {
                    $payload['id'] = $existing->id_observation;
                    $res = $this->put('Observation/' . $existing->id_observation, $payload);
                } else {
                    $res = $this->post('Observation', $payload);
                }

                if ($res['status']) {
                    DB::table($def['table'])->updateOrInsert(
                        ['no_rawat' => $no_rawat, 'tgl_perawatan' => $tgl_perawatan, 'jam_rawat' => $jam_rawat],
                        ['id_observation' => $res['data']['id']]
                    );
                    $results[$key] = true;
                } else {
                    $results[$key] = $res['message'];
                }
            }
        }

        // Special Case: Tensi (Systolic/Diastolic)
        if ($data->tensi && $data->tensi != '-' && strpos($data->tensi, '/') !== false) {
            $tensiArr = explode('/', $data->tensi);
            $systolic = trim($tensiArr[0]);
            $diastolic = trim($tensiArr[1]);

            $payload = [
                'resourceType' => 'Observation',
                'status' => 'final',
                'category' => [['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'vital-signs', 'display' => 'Vital Signs']]]],
                'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '35094-2', 'display' => 'Blood pressure panel']], 'text' => 'Blood pressure systolic & diastolic'],
                'subject' => ['reference' => 'Patient/' . $patientIhsId],
                'performer' => [['reference' => 'Practitioner/' . $practitionerIhsId]],
                'encounter' => ['reference' => 'Encounter/' . $data->id_encounter],
                'effectiveDateTime' => $data->tgl_perawatan . 'T' . $data->jam_rawat . '+07:00',
                'issued' => $data->tgl_perawatan . 'T' . $data->jam_rawat . '+07:00',
                'component' => [
                    [
                        'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6', 'display' => 'Systolic blood pressure']]],
                        'valueQuantity' => ['value' => (float)$systolic, 'unit' => 'mm[Hg]', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]']
                    ],
                    [
                        'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8462-4', 'display' => 'Diastolic blood pressure']]],
                        'valueQuantity' => ['value' => (float)$diastolic, 'unit' => 'mm[Hg]', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]']
                    ]
                ]
            ];

            $existing = DB::table('satu_sehat_observationttvtensi')
                ->where('no_rawat', $no_rawat)
                ->where('tgl_perawatan', $tgl_perawatan)
                ->where('jam_rawat', $jam_rawat)
                ->first();

            if ($existing) {
                $payload['id'] = $existing->id_observation;
                $res = $this->put('Observation/' . $existing->id_observation, $payload);
            } else {
                $res = $this->post('Observation', $payload);
            }

            if ($res['status']) {
                DB::table('satu_sehat_observationttvtensi')->updateOrInsert(
                    ['no_rawat' => $no_rawat, 'tgl_perawatan' => $tgl_perawatan, 'jam_rawat' => $jam_rawat],
                    ['id_observation' => $res['data']['id']]
                );
                $results['tensi'] = true;
            } else {
                $results['tensi'] = $res['message'];
            }
        }

        return ['status' => true, 'message' => 'Proses sinkronisasi selesai', 'results' => $results];
    }

    private function buildObservationPayload($patientId, $practitionerId, $encounterId, $patientName, $tgl, $jam, $def, $value)
    {
        return [
            'resourceType' => 'Observation',
            'status' => 'final',
            'category' => [['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'vital-signs', 'display' => 'Vital Signs']]]],
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => $def['loinc'], 'display' => $def['display']]]],
            'subject' => ['reference' => 'Patient/' . $patientId],
            'performer' => [['reference' => 'Practitioner/' . $practitionerId]],
            'encounter' => [
                'reference' => 'Encounter/' . $encounterId,
                'display' => 'Pemeriksaan ' . $def['display'] . ' Pasien ' . $patientName . ' pada ' . $tgl . ' ' . $jam
            ],
            'effectiveDateTime' => $tgl . 'T' . $jam . '+07:00',
            'valueQuantity' => [
                'value' => (float)str_replace(',', '.', $value),
                'unit' => $def['unit'],
                'system' => 'http://unitsofmeasure.org',
                'code' => $def['code']
            ]
        ];
    }
}

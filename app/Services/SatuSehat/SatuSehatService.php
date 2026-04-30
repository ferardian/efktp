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
                'reference' => 'Patient/' . $patientIhsId,
                'display'   => $data->nm_pasien
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
                        'reference' => 'Practitioner/' . $practitionerIhsId,
                        'display'   => $data->nama_dokter
                    ]
                ]
            ],
            'period' => [
                'start' => $data->tgl_registrasi . 'T' . $data->jam_reg . '+07:00'
            ],
            'location' => [
                [
                    'location' => [
                        'reference' => 'Location/' . $data->id_lokasi_satusehat,
                        'display'   => $data->nm_poli
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
            $result = $this->put('Encounter/' . $data->id_encounter, $payload);
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
}

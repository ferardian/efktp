<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\SatuSehat\SatuSehatService;

class SatuSehat extends Controller
{
    protected SatuSehatService $service;

    public function __construct()
    {
        $this->service = new SatuSehatService();
    }

    /**
     * Show mapping page
     */
    public function index()
    {
        return view('content.satusehat.mapping_organisasi');
    }

    /**
     * Get mapping data (JSON)
     */
    public function getData(): JsonResponse
    {
        $data = $this->service->getMappingStatus();
        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * Show patient search page
     */
    public function patientIndex()
    {
        return view('content.satusehat.get_pasien');
    }

    /**
     * Get patient data (JSON)
     */
    public function getPatient(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => 'required'
        ]);

        $result = $this->service->getPatient($request->nik);
        return response()->json($result);
    }

    /**
     * Map organization
     */
    public function map(Request $request): JsonResponse
    {
        $request->validate([
            'dep_id' => 'required'
        ]);

        $result = $this->service->mapOrganization($request->dep_id);
        return response()->json($result);
    }

    /**
     * Show location mapping page
     */
    public function locationIndex()
    {
        return view('content.satusehat.mapping_lokasi');
    }

    /**
     * Get location data (JSON)
     */
    public function getLocationData(Request $request): JsonResponse
    {
        $type = $request->get('type', 'ralan');
        $data = $this->service->getLocationMappingStatus($type);
        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * Map location
     */
    public function mapLocation(Request $request): JsonResponse
    {
        $request->validate([
            'type'            => 'required',
            'id'              => 'required',
            'longitude'       => 'required',
            'latitude'        => 'required',
            'altitude'        => 'required',
            'organization_id' => 'required'
        ]);

        $result = $this->service->mapLocation(
            $request->type,
            $request->id,
            $request->only(['longitude', 'latitude', 'altitude', 'organization_id'])
        );

        return response()->json($result);
    }

    /**
     * Get mapped organizations list for lookup
     */
    public function getOrganizations(): JsonResponse
    {
        $data = DB::table('satu_sehat_mapping_departemen')
            ->join('departemen', 'satu_sehat_mapping_departemen.dep_id', '=', 'departemen.dep_id')
            ->select('satu_sehat_mapping_departemen.id_organisasi_satusehat as id', 'departemen.nama')
            ->whereNotNull('satu_sehat_mapping_departemen.id_organisasi_satusehat')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * Show encounter sync page
     */
    public function encounterIndex()
    {
        return view('content.satusehat.encounter');
    }

    /**
     * Get encounter data (JSON)
     */
    public function getEncounterData(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $data = $this->service->getEncounterList($search, $startDate, $endDate);
        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * Sync encounter
     */
    public function syncEncounter(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat' => 'required'
        ]);

        $result = $this->service->syncEncounter($request->no_rawat);
        return response()->json($result);
    }

    /**
     * Show condition sync page
     */
    public function conditionIndex()
    {
        return view('content.satusehat.condition');
    }

    /**
     * Get condition data (JSON)
     */
    public function getConditionData(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));

        $data = $this->service->getConditionList($search, $startDate, $endDate);
        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    /**
     * Sync condition
     */
    public function syncCondition(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat'    => 'required',
            'kd_penyakit' => 'required',
            'status'      => 'required'
        ]);

        $result = $this->service->syncCondition(
            $request->no_rawat,
            $request->kd_penyakit,
            $request->status
        );
        return response()->json($result);
    }

    public function observationTTVIndex()
    {
        return view('content.satusehat.observation-ttv');
    }

    public function getObservationTTVData(Request $request)
    {
        $data = $this->service->getObservationTTVList($request->tgl_awal, $request->tgl_akhir);
        return response()->json($data);
    }

    public function syncObservationTTV(Request $request)
    {
        $res = $this->service->syncObservationTTV($request->no_rawat, $request->tgl_perawatan, $request->jam_rawat);
        return response()->json($res);
    }

    public function autoSync(): JsonResponse
    {
        $synced = [];
        $errors = [];

        // Sync Encounters (Today only for performance)
        $today = date('Y-m-d');
        $encounters = $this->service->getEncounterList(null, $today, $today);
        $pendingEncounters = array_filter($encounters, fn($e) => empty($e->id_encounter));
        foreach (array_slice($pendingEncounters, 0, 2) as $e) {
            $res = $this->service->syncEncounter($e->no_rawat);
            if ($res['status']) $synced[] = "Encounter: {$e->no_rawat}";
            else $errors[] = "Encounter {$e->no_rawat}: " . $res['message'];
        }

        // Sync Conditions (if encounter is already synced)
        $conditions = $this->service->getConditionList(null, $today, $today);
        $pendingConditions = array_filter($conditions, fn($c) => empty($c->id_condition) && !empty($c->id_encounter));
        foreach (array_slice($pendingConditions, 0, 2) as $c) {
            $res = $this->service->syncCondition($c->no_rawat, $c->kd_penyakit, $c->status_diagnosa);
            if ($res['status']) $synced[] = "Condition: {$c->no_rawat} ({$c->kd_penyakit})";
            else $errors[] = "Condition {$c->no_rawat}: " . $res['message'];
        }

        // Sync Observations TTV (if encounter is already synced)
        $ttvs = $this->service->getObservationTTVList($today, $today);
        $pendingTtvs = array_filter($ttvs, fn($t) => empty($t->id_suhu) && !empty($t->id_encounter));
        foreach (array_slice($pendingTtvs, 0, 2) as $t) {
            $res = $this->service->syncObservationTTV($t->no_rawat, $t->tgl_perawatan, $t->jam_rawat);
            if ($res['status']) $synced[] = "TTV: {$t->no_rawat}";
            else $errors[] = "TTV {$t->no_rawat}: " . $res['message'];
        }

        return response()->json([
            'status' => true,
            'synced' => $synced,
            'errors' => $errors,
            'count'  => count($synced)
        ]);
    }
}

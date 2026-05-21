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
    public function medicationIndex()
    {
        return view('content.satusehat.medication');
    }

    public function getObatLokal(Request $request): JsonResponse
    {
        $keyword = $request->get('keyword', '');
        $statusMap = $request->get('status_map', 'all'); // 'all', 'mapped', 'unmapped', 'not_synced'

        $query = DB::table('databarang as d')
            ->join('jenis as j', 'j.kdjns', '=', 'd.kdjns')
            ->leftJoin('industrifarmasi as ind', 'ind.kode_industri', '=', 'd.kode_industri')
            ->leftJoin('satu_sehat_mapping_obat as smo', 'smo.kode_brng', '=', 'd.kode_brng')
            ->leftJoin('satu_sehat_medication as sm', 'sm.kode_brng', '=', 'd.kode_brng')
            ->where('d.status', '1') // Status Aktif
            ->where('j.nama', '!=', 'ALKES') // Bukan ALKES
            ->select(
                'd.kode_brng', 'd.nama_brng', 'd.kdjns', 'j.nama as nm_jns', 'ind.nama_industri',
                'smo.obat_code', 'smo.obat_display', 'smo.form_code', 'smo.route_code', 'smo.denominator_code',
                'sm.id_medication',
                DB::raw("IF(smo.kode_brng IS NOT NULL, 1, 0) as is_mapped"),
                DB::raw("IF(sm.id_medication IS NOT NULL, 1, 0) as is_synced")
            );

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('d.kode_brng', 'like', "%{$keyword}%")
                  ->orWhere('d.nama_brng', 'like', "%{$keyword}%")
                  ->orWhere('smo.obat_code', 'like', "%{$keyword}%")
                  ->orWhere('smo.obat_display', 'like', "%{$keyword}%");
            });
        }

        if ($statusMap === 'mapped') {
            $query->whereNotNull('smo.kode_brng');
        } elseif ($statusMap === 'unmapped') {
            $query->whereNull('smo.kode_brng');
        } elseif ($statusMap === 'not_synced') {
            $query->whereNotNull('smo.kode_brng')->whereNull('sm.id_medication');
        }

        $data = $query->orderBy('d.nama_brng', 'asc')->paginate($request->get('limit', 20));

        return response()->json([
            'success' => true,
            'message' => 'Data obat lokal berhasil diambil',
            'data'    => $data
        ]);
    }

    public function searchKfa(Request $request): JsonResponse
    {
        $params = $request->only([
            'page', 'size', 'product_type', 'from_date', 'to_date', 
            'farmalkes_type', 'keyword', 'template_code', 'packaging_code'
        ]);
        
        if (!isset($params['page'])) $params['page'] = 1;
        if (!isset($params['size'])) $params['size'] = 100;
        if (!isset($params['product_type'])) $params['product_type'] = 'farmasi';

        $kfaData = $this->service->getKfaProducts($params);

        if (!$kfaData || !$kfaData['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari server KFA SatuSehat',
                'error'   => $kfaData['message'] ?? 'Unknown error'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data KFA berhasil diambil',
            'data'    => $kfaData['data']
        ]);
    }

    public function saveMappingObat(Request $request): JsonResponse
    {
        $request->validate([
            'kode_brng' => 'required|string',
            'obat_code' => 'required|string',
            'obat_system' => 'required|string',
            'obat_display' => 'required|string',
            'form_code' => 'nullable|string',
            'form_system' => 'nullable|string',
            'form_display' => 'nullable|string',
            'route_code' => 'nullable|string',
            'route_system' => 'nullable|string',
            'route_display' => 'nullable|string',
            'denominator_code' => 'nullable|string',
            'denominator_system' => 'nullable|string',
            'numerator_code' => 'nullable|string',
            'numerator_system' => 'nullable|string'
        ]);

        $data = $request->except(['_token']);

        try {
            DB::table('satu_sehat_mapping_obat')->updateOrInsert(
                ['kode_brng' => $data['kode_brng']],
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Mapping Obat KFA berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMappingObat(Request $request, $kode_brng): JsonResponse
    {
        try {
            DB::table('satu_sehat_mapping_obat')->where('kode_brng', $kode_brng)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Mapping Obat KFA berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mapping: ' . $e->getMessage()
            ], 500);
        }
    }
}

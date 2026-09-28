<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Lab\TemplateLaboratorium;
use App\Models\SatuSehatMappingLab;
use App\Services\SatuSehat\SatuSehatServiceRequestLabService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SatuSehatServiceRequestLabController extends Controller
{
    protected SatuSehatServiceRequestLabService $service;

    public function __construct(SatuSehatServiceRequestLabService $service)
    {
        $this->service = $service;
    }

    /**
     * View monitoring and send ServiceRequest Lab
     */
    public function index()
    {
        return view('content.satusehat.servicerequest_lab');
    }

    /**
     * Get list of lab ServiceRequests (JSON)
     */
    public function getData(Request $request): JsonResponse
    {
        $params = $request->only(['tgl_awal', 'tgl_akhir', 'search', 'status']);
        try {
            $data = $this->service->getListServiceRequestLab($params);
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data permintaan lab berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data permintaan lab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send single lab ServiceRequest to SatuSehat
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat'       => 'required',
            'noorder'        => 'required',
            'id_template'    => 'required',
            'kd_jenis_prw'   => 'required',
            'no_ktp'         => 'required',
            'ktp_dokter'     => 'required',
            'id_encounter'   => 'required',
            'code'           => 'required',
            'Pemeriksaan'    => 'required',
            'tgl_permintaan' => 'required',
            'jam_permintaan' => 'required',
            'nm_pasien'      => 'required',
            'no_rkm_medis'   => 'required',
        ]);

        $res = $this->service->send($request->all());

        return response()->json($res, $res['success'] ? 200 : 400);
    }

    /**
     * Batch send lab ServiceRequests to SatuSehat
     */
    public function syncBatch(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date',
            'keys'      => 'nullable|array'
        ]);

        try {
            $res = $this->service->sendBatch(
                $request->tgl_awal,
                $request->tgl_akhir,
                $request->keys ?? []
            );

            return response()->json([
                'success' => true,
                'message' => "Proses sinkronisasi selesai. Berhasil: {$res['success']}, Gagal: {$res['error']} dari {$res['total']} data.",
                'data'    => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi batch: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================================================================
    // MAPPING LAB (LOINC & SNOMED CT)
    // =========================================================================

    /**
     * View Lab Mapping (LOINC & SNOMED CT)
     */
    public function mappingIndex()
    {
        return view('content.satusehat.mapping_lab');
    }

    /**
     * Get Lab Mapping Data (JSON)
     */
    public function getMappingData(Request $request): JsonResponse
    {
        try {
            $query = TemplateLaboratorium::with(['jenisPerawatan:kd_jenis_prw,nm_perawatan', 'satuSehatMappingLab']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('Pemeriksaan', 'LIKE', "%{$search}%")
                      ->orWhere('id_template', 'LIKE', "%{$search}%")
                      ->orWhereHas('jenisPerawatan', function ($sub) use ($search) {
                          $sub->where('nm_perawatan', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('satuSehatMappingLab', function ($sub) use ($search) {
                          $sub->where('code', 'LIKE', "%{$search}%")
                              ->orWhere('display', 'LIKE', "%{$search}%");
                      });
                });
            }

            if ($request->filled('status')) {
                if ($request->status === 'mapped') {
                    $query->has('satuSehatMappingLab');
                } elseif ($request->status === 'unmapped') {
                    $query->doesntHave('satuSehatMappingLab');
                }
            }

            if ($request->filled('kd_jenis_prw')) {
                $query->where('kd_jenis_prw', $request->kd_jenis_prw);
            }

            $data = $query->orderBy('kd_jenis_prw')->orderBy('urut')->get();

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            Log::error("SatuSehatServiceRequestLabController getMappingData error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Lab Mapping
     */
    public function saveMapping(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_template'    => 'required|exists:template_laboratorium,id_template',
            'code'           => 'required|string',
            'system'         => 'required|string',
            'display'        => 'required|string',
            'sampel_code'    => 'required|string',
            'sampel_system'  => 'required|string',
            'sampel_display' => 'required|string',
        ]);

        try {
            $mapping = SatuSehatMappingLab::updateOrCreate(
                ['id_template' => $validated['id_template']],
                $validated
            );

            return response()->json([
                'success' => true,
                'message' => 'Mapping parameter lab ke SatuSehat berhasil disimpan',
                'data'    => $mapping
            ]);
        } catch (\Exception $e) {
            Log::error("SatuSehatServiceRequestLabController saveMapping error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan mapping: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Lab Mapping
     */
    public function deleteMapping($id_template): JsonResponse
    {
        try {
            $deleted = SatuSehatMappingLab::where('id_template', $id_template)->delete();
            return response()->json([
                'success' => true,
                'message' => $deleted ? 'Mapping lab berhasil dihapus' : 'Data mapping tidak ditemukan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mapping: ' . $e->getMessage()
            ], 500);
        }
    }
}

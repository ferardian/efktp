<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Services\SatuSehat\SatuSehatAllergyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SatuSehatAllergyController extends Controller
{
    protected SatuSehatAllergyService $service;

    public function __construct(SatuSehatAllergyService $service)
    {
        $this->service = $service;
    }

    /**
     * View Allergy monitoring & mapping
     */
    public function allergyIndex()
    {
        return view('content.satusehat.allergy');
    }

    /**
     * Get list of Allergy notes (JSON)
     */
    public function getAllergyData(Request $request): JsonResponse
    {
        $params = $request->only(['tgl_awal', 'tgl_akhir', 'search', 'status', 'show_all']);
        try {
            $data = $this->service->getAllergyList($params);
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data Alergi berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::channel('satusehat')->error('Gagal mengambil data Alergi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync single AllergyIntolerance
     */
    public function syncSingle(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat'      => 'required',
            'tgl_perawatan' => 'required',
            'jam_rawat'     => 'required',
            'alergi_text'   => 'required',
        ]);

        $res = $this->service->syncSingleAllergy(
            $request->no_rawat,
            $request->tgl_perawatan,
            $request->jam_rawat,
            $request->alergi_text
        );

        return response()->json($res, $res['success'] ? 200 : 400);
    }

    /**
     * Batch sync AllergyIntolerance
     */
    public function syncBatch(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date',
            'keys'      => 'nullable|array'
        ]);

        try {
            $res = $this->service->syncBatchAllergy(
                $request->tgl_awal,
                $request->tgl_akhir,
                $request->keys ?? []
            );

            return response()->json([
                'success' => true,
                'message' => "Proses sinkronisasi Alergi selesai. Berhasil: {$res['success']}, Gagal: {$res['error']}, Dilewati: {$res['skip']}.",
                'data'    => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of Allergy Mappings
     */
    public function getMappings(Request $request): JsonResponse
    {
        $search   = $request->query('search', '');
        $category = $request->query('category', 'all');

        $query = DB::table('satu_sehat_mapping_alergi');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('keyword', 'LIKE', "%{$search}%")
                    ->orWhere('display', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $mappings = $query->orderBy('category')->orderBy('keyword')->get();

        return response()->json([
            'success' => true,
            'data'    => $mappings
        ]);
    }

    /**
     * Save / Update Allergy Mapping
     */
    public function saveMapping(Request $request): JsonResponse
    {
        $request->validate([
            'keyword'  => 'required|string|max:100',
            'category' => 'required|in:food,medication,environment,biologic',
            'code'     => 'required|string|max:50',
            'system'   => 'required|string|max:150',
            'display'  => 'required|string|max:200',
        ]);

        $keyword = strtolower(trim($request->keyword));

        DB::table('satu_sehat_mapping_alergi')->updateOrInsert(
            ['keyword' => $keyword],
            [
                'category'   => $request->category,
                'code'       => trim($request->code),
                'system'     => trim($request->system),
                'display'    => trim($request->display),
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Mapping terminologi alergi berhasil disimpan'
        ]);
    }

    /**
     * Delete Allergy Mapping
     */
    public function deleteMapping($id): JsonResponse
    {
        DB::table('satu_sehat_mapping_alergi')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mapping terminologi alergi berhasil dihapus'
        ]);
    }
}


<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Services\SatuSehat\SatuSehatImmunizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SatuSehatImmunizationController extends Controller
{
    protected SatuSehatImmunizationService $service;

    public function __construct(SatuSehatImmunizationService $service)
    {
        $this->service = $service;
    }

    /**
     * View Immunization monitoring
     */
    public function immunizationIndex()
    {
        return view('content.satusehat.immunization');
    }

    /**
     * Get list of Immunization (JSON)
     */
    public function getImmunizationData(Request $request): JsonResponse
    {
        $params = $request->only(['tgl_awal', 'tgl_akhir', 'search', 'status']);
        try {
            $data = $this->service->getImmunizationList($params);
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data Imunisasi berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::channel('satusehat')->error('Gagal mengambil data Imunisasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync single Immunization
     */
    public function syncImmunization(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat'      => 'required',
            'tgl_perawatan' => 'required',
            'jam'           => 'required',
            'kode_brng'     => 'required',
        ]);

        $customOverrides = [];
        if ($request->filled('lot_number')) {
            $customOverrides['lot_number'] = $request->lot_number;
        }
        if ($request->filled('dose_number')) {
            $customOverrides['dose_number'] = $request->dose_number;
        }
        if ($request->filled('reason_code')) {
            $customOverrides['reason_code'] = $request->reason_code;
        }
        if ($request->filled('reason_display')) {
            $customOverrides['reason_display'] = $request->reason_display;
        }

        $res = $this->service->syncImmunization(
            $request->no_rawat,
            $request->tgl_perawatan,
            $request->jam,
            $request->kode_brng,
            $request->no_batch ?? '',
            $request->no_faktur ?? '',
            $customOverrides
        );

        return response()->json($res, $res['success'] ? 200 : 400);
    }

    /**
     * Batch sync Immunization
     */
    public function syncBatchImmunization(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date',
            'keys'      => 'nullable|array'
        ]);

        try {
            $res = $this->service->syncBatchImmunization(
                $request->tgl_awal,
                $request->tgl_akhir,
                $request->keys ?? []
            );

            return response()->json([
                'success' => true,
                'message' => "Proses sinkronisasi Imunisasi selesai. Berhasil: {$res['success']}, Gagal: {$res['error']}, Dilewati: {$res['skip']}.",
                'data'    => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi batch: ' . $e->getMessage()
            ], 500);
        }
    }
}


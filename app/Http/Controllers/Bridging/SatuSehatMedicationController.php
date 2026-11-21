<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Services\SatuSehat\SatuSehatMedicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SatuSehatMedicationController extends Controller
{
    protected SatuSehatMedicationService $service;

    public function __construct(SatuSehatMedicationService $service)
    {
        $this->service = $service;
    }

    // =========================================================================
    // MEDICATION REQUEST (RESEP OBAT)
    // =========================================================================

    /**
     * View MedicationRequest monitoring
     */
    public function medicationRequestIndex()
    {
        return view('content.satusehat.medication-request');
    }

    /**
     * Get list of MedicationRequest (JSON)
     */
    public function getMedicationRequestData(Request $request): JsonResponse
    {
        $params = $request->only(['tgl_awal', 'tgl_akhir', 'search', 'status']);
        try {
            $data = $this->service->getMedicationRequestList($params);
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data MedicationRequest berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::channel('satusehat')->error('Gagal mengambil data MedicationRequest: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync single MedicationRequest
     */
    public function syncMedicationRequest(Request $request): JsonResponse
    {
        $request->validate([
            'no_resep'  => 'required',
            'kode_brng' => 'required',
        ]);

        $res = $this->service->syncMedicationRequest(
            $request->no_resep,
            $request->kode_brng,
            $request->no_racik ?? null
        );

        return response()->json($res, $res['success'] ? 200 : 400);
    }

    /**
     * Batch sync MedicationRequest
     */
    public function syncBatchMedicationRequest(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date',
            'keys'      => 'nullable|array'
        ]);

        try {
            $res = $this->service->sendBatchMedicationRequest(
                $request->tgl_awal,
                $request->tgl_akhir,
                $request->keys ?? []
            );

            return response()->json([
                'success' => true,
                'message' => "Proses sinkronisasi MedicationRequest selesai. Berhasil: {$res['success']}, Gagal: {$res['error']}, Dilewati: {$res['skip']}.",
                'data'    => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sinkronisasi batch: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================================================================
    // MEDICATION DISPENSE (PENYERAHAN OBAT)
    // =========================================================================

    /**
     * View MedicationDispense monitoring
     */
    public function medicationDispenseIndex()
    {
        return view('content.satusehat.medication-dispense');
    }

    /**
     * Get list of MedicationDispense (JSON)
     */
    public function getMedicationDispenseData(Request $request): JsonResponse
    {
        $params = $request->only(['tgl_awal', 'tgl_akhir', 'search', 'status']);
        try {
            $data = $this->service->getMedicationDispenseList($params);
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data MedicationDispense berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::channel('satusehat')->error('Gagal mengambil data MedicationDispense: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync single MedicationDispense
     */
    public function syncMedicationDispense(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat'      => 'required',
            'tgl_perawatan' => 'required',
            'jam'           => 'required',
            'kode_brng'     => 'required',
        ]);

        $res = $this->service->syncMedicationDispense(
            $request->no_rawat,
            $request->tgl_perawatan,
            $request->jam,
            $request->kode_brng,
            (string)($request->no_batch ?? ''),
            (string)($request->no_faktur ?? '')
        );

        return response()->json($res, $res['success'] ? 200 : 400);
    }

    /**
     * Batch sync MedicationDispense
     */
    public function syncBatchMedicationDispense(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date',
            'keys'      => 'nullable|array'
        ]);

        try {
            $res = $this->service->sendBatchMedicationDispense(
                $request->tgl_awal,
                $request->tgl_akhir,
                $request->keys ?? []
            );

            return response()->json([
                'success' => true,
                'message' => "Proses sinkronisasi MedicationDispense selesai. Berhasil: {$res['success']}, Gagal: {$res['error']}, Dilewati: {$res['skip']}.",
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

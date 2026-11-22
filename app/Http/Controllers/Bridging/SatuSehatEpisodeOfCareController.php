<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Services\SatuSehat\SatuSehatEpisodeOfCareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SatuSehatEpisodeOfCareController extends Controller
{
    protected SatuSehatEpisodeOfCareService $service;

    public function __construct(SatuSehatEpisodeOfCareService $service)
    {
        $this->service = $service;
    }

    /**
     * View EpisodeOfCare monitoring
     */
    public function episodeIndex()
    {
        return view('content.satusehat.episode-of-care');
    }

    /**
     * Get list of EpisodeOfCare records (JSON)
     */
    public function getEpisodeData(Request $request): JsonResponse
    {
        $params = $request->only(['tgl_awal', 'tgl_akhir', 'search', 'tipe', 'status']);
        try {
            $data = $this->service->getEpisodeOfCareList($params);
            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data EpisodeOfCare berhasil diambil'
            ]);
        } catch (\Exception $e) {
            Log::channel('satusehat')->error('Gagal mengambil data EpisodeOfCare: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync single EpisodeOfCare
     */
    public function syncSingle(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat'    => 'required',
            'kd_penyakit' => 'required',
        ]);

        $res = $this->service->syncSingleEpisode(
            $request->no_rawat,
            $request->kd_penyakit
        );

        return response()->json($res, $res['success'] ? 200 : 400);
    }

    /**
     * Batch sync EpisodeOfCare
     */
    public function syncBatch(Request $request): JsonResponse
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date',
            'keys'      => 'nullable|array'
        ]);

        try {
            $res = $this->service->syncBatchEpisode(
                $request->tgl_awal,
                $request->tgl_akhir,
                $request->keys ?? []
            );

            return response()->json([
                'success' => true,
                'message' => "Proses sinkronisasi EpisodeOfCare selesai. Berhasil: {$res['success']}, Gagal: {$res['error']}, Dilewati: {$res['skip']}.",
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

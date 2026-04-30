<?php

namespace App\Http\Controllers\Bridging;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Bpjs\Antrian\AntrianService;

class Antrian extends Controller
{
    protected AntrianService $antrian;

    public function __construct()
    {
        $this->antrian = new AntrianService();
    }

    /**
     * Tambah antrian pasien ke Mobile JKN FKTP
     */
    public function add(Request $request): JsonResponse
    {
        $result = $this->antrian->add($request->all());
        return response()->json($result);
    }

    /**
     * Update status antrian (1 = mulai dilayani, 2 = batal)
     */
    public function panggil(Request $request): JsonResponse
    {
        $result = $this->antrian->panggil($request->all());
        return response()->json($result);
    }

    /**
     * Batalkan antrian pasien
     */
    public function batal(Request $request): JsonResponse
    {
        $result = $this->antrian->batal($request->all());
        return response()->json($result);
    }
}

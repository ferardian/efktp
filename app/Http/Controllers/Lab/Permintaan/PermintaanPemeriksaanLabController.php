<?php

namespace App\Http\Controllers\Lab\Permintaan;

use App\Http\Controllers\Controller;
use App\Models\Lab\Permintaan\PermintaanPemeriksaanLab;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class PermintaanPemeriksaanLabController extends Controller
{
    use Track;
    private $pemeriksaan;

    public function __construct()
    {
        $this->pemeriksaan = new PermintaanPemeriksaanLab();
    }

    function create(Request $request): JsonResponse
    {
        foreach ($request->data as $item => $value) {
            try {
                $pemeriksaan = $this->pemeriksaan->create($value);
                if ($pemeriksaan) {
                    $this->insertSql($this->pemeriksaan, $value);
                }
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
        return response()->json('Berhasil');
    }
}

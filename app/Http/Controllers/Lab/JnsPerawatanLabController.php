<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\JnsPerawatanLab;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JnsPerawatanLabController extends Controller
{
    protected $jenis;
    public function __construct()
    {
        $this->jenis = new JnsPerawatanLab();
    }

    function get(Request $request): JsonResponse
    {
        $jenis = $this->jenis;
        $data = $jenis->where('status', '1')
            ->where('nm_perawatan', 'like', "%{$request->nm_perawatan}%")->get();
        return response()->json($data);
    }
    function getTemplate(Request $request): JsonResponse
    {
        $data = $this->jenis->select(['kd_jenis_prw', 'nm_perawatan'])->whereIn('kd_jenis_prw', $request->kode)
            ->with('template', function ($query) use ($request) {
                if ($request->nm_perawatan) {
                    return $query = $query->where('nm_perawatan', 'like', "%{$request->nm_perawatan}%");
                }
                return $query;
            })->get();
        return response()->json($data);
    }
}

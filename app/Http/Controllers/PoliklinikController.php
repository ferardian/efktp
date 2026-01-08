<?php

namespace App\Http\Controllers;

use App\Models\Poliklinik;
use App\Traits\Track;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $poli = Poliklinik::where('status', '1');
        if ($request->poli) {
            $poli = $poli->where('nm_poli', 'like', "%{$request->poli}%");
        }
        $poli = $poli->get();
        return response()->json($poli);
    }
    function getTarifPoliklinik($kd_poli): int
    {
        $poli = Poliklinik::select('registrasi')->where('kd_poli', $kd_poli)->first();
        return $poli->registrasi;
    }
}

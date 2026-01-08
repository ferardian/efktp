<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Traits\Track;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    function get(Request $request)
    {
        if ($request->kd_dokter) {
            $dokter = Dokter::where('kd_dokter', $request->kd_dokter)->first();
        } else if ($request->dokter) {
            $dokter = Dokter::where('nm_dokter', 'like', "%{$request->dokter}%")
                ->where('status', '1')
                ->get();
        } else {
            $dokter = Dokter::where('status', '1')->get();
        }
        return response()->json($dokter);
    }
}

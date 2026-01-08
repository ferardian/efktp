<?php

namespace App\Http\Controllers;

use App\Models\MappingPoliklinikPcare;
use Illuminate\Http\Request;

class MappingPoliklinikPcareController extends Controller
{
    function get(Request $request)
    {
        $mapping = MappingPoliklinikPcare::with('poliklinik');

        if ($request->kdPoliPcare) {
            $mapping = $mapping->where('kd_poli_pcare', $request->kdPoliPcare)->first();
        } else {
            $mapping = $mapping->where('kd_poli_rs', $request->kdPoli)->first();
        }
        return response()->json($mapping);
    }
}

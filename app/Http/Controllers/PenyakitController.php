<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    function get(Request $request)
    {
        $penyakits = new Penyakit();
        if ($request) {
            $penyakit = $penyakits->where('kd_penyakit', 'like', "%{$request->penyakit}%")
                ->orWhere('nm_penyakit', 'like', "%{$request->penyakit}%")
                ->limit(10)
                ->get();
        } else {
            $penyakit = $penyakits->limit(10)->get();
        }
        return response()->json($penyakit);
    }
}

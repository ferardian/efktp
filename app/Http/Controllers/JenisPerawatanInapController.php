<?php

namespace App\Http\Controllers;

use App\Models\JenisPerawatanInap;
use Illuminate\Http\Request;

class JenisPerawatanInapController extends Controller
{
    public function get(Request $request)
    {
        $data = JenisPerawatanInap::where('status', '1');
        
        if ($request->nm_perawatan) {
            $data = $data->where('nm_perawatan', 'like', '%' . $request->nm_perawatan . '%');
        }

        if ($request->pelaksana) {
            if ($request->pelaksana == 'dr') {
                $data = $data->where('total_byrdr', '>', 0);
            } elseif ($request->pelaksana == 'pr') {
                $data = $data->where('total_byrpr', '>', 0);
            } elseif ($request->pelaksana == 'drpr') {
                $data = $data->where('total_byrdrpr', '>', 0);
            }
        }

        return response()->json($data->get());
    }
}

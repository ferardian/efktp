<?php

namespace App\Http\Controllers;

use App\Models\Icd9;
use Illuminate\Http\Request;

class Icd9Controller extends Controller
{
    //
    function get(Request $request)
    {
        if ($request->kode) {
            $prosedur = Icd9::where('kode', 'like', "%{$request->kode}%")->orWhere('deskripsi_panjang', 'like', '%' . $request->kode . '%')->limit(10)->get();
        } else {
            $prosedur = Icd9::limit(10)->get();
        }
        return response()->json($prosedur);
    }
}

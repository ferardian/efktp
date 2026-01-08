<?php

namespace App\Http\Controllers;

use App\Models\Penjab;
use Illuminate\Http\Request;

class PenjabController extends Controller
{
    function get(Request $request)
    {
        $penjab = Penjab::where(['status' => '1']);
        if ($request->penjab) {
            $penjab = $penjab->where('png_jawab', 'like', "{$request->penjab}%")->get();
        } else if ($request->nama) {
            $penjab = $penjab->where('png_jawab', 'like', "{$request->nama}%")->first();
        } else {
            $penjab = $penjab->limit(10)->get();
        }
        return response()->json($penjab);
    }
}

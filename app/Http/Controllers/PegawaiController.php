<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    function get(Request $request): object
    {
        $pegawai = Pegawai::where('nama', 'like', "%{$request->pegawai}%")
            ->with(['departemen', 'dokter'])
            ->limit(10)
            ->get();
        return response()->json($pegawai);
    }
    function getPegawai(Request $request): object
    {
        $pegawai = Pegawai::where('nik', $request->nik)->first();
        return response()->json($pegawai);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SukuBangsa;
use App\Traits\Track;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SukuBangsaController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $suku = SukuBangsa::where('nama_suku_bangsa', 'like', "{$request->suku}%")->get();
        return response()->json($suku);
    }
    function create(Request $request)
    {
        $suku = SukuBangsa::where('nama_suku_bangsa', 'like', "%{$request->suku}%")->orWhere('id', $request->suku)->first();
        if (!$suku) {
            try {
                $data = [
                    'nama_suku_bangsa' => $request->suku,
                ];
                $suku = SukuBangsa::create($data);
                if ($suku) {
                    $this->insertSql(new SukuBangsa(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $suku->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

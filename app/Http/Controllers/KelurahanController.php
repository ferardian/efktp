<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $kelurahan = Kelurahan::where('nm_kel', 'like', "%{$request->kelurahan}%")->get();
        return response()->json($kelurahan);
    }
    function create(Request $request)
    {
        $kelurahan = Kelurahan::where('nm_kel', $request->kelurahan)
            ->orWhere('kd_kel', $request->kelurahan)->first();
        if (!$kelurahan) {
            try {
                $data = [
                    'nm_kel' => strtoupper($request->kelurahan)
                ];
                $create = Kelurahan::create($data);
                if ($create) {
                    $this->insertSql(new Kelurahan(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $create->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

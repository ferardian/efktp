<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KabupatenController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $kabupaten = Kabupaten::where('nm_kab', 'like', "{$request->kabupaten}%")->get();
        return response()->json($kabupaten);
    }
    function create(Request $request)
    {
        $kabupaten = Kabupaten::where('nm_kab', $request->kabupaten)
            ->orWhere('kd_kab', $request->kabupaten)->first();
        if (!$kabupaten) {
            try {
                $data = [
                    'nm_kab' => strtoupper($request->kabupaten)
                ];
                $create = Kabupaten::create($data);
                if ($create) {
                    $this->insertSql(new Kabupaten(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $create->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

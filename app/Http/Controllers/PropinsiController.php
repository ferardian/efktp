<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Propinsi;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PropinsiController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $propinsi = Propinsi::where('nm_prop', 'like', "%{$request->propinsi}%")->get();
        return response()->json($propinsi);
    }
    function create(Request $request)
    {
        $propinsi = Propinsi::where('nm_prop',  $request->propinsi)
            ->orWhere('kd_prop', $request->propinsi)->first();
        if (!$propinsi) {
            try {
                $data = [
                    'nm_prop' => strtoupper($request->propinsi)
                ];
                $create = Propinsi::create($data);
                if ($create) {
                    $this->insertSql(new Propinsi(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $create->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

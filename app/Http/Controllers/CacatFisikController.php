<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\CacatFisik;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CacatFisikController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $cacat = CacatFisik::where('nama_cacat', 'like', "{$request->cacat}%")->get();
        return response()->json($cacat);
    }
    function create(Request $request)
    {
        $cacat = CacatFisik::where('nama_cacat', 'like', "%{$request->cacat}%")->orWhere('id', $request->cacat)->first();
        if (!$cacat) {
            try {
                $data = [
                    'nama_cacat' => $request->cacat,
                ];
                $cacat = CacatFisik::create($data);
                if ($cacat) {
                    $this->insertSql(new CacatFisik(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $cacat->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BahasaPasien;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BahasaPasienController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $bahasa = BahasaPasien::where('nama_bahasa', 'like', "{$request->bahasa}%")->get();
        return response()->json($bahasa);
    }
    function create(Request $request)
    {
        $bahasa = BahasaPasien::where('nama_bahasa', 'like', "%{$request->bahasa}%")->orWhere('id', $request->bahasa)->first();
        if (!$bahasa) {
            try {
                $data = ['nama_bahasa' => $request->bahasa];
                $bahasa = BahasaPasien::create($data);
                if ($bahasa) {
                    $this->insertSql(new BahasaPasien(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $bahasa->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use function PHPUnit\Framework\isEmpty;

class KecamatanController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $kecamatan = Kecamatan::where('nm_kec', 'like', "%{$request->kecamatan}%")->limit(10)->get();
        return response()->json($kecamatan);
    }
    function create(Request $request)
    {
        $kecamatan = Kecamatan::where('nm_kec', $request->kecamatan)
            ->orWhere('kd_kec', $request->kecamatan)
            ->first();
        if ($kecamatan->kd_kec == 0 || !$kecamatan) {
            try {
                $data = [
                    'nm_kec' => strtoupper($request->kecamatan)
                ];
                $create = Kecamatan::create($data);
                if ($create) {
                    $this->insertSql(new Kecamatan(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $create->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

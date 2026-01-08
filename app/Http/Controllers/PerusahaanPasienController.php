<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use Illuminate\Http\Request;
use App\Models\PerusahaanPasien;
use Illuminate\Database\QueryException;

class PerusahaanPasienController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $kabupaten = PerusahaanPasien::where('nama_perusahaan', 'like', "{$request->perusahaan}%")->get();
        return response()->json($kabupaten);
    }
    function create(Request $request)
    {
        $kabupaten = PerusahaanPasien::where('nama_perusahaan', 'like', "%{$request->perusahaan}%")
            ->orWhere('kode_perusahaan', $request->perusahaan)->first();
        if (!$kabupaten) {
            try {
                $data = [
                    'nama_perusahaan' => strtoupper($request->perusahaan)
                ];
                $create = PerusahaanPasien::create($data);
                if ($create) {
                    $this->insertSql(new PerusahaanPasien(), $data);
                }
                return response()->json(['message' => 'SUKSES', 'id' => $create->id], 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }
}

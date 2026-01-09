<?php

namespace App\Http\Controllers;

use App\Models\MapingDokterPcare;
use Illuminate\Http\Request;

class MapingDokterController extends Controller
{
    public function get(Request $request)
    {
        $dokter = new MapingDokterPcare();
        if ($request->dokter) {
            $dokter = $dokter->where('nm_dokter_pcare', 'like', "%{$request->dokter}%")->get();
        } else if ($request->kdDokterPcare) {
            $dokter = $dokter->where('kd_dokter_pcare', $request->kdDokterPcare)->first();
        } else if ($request->kdDokter) {
            $dokter = $dokter->where('kd_dokter', $request->kdDokter)->first();
        } else {
            $dokter = $dokter->limit(10)->get();
        }
        return response()->json($dokter);
    }

    public function getDokterForMapping(Request $request)
    {
        $dokter = \App\Models\Dokter::where('status', '1')->with('maping');
        return \Yajra\DataTables\DataTables::of($dokter)->make(true);
    }

    public function store(Request $request)
    {
        $data = [
            'kd_dokter' => $request->kdDokter,
            'kd_dokter_pcare' => $request->kdDokterPcare,
            'nm_dokter_pcare' => $request->nmDokterPcare,
        ];
        try {
            $map = MapingDokterPcare::updateOrCreate(
                ['kd_dokter' => $request->kdDokter],
                $data
            );
            return response()->json(['message' => 'Berhasil mapping dokter', 'data' => $map]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal mapping dokter', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $map = MapingDokterPcare::where('kd_dokter', $request->kdDokter)->delete();
            return response()->json(['message' => 'Berhasil hapus mapping dokter']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal hapus mapping dokter', 'error' => $e->getMessage()], 500);
        }
    }
}

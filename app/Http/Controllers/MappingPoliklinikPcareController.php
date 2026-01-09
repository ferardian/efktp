<?php

namespace App\Http\Controllers;

use App\Models\MappingPoliklinikPcare;
use Illuminate\Http\Request;

class MappingPoliklinikPcareController extends Controller
{
    function get(Request $request)
    {
        $mapping = MappingPoliklinikPcare::with('poliklinik');

        if ($request->kdPoliPcare) {
            $mapping = $mapping->where('kd_poli_pcare', $request->kdPoliPcare)->first();
        } else {
            $mapping = $mapping->where('kd_poli_rs', $request->kdPoli)->first();
        }
        return response()->json($mapping);
    }

    function getPoliklinikForMapping(Request $request)
    {
        $poliklinik = \App\Models\Poliklinik::active()->with('maping');
        return \Yajra\DataTables\DataTables::of($poliklinik)->make(true);
    }

    function store(Request $request)
    {
        $data = [
            'kd_poli_rs' => $request->kdPoliRs,
            'kd_poli_pcare' => $request->kdPoliPcare,
            'nm_poli_pcare' => $request->nmPoliPcare,
        ];
        try {
            $map = MappingPoliklinikPcare::updateOrCreate(
                ['kd_poli_rs' => $request->kdPoliRs],
                $data
            );
            return response()->json(['message' => 'Berhasil mapping poliklinik', 'data' => $map]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal mapping poliklinik', 'error' => $e->getMessage()], 500);
        }
    }

    function delete(Request $request)
    {
        try {
            $map = MappingPoliklinikPcare::where('kd_poli_rs', $request->kdPoliRs)->delete();
            return response()->json(['message' => 'Berhasil hapus mapping poliklinik']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal hapus mapping poliklinik', 'error' => $e->getMessage()], 500);
        }
    }
}

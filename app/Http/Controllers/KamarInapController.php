<?php

namespace App\Http\Controllers;

use App\Models\KamarInap;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KamarInapController extends Controller
{

    function get(Request $request)
    {
        $kamarInap = KamarInap::with(['regPeriksa' => function ($q) {
            return $q->with(['pasien' => function ($q) {
                return $q->with(['kel', 'kec', 'kab']);
            }, 'dokter', 'penjab']);
        }, 'kamar.bangsal'])->where('stts_pulang', '!=', 'Pindah Kamar');

        if ($request->tglAwal && $request->tglAkhir) {
            $kamarInap = $kamarInap->whereBetween('tgl_masuk', [date('Y-m-d', strtotime($request->tglAwal)), date('Y-m-d', strtotime($request->tglAkhir))]);
        } else {
            $kamarInap = $kamarInap->where('tgl_masuk', date('Y-m-d'))
            ->whereNot('stts_pulang', 'Atas Persetujuan Dokter' );
        }

        if ($request->pulang == 'Belum Pulang') {
            $kamarInap = $kamarInap->where('stts_pulang', '-');
        } else if ($request->pulang == 'Pulang') {
            $kamarInap = $kamarInap->where('stts_pulang', '!=', '-')->orWhere('stts_pulang', '==', 'Atas Persetujuan Dokter');
        }

        if ($request->dataTable) {
            return DataTables::of($kamarInap)->make(true);
        }
        return response()->json($kamarInap->get());
    }
}

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

        // Filter by status first
        if ($request->pulang == 'Belum Pulang') {
            $kamarInap = $kamarInap->where('stts_pulang', '-');
        } 
        // For Pulang filter, already filtered by != 'Pindah Kamar' at the beginning
        // No additional status filter needed

        // Then apply date filtering based on filter type
        if ($request->tglAwal && $request->tglAkhir) {
            $tglAwal = date('Y-m-d', strtotime($request->tglAwal));
            $tglAkhir = date('Y-m-d', strtotime($request->tglAkhir));
            
            // If filter is "Pulang", use tgl_keluar
            if ($request->pulang == 'Pulang') {
                $kamarInap = $kamarInap->whereBetween('tgl_keluar', [$tglAwal, $tglAkhir]);
            } 
            // If filter is "Masuk", use tgl_masuk
            else if ($request->pulang == 'Masuk') {
                $kamarInap = $kamarInap->whereBetween('tgl_masuk', [$tglAwal, $tglAkhir]);
            }
        } else if ($request->pulang == 'Belum Pulang') {
            // For Belum Pulang without date range, no additional date filter needed
            // Just show all patients who haven't been discharged
        }

        if ($request->dataTable) {
            if ($kamarInap->count() == 0) {
                return response()->json([
                    'draw' => 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }
            return DataTables::of($kamarInap)->make(true);
        }
        return response()->json($kamarInap->get());
    }

    function create(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required',
            'kd_kamar' => 'required',
            'tgl_masuk' => 'required',
            'jam_masuk' => 'required',
            'diagnosa_awal' => 'required',
            'lama' => 'required',
        ]);

        try {
            // Check if already exists? Usually one active Ranap per No Rawat
            $exists = KamarInap::where('no_rawat', $request->no_rawat)->first();
            if ($exists) {
                return response()->json(['message' => 'Pasien sudah terdaftar di Kamar Inap'], 400);
            }

            // Insert Kamar Inap
            KamarInap::create([
                'no_rawat' => $request->no_rawat,
                'kd_kamar' => $request->kd_kamar,
                'trf_kamar' => str_replace('.', '', $request->trf_kamar), // simple sanitize currency
                'diagnosa_awal' => $request->diagnosa_awal,
                'diagnosa_akhir' => '-',
                'tgl_masuk' => date('Y-m-d', strtotime($request->tgl_masuk)),
                'jam_masuk' => $request->jam_masuk,
                'tgl_keluar' => '0000-00-00',
                'jam_keluar' => '00:00:00',
                'lama' => $request->lama,
                'ttl_biaya' => 0, // Calculated later usually
                'stts_pulang' => '-',
            ]);

            // Update Status Kamar to ISI
            \App\Models\Kamar::where('kd_kamar', $request->kd_kamar)->update(['status' => 'ISI']);
            
            // Update Status Registrasi (RegPeriksa)? Typically changed to 'Dirawat'
            // \App\Models\RegPeriksa::where('no_rawat', $request->no_rawat)->update(['stts' => 'Dirawat']); // Uncomment if needed

            return response()->json(['message' => 'Berhasil menyimpan data Kamar Inap']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function getKamarKosong(Request $request) {
        $search = $request->search;
        $kamar = \App\Models\Kamar::with('bangsal')
            ->where(function($q) {
                $q->where('status', 'ISI')
                  ->orWhere('status', 'KOSONG');
            });

        $kamar = $kamar->where(function($q) use ($search) {
             $q->where('kd_kamar', 'like', "%$search%")
               ->orWhereHas('bangsal', function($b) use ($search) {
                   $b->where('nm_bangsal', 'like', "%$search%");
               });
        });

        return response()->json($kamar->limit(20)->get());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\RawatInapDr;
use App\Models\RawatInapPr;
use App\Models\RawatInapDrPr;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TindakanRanapController extends Controller
{
    public function get(Request $request)
    {
        $no_rawat = $request->no_rawat;
        
        $dr = RawatInapDr::with(['tindakan', 'dokter'])->where('no_rawat', $no_rawat);
        $pr = RawatInapPr::with(['tindakan', 'petugas'])->where('no_rawat', $no_rawat);
        $drpr = RawatInapDrPr::with(['tindakan', 'dokter', 'petugas'])->where('no_rawat', $no_rawat);

        if ($request->tgl_awal && $request->tgl_akhir) {
            $dr->whereBetween('tgl_perawatan', [$request->tgl_awal, $request->tgl_akhir]);
            $pr->whereBetween('tgl_perawatan', [$request->tgl_awal, $request->tgl_akhir]);
            $drpr->whereBetween('tgl_perawatan', [$request->tgl_awal, $request->tgl_akhir]);
        }

        $dr = $dr->get()->map(function($item) {
            $item->pelaksana = 'Dokter';
            $item->nama_pelaksana = $item->dokter->nm_dokter;
            return $item;
        });

        $pr = $pr->get()->map(function($item) {
            $item->pelaksana = 'Petugas';
            $item->nama_pelaksana = $item->petugas->nama;
            return $item;
        });

        $drpr = $drpr->get()->map(function($item) {
            $item->pelaksana = 'Dokter & Petugas';
            $item->nama_pelaksana = $item->dokter->nm_dokter . ' & ' . $item->petugas->nama;
            return $item;
        });

        $all = $dr->concat($pr)->concat($drpr)->sortByDesc(function($item) {
            return $item->tgl_perawatan . ' ' . $item->jam_rawat;
        });

        if ($request->dataTable) {
            return DataTables::of($all)->make(true);
        }

        return response()->json($all);
    }

    public function create(Request $request, \App\Action\TindakanRanapAction $action)
    {
        try {
            $action->handleCreate($request->all());
            return response()->json('Berhasil menambah tindakan');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function delete(Request $request, \App\Action\TindakanRanapAction $action)
    {
        try {
            $action->handleDelete($request->all());
            return response()->json('Berhasil menghapus tindakan');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}

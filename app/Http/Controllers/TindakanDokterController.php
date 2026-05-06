<?php

namespace App\Http\Controllers;

use App\Models\SetAkunRalan;
use App\Models\TindakanDokter;
use App\Traits\ResponseHandlerTrait;
use App\Traits\Track;
use DB;
use Exception;
use Illuminate\Http\Request;

class TindakanDokterController extends Controller
{
    use ResponseHandlerTrait, Track;
    function create(Request $request)
    {
        $data = $request->all();

        try {
            $tindakan = (new \App\Action\TindakanDokterAction())->handleCreate($data);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);

        }
        return $this->success($tindakan['data']);
    }

    function delete(Request $request)
    {
        $data = $request->all();
        try {
            $tindakan = (new \App\Action\TindakanDokterAction())->handleDelete($data);
            return $this->success($tindakan);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    function get(Request $request)
    {
        $no_rawat = $request->no_rawat;
        
        $dr = \App\Models\TindakanDokter::with(['tindakan', 'dokter'])
            ->where('no_rawat', $no_rawat)->get()->map(function($item) {
                $item->pelaksana = 'Dokter';
                $item->nama_pelaksana = $item->dokter->nm_dokter ?? '-';
                $item->type = 'dr';
                return $item;
            });

        $pr = \App\Models\TindakanPetugas::with(['tindakan', 'petugas'])
            ->where('no_rawat', $no_rawat)->get()->map(function($item) {
                $item->pelaksana = 'Petugas';
                $item->nama_pelaksana = $item->petugas->nama ?? '-';
                $item->type = 'pr';
                return $item;
            });

        $drpr = \App\Models\TindakanDokterPetugas::with(['tindakan', 'dokter', 'petugas'])
            ->where('no_rawat', $no_rawat)->get()->map(function($item) {
                $item->pelaksana = 'Dokter & Petugas';
                $item->nama_pelaksana = ($item->dokter->nm_dokter ?? '-') . ' & ' . ($item->petugas->nama ?? '-');
                $item->type = 'drpr';
                return $item;
            });

        $data = $dr->concat($pr)->concat($drpr);
        
        return $this->success($data);
    }






}

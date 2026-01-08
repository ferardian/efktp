<?php

namespace App\Http\Controllers\Lab\Permintaan;

use App\Models\Lab\Permintaan\PermintaanLab;
use App\Http\Controllers\Controller;
use App\Traits\ResponseHandlerTrait;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

class PermintaanLabController extends Controller
{
    use Track, ResponseHandlerTrait;

    private $permintaan;
    public function __construct()
    {
        $this->permintaan = new PermintaanLab();
    }


	function index()
	{
		return view('content.laboratorium.permintaan');
	}
    function get(Request $request)
    {
		if($request->dataTable){
			return $this->getDataTable($request);
		}
        $permintaan = $this->permintaan->where('no_rawat', $request->no_rawat)
            ->with(['pemeriksaan' => function ($q) {
                return $q->with([
                    'jenis' => function ($q) {
                        return $q->select(['kd_jenis_prw', 'nm_perawatan']);
                    }, 'detail.item' => function ($q) {
                        return $q->select(['id_template', 'Pemeriksaan as nama', 'kd_jenis_prw', 'satuan', 'nilai_rujukan_la as la', 'nilai_rujukan_pa as pa', 'nilai_rujukan_ld as ld', 'nilai_rujukan_pd as pd']);
                    }
                ]);
            }])->get();
        return response()->json($permintaan);
    }
    function getNoOrder(): JsonResponse
    {
        $permintaan = $this->permintaan
            ->where('tgl_permintaan', date('Y-m-d'))
            ->select('noorder')->orderBy('noorder', 'desc')->first();
        if ($permintaan == null) {
            $now = date('Ymd');
            $nomor = "PK{$now}0001";
        } else {
            $arrString = explode('PK', $permintaan->noorder);
            $nomor =  (int)$arrString[1] + 1;
            $nomor = "PK{$nomor}";
        }
        return response()->json($nomor);
    }
    function create(Request $request): JsonResponse
    {
        $noorder = json_decode($this->getNoOrder()->getContent(), true);
        $data = [
            'noorder' => $noorder,
            'no_rawat' => $request->no_rawat,
            'tgl_permintaan' => date('Y-m-d'),
            'jam_permintaan' => date('H:i:s'),
            'dokter_perujuk' => $request->kd_dokter,
            'status' => $request->status,
            'informasi_tambahan' => $request->informasi_tambahan,
            'diagnosa_klinis' => $request->diagnosa_klinis,
        ];

        try {
            $permintaan = $this->permintaan->create($data);
            if ($permintaan) {
                $this->insertSql($this->permintaan, $data);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json([
            'status' => true,
            'data' => $permintaan->noorder
        ]);
    }

    function delete($noorder): JsonResponse
    {
        $find = $this->permintaan->where('noorder', $noorder);

        if ($find->first()) {
            try {
                $delete = $find->delete();
                if ($delete) {
                    $this->deleteSql($this->permintaan, ['noorder' => $noorder]);
                }
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
            return response()->json('Berhasil');
        }
        return response()->json(['Tidak ditemukan'], 400);
    }
    function createPermintaan(Request $request): JsonResponse
    {
    }

	function getDataTable(Request $request)
	{
		$keyword = $request->dataTable;
		$tgl_permintaan = $request->dataTable['tgl_permintaan'];
		unset($keyword['tgl_permintaan']);
//		if($request->dataTable){
			$permintaan = $this->permintaan
				->whereBetween('tgl_permintaan', $tgl_permintaan)
				->orWhere($keyword)
			->with('registrasi', 'pasien', 'perujuk', 'poliklinik', 'penjab');
//
//		}else{
//			$permintaan = $this->permintaan;
//		}
//
		return DataTables::of($permintaan)->make(true);
	}

}

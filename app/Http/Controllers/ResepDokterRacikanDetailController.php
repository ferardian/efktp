<?php

namespace App\Http\Controllers;

use App\Models\ResepDokterRacikan;
use App\Models\ResepDokterRacikanDetail;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\DB;

class ResepDokterRacikanDetailController extends Controller
{
	use Track, ResponseTrait;

	public $mdRacikanDetail;

	public function __construct()
	{
		$this->mdRacikanDetail = new ResepDokterRacikanDetail();
	}

	function get(Request $request)
	{
		$keys = [
			'no_resep' => $request->no_resep,
			'no_racik' => $request->no_racik,
		];

		$resepDetail = ResepDokterRacikanDetail::where($keys)->with('obat.satuan', 'obat.jenis')->get();
		return response()->json($resepDetail);
	}

	public function create(Request $request)
	{

		$countData = count($request->data);
		$keys = [
			'no_resep' => $request->no_resep,
			'no_racik' => $request->no_racik
		];
		$cekResep = ResepDokterRacikanDetail::where($keys);

		// return sizeof($cekResep->get());
		if (sizeof($cekResep->get())) {
			$delete = $this->delete($request);
		}
		try {
			DB::transaction(function () use ($countData, $keys, $request) {
				for ($i = 0; $i < $countData; $i++) {
					$resep = ResepDokterRacikanDetail::create($request->data[$i]);
					ResepDokterRacikan::where($keys)->update(['aturan_pakai' => $request->aturan_pakai]);
					if ($resep) {
						$this->insertSql($this->mdRacikanDetail, $request->data[$i]);
					}
				}

			});

		} catch (\Exception $e) {
			return $this->erro(null, $e->getMessage());
		}
		return $this->success();
	}

	public function delete(Request $request)
	{
		$keys = [
			'no_resep' => $request->no_resep,
			'no_racik' => $request->no_racik,
		];
		if ($request->obat) {
			$keys['kode_brng'] = $request->obat;
		}

		try {

			$delete = ResepDokterRacikanDetail::where($keys)->delete();
			if ($delete) {
				$this->deleteSql($this->mdRacikanDetail, $keys);
			}
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
		return response()->json('SUKSES');
	}
}

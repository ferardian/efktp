<?php

namespace App\Http\Controllers;

use App\Models\ResepDokterRacikan;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ResepDokterRacikanController extends Controller
{
	//
	function get(Request $request)
	{
		if ($request->no_racik) {
			$resep = ResepDokterRacikan::where([
				'no_resep' => $request->no_resep,
				'no_racik' => $request->no_racik,
			])->with(['detail' => function ($query) {
				return $query->with(['obat.satuan']);
			}, 'metode'])->first();
			return response()->json($resep);
		}

		$resep = ResepDokterRacikan::where([
			'no_resep' => $request->no_resep,
		])->with(['detail' => function ($query) {
			return $query->with(['obat.satuan']);
		}, 'metode'])->get();
		return response()->json($resep);
	}

	function create(Request $request)
	{
		if (!$request->data) {
			return false;
		}
		$countData = count($request->data);

		try {
			for ($i = 0; $i < $countData; $i++) {
				$response[] = [
					'no_resep' => $request->data[$i]['no_resep'],
					'no_racik' => $request->data[$i]['no_racik'],
					'nama_racik' => $request->data[$i]['nama_racik'],
					'jml_dr' => $request->data[$i]['jml_dr'],
					'kd_racik' => $request->data[$i]['kd_racik'],
					'keterangan' => $request->data[$i]['keterangan'],
					'aturan_pakai' => $request->data[$i]['aturan_pakai'],
				];

			}
			DB::transaction(function () use ($response) {
				$resep = ResepDokterRacikan::insert($response);
				if ($resep) {
					$this->insertSql(new ResepDokterRacikan(), $response);
				}
			});
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
		return response()->json(['SUKSES', $response]);
	}

	function delete(Request $request)
	{
		$keys = [
			'no_resep' => $request->no_resep,
			'no_racik' => $request->no_racik,
		];
		try {
			DB::transaction(function () use ($keys) {
				$resep = ResepDokterRacikan::where($keys)->delete();
				if ($resep) {
					$this->deleteSql(new ResepDokterRacikan(), $keys);
				}
			});
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
		return response()->json('SUKSES', 200);
	}
}

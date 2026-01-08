<?php

namespace App\Http\Controllers;

use App\Models\EfktpTemplateRacikan;
use App\Models\EfktpTemplateRacikanDetail;
use App\Traits\Track;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Svg\Tag\Rect;
use Yajra\DataTables\DataTables;

class EfktpTemplateRacikanController extends Controller
{
	use Track;


	function index()
	{
		return view('content.farmasi.template.template');
	}

	function create(Request $request)
	{
		$data = [
			'kd_dokter' => $request->kd_dokter,
			'nm_racik' => $request->nm_racik,
		];
		try {
			DB::transaction(function () use ($data, $request) {
				$create = EfktpTemplateRacikan::create($data);
				if ($create) {
					$this->insertSql(new EfktpTemplateRacikan(), $data);
					$detail = new EfktpTemplateRacikanDetailController();
					$detail->create($request);
				}
			});
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 500);
		}
		return response()->json('SUKSES');
	}

	function update(Request $request)
	{
		$data = [
			'id' => $request->id,
			'kd_dokter' => $request->kd_dokter,
			'nm_racik' => $request->nm_racik,
		];

		if (!isset($request->id)) {
			return $this->create($request);
		}

		$template = EfktpTemplateRacikan::where('id', $data['id']);
		if ($template->first()) {
			try {
				$update = $template->update($data);
				$this->updateSql(new EfktpTemplateRacikan(), $data, ['id', $request->id]);
				$detail = new EfktpTemplateRacikanDetailController();
				$delete = $detail->delete($request);
				if ($request->obat) {
					$createDetail = $detail->create($request);
				}
			} catch (QueryException $e) {
				return response()->json($e->errorInfo);
			}
		}
		return response()->json('SUKSES');
	}

	function get(Request $request)
	{
		$getTemplate = EfktpTemplateRacikan::with(['dokter', 'detail' => function ($query) {
			return $query->with('barang');
		}]);

		if ($request->id) {
			$result = $getTemplate->where('id', $request->id)->first();
			return response()->json($result);
		} else if ($request->nm_racik) {
			$result = $getTemplate->where('nm_racik', $request->nm_racik)->first();
			return response()->json($result);
		} else if ($request->datatable || $request->kd_dokter) {
			$template = $getTemplate;
			if ($request->kd_dokter) {
				$template = $getTemplate->where('kd_dokter', $request->kd_dokter);
			}
			$template->get();
			return DataTables::of($template)->make(true);
		}
	}

	function search(Request $request)
	{
		$getTemplate = EfktpTemplateRacikan::where('nm_racik', 'like', '%' . $request->racik . '%')
			->with('detail.barang', 'dokter')->get();
		return response()->json($getTemplate);
	}

	function delete(Request $request)
	{
		$template = EfktpTemplateRacikan::where('id', $request->id)->delete();
		if ($template) {
			$this->deleteSql(new EfktpTemplateRacikanDetail(), ['id' => $request->id]);
		}
	}
}

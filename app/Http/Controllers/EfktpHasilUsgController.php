<?php

namespace App\Http\Controllers;

use App\Http\Requests\EfktpHasilUsgRequest;
use App\Models\EfktpHasilUsg;
use App\Traits\Track;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;

class EfktpHasilUsgController extends Controller
{
	public EfktpHasilUsg $model;
	use Track;
	use ResponseTrait;

	public function __construct(EfktpHasilUsg $model)
	{
		$this->model = $model;
	}

	public function create(EfktpHasilUsgRequest $request)
	{
		$validated = $request->validated();
		try {

			$validated['tgl_periksa'] = now()->translatedFormat('Y-m-d');
			$validated['jam_periksa'] = now()->translatedFormat('H:i:s');

			$create = $this->model->updateOrCreate(['no_rawat' => $validated['no_rawat']], $validated);
			if ($create) {
				$this->insertSql($this->model, $validated);
			}
		} catch (\Exception $e) {
			return $this->error($e, $e->getMessage());
		}

		return $this->success();
	}

	public function delete(Request $request)
	{
		try {
			$delete = $this->model->where('no_rawat', $request->no_rawat)->delete();
			if ($delete) {
				$this->deleteSql($this->model, ['no_rawat' => $request->no_rawat]);
			}
		} catch (\Exception $e) {
			return $this->error(null, $e->getMessage());
		}
		return $this->success();
	}

	public function first(Request $request)
	{
		$result = $this->model
			->where('no_rawat', $request->no_rawat)
			->first();
		return $this->success($result);
	}

	public function getHistory(string $no_rkm_medis)
	{
		$result = $this->model->with(['pasien', 'regPeriksa.poliklinik', 'dokter'])
			->whereHas('regPeriksa', function ($query) use ($no_rkm_medis) {
				return $query->where('no_rkm_medis', $no_rkm_medis);
			})->get();


		return $this->success($result);
	}


}

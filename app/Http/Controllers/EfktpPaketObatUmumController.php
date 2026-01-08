<?php

namespace App\Http\Controllers;

use App\Models\EfktpPaketObatUmum;
use App\Traits\Track;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\DB;

class EfktpPaketObatUmumController extends Controller
{
	use Track, ResponseTrait;

	protected EfktpPaketObatUmum $model;

	function __construct(EfktpPaketObatUmum $model)
	{
		$this->model = $model;
	}

	public function storeMany(array $data, int $idPaket)
	{
		try {
			DB::transaction(function () use ($data, $idPaket) {
				$this->destroy($idPaket);
				$create = collect($data)->map(function ($item) use ($idPaket) {
					$created = $this->model->create([
						'paket_id' => $idPaket,
						'kode_brng' => $item['kode_brng'],
						'jumlah' => $item['jumlah'],
						'aturan_pakai' => $item['aturan_pakai']
					]);
					return [
						'id' => $created->id,
						'kode_brng' => $created->kode_brng,
						'jumlah' => $created->jumlah,
						'aturan_pakai' => $created->aturan_pakai,
					];
				});

				if ($create) {
					$this->insertSql($this->model, $create);
				}

			});
		} catch (\Exception $e) {
			return $this->error(null, $e->getMessage());
		}


	}

	public function destroy(int $pkaetId)
	{
		try {
			$id = ['paket_id' => $pkaetId];
			$delete = $this->model->where($id)->delete();
			if ($delete) {
				$this->deleteSql($this->model, $id);
			}
		} catch (\Exception $e) {
			return $this->error(null, $e->getMessage());
		}

		return $this->success();
	}
}

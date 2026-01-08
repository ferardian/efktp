<?php

namespace App\Http\Controllers;

use App\Models\EfktpPaketObatRacik;
use App\Traits\Track;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\DB;

class EfktpPaketObatRacikController extends Controller
{
	use Track, ResponseTrait;

	protected EfktpPaketObatRacik $model;

	public function __construct(EfktpPaketObatRacik $model)
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
						'template_id' => $item['id_template'],
						'jumlah' => $item['jumlah'],
						'kd_racik' => $item['kd_racik'],
						'aturan_pakai' => $item['aturan_pakai']
					]);
					return [
						'id' => $created->id,
						'template_id' => $created->id_template,
						'jumlah' => $created->jumlah,
						'kd_racik' => $created->kd_racik,
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
		return $this->success();

	}

	public function destroy(int $paket_id)
	{

		try {
			$delete = $this->model->where([
				'paket_id' => $paket_id,
			])->delete();
			if ($delete) {
				$this->deleteSql($this->model, ['paket_id' => $paket_id]);
			}
		} catch (\Exception $e) {
			return $this->error($e->getMessage());
		}
		return $this->success();
	}
}

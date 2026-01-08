<?php

namespace App\Action;

use App\Models\ResepObat;

class GenerateNoResep
{

	public function handle(ResepObat $resep): int
	{
		$resep = $resep->select('no_resep')
			->orderBy('no_resep', 'DESC')
			->where('tgl_peresepan', date('Y-m-d'))
			->orWhere('tgl_perawatan', date('Y-m-d'))
			->first();

		if ($resep) {
			$no_resep = $resep->no_resep + 1;
		} else {
			$no_resep = date('Ymd') . '0001';
		}
		return $no_resep;
	}
}
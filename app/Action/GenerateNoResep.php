<?php

namespace App\Action;

use App\Models\ResepObat;

class GenerateNoResep
{

	public function handle(ResepObat $resep): int
	{
		$todayPrefix = date('Ymd');
		$resep = $resep->select('no_resep')
			->where('no_resep', 'like', $todayPrefix . '%')
			->orderBy('no_resep', 'DESC')
			->first();

		if ($resep) {
			$no_resep = $resep->no_resep + 1;
		} else {
			$no_resep = $todayPrefix . '0001';
		}
		return $no_resep;
	}
}
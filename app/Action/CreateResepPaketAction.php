<?php

namespace App\Action;

use App\Http\Controllers\ResepObatController;
use App\Models\ResepDokter;
use App\Models\ResepDokterRacikan;
use App\Models\ResepDokterRacikanDetail;
use App\Models\ResepObat;
use App\Traits\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CreateResepPaketAction
{
	use Track;

	protected ResepObatController $controller;

	public function __construct()
	{
		$this->controller = new ResepObatController();
	}

	public function handle(Request $request)
	{
		[$noResep, $lastNoRacik] = $this->resolveResep($request);

		$umum = $this->prepareUmum($request->umum ?? [], $noResep);
		$racikan = $this->prepareRacikan($request->racikan ?? [], $noResep, $lastNoRacik);

		$this->storeResep(
			$umum,
			$racikan['utama'],
			$racikan['detail']
		);

		return $noResep;
	}

	private function resolveResep(Request $request): array
	{
		$resep = ResepObat::where([
			'no_rawat' => $request->no_rawat,
			'tgl_peresepan' => date('Y-m-d'),
		])->first();

		if (!$resep) {
			$noResep = $this->controller->setNoResep($request);
			$this->controller->create($request);
			return [$noResep, 0];
		}

		$lastNoRacik = ResepDokterRacikan::where('no_resep', $resep->no_resep)
			->max('no_racik') ?? 0;

		return [$resep->no_resep, $lastNoRacik];
	}

	private function prepareUmum(array $items, string $noResep): array
	{
		return collect($items)->map(fn($item) => [
			...$item,
			'no_resep' => $noResep,
		])->toArray();
	}

	private function prepareRacikan(array $items, string $noResep, int $startRacik): array
	{
		$racikan = collect($items)->map(function ($item) use ($noResep, &$startRacik) {
			$noRacik = ++$startRacik;

			return [
				...$item,
				'no_resep' => $noResep,
				'no_racik' => $noRacik,
				'detail' => collect($item['detail'] ?? [])
					->map(fn($d) => [
						...$d,
						'no_resep' => $noResep,
						'no_racik' => $noRacik,
					])->toArray(),
			];
		});

		return [
			'utama' => $racikan->map(fn($r) => collect($r)->except('detail'))->toArray(),
			'detail' => $racikan->pluck('detail')->flatten(1)->values()->toArray(),
		];
	}

	private function storeResep(array $umum, array $racikan, array $detail): void
	{
		DB::transaction(function () use ($umum, $racikan, $detail) {
			if ($umum) {
				ResepDokter::insert($umum);
				$this->insertSql(new ResepDokter(), $umum);
			}

			if ($racikan) {
				ResepDokterRacikan::insert($racikan);
				$this->insertSql(new ResepDokterRacikan(), $racikan);
			}

			if ($detail) {
				ResepDokterRacikanDetail::insert($detail);
				$this->insertSql(new ResepDokterRacikanDetail(), $detail);
			}
		});
	}





}
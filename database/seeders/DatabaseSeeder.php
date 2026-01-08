<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DataBarang;
use App\Models\EfktpPaketObat;
use App\Models\EfktpPaketObatRacik;
use App\Models\EfktpPaketObatUmum;
use App\Models\EfktpTemplateRacikan;
use App\Models\EfktpTemplateRacikanDetail;
use App\Models\MetodeRacik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// \App\Models\User::factory(10)->create();

		// \App\Models\User::factory()->create([
		//     'name' => 'Test User',
		//     'email' => 'test@example.com',
		// ]);
		$databarang = DataBarang::where('status', '1')
			->get();

		for ($i = 1; $i <= 10; $i++) {
			EfktpTemplateRacikan::create([
				'kd_dokter' => Arr::random(['D0000004', 'D0000003', 'D0000002']),
				'nm_racik' => 'RACIK ' . rand(100, 999)
			]);
		}
		for ($i = 1; $i <= 10; $i++) {
			EfktpTemplateRacikanDetail::create([
				'id_racik' => Arr::random(EfktpTemplateRacikan::all()->pluck('id')->toArray()),
				'kode_brng' => Arr::random($databarang->pluck('kode_brng')->toArray()),
			]);
		}

		for ($i = 1; $i <= 10; $i++) {
			EfktpPaketObat::create([
				'nama' => 'PAKET ' . $i,
				'kd_poli' => Arr::random(['U0009', 'U0001', 'U0002']),
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}


		for ($i = 1; $i <= 10; $i++) {
			EfktpPaketObatUmum::create([
				'paket_id' => Arr::random(EfktpPaketObat::all()->pluck('id')->toArray()),
				'jumlah' => 10,
				'aturan_pakai' => Arr::random(['3 x 1 habiskan', '1x1', '2x1', 'oleskan']),
				'kode_brng' => Arr::random($databarang->pluck('kode_brng')->toArray()),
			]);
		}

		for ($i = 1; $i <= 10; $i++) {
			EfktpPaketObatRacik::create([
				'paket_id' => Arr::random(EfktpPaketObat::all()->pluck('id')->toArray()),
				'jumlah' => 10,
				'aturan_pakai' => Arr::random(['3 x 1 habiskan', '1x1', '2x1', 'oleskan']),
				'kd_racik' => Arr::random(MetodeRacik::all()->pluck('kd_racik')->toArray()),
				'template_id' => Arr::random(EfktpTemplateRacikan::all()->pluck('id')->toArray()),
			]);
		}


	}
}

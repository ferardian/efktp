<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EfktpHasilUsgTest extends TestCase
{
	/**
	 * A basic feature test example.
	 */
	public function test_example(): void
	{
		$response = $this->get('/');

		$response->assertStatus(200);
	}

	public function test_is_create_success()
	{
		$data = [
			'no_rawat' => '2025/10/02/000001',
			'kd_dokter' => 'D0000004',
			'tgl_periksa' => now()->toDateTimeString(),
			'janin' => 'Tunggal',
			'presentasi' => 'Kepala',
			'DJJ' => 'Normal',
			'letak_punggung' => 'Kiri',
			'letak_plasenta' => 'Fundus',
			'jenis_kelamin' => 'L',
			'TBJ' => '3000 gram',
			'HPL' => now()->addMonths(1)->toDateString(),
			'umur_kehamilan' => 32,
			'GS' => '30mm',
			'ketuban' => 'Cukup',
		];

		$response = $this->postJson('hasil-usg', $data);
		$response->assertStatus(200);

		$this->assertDatabaseHas('efktp_hasil_usg', [
			'no_rawat' => '2025/10/02/000001',
			'kd_dokter' => 'D0000004',
		]);
	}
}

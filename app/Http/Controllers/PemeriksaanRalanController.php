<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanRalan;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PemeriksaanRalanController extends Controller
{
	use Track;

	public $pemeriksaan;

	public function __construct()
	{
		$this->pemeriksaan = new PemeriksaanRalan();
	}

	public function show(Request $req)
	{
		$pemeriksaan = $this->pemeriksaan->with(['diagnosa', 'prosedur', 'pegawai', 'regPeriksa.poliklinik', 'regPeriksa.triaseIgd' => function ($q) {
			$q->with(['skala1.master', 'skala2.master', 'skala3.master', 'skala4.master', 'skala5.master']);
		}, 'regPeriksa.penilaianMedisIgd', 'regPeriksa.periksaLab' => function ($q) {
			$q->with(['jenis', 'detail.template']);
		}, 'rujukInternal.dokter', 'rujukInternal.poliklinik', 'resep']);
		if ($req->nip) {
			$result = $pemeriksaan->where('no_rawat', $req->no_rawat)->where('nip', $req->nip)->first();
		} else {
			$result = $pemeriksaan->where('no_rawat', $req->no_rawat)->get();
		}
		return response()->json($result);
	}

	public function get(Request $req)
	{
		$pemeriksaan = $this->pemeriksaan->where('no_rawat', $req->no_rawat)
			->with(['diagnosa', 'prosedur', 'pegawai', 'regPeriksa.poliklinik', 'resep'])
			->first();
		return response()->json($pemeriksaan);
	}

	public function create(Request $req)
	{
		$data = [
			'no_rawat' => $req->no_rawat,
			'tgl_perawatan' => date('Y-m-d'),
			'jam_rawat' => date('H:i:s'),
			'nip' => $req->nip,
			'keluhan' => $req->keluhan,
			'pemeriksaan' => $req->pemeriksaan,
			'suhu_tubuh' => $req->suhu_tubuh,
			'tensi' => $req->tensi,
			'tinggi' => $req->tinggi,
			'berat' => $req->berat,
			'respirasi' => $req->respirasi,
			'nadi' => $req->nadi,
			'spo2' => $req->spo2,
			'gcs' => $req->gcs,
			'kesadaran' => $req->kesadaran,
			'alergi' => $req->alergi ? $req->alergi : '-',
			'lingkar_perut' => $req->lingkar_perut,
			'rtl' => $req->rtl,
			'penilaian' => $req->penilaian,
			'instruksi' => $req->instruksi,
			'evaluasi' => '-',
		];

		$find = PemeriksaanRalan::where(['no_rawat' => $req->no_rawat, 'nip' => $req->nip])->first();
		if ($find) {
			unset($data['tgl_rawat'], $data['jam_rawat']);
			$request = $req->merge($data); //convert array data menjadi object request laravel
			return $update = $this->update($request);
		}
		try {
			$pemeriksaan = $this->pemeriksaan->create($data);
			if ($pemeriksaan) {
				$this->insertSql(new PemeriksaanRalan(), $data);
				$this->updateAntrianPanggil($req->no_rawat);
				return response()->json('SUKSES', 201);
			}
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 400);
		}
	}

	public function update(Request $req)
	{
		$data = [
			'keluhan' => $req->keluhan,
			'pemeriksaan' => $req->pemeriksaan,
			'suhu_tubuh' => $req->suhu_tubuh,
			'tensi' => $req->tensi,
			'tinggi' => $req->tinggi,
			'berat' => $req->berat,
			'respirasi' => $req->respirasi,
			'nadi' => $req->nadi,
			'spo2' => $req->spo2,
			'gcs' => $req->gcs,
			'kesadaran' => $req->kesadaran,
			'alergi' => $req->alergi ? $req->alergi : '-',
			'lingkar_perut' => $req->lingkar_perut,
			'rtl' => $req->rtl,
			'penilaian' => $req->penilaian,
			'instruksi' => $req->instruksi,
			'evaluasi' => '-',
		];
		$keys = [
			'no_rawat' => $req->no_rawat,
			'nip' => $req->nip,
		];
		try {
			$pemeriksaan = $this->pemeriksaan->where($keys)->update($data);
			if ($pemeriksaan) {
				$this->updateSql(new PemeriksaanRalan(), $data, $keys);
				$this->updateAntrianPanggil($req->no_rawat);
			}
			return response()->json('SUKSES', 201);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 400);
		}
	}

	/**
	 * Kirim update status antrean Task 1 (Panggil / Mulai Pelayanan Poli) ke BPJS Antrol
	 * jika config ANTRIAN_ENABLED=true di .env
	 */
	private function updateAntrianPanggil(string $noRawat): void
	{
		if (!config('bpjs.antrian.enabled', false)) {
			return;
		}

		try {
			$regPeriksa = \App\Models\RegPeriksa::where('no_rawat', $noRawat)
				->with(['pasien', 'poliklinik.maping'])
				->first();

			if (!$regPeriksa) {
				return;
			}

			$kdPoliPcare = $regPeriksa->poliklinik->maping->kd_poli_pcare ?? '';
			if (empty($kdPoliPcare)) {
				return;
			}

			$noPeserta = $regPeriksa->pasien->no_peserta ?? '';
			$isBpjs    = !empty($noPeserta) && $noPeserta !== '-';

			$waktu = (int) (microtime(true) * 1000);
			$payload = [
				'tanggalperiksa' => date('Y-m-d', strtotime($regPeriksa->tgl_registrasi)),
				'kodepoli'       => $kdPoliPcare,
				'nomorkartu'     => $isBpjs ? $noPeserta : '',
				'status'         => '1', // 1 = Mulai Pelayanan Poli / Panggil
				'waktu'          => (string) $waktu,
			];

			$antrianService = new \App\Services\Bpjs\Antrian\AntrianService();
			$res = $antrianService->panggil($payload);

			\Illuminate\Support\Facades\Log::info("[ANTRIAN PANGGIL TASK 1] CPPT no_rawat: {$noRawat}", [
				'payload'  => $payload,
				'response' => $res,
			]);
		} catch (\Throwable $e) {
			\Illuminate\Support\Facades\Log::error("[ANTRIAN PANGGIL TASK 1 ERROR] no_rawat: {$noRawat} - " . $e->getMessage());
		}
	}
}

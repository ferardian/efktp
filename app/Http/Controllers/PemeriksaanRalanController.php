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
		$pemeriksaan = $this->pemeriksaan->with(['diagnosa', 'prosedur', 'pegawai', 'regPeriksa.poliklinik', 'rujukInternal.dokter', 'rujukInternal.poliklinik', 'resep']);
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
			}
			return response()->json('SUKSES', 201);
		} catch (QueryException $e) {
			return response()->json($e->errorInfo, 400);
		}
	}
}

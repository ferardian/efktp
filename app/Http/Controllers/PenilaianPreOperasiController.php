<?php

namespace App\Http\Controllers;

use App\Models\PenilaianPreOperasi;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianPreOperasiController extends Controller
{
	use Track;

	public function getByNoRawat(Request $request, ?string $no_rawat = null): JsonResponse
	{
		$no_rawat = $request->no_rawat ?? $no_rawat;
		if ($no_rawat) {
			$no_rawat = urldecode($no_rawat);
		}
		if (!$no_rawat) {
			return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
		}

		$data = PenilaianPreOperasi::with(['dokter', 'regPeriksa.pasien'])
			->where('no_rawat', $no_rawat)
			->orderBy('tanggal', 'desc')
			->get();

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	public function first(Request $request): JsonResponse
	{
		$query = PenilaianPreOperasi::with(['dokter', 'regPeriksa.pasien'])
			->where('no_rawat', $request->no_rawat);

		if ($request->has('tanggal') && !empty($request->tanggal)) {
			$query->where('tanggal', $request->tanggal);
		}

		$data = $query->orderBy('tanggal', 'desc')->first();

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	public function store(Request $request): JsonResponse
	{
		$request->validate([
			'no_rawat' => 'required|string',
			'tanggal' => 'required',
			'kd_dokter' => 'required|string',
		]);

		DB::beginTransaction();
		try {
			$tanggal = date('Y-m-d H:i:s', strtotime($request->tanggal));
			$data = [
				'no_rawat' => $request->no_rawat,
				'tanggal' => $tanggal,
				'kd_dokter' => $request->kd_dokter,
				'ringkasan_klinik' => $request->ringkasan_klinik ?? '-',
				'pemeriksaan_fisik' => $request->pemeriksaan_fisik ?? '-',
				'pemeriksaan_diagnostik' => $request->pemeriksaan_diagnostik ?? '-',
				'diagnosa_pre_operasi' => $request->diagnosa_pre_operasi ?? '-',
				'rencana_tindakan_bedah' => $request->rencana_tindakan_bedah ?? '-',
				'hal_hal_yang_perludi_persiapkan' => $request->hal_hal_yang_perludi_persiapkan ?? '-',
				'terapi_pre_operasi' => $request->terapi_pre_operasi ?? '-',
			];

			// Jika update record yang sudah ada
			if ($request->has('tanggal_lama') && !empty($request->tanggal_lama)) {
				PenilaianPreOperasi::where('no_rawat', $request->no_rawat)
					->where('tanggal', $request->tanggal_lama)
					->delete();
			}

			$record = PenilaianPreOperasi::updateOrCreate(
				['no_rawat' => $request->no_rawat, 'tanggal' => $tanggal],
				$data
			);

			$this->insertSql($record, $data);
			DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Kajian Pra Bedah berhasil disimpan',
				'data' => $record,
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'success' => false,
				'message' => 'Gagal menyimpan: ' . $e->getMessage(),
			], 500);
		}
	}

	public function delete(Request $request): JsonResponse
	{
		DB::beginTransaction();
		try {
			$record = PenilaianPreOperasi::where('no_rawat', $request->no_rawat)
				->where('tanggal', $request->tanggal)
				->first();

			if ($record) {
				$record->delete();
				$this->deleteSql($record, ['no_rawat' => $request->no_rawat, 'tanggal' => $request->tanggal]);
			}
			DB::commit();

			return response()->json(['success' => true, 'message' => 'Kajian Pra Bedah berhasil dihapus']);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
		}
	}

	public function print(Request $request)
	{
		$data = PenilaianPreOperasi::with(['dokter', 'regPeriksa.pasien', 'regPeriksa.poliklinik'])
			->where('no_rawat', $request->no_rawat)
			->where('tanggal', $request->tanggal)
			->firstOrFail();

		$setting = Setting::first();

		return view('content.print.praBedahPdf', compact('data', 'setting'));
	}
}

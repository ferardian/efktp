<?php

namespace App\Http\Controllers;

use App\Models\LaporanAnestesi;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanAnestesiController extends Controller
{
	use Track;

	public function getByNoRawat(string $no_rawat): JsonResponse
	{
		$data = LaporanAnestesi::with(['dokterAnestesi', 'dokterOperator1', 'regPeriksa.pasien'])
			->where('no_rawat', $no_rawat)
			->orderBy('mulai', 'desc')
			->get();

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	public function first(Request $request): JsonResponse
	{
		$query = LaporanAnestesi::with(['dokterAnestesi', 'dokterOperator1', 'dokterOperator2', 'asisten', 'penata', 'petugasRecovery', 'regPeriksa.pasien'])
			->where('no_rawat', $request->no_rawat);

		if ($request->has('mulai') && !empty($request->mulai)) {
			$query->where('mulai', $request->mulai);
		}

		$data = $query->orderBy('mulai', 'desc')->first();

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	public function store(Request $request): JsonResponse
	{
		$request->validate([
			'no_rawat' => 'required|string',
			'mulai' => 'required',
			'selesai' => 'required',
			'dokter_anestesi' => 'required|string',
			'operator1' => 'required|string',
		]);

		DB::beginTransaction();
		try {
			$mulai = date('Y-m-d H:i:s', strtotime($request->mulai));
			$selesai = date('Y-m-d H:i:s', strtotime($request->selesai));

			// Hitung lama operasi & anestesi jika tidak diinput manual
			$diffMinutes = max(1, round((strtotime($selesai) - strtotime($mulai)) / 60));
			$lamaOperasi = $request->lama_operasi ?: ($diffMinutes . ' Menit');
			$lamaAnestesi = $request->lama_anastesi ?: ($diffMinutes . ' Menit');

			$data = [
				'no_rawat' => $request->no_rawat,
				'mulai' => $mulai,
				'selesai' => $selesai,
				'tempat_pemantauan' => $request->tempat_pemantauan ?? 'OK',
				'tindakan_operasi' => $request->tindakan_operasi ?? '-',
				'operator1' => $request->operator1,
				'asisten_operator' => $request->asisten_operator ?: '-',
				'dokter_anestesi' => $request->dokter_anestesi,
				'operator2' => $request->operator2 ?: '-',
				'onloop' => $request->onloop ?: '-',
				'penata_anestesi' => $request->penata_anestesi ?: '-',
				'diagnosa_preop' => $request->diagnosa_preop ?? '-',
				'diagnosa_postop' => $request->diagnosa_postop ?? '-',
				'status_asa' => $request->status_asa ?? '1',
				'karena' => $request->karena ?? '-',
				'premedikasi' => $request->premedikasi ?? '-',
				'ttv_premedikasi_td' => $request->ttv_premedikasi_td ?? '-',
				'ttv_premedikasi_rr' => $request->ttv_premedikasi_rr ?? '-',
				'ttv_premedikasi_hr' => $request->ttv_premedikasi_hr ?? '-',
				'ttv_premedikasi_spo2' => $request->ttv_premedikasi_spo2 ?? '-',
				'ttv_premedikasi_ekg' => $request->ttv_premedikasi_ekg ?? 'Sinus',
				'ttv_premedikasi_suhu' => $request->ttv_premedikasi_suhu ?? '-',
				'ttv_premedikasi_lain' => $request->ttv_premedikasi_lain ?? '-',
				'lama_operasi' => $lamaOperasi,
				'lama_anastesi' => $lamaAnestesi,
				'keadaan_umum_bb' => $request->keadaan_umum_bb ?? '-',
				'keadaan_umum_tb' => $request->keadaan_umum_tb ?? '-',
				'keadaan_umum_alergi' => $request->keadaan_umum_alergi ?? '-',
				'keadaan_umum_malampathy' => $request->keadaan_umum_malampathy ?? 'Class 1',
				'keadaan_umum_e' => $request->keadaan_umum_e ?? '4',
				'keadaan_umum_v' => $request->keadaan_umum_v ?? '5',
				'keadaan_umum_m' => $request->keadaan_umum_m ?? '6',
				'jenis_anestesi_lokasi' => $request->jenis_anestesi_lokasi ?? '-',
				'jenis_anestesi_sedasi' => in_array($request->jenis_anestesi_sedasi, ['Ringan', 'Sedang', 'Berat']) ? $request->jenis_anestesi_sedasi : 'Sedang',
				'jenis_anestesi_regional' => in_array($request->jenis_anestesi_regional, ['Spinal', 'Epidural', 'Combined']) ? $request->jenis_anestesi_regional : 'Spinal',
				'jenis_anestesi_ga_ett' => in_array($request->jenis_anestesi_ga_ett, ['Ya', 'Tidak']) ? $request->jenis_anestesi_ga_ett : 'Tidak',
				'jenis_anestesi_ga_ntt' => in_array($request->jenis_anestesi_ga_ntt, ['Ya', 'Tidak']) ? $request->jenis_anestesi_ga_ntt : 'Tidak',
				'jenis_anestesi_ga_ema' => in_array($request->jenis_anestesi_ga_ema, ['Ya', 'Tidak']) ? $request->jenis_anestesi_ga_ema : 'Tidak',
				'jenis_anestesi_ga_bm' => in_array($request->jenis_anestesi_ga_bm, ['Ya', 'Tidak']) ? $request->jenis_anestesi_ga_bm : 'Tidak',
				'posisi' => $request->posisi ?? 'Supine',
				'perdarahan' => $request->perdarahan ?? '± 50 cc',
				'urine' => $request->urine ?? '± 100 cc',
				'komplikasi' => $request->komplikasi ?? 'Tidak Ada',
				'ekstubasi' => $request->ekstubasi ?? 'Di OK',
				'jumlah_pack' => $request->jumlah_pack ?? '-',
				'dipindahkan_ke' => $request->dipindahkan_ke ?? 'Ruang Pemulihan (RR)',
				'serah_terima_pasien' => in_array($request->serah_terima_pasien, ['RR', 'ICU/ICCU', 'NICU/PICU', 'ODC']) ? $request->serah_terima_pasien : 'RR',
				'catatan' => $request->catatan ?? '-',
				'nip_recovery_room' => $request->nip_recovery_room ?: '-',
			];

			if ($request->has('mulai_lama') && !empty($request->mulai_lama)) {
				LaporanAnestesi::where('no_rawat', $request->no_rawat)
					->where('mulai', $request->mulai_lama)
					->delete();
			}

			$record = LaporanAnestesi::updateOrCreate(
				['no_rawat' => $request->no_rawat, 'mulai' => $mulai],
				$data
			);

			$this->insertSql($record, $data);
			DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Laporan & Monitoring Anestesi berhasil disimpan',
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
			$record = LaporanAnestesi::where('no_rawat', $request->no_rawat)
				->where('mulai', $request->mulai)
				->first();

			if ($record) {
				$record->delete();
				$this->deleteSql($record, ['no_rawat' => $request->no_rawat, 'mulai' => $request->mulai]);
			}
			DB::commit();

			return response()->json(['success' => true, 'message' => 'Laporan Anestesi berhasil dihapus']);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
		}
	}

	public function print(Request $request)
	{
		$data = LaporanAnestesi::with(['dokterAnestesi', 'dokterOperator1', 'regPeriksa.pasien', 'regPeriksa.poliklinik'])
			->where('no_rawat', $request->no_rawat)
			->where('mulai', $request->mulai)
			->firstOrFail();

		$setting = Setting::first();

		return view('content.print.laporanAnestesiPdf', compact('data', 'setting'));
	}
}

<?php

namespace App\Http\Controllers;

use App\Models\PenilaianPreAnestesi;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianPreAnestesiController extends Controller
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

		$data = PenilaianPreAnestesi::with(['dokter', 'regPeriksa.pasien'])
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
		$query = PenilaianPreAnestesi::with(['dokter', 'regPeriksa.pasien'])
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
			$tanggalOperasi = !empty($request->tanggal_operasi) ? date('Y-m-d H:i:s', strtotime($request->tanggal_operasi)) : null;
			$puasa = !empty($request->puasa) ? date('Y-m-d H:i:s', strtotime($request->puasa)) : null;

			$data = [
				'no_rawat' => $request->no_rawat,
				'tanggal' => $tanggal,
				'kd_dokter' => $request->kd_dokter,
				'tanggal_operasi' => $tanggalOperasi,
				'diagnosa' => $request->diagnosa ?? '-',
				'rencana_tindakan' => $request->rencana_tindakan ?? '-',
				'tb' => $request->tb ?? '',
				'bb' => $request->bb ?? '',
				'td' => $request->td ?? '',
				'io2' => $request->io2 ?? ($request->spo2 ?? '-'),
				'nadi' => $request->nadi ?? '',
				'pernapasan' => $request->pernapasan ?? ($request->rr ?? '-'),
				'suhu' => $request->suhu ?? '',
				'fisik_cardiovasculer' => $request->fisik_cardiovasculer ?? '-',
				'fisik_paru' => $request->fisik_paru ?? '-',
				'fisik_abdomen' => $request->fisik_abdomen ?? '-',
				'fisik_extrimitas' => $request->fisik_extrimitas ?? '-',
				'fisik_endokrin' => $request->fisik_endokrin ?? '-',
				'fisik_ginjal' => $request->fisik_ginjal ?? '-',
				'fisik_obatobatan' => $request->fisik_obatobatan ?? '-',
				'fisik_laborat' => $request->fisik_laborat ?? '-',
				'fisik_penunjang' => $request->fisik_penunjang ?? '-',
				'riwayat_penyakit_alergiobat' => $request->riwayat_penyakit_alergiobat ?? '-',
				'riwayat_penyakit_alergilainnya' => $request->riwayat_penyakit_alergilainnya ?? '-',
				'riwayat_penyakit_terapi' => $request->riwayat_penyakit_terapi ?? '-',
				'riwayat_kebiasaan_merokok' => in_array($request->riwayat_kebiasaan_merokok, ['Tidak', 'Ya']) ? $request->riwayat_kebiasaan_merokok : 'Tidak',
				'riwayat_kebiasaan_ket_merokok' => $request->riwayat_kebiasaan_ket_merokok ?? '-',
				'riwayat_kebiasaan_alkohol' => in_array($request->riwayat_kebiasaan_alkohol, ['Tidak', 'Ya']) ? $request->riwayat_kebiasaan_alkohol : 'Tidak',
				'riwayat_kebiasaan_ket_alkohol' => $request->riwayat_kebiasaan_ket_alkohol ?? '-',
				'riwayat_kebiasaan_obat' => in_array($request->riwayat_kebiasaan_obat, ['-', 'Obat Obatan', 'Vitamin', 'Jamu Jamuan']) ? $request->riwayat_kebiasaan_obat : '-',
				'riwayat_kebiasaan_ket_obat' => $request->riwayat_kebiasaan_ket_obat ?? '-',
				'riwayat_medis_cardiovasculer' => $request->riwayat_medis_cardiovasculer ?? '-',
				'riwayat_medis_respiratory' => $request->riwayat_medis_respiratory ?? '-',
				'riwayat_medis_endocrine' => $request->riwayat_medis_endocrine ?? '-',
				'riwayat_medis_lainnya' => $request->riwayat_medis_lainnya ?? '-',
				'asa' => $request->asa ?? '1',
				'puasa' => $puasa,
				'rencana_anestesi' => $request->rencana_anestesi ?? 'GA',
				'rencana_perawatan' => $request->rencana_perawatan ?? 'Ruang Rawat Biasa',
				'catatan_khusus' => $request->catatan_khusus ?? '-',
			];

			if ($request->has('tanggal_lama') && !empty($request->tanggal_lama)) {
				PenilaianPreAnestesi::where('no_rawat', $request->no_rawat)
					->where('tanggal', $request->tanggal_lama)
					->delete();
			}

			$record = PenilaianPreAnestesi::updateOrCreate(
				['no_rawat' => $request->no_rawat, 'tanggal' => $tanggal],
				$data
			);

			$this->insertSql($record, $data);
			DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Kajian Pra Anestesi berhasil disimpan',
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
			$record = PenilaianPreAnestesi::where('no_rawat', $request->no_rawat)
				->where('tanggal', $request->tanggal)
				->first();

			if ($record) {
				$record->delete();
				$this->deleteSql($record, ['no_rawat' => $request->no_rawat, 'tanggal' => $request->tanggal]);
			}
			DB::commit();

			return response()->json(['success' => true, 'message' => 'Kajian Pra Anestesi berhasil dihapus']);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
		}
	}

	public function print(Request $request)
	{
		$data = PenilaianPreAnestesi::with(['dokter', 'regPeriksa.pasien', 'regPeriksa.poliklinik'])
			->where('no_rawat', $request->no_rawat)
			->where('tanggal', $request->tanggal)
			->firstOrFail();

		$setting = Setting::first();

		return view('content.print.praAnestesiPdf', compact('data', 'setting'));
	}
}

<?php

namespace App\Http\Controllers;

use App\Models\BuktiPersetujuanPenolakanTindakanPenerimaInformasi;
use App\Models\BuktiPersetujuanPenolakanTindakanSaksiKeluarga;
use App\Models\PersetujuanPenolakanTindakan;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PersetujuanPenolakanTindakanController extends Controller
{
	use Track;

	/**
	 * Mengambil daftar informed consent untuk nomor rawat tertentu.
	 */
	public function getByNoRawat(Request $request, ?string $no_rawat = null): JsonResponse
	{
		$no_rawat = $request->no_rawat ?? $no_rawat;
		if (!$no_rawat) {
			return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
		}

		$data = PersetujuanPenolakanTindakan::with([
			'dokter',
			'petugas',
			'buktiPenerimaInformasi',
			'buktiSaksiKeluarga',
			'regPeriksa.pasien',
		])
			->where('no_rawat', $no_rawat)
			->orderBy('tanggal', 'desc')
			->orderBy('no_pernyataan', 'desc')
			->get();

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	/**
	 * Mengambil detail 1 informed consent.
	 */
	public function show(string $no_pernyataan): JsonResponse
	{
		$data = PersetujuanPenolakanTindakan::with([
			'dokter',
			'petugas',
			'buktiPenerimaInformasi',
			'buktiSaksiKeluarga',
			'regPeriksa.pasien',
		])
			->where('no_pernyataan', $no_pernyataan)
			->first();

		if (!$data) {
			return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
		}

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	/**
	 * Generate nomor pernyataan unik: PJYYYYMMDDXXXX
	 */
	public function generateNoPernyataan(string $tanggal): string
	{
		$prefix = 'PJ' . date('Ymd', strtotime($tanggal));
		$last = PersetujuanPenolakanTindakan::where('no_pernyataan', 'like', $prefix . '%')
			->orderBy('no_pernyataan', 'desc')
			->first();

		if ($last) {
			$lastNum = (int) substr($last->no_pernyataan, -4);
			$nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
		} else {
			$nextNum = '0001';
		}

		return $prefix . $nextNum;
	}

	/**
	 * Simpan atau update informed consent.
	 */
	public function store(Request $request): JsonResponse
	{
		$request->validate([
			'no_rawat' => 'required|string',
			'tanggal' => 'required|date',
			'kd_dokter' => 'required|string',
			'penerima_informasi' => 'required|string',
			'hubungan_penerima_informasi' => 'required|string',
			'pernyataan' => 'required|in:Persetujuan,Penolakan,Belum Dikonfirmasi',
		]);

		DB::beginTransaction();
		try {
			$isNew = empty($request->no_pernyataan);
			$noPernyataan = $isNew
				? $this->generateNoPernyataan($request->tanggal)
				: $request->no_pernyataan;

			$data = [
				'no_pernyataan' => $noPernyataan,
				'no_rawat' => $request->no_rawat,
				'tanggal' => $request->tanggal,
				'diagnosa' => $request->diagnosa ?? '-',
				'diagnosa_konfirmasi' => $request->diagnosa_konfirmasi ? 'true' : 'false',
				'tindakan' => $request->tindakan ?? '-',
				'tindakan_konfirmasi' => $request->tindakan_konfirmasi ? 'true' : 'false',
				'indikasi_tindakan' => $request->indikasi_tindakan ?? '-',
				'indikasi_tindakan_konfirmasi' => $request->indikasi_tindakan_konfirmasi ? 'true' : 'false',
				'tata_cara' => $request->tata_cara ?? '-',
				'tata_cara_konfirmasi' => $request->tata_cara_konfirmasi ? 'true' : 'false',
				'tujuan' => $request->tujuan ?? '-',
				'tujuan_konfirmasi' => $request->tujuan_konfirmasi ? 'true' : 'false',
				'risiko' => $request->risiko ?? '-',
				'risiko_konfirmasi' => $request->risiko_konfirmasi ? 'true' : 'false',
				'komplikasi' => $request->komplikasi ?? '-',
				'komplikasi_konfirmasi' => $request->komplikasi_konfirmasi ? 'true' : 'false',
				'prognosis' => $request->prognosis ?? '-',
				'prognosis_konfirmasi' => $request->prognosis_konfirmasi ? 'true' : 'false',
				'alternatif_dan_risikonya' => $request->alternatif_dan_risikonya ?? '-',
				'alternatif_konfirmasi' => $request->alternatif_konfirmasi ? 'true' : 'false',
				'biaya' => (float) ($request->biaya ?? 0),
				'biaya_konfirmasi' => $request->biaya_konfirmasi ? 'true' : 'false',
				'lain_lain' => $request->lain_lain ?? '-',
				'lain_lain_konfirmasi' => $request->lain_lain_konfirmasi ? 'true' : 'false',
				'kd_dokter' => $request->kd_dokter,
				'nip' => $request->nip ?: (session()->get('pegawai')->nik ?? '-'),
				'penerima_informasi' => $request->penerima_informasi,
				'alasan_diwakilkan_penerima_informasi' => $request->alasan_diwakilkan_penerima_informasi ?? '-',
				'jk_penerima_informasi' => in_array($request->jk_penerima_informasi, ['L', 'P']) ? $request->jk_penerima_informasi : 'L',
				'tanggal_lahir_penerima_informasi' => $request->tanggal_lahir_penerima_informasi ?: '1990-01-01',
				'umur_penerima_informasi' => $request->umur_penerima_informasi ?? '-',
				'alamat_penerima_informasi' => $request->alamat_penerima_informasi ?? '-',
				'no_hp' => $request->no_hp ?? '-',
				'hubungan_penerima_informasi' => $request->hubungan_penerima_informasi,
				'pernyataan' => $request->pernyataan,
				'saksi_keluarga' => $request->saksi_keluarga ?? '-',
			];

			$record = PersetujuanPenolakanTindakan::updateOrCreate(
				['no_pernyataan' => $noPernyataan],
				$data
			);

			// Simpan Signature TTD Penerima Informasi (Canvas Base64)
			if ($request->has('ttd_penerima') && !empty($request->ttd_penerima) && str_contains($request->ttd_penerima, 'data:image')) {
				$pathPenerima = $this->saveSignatureImage($request->ttd_penerima, 'penerima_' . $noPernyataan);
				BuktiPersetujuanPenolakanTindakanPenerimaInformasi::updateOrCreate(
					['no_pernyataan' => $noPernyataan],
					['photo' => $pathPenerima]
				);
			}

			// Simpan Signature TTD Saksi Keluarga (Canvas Base64)
			if ($request->has('ttd_saksi') && !empty($request->ttd_saksi) && str_contains($request->ttd_saksi, 'data:image')) {
				$pathSaksi = $this->saveSignatureImage($request->ttd_saksi, 'saksi_' . $noPernyataan);
				BuktiPersetujuanPenolakanTindakanSaksiKeluarga::updateOrCreate(
					['no_pernyataan' => $noPernyataan],
					['photo' => $pathSaksi]
				);
			}

			$this->insertSql($record, $data);
			DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Data informed consent berhasil disimpan',
				'no_pernyataan' => $noPernyataan,
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'success' => false,
				'message' => 'Gagal menyimpan: ' . $e->getMessage(),
			], 500);
		}
	}

	/**
	 * Hapus informed consent.
	 */
	public function delete(Request $request): JsonResponse
	{
		$noPernyataan = $request->no_pernyataan;
		$record = PersetujuanPenolakanTindakan::where('no_pernyataan', $noPernyataan)->first();

		if (!$record) {
			return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
		}

		DB::beginTransaction();
		try {
			BuktiPersetujuanPenolakanTindakanPenerimaInformasi::where('no_pernyataan', $noPernyataan)->delete();
			BuktiPersetujuanPenolakanTindakanSaksiKeluarga::where('no_pernyataan', $noPernyataan)->delete();
			$record->delete();

			$this->deleteSql($record, ['no_pernyataan' => $noPernyataan]);
			DB::commit();

			return response()->json(['success' => true, 'message' => 'Informed consent berhasil dihapus']);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
		}
	}

	/**
	 * Cetak Formulir Informed Consent (Persetujuan / Penolakan Tindakan Medis).
	 */
	public function print(string $no_pernyataan)
	{
		$data = PersetujuanPenolakanTindakan::with([
			'dokter',
			'petugas',
			'buktiPenerimaInformasi',
			'buktiSaksiKeluarga',
			'regPeriksa.pasien',
			'regPeriksa.poliklinik',
		])->where('no_pernyataan', $no_pernyataan)->firstOrFail();

		$setting = Setting::first();

		return view('content.print.informedConsentPdf', compact('data', 'setting'));
	}

	/**
	 * Helper untuk menyimpan signature base64 ke file PNG
	 */
	private function saveSignatureImage(string $base64String, string $filename): string
	{
		$folder = public_path('storage/signatures');
		if (!File::exists($folder)) {
			File::makeDirectory($folder, 0755, true);
		}

		$image_parts = explode(";base64,", $base64String);
		$image_base64 = base64_decode($image_parts[1] ?? $base64String);
		$filePath = 'storage/signatures/' . $filename . '.png';

		file_put_contents(public_path($filePath), $image_base64);

		return $filePath;
	}

	/**
	 * Mengambil daftar template tindakan medis dari database.
	 */
	public function getTemplates(): JsonResponse
	{
		$templates = DB::table('template_persetujuan_penolakan_tindakan')
			->orderBy('tindakan', 'asc')
			->get();

		return response()->json([
			'success' => true,
			'data' => $templates,
		]);
	}

	/**
	 * Mengambil diagnosa pasien saat ini dari diagnosa_pasien dan SOAP pemeriksaan.
	 */
	public function getDiagnosaPasien(Request $request, ?string $no_rawat = null): JsonResponse
	{
		$no_rawat = $request->no_rawat ?? $no_rawat;
		if (!$no_rawat) {
			return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
		}

		// 1. Diagnosa ICD-10
		$diagnosaIcd = DB::table('diagnosa_pasien')
			->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
			->where('diagnosa_pasien.no_rawat', $no_rawat)
			->orderBy('diagnosa_pasien.prioritas', 'asc')
			->select('diagnosa_pasien.kd_penyakit', 'penyakit.nm_penyakit', 'diagnosa_pasien.status', 'diagnosa_pasien.prioritas')
			->get();

		// 2. Diagnosa/Asesmen SOAP
		$pemeriksaan = DB::table('pemeriksaan_ralan')->where('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'desc')->orderBy('jam_rawat', 'desc')->first();
		$penilaian = $pemeriksaan->penilaian ?? '';

		$diagnosaStr = '';
		if ($diagnosaIcd->isNotEmpty()) {
			$diagnosaStr = $diagnosaIcd->map(function ($d) {
				return "{$d->nm_penyakit} ({$d->kd_penyakit})";
			})->implode(', ');
		} elseif (!empty($penilaian)) {
			$diagnosaStr = trim($penilaian);
		}

		return response()->json([
			'success' => true,
			'diagnosa' => $diagnosaStr,
			'list_icd' => $diagnosaIcd,
			'penilaian' => $penilaian,
		]);
	}

	/**
	 * Menyimpan / memperbarui template persetujuan tindakan medis.
	 */
	public function saveTemplate(Request $request): JsonResponse
	{
		$request->validate([
			'tindakan' => 'required',
		]);

		$kode = $request->kode_template;
		if (empty($kode)) {
			$last = DB::table('template_persetujuan_penolakan_tindakan')->max('kode_template');
			$num = intval($last) + 1;
			$kode = sprintf('%02d', $num);
		}

		$data = [
			'kode_template' => $kode,
			'diagnosa' => $request->diagnosa ?? '-',
			'tindakan' => $request->tindakan ?? '-',
			'indikasi_tindakan' => $request->indikasi_tindakan ?? '-',
			'tata_cara' => $request->tata_cara ?? '-',
			'tujuan' => $request->tujuan ?? '-',
			'risiko' => $request->risiko ?? '-',
			'komplikasi' => $request->komplikasi ?? '-',
			'prognosis' => $request->prognosis ?? '-',
			'alternatif_dan_risikonya' => $request->alternatif_dan_risikonya ?? '-',
			'lain_lain' => $request->lain_lain ?? '-',
			'biaya' => floatval(str_replace(['.', ','], ['', '.'], $request->biaya ?? '0')),
		];

		DB::table('template_persetujuan_penolakan_tindakan')
			->updateOrInsert(['kode_template' => $kode], $data);

		return response()->json([
			'success' => true,
			'message' => 'Template tindakan medis berhasil disimpan',
			'data' => $data,
		]);
	}
}

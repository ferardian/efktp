<?php

namespace App\Http\Controllers;

use App\Models\BuktiPerencanaanPemulanganSaksiKeluarga;
use App\Models\PerencanaanPemulangan;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PerencanaanPemulanganController extends Controller
{
	use Track;

	public function show(string $no_rawat): JsonResponse
	{
		$data = PerencanaanPemulangan::with(['petugas', 'buktiSaksi', 'regPeriksa.pasien'])
			->where('no_rawat', $no_rawat)
			->first();

		return response()->json([
			'success' => true,
			'data' => $data,
		]);
	}

	public function store(Request $request): JsonResponse
	{
		$request->validate([
			'no_rawat' => 'required|string',
			'rencana_pulang' => 'required|date',
			'nama_pasien_keluarga' => 'required|string',
		]);

		DB::beginTransaction();
		try {
			$nip = $request->nip ?: (session()->get('pegawai')->nik ?? '-');
			if (empty($nip) || trim($nip) === '') {
				$nip = '-';
			}
			$this->ensurePetugasExists($nip);

			$data = [
				'no_rawat' => $request->no_rawat,
				'rencana_pulang' => $request->rencana_pulang,
				'alasan_masuk' => $request->alasan_masuk ?? '-',
				'diagnosa_medis' => $request->diagnosa_medis ?? '-',
				'pengaruh_ri_pasien_dan_keluarga' => in_array($request->pengaruh_ri_pasien_dan_keluarga, ['Tidak', 'Ya']) ? $request->pengaruh_ri_pasien_dan_keluarga : 'Tidak',
				'keterangan_pengaruh_ri_pasien_dan_keluarga' => $request->keterangan_pengaruh_ri_pasien_dan_keluarga ?? '-',
				'pengaruh_ri_pekerjaan_sekolah' => in_array($request->pengaruh_ri_pekerjaan_sekolah, ['Tidak', 'Ya']) ? $request->pengaruh_ri_pekerjaan_sekolah : 'Tidak',
				'keterangan_pengaruh_ri_pekerjaan_sekolah' => $request->keterangan_pengaruh_ri_pekerjaan_sekolah ?? '-',
				'pengaruh_ri_keuangan' => in_array($request->pengaruh_ri_keuangan, ['Tidak', 'Ya']) ? $request->pengaruh_ri_keuangan : 'Tidak',
				'keterangan_pengaruh_ri_keuangan' => $request->keterangan_pengaruh_ri_keuangan ?? '-',
				'antisipasi_masalah_saat_pulang' => in_array($request->antisipasi_masalah_saat_pulang, ['Tidak', 'Ya']) ? $request->antisipasi_masalah_saat_pulang : 'Tidak',
				'keterangan_antisipasi_masalah_saat_pulang' => $request->keterangan_antisipasi_masalah_saat_pulang ?? '-',
				'bantuan_diperlukan_dalam' => $request->bantuan_diperlukan_dalam ?? 'Minum Obat',
				'keterangan_bantuan_diperlukan_dalam' => $request->keterangan_bantuan_diperlukan_dalam ?? '-',
				'adakah_yang_membantu_keperluan' => in_array($request->adakah_yang_membantu_keperluan, ['Tidak', 'Ada']) ? $request->adakah_yang_membantu_keperluan : 'Ada',
				'keterangan_adakah_yang_membantu_keperluan' => $request->keterangan_adakah_yang_membantu_keperluan ?? '-',
				'pasien_tinggal_sendiri' => in_array($request->pasien_tinggal_sendiri, ['Tidak', 'Ya']) ? $request->pasien_tinggal_sendiri : 'Tidak',
				'keterangan_pasien_tinggal_sendiri' => $request->keterangan_pasien_tinggal_sendiri ?? '-',
				'pasien_menggunakan_peralatan_medis' => in_array($request->pasien_menggunakan_peralatan_medis, ['Tidak', 'Ya']) ? $request->pasien_menggunakan_peralatan_medis : 'Tidak',
				'keterangan_pasien_menggunakan_peralatan_medis' => $request->keterangan_pasien_menggunakan_peralatan_medis ?? '-',
				'pasien_memerlukan_alat_bantu' => in_array($request->pasien_memerlukan_alat_bantu, ['Tidak', 'Ya']) ? $request->pasien_memerlukan_alat_bantu : 'Tidak',
				'keterangan_pasien_memerlukan_alat_bantu' => $request->keterangan_pasien_memerlukan_alat_bantu ?? '-',
				'memerlukan_perawatan_khusus' => in_array($request->memerlukan_perawatan_khusus, ['Tidak', 'Ya']) ? $request->memerlukan_perawatan_khusus : 'Tidak',
				'keterangan_memerlukan_perawatan_khusus' => $request->keterangan_memerlukan_perawatan_khusus ?? '-',
				'bermasalah_memenuhi_kebutuhan' => in_array($request->bermasalah_memenuhi_kebutuhan, ['Tidak', 'Ya']) ? $request->bermasalah_memenuhi_kebutuhan : 'Tidak',
				'keterangan_bermasalah_memenuhi_kebutuhan' => $request->keterangan_bermasalah_memenuhi_kebutuhan ?? '-',
				'memiliki_nyeri_kronis' => in_array($request->memiliki_nyeri_kronis, ['Tidak', 'Ya']) ? $request->memiliki_nyeri_kronis : 'Tidak',
				'keterangan_memiliki_nyeri_kronis' => $request->keterangan_memiliki_nyeri_kronis ?? '-',
				'memerlukan_edukasi_kesehatan' => in_array($request->memerlukan_edukasi_kesehatan, ['Tidak', 'Ya']) ? $request->memerlukan_edukasi_kesehatan : 'Ya',
				'keterangan_memerlukan_edukasi_kesehatan' => $request->keterangan_memerlukan_edukasi_kesehatan ?? '-',
				'memerlukan_keterampilkan_khusus' => in_array($request->memerlukan_keterampilkan_khusus, ['Tidak', 'Ya']) ? $request->memerlukan_keterampilkan_khusus : 'Tidak',
				'keterangan_memerlukan_keterampilkan_khusus' => $request->keterangan_memerlukan_keterampilkan_khusus ?? '-',
				'nama_pasien_keluarga' => $request->nama_pasien_keluarga,
				'nip' => $nip,
			];

			$record = PerencanaanPemulangan::updateOrCreate(
				['no_rawat' => $request->no_rawat],
				$data
			);

			// Simpan Signature TTD Saksi Keluarga jika ada
			if ($request->has('ttd_saksi') && !empty($request->ttd_saksi) && str_contains($request->ttd_saksi, 'data:image')) {
				$pathSaksi = $this->saveSignatureImage($request->ttd_saksi, 'pulang_' . str_replace('/', '_', $request->no_rawat));
				BuktiPerencanaanPemulanganSaksiKeluarga::updateOrCreate(
					['no_rawat' => $request->no_rawat],
					['photo' => $pathSaksi]
				);
			}

			$this->insertSql($record, $data);
			DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Perencanaan Pemulangan (Discharge Planning) berhasil disimpan',
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
			BuktiPerencanaanPemulanganSaksiKeluarga::where('no_rawat', $request->no_rawat)->delete();
			$record = PerencanaanPemulangan::where('no_rawat', $request->no_rawat)->first();
			if ($record) {
				$record->delete();
				$this->deleteSql($record, ['no_rawat' => $request->no_rawat]);
			}
			DB::commit();

			return response()->json(['success' => true, 'message' => 'Perencanaan Pemulangan berhasil dihapus']);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
		}
	}

	public function print(string $no_rawat)
	{
		$data = PerencanaanPemulangan::with(['petugas', 'buktiSaksi', 'regPeriksa.pasien', 'regPeriksa.poliklinik'])
			->where('no_rawat', $no_rawat)
			->firstOrFail();

		$setting = Setting::first();

		return view('content.print.perencanaanPemulanganPdf', compact('data', 'setting'));
	}

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
	 * Otomatis sinkronisasi data petugas dari pegawai atau buat default
	 * agar tidak terjadi Integrity Constraint Violation (Foreign Key) pada perencanaan_pemulangan.
	 */
	private function ensurePetugasExists(string $nip): void
	{
		if (empty($nip)) {
			$nip = '-';
		}

		$exists = DB::table('petugas')->where('nip', $nip)->exists();
		if (!$exists) {
			$pegawai = DB::table('pegawai')->where('nik', $nip)->first();
			$kdJbtn = DB::table('jabatan')->value('kd_jbtn') ?: 'J001';

			if ($pegawai) {
				DB::table('petugas')->insert([
					'nip' => $pegawai->nik,
					'nama' => $pegawai->nama,
					'jk' => in_array($pegawai->jk ?? '', ['L', 'P']) ? $pegawai->jk : 'L',
					'tmp_lahir' => $pegawai->tmp_lahir ?: '-',
					'tgl_lahir' => $pegawai->tgl_lahir ?: date('Y-m-d'),
					'gol_darah' => '-',
					'agama' => '-',
					'stts_nikah' => '-',
					'alamat' => $pegawai->alamat ?: '-',
					'kd_jbtn' => $kdJbtn,
					'no_telp' => '-',
					'status' => '1'
				]);
			} else {
				DB::table('petugas')->insert([
					'nip' => $nip,
					'nama' => $nip === '-' ? '-' : 'Petugas ' . $nip,
					'jk' => 'L',
					'tmp_lahir' => '-',
					'tgl_lahir' => date('Y-m-d'),
					'gol_darah' => '-',
					'agama' => '-',
					'stts_nikah' => '-',
					'alamat' => '-',
					'kd_jbtn' => $kdJbtn,
					'no_telp' => '-',
					'status' => '1'
				]);
			}
		}
	}
}

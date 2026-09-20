<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanRalan;
use App\Models\PemeriksaanRanap;
use App\Models\PenilaianAwalKeperawatanRalan;
use App\Models\PenilaianAwalKeperawatanRanap;
use App\Models\RegPeriksa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TtvLookupController extends Controller
{
	/**
	 * Mengambil data TTV dan pemeriksaan fisik paling mutakhir untuk suatu nomor rawat.
	 */
	public function getLatest(Request $request, ?string $no_rawat = null): JsonResponse
	{
		$no_rawat = $request->no_rawat ?? $no_rawat;
		if (!$no_rawat) {
			return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
		}

		$history = $this->collectTtvHistory($no_rawat);
		$latest = $history->first();

		// Ambil juga info pasien & diagnosa awal dari registrasi
		$reg = RegPeriksa::with(['pasien', 'poliklinik', 'dokter'])->where('no_rawat', $no_rawat)->first();

		return response()->json([
			'success' => true,
			'data' => $latest,
			'registrasi' => $reg ? [
				'no_rawat' => $reg->no_rawat,
				'no_rkm_medis' => $reg->no_rkm_medis,
				'nm_pasien' => $reg->pasien->nm_pasien ?? '-',
				'tgl_lahir' => $reg->pasien->tgl_lahir ?? null,
				'jk' => $reg->pasien->jk ?? '-',
				'alamat' => $reg->pasien->alamat ?? '-',
				'kd_dokter' => $reg->kd_dokter,
				'nm_dokter' => $reg->dokter->nm_dokter ?? '-',
				'nm_poli' => $reg->poliklinik->nm_poli ?? '-',
			] : null,
		]);
	}

	/**
	 * Mengambil seluruh riwayat catatan TTV pada pasien (baik ralan maupun ranap).
	 */
	public function getHistory(Request $request, ?string $no_rawat = null): JsonResponse
	{
		$no_rawat = $request->no_rawat ?? $no_rawat;
		if (!$no_rawat) {
			return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
		}

		$history = $this->collectTtvHistory($no_rawat);

		return response()->json([
			'success' => true,
			'data' => $history->values(),
		]);
	}

	/**
	 * Helper untuk mengumpulkan dan mengurutkan catatan TTV dari berbagai tabel.
	 */
	private function collectTtvHistory(string $no_rawat)
	{
		$records = collect();

		// 1. Dari Pemeriksaan Ranap (CPPT Ranap)
		$ranap = PemeriksaanRanap::with('pegawai')
			->where('no_rawat', $no_rawat)
			->get();

		foreach ($ranap as $r) {
			$timestamp = strtotime($r->tgl_perawatan . ' ' . $r->jam_rawat);
			$records->push([
				'id' => 'ranap_' . $r->tgl_perawatan . '_' . $r->jam_rawat,
				'sumber' => 'CPPT Rawat Inap',
				'tgl' => $r->tgl_perawatan,
				'jam' => $r->jam_rawat,
				'timestamp' => $timestamp,
				'waktu_formatted' => date('d-m-Y H:i', $timestamp),
				'petugas' => $r->pegawai->nama ?? '-',
				'nip' => $r->nip,
				'td' => $r->tensi ?? '-',
				'tensi' => $r->tensi ?? '-',
				'nadi' => $r->nadi ?? '-',
				'rr' => $r->respirasi ?? '-',
				'respirasi' => $r->respirasi ?? '-',
				'suhu' => $r->suhu_tubuh ?? '-',
				'spo2' => $r->spo2 ?? '-',
				'tb' => $r->tinggi ?? '-',
				'tinggi' => $r->tinggi ?? '-',
				'bb' => $r->berat ?? '-',
				'berat' => $r->berat ?? '-',
				'gcs' => $r->gcs ?? '-',
				'kesadaran' => $r->kesadaran ?? '-',
				'keluhan' => $r->keluhan ?? '-',
				'pemeriksaan' => $r->pemeriksaan ?? '-',
				'penilaian' => $r->penilaian ?? '-',
				'alergi' => $r->alergi ?? '-',
			]);
		}

		// 2. Dari Pemeriksaan Ralan (CPPT Ralan)
		$ralan = PemeriksaanRalan::with('pegawai')
			->where('no_rawat', $no_rawat)
			->get();

		foreach ($ralan as $r) {
			$timestamp = strtotime($r->tgl_perawatan . ' ' . $r->jam_rawat);
			$records->push([
				'id' => 'ralan_' . $r->tgl_perawatan . '_' . $r->jam_rawat,
				'sumber' => 'CPPT Rawat Jalan',
				'tgl' => $r->tgl_perawatan,
				'jam' => $r->jam_rawat,
				'timestamp' => $timestamp,
				'waktu_formatted' => date('d-m-Y H:i', $timestamp),
				'petugas' => $r->pegawai->nama ?? '-',
				'nip' => $r->nip,
				'td' => $r->tensi ?? '-',
				'tensi' => $r->tensi ?? '-',
				'nadi' => $r->nadi ?? '-',
				'rr' => $r->respirasi ?? '-',
				'respirasi' => $r->respirasi ?? '-',
				'suhu' => $r->suhu_tubuh ?? '-',
				'spo2' => $r->spo2 ?? '-',
				'tb' => $r->tinggi ?? '-',
				'tinggi' => $r->tinggi ?? '-',
				'bb' => $r->berat ?? '-',
				'berat' => $r->berat ?? '-',
				'gcs' => $r->gcs ?? '-',
				'kesadaran' => $r->kesadaran ?? '-',
				'keluhan' => $r->keluhan ?? '-',
				'pemeriksaan' => $r->pemeriksaan ?? '-',
				'penilaian' => $r->penilaian ?? '-',
				'alergi' => $r->alergi ?? '-',
			]);
		}

		// 3. Dari Penilaian Awal Keperawatan Ranap
		$awalRanap = PenilaianAwalKeperawatanRanap::with('petugas')
			->where('no_rawat', $no_rawat)
			->get();

		foreach ($awalRanap as $r) {
			$timestamp = strtotime($r->tanggal ?? date('Y-m-d H:i:s'));
			$records->push([
				'id' => 'awal_ranap_' . $r->no_rawat,
				'sumber' => 'Asesmen Awal Ranap',
				'tgl' => date('Y-m-d', $timestamp),
				'jam' => date('H:i:s', $timestamp),
				'timestamp' => $timestamp,
				'waktu_formatted' => date('d-m-Y H:i', $timestamp),
				'petugas' => $r->petugas->nama ?? '-',
				'nip' => $r->nip,
				'td' => $r->td ?? '-',
				'tensi' => $r->td ?? '-',
				'nadi' => $r->nadi ?? '-',
				'rr' => $r->rr ?? '-',
				'respirasi' => $r->rr ?? '-',
				'suhu' => $r->suhu ?? '-',
				'spo2' => $r->spo2 ?? '-',
				'tb' => $r->tb ?? '-',
				'tinggi' => $r->tb ?? '-',
				'bb' => $r->bb ?? '-',
				'berat' => $r->bb ?? '-',
				'gcs' => $r->gcs ?? '-',
				'kesadaran' => $r->kesadaran ?? '-',
				'keluhan' => $r->keluhan_utama ?? '-',
				'pemeriksaan' => '-',
				'penilaian' => '-',
				'alergi' => $r->alergi ?? '-',
			]);
		}

		// Urutkan dari yang paling baru
		return $records->sortByDesc('timestamp')->values();
	}
}

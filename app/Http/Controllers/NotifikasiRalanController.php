<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiRalanController extends Controller
{
    /**
     * Mendapatkan daftar pasien rawat jalan hari ini yang telah selesai diperiksa
     * dan menunggu proses kasir / administrasi / farmasi.
     */
    public function getAntreanSelesai(Request $request)
    {
        if (!config('app.notifikasi_selesai_ralan', true)) {
            return response()->json([
                'enabled' => false,
                'count'   => 0,
                'data'    => [],
            ]);
        }

        $today = date('Y-m-d');

        $query = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->leftJoin('nota_jalan', 'reg_periksa.no_rawat', '=', 'nota_jalan.no_rawat')
            ->where('reg_periksa.status_lanjut', 'Ralan')
            ->where('reg_periksa.tgl_registrasi', $today)
            ->where('reg_periksa.status_bayar', 'Belum Bayar')
            ->whereNull('nota_jalan.no_nota')
            ->where(function ($q) {
                $q->where('reg_periksa.stts', 'Sudah')
                  ->orWhereExists(function ($sub) {
                      $sub->select(DB::raw(1))
                          ->from('pemeriksaan_ralan')
                          ->whereColumn('pemeriksaan_ralan.no_rawat', 'reg_periksa.no_rawat');
                  });
            });

        $totalCount = (clone $query)->count();

        $list = (clone $query)->select(
            'reg_periksa.no_rawat',
            'reg_periksa.no_rkm_medis',
            'reg_periksa.jam_reg',
            'reg_periksa.stts',
            'pasien.nm_pasien',
            'poliklinik.nm_poli',
            'dokter.nm_dokter',
            'penjab.png_jawab',
            DB::raw('(SELECT jam_rawat FROM pemeriksaan_ralan WHERE pemeriksaan_ralan.no_rawat = reg_periksa.no_rawat ORDER BY jam_rawat DESC LIMIT 1) as jam_selesai')
        )
        ->orderByRaw("COALESCE((SELECT jam_rawat FROM pemeriksaan_ralan WHERE pemeriksaan_ralan.no_rawat = reg_periksa.no_rawat ORDER BY jam_rawat DESC LIMIT 1), reg_periksa.jam_reg) DESC")
        ->limit(10)
        ->get();

        return response()->json([
            'enabled'   => true,
            'count'     => $totalCount,
            'data'      => $list,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}

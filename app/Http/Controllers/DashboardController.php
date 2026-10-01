<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $today = date('Y-m-d');

        // Statistik Bed Rawat Inap (Klinik Utama Rawat Inap)
        $kamarStats = DB::table('kamar')
            ->where('statusdata', '1')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $bedIsi = $kamarStats['ISI'] ?? 0;
        $bedKosong = $kamarStats['KOSONG'] ?? 0;
        $bedTotal = $bedIsi + $bedKosong;
        $borPercent = $bedTotal > 0 ? round(($bedIsi / $bedTotal) * 100, 1) : 0;

        // A. Ringkasan Antrean Poli Hari Ini
        $poliQueue = DB::table('reg_periksa')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->where('reg_periksa.tgl_registrasi', $today)
            ->select(
                'poliklinik.kd_poli',
                'poliklinik.nm_poli',
                DB::raw("SUM(CASE WHEN reg_periksa.stts = 'Belum' THEN 1 ELSE 0 END) as menunggu"),
                DB::raw("SUM(CASE WHEN reg_periksa.stts = 'Sudah' THEN 1 ELSE 0 END) as selesai"),
                DB::raw("SUM(CASE WHEN reg_periksa.stts = 'Dirujuk' THEN 1 ELSE 0 END) as dirujuk"),
                DB::raw("count(*) as total")
            )
            ->groupBy('poliklinik.kd_poli', 'poliklinik.nm_poli')
            ->orderByDesc('total')
            ->get();

        // D. 5 Pendaftaran Pasien Terakhir Hari Ini
        $recentPatients = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->leftJoin('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->where('reg_periksa.tgl_registrasi', $today)
            ->select(
                'reg_periksa.no_rawat',
                'reg_periksa.no_rkm_medis',
                'reg_periksa.no_reg',
                'reg_periksa.jam_reg',
                'reg_periksa.stts',
                'reg_periksa.status_bayar',
                'pasien.nm_pasien',
                'poliklinik.nm_poli',
                'dokter.nm_dokter'
            )
            ->orderByDesc('reg_periksa.jam_reg')
            ->orderByDesc('reg_periksa.no_reg')
            ->limit(5)
            ->get();

        return view('content.dashboard', [
            'data' => $setting,
            'bedStats' => [
                'isi' => $bedIsi,
                'kosong' => $bedKosong,
                'total' => $bedTotal,
                'bor' => $borPercent,
            ],
            'poliQueue' => $poliQueue,
            'recentPatients' => $recentPatients,
        ]);
    }

    public function getDemografi(Request $request)
    {
        $tgl1 = $request->tgl1 ? date('Y-m-d', strtotime($request->tgl1)) : date('Y-m-d');
        $tgl2 = $request->tgl2 ? date('Y-m-d', strtotime($request->tgl2)) : date('Y-m-d');

        // 1. Status Pendaftaran (Baru vs Lama)
        $statusDaftar = DB::table('reg_periksa')
            ->whereBetween('tgl_registrasi', [$tgl1, $tgl2])
            ->select('stts_daftar', DB::raw('count(*) as count'))
            ->groupBy('stts_daftar')
            ->pluck('count', 'stts_daftar');

        $baru = $statusDaftar['Baru'] ?? 0;
        $lama = $statusDaftar['Lama'] ?? 0;
        $totalPasien = $baru + $lama;

        // 2. Gender (Laki-laki vs Perempuan)
        $gender = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->whereBetween('reg_periksa.tgl_registrasi', [$tgl1, $tgl2])
            ->select('pasien.jk', DB::raw('count(*) as count'))
            ->groupBy('pasien.jk')
            ->pluck('count', 'pasien.jk');

        $laki = $gender['L'] ?? 0;
        $perempuan = $gender['P'] ?? 0;
        $totalGender = $laki + $perempuan;

        // 3. Kunjungan by Poliklinik
        $poli = DB::table('reg_periksa')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->whereBetween('reg_periksa.tgl_registrasi', [$tgl1, $tgl2])
            ->select('poliklinik.nm_poli', DB::raw('count(*) as count'))
            ->groupBy('poliklinik.nm_poli')
            ->orderByDesc('count')
            ->take(10)
            ->pluck('count', 'poliklinik.nm_poli');

        return response()->json([
            'status_daftar' => [
                'baru' => $baru,
                'lama' => $lama,
                'total' => $totalPasien,
                'persen_baru' => $totalPasien > 0 ? round(($baru / $totalPasien) * 100, 1) : 0,
                'persen_lama' => $totalPasien > 0 ? round(($lama / $totalPasien) * 100, 1) : 0,
            ],
            'gender' => [
                'laki' => $laki,
                'perempuan' => $perempuan,
                'total' => $totalGender,
                'persen_laki' => $totalGender > 0 ? round(($laki / $totalGender) * 100, 1) : 0,
                'persen_perempuan' => $totalGender > 0 ? round(($perempuan / $totalGender) * 100, 1) : 0,
            ],
            'poli' => [
                'labels' => array_keys($poli->toArray()),
                'counts' => array_values($poli->toArray()),
            ]
        ]);
    }
}

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
}

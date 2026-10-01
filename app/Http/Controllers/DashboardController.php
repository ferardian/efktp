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

        return view('content.dashboard', [
            'data' => $setting,
            'bedStats' => [
                'isi' => $bedIsi,
                'kosong' => $bedKosong,
                'total' => $bedTotal,
                'bor' => $borPercent,
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SetJamMinimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetJamMinimalController extends Controller
{
    public function index()
    {
        $setting = SetJamMinimal::getSetting();
        return view('content.setting.set_jam_ranap', compact('setting'));
    }

    public function get()
    {
        $setting = SetJamMinimal::getSetting();
        return response()->json($setting);
    }

    public function update(Request $request)
    {
        $request->validate([
            'lamajam'  => 'required|numeric|min:1',
            'hariawal' => 'required|in:Yes,No',
        ]);

        try {
            $data = [
                'lamajam'                   => (int) $request->lamajam,
                'hariawal'                  => $request->hariawal,
                'feeperujuk'                => (float) ($request->feeperujuk ?? 0),
                'diagnosaakhir'             => $request->diagnosaakhir ?? 'Yes',
                'bayi'                      => (int) ($request->bayi ?? 0),
                'aktifkan_hapus_data_salah' => $request->aktifkan_hapus_data_salah ?? 'No',
                'kamar_inap_kasir_ralan'    => $request->kamar_inap_kasir_ralan ?? 'No',
                'ubah_status_kamar'         => $request->ubah_status_kamar ?? 'Yes',
            ];

            $existing = DB::table('set_jam_minimal')->first();
            if ($existing) {
                DB::table('set_jam_minimal')->update($data);
            } else {
                DB::table('set_jam_minimal')->insert($data);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengaturan jam minimal rawat inap berhasil diperbarui',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }
}

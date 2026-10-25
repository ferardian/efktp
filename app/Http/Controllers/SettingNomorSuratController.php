<?php

namespace App\Http\Controllers;

use App\Models\SettingNomorSurat;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;

class SettingNomorSuratController extends Controller
{
    /**
     * Tampilkan halaman pengaturan nomor surat
     */
    public function index()
    {
        $setting = SettingNomorSurat::getSetting();

        $previewSakit = NomorSuratService::preview(
            'sakit',
            $setting->format_sakit,
            $setting->start_num_sakit,
            $setting->reset_periode
        );

        $previewSehat = NomorSuratService::preview(
            'sehat',
            $setting->format_sehat,
            $setting->start_num_sehat,
            $setting->reset_periode
        );

        return view('content.setting.set_nomor_surat', compact('setting', 'previewSakit', 'previewSehat'));
    }

    /**
     * Ambil data setting nomor surat (JSON)
     */
    public function get()
    {
        $setting = SettingNomorSurat::getSetting();
        return response()->json($setting);
    }

    /**
     * Simpan pembaruan setting nomor surat
     */
    public function update(Request $request)
    {
        $request->validate([
            'format_sakit'    => 'required|string|max:150',
            'format_sehat'    => 'required|string|max:150',
            'start_num_sakit' => 'nullable|integer|min:0',
            'start_num_sehat' => 'nullable|integer|min:0',
            'reset_periode'   => 'required|in:never,yearly,monthly',
        ], [
            'format_sakit.required' => 'Format surat sakit wajib diisi',
            'format_sehat.required' => 'Format surat sehat wajib diisi',
            'reset_periode.in'      => 'Pilihan periode reset tidak valid',
        ]);

        try {
            $setting = SettingNomorSurat::first();

            $data = [
                'format_sakit'    => trim($request->format_sakit),
                'format_sehat'    => trim($request->format_sehat),
                'start_num_sakit' => (int) ($request->start_num_sakit ?? 0),
                'start_num_sehat' => (int) ($request->start_num_sehat ?? 0),
                'reset_periode'   => $request->reset_periode,
            ];

            if ($setting) {
                $setting->update($data);
            } else {
                SettingNomorSurat::create($data);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengaturan format penomoran surat berhasil disimpan!',
                'data'    => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint simulasi live preview
     */
    public function preview(Request $request)
    {
        $jenis        = $request->jenis ?: 'sakit';
        $template     = $request->format ?: '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}';
        $startNum     = (int) ($request->start_num ?? 0);
        $resetPeriode = $request->reset_periode ?: 'monthly';
        $tanggal      = $request->tanggal;

        $result = NomorSuratService::preview($jenis, $template, $startNum, $resetPeriode, $tanggal);

        return response()->json($result);
    }
}

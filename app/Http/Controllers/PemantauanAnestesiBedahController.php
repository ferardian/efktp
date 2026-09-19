<?php

namespace App\Http\Controllers;

use App\Models\BuktiAnestesiSignin;
use App\Models\Dokter;
use App\Models\Pegawai;
use App\Models\PemantauanFisiologiAnestesi;
use App\Models\RegPeriksa;
use App\Models\Setting;
use App\Models\VerifikasiPemantauanAnestesi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PemantauanAnestesiBedahController extends Controller
{
    public function get(string $no_rawat): JsonResponse
    {
        $no_rawat = urldecode($no_rawat);

        $signin = BuktiAnestesiSignin::with(['dokterBedah', 'dokterAnestesi', 'petugasOk'])
            ->where('no_rawat', $no_rawat)
            ->latest('tanggal')
            ->first();

        $pemantauan = PemantauanFisiologiAnestesi::where('no_rawat', $no_rawat)
            ->orderBy('id', 'asc')
            ->get();

        $verifikasi = VerifikasiPemantauanAnestesi::with('dokter')
            ->where('no_rawat', $no_rawat)
            ->latest('tanggal_verifikasi')
            ->first();

        return response()->json([
            'success' => true,
            'signin' => $signin,
            'pemantauan' => $pemantauan,
            'verifikasi' => $verifikasi,
        ]);
    }

    public function storeSignin(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'tanggal' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $tanggal = date('Y-m-d H:i:s', strtotime($request->tanggal));

            $data = [
                'no_rawat' => $request->no_rawat,
                'tanggal' => $tanggal,
                'tindakan' => $request->tindakan ?? '-',
                'diagnosa' => $request->diagnosa ?? '-',
                'kd_dokter_bedah' => $request->kd_dokter_bedah ?? '-',
                'kd_dokter_anestesi' => $request->kd_dokter_anestesi ?? '-',
                'identitas_sesuai' => $request->identitas_sesuai === 'Ya' ? 'Ya' : 'Tidak',
                'informed_consent' => $request->informed_consent === 'Ya' ? 'Ya' : 'Tidak',
                'rencana_anestesi' => $request->rencana_anestesi ?? '-',
                'obat_anestesi' => $request->obat_anestesi ?? '-',
                'dosis' => $request->dosis ?? '-',
                'kesiapan_alat_obat' => $request->kesiapan_alat_obat === 'Lengkap' ? 'Lengkap' : 'Tidak Lengkap',
                'alergi' => $request->alergi ?? '-',
                'catatan' => $request->catatan ?? '-',
                'nip_perawat_ok' => $request->nip_perawat_ok ?: (session()->get('pegawai')->nik ?? '-'),
            ];

            // Hapus data lama untuk no_rawat ini jika ada tanggal lama
            if ($request->filled('tanggal_lama')) {
                BuktiAnestesiSignin::where('no_rawat', $request->no_rawat)
                    ->where('tanggal', $request->tanggal_lama)
                    ->delete();
            }

            $record = BuktiAnestesiSignin::updateOrCreate(
                ['no_rawat' => $request->no_rawat, 'tanggal' => $tanggal],
                $data
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti Sign In & Data Anestesi berhasil disimpan',
                'data' => $record,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan Sign In: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storePemantauan(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'tanggal_verifikasi' => 'required|date',
            'kd_dokter' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $no_rawat = $request->no_rawat;
            $tanggal = $request->tanggal_verifikasi;

            // Simpan baris tabel pemantauan
            if ($request->has('pemantauan') && is_array($request->pemantauan)) {
                PemantauanFisiologiAnestesi::where('no_rawat', $no_rawat)->delete();

                foreach ($request->pemantauan as $row) {
                    if (!empty($row['waktu_menit'])) {
                        PemantauanFisiologiAnestesi::create([
                            'no_rawat' => $no_rawat,
                            'tanggal' => $tanggal,
                            'waktu_menit' => $row['waktu_menit'],
                            'jam' => $row['jam'] ?? date('H:i'),
                            'keluhan' => $row['keluhan'] ?? '-',
                            'td' => $row['td'] ?? '-',
                            'nadi' => $row['nadi'] ?? '-',
                            'rr' => $row['rr'] ?? '-',
                            'suhu' => $row['suhu'] ?? '-',
                            'spo2' => $row['spo2'] ?? '-',
                            'keterangan' => $row['keterangan'] ?? '-',
                        ]);
                    }
                }
            }

            // Simpan Tanda Tangan Verifikasi Dokter
            $verifikasiData = [
                'no_rawat' => $no_rawat,
                'tanggal_verifikasi' => $tanggal,
                'jam_verifikasi' => $request->jam_verifikasi ?: date('H:i'),
                'kd_dokter' => $request->kd_dokter,
            ];

            if ($request->filled('tanda_tangan') && str_contains($request->tanda_tangan, 'data:image')) {
                $path = $this->saveSignatureImage($request->tanda_tangan, 'verif_anestesi_' . str_replace('/', '_', $no_rawat));
                $verifikasiData['tanda_tangan'] = $path;
            }

            $verif = VerifikasiPemantauanAnestesi::updateOrCreate(
                ['no_rawat' => $no_rawat, 'tanggal_verifikasi' => $tanggal],
                $verifikasiData
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tabel Pemantauan Fisiologi & Verifikasi Dokter berhasil disimpan',
                'verifikasi' => $verif,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pemantauan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $no_rawat = $request->no_rawat;
            BuktiAnestesiSignin::where('no_rawat', $no_rawat)->delete();
            PemantauanFisiologiAnestesi::where('no_rawat', $no_rawat)->delete();
            VerifikasiPemantauanAnestesi::where('no_rawat', $no_rawat)->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Seluruh data pemantauan anestesi berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function print(string $no_rawat)
    {
        $no_rawat = urldecode($no_rawat);

        $regPeriksa = RegPeriksa::with(['pasien', 'poliklinik'])
            ->where('no_rawat', $no_rawat)
            ->firstOrFail();

        $signin = BuktiAnestesiSignin::with(['dokterBedah', 'dokterAnestesi', 'petugasOk'])
            ->where('no_rawat', $no_rawat)
            ->latest('tanggal')
            ->first();

        $pemantauan = PemantauanFisiologiAnestesi::where('no_rawat', $no_rawat)
            ->orderBy('id', 'asc')
            ->get();

        $verifikasi = VerifikasiPemantauanAnestesi::with('dokter')
            ->where('no_rawat', $no_rawat)
            ->latest('tanggal_verifikasi')
            ->first();

        $setting = Setting::first();

        return view('content.print.pemantauanAnestesiBedahPdf', compact('regPeriksa', 'signin', 'pemantauan', 'verifikasi', 'setting'));
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
}

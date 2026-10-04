<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\PersetujuanPenolakanRujukan;
use App\Models\Petugas;
use App\Models\RegPeriksa;
use App\Models\Rujuk;
use App\Models\Setting;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PersetujuanPenolakanRujukanController extends Controller
{
    use Track;

    /**
     * Mengambil daftar persetujuan/penolakan rujukan untuk nomor rawat tertentu.
     */
    public function getByNoRawat(Request $request, ?string $no_rawat = null): JsonResponse
    {
        $no_rawat = $request->no_rawat ?? $no_rawat;
        if (!$no_rawat) {
            return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
        }

        $data = PersetujuanPenolakanRujukan::with([
            'dokter',
            'petugas',
            'regPeriksa.pasien',
            'regPeriksa.poliklinik',
        ])
            ->where('no_rawat', $no_rawat)
            ->orderBy('tanggal', 'desc')
            ->orderBy('no_surat', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Mengambil detail 1 dokumen persetujuan/penolakan rujukan.
     */
    public function show(string $no_surat): JsonResponse
    {
        $data = PersetujuanPenolakanRujukan::with([
            'dokter',
            'petugas',
            'regPeriksa.pasien',
            'regPeriksa.poliklinik',
        ])
            ->where('no_surat', $no_surat)
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
     * Mengambil referensi data rujukan dan diagnosa pasien saat ini.
     */
    public function getReferensiRujukan(Request $request, ?string $no_rawat = null): JsonResponse
    {
        $no_rawat = $request->no_rawat ?? $no_rawat;
        if (!$no_rawat) {
            return response()->json(['success' => false, 'message' => 'No rawat diperlukan'], 400);
        }

        // 1. Ambil data dari tabel rujuk (Khanza)
        $rujukan = Rujuk::where('no_rawat', $no_rawat)->first();

        // 2. Ambil diagnosa ICD-10
        $diagnosaIcd = DB::table('diagnosa_pasien')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->where('diagnosa_pasien.no_rawat', $no_rawat)
            ->orderBy('diagnosa_pasien.prioritas', 'asc')
            ->select('diagnosa_pasien.kd_penyakit', 'penyakit.nm_penyakit', 'diagnosa_pasien.status', 'diagnosa_pasien.prioritas')
            ->get();

        // 3. SOAP pemeriksaan ralan/ranap
        $pemeriksaan = DB::table('pemeriksaan_ralan')->where('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'desc')->orderBy('jam_rawat', 'desc')->first();
        if (!$pemeriksaan) {
            $pemeriksaan = DB::table('pemeriksaan_ranap')->where('no_rawat', $no_rawat)->orderBy('tgl_perawatan', 'desc')->orderBy('jam_rawat', 'desc')->first();
        }

        $diagnosaStr = '';
        if ($diagnosaIcd->isNotEmpty()) {
            $diagnosaStr = $diagnosaIcd->map(function ($d) {
                return "{$d->nm_penyakit} ({$d->kd_penyakit})";
            })->implode(', ');
        } elseif (!empty($pemeriksaan->penilaian)) {
            $diagnosaStr = trim($pemeriksaan->penilaian);
        } elseif (!empty($rujukan->keterangan_diagnosa)) {
            $diagnosaStr = trim($rujukan->keterangan_diagnosa);
        }

        return response()->json([
            'success' => true,
            'rujukan' => $rujukan,
            'diagnosa' => $diagnosaStr,
            'tindakan_stabilisasi' => $pemeriksaan->instruksi ?? '',
        ]);
    }

    /**
     * Generate nomor surat unik: PRYYYYMMDDXXXX
     */
    public function generateNoSurat(string $tanggal): string
    {
        $prefix = 'PR' . date('Ymd', strtotime($tanggal));
        $last = PersetujuanPenolakanRujukan::where('no_surat', 'like', $prefix . '%')
            ->orderBy('no_surat', 'desc')
            ->first();

        if ($last) {
            $lastNum = (int) substr($last->no_surat, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }

    /**
     * Simpan atau update persetujuan/penolakan rujukan.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'no_rawat' => 'required|string',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:Persetujuan,Penolakan',
            'nama_pj' => 'required|string',
            'hubungan' => 'required|string',
            'faskes_tujuan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $isNew = empty($request->no_surat);
            $noSurat = $isNew
                ? $this->generateNoSurat($request->tanggal)
                : $request->no_surat;

            // Validasi & fallback kd_dokter
            $kdDokter = $request->kd_dokter;
            if (empty($kdDokter) || !DB::table('dokter')->where('kd_dokter', $kdDokter)->exists()) {
                $kdDokter = DB::table('dokter')->where('kd_dokter', '!=', '-')->value('kd_dokter')
                    ?? DB::table('dokter')->value('kd_dokter')
                    ?? '-';
            }

            // Validasi & fallback nip petugas
            $nip = $request->nip ?: (session()->get('pegawai')->nik ?? session()->get('nik') ?? '-');
            if (!DB::table('petugas')->where('nip', $nip)->exists()) {
                if (DB::table('petugas')->where('nip', '-')->exists()) {
                    $nip = '-';
                } else {
                    $nip = DB::table('petugas')->value('nip') ?? '-';
                }
            }

            $data = [
                'no_surat' => $noSurat,
                'no_rawat' => $request->no_rawat,
                'tanggal' => $request->tanggal,
                'jenis' => $request->jenis,
                'diagnosa' => $request->diagnosa ?? '-',
                'alasan_rujuk' => $request->alasan_rujuk ?? '-',
                'faskes_tujuan' => $request->faskes_tujuan,
                'bagian_tujuan' => $request->bagian_tujuan ?? '-',
                'transportasi' => $request->transportasi ?? 'Ambulans',
                'pendamping' => $request->pendamping ?? 'Keluarga & Petugas',
                'tindakan_stabilisasi' => $request->tindakan_stabilisasi ?? '-',
                'risiko_rujuk' => $request->risiko_rujuk ?? '-',
                'risiko_tidak_rujuk' => $request->risiko_tidak_rujuk ?? '-',
                'alasan_menolak' => $request->jenis === 'Penolakan' ? ($request->alasan_menolak ?? '-') : null,
                'nama_pj' => $request->nama_pj,
                'hubungan' => $request->hubungan,
                'jk_pj' => in_array($request->jk_pj, ['L', 'P']) ? $request->jk_pj : 'L',
                'umur_pj' => $request->umur_pj ?? '-',
                'alamat_pj' => $request->alamat_pj ?? '-',
                'no_hp_pj' => $request->no_hp_pj ?? '-',
                'kd_dokter' => $kdDokter,
                'nip' => $nip,
                'nama_saksi' => $request->nama_saksi ?? '-',
            ];

            // Simpan Signature TTD Penerima (Canvas Base64)
            if ($request->has('ttd_penerima') && !empty($request->ttd_penerima) && str_contains($request->ttd_penerima, 'data:image')) {
                $data['ttd_penerima'] = $this->saveSignatureImage($request->ttd_penerima, 'rujuk_penerima_' . $noSurat);
            }

            // Simpan Signature TTD Saksi (Canvas Base64)
            if ($request->has('ttd_saksi') && !empty($request->ttd_saksi) && str_contains($request->ttd_saksi, 'data:image')) {
                $data['ttd_saksi'] = $this->saveSignatureImage($request->ttd_saksi, 'rujuk_saksi_' . $noSurat);
            }

            // Simpan Signature TTD Dokter / Petugas (Canvas Base64)
            if ($request->has('ttd_dokter') && !empty($request->ttd_dokter) && str_contains($request->ttd_dokter, 'data:image')) {
                $data['ttd_dokter'] = $this->saveSignatureImage($request->ttd_dokter, 'rujuk_dokter_' . $noSurat);
            }

            $record = PersetujuanPenolakanRujukan::updateOrCreate(
                ['no_surat' => $noSurat],
                $data
            );

            $this->insertSql($record, $data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data persetujuan/penolakan rujukan berhasil disimpan',
                'no_surat' => $noSurat,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data rujukan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus berkas persetujuan/penolakan rujukan.
     */
    public function destroy(Request $request): JsonResponse
    {
        $noSurat = $request->no_surat;
        $record = PersetujuanPenolakanRujukan::where('no_surat', $noSurat)->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            // Hapus file signatures jika ada
            if ($record->ttd_penerima && File::exists(public_path($record->ttd_penerima))) {
                File::delete(public_path($record->ttd_penerima));
            }
            if ($record->ttd_saksi && File::exists(public_path($record->ttd_saksi))) {
                File::delete(public_path($record->ttd_saksi));
            }
            if ($record->ttd_dokter && File::exists(public_path($record->ttd_dokter))) {
                File::delete(public_path($record->ttd_dokter));
            }

            $record->delete();
            $this->deleteSql($record, ['no_surat' => $noSurat]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Data formulir rujukan berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cetak PDF Formulir Persetujuan / Penolakan Rujukan & Edukasi Rujukan.
     */
    public function print(string $no_surat)
    {
        $data = PersetujuanPenolakanRujukan::with([
            'dokter',
            'petugas',
            'regPeriksa.pasien',
            'regPeriksa.poliklinik',
        ])->where('no_surat', $no_surat)->firstOrFail();

        $setting = Setting::first();

        return view('content.print.persetujuanRujukanPdf', compact('data', 'setting'));
    }

    /**
     * Helper simpan tanda tangan canvas base64.
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
}

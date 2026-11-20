<?php

namespace App\Http\Controllers;

use App\Models\Bangsal;
use App\Models\Kamar;
use App\Models\KamarInap;
use App\Traits\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class KamarController extends Controller
{
    use Track;

    public function index()
    {
        $bangsal = Bangsal::where('status', '1')->orderBy('nm_bangsal')->get();
        $kelasList = ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas Utama', 'Kelas VIP', 'Kelas VVIP'];
        $statusList = ['KOSONG', 'ISI', 'DIBERSIHKAN', 'DIBOOKING'];

        $ubahStatusKamar = DB::table('set_jam_minimal')->value('ubah_status_kamar') ?? 'Yes';

        return view('content.kamar.kamar', compact('bangsal', 'kelasList', 'statusList', 'ubahStatusKamar'));
    }

    /**
     * DataTables endpoint untuk list kamar/bed (Adopsi DlgKamar.tampil())
     */
    public function data(Request $request)
    {
        $statusdata = $request->statusdata ?? '1';

        $query = DB::table('kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->leftJoin('kamar_inap', function ($join) {
                $join->on('kamar.kd_kamar', '=', 'kamar_inap.kd_kamar')
                     ->where('kamar_inap.stts_pulang', '=', '-');
            })
            ->leftJoin('reg_periksa', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->leftJoin('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->select([
                'kamar.kd_kamar',
                'kamar.kd_bangsal',
                'bangsal.nm_bangsal',
                'kamar.kelas',
                'kamar.trf_kamar',
                'kamar.status',
                'kamar.statusdata',
                'kamar_inap.no_rawat',
                'kamar_inap.tgl_masuk',
                'kamar_inap.jam_masuk',
                'pasien.nm_pasien',
                'reg_periksa.no_rkm_medis'
            ])
            ->where('kamar.statusdata', $statusdata);

        // Filter Bangsal
        if (!empty($request->kd_bangsal)) {
            $query->where('kamar.kd_bangsal', $request->kd_bangsal);
        }

        // Filter Status Kamar (KOSONG, ISI, DIBERSIHKAN, DIBOOKING)
        if (!empty($request->status)) {
            $query->where('kamar.status', $request->status);
        }

        // Filter Kelas Kamar
        if (!empty($request->kelas)) {
            $query->where('kamar.kelas', $request->kelas);
        }

        // Search Filter
        if (!empty($request->search_custom)) {
            $kw = $request->search_custom;
            $query->where(function ($q) use ($kw) {
                $q->where('kamar.kd_kamar', 'like', "%{$kw}%")
                  ->orWhere('bangsal.nm_bangsal', 'like', "%{$kw}%")
                  ->orWhere('kamar.kelas', 'like', "%{$kw}%")
                  ->orWhere('kamar.status', 'like', "%{$kw}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$kw}%")
                  ->orWhere('kamar_inap.no_rawat', 'like', "%{$kw}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('trf_kamar', function ($row) {
                return (float) $row->trf_kamar;
            })
            ->make(true);
    }

    /**
     * Ringkasan ketersediaan bed kamar inap
     */
    public function stats()
    {
        $stats = DB::table('kamar')
            ->selectRaw("
                COUNT(CASE WHEN statusdata = '1' THEN 1 END) as total,
                COUNT(CASE WHEN statusdata = '1' AND status = 'KOSONG' THEN 1 END) as kosong,
                COUNT(CASE WHEN statusdata = '1' AND status = 'ISI' THEN 1 END) as isi,
                COUNT(CASE WHEN statusdata = '1' AND status = 'DIBERSIHKAN' THEN 1 END) as dibersihkan,
                COUNT(CASE WHEN statusdata = '1' AND status = 'DIBOOKING' THEN 1 END) as dibooking,
                COUNT(CASE WHEN statusdata = '0' THEN 1 END) as terhapus
            ")
            ->first();

        return response()->json($stats);
    }

    /**
     * Generate auto kode kamar / nomer bed (Adopsi Valid.autoNomer(" kamar ","K",7,TKd))
     */
    public function autoKode(Request $request)
    {
        $kdBangsal = $request->kd_bangsal;

        // Cek apakah ada format penomoran berbasis kode bangsal (misal: K1.01, VIP.01)
        if (!empty($kdBangsal)) {
            $latest = DB::table('kamar')
                ->where('kd_bangsal', $kdBangsal)
                ->where('kd_kamar', 'like', "{$kdBangsal}.%")
                ->orderBy('kd_kamar', 'desc')
                ->first();

            if ($latest) {
                $parts = explode('.', $latest->kd_kamar);
                $num = isset($parts[1]) ? intval($parts[1]) + 1 : 1;
                $candidate = $kdBangsal . '.' . str_pad($num, 2, '0', STR_PAD_LEFT);
                if (!DB::table('kamar')->where('kd_kamar', $candidate)->exists()) {
                    return response()->json(['kd_kamar' => $candidate]);
                }
            }
        }

        // Format standar Khanza (K000001 ... K999999)
        $latestStandard = DB::table('kamar')
            ->where('kd_kamar', 'like', 'K%')
            ->whereRaw("kd_kamar REGEXP '^K[0-9]{6}$'")
            ->orderBy('kd_kamar', 'desc')
            ->first();

        if ($latestStandard) {
            $num = intval(substr($latestStandard->kd_kamar, 1)) + 1;
            $code = 'K' . str_pad($num, 6, '0', STR_PAD_LEFT);
        } else {
            // Hitung total kamar sebagai basis awal
            $count = DB::table('kamar')->count() + 1;
            $code = 'K' . str_pad($count, 6, '0', STR_PAD_LEFT);
        }

        // Pastikan unik
        while (DB::table('kamar')->where('kd_kamar', $code)->exists()) {
            $num = intval(substr($code, 1)) + 1;
            $code = 'K' . str_pad($num, 6, '0', STR_PAD_LEFT);
        }

        return response()->json(['kd_kamar' => $code]);
    }

    /**
     * Simpan kamar baru (Adopsi DlgKamar.BtnSimpanActionPerformed)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kd_kamar'   => 'required|string|max:15',
            'kd_bangsal' => 'required|string|exists:bangsal,kd_bangsal',
            'trf_kamar'  => 'required|numeric|min:0',
            'status'     => 'required|in:KOSONG,ISI,DIBERSIHKAN,DIBOOKING',
            'kelas'      => 'required|string|max:20',
        ], [
            'kd_kamar.required'   => 'Nomer Bed / Kode Kamar wajib diisi.',
            'kd_bangsal.required' => 'Bangsal / Ruangan wajib dipilih.',
            'trf_kamar.required'  => 'Tarif Kamar wajib diisi.',
            'trf_kamar.numeric'   => 'Tarif Kamar harus berupa angka.',
            'status.required'     => 'Status Kamar wajib dipilih.',
            'kelas.required'      => 'Kelas Kamar wajib dipilih.',
        ]);

        $kdKamar = trim($request->kd_kamar);

        // Cek duplikasi
        $existing = DB::table('kamar')->where('kd_kamar', $kdKamar)->first();
        if ($existing) {
            if ($existing->statusdata === '1') {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Nomer Bed / Kode Kamar \"{$kdKamar}\" sudah terdaftar!"
                ], 422);
            } else {
                // Reaktivasi kamar yang sebelumnya berstatus terhapus
                DB::table('kamar')->where('kd_kamar', $kdKamar)->update([
                    'kd_bangsal' => $request->kd_bangsal,
                    'trf_kamar'  => $request->trf_kamar,
                    'status'     => $request->status,
                    'kelas'      => $request->kelas,
                    'statusdata' => '1',
                ]);

                $this->updateSql(new Kamar(), ['statusdata' => '1', 'trf_kamar' => $request->trf_kamar]);

                return response()->json([
                    'status'  => 'success',
                    'message' => "Kamar \"{$kdKamar}\" berhasil diaktifkan kembali dan diperbarui."
                ]);
            }
        }

        $data = [
            'kd_kamar'   => $kdKamar,
            'kd_bangsal' => $request->kd_bangsal,
            'trf_kamar'  => $request->trf_kamar,
            'status'     => $request->status,
            'kelas'      => $request->kelas,
            'statusdata' => '1',
        ];

        DB::table('kamar')->insert($data);
        $this->insertSql(new Kamar(), $data);

        return response()->json([
            'status'  => 'success',
            'message' => "Kamar / Bed \"{$kdKamar}\" berhasil ditambahkan."
        ]);
    }

    /**
     * Update kamar (Adopsi DlgKamar.BtnEditActionPerformed)
     */
    public function update(Request $request, $kd_kamar)
    {
        $kamar = DB::table('kamar')->where('kd_kamar', $kd_kamar)->first();
        if (!$kamar) {
            return response()->json(['status' => 'error', 'message' => 'Data kamar tidak ditemukan'], 404);
        }

        $request->validate([
            'kd_bangsal' => 'required|string|exists:bangsal,kd_bangsal',
            'trf_kamar'  => 'required|numeric|min:0',
            'status'     => 'required|in:KOSONG,ISI,DIBERSIHKAN,DIBOOKING',
            'kelas'      => 'required|string|max:20',
        ]);

        $newKdKamar = trim($request->new_kd_kamar ?: $kd_kamar);

        // Jika kode kamar diubah, cek duplikasi
        if ($newKdKamar !== $kd_kamar) {
            $isExists = DB::table('kamar')->where('kd_kamar', $newKdKamar)->exists();
            if ($isExists) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Nomer Bed / Kode Kamar baru \"{$newKdKamar}\" sudah digunakan!"
                ], 422);
            }
        }

        // Cek proteksi: jika ada pasien aktif yang sedang dirawat di bed ini dan status diubah jadi KOSONG
        if ($request->status === 'KOSONG') {
            $activePatient = DB::table('kamar_inap')
                ->where('kd_kamar', $kd_kamar)
                ->where('stts_pulang', '-')
                ->first();

            if ($activePatient) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Kamar \"{$kd_kamar}\" sedang ditempati pasien aktif (No. Rawat: {$activePatient->no_rawat}). Pulangkan atau pindahkan pasien terlebih dahulu dari menu Rawat Inap."
                ], 422);
            }
        }

        $updateData = [
            'kd_kamar'   => $newKdKamar,
            'kd_bangsal' => $request->kd_bangsal,
            'trf_kamar'  => $request->trf_kamar,
            'status'     => $request->status,
            'kelas'      => $request->kelas,
        ];

        DB::table('kamar')->where('kd_kamar', $kd_kamar)->update($updateData);
        $this->updateSql(new Kamar(), $updateData);

        return response()->json([
            'status'  => 'success',
            'message' => "Data Kamar / Bed \"{$newKdKamar}\" berhasil diperbarui."
        ]);
    }

    /**
     * Quick status change (Ubah Status Kamar Langsung)
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'kd_kamar' => 'required|string|exists:kamar,kd_kamar',
            'status'   => 'required|in:KOSONG,ISI,DIBERSIHKAN,DIBOOKING',
        ]);

        $kdKamar = $request->kd_kamar;
        $newStatus = $request->status;

        // Cek setting ubah_status_kamar
        $ubahSetting = DB::table('set_jam_minimal')->value('ubah_status_kamar') ?? 'Yes';
        if ($ubahSetting === 'No' && session('role') !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pengubahan status kamar dinonaktifkan oleh Administrator pada Pengaturan Jam Minimal.'
            ], 403);
        }

        // Jika diubah jadi KOSONG tapi masih ada pasien aktif
        if ($newStatus === 'KOSONG') {
            $activePatient = DB::table('kamar_inap')
                ->where('kd_kamar', $kdKamar)
                ->where('stts_pulang', '-')
                ->first();

            if ($activePatient) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Kamar \"{$kdKamar}\" sedang ditempati pasien (No. Rawat: {$activePatient->no_rawat}). Selesaikan kepulangan pasien terlebih dahulu di Rawat Inap."
                ], 422);
            }
        }

        DB::table('kamar')->where('kd_kamar', $kdKamar)->update(['status' => $newStatus]);
        $this->updateSql(new Kamar(), ['status' => $newStatus]);

        return response()->json([
            'status'  => 'success',
            'message' => "Status Kamar \"{$kdKamar}\" berhasil diubah menjadi {$newStatus}."
        ]);
    }

    /**
     * Hapus kamar / Soft Delete (Adopsi DlgKamar.BtnHapusActionPerformed: statusdata='0')
     */
    public function destroy(Request $request, $kd_kamar)
    {
        $kamar = DB::table('kamar')->where('kd_kamar', $kd_kamar)->first();
        if (!$kamar) {
            return response()->json(['status' => 'error', 'message' => 'Kamar tidak ditemukan'], 404);
        }

        // Cek apakah kamar sedang ditempati pasien aktif
        $isOccupied = DB::table('kamar_inap')
            ->where('kd_kamar', $kd_kamar)
            ->where('stts_pulang', '-')
            ->exists();

        if ($isOccupied) {
            return response()->json([
                'status'  => 'error',
                'message' => "Kamar \"{$kd_kamar}\" sedang ditempati pasien rawat inap dan tidak dapat dihapus!"
            ], 422);
        }

        // Soft delete standar Khanza
        DB::table('kamar')->where('kd_kamar', $kd_kamar)->update(['statusdata' => '0']);
        $this->updateSql(new Kamar(), ['statusdata' => '0']);

        return response()->json([
            'status'  => 'success',
            'message' => "Kamar / Bed \"{$kd_kamar}\" berhasil dihapus dari data aktif."
        ]);
    }

    /**
     * Restore kamar terhapus (Adopsi DlgRestoreKamar)
     */
    public function restore(Request $request, $kd_kamar)
    {
        $kamar = DB::table('kamar')->where('kd_kamar', $kd_kamar)->first();
        if (!$kamar) {
            return response()->json(['status' => 'error', 'message' => 'Kamar tidak ditemukan'], 404);
        }

        DB::table('kamar')->where('kd_kamar', $kd_kamar)->update(['statusdata' => '1']);
        $this->updateSql(new Kamar(), ['statusdata' => '1']);

        return response()->json([
            'status'  => 'success',
            'message' => "Kamar / Bed \"{$kd_kamar}\" berhasil dipulihkan ke data aktif."
        ]);
    }

    /**
     * List Bangsal untuk modal select
     */
    public function getBangsal()
    {
        $bangsal = Bangsal::where('status', '1')->orderBy('nm_bangsal')->get();
        return response()->json($bangsal);
    }
}

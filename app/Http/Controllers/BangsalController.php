<?php

namespace App\Http\Controllers;

use App\Models\Bangsal;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BangsalController extends Controller
{
    use Track;

    /**
     * Daftar seluruh bangsal beserta jumlah kamar aktifnya
     */
    public function index(): JsonResponse
    {
        $bangsal = DB::table('bangsal as b')
            ->leftJoin('kamar as k', function ($join) {
                $join->on('b.kd_bangsal', '=', 'k.kd_bangsal')
                     ->where('k.statusdata', '=', '1');
            })
            ->select(
                'b.kd_bangsal',
                'b.nm_bangsal',
                'b.status',
                DB::raw('COUNT(k.kd_kamar) as jumlah_kamar')
            )
            ->groupBy('b.kd_bangsal', 'b.nm_bangsal', 'b.status')
            ->orderBy('b.status', 'desc')
            ->orderBy('b.nm_bangsal', 'asc')
            ->get();

        return response()->json($bangsal);
    }

    /**
     * Simpan bangsal baru
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'kd_bangsal' => strtoupper(trim((string) $request->kd_bangsal)),
            'nm_bangsal' => trim((string) $request->nm_bangsal),
        ]);

        $validated = $request->validate([
            'kd_bangsal' => 'required|string|max:5|unique:bangsal,kd_bangsal',
            'nm_bangsal' => 'required|string|max:30',
            'status'     => 'nullable|in:0,1',
        ], [
            'kd_bangsal.required' => 'Kode bangsal wajib diisi.',
            'kd_bangsal.max'      => 'Kode bangsal maksimal 5 karakter (standar Khanza).',
            'kd_bangsal.unique'   => 'Kode bangsal sudah terdaftar.',
            'nm_bangsal.required' => 'Nama bangsal wajib diisi.',
            'nm_bangsal.max'      => 'Nama bangsal maksimal 30 karakter.',
        ]);

        $data = [
            'kd_bangsal' => $validated['kd_bangsal'],
            'nm_bangsal' => $validated['nm_bangsal'],
            'status'     => $validated['status'] ?? '1',
        ];

        $bangsal = DB::transaction(function () use ($data) {
            $created = Bangsal::create($data);
            $this->insertSql(new Bangsal(), $data);
            return $created;
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Bangsal \"{$data['nm_bangsal']} ({$data['kd_bangsal']})\" berhasil ditambahkan.",
            'data'    => $bangsal,
        ], 201);
    }

    /**
     * Perbarui data bangsal
     */
    public function update(Request $request, string $kd_bangsal): JsonResponse
    {
        $bangsal = Bangsal::where('kd_bangsal', $kd_bangsal)->first();
        if (!$bangsal) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data bangsal tidak ditemukan.',
            ], 404);
        }

        $request->merge([
            'nm_bangsal' => trim((string) $request->nm_bangsal),
        ]);

        $validated = $request->validate([
            'nm_bangsal' => 'required|string|max:30',
            'status'     => 'required|in:0,1',
        ], [
            'nm_bangsal.required' => 'Nama bangsal wajib diisi.',
            'nm_bangsal.max'      => 'Nama bangsal maksimal 30 karakter.',
        ]);

        $data = [
            'nm_bangsal' => $validated['nm_bangsal'],
            'status'     => $validated['status'],
        ];

        DB::transaction(function () use ($kd_bangsal, $data) {
            Bangsal::where('kd_bangsal', $kd_bangsal)->update($data);
            $this->updateSql(new Bangsal(), $data, ['kd_bangsal' => $kd_bangsal]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Bangsal \"{$data['nm_bangsal']}\" berhasil diperbarui.",
            'data'    => Bangsal::where('kd_bangsal', $kd_bangsal)->first(),
        ]);
    }

    /**
     * Nonaktifkan bangsal (atau hapus jika belum memiliki relasi kamar/stok)
     */
    public function destroy(string $kd_bangsal): JsonResponse
    {
        $bangsal = Bangsal::where('kd_bangsal', $kd_bangsal)->first();
        if (!$bangsal) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data bangsal tidak ditemukan.',
            ], 404);
        }

        $usedInKamar = DB::table('kamar')->where('kd_bangsal', $kd_bangsal)->exists();
        $usedInGudang = DB::table('gudangbarang')->where('kd_bangsal', $kd_bangsal)->exists();

        if ($usedInKamar || $usedInGudang) {
            DB::transaction(function () use ($kd_bangsal) {
                Bangsal::where('kd_bangsal', $kd_bangsal)->update(['status' => '0']);
                $this->updateSql(new Bangsal(), ['status' => '0'], ['kd_bangsal' => $kd_bangsal]);
            });

            return response()->json([
                'status'  => 'success',
                'message' => "Bangsal \"{$bangsal->nm_bangsal}\" masih memiliki riwayat kamar/stok sehingga statusnya dinonaktifkan.",
            ]);
        }

        DB::transaction(function () use ($kd_bangsal) {
            Bangsal::where('kd_bangsal', $kd_bangsal)->delete();
            $this->deleteSql(new Bangsal(), ['kd_bangsal' => $kd_bangsal]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => "Bangsal \"{$bangsal->nm_bangsal}\" berhasil dihapus.",
        ]);
    }
}

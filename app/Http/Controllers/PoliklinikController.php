<?php

namespace App\Http\Controllers;

use App\Models\Poliklinik;
use App\Traits\Track;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    use Track;

    public function index()
    {
        return view('content.master.poliklinik');
    }

    public function data(Request $request)
    {
        $poli = \Illuminate\Support\Facades\DB::table('poliklinik')->orderBy('kd_poli', 'asc');
        if ($request->has('status') && $request->status !== '') {
            $poli = $poli->where('status', $request->status);
        }
        return datatables()->of($poli)->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_poli' => 'required|unique:poliklinik,kd_poli',
            'nm_poli' => 'required',
            'registrasi' => 'required|numeric',
            'registrasilama' => 'required|numeric',
        ]);

        try {
            $data = $request->only(['kd_poli', 'nm_poli', 'registrasi', 'registrasilama', 'status']);
            $data['status'] = $request->status ?? '1';
            
            $poli = Poliklinik::create($data);
            return response()->json(['message' => 'Data Poliklinik berhasil disimpan', 'data' => $poli], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $kd_poli)
    {
        $request->validate([
            'nm_poli' => 'required',
            'registrasi' => 'required|numeric',
            'registrasilama' => 'required|numeric',
        ]);

        try {
            $poli = Poliklinik::where('kd_poli', $kd_poli)->firstOrFail();
            $data = $request->only(['nm_poli', 'registrasi', 'registrasilama', 'status']);
            $data['status'] = $request->status ?? '1';
            
            $poli->update($data);
            return response()->json(['message' => 'Data Poliklinik berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($kd_poli)
    {
        try {
            $poli = Poliklinik::where('kd_poli', $kd_poli)->firstOrFail();
            $poli->delete();
            return response()->json(['message' => 'Data Poliklinik berhasil dihapus']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback to setting status to '0' (non-active) if foreign key constraint triggered
            $poli = Poliklinik::where('kd_poli', $kd_poli)->firstOrFail();
            $poli->status = '0';
            $poli->save();
            return response()->json(['message' => 'Poliklinik tidak bisa dihapus karena memiliki data rujukan atau tindakan terkait. Status dinonaktifkan.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    function get(Request $request)
    {
        $poli = Poliklinik::where('status', '1');
        if ($request->poli) {
            $poli = $poli->where('nm_poli', 'like', "%{$request->poli}%");
        }
        $poli = $poli->get();
        return response()->json($poli);
    }
    function getTarifPoliklinik($kd_poli): int
    {
        $poli = Poliklinik::select('registrasi')->where('kd_poli', $kd_poli)->first();
        return $poli->registrasi;
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        $ids = $request->ids;
        $deleted = 0;
        $deactivated = 0;

        foreach ($ids as $id) {
            try {
                $poli = Poliklinik::where('kd_poli', $id)->firstOrFail();
                $poli->delete();
                $deleted++;
            } catch (\Illuminate\Database\QueryException $e) {
                $poli = Poliklinik::where('kd_poli', $id)->firstOrFail();
                $poli->status = '0';
                $poli->save();
                $deactivated++;
            }
        }

        return response()->json([
            'message' => "Proses selesai. Berhasil menghapus {$deleted} poliklinik dan menonaktifkan {$deactivated} poliklinik yang terikat transaksi."
        ]);
    }

    public function deactivateAll()
    {
        try {
            $updated = Poliklinik::where('status', '1')->update(['status' => '0']);
            return response()->json([
                'message' => "Semua poliklinik ({$updated} data) berhasil dinonaktifkan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Gagal menonaktifkan poliklinik: " . $e->getMessage()
            ], 500);
        }
    }
}

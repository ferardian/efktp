<?php

namespace App\Http\Controllers;

use App\Models\DataSuplier;
use App\Models\Pemesanan;
use App\Models\SuratPemesananMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataSuplierController extends Controller
{
    public function index()
    {
        return view('content.farmasi.suplier');
    }

    public function data()
    {
        $data = DataSuplier::orderBy('nama_suplier', 'asc')->get();
        return response()->json($data);
    }

    public function getNextKode()
    {
        $last = DataSuplier::where('kode_suplier', 'like', 'S%')
            ->orderBy('kode_suplier', 'desc')
            ->first();

        $nextSeq = 1;
        if ($last && !empty($last->kode_suplier)) {
            $numPart = intval(preg_replace('/[^0-9]/', '', $last->kode_suplier));
            if ($numPart > 0) {
                $nextSeq = $numPart + 1;
            }
        }

        $nextKode = 'S' . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        return response()->json(['kode_suplier' => $nextKode]);
    }

    public function show($kode_suplier)
    {
        $suplier = DataSuplier::where('kode_suplier', $kode_suplier)->first();
        if (!$suplier) {
            return response()->json(['message' => 'Supplier tidak ditemukan'], 404);
        }
        return response()->json($suplier);
    }

    public function store(Request $request)
    {
        $isEdit = filter_var($request->is_edit ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($isEdit) {
            $request->validate([
                'kode_suplier' => 'required|string',
                'nama_suplier' => 'required|string|max:50',
            ]);
        } else {
            $request->validate([
                'kode_suplier' => 'required|string|max:5|unique:datasuplier,kode_suplier',
                'nama_suplier' => 'required|string|max:50',
            ]);
        }

        try {
            $suplier = DataSuplier::updateOrCreate(
                ['kode_suplier' => $request->kode_suplier],
                [
                    'nama_suplier' => $request->nama_suplier,
                    'alamat'       => $request->alamat ?? '',
                    'kota'         => $request->kota ?? '',
                    'no_telp'      => $request->no_telp ?? '',
                    'nama_bank'    => $request->nama_bank ?? '',
                    'rekening'     => $request->rekening ?? '',
                ]
            );

            $msg = $isEdit ? 'Data Supplier berhasil diperbarui' : 'Supplier baru berhasil ditambahkan';
            return response()->json([
                'message' => $msg,
                'data'    => $suplier
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal simpan suplier: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan supplier: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($kode_suplier)
    {
        try {
            // Integrity checks: ensure supplier is not referenced in transactions
            $inPemesanan = Pemesanan::where('kode_suplier', $kode_suplier)->exists();
            $inSp = SuratPemesananMedis::where('kode_suplier', $kode_suplier)->exists();

            if ($inPemesanan || $inSp) {
                return response()->json([
                    'message' => 'Supplier tidak dapat dihapus karena sudah digunakan dalam riwayat penerimaan / SP Medis'
                ], 422);
            }

            $suplier = DataSuplier::where('kode_suplier', $kode_suplier)->firstOrFail();
            $suplier->delete();

            return response()->json(['message' => 'Data Supplier berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Gagal hapus suplier: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus supplier: ' . $e->getMessage()], 500);
        }
    }
}

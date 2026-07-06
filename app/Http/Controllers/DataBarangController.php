<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DataBarangController extends Controller
{
    public function index()
    {
        $satuan = \App\Models\Satuan::orderBy('satuan', 'ASC')->get();
        $jenis = \App\Models\Jenis::orderBy('nama', 'ASC')->get();
        $industri = \App\Models\IndustriFarmasi::orderBy('nama_industri', 'ASC')->get();
        $kategori = \App\Models\KategoriBarang::orderBy('nama', 'ASC')->get();
        $golongan = \App\Models\GolonganBarang::orderBy('nama', 'ASC')->get();

        return view('content.dataBarang', compact('satuan', 'jenis', 'industri', 'kategori', 'golongan'));
    }

    public function get(Request $request)
    {
        $barang = DataBarang::query();
        
        if ($request->has('status') && $request->status !== 'semua') {
            $barang->where('status', $request->status);
        } elseif (!$request->has('allData')) {
            $barang->where('status', '1');
        }

        $barang->with(['satuan', 'satuanBesar', 'jenis', 'golongan', 'industri', 'kategori', 'mapping', 'gudangBarang.lokasi']);

        $searchTerm = $request->barang;

        $barang = $barang->where('nama_brng', 'like', '%' . $searchTerm . "%")
            ->whereHas('jenis', function ($query) {
                return $query->where('nama', 'not like', 'logistik');
            })->orderBy('nama_brng', 'ASC')
            ->get();

        if ($request->dataTable) {
            return DataTables::of($barang)->make(true);
        }
        return response()->json($barang);
    }

    public function getNextKode()
    {
        $latest = DataBarang::where('kode_brng', 'like', 'B%')
            ->selectRaw("MAX(CAST(SUBSTRING(kode_brng, 2) AS UNSIGNED)) as max_num")
            ->first();
        $num = $latest && $latest->max_num ? ($latest->max_num + 1) : 1;
        $nextKode = 'B' . str_pad($num, 9, '0', STR_PAD_LEFT);
        return response()->json(['next_kode' => $nextKode]);
    }

    public function detail($kode_brng)
    {
        $barang = DataBarang::where('kode_brng', $kode_brng)->firstOrFail();
        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required|unique:databarang,kode_brng',
            'nama_brng' => 'required',
        ]);

        try {
            $data = $this->sanitizeData($request);
            $barang = DataBarang::create($data);
            return response()->json(['message' => 'Data obat berhasil disimpan', 'data' => $barang], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $kode_brng)
    {
        $request->validate([
            'nama_brng' => 'required',
        ]);

        try {
            $barang = DataBarang::where('kode_brng', $kode_brng)->firstOrFail();
            $data = $this->sanitizeData($request);
            $barang->update($data);
            return response()->json(['message' => 'Data obat berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($kode_brng)
    {
        try {
            $barang = DataBarang::where('kode_brng', $kode_brng)->firstOrFail();
            $barang->delete();
            return response()->json(['message' => 'Data obat berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required|array',
            'kode_brng.*' => 'required|string',
            'status' => 'required|in:0,1'
        ]);

        try {
            DataBarang::whereIn('kode_brng', $request->kode_brng)->update(['status' => $request->status]);
            $msg = $request->status === '1' ? 'Berhasil mengaktifkan data obat terpilih' : 'Berhasil menonaktifkan data obat terpilih';
            return response()->json(['message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui status data obat: ' . $e->getMessage()], 500);
        }
    }

    private function sanitizeData(Request $request)
    {
        $data = $request->all();
        
        $numericFields = [
            'dasar', 'h_beli', 'ralan', 'kelas1', 'kelas2', 'kelas3', 
            'utama', 'vip', 'vvip', 'beliluar', 'jualbebas', 'karyawan', 
            'stokminimal', 'isi', 'kapasitas'
        ];

        foreach ($numericFields as $field) {
            $data[$field] = !empty($data[$field]) ? $data[$field] : 0;
        }

        $data['expire'] = !empty($data['expire']) ? $data['expire'] : '1900-01-01';
        $data['status'] = $data['status'] ?? '1';

        return $data;
    }
}

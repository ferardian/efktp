<?php

namespace App\Http\Controllers;

use App\Models\JenisPerawatan;
use Illuminate\Http\Request;

class JenisPerawatanController extends Controller
{
    protected $model;
    function __construct(JenisPerawatan $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $kategori = \App\Models\KategoriPerawatan::orderBy('nm_kategori', 'asc')->get();
        $penjab = \App\Models\Penjab::where('status', '1')->orderBy('png_jawab', 'asc')->get();
        $poliklinik = \App\Models\Poliklinik::where('status', '1')->orderBy('nm_poli', 'asc')->get();

        return view('content.master.tarif_ralan', compact('kategori', 'penjab', 'poliklinik'));
    }

    public function getNextKode()
    {
        $latest = \App\Models\JenisPerawatan::where('kd_jenis_prw', 'like', 'RJ%')
            ->selectRaw("MAX(CAST(SUBSTRING(kd_jenis_prw, 3) AS UNSIGNED)) as max_num")
            ->first();
        $num = $latest && $latest->max_num ? ($latest->max_num + 1) : 1;
        $nextKode = 'RJ' . str_pad($num, 5, '0', STR_PAD_LEFT);
        return response()->json(['next_kode' => $nextKode]);
    }

    public function dataTable(Request $request)
    {
        $data = $this->model->with(['kategori', 'penjab', 'poliklinik'])
            ->orderBy('kd_jenis_prw', 'asc');
        if ($request->has('status') && $request->status !== '') {
            $data = $data->where('status', $request->status);
        }
        if ($request->kd_kategori) {
            $data = $data->where('kd_kategori', $request->kd_kategori);
        }
        if ($request->kd_pj) {
            $data = $data->where('kd_pj', $request->kd_pj);
        }
        if ($request->kd_poli) {
            $data = $data->where('kd_poli', $request->kd_poli);
        }
        return datatables()->of($data)

            ->addColumn('_checked', function ($row) {
                return false;
            })
            ->make(true);
    }

    public function get(Request $request)
    {
        $data = $this->model->where('status', '1');
        if ($request->nm_perawatan) {
            $data = $data->where('nm_perawatan', 'like', '%' . $request->nm_perawatan . '%');
        }

        if ($request->pelaksana) {
            if ($request->pelaksana == 'dr') {
                $data = $data->where('total_byrdr', '>', 0);
            } elseif ($request->pelaksana == 'pr') {
                $data = $data->where('total_byrpr', '>', 0);
            } elseif ($request->pelaksana == 'drpr') {
                $data = $data->where('total_byrdrpr', '>', 0);
            }
        }

        return response()->json($data->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_jenis_prw' => 'required|unique:jns_perawatan,kd_jenis_prw',
            'nm_perawatan' => 'required',
            'kd_kategori' => 'required',
            'kd_pj' => 'required',
            'kd_poli' => 'required',
        ]);

        try {
            $data = $request->all();
            
            // Calculate totals
            $material = floatval($request->material ?? 0);
            $bhp = floatval($request->bhp ?? 0);
            $tarif_tindakandr = floatval($request->tarif_tindakandr ?? 0);
            $tarif_tindakanpr = floatval($request->tarif_tindakanpr ?? 0);
            $kso = floatval($request->kso ?? 0);
            $menejemen = floatval($request->menejemen ?? 0);

            $data['total_byrdr'] = $material + $bhp + $tarif_tindakandr + $kso + $menejemen;
            $data['total_byrpr'] = $material + $bhp + $tarif_tindakanpr + $kso + $menejemen;
            $data['total_byrdrpr'] = $material + $bhp + $tarif_tindakandr + $tarif_tindakanpr + $kso + $menejemen;
            $data['status'] = $request->status ?? '1';

            $tarif = JenisPerawatan::create($data);
            return response()->json(['message' => 'Tarif Rawat Jalan berhasil disimpan', 'data' => $tarif], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $kd_jenis_prw)
    {
        $request->validate([
            'nm_perawatan' => 'required',
            'kd_kategori' => 'required',
            'kd_pj' => 'required',
            'kd_poli' => 'required',
        ]);

        try {
            $tarif = JenisPerawatan::findOrFail($kd_jenis_prw);
            $data = $request->all();

            // Calculate totals
            $material = floatval($request->material ?? 0);
            $bhp = floatval($request->bhp ?? 0);
            $tarif_tindakandr = floatval($request->tarif_tindakandr ?? 0);
            $tarif_tindakanpr = floatval($request->tarif_tindakanpr ?? 0);
            $kso = floatval($request->kso ?? 0);
            $menejemen = floatval($request->menejemen ?? 0);

            $data['total_byrdr'] = $material + $bhp + $tarif_tindakandr + $kso + $menejemen;
            $data['total_byrpr'] = $material + $bhp + $tarif_tindakanpr + $kso + $menejemen;
            $data['total_byrdrpr'] = $material + $bhp + $tarif_tindakandr + $tarif_tindakanpr + $kso + $menejemen;
            $data['status'] = $request->status ?? '1';

            $tarif->update($data);
            return response()->json(['message' => 'Tarif Rawat Jalan berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($kd_jenis_prw)
    {
        try {
            $tarif = JenisPerawatan::findOrFail($kd_jenis_prw);
            $tarif->delete();
            return response()->json(['message' => 'Tarif Rawat Jalan berhasil dihapus']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback: if foreign key constraint exists, set status to 0 (nonactive)
            $tarif = JenisPerawatan::findOrFail($kd_jenis_prw);
            $tarif->status = '0';
            $tarif->save();
            return response()->json(['message' => 'Tarif tidak dapat dihapus karena sudah memiliki riwayat transaksi. Status diubah menjadi Non-Aktif.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
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
                $jns = JenisPerawatan::findOrFail($id);
                $jns->delete();
                $deleted++;
            } catch (\Illuminate\Database\QueryException $e) {
                $jns = JenisPerawatan::findOrFail($id);
                $jns->status = '0';
                $jns->save();
                $deactivated++;
            }
        }

        return response()->json([
            'message' => "Proses selesai. Berhasil menghapus {$deleted} data dan menonaktifkan {$deactivated} data yang terikat transaksi."
        ]);
    }

    public function deactivateAll()
    {
        try {
            $updated = JenisPerawatan::where('status', '1')->update(['status' => '0']);
            return response()->json([
                'message' => "Semua tarif rawat jalan ({$updated} data) berhasil dinonaktifkan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Gagal menonaktifkan tarif: " . $e->getMessage()
            ], 500);
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\JenisPerawatanInap;
use App\Models\KategoriPerawatan;
use App\Models\Penjab;
use App\Models\Bangsal;
use Illuminate\Http\Request;

class JenisPerawatanInapController extends Controller
{
    protected $model;

    public function __construct(JenisPerawatanInap $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $kategori = KategoriPerawatan::orderBy('nm_kategori', 'asc')->get();
        $penjab   = Penjab::where('status', '1')->orderBy('png_jawab', 'asc')->get();
        $bangsal  = Bangsal::where('status', '1')->orderBy('nm_bangsal', 'asc')->get();
        $kelas    = ['-', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas Utama', 'Kelas VIP', 'Kelas VVIP'];

        return view('content.master.tarif_ranap', compact('kategori', 'penjab', 'bangsal', 'kelas'));
    }

    public function getNextKode()
    {
        $latest = JenisPerawatanInap::where('kd_jenis_prw', 'like', 'RI%')
            ->selectRaw("MAX(CAST(SUBSTRING(kd_jenis_prw, 3) AS UNSIGNED)) as max_num")
            ->first();

        $num = $latest && $latest->max_num ? ($latest->max_num + 1) : 1;
        $nextKode = 'RI' . str_pad($num, 5, '0', STR_PAD_LEFT);

        return response()->json(['next_kode' => $nextKode]);
    }

    public function dataTable(Request $request)
    {
        $data = $this->model->with(['kategori', 'penjab', 'bangsal'])
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

        if ($request->kd_bangsal) {
            $data = $data->where('kd_bangsal', $request->kd_bangsal);
        }

        if ($request->kelas) {
            $data = $data->where('kelas', $request->kelas);
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

        if ($request->keyword) {
            $data = $data->where(function ($q) use ($request) {
                $q->where('nm_perawatan', 'like', '%' . $request->keyword . '%')
                    ->orWhere('kd_jenis_prw', 'like', '%' . $request->keyword . '%');
            });
        } elseif ($request->nm_perawatan) {
            $data = $data->where('nm_perawatan', 'like', '%' . $request->nm_perawatan . '%');
        }

        if ($request->kd_pj) {
            $data = $data->where(function ($q) use ($request) {
                $q->where('kd_pj', $request->kd_pj)->orWhere('kd_pj', '-');
            });
        }

        if ($request->kd_bangsal) {
            $data = $data->where(function ($q) use ($request) {
                $q->where('kd_bangsal', $request->kd_bangsal)->orWhere('kd_bangsal', '-');
            });
        }

        if ($request->kelas) {
            $data = $data->where(function ($q) use ($request) {
                $q->where('kelas', $request->kelas)->orWhere('kelas', '-');
            });
        }

        if ($request->pelaksana) {
            if ($request->pelaksana == 'dr') {
                $data = $data->where(function ($q) {
                    $q->where('total_byrdr', '>', 0)
                        ->orWhere('material', '>', 0)
                        ->orWhere('menejemen', '>', 0);
                });
            } elseif ($request->pelaksana == 'pr') {
                $data = $data->where(function ($q) {
                    $q->where('total_byrpr', '>', 0)
                        ->orWhere('material', '>', 0)
                        ->orWhere('menejemen', '>', 0);
                });
            } elseif ($request->pelaksana == 'drpr') {
                $data = $data->where(function ($q) {
                    $q->where('total_byrdrpr', '>', 0)
                        ->orWhere('material', '>', 0)
                        ->orWhere('menejemen', '>', 0);
                });
            }
        }

        if ($request->limit) {
            $data = $data->limit((int)$request->limit);
        }

        return response()->json($data->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_jenis_prw' => 'required|unique:jns_perawatan_inap,kd_jenis_prw',
            'nm_perawatan' => 'required',
            'kd_kategori'  => 'required',
            'kd_pj'        => 'required',
            'kd_bangsal'   => 'required',
            'kelas'        => 'required',
        ]);

        try {
            $data = $request->all();

            // Calculate totals
            $material         = floatval($request->material ?? 0);
            $bhp              = floatval($request->bhp ?? 0);
            $tarif_tindakandr = floatval($request->tarif_tindakandr ?? 0);
            $tarif_tindakanpr = floatval($request->tarif_tindakanpr ?? 0);
            $kso              = floatval($request->kso ?? 0);
            $menejemen        = floatval($request->menejemen ?? 0);

            $data['total_byrdr']   = $material + $bhp + $tarif_tindakandr + $kso + $menejemen;
            $data['total_byrpr']   = $material + $bhp + $tarif_tindakanpr + $kso + $menejemen;
            $data['total_byrdrpr'] = $material + $bhp + $tarif_tindakandr + $tarif_tindakanpr + $kso + $menejemen;
            $data['status']        = $request->status ?? '1';

            $tarif = JenisPerawatanInap::create($data);
            return response()->json(['message' => 'Tarif Rawat Inap berhasil disimpan', 'data' => $tarif], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $kd_jenis_prw)
    {
        $request->validate([
            'nm_perawatan' => 'required',
            'kd_kategori'  => 'required',
            'kd_pj'        => 'required',
            'kd_bangsal'   => 'required',
            'kelas'        => 'required',
        ]);

        try {
            $tarif = JenisPerawatanInap::findOrFail($kd_jenis_prw);
            $data = $request->all();

            // Calculate totals
            $material         = floatval($request->material ?? 0);
            $bhp              = floatval($request->bhp ?? 0);
            $tarif_tindakandr = floatval($request->tarif_tindakandr ?? 0);
            $tarif_tindakanpr = floatval($request->tarif_tindakanpr ?? 0);
            $kso              = floatval($request->kso ?? 0);
            $menejemen        = floatval($request->menejemen ?? 0);

            $data['total_byrdr']   = $material + $bhp + $tarif_tindakandr + $kso + $menejemen;
            $data['total_byrpr']   = $material + $bhp + $tarif_tindakanpr + $kso + $menejemen;
            $data['total_byrdrpr'] = $material + $bhp + $tarif_tindakandr + $tarif_tindakanpr + $kso + $menejemen;
            $data['status']        = $request->status ?? '1';

            $tarif->update($data);
            return response()->json(['message' => 'Tarif Rawat Inap berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($kd_jenis_prw)
    {
        try {
            $tarif = JenisPerawatanInap::findOrFail($kd_jenis_prw);
            $tarif->delete();
            return response()->json(['message' => 'Tarif Rawat Inap berhasil dihapus']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback: if foreign key constraint exists, set status to 0 (nonactive)
            $tarif = JenisPerawatanInap::findOrFail($kd_jenis_prw);
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
                $jns = JenisPerawatanInap::findOrFail($id);
                $jns->delete();
                $deleted++;
            } catch (\Illuminate\Database\QueryException $e) {
                $jns = JenisPerawatanInap::findOrFail($id);
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
            $updated = JenisPerawatanInap::where('status', '1')->update(['status' => '0']);
            return response()->json([
                'message' => "Semua tarif rawat inap ({$updated} data) berhasil dinonaktifkan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Gagal menonaktifkan tarif: " . $e->getMessage()
            ], 500);
        }
    }
}

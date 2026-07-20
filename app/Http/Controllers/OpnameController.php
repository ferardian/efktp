<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\DataBarang;
use App\Models\Bangsal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class OpnameController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (config('app.enable_menu_role')) {
                $userRole = session()->get('role');
                $allowedRoles = ['admin', 'apoteker', 'owner'];

                $hasMenuAccess = DB::table('menu_role')
                    ->join('menus', 'menu_role.menu_id', '=', 'menus.id')
                    ->where('menu_role.role', $userRole)
                    ->where(function($q) {
                        $q->where('menus.url', 'farmasi/opname')
                          ->orWhere('menus.url', '/farmasi/opname');
                    })
                    ->exists();

                if (!in_array($userRole, $allowedRoles) && !$hasMenuAccess) {
                    if ($request->ajax()) {
                        return response()->json(['message' => 'Akses ditolak.'], 403);
                    }
                    return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.');
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $bangsal = Bangsal::where('status', '1')->orderBy('nm_bangsal', 'asc')->get();
        return view('content.farmasi.opname', compact('bangsal'));
    }

    public function data(Request $request)
    {
        $query = Opname::with(['barang', 'bangsal']);

        if ($request->tgl_awal) {
            $query->where('tanggal', '>=', $request->tgl_awal);
        }
        if ($request->tgl_akhir) {
            $query->where('tanggal', '<=', $request->tgl_akhir);
        }
        if ($request->kd_bangsal) {
            $query->where('kd_bangsal', $request->kd_bangsal);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();
            
        return response()->json($data);
    }

    public function getItems(Request $request)
    {
        $request->validate([
            'kd_bangsal' => 'required'
        ]);

        $items = DB::table('databarang')
            ->leftJoin('kodesatuan', 'databarang.kode_sat', '=', 'kodesatuan.kode_sat')
            ->leftJoin('gudangbarang', function($join) use ($request) {
                $join->on('databarang.kode_brng', '=', 'gudangbarang.kode_brng')
                     ->where('gudangbarang.kd_bangsal', '=', $request->kd_bangsal);
            })
            ->where('databarang.status', '1')
            ->select(
                'databarang.kode_brng',
                'databarang.nama_brng',
                'kodesatuan.satuan',
                DB::raw('COALESCE(gudangbarang.no_batch, "") as no_batch'),
                DB::raw('COALESCE(gudangbarang.no_faktur, "") as no_faktur'),
                DB::raw('COALESCE(gudangbarang.stok, 0) as stok'),
                'databarang.h_beli'
            )
            ->orderBy('databarang.nama_brng', 'asc')
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_bangsal' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
            'items' => 'required|array',
            'items.*.kode_brng' => 'required',
            'items.*.stok' => 'required|numeric',
            'items.*.real' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                $stok = floatval($item['stok']);
                $real = floatval($item['real']);
                $selisih = $real - $stok;

                // Skip saving if there is no adjustment made
                if ($selisih == 0) {
                    continue;
                }

                $h_beli = floatval($item['h_beli'] ?? 0);

                $nomihilang = 0;
                $nomilebih = 0;
                $lebih = 0;
                $kurang = 0;

                if ($selisih < 0) {
                    $kurang = abs($selisih);
                    $nomihilang = $kurang * $h_beli;
                } else if ($selisih > 0) {
                    $lebih = $selisih;
                    $nomilebih = $lebih * $h_beli;
                }

                $no_batch = $item['no_batch'] ?? '';
                $no_faktur = $item['no_faktur'] ?? '';

                // 1. Save Opname Record
                Opname::create([
                    'kode_brng' => $item['kode_brng'],
                    'h_beli' => $h_beli,
                    'tanggal' => $request->tanggal,
                    'stok' => $stok,
                    'real' => $real,
                    'selisih' => $selisih,
                    'nomihilang' => $nomihilang,
                    'lebih' => $lebih,
                    'nomilebih' => $nomilebih,
                    'keterangan' => $request->keterangan ?? '-',
                    'kd_bangsal' => $request->kd_bangsal,
                    'no_batch' => $no_batch,
                    'no_faktur' => $no_faktur
                ]);

                // 2. Adjust stock in gudangbarang
                $gudang = DB::table('gudangbarang')
                    ->where('kode_brng', $item['kode_brng'])
                    ->where('kd_bangsal', $request->kd_bangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->first();

                if ($gudang) {
                    DB::table('gudangbarang')
                        ->where('kode_brng', $item['kode_brng'])
                        ->where('kd_bangsal', $request->kd_bangsal)
                        ->where('no_batch', $no_batch)
                        ->where('no_faktur', $no_faktur)
                        ->update(['stok' => $real]);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng' => $item['kode_brng'],
                        'kd_bangsal' => $request->kd_bangsal,
                        'stok' => $real,
                        'no_batch' => $no_batch,
                        'no_faktur' => $no_faktur
                    ]);
                }
            }

            // Clean up 0 stock entries
            DB::table('gudangbarang')->where('stok', '<=', 0)->delete();

            DB::commit();
            return response()->json(['message' => 'Transaksi batch stok opname berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan stok opname: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required',
            'tanggal' => 'required|date',
            'kd_bangsal' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $no_batch = $request->no_batch ?? '';
            $no_faktur = $request->no_faktur ?? '';

            // Find the opname record
            $opname = Opname::where('kode_brng', $request->kode_brng)
                ->where('tanggal', $request->tanggal)
                ->where('kd_bangsal', $request->kd_bangsal)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->firstOrFail();

            // Reverse stock: new_stock = current_stock - selisih
            $gudang = DB::table('gudangbarang')
                ->where('kode_brng', $request->kode_brng)
                ->where('kd_bangsal', $request->kd_bangsal)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->first();

            if ($gudang) {
                DB::table('gudangbarang')
                    ->where('kode_brng', $request->kode_brng)
                    ->where('kd_bangsal', $request->kd_bangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->decrement('stok', $opname->selisih);
            } else {
                DB::table('gudangbarang')->insert([
                    'kode_brng' => $request->kode_brng,
                    'kd_bangsal' => $request->kd_bangsal,
                    'stok' => $opname->stok,
                    'no_batch' => $no_batch,
                    'no_faktur' => $no_faktur
                ]);
            }

            // Delete opname entry
            Opname::where('kode_brng', $request->kode_brng)
                ->where('tanggal', $request->tanggal)
                ->where('kd_bangsal', $request->kd_bangsal)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->delete();

            // Clean up 0 stock entries
            DB::table('gudangbarang')->where('stok', '<=', 0)->delete();

            DB::commit();
            return response()->json(['message' => 'Riwayat stok opname berhasil dihapus dan stok gudang dikembalikan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus riwayat opname: ' . $e->getMessage()], 500);
        }
    }
}

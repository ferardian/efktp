<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\DataBarang;
use App\Models\Bangsal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'tanggal' => 'required',
            'keterangan' => 'required',
            'items' => 'required|array',
            'items.*.kode_brng' => 'required',
            'items.*.stok' => 'required|numeric',
            'items.*.real' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $tglOpname = date('Y-m-d', strtotime(str_replace('/', '-', $request->tanggal)));
            $nipInput = session()->get('pegawai')->nik ?? session()->get('nik') ?? '-';
            $catatan = substr($request->keterangan ?? '-', 0, 60);

            $savedCount = 0;

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

                $no_batch = substr($item['no_batch'] ?? '', 0, 20);
                $no_faktur = substr($item['no_faktur'] ?? '', 0, 20);

                // 1. Delete existing opname record on that date/batch if already exists to prevent Duplicate entry
                DB::table('opname')
                    ->where('kode_brng', $item['kode_brng'])
                    ->where('tanggal', $tglOpname)
                    ->where('kd_bangsal', $request->kd_bangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->delete();

                // 2. Insert Opname Record
                DB::table('opname')->insert([
                    'kode_brng'  => $item['kode_brng'],
                    'h_beli'     => $h_beli,
                    'tanggal'    => $tglOpname,
                    'stok'       => $stok,
                    'real'       => $real,
                    'selisih'    => $selisih,
                    'nomihilang' => $nomihilang,
                    'lebih'      => $lebih,
                    'nomilebih'  => $nomilebih,
                    'keterangan' => $catatan,
                    'kd_bangsal' => $request->kd_bangsal,
                    'no_batch'   => $no_batch,
                    'no_faktur'  => $no_faktur,
                ]);

                // 3. Adjust stock in gudangbarang
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
                        'kode_brng'  => $item['kode_brng'],
                        'kd_bangsal' => $request->kd_bangsal,
                        'stok'       => $real,
                        'no_batch'   => $no_batch,
                        'no_faktur'  => $no_faktur,
                    ]);
                }

                // 4. Catat ke riwayat_barang_medis untuk audit trail & kartu stok Khanza
                $posisi = ($selisih > 0) ? 'Lebih' : 'Hilang';
                $masuk = ($selisih > 0) ? $lebih : 0;
                $keluar = ($selisih < 0) ? $kurang : 0;

                try {
                    DB::table('riwayat_barang_medis')->insert([
                        'kode_brng'  => $item['kode_brng'],
                        'stok_awal'  => $stok,
                        'masuk'      => $masuk,
                        'keluar'     => $keluar,
                        'stok_akhir' => $real,
                        'posisi'     => 'Opname',
                        'tanggal'    => $tglOpname,
                        'jam'        => date('H:i:s'),
                        'petugas'    => $nipInput,
                        'kd_bangsal' => $request->kd_bangsal,
                        'status'     => 'Simpan',
                        'no_batch'   => $no_batch,
                        'no_faktur'  => $no_faktur,
                        'keterangan' => substr('Stok Opname: ' . ($request->keterangan ?? '-'), 0, 100),
                    ]);
                } catch (\Throwable $th) {
                    Log::warning('Riwayat barang medis opname failed: ' . $th->getMessage());
                }

                $savedCount++;
            }

            DB::commit();
            return response()->json([
                'message'     => "Transaksi batch stok opname berhasil disimpan ({$savedCount} item diperbarui)",
                'saved_count' => $savedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan stok opname: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['_token'])
            ]);
            return response()->json(['message' => 'Gagal menyimpan stok opname: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required',
            'tanggal' => 'required',
            'kd_bangsal' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $tglOpname = date('Y-m-d', strtotime(str_replace('/', '-', $request->tanggal)));
            $no_batch = substr($request->no_batch ?? '', 0, 20);
            $no_faktur = substr($request->no_faktur ?? '', 0, 20);

            // Find the opname record
            $opname = Opname::where('kode_brng', $request->kode_brng)
                ->where('tanggal', $tglOpname)
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
                    'kode_brng'  => $request->kode_brng,
                    'kd_bangsal' => $request->kd_bangsal,
                    'stok'       => $opname->stok,
                    'no_batch'   => $no_batch,
                    'no_faktur'  => $no_faktur
                ]);
            }

            // Catat pembatalan ke riwayat_barang_medis
            $nipInput = session()->get('pegawai')->nik ?? session()->get('nik') ?? '-';
            try {
                $curStok = DB::table('gudangbarang')
                    ->where('kode_brng', $request->kode_brng)
                    ->where('kd_bangsal', $request->kd_bangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->value('stok') ?? 0;

                DB::table('riwayat_barang_medis')->insert([
                    'kode_brng'  => $request->kode_brng,
                    'stok_awal'  => $opname->real,
                    'masuk'      => ($opname->selisih < 0) ? abs($opname->selisih) : 0,
                    'keluar'     => ($opname->selisih > 0) ? $opname->selisih : 0,
                    'stok_akhir' => $curStok,
                    'posisi'     => 'Opname',
                    'tanggal'    => date('Y-m-d'),
                    'jam'        => date('H:i:s'),
                    'petugas'    => $nipInput,
                    'kd_bangsal' => $request->kd_bangsal,
                    'status'     => 'Hapus',
                    'no_batch'   => $no_batch,
                    'no_faktur'  => $no_faktur,
                    'keterangan' => 'Batal Stok Opname tgl ' . $tglOpname,
                ]);
            } catch (\Throwable $th) {
                Log::warning('Riwayat barang medis batal opname failed: ' . $th->getMessage());
            }

            // Delete opname entry
            Opname::where('kode_brng', $request->kode_brng)
                ->where('tanggal', $tglOpname)
                ->where('kd_bangsal', $request->kd_bangsal)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->delete();

            DB::commit();
            return response()->json(['message' => 'Riwayat stok opname berhasil dihapus dan stok gudang dikembalikan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus riwayat opname: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghapus riwayat opname: ' . $e->getMessage()], 500);
        }
    }
}

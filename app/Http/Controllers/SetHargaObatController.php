<?php

namespace App\Http\Controllers;

use App\Models\SetHargaObat;
use App\Models\SetPenjualanUmum;
use App\Models\SetPenjualan;
use App\Models\SetPenjualanPerBarang;
use App\Models\Jenis;
use App\Models\DataBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SetHargaObatController extends Controller
{
    public function index()
    {
        $pengaturanUmum = SetHargaObat::first();
        if (!$pengaturanUmum) {
            $pengaturanUmum = (object)[
                'setharga'   => 'Per Jenis',
                'hargadasar' => 'Harga Beli',
                'ppn'        => 'Yes'
            ];
        }

        $marginUmum = SetPenjualanUmum::first();
        if (!$marginUmum) {
            $marginUmum = (object)[
                'ralan'     => 20,
                'kelas1'    => 20,
                'kelas2'    => 20,
                'kelas3'    => 20,
                'utama'     => 20,
                'vip'       => 20,
                'vvip'      => 20,
                'beliluar'  => 20,
                'jualbebas' => 20,
                'karyawan'  => 20,
            ];
        }

        $jenisList = Jenis::orderBy('nama', 'asc')->get();

        return view('content.farmasi.setHarga', compact('pengaturanUmum', 'marginUmum', 'jenisList'));
    }

    public function updatePengaturanUmum(Request $request)
    {
        $request->validate([
            'setharga'   => 'required|in:Umum,Per Jenis,Per Barang',
            'hargadasar' => 'required|in:Harga Beli,Harga Diskon',
            'ppn'        => 'required|in:Yes,No',
        ]);

        try {
            DB::beginTransaction();
            DB::table('set_harga_obat')->delete();
            DB::table('set_harga_obat')->insert([
                'setharga'   => $request->setharga,
                'hargadasar' => $request->hargadasar,
                'ppn'        => $request->ppn,
            ]);
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Kebijakan penetapan harga obat berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMarginUmum(Request $request)
    {
        $request->validate([
            'ralan'     => 'required|numeric|min:0',
            'kelas1'    => 'required|numeric|min:0',
            'kelas2'    => 'required|numeric|min:0',
            'kelas3'    => 'required|numeric|min:0',
            'utama'     => 'required|numeric|min:0',
            'vip'       => 'required|numeric|min:0',
            'vvip'      => 'required|numeric|min:0',
            'beliluar'  => 'required|numeric|min:0',
            'jualbebas' => 'required|numeric|min:0',
            'karyawan'  => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            DB::table('setpenjualanumum')->delete();
            DB::table('setpenjualanumum')->insert([
                'ralan'     => $request->ralan,
                'kelas1'    => $request->kelas1,
                'kelas2'    => $request->kelas2,
                'kelas3'    => $request->kelas3,
                'utama'     => $request->utama,
                'vip'       => $request->vip,
                'vvip'      => $request->vvip,
                'beliluar'  => $request->beliluar,
                'jualbebas' => $request->jualbebas,
                'karyawan'  => $request->karyawan,
            ]);
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Margin harga umum berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan margin umum: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dataJenis(Request $request)
    {
        $data = SetPenjualan::with('jenis')
            ->select('setpenjualan.*')
            ->join('jenis', 'setpenjualan.kdjns', '=', 'jenis.kdjns')
            ->orderBy('jenis.nama', 'asc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_jenis', function ($row) {
                return $row->jenis ? $row->jenis->nama : '-';
            })
            ->make(true);
    }

    public function storeJenis(Request $request)
    {
        $request->validate([
            'kdjns'     => 'required|exists:jenis,kdjns',
            'ralan'     => 'required|numeric|min:0',
            'kelas1'    => 'required|numeric|min:0',
            'kelas2'    => 'required|numeric|min:0',
            'kelas3'    => 'required|numeric|min:0',
            'utama'     => 'required|numeric|min:0',
            'vip'       => 'required|numeric|min:0',
            'vvip'      => 'required|numeric|min:0',
            'beliluar'  => 'required|numeric|min:0',
            'jualbebas' => 'required|numeric|min:0',
            'karyawan'  => 'required|numeric|min:0',
        ]);

        try {
            SetPenjualan::updateOrCreate(
                ['kdjns' => $request->kdjns],
                [
                    'ralan'     => $request->ralan,
                    'kelas1'    => $request->kelas1,
                    'kelas2'    => $request->kelas2,
                    'kelas3'    => $request->kelas3,
                    'utama'     => $request->utama,
                    'vip'       => $request->vip,
                    'vvip'      => $request->vvip,
                    'beliluar'  => $request->beliluar,
                    'jualbebas' => $request->jualbebas,
                    'karyawan'  => $request->karyawan,
                ]
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Margin untuk jenis obat berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan margin jenis obat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteJenis($kdjns)
    {
        try {
            SetPenjualan::where('kdjns', $kdjns)->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Pengaturan margin jenis obat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus margin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dataBarang(Request $request)
    {
        $data = SetPenjualanPerBarang::with(['barang.satuan', 'barang.jenis'])
            ->select('setpenjualanperbarang.*')
            ->join('databarang', 'setpenjualanperbarang.kode_brng', '=', 'databarang.kode_brng')
            ->orderBy('databarang.nama_brng', 'asc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_brng', function ($row) {
                return $row->barang ? $row->barang->nama_brng : '-';
            })
            ->addColumn('satuan', function ($row) {
                return $row->barang && $row->barang->satuan ? $row->barang->satuan->satuan : '-';
            })
            ->addColumn('h_beli', function ($row) {
                return $row->barang ? floatval($row->barang->h_beli) : 0;
            })
            ->make(true);
    }

    public function searchBarang(Request $request)
    {
        $q = $request->q ?? '';
        $items = DataBarang::with('satuan')
            ->where('status', '1')
            ->where(function ($query) use ($q) {
                $query->where('nama_brng', 'like', "%{$q}%")
                    ->orWhere('kode_brng', 'like', "%{$q}%");
            })
            ->limit(25)
            ->get(['kode_brng', 'nama_brng', 'kode_sat', 'kdjns', 'h_beli', 'ralan']);

        return response()->json($items);
    }

    public function storeBarang(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required|exists:databarang,kode_brng',
            'ralan'     => 'required|numeric|min:0',
            'kelas1'    => 'required|numeric|min:0',
            'kelas2'    => 'required|numeric|min:0',
            'kelas3'    => 'required|numeric|min:0',
            'utama'     => 'required|numeric|min:0',
            'vip'       => 'required|numeric|min:0',
            'vvip'      => 'required|numeric|min:0',
            'beliluar'  => 'required|numeric|min:0',
            'jualbebas' => 'required|numeric|min:0',
            'karyawan'  => 'required|numeric|min:0',
        ]);

        try {
            SetPenjualanPerBarang::updateOrCreate(
                ['kode_brng' => $request->kode_brng],
                [
                    'ralan'     => $request->ralan,
                    'kelas1'    => $request->kelas1,
                    'kelas2'    => $request->kelas2,
                    'kelas3'    => $request->kelas3,
                    'utama'     => $request->utama,
                    'vip'       => $request->vip,
                    'vvip'      => $request->vvip,
                    'beliluar'  => $request->beliluar,
                    'jualbebas' => $request->jualbebas,
                    'karyawan'  => $request->karyawan,
                ]
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Margin khusus obat berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan margin obat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteBarang($kode_brng)
    {
        try {
            SetPenjualanPerBarang::where('kode_brng', $kode_brng)->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Pengaturan margin khusus obat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus margin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Terapkan / Update Harga ke databarang
     * Mengadopsi ppUPdate / ppUPdate1 / ppUPdate2 dari DlgSetHarga.java
     */
    public function applyHarga(Request $request)
    {
        $scope = $request->scope; // 'umum', 'jenis', atau 'barang'
        $target = $request->target; // kdjns atau kode_brng jika scope jenis/barang

        $setting = SetHargaObat::first();
        $ppnRate = ($setting && $setting->ppn === 'Yes') ? 0.11 : 0.0;

        $roundUp = function ($val, $step = 100) {
            if ($val <= 0) return 0;
            return ceil($val / $step) * $step;
        };

        try {
            DB::beginTransaction();

            $updatedCount = 0;

            if ($scope === 'umum') {
                $margin = SetPenjualanUmum::first();
                if (!$margin) {
                    return response()->json(['status' => 'error', 'message' => 'Margin umum belum diatur'], 400);
                }

                $barangs = DataBarang::where('status', '1')->get();
                foreach ($barangs as $b) {
                    $h_dasar = floatval($b->h_beli) + (floatval($b->h_beli) * $ppnRate);
                    $b->update([
                        'dasar'     => $h_dasar,
                        'ralan'     => $roundUp($h_dasar + ($h_dasar * ($margin->ralan / 100))),
                        'kelas1'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas1 / 100))),
                        'kelas2'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas2 / 100))),
                        'kelas3'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas3 / 100))),
                        'utama'     => $roundUp($h_dasar + ($h_dasar * ($margin->utama / 100))),
                        'vip'       => $roundUp($h_dasar + ($h_dasar * ($margin->vip / 100))),
                        'vvip'      => $roundUp($h_dasar + ($h_dasar * ($margin->vvip / 100))),
                        'beliluar'  => $roundUp($h_dasar + ($h_dasar * ($margin->beliluar / 100))),
                        'jualbebas' => $roundUp($h_dasar + ($h_dasar * ($margin->jualbebas / 100))),
                        'karyawan'  => $roundUp($h_dasar + ($h_dasar * ($margin->karyawan / 100))),
                    ]);
                    $updatedCount++;
                }

            } elseif ($scope === 'jenis') {
                $margin = SetPenjualan::where('kdjns', $target)->first();
                if (!$margin) {
                    return response()->json(['status' => 'error', 'message' => 'Margin untuk jenis obat ini belum diatur'], 400);
                }

                $barangs = DataBarang::where('kdjns', $target)->where('status', '1')->get();
                foreach ($barangs as $b) {
                    $h_dasar = floatval($b->h_beli) + (floatval($b->h_beli) * $ppnRate);
                    $b->update([
                        'dasar'     => $h_dasar,
                        'ralan'     => $roundUp($h_dasar + ($h_dasar * ($margin->ralan / 100))),
                        'kelas1'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas1 / 100))),
                        'kelas2'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas2 / 100))),
                        'kelas3'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas3 / 100))),
                        'utama'     => $roundUp($h_dasar + ($h_dasar * ($margin->utama / 100))),
                        'vip'       => $roundUp($h_dasar + ($h_dasar * ($margin->vip / 100))),
                        'vvip'      => $roundUp($h_dasar + ($h_dasar * ($margin->vvip / 100))),
                        'beliluar'  => $roundUp($h_dasar + ($h_dasar * ($margin->beliluar / 100))),
                        'jualbebas' => $roundUp($h_dasar + ($h_dasar * ($margin->jualbebas / 100))),
                        'karyawan'  => $roundUp($h_dasar + ($h_dasar * ($margin->karyawan / 100))),
                    ]);
                    $updatedCount++;
                }

            } elseif ($scope === 'barang') {
                $margin = SetPenjualanPerBarang::where('kode_brng', $target)->first();
                if (!$margin) {
                    return response()->json(['status' => 'error', 'message' => 'Margin khusus untuk obat ini belum diatur'], 400);
                }

                $b = DataBarang::where('kode_brng', $target)->first();
                if ($b) {
                    $h_dasar = floatval($b->h_beli) + (floatval($b->h_beli) * $ppnRate);
                    $b->update([
                        'dasar'     => $h_dasar,
                        'ralan'     => $roundUp($h_dasar + ($h_dasar * ($margin->ralan / 100))),
                        'kelas1'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas1 / 100))),
                        'kelas2'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas2 / 100))),
                        'kelas3'    => $roundUp($h_dasar + ($h_dasar * ($margin->kelas3 / 100))),
                        'utama'     => $roundUp($h_dasar + ($h_dasar * ($margin->utama / 100))),
                        'vip'       => $roundUp($h_dasar + ($h_dasar * ($margin->vip / 100))),
                        'vvip'      => $roundUp($h_dasar + ($h_dasar * ($margin->vvip / 100))),
                        'beliluar'  => $roundUp($h_dasar + ($h_dasar * ($margin->beliluar / 100))),
                        'jualbebas' => $roundUp($h_dasar + ($h_dasar * ($margin->jualbebas / 100))),
                        'karyawan'  => $roundUp($h_dasar + ($h_dasar * ($margin->karyawan / 100))),
                    ]);
                    $updatedCount = 1;
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "Harga jual berhasil diterapkan ke {$updatedCount} data obat di master databarang."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menerapkan harga: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Interactive Live Simulator
     */
    public function simulatePrice(Request $request)
    {
        $h_beli = floatval($request->h_beli ?? 0);
        $kdjns = $request->kdjns ?? null;
        $kode_brng = $request->kode_brng ?? null;

        $setting = SetHargaObat::first();
        $ppnRate = ($setting && $setting->ppn === 'Yes') ? 0.11 : 0.0;
        $h_dasar = $h_beli + ($h_beli * $ppnRate);

        // Cari margin yang berlaku berdasarkan mode
        $margin = null;
        $modeApplied = 'Umum';

        if ($kode_brng) {
            $margin = SetPenjualanPerBarang::where('kode_brng', $kode_brng)->first();
            if ($margin) $modeApplied = 'Per Barang';
        }

        if (!$margin && $kdjns) {
            $margin = SetPenjualan::where('kdjns', $kdjns)->first();
            if ($margin) $modeApplied = 'Per Jenis';
        }

        if (!$margin) {
            $margin = SetPenjualanUmum::first();
            $modeApplied = 'Umum';
        }

        if (!$margin) {
            $margin = (object)[
                'ralan' => 20, 'kelas1' => 20, 'kelas2' => 20, 'kelas3' => 20,
                'utama' => 20, 'vip' => 20, 'vvip' => 20, 'beliluar' => 20,
                'jualbebas' => 20, 'karyawan' => 20
            ];
        }

        $roundUp = function ($val, $step = 100) {
            if ($val <= 0) return 0;
            return ceil($val / $step) * $step;
        };

        $prices = [
            'ralan'     => ['margin' => $margin->ralan, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->ralan / 100)))],
            'jualbebas' => ['margin' => $margin->jualbebas, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->jualbebas / 100)))],
            'karyawan'  => ['margin' => $margin->karyawan, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->karyawan / 100)))],
            'beliluar'  => ['margin' => $margin->beliluar, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->beliluar / 100)))],
            'kelas1'    => ['margin' => $margin->kelas1, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->kelas1 / 100)))],
            'kelas2'    => ['margin' => $margin->kelas2, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->kelas2 / 100)))],
            'kelas3'    => ['margin' => $margin->kelas3, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->kelas3 / 100)))],
            'utama'     => ['margin' => $margin->utama, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->utama / 100)))],
            'vip'       => ['margin' => $margin->vip, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->vip / 100)))],
            'vvip'      => ['margin' => $margin->vvip, 'harga' => $roundUp($h_dasar + ($h_dasar * ($margin->vvip / 100)))],
        ];

        return response()->json([
            'h_beli'       => $h_beli,
            'ppn_rate'     => $ppnRate * 100,
            'h_dasar'      => $h_dasar,
            'mode_applied' => $modeApplied,
            'prices'       => $prices
        ]);
    }

    /**
     * Data Monitoring Master Obat & Harga Terkini
     */
    public function dataMonitoringObat(Request $request)
    {
        $query = DataBarang::with(['satuan', 'jenis'])
            ->where('status', '1');

        if ($request->filled('kdjns')) {
            $query->where('kdjns', $request->kdjns);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('satuan_nama', function ($row) {
                return $row->satuan ? $row->satuan->satuan : '-';
            })
            ->addColumn('jenis_nama', function ($row) {
                return $row->jenis ? $row->jenis->nama : '-';
            })
            ->editColumn('h_beli', function ($row) {
                return floatval($row->h_beli);
            })
            ->editColumn('dasar', function ($row) {
                return floatval($row->dasar);
            })
            ->editColumn('ralan', function ($row) {
                return floatval($row->ralan);
            })
            ->editColumn('jualbebas', function ($row) {
                return floatval($row->jualbebas);
            })
            ->editColumn('kelas1', function ($row) {
                return floatval($row->kelas1);
            })
            ->editColumn('kelas2', function ($row) {
                return floatval($row->kelas2);
            })
            ->editColumn('kelas3', function ($row) {
                return floatval($row->kelas3);
            })
            ->editColumn('vip', function ($row) {
                return floatval($row->vip);
            })
            ->editColumn('karyawan', function ($row) {
                return floatval($row->karyawan);
            })
            ->make(true);
    }

    /**
     * Detail 10 Kategori Tarif Obat untuk Modal Lookup
     */
    public function detailHargaObat($kode_brng)
    {
        $barang = DataBarang::with(['satuan', 'jenis'])
            ->where('kode_brng', $kode_brng)
            ->first();

        if (!$barang) {
            return response()->json(['status' => 'error', 'message' => 'Obat tidak ditemukan'], 404);
        }

        $h_dasar = floatval($barang->dasar > 0 ? $barang->dasar : $barang->h_beli);

        $calculateMargin = function ($sellingPrice) use ($h_dasar) {
            if ($h_dasar <= 0) return 0;
            return round((($sellingPrice - $h_dasar) / $h_dasar) * 100, 1);
        };

        return response()->json([
            'status' => 'success',
            'data'   => [
                'kode_brng'   => $barang->kode_brng,
                'nama_brng'   => $barang->nama_brng,
                'satuan'      => $barang->satuan ? $barang->satuan->satuan : '-',
                'jenis'       => $barang->jenis ? $barang->jenis->nama : '-',
                'h_beli'      => floatval($barang->h_beli),
                'dasar'       => floatval($barang->dasar),
                'prices'      => [
                    'ralan'     => ['label' => 'Rawat Jalan (Ralan)', 'harga' => floatval($barang->ralan), 'margin' => $calculateMargin($barang->ralan)],
                    'jualbebas' => ['label' => 'Jual Bebas (OTC)', 'harga' => floatval($barang->jualbebas), 'margin' => $calculateMargin($barang->jualbebas)],
                    'karyawan'  => ['label' => 'Karyawan', 'harga' => floatval($barang->karyawan), 'margin' => $calculateMargin($barang->karyawan)],
                    'beliluar'  => ['label' => 'Beli Luar', 'harga' => floatval($barang->beliluar), 'margin' => $calculateMargin($barang->beliluar)],
                    'kelas3'    => ['label' => 'Rawat Inap Kelas 3', 'harga' => floatval($barang->kelas3), 'margin' => $calculateMargin($barang->kelas3)],
                    'kelas2'    => ['label' => 'Rawat Inap Kelas 2', 'harga' => floatval($barang->kelas2), 'margin' => $calculateMargin($barang->kelas2)],
                    'kelas1'    => ['label' => 'Rawat Inap Kelas 1', 'harga' => floatval($barang->kelas1), 'margin' => $calculateMargin($barang->kelas1)],
                    'utama'     => ['label' => 'Rawat Inap Utama', 'harga' => floatval($barang->utama), 'margin' => $calculateMargin($barang->utama)],
                    'vip'       => ['label' => 'Rawat Inap VIP', 'harga' => floatval($barang->vip), 'margin' => $calculateMargin($barang->vip)],
                    'vvip'      => ['label' => 'Rawat Inap VVIP', 'harga' => floatval($barang->vvip), 'margin' => $calculateMargin($barang->vvip)],
                ]
            ]
        ]);
    }
}

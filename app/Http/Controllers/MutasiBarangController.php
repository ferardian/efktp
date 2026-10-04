<?php

namespace App\Http\Controllers;

use App\Models\Bangsal;
use App\Models\DataBarang;
use App\Models\MutasiBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MutasiBarangController extends Controller
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
                        $q->where('menus.url', 'farmasi/mutasi')
                          ->orWhere('menus.url', '/farmasi/mutasi');
                    })
                    ->exists();

                if (!in_array($userRole, $allowedRoles) && !$hasMenuAccess) {
                    if ($request->ajax()) {
                        return response()->json(['message' => 'Akses ditolak.'], 403);
                    }
                    return redirect('/')->with('error', 'Anda tidak memiliki hak akses ke halaman mutasi obat.');
                }
            }
            return $next($request);
        });
    }

    /**
     * Halaman Utama Mutasi Obat & BHP
     */
    public function index()
    {
        $bangsal = Bangsal::where('status', '1')->orderBy('nm_bangsal', 'asc')->get();
        return view('content.farmasi.mutasi.index', compact('bangsal'));
    }

    /**
     * DataTables Riwayat Mutasi Barang
     */
    public function data(Request $request)
    {
        $query = DB::table('mutasibarang')
            ->join('databarang', 'mutasibarang.kode_brng', '=', 'databarang.kode_brng')
            ->leftJoin('kodesatuan', 'databarang.kode_sat', '=', 'kodesatuan.kode_sat')
            ->leftJoin('bangsal as b_dari', 'mutasibarang.kd_bangsaldari', '=', 'b_dari.kd_bangsal')
            ->leftJoin('bangsal as b_ke', 'mutasibarang.kd_bangsalke', '=', 'b_ke.kd_bangsal')
            ->select(
                'mutasibarang.kode_brng',
                'databarang.nama_brng',
                'kodesatuan.satuan',
                'mutasibarang.jml',
                'mutasibarang.harga',
                DB::raw('(mutasibarang.jml * mutasibarang.harga) as total_nominal'),
                'mutasibarang.kd_bangsaldari',
                DB::raw('COALESCE(b_dari.nm_bangsal, mutasibarang.kd_bangsaldari) as nm_bangsaldari'),
                'mutasibarang.kd_bangsalke',
                DB::raw('COALESCE(b_ke.nm_bangsal, mutasibarang.kd_bangsalke) as nm_bangsalke'),
                'mutasibarang.tanggal',
                'mutasibarang.keterangan',
                'mutasibarang.no_batch',
                'mutasibarang.no_faktur'
            );

        if ($request->tgl_awal) {
            $query->whereDate('mutasibarang.tanggal', '>=', $request->tgl_awal);
        }
        if ($request->tgl_akhir) {
            $query->whereDate('mutasibarang.tanggal', '<=', $request->tgl_akhir);
        }
        if ($request->kd_bangsaldari) {
            $query->where('mutasibarang.kd_bangsaldari', $request->kd_bangsaldari);
        }
        if ($request->kd_bangsalke) {
            $query->where('mutasibarang.kd_bangsalke', $request->kd_bangsalke);
        }

        $query->orderBy('mutasibarang.tanggal', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($row) {
                return date('d/m/Y H:i', strtotime($row->tanggal));
            })
            ->editColumn('jml', function ($row) {
                return number_format($row->jml, 0, ',', '.') . ' ' . ($row->satuan ?? '');
            })
            ->editColumn('harga', function ($row) {
                return 'Rp ' . number_format($row->harga, 0, ',', '.');
            })
            ->editColumn('total_nominal', function ($row) {
                return 'Rp ' . number_format($row->total_nominal, 0, ',', '.');
            })
            ->addColumn('dari_ke', function ($row) {
                return '<span class="badge bg-secondary-subtle text-secondary border">' . e($row->nm_bangsaldari) . '</span>' .
                       ' <i class="ti ti-arrow-right text-primary"></i> ' .
                       '<span class="badge bg-primary-subtle text-primary border">' . e($row->nm_bangsalke) . '</span>';
            })
            ->addColumn('batch_faktur', function ($row) {
                $batch = !empty($row->no_batch) ? e($row->no_batch) : '-';
                $faktur = !empty($row->no_faktur) ? e($row->no_faktur) : '-';
                return '<small class="text-muted d-block">Batch: ' . $batch . '</small><small class="text-muted d-block">Faktur: ' . $faktur . '</small>';
            })
            ->addColumn('action', function ($row) {
                $tglRaw = $row->tanggal;
                return '<button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" ' .
                       'onclick="batalMutasiItem(\'' . e($row->kode_brng) . '\', \'' . e($row->kd_bangsaldari) . '\', \'' . e($row->kd_bangsalke) . '\', \'' . e($tglRaw) . '\', \'' . e($row->no_batch) . '\', \'' . e($row->no_faktur) . '\', ' . floatval($row->jml) . ')" title="Batalkan / Rollback Mutasi">' .
                       '<i class="ti ti-rotate-2"></i><span>Batal</span></button>';
            })
            ->rawColumns(['dari_ke', 'batch_faktur', 'action'])
            ->make(true);
    }

    /**
     * Cari Obat & Stok di Gudang Asal (untuk modal lookup / dropdown)
     */
    public function getStokAsal(Request $request)
    {
        $request->validate([
            'kd_bangsaldari' => 'required',
            'kd_bangsalke'   => 'required',
        ]);

        $dari = $request->kd_bangsaldari;
        $ke   = $request->kd_bangsalke;
        $q    = $request->q;

        // Ambil stok di gudang asal
        $query = DB::table('gudangbarang')
            ->join('databarang', 'gudangbarang.kode_brng', '=', 'databarang.kode_brng')
            ->leftJoin('kodesatuan', 'databarang.kode_sat', '=', 'kodesatuan.kode_sat')
            ->where('gudangbarang.kd_bangsal', $dari)
            ->where('gudangbarang.stok', '>', 0)
            ->where('databarang.status', '1');

        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('databarang.nama_brng', 'like', "%{$q}%")
                  ->orWhere('databarang.kode_brng', 'like', "%{$q}%")
                  ->orWhere('gudangbarang.no_batch', 'like', "%{$q}%");
            });
        }

        $items = $query->select(
            'databarang.kode_brng',
            'databarang.nama_brng',
            'kodesatuan.satuan',
            'databarang.h_beli',
            'gudangbarang.stok as stok_asal',
            DB::raw('COALESCE(gudangbarang.no_batch, "") as no_batch'),
            DB::raw('COALESCE(gudangbarang.no_faktur, "") as no_faktur')
        )->orderBy('databarang.nama_brng', 'asc')->limit(100)->get();

        // Cari stok di gudang tujuan untuk masing-masing item
        foreach ($items as $item) {
            $stokTujuan = DB::table('gudangbarang')
                ->where('kode_brng', $item->kode_brng)
                ->where('kd_bangsal', $ke)
                ->where('no_batch', $item->no_batch)
                ->where('no_faktur', $item->no_faktur)
                ->value('stok');

            $item->stok_tujuan = floatval($stokTujuan ?? 0);
        }

        return response()->json($items);
    }

    /**
     * Simpan Transaksi Mutasi Barang (Batch Items)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kd_bangsaldari' => 'required',
            'kd_bangsalke'   => 'required|different:kd_bangsaldari',
            'tanggal'        => 'required',
            'keterangan'     => 'required|string|max:60',
            'items'          => 'required|array|min:1',
            'items.*.kode_brng' => 'required',
            'items.*.jml'       => 'required|numeric|min:0.01',
        ], [
            'kd_bangsalke.different' => 'Gudang/Depo tujuan tidak boleh sama dengan gudang asal!',
            'items.min'              => 'Pilih minimal satu barang untuk dimutasi!',
        ]);

        $dari       = $request->kd_bangsaldari;
        $ke         = $request->kd_bangsalke;
        $tglInput   = date('Y-m-d H:i:s', strtotime($request->tanggal));
        $keterangan = trim(substr($request->keterangan, 0, 60));
        $nipInput   = session()->get('pegawai')->nik ?? session()->get('nik') ?? '-';

        $bangsalDari = Bangsal::where('kd_bangsal', $dari)->value('nm_bangsal') ?? $dari;
        $bangsalKe   = Bangsal::where('kd_bangsal', $ke)->value('nm_bangsal') ?? $ke;

        try {
            DB::beginTransaction();

            $savedCount = 0;
            $itemsSummary = [];

            foreach ($request->items as $item) {
                $kode_brng  = $item['kode_brng'];
                $jml        = floatval($item['jml']);
                $no_batch   = substr($item['no_batch'] ?? '', 0, 20);
                $no_faktur  = substr($item['no_faktur'] ?? '', 0, 20);

                if ($jml <= 0) continue;

                // 1. Validasi ketersediaan stok di gudang asal
                $stokGudangAsal = DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $dari)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->lockForUpdate()
                    ->first();

                $currentStokAsal = $stokGudangAsal ? floatval($stokGudangAsal->stok) : 0;
                if ($jml > $currentStokAsal) {
                    $nmBrng = DataBarang::where('kode_brng', $kode_brng)->value('nama_brng') ?? $kode_brng;
                    throw new \Exception("Stok tidak mencukupi untuk {$nmBrng} (Stok: {$currentStokAsal}, Diminta: {$jml})");
                }

                // Ambil harga beli barang
                $h_beli = floatval($item['h_beli'] ?? 0);
                if ($h_beli <= 0) {
                    $h_beli = floatval(DataBarang::where('kode_brng', $kode_brng)->value('h_beli') ?? 0);
                }

                // 2. Insert ke mutasibarang
                DB::table('mutasibarang')->insert([
                    'kode_brng'      => $kode_brng,
                    'jml'            => $jml,
                    'harga'          => $h_beli,
                    'kd_bangsaldari' => $dari,
                    'kd_bangsalke'   => $ke,
                    'tanggal'        => $tglInput,
                    'keterangan'     => $keterangan,
                    'no_batch'       => $no_batch,
                    'no_faktur'      => $no_faktur,
                ]);

                // 3. Potong stok di gudang asal
                $newStokAsal = $currentStokAsal - $jml;
                DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $dari)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->update(['stok' => $newStokAsal]);

                // Catat Riwayat Barang Medis KELUAR (dari gudang asal)
                $this->recordRiwayatMedis(
                    $kode_brng,
                    0,
                    $jml,
                    $currentStokAsal,
                    $newStokAsal,
                    'Mutasi',
                    date('Y-m-d', strtotime($tglInput)),
                    $nipInput,
                    $dari,
                    'Simpan',
                    $no_batch,
                    $no_faktur,
                    "Mutasi keluar ke {$bangsalKe}, ket: {$keterangan}"
                );

                // 4. Tambah stok di gudang tujuan (UPSERT)
                $stokGudangTujuan = DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $ke)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->lockForUpdate()
                    ->first();

                $currentStokTujuan = $stokGudangTujuan ? floatval($stokGudangTujuan->stok) : 0;
                $newStokTujuan     = $currentStokTujuan + $jml;

                if ($stokGudangTujuan) {
                    DB::table('gudangbarang')
                        ->where('kode_brng', $kode_brng)
                        ->where('kd_bangsal', $ke)
                        ->where('no_batch', $no_batch)
                        ->where('no_faktur', $no_faktur)
                        ->update(['stok' => $newStokTujuan]);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng'  => $kode_brng,
                        'kd_bangsal' => $ke,
                        'stok'       => $newStokTujuan,
                        'no_batch'   => $no_batch,
                        'no_faktur'  => $no_faktur,
                    ]);
                }

                // Catat Riwayat Barang Medis MASUK (ke gudang tujuan)
                $this->recordRiwayatMedis(
                    $kode_brng,
                    $jml,
                    0,
                    $currentStokTujuan,
                    $newStokTujuan,
                    'Mutasi',
                    date('Y-m-d', strtotime($tglInput)),
                    $nipInput,
                    $ke,
                    'Simpan',
                    $no_batch,
                    $no_faktur,
                    "Mutasi masuk dari {$bangsalDari}, ket: {$keterangan}"
                );

                $savedCount++;
                $itemsSummary[] = [
                    'kode_brng' => $kode_brng,
                    'jml'       => $jml,
                    'harga'     => $h_beli,
                ];
            }

            if ($savedCount === 0) {
                throw new \Exception('Tidak ada item valid yang dimutasi!');
            }

            DB::commit();

            return response()->json([
                'status'         => 'success',
                'message'        => "Mutasi {$savedCount} obat/BHP dari {$bangsalDari} ke {$bangsalKe} berhasil disimpan.",
                'saved_count'    => $savedCount,
                'kd_bangsaldari' => $dari,
                'kd_bangsalke'   => $ke,
                'tanggal'        => $tglInput,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal simpan mutasi barang: ' . $e->getMessage(), [
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memproses mutasi: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Batalkan / Rollback Transaksi Mutasi
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'kode_brng'      => 'required',
            'kd_bangsaldari' => 'required',
            'kd_bangsalke'   => 'required',
            'tanggal'        => 'required',
        ]);

        $kode_brng = $request->kode_brng;
        $dari      = $request->kd_bangsaldari;
        $ke        = $request->kd_bangsalke;
        $tgl       = $request->tanggal;
        $no_batch  = $request->no_batch ?? '';
        $no_faktur = $request->no_faktur ?? '';
        $nipInput  = session()->get('pegawai')->nik ?? session()->get('nik') ?? '-';

        try {
            DB::beginTransaction();

            $mutasi = DB::table('mutasibarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsaldari', $dari)
                ->where('kd_bangsalke', $ke)
                ->where('tanggal', $tgl)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->first();

            if (!$mutasi) {
                throw new \Exception('Data transaksi mutasi tidak ditemukan!');
            }

            $jml = floatval($mutasi->jml);

            // Validasi sisa stok di lokasi tujuan sebelum ditarik kembali
            $stokTujuan = DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $ke)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->lockForUpdate()
                ->first();

            $currStokTujuan = $stokTujuan ? floatval($stokTujuan->stok) : 0;
            if ($currStokTujuan < $jml) {
                throw new \Exception("Stok di lokasi tujuan telah terpakai (Tersisa: {$currStokTujuan}, Perlu di-rollback: {$jml}). Pembatalan mutasi ditolak.");
            }

            // 1. Tarik stok dari lokasi tujuan
            $newStokTujuan = $currStokTujuan - $jml;
            DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $ke)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->update(['stok' => $newStokTujuan]);

            // Riwayat Rollback Tujuan
            $this->recordRiwayatMedis(
                $kode_brng,
                0,
                $jml,
                $currStokTujuan,
                $newStokTujuan,
                'Mutasi',
                date('Y-m-d'),
                $nipInput,
                $ke,
                'Hapus',
                $no_batch,
                $no_faktur,
                "Pembatalan mutasi dari {$dari}"
            );

            // 2. Kembalikan stok ke lokasi asal
            $stokAsal = DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $dari)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->lockForUpdate()
                ->first();

            $currStokAsal = $stokAsal ? floatval($stokAsal->stok) : 0;
            $newStokAsal  = $currStokAsal + $jml;

            if ($stokAsal) {
                DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $dari)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->update(['stok' => $newStokAsal]);
            } else {
                DB::table('gudangbarang')->insert([
                    'kode_brng'  => $kode_brng,
                    'kd_bangsal' => $dari,
                    'stok'       => $newStokAsal,
                    'no_batch'   => $no_batch,
                    'no_faktur'  => $no_faktur,
                ]);
            }

            // Riwayat Rollback Asal
            $this->recordRiwayatMedis(
                $kode_brng,
                $jml,
                0,
                $currStokAsal,
                $newStokAsal,
                'Mutasi',
                date('Y-m-d'),
                $nipInput,
                $dari,
                'Hapus',
                $no_batch,
                $no_faktur,
                "Kembali dari pembatalan mutasi ke {$ke}"
            );

            // 3. Hapus baris mutasibarang
            DB::table('mutasibarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsaldari', $dari)
                ->where('kd_bangsalke', $ke)
                ->where('tanggal', $tgl)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->delete();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi mutasi berhasil dibatalkan dan stok telah dikembalikan.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal membatalkan mutasi: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membatalkan: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Cetak Bukti / Surat Jalan Mutasi Barang
     */
    public function print(Request $request)
    {
        $request->validate([
            'kd_bangsaldari' => 'required',
            'kd_bangsalke'   => 'required',
            'tanggal'        => 'required',
        ]);

        $dari = $request->kd_bangsaldari;
        $ke   = $request->kd_bangsalke;
        $tgl  = $request->tanggal;

        $setting = DB::table('setting')->first();
        $bangsalDari = Bangsal::where('kd_bangsal', $dari)->first();
        $bangsalKe   = Bangsal::where('kd_bangsal', $ke)->first();

        $items = DB::table('mutasibarang')
            ->join('databarang', 'mutasibarang.kode_brng', '=', 'databarang.kode_brng')
            ->leftJoin('kodesatuan', 'databarang.kode_sat', '=', 'kodesatuan.kode_sat')
            ->where('mutasibarang.kd_bangsaldari', $dari)
            ->where('mutasibarang.kd_bangsalke', $ke)
            ->where('mutasibarang.tanggal', $tgl)
            ->select(
                'mutasibarang.*',
                'databarang.nama_brng',
                'kodesatuan.satuan'
            )->get();

        return view('content.print.buktiMutasiBarang', compact('setting', 'bangsalDari', 'bangsalKe', 'tgl', 'items'));
    }

    /**
     * Helper Audit Trail Kartu Stok (riwayat_barang_medis)
     */
    private function recordRiwayatMedis($kode_brng, $masuk, $keluar, $stokAwal, $stokAkhir, $posisi, $tgl, $petugas, $kd_bangsal, $status, $no_batch, $no_faktur, $keterangan)
    {
        try {
            DB::table('riwayat_barang_medis')->insert([
                'kode_brng'  => $kode_brng,
                'stok_awal'  => max(0, $stokAwal),
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => max(0, $stokAkhir),
                'posisi'     => $posisi,
                'tanggal'    => $tgl,
                'jam'        => date('H:i:s'),
                'petugas'    => $petugas,
                'kd_bangsal' => $kd_bangsal,
                'status'     => $status,
                'no_batch'   => $no_batch,
                'no_faktur'  => $no_faktur,
                'keterangan' => substr($keterangan, 0, 100),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Riwayat barang medis mutasi failed: ' . $e->getMessage());
        }
    }
}

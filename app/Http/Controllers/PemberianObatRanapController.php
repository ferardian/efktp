<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemberianObatRanapController extends Controller
{
    /**
     * Mengambil daftar pemberian obat / BHP pasien rawat inap
     */
    public function getData(Request $request)
    {
        $no_rawat = $request->no_rawat;
        if (!$no_rawat) {
            return response()->json(['message' => 'No Rawat tidak valid'], 400);
        }

        // Cek status billing apakah sudah ditutup/dibayar
        $is_locked = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();

        // Ambil data kamar & bangsal pasien saat ini
        $kamarPasien = DB::table('kamar_inap')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->where('kamar_inap.no_rawat', $no_rawat)
            ->orderByDesc('kamar_inap.tgl_masuk')
            ->orderByDesc('kamar_inap.jam_masuk')
            ->select('kamar.kd_kamar', 'kamar.kelas', 'kamar.kd_bangsal', 'bangsal.nm_bangsal')
            ->first();

        // Ambil default lokasi depo farmasi / apotek untuk pemberian obat / BHP ranap
        $defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (\App\Models\Setting::first()?->kd_bangsal_apotek ?: 'AP');
        if (!DB::table('bangsal')->where('kd_bangsal', $defaultApotek)->exists()) {
            $defaultApotek = DB::table('bangsal')->where('nm_bangsal', 'LIKE', '%apotek%')->value('kd_bangsal') ?: 'AP';
        }
        $defaultKdBangsal = $defaultApotek;

        // Daftar pemberian obat & alkes ranap
        $pemberian = DB::table('detail_pemberian_obat')
            ->join('databarang', 'detail_pemberian_obat.kode_brng', '=', 'databarang.kode_brng')
            ->leftJoin('bangsal', 'detail_pemberian_obat.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->where('detail_pemberian_obat.no_rawat', $no_rawat)
            ->where('detail_pemberian_obat.status', 'Ranap')
            ->orderByDesc('detail_pemberian_obat.tgl_perawatan')
            ->orderByDesc('detail_pemberian_obat.jam')
            ->select(
                'detail_pemberian_obat.tgl_perawatan',
                'detail_pemberian_obat.jam',
                'detail_pemberian_obat.no_rawat',
                'detail_pemberian_obat.kode_brng',
                'databarang.nama_brng',
                'databarang.kode_sat',
                'detail_pemberian_obat.h_beli',
                'detail_pemberian_obat.biaya_obat',
                'detail_pemberian_obat.jml',
                'detail_pemberian_obat.embalase',
                'detail_pemberian_obat.tuslah',
                'detail_pemberian_obat.total',
                'detail_pemberian_obat.kd_bangsal',
                'bangsal.nm_bangsal',
                'detail_pemberian_obat.no_batch',
                'detail_pemberian_obat.no_faktur'
            )
            ->get();

        $totalBiaya = $pemberian->sum('total');

        // Daftar bangsal aktif untuk pilihan sumber stok
        $bangsalList = DB::table('bangsal')
            ->where('status', '1')
            ->orderBy('nm_bangsal')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        return response()->json([
            'status' => 'success',
            'is_locked' => $is_locked,
            'kamar' => $kamarPasien,
            'default_bangsal' => $defaultKdBangsal,
            'bangsal_list' => $bangsalList,
            'total_biaya' => $totalBiaya,
            'data' => $pemberian
        ]);
    }

    /**
     * Pencarian barang / BHP dengan sisa stok bangsal dan penentuan harga kelas ranap
     */
    public function cariBarang(Request $request)
    {
        $q = trim($request->q ?? '');
        $kdBangsal = $request->kd_bangsal ?? 'AP';
        $kelas = $request->kelas ?? '';

        $query = DB::table('databarang')
            ->where('databarang.status', '1');

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('databarang.nama_brng', 'like', "%{$q}%")
                    ->orWhere('databarang.kode_brng', 'like', "%{$q}%");
            });
        }

        $items = $query->limit(30)->get();

        // Ambil stok di bangsal terpilih
        $kodeBrngs = $items->pluck('kode_brng')->toArray();
        $stokMap = DB::table('gudangbarang')
            ->whereIn('kode_brng', $kodeBrngs)
            ->where('kd_bangsal', $kdBangsal)
            ->groupBy('kode_brng')
            ->select('kode_brng', DB::raw('SUM(stok) as total_stok'))
            ->pluck('total_stok', 'kode_brng')
            ->toArray();

        $results = [];
        $kelasLower = strtolower($kelas);

        foreach ($items as $item) {
            // Hitung harga sesuai kelas rawat inap
            $harga = 0;
            if (str_contains($kelasLower, '1')) {
                $harga = floatval($item->kelas1);
            } elseif (str_contains($kelasLower, '2')) {
                $harga = floatval($item->kelas2);
            } elseif (str_contains($kelasLower, '3')) {
                $harga = floatval($item->kelas3);
            } elseif (str_contains($kelasLower, 'vvip')) {
                $harga = floatval($item->vvip);
            } elseif (str_contains($kelasLower, 'vip')) {
                $harga = floatval($item->vip);
            } elseif (str_contains($kelasLower, 'utama')) {
                $harga = floatval($item->utama);
            }

            if ($harga <= 0) {
                $harga = floatval($item->ralan > 0 ? $item->ralan : ($item->h_beli > 0 ? $item->h_beli : 0));
            }

            $currentStok = floatval($stokMap[$item->kode_brng] ?? 0);

            $results[] = [
                'id' => $item->kode_brng,
                'kode_brng' => $item->kode_brng,
                'nama_brng' => $item->nama_brng,
                'kode_sat' => $item->kode_sat ?? '-',
                'h_beli' => floatval($item->h_beli),
                'biaya_obat' => $harga,
                'stok' => $currentStok,
                'text' => "{$item->nama_brng} [{$item->kode_sat}] - Stok: {$currentStok} - Rp " . number_format($harga, 0, ',', '.')
            ];
        }

        return response()->json($results);
    }

    /**
     * Simpan pemberian obat/BHP langsung ke pasien ranap
     */
    public function simpan(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required',
            'kode_brng' => 'required',
            'jml' => 'required|numeric|min:0.01',
            'kd_bangsal' => 'required',
        ]);

        $no_rawat = $request->no_rawat;
        $kode_brng = $request->kode_brng;
        $qty = floatval($request->jml);
        $kdBangsal = $request->kd_bangsal;
        $tgl_perawatan = $request->tgl_perawatan ? date('Y-m-d', strtotime($request->tgl_perawatan)) : date('Y-m-d');
        $jam = $request->jam ?: date('H:i:s');
        $embalase = floatval($request->embalase ?? 0);
        $tuslah = floatval($request->tuslah ?? 0);

        // 1. Cek kunci billing
        $is_locked = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($is_locked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Billing pasien sudah ditutup dan diverifikasi oleh Kasir. Penambahan BHP/Obat tidak diizinkan.'
            ], 422);
        }

        // 2. Ambil data barang
        $barang = DB::table('databarang')->where('kode_brng', $kode_brng)->first();
        if (!$barang) {
            return response()->json(['status' => 'error', 'message' => 'Data barang tidak ditemukan'], 404);
        }

        // Tentukan harga satuan
        $biaya_obat = floatval($request->biaya_obat ?? 0);
        if ($biaya_obat <= 0) {
            $kamarPasien = DB::table('kamar_inap')
                ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->where('kamar_inap.no_rawat', $no_rawat)
                ->orderByDesc('kamar_inap.tgl_masuk')
                ->orderByDesc('kamar_inap.jam_masuk')
                ->select('kamar.kelas')
                ->first();

            $kelasLower = strtolower($kamarPasien->kelas ?? '');
            if (str_contains($kelasLower, '1')) {
                $biaya_obat = floatval($barang->kelas1);
            } elseif (str_contains($kelasLower, '2')) {
                $biaya_obat = floatval($barang->kelas2);
            } elseif (str_contains($kelasLower, '3')) {
                $biaya_obat = floatval($barang->kelas3);
            } elseif (str_contains($kelasLower, 'vvip')) {
                $biaya_obat = floatval($barang->vvip);
            } elseif (str_contains($kelasLower, 'vip')) {
                $biaya_obat = floatval($barang->vip);
            } elseif (str_contains($kelasLower, 'utama')) {
                $biaya_obat = floatval($barang->utama);
            }

            if ($biaya_obat <= 0) {
                $biaya_obat = floatval($barang->ralan > 0 ? $barang->ralan : ($barang->h_beli > 0 ? $barang->h_beli : 0));
            }
        }

        $h_beli = floatval($barang->h_beli);
        $petugas = session('username') ?? 'Admin';

        // 3. Cek total stok di bangsal
        $totalStok = floatval(DB::table('gudangbarang')
            ->where('kode_brng', $kode_brng)
            ->where('kd_bangsal', $kdBangsal)
            ->sum('stok') ?? 0);

        if ($totalStok < $qty) {
            $bangsalInfo = DB::table('bangsal')->where('kd_bangsal', $kdBangsal)->first();
            $namaBangsal = $bangsalInfo ? $bangsalInfo->nm_bangsal : $kdBangsal;
            return response()->json([
                'status' => 'error',
                'message' => "Stok {$barang->nama_brng} di bangsal {$namaBangsal} tidak mencukupi. Tersedia: {$totalStok}, dibutuhkan: {$qty}"
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Potong stok FIFO dari gudangbarang
            $gudangRows = DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $kdBangsal)
                ->where('stok', '>', 0)
                ->orderByRaw("CASE WHEN no_batch = '' OR no_batch IS NULL THEN 0 ELSE 1 END ASC")
                ->orderBy('no_batch', 'ASC')
                ->get();

            $remaining = $qty;
            $deductions = [];

            foreach ($gudangRows as $row) {
                if ($remaining <= 0) break;

                $take = min((float) $row->stok, $remaining);

                DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $kdBangsal)
                    ->where('no_batch', $row->no_batch ?? '')
                    ->where('no_faktur', $row->no_faktur ?? '')
                    ->decrement('stok', $take);

                $deductions[] = [
                    'no_batch'  => $row->no_batch ?? '',
                    'no_faktur' => $row->no_faktur ?? '',
                    'jml'       => $take,
                    'stok_awal' => floatval($row->stok),
                ];

                $remaining -= $take;
            }

            // Fallback jika tidak ada batch ber-stok positif
            if ($remaining > 0) {
                $defaultRow = DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $kdBangsal)
                    ->where('no_batch', '')
                    ->where('no_faktur', '')
                    ->first();

                $stokAwal = $defaultRow ? floatval($defaultRow->stok) : 0;
                if ($defaultRow) {
                    DB::table('gudangbarang')
                        ->where('kode_brng', $kode_brng)
                        ->where('kd_bangsal', $kdBangsal)
                        ->where('no_batch', '')
                        ->where('no_faktur', '')
                        ->decrement('stok', $remaining);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng'  => $kode_brng,
                        'kd_bangsal' => $kdBangsal,
                        'stok'       => -$remaining,
                        'no_batch'   => '',
                        'no_faktur'  => '',
                    ]);
                }

                $deductions[] = [
                    'no_batch'  => '',
                    'no_faktur' => '',
                    'jml'       => $remaining,
                    'stok_awal' => $stokAwal,
                ];
            }

            // Insert ke detail_pemberian_obat & riwayat_barang_medis untuk setiap batch yang terpotong
            $first = true;
            foreach ($deductions as $d) {
                // Embalase & tuslah hanya dikenakan sekali pada baris pertama jika terbagi multi-batch
                $emb = $first ? $embalase : 0;
                $tus = $first ? $tuslah : 0;
                $first = false;

                $subTotal = ($biaya_obat * $d['jml']) + $emb + $tus;

                // Cek apakah record dengan key yang sama sudah ada (jika ada, update jumlah & total)
                $existing = DB::table('detail_pemberian_obat')
                    ->where('tgl_perawatan', $tgl_perawatan)
                    ->where('jam', $jam)
                    ->where('no_rawat', $no_rawat)
                    ->where('kode_brng', $kode_brng)
                    ->where('no_batch', $d['no_batch'])
                    ->where('no_faktur', $d['no_faktur'])
                    ->first();

                if ($existing) {
                    $newJml = floatval($existing->jml) + $d['jml'];
                    $newTotal = floatval($existing->total) + ($biaya_obat * $d['jml']);
                    DB::table('detail_pemberian_obat')
                        ->where('tgl_perawatan', $tgl_perawatan)
                        ->where('jam', $jam)
                        ->where('no_rawat', $no_rawat)
                        ->where('kode_brng', $kode_brng)
                        ->where('no_batch', $d['no_batch'])
                        ->where('no_faktur', $d['no_faktur'])
                        ->update([
                            'jml' => $newJml,
                            'total' => $newTotal,
                        ]);
                } else {
                    DB::table('detail_pemberian_obat')->insert([
                        'tgl_perawatan' => $tgl_perawatan,
                        'jam'           => $jam,
                        'no_rawat'      => $no_rawat,
                        'kode_brng'     => $kode_brng,
                        'h_beli'        => $h_beli,
                        'biaya_obat'    => $biaya_obat,
                        'jml'           => $d['jml'],
                        'embalase'      => $emb,
                        'tuslah'        => $tus,
                        'total'         => $subTotal,
                        'status'        => 'Ranap',
                        'kd_bangsal'    => $kdBangsal,
                        'no_batch'      => $d['no_batch'],
                        'no_faktur'     => $d['no_faktur'],
                    ]);
                }

                // Catat ke riwayat_barang_medis
                DB::table('riwayat_barang_medis')->insert([
                    'kode_brng'  => $kode_brng,
                    'stok_awal'  => $d['stok_awal'],
                    'masuk'      => 0,
                    'keluar'     => $d['jml'],
                    'stok_akhir' => max(0, $d['stok_awal'] - $d['jml']),
                    'posisi'     => 'Pemberian Obat',
                    'tanggal'    => $tgl_perawatan,
                    'jam'        => $jam,
                    'petugas'    => $petugas,
                    'kd_bangsal' => $kdBangsal,
                    'status'     => 'Simpan',
                    'no_batch'   => $d['no_batch'],
                    'no_faktur'  => $d['no_faktur'],
                    'keterangan' => substr("Pemberian Obat/BHP Ranap: {$no_rawat}", 0, 150),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil memberikan {$barang->nama_brng} ({$qty} {$barang->kode_sat})"
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PemberianObatRanapController simpan error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pemberian obat/BHP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus pemberian obat / BHP dan kembalikan stok gudang
     */
    public function hapus(Request $request)
    {
        $request->validate([
            'no_rawat'      => 'required',
            'kode_brng'     => 'required',
            'tgl_perawatan' => 'required',
            'jam'           => 'required',
        ]);

        $no_rawat = $request->no_rawat;
        $kode_brng = $request->kode_brng;
        $tgl_perawatan = $request->tgl_perawatan;
        $jam = $request->jam;
        $no_batch = $request->no_batch ?? '';
        $no_faktur = $request->no_faktur ?? '';

        // 1. Cek kunci billing
        $is_locked = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($is_locked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Billing pasien sudah ditutup/dibayar kasir. Data tidak boleh dihapus.'
            ], 422);
        }

        $row = DB::table('detail_pemberian_obat')
            ->where('no_rawat', $no_rawat)
            ->where('kode_brng', $kode_brng)
            ->where('tgl_perawatan', $tgl_perawatan)
            ->where('jam', $jam)
            ->where('no_batch', $no_batch)
            ->where('no_faktur', $no_faktur)
            ->first();

        if (!$row) {
            return response()->json(['status' => 'error', 'message' => 'Data pemberian obat tidak ditemukan'], 404);
        }

        $qty = floatval($row->jml);
        $kdBangsal = $row->kd_bangsal;
        $petugas = session('username') ?? 'Admin';

        DB::beginTransaction();
        try {
            // 1. Kembalikan stok ke gudangbarang
            $gudang = DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $kdBangsal)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->first();

            $stokAwal = $gudang ? floatval($gudang->stok) : 0;

            if ($gudang) {
                DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $kdBangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->increment('stok', $qty);
            } else {
                DB::table('gudangbarang')->insert([
                    'kode_brng'  => $kode_brng,
                    'kd_bangsal' => $kdBangsal,
                    'stok'       => $qty,
                    'no_batch'   => $no_batch,
                    'no_faktur'  => $no_faktur,
                ]);
            }

            // 2. Catat audit trail ke riwayat_barang_medis
            DB::table('riwayat_barang_medis')->insert([
                'kode_brng'  => $kode_brng,
                'stok_awal'  => $stokAwal,
                'masuk'      => $qty,
                'keluar'     => 0,
                'stok_akhir' => $stokAwal + $qty,
                'posisi'     => 'Pemberian Obat',
                'tanggal'    => date('Y-m-d'),
                'jam'        => date('H:i:s'),
                'petugas'    => $petugas,
                'kd_bangsal' => $kdBangsal,
                'status'     => 'Hapus',
                'no_batch'   => $no_batch,
                'no_faktur'  => $no_faktur,
                'keterangan' => substr("Batal Pemberian BHP/Obat Ranap: {$no_rawat}", 0, 150),
            ]);

            // 3. Hapus dari detail_pemberian_obat
            DB::table('detail_pemberian_obat')
                ->where('no_rawat', $no_rawat)
                ->where('kode_brng', $kode_brng)
                ->where('tgl_perawatan', $tgl_perawatan)
                ->where('jam', $jam)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil membatalkan dan mengembalikan stok pemberian obat/BHP.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PemberianObatRanapController hapus error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pemberian obat: ' . $e->getMessage()
            ], 500);
        }
    }
}

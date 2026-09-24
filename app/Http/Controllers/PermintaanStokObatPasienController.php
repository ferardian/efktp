<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PermintaanStokObatPasienController extends Controller
{
    /**
     * Generate Nomor Permintaan format Khanza: SP + Ymd + 4 digit counter (contoh: SP202609240001)
     */
    public function generateNoPermintaan(): string
    {
        $today = date('Ymd');
        $prefix = 'SP' . $today;

        $lastNo = DB::table('permintaan_stok_obat_pasien')
            ->where('no_permintaan', 'like', $prefix . '%')
            ->orderByDesc('no_permintaan')
            ->value('no_permintaan');

        if ($lastNo) {
            $lastCounter = intval(substr($lastNo, strlen($prefix)));
            $newCounter = str_pad($lastCounter + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newCounter = '0001';
        }

        return $prefix . $newCounter;
    }

    /**
     * Hitung total stok obat di suatu bangsal/depo
     */
    private function getStokObatBangsal(string $kode_brng, string $kd_bangsal): float
    {
        return (float) (DB::table('gudangbarang')
            ->where('kode_brng', $kode_brng)
            ->where('kd_bangsal', $kd_bangsal)
            ->sum('stok') ?? 0);
    }

    /**
     * Hitung harga obat berdasarkan status Ranap dan kelas kamar pasien
     */
    private function calculateHargaObat($obat, string $kelas = ''): float
    {
        $kelasLower = strtolower($kelas);
        $harga = 0;
        if (str_contains($kelasLower, '1')) {
            $harga = floatval($obat->kelas1);
        } elseif (str_contains($kelasLower, '2')) {
            $harga = floatval($obat->kelas2);
        } elseif (str_contains($kelasLower, '3')) {
            $harga = floatval($obat->kelas3);
        } elseif (str_contains($kelasLower, 'vvip')) {
            $harga = floatval($obat->vvip);
        } elseif (str_contains($kelasLower, 'vip')) {
            $harga = floatval($obat->vip);
        } elseif (str_contains($kelasLower, 'utama')) {
            $harga = floatval($obat->utama);
        }

        if ($harga <= 0) {
            $harga = floatval($obat->ralan > 0 ? $obat->ralan : ($obat->h_beli > 0 ? $obat->h_beli : 0));
        }
        return $harga;
    }

    /**
     * Potong stok obat FIFO dari gudangbarang dan catat kartu stok riwayat_barang_medis
     */
    private function deductStokObat(string $kode_brng, string $kd_bangsal, float $qtyToDeduct, string $nama_brng = '', string $keterangan = ''): array
    {
        $totalStok = $this->getStokObatBangsal($kode_brng, $kd_bangsal);
        if ($totalStok < $qtyToDeduct) {
            $name = $nama_brng ?: $kode_brng;
            throw new \Exception("Stok obat \"{$name}\" tidak cukup. Stok saat ini: {$totalStok} unit, dibutuhkan: {$qtyToDeduct}");
        }

        $pegawai = session()->get('pegawai');
        $petugas = $pegawai ? ($pegawai->nik ?? $pegawai->nama ?? 'Admin') : 'Admin';

        $gudangRows = DB::table('gudangbarang')
            ->where('kode_brng', $kode_brng)
            ->where('kd_bangsal', $kd_bangsal)
            ->where('stok', '>', 0)
            ->orderByRaw("CASE WHEN no_batch = '' OR no_batch IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('no_batch', 'ASC')
            ->get();

        $remaining = $qtyToDeduct;
        $deductions = [];

        foreach ($gudangRows as $row) {
            if ($remaining <= 0) break;

            $take = min((float) $row->stok, $remaining);
            $stokAwal = (float) $row->stok;
            $stokAkhir = $stokAwal - $take;

            DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $kd_bangsal)
                ->where('no_batch', $row->no_batch ?? '')
                ->where('no_faktur', $row->no_faktur ?? '')
                ->decrement('stok', $take);

            DB::table('riwayat_barang_medis')->insert([
                'kode_brng'  => $kode_brng,
                'stok_awal'  => $stokAwal,
                'masuk'      => 0,
                'keluar'     => $take,
                'stok_akhir' => $stokAkhir,
                'posisi'     => 'Pemberian Obat',
                'tanggal'    => date('Y-m-d'),
                'jam'        => date('H:i:s'),
                'petugas'    => $petugas,
                'kd_bangsal' => $kd_bangsal,
                'status'     => 'Simpan',
                'no_batch'   => $row->no_batch ?? '',
                'no_faktur'  => $row->no_faktur ?? '',
                'keterangan' => substr($keterangan ?: "Validasi UDD: {$kode_brng}", 0, 150),
            ]);

            $deductions[] = [
                'no_batch'  => $row->no_batch ?? '',
                'no_faktur' => $row->no_faktur ?? '',
                'jml'       => $take,
            ];

            $remaining -= $take;
        }

        if ($remaining > 0) {
            $defaultRow = DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $kd_bangsal)
                ->where('no_batch', '')
                ->where('no_faktur', '')
                ->first();

            $stokAwal = $defaultRow ? (float) $defaultRow->stok : 0;
            $stokAkhir = $stokAwal - $remaining;

            if ($defaultRow) {
                DB::table('gudangbarang')
                    ->where('kode_brng', $kode_brng)
                    ->where('kd_bangsal', $kd_bangsal)
                    ->where('no_batch', '')
                    ->where('no_faktur', '')
                    ->decrement('stok', $remaining);
            } else {
                DB::table('gudangbarang')->insert([
                    'kode_brng'  => $kode_brng,
                    'kd_bangsal' => $kd_bangsal,
                    'stok'       => -$remaining,
                    'no_batch'   => '',
                    'no_faktur'  => '',
                ]);
            }

            DB::table('riwayat_barang_medis')->insert([
                'kode_brng'  => $kode_brng,
                'stok_awal'  => $stokAwal,
                'masuk'      => 0,
                'keluar'     => $remaining,
                'stok_akhir' => $stokAkhir,
                'posisi'     => 'Pemberian Obat',
                'tanggal'    => date('Y-m-d'),
                'jam'        => date('H:i:s'),
                'petugas'    => $petugas,
                'kd_bangsal' => $kd_bangsal,
                'status'     => 'Simpan',
                'no_batch'   => '',
                'no_faktur'  => '',
                'keterangan' => substr($keterangan ?: "Validasi UDD: {$kode_brng}", 0, 150),
            ]);

            $deductions[] = [
                'no_batch'  => '',
                'no_faktur' => '',
                'jml'       => $remaining,
            ];
        }

        return $deductions;
    }

    /**
     * Dapatkan daftar permintaan stok obat pasien:
     * - Jika no_rawat disertakan: untuk CPPT Ranap
     * - Jika filter tgl_awal & tgl_akhir disertakan: untuk Farmasi
     */
    public function get(Request $request)
    {
        $query = DB::table('permintaan_stok_obat_pasien as p')
            ->join('reg_periksa as rp', 'p.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')
            ->leftJoin('dokter as d', 'p.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('kamar_inap as ki', function ($join) {
                $join->on('p.no_rawat', '=', 'ki.no_rawat')
                    ->whereRaw('ki.stts_pulang = "-"');
            })
            ->leftJoin('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->leftJoin('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->select([
                'p.no_permintaan',
                'p.tgl_permintaan',
                'p.jam',
                'p.no_rawat',
                'p.kd_dokter',
                'p.status',
                'p.tgl_validasi',
                'p.jam_validasi',
                'rp.no_rkm_medis',
                'ps.nm_pasien',
                'd.nm_dokter',
                'k.kd_kamar',
                'k.kelas',
                'b.nm_bangsal',
            ]);

        if ($request->no_rawat) {
            $query->where('p.no_rawat', $request->no_rawat);
        } else {
            if ($request->tgl_awal && $request->tgl_akhir) {
                $query->whereBetween('p.tgl_permintaan', [
                    date('Y-m-d', strtotime($request->tgl_awal)),
                    date('Y-m-d', strtotime($request->tgl_akhir)),
                ]);
            } else {
                $query->where('p.tgl_permintaan', date('Y-m-d'));
            }

            if ($request->status && $request->status !== 'semua') {
                $query->where('p.status', $request->status);
            }
        }

        $permintaan = $query->orderByDesc('p.tgl_permintaan')
            ->orderByDesc('p.jam')
            ->get();

        // Ambil item obat untuk setiap permintaan
        $noPermintaanList = $permintaan->pluck('no_permintaan')->toArray();
        $details = DB::table('detail_permintaan_stok_obat_pasien as d')
            ->join('databarang as b', 'd.kode_brng', '=', 'b.kode_brng')
            ->leftJoin('kodesatuan as s', 'b.kode_sat', '=', 's.kode_sat')
            ->whereIn('d.no_permintaan', $noPermintaanList)
            ->select([
                'd.*',
                'b.nama_brng',
                's.satuan',
            ])
            ->get()
            ->groupBy('no_permintaan');

        $result = $permintaan->map(function ($row) use ($details) {
            $row->items = $details->get($row->no_permintaan, collect([]));
            $row->item_count = $row->items->count();
            return $row;
        });

        if ($request->dataTable) {
            return DataTables::of($result)->make(true);
        }

        return response()->json($result);
    }

    /**
     * Dapatkan detail lengkap single permintaan stok obat (untuk modal validasi di farmasi)
     */
    public function getDetail(Request $request)
    {
        $no_permintaan = $request->no_permintaan;
        if (!$no_permintaan) {
            return response()->json(['message' => 'No permintaan tidak boleh kosong'], 400);
        }

        $header = DB::table('permintaan_stok_obat_pasien as p')
            ->join('reg_periksa as rp', 'p.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')
            ->leftJoin('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->leftJoin('dokter as d', 'p.kd_dokter', '=', 'd.kd_dokter')
            ->where('p.no_permintaan', $no_permintaan)
            ->select([
                'p.*',
                'rp.no_rkm_medis',
                'ps.nm_pasien',
                'pj.png_jawab',
                'd.nm_dokter',
            ])
            ->first();

        if (!$header) {
            return response()->json(['message' => 'Data permintaan tidak ditemukan'], 404);
        }

        // Cari kamar inap dan kelas
        $kamarRow = DB::table('kamar_inap')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->where('kamar_inap.no_rawat', $header->no_rawat)
            ->orderByDesc('kamar_inap.tgl_masuk')
            ->orderByDesc('kamar_inap.jam_masuk')
            ->select('kamar.kd_kamar', 'kamar.kelas', 'kamar.kd_bangsal', 'bangsal.nm_bangsal')
            ->first();

        $kamarKelas = $kamarRow ? $kamarRow->kelas : '';
        $kdBangsalKamar = $kamarRow ? $kamarRow->kd_bangsal : null;

        // Tentukan depo farmasi
        $depoRanap = $kdBangsalKamar ? DB::table('set_depo_ranap')->where('kd_bangsal', $kdBangsalKamar)->value('kd_depo') : null;
        $defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
        $bangsal = $depoRanap ?: $defaultApotek;
        $nmBangsalDepo = DB::table('bangsal')->where('kd_bangsal', $bangsal)->value('nm_bangsal') ?? 'Apotek';

        // Ambil detail item obat
        $items = DB::table('detail_permintaan_stok_obat_pasien as d')
            ->join('databarang as b', 'd.kode_brng', '=', 'b.kode_brng')
            ->leftJoin('kodesatuan as s', 'b.kode_sat', '=', 's.kode_sat')
            ->where('d.no_permintaan', $no_permintaan)
            ->select([
                'd.*',
                'b.nama_brng',
                'b.ralan',
                'b.kelas1',
                'b.kelas2',
                'b.kelas3',
                'b.utama',
                'b.vip',
                'b.vvip',
                'b.h_beli',
                's.satuan',
            ])
            ->get();

        $itemsCalculated = $items->map(function ($item) use ($bangsal, $kamarKelas) {
            $item->stok_depo = $this->getStokObatBangsal($item->kode_brng, $bangsal);
            $item->biaya_obat = $this->calculateHargaObat($item, $kamarKelas);
            $item->total = $item->biaya_obat * floatval($item->jml);

            // Ekstrak jam-jam yang true untuk kemudahan render di UI
            $jamList = [];
            for ($i = 0; $i < 24; $i++) {
                $col = 'jam' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (($item->$col ?? 'false') === 'true') {
                    $jamList[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                }
            }
            $item->jadwal_jam = $jamList;
            return $item;
        });

        return response()->json([
            'header' => $header,
            'kamar' => $kamarRow,
            'depo' => [
                'kd_bangsal' => $bangsal,
                'nm_bangsal' => $nmBangsalDepo,
            ],
            'items' => $itemsCalculated,
        ]);
    }

    /**
     * Simpan permintaan stok obat pasien dari Ruang Rawat Inap (Perawat / CPPT)
     */
    public function simpan(Request $request)
    {
        $no_rawat = $request->no_rawat;
        if (!$no_rawat) {
            return response()->json(['message' => 'No. Rawat tidak boleh kosong'], 400);
        }

        // Cek proteksi billing
        $isBilled = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($isBilled) {
            return response()->json(['message' => 'Billing pasien sudah selesai di Kasir. Permintaan stok obat ditolak.'], 422);
        }

        // Validasi dokter
        $kd_dokter = $request->kd_dokter;
        $isDokter = $kd_dokter ? DB::table('dokter')->where('kd_dokter', $kd_dokter)->exists() : false;
        if (!$isDokter) {
            $dpjp = DB::table('dpjp_ranap')->where('no_rawat', $no_rawat)->value('kd_dokter');
            if ($dpjp && DB::table('dokter')->where('kd_dokter', $dpjp)->exists()) {
                $kd_dokter = $dpjp;
            } else {
                $regDokter = DB::table('reg_periksa')->where('no_rawat', $no_rawat)->value('kd_dokter');
                if ($regDokter && DB::table('dokter')->where('kd_dokter', $regDokter)->exists()) {
                    $kd_dokter = $regDokter;
                } else {
                    $kd_dokter = DB::table('dokter')->where('status', '1')->value('kd_dokter');
                }
            }
        }

        if (!$kd_dokter) {
            return response()->json(['message' => 'Dokter DPJP / penanggung jawab tidak ditemukan'], 422);
        }

        $items = $request->items;
        if (empty($items) || !is_array($items)) {
            return response()->json(['message' => 'Pilih minimal satu item obat untuk diajukan'], 422);
        }

        try {
            $no_permintaan = DB::transaction(function () use ($no_rawat, $kd_dokter, $items) {
                $no_permintaan = $this->generateNoPermintaan();
                $tgl_permintaan = date('Y-m-d');
                $jam = date('H:i:s');

                DB::table('permintaan_stok_obat_pasien')->insert([
                    'no_permintaan'  => $no_permintaan,
                    'tgl_permintaan' => $tgl_permintaan,
                    'jam'            => $jam,
                    'no_rawat'       => $no_rawat,
                    'kd_dokter'      => $kd_dokter,
                    'status'         => 'Belum',
                    'tgl_validasi'   => '0000-00-00',
                    'jam_validasi'   => '00:00:00',
                ]);

                $detailsToInsert = [];
                foreach ($items as $item) {
                    $kode_brng = $item['kode_brng'] ?? '';
                    $jml = floatval($item['jml'] ?? 0);
                    $aturan = $item['aturan_pakai'] ?? '';

                    if (!$kode_brng || $jml <= 0) continue;

                    $rowDetail = [
                        'no_permintaan' => $no_permintaan,
                        'kode_brng'     => $kode_brng,
                        'jml'           => $jml,
                        'aturan_pakai'  => $aturan,
                    ];

                    for ($i = 0; $i < 24; $i++) {
                        $col = 'jam' . str_pad($i, 2, '0', STR_PAD_LEFT);
                        $rowDetail[$col] = (!empty($item[$col]) && ($item[$col] === true || $item[$col] === 'true' || $item[$col] === 1 || $item[$col] === '1')) ? 'true' : 'false';
                    }

                    $detailsToInsert[] = $rowDetail;
                }

                if (!empty($detailsToInsert)) {
                    DB::table('detail_permintaan_stok_obat_pasien')->insert($detailsToInsert);
                }

                return $no_permintaan;
            });

            return response()->json([
                'status'        => 'success',
                'message'       => 'Permintaan stok obat pasien berhasil disimpan dan dikirim ke Farmasi',
                'no_permintaan' => $no_permintaan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Hapus permintaan stok obat pasien (hanya jika status masih 'Belum')
     */
    public function hapus(Request $request)
    {
        $no_permintaan = $request->no_permintaan;
        if (!$no_permintaan) {
            return response()->json(['message' => 'No. Permintaan tidak boleh kosong'], 400);
        }

        $permintaan = DB::table('permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->first();
        if (!$permintaan) {
            return response()->json(['message' => 'Data permintaan tidak ditemukan'], 404);
        }

        if ($permintaan->status === 'Sudah') {
            return response()->json(['message' => 'Permintaan sudah divalidasi oleh Farmasi. Batalkan validasi di Farmasi terlebih dahulu.'], 422);
        }

        // Cek proteksi billing
        $isBilled = DB::table('nota_inap')->where('no_rawat', $permintaan->no_rawat)->exists();
        if ($isBilled) {
            return response()->json(['message' => 'Billing pasien sudah selesai di Kasir. Data tidak dapat dihapus.'], 422);
        }

        try {
            DB::transaction(function () use ($no_permintaan) {
                DB::table('detail_permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->delete();
                DB::table('permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Permintaan stok obat berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Validasi Permintaan Stok Obat Pasien oleh Petugas Farmasi:
     * - Potong stok gudangbarang FIFO
     * - Catat riwayat_barang_medis
     * - Masukkan ke detail_pemberian_obat (status = Ranap) & aturan_pakai
     * - Update status permintaan menjadi 'Sudah'
     */
    public function validasi(Request $request)
    {
        $no_permintaan = $request->no_permintaan;
        if (!$no_permintaan) {
            return response()->json(['message' => 'No. Permintaan tidak boleh kosong'], 400);
        }

        $permintaan = DB::table('permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->first();
        if (!$permintaan) {
            return response()->json(['message' => 'Permintaan tidak ditemukan'], 404);
        }

        if ($permintaan->status === 'Sudah') {
            return response()->json(['message' => 'Permintaan ini sudah divalidasi sebelumnya'], 400);
        }

        $no_rawat = $permintaan->no_rawat;

        // Cek proteksi billing terkunci
        $isBilled = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($isBilled) {
            return response()->json(['message' => 'Billing rawat inap pasien sudah diselesaikan di Kasir. Validasi obat ditolak.'], 422);
        }

        // Kamar inap & kelas
        $kamarRow = DB::table('kamar_inap')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->where('kamar_inap.no_rawat', $no_rawat)
            ->orderByDesc('kamar_inap.tgl_masuk')
            ->orderByDesc('kamar_inap.jam_masuk')
            ->select('kamar.kd_kamar', 'kamar.kelas', 'kamar.kd_bangsal', 'bangsal.nm_bangsal')
            ->first();

        $kamarKelas = $kamarRow ? $kamarRow->kelas : '';
        $kdBangsalKamar = $kamarRow ? $kamarRow->kd_bangsal : null;

        // Tentukan depo farmasi
        $depoRanap = $kdBangsalKamar ? DB::table('set_depo_ranap')->where('kd_bangsal', $kdBangsalKamar)->value('kd_depo') : null;
        $defaultApotek = DB::table('set_lokasi')->value('kd_bangsal') ?: (Setting::first()?->kd_bangsal_apotek ?: 'AP');
        $bangsal = $depoRanap ?: $defaultApotek;

        $items = DB::table('detail_permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->get();
        if ($items->isEmpty()) {
            return response()->json(['message' => 'Tidak ada obat di dalam permintaan ini'], 422);
        }

        try {
            DB::transaction(function () use ($no_permintaan, $no_rawat, $bangsal, $kamarKelas, $items) {
                $tgl_validasi = date('Y-m-d');
                $jam_validasi = date('H:i:s');

                $stokPasienToInsert = [];

                foreach ($items as $item) {
                    $obat = DB::table('databarang')->where('kode_brng', $item->kode_brng)->first();
                    if (!$obat) {
                        throw new \Exception("Barang dengan kode {$item->kode_brng} tidak ditemukan");
                    }

                    $qty = floatval($item->jml);
                    if ($qty <= 0) continue;

                    // Potong stok FIFO & catat riwayat_barang_medis
                    $deductions = $this->deductStokObat(
                        $item->kode_brng,
                        $bangsal,
                        $qty,
                        $obat->nama_brng,
                        "Validasi Permintaan Stok {$no_permintaan}: {$no_rawat}"
                    );

                    foreach ($deductions as $d) {
                        $subQty = floatval($d['jml']);

                        $stokPasienToInsert[] = [
                            'tanggal'      => $tgl_validasi,
                            'jam'          => $jam_validasi,
                            'no_rawat'     => $no_rawat,
                            'kode_brng'    => $item->kode_brng,
                            'jumlah'       => $subQty,
                            'kd_bangsal'   => $bangsal,
                            'no_batch'     => $d['no_batch'] ?? '',
                            'no_faktur'    => $d['no_faktur'] ?? '',
                            'aturan_pakai' => $item->aturan_pakai ?? '',
                            'jam00'        => $item->jam00 ?? 'false',
                            'jam01'        => $item->jam01 ?? 'false',
                            'jam02'        => $item->jam02 ?? 'false',
                            'jam03'        => $item->jam03 ?? 'false',
                            'jam04'        => $item->jam04 ?? 'false',
                            'jam05'        => $item->jam05 ?? 'false',
                            'jam06'        => $item->jam06 ?? 'false',
                            'jam07'        => $item->jam07 ?? 'false',
                            'jam08'        => $item->jam08 ?? 'false',
                            'jam09'        => $item->jam09 ?? 'false',
                            'jam10'        => $item->jam10 ?? 'false',
                            'jam11'        => $item->jam11 ?? 'false',
                            'jam12'        => $item->jam12 ?? 'false',
                            'jam13'        => $item->jam13 ?? 'false',
                            'jam14'        => $item->jam14 ?? 'false',
                            'jam15'        => $item->jam15 ?? 'false',
                            'jam16'        => $item->jam16 ?? 'false',
                            'jam17'        => $item->jam17 ?? 'false',
                            'jam18'        => $item->jam18 ?? 'false',
                            'jam19'        => $item->jam19 ?? 'false',
                            'jam20'        => $item->jam20 ?? 'false',
                            'jam21'        => $item->jam21 ?? 'false',
                            'jam22'        => $item->jam22 ?? 'false',
                            'jam23'        => $item->jam23 ?? 'false',
                        ];
                    }
                }

                if (!empty($stokPasienToInsert)) {
                    DB::table('stok_obat_pasien')->insert($stokPasienToInsert);
                }

                // Update status permintaan menjadi 'Sudah'
                DB::table('permintaan_stok_obat_pasien')
                    ->where('no_permintaan', $no_permintaan)
                    ->update([
                        'status'       => 'Sudah',
                        'tgl_validasi' => $tgl_validasi,
                        'jam_validasi' => $jam_validasi,
                    ]);
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Permintaan stok obat pasien berhasil divalidasi. Stok telah diserahkan ke ruangan rawat inap (Stok Obat Pasien UDD).',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Batal Validasi Permintaan Stok Obat Pasien oleh Farmasi:
     * - Cek apakah obat sudah ada yang diberikan di detail_pemberian_obat
     * - Kembalikan stok ke gudangbarang
     * - Catat riwayat_barang_medis dengan status 'Hapus'
     * - Hapus dari stok_obat_pasien
     * - Reset status permintaan ke 'Belum'
     */
    public function batalValidasi(Request $request)
    {
        $no_permintaan = $request->no_permintaan;
        if (!$no_permintaan) {
            return response()->json(['message' => 'No. Permintaan tidak boleh kosong'], 400);
        }

        $permintaan = DB::table('permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->first();
        if (!$permintaan) {
            return response()->json(['message' => 'Permintaan tidak ditemukan'], 404);
        }

        if ($permintaan->status !== 'Sudah') {
            return response()->json(['message' => 'Permintaan belum divalidasi, tidak dapat dibatalkan'], 400);
        }

        $no_rawat = $permintaan->no_rawat;

        // Cek proteksi billing terkunci
        $isBilled = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($isBilled) {
            return response()->json(['message' => 'Billing rawat inap pasien sudah diselesaikan di Kasir. Pembatalan validasi ditolak.'], 422);
        }

        $tgl_validasi = $permintaan->tgl_validasi;
        $jam_validasi = $permintaan->jam_validasi;
        $bangsal = Setting::first()?->kd_bangsal_apotek ?? 'AP';

        // Ambil stok_obat_pasien yang divalidasi pada waktu ini
        $stokPasienRows = DB::table('stok_obat_pasien')
            ->where('no_rawat', $no_rawat)
            ->where('tanggal', $tgl_validasi)
            ->where('jam', $jam_validasi)
            ->get();

        if ($stokPasienRows->isEmpty()) {
            // Coba ambil berdasarkan kode barang permintaan jika jam beda tipis
            $itemKode = DB::table('detail_permintaan_stok_obat_pasien')->where('no_permintaan', $no_permintaan)->pluck('kode_brng');
            $stokPasienRows = DB::table('stok_obat_pasien')
                ->where('no_rawat', $no_rawat)
                ->whereIn('kode_brng', $itemKode)
                ->get();
        }

        // Cek apakah ada obat dari stok pasien ini yang sudah diberikan di detail_pemberian_obat
        $kodeBrngList = $stokPasienRows->pluck('kode_brng')->toArray();
        $sudahDiberikan = DB::table('detail_pemberian_obat')
            ->where('no_rawat', $no_rawat)
            ->whereIn('kode_brng', $kodeBrngList)
            ->where('tgl_perawatan', '>=', $tgl_validasi)
            ->where('status', 'Ranap')
            ->exists();

        if ($sudahDiberikan) {
            return response()->json([
                'message' => 'Sebagian atau seluruh obat pada permintaan ini sudah diberikan oleh perawat ke pasien. Silakan batalkan pemberian obat di tab Beri Obat terlebih dahulu sebelum membatalkan validasi stok.'
            ], 422);
        }

        try {
            DB::transaction(function () use ($no_permintaan, $no_rawat, $stokPasienRows, $bangsal) {
                $pegawai = session()->get('pegawai');
                $petugas = $pegawai ? ($pegawai->nik ?? $pegawai->nama ?? 'Admin') : 'Admin';

                // Kembalikan stok ke gudangbarang & catat riwayat_barang_medis
                foreach ($stokPasienRows as $item) {
                    $targetBangsal = $item->kd_bangsal ?: $bangsal;
                    $gbQuery = DB::table('gudangbarang')
                        ->where('kode_brng', $item->kode_brng)
                        ->where('kd_bangsal', $targetBangsal)
                        ->where('no_batch', $item->no_batch ?? '')
                        ->where('no_faktur', $item->no_faktur ?? '');

                    $currentStock = (float) ($gbQuery->value('stok') ?? 0);
                    $qty = floatval($item->jumlah);

                    if ($gbQuery->exists()) {
                        $gbQuery->increment('stok', $qty);
                    } else {
                        DB::table('gudangbarang')->insert([
                            'kode_brng'  => $item->kode_brng,
                            'kd_bangsal' => $targetBangsal,
                            'stok'       => $qty,
                            'no_batch'   => $item->no_batch ?? '',
                            'no_faktur'  => $item->no_faktur ?? '',
                        ]);
                    }

                    DB::table('riwayat_barang_medis')->insert([
                        'kode_brng'  => $item->kode_brng,
                        'stok_awal'  => $currentStock,
                        'masuk'      => $qty,
                        'keluar'     => 0,
                        'stok_akhir' => $currentStock + $qty,
                        'posisi'     => 'Stok Pasien Ranap',
                        'tanggal'    => date('Y-m-d'),
                        'jam'        => date('H:i:s'),
                        'petugas'    => $petugas,
                        'kd_bangsal' => $targetBangsal,
                        'status'     => 'Hapus',
                        'no_batch'   => $item->no_batch ?? '',
                        'no_faktur'  => $item->no_faktur ?? '',
                        'keterangan' => substr("Batal Validasi Permintaan UDD {$no_permintaan}: {$no_rawat}", 0, 150),
                    ]);
                }

                // Hapus dari stok_obat_pasien
                foreach ($stokPasienRows as $item) {
                    DB::table('stok_obat_pasien')
                        ->where('no_rawat', $no_rawat)
                        ->where('tanggal', $item->tanggal)
                        ->where('jam', $item->jam)
                        ->where('kode_brng', $item->kode_brng)
                        ->where('no_batch', $item->no_batch ?? '')
                        ->where('no_faktur', $item->no_faktur ?? '')
                        ->delete();
                }

                // Reset status permintaan ke 'Belum'
                DB::table('permintaan_stok_obat_pasien')
                    ->where('no_permintaan', $no_permintaan)
                    ->update([
                        'status'       => 'Belum',
                        'tgl_validasi' => '0000-00-00',
                        'jam_validasi' => '00:00:00',
                    ]);
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Validasi permintaan stok obat berhasil dibatalkan. Stok telah dikembalikan ke depo.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Dapatkan daftar stok obat UDD pasien di ruangan beserta riwayat kepatuhan jam pemberian obat
     */
    public function getStokPasien(Request $request)
    {
        $no_rawat = $request->no_rawat;
        if (!$no_rawat) {
            return response()->json(['message' => 'No. Rawat tidak boleh kosong'], 400);
        }

        // Ambil stok obat pasien di ruangan
        $stokList = DB::table('stok_obat_pasien as s')
            ->join('databarang as b', 's.kode_brng', '=', 'b.kode_brng')
            ->leftJoin('kodesatuan as sat', 'b.kode_sat', '=', 'sat.kode_sat')
            ->where('s.no_rawat', $no_rawat)
            ->select([
                's.*',
                'b.nama_brng',
                'b.kode_sat',
                'sat.satuan',
                'b.ralan',
                'b.kelas1',
                'b.kelas2',
                'b.kelas3',
                'b.utama',
                'b.vip',
                'b.vvip',
                'b.h_beli',
            ])
            ->orderBy('s.tanggal', 'DESC')
            ->orderBy('s.jam', 'DESC')
            ->get();

        // Ambil riwayat pemberian obat pasien yang berstatus Ranap
        $pemberianList = DB::table('detail_pemberian_obat as d')
            ->where('d.no_rawat', $no_rawat)
            ->where('d.status', 'Ranap')
            ->select('d.tgl_perawatan', 'd.jam', 'd.kode_brng', 'd.jml', 'd.biaya_obat', 'd.total')
            ->get();

        // Ambil kelas kamar inap pasien
        $kamarRow = DB::table('kamar_inap')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->where('kamar_inap.no_rawat', $no_rawat)
            ->orderByDesc('kamar_inap.tgl_masuk')
            ->orderByDesc('kamar_inap.jam_masuk')
            ->select('kamar.kelas')
            ->first();
        $kamarKelas = $kamarRow ? $kamarRow->kelas : '';

        $results = [];

        foreach ($stokList as $s) {
            $harga = $this->calculateHargaObat($s, $kamarKelas);
            $totalStok = floatval($s->jumlah);

            // Ambil semua pemberian obat untuk barang ini
            $diberikanForThis = $pemberianList->where('kode_brng', $s->kode_brng);
            $totalDiberikan = floatval($diberikanForThis->sum('jml'));

            // Evaluasi setiap jam 00-23
            $jadwal = [];
            for ($h = 0; $h < 24; $h++) {
                $col = 'jam' . str_pad($h, 2, '0', STR_PAD_LEFT);
                if (($s->$col ?? 'false') === 'true') {
                    $jamStr = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00';
                    $jamDisplay = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';

                    // Cek rentang toleransi waktu pemberian (misal 2 jam sekitar jam jadwal)
                    $hMin = max(0, $h - 2);
                    $hMax = min(23, $h + 2);
                    $timeMin = str_pad($hMin, 2, '0', STR_PAD_LEFT) . ':00:00';
                    $timeMax = str_pad($hMax, 2, '0', STR_PAD_LEFT) . ':59:59';

                    $given = $diberikanForThis->first(function ($p) use ($timeMin, $timeMax) {
                        return $p->jam >= $timeMin && $p->jam <= $timeMax;
                    });

                    $statusWaktu = '-';
                    $statusClass = 'secondary';
                    if ($given) {
                        $jamJadwalSec = $h * 3600;
                        $timeParts = explode(':', $given->jam);
                        $gH = intval($timeParts[0] ?? 0);
                        $gM = intval($timeParts[1] ?? 0);
                        $gS = intval($timeParts[2] ?? 0);
                        $jamRiilSec = ($gH * 3600) + ($gM * 60) + $gS;
                        $diffMin = round(($jamRiilSec - $jamJadwalSec) / 60);

                        if (abs($diffMin) <= 30) {
                            $statusWaktu = 'Tepat Waktu';
                            $statusClass = 'success';
                        } elseif ($diffMin > 30) {
                            $statusWaktu = "Terlambat {$diffMin} mnt";
                            $statusClass = 'danger';
                        } else {
                            $early = abs($diffMin);
                            $statusWaktu = "Lebih Awal {$early} mnt";
                            $statusClass = 'warning';
                        }
                    }

                    $jadwal[] = [
                        'jam_jadwal'       => $jamDisplay,
                        'jam_jadwal_full'  => $jamStr,
                        'sudah_diberikan'  => $given ? true : false,
                        'jam_riil'         => $given ? $given->jam : null,
                        'tgl_riil'         => $given ? $given->tgl_perawatan : null,
                        'status_waktu'     => $statusWaktu,
                        'status_class'     => $statusClass,
                        'dosis'            => 1,
                    ];
                }
            }

            $sisaStok = max(0, $totalStok - $totalDiberikan);

            $results[] = [
                'tanggal_validasi' => $s->tanggal,
                'jam_validasi'     => $s->jam,
                'kode_brng'        => $s->kode_brng,
                'nama_brng'        => $s->nama_brng,
                'satuan'           => $s->satuan ?: ($s->kode_sat ?: ''),
                'aturan_pakai'     => $s->aturan_pakai,
                'total_stok'       => $totalStok,
                'total_diberikan'  => $totalDiberikan,
                'sisa_stok'        => $sisaStok,
                'harga'            => $harga,
                'kd_bangsal'       => $s->kd_bangsal,
                'no_batch'         => $s->no_batch,
                'no_faktur'        => $s->no_faktur,
                'jadwal'           => $jadwal,
            ];
        }

        return response()->json($results);
    }

    /**
     * Pelaksanaan Pemberian Obat UDD ke Pasien oleh Perawat:
     * - Memasukkan obat ke detail_pemberian_obat (status = Ranap)
     * - Mencatat jam riil pemberian dan aturan pakai
     * - Otomatis masuk ke billing kamar inap pasien
     */
    public function berikanObatPasien(Request $request)
    {
        $request->validate([
            'no_rawat'   => 'required',
            'kode_brng'  => 'required',
            'jam_jadwal' => 'required',
        ]);

        $no_rawat = $request->no_rawat;
        $kode_brng = $request->kode_brng;
        $jam_jadwal = $request->jam_jadwal;
        $jam_riil = $request->jam_riil ?: date('H:i:s');
        $tgl_perawatan = $request->tgl_perawatan ? date('Y-m-d', strtotime($request->tgl_perawatan)) : date('Y-m-d');
        $jml = floatval($request->jml ?: 1);

        // 1. Cek proteksi billing terkunci
        $isBilled = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($isBilled) {
            return response()->json(['message' => 'Billing rawat inap pasien sudah diselesaikan di Kasir. Pemberian obat ditolak.'], 422);
        }

        // 2. Ambil stok obat pasien di ruangan
        $stokPasien = DB::table('stok_obat_pasien')
            ->where('no_rawat', $no_rawat)
            ->where('kode_brng', $kode_brng)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->first();

        if (!$stokPasien) {
            return response()->json(['message' => 'Stok obat pasien di ruangan tidak ditemukan atau belum divalidasi farmasi.'], 404);
        }

        // Hitung total stok dan yang sudah diberikan untuk proteksi stok ruangan
        $totalStok = DB::table('stok_obat_pasien')
            ->where('no_rawat', $no_rawat)
            ->where('kode_brng', $kode_brng)
            ->sum('jumlah');

        $totalDiberikan = DB::table('detail_pemberian_obat')
            ->where('no_rawat', $no_rawat)
            ->where('kode_brng', $kode_brng)
            ->where('status', 'Ranap')
            ->sum('jml');

        $sisaStok = max(0, $totalStok - $totalDiberikan);
        if ($jml > $sisaStok) {
            return response()->json(['message' => "Jumlah obat yang diberikan ({$jml}) melebihi sisa stok di ruangan ({$sisaStok})."], 422);
        }

        // 3. Ambil data barang & tarif kelas
        $barang = DB::table('databarang')->where('kode_brng', $kode_brng)->first();
        if (!$barang) {
            return response()->json(['message' => 'Data barang tidak ditemukan.'], 404);
        }

        $kamarRow = DB::table('kamar_inap')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->where('kamar_inap.no_rawat', $no_rawat)
            ->orderByDesc('kamar_inap.tgl_masuk')
            ->orderByDesc('kamar_inap.jam_masuk')
            ->select('kamar.kelas')
            ->first();
        $kamarKelas = $kamarRow ? $kamarRow->kelas : '';
        $biaya_obat = $this->calculateHargaObat($barang, $kamarKelas);
        $total = $biaya_obat * $jml;

        DB::beginTransaction();
        try {
            // Hindari duplikasi primary key pada jam_riil yang persis sama
            $exists = DB::table('detail_pemberian_obat')
                ->where('no_rawat', $no_rawat)
                ->where('tgl_perawatan', $tgl_perawatan)
                ->where('jam', $jam_riil)
                ->where('kode_brng', $kode_brng)
                ->exists();

            if ($exists) {
                $jam_riil = date('H:i:s', strtotime($jam_riil) + 1);
            }

            DB::table('detail_pemberian_obat')->insert([
                'tgl_perawatan' => $tgl_perawatan,
                'jam'           => $jam_riil,
                'no_rawat'      => $no_rawat,
                'kode_brng'     => $kode_brng,
                'h_beli'        => floatval($barang->h_beli),
                'biaya_obat'    => $biaya_obat,
                'jml'           => $jml,
                'embalase'      => 0,
                'tuslah'        => 0,
                'total'         => $total,
                'status'        => 'Ranap',
                'kd_bangsal'    => $stokPasien->kd_bangsal ?: 'AP',
                'no_batch'      => $stokPasien->no_batch ?? '',
                'no_faktur'     => $stokPasien->no_faktur ?? '',
            ]);

            $aturanText = $request->aturan_pakai ?: ($stokPasien->aturan_pakai ?: '');
            if (!empty($aturanText)) {
                DB::table('aturan_pakai')->insert([
                    'tgl_perawatan' => $tgl_perawatan,
                    'jam'           => $jam_riil,
                    'no_rawat'      => $no_rawat,
                    'kode_brng'     => $kode_brng,
                    'aturan'        => "Jadwal {$jam_jadwal} WIB: {$aturanText}",
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "Obat {$barang->nama_brng} berhasil diberikan ke pasien (Jam riil: {$jam_riil}, Jadwal: {$jam_jadwal}) dan tercatat di billing.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mencatat pemberian obat: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Batal pemberian obat UDD oleh perawat:
     * - Hapus dari detail_pemberian_obat
     * - Hapus dari aturan_pakai
     * - Tagihan di billing ranap otomatis berkurang
     */
    public function batalBeriObatPasien(Request $request)
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

        $isBilled = DB::table('nota_inap')->where('no_rawat', $no_rawat)->exists();
        if ($isBilled) {
            return response()->json(['message' => 'Billing rawat inap pasien sudah diselesaikan di Kasir. Pembatalan ditolak.'], 422);
        }

        DB::beginTransaction();
        try {
            DB::table('detail_pemberian_obat')
                ->where('no_rawat', $no_rawat)
                ->where('kode_brng', $kode_brng)
                ->where('tgl_perawatan', $tgl_perawatan)
                ->where('jam', $jam)
                ->where('status', 'Ranap')
                ->delete();

            DB::table('aturan_pakai')
                ->where('no_rawat', $no_rawat)
                ->where('kode_brng', $kode_brng)
                ->where('tgl_perawatan', $tgl_perawatan)
                ->where('jam', $jam)
                ->delete();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pemberian obat berhasil dibatalkan dan dihapus dari billing rawat inap.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membatalkan pemberian: ' . $e->getMessage()], 500);
        }
    }
}

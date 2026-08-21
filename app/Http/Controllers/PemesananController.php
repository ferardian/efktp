<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPesan;
use App\Models\DataBatch;
use App\Models\DataBarang;
use App\Models\Bangsal;
use App\Models\DataSuplier;
use App\Models\Petugas;
use App\Models\SuratPemesananMedis;
use App\Models\DetailSuratPemesananMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    public function index()
    {
        $suplier = DataSuplier::orderBy('nama_suplier', 'asc')->get();
        $bangsal = Bangsal::where('status', '1')->orderBy('nm_bangsal', 'asc')->get();
        $petugas = Petugas::where('status', '1')->orderBy('nama', 'asc')->get();
        
        return view('content.farmasi.penerimaan', compact('suplier', 'bangsal', 'petugas'));
    }

    public function data(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');

        $query = Pemesanan::with(['suplier', 'bangsal', 'petugas'])
            ->whereBetween('tgl_faktur', [$tglAwal, $tglAkhir])
            ->orderBy('tgl_faktur', 'desc');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                  ->orWhere('no_order', 'like', "%{$search}%")
                  ->orWhereHas('suplier', function($qs) use ($search) {
                      $qs->where('nama_suplier', 'like', "%{$search}%");
                  });
            });
        }

        $data = $query->get();
        return response()->json($data);
    }

    /**
     * Get full penerimaan data for edit form
     */
    public function editData($no_faktur)
    {
        $pemesanan = Pemesanan::with(['suplier', 'bangsal', 'petugas', 'detail.barang.satuan'])
            ->where('no_faktur', $no_faktur)
            ->first();

        if (!$pemesanan) {
            return response()->json(['message' => 'Data penerimaan tidak ditemukan'], 404);
        }

        if ($pemesanan->detail && count($pemesanan->detail) > 0) {
            foreach ($pemesanan->detail as $item) {
                $batch = DataBatch::where('no_faktur', $no_faktur)
                    ->where('kode_brng', $item->kode_brng)
                    ->where('no_batch', $item->no_batch)
                    ->first();

                if ($batch) {
                    $item->ralan = $batch->ralan;
                    $item->kelas1 = $batch->kelas1;
                    $item->kelas2 = $batch->kelas2;
                    $item->kelas3 = $batch->kelas3;
                    $item->utama = $batch->utama;
                    $item->vip = $batch->vip;
                    $item->vvip = $batch->vvip;
                    $item->karyawan = $batch->karyawan;
                    $item->beliluar = $batch->beliluar;
                    $item->jualbebas = $batch->jualbebas;
                } else if ($item->barang) {
                    $item->ralan = $item->barang->ralan;
                    $item->kelas1 = $item->barang->kelas1;
                    $item->kelas2 = $item->barang->kelas2;
                    $item->kelas3 = $item->barang->kelas3;
                    $item->utama = $item->barang->utama;
                    $item->vip = $item->barang->vip;
                    $item->vvip = $item->barang->vvip;
                    $item->karyawan = $item->barang->karyawan;
                    $item->beliluar = $item->barang->beliluar;
                    $item->jualbebas = $item->barang->jualbebas;
                }
            }
        }

        return response()->json($pemesanan);
    }

    /**
     * Auto generate No. Faktur Penerimaan (Format: PB + YYYYMMDD + 3 digit sequence)
     */
    public function getNextNoFaktur(Request $request)
    {
        $tgl = $request->tgl_faktur ?? date('Y-m-d');
        $dateFormatted = date('Ymd', strtotime($tgl));
        $prefix = 'PB' . $dateFormatted;

        $last = DB::table('pemesanan')
            ->whereDate('tgl_faktur', $tgl)
            ->orderBy('no_faktur', 'desc')
            ->first();

        $nextSeq = 1;
        if ($last && !empty($last->no_faktur)) {
            $lastNum = intval(substr($last->no_faktur, -3));
            if ($lastNum > 0) {
                $nextSeq = $lastNum + 1;
            }
        }

        $noFaktur = $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
        return response()->json(['no_faktur' => $noFaktur]);
    }

    /**
     * Get list of Surat Pemesanan Medis (SP Order)
     */
    public function getSuratPemesananList(Request $request)
    {
        $query = SuratPemesananMedis::with('suplier')
            ->where('status', 'Proses Pesan')
            ->orderBy('tanggal', 'desc');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pemesanan', 'like', "%{$search}%")
                  ->orWhereHas('suplier', function($qs) use ($search) {
                      $qs->where('nama_suplier', 'like', "%{$search}%");
                  });
            });
        }

        return response()->json($query->get());
    }

    /**
     * Get detail items of a Surat Pemesanan Medis (SP Order)
     */
    public function getSuratPemesananDetail($no_order)
    {
        $sp = SuratPemesananMedis::with(['suplier', 'detail.barang.satuan'])
            ->where('no_pemesanan', $no_order)
            ->first();

        if (!$sp) {
            return response()->json(['message' => 'Data Surat Pemesanan (SP) tidak ditemukan'], 404);
        }

        return response()->json($sp);
    }

    /**
     * Auto calculate selling prices based on setpenjualan margin % and round-up
     */
    public function calculatePrices(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required',
            'h_beli' => 'required|numeric',
        ]);

        $kode_brng = $request->kode_brng;
        $h_beli = floatval($request->h_beli);
        $ppn_percent = floatval($request->ppn_percent ?? 0);

        $barang = DataBarang::where('kode_brng', $kode_brng)->first();
        if (!$barang) {
            return response()->json(['message' => 'Obat tidak ditemukan'], 404);
        }

        $h_dasar = $h_beli + (($ppn_percent / 100) * $h_beli);
        $setpenjualan = DB::table('setpenjualan')->where('kdjns', $barang->kdjns)->first();

        $roundUp = function ($val, $step = 100) {
            if ($val <= 0) return 0;
            return ceil($val / $step) * $step;
        };

        if ($setpenjualan) {
            $prices = [
                'ralan'     => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->ralan) / 100))),
                'kelas1'    => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->kelas1) / 100))),
                'kelas2'    => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->kelas2) / 100))),
                'kelas3'    => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->kelas3) / 100))),
                'utama'     => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->utama) / 100))),
                'vip'       => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->vip) / 100))),
                'vvip'      => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->vvip) / 100))),
                'beliluar'  => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->beliluar) / 100))),
                'jualbebas' => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->jualbebas) / 100))),
                'karyawan'  => $roundUp($h_dasar + ($h_dasar * (floatval($setpenjualan->karyawan) / 100))),
            ];
        } else {
            $defaultPrice = $roundUp($h_dasar * 1.20);
            $prices = [
                'ralan'     => $defaultPrice,
                'kelas1'    => $defaultPrice,
                'kelas2'    => $defaultPrice,
                'kelas3'    => $defaultPrice,
                'utama'     => $defaultPrice,
                'vip'       => $defaultPrice,
                'vvip'      => $defaultPrice,
                'beliluar'  => $defaultPrice,
                'jualbebas' => $defaultPrice,
                'karyawan'  => $defaultPrice,
            ];
        }

        return response()->json([
            'h_beli'  => $h_beli,
            'h_dasar' => $h_dasar,
            'prices'  => $prices
        ]);
    }

    public function store(Request $request)
    {
        $isEdit = filter_var($request->is_edit ?? false, FILTER_VALIDATE_BOOLEAN);
        $originalNoFaktur = trim($request->original_no_faktur ?? '');

        if ($isEdit && !empty($originalNoFaktur)) {
            $request->validate([
                'no_faktur' => 'required',
                'kode_suplier' => 'required',
                'kd_bangsal' => 'required',
                'tgl_faktur' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.kode_brng' => 'required',
                'items.*.jumlah' => 'required|numeric|min:0.01',
                'items.*.h_beli' => 'required|numeric',
            ]);

            if ($request->no_faktur !== $originalNoFaktur) {
                if (Pemesanan::where('no_faktur', $request->no_faktur)->exists()) {
                    return response()->json(['message' => 'No. Faktur baru sudah digunakan pada transaksi lain'], 422);
                }
            }
        } else {
            $request->validate([
                'no_faktur' => 'required|unique:pemesanan,no_faktur',
                'kode_suplier' => 'required',
                'kd_bangsal' => 'required',
                'tgl_faktur' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.kode_brng' => 'required',
                'items.*.jumlah' => 'required|numeric|min:0.01',
                'items.*.h_beli' => 'required|numeric',
            ]);
        }

        try {
            DB::beginTransaction();

            // Reverse previous record if editing
            if ($isEdit && !empty($originalNoFaktur)) {
                $prevPemesanan = Pemesanan::where('no_faktur', $originalNoFaktur)->first();
                if ($prevPemesanan) {
                    $prevDetails = DetailPesan::where('no_faktur', $originalNoFaktur)->get();
                    $nipRevert = session()->get('pegawai')->nik ?? session()->get('nik') ?? '-';

                    foreach ($prevDetails as $pDetail) {
                        $pJumlah2 = floatval($pDetail->jumlah2 > 0 ? $pDetail->jumlah2 : $pDetail->jumlah);
                        $pBatch = trim($pDetail->no_batch ?? '');

                        $this->recordRiwayatMedis(
                            $pDetail->kode_brng,
                            0,
                            $pJumlah2,
                            'Penerimaan',
                            date('Y-m-d'),
                            $nipRevert,
                            $prevPemesanan->kd_bangsal,
                            'Hapus',
                            $pBatch,
                            $originalNoFaktur,
                            'Revisi Penerimaan ' . $originalNoFaktur
                        );

                        DB::table('gudangbarang')
                            ->where('kode_brng', $pDetail->kode_brng)
                            ->where('kd_bangsal', $prevPemesanan->kd_bangsal)
                            ->where('no_batch', $pBatch)
                            ->where('no_faktur', $originalNoFaktur)
                            ->decrement('stok', $pJumlah2);
                    }

                    $this->deleteJurnalPenerimaan($originalNoFaktur);

                    DetailPesan::where('no_faktur', $originalNoFaktur)->delete();
                    DataBatch::where('no_faktur', $originalNoFaktur)->delete();
                    $prevPemesanan->delete();
                    DB::table('gudangbarang')->where('stok', '<=', 0)->delete();
                }
            }

            // Validate NIP Petugas against petugas table for Foreign Key integrity
            $nipInput = $request->nip ?? session()->get('pegawai')->nik ?? session()->get('nik') ?? '';
            $petugasObj = Petugas::where('nip', $nipInput)->first();
            if (!$petugasObj) {
                // Fallback to first active petugas if nip is missing or 'spv'
                $petugasObj = Petugas::where('status', '1')->first() ?? Petugas::first();
            }
            $nip = $petugasObj ? $petugasObj->nip : '-';

            $suplier = DataSuplier::where('kode_suplier', $request->kode_suplier)->first();
            $namaSuplier = $suplier ? $suplier->nama_suplier : $request->kode_suplier;

            // 1. Save Header
            $pemesanan = Pemesanan::create([
                'no_faktur'    => $request->no_faktur,
                'no_order'     => $request->no_order ?? '',
                'kode_suplier' => $request->kode_suplier,
                'nip'          => $nip,
                'tgl_pesan'    => $request->tgl_pesan ?? $request->tgl_faktur,
                'tgl_faktur'   => $request->tgl_faktur,
                'tgl_tempo'    => $request->tgl_tempo ?? $request->tgl_faktur,
                'total1'       => $request->total1 ?? 0,
                'potongan'     => $request->potongan ?? 0,
                'total2'       => $request->total2 ?? 0,
                'ppn'          => $request->ppn ?? 0,
                'meterai'      => $request->meterai ?? 0,
                'tagihan'      => $request->tagihan ?? 0,
                'kd_bangsal'   => $request->kd_bangsal,
                'status'       => 'Belum Dibayar'
            ]);

            // 2. Save Details, Batch, Stock, Audit Trail
            foreach ($request->items as $item) {
                $barang = DataBarang::where('kode_brng', $item['kode_brng'])->first();
                $isi = ($barang && floatval($barang->isi) > 0) ? floatval($barang->isi) : 1;
                
                $jumlahBeli = floatval($item['jumlah']);
                $jumlah2 = $jumlahBeli * $isi; // Unit conversion to base unit

                $subtotal = floatval($item['h_beli']) * $jumlahBeli;
                $dis = floatval($item['dis'] ?? 0);
                $besardis = ($dis / 100) * $subtotal;
                $total = $subtotal - $besardis;
                $no_batch = trim($item['no_batch'] ?? '');
                $kadaluarsa = !empty($item['kadaluarsa']) ? $item['kadaluarsa'] : '1900-01-01';

                DetailPesan::create([
                    'no_faktur'  => $request->no_faktur,
                    'kode_brng'  => $item['kode_brng'],
                    'kode_sat'   => $item['kode_sat'] ?? ($barang->kode_sat ?? ''),
                    'jumlah'     => $jumlahBeli,
                    'h_pesan'    => $item['h_beli'],
                    'subtotal'   => $subtotal,
                    'dis'        => $dis,
                    'besardis'   => $besardis,
                    'total'      => $total,
                    'no_batch'   => $no_batch,
                    'jumlah2'    => $jumlah2,
                    'kadaluarsa' => $kadaluarsa
                ]);

                // 3. Save Data Batch
                DataBatch::create([
                    'no_batch'       => $no_batch,
                    'kode_brng'      => $item['kode_brng'],
                    'tgl_beli'       => $request->tgl_faktur,
                    'tgl_kadaluarsa' => $kadaluarsa,
                    'asal'           => 'Penerimaan',
                    'no_faktur'      => $request->no_faktur,
                    'dasar'          => $item['h_beli'],
                    'h_beli'         => $item['h_beli'],
                    'ralan'          => $item['ralan'] ?? 0,
                    'kelas1'         => $item['kelas1'] ?? 0,
                    'kelas2'         => $item['kelas2'] ?? 0,
                    'kelas3'         => $item['kelas3'] ?? 0,
                    'utama'          => $item['utama'] ?? 0,
                    'vip'            => $item['vip'] ?? 0,
                    'vvip'           => $item['vvip'] ?? 0,
                    'beliluar'       => $item['beliluar'] ?? 0,
                    'jualbebas'      => $item['jualbebas'] ?? 0,
                    'karyawan'       => $item['karyawan'] ?? 0,
                    'jumlahbeli'     => $jumlah2,
                    'sisa'           => $jumlah2
                ]);

                // 4. Update prices in databarang
                DataBarang::where('kode_brng', $item['kode_brng'])->update([
                    'h_beli'    => $item['h_beli'],
                    'dasar'     => $item['h_beli'],
                    'ralan'     => $item['ralan'] ?? 0,
                    'kelas1'    => $item['kelas1'] ?? 0,
                    'kelas2'    => $item['kelas2'] ?? 0,
                    'kelas3'    => $item['kelas3'] ?? 0,
                    'utama'     => $item['utama'] ?? 0,
                    'vip'       => $item['vip'] ?? 0,
                    'vvip'      => $item['vvip'] ?? 0,
                    'beliluar'  => $item['beliluar'] ?? 0,
                    'jualbebas' => $item['jualbebas'] ?? 0,
                    'karyawan'  => $item['karyawan'] ?? 0,
                    'expire'    => $kadaluarsa
                ]);

                // 5. Audit Trail: Record in riwayat_barang_medis BEFORE incrementing stock
                $this->recordRiwayatMedis(
                    $item['kode_brng'],
                    $jumlah2,
                    0,
                    'Penerimaan',
                    $request->tgl_faktur,
                    $nip,
                    $request->kd_bangsal,
                    'Simpan',
                    $no_batch,
                    $request->no_faktur,
                    $request->no_faktur . ' ' . ($request->no_order ?? '') . ' ' . $namaSuplier
                );

                // 6. Update/Insert gudangbarang stock
                $gudang = DB::table('gudangbarang')
                    ->where('kode_brng', $item['kode_brng'])
                    ->where('kd_bangsal', $request->kd_bangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $request->no_faktur)
                    ->first();

                if ($gudang) {
                    DB::table('gudangbarang')
                        ->where('kode_brng', $item['kode_brng'])
                        ->where('kd_bangsal', $request->kd_bangsal)
                        ->where('no_batch', $no_batch)
                        ->where('no_faktur', $request->no_faktur)
                        ->increment('stok', $jumlah2);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng'  => $item['kode_brng'],
                        'kd_bangsal' => $request->kd_bangsal,
                        'stok'       => $jumlah2,
                        'no_batch'   => $no_batch,
                        'no_faktur'  => $request->no_faktur
                    ]);
                }
            }

            // 7. Auto Post Journal Akuntansi
            $this->postJurnalPenerimaan(
                $request->no_faktur,
                $request->tgl_faktur,
                $request->kd_bangsal,
                floatval($request->total2 ?? 0),
                floatval($request->ppn ?? 0),
                floatval($request->meterai ?? 0),
                floatval($request->tagihan ?? 0),
                $nip
            );

            // 8. Update SP status if loaded from SP
            if (!empty($request->no_order)) {
                SuratPemesananMedis::where('no_pemesanan', $request->no_order)
                    ->update(['status' => 'Sudah Datang']);
            }

            DB::commit();
            return response()->json(['message' => 'Transaksi penerimaan obat berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan penerimaan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($no_faktur)
    {
        try {
            DB::beginTransaction();

            $pemesanan = Pemesanan::where('no_faktur', $no_faktur)->firstOrFail();
            $details = DetailPesan::where('no_faktur', $no_faktur)->get();

            $nipInput = session()->get('pegawai')->nik ?? session()->get('nik') ?? '';
            $petugasObj = Petugas::where('nip', $nipInput)->first();
            if (!$petugasObj) {
                $petugasObj = Petugas::where('status', '1')->first() ?? Petugas::first();
            }
            $nip = $petugasObj ? $petugasObj->nip : '-';

            // Reverse stock & audit trail
            foreach ($details as $detail) {
                $jumlah2 = floatval($detail->jumlah2 > 0 ? $detail->jumlah2 : $detail->jumlah);
                $no_batch = trim($detail->no_batch ?? '');

                // Audit trail: Record deletion in riwayat_barang_medis
                $this->recordRiwayatMedis(
                    $detail->kode_brng,
                    0,
                    $jumlah2,
                    'Penerimaan',
                    date('Y-m-d'),
                    $nip,
                    $pemesanan->kd_bangsal,
                    'Hapus',
                    $no_batch,
                    $no_faktur,
                    'Batal Penerimaan ' . $no_faktur
                );

                DB::table('gudangbarang')
                    ->where('kode_brng', $detail->kode_brng)
                    ->where('kd_bangsal', $pemesanan->kd_bangsal)
                    ->where('no_batch', $no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->decrement('stok', $jumlah2);
            }

            // Reverse/Delete Jurnal Entries
            $this->deleteJurnalPenerimaan($no_faktur);

            // Revert SP Status if applicable
            if (!empty($pemesanan->no_order)) {
                SuratPemesananMedis::where('no_pemesanan', $pemesanan->no_order)
                    ->update(['status' => 'Proses Pesan']);
            }

            // Remove detail, batch records and header record
            DetailPesan::where('no_faktur', $no_faktur)->delete();
            DataBatch::where('no_faktur', $no_faktur)->delete();
            $pemesanan->delete();

            // Clean up 0 stock entries
            DB::table('gudangbarang')->where('stok', '<=', 0)->delete();

            DB::commit();
            return response()->json(['message' => 'Transaksi penerimaan obat berhasil dibatalkan/dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus penerimaan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membatalkan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function detail(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|string'
        ]);

        $details = DetailPesan::with(['barang.satuan'])
            ->where('no_faktur', $request->no_faktur)
            ->get();

        return response()->json($details);
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    private function generateNoJurnal($date)
    {
        $dateFormatted = date('Ymd', strtotime($date));
        $count = DB::table('jurnal')->whereDate('tgl_jurnal', $date)->count();
        do {
            $count++;
            $noJurnal = 'JR' . $dateFormatted . str_pad($count, 6, '0', STR_PAD_LEFT);
        } while (DB::table('jurnal')->where('no_jurnal', $noJurnal)->exists());
        return $noJurnal;
    }

    private function postJurnalPenerimaan($no_faktur, $tgl_faktur, $kd_bangsal, $total2, $ppn, $meterai, $tagihan, $nip)
    {
        try {
            $setAkun = DB::table('set_akun')->first();
            if (!$setAkun || empty($setAkun->Pemesanan_Obat) || empty($setAkun->Kontra_Pemesanan_Obat)) {
                return;
            }

            $bangsal = Bangsal::where('kd_bangsal', $kd_bangsal)->first();
            $nmGudang = $bangsal ? strtoupper($bangsal->nm_bangsal) : $kd_bangsal;

            DB::table('tampjurnal')->delete();

            // 1. Debet Persediaan
            DB::table('tampjurnal')->insert([
                'kd_rek' => $setAkun->Pemesanan_Obat,
                'nm_rek' => 'PERSEDIAAN BARANG',
                'debet'  => $total2 + $meterai,
                'kredit' => 0,
            ]);

            // 2. Debet PPN Masukan jika ada PPN
            if ($ppn > 0 && !empty($setAkun->PPN_Masukan)) {
                DB::table('tampjurnal')->insert([
                    'kd_rek' => $setAkun->PPN_Masukan,
                    'nm_rek' => 'PPN Masukan Obat',
                    'debet'  => $ppn,
                    'kredit' => 0,
                ]);
            }

            // 3. Kredit Hutang Usaha / Pembelian
            DB::table('tampjurnal')->insert([
                'kd_rek' => $setAkun->Kontra_Pemesanan_Obat,
                'nm_rek' => 'HUTANG USAHA',
                'debet'  => 0,
                'kredit' => $tagihan,
            ]);

            $noJurnal = $this->generateNoJurnal($tgl_faktur);
            $keterangan = "PENERIMAAN BARANG DI {$nmGudang}, OLEH {$nip}";

            DB::table('jurnal')->insert([
                'no_jurnal'  => $noJurnal,
                'no_bukti'   => $no_faktur,
                'tgl_jurnal' => $tgl_faktur,
                'jam_jurnal' => date('H:i:s'),
                'jenis'      => 'U',
                'keterangan' => $keterangan,
            ]);

            $tamp = DB::table('tampjurnal')->get();
            foreach ($tamp as $t) {
                DB::table('detailjurnal')->insert([
                    'no_jurnal' => $noJurnal,
                    'kd_rek'    => $t->kd_rek,
                    'debet'     => $t->debet,
                    'kredit'    => $t->kredit,
                ]);
            }
            DB::table('tampjurnal')->delete();
        } catch (\Throwable $e) {
            Log::warning('Journal posting for penerimaan failed: ' . $e->getMessage());
        }
    }

    private function deleteJurnalPenerimaan($no_faktur)
    {
        try {
            $jurnals = DB::table('jurnal')->where('no_bukti', $no_faktur)->pluck('no_jurnal');
            if ($jurnals->count() > 0) {
                DB::table('detailjurnal')->whereIn('no_jurnal', $jurnals)->delete();
                DB::table('jurnal')->whereIn('no_jurnal', $jurnals)->delete();
            }
        } catch (\Throwable $e) {
            Log::warning('Journal deletion for penerimaan failed: ' . $e->getMessage());
        }
    }

    private function recordRiwayatMedis($kode_brng, $masuk, $keluar, $posisi, $tgl, $petugas, $kd_bangsal, $status, $no_batch, $no_faktur, $keterangan)
    {
        try {
            $gudang = DB::table('gudangbarang')
                ->where('kode_brng', $kode_brng)
                ->where('kd_bangsal', $kd_bangsal)
                ->where('no_batch', $no_batch)
                ->where('no_faktur', $no_faktur)
                ->first();

            $stokAwal = $gudang ? floatval($gudang->stok) : 0;
            $stokAkhir = ($status === 'Simpan') ? ($stokAwal + $masuk - $keluar) : ($stokAwal - $keluar);

            DB::table('riwayat_barang_medis')->insert([
                'kode_brng'  => $kode_brng,
                'stok_awal'  => $stokAwal,
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
            Log::warning('Riwayat barang medis record failed: ' . $e->getMessage());
        }
    }
}

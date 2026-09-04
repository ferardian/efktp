<?php

namespace App\Http\Controllers;

use App\Models\AkunBayar;
use App\Models\Bangsal;
use App\Models\DataBarang;
use App\Models\DetailJual;
use App\Models\Pasien;
use App\Models\Penjualan;
use App\Models\Petugas;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $bangsal = Bangsal::where('status', '1')
            ->orWhereNull('status')
            ->orderBy('nm_bangsal', 'ASC')
            ->get();

        $petugas = Petugas::where('status', '1')
            ->orderBy('nama', 'ASC')
            ->get();

        $akunBayar = AkunBayar::orderBy('nama_bayar', 'ASC')->get();
        if ($akunBayar->isEmpty()) {
            // Fallback default cash account if table is empty
            $akunBayar = collect([
                (object)['nama_bayar' => 'Bayar Cash', 'kd_rek' => '111010', 'ppn' => 0]
            ]);
        }

        $currentNip = session()->get('pegawai')->nik ?? session()->get('nik') ?? (Petugas::first()->nip ?? '-');
        $today = date('Y-m-d');
        $nextNota = $this->generateNextNota($today);

        return view('content.farmasi.penjualan', compact(
            'bangsal',
            'petugas',
            'akunBayar',
            'currentNip',
            'today',
            'nextNota'
        ));
    }

    public function getNextNota(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $nota = $this->generateNextNota($tanggal);
        return response()->json(['next_nota' => $nota]);
    }

    private function generateNextNota($tanggal)
    {
        $cleanDate = str_replace('-', '', $tanggal);
        $prefix = "PJ" . $cleanDate;

        $latest = Penjualan::where('tgl_jual', $tanggal)
            ->where('nota_jual', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(SUBSTRING(nota_jual, 11) AS UNSIGNED)) as max_num")
            ->first();

        $nextNum = ($latest && $latest->max_num) ? ($latest->max_num + 1) : 1;
        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function data(Request $request)
    {
        $query = Penjualan::with(['detailJual', 'petugas', 'bangsal', 'pasien']);

        if ($request->has('tgl_awal') && $request->has('tgl_akhir')) {
            $query->whereBetween('tgl_jual', [$request->tgl_awal, $request->tgl_akhir]);
        } else {
            $query->where('tgl_jual', date('Y-m-d'));
        }

        if ($request->has('status') && $request->status !== 'semua' && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('search_keyword') && !empty($request->search_keyword)) {
            $kw = $request->search_keyword;
            $query->where(function($q) use ($kw) {
                $q->where('nota_jual', 'like', "%{$kw}%")
                  ->orWhere('nm_pasien', 'like', "%{$kw}%")
                  ->orWhere('no_rkm_medis', 'like', "%{$kw}%")
                  ->orWhere('keterangan', 'like', "%{$kw}%")
                  ->orWhere('nama_bayar', 'like', "%{$kw}%");
            });
        }

        $penjualan = $query->orderBy('nota_jual', 'DESC')->get();

        return DataTables::of($penjualan)
            ->addColumn('total_obat', function ($row) {
                return $row->detailJual->sum('total');
            })
            ->addColumn('grand_total', function ($row) {
                $totObat = $row->detailJual->sum('total');
                $ongkir = floatval($row->ongkir ?? 0);
                $ppn = floatval($row->ppn ?? 0);
                return $totObat + $ongkir + $ppn;
            })
            ->make(true);
    }

    public function searchObat(Request $request)
    {
        $term = trim($request->term ?? $request->q ?? '');
        $kdBangsal = $request->kd_bangsal ?? 'AP';

        $query = DataBarang::with(['satuan', 'satuanBesar', 'jenis', 'kategori'])
            ->where('status', '1');

        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('kode_brng', 'like', "%{$term}%")
                  ->orWhere('nama_brng', 'like', "%{$term}%");
            });
        }

        $items = $query->orderBy('nama_brng', 'ASC')->limit(50)->get();

        $results = $items->map(function ($item) use ($kdBangsal) {
            // Ambil stok dari gudangbarang sesuai bangsal yang dipilih
            $stokGudang = DB::table('gudangbarang')
                ->where('kode_brng', $item->kode_brng)
                ->where('kd_bangsal', $kdBangsal)
                ->sum('stok');

            $satuanKecil = $item->satuan->satuan ?? '-';
            $satuanBesar = $item->satuanBesar->satuan ?? null;
            $isi = floatval($item->isi ?: 1);

            return [
                'kode_brng'     => $item->kode_brng,
                'nama_brng'     => $item->nama_brng,
                'kode_sat'      => $item->kode_sat,
                'satuan'        => $satuanKecil,
                'kode_satbesar' => $item->kode_satbesar,
                'satuan_besar'  => $satuanBesar,
                'isi'           => $isi,
                'kapasitas'     => $item->kapasitas ?: '-',
                'stok'          => floatval($stokGudang ?: 0),
                'h_beli'        => floatval($item->h_beli ?: $item->dasar ?: 0),
                'dasar'         => floatval($item->dasar ?: $item->h_beli ?: 0),
                'jualbebas'     => floatval($item->jualbebas ?: 0),
                'karyawan'      => floatval($item->karyawan ?: 0),
                'beliluar'      => floatval($item->beliluar ?: 0),
                'ralan'         => floatval($item->ralan ?: 0),
                'kelas1'        => floatval($item->kelas1 ?: 0),
                'kelas2'        => floatval($item->kelas2 ?: 0),
                'kelas3'        => floatval($item->kelas3 ?: 0),
                'utama'         => floatval($item->utama ?: 0),
                'vip'           => floatval($item->vip ?: 0),
                'vvip'          => floatval($item->vvip ?: 0),
            ];
        });

        return response()->json($results);
    }

    public function searchPasien(Request $request)
    {
        $term = trim($request->term ?? $request->q ?? '');
        if (empty($term)) {
            return response()->json([]);
        }

        $pasien = Pasien::where('no_rkm_medis', 'like', "%{$term}%")
            ->orWhere('nm_pasien', 'like', "%{$term}%")
            ->orWhere('no_ktp', 'like', "%{$term}%")
            ->limit(20)
            ->get(['no_rkm_medis', 'nm_pasien', 'alamat', 'jk', 'tgl_lahir', 'no_tlp']);

        return response()->json($pasien);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_jual'   => 'required|date',
            'kd_bangsal' => 'required',
            'jns_jual'   => 'required',
            'items'      => 'required|array|min:1',
            'items.*.kode_brng' => 'required',
            'items.*.jumlah'    => 'required|numeric|min:0.01',
            'items.*.h_jual'    => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $tglJual = $request->tgl_jual;
            $notaJual = $request->nota_jual;

            // Generate nomor nota jika kosong atau sudah pernah ada
            if (empty($notaJual) || Penjualan::where('nota_jual', $notaJual)->exists()) {
                $notaJual = $this->generateNextNota($tglJual);
            }

            // Validasi NIP Petugas
            $nipInput = $request->nip ?? session()->get('pegawai')->nik ?? session()->get('nik') ?? '';
            $petugasObj = Petugas::where('nip', $nipInput)->first();
            if (!$petugasObj) {
                $petugasObj = Petugas::where('status', '1')->first() ?? Petugas::first();
            }
            $nip = $petugasObj ? $petugasObj->nip : '-';

            // Akun bayar & rekening
            $namaBayar = $request->nama_bayar ?? 'Bayar Cash';
            $kdRek = $request->kd_rek ?? '111010';
            $akun = AkunBayar::where('nama_bayar', $namaBayar)->first();
            if ($akun) {
                $kdRek = $akun->kd_rek;
            }

            $ongkir = floatval($request->ongkir ?? 0);
            $ppn = floatval($request->ppn ?? 0);
            $pembulatan = floatval($request->pembulatan ?? 0);
            $status = $request->status ?? 'Sudah Dibayar';

            // Catatan keterangan jika ada pembulatan
            $keterangan = $request->keterangan ?: '-';
            if ($pembulatan != 0) {
                $sign = $pembulatan > 0 ? '+' : '';
                $bulatText = "Pembulatan: {$sign}" . number_format($pembulatan, 0, ',', '.');
                $keterangan = ($keterangan === '-' ? '' : $keterangan . ' | ') . $bulatText;
            }

            // 1. Simpan Header Penjualan
            $penjualan = Penjualan::create([
                'nota_jual'    => $notaJual,
                'tgl_jual'     => $tglJual,
                'nip'          => $nip,
                'no_rkm_medis' => $request->no_rkm_medis ?: '-',
                'nm_pasien'    => $request->nm_pasien ?: 'UMUM',
                'keterangan'   => $keterangan,
                'jns_jual'     => $request->jns_jual,
                'ongkir'       => $ongkir,
                'ppn'          => $ppn,
                'status'       => $status,
                'kd_bangsal'   => $request->kd_bangsal,
                'kd_rek'       => $kdRek,
                'nama_bayar'   => $namaBayar,
            ]);

            $totalObat = 0;
            $totalHpp = 0;

            // 2. Simpan Detail Jual, Kurangi Stok Gudang & Catat Riwayat
            foreach ($request->items as $item) {
                $kodeBrng = $item['kode_brng'];
                $jumlah = floatval($item['jumlah']);
                $hJual = floatval($item['h_jual']);
                $hBeli = floatval($item['h_beli'] ?? 0);
                $dis = floatval($item['dis'] ?? 0);
                $subtotal = $jumlah * $hJual;
                $bsrDis = floatval($item['bsr_dis'] ?? (($subtotal * $dis) / 100));
                $tambahan = floatval($item['tambahan'] ?? 0);
                $embalase = floatval($item['embalase'] ?? 0);
                $tuslah = floatval($item['tuslah'] ?? 0);
                $itemTotal = $subtotal - $bsrDis + $tambahan + $embalase + $tuslah;
                $kodeSat = $item['kode_sat'] ?? '-';
                $aturanPakai = $item['aturan_pakai'] ?? '';
                $noBatch = $item['no_batch'] ?? '';
                $noFaktur = $item['no_faktur'] ?? '';

                $totalObat += $itemTotal;
                $totalHpp += ($jumlah * $hBeli);

                // Insert detailjual
                DetailJual::create([
                    'nota_jual'    => $notaJual,
                    'kode_brng'    => $kodeBrng,
                    'kode_sat'     => $kodeSat,
                    'h_jual'       => $hJual,
                    'h_beli'       => $hBeli,
                    'jumlah'       => $jumlah,
                    'subtotal'     => $subtotal,
                    'dis'          => $dis,
                    'bsr_dis'      => $bsrDis,
                    'tambahan'     => $tambahan,
                    'embalase'     => $embalase,
                    'tuslah'       => $tuslah,
                    'aturan_pakai' => $aturanPakai,
                    'total'        => $itemTotal,
                    'no_batch'     => $noBatch,
                    'no_faktur'    => $noFaktur,
                ]);

                // Kurangi stok di gudangbarang
                $gudangQuery = DB::table('gudangbarang')
                    ->where('kode_brng', $kodeBrng)
                    ->where('kd_bangsal', $request->kd_bangsal);

                if (!empty($noBatch)) {
                    $gudangQuery->where('no_batch', $noBatch);
                }
                if (!empty($noFaktur)) {
                    $gudangQuery->where('no_faktur', $noFaktur);
                }

                $gudang = $gudangQuery->first();
                if ($gudang) {
                    $gudangQuery->decrement('stok', $jumlah);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng'  => $kodeBrng,
                        'kd_bangsal' => $request->kd_bangsal,
                        'stok'       => -$jumlah,
                        'no_batch'   => $noBatch,
                        'no_faktur'  => $noFaktur,
                    ]);
                }

                // Catat Riwayat Barang Medis (Audit Trail)
                $this->recordRiwayatMedis(
                    $kodeBrng,
                    0,
                    $jumlah,
                    'Penjualan',
                    $tglJual,
                    $nip,
                    $request->kd_bangsal,
                    'Simpan',
                    $noBatch,
                    $noFaktur,
                    $notaJual . ' ' . ($request->no_rkm_medis ?: '-') . ' ' . ($request->nm_pasien ?: 'UMUM')
                );
            }

            // 3. Posting Jurnal Akuntansi (jika status Lunas/Sudah Dibayar)
            if ($status === 'Sudah Dibayar') {
                $totalNet = $totalObat + $ongkir + $ppn;
                $grandTotal = $totalNet + $pembulatan;
                $this->postJurnalPenjualan(
                    $notaJual,
                    $tglJual,
                    $request->kd_bangsal,
                    $kdRek,
                    $namaBayar,
                    $grandTotal,
                    $totalNet,
                    $pembulatan,
                    $totalHpp,
                    $nip
                );
            }

            DB::commit();

            return response()->json([
                'message'   => 'Transaksi penjualan obat berhasil disimpan',
                'nota_jual' => $notaJual
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan penjualan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function detail($nota_jual)
    {
        $penjualan = Penjualan::with(['detailJual.barang.satuan', 'petugas', 'bangsal', 'pasien'])
            ->where('nota_jual', $nota_jual)
            ->firstOrFail();

        $totalObat = $penjualan->detailJual->sum('total');
        $grandTotal = $totalObat + floatval($penjualan->ongkir ?? 0) + floatval($penjualan->ppn ?? 0);

        return response()->json([
            'penjualan'   => $penjualan,
            'total_obat'  => $totalObat,
            'grand_total' => $grandTotal
        ]);
    }

    public function destroy($nota_jual)
    {
        try {
            DB::beginTransaction();

            $penjualan = Penjualan::where('nota_jual', $nota_jual)->firstOrFail();
            $details = DetailJual::where('nota_jual', $nota_jual)->get();

            $nipInput = session()->get('pegawai')->nik ?? session()->get('nik') ?? '';
            $petugasObj = Petugas::where('nip', $nipInput)->first();
            if (!$petugasObj) {
                $petugasObj = Petugas::where('status', '1')->first() ?? Petugas::first();
            }
            $nip = $petugasObj ? $petugasObj->nip : '-';

            // Reversal Stok & Catat Riwayat Pembatalan
            foreach ($details as $detail) {
                $gudangQuery = DB::table('gudangbarang')
                    ->where('kode_brng', $detail->kode_brng)
                    ->where('kd_bangsal', $penjualan->kd_bangsal);

                if (!empty($detail->no_batch)) {
                    $gudangQuery->where('no_batch', $detail->no_batch);
                }
                if (!empty($detail->no_faktur)) {
                    $gudangQuery->where('no_faktur', $detail->no_faktur);
                }

                $gudang = $gudangQuery->first();
                if ($gudang) {
                    $gudangQuery->increment('stok', $detail->jumlah);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng'  => $detail->kode_brng,
                        'kd_bangsal' => $penjualan->kd_bangsal,
                        'stok'       => $detail->jumlah,
                        'no_batch'   => $detail->no_batch ?? '',
                        'no_faktur'  => $detail->no_faktur ?? '',
                    ]);
                }

                $this->recordRiwayatMedis(
                    $detail->kode_brng,
                    $detail->jumlah,
                    0,
                    'Penjualan',
                    date('Y-m-d'),
                    $nip,
                    $penjualan->kd_bangsal,
                    'Hapus',
                    $detail->no_batch ?? '',
                    $detail->no_faktur ?? '',
                    'Batal Penjualan ' . $nota_jual
                );
            }

            // Hapus Jurnal Akuntansi
            $this->deleteJurnalPenjualan($nota_jual);

            // Hapus Detail & Header Penjualan
            DetailJual::where('nota_jual', $nota_jual)->delete();
            $penjualan->delete();

            DB::commit();

            return response()->json(['message' => 'Transaksi penjualan berhasil dibatalkan/dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus penjualan: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membatalkan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function printNota($nota_jual)
    {
        $penjualan = Penjualan::with(['detailJual.barang.satuan', 'petugas', 'bangsal', 'pasien'])
            ->where('nota_jual', $nota_jual)
            ->firstOrFail();

        $setting = Setting::first();
        $totalObat = $penjualan->detailJual->sum('total');

        // Deteksi pembulatan dari keterangan
        $pembulatan = 0;
        if (!empty($penjualan->keterangan) && preg_match('/Pembulatan:\s*([+-]?\d+[\d\.]*)/', $penjualan->keterangan, $matches)) {
            $cleaned = str_replace('.', '', $matches[1]);
            $pembulatan = floatval($cleaned);
        }

        $grandTotal = $totalObat + floatval($penjualan->ongkir ?? 0) + floatval($penjualan->ppn ?? 0) + $pembulatan;

        return view('content.print.notaPenjualan', compact(
            'penjualan',
            'setting',
            'totalObat',
            'pembulatan',
            'grandTotal'
        ));
    }

    private function postJurnalPenjualan($notaJual, $tglJual, $kdBangsal, $kdRek, $namaBayar, $grandTotal, $totalNetObat, $pembulatan, $totalHpp, $nip)
    {
        try {
            $setAkun = DB::table('set_akun')->first();
            if (!$setAkun || empty($setAkun->Penjualan_Obat)) {
                return;
            }

            $bangsal = Bangsal::where('kd_bangsal', $kdBangsal)->first();
            $nmGudang = $bangsal ? strtoupper($bangsal->nm_bangsal) : $kdBangsal;

            DB::table('tampjurnal')->delete();

            // 1. Debet: Akun Bayar Kas/Bank (sebesar uang yang diterima kasir / grandTotal dibulatkan)
            DB::table('tampjurnal')->insert([
                'kd_rek' => $kdRek,
                'nm_rek' => $namaBayar,
                'debet'  => $grandTotal,
                'kredit' => 0,
            ]);

            // 2. Kredit: Penjualan Obat Bebas (sebesar nilai transaksi obat sebenarnya + ongkir + ppn)
            DB::table('tampjurnal')->insert([
                'kd_rek' => $setAkun->Penjualan_Obat,
                'nm_rek' => 'PENJUALAN OBAT BEBAS',
                'debet'  => 0,
                'kredit' => $totalNetObat,
            ]);

            // 3. Selisih Pembulatan (Rounding)
            if ($pembulatan > 0) {
                // Pembulatan ke atas diakui sebagai PENDAPATAN LAIN-LAIN / PEMBULATAN di sisi Kredit
                DB::table('tampjurnal')->insert([
                    'kd_rek' => '430107',
                    'nm_rek' => 'PENDAPATAN PEMBULATAN PENJUALAN',
                    'debet'  => 0,
                    'kredit' => $pembulatan,
                ]);
            } elseif ($pembulatan < 0) {
                // Pembulatan ke bawah diakui sebagai BEBAN LAIN-LAIN / PEMBULATAN di sisi Debet
                DB::table('tampjurnal')->insert([
                    'kd_rek' => '5103',
                    'nm_rek' => 'BEBAN PEMBULATAN PENJUALAN',
                    'debet'  => abs($pembulatan),
                    'kredit' => 0,
                ]);
            }

            // 3. Debet: HPP Obat Jual Bebas
            if (!empty($setAkun->HPP_Obat_Jual_Bebas) && $totalHpp > 0) {
                DB::table('tampjurnal')->insert([
                    'kd_rek' => $setAkun->HPP_Obat_Jual_Bebas,
                    'nm_rek' => 'HPP OBAT JUAL BEBAS',
                    'debet'  => $totalHpp,
                    'kredit' => 0,
                ]);
            }

            // 4. Kredit: Persediaan Obat Jual Bebas
            if (!empty($setAkun->Persediaan_Obat_Jual_Bebas) && $totalHpp > 0) {
                DB::table('tampjurnal')->insert([
                    'kd_rek' => $setAkun->Persediaan_Obat_Jual_Bebas,
                    'nm_rek' => 'PERSEDIAAN OBAT JUAL BEBAS',
                    'debet'  => 0,
                    'kredit' => $totalHpp,
                ]);
            }

            $noJurnal = $this->generateNoJurnal($tglJual);
            $keterangan = "PENJUALAN DI {$nmGudang}, OLEH {$nip}";

            DB::table('jurnal')->insert([
                'no_jurnal'  => $noJurnal,
                'no_bukti'   => $notaJual,
                'tgl_jurnal' => $tglJual,
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
            Log::warning('Journal posting for penjualan failed: ' . $e->getMessage());
        }
    }

    private function deleteJurnalPenjualan($notaJual)
    {
        try {
            $jurnals = DB::table('jurnal')->where('no_bukti', $notaJual)->pluck('no_jurnal');
            if ($jurnals->count() > 0) {
                DB::table('detailjurnal')->whereIn('no_jurnal', $jurnals)->delete();
                DB::table('jurnal')->whereIn('no_jurnal', $jurnals)->delete();
            }
        } catch (\Throwable $e) {
            Log::warning('Journal deletion for penjualan failed: ' . $e->getMessage());
        }
    }

    private function generateNoJurnal($tanggal)
    {
        $ymd = str_replace('-', '', $tanggal);
        $prefix = "JR" . $ymd;
        $latest = DB::table('jurnal')
            ->where('no_jurnal', 'like', $prefix . '%')
            ->orderBy('no_jurnal', 'desc')
            ->first();

        if ($latest) {
            $lastNum = intval(substr($latest->no_jurnal, -6));
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
    }

    private function recordRiwayatMedis($kodeBrng, $masuk, $keluar, $posisi, $tgl, $petugas, $kdBangsal, $status, $noBatch, $noFaktur, $keterangan)
    {
        try {
            $gudang = DB::table('gudangbarang')
                ->where('kode_brng', $kodeBrng)
                ->where('kd_bangsal', $kdBangsal);

            if (!empty($noBatch)) {
                $gudang->where('no_batch', $noBatch);
            }
            if (!empty($noFaktur)) {
                $gudang->where('no_faktur', $noFaktur);
            }

            $firstGudang = $gudang->first();
            $stokAwal = $firstGudang ? floatval($firstGudang->stok) : 0;
            $stokAkhir = ($status === 'Simpan') ? ($stokAwal - $keluar) : ($stokAwal + $masuk);

            DB::table('riwayat_barang_medis')->insert([
                'kode_brng'  => $kodeBrng,
                'stok_awal'  => $stokAwal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => max(0, $stokAkhir),
                'posisi'     => $posisi,
                'tanggal'    => $tgl,
                'jam'        => date('H:i:s'),
                'petugas'    => $petugas,
                'kd_bangsal' => $kdBangsal,
                'status'     => $status,
                'no_batch'   => $noBatch ?: '',
                'no_faktur'  => $noFaktur ?: '',
                'keterangan' => substr($keterangan, 0, 100),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Riwayat barang medis record failed: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPesan;
use App\Models\DataBatch;
use App\Models\DataBarang;
use App\Models\Bangsal;
use App\Models\DataSuplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function index()
    {
        $suplier = DataSuplier::orderBy('nama_suplier', 'asc')->get();
        $bangsal = Bangsal::where('status', '1')->orderBy('nm_bangsal', 'asc')->get();
        
        return view('content.farmasi.penerimaan', compact('suplier', 'bangsal'));
    }

    public function data(Request $request)
    {
        $data = Pemesanan::with(['suplier', 'bangsal'])
            ->orderBy('tgl_faktur', 'desc')
            ->get();
            
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|unique:pemesanan,no_faktur',
            'kode_suplier' => 'required',
            'kd_bangsal' => 'required',
            'tgl_faktur' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.kode_brng' => 'required',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.h_beli' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $nip = session()->get('pegawai')->nik ?? '-';

            // 1. Save Header
            $pemesanan = Pemesanan::create([
                'no_faktur' => $request->no_faktur,
                'no_order' => $request->no_order ?? '',
                'kode_suplier' => $request->kode_suplier,
                'nip' => $nip,
                'tgl_pesan' => $request->tgl_pesan ?? $request->tgl_faktur,
                'tgl_faktur' => $request->tgl_faktur,
                'tgl_tempo' => $request->tgl_tempo ?? $request->tgl_faktur,
                'total1' => $request->total1 ?? 0,
                'potongan' => $request->potongan ?? 0,
                'total2' => $request->total2 ?? 0,
                'ppn' => $request->ppn ?? 0,
                'meterai' => $request->meterai ?? 0,
                'tagihan' => $request->tagihan ?? 0,
                'kd_bangsal' => $request->kd_bangsal,
                'status' => 'Belum Lunas'
            ]);

            // 2. Save Details
            foreach ($request->items as $item) {
                $subtotal = floatval($item['h_beli']) * floatval($item['jumlah']);
                $dis = floatval($item['dis'] ?? 0);
                $besardis = ($dis / 100) * $subtotal;
                $total = $subtotal - $besardis;

                DetailPesan::create([
                    'no_faktur' => $request->no_faktur,
                    'kode_brng' => $item['kode_brng'],
                    'kode_sat' => $item['kode_sat'],
                    'jumlah' => $item['jumlah'],
                    'h_pesan' => $item['h_beli'],
                    'subtotal' => $subtotal,
                    'dis' => $dis,
                    'besardis' => $besardis,
                    'total' => $total,
                    'no_batch' => $item['no_batch'] ?? '',
                    'jumlah2' => $item['jumlah'],
                    'kadaluarsa' => $item['kadaluarsa'] ?? '1900-01-01'
                ]);

                // 3. Save Data Batch
                DataBatch::create([
                    'no_batch' => $item['no_batch'] ?? '',
                    'kode_brng' => $item['kode_brng'],
                    'tgl_beli' => $request->tgl_faktur,
                    'tgl_kadaluarsa' => $item['kadaluarsa'] ?? '1900-01-01',
                    'asal' => 'Pembelian',
                    'no_faktur' => $request->no_faktur,
                    'dasar' => $item['h_beli'],
                    'h_beli' => $item['h_beli'],
                    'ralan' => $item['ralan'] ?? 0,
                    'kelas1' => $item['kelas1'] ?? 0,
                    'kelas2' => $item['kelas2'] ?? 0,
                    'kelas3' => $item['kelas3'] ?? 0,
                    'utama' => $item['utama'] ?? 0,
                    'vip' => $item['vip'] ?? 0,
                    'vvip' => $item['vvip'] ?? 0,
                    'beliluar' => $item['beliluar'] ?? 0,
                    'jualbebas' => $item['jualbebas'] ?? 0,
                    'karyawan' => $item['karyawan'] ?? 0,
                    'jumlahbeli' => $item['jumlah'],
                    'sisa' => $item['jumlah']
                ]);

                // 4. Update prices in databarang
                DataBarang::where('kode_brng', $item['kode_brng'])->update([
                    'h_beli' => $item['h_beli'],
                    'dasar' => $item['h_beli'],
                    'ralan' => $item['ralan'] ?? 0,
                    'kelas1' => $item['kelas1'] ?? 0,
                    'kelas2' => $item['kelas2'] ?? 0,
                    'kelas3' => $item['kelas3'] ?? 0,
                    'utama' => $item['utama'] ?? 0,
                    'vip' => $item['vip'] ?? 0,
                    'vvip' => $item['vvip'] ?? 0,
                    'beliluar' => $item['beliluar'] ?? 0,
                    'jualbebas' => $item['jualbebas'] ?? 0,
                    'karyawan' => $item['karyawan'] ?? 0,
                    'expire' => $item['kadaluarsa'] ?? '1900-01-01'
                ]);

                // 5. Update/Insert gudangbarang stock
                $no_batch = $item['no_batch'] ?? '';
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
                        ->increment('stok', $item['jumlah']);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng' => $item['kode_brng'],
                        'kd_bangsal' => $request->kd_bangsal,
                        'stok' => $item['jumlah'],
                        'no_batch' => $no_batch,
                        'no_faktur' => $request->no_faktur
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Transaksi penerimaan obat berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($no_faktur)
    {
        try {
            DB::beginTransaction();

            $pemesanan = Pemesanan::where('no_faktur', $no_faktur)->firstOrFail();
            $details = DetailPesan::where('no_faktur', $no_faktur)->get();

            // Reverse stock
            foreach ($details as $detail) {
                DB::table('gudangbarang')
                    ->where('kode_brng', $detail->kode_brng)
                    ->where('kd_bangsal', $pemesanan->kd_bangsal)
                    ->where('no_batch', $detail->no_batch)
                    ->where('no_faktur', $no_faktur)
                    ->decrement('stok', $detail->jumlah);
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LaporanPenjualanItemController extends Controller
{
    /**
     * Tampilan Utama Halaman Laporan Penjualan Per Item
     */
    public function index(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');
        $jenisList = Jenis::orderBy('nama', 'asc')->get();

        return view('content.farmasi.laporanPenjualanItem', compact('tglAwal', 'tglAkhir', 'jenisList'));
    }

    /**
     * Data JSON untuk DataTables & Summary Cards
     */
    public function data(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');
        $sumber = $request->sumber ?? 'semua'; // 'semua', 'bebas', 'resep'
        $kdjns = $request->kdjns ?? '';

        $query = $this->buildReportQuery($tglAwal, $tglAkhir, $sumber, $kdjns);

        // Hitung Summary Keseluruhan (tanpa pagination)
        $summaryData = DB::table(DB::raw("({$query->toSql()}) as report_summary"))
            ->mergeBindings($query)
            ->selectRaw("
                COUNT(*) as total_items,
                COALESCE(SUM(total_qty), 0) as grand_total_qty,
                COALESCE(SUM(qty_bebas), 0) as grand_qty_bebas,
                COALESCE(SUM(qty_resep), 0) as grand_qty_resep,
                COALESCE(SUM(total_hpp), 0) as grand_total_hpp,
                COALESCE(SUM(total_jual), 0) as grand_total_jual,
                COALESCE(SUM(laba_kotor), 0) as grand_laba_kotor
            ")
            ->first();

        $grandHpp = floatval($summaryData->grand_total_hpp ?? 0);
        $grandLaba = floatval($summaryData->grand_laba_kotor ?? 0);
        $avgMargin = $grandHpp > 0 ? round(($grandLaba / $grandHpp) * 100, 2) : 0;

        $summary = [
            'total_items'     => intval($summaryData->total_items ?? 0),
            'grand_total_qty' => floatval($summaryData->grand_total_qty ?? 0),
            'grand_qty_bebas' => floatval($summaryData->grand_qty_bebas ?? 0),
            'grand_qty_resep' => floatval($summaryData->grand_qty_resep ?? 0),
            'grand_total_hpp' => $grandHpp,
            'grand_total_jual'=> floatval($summaryData->grand_total_jual ?? 0),
            'grand_laba_kotor'=> $grandLaba,
            'avg_margin'      => $avgMargin,
        ];

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('margin_persen', function ($row) {
                $hpp = floatval($row->total_hpp);
                $laba = floatval($row->laba_kotor);
                return $hpp > 0 ? round(($laba / $hpp) * 100, 2) : 0;
            })
            ->addColumn('avg_hpp', function ($row) {
                $qty = floatval($row->total_qty);
                return $qty > 0 ? round(floatval($row->total_hpp) / $qty, 2) : 0;
            })
            ->addColumn('avg_jual', function ($row) {
                $qty = floatval($row->total_qty);
                return $qty > 0 ? round(floatval($row->total_jual) / $qty, 2) : 0;
            })
            ->with('summary', $summary)
            ->make(true);
    }

    /**
     * Cetak Laporan (Print View)
     */
    public function print(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');
        $sumber = $request->sumber ?? 'semua';
        $kdjns = $request->kdjns ?? '';

        $query = $this->buildReportQuery($tglAwal, $tglAkhir, $sumber, $kdjns);
        $items = $query->get();

        $setting = Setting::first();
        $namaJenis = 'Semua Jenis';
        if (!empty($kdjns)) {
            $jns = Jenis::where('kdjns', $kdjns)->first();
            if ($jns) $namaJenis = $jns->nama;
        }

        $grandQty = $items->sum('total_qty');
        $grandHpp = $items->sum('total_hpp');
        $grandJual = $items->sum('total_jual');
        $grandLaba = $items->sum('laba_kotor');
        $avgMargin = $grandHpp > 0 ? round(($grandLaba / $grandHpp) * 100, 2) : 0;

        return view('content.print.laporanPenjualanItemPrint', compact(
            'items',
            'setting',
            'tglAwal',
            'tglAkhir',
            'sumber',
            'namaJenis',
            'grandQty',
            'grandHpp',
            'grandJual',
            'grandLaba',
            'avgMargin'
        ));
    }

    /**
     * Export Excel / CSV
     */
    public function exportExcel(Request $request)
    {
        $tglAwal = $request->tgl_awal ?? date('Y-m-01');
        $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');
        $sumber = $request->sumber ?? 'semua';
        $kdjns = $request->kdjns ?? '';

        $query = $this->buildReportQuery($tglAwal, $tglAkhir, $sumber, $kdjns);
        $items = $query->get();

        $filename = "Laporan_Penjualan_Obat_Per_Item_{$tglAwal}_sd_{$tglAkhir}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function () use ($items, $sumber) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens UTF-8 properly
            fputs($file, "\xEF\xBB\xBF");

            // Header columns
            if ($sumber === 'semua') {
                fputcsv($file, [
                    'No', 'Kode Obat', 'Nama Obat', 'Satuan', 'Jenis Obat',
                    'Qty Bebas', 'Qty Resep', 'Total Qty',
                    'Total HPP (Modal)', 'Total Penjualan (Omzet)', 'Laba Kotor (Rp)', 'Margin (%)'
                ], ';');
            } else {
                fputcsv($file, [
                    'No', 'Kode Obat', 'Nama Obat', 'Satuan', 'Jenis Obat',
                    'Qty Terjual', 'Total HPP (Modal)', 'Total Penjualan (Omzet)', 'Laba Kotor (Rp)', 'Margin (%)'
                ], ';');
            }

            $no = 1;
            $totQty = 0; $totHpp = 0; $totJual = 0; $totLaba = 0;

            foreach ($items as $item) {
                $marginPersen = floatval($item->total_hpp) > 0 
                    ? round((floatval($item->laba_kotor) / floatval($item->total_hpp)) * 100, 2) 
                    : 0;

                $totQty += floatval($item->total_qty);
                $totHpp += floatval($item->total_hpp);
                $totJual += floatval($item->total_jual);
                $totLaba += floatval($item->laba_kotor);

                if ($sumber === 'semua') {
                    fputcsv($file, [
                        $no++,
                        $item->kode_brng,
                        $item->nama_brng,
                        $item->nama_satuan,
                        $item->nama_jenis,
                        $item->qty_bebas,
                        $item->qty_resep,
                        $item->total_qty,
                        round($item->total_hpp, 0),
                        round($item->total_jual, 0),
                        round($item->laba_kotor, 0),
                        $marginPersen . '%'
                    ], ';');
                } else {
                    fputcsv($file, [
                        $no++,
                        $item->kode_brng,
                        $item->nama_brng,
                        $item->nama_satuan,
                        $item->nama_jenis,
                        $item->total_qty,
                        round($item->total_hpp, 0),
                        round($item->total_jual, 0),
                        round($item->laba_kotor, 0),
                        $marginPersen . '%'
                    ], ';');
                }
            }

            // Total row
            $grandMargin = $totHpp > 0 ? round(($totLaba / $totHpp) * 100, 2) : 0;
            if ($sumber === 'semua') {
                fputcsv($file, [
                    '', '', 'TOTAL KESELURUHAN', '', '',
                    '', '', $totQty,
                    round($totHpp, 0),
                    round($totJual, 0),
                    round($totLaba, 0),
                    $grandMargin . '%'
                ], ';');
            } else {
                fputcsv($file, [
                    '', '', 'TOTAL KESELURUHAN', '', '',
                    $totQty,
                    round($totHpp, 0),
                    round($totJual, 0),
                    round($totLaba, 0),
                    $grandMargin . '%'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Membangun Query Agregasi Penjualan Per Item
     */
    private function buildReportQuery($tglAwal, $tglAkhir, $sumber = 'semua', $kdjns = '')
    {
        $unionParts = [];

        // 1. Penjualan Bebas (Kasir Apotek)
        if ($sumber === 'semua' || $sumber === 'bebas') {
            $qBebas = DB::table('detailjual as dj')
                ->join('penjualan as p', 'dj.nota_jual', '=', 'p.nota_jual')
                ->whereBetween('p.tgl_jual', [$tglAwal, $tglAkhir])
                ->where(function($q) {
                    $q->whereNull('p.status')
                      ->orWhere('p.status', '!=', 'Batal');
                })
                ->selectRaw('
                    dj.kode_brng,
                    SUM(dj.jumlah) as qty_bebas,
                    0 as qty_resep,
                    SUM(dj.h_beli * dj.jumlah) as hpp_bebas,
                    0 as hpp_resep,
                    SUM(dj.total) as total_bebas,
                    0 as total_resep
                ')
                ->groupBy('dj.kode_brng');

            $unionParts[] = $qBebas;
        }

        // 2. Pemberian Obat Resep (Pasien Rawat Jalan & Inap)
        if ($sumber === 'semua' || $sumber === 'resep') {
            $qResep = DB::table('detail_pemberian_obat as dpo')
                ->whereBetween('dpo.tgl_perawatan', [$tglAwal, $tglAkhir])
                ->selectRaw('
                    dpo.kode_brng,
                    0 as qty_bebas,
                    SUM(dpo.jml) as qty_resep,
                    0 as hpp_bebas,
                    SUM(dpo.h_beli * dpo.jml) as hpp_resep,
                    0 as total_bebas,
                    SUM(dpo.total) as total_resep
                ')
                ->groupBy('dpo.kode_brng');

            $unionParts[] = $qResep;
        }

        // Gabungkan (Union All)
        $combinedQuery = null;
        if (count($unionParts) === 1) {
            $combinedQuery = $unionParts[0];
        } else {
            $combinedQuery = $unionParts[0]->unionAll($unionParts[1]);
        }

        // Final Query ke databarang, satuan, jenis
        $finalQuery = DB::table(DB::raw("({$combinedQuery->toSql()}) as u"))
            ->mergeBindings($combinedQuery)
            ->join('databarang as b', 'u.kode_brng', '=', 'b.kode_brng')
            ->leftJoin('kodesatuan as s', 'b.kode_sat', '=', 's.kode_sat')
            ->leftJoin('jenis as j', 'b.kdjns', '=', 'j.kdjns')
            ->selectRaw('
                u.kode_brng,
                b.nama_brng,
                b.kdjns,
                COALESCE(s.satuan, b.kode_sat, "-") as nama_satuan,
                COALESCE(j.nama, "-") as nama_jenis,
                SUM(u.qty_bebas) as qty_bebas,
                SUM(u.qty_resep) as qty_resep,
                SUM(u.qty_bebas + u.qty_resep) as total_qty,
                SUM(u.hpp_bebas + u.hpp_resep) as total_hpp,
                SUM(u.total_bebas + u.total_resep) as total_jual,
                (SUM(u.total_bebas + u.total_resep) - SUM(u.hpp_bebas + u.hpp_resep)) as laba_kotor
            ')
            ->groupBy('u.kode_brng', 'b.nama_brng', 'b.kdjns', 's.satuan', 'b.kode_sat', 'j.nama');

        if (!empty($kdjns)) {
            $finalQuery->where('b.kdjns', $kdjns);
        }

        return $finalQuery;
    }
}

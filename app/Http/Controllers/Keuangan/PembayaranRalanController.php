<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranRalanController extends Controller
{
    public function index(Request $request)
    {
        $dokter = DB::table('dokter')->where('status', '1')->get();
        $poliklinik = DB::table('poliklinik')->where('status', '1')->get();
        $penjab = DB::table('penjab')->get();

        return view('content.keuangan.pembayaranRalan', compact('dokter', 'poliklinik', 'penjab'));
    }

    public function getData(Request $request)
    {
        $tglAwal = $request->tgl_awal ? Carbon::parse($request->tgl_awal)->format('Y-m-d') : date('Y-m-d');
        $tglAkhir = $request->tgl_akhir ? Carbon::parse($request->tgl_akhir)->format('Y-m-d') : date('Y-m-d');
        $kdDokter = $request->kd_dokter;
        $kdPoli = $request->kd_poli;
        $kdPj = $request->kd_pj;
        $statusBayar = $request->status_bayar ?? 'Semua';
        $keyword = $request->keyword;

        $query = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->leftJoin('nota_jalan', 'reg_periksa.no_rawat', '=', 'nota_jalan.no_rawat')
            ->leftJoin('rujuk_masuk', 'reg_periksa.no_rawat', '=', 'rujuk_masuk.no_rawat')
            ->where('reg_periksa.status_lanjut', 'Ralan')
            ->whereBetween('reg_periksa.tgl_registrasi', [$tglAwal, $tglAkhir]);

        if ($kdDokter) {
            $query->where('reg_periksa.kd_dokter', $kdDokter);
        }
        if ($kdPoli) {
            $query->where('reg_periksa.kd_poli', $kdPoli);
        }
        if ($kdPj) {
            $query->where('reg_periksa.kd_pj', $kdPj);
        }
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('reg_periksa.no_rawat', 'like', "%{$keyword}%")
                  ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$keyword}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$keyword}%");
            });
        }

        $registrasiList = $query->select(
            'reg_periksa.no_rawat',
            'reg_periksa.no_rkm_medis',
            'reg_periksa.tgl_registrasi',
            'reg_periksa.biaya_reg',
            'reg_periksa.status_bayar as status_bayar_reg',
            'pasien.nm_pasien',
            'poliklinik.nm_poli',
            'dokter.nm_dokter',
            'penjab.png_jawab',
            'nota_jalan.no_nota',
            'rujuk_masuk.perujuk'
        )->orderBy('reg_periksa.tgl_registrasi', 'DESC')
         ->orderBy('reg_periksa.no_rawat', 'DESC')
         ->get();

        $data = [];
        $totals = [
            'registrasi' => 0,
            'obat' => 0,
            'tindakan' => 0,
            'operasi' => 0,
            'laborat' => 0,
            'radiologi' => 0,
            'tambahan' => 0,
            'potongan' => 0,
            'grand_total' => 0,
        ];

        foreach ($registrasiList as $reg) {
            $noRawat = $reg->no_rawat;

            // Biaya Registrasi
            $biayaReg = (float) $reg->biaya_reg;

            // Biaya Obat
            $biayaObat = (float) DB::table('detail_pemberian_obat')
                ->where('no_rawat', $noRawat)
                ->sum('total');

            // Biaya Tindakan (Dokter, Paramedis, Dokter+Paramedis)
            $tindakanDr = (float) DB::table('rawat_jl_dr')->where('no_rawat', $noRawat)->sum('biaya_rawat');
            $tindakanPr = (float) DB::table('rawat_jl_pr')->where('no_rawat', $noRawat)->sum('biaya_rawat');
            $tindakanDrPr = (float) DB::table('rawat_jl_drpr')->where('no_rawat', $noRawat)->sum('biaya_rawat');
            $biayaTindakan = $tindakanDr + $tindakanPr + $tindakanDrPr;

            // Biaya Operasi (jika ada)
            $biayaOperasi = (float) DB::table('operasi')->where('no_rawat', $noRawat)->sum('biayaoperator1');

            // Biaya Lab
            $biayaLab = (float) DB::table('periksa_lab')->where('no_rawat', $noRawat)->sum('biaya');

            // Biaya Radiologi
            $biayaRad = (float) DB::table('periksa_radiologi')->where('no_rawat', $noRawat)->sum('biaya');

            // Tambahan & Potongan
            $biayaTambahan = (float) DB::table('tambahan_biaya')->where('no_rawat', $noRawat)->sum('besar_biaya');
            $biayaPotongan = (float) DB::table('pengurangan_biaya')->where('no_rawat', $noRawat)->sum('besar_pengurangan');

            $totalBiaya = ($biayaReg + $biayaObat + $biayaTindakan + $biayaOperasi + $biayaLab + $biayaRad + $biayaTambahan) - $biayaPotongan;

            $sttsBayar = ($totalBiaya > 0 || $reg->no_nota || $reg->status_bayar_reg === 'Sudah Bayar') ? 'Sudah Bayar' : 'Belum Bayar';

            if ($statusBayar !== 'Semua' && $sttsBayar !== $statusBayar) {
                continue;
            }

            $totals['registrasi'] += $biayaReg;
            $totals['obat'] += $biayaObat;
            $totals['tindakan'] += $biayaTindakan;
            $totals['operasi'] += $biayaOperasi;
            $totals['laborat'] += $biayaLab;
            $totals['radiologi'] += $biayaRad;
            $totals['tambahan'] += $biayaTambahan;
            $totals['potongan'] += $biayaPotongan;
            $totals['grand_total'] += $totalBiaya;

            $data[] = [
                'no_rawat' => $reg->no_rawat,
                'no_nota' => $reg->no_nota ?? '-',
                'tgl_registrasi' => date('d-m-Y', strtotime($reg->tgl_registrasi)),
                'no_rkm_medis' => $reg->no_rkm_medis,
                'nm_pasien' => $reg->nm_pasien,
                'nm_poli' => $reg->nm_poli,
                'nm_dokter' => $reg->nm_dokter,
                'png_jawab' => $reg->png_jawab,
                'perujuk' => $reg->perujuk ?? '-',
                'biaya_reg' => $biayaReg,
                'biaya_obat' => $biayaObat,
                'biaya_tindakan' => $biayaTindakan,
                'biaya_operasi' => $biayaOperasi,
                'biaya_lab' => $biayaLab,
                'biaya_rad' => $biayaRad,
                'biaya_tambahan' => $biayaTambahan,
                'biaya_potongan' => $biayaPotongan,
                'total_biaya' => $totalBiaya,
                'status_bayar' => $sttsBayar,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'totals' => $totals,
            'count' => count($data),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $res = $this->getData($request)->getData(true);
        $data = $res['data'];
        $totals = $res['totals'];
        $setting = \App\Models\Setting::first();

        $tgl_awal = $request->tgl_awal ? Carbon::parse($request->tgl_awal)->format('d-m-Y') : date('d-m-Y');
        $tgl_akhir = $request->tgl_akhir ? Carbon::parse($request->tgl_akhir)->format('d-m-Y') : date('d-m-Y');

        $poliName = $request->kd_poli ? (DB::table('poliklinik')->where('kd_poli', $request->kd_poli)->value('nm_poli') ?? 'Semua') : 'Semua';
        $dokterName = $request->kd_dokter ? (DB::table('dokter')->where('kd_dokter', $request->kd_dokter)->value('nm_dokter') ?? 'Semua') : 'Semua';
        $penjabName = $request->kd_pj ? (DB::table('penjab')->where('kd_pj', $request->kd_pj)->value('png_jawab') ?? 'Semua') : 'Semua';
        $statusBayar = $request->status_bayar ?? 'Semua';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('content.print.rekapPembayaranRalanPdf', [
            'data' => $data,
            'totals' => $totals,
            'setting' => $setting,
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'poliName' => $poliName,
            'dokterName' => $dokterName,
            'penjabName' => $penjabName,
            'statusBayar' => $statusBayar,
        ])
        ->setPaper('a4', 'landscape')
        ->setOptions(['defaultFont' => 'Arial', 'isRemoteEnabled' => true]);

        return $pdf->stream("Rekap_Pembayaran_Ralan_{$tgl_awal}_s.d_{$tgl_akhir}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $res = $this->getData($request)->getData(true);
        $data = $res['data'];
        $totals = $res['totals'];

        $tgl_awal = $request->tgl_awal ? Carbon::parse($request->tgl_awal)->format('d-m-Y') : date('d-m-Y');
        $tgl_akhir = $request->tgl_akhir ? Carbon::parse($request->tgl_akhir)->format('d-m-Y') : date('d-m-Y');

        $fileName = "Rekap_Pembayaran_Ralan_{$tgl_awal}_s.d_{$tgl_akhir}.csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($data, $totals) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No', 'Tgl Registrasi', 'No. Nota', 'No. RM', 'Nama Pasien', 'Poliklinik', 'Dokter', 'Penjab', 'Perujuk', 'Registrasi', 'Obat+BHP', 'Tindakan', 'Operasi', 'Laborat', 'Radiologi', 'Tambahan', 'Potongan', 'Total Biaya', 'Status Bayar'], ';');

            foreach ($data as $idx => $row) {
                fputcsv($file, [
                    $idx + 1,
                    $row['tgl_registrasi'],
                    $row['no_nota'],
                    $row['no_rkm_medis'],
                    $row['nm_pasien'],
                    $row['nm_poli'],
                    $row['nm_dokter'],
                    $row['png_jawab'],
                    $row['perujuk'],
                    $row['biaya_reg'],
                    $row['biaya_obat'],
                    $row['biaya_tindakan'],
                    $row['biaya_operasi'],
                    $row['biaya_lab'],
                    $row['biaya_rad'],
                    $row['biaya_tambahan'],
                    $row['biaya_potongan'],
                    $row['total_biaya'],
                    $row['status_bayar']
                ], ';');
            }

            fputcsv($file, [
                'TOTAL', '', '', '', '', '', '', '', '',
                $totals['registrasi'],
                $totals['obat'],
                $totals['tindakan'],
                $totals['operasi'],
                $totals['laborat'],
                $totals['radiologi'],
                $totals['tambahan'],
                $totals['potongan'],
                $totals['grand_total'],
                ''
            ], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

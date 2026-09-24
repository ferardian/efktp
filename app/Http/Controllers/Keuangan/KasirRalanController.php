<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirRalanController extends Controller
{
    public function index(Request $request)
    {
        $dokter = DB::table('dokter')->where('status', '1')->orderBy('nm_dokter')->get();
        $poliklinik = DB::table('poliklinik')->where('status', '1')->orderBy('nm_poli')->get();
        $penjab = DB::table('penjab')->orderBy('png_jawab')->get();
        $petugas = DB::table('petugas')->where('status', '1')->orderBy('nama')->get();

        return view('content.keuangan.kasirRalan', compact('dokter', 'poliklinik', 'penjab', 'petugas'));
    }

    public function getAntrean(Request $request)
    {
        $tglAwal = $request->tgl_awal ? Carbon::parse($request->tgl_awal)->format('Y-m-d') : date('Y-m-d');
        $tglAkhir = $request->tgl_akhir ? Carbon::parse($request->tgl_akhir)->format('Y-m-d') : date('Y-m-d');
        $kdDokter = $request->kd_dokter;
        $kdPoli = $request->kd_poli;
        $kdPj = $request->kd_pj;
        $statusBayar = $request->status_bayar ?? 'Belum Bayar';
        $statusPeriksa = $request->status_periksa;
        $keyword = $request->keyword;

        $query = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->leftJoin('nota_jalan', 'reg_periksa.no_rawat', '=', 'nota_jalan.no_rawat')
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
        if ($statusBayar && $statusBayar !== 'Semua') {
            $query->where('reg_periksa.status_bayar', $statusBayar);
        }
        if ($statusPeriksa && $statusPeriksa !== 'Semua') {
            if ($statusPeriksa === 'Sudah') {
                $query->where(function ($q) {
                    $q->where('reg_periksa.stts', 'Sudah')
                      ->orWhereExists(function ($sub) {
                          $sub->select(DB::raw(1))->from('pemeriksaan_ralan')->whereColumn('pemeriksaan_ralan.no_rawat', 'reg_periksa.no_rawat');
                      });
                });
            } elseif ($statusPeriksa === 'Belum') {
                $query->where('reg_periksa.stts', 'Belum')
                      ->whereNotExists(function ($sub) {
                          $sub->select(DB::raw(1))->from('pemeriksaan_ralan')->whereColumn('pemeriksaan_ralan.no_rawat', 'reg_periksa.no_rawat');
                      });
            } else {
                $query->where('reg_periksa.stts', $statusPeriksa);
            }
        }
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('reg_periksa.no_rawat', 'like', "%{$keyword}%")
                  ->orWhere('reg_periksa.no_rkm_medis', 'like', "%{$keyword}%")
                  ->orWhere('pasien.nm_pasien', 'like', "%{$keyword}%");
            });
        }

        $list = $query->select(
            'reg_periksa.no_rawat',
            'reg_periksa.no_rkm_medis',
            'reg_periksa.kd_dokter',
            'reg_periksa.kd_poli',
            'reg_periksa.kd_pj',
            'reg_periksa.tgl_registrasi',
            'reg_periksa.jam_reg',
            'reg_periksa.status_bayar',
            'reg_periksa.stts',
            'reg_periksa.biaya_reg',
            'pasien.nm_pasien',
            'poliklinik.nm_poli',
            'dokter.nm_dokter',
            'penjab.png_jawab',
            'nota_jalan.no_nota'
        )->orderBy('reg_periksa.status_bayar', 'ASC')
         ->orderBy('reg_periksa.tgl_registrasi', 'DESC')
         ->orderBy('reg_periksa.jam_reg', 'DESC')
         ->get();

        $noRawats = $list->pluck('no_rawat')->toArray();
        $pemeriksaanList = DB::table('pemeriksaan_ralan')->whereIn('no_rawat', $noRawats)->pluck('no_rawat')->flip();
        $tindakanDrList = DB::table('rawat_jl_dr')->whereIn('no_rawat', $noRawats)->pluck('no_rawat')->flip();
        $tindakanPrList = DB::table('rawat_jl_pr')->whereIn('no_rawat', $noRawats)->pluck('no_rawat')->flip();
        $tindakanDrprList = DB::table('rawat_jl_drpr')->whereIn('no_rawat', $noRawats)->pluck('no_rawat')->flip();

        $data = [];
        foreach ($list as $item) {
            $hasPemeriksaan = $pemeriksaanList->has($item->no_rawat);
            $hasTindakan = $tindakanDrList->has($item->no_rawat) || $tindakanPrList->has($item->no_rawat) || $tindakanDrprList->has($item->no_rawat);

            $isSudahPeriksa = ($item->stts === 'Sudah') || $hasPemeriksaan || $hasTindakan;

            $statusPeriksaLabel = 'Belum Periksa';
            if ($item->stts === 'Sudah' || $hasPemeriksaan) {
                $statusPeriksaLabel = 'Sudah Periksa';
            } elseif ($item->stts === 'Dirawat') {
                $statusPeriksaLabel = 'Sedang Diperiksa';
            } elseif ($item->stts === 'Berkas Diterima') {
                $statusPeriksaLabel = 'Menunggu';
            } elseif ($item->stts === 'Batal') {
                $statusPeriksaLabel = 'Batal';
            } elseif ($item->stts === 'Dirujuk') {
                $statusPeriksaLabel = 'Dirujuk';
            } elseif ($hasTindakan) {
                $statusPeriksaLabel = 'Sudah Periksa';
            }

            // Check if patient has unvalidated prescription
            // In Khanza, unvalidated prescription has tgl_perawatan = '0000-00-00' or NULL
            $unvalidatedReseps = DB::table('resep_obat')
                ->where('no_rawat', $item->no_rawat)
                ->where('status', 'ralan')
                ->where(function ($q) {
                    $q->where('tgl_perawatan', '0000-00-00')
                      ->orWhereNull('tgl_perawatan');
                })
                ->get();

            $unvalidatedCount = 0;
            foreach ($unvalidatedReseps as $r) {
                // Ensure resep actually has items in resep_dokter or resep_dokter_racikan
                $hasItems = DB::table('resep_dokter')->where('no_resep', $r->no_resep)->exists()
                    || DB::table('resep_dokter_racikan')->where('no_resep', $r->no_resep)->exists();
                if ($hasItems) {
                    $unvalidatedCount++;
                }
            }
            $hasUnvalidated = $unvalidatedCount > 0;

            $data[] = [
                'no_rawat' => $item->no_rawat,
                'no_rkm_medis' => $item->no_rkm_medis,
                'tgl_registrasi' => date('d/m/Y', strtotime($item->tgl_registrasi)),
                'jam_reg' => $item->jam_reg,
                'status_bayar' => $item->status_bayar,
                'stts' => $item->stts,
                'status_periksa' => $statusPeriksaLabel,
                'is_sudah_periksa' => $isSudahPeriksa,
                'has_pemeriksaan' => $hasPemeriksaan,
                'has_tindakan' => $hasTindakan,
                'no_nota' => $item->no_nota ?? '-',
                'nm_pasien' => $item->nm_pasien,
                'nm_poli' => $item->nm_poli,
                'nm_dokter' => $item->nm_dokter,
                'png_jawab' => $item->png_jawab,
                'has_unvalidated_resep' => $hasUnvalidated,
                'unvalidated_count' => $unvalidatedCount
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'count' => count($data)
        ]);
    }
}

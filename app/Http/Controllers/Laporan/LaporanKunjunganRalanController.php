<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Penjab;
use App\Models\Poliklinik;
use App\Models\RegPeriksa;
use Illuminate\Http\Request;

class LaporanKunjunganRalanController extends Controller
{
    public function index()
    {
        $poliklinik = Poliklinik::where('status', '1')->get();
        $dokter     = Dokter::where('status', '1')->get();
        $penjab     = Penjab::where('status', '1')->get();
        $kabupaten  = Kabupaten::orderBy('nm_kab')->get();
        $kecamatan  = Kecamatan::orderBy('nm_kec')->get();

        return view('content.laporan.kunjunganRalan', compact('poliklinik', 'dokter', 'penjab', 'kabupaten', 'kecamatan'));
    }

    public function getData(Request $request)
    {
        $tglAwal  = $request->tglAwal ? date('Y-m-d', strtotime($request->tglAwal)) : date('Y-m-d');
        $tglAkhir = $request->tglAkhir ? date('Y-m-d', strtotime($request->tglAkhir)) : date('Y-m-d');

        $query = RegPeriksa::where('status_lanjut', 'Ralan')
            ->where('stts', '!=', 'Batal')
            ->whereBetween('tgl_registrasi', [$tglAwal, $tglAkhir])
            ->with([
                'pasien.kel',
                'pasien.kec',
                'pasien.kab',
                'dokter',
                'poliklinik',
                'penjab',
                'diagnosa' => function ($q) {
                    $q->orderBy('prioritas', 'asc')->with('penyakit');
                }
            ]);

        if ($request->kd_poli && $request->kd_poli !== '-') {
            $query->where('kd_poli', $request->kd_poli);
        }

        if ($request->kd_dokter && $request->kd_dokter !== '-') {
            $query->where('kd_dokter', $request->kd_dokter);
        }

        if ($request->kd_pj && $request->kd_pj !== '-') {
            $query->where('kd_pj', $request->kd_pj);
        }

        if ($request->stts_daftar && $request->stts_daftar !== '-') {
            $query->where('stts_daftar', $request->stts_daftar);
        }

        // Filter Wilayah Pasien (Kabupaten, Kecamatan, Kelurahan)
        if ($request->kd_kab || $request->kd_kec || $request->kd_kel) {
            $query->whereHas('pasien', function ($q) use ($request) {
                if ($request->kd_kab) {
                    $q->where('kd_kab', $request->kd_kab);
                }
                if ($request->kd_kec) {
                    $q->where('kd_kec', $request->kd_kec);
                }
                if ($request->kd_kel) {
                    $q->where('kd_kel', $request->kd_kel);
                }
            });
        }

        // Filter Keyword
        if ($request->keyword) {
            $kw = '%' . $request->keyword . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('no_rawat', 'like', $kw)
                  ->orWhere('no_rkm_medis', 'like', $kw)
                  ->orWhereHas('pasien', function ($qp) use ($kw) {
                      $qp->where('nm_pasien', 'like', $kw)
                        ->orWhere('alamat', 'like', $kw);
                  })
                  ->orWhereHas('dokter', function ($qd) use ($kw) {
                      $qd->where('nm_dokter', 'like', $kw);
                  })
                  ->orWhereHas('poliklinik', function ($qpo) use ($kw) {
                      $qpo->where('nm_poli', 'like', $kw);
                  });
            });
        }

        $records = $query->orderBy('tgl_registrasi', 'asc')
            ->orderBy('jam_reg', 'asc')
            ->get();

        // Calculate Stats Summary
        $totalPasien = $records->count();
        $pasienLama  = 0;
        $pasienBaru  = 0;
        $lakiLaki    = 0;
        $perempuan   = 0;

        $formattedData = [];
        $no = 1;

        foreach ($records as $row) {
            $pasien = $row->pasien;
            $sttsDaftar = $row->stts_daftar; // 'Lama' / 'Baru'

            if (strtolower($sttsDaftar) === 'baru') {
                $pasienBaru++;
            } else {
                $pasienLama++;
            }

            $jk = $pasien ? $pasien->jk : '-';
            if ($jk === 'L') {
                $lakiLaki++;
            } elseif ($jk === 'P') {
                $perempuan++;
            }

            // Alamat Lengkap
            $almt = $pasien ? $pasien->alamat : '';
            if ($pasien && $pasien->kel) {
                $almt .= ', ' . $pasien->kel->nm_kel;
            }
            if ($pasien && $pasien->kec) {
                $almt .= ', ' . $pasien->kec->nm_kec;
            }
            if ($pasien && $pasien->kab) {
                $almt .= ', ' . $pasien->kab->nm_kab;
            }

            // Diagnosa Utama
            $primaryDiag = $row->diagnosa->first();
            $kdPenyakit = $primaryDiag && $primaryDiag->penyakit ? $primaryDiag->penyakit->kd_penyakit : '-';
            $nmPenyakit = $primaryDiag && $primaryDiag->penyakit ? $primaryDiag->penyakit->nm_penyakit : '-';

            $formattedData[] = [
                'no'             => $no++,
                'no_rawat'       => $row->no_rawat,
                'tgl_registrasi' => date('d-m-Y', strtotime($row->tgl_registrasi)) . ' ' . $row->jam_reg,
                'stts_daftar'    => $sttsDaftar ?: 'Lama',
                'no_rkm_medis'   => $row->no_rkm_medis,
                'nm_pasien'      => $pasien ? $pasien->nm_pasien : '-',
                'jk'             => $jk,
                'umur'           => $row->umurdaftar . ' ' . $row->sttsumur,
                'alamat'         => $almt ?: '-',
                'kd_penyakit'    => $kdPenyakit,
                'nm_penyakit'    => $nmPenyakit,
                'nm_dokter'      => $row->dokter ? $row->dokter->nm_dokter : '-',
                'nm_poli'        => $row->poliklinik ? $row->poliklinik->nm_poli : '-',
                'png_jawab'      => $row->penjab ? $row->penjab->png_jawab : '-',
            ];
        }

        return response()->json([
            'summary' => [
                'total'       => $totalPasien,
                'pasien_baru' => $pasienBaru,
                'pasien_lama' => $pasienLama,
                'laki_laki'   => $lakiLaki,
                'perempuan'   => $perempuan,
            ],
            'data' => $formattedData,
        ]);
    }
}

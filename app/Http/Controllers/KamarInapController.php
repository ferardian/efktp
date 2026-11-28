<?php

namespace App\Http\Controllers;

use App\Models\KamarInap;
use App\Models\SetJamMinimal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KamarInapController extends Controller
{

    function get(Request $request)
    {
        $kamarInap = KamarInap::with(['regPeriksa' => function ($q) {
            return $q->with(['pasien' => function ($q) {
                return $q->with(['kel', 'kec', 'kab']);
            }, 'dokter', 'penjab'])->withCount([
                'persetujuanPenolakanTindakan',
                'penilaianPreOperasi',
                'penilaianPreAnestesi',
                'laporanAnestesi',
                'buktiAnestesiSignin',
                'perencanaanPemulangan',
            ]);
        }, 'kamar.bangsal'])->where('stts_pulang', '!=', 'Pindah Kamar');

        // Filter by status first
        if ($request->pulang == 'Belum Pulang') {
            $kamarInap = $kamarInap->where('stts_pulang', '-');
        } 
        // For Pulang filter, already filtered by != 'Pindah Kamar' at the beginning
        // No additional status filter needed

        // Then apply date filtering based on filter type
        if ($request->tglAwal && $request->tglAkhir) {
            $tglAwal = date('Y-m-d', strtotime($request->tglAwal));
            $tglAkhir = date('Y-m-d', strtotime($request->tglAkhir));
            
            // If filter is "Pulang", use tgl_keluar
            if ($request->pulang == 'Pulang') {
                $kamarInap = $kamarInap->whereBetween('tgl_keluar', [$tglAwal, $tglAkhir]);
            } 
            // If filter is "Masuk", use tgl_masuk
            else if ($request->pulang == 'Masuk') {
                $kamarInap = $kamarInap->whereBetween('tgl_masuk', [$tglAwal, $tglAkhir]);
            }
        } else if ($request->pulang == 'Belum Pulang') {
            // For Belum Pulang without date range, no additional date filter needed
            // Just show all patients who haven't been discharged
        }

        if ($request->dataTable) {
            if ($kamarInap->count() == 0) {
                return response()->json([
                    'draw' => 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }
            return DataTables::of($kamarInap)
                ->editColumn('lama', function ($row) {
                    if ($row->stts_pulang == '-' || empty($row->tgl_keluar) || $row->tgl_keluar == '0000-00-00') {
                        return SetJamMinimal::hitungLama($row->tgl_masuk, $row->jam_masuk);
                    }
                    return (int) $row->lama;
                })
                ->make(true);
        }
        return response()->json($kamarInap->get());
    }

    function create(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required',
            'kd_kamar' => 'required',
            'tgl_masuk' => 'required',
            'jam_masuk' => 'required',
            'diagnosa_awal' => 'required',
            'lama' => 'required',
        ]);

        try {
            // Check if already exists? Usually one active Ranap per No Rawat
            $exists = KamarInap::where('no_rawat', $request->no_rawat)->first();
            if ($exists) {
                return response()->json(['message' => 'Pasien sudah terdaftar di Kamar Inap'], 400);
            }

            // Insert Kamar Inap
            KamarInap::create([
                'no_rawat' => $request->no_rawat,
                'kd_kamar' => $request->kd_kamar,
                'trf_kamar' => str_replace('.', '', $request->trf_kamar), // simple sanitize currency
                'diagnosa_awal' => $request->diagnosa_awal,
                'diagnosa_akhir' => '-',
                'tgl_masuk' => date('Y-m-d', strtotime($request->tgl_masuk)),
                'jam_masuk' => $request->jam_masuk,
                'tgl_keluar' => '0000-00-00',
                'jam_keluar' => '00:00:00',
                'lama' => $request->lama,
                'ttl_biaya' => 0, // Calculated later usually
                'stts_pulang' => '-',
            ]);

            // Update Status Kamar to ISI
            \App\Models\Kamar::where('kd_kamar', $request->kd_kamar)->update(['status' => 'ISI']);
            
            // Update Status Registrasi (RegPeriksa)? Typically changed to 'Dirawat'
            // \App\Models\RegPeriksa::where('no_rawat', $request->no_rawat)->update(['stts' => 'Dirawat']); // Uncomment if needed

            return response()->json(['message' => 'Berhasil menyimpan data Kamar Inap']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function getKamarKosong(Request $request) {
        $search = $request->search;
        $kamar = \App\Models\Kamar::with('bangsal');

        if ($request->boolean('only_kosong')) {
            $kamar->where('status', 'KOSONG');
        } else {
            $kamar->where(function($q) {
                $q->where('status', 'ISI')
                  ->orWhere('status', 'KOSONG');
            });
        }

        $kamar = $kamar->where(function($q) use ($search) {
             $q->where('kd_kamar', 'like', "%$search%")
               ->orWhereHas('bangsal', function($b) use ($search) {
                   $b->where('nm_bangsal', 'like', "%$search%");
               });
        });

        return response()->json($kamar->limit(20)->get());
    }

    function getDetail(Request $request)
    {
        $kamarInap = KamarInap::with(['regPeriksa.pasien', 'kamar.bangsal'])
            ->where('no_rawat', $request->no_rawat)
            ->where('kd_kamar', $request->kd_kamar)
            ->where('tgl_masuk', $request->tgl_masuk)
            ->where('jam_masuk', $request->jam_masuk)
            ->first();

        return response()->json($kamarInap);
    }

    function pulangkan(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required',
            'kd_kamar' => 'required',
            'tgl_masuk' => 'required',
            'jam_masuk' => 'required',
            'tgl_keluar' => 'required',
            'jam_keluar' => 'required',
            'stts_pulang' => 'required',
            'diagnosa_akhir' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $kamarInap = KamarInap::where('no_rawat', $request->no_rawat)
                ->where('kd_kamar', $request->kd_kamar)
                ->where('tgl_masuk', $request->tgl_masuk)
                ->where('jam_masuk', $request->jam_masuk)
                ->first();

            if (!$kamarInap) {
                return response()->json(['message' => 'Data Kamar Inap tidak ditemukan'], 404);
            }

            // Calculate lama (length of stay) using SetJamMinimal
            $lama = SetJamMinimal::hitungLama(
                $request->tgl_masuk,
                $request->jam_masuk,
                $request->tgl_keluar,
                $request->jam_keluar
            );

            // Update Kamar Inap
            $kamarInap->update([
                'tgl_keluar' => date('Y-m-d', strtotime($request->tgl_keluar)),
                'jam_keluar' => $request->jam_keluar,
                'stts_pulang' => $request->stts_pulang,
                'diagnosa_akhir' => $request->diagnosa_akhir,
                'lama' => $lama,
                'ttl_biaya' => $lama * floatval($kamarInap->trf_kamar),
            ]);

            // Update Kamar Status to KOSONG
            \App\Models\Kamar::where('kd_kamar', $request->kd_kamar)->update(['status' => 'KOSONG']);

            // Update RegPeriksa status to Sudah
            \App\Models\RegPeriksa::where('no_rawat', $request->no_rawat)->update(['stts' => 'Sudah']);

            \DB::commit();
            return response()->json(['message' => 'Berhasil memulangkan pasien']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function pindahKamar(Request $request)
    {
        $request->validate([
            'no_rawat'       => 'required|string',
            'kd_kamar_lama'  => 'required|string',
            'tgl_masuk_lama' => 'required|string',
            'jam_masuk_lama' => 'required|string',
            'kd_kamar_baru'  => 'required|string',
            'tgl_pindah'     => 'required|string',
            'jam_pindah'     => 'required|string',
            'mode'           => 'required|in:1,2,3,4',
        ]);

        if ($request->kd_kamar_baru === $request->kd_kamar_lama) {
            return response()->json(['message' => 'Kamar tujuan tidak boleh sama dengan kamar asal'], 422);
        }

        \DB::beginTransaction();
        try {
            $oldQuery = \DB::table('kamar_inap')
                ->where('no_rawat', $request->no_rawat)
                ->where('kd_kamar', $request->kd_kamar_lama)
                ->where('tgl_masuk', $request->tgl_masuk_lama)
                ->where('jam_masuk', $request->jam_masuk_lama);

            $old = $oldQuery->first();
            if (!$old) {
                \DB::rollBack();
                return response()->json(['message' => 'Data kamar inap asal tidak ditemukan'], 404);
            }

            $kamarBaru = \App\Models\Kamar::where('kd_kamar', $request->kd_kamar_baru)->first();
            if (!$kamarBaru) {
                \DB::rollBack();
                return response()->json(['message' => 'Kamar tujuan tidak ditemukan'], 404);
            }

            if (strtoupper($kamarBaru->status) !== 'KOSONG') {
                \DB::rollBack();
                return response()->json(['message' => 'Maaf, status kamar tujuan sudah terisi. Silahkan pilih kamar kosong.'], 422);
            }

            $tglPindah = date('Y-m-d', strtotime($request->tgl_pindah));
            $jamPindah = $request->jam_pindah;
            $trfBaru   = $request->filled('trf_kamar_baru')
                ? (float) str_replace(['.', ','], ['', '.'], $request->trf_kamar_baru)
                : (float) $kamarBaru->trf_kamar;

            $mode = (string) $request->mode;

            if ($mode === '1') {
                // 1. Hapus kamar inap lama dari billing, mulai menginap di kamar baru
                \DB::table('kamar_inap')->insert([
                    'no_rawat'       => $old->no_rawat,
                    'kd_kamar'       => $request->kd_kamar_baru,
                    'trf_kamar'      => $trfBaru,
                    'diagnosa_awal'  => $old->diagnosa_awal,
                    'diagnosa_akhir' => $old->diagnosa_akhir ?: '-',
                    'tgl_masuk'      => $tglPindah,
                    'jam_masuk'      => $jamPindah,
                    'tgl_keluar'     => '0000-00-00',
                    'jam_keluar'     => '00:00:00',
                    'lama'           => 0,
                    'ttl_biaya'      => 0,
                    'stts_pulang'    => '-',
                ]);

                $oldQuery->delete();
            } elseif ($mode === '2') {
                // 2. Ganti kamar pada record lama & sesuaikan tarif ke tarif baru
                $lama = SetJamMinimal::hitungLama($old->tgl_masuk, $old->jam_masuk);
                $oldQuery->update([
                    'kd_kamar'  => $request->kd_kamar_baru,
                    'trf_kamar' => $trfBaru,
                    'lama'      => $lama,
                    'ttl_biaya' => $lama * $trfBaru,
                ]);
            } else {
                // 3 & 4. Tutup record lama dengan status 'Pindah Kamar', buat record baru
                $lamaLama = SetJamMinimal::hitungLama($old->tgl_masuk, $old->jam_masuk, $tglPindah, $jamPindah);
                $trfLamaDipakai = ($mode === '4')
                    ? max((float) $old->trf_kamar, $trfBaru)
                    : (float) $old->trf_kamar;

                $oldQuery->update([
                    'trf_kamar'   => $trfLamaDipakai,
                    'tgl_keluar'  => $tglPindah,
                    'jam_keluar'  => $jamPindah,
                    'lama'        => $lamaLama,
                    'ttl_biaya'   => $lamaLama * $trfLamaDipakai,
                    'stts_pulang' => 'Pindah Kamar',
                ]);

                \DB::table('kamar_inap')->insert([
                    'no_rawat'       => $old->no_rawat,
                    'kd_kamar'       => $request->kd_kamar_baru,
                    'trf_kamar'      => $trfBaru,
                    'diagnosa_awal'  => $old->diagnosa_awal,
                    'diagnosa_akhir' => '-',
                    'tgl_masuk'      => $tglPindah,
                    'jam_masuk'      => $jamPindah,
                    'tgl_keluar'     => '0000-00-00',
                    'jam_keluar'     => '00:00:00',
                    'lama'           => 0,
                    'ttl_biaya'      => 0,
                    'stts_pulang'    => '-',
                ]);
            }

            // Update status kamar lama -> KOSONG & kamar baru -> ISI
            \App\Models\Kamar::where('kd_kamar', $request->kd_kamar_lama)->update(['status' => 'KOSONG']);
            \App\Models\Kamar::where('kd_kamar', $request->kd_kamar_baru)->update(['status' => 'ISI']);

            \DB::commit();
            return response()->json(['message' => 'Berhasil memindahkan kamar pasien']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Gagal pindah kamar: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanRanap;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class PemeriksaanRanapController extends Controller
{
    use Track;
    public function __construct()
    {
    }

    function get(Request $request)
    {
        $pemeriksaanRanap = PemeriksaanRanap::with(['regPeriksa' => function ($q) {
            return $q->with('pasien.alergi');
        }, 'pegawai.dokter'])->where('no_rawat', $request->no_rawat)
            ->orderBy('tgl_perawatan', 'DESC')->orderBy('jam_rawat', 'DESC');

        if ($request->tgl_perawatan && $request->jam_rawat) {
            $pemeriksaanRanap = $pemeriksaanRanap
                ->where('tgl_perawatan',  $request->tgl_perawatan)
                ->where('jam_rawat', $request->jam_rawat)
                ->first();
        } else if ($request->tglCppt1 && $request->tglCppt2) {
            $pemeriksaanRanap = $pemeriksaanRanap
                ->whereBetween('tgl_perawatan', [
                    date('Y-m-d', strtotime($request->tglCppt1)),
                    date('Y-m-d', strtotime($request->tglCppt2))])
                ->get();
            return response()->json($pemeriksaanRanap);
        } else {
            $pemeriksaanRanap = $pemeriksaanRanap->get();
            if ($request->datatable) {
                return DataTables::of($pemeriksaanRanap)->make(true);
            }
        }
        return response()->json($pemeriksaanRanap);
    }


    function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'tgl_perawatan' => date('Y-m-d', strtotime($request->tgl_perawatan)),
            'jam_rawat' => $request->jam_rawat,
            'nip' => $request->nip,
            'keluhan' => $request->keluhan,
            'penilaian' => $request->penilaian,
            'pemeriksaan' => $request->pemeriksaan,
            'rtl' => $request->rtl,
            'instruksi' => $request->instruksi,
            'suhu_tubuh' => $request->suhu_tubuh,
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'tensi' => $request->tensi,
            'respirasi' => $request->respirasi,
            'nadi' => $request->nadi,
            'spo2' => $request->spo2,
            'gcs' => $request->gcs,
            'kesadaran' => $request->kesadaran,
            'alergi' => $request->alergi,
            'evaluasi' => '-',
        ];

        try {
            $pemeriksaan = PemeriksaanRanap::create($data);
            if ($pemeriksaan) {
                $this->insertSql(new PemeriksaanRanap(), $data);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 201);
    }

    function update(Request $request)
    {
        $key = [
            'no_rawat' => $request->no_rawat,
            'tgl_perawatan' => date('Y-m-d', strtotime($request->tgl_perawatan_awal)),
            'nip' => $request->nip,
            'jam_rawat' => $request->jam_rawat_awal,
        ];
        $data = [
            'tgl_perawatan' => date('Y-m-d', strtotime($request->tgl_perawatan)),
            'jam_rawat' => $request->jam_rawat,
            'keluhan' => $request->keluhan,
            'penilaian' => $request->penilaian,
            'pemeriksaan' => $request->pemeriksaan,
            'rtl' => $request->rtl,
            'instruksi' => $request->instruksi,
            'evaluasi' => $request->evaluasi,
            'suhu_tubuh' => $request->suhu_tubuh,
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'tensi' => $request->tensi,
            'respirasi' => $request->respirasi,
            'nadi' => $request->nadi,
            'spo2' => $request->spo2,
            'gcs' => $request->gcs,
            'alergi' => $request->alergi,
            'kesadaran' => $request->kesadaran,
        ];

        try {
            $pemeriksaan = PemeriksaanRanap::where($key)->update($data);
            if ($pemeriksaan) {
                $this->updateSql(new PemeriksaanRanap(), $data, $key);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function delete(Request $request)
    {
        $key = [
            'no_rawat' => $request->no_rawat,
            'tgl_perawatan' => $request->tgl_perawatan,
            'jam_rawat' => $request->jam_rawat,
        ];
        try {
            $pemeriksaan = PemeriksaanRanap::where($key)->delete();
            if ($pemeriksaan) {
                $this->deleteSql(new PemeriksaanRanap(), $key);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

}

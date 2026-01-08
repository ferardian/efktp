<?php

namespace App\Http\Controllers;

use App\Traits\Track;
use Illuminate\Http\Request;
use App\Models\PemeriksaanGigiHasil;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\QueryException;
use PhpParser\Node\Stmt\TryCatch;
use Yajra\DataTables\DataTables;

class PemeriksaanGigiHasilController extends Controller
{
    use Track;
    function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'posisi_gigi' => $request->posisi_gigi,
            'hasil' => $request->hasil,
            'kd_penyakit' => $request->kd_penyakit,
            'kd_tindakan' => $request->kd_tindakan,
            'keterangan' => $request->keterangan,
            'kd_dokter' => session()->get('pegawai')->nik,
        ];

        $isExist = PemeriksaanGigiHasil::where(['no_rawat' => $request->no_rawat, 'posisi_gigi' => $request->posisi_gigi])->first();

        if ($isExist) {
            $this->update(new \Illuminate\Http\Request($data));
            return true;
        }

        try {
            $gigi = PemeriksaanGigiHasil::create($data);
            if ($gigi) {
                $this->insertSql(new PemeriksaanGigiHasil(), $data);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function get(Request $request)
    {
        $gigi = PemeriksaanGigiHasil::with(['diagnosa', 'tindakan', 'regPeriksa'])->where('no_rawat', $request->no_rawat);
        if ($request->posisi) {
            $gigi = $gigi->where('posisi_gigi', $request->posisi)->first();
            return response()->json($gigi, 200);
        }
        $gigi = $gigi->get();
        if ($request->dataTable) {
            return DataTables::of($gigi)->make(true);
        }
        return response()->json($gigi, 200);
    }

    function update(Request $request)
    {
        $gigi = PemeriksaanGigiHasil::where(['no_rawat' => $request->no_rawat, 'posisi_gigi' => $request->posisi_gigi]);

        $data = [
            'hasil' => $request->hasil,
            'kd_penyakit' => $request->kd_penyakit,
            'kd_tindakan' => $request->kd_tindakan,
            'keterangan' => $request->keterangan,
            'kd_dokter' => session()->get('pegawai')->nik,
        ];
        if ($gigi->first()) {
            try {
                $gigi->update($data);
                if ($gigi) {
                    $this->updateSql(new PemeriksaanGigiHasil(), $data, ['no_rawat' => $request->no_rawat, 'posisi_gigi' => $request->posisi_gigi]);
                }
                return response()->json('SUKSES', 200);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
    }

    function delete(Request $request)
    {
        try {
            $gigi = PemeriksaanGigiHasil::where('id', $request->id)->delete();
            if ($gigi) {
                $this->deleteSql(new PemeriksaanGigiHasil(), ['id' => $request->id]);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
}

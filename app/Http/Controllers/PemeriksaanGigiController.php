<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\PemeriksaanGigi;
use App\Models\RegPeriksa;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isEmpty;

class PemeriksaanGigiController extends Controller
{
    use Track;
    function get(Request $request)
    {
        $gigi = PemeriksaanGigi::where('no_rawat', $request->no_rawat)
            ->with('dokter', 'regPeriksa.pasien', 'hasil')
            ->first();

        if ($request->dataTable) {
            return DataTables::of($gigi)->make(true);
        }
        return response()->json($gigi);
    }
    function getRiwayat(Request $request)
    {
        $riwayat = RegPeriksa::where('no_rkm_medis', $request->no_rkm_medis)
            ->with(['gigi' => function ($q) {
                return $q->with(['hasil' => function ($q) {
                    return $q->with('diagnosa', 'tindakan')->orderBy('posisi_gigi', 'ASC');
                }, 'dokter']);
            }, 'pasien'])
            ->whereHas('gigi')
            ->get();

        if ($request->dataTable) {
            return DataTables::of($riwayat)->make(true);
        }
        return response()->json($riwayat);
    }
    function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'kd_dokter' => $request->kd_dokter,
            'oklusi' => $request->oklusi,
            'palatinus' => $request->palatinus,
            'mandibularis' => $request->mandibularis,
            'palatum' => $request->palatum,
            'diastema' => $request->diastema,
            'ket_diastema' => $request->ket_diastema,
            'anomali' => $request->anomali,
            'ket_anomali' => $request->ket_anomali,
            'lainnya' => $request->lainnya,
            'd' => $request->d,
            'm' => $request->m,
            'f' => $request->f,
        ];
        $isExist = PemeriksaanGigi::where(['no_rawat' => $request->no_rawat])->first();
        if ($isExist) {
            $this->update(new \Illuminate\Http\Request($data));
            return true;
        }
        try {
            $gigi = PemeriksaanGigi::create($data);
            if ($gigi) {
                $this->insertSql(new PemeriksaanGigi(), $data);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 201);
        }
    }
    function update(Request $request)
    {
        $data = [
            'oklusi' => $request->oklusi,
            'palatinus' => $request->palatinus,
            'mandibularis' => $request->mandibularis,
            'palatum' => $request->palatum,
            'diastema' => $request->diastema,
            'ket_diastema' => $request->ket_diastema,
            'anomali' => $request->anomali,
            'ket_anomali' => $request->ket_anomali,
            'lainnya' => $request->lainnya,
            'd' => $request->d,
            'm' => $request->m,
            'f' => $request->f,
        ];
        try {
            $gigi = PemeriksaanGigi::where('no_rawat', $request->no_rawat)->update($data);
            if ($gigi) {
                $this->updateSql(new PemeriksaanGigi(), $data, ['no_rawat' => $request->no_rawat]);
            }
            return response()->json('SUKSES', 200);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
}

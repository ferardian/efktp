<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaPasien;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosaPasienController extends Controller
{
    use Track;
    public function show(Request $request)
    {
        $diagsnosaPasien = DiagnosaPasien::where('no_rawat', $request->no_rawat)->get();
        return $diagsnosaPasien;
    }
    public function create(Request $request)
    {
        $data = $request->data;

        for ($i = 0; $i < count($data); $i++) {
            try {
                $diagnosa = DiagnosaPasien::create($data[$i]);
                if ($diagnosa) {
                    $this->insertSql(new DiagnosaPasien(), $data[$i]);
                }
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
        return response()->json('SUKSES');
    }
    public function get(Request $request)
    {
        $key = [
            'no_rawat' => $request->no_rawat,
        ];
        $diagnosa = DiagnosaPasien::where($key)->with('penyakit')->orderBy('prioritas', 'ASC')->get();

        return response()->json($diagnosa);
    }
    public function delete(Request $request)
    {
        $key = [
            'no_rawat' => $request->no_rawat,
            'kd_penyakit' => $request->kd_penyakit,
        ];
        try {
            $diagnosa = DiagnosaPasien::where($key)->delete();
            if ($diagnosa) {
                $this->deleteSql(new DiagnosaPasien(), $key);
            }
            return response()->json($diagnosa);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $delete = DiagnosaPasien::where('no_rawat', $request->data[0]['no_rawat'])->delete();
            if ($delete) {
                $this->deleteSql(new DiagnosaPasien(), ['no_rawat' => $request->data[0]['no_rawat']]);
                $this->create($request);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }

        return response()->json('SUKSES');

    }

    public function grafik(Request $request)
    {
        $diagnosa = DiagnosaPasien::with(['penyakit' => function ($q) {
            return $q->select('kd_penyakit', 'nm_penyakit');
        }])->where('prioritas', 1)->groupBy('kd_penyakit')
            ->select('kd_penyakit', DB::raw('COUNT(*) as count'));

        if ($request->tglDiagnosa1 || $request->tglDiagnosa2) {
            $diagnosa = $diagnosa->whereHas('regPeriksa', function ($q) use ($request) {
                return $q->whereBetween('tgl_registrasi', [$request->tglDiagnosa1, $request->tglDiagnosa2]);
            });
        } else {
            $diagnosa = $diagnosa->whereHas('regPeriksa', function ($q) use ($request) {
                return $q->whereMonth('tgl_registrasi', date('m'))->whereYear('tgl_registrasi', date('Y'));
            });
        }
        $diagnosa = $diagnosa->limit(10)->orderBy('count', 'DESC')->get();
        return response()->json($diagnosa);
    }
}

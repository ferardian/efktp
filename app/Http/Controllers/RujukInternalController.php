<?php

namespace App\Http\Controllers;

use App\Models\RujukInternal;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use League\CommonMark\Extension\SmartPunct\EllipsesParser;
use Yajra\DataTables\DataTables;

class RujukInternalController extends Controller
{
    use Track;
    function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'kd_dokter' => $request->kd_dokter,
            'kd_poli' => $request->kd_poli,
        ];

        try {
            $rujuk = RujukInternal::create($data);
            if ($rujuk) {
                $this->insertSql(new RujukInternal(), $data);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo);
        }
    }
    function get(Request $request)
    {
        $rujuk = RujukInternal::with(['regPeriksa.pasien','poliAsal','dokterAsal', 'dokter', 'poliklinik', 'pemeriksaanAwal' , 'pemeriksaan']);
        if ($request->tglAwal || $request->tglAkhir) {
            $get = $rujuk->whereHas('regPeriksa', function ($query) use ($request) {
                return $query->whereBetween('tgl_registrasi', [date('Y-m-d', strtotime($request->tglAwal)), date('Y-m-d', strtotime($request->tglAkhir)), date('Y-m-d',)]);
            })->get();
        } else {
            $get = $rujuk->whereHas('regPeriksa', function ($query) use ($request) {
                return $query->where('tgl_registrasi', date('Y-m-d'));
            })->get();
        }
        if ($request->dataTable) {
            return DataTables::of($get)->make(true);
        }
        return response()->json($get);
    }

    function update(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'kd_dokter' => $request->kd_dokter,
            'kd_poli' => $request->kd_poli,
        ];


        try {
            $request->validate(['no_rawat' => 'required','kd_dokter' => 'required','kd_poli' => 'required']);
            $rujuk = RujukInternal::where([
                'no_rawat' => $request->no_rawat,
            ])->update();
            if ($rujuk) {
                $this->updateSql(new RujukInternal(), $data, [
                    'no_rawat' => $request->no_rawat,
                ]);
            }
            return response()->json('SUKSES', 201);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    function show(Request $request)
    {
        $rujuk = RujukInternal::where('no_rawat', $request->no_rawat)
            ->with(['poliklinik', 'pemeriksaan', 'dokter', 'regPeriksa' => function ($query) {
                return $query->with(['pasien.alergi', 'penjab', 'pemeriksaanDokter']);
            }])->first();
        return response()->json($rujuk);
    }
    function delete(Request $request)
    {
        try {
            $delete = RujukInternal::where('no_rawat', $request->no_rawat)->delete();
            if ($delete) {
                $this->deleteSql(new RujukInternal(), ['no_rawat' => $request->no_rawat]);
            }
            return response()->json('SUKSES');
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Rujuk;
use App\Models\Setting;
use App\Traits\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RujukController extends Controller
{
    use Track;
    protected $rujuk;
    public function __construct()
    {
        $this->rujuk = new Rujuk();
    }

    public function get(Request $request)
    {
        $rujuk = $this->rujuk;

        if ($request->tglAwal && $request->tglAkhir) {
            $rujuk = $rujuk->tglBetween([date('Y-m-d', strtotime($request->tglAwal)), date('Y-m-d', strtotime($request->tglAkhir))])->get();
        } else {
            $rujuk = $rujuk->where('tgl_rujuk', date('Y-m-d'))->get();
        }

        if ($request->dataTable) {
            return DataTables::of($rujuk)->make(true);
        }
        return response()->json($rujuk);
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'no_rawat' => 'required',
            'no_rujuk' => 'required',
            'kd_dokter' => 'required',
            'kat_rujuk' => 'required',
            'keterangan_diagnosa' => 'required',
            'rujuk_ke' => 'required',
            'tgl_rujuk' => 'required',
            'jam' => 'required',
            'keterangan' => 'required',
            'ambulance' => 'required',
            'no_rujuk' => 'required',
        ]);

        $data = [
            'no_rawat' => $request->no_rawat,
            'no_rujuk' => json_decode($this->setNoRujuk()->getContent()),
            'kd_dokter' => $request->kd_dokter,
            'kat_rujuk' => $request->kat_rujuk,
            'keterangan_diagnosa' => $request->keterangan_diagnosa,
            'rujuk_ke' => $request->rujuk_ke,
            'tgl_rujuk' => date('Y-m-d', strtotime($request->tgl_rujuk)),
            'jam' => $request->jam,
            'keterangan' => $request->keterangan,
            'ambulance' => $request->ambulance,
        ];

        $isExist = $this->rujuk->where('no_rawat', $request->no_rawat)->first();

        if ($isExist) {
            $this->update($request);
            return true;
        }

        try {
            $rujuk = $this->rujuk->create($data);
            if ($rujuk) {
                $this->insertSql(new Rujuk(), $data);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json(['message' => 'SUKSES', 'data' => $rujuk], 201);
    }

    public function update(Request $request)
    {
        $keys = [
            'no_rawat' => $request->no_rawat,
            'no_rujuk' => $request->no_rujuk,
        ];

        $validate = $request->validate([
            'kd_dokter' => 'required',
            'kat_rujuk' => 'required',
            'keterangan_diagnosa' => 'required',
            'rujuk_ke' => 'required',
            'tgl_rujuk' => 'required',
            'jam' => 'required',
            'keterangan' => 'required',
            'ambulance' => 'required',
        ]);

        $data = [
            'kd_dokter' => $request->kd_dokter,
            'kat_rujuk' => $request->kat_rujuk,
            'keterangan_diagnosa' => $request->keterangan_diagnosa,
            'rujuk_ke' => $request->rujuk_ke,
            'tgl_rujuk' => date('Y-m-d', strtotime($request->tgl_rujuk)),
            'jam' => $request->jam,
            'keterangan' => $request->keterangan,
            'ambulance' => $request->ambulance,
        ];
        try {
            $rujuk = $this->rujuk->where($keys)->update($data);
            if ($rujuk) {
                $this->updateSql(new Rujuk(), $keys, $data);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);
    }

    public function delete(Request $request)
    {
        $keys = [
            'no_rawat' => $request->no_rawat,
        ];
        try {
            $rujuk = $this->rujuk->where($keys)->delete();
            if ($rujuk) {
                $this->deleteSql(new Rujuk(), $keys);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);
    }

    public function detail(Request $request)
    {
        $rujuk = $this->rujuk;

        if ($request->no_rujuk) {
            $rujuk = $rujuk->noRujuk($request->no_rujuk)->first();
        }

        if ($request->no_rawat) {
            $rujuk = $rujuk->noRawat($request->no_rawat)->first();
        }
        return response()->json($rujuk);
    }
    public function setNoRujuk()
    {

        $rujuk = $this->rujuk->orderBy('no_rujuk', 'DESC')->first();

        if (!$rujuk) {
            $no_rujuk = 'R' . sprintf('%09d', 1);
        } else {

            $no_rujuk = (int) explode('R', $rujuk->no_rujuk)[1] + 1;
            $no_rujuk = 'R' . sprintf('%09d', $no_rujuk);
        }
        return response()->json($no_rujuk);
    }
    public function getKeterangan(Request $request)
    {
        $keterangan = $this->rujuk->select('keterangan')->groupBy('keterangan');

        if ($request->keterangan) {
            $keterangan = $keterangan->where('keterangan', 'LIKE', '%' . $request->keterangan . '%')->get();
        } else {
            $keterangan = $keterangan->limit(10)->get();
        }

        return response()->json($keterangan);
    }
    public function getFaskesRujuk(Request $request)
    {
        $faskes = $this->rujuk->select('rujuk_ke')->groupBy('rujuk_ke');

        if ($request->faskes) {
            $faskes = $faskes->where('rujuk_ke', 'LIKE', '%' . $request->faskes . '%')->get();
        } else {
            $faskes = $faskes->limit(10)->get();
        }

        return response()->json($faskes);
    }
    public function print(Request $request)
    {
        if ($request->has('no_rawat')) {
            $key = ['no_rawat' => $request->no_rawat];
        }

        try {
            $data = $this->rujuk->where($key)
                ->first();
            $setting = Setting::first()->toArray();
            $pdf = PDF::loadView('content.print.rujukEksternal', ['data' => $data->toArray(), 'setting' => $setting]);
            $pdf->setPaper('a5', 'portrait')->setOptions(['defaultFont' => 'sherif', 'isRemoteEnabled' => true]);
            return $pdf->stream("surat-rujukan-keluar-{$data->pasien->nm_pasien}.pdf");
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
}

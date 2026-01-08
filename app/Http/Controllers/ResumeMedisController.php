<?php

namespace App\Http\Controllers;

//use App\Models\ResumeMedis;
use App\Models\ResumeMedis;
use App\Traits\Track;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ResumeMedisController extends Controller
{
    use Track;
    function get(Request $request) : object
    {
        $resume = new ResumeMedis();
        $resume = $resume->where('no_rawat', $request->no_rawat)
            ->with('pasien')
            ->first();
        return response()->json($resume);
    }


    function create(Request $request)
    {
        $data = [
            'no_rawat' => $request->no_rawat,
            'kd_dokter' => $request->kd_dokter,
            'diagnosa_awal' => $request->diagnosa_awal,
            'alasan' => $request->alasan,
            'keluhan_utama' => $request->keluhan_utama,
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'jalannya_penyakit' => $request->jalannya_penyakit ? $request->jalannya_penyakit : '-',
            'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
            'hasil_laborat' => $request->hasil_laborat,
            'tindakan_dan_operasi' => $request->tindakan_dan_operasi ? $request->tindakan_dan_operasi : '-',
            'obat_di_rs' => $request->obat_di_rs,
            'diagnosa_utama' => $request->diagnosa_utama,
            'kd_diagnosa_utama' => $request->kd_diagnosa_utama,
            'diagnosa_sekunder' => $request->diagnosa_sekunder,
            'kd_diagnosa_sekunder' => $request->kd_diagnosa_sekunder,
            'diagnosa_sekunder2' => $request->diagnosa_sekunder2,
            'kd_diagnosa_sekunder2' => $request->kd_diagnosa_sekunder2,
            'diagnosa_sekunder3' => $request->diagnosa_sekunder3,
            'kd_diagnosa_sekunder3' => $request->kd_diagnosa_sekunder3,
            'diagnosa_sekunder4' => $request->diagnosa_sekunder4,
            'kd_diagnosa_sekunder4' => $request->kd_diagnosa_sekunder4,
            'prosedur_utama' => $request->prosedur_utama,
            'kd_prosedur_utama' => $request->kd_prosedur_utama,
            'prosedur_sekunder' => $request->prosedur_sekunder,
            'kd_prosedur_sekunder' => $request->kd_prosedur_sekunder,
            'prosedur_sekunder2' => $request->prosedur_sekunder2,
            'kd_prosedur_sekunder2' => $request->kd_prosedur_sekunder2,
            'prosedur_sekunder3' => $request->prosedur_sekunder3,
            'kd_prosedur_sekunder3' => $request->kd_prosedur_sekunder3,
            'alergi' => $request->alergi ? $request->alergi :'-',
            'diet' => $request->diet ? $request->diet : '-',
            'lab_belum' => $request->lab_belum ? $request->lab_belum  : '-',
            'edukasi' => $request->edukasi,
            'cara_keluar' => $request->cara_keluar,
            'ket_keluar' => $request->ket_keluar,
            'keadaan' => $request->keadaan,
            'ket_keadaan' => $request->ket_keadaan,
            'dilanjutkan' => $request->dilanjutkan,
            'ket_dilanjutkan' => $request->ket_dilanjutkan,
            'kontrol' => date('Y-m-d H:i:s', strtotime($request->kontrol)),
            'obat_pulang' => $request->obat_pulang,
        ];

        $isAvail = ResumeMedis::where('no_rawat', $request->no_rawat)->first();

        if($isAvail){
            $this->update($request);
            return true;
        }

        try {
            $create = ResumeMedis::create($data);
            if($create){
                $this->insertSql(new ResumeMedis(), $data);
            }
        }catch (QueryException $e){
            return response()->json($e->errorInfo, 500);
        }

        return response()->json('SUKSES', 201);
    }

    function update(Request $request){
        $data = [
            'kd_dokter' => $request->kd_dokter,
            'diagnosa_awal' => $request->diagnosa_awal,
            'alasan' => $request->alasan,
            'keluhan_utama' => $request->keluhan_utama,
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'jalannya_penyakit' => $request->jalannya_penyakit ? $request->jalannya_penyakit : '-',
            'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
            'hasil_laborat' => $request->hasil_laborat,
            'tindakan_dan_operasi' => $request->tindakan_dan_operasi ? $request->tindakan_dan_operasi : '-',
            'obat_di_rs' => $request->obat_di_rs,
            'diagnosa_utama' => $request->diagnosa_utama,
            'kd_diagnosa_utama' => $request->kd_diagnosa_utama,
            'diagnosa_sekunder' => $request->diagnosa_sekunder,
            'kd_diagnosa_sekunder' => $request->kd_diagnosa_sekunder,
            'diagnosa_sekunder2' => $request->diagnosa_sekunder2,
            'kd_diagnosa_sekunder2' => $request->kd_diagnosa_sekunder2,
            'diagnosa_sekunder3' => $request->diagnosa_sekunder3,
            'kd_diagnosa_sekunder3' => $request->kd_diagnosa_sekunder3,
            'diagnosa_sekunder4' => $request->diagnosa_sekunder4,
            'kd_diagnosa_sekunder4' => $request->kd_diagnosa_sekunder4,
            'prosedur_utama' => $request->prosedur_utama,
            'kd_prosedur_utama' => $request->kd_prosedur_utama,
            'prosedur_sekunder' => $request->prosedur_sekunder,
            'kd_prosedur_sekunder' => $request->kd_prosedur_sekunder,
            'prosedur_sekunder2' => $request->prosedur_sekunder2,
            'kd_prosedur_sekunder2' => $request->kd_prosedur_sekunder2,
            'prosedur_sekunder3' => $request->prosedur_sekunder3,
            'kd_prosedur_sekunder3' => $request->kd_prosedur_sekunder3,
            'alergi' => $request->alergi ? $request->alergi :'-',
            'diet' => $request->diet ? $request->diet : '-',
            'lab_belum' => $request->lab_belum ? $request->lab_belum  : '-',
            'edukasi' => $request->edukasi,
            'cara_keluar' => $request->cara_keluar,
            'ket_keluar' => $request->ket_keluar,
            'keadaan' => $request->keadaan,
            'ket_keadaan' => $request->ket_keadaan,
            'dilanjutkan' => $request->dilanjutkan,
            'ket_dilanjutkan' => $request->ket_dilanjutkan,
            'kontrol' => date('Y-m-d H:i:s', strtotime($request->kontrol)),
            'obat_pulang' => $request->obat_pulang,
        ];
        $key = ['no_rawat' => $request->no_rawat];
        try {
            $update = ResumeMedis::where($key)->update($data);
            if($update){
                $this->updateSql(new ResumeMedis(), $data, $key);
            }
        }catch(QueryException $e){
            return response()->json($e->errorInfo, 500);
        }

        return response()->json('SUKSES', 201);
    }

}

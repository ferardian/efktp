<?php

namespace App\Http\Controllers;

use App\Models\setNoRkmMedis;
use App\Models\Pasien;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class setNoRkmMedisController extends Controller
{
    use Track;
    function get()
    {
        $noRkmMedis = setNoRkmMedis::first();
        $setNoRm = $noRkmMedis->no_rkm_medis + 1;
        return response()->json(sprintf('%06d', $setNoRm));

    }
    function delete(Request $request)
    {
        try{
            $delete = setNoRkmMedis::truncate();
            $this->deleteSql(new SetNoRkmMedis(), ['no_rkm_medis' => $request->no_rkm_medis]);
        }catch(QueryException $e){
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);

    }

    function create(Request $request){

        try {
            $this->delete($request);
            $createNoRm = setNoRkmMedis::create(['no_rkm_medis' => $request->no_rkm_medis]);
            $this->insertSql(new setNoRkmMedis(), ['no_rkm_medis' => $request->no_rkm_medis]);
        }catch (QueryException $e){
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);
    }

}

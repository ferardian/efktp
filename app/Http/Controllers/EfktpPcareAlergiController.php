<?php

namespace App\Http\Controllers;

use App\Models\EfktpPcareAlergi;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EfktpPcareAlergiController extends Controller
{
    use Track;

    function create(Request $request)
    {
        $key = [
            'no_rkm_medis' => $request->no_rkm_medis,

        ];
        $arrAlergi = $request->alergi;
        if ($arrAlergi) {
            $countAlergi =  count($arrAlergi);
            try {
                $findalergi = EfktpPcareAlergi::where('no_rkm_medis', $request->no_rkm_medis)->get();
                if ($findalergi) {
                    $deleted = EfktpPcareAlergi::where('no_rkm_medis', $request->no_rkm_medis)->delete();
                    if ($deleted) {
                        $this->deleteSql(new EfktpPcareAlergi(), ['no_rkm_medis' => $request->no_rkm_medis]);
                    }
                }
                for ($i = 0; $i < $countAlergi; $i++) {
                    $data = [
                        'no_rkm_medis' => $request->no_rkm_medis,
                        'alergi' => $arrAlergi[$i]
                    ];
                    $alergi = EfktpPcareAlergi::create($data);
                    if ($alergi) {
                        $this->insertSql(new EfktpPcareAlergi(), $data);
                    }
                }
                return response()->json('SUKES', 201);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        } else {
            $deleted = EfktpPcareAlergi::where('no_rkm_medis', $request->no_rkm_medis)->delete();
            return response()->json($deleted);
        }
    }
    function get(Request $request)
    {

        $alergi = EfktpPcareAlergi::where('no_rkm_medis', $request->no_rkm_medis)
            ->orWhere('alergi', 'like', '%' . $request->alergi . '%')
	        ->limit(10)
	        ->get();
        return response()->json($alergi);
    }
}

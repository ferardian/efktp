<?php

namespace App\Http\Controllers;

use App\Models\EfktpTemplateRacikan;
use App\Models\EfktpTemplateRacikanDetail;
use App\Traits\Track;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EfktpTemplateRacikanDetailController extends Controller
{
    use Track;

    function create(Request $request)
    {
        $racik = $request->nm_racik;
        $obat = $request->obat;
        $getTemplate = EfktpTemplateRacikan::where('nm_racik', $racik)->first();
        for ($i = 0; $i < count($obat); $i++) {
            try {
                $data = [
                    'id_racik' => $getTemplate->id,
                    'kode_brng' => $obat[$i]['kode_brng'],
                ];
                $create = EfktpTemplateRacikanDetail::create($data);
                if ($create) {
                    $this->insertSql(new EfktpTemplateRacikanDetail(), $data);
                }
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
        return response()->json('SUKSES');
    }
    function delete(Request $request)
    {
        try {
            $delete = EfktpTemplateRacikanDetail::where('id_racik', $request->id)->delete();
            if ($delete) {
                $this->deleteSql(new EfktpTemplateRacikanDetail(), ['id_racik' => $request->id]);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES');
    }
}

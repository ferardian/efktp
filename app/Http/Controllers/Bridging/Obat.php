<?php

namespace App\Http\Controllers\Bridging;

use AamDsam\Bpjs\PCare;
use App\Traits\PcareConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class Obat extends Controller
{
    use PcareConfig;

    public $bpjs;
    public function __construct()
    {
        $this->bpjs = new Pcare\Obat($this->config());
    }

    public function get($keyword)
    {
        $bpjs = $this->bpjs;
        return $bpjs->dpho($keyword)->index(0, 100);
    }

    function create(Request $request)
    {
        $obat = $request->data;
        $bpjs = $this->bpjs->kunjungan('');
        foreach ($obat as $o => $item) {
            $post = [
                'kdObatSK' => 0,
                'noKunjungan' => $item['noKunjungan'],
                'racikan' => (bool)$item['racikan'],
                'kdRacikan' => $item['kdRacikan'],
                'obatDPHO' => (bool)$item['obatDPHO'],
                'kdObat' => $item['kdObat'],
                'signa1' => (int)$item['signa1'],
                'signa2' => (int)$item['signa2'],
                'jmlObat' => (int)$item['jmlObat'],
                'jmlPermintaan' => (int) $item['jmlPermintaan'],
                'nmObatNonDPHO' => $item['nmObatNonDPHO'],
            ];
            try {
                $store = $bpjs->store($post);
                $response[] = [$store, $post];
            } catch (QueryException $e) {
                return $e->errorInfo;
            }
        }
        return response()->json($response, 200);
    }
}

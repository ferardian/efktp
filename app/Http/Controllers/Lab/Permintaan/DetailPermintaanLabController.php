<?php

namespace App\Http\Controllers\Lab\Permintaan;

use App\Traits\ResponseHandlerTrait;
use App\Traits\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lab\DetailPemeriksaanLab;
use App\Models\Lab\Permintaan\DetailPermintaanLab;

class DetailPermintaanLabController extends Controller
{
    use Track, ResponseHandlerTrait;
    protected $detail;

    public function __construct()
    {
        $this->detail = new DetailPermintaanLab();
    }

    function create(Request $request)
    {
        foreach ($request->data as $item => $value) {
            $data = [
                'noorder' => $value['noorder'],
                'id_template' => $value['id_template'],
                'kd_jenis_prw' => $value['kd_jenis_prw'],
                'stts_bayar' => 'Belum',
            ];
            try {
                $detail = $this->detail->create($data);
                if ($detail) {
                    $this->insertSql($this->detail, $data);
                }
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
        return response()->json([
            'status' => 'Sukses',
            'message' => 'Data berhasil disimpan'
        ]);
    }

	function get($noorder, $kd_jenis_prw='') : JsonResponse
	{
		$permintaan = $this->detail->with(['item'=>function($q){
			return $q->select(['kd_jenis_prw', 'id_template', 'Pemeriksaan as nama', 'nilai_rujukan_ld as ld', 'nilai_rujukan_la as la', 'nilai_rujukan_pd as pd', 'nilai_rujukan_pa as pa', 'satuan']);
		}, 'jenis' => function($q){
			return $q->select(['nm_perawatan', 'kd_jenis_prw']);
		}]);

		if(!$kd_jenis_prw){
			$permintaan = $permintaan->where('noorder', $noorder)->get();
		}else{
			$permintaan = $permintaan->where(['noorder'=>$noorder,  'kd_jenis_prw' => $kd_jenis_prw])->first();
		}

		return $this->success($permintaan);

	}
}

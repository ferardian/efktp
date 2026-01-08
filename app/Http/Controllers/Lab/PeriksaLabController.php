<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\PeriksaLab;
use Illuminate\Http\Request;

class PeriksaLabController extends Controller
{
    protected $periksa;

    public function __construct()
    {
        $this->periksa = new PeriksaLab();
    }

    public function get(Request $request)
    {

        $clause = [
            'no_rawat' => $request->no_rawat,
        ];

        $request->has('tgl') ?  $clause['tgl_periksa'] = date('Y-m-d', strtotime($request->tgl)) : null;
        $request->has('kode') ?  $clause['kd_jenis_prw'] = $request->kode : null;

        $periksa = $this->periksa->where($clause);

        $periksa = $periksa->select('no_rawat', 'nip', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'kd_dokter', 'dokter_perujuk', 'status')
            ->with([
                'pegawai',
                'jenis' => function ($q) {
                    return $q->select(['nm_perawatan', 'kd_jenis_prw']);
                },
                'detail' => function ($q) {
                    return $q->select(['no_rawat', 'kd_jenis_prw', 'tgl_periksa', 'jam', 'id_template', 'nilai', 'nilai_rujukan', 'keterangan'])
                        ->with('template', function ($q) {
                            return $q->select(['kd_jenis_prw', 'id_template', 'Pemeriksaan as nama', 'satuan']);
                        });
                }
            ])->get();

        return $this->success(['count' => count($periksa), 'result' => $periksa]);
    }
}

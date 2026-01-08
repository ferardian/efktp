<?php

namespace App\Http\Controllers;

use App\Models\JenisPerawatan;
use Illuminate\Http\Request;

class JenisPerawatanController extends Controller
{
    protected $model;
    function __construct(JenisPerawatan $model)
    {
        $this->model = $model;
    }
    public function dataTable(Request $request)
    {
        $data = $this->model->with(['kategori', 'penjab', 'poliklinik'])
            ->where('status', '1')->orderBy('kd_jenis_prw', 'asc');
        if ($request->kd_kategori) {
            $data = $data->where('kd_kategori', $request->kd_kategori);
        }
        if ($request->kd_pj) {
            $data = $data->where('kd_pj', $request->kd_pj);
        }
        if ($request->kd_poli) {
            $data = $data->where('kd_poli', $request->kd_poli);
        }
        return datatables()->of($data)

            ->addColumn('_checked', function ($row) {
                return false;
            })
            ->make(true);
    }
}

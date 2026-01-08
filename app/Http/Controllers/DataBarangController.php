<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DataBarangController extends Controller
{

    public function index()
    {
        return view('content.dataBarang');
    }
    public function get(Request $request)
    {
        $barang = DataBarang::where('status', '1')
            ->with(['satuan', 'satuanBesar', 'jenis', 'golongan', 'industri', 'kategori', 'mapping', 'gudangBarang.lokasi']);
        if ($request) {
            $barang = $barang
                ->where('nama_brng', 'like', '%' . $request->barang . "%")
                ->whereHas('jenis', function ($query) {
                    return $query->where('nama', 'not like', 'logistik');
                })->orderBy('nama_brng', 'ASC')
                ->get();
        } else {
            $barang = $barang->whereHas('jenis', function ($query) {
                return $query->where('nama', 'not like', 'logistik');
            })->limit(10)->orderBy('nama_brng', 'ASC')->get();
        }

        if ($request->dataTable) {
            return DataTables::of($barang)->make(true);
        }
        return response()->json($barang);
    }

}

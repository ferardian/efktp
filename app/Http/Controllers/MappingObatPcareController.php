<?php

namespace App\Http\Controllers;

use App\Models\MappingObatPcare;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MappingObatPcareController extends Controller
{
    public function get(Request $request)
    {
        $obat = MappingObatPcare::with('obat');
        if ($request->kode) {
            $obat = $obat->where('kode_brng', $request->kode)->first();
        } else {
            $obat = $obat->get();
        }
        return response()->json($obat);
    }

    public function create(Request $request): JsonResponse
    {
        $data = [
            'kode_brng' => $request->kode_brng,
            'kode_brng_pcare' => $request->kode,
            'nama_brng_pcare' => $request->nama,
        ];

        $isAvailable = MappingObatPcare::where('kode_brng', $request->kode_brng)->first();
        if ($isAvailable) {
            return $this->update($request);
        }
        try {
            $store = MappingObatPcare::create($data);
            if ($store) {
                $this->insertSql(new MappingObatPcare(), $data);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 201);
    }

    public function update(Request $request)
    {
        $data = [
            'kode_brng' => $request->kode_brng,
            'kode_brng_pcare' => $request->kode,
            'nama_brng_pcare' => $request->nama,
        ];
        try {
            $update = MappingObatPcare::where('kode_brng', $request->kode_brng)->update($data);
            if ($update) {
                $this->updateSql(new MappingObatPcare(), $data, ['kode_brng' => $request->kode_brng]);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 201);
    }

    public function delete($kdBarang): JsonResponse
    {
        try {
            $delete = MappingObatPcare::where('kode_brng', $kdBarang)->delete();
            if ($delete) {
                $this->deleteSql(new MappingObatPcare(), ['kode_brng' => $kdBarang]);
            }
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);
    }
}

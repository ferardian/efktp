<?php

namespace App\Http\Controllers;

use App\Models\ResepDokter;
use App\Traits\Track;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ResepDokterController extends Controller
{
    use Track;
    //
    public function get(Request $request)
    {
        $resep = ResepDokter::where('no_resep', $request->no_resep)
            ->with('obat.satuan')->get();
        return response()->json($resep);
    }

    public function masterAturanPakai(Request $request)
    {
        $keyword = $request->keyword;
        $aturan = DB::table('master_aturan_pakai')
            ->where('aturan', 'like', "%$keyword%")
            ->limit(50)
            ->get();
        return response()->json($aturan);
    }

    public function storeAturanPakai(Request $request)
    {
        if ($request->has('aturan') && !empty($request->aturan)) {
            DB::table('master_aturan_pakai')->insertOrIgnore([
                'aturan' => $request->aturan
            ]);
        }
        return response()->json('SUKSES');
    }

    public function create(Request $request)
    {
        // return $request->dataObat;
        // return ['key' => array_keys($request->dataObat), 'val' => array_values($request->dataObat)];
        try {
            DB::transaction(function () use ($request) {
                $resep = ResepDokter::insert($request->dataObat);
                if ($resep) {
                    $this->insertSql(new ResepDokter(), collect($request->dataObat)->map(function ($item) {
                        return $item;
                    }));

                    // Otomatis simpan aturan pakai baru ke master
                    foreach ($request->dataObat as $obat) {
                        if (!empty($obat['aturan_pakai'])) {
                            DB::table('master_aturan_pakai')->insertOrIgnore([
                                'aturan' => $obat['aturan_pakai']
                            ]);
                        }
                    }
                }
            });
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
        return response()->json('SUKSES', 200);
    }
    public function delete(Request $request)
    {
        $key = [
            'no_resep' => $request->no_resep,
            'kode_brng' => $request->kode_brng,
        ];
        try {
            $resep = ResepDokter::where($key)->delete();
            if ($resep) {
                $this->deleteSql(new ResepDokter(), $key);
            }
            return response()->json($resep);
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 500);
        }
    }

    public function update(Request $request)
    {
        $key = [
            'no_resep' => $request->no_resep,
            'kode_brng' => $request->kode_brng,
        ];

        try {
            $resep = ResepDokter::where($key)->update($request->all());
            if ($resep) {
                $this->updateSql(new ResepDokter(), $request->all(), $key);

                // Otomatis simpan aturan pakai baru ke master jika ada perubahan
                if ($request->has('aturan_pakai') && !empty($request->aturan_pakai)) {
                    DB::table('master_aturan_pakai')->insertOrIgnore([
                        'aturan' => $request->aturan_pakai
                    ]);
                }
            }
            return response()->json('SUKSES');
        } catch (QueryException $e) {
            return response()->json($e->errorInfo, 400);
        }
    }
}

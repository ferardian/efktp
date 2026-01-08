<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\EfktpKategoriBerkasPenunjang;
use Illuminate\Database\QueryException;

class EfktpKategoriBerkasPenunjangController extends Controller
{
    function get(Request $request): JsonResponse
    {
		$kategoris = EfktpKategoriBerkasPenunjang::where('kategori', 'like', "%$request->kategori%")->get();
        return response()->json($kategoris);
    }

	function getFirst() : JsonResponse
	{
        $kategoris = EfktpKategoriBerkasPenunjang::orderBy('id', 'ASC')->first();
        return response()->json($kategoris);
	}
    function create(Request $request): JsonResponse
    {
        $kategori = EfktpKategoriBerkasPenunjang::where('kategori', $request->kategori)
            ->orWhere('id', $request->kategori)->first();

        if (!$kategori) {
            $data  = [
                'kategori' => strtoupper($request->kategori),
            ];
            try {
                $kategoris = EfktpKategoriBerkasPenunjang::create($data);
                return response()->json(['message' => 'SUKSES', 'data' => $kategoris]);
            } catch (QueryException $e) {
                return response()->json($e->errorInfo, 500);
            }
        }
        return response()->json(['message' => 'OK']);
    }


}

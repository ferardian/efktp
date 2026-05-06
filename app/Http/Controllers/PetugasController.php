<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function get(Request $request)
    {
        $data = Petugas::where('status', '1');
        
        if ($request->petugas) {
            $data = $data->where('nama', 'like', '%' . $request->petugas . '%');
        }

        return response()->json($data->limit(20)->get());
    }
}

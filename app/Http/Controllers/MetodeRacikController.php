<?php

namespace App\Http\Controllers;

use App\Models\MetodeRacik;
use Illuminate\Http\Request;

class MetodeRacikController extends Controller
{
    //

    function get(Request $request)
    {
        if($request){
            $metode = MetodeRacik::where('nm_racik', 'like', '%'.$request->racik.'%')->get();
        }else{
            $metode = MetodeRacik::get();
        }
        return response()->json($metode);
    }
}

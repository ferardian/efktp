<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    function getKodePPK()
    {
        $setting = Setting::select('kode_ppk')->first();
        return response()->json($setting->kode_ppk);
    }
}

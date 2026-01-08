<?php

namespace App\Http\Controllers;

use App\Models\Poliklinik;
use App\Models\Setting;
use Illuminate\Http\Request;

class AntreanController extends Controller
{
    protected function setting()
    {
        $setting = Setting::first();

        if ($setting) {
            $setting->logo = 'data:image/jpeg;base64,' . base64_encode($setting->logo);
            $setting->wallpaper = 'data:image/jpeg;base64,' . base64_encode($setting->wallpaper);
        }

        return $setting;
    }

    public function poliklinik()
    {
        return view('antrean.poliklinik', [
            'data' => $this->setting()
        ]);
    }

    public function poliklinikV2()
    {
        return view('antrean.poliklinik2', [
            'data' => $this->setting(),
            'poliklinik' => Poliklinik::active()->get()
        ]);
    }

    public function farmasi()
    {
        return view('antrean.farmasi', [
            'data' => $this->setting()
        ]);
    }
}

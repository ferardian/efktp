<?php

namespace App\Traits;

use App\Models\BridgingPcareSetting;

trait PcareConfig
{

    public static function config()
    {
        $setting = BridgingPcareSetting::first();
        return $config = [
            'cons_id' => config('bpjs.cons_id'),
            'secret_key' => config('bpjs.secret_key'),
            'user_key' => config('bpjs.user_key'),
            'base_url' => config('bpjs.base_url'),
            'app_code' => config('bpjs.app_code'),
            'icare_url' => config('bpjs.icare_url'),
            'username' => $setting->user,
            'password' => $setting->password,
            'user_icare' => $setting->userIcare,
            'password_icare' => $setting->passwordIcare,
        ];
    }
}

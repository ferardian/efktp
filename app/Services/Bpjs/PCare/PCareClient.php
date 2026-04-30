<?php

namespace App\Services\Bpjs\PCare;

use App\Models\BridgingPcareSetting;
use App\Services\Bpjs\BpjsHttpClient;

class PCareClient extends BpjsHttpClient
{
    public function __construct()
    {
        $setting = BridgingPcareSetting::first();

        parent::__construct([
            'base_url'   => config('bpjs.base_url'),
            'cons_id'    => config('bpjs.cons_id'),
            'secret_key' => config('bpjs.secret_key'),
            'user_key'   => config('bpjs.user_key'),
            'app_code'   => config('bpjs.app_code', '095'),
            'username'   => $setting?->user ?? '',
            'password'   => $setting?->password ?? '',
        ]);
    }
}

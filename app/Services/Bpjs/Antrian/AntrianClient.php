<?php

namespace App\Services\Bpjs\Antrian;

use App\Services\Bpjs\BpjsHttpClient;

class AntrianClient extends BpjsHttpClient
{
    public function __construct()
    {
        parent::__construct([
            'base_url'   => config('bpjs.antrian.base_url'),
            'cons_id'    => config('bpjs.antrian.cons_id'),
            'secret_key' => config('bpjs.antrian.secret_key'),
            'user_key'   => config('bpjs.antrian.user_key'),
            'app_code'   => config('bpjs.antrian.app_code', '095'),
            // Antrian FKTP tidak butuh username/password Basic Auth
            'username'   => '',
            'password'   => '',
        ]);
    }

    /**
     * Override headers untuk Antrian FKTP
     * (tidak ada X-authorization Basic, hanya user_key)
     */
    protected function headers(): array
    {
        return [
            'X-cons-id'   => $this->consId,
            'X-timestamp' => $this->timestamp,
            'X-signature' => $this->signature,
            'user_key'    => $this->userKey,
            'Content-Type'=> 'application/json',
            'Accept'      => 'application/json',
        ];
    }
}

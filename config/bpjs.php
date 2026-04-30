<?php
return [
    // PCare
    'cons_id'    => env('PCARE_CONS_ID'),
    'secret_key' => env('PCARE_SECRET_KEY'),
    'user_key'   => env('PCARE_USER_KEY'),
    'base_url'   => env('PCARE_BASE_URL'),
    'app_code'   => env('PCARE_APP_CODE', '095'),
    'icare_url'  => env('ICARE_BASE_URL'),

    // Antrian FKTP
    'antrian' => [
        'enabled'    => env('ANTRIAN_ENABLED', false),
        'base_url'   => env('ANTRIAN_BASE_URL', 'https://apijkn.bpjs-kesehatan.go.id/antreanrs-rest'),
        'cons_id'    => env('ANTRIAN_CONS_ID'),
        'secret_key' => env('ANTRIAN_SECRET_KEY'),
        'user_key'   => env('ANTRIAN_USER_KEY'),
        'app_code'   => env('ANTRIAN_APP_CODE', '095'),
    ],
];
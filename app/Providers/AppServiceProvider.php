<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            $protocol = 'http';

            // 1. Deteksi HTTPS
            if (
                (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')) ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                ($_SERVER['SERVER_PORT'] ?? '') == 443
            ) {
                $protocol = 'https';
                URL::forceScheme('https');
            }

            // 2. Deteksi Host & Path
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            
            $path = '';
            if (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $dynamicUrl = "$protocol://$host$path";
            config(['app.url' => $dynamicUrl]);

            // 3. LOGIKA ASSET:
            // Kita cek SCRIPT_NAME. Jika SCRIPT_NAME mengandung '/public/index.php',
            // artinya index.php yang dijalankan memang berada di subfolder public.
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            
            // Cek apakah URL yang sedang diakses saat ini mengandung '/public/'
            // Jika kita mengakses lewat index.php di root, SCRIPT_NAME biasanya adalah '/index.php' atau '/efktp/index.php'
            // Jika kita mengakses lewat folder public, SCRIPT_NAME biasanya adalah '/public/index.php' atau '/efktp/public/index.php'
            
            if (strpos($scriptName, '/public/index.php') !== false) {
                // Jika sudah ada 'public' di script yang dijalankan, maka aset dipanggil tanpa /public lagi
                config(['app.asset_url' => $dynamicUrl]);
            } else {
                // Jika index.php yang dijalankan ada di root, maka aset WAJIB pakai /public
                config(['app.asset_url' => "$dynamicUrl/public"]);
            }
        }
    }
}

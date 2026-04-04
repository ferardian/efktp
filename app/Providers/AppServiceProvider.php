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
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            
            $path = '';
            if (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $dynamicUrl = "$protocol://$host$path";
            config(['app.url' => $dynamicUrl]);

            // 3. LOGIKA ASSET PINTAR:
            // Cek apakah file index.php yang sedang berjalan berada di folder 'public' atau tidak
            $scriptFileName = $_SERVER['SCRIPT_FILENAME'] ?? '';
            
            // Jika jalur file fisik mengandung '/public/index.php', 
            // artinya webserver (seperti di server publik Anda) sudah mengarah ke PUBLIC.
            if (strpos($scriptFileName, '/public/index.php') !== false) {
                // Jangan tambahkan /public lagi
                config(['app.asset_url' => $dynamicUrl]);
            } else {
                // Jika tidak (berarti index.php di root dijalankan), tambahkan /public
                config(['app.asset_url' => "$dynamicUrl/public"]);
            }
        }
    }
}

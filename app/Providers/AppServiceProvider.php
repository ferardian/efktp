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
            // 1. Deteksi Protokol
            $protocol = 'http';
            if (
                (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')) ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                ($_SERVER['SERVER_PORT'] ?? '') == 443
            ) {
                $protocol = 'https';
                URL::forceScheme('https');
            }

            // 2. Deteksi Host & Subfolder
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            
            $path = '';
            // Jika diakses lewat /efktp, kita set path-nya
            if (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $baseUri = "$protocol://$host$path";
            config(['app.url' => $baseUri]);

            // 3. LOGIKA ASSET SEDERHANA & AMPUH:
            if (!empty($path)) {
                // Jika akses via /efktp (Lokal Mac atau Publik), aset selalu butuh /public
                // karena kita meletakkan index.php di root.
                config(['app.asset_url' => "$baseUri/public"]);
            } else {
                // Jika akses langsung (Docker Sail), aset langsung di root
                config(['app.asset_url' => $baseUri]);
            }
        }
    }
}

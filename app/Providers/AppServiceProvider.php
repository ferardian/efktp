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
            // Jika ada kata /efktp di awal URL, masukkan ke jalur utama
            if (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $baseUri = "$protocol://$host$path";
            config(['app.url' => $baseUri]);

            // 3. LOGIKA ASSET EKSTREM (Sangat Spesifik):
            $currentDir = dirname($_SERVER['SCRIPT_FILENAME'] ?? '');
            $isPublicDomain = (strpos($host, 'fktp.dokteraci.my.id') !== false);
            
            // Aturan :
            // Jika ini domain publik, PASTI butuh /public karena kita pakai index.php di root.
            if ($isPublicDomain) {
                config(['app.asset_url' => "$baseUri/public"]);
            }
            // Jika bukan domain publik (misal: localhost atau IP lokal), cek folder fisik
            else {
                if (is_dir($currentDir . '/public')) {
                    // Jika ada folder public, berarti kita di root (Butuh /public)
                    config(['app.asset_url' => "$baseUri/public"]);
                } else {
                    // Jika tidak ada folder public, berarti kita sudah di dalam public (Gak butuh /public)
                    config(['app.asset_url' => $baseUri]);
                }
            }
        }
    }
}

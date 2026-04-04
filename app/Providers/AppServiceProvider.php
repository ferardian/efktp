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

            // 1. Deteksi HTTPS - Sangat penting untuk server publik
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
            if (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $baseUri = "$protocol://$host$path";
            config(['app.url' => $baseUri]);

            // 3. LOGIKA ASSET PALING SEDERHANA:
            // Kita cek apakah ada folder 'public' di sebelah file index.php yang sedang jalan.
            // dirname(__SERVER['SCRIPT_FILENAME']) memberikan lokasi folder index.php saat ini.
            $currentDir = dirname($_SERVER['SCRIPT_FILENAME'] ?? '');
            
            if (is_dir($currentDir . '/public')) {
                // Berarti index.php ada di ROOT (seperti di server publik/lokal Anda)
                config(['app.asset_url' => "$baseUri/public"]);
            } else {
                // Berarti index.php ada di dalam folder PUBLIC (seperti di Docker Sail)
                config(['app.asset_url' => $baseUri]);
            }
        }
    }
}

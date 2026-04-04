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
            // Jika diakses lewat /efktp, pastikan base URL juga mengandung /efktp
            if (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $dynamicUrl = "$protocol://$host$path";
            config(['app.url' => $dynamicUrl]);

            // 3. LOGIKA ASSET SUPER AKURAT:
            // Cek lokasi file fisik index.php yang sedang dieksekusi
            $scriptFileName = realpath($_SERVER['SCRIPT_FILENAME'] ?? '');
            $rootIndexFile = realpath(base_path('index.php'));
            $publicIndexFile = realpath(public_path('index.php'));

            // Jika yang jalan adalah index.php di folder PUBLIC (seperti di Docker Sail)
            if ($scriptFileName === $publicIndexFile) {
                config(['app.asset_url' => $dynamicUrl]);
            } 
            // Jika yang jalan adalah index.php di folder ROOT (seperti di Apache Subfolder)
            elseif ($scriptFileName === $rootIndexFile) {
                config(['app.asset_url' => "$dynamicUrl/public"]);
            }
            // Fallback default
            else {
                config(['app.asset_url' => $dynamicUrl]);
            }
        }
    }
}

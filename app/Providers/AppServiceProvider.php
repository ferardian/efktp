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
            
            // Deteksi Subfolder secara dinamis
            $scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            $path = ($scriptPath === '/' || $scriptPath === '\\' || empty($scriptPath)) ? '' : $scriptPath;

            $baseUri = "$protocol://$host$path";
            config(['app.url' => $baseUri]);

            // Force the root URL for the URL generator
            URL::forceRootUrl($baseUri);

            // 3. LOGIKA ASSET:
            if (!empty($path)) {
                // Asset butuh /public karena index.php di root subfolder
                config(['app.asset_url' => "$baseUri/public"]);
            } else {
                config(['app.asset_url' => $baseUri]);
            }

            // Garansi: Paksa generator URL menggunakan asset_url yang baru diset
            if (config('app.asset_url')) {
                app('url')->setAssetRoot(config('app.asset_url'));
            }
        }
    }
}

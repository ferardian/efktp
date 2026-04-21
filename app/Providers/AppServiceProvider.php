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
        // Pindahkan deteksi ke sini agar dijalankan seawal mungkin
        if (!app()->runningInConsole() && isset($_SERVER['HTTP_HOST'])) {
            $protocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            $path = ($scriptPath === '/' || $scriptPath === '\\' || empty($scriptPath)) ? '' : $scriptPath;
            $baseUri = "$protocol://$host$path";

            config(['app.url' => $baseUri]);
            if ($path !== '') {
                config(['app.asset_url' => $baseUri . '/public']);
            } else {
                config(['app.asset_url' => $baseUri]);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            // Paksa Root URL dan Skema di tingkat Generator
            URL::forceRootUrl(config('app.url'));
            if (config('app.url') && str_starts_with(config('app.url'), 'https')) {
                URL::forceScheme('https');
            }
        }
    }
}

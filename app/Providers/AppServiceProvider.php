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

            // Deteksi HTTPS
            if (
                (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')) ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                ($_SERVER['SERVER_PORT'] ?? '') == 443
            ) {
                $protocol = 'https';
                URL::forceScheme('https');
            }

            // Ambil host
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';

            // Deteksi apakah sedang diakses via subfolder /efktp
            // Kita cek SCRIPT_NAME atau REQUEST_URI
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            
            $path = '';
            
            // Logika 1: Jika diakses via Apache konvensional (localhost/efktp)
            if (strpos($scriptName, '/efktp') === 0) {
                $path = '/efktp';
            } 
            // Logika 2: Jika diakses via Docker tapi user memaksa /efktp di URL
            elseif (strpos($requestUri, '/efktp') === 0) {
                $path = '/efktp';
            }

            $dynamicUrl = "$protocol://$host$path";

            config(['app.url' => $dynamicUrl]);
            
            // Asset handling
            // Jika ada $path (/efktp), kita perlu cek apakah folder public masuk dalam URL
            // Di Docker Sail, biasanya public diserve di root. 
            // Di Apache konvensional, index.php di root memanggil folder public secara internal.
            
            // Kita asumsikan untuk konsistensi: jika ada /efktp, asset juga lewat /efktp/public
            // KECUALI jika index.php sudah ada di root (seperti setup Anda saat ini)
            
            if (!empty($path)) {
                // Jika setup Anda index.php di root, maka aset diakses via /efktp/public/...
                // karena kita mengembalikan folder aset ke dalam public/ tadi.
                config(['app.asset_url' => "$dynamicUrl/public"]);
            } else {
                // Jika di root (seperti di Docker Sail saat ini)
                config(['app.asset_url' => $dynamicUrl]);
            }
        }
    }
}

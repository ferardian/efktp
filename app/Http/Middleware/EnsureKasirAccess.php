<?php

namespace App\Http\Middleware;

use App\Helpers\KasirHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKasirAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!KasirHelper::canAccessKasir()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda tidak memiliki wewenang kasir/keuangan untuk melakukan tindakan ini.'
                ], 403);
            }

            return redirect('/')->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk mengakses halaman kasir.');
        }

        return $next($request);
    }
}


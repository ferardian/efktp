<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KasirHelper
{
    /**
     * Memeriksa apakah user saat ini memiliki izin akses ke modul kasir dan transaksi billing.
     *
     * @param string|null $role
     * @return bool
     */
    public static function canAccessKasir(?string $role = null): bool
    {
        $role = $role ?? session('role');
        if (!$role) {
            return false;
        }

        // Role utama kasir, keuangan, dan administrator faskes selalu diizinkan
        if (in_array($role, ['admin', 'owner', 'kasir', 'keuangan'])) {
            return true;
        }

        // Jika fitur pembatasan menu role aktif, periksa tabel mapping menu_role
        if (config('app.enable_menu_role')) {
            try {
                if (Schema::hasTable('menu_role') && Schema::hasTable('menus')) {
                    $hasMenuAccess = DB::table('menu_role')
                        ->join('menus', 'menu_role.menu_id', '=', 'menus.id')
                        ->where('menu_role.role', $role)
                        ->where(function ($q) {
                            $q->where('menus.url', 'kasir/ralan')
                              ->orWhere('menus.url', '/kasir/ralan')
                              ->orWhere('menus.url', 'kasir/ranap')
                              ->orWhere('menus.url', '/kasir/ranap')
                              ->orWhere('menus.url', 'keuangan/pembayaran-ralan')
                              ->orWhere('menus.url', '/keuangan/pembayaran-ralan');
                        })
                        ->exists();

                    if ($hasMenuAccess) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                // Fallback jika database mapping terkendala
            }

            return false;
        }

        // Jika enable_menu_role non-aktif:
        // Role 'petugas' diizinkan (misal petugas kasir/front office di faskes kecil)
        if ($role === 'petugas') {
            return true;
        }

        // Role klinis & penunjang (dokter, perawat, apoteker, laborat, rekam_medis) ditolak
        return false;
    }
}

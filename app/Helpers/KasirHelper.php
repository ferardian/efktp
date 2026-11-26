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

        // 1. Role utama kasir, keuangan, dan administrator faskes selalu diizinkan
        if (in_array($role, ['admin', 'owner', 'kasir', 'keuangan'])) {
            return true;
        }

        // 2. Jika faskes adalah praktek mandiri dokter / dokter merangkap kasir
        if ($role === 'dokter' && config('app.dokter_bisa_kasir', false)) {
            return true;
        }

        // 3. Jika fitur pembatasan menu role aktif, periksa apakah role user benar-benar
        // memiliki hak akses ke menu kasir DAN parent menu Keuangan (jika ada parent)
        if (config('app.enable_menu_role')) {
            try {
                if (Schema::hasTable('menu_role') && Schema::hasTable('menus')) {
                    $hasMenuAccess = DB::table('menus as sub')
                        ->join('menu_role as mr_sub', 'sub.id', '=', 'mr_sub.menu_id')
                        ->leftJoin('menus as parent', 'sub.parent_id', '=', 'parent.id')
                        ->leftJoin('menu_role as mr_parent', function ($join) use ($role) {
                            $join->on('parent.id', '=', 'mr_parent.menu_id')
                                 ->where('mr_parent.role', '=', $role);
                        })
                        ->where('mr_sub.role', $role)
                        ->where(function ($q) {
                            $q->where('sub.url', 'kasir/ralan')
                              ->orWhere('sub.url', '/kasir/ralan')
                              ->orWhere('sub.url', 'kasir/ranap')
                              ->orWhere('sub.url', '/kasir/ranap');
                        })
                        ->where(function ($q) {
                            // Submenu tidak memiliki parent ATAU parentnya juga diizinkan untuk role ini
                            $q->whereNull('sub.parent_id')
                              ->orWhereNotNull('mr_parent.menu_id');
                        })
                        ->exists();

                    return $hasMenuAccess;
                }
            } catch (\Exception $e) {
                // Fallback jika database mapping terkendala
            }

            return false;
        }

        // 4. Jika enable_menu_role non-aktif, izinkan juga role petugas (sesuai menu statis eFKTP)
        return $role === 'petugas';
    }
}

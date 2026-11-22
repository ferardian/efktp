<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menus') || !Schema::hasTable('menu_role')) {
            return;
        }

        $satusehatParent = DB::table('menus')
            ->where(function($q) {
                $q->where('name', 'SATU SEHAT')->orWhere('name', 'Satu Sehat');
            })
            ->whereNull('parent_id')
            ->first();

        if (!$satusehatParent) {
            return;
        }

        $roles = ['admin', 'petugas', 'owner', 'dokter', 'apoteker'];

        // 1. Medication Request
        $medReq = DB::table('menus')->where('url', 'satusehat/medication-request')->first();
        if (!$medReq) {
            $medReqId = DB::table('menus')->insertGetId([
                'name'       => 'Medication Request',
                'url'        => 'satusehat/medication-request',
                'icon'       => null,
                'parent_id'  => $satusehatParent->id,
                'order_num'  => 10,
                'target'     => '_self',
                'position'   => 'sidebar',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $medReqId = $medReq->id;
        }

        foreach ($roles as $r) {
            DB::table('menu_role')->insertOrIgnore([
                'menu_id' => $medReqId,
                'role'    => $r
            ]);
        }

        // 2. Medication Dispense
        $medDisp = DB::table('menus')->where('url', 'satusehat/medication-dispense')->first();
        if (!$medDisp) {
            $medDispId = DB::table('menus')->insertGetId([
                'name'       => 'Medication Dispense',
                'url'        => 'satusehat/medication-dispense',
                'icon'       => null,
                'parent_id'  => $satusehatParent->id,
                'order_num'  => 11,
                'target'     => '_self',
                'position'   => 'sidebar',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $medDispId = $medDisp->id;
        }

        foreach ($roles as $r) {
            DB::table('menu_role')->insertOrIgnore([
                'menu_id' => $medDispId,
                'role'    => $r
            ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        $menuIds = DB::table('menus')
            ->whereIn('url', ['satusehat/medication-request', 'satusehat/medication-dispense'])
            ->pluck('id');

        if ($menuIds->isNotEmpty()) {
            if (Schema::hasTable('menu_role')) {
                DB::table('menu_role')->whereIn('menu_id', $menuIds)->delete();
            }
            DB::table('menus')->whereIn('id', $menuIds)->delete();
        }
    }
};


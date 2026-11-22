<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create satu_sehat_procedure table if not exists
        if (!Schema::hasTable('satu_sehat_procedure')) {
            Schema::create('satu_sehat_procedure', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->string('kode', 8);
                $table->enum('status', ['Ralan', 'Ranap'])->default('Ralan');
                $table->string('id_procedure', 40)->nullable();
                $table->timestamps();

                $table->primary(['no_rawat', 'kode', 'status']);
            });
        }

        // 2. Register Procedure menu in menus and menu_role
        if (Schema::hasTable('menus') && Schema::hasTable('menu_role')) {
            $satusehatParent = DB::table('menus')
                ->where(function($q) {
                    $q->where('name', 'SATU SEHAT')->orWhere('name', 'Satu Sehat');
                })
                ->whereNull('parent_id')
                ->first();

            if ($satusehatParent) {
                $existing = DB::table('menus')->where('url', 'satusehat/procedure')->first();
                if (!$existing) {
                    $menuId = DB::table('menus')->insertGetId([
                        'name'       => 'Procedure',
                        'url'        => 'satusehat/procedure',
                        'icon'       => null,
                        'parent_id'  => $satusehatParent->id,
                        'order_num'  => 12,
                        'target'     => '_self',
                        'position'   => 'sidebar',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $menuId = $existing->id;
                }

                $roles = ['admin', 'petugas', 'owner', 'dokter'];
                foreach ($roles as $r) {
                    DB::table('menu_role')->insertOrIgnore([
                        'menu_id' => $menuId,
                        'role'    => $r
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            $menuId = DB::table('menus')->where('url', 'satusehat/procedure')->value('id');
            if ($menuId) {
                if (Schema::hasTable('menu_role')) {
                    DB::table('menu_role')->where('menu_id', $menuId)->delete();
                }
                DB::table('menus')->where('id', $menuId)->delete();
            }
        }
    }
};


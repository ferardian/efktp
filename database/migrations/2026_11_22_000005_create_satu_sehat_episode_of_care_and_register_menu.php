<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create satu_sehat_episode_of_care table if not exists
        if (!Schema::hasTable('satu_sehat_episode_of_care')) {
            Schema::create('satu_sehat_episode_of_care', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('no_rawat', 17)->nullable();
                $table->string('no_rkm_medis', 15);
                $table->string('tipe', 20)->default('ANC'); // ANC, TB, DM, HT, UMUM
                $table->string('status', 20)->default('active'); // active, finished, cancelled
                $table->string('id_episode_of_care', 64)->nullable();
                $table->string('id_condition_primary', 64)->nullable();
                $table->date('tgl_mulai');
                $table->date('tgl_selesai')->nullable();
                $table->timestamps();

                $table->index(['no_rkm_medis', 'tipe', 'status'], 'idx_eoc_rm_tipe_status');
                $table->index('no_rawat', 'idx_eoc_no_rawat');
            });
        }

        // 2. Register Episode Perawatan menu in menus and menu_role
        if (Schema::hasTable('menus') && Schema::hasTable('menu_role')) {
            $satusehatParent = DB::table('menus')
                ->where(function($q) {
                    $q->where('name', 'SATU SEHAT')->orWhere('name', 'Satu Sehat');
                })
                ->whereNull('parent_id')
                ->first();

            if ($satusehatParent) {
                $existing = DB::table('menus')->where('url', 'satusehat/episode-of-care')->first();
                if (!$existing) {
                    $menuId = DB::table('menus')->insertGetId([
                        'name'       => 'Episode Perawatan',
                        'url'        => 'satusehat/episode-of-care',
                        'icon'       => null,
                        'parent_id'  => $satusehatParent->id,
                        'order_num'  => 15,
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
            $menuId = DB::table('menus')->where('url', 'satusehat/episode-of-care')->value('id');
            if ($menuId) {
                if (Schema::hasTable('menu_role')) {
                    DB::table('menu_role')->where('menu_id', $menuId)->delete();
                }
                DB::table('menus')->where('id', $menuId)->delete();
            }
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create satu_sehat_mapping_vaksin table if not exists
        if (!Schema::hasTable('satu_sehat_mapping_vaksin')) {
            Schema::create('satu_sehat_mapping_vaksin', function (Blueprint $table) {
                $table->string('kode_brng', 15)->primary();
                $table->string('vaksin_code', 50)->nullable();
                $table->string('vaksin_system', 150)->nullable();
                $table->string('vaksin_display', 200)->nullable();
                $table->string('route_code', 50)->nullable();
                $table->string('route_system', 150)->nullable();
                $table->string('route_display', 100)->nullable();
                $table->string('dose_quantity_code', 20)->nullable();
                $table->string('dose_quantity_system', 150)->nullable();
                $table->string('dose_quantity_unit', 50)->nullable();
                $table->timestamps();
            });
        }

        // 2. Create satu_sehat_immunization table if not exists
        if (!Schema::hasTable('satu_sehat_immunization')) {
            Schema::create('satu_sehat_immunization', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->date('tgl_perawatan');
                $table->time('jam');
                $table->string('kode_brng', 15);
                $table->string('no_batch', 50)->default('');
                $table->string('no_faktur', 50)->default('');
                $table->string('id_immunization', 64)->nullable();
                $table->timestamps();

                $table->primary(['no_rawat', 'tgl_perawatan', 'jam', 'kode_brng', 'no_batch', 'no_faktur'], 'ss_immunization_primary');
            });
        }

        // 3. Register Imunisasi menu in menus and menu_role
        if (Schema::hasTable('menus') && Schema::hasTable('menu_role')) {
            $satusehatParent = DB::table('menus')
                ->where(function($q) {
                    $q->where('name', 'SATU SEHAT')->orWhere('name', 'Satu Sehat');
                })
                ->whereNull('parent_id')
                ->first();

            if ($satusehatParent) {
                $existing = DB::table('menus')->where('url', 'satusehat/immunization')->first();
                if (!$existing) {
                    $menuId = DB::table('menus')->insertGetId([
                        'name'       => 'Imunisasi',
                        'url'        => 'satusehat/immunization',
                        'icon'       => null,
                        'parent_id'  => $satusehatParent->id,
                        'order_num'  => 13,
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
            $menuId = DB::table('menus')->where('url', 'satusehat/immunization')->value('id');
            if ($menuId) {
                if (Schema::hasTable('menu_role')) {
                    DB::table('menu_role')->where('menu_id', $menuId)->delete();
                }
                DB::table('menus')->where('id', $menuId)->delete();
            }
        }
    }
};

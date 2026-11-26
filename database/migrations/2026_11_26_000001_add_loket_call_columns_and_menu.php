<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('tb_list')) {
            Schema::table('tb_list', function (Blueprint $table) {
                if (!Schema::hasColumn('tb_list', 'called_at')) {
                    $table->timestamp('called_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('tb_list', 'call_count')) {
                    $table->unsignedInteger('call_count')->default(0)->after('called_at');
                }
            });
        }

        // Daftarkan menu Pemanggil Loket & Display Loket di bawah parent 'Antrean'
        if (Schema::hasTable('menus')) {
            $parentId = DB::table('menus')
                ->where('name', 'Antrean')
                ->whereNull('parent_id')
                ->value('id');

            if ($parentId) {
                $menusToAdd = [
                    [
                        'name'      => 'Pemanggil Antrean Loket',
                        'url'       => 'antrean/loket/panggil',
                        'icon'      => 'ti ti-speakerphone',
                        'parent_id' => $parentId,
                        'order_num' => 4,
                        'target'    => null,
                        'position'  => 'navbar',
                    ],
                    [
                        'name'      => 'Display Antrean Loket',
                        'url'       => 'antrean/loket',
                        'icon'      => 'ti ti-device-tv',
                        'parent_id' => $parentId,
                        'order_num' => 5,
                        'target'    => '_blank',
                        'position'  => 'navbar',
                    ],
                ];

                $roles = ['admin', 'petugas', 'owner', 'dokter', 'apoteker'];

                foreach ($menusToAdd as $menuData) {
                    $existingId = DB::table('menus')->where('url', $menuData['url'])->value('id');
                    if (!$existingId) {
                        $menuId = DB::table('menus')->insertGetId(array_merge($menuData, [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]));
                    } else {
                        $menuId = $existingId;
                    }

                    if (Schema::hasTable('menu_role')) {
                        foreach ($roles as $role) {
                            DB::table('menu_role')->insertOrIgnore([
                                'menu_id' => $menuId,
                                'role'    => $role,
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            $ids = DB::table('menus')
                ->whereIn('url', ['antrean/loket/panggil', 'antrean/loket'])
                ->pluck('id');
            if ($ids->isNotEmpty()) {
                if (Schema::hasTable('menu_role')) {
                    DB::table('menu_role')->whereIn('menu_id', $ids)->delete();
                }
                DB::table('menus')->whereIn('id', $ids)->delete();
            }
        }

        if (Schema::hasTable('tb_list')) {
            Schema::table('tb_list', function (Blueprint $table) {
                if (Schema::hasColumn('tb_list', 'call_count')) {
                    $table->dropColumn('call_count');
                }
                if (Schema::hasColumn('tb_list', 'called_at')) {
                    $table->dropColumn('called_at');
                }
            });
        }
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create satu_sehat_mapping_alergi table if not exists
        if (!Schema::hasTable('satu_sehat_mapping_alergi')) {
            Schema::create('satu_sehat_mapping_alergi', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('keyword', 100)->unique();
                $table->enum('category', ['food', 'medication', 'environment', 'biologic'])->default('food');
                $table->string('code', 50);
                $table->string('system', 150)->default('http://snomed.info/sct');
                $table->string('display', 200);
                $table->timestamps();
            });

            // Seed default standard SNOMED CT mappings
            $defaults = [
                ['keyword' => 'udang', 'category' => 'food', 'code' => '227037002', 'system' => 'http://snomed.info/sct', 'display' => 'Fish and seafood (substance)'],
                ['keyword' => 'seafood', 'category' => 'food', 'code' => '227037002', 'system' => 'http://snomed.info/sct', 'display' => 'Fish and seafood (substance)'],
                ['keyword' => 'ikan', 'category' => 'food', 'code' => '227037002', 'system' => 'http://snomed.info/sct', 'display' => 'Fish and seafood (substance)'],
                ['keyword' => 'telur', 'category' => 'food', 'code' => '91930004', 'system' => 'http://snomed.info/sct', 'display' => 'Allergy to eggs (finding)'],
                ['keyword' => 'susu', 'category' => 'food', 'code' => '3718001', 'system' => 'http://snomed.info/sct', 'display' => "Cow's milk (substance)"],
                ['keyword' => 'kacang', 'category' => 'food', 'code' => '91935009', 'system' => 'http://snomed.info/sct', 'display' => 'Allergy to peanut (finding)'],
                ['keyword' => 'amoxicillin', 'category' => 'medication', 'code' => '372687004', 'system' => 'http://snomed.info/sct', 'display' => 'Amoxicillin (substance)'],
                ['keyword' => 'amoksisilin', 'category' => 'medication', 'code' => '372687004', 'system' => 'http://snomed.info/sct', 'display' => 'Amoxicillin (substance)'],
                ['keyword' => 'penisilin', 'category' => 'medication', 'code' => '764146007', 'system' => 'http://snomed.info/sct', 'display' => 'Penicillin (substance)'],
                ['keyword' => 'penicillin', 'category' => 'medication', 'code' => '764146007', 'system' => 'http://snomed.info/sct', 'display' => 'Penicillin (substance)'],
                ['keyword' => 'ampicillin', 'category' => 'medication', 'code' => '372688009', 'system' => 'http://snomed.info/sct', 'display' => 'Ampicillin (substance)'],
                ['keyword' => 'paracetamol', 'category' => 'medication', 'code' => '387517004', 'system' => 'http://snomed.info/sct', 'display' => 'Paracetamol (substance)'],
                ['keyword' => 'ibuprofen', 'category' => 'medication', 'code' => '387207008', 'system' => 'http://snomed.info/sct', 'display' => 'Ibuprofen (substance)'],
                ['keyword' => 'asam mefenamat', 'category' => 'medication', 'code' => '387207008', 'system' => 'http://snomed.info/sct', 'display' => 'Mefenamic acid (substance)'],
                ['keyword' => 'sulfa', 'category' => 'medication', 'code' => '91936005', 'system' => 'http://snomed.info/sct', 'display' => 'Allergy to sulfonamide (finding)'],
                ['keyword' => 'debu', 'category' => 'environment', 'code' => '390952000', 'system' => 'http://snomed.info/sct', 'display' => 'House dust mite allergy (finding)'],
                ['keyword' => 'dingin', 'category' => 'environment', 'code' => '282140000', 'system' => 'http://snomed.info/sct', 'display' => 'Cold urticaria (disorder)'],
            ];

            foreach ($defaults as $d) {
                DB::table('satu_sehat_mapping_alergi')->insertOrIgnore(array_merge($d, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // 2. Create satu_sehat_allergy table if not exists
        if (!Schema::hasTable('satu_sehat_allergy')) {
            Schema::create('satu_sehat_allergy', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->date('tgl_perawatan');
                $table->time('jam_rawat');
                $table->string('alergi_text', 255);
                $table->string('id_allergy', 64)->nullable();
                $table->timestamps();

                $table->primary(['no_rawat', 'tgl_perawatan', 'jam_rawat', 'alergi_text'], 'ss_allergy_primary');
            });
        }

        // 3. Register Alergi menu in menus and menu_role
        if (Schema::hasTable('menus') && Schema::hasTable('menu_role')) {
            $satusehatParent = DB::table('menus')
                ->where(function($q) {
                    $q->where('name', 'SATU SEHAT')->orWhere('name', 'Satu Sehat');
                })
                ->whereNull('parent_id')
                ->first();

            if ($satusehatParent) {
                $existing = DB::table('menus')->where('url', 'satusehat/allergy')->first();
                if (!$existing) {
                    $menuId = DB::table('menus')->insertGetId([
                        'name'       => 'Alergi (AllergyIntolerance)',
                        'url'        => 'satusehat/allergy',
                        'icon'       => null,
                        'parent_id'  => $satusehatParent->id,
                        'order_num'  => 14,
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
            $menuId = DB::table('menus')->where('url', 'satusehat/allergy')->value('id');
            if ($menuId) {
                if (Schema::hasTable('menu_role')) {
                    DB::table('menu_role')->where('menu_id', $menuId)->delete();
                }
                DB::table('menus')->where('id', $menuId)->delete();
            }
        }
    }
};

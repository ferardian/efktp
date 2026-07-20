<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Smart Detection: Skip refactoring if this is a legacy client with existing patient region data
        if (Schema::hasTable('pasien')) {
            $hasExistingPatients = DB::table('pasien')
                ->whereNotNull('kd_kel')
                ->whereNotIn('kd_kel', [0, 1])
                ->exists();

            if ($hasExistingPatients) {
                \Illuminate\Support\Facades\Log::info('Legacy client with existing patient region data detected. Skipping region refactoring.');
                return;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Drop Foreign Key constraints referencing region tables
        $fks = DB::select("SELECT TABLE_NAME, CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA='sik' AND REFERENCED_TABLE_NAME IN ('propinsi', 'kabupaten', 'kecamatan', 'kelurahan')");
        foreach ($fks as $fk) {
            $this->dropForeignKeyIfExists($fk->TABLE_NAME, $fk->CONSTRAINT_NAME);
        }

        // 2. Drop UNIQUE constraints on nm_prop, nm_kab, nm_kec, nm_kel if they exist
        $this->dropUniqueIndex('propinsi', 'nm_prop');
        $this->dropUniqueIndex('kabupaten', 'nm_kab');
        $this->dropUniqueIndex('kecamatan', 'nm_kec');
        $this->dropUniqueIndex('kelurahan', 'nm_kel');

        // 3. Modify referencing columns in pasien and utd_pendonor to BIGINT(20) FIRST
        if (Schema::hasTable('pasien')) {
            DB::statement("ALTER TABLE pasien MODIFY COLUMN kd_prop BIGINT(20) NULL, MODIFY COLUMN kd_kab BIGINT(20) NULL, MODIFY COLUMN kd_kec BIGINT(20) NULL, MODIFY COLUMN kd_kel BIGINT(20) NULL");
        }
        if (Schema::hasTable('utd_pendonor')) {
            DB::statement("ALTER TABLE utd_pendonor MODIFY COLUMN kd_prop BIGINT(20) NULL, MODIFY COLUMN kd_kab BIGINT(20) NULL, MODIFY COLUMN kd_kec BIGINT(20) NULL, MODIFY COLUMN kd_kel BIGINT(20) NULL");
        }

        // 4. Modify Primary Column Types and Drop AUTO_INCREMENT in region tables
        DB::statement("ALTER TABLE propinsi MODIFY COLUMN kd_prop BIGINT(20) NOT NULL, MODIFY COLUMN nm_prop VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE kabupaten MODIFY COLUMN kd_kab BIGINT(20) NOT NULL, MODIFY COLUMN nm_kab VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE kecamatan MODIFY COLUMN kd_kec BIGINT(20) NOT NULL, MODIFY COLUMN nm_kec VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE kelurahan MODIFY COLUMN kd_kel BIGINT(20) NOT NULL, MODIFY COLUMN nm_kel VARCHAR(100) NOT NULL");

        // 5. Keep default '-' records and clean up other legacy records
        // Default record for propinsi
        $defaultProp = DB::table('propinsi')->where('kd_prop', 1)->orWhere('nm_prop', '-')->first();
        DB::table('propinsi')->whereNotIn('kd_prop', [1])->where('nm_prop', '!=', '-')->delete();
        if (!$defaultProp) {
            DB::table('propinsi')->insert(['kd_prop' => 1, 'nm_prop' => '-']);
        } else {
            DB::table('propinsi')->where('kd_prop', $defaultProp->kd_prop)->update(['kd_prop' => 1, 'nm_prop' => '-']);
        }

        // Default record for kabupaten
        $defaultKab = DB::table('kabupaten')->where('kd_kab', 1)->orWhere('nm_kab', '-')->first();
        DB::table('kabupaten')->whereNotIn('kd_kab', [1])->where('nm_kab', '!=', '-')->delete();
        if (!$defaultKab) {
            DB::table('kabupaten')->insert(['kd_kab' => 1, 'nm_kab' => '-']);
        } else {
            DB::table('kabupaten')->where('kd_kab', $defaultKab->kd_kab)->update(['kd_kab' => 1, 'nm_kab' => '-']);
        }

        // Default record for kecamatan
        $defaultKec = DB::table('kecamatan')->where('kd_kec', 1)->orWhere('nm_kec', '-')->first();
        DB::table('kecamatan')->whereNotIn('kd_kec', [1])->where('nm_kec', '!=', '-')->delete();
        if (!$defaultKec) {
            DB::table('kecamatan')->insert(['kd_kec' => 1, 'nm_kec' => '-']);
        } else {
            DB::table('kecamatan')->where('kd_kec', $defaultKec->kd_kec)->update(['kd_kec' => 1, 'nm_kec' => '-']);
        }

        // Default record for kelurahan
        $defaultKel = DB::table('kelurahan')->where('kd_kel', 1)->orWhere('nm_kel', '-')->first();
        DB::table('kelurahan')->whereNotIn('kd_kel', [1])->where('nm_kel', '!=', '-')->delete();
        if (!$defaultKel) {
            DB::table('kelurahan')->insert(['kd_kel' => 1, 'nm_kel' => '-']);
        } else {
            DB::table('kelurahan')->where('kd_kel', $defaultKel->kd_kel)->update(['kd_kel' => 1, 'nm_kel' => '-']);
        }

        // 6. Import from sip_pekalongan
        // PROPINSI
        $provs = DB::table('sip_pekalongan.satset_prov')->get();
        foreach ($provs as $p) {
            if ($p->id_prov == 1) continue;
            DB::table('propinsi')->updateOrInsert(
                ['kd_prop' => (int) $p->id_prov],
                ['nm_prop' => $p->nm_prov]
            );
        }

        // KABUPATEN
        $kabs = DB::table('sip_pekalongan.satset_kab')->get();
        foreach ($kabs as $k) {
            if ($k->id_kab == 1) continue;
            DB::table('kabupaten')->updateOrInsert(
                ['kd_kab' => (int) $k->id_kab],
                ['nm_kab' => $k->nm_kab]
            );
        }

        // KECAMATAN
        $kecs = DB::table('sip_pekalongan.satset_kec')->get();
        $kecChunks = $kecs->chunk(1000);
        foreach ($kecChunks as $chunk) {
            $insertData = [];
            foreach ($chunk as $kc) {
                if ($kc->id_kec == 1) continue;
                $insertData[] = [
                    'kd_kec' => (int) $kc->id_kec,
                    'nm_kec' => $kc->nm_kec
                ];
            }
            if (!empty($insertData)) {
                DB::table('kecamatan')->upsert($insertData, ['kd_kec'], ['nm_kec']);
            }
        }

        // KELURAHAN (83,437 rows -> chunked processing)
        DB::table('sip_pekalongan.satset_desa')
            ->orderBy('id_desa')
            ->chunk(3000, function ($desas) {
                $insertData = [];
                foreach ($desas as $d) {
                    if (empty($d->id_desa) || $d->id_desa == 1) continue;
                    $insertData[] = [
                        'kd_kel' => (int) $d->id_desa,
                        'nm_kel' => $d->nm_desa
                    ];
                }
                if (!empty($insertData)) {
                    DB::table('kelurahan')->upsert($insertData, ['kd_kel'], ['nm_kel']);
                }
            });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function dropForeignKeyIfExists(string $table, string $constraint): void
    {
        try {
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
        } catch (\Exception $e) {
            // Ignore if foreign key doesn't exist
        }
    }

    private function dropUniqueIndex(string $table, string $column): void
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Column_name = ?", [$column]);
        foreach ($indexes as $idx) {
            if ($idx->Non_unique == 0 && $idx->Key_name !== 'PRIMARY') {
                try {
                    DB::statement("ALTER TABLE {$table} DROP INDEX {$idx->Key_name}");
                } catch (\Exception $e) {
                    // Ignore if already dropped
                }
            }
        }
    }

    public function down(): void
    {
        // Down migration not required
    }
};

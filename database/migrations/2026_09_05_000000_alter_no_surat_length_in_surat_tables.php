<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('surat_keterangan_sehat')) {
            DB::statement('ALTER TABLE surat_keterangan_sehat MODIFY no_surat VARCHAR(50) NOT NULL');
        }

        if (Schema::hasTable('suratsakit')) {
            DB::statement('ALTER TABLE suratsakit MODIFY no_surat VARCHAR(50) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('surat_keterangan_sehat')) {
            DB::statement('ALTER TABLE surat_keterangan_sehat MODIFY no_surat VARCHAR(17) NOT NULL');
        }

        if (Schema::hasTable('suratsakit')) {
            DB::statement('ALTER TABLE suratsakit MODIFY no_surat VARCHAR(17) NOT NULL');
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE satu_sehat_servicerequest_lab DROP PRIMARY KEY, ADD PRIMARY KEY (noorder, id_template)');
        } catch (\Throwable $e) {
            // Already updated or altered
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE satu_sehat_servicerequest_lab DROP PRIMARY KEY, ADD PRIMARY KEY (noorder, kd_jenis_prw)');
        } catch (\Throwable $e) {
            // ignore
        }
    }
};

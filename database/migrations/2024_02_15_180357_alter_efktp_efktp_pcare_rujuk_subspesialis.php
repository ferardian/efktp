<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('efktp_pcare_rujuk_subspesialis', function (Blueprint $table) {
            $table->string('catatanRujuk')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('efktp_pcare_rujuk_subspesialis', function (Blueprint $table) {
            $table->string('catatanRujuk')->change();
        });
    }
};

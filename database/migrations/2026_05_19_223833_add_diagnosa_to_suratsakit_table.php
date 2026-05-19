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
        Schema::table('suratsakit', function (Blueprint $table) {
            $table->string('diagnosa_surat', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suratsakit', function (Blueprint $table) {
            $table->dropColumn('diagnosa_surat');
        });
    }
};

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
        if (!Schema::hasTable('penilaian_pre_operasi')) {
            Schema::create('penilaian_pre_operasi', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->dateTime('tanggal');
                $table->string('kd_dokter', 20)->index();
                $table->string('ringkasan_klinik', 500)->nullable();
                $table->string('pemeriksaan_fisik', 500)->nullable();
                $table->string('pemeriksaan_diagnostik', 500)->nullable();
                $table->string('diagnosa_pre_operasi', 500)->nullable();
                $table->string('rencana_tindakan_bedah', 500)->nullable();
                $table->string('hal_hal_yang_perludi_persiapkan', 500)->nullable();
                $table->string('terapi_pre_operasi', 500)->nullable();

                $table->primary(['no_rawat', 'tanggal']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_pre_operasi');
    }
};

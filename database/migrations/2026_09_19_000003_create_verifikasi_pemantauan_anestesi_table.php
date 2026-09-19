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
        if (!Schema::hasTable('verifikasi_pemantauan_anestesi')) {
            Schema::create('verifikasi_pemantauan_anestesi', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->date('tanggal_verifikasi');
                $table->string('jam_verifikasi', 10);
                $table->string('kd_dokter', 20);
                $table->string('tanda_tangan', 255)->nullable();

                $table->primary(['no_rawat', 'tanggal_verifikasi']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_pemantauan_anestesi');
    }
};

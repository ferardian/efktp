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
        if (!Schema::hasTable('pemantauan_fisiologi_anestesi')) {
            Schema::create('pemantauan_fisiologi_anestesi', function (Blueprint $table) {
                $table->id();
                $table->string('no_rawat', 17);
                $table->date('tanggal');
                $table->string('waktu_menit', 50);
                $table->string('jam', 10)->default('-');
                $table->string('keluhan', 100)->default('-');
                $table->string('td', 15)->default('-');
                $table->string('nadi', 10)->default('-');
                $table->string('rr', 10)->default('-');
                $table->string('suhu', 10)->default('-');
                $table->string('spo2', 10)->default('-');
                $table->string('keterangan', 150)->default('-');

                $table->index(['no_rawat', 'tanggal']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemantauan_fisiologi_anestesi');
    }
};

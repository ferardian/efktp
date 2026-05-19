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
        Schema::create('data_triase_ugd', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            
            $table->string('no_rawat', 20)->primary();
            $table->dateTime('tgl_triase');
            $table->enum('rujukan', ['Tidak', 'Ya'])->default('Tidak');
            $table->string('rujukan_dari', 100)->nullable();
            $table->text('keluhan_utama');
            $table->text('survey_primer'); // JSON string storing checkbox options
            $table->enum('skala_triase', ['Kategori 1', 'Kategori 2', 'Kategori 3', 'Kategori 4']);
            $table->tinyInteger('skala_nyeri')->nullable();
            $table->enum('nyeri_tipe', ['AKUT', 'KRONIK'])->nullable();
            $table->string('nyeri_lokasi', 100)->nullable();
            $table->string('nyeri_durasi', 100)->nullable();
            $table->enum('resiko_jatuh', ['Resiko Tinggi', 'Resiko Sedang', 'Resiko rendah/ tidak beresiko'])->nullable();
            $table->tinyInteger('resiko_jatuh_skor')->nullable();
            $table->text('luka_perdarahan')->nullable();
            $table->text('body_map_points')->nullable(); // JSON coordinates/description
            $table->time('keputusan_jam')->nullable();
            $table->string('rencana_tindak_lanjut', 100)->nullable();
            $table->string('rujuk_tujuan', 100)->nullable();
            $table->string('nip', 20)->index();
            $table->timestamps();

            // Foreign Key Relations
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('Cascade')->onUpdate('Cascade');
            $table->foreign('nip')->references('nip')->on('petugas')->onDelete('Cascade')->onUpdate('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_triase_ugd');
    }
};

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
        Schema::create('efktp_pemeriksaan_gigi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->id();
            $table->string('no_rawat', 20)->index();
            $table->string('kd_dokter', 20)->index();
            $table->enum('oklusi', ['-', 'Normal Bite', 'Cross Bite', 'Step Bite']);
            $table->enum('palatinus', ['-', 'Tidak Ada', 'Kecil', 'Sedang', 'Besar', 'Multiple']);
            $table->enum('mandibularis', ['-', 'Tidak Ada', 'Sisi Kiri', 'Sisi Kanan', 'Kedua Sisi']);
            $table->enum('palatum', ['-', 'Dalam', 'Sedang', 'Rendah']);
            $table->enum('diastema', ['-', 'Tidak Ada', 'Ada']);
            $table->string('ket_diastema', 50);
            $table->enum('anomali', ['-', 'Tidak Ada', 'Ada']);
            $table->string('ket_anomali', 50);
            $table->string('lainnya', 50);
            $table->string('d', 10);
            $table->string('m', 10);
            $table->string('f', 10);
            $table->timestamps();
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('Cascade')->onUpdate('Cascade');
            $table->foreign('kd_dokter')->references('kd_dokter')->on('dokter')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efktp_pemeriksaan_gigi');
    }
};

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
        if (!Schema::hasTable('penilaian_pre_anestesi')) {
            Schema::create('penilaian_pre_anestesi', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->dateTime('tanggal');
                $table->string('kd_dokter', 20)->index();
                $table->dateTime('tanggal_operasi')->nullable();
                $table->string('diagnosa', 100);
                $table->string('rencana_tindakan', 100);
                $table->string('tb', 5);
                $table->string('bb', 5);
                $table->string('suhu', 5);
                $table->string('td', 8);
                $table->string('nadi', 5);
                $table->string('pernapasan', 5);
                $table->string('io2', 5)->default('-');
                $table->enum('keadaan_umum', ['Baik', 'Sedang', 'Buruk']);
                $table->enum('kesadaran', ['Compos Mentis', 'Apatis', 'Somnolen', 'Sopor', 'Koma']);
                $table->enum('mallampati', ['1', '2', '3', '4', 'Tidak Dikaji']);
                $table->enum('merokok', ['Tidak', 'Ya']);
                $table->string('ket_merokok', 50)->default('-');
                $table->enum('alkohol', ['Tidak', 'Ya']);
                $table->string('ket_alkohol', 50)->default('-');
                $table->enum('alergi', ['Tidak', 'Ya']);
                $table->string('ket_alergi', 50)->default('-');
                $table->string('riwayat_medis_cardiovasculer', 100)->nullable();
                $table->string('riwayat_medis_respiratory', 100)->nullable();
                $table->string('riwayat_medis_endocrine', 100)->nullable();
                $table->string('riwayat_medis_lainnya', 100)->nullable();
                $table->enum('asa', ['1', '2', '3', '4', '5', 'E'])->nullable();
                $table->dateTime('puasa')->nullable();
                $table->enum('rencana_anestesi', ['GA', 'RA Spinal', 'RA Epidural', 'RA Combined', 'Blok Syaraf'])->nullable();
                $table->string('rencana_perawatan', 40)->nullable();
                $table->string('catatan_khusus', 100)->nullable();

                $table->primary(['no_rawat', 'tanggal']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_pre_anestesi');
    }
};

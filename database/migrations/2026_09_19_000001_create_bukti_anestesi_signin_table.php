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
        if (!Schema::hasTable('bukti_anestesi_signin')) {
            Schema::create('bukti_anestesi_signin', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->dateTime('tanggal');
                $table->string('tindakan', 100)->default('-');
                $table->string('diagnosa', 150)->default('-');
                $table->string('kd_dokter_bedah', 20)->default('-');
                $table->string('kd_dokter_anestesi', 20)->default('-');
                $table->enum('identitas_sesuai', ['Ya', 'Tidak'])->default('Ya');
                $table->enum('informed_consent', ['Ya', 'Tidak'])->default('Ya');
                $table->string('rencana_anestesi', 100)->default('-');
                $table->string('obat_anestesi', 100)->default('-');
                $table->string('dosis', 50)->default('-');
                $table->enum('kesiapan_alat_obat', ['Lengkap', 'Tidak Lengkap'])->default('Lengkap');
                $table->string('alergi', 100)->default('-');
                $table->text('catatan')->nullable();
                $table->string('nip_perawat_ok', 20)->default('-');

                $table->primary(['no_rawat', 'tanggal']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_anestesi_signin');
    }
};

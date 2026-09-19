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
        if (!Schema::hasTable('laporan_anestesi')) {
            Schema::create('laporan_anestesi', function (Blueprint $table) {
                $table->string('no_rawat', 17);
                $table->dateTime('mulai');
                $table->dateTime('selesai');
                $table->enum('tempat_pemantauan', ['OK', 'Cathlab', 'ICU/ICCU', 'Radiologi', 'Endoscopy'])->nullable();
                $table->string('tindakan_operasi', 60);
                $table->string('operator1', 20)->index();
                $table->string('asisten_operator', 20)->default('-');
                $table->string('dokter_anestesi', 20)->index();
                $table->string('operator2', 20)->default('-');
                $table->string('onloop', 20)->default('-');
                $table->string('penata_anestesi', 20)->default('-');
                $table->string('diagnosa_preop', 100);
                $table->string('diagnosa_postop', 100);
                $table->enum('status_asa', ['1', '2', '3', '4', '5', 'E'])->nullable();
                $table->string('karena', 40)->nullable();
                $table->string('premedikasi', 500)->nullable();
                $table->string('ttv_premedikasi_td', 8)->default('-');
                $table->string('ttv_premedikasi_rr', 5)->default('-');
                $table->string('ttv_premedikasi_hr', 5)->default('-');
                $table->string('ttv_premedikasi_spo2', 5)->default('-');
                $table->string('ttv_premedikasi_ekg', 5)->default('-');
                $table->string('ttv_premedikasi_suhu', 5)->default('-');
                $table->string('ttv_premedikasi_lain', 30)->default('-');
                $table->string('lama_operasi', 10)->default('-');
                $table->string('lama_anastesi', 10)->default('-');
                $table->string('keadaan_umum_bb', 5)->default('-');
                $table->string('keadaan_umum_tb', 5)->default('-');
                $table->string('keadaan_umum_alergi', 50)->default('-');
                $table->string('keadaan_umum_malampathy', 50)->default('-');
                $table->string('keadaan_umum_e', 1)->default('-');
                $table->string('keadaan_umum_v', 1)->default('-');
                $table->string('keadaan_umum_m', 1)->default('-');
                $table->string('jenis_anestesi_lokasi', 30)->default('-');
                $table->enum('jenis_anestesi_sedasi', ['Ringan', 'Sedang', 'Berat'])->default('Sedang');
                $table->enum('jenis_anestesi_regional', ['Spinal', 'Epidural', 'Combined'])->default('Spinal');
                $table->enum('jenis_anestesi_ga_ett', ['Ya', 'Tidak'])->default('Tidak');
                $table->enum('jenis_anestesi_ga_ntt', ['Ya', 'Tidak'])->default('Tidak');
                $table->enum('jenis_anestesi_ga_ema', ['Ya', 'Tidak'])->default('Tidak');
                $table->enum('jenis_anestesi_ga_bm', ['Ya', 'Tidak'])->default('Tidak');
                $table->string('posisi', 40)->default('Supine');
                $table->string('perdarahan', 40)->default('-');
                $table->string('urine', 40)->default('-');
                $table->string('komplikasi', 40)->default('-');
                $table->string('ekstubasi', 40)->default('-');
                $table->string('jumlah_pack', 10)->default('-');
                $table->string('dipindahkan_ke', 40)->default('Ruang Pemulihan (RR)');
                $table->enum('serah_terima_pasien', ['RR', 'ICU/ICCU', 'NICU/PICU', 'ODC'])->default('RR');
                $table->string('catatan', 100)->default('-');
                $table->string('nip_recovery_room', 20)->default('-');

                $table->primary(['no_rawat', 'mulai']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_anestesi');
    }
};

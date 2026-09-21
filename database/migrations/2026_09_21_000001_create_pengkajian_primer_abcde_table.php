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
        Schema::create('pengkajian_primer_abcde', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';

            $table->string('no_rawat', 20)->primary();
            $table->dateTime('tgl_pengkajian');

            // A - Airway
            $table->enum('airway_kondisi', ['Bebas', 'Obstruksi Parsial', 'Obstruksi Total'])->default('Bebas');
            $table->enum('airway_bicara', ['Bisa Berbicara', 'Tidak Bisa Berbicara'])->default('Bisa Berbicara');
            $table->enum('airway_suara', ['Normal / Bebas', 'Snoring (Mengorok)', 'Gargling (Kumur/Cairan)', 'Stridor', 'Benda Asing'])->default('Normal / Bebas');
            $table->enum('airway_cervical', ['Tidak Ada Cedera', 'Curiga Fraktur / Cedera Leher (Trauma)'])->default('Tidak Ada Cedera');
            $table->text('airway_tindakan')->nullable();

            // B - Breathing
            $table->enum('breathing_gerakan', ['Simetris', 'Asimetris', 'Retraksi Dinding Dada', 'Flail Chest'])->default('Simetris');
            $table->enum('breathing_suara', ['Vesikuler (Normal)', 'Wheezing (Mengi)', 'Ronkhi (Basah)', 'Menurun / Hilang'])->default('Vesikuler (Normal)');
            $table->enum('breathing_embusan', ['Kuat & Terasa', 'Lemah / Dangkal', 'Tidak Terasa'])->default('Kuat & Terasa');
            $table->string('breathing_rr', 10)->default('20');
            $table->string('breathing_spo2', 10)->default('98');
            $table->text('breathing_tindakan')->nullable();

            // C - Circulation
            $table->enum('circulation_nadi', ['Kuat & Teratur', 'Lemah & Cepat', 'Tidak Teraba / Henti Jantung', 'Irreguler / Tidak Teratur'])->default('Kuat & Teratur');
            $table->string('circulation_hr', 10)->default('80');
            $table->string('circulation_td', 15)->default('120/80');
            $table->enum('circulation_crt', ['< 2 Detik (Normal)', '> 2 Detik (Melambat)'])->default('< 2 Detik (Normal)');
            $table->enum('circulation_akral', ['Hangat Kering Merah', 'Dingin Basah Pucat (Syok)', 'Sianosis (Kebiruan)'])->default('Hangat Kering Merah');
            $table->enum('circulation_perdarahan', ['Tidak Ada Perdarahan', 'Perdarahan Ringan / Terkontrol', 'Perdarahan Aktif Masif'])->default('Tidak Ada Perdarahan');
            $table->string('circulation_lokasi_perdarahan', 150)->nullable()->default('-');
            $table->text('circulation_tindakan')->nullable();

            // D - Disability
            $table->enum('disability_avpu', ['Alert (Sadar Penuh)', 'Verbal (Respons Suara)', 'Pain (Respons Nyeri)', 'Unresponsive (Tidak Merespons)'])->default('Alert (Sadar Penuh)');
            $table->string('disability_gcs_e', 5)->default('4');
            $table->string('disability_gcs_v', 5)->default('5');
            $table->string('disability_gcs_m', 5)->default('6');
            $table->string('disability_gcs_total', 5)->default('15');
            $table->enum('disability_pupil', ['Isokor (Normal)', 'Anisokor', 'Pinpoint', 'Midriasis Maksimal'])->default('Isokor (Normal)');
            $table->string('disability_pupil_diameter', 15)->default('3mm / 3mm');
            $table->enum('disability_refleks_cahaya', ['+/+ (Keduanya Positif)', '+/- (Kanan Positif, Kiri Negatif)', '-/+ (Kanan Negatif, Kiri Positif)', '-/- (Keduanya Negatif)'])->default('+/+ (Keduanya Positif)');
            $table->string('disability_gds', 10)->nullable()->default('-');
            $table->text('disability_tindakan')->nullable();

            // E - Exposure
            $table->enum('exposure_cedera', ['Tidak Ada Cedera Luar', 'Terdapat Cedera / Jejas Tersembunyi'])->default('Tidak Ada Cedera Luar');
            $table->text('exposure_deskripsi_cedera')->nullable();
            $table->string('exposure_suhu', 10)->default('36.5');
            $table->enum('exposure_hipotermia', ['Tidak Ada', 'Hipotermia Ringan (32 - 35°C)', 'Hipotermia Sedang (28 - 32°C)', 'Hipotermia Berat (< 28°C)'])->default('Tidak Ada');
            $table->text('exposure_tindakan')->nullable();

            // Kesimpulan & Evaluasi
            $table->enum('status_pasien', ['Stabil', 'Potensial Kritis', 'Kritis / Tidak Stabil', 'Mengancam Nyawa (Life-Threatening)'])->default('Stabil');
            $table->enum('rencana_tindak_lanjut', ['Observasi UGD', 'Pindah Rawat Inap', 'Pindah ICU / HCU', 'Kamar Operasi Cito (OK)', 'Rujuk ke Faskes Lain'])->default('Observasi UGD');
            $table->text('catatan_tambahan')->nullable();
            $table->string('nip', 20)->index();
            $table->timestamps();

            // Foreign Key
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('Cascade')->onUpdate('Cascade');
            $table->foreign('nip')->references('nip')->on('petugas')->onDelete('Cascade')->onUpdate('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengkajian_primer_abcde');
    }
};

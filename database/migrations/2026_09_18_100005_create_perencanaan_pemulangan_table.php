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
        if (!Schema::hasTable('perencanaan_pemulangan')) {
            Schema::create('perencanaan_pemulangan', function (Blueprint $table) {
                $table->string('no_rawat', 17)->primary();
                $table->date('rencana_pulang');
                $table->string('alasan_masuk', 150)->nullable();
                $table->string('diagnosa_medis', 50)->nullable();
                $table->enum('pengaruh_ri_pasien_dan_keluarga', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_pengaruh_ri_pasien_dan_keluarga', 100)->default('-');
                $table->enum('pengaruh_ri_pekerjaan_sekolah', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_pengaruh_ri_pekerjaan_sekolah', 100)->default('-');
                $table->enum('pengaruh_ri_keuangan', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_pengaruh_ri_keuangan', 100)->default('-');
                $table->enum('antisipasi_masalah_saat_pulang', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_antisipasi_masalah_saat_pulang', 100)->default('-');
                $table->string('bantuan_diperlukan_dalam', 100)->default('Minum Obat');
                $table->string('keterangan_bantuan_diperlukan_dalam', 100)->default('-');
                $table->enum('adakah_yang_membantu_keperluan', ['Tidak', 'Ada'])->default('Ada');
                $table->string('keterangan_adakah_yang_membantu_keperluan', 100)->default('-');
                $table->enum('pasien_tinggal_sendiri', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_pasien_tinggal_sendiri', 100)->default('-');
                $table->enum('pasien_menggunakan_peralatan_medis', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_pasien_menggunakan_peralatan_medis', 100)->default('-');
                $table->enum('pasien_memerlukan_alat_bantu', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_pasien_memerlukan_alat_bantu', 100)->default('-');
                $table->enum('memerlukan_perawatan_khusus', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_memerlukan_perawatan_khusus', 100)->default('-');
                $table->enum('bermasalah_memenuhi_kebutuhan', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_bermasalah_memenuhi_kebutuhan', 100)->default('-');
                $table->enum('memiliki_nyeri_kronis', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_memiliki_nyeri_kronis', 100)->default('-');
                $table->enum('memerlukan_edukasi_kesehatan', ['Tidak', 'Ya'])->default('Ya');
                $table->string('keterangan_memerlukan_edukasi_kesehatan', 100)->default('-');
                $table->enum('memerlukan_keterampilkan_khusus', ['Tidak', 'Ya'])->default('Tidak');
                $table->string('keterangan_memerlukan_keterampilkan_khusus', 100)->default('-');
                $table->string('nama_pasien_keluarga', 50);
                $table->string('nip', 20)->index();
            });
        }

        if (!Schema::hasTable('bukti_perencanaan_pemulangan_saksikeluarga')) {
            Schema::create('bukti_perencanaan_pemulangan_saksikeluarga', function (Blueprint $table) {
                $table->string('no_rawat', 17)->primary();
                $table->string('photo', 500)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_perencanaan_pemulangan_saksikeluarga');
        Schema::dropIfExists('perencanaan_pemulangan');
    }
};

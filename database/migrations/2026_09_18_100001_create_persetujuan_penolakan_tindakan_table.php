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
        if (!Schema::hasTable('persetujuan_penolakan_tindakan')) {
            Schema::create('persetujuan_penolakan_tindakan', function (Blueprint $table) {
                $table->string('no_pernyataan', 20)->primary();
                $table->string('no_rawat', 17)->nullable()->index();
                $table->date('tanggal')->nullable();
                $table->string('diagnosa', 200)->nullable();
                $table->enum('diagnosa_konfirmasi', ['true', 'false'])->nullable();
                $table->string('tindakan', 200)->nullable();
                $table->enum('tindakan_konfirmasi', ['true', 'false'])->nullable();
                $table->string('indikasi_tindakan', 200)->nullable();
                $table->enum('indikasi_tindakan_konfirmasi', ['true', 'false'])->nullable();
                $table->string('tata_cara', 400)->nullable();
                $table->enum('tata_cara_konfirmasi', ['true', 'false'])->nullable();
                $table->string('tujuan', 200)->nullable();
                $table->enum('tujuan_konfirmasi', ['true', 'false'])->nullable();
                $table->string('risiko', 200)->nullable();
                $table->enum('risiko_konfirmasi', ['true', 'false'])->nullable();
                $table->string('komplikasi', 200)->nullable();
                $table->enum('komplikasi_konfirmasi', ['true', 'false'])->nullable();
                $table->string('prognosis', 200)->nullable();
                $table->enum('prognosis_konfirmasi', ['true', 'false'])->nullable();
                $table->string('alternatif_dan_risikonya', 200)->nullable();
                $table->enum('alternatif_konfirmasi', ['true', 'false'])->nullable();
                $table->double('biaya')->nullable();
                $table->enum('biaya_konfirmasi', ['true', 'false'])->nullable();
                $table->string('lain_lain', 200)->nullable();
                $table->enum('lain_lain_konfirmasi', ['true', 'false'])->nullable();
                $table->string('kd_dokter', 20)->index();
                $table->string('nip', 20)->index();
                $table->string('penerima_informasi', 50);
                $table->string('alasan_diwakilkan_penerima_informasi', 40);
                $table->enum('jk_penerima_informasi', ['L', 'P']);
                $table->date('tanggal_lahir_penerima_informasi');
                $table->string('umur_penerima_informasi', 30);
                $table->string('alamat_penerima_informasi', 100);
                $table->string('no_hp', 40);
                $table->enum('hubungan_penerima_informasi', ['Diri Sendiri', 'Orang Tua', 'Anak', 'Saudara Kandung', 'Teman', 'Lain-lain']);
                $table->enum('pernyataan', ['Belum Dikonfirmasi', 'Persetujuan', 'Penolakan']);
                $table->string('saksi_keluarga', 50);
            });
        }

        if (!Schema::hasTable('bukti_persetujuan_penolakan_tindakan_penerimainformasi')) {
            Schema::create('bukti_persetujuan_penolakan_tindakan_penerimainformasi', function (Blueprint $table) {
                $table->string('no_pernyataan', 20)->primary();
                $table->string('photo', 500)->nullable();
            });
        }

        if (!Schema::hasTable('bukti_persetujuan_penolakan_tindakan_saksikeluarga')) {
            Schema::create('bukti_persetujuan_penolakan_tindakan_saksikeluarga', function (Blueprint $table) {
                $table->string('no_pernyataan', 20)->primary();
                $table->string('photo', 500)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_persetujuan_penolakan_tindakan_saksikeluarga');
        Schema::dropIfExists('bukti_persetujuan_penolakan_tindakan_penerimainformasi');
        Schema::dropIfExists('persetujuan_penolakan_tindakan');
    }
};

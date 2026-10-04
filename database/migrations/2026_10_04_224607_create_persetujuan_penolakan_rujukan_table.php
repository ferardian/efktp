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
        if (!Schema::hasTable('persetujuan_penolakan_rujukan')) {
            Schema::create('persetujuan_penolakan_rujukan', function (Blueprint $table) {
                $table->string('no_surat', 30)->primary();
                $table->string('no_rawat', 17)->index();
                $table->dateTime('tanggal');
                $table->enum('jenis', ['Persetujuan', 'Penolakan'])->default('Persetujuan');

                // Data Edukasi & Rujukan
                $table->text('diagnosa')->nullable();
                $table->text('alasan_rujuk')->nullable();
                $table->string('faskes_tujuan', 150);
                $table->string('bagian_tujuan', 100)->nullable();
                $table->string('transportasi', 100)->default('Ambulans');
                $table->string('pendamping', 100)->nullable();
                $table->text('tindakan_stabilisasi')->nullable();
                $table->text('risiko_rujuk')->nullable();
                $table->text('risiko_tidak_rujuk')->nullable();
                $table->text('alasan_menolak')->nullable();

                // Identitas Yang Memberi Pernyataan / Penerima Edukasi
                $table->string('nama_pj', 60);
                $table->string('hubungan', 30);
                $table->enum('jk_pj', ['L', 'P'])->default('L');
                $table->string('umur_pj', 20)->nullable();
                $table->string('alamat_pj', 200)->nullable();
                $table->string('no_hp_pj', 25)->nullable();

                // Dokter & Petugas / Saksi
                $table->string('kd_dokter', 20)->nullable()->index();
                $table->string('nip', 20)->nullable()->index();
                $table->string('nama_saksi', 60)->nullable();

                // Path Tanda Tangan Digital
                $table->text('ttd_penerima')->nullable();
                $table->text('ttd_saksi')->nullable();
                $table->text('ttd_dokter')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_penolakan_rujukan');
    }
};

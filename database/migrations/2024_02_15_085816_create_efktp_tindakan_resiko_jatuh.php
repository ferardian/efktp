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
        Schema::create('efktp_tindakan_resiko_jatuh', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->string('no_rawat', 20)->primary();
            $table->string('nip', 20)->index();
            $table->string('berjalan_a', 100);
            $table->string('berjalan_b', 100);
            $table->string('hasil', 100);
            $table->string('ket_hasil', 100);
            $table->string('tindakan', 100);
            $table->datetime('tanggal');
            $table->timestamps();
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('Cascade')->onUpdate('Cascade');
            $table->foreign('nip')->references('nip')->on('petugas')->onDelete('Cascade')->onUpdate('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efktp_tindakan_resiko_jatuh');
    }
};

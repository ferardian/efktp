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
        Schema::create('efktp_pcare_rujuk_subspesialis', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->id();
            $table->string('noKunjungan', 40);
            $table->string('kdPpkAsal', 40)->nullable();
            $table->string('nmPpkAsal', 200)->nullable();
            $table->string('kdKR', 40)->nullable();
            $table->string('nmKR', 40)->nullable();
            $table->string('kdKC', 40)->nullable();
            $table->string('nmKC', 40)->nullable();
            $table->date('tglAkhirRujuk');
            $table->string('jadwal', 200)->nullable();
            $table->string('infoDenda', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efktp_pcare_rujuk_subspesialis');
    }
};

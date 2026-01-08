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
        Schema::create('efktp_alergi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->id();
            $table->string('no_rkm_medis', 15);
            $table->string('alergi', 40);
            $table->timestamps();
            $table->foreign('no_rkm_medis')
                ->references('no_rkm_medis')->on('pasien')
                ->onDelete('Cascade')->onDelete('Cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efktp_alergi');
    }
};

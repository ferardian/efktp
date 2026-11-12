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
        if (!Schema::hasTable('efktp_pasien_prolanis_prb')) {
            Schema::create('efktp_pasien_prolanis_prb', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'latin1';
                $table->collation = 'latin1_swedish_ci';
                $table->id();
                $table->string('no_peserta', 25)->unique();
                $table->string('no_rkm_medis', 15)->nullable()->index();
                $table->string('prolanis', 100)->nullable();
                $table->string('prb', 100)->nullable();
                $table->boolean('is_prolanis')->default(false)->index();
                $table->boolean('is_prb')->default(false)->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efktp_pasien_prolanis_prb');
    }
};


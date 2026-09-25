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
        if (!Schema::hasTable('tb_list')) {
            Schema::create('tb_list', function (Blueprint $table) {
                $table->increments('kd_list');
                $table->date('date_list')->index();
                $table->string('kd_layanan', 5)->index();
                $table->string('antrian', 10);
                $table->time('jam');
                $table->string('keterangan', 50)->default('Print');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_list');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('setting_nomor_surat')) {
            Schema::create('setting_nomor_surat', function (Blueprint $table) {
                $table->id();
                $table->string('format_sakit', 150)->default('{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}');
                $table->string('format_sehat', 150)->default('{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}');
                $table->unsignedInteger('start_num_sakit')->default(0);
                $table->unsignedInteger('start_num_sehat')->default(0);
                $table->enum('reset_periode', ['never', 'yearly', 'monthly'])->default('monthly');
                $table->timestamps();
            });

            // Insert initial default configuration
            DB::table('setting_nomor_surat')->insert([
                'format_sakit'      => '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}',
                'format_sehat'      => '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}',
                'start_num_sakit'   => 0,
                'start_num_sehat'   => 0,
                'reset_periode'     => 'monthly',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_nomor_surat');
    }
};

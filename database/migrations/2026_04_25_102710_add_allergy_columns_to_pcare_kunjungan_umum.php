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
        Schema::table('pcare_kunjungan_umum', function (Blueprint $table) {
            $table->string('KdAlergiMakanan', 5)->after('status')->default('');
            $table->string('NmAlergiMakanan', 50)->after('KdAlergiMakanan')->default('');
            $table->string('KdAlergiUdara', 5)->after('NmAlergiMakanan')->default('');
            $table->string('NmAlergiUdara', 50)->after('KdAlergiUdara')->default('');
            $table->string('KdAlergiObat', 5)->after('NmAlergiUdara')->default('');
            $table->string('NmAlergiObat', 50)->after('KdAlergiObat')->default('');
            $table->string('KdPrognosa', 5)->after('NmAlergiObat')->default('');
            $table->string('NmPrognosa', 100)->after('KdPrognosa')->default('');
            $table->string('terapi_non_obat', 2000)->after('NmPrognosa')->default('');
            $table->string('bmhp', 2000)->after('terapi_non_obat')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pcare_kunjungan_umum', function (Blueprint $table) {
            $table->dropColumn([
                'KdAlergiMakanan', 'NmAlergiMakanan', 'KdAlergiUdara', 'NmAlergiUdara',
                'KdAlergiObat', 'NmAlergiObat', 'KdPrognosa', 'NmPrognosa',
                'terapi_non_obat', 'bmhp'
            ]);
        });
    }
};

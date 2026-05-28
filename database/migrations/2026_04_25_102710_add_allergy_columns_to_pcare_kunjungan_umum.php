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
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'KdAlergiMakanan')) {
                $table->string('KdAlergiMakanan', 5)->after('status')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'NmAlergiMakanan')) {
                $table->string('NmAlergiMakanan', 50)->after('KdAlergiMakanan')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'KdAlergiUdara')) {
                $table->string('KdAlergiUdara', 5)->after('NmAlergiMakanan')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'NmAlergiUdara')) {
                $table->string('NmAlergiUdara', 50)->after('KdAlergiUdara')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'KdAlergiObat')) {
                $table->string('KdAlergiObat', 5)->after('NmAlergiUdara')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'NmAlergiObat')) {
                $table->string('NmAlergiObat', 50)->after('KdAlergiObat')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'KdPrognosa')) {
                $table->string('KdPrognosa', 5)->after('NmAlergiObat')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'NmPrognosa')) {
                $table->string('NmPrognosa', 100)->after('KdPrognosa')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'terapi_non_obat')) {
                $table->string('terapi_non_obat', 2000)->after('NmPrognosa')->default('');
            }
            if (!Schema::hasColumn('pcare_kunjungan_umum', 'bmhp')) {
                $table->string('bmhp', 2000)->after('terapi_non_obat')->default('');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pcare_kunjungan_umum', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach ([
                'KdAlergiMakanan', 'NmAlergiMakanan', 'KdAlergiUdara', 'NmAlergiUdara',
                'KdAlergiObat', 'NmAlergiObat', 'KdPrognosa', 'NmPrognosa',
                'terapi_non_obat', 'bmhp'
            ] as $column) {
                if (Schema::hasColumn('pcare_kunjungan_umum', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

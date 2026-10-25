<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingNomorSurat extends Model
{
    use HasFactory;

    protected $table = 'setting_nomor_surat';
    protected $guarded = ['id'];

    /**
     * Pastikan tabel dan record default tersedia (self-healing)
     *
     * @return \App\Models\SettingNomorSurat|object
     */
    public static function getSetting()
    {
        try {
            if (!Schema::hasTable('setting_nomor_surat')) {
                DB::statement("
                    CREATE TABLE IF NOT EXISTS `setting_nomor_surat` (
                        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `format_sakit` VARCHAR(150) NOT NULL DEFAULT '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}',
                        `format_sehat` VARCHAR(150) NOT NULL DEFAULT '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}',
                        `start_num_sakit` INT UNSIGNED NOT NULL DEFAULT 0,
                        `start_num_sehat` INT UNSIGNED NOT NULL DEFAULT 0,
                        `reset_periode` ENUM('never', 'yearly', 'monthly') NOT NULL DEFAULT 'monthly',
                        `created_at` TIMESTAMP NULL DEFAULT NULL,
                        `updated_at` TIMESTAMP NULL DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ");
            }

            $setting = self::first();
            if (!$setting) {
                $setting = self::create([
                    'format_sakit'    => config('surat.format_sakit', '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}'),
                    'format_sehat'    => config('surat.format_sehat', '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}'),
                    'start_num_sakit' => 0,
                    'start_num_sehat' => 0,
                    'reset_periode'   => config('surat.reset_periode', 'monthly'),
                ]);
            }

            return $setting;
        } catch (\Throwable $e) {
            // Fallback object jika koneksi database terbatas / gagal query tabel
            return (object) [
                'id'              => 1,
                'format_sakit'    => config('surat.format_sakit', '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}'),
                'format_sehat'    => config('surat.format_sehat', '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}'),
                'start_num_sakit' => 0,
                'start_num_sehat' => 0,
                'reset_periode'   => config('surat.reset_periode', 'monthly'),
            ];
        }
    }
}

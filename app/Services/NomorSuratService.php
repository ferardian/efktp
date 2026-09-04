<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    /**
     * Konversi angka bulan (1-12) ke angka Romawi
     *
     * @param int|string $month
     * @return string
     */
    public static function getRomanMonth($month)
    {
        $romans = [
            1  => 'I',
            2  => 'II',
            3  => 'III',
            4  => 'IV',
            5  => 'V',
            6  => 'VI',
            7  => 'VII',
            8  => 'VIII',
            9  => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $romans[(int)$month] ?? 'I';
    }

    /**
     * Generate nomor surat dinamis berdasarkan template format
     *
     * @param string $jenis 'sehat'|'sakit'
     * @param string|null $tanggal
     * @return string
     */
    public static function generate($jenis = 'sehat', $tanggal = null)
    {
        $dateObj = $tanggal ? Carbon::parse($tanggal) : now();
        $year = $dateObj->format('Y');
        $year2 = $dateObj->format('y');
        $month = $dateObj->format('m');
        $monthNum = (int)$dateObj->format('n');
        $day = $dateObj->format('d');
        $romanMonth = self::getRomanMonth($monthNum);

        $defaultSehat = '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}';
        $defaultSakit = '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}';

        $template = ($jenis === 'sehat')
            ? config('surat.format_sehat', $defaultSehat)
            : config('surat.format_sakit', $defaultSakit);

        $resetPeriode = config('surat.reset_periode', 'monthly');

        if ($jenis === 'sehat') {
            $table = 'surat_keterangan_sehat';
            $dateCol = 'tanggalsurat';
        } else {
            $table = 'suratsakit';
            $dateCol = 'tanggalawal';
        }

        // Hitung total surat pada periode yang ditentukan
        $query = DB::table($table);
        if ($resetPeriode === 'yearly') {
            $query->whereYear($dateCol, $year);
        } else {
            $query->whereYear($dateCol, $year)
                  ->whereMonth($dateCol, $month);
        }

        $nextSeq = $query->count() + 1;

        // Loop untuk menjamin nomor yang dihasilkan tidak duplikat
        do {
            $num3 = sprintf('%03d', $nextSeq);
            $num4 = sprintf('%04d', $nextSeq);

            $replacements = [
                '{NUM3}'        => $num3,
                '{NUM4}'        => $num4,
                '{NUM}'         => $nextSeq,
                '{ROMAN_MONTH}' => $romanMonth,
                '{MONTH}'       => $month,
                '{YEAR}'        => $year,
                '{YEAR2}'       => $year2,
                '{DAY}'         => $day,
            ];

            $noSurat = str_replace(array_keys($replacements), array_values($replacements), $template);

            $exists = DB::table($table)->where('no_surat', $noSurat)->exists();
            if ($exists) {
                $nextSeq++;
            }
        } while ($exists);

        return $noSurat;
    }
}

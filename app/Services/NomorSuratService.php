<?php

namespace App\Services;

use App\Models\SettingNomorSurat;
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
     * Konversi angka bulan (1-12) ke nama bulan Indonesia
     *
     * @param int|string $month
     * @return string
     */
    public static function getIndonesianMonth($month)
    {
        $months = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[(int)$month] ?? 'Januari';
    }

    /**
     * Format string template nomor surat dengan token yang tersedia
     *
     * @param string $template
     * @param int $sequence
     * @param Carbon $dateObj
     * @return string
     */
    public static function formatNomor($template, $sequence, Carbon $dateObj)
    {
        $year       = $dateObj->format('Y');
        $year2      = $dateObj->format('y');
        $month      = $dateObj->format('m');
        $monthNum   = (int)$dateObj->format('n');
        $day        = $dateObj->format('d');
        $romanMonth = self::getRomanMonth($monthNum);
        $monthName  = self::getIndonesianMonth($monthNum);

        $replacements = [
            '{NUM}'         => $sequence,
            '{NUM3}'        => sprintf('%03d', $sequence),
            '{NUM4}'        => sprintf('%04d', $sequence),
            '{NUM5}'        => sprintf('%05d', $sequence),
            '{ROMAN_MONTH}' => $romanMonth,
            '{MONTH}'       => $month,
            '{MONTH_NAME}'  => $monthName,
            '{YEAR}'        => $year,
            '{YEAR2}'       => $year2,
            '{DAY}'         => $day,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Generate nomor surat dinamis berdasarkan template format dan setting database
     *
     * @param string $jenis 'sehat'|'sakit'
     * @param string|null $tanggal
     * @return string
     */
    public static function generate($jenis = 'sehat', $tanggal = null)
    {
        $dateObj = $tanggal ? Carbon::parse($tanggal) : now();
        $year    = $dateObj->format('Y');
        $month   = $dateObj->format('m');

        $defaultSehat = '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}';
        $defaultSakit = '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}';

        // Ambil setting dinamis dari database (fallback ke config)
        $setting = SettingNomorSurat::getSetting();

        if ($jenis === 'sehat') {
            $table        = 'surat_keterangan_sehat';
            $dateCol      = 'tanggalsurat';
            $template     = !empty($setting->format_sehat) ? $setting->format_sehat : config('surat.format_sehat', $defaultSehat);
            $startNum     = (int) ($setting->start_num_sehat ?? 0);
        } else {
            $table        = 'suratsakit';
            $dateCol      = 'tanggalawal';
            $template     = !empty($setting->format_sakit) ? $setting->format_sakit : config('surat.format_sakit', $defaultSakit);
            $startNum     = (int) ($setting->start_num_sakit ?? 0);
        }

        $resetPeriode = $setting->reset_periode ?? config('surat.reset_periode', 'monthly');

        // Hitung total surat pada periode yang ditentukan
        $query = DB::table($table);
        if ($resetPeriode === 'yearly') {
            $query->whereYear($dateCol, $year);
        } elseif ($resetPeriode === 'monthly') {
            $query->whereYear($dateCol, $year)
                  ->whereMonth($dateCol, $month);
        }
        // Jika 'never', tidak ada filter tanggal/tahun (nomor urut terus berlanjut)

        $countInDb = $query->count();

        // Urutan berikutnya adalah nilai terbesar antara startNum + 1 dan jumlah data yang ada + 1
        $nextSeq = max($startNum, $countInDb) + 1;

        // Loop untuk menjamin nomor yang dihasilkan tidak duplikat di database
        do {
            $noSurat = self::formatNomor($template, $nextSeq, $dateObj);

            $exists = DB::table($table)->where('no_surat', $noSurat)->exists();
            if ($exists) {
                $nextSeq++;
            }
        } while ($exists);

        return $noSurat;
    }

    /**
     * Simulasikan generate nomor surat untuk preview di form pengaturan
     *
     * @param string $jenis 'sehat'|'sakit'
     * @param string $template
     * @param int $startNum
     * @param string $resetPeriode
     * @param string|null $tanggal
     * @return array
     */
    public static function preview($jenis, $template, $startNum = 0, $resetPeriode = 'monthly', $tanggal = null)
    {
        $dateObj = $tanggal ? Carbon::parse($tanggal) : now();
        $year    = $dateObj->format('Y');
        $month   = $dateObj->format('m');

        $table   = ($jenis === 'sehat') ? 'surat_keterangan_sehat' : 'suratsakit';
        $dateCol = ($jenis === 'sehat') ? 'tanggalsurat' : 'tanggalawal';

        try {
            $query = DB::table($table);
            if ($resetPeriode === 'yearly') {
                $query->whereYear($dateCol, $year);
            } elseif ($resetPeriode === 'monthly') {
                $query->whereYear($dateCol, $year)
                      ->whereMonth($dateCol, $month);
            }
            $countInDb = $query->count();
        } catch (\Throwable $e) {
            $countInDb = 0;
        }

        $nextSeq = max((int)$startNum, $countInDb) + 1;
        $previewNomor = self::formatNomor($template, $nextSeq, $dateObj);

        return [
            'status'         => 'success',
            'preview'        => $previewNomor,
            'next_sequence'  => $nextSeq,
            'count_in_db'    => $countInDb,
            'sample_date'    => $dateObj->format('d-m-Y'),
        ];
    }
}

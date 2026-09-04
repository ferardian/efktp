<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Format Penomoran Surat Dinamis
    |--------------------------------------------------------------------------
    |
    | Token yang tersedia:
    | {NUM3}        : Nomor urut 3 digit (001, 002, ...)
    | {NUM4}        : Nomor urut 4 digit (0001, 0002, ...)
    | {NUM}         : Nomor urut tanpa padding (1, 2, ...)
    | {ROMAN_MONTH} : Bulan angka Romawi (I, II, ..., VIII, ..., XII)
    | {MONTH}       : Bulan angka 2 digit (01 - 12)
    | {YEAR}        : Tahun 4 digit (2026)
    | {YEAR2}       : Tahun 2 digit (26)
    | {DAY}         : Tanggal 2 digit (01 - 31)
    |
    */

    'format_sehat'  => env('FORMAT_SURAT_SEHAT', '{NUM3}/SK.Sehat.KPA/{ROMAN_MONTH}/{YEAR}'),
    'format_sakit'  => env('FORMAT_SURAT_SAKIT', '{NUM3}/SK.Sakit.KPA/{ROMAN_MONTH}/{YEAR}'),

    /*
    |--------------------------------------------------------------------------
    | Periode Reset Nomor Urut
    |--------------------------------------------------------------------------
    |
    | Pilihan:
    | 'monthly' : Nomor urut kembali ke 001 setiap awal bulan (default)
    | 'yearly'  : Nomor urut kembali ke 001 setiap awal tahun
    |
    */
    'reset_periode' => env('RESET_PERIODE_SURAT', 'monthly'),
];

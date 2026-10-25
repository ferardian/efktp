<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Format Penomoran Surat Dinamis
    |--------------------------------------------------------------------------
    |
    | Token yang tersedia:
    | {NUM}         : Nomor urut tanpa padding (1, 2, 1091, ...)
    | {NUM3}        : Nomor urut 3 digit (001, 002, 1091, ...)
    | {NUM4}        : Nomor urut 4 digit (0001, 0002, 1091, ...)
    | {NUM5}        : Nomor urut 5 digit (00001, 00002, 01091, ...)
    | {ROMAN_MONTH} : Bulan angka Romawi (I, II, ..., IX, X, XI, XII)
    | {MONTH}       : Bulan angka 2 digit (01 - 12)
    | {MONTH_NAME}  : Nama bulan Indonesia (Januari, Februari, ..., Oktober, ...)
    | {YEAR}        : Tahun 4 digit (2026)
    | {YEAR2}       : Tahun 2 digit (26)
    | {DAY}         : Tanggal 2 digit (01 - 31)
    |
    | Catatan: Pengaturan ini dapat disesuaikan secara dinamis melalui menu
    | /setting/nomor-surat di aplikasi web.
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
    | 'never'   : Nomor urut terus bertambah (tidak pernah reset)
    | 'yearly'  : Nomor urut kembali ke 1 setiap awal tahun
    | 'monthly' : Nomor urut kembali ke 1 setiap awal bulan (default)
    |
    */
    'reset_periode' => env('RESET_PERIODE_SURAT', 'monthly'),
];

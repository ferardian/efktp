<?php

Route::get('/jenis-perawatan/table', [App\Http\Controllers\JenisPerawatanController::class, 'dataTable']);
Route::get('/master/tarif-ralan', [App\Http\Controllers\JenisPerawatanController::class, 'index']);
Route::post('/master/tarif-ralan', [App\Http\Controllers\JenisPerawatanController::class, 'store']);
Route::put('/master/tarif-ralan/{kd_jenis_prw}', [App\Http\Controllers\JenisPerawatanController::class, 'update']);
Route::delete('/master/tarif-ralan/{kd_jenis_prw}', [App\Http\Controllers\JenisPerawatanController::class, 'destroy']);
Route::get('/master/tarif-ralan/get-next-kode', [App\Http\Controllers\JenisPerawatanController::class, 'getNextKode']);
Route::post('/master/tarif-ralan/bulk-delete', [App\Http\Controllers\JenisPerawatanController::class, 'bulkDestroy']);
Route::post('/master/tarif-ralan/deactivate-all', [App\Http\Controllers\JenisPerawatanController::class, 'deactivateAll']);

// Tarif Rawat Inap
Route::get('/jenis-perawatan-inap/table', [App\Http\Controllers\JenisPerawatanInapController::class, 'dataTable']);
Route::get('/jenis-perawatan-inap/get', [App\Http\Controllers\JenisPerawatanInapController::class, 'get']);
Route::get('/master/tarif-ranap', [App\Http\Controllers\JenisPerawatanInapController::class, 'index']);
Route::post('/master/tarif-ranap', [App\Http\Controllers\JenisPerawatanInapController::class, 'store']);
Route::put('/master/tarif-ranap/{kd_jenis_prw}', [App\Http\Controllers\JenisPerawatanInapController::class, 'update']);
Route::delete('/master/tarif-ranap/{kd_jenis_prw}', [App\Http\Controllers\JenisPerawatanInapController::class, 'destroy']);
Route::get('/master/tarif-ranap/get-next-kode', [App\Http\Controllers\JenisPerawatanInapController::class, 'getNextKode']);
Route::post('/master/tarif-ranap/bulk-delete', [App\Http\Controllers\JenisPerawatanInapController::class, 'bulkDestroy']);
Route::post('/master/tarif-ranap/deactivate-all', [App\Http\Controllers\JenisPerawatanInapController::class, 'deactivateAll']);

// Pengaturan Jam Minimal Kamar Inap (Pengakuan Hari Rawat Inap)
Route::get('/setting/jam-ranap', [App\Http\Controllers\SetJamMinimalController::class, 'index']);
Route::get('/setting/jam-ranap/get', [App\Http\Controllers\SetJamMinimalController::class, 'get']);
Route::post('/setting/jam-ranap', [App\Http\Controllers\SetJamMinimalController::class, 'update']);

// Pengaturan Format Penomoran Surat Dinamis (Surat Sakit & Surat Sehat)
Route::get('/setting/nomor-surat', [App\Http\Controllers\SettingNomorSuratController::class, 'index']);
Route::get('/setting/nomor-surat/get', [App\Http\Controllers\SettingNomorSuratController::class, 'get']);
Route::post('/setting/nomor-surat', [App\Http\Controllers\SettingNomorSuratController::class, 'update']);
Route::post('/setting/nomor-surat/preview', [App\Http\Controllers\SettingNomorSuratController::class, 'preview']);
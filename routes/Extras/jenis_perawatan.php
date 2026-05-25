<?php

Route::get('/jenis-perawatan/table', [App\Http\Controllers\JenisPerawatanController::class, 'dataTable']);
Route::get('/master/tarif-ralan', [App\Http\Controllers\JenisPerawatanController::class, 'index']);
Route::post('/master/tarif-ralan', [App\Http\Controllers\JenisPerawatanController::class, 'store']);
Route::put('/master/tarif-ralan/{kd_jenis_prw}', [App\Http\Controllers\JenisPerawatanController::class, 'update']);
Route::delete('/master/tarif-ralan/{kd_jenis_prw}', [App\Http\Controllers\JenisPerawatanController::class, 'destroy']);
Route::get('/master/tarif-ralan/get-next-kode', [App\Http\Controllers\JenisPerawatanController::class, 'getNextKode']);
Route::post('/master/tarif-ralan/bulk-delete', [App\Http\Controllers\JenisPerawatanController::class, 'bulkDestroy']);
Route::post('/master/tarif-ralan/deactivate-all', [App\Http\Controllers\JenisPerawatanController::class, 'deactivateAll']);
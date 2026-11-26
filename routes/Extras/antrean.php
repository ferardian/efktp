<?php

use App\Http\Controllers\AntreanController;
use App\Http\Controllers\AntreanLoketController;

Route::get('/antrean/poliklinik', [AntreanController::class, 'poliklinik']);
Route::get('/antrean/poliklinik/v2', [AntreanController::class, 'poliklinikV2']);
Route::get('/antrean/farmasi', [AntreanController::class, 'farmasi']);
Route::get('/antrean/loket', [AntreanLoketController::class, 'display'])->name('antrean.loket.display');
Route::get('/antrean/loket/data', [AntreanLoketController::class, 'getData'])->name('antrean.loket.data');
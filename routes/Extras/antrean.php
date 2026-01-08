<?php

use App\Http\Controllers\AntreanController;

Route::get('/antrean/poliklinik', [AntreanController::class, 'poliklinik']);
Route::get('/antrean/poliklinik/v2', [AntreanController::class, 'poliklinikV2']);
Route::get('/antrean/farmasi', [AntreanController::class, 'farmasi']);
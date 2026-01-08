<?php

Route::middleware('auth')->group(function ($route) {
	$route->get('hasil-usg', [\App\Http\Controllers\EfktpHasilUsgController::class, 'first']);
	$route->post('hasil-usg', [\App\Http\Controllers\EfktpHasilUsgController::class, 'create']);
	$route->get('hasil-usg/get', [\App\Http\Controllers\EfktpHasilUsgController::class, 'get']);
	$route->delete('hasil-usg', [\App\Http\Controllers\EfktpHasilUsgController::class, 'delete']);
	$route->get('riwayat-hasil-usg/{no_rkm_medis}', [\App\Http\Controllers\EfktpHasilUsgController::class, 'getHistory']);
});


<?php

Route::middleware('auth')->group(function ($route) {
	$route->get('master/paket-obat', [\App\Http\Controllers\EfktpPaketObatController::class, 'index']);
	$route->get('paket-obat/datatable', [\App\Http\Controllers\EfktpPaketObatController::class, 'datatable']);
	$route->get('paket-obat/datatable', [\App\Http\Controllers\EfktpPaketObatController::class, 'datatable']);
	$route->get('paket-obat/{id}', [\App\Http\Controllers\EfktpPaketObatController::class, 'show']);
	$route->delete('paket-obat/{id}', [\App\Http\Controllers\EfktpPaketObatController::class, 'destroy']);
	$route->post('paket-obat', [\App\Http\Controllers\EfktpPaketObatController::class, 'store']);
	$route->put('paket-obat', [\App\Http\Controllers\EfktpPaketObatController::class, 'update']);
//	$route->post('hasil-usg', [\App\Http\Controllers\EfktpHasilUsgController::class, 'create']);
//	$route->get('hasil-usg/get', [\App\Http\Controllers\EfktpHasilUsgController::class, 'get']);
//	$route->delete('hasil-usg', [\App\Http\Controllers\EfktpHasilUsgController::class, 'delete']);
//	$route->get('riwayat-hasil-usg/{no_rkm_medis}', [\App\Http\Controllers\EfktpHasilUsgController::class, 'getHistory']);
});


<?php

use App\Http\Controllers\Lab\JnsPerawatanLabController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/master/tarif-lab', [JnsPerawatanLabController::class, 'index'])->name('master.tarif-lab.index');
    Route::get('/master/tarif-lab/data', [JnsPerawatanLabController::class, 'dataTable'])->name('master.tarif-lab.data');
    Route::get('/master/tarif-lab/get-next-kode', [JnsPerawatanLabController::class, 'getNextKode'])->name('master.tarif-lab.next-kode');
    Route::get('/master/tarif-lab/detail/{kd_jenis_prw}', [JnsPerawatanLabController::class, 'show'])->name('master.tarif-lab.show');
    Route::post('/master/tarif-lab', [JnsPerawatanLabController::class, 'store'])->name('master.tarif-lab.store');
    Route::put('/master/tarif-lab/{kd_jenis_prw}', [JnsPerawatanLabController::class, 'update'])->name('master.tarif-lab.update');
    Route::delete('/master/tarif-lab/{kd_jenis_prw}', [JnsPerawatanLabController::class, 'destroy'])->name('master.tarif-lab.destroy');
    Route::post('/master/tarif-lab/toggle-status/{kd_jenis_prw}', [JnsPerawatanLabController::class, 'toggleStatus'])->name('master.tarif-lab.toggle-status');

    // Sub-pemeriksaan & Template Laboratorium
    Route::get('/master/tarif-lab/{kd_jenis_prw}/template', [JnsPerawatanLabController::class, 'getTemplates'])->name('master.tarif-lab.template.get');
    Route::post('/master/tarif-lab/template', [JnsPerawatanLabController::class, 'storeTemplate'])->name('master.tarif-lab.template.store');
    Route::delete('/master/tarif-lab/template/{id_template}', [JnsPerawatanLabController::class, 'destroyTemplate'])->name('master.tarif-lab.template.destroy');
    Route::post('/master/tarif-lab/template/copy', [JnsPerawatanLabController::class, 'copyTemplate'])->name('master.tarif-lab.template.copy');
});

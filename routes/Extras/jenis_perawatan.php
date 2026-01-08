<?php

Route::get('/jenis-perawatan/table', [App\Http\Controllers\JenisPerawatanController::class, 'dataTable']);
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImportController;

Route::post('/import-excel', [ImportController::class, 'preview']);
Route::post('/generate-sql', [ImportController::class, 'generateSql']);
Route::post('/save-excel', [ImportController::class, 'saveToDb']);
Route::get('/export-sql', [ImportController::class, 'exportSql']);

// Catch-all SPA al final
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

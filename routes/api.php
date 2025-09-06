<?php
use App\Http\Controllers\ImportController;

Route::post('/import-excel', [ImportController::class, 'preview']);
Route::post('/generate-sql', [ImportController::class, 'generateSql']);
Route::post('/save-excel', [ImportController::class, 'saveToDb']);
Route::get('/export-sql', [ImportController::class, 'exportSql']);

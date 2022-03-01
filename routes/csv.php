<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvController;

Route::get('/', function () {
    return redirect('/csv/import');
})->name('csv.index');

Route::get('/import', [CsvController::class, 'importForm'])->name('csv.import.form');
Route::post('/import', [CsvController::class, 'importAction'])->name('csv.import.action');

Route::get('/export', [CsvController::class, 'exportForm'])->name('csv.export.form');
Route::post('/export', [CsvController::class, 'exportAction'])->name('csv.export.action');

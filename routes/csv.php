<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvController;

Route::get('/', function () {
    return redirect('/csv/import');
})->name('csv.index');

Route::get('/import', [CsvController::class, 'importForm'])->name('csv.import.form');
Route::post('/import', [CsvController::class, 'importAction'])->name('csv.import.action');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvController;

Route::get('/', [CsvController::class, 'index'])->name('csv.index');
Route::post('/upload', [CsvController::class, 'upload'])->name('csv.upload');

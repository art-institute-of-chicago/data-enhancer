<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvController;

Route::get('/', [CsvController::class, 'index'])->name('csv');

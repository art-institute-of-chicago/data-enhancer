<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api/v1');
});

Route::get('{apiVersion}/{resourceName}', [ApiController::class, 'indexResource']);
Route::get('{apiVersion}/{resourceName}/{id}', [ApiController::class, 'showResource']);

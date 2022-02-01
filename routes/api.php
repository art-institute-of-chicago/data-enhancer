<?php

use App\Http\Controllers\ArtworkTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api/v1');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('artwork-types', [ArtworkTypeController::class, 'index']);
    Route::get('artwork-types/{id}', [ArtworkTypeController::class, 'show']);
});

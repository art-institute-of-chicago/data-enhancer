<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\ArtworkTypeController;
use App\Http\Controllers\TermController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api/v1');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('agents', [AgentController::class, 'index']);
    Route::get('agents/{id}', [AgentController::class, 'show']);

    Route::get('artworks', [ArtworkController::class, 'index']);
    Route::get('artworks/{id}', [ArtworkController::class, 'show']);

    Route::get('artwork-types', [ArtworkTypeController::class, 'index']);
    Route::get('artwork-types/{id}', [ArtworkTypeController::class, 'show']);

    Route::get('terms', [TermController::class, 'index']);
    Route::get('terms/{id}', [TermController::class, 'show']);
});

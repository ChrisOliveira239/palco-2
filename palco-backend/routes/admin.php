<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::get('/{event}', [EventController::class, 'show']);
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);
        Route::post('/{event}/restore', [EventController::class, 'restore']);
        Route::get('/{event}/sessions', [EventSessionController::class, 'index']);
        Route::post('/{event}/sessions', [EventSessionController::class, 'store']);
    });

    Route::prefix('event-sessions')->group(function () {
        Route::put('/{eventSession}', [EventSessionController::class, 'update']);
        Route::delete('/{eventSession}', [EventSessionController::class, 'destroy']);
    });
});
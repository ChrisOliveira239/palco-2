<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventFavoriteController;
use App\Http\Controllers\EventSessionController;
use App\Http\Controllers\UserCityController;
use App\Http\Controllers\UserFavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });
    Route::get('/cities', [UserCityController::class, 'index']);
    Route::put('/cities', [UserCityController::class, 'update']);
    Route::get('/favorites', [UserFavoriteController::class, 'index']);
});

Route::get('/cities', [CityController::class, 'index']);

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{event}/sessions', [EventSessionController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);
        Route::post('/{event}/sessions', [EventSessionController::class, 'store']);
        Route::post('/{event}/favorite', [EventFavoriteController::class, 'store']);
        Route::delete('/{event}/favorite', [EventFavoriteController::class, 'destroy']);
    });
});

Route::prefix('event-sessions')->middleware('auth:sanctum')->group(function () {
    Route::put('/{eventSession}', [EventSessionController::class, 'update']);
    Route::delete('/{eventSession}', [EventSessionController::class, 'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

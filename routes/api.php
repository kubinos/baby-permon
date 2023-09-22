<?php

use App\Http\Controllers\Api\ConfigLevelController;
use App\Http\Controllers\Api\SoundController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => '/config/level'], function () {
    Route::get('', [ConfigLevelController::class, 'index']);
    Route::put('', [ConfigLevelController::class, 'update']);
});

Route::apiResource('sounds', SoundController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::apiResource('stations', StationController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::apiResource('tasks', TaskController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

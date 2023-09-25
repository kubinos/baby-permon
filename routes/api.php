<?php

use App\Http\Controllers\Api\ConfigLevelController;
use App\Http\Controllers\Api\GameController;
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
    Route::get('', [ConfigLevelController::class, 'index'])
        ->name('config.level.index');

    Route::put('', [ConfigLevelController::class, 'update'])
        ->name('config.level.update');
});

Route::apiResource('sounds', SoundController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::apiResource('stations', StationController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::apiResource('tasks', TaskController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::group(['prefix' => '/games'], function () {
    Route::post('/games', [GameController::class, 'store'])
        ->name('games.store');

    Route::delete('/games/{chip}', [GameController::class, 'destroy'])
        ->name('games.destroy');
});

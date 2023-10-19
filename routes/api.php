<?php

use App\Http\Controllers\Api\ConfigLevelController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\PLCController;
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

// plc

Route::get('/tryOpenDoor', [PLCController::class, 'tryOpenDoor'])
    ->name('plc.tryOpenDoor');

Route::get('/task', [PLCController::class, 'task'])
    ->name('plc.task');

Route::put('/answer', [PLCController::class, 'answer'])
    ->name('plc.answer');

// app

Route::group(['prefix' => '/config/level'], function () {
    Route::get('', [ConfigLevelController::class, 'index'])
        ->name('config.level.index');

    Route::put('', [ConfigLevelController::class, 'update'])
        ->name('config.level.update');
});

Route::get('/config/enums', [ConfigLevelController::class, 'enums'])
    ->name('config.enums');

Route::apiResource('sounds', SoundController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::apiResource('stations', StationController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::apiResource('tasks', TaskController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy']);

Route::group(['prefix' => '/games'], function () {
    Route::get('', [GameController::class, 'index'])
        ->name('games.index');

    Route::post('', [GameController::class, 'store'])
        ->name('games.store');

    Route::delete('/{chip}', [GameController::class, 'destroy'])
        ->name('games.destroy');
});

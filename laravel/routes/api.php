<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('login')->prefix('admin')->group(function () {
    Route::post('/', [\App\Http\Controllers\PlayerController::class, 'create'])->middleware('login');
    Route::get('{id?}', [\App\Http\Controllers\PlayerController::class, 'read'])->where('id', '\d');
    Route::put('{id}', [\App\Http\Controllers\PlayerController::class, 'update'])->where('id', '\d');
    Route::delete('{id}', [\App\Http\Controllers\PlayerController::class, 'delete'])->where('id', '\d');
});

Route::middleware('login')->prefix('play')->group(function () {
    Route::get('/', [\App\Http\Controllers\GameController::class, 'getAll']);
    Route::post('/create', [\App\Http\Controllers\GameController::class, 'createGame']);
    Route::post('/join/{id?}', [\App\Http\Controllers\GameController::class, 'joinGame']);
    Route::post('/{gameId}', [\App\Http\Controllers\GameController::class, 'play'])->where('gameId', '\d');
});


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

Route::prefix('admin')->group(function () {
    Route::post('/', [\App\Http\Controllers\PlayerController::class, 'create'])->middleware('login');
    Route::get('{id?}', [\App\Http\Controllers\PlayerController::class, 'read'])->where('id', '\d')->middleware('login');
    Route::put('{id}', [\App\Http\Controllers\PlayerController::class, 'update'])->where('id', '\d')->middleware('login');
    Route::delete('{id}', [\App\Http\Controllers\PlayerController::class, 'delete'])->where('id', '\d')->middleware('login');
});

Route::prefix('play')->group(function () {
    Route::get('/', [\App\Http\Controllers\GameController::class, 'getAll'])->middleware('login');
    Route::post('/create', [\App\Http\Controllers\GameController::class, 'createGame'])->middleware('login');
    Route::post('/join/{id?}', [\App\Http\Controllers\GameController::class, 'joinGame'])->middleware('login');
    Route::post('/{gameId}', [\App\Http\Controllers\GameController::class, 'play'])->where('gameId', '\d')->middleware('login');
});

Route::prefix('login')->group(function () {
    Route::get('/', [\App\Http\Controllers\LoginController::class, 'login'])->middleware('login');
});

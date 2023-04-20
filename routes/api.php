<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    
    Route::apiResource('user', \App\Http\Controllers\UserController::class);
    Route::apiResource('project', \App\Http\Controllers\ProjectController::class);
    Route::apiResource('task', \App\Http\Controllers\TaskController::class);
//});
Route::post('/login', [\App\Http\Controllers\ApiAuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\ApiAuthController::class, 'logout']);

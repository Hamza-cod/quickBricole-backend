<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HandymanController;
use App\Http\Controllers\UserController;
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

    Route::middleware(['auth:sanctum,handyman'])->get('/user', function (Request $request) {
        return $request->user();
    });

Route::put('users/{user}',[UserController::class,'update']);
Route::delete('users/{user}',[UserController::class,'destroy']);
Route::get('users/{user}',[UserController::class,'show']);

Route::apiResource('handymans',HandymanController::class);
Route::apiResource('categories',CategoryController::class);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\AdminPasswordController;
use App\Http\Controllers\Api\ViaCepController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::put('/me/password', [PasswordController::class, 'update']);
    Route::post('/users/{user}/reset-password', [AdminPasswordController::class, 'reset']);
    Route::get('/cep/{cep}', [ViaCepController::class, 'show']);
});

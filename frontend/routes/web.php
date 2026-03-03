<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\Front\UploadController;

Route::get('/', fn() => redirect('/users'))->middleware('auth.front');

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.front');

Route::middleware('auth.front')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/cep/{cep}', [UserController::class, 'cep']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
});

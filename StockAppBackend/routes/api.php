<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/users/{id}', [AuthController::class, 'show'])->middleware('auth:sanctum');
Route::get('users', [AuthController::class, 'index'])->middleware('auth:sanctum');

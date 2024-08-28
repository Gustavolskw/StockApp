<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'destroy']);
Route::get('/users/{id}', [AuthController::class, 'show']);
Route::get('users', [AuthController::class, 'index']);
Route::delete('users/delete/{id}', [AuthController::class, 'delete']);
Route::put('users/active/{id}', [AuthController::class, 'active']);
Route::put('users/update/{id}', [AuthController::class, 'update']);

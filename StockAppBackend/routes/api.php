<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'destroy']);
Route::get('/users/{id}', [AuthController::class, 'show']);
Route::get('users', [AuthController::class, 'index']);
Route::delete('users/delete/{id}', [AuthController::class, 'delete']);
Route::put('users/active/{id}', [AuthController::class, 'active']);
Route::put('users/update/{id}', [AuthController::class, 'update']);


Route::prefix('/product')->group(function () {
    Route::get('/type', [ProductTypeController::class, 'index']);
    Route::post('/type', [ProductTypeController::class, 'store']);
    Route::put('/type/{id}', [ProductTypeController::class, 'update']);
    Route::delete('/type/{id}', [ProductTypeController::class, 'delete']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::get('/movies', [MovieController::class, 'index']);
Route::post('/movies', [MovieController::class, 'store']);
Route::post('/movies/{id}', [MovieController::class, 'update']);
Route::delete('/movies/{id}', [MovieController::class, 'destroy']);
Route::post('/movies/import', [MovieController::class, 'import']);

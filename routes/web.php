<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;

// Public pages
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/search', [PageController::class, 'landing'])->name('search');
Route::get('/watch/{id}', [PageController::class, 'watch'])->name('watch');

// Auth pages
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated pages
Route::get('/home', [PageController::class, 'home'])->name('home')->middleware('auth');

// Admin
Route::get('/admin', [PageController::class, 'admin'])->name('admin');
Route::post('/admin/movies', [MovieController::class, 'store'])->name('movies.store');
Route::post('/admin/movies/{id}', [MovieController::class, 'update'])->name('movies.update');
Route::delete('/admin/movies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy');
Route::post('/admin/movies/import', [MovieController::class, 'importExcel'])->name('movies.import');

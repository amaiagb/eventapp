<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;

// Ruta principal - usando HomeController para seguir MVC
Route::get('/', [HomeController::class, 'index'])->name('home');

// Buscador
Route::get('/buscador', [SearchController::class, 'index'])->name('search.index');

Auth::routes();

// Event routes
Route::resource('events', EventController::class);

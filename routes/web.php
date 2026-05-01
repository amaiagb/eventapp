<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;

// Ruta principal - usando HomeController para seguir MVC
Route::get('/', [HomeController::class, 'index'])->name('home');

// Buscador
Route::get('/buscador', [SearchController::class, 'index'])->name('search.index');

// Authentication routes
Auth::routes();

// Direct logout URL (GET)
Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout.direct');

// Profile routes (require authentication)
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/details', [ProfileController::class, 'details'])->name('details');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::delete('/delete', [ProfileController::class, 'delete'])->name('delete');
    Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('change-password');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('password.update');
});

// Report routes (require authentication)
Route::middleware('auth')->prefix('report')->name('report.')->group(function () {
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/store', [ReportController::class, 'store'])->name('store');
});

// Admin routes (require admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    
    // Toggle routes
    Route::patch('/events/{event}/toggle', [AdminController::class, 'toggleEvent'])->name('events.toggle');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    
    // Report management routes
    Route::patch('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
    Route::patch('/reports/{report}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');
});

// Event routes
Route::resource('events', EventController::class);

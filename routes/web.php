<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

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
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
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
    Route::get('/events/{event}', [AdminController::class, 'showEvent'])->name('events.show');
    Route::patch('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
    Route::patch('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
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
Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register')->middleware('auth');
Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel')->middleware('auth');
Route::get('/my-events', [EventController::class, 'myEvents'])->name('events.my-events')->middleware('auth');

// User profile routes
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('auth');
Route::post('/users/{user}/follow', [UserController::class, 'toggleFollow'])->name('users.follow')->middleware('auth');

// Message routes
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store')->middleware('auth');
// Event routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class);
    Route::get('/mis-eventos', [EventController::class, 'myEvents'])->name('my.events');
});

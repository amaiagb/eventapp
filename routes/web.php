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
Route::middleware(['auth', 'active'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::get('/details', [ProfileController::class, 'details'])->name('details');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::delete('/delete', [ProfileController::class, 'delete'])->name('delete');
    Route::get('/change-password', [ProfileController::class, 'showChangePassword'])->name('change-password');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('password.update');
});

// Report routes (require authentication)
Route::middleware(['auth', 'active'])->prefix('report')->name('report.')->group(function () {
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/store', [ReportController::class, 'store'])->name('store');
});

// Admin routes (require admin role)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::get('/events/{event}', [AdminController::class, 'showEvent'])->name('events.show');
    Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::patch('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
    Route::patch('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
    Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.delete');
    Route::get('/events/{event}/info', [AdminController::class, 'getEventInfo'])->name('events.info');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/users/{user}/events-count', [AdminController::class, 'getUserEventsCount'])->name('users.events-count');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    
    // Toggle routes
    Route::patch('/events/{event}/toggle', [AdminController::class, 'toggleEvent'])->name('events.toggle');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    
    // Report management routes
    Route::patch('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
    Route::patch('/reports/{report}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');
    
    // Category management routes
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    
    // Tag management routes
    Route::get('/tags', [AdminController::class, 'tags'])->name('tags');
    Route::get('/tags/create', [AdminController::class, 'createTag'])->name('tags.create');
    Route::post('/tags', [AdminController::class, 'storeTag'])->name('tags.store');
    Route::get('/tags/{tag}/edit', [AdminController::class, 'editTag'])->name('tags.edit');
    Route::put('/tags/{tag}', [AdminController::class, 'updateTag'])->name('tags.update');
    Route::delete('/tags/{tag}', [AdminController::class, 'deleteTag'])->name('tags.delete');
});

// Event routes
Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register')->middleware(['auth', 'active']);
Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel')->middleware(['auth', 'active']);
Route::get('/my-events', [EventController::class, 'myEvents'])->name('events.my-events')->middleware(['auth', 'active']);
Route::get('/events/filtered/{type}', [EventController::class, 'filteredEvents'])->name('events.filtered')->middleware(['auth', 'active']);

// User profile routes
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware(['auth', 'active']);
Route::post('/users/{user}/follow', [UserController::class, 'toggleFollow'])->name('users.follow')->middleware(['auth', 'active']);

// Message routes
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store')->middleware(['auth', 'active']);
// Event routes (require authentication)
Route::middleware(['auth', 'active'])->group(function () {
    Route::resource('events', EventController::class);
    Route::get('/mis-eventos', [EventController::class, 'myEvents'])->name('my.events');
});

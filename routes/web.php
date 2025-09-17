<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MachineryController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Machinery browsing and search
Route::get('/browse', [HomeController::class, 'browse'])->name('machinery.browse');
Route::get('/search', [MachineryController::class, 'search'])->name('machinery.search');
Route::get('/compare', [MachineryController::class, 'compare'])->name('machinery.compare');

// Machinery details
Route::get('/machinery/{machinery}', [MachineryController::class, 'show'])->name('machinery.show');
Route::get('/category/{category}', [MachineryController::class, 'category'])->name('machinery.category');

// AJAX routes
Route::post('/machinery/{machinery}/check-availability', [MachineryController::class, 'checkAvailability'])
    ->name('machinery.check-availability');

// Authentication routes (to be implemented)
// Route::middleware('auth')->group(function () {
//     // User dashboard, cart, orders, etc.
// });

// Admin routes (to be implemented)
// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
//     // Admin panel routes
// });

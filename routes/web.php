<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MachineryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\SellerDashboardController;

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

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{machinery}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
});

// Customer dashboard routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('/rentals', [CustomerDashboardController::class, 'rentals'])->name('rentals');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/reviews', [CustomerDashboardController::class, 'reviews'])->name('reviews');
});

// Seller dashboard routes
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/machinery', [SellerDashboardController::class, 'machinery'])->name('machinery');
    Route::get('/machinery/create', [SellerDashboardController::class, 'createMachinery'])->name('machinery.create');
    Route::post('/machinery', [SellerDashboardController::class, 'storeMachinery'])->name('machinery.store');
    Route::get('/machinery/{machinery}/edit', [SellerDashboardController::class, 'editMachinery'])->name('machinery.edit');
    Route::patch('/machinery/{machinery}', [SellerDashboardController::class, 'updateMachinery'])->name('machinery.update');
    Route::get('/sales', [SellerDashboardController::class, 'sales'])->name('sales');
    Route::get('/rentals', [SellerDashboardController::class, 'rentals'])->name('rentals');
    Route::get('/profile', [SellerDashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [SellerDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Admin routes (to be implemented)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Fallback route for 404 errors
Route::fallback(function () {
    return redirect()->route('home');
});

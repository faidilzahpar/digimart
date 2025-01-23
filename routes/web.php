<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Models\Product;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/', [ProductController::class, 'index'])->name('index');

Route::get('/kategori/{kategori}', [ProductController::class, 'kategori'])->name('kategori');

Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::get('/history', [ProductController::class, 'purchaseHistory'])->name('history');

Route::get('products/{id}', [ProductController::class, 'detail'])->name('detail');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminProductController::class, 'index'])->name('admin.index');
    Route::resource('products', AdminProductController::class);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('profile.changePassword');
    Route::post('/profile/delete-image', [AuthController::class, 'deleteProfileImage'])->name('profile.deleteImage');
    Route::post('/add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('addToCart');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminProductController::class, 'index'])->name('admin.index');
    Route::resource('products', AdminProductController::class);
});

Route::get('/add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('addToCart');
Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [ProductController::class, 'processCheckout'])->name('processCheckout');
Route::delete('/cart/remove/{id}', [ProductController::class, 'removeFromCart'])->name('removeFromCart');

Route::post('/buy/{id}', [ProductController::class, 'processPurchase'])->name('processPurchase');
Route::get('/download/{id}/{filename}', [ProductController::class, 'downloadFiles'])->name('downloadFiles');

// Route untuk forgot password
Route::get('password/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLink'])->name('password.email');

// Route untuk reset password
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');


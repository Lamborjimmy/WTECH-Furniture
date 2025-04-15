<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{pivotId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{pivotId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
});

require __DIR__.'/auth.php';

Route::get('/products', [ProductController::class, 'index']) ->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/', fn() => view('home'))->name('home');
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('home.index');
Route::get('/products', [ProductController::class, 'index']) ->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{pivotId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{pivotId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::get('/cart/payment', [CartController::class, 'payment'])->name('cart.payment');
Route::post('/cart/payment', [CartController::class, 'storePayment'])->name('cart.payment.store');
Route::get('/cart/delivery', [CartController::class, 'delivery'])->name('cart.delivery');
Route::post('/cart/delivery/store', [CartController::class, 'storeDelivery'])->name('cart.delivery.store');
Route::get('/order-complete/{id}', [CartController::class, 'orderComplete'])->name('order.complete');

require __DIR__.'/auth.php';

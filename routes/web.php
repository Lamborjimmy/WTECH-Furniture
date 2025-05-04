<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('home.index');
Route::get('/products', [ProductController::class, 'index']) ->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::get('/order/payment', [OrderController::class, 'payment'])->name('order.payment');
Route::post('/order/payment', [OrderController::class, 'storePayment'])->name('order.payment.store');
Route::get('/order/delivery', [OrderController::class, 'delivery'])->name('order.delivery');
Route::post('/order/delivery', [OrderController::class, 'storeDelivery'])->name('order.delivery.store');
Route::get('/order-complete/{id}', [OrderController::class, 'orderComplete'])->name('order.complete');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__.'/auth.php';

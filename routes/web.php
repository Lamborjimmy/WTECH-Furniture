<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomerMiddleware;

Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/', [ProductController::class, 'home'])->name('home.index');
    Route::get('/products', [ProductController::class, 'index']) ->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
});

Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
});

Route::middleware([CustomerMiddleware::class])->group(function () {
    Route::get('/order/payment', [OrderController::class, 'payment'])->name('order.payment');
    Route::post('/order/payment', [OrderController::class, 'storePayment'])->name('order.payment.store');
    Route::get('/order/delivery', [OrderController::class, 'delivery'])->name('order.delivery');
    Route::post('/order/delivery', [OrderController::class, 'storeDelivery'])->name('order.delivery.store');
    Route::get('/order-complete/{id}', [OrderController::class, 'orderComplete'])->name('order.complete');
});

Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/admin', [AdminProductController::class, 'index'])->name('admin.index');
    Route::get('/admin/add', [AdminProductController::class, 'create'])->name('admin.create');
    Route::post('/admin/add', [AdminProductController::class, 'store'])->name('admin.store');
    Route::get('/admin/edit/{id}', [AdminProductController::class, 'createEdit'])->name('admin.createEdit');
    Route::put('/admin/edit/{id}', [AdminProductController::class, 'storeEdit'])->name('admin.storeEdit');
    Route::delete('/admin/delete/{id}', [AdminProductController::class, 'destroy'])->name('admin.destroy');
});

require __DIR__.'/auth.php';

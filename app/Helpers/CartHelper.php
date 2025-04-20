<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Product;

class CartHelper
{
    public static function getCartItems($cart = null): Collection
    {
        if (Auth::check()) {
            return $cart && $cart->products
                ? $cart->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'price' => $product->price,
                        'quantity' => $product->pivot->quantity,
                        'in_stock' => $product->in_stock,
                        'image' => $product->mainImage?->path,
                    ];
                })
                : collect();
        } else {
            $sessionCart = session()->get('cart', []);
            return collect($sessionCart)->map(function ($item, $productId) {
                $product = Product::find($productId);

                return [
                    'id' => $productId,
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'in_stock' => $product?->in_stock ?? 0,
                    'image' => $item['image'] ?? null,
                ];
            });
        }
    }
}

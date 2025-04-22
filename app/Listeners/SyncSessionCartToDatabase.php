<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\Cart;
use App\Models\Product;

class SyncSessionCartToDatabase
{
    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        $user = $event->user;
        $sessionCart = session('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cart->products()->detach();

        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cart->products()->syncWithoutDetaching([
                    $productId => ['quantity' => $item['quantity']]
                ]);
            }
        }

        session()->forget('cart');
    }
}

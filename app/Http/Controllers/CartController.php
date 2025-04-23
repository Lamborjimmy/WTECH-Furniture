<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\DeliveryOption;
use App\Models\PaymentOption;
use App\Models\Product;
use App\Models\DeliveryDetail;
use App\Models\Order;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $total = 0;
        $discount = 0;

        if (Auth::check()) {
            $cart = Cart::with('products.mainImage')->firstOrCreate(['user_id' => Auth::id()]);

            $total = $cart->products->sum(function ($product) {
                return $product->price * $product->pivot->quantity;
            });
        } else {
            $cart = session()->get('cart', []);

            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->where('amount', '>', 0)->first();
            
            if ($coupon) {
                $discount = ($coupon->discount / 100) * $total;
            } else {
                session()->forget('coupon_code');
            }
        }

        $cartItems = CartHelper::getCartItems($cart);

        return view('cart', compact('cartItems', 'total', 'discount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $currentQuantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity ?? 0;
            $newQuantity = $currentQuantity + $request->quantity;

            if ($product->in_stock < $newQuantity) {
                return redirect()->back()->with('error', 'Nedostatok zásob pre ' . $product->title);
            }

            $cart->products()->syncWithoutDetaching([$product->id => ['quantity' => $newQuantity]]);
        } else {
            $cart = session()->get('cart', []);

            $currentQuantity = $cart[$request->product_id]['quantity'] ?? 0;
            $newQuantity = $currentQuantity + $request->quantity;

            if ($product->in_stock < $newQuantity) {
                return redirect()->back()->with('error', 'Nedostatok zásob pre ' . $product->title);
            }

            $cart[$request->product_id] = [
                'title' => $product->title,
                'price' => $product->price,
                'quantity' => $newQuantity,
                'image' => $product->mainImage->path,
            ];

            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Produkt pridaný do košíka!');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);
        
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->firstOrFail();
            $product = $cart->products()->where('product_id', $productId)->first();
            
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Produkt nenájdený v košíku.');
            }
            
            $newQuantity = $request->action === 'increase'
                ? $product->pivot->quantity + 1
                : $product->pivot->quantity - 1;
            
            if ($newQuantity < 1) {
                $cart->products()->detach($productId);
                return redirect()->route('cart.index')->with('success', 'Produkt odstránený z košíka.');
            }
            
            if ($product->in_stock < $newQuantity) {
                return redirect()->route('cart.index')->with('error', 'Nedostatok zásob pre ' . $product->title);
            }
    
            $cart->products()->updateExistingPivot($productId, ['quantity' => $newQuantity]);
        } else {
            $cart = session()->get('cart', []);
            $product = Product::findOrFail($productId);

            if (!isset($cart[$productId])) {
                return redirect()->route('cart.index')->with('error', 'Produkt nenájdený v košíku.');
            }

            $currentQuantity = $cart[$productId]['quantity'] ?? 0;
            $newQuantity = $request->action === 'increase'
                ? $currentQuantity + 1
                : $currentQuantity - 1;

            if ($newQuantity < 1) {
                unset($cart[$productId]);
                session(['cart' => $cart]);
                return redirect()->route('cart.index')->with('success', 'Produkt odstránený z košíka.');
            }

            if ($product->in_stock < $newQuantity) {
                return redirect()->route('cart.index')->with('error', 'Nedostatok zásob pre ' . $product->title);
            }

            $cart[$productId]['quantity'] = $newQuantity;
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Množstvo aktualizované.');
    }

    public function remove($productId)
    {    
        if (Auth::check())  {
            $cart = Cart::where('user_id', Auth::id())->firstOrFail();
            $product = $cart->products()->where('product_id', $productId)->first();
    
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Produkt nenájdený v košíku.');
            }
    
            $cart->products()->detach($productId);
        }
        else {
            $product = Product::findOrFail($productId);
            $cart = session()->get('cart', []);

            if (!isset($cart[$productId])) {
                return redirect()->route('cart.index')->with('error', 'Produkt nenájdený v košíku.');
            }

            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Produkt odstránený z košíka.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->where('amount', '>', 0)->first();

        if (!$coupon) {
            return redirect()->route('cart.index')->with('error', 'Neplatný alebo vyčerpaný zľavový kód.');
        }

        if (session('coupon_code')) {
            return redirect()->route('cart.index')->with('error', 'Zľavový kód už bol raz zadaný.');
        }

        session(['coupon_code' => $request->coupon_code]);

        return redirect()->route('cart.index')->with('success', 'Zľavový kód použitý.');
    }

    public function removeCoupon()
    {
        if (session('coupon_code')) {
            session()->forget('coupon_code');
            return redirect()->route('cart.index')->with('success', 'Zľavový kód odstránený.');
        }
        
        return redirect()->route('cart.index')->with('error', 'Žiadny zľavový kód nebol zadaný.');
    }
}

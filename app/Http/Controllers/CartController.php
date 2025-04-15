<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\DeliveryOption;
use App\Models\PaymentOption;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::check() ? Cart::with('products.mainImage')->firstOrCreate(['user_id' => Auth::id()]) : null;
        $total = $cart ? $cart->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        }) : 0;
        $discount = 0;
        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->where('amount', '>', 0)->first();
            if ($coupon) {
                $discount = ($coupon->discount / 100) * $total;
            } else {
                session()->forget('coupon_code');
            }
        }

        return view('cart', compact('cart', 'total', 'discount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        if ($product->in_stock < $request->quantity) {
            return redirect()->back()->with('error', 'Nedostatok zásob pre ' . $product->title);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $currentQuantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity ?? 0;
        $newQuantity = $currentQuantity + $request->quantity;

        if ($product->in_stock < $newQuantity) {
            return redirect()->back()->with('error', 'Nedostatok zásob pre ' . $product->title);
        }

        $cart->products()->syncWithoutDetaching([$product->id => ['quantity' => $newQuantity]]);

        return redirect()->route('cart.index')->with('success', 'Produkt pridaný do košíka!');
    }

    public function update(Request $request, $pivotId)
    {
        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);

        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        $pivot = $cart->products()->newPivotStatement()->where('id', $pivotId)->first();

        if (!$pivot) {
            return redirect()->route('cart.index')->with('error', 'Produkt nenájdený v košíku.');
        }

        $product = Product::findOrFail($pivot->product_id);
        $newQuantity = $request->action === 'increase' ? $pivot->quantity + 1 : $pivot->quantity - 1;

        if ($newQuantity < 1) {
            $cart->products()->detach($product->id);
            return redirect()->route('cart.index')->with('success', 'Produkt odstránený z košíka.');
        }

        if ($product->in_stock < $newQuantity) {
            return redirect()->route('cart.index')->with('error', 'Nedostatok zásob pre ' . $product->title);
        }

        $cart->products()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);

        return redirect()->route('cart.index')->with('success', 'Množstvo aktualizované.');
    }

    public function remove($pivotId)
    {
        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        $pivot = $cart->products()->newPivotStatement()->where('id', $pivotId)->first();

        if (!$pivot) {
            return redirect()->route('cart.index')->with('error', 'Produkt nenájdený v košíku.');
        }

        $cart->products()->detach($pivot->product_id);

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

        session(['coupon_code' => $request->coupon_code]);

        return redirect()->route('cart.index')->with('success', 'Zľavový kód použitý.');
    }

    public function payment()
    {
        $cart = Auth::check() ? Cart::with('products.mainImage')->firstOrCreate(['user_id' => Auth::id()]) : null;

        if (!$cart || $cart->products->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
        }

        $total = $cart->products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });
        $discount = 0;
        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->where('amount', '>', 0)->first();
            if ($coupon) {
                $discount = ($coupon->discount / 100) * $total;
            } else {
                session()->forget('coupon_code');
            }
        }

        $paymentOptions = PaymentOption::all();
        $deliveryOptions = DeliveryOption::all();

        return view('cart-payment', compact('cart', 'total', 'discount', 'paymentOptions', 'deliveryOptions'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'payment_option_id' => 'required|exists:payment_options,id',
            'delivery_option_id' => 'required|exists:delivery_options,id',
            'cart_total' => 'required|numeric|min:0',
        ]);

        $cart = Cart::where('user_id', Auth::id())->firstOrFail();

        if ($cart->products->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
        }
        $deliveryOption = DeliveryOption::findOrFail($request->delivery_option_id);
        $finalTotal = $request->cart_total + $deliveryOption->price;
        session([
            'cart.payment_option_id' => $request->payment_option_id,
            'cart.delivery_option_id' => $request->delivery_option_id,
            'cart.total' => $finalTotal,
        ]);
        // Redirect to delivery details step (placeholder)
        return redirect()->route('cart.index')->with('success', 'Možnosti dopravy a platby uložené.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\DeliveryOption;
use App\Models\PaymentOption;
use App\Models\Product;
use App\Models\DeliveryDetail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        return redirect()->route('cart.delivery')->with('success', 'Možnosti dopravy a platby uložené.');
    }
    public function delivery()
    {
        $cart = Auth::check() ? Cart::with('products.mainImage')->firstOrCreate(['user_id' => Auth::id()]) : null;

        if (!$cart || $cart->products->isEmpty()) {
            Log::warning('Cart is empty or null', ['user_id' => Auth::id()]);
            return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
        }

        if (!session('cart.payment_option_id') || !session('cart.delivery_option_id')) {
            Log::warning('Missing payment or delivery options', session()->all());
            return redirect()->route('cart.payment')->with('error', 'Vyberte spôsob dopravy a platby.');
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
        return view('cart-delivery', compact('cart', 'total', 'discount'));
    }
    public function storeDelivery(Request $request)
    {
        $request->validate([
            'fullname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}]+\s[\p{L}]+$/u', // At least two words (first and last name)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+421\s?\d{3}\s?\d{3}\s?\d{3}$/', // Slovak format, e.g., +421 123 456 789
            ],
            'street_and_number' => [
                'required',
                'string',
                'max:255',
                'regex:/[\p{L}].*\d/', // Contains letters and at least one number
            ],
            'city' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\s]+$/u', // Only letters and spaces
            ],
            'post_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{5}$/', // Slovak 5-digit postal code
            ],
            'country' => [
                'required',
                'string',
                'max:100',
                'in:Slovenská Republika', // Must be Slovenská Republika
            ],
        ], [
            'fullname.regex' => 'Meno a priezvisko musí obsahovať aspoň dve slová (meno a priezvisko).',
            'phone_number.regex' => 'Telefónne číslo musí byť v tvare +421 123 456 789.',
            'street_and_number.regex' => 'Ulica a číslo musí obsahovať písmená a aspoň jedno číslo.',
            'city.regex' => 'Mesto môže obsahovať iba písmená a medzery.',
            'post_code.regex' => 'PSČ musí byť päťciferné číslo (napr. 84216).',
            'country.in' => 'Krajina musí byť Slovenská Republika.',
        ]);

        $cart = Cart::where('user_id', Auth::id())->firstOrFail();

        if ($cart->products->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
        }

        if (!session('cart.payment_option_id') || !session('cart.delivery_option_id') || !session('cart.total')) {
            return redirect()->route('cart.payment')->with('error', 'Vyberte spôsob dopravy a platby.');
        }

        try {
            // 1. Insert into delivery_details
            $deliveryDetail = DeliveryDetail::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'street_and_number' => $request->street_and_number,
                'city' => $request->city,
                'post_code' => $request->post_code,
                'country' => $request->country,
            ]);

            // 2. Insert into orders
            $coupon = session('coupon_code') ? Coupon::where('code', session('coupon_code'))->first() : null;

            $order = Order::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'processed_date' => now(),
                'delivery_detail_id' => $deliveryDetail->id,
                'payment_option_id' => session('cart.payment_option_id'),
                'delivery_option_id' => session('cart.delivery_option_id'),
                'coupon_id' => $coupon ? $coupon->id : null,
            ]);

            // 3. Insert into orders_products pivot table
            foreach ($cart->products as $product) {
                $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
            }

            // Decrement coupon amount if used
            if ($coupon && $coupon->amount > 0) {
                $coupon->decrement('amount');
            }

            // Clear cart and session
            $cart->products()->detach();
            session()->forget(['cart.payment_option_id', 'cart.delivery_option_id', 'cart.delivery_price', 'coupon_code']);
            // Keep total for order-complete
            session(['order.total' => session('cart.total')]);
            session()->forget('cart.total');

            return redirect()->route('order.complete', $order->id)->with('success', 'Objednávka bola úspešne vytvorená.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chyba pri vytváraní objednávky. Skúste to znova.');
        }
    }

    public function orderComplete($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Neoprávnený prístup.');
        }

        $total = session('order.total');

        return view('order-complete', compact('order', 'total'));
    }
}
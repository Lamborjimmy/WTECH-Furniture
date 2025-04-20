<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentOption;
use App\Models\DeliveryOption;
use App\Models\DeliveryDetail;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function payment()
    {
        if (Auth::check()) {
            $cart = Cart::with('products.mainImage')->firstOrCreate(['user_id' => Auth::id()]);
            
            if (!$cart || $cart->products->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
            }
    
            $total = $cart->products->sum(fn($product) => $product->price * $product->pivot->quantity);
        } else {
            $cart = session()->get('cart', []);
    
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
            }

            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        }
    
        $discount = 0;
        
        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->where('amount', '>', 0)->first();
            if ($coupon) {
                $discount = ($coupon->discount / 100) * $total;
            } else {
                session()->forget('coupon_code');
            }
        }
    
        return view('cart-payment', [
            'cartItems' => CartHelper::getCartItems($cart),
            'total' => $total,
            'discount' => $discount,
            'paymentOptions' => PaymentOption::all(),
            'deliveryOptions' => DeliveryOption::all(),
        ]);
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'payment_option_id' => 'required|exists:payment_options,id',
            'delivery_option_id' => 'required|exists:delivery_options,id',
            'cart_total' => 'required|numeric|min:0',
        ]);

        if (!Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->firstOrFail();

            if ($cart->products->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
            }
        } else {
            $cart = session()->get('cart', []);
    
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
            }
        }

        $delivery = DeliveryOption::findOrFail($request->delivery_option_id);
        $finalTotal = $request->cart_total + $delivery->price;

        session([
            'order.payment_option_id' => $request->payment_option_id,
            'order.delivery_option_id' => $request->delivery_option_id,
            'order.total' => $finalTotal,
        ]);

        return redirect()->route('order.delivery')->with('success', 'Možnosti dopravy a platby uložené.');
    }

    public function delivery()
    {
        if (!Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->firstOrFail();

            if ($cart->products->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
            }
        } else {
            $cart = session()->get('cart', []);
    
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Váš košík je prázdny.');
            }
        }

        if (!session('order.payment_option_id') || !session('order.delivery_option_id') || !session('order.total')) {
            return redirect()->route('order.payment')->with('error', 'Vyberte spôsob dopravy a platby.');
        }

        $total = session('order.total');

        $discount = 0;
        
        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->where('amount', '>', 0)->first();
            if ($coupon) {
                $discount = ($coupon->discount / 100) * $total;
            } else {
                session()->forget('coupon_code');
            }
        }

        return view('cart-delivery', [
            'cartItems' => $cart,
            'total' => $total,
            'discount' => $discount,
        ]);
    }

    public function storeDelivery(Request $request)
    {
        $request->validate([
            'fullname' => [
                'required', 'string', 'max:255',
                'regex:/^[\p{L}]+\s[\p{L}]+$/u',
            ],
            'email' => 'required|email|max:255',
            'phone_number' => [
                'required', 'string', 'max:20',
                'regex:/^\+421\s?\d{3}\s?\d{3}\s?\d{3}$/',
            ],
            'street_and_number' => [
                'required', 'string', 'max:255',
                'regex:/[\p{L}].*\d/',
            ],
            'city' => [
                'required', 'string', 'max:100',
                'regex:/^[\p{L}\s]+$/u',
            ],
            'post_code' => [
                'required', 'string', 'max:20',
                'regex:/^\d{5}$/',
            ],
            'country' => [
                'required', 'string', 'max:100',
                'in:Slovenská Republika',
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

        if (!session('order.payment_option_id') || !session('order.delivery_option_id') || !session('order.total')) {
            return redirect()->route('order.payment')->with('error', 'Vyberte spôsob dopravy a platby.');
        }

        try {
            $deliveryDetail = DeliveryDetail::create($request->only([
                'fullname', 'email', 'phone_number', 'street_and_number', 'city', 'post_code', 'country',
            ]));

            $coupon = session('coupon_code')
                ? Coupon::where('code', session('coupon_code'))->first()
                : null;

            $order = Order::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'processed_date' => now(),
                'delivery_detail_id' => $deliveryDetail->id,
                'payment_option_id' => session('order.payment_option_id'),
                'delivery_option_id' => session('order.delivery_option_id'),
                'coupon_id' => $coupon?->id,
            ]);

            foreach ($cart->products as $product) {
                $order->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity
                ]);
            }

            if ($coupon && $coupon->amount > 0) {
                $coupon->decrement('amount');
            }

            // Clear everything except total for complete page
            $cart->products()->detach();
            session()->forget([
                'order.payment_option_id',
                'order.delivery_option_id',
                'order.delivery_price',
                'coupon_code',
            ]);

            session()->put('order.total', session('order.total'));
            session()->forget('order.total');

            return redirect()->route('order.complete', $order->id)
                ->with('success', 'Objednávka bola úspešne vytvorená.');
        } catch (\Exception $e) {
            Log::error('Order creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Chyba pri vytváraní objednávky. Skúste to znova.');
        }
    }

    public function orderComplete($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== null && $order->user_id !== Auth::id()) {
            abort(403, 'Neoprávnený prístup.');
        }

        $total = session('order.total');

        return view('order-complete', compact('order', 'total'));
    }
}

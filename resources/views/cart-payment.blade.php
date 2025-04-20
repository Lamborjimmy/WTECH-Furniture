<x-app-layout>
    <x-slot name="title">Doprava a platba</x-slot>

    <main class="container py-4 flex-grow-1">
        <section>
            <!-- Cart Header -->
            <div class="cart-header d-flex flex-column flex-md-row justify-content-evenly align-items-center mb-4">
                <a href="{{ route('cart.index') }}" class="text-decoration-none text-secondary d-flex align-items-center">
                    <i class="bi bi-1-circle-fill fs-4 me-2"></i>
                    <span class="fw-bold fs-4">Košík</span>
                </a>
                <a href="{{ route('order.payment') }}" class="text-decoration-none text-dark d-flex align-items-center">
                    <i class="bi bi-2-circle-fill fs-4 me-2"></i>
                    <span class="fw-bold fs-4">Doprava a platba</span>
                </a>
                <a href="#" class="text-decoration-none text-secondary d-flex align-items-center">
                    <i class="bi bi-3-circle-fill fs-4 me-2"></i>
                    <span class="fw-bold fs-4">Dodacie údaje</span>
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <!-- Payment and Delivery Options -->
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <h3 class="mb-3">Doprava a platba</h3>
                    <form id="payment-form" action="{{ route('order.payment.store') }}" method="POST">
                        @csrf
                        <div class="card p-3 border border-secondary border-opacity-25 rounded-0">
                            <h4>Platba</h4>
                            @foreach ($paymentOptions as $option)
                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="payment_option_id"
                                        id="payment{{ $option->id }}"
                                        value="{{ $option->id }}"
                                        {{ old('payment_option_id', session('cart.payment_option_id')) == $option->id ? 'checked' : '' }}
                                        required
                                    />
                                    <label class="form-check-label" for="payment{{ $option->id }}">
                                        {{ $option->name }}
                                    </label>
                                </div>
                            @endforeach

                            <h4>Doprava</h4>
                            @foreach ($deliveryOptions as $option)
                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="delivery_option_id"
                                        id="shipping{{ $option->id }}"
                                        value="{{ $option->id }}"
                                        {{ old('delivery_option_id', session('cart.delivery_option_id')) == $option->id ? 'checked' : '' }}
                                        required
                                    />
                                    <label class="form-check-label" for="shipping{{ $option->id }}">
                                        {{ $option->name }} ({{ $option->price == 0 ? 'Zdarma' : number_format($option->price, 2) . '€' }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <!-- Hidden Cart Total for Reference -->
                        <input type="hidden" name="cart_total" value="{{ $total }}">
                    </form>
                </div>

                <!-- Cart Summary -->
                <div class="col-12 col-md-6">
                    <h3 class="mb-3">Prehľad košíka</h3>
                    <div class="cart-items">
                        @if ($cartItems->isNotEmpty())
                            @foreach ($cartItems as $item)
                                <div class="card mb-2 border border-secondary border-opacity-25 rounded-0">
                                    <div class="row g-0">
                                        <div class="col-2 bg-light rounded-start d-flex align-items-center justify-content-center p-1">
                                            @if ($item['image'])
                                                <img
                                                    src="{{ asset('storage/' . $item['image']) }}"
                                                    class="img-fluid"
                                                    alt="{{ $item['title'] }}"
                                                />
                                            @else
                                                <img
                                                    src="{{ asset('images/placeholder.png') }}"
                                                    class="img-fluid"
                                                    alt="No image"
                                                />
                                            @endif
                                        </div>
                                        <div class="col-10">
                                            <div class="card-body py-2 px-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title fw-bold fs-5 text-dark m-0">
                                                        <a href="{{ route('products.show', $item['id']) }}" class="text-dark text-decoration-none">
                                                            {{ $item['title'] }}
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div class="d-flex flex-column align-items-end gap-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <h5 class="card-title fw-bold fs-6 text-secondary m-0">Množstvo</h5>
                                                        <span class="form-control text-center py-0" style="width: 50px; font-size: 0.9rem">
                                                            {{ $item['quantity'] }}
                                                        </span>
                                                    </div>
                                                    <span class="fw-bold" style="font-size: 1rem">
                                                        {{ number_format($item['price'] * $item['quantity'], 2) }}€
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center">Váš košík je prázdny.</p>
                        @endif
                    </div>
                </div>

                <!-- Navigation -->
                @if ($cartItems->isNotEmpty())
                    <div class="cart-navigation mt-4">
                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Späť
                            </a>
                            <div class="d-flex flex-column align-items-center align-items-md-end">
                                <h4 class="mb-2">
                                    Celkom: 
                                    <span class="fw-bold">
                                        {{ number_format($total - $discount, 2) }}€
                                        @if ($discount > 0)
                                            (Zľava: {{ number_format($discount, 2) }}€)
                                        @endif
                                    </span>
                                </h4>
                                <button type="submit" form="payment-form" class="btn btn-success">
                                    Pokračovať <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
</x-app-layout>
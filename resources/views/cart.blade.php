<x-app-layout>
    <x-slot name="title">Košík</x-slot>

    <main class="container py-4">
        <section>
            <!-- Cart Header -->
            @include('components.cart-stepper', ['step' => 1])

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="cart-items">  
                @if ($cartItems->isNotEmpty())
                    @foreach ($cartItems as $item)
                        <div class="card mb-3 border border-secondary border-opacity-25 rounded-0">
                            <div class="row g-0">
                                <div class="col-2 bg-light rounded-start d-flex align-items-center justify-content-center p-2">
                                    @if ($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" class="img-fluid" alt="{{ $item['title'] }}" />
                                    @else
                                        <img src="{{ asset('images/placeholder.png') }}" class="img-fluid" alt="No image" />
                                    @endif
                                </div>
                                <div class="col-10">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title fw-bold fs-4">
                                                <a href="{{ $item['id'] ? route('products.show', $item['id']) : '#' }}" class="text-dark text-decoration-none">
                                                    {{ $item['title'] }}
                                                </a>
                                            </h5>
                                            <form action="{{ $item['id'] ? route('cart.remove', $item['id']) : '#' }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="fw-bold text-secondary">
                                                {{ $item['in_stock'] > 0 ? 'Skladom' : 'Nedostupné' }}
                                                <i class="bi {{ $item['in_stock'] > 0 ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }} ms-1"></i>
                                            </span>
                                            <div class="d-flex flex-column align-items-end">
                                                <form action="{{ $item['id'] ? route('cart.update', $item['id']) : '#' }}" method="POST" class="input-group w-auto mb-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-outline-secondary" type="submit" name="action" value="decrease">-</button>
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        class="form-control text-center"
                                                        style="width: 60px"
                                                        value="{{ $item['quantity'] }}"
                                                        min="1"
                                                        max="{{ $item['in_stock'] }}"
                                                        readonly
                                                    />
                                                    <button class="btn btn-outline-secondary" type="submit" name="action" value="increase">+</button>
                                                </form>
                                                <span class="fs-5 fw-bold">{{ number_format($item['price'] * $item['quantity'], 2) }}€</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                <p class="text-start">Váš košík je prázdny.</p>
                @endif
            </div>

            <!-- Cart Navigation -->
            @if ($cartItems->isNotEmpty())
                <div class="cart-navigation mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3">
                        <div class="d-flex flex-column align-items-center align-items-md-start gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('cart.applyCoupon') }}" method="POST" class="d-flex w-100 w-md-auto">
                                @csrf
                                <input
                                    class="form-control me-2"
                                    type="text"
                                    name="coupon_code"
                                    placeholder="Zľavový kód"
                                    aria-label="Coupon code"
                                    value="{{ session('coupon_code') }}"
                                />
                                <button class="btn btn-outline-primary" type="submit">Vložiť</button>
                            </form>
                            @if (session('coupon_code'))
                                <form action="{{ route('cart.removeCoupon') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Späť k nákupu
                            </a>
                        </div>
                        <div class="d-flex flex-column align-items-center align-items-md-end">
                            <h4 class="mb-2">
                                Celkom: 
                                <span class="fw-bold">
                                    {{ number_format($total - $discount, 2) }}€
                                </span>
                                @if ($discount > 0)
                                    <span class="text-danger ms-2">Zľava: </span>
                                    <span class="text-danger fw-bold">
                                        {{ number_format($discount, 2) }}€
                                    </span>
                                @endif
                            </h4>
                            <a href="{{ route('order.payment') }}" class="btn btn-success">
                                Pokračovať <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </main>
</x-app-layout>
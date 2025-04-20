<x-app-layout>
    <x-slot name="title">Dodacie údaje</x-slot>

    <main class="container py-4 flex-grow-1">
        <section>
            <!-- Cart Header -->
            <div class="cart-header d-flex flex-column flex-md-row justify-content-evenly align-items-center mb-4">
                <a href="{{ route('cart.index') }}" class="text-decoration-none text-secondary d-flex align-items-center">
                    <i class="bi bi-1-circle-fill fs-4 me-2"></i>
                    <span class="fw-bold fs-4">Košík</span>
                </a>
                <a href="{{ route('order.payment') }}" class="text-decoration-none text-secondary d-flex align-items-center">
                    <i class="bi bi-2-circle-fill fs-4 me-2"></i>
                    <span class="fw-bold fs-4">Doprava a platba</span>
                </a>
                <a class="text-decoration-none text-dark d-flex align-items-center">
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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0 p-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!-- Delivery Details Form -->
                <div class="col-12 col-md-6 mb-4 mb-md-0">
                    <h3 class="mb-3">Dodacie údaje</h3>
                    <div class="card p-3 border border-secondary border-opacity-25 rounded-0">
                        <form id="delivery-form" action="{{ route('cart.delivery.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Meno a priezvisko</label>
                                <input
                                    type="text"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    id="fullname"
                                    name="fullname"
                                    value="{{ old('fullname') }}"
                                    placeholder="Jaroslav Ertl"
                                    required
                                />
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', Auth::user()->email ?? '') }}"
                                    placeholder="jaroslav.ertl@gmail.com"
                                    required
                                />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Telefónne číslo</label>
                                <input
                                    type="tel"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    id="phone_number"
                                    name="phone_number"
                                    value="{{ old('phone_number') }}"
                                    placeholder="+421 123 456 789"
                                    required
                                />
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="street_and_number" class="form-label">Ulica a číslo</label>
                                <input
                                    type="text"
                                    class="form-control @error('street_and_number') is-invalid @enderror"
                                    id="street_and_number"
                                    name="street_and_number"
                                    value="{{ old('street_and_number') }}"
                                    placeholder="Iľkovičova 6276/2"
                                    required
                                />
                                @error('street_and_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="city" class="form-label">Mesto</label>
                                    <input
                                        type="text"
                                        class="form-control @error('city') is-invalid @enderror"
                                        id="city"
                                        name="city"
                                        value="{{ old('city') }}"
                                        placeholder="Bratislava 4"
                                        required
                                    />
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="post_code" class="form-label">PSČ</label>
                                    <input
                                        type="text"
                                        class="form-control @error('post_code') is-invalid @enderror"
                                        id="post_code"
                                        name="post_code"
                                        value="{{ old('post_code') }}"
                                        placeholder="842 16"
                                        required
                                    />
                                    @error('post_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Krajina</label>
                                <input
                                    type="text"
                                    class="form-control @error('country') is-invalid @enderror"
                                    id="country"
                                    name="country"
                                    value="{{ old('country', 'Slovenská Republika') }}"
                                    placeholder="Slovenská Republika"
                                    required
                                />
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
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
                                                        <span class="form-control text-center py-0" style="width: 40px; font-size: 0.9rem">
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
                            <p class="text-start">Váš košík je prázdny.</p>
                        @endif
                    </div>
                </div>

                <!-- Navigation -->
                @if ($cartItems>isNotEmpty())
                    <div class="cart-navigation mt-4">
                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-3">
                            <a href="{{ route('order.payment') }}" class="btn btn-outline-secondary">
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
                                <button type="submit" form="delivery-form" class="btn btn-success">
                                    Objednať <i class="bi bi-check2 ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
</x-app-layout>
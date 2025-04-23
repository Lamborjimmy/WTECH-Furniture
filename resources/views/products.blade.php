<x-app-layout>
    <main class="container py-4 d-flex flex-column justify-content-between flex-grow-1">
        <div>
            <div class="d-flex justify-content-between mb-4">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                    Filtrovať
                </button>

                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasFilterLabel">Filter</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <form class="d-flex flex-column gap-3" action="{{ route('products.index') }}" method="GET">
                            <div>
                                <!-- Hidden inputs to retain the current search and sort parameters -->
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="placement" value="{{ request('placement') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                
                                <label class="mb-1 fs-7" for="category">Kategória</label>
                                <select id="category" name="category" class="form-select">
                                    <option value="" {{ request('category') == '' ? 'selected' : '' }}>Všetky</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 fs-7" for="color">Farba</label>
                                <select id="color" name="color" class="form-select">
                                    <option value="" {{ request('color') == '' ? 'selected' : '' }}>Všetky</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}" {{ request('color') == $color->id ? 'selected' : '' }}>
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 fs-7" for="material">Materiál</label>
                                <select id="material" name="material" class="form-select">
                                    <option value="" {{ request('material') == '' ? 'selected' : '' }}>Všetky</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}" {{ request('material') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="price_from">Cena od</label>
                                <div class="input-group">
                                    <input id="price_from" name="price_from" type="number" class="form-control" aria-label="Price From" aria-describedby="priceFromEuro" value="{{ request('price_from') }}">
                                    <span class="input-group-text" id="priceFromEuro">€</span>
                                </div>
                            </div>
                            <div>
                                <label for="price_to">Cena do</label>
                                <div class="input-group">
                                    <input id="price_to" name="price_to" type="number" class="form-control" aria-label="Price To" aria-describedby="priceToEuro" value="{{ request('price_to') }}">
                                    <span class="input-group-text" id="priceToEuro">€</span>
                                </div>
                            </div>
                            <button class="btn btn-success mt-5" data-bs-dismiss="offcanvas">
                                <i class="bi bi-search me-2"></i> Použiť
                            </button>
                            <a type="reset" class="btn btn-danger mt-1" href='{{ route('products.index') }}'">
                                <i class="bi bi-arrow-clockwise"></i> Obnoviť
                            </a>
                        </form>
                    </div>
                </div>

                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Hidden inputs to retain the current search and sort parameters -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="placement" value="{{ request('placement') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="color" value="{{ request('color') }}">
                    <input type="hidden" name="material" value="{{ request('material') }}">
                    <input type="hidden" name="price_from" value="{{ request('price_from') }}">
                    <input type="hidden" name="price_to" value="{{ request('price_to') }}">

                    <select class="form-select" name="sort" onchange="this.form.submit()">
                        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Základné</option>
                        <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Najlacnejšie</option>
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Najdrahšie</option>
                        <option value="alphabetical" {{ request('sort') == 'alphabetical' ? 'selected' : '' }}>Abecedne</option>
                    </select>
                </form>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                @forelse ($products as $product)
                    <div class="col">
                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                            <div class="bg-light rounded">
                                @if ($product->mainImage)
                                    <img src="{{ asset('storage/' . $product->mainImage->path) }}" class="img-fluid" alt="{{ $product->title }}">
                                @else
                                    <img src="{{ asset('images/placeholder.png') }}" class="img-fluid" alt="No image">
                                @endif
                            </div>
                            <div class="card-body px-0">
                                <h5 class="card-title fw-bold fs-6 text-secondary">{{ $product->title }}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Žiadne produkty nenájdené.</p>
                @endforelse
            </div>
        </div>
        <section class="container mt-4">
            {{ $products->links('vendor.pagination.bootstrap-5') }}
        </section>
    </main>
</x-app-layout>

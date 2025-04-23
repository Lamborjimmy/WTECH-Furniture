<x-app-layout>
    <main class="container py-4">
        <section class="mb-4">
            <div class="row g-4">
                <div class="col-12 col-md-8 bg-light">
                    @if(isset($mainImages[0]))
                        <img 
                            src="{{ asset('storage/' . $mainImages[0]->path) }}"
                            alt="{{ $mainImages[0]->title ?? 'Main Image' }}"
                            class="big-image img-fluid"
                        />
                    @endif
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-column gap-4">
                        @if(isset($mainImages[1]))
                            <div class="bg-light">
                                <img 
                                    src="{{ asset('storage/' . $mainImages[1]->path) }}"
                                    alt="{{ $mainImages[1]->title ?? 'Side Image 2' }}"
                                    class="side-image img-fluid"
                                />
                            </div>
                        @endif
                        @if(isset($mainImages[2]))
                            <div class="bg-light">
                                <img 
                                    src="{{ asset('storage/' . $mainImages[2]->path) }}"
                                    alt="{{ $mainImages[2]->title ?? 'Side Image 3' }}"
                                    class="side-image img-fluid"
                                />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section>
            <h1 class="m-0">Najpredávanejšie</h1>
            <div id="popularProductsCarouselLg" class="carousel slide d-none d-lg-block" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($popularProducts->chunk(4) as $chunkIndex => $chunk)
                        <div class="carousel-item @if($chunkIndex === 0) active @endif">
                            <div class="row row-cols-4 g-3 my-4 justify-content-center">
                                @foreach($chunk as $product)
                                    <div class="col d-flex justify-content-center m-0">
                                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                                            <div class="bg-light rounded">
                                                <img 
                                                    src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}"
                                                    class="img-fluid"
                                                    alt="{{ $product->title }}"
                                                />
                                            </div>
                                            <div class="card-body px-0">
                                                <h5 class="card-title fw-bold fs-6 text-secondary mb-2">
                                                    {{ $product->title }}
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        
            <div id="popularProductsCarouselMd" class="carousel slide d-none d-md-block d-lg-none" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($popularProducts->chunk(3) as $chunkIndex => $chunk)
                        <div class="carousel-item @if($chunkIndex === 0) active @endif">
                            <div class="row row-cols-3 g-3 my-4 justify-content-center m-0">
                                @foreach($chunk as $product)
                                    <div class="col d-flex justify-content-center">
                                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                                            <div class="bg-light rounded">
                                                <img 
                                                    src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}"
                                                    class="img-fluid"
                                                    alt="{{ $product->title }}"
                                                />
                                            </div>
                                            <div class="card-body px-0">
                                                <h5 class="card-title fw-bold fs-6 text-secondary mb-2">
                                                    {{ $product->title }}
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="popularProductsCarouselSm" class="carousel slide d-block d-md-none" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($popularProducts->chunk(1) as $chunkIndex => $chunk)
                        <div class="carousel-item @if($chunkIndex === 0) active @endif">
                            <div class="row row-cols-1 g-3 my-4 justify-content-center">
                                @foreach($chunk as $product)
                                    <div class="col d-flex justify-content-center m-0">
                                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                                            <div class="bg-light rounded">
                                                <img 
                                                    src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}"
                                                    class="img-fluid"
                                                    alt="{{ $product->title }}"
                                                />
                                            </div>
                                            <div class="card-body px-0">
                                                <h5 class="card-title fw-bold fs-6 text-secondary mb-2">
                                                    {{ $product->title }}
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section>
            <h1 class="m-0">Nové produkty</h1>
            <div id="newProductsCarouselLg" class="carousel slide d-none d-lg-block" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($newestProducts->chunk(4) as $chunkIndex => $chunk)
                        <div class="carousel-item @if($chunkIndex === 0) active @endif">
                            <div class="row row-cols-4 g-3 my-4 justify-content-center">
                                @foreach($chunk as $product)
                                    <div class="col d-flex justify-content-center m-0">
                                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                                            <div class="bg-light rounded">
                                                <img 
                                                    src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}"
                                                    class="img-fluid"
                                                    alt="{{ $product->title }}"
                                                />
                                            </div>
                                            <div class="card-body px-0">
                                                <h5 class="card-title fw-bold fs-6 text-secondary mb-2">
                                                    {{ $product->title }}
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        
            <div id="newProductsCarouselMd" class="carousel slide d-none d-md-block d-lg-none" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($newestProducts->chunk(3) as $chunkIndex => $chunk)
                        <div class="carousel-item @if($chunkIndex === 0) active @endif">
                            <div class="row row-cols-3 g-3 my-4 justify-content-center">
                                @foreach($chunk as $product)
                                    <div class="col d-flex justify-content-center m-0">
                                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                                            <div class="bg-light rounded">
                                                <img 
                                                    src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}"
                                                    class="img-fluid"
                                                    alt="{{ $product->title }}"
                                                />
                                            </div>
                                            <div class="card-body px-0">
                                                <h5 class="card-title fw-bold fs-6 text-secondary mb-2">
                                                    {{ $product->title }}
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="newProductsCarouselSm" class="carousel slide d-block d-md-none" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($newestProducts->chunk(1) as $chunkIndex => $chunk)
                        <div class="carousel-item @if($chunkIndex === 0) active @endif">
                            <div class="row row-cols-1 g-3 my-4 justify-content-center">
                                @foreach($chunk as $product)
                                    <div class="col d-flex justify-content-center m-0">
                                        <div class="card p-3 px-5 px-sm-3 border border-secondary border-opacity-25 rounded-0">
                                            <div class="bg-light rounded">
                                                <img 
                                                    src="{{ asset('storage/' . ($product->mainImage->path ?? 'images/placeholder.jpg')) }}"
                                                    class="img-fluid"
                                                    alt="{{ $product->title }}"
                                                />
                                            </div>
                                            <div class="card-body px-0">
                                                <h5 class="card-title fw-bold fs-6 text-secondary mb-2">
                                                    {{ $product->title }}
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">{{ number_format($product->price, 2) }}€</span>
                                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                        <i class="bi bi-bag-fill me-2"></i> Kúpiť
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</x-app-layout>

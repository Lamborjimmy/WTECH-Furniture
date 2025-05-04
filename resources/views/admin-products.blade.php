<x-app-layout>
    <x-slot name="title">Administrácia - Produkty</x-slot>

    <main class="container py-4">
        <h3 class="fs-4 mb-3">Zoznam produktov</h3>
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-5">
            <form class="col-12 col-md-6 col-lg-4 d-flex" role="search" action="{{ route('admin.products.index') }}" method="GET">
                <input
                    class="form-control me-2"
                    type="search"
                    name="search"
                    placeholder="Hľadať"
                    aria-label="Hľadať"
                    value="{{ request('search') }}"
                />
                <button class="btn btn-outline-success" type="submit">Hľadať</button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i>
                Pridať produkt
            </a>
        </div>

        <section class="d-flex flex-column gap-3">
            @forelse ($products as $product)
                <div class="card p-3 border border-secondary border-opacity-25 rounded-0 flex-md-row justify-content-center align-items-center">
                    <div class="d-flex gap-3 align-items-center">
                        <img
                            src="{{ $product->mainImage ? asset('storage/' . $product->mainImage->path) : asset('assets/placeholder.png') }}"
                            class="img-fluid col-4 col-sm-3 col-lg-2 col-xl-1"
                            alt="{{ $product->title }}"
                        />
                        <div>
                            <h4 class="fs-5 text-secondary m-0">{{ $product->title }}</h4>
                            <span>{{ $product->code }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-3 justify-content-end mt-3 mt-md-0">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-success">
                            <i class="bi bi-pen-fill"></i>
                            Upraviť
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Naozaj chcete odstrániť produkt „{{ $product->title }}“?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash-fill"></i>
                                Odstrániť
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center">Žiadne produkty nenájdené.</p>
            @endforelse
        </section>

        <div class="mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </main>
</x-app-layout>
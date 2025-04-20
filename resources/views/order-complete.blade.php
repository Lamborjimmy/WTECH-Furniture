<x-app-layout>
    <x-slot name="title">Ďakujeme</x-slot>

    <main class="container py-4 flex-grow-1">
        <section>
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 text-center">
                    <h1 class="mb-4">Ďakujeme za Vašu objednávku!</h1>
                    <p class="mb-4">
                        Vaša objednávka bola úspešne prijatá. Číslo Vašej objednávky je:
                        <strong>{{ $order->id }}</strong>
                    </p>
                    @if ($total)
                        <p class="mb-4">
                            Celková suma: <strong>{{ number_format($total, 2) }}€</strong>
                        </p>
                    @endif
                    <p class="mb-4">
                        O stave Vašej objednávky Vás budeme informovať prostredníctvom
                        e-mailu. Ak máte akékoľvek otázky, neváhajte nás kontaktovať.
                    </p>
                    <a href="{{ route('home.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Späť na hlavnú stránku
                    </a>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
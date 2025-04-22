<footer class="bg-light text-dark py-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start gap-3">
                <a href="{{ route('home.index') }}" class="text-dark text-decoration-none">Domov</a>
                <a href="#" class="text-dark text-decoration-none">Podmienky</a>
                <a href="{{ route('products.index') }}" class="text-dark text-decoration-none">Produkty</a>
                <a href="#" class="text-dark text-decoration-none">Kontakt</a>
            </div>
            <div class="col-12 col-md-6 mb-2 mb-md-0 text-center text-md-end d-flex justify-content-end align-items-center">
                <a href="{{ route('home.index') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mt-3 mt-md-0" style="max-width: 6rem;">
                </a>
            </div>
        </div>
    </div>
</footer>

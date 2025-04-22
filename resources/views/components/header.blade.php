<nav class="navbar navbar-expand-lg bg-light py-2 d-flex flex-column">
    <div class="container d-flex justify-content-between">
        <a href="{{ route('home.index') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 8.5rem;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbar">
            <form class="d-flex ms-0 ms-lg-5 my-3 my-lg-0" role="search" action="{{ route('products.index') }}">
                <input class="form-control me-2" type="search" name="search" placeholder="Hľadať" aria-label="Search" value="{{ request('search') }}">
                <button class="btn btn-outline-success" type="submit">Hľadať</button>
            </form>
            <ul class="navbar-nav text-center d-flex align-items-center">
                <li class="nav-item">
                    <a 
                        class="d-flex align-items-center gap-1 nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}" 
                        href="{{ route('home.index') }}"
                    >
                        <i class="bi bi-house-fill"></i> Domov
                    </a>
                </li>
                <li class="nav-item">
                    <a 
                        class="d-flex align-items-center gap-1 nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" 
                        href="{{ route('products.index') }}"
                    >
                        <i class="bi bi-bag-fill"></i> Produkty
                    </a>
                </li>
                @guest
                    <li class="nav-item">
                        <a 
                            class="d-flex align-items-center gap-1 nav-link" 
                            href="{{ route('login') }}"
                        >
                            <i class="bi bi-person-fill"></i> Prihlásiť sa
                        </a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="nav-link">
                                <i class="bi bi-door-closed-fill"></i> Odhlásiť sa
                            </button>
                        </form>
                    </li>
                @endauth
                <li class="nav-item">
                    <a 
                        class="d-flex align-items-center gap-1 nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}" 
                        href="{{ route('cart.index') }}"
                    >
                        <i class="bi bi-cart-fill"></i> Košík
                    </a>
                </li>
            </ul>
            <hr class="d-lg-none my-3">
            <ul class="navbar-nav text-center d-flex align-items-center d-lg-none">
                @foreach ($placements as $placement)
                    <li class="nav-item">
                        <a class="d-flex align-items-center gap-1 nav-link" href="{{ route('products.index', ['placement' => $placement->id]) }}">
                            <i 
                                class="bi bi-{{ $placement->name == 'Spálňa' ? 'lamp-fill' : ($placement->name == 'Kuchyňa' ? 'cup-hot-fill' : ($placement->name == 'Kúpeľňa' ? 'droplet-fill' : ($placement->name == 'Obývacia izba' ? 'tv-fill' : 'laptop-fill'))) }}"
                            >
                            </i>
                            {{ $placement->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="container d-none d-lg-block bg-light mt-1">
        <ul class="navbar-nav text-center d-flex align-items-center">
            @foreach ($placements as $placement)
                <li class="nav-item">
                    <a class="d-flex align-items-center gap-2 nav-link {{ $loop->first ? 'ps-0' : '' }}" href="{{ route('products.index', ['placement' => $placement->id]) }}">
                        <i 
                            class="bi bi-{{ $placement->name == 'Spálňa' ? 'lamp-fill' : ($placement->name == 'Kuchyňa' ? 'cup-hot-fill' : ($placement->name == 'Kúpeľňa' ? 'droplet-fill' : ($placement->name == 'Obývacia izba' ? 'tv-fill' : 'laptop-fill'))) }}"
                        >
                        </i>
                        {{ $placement->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>

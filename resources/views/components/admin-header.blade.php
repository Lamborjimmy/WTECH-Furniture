<nav class="navbar bg-light py-2">
    <div class="container w-100 d-flex align-items-center justify-content-between">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 8.5rem;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-box-arrow-in-left"></i>
                Odhlásiť sa
            </button>
        </form>
    </div>
</nav>
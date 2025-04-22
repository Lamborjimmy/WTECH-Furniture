<nav class="navbar bg-light py-2">
    <div
      class="container w-100 d-flex align-items-center justify-content-between"
    >
      <a href={{ route('home.index') }}>
        <img src={{ asset('images/logo.png') }} alt="Logo" style="max-width: 8.5rem;">
      </a>
        <a href={{ url()->previous() }} class="btn btn-secondary text-decoration-none">
            <i class="bi bi-arrow-left"></i>
            Späť
        </a>
    </div>
</nav>

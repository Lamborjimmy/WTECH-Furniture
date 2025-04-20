<x-app-layout>
    <main class="container row py-5 my-5 justify-content-center mx-auto">
        <form class="col-lg-4 p-4 bg-light rounded shadow-sm" method="POST" action="{{ route('login') }}">
            @csrf
            <h3 class="fs-3 text-secondary text-center mb-3">Prihlásenie</h3>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    name="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    placeholder="Zadajte email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input
                    name="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    placeholder="Zadajte heslo"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-3 form-check">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    name="remember" 
                    id="remember"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="remember">
                    Zapamätať si ma
                </label>
            </div>
            <button
                type="submit"
                class="btn btn-primary mt-3 col-12"
            >
                <i class="bi bi-box-arrow-in-right"></i>
                Prihlásiť sa
            </button>
            <p class="mt-3 text-center">
                Nemáte ešte účet?
                <a class="link-primary" href="{{ route('register') }}">Zaregistrovať sa</a>
            </p>
        </form>
    </main>
</x-app-layout>

<x-app-layout>
    <main class="container row py-5 my-5 justify-content-center mx-auto">
        <form method="POST" action="{{ route('register') }}" class="col-lg-4 p-4 bg-light rounded shadow-sm">
            @csrf
            <h3 class="fs-3 text-secondary text-center mb-3">Registrácia</h3>
            <div class="mb-3">
                <label for="name" class="form-label">Používateľské meno</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Zadajte meno"
                    required
                    autofocus
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Zadajte email"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    placeholder="Zadajte heslo"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Zopakujte heslo</label>
                <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Zopakujte heslo"
                    required
                >
            </div>
            <button type="submit" class="btn btn-success mt-3 col-12">
                <i class="bi bi-person-fill"></i>
                Zaregistrovať sa
            </button>
            <p class="mt-3 text-center">
                Už máte účet?
                <a class="link-primary" href="{{ route('login') }}">Prihlásiť sa</a>
            </p>
        </form>
    </main>
</x-app-layout>

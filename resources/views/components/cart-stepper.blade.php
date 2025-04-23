@props(['step' => 1])

<div class="cart-header d-flex flex-column flex-md-row justify-content-evenly align-items-center mb-4">
    <a 
        href="{{ $step >= 1 ? route('cart.index') : '#' }}" 
        class="text-decoration-none d-flex align-items-center {{ $step == 1 ? 'text-dark' : 'text-secondary' }}"
    >
        <i class="bi bi-1-circle-fill fs-4 me-2"></i>
        <span class="fw-bold fs-4">Košík</span>
    </a>
    <a 
        href="{{ $step >= 2 ? route('order.payment') : '#' }}" 
        class="text-decoration-none d-flex align-items-center {{ $step == 2 ? 'text-dark' : 'text-secondary' }}"
    >
        <i class="bi bi-2-circle-fill fs-4 me-2"></i>
        <span class="fw-bold fs-4">Doprava a platba</span>
    </a>
    <a 
        href="{{ $step >= 3 ? route('order.delivery') : '#' }}" 
        class="text-decoration-none d-flex align-items-center {{ $step == 3 ? 'text-dark' : 'text-secondary' }}"
    >
        <i class="bi bi-3-circle-fill fs-4 me-2"></i>
        <span class="fw-bold fs-4">Dodacie údaje</span>
    </a>
</div>

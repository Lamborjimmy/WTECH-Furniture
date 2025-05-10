<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role_id === 1) {
            return $next($request);
        }

        return redirect()->intended(route('admin.products.index', absolute: false));
    }
}

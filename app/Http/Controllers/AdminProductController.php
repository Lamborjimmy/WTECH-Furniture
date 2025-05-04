<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    public function __construct()
    {
        /*$this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role->name !== 'admin') {
                abort(403, 'Neoprávnený prístup.');
            }
            return $next($request);
        });*/
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $products = Product::with('mainImage')
            ->where('valid', true)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('added_date', 'desc')
            ->paginate(8);

        return view('admin-products', compact('products'));
    }

    public function create()
    {
        // Placeholder for create view (admin-add.blade.php)
        return view('admin-products-create');
    }

    public function edit($id)
    {
        $product = Product::where('valid', true)->findOrFail($id);
        // Placeholder for edit view (admin-products-edit.blade.php)
        return view('admin-products-edit', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::where('valid', true)->findOrFail($id);
        $product->update(['valid' => false]);

        return redirect()->route('admin.products.index')->with('success', 'Produkt „' . $product->title . '“ bol odstránený.');
    }
}
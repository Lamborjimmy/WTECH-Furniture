<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Placement;
use App\Models\ImageReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'mainImage'])->where('valid', true);

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        // Filters
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        if ($request->has('color') && $request->color != '') {
            $query->where('color_id', $request->color);
        }
        if ($request->has('material') && $request->material != '') {
            $query->where('material_id', $request->material);
        }
        if ($request->has('placement') && $request->placement != '') {
            $query->where('placement_id', $request->placement);
        }
        if ($request->has('price_from') && $request->price_from != '') {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->has('price_to') && $request->price_to != '') {
            $query->where('price', '<=', $request->price_to);
        }

        // Sorting
        if ($request->has('sort')) {
            if ($request->sort == 'cheapest') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'expensive') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort == 'alphabetical') {
                $query->orderBy('title', 'asc');
            }
        }

        $products = $query->paginate(10)->appends($request->query());
        $categories = Category::all();
        $colors = Color::all();
        $materials = Material::all();
        $placements = Placement::all();

        return view('products', compact('products', 'categories', 'colors', 'materials', 'placements'));
    }

    public function show($id)
    {
        $product = Product::with(['color', 'category', 'material', 'placement', 'images', 'mainImage'])
            ->where('valid', true)
            ->findOrFail($id);
        
        $relatedProducts = Product::with(['mainImage'])
            ->where('valid', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(8)
            ->get();

        if ($relatedProducts->count() < 8) {
            $remain = 8 - $relatedProducts->count();
        
            $remainingProducts = Product::whereNotIn('id', $relatedProducts->pluck('id'))
                ->inRandomOrder()
                ->where('valid', true)
                ->where('id', '!=', $product->id)
                ->with('mainImage')
                ->limit($remain)
                ->get();
        
            $relatedProducts = $relatedProducts->concat($remainingProducts);
        }
        
        $categories = Category::all();

        return view('product-detail', compact('product', 'relatedProducts', 'categories'));
    }

    public function home()
    {
        $mainImages = ImageReference::where('is_main', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $products = DB::select('
            SELECT
                products.*
            FROM
                products
            JOIN
                orders_products ON products.id = orders_products.product_id
            WHERE
                products.valid = true
            GROUP BY
                products.id
            ORDER BY
                SUM(orders_products.quantity) DESC
            LIMIT 8
        ');

        $popularProducts = Product::hydrate($products);
        $popularProducts->load('mainImage');      

        if ($popularProducts->count() < 8) {
            $remain = 8 - $popularProducts->count();
        
            $remainingProducts = Product::whereNotIn('id', $popularProducts->pluck('id'))
                ->inRandomOrder()
                ->where('valid', true)
                ->with('mainImage')
                ->limit($remain)
                ->get();
        
            $popularProducts = $popularProducts->concat($remainingProducts);
        }

        $newestProducts = Product::with(['mainImage'])
            ->where('valid', true)
            ->orderByDesc('added_date')
            ->limit(8)
            ->get();

        return view('home', compact('mainImages', 'popularProducts', 'newestProducts'));
    }
}
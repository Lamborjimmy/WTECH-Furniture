<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Placement;
use App\Models\ImageReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $categories = Category::all();
        $colors = Color::all();
        $materials = Material::all();
        $placements = Placement::all();

        return view('admin-products-create', compact('categories', 'colors', 'materials', 'placements'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:products,title',
            'code' => 'required|string|max:50|unique:products,code|regex:/^[A-Za-z0-9]+$/',
            'description' => 'required|string|max:1000',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'detail_image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'detail_image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'detail_image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:colors,id',
            'material_id' => 'required|exists:materials,id',
            'placement_id' => 'required|exists:placements,id',
            'price' => 'required|numeric|gt:0|max:10000',
            'width' => 'required|numeric|gt:0|max:1000',
            'length' => 'required|numeric|gt:0|max:1000',
            'depth' => 'required|numeric|gt:0|max:1000',
            'in_stock' => 'required|integer|min:1',
        ], [
            'title.unique' => 'Produkt s týmto názvom už existuje.',
            'code.unique' => 'Produkt s týmto kódom už existuje.',
            'code.regex' => 'Kód môže obsahovať iba písmená a čísla.',
            'main_image.required' => 'Hlavný obrázok je povinný.',
            'main_image.image' => 'Hlavný obrázok musí byť platný obrázok (jpeg, png, jpg).',
            'main_image.max' => 'Hlavný obrázok nesmie byť väčší ako 5MB.',
            'detail_image_*.image' => 'Detailný obrázok musí byť platný obrázok (jpeg, png, jpg).',
            'detail_image_*.max' => 'Detailný obrázok nesmie byť väčší ako 5MB.',
            'price.gt' => 'Cena musí byť väčšia ako 0.',
            'width.gt' => 'Šírka musí byť väčšia ako 0.',
            'length.gt' => 'Dĺžka musí byť väčšia ako 0.',
            'depth.gt' => 'Hĺbka musí byť väčšia ako 0.',
            'in_stock.gt' => 'Množstvo musí byť aspoň 1.',
        ]);

        try {

            // Create product
            $product = Product::create([
                'title' => $validated['title'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'color_id' => $validated['color_id'],
                'material_id' => $validated['material_id'],
                'placement_id' => $validated['placement_id'],
                'price' => $validated['price'],
                'width' => $validated['width'],
                'length' => $validated['length'],
                'depth' => $validated['depth'],
                'in_stock' => $validated['in_stock'] ?? 0,
                'valid' => true,
                'added_date' => now(),
                'modified_date' => now(),
            ]);

            // Store main image
            if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
                $file = $request->file('main_image');
                $extension = $file->getClientOriginalExtension();
                $uniqueName = $product->code . '-main-' . time() . '.' . $extension;
                $path = $file->storeAs('products', $uniqueName, 'public');

                $imageReference = ImageReference::create([
                    'product_id' => $product->id,
                    'title' => $product->title . ' - Hlavný obrázok',
                    'path' => $path,
                    'is_main' => true,
                ]);
            } 

            // Store detail images
            foreach (['detail_image_1', 'detail_image_2', 'detail_image_3'] as $index => $field) {
                if ($request->hasFile($field) && $request->file($field)->isValid()) {
                    $file = $request->file($field);
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = $product->code . '-detail-' . ($index + 1) . '-' . time() . '.' . $extension;
                    $path = $file->storeAs('products', $uniqueName, 'public');

                    $imageReference = ImageReference::create([
                        'product_id' => $product->id,
                        'title' => $product->title . ' - Detail ' . ($index + 1),
                        'path' => $path,
                        'is_main' => false,
                    ]);
                }
            }
            return redirect()->route('admin.products.index')->with('success', 'Produkt „' . $product->title . '“ bol úspešne pridaný.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chyba pri pridávaní produktu: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::where('valid', true)->findOrFail($id);
        return view('admin-products-edit', compact('product'));
    }

     public function destroy($id)
    {
        $product = Product::where('valid', true)->findOrFail($id);

        try {
            // Delete associated image files and database records
            $images = ImageReference::where('product_id', $product->id)->get();
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                    Log::info('Image file deleted', ['product_id' => $product->id, 'path' => $image->path]);
                }
                $image->delete();
                Log::info('Image reference deleted', ['image_id' => $image->id, 'product_id' => $product->id]);
            }

            // Delete the product
            $product->delete();
            Log::info('Product deleted', ['product_id' => $product->id, 'title' => $product->title]);

            return redirect()->route('admin.products.index')->with('success', 'Produkt „' . $product->title . '“ bol odstránený.');
        } catch (\Exception $e) {
            Log::error('Error deleting product', ['product_id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.products.index')->with('error', 'Chyba pri odstraňovaní produktu: ' . $e->getMessage());
        }
    }
}
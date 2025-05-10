<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Placement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::with('mainImage')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin-products', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $materials = Material::all();
        $placements = Placement::all();

        return view('product-form', compact('categories', 'colors', 'materials', 'placements'));
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
                'modified_date' => NULL,
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
            return redirect()->route('admin.index')->with('success', 'Produkt „' . $product->title . '“ bol úspešne pridaný.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chyba pri pridávaní produktu: ' . $e->getMessage())->withInput();
        }
    }

    public function createEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $colors = Color::all();
        $materials = Material::all();
        $placements = Placement::all();

        $mainImage = $product->images()->where('is_main', true)->first();
        $detailImages = $product->images()->where('is_main', false)->get();
    
        return view('product-form', compact('product', 'categories', 'colors', 
            'materials', 'placements', 'mainImage', 'detailImages'));
    }

    public function storeEdit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'code' => 'required|string|max:50',
                'description' => 'required|string|max:1000',
                'category_id' => 'required|exists:categories,id',
                'color_id' => 'required|exists:colors,id',
                'material_id' => 'required|exists:materials,id',
                'placement_id' => 'required|exists:placements,id',
                'price' => 'required|numeric|gt:0',
                'in_stock' => 'required|integer|min:0',
                'width' => 'required|numeric|gt:0',
                'length' => 'required|numeric|gt:0',
                'depth' => 'required|numeric|gt:0',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'detail_image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'detail_image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'detail_image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:image_references,id',
            ], [
                'title.unique' => 'Produkt s týmto názvom už existuje.',
                'code.unique' => 'Produkt s týmto kódom už existuje.',
                'code.regex' => 'Kód môže obsahovať iba písmená a čísla.',
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
    
            if ($request->hasFile('main_image')) {
                $oldMainImage = $product->images()->where('is_main', true)->first();
                if ($oldMainImage) {
                    Storage::delete('public/' . $oldMainImage->path);
                    $oldMainImage->delete();
                }
    
                $file = $request->file('main_image');
                $extension = $file->getClientOriginalExtension();
                $uniqueName = $product->code . '-main-' . time() . '.' . $extension;
                $path = $file->storeAs('products', $uniqueName, 'public');

                $product->images()->create([
                    'product_id' => $product->id,
                    'title' => $product->title . ' - Hlavný obrázok',
                    'path' => $path,
                    'is_main' => true,
                ]);
            }
    
            $detailImages = $product->images()
                ->where('is_main', false)
                ->orderBy('id')
                ->get();

            foreach ($detailImages as $index => $image) {
                $position = $index + 1;
                
                if ($request->has('delete_images') && in_array($image->id, $request->delete_images)) {
                    Storage::delete('public/' . $image->path);
                    $image->delete();
                    
                    if ($request->hasFile("detail_image_{$position}")) {
                        $file = $request->file("detail_image_{$position}");
                        $extension = $file->getClientOriginalExtension();
                        $uniqueName = $product->code . '-detail-' . $position . '-' . time() . '.' . $extension;
                        $path = $file->storeAs('products', $uniqueName, 'public');

                        $product->images()->create([
                            'product_id' => $product->id,
                            'title' => $product->title . ' - Detail ' . $position,
                            'path' => $path,
                            'is_main' => false,
                        ]);
                    }
                }
            }
    
            for ($i = count($detailImages) + 1; $i <= 3; $i++) {
                if ($request->hasFile("detail_image_{$i}")) {
                    $file = $request->file("detail_image_{$i}");
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = $product->code . '-detail-' . $i . '-' . time() . '.' . $extension;
                    $path = $file->storeAs('products', $uniqueName, 'public');

                    $product->images()->create([
                        'product_id' => $product->id,
                        'title' => $product->title . ' - Detail ' . $i,
                        'path' => $path,
                        'is_main' => false,
                    ]);
                }
            }
    
            $updatedProduct = [
                'title' => $validated['title'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'color_id' => $validated['color_id'],
                'material_id' => $validated['material_id'],
                'placement_id' => $validated['placement_id'],
                'price' => $validated['price'],
                'in_stock' => $validated['in_stock'],
                'width' => $validated['width'],
                'length' => $validated['length'],
                'depth' => $validated['depth'],
                'modified_date' => now()
            ];
    
            $product->update($updatedProduct);
    
            return redirect()->route('admin.index')->with('success', 'Produkt „' . $product->title . '“ bol úspešne upravený.');
        }
        catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Chyba pri úprave produktu: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $product = Product::where('valid', true)->findOrFail($id);

        try {
            // Delete associated cart entries
            $cartEntries = CartsProducts::where('product_id', $product->id)->get();
            
            foreach ($cartEntries as $cartEntry) {
                $cartEntry->delete();
            }

            // Delete associated image files and database records
            $images = ImageReference::where('product_id', $product->id)->get();
            
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);            
                }
                $image->delete();
            }

            // Soft delete the product
            $product->update(['valid' => false]); 

            return redirect()->route('admin.index')->with('success', 'Produkt „' . $product->title . '“ bol odstránený.');
        } catch (\Exception $e) {
            return redirect()->route('admin.index')->with('error', 'Chyba pri odstraňovaní produktu: ' . $e->getMessage());
        }
    }
}

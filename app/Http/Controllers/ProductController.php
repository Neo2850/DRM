<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductSpecification;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::with(['category', 'brand'])->paginate(10);
        return Inertia::render('AdminSide/Products/Index', [
            'products'=> $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('AdminSide/Products/Create', [
            'categories' => Category::with('specifications')->get(),
            'brands' => Brand::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'warranty' => 'required|string|max:255',
        'images.*' => 'nullable|file|image|max:10240',
        'specifications' => 'nullable|array',
        'specifications.*.id' => 'required|integer|exists:specifications,id',
        'specifications.*.value' => 'required|string|max:255',
    ]);

    try {
        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('product_images', 'public');
            }
        }

        // Save product
        $product = Product::create(array_merge($validated, [
            'product_images' => $images,
            'sku' => '', // Placeholder SKU
        ]));

        // Generate SKU
        $category = Category::find($validated['category_id']);
        $product->update(['sku' => $category->sku . '-' . $product->id]);

        // Attach specifications
        if (!empty($validated['specifications'])) {
            foreach ($validated['specifications'] as $spec) {
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'spec_id' => $spec['id'],
                    'value' => $spec['value'],
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('AdminSide/Products/Edit', [
            'product' => $product->load(['specifications' => function($query) {
                $query->select('specifications.id', 'specifications.name', 'product_specifications.value');
            }]),
            'categories' => Category::with('specifications')->get(),
            'brands' => Brand::all(),
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'warranty' => 'required|string|max:255',
            'images.*' => 'nullable|file|image|max:10240',
            'specifications' => 'nullable|array',
            'specifications.*.id' => 'required|integer|exists:specifications,id',
            'specifications.*.value' => 'required|string|max:255',
            'remove_images' => 'nullable|array',
        ]);

        try {
            // Handle image deletions first
            if ($request->remove_images) {
                foreach ($request->remove_images as $image) {
                    // Delete the file from storage
                    Storage::disk('public')->delete($image);
                    // Remove from product's images array
                    $product->product_images = array_diff($product->product_images, [$image]);
                }
            }

            // Handle new image uploads
            $newImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $newImages[] = $image->store('product_images', 'public');
                }
            }

            // Merge existing and new images
            $allImages = array_merge($product->product_images ?? [], $newImages);

            // Update product with basic information
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'warranty' => $validated['warranty'],
                'product_images' => $allImages,
            ]);

            // Update specifications
            // First, delete existing specifications
            $product->specifications()->detach();

            // Then add new specifications
            if (!empty($validated['specifications'])) {
                foreach ($validated['specifications'] as $spec) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'spec_id' => $spec['id'],
                        'value' => $spec['value'],
                    ]);
                }
            }

            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Delete product images from storage
            if (!empty($product->product_images)) {
                foreach ($product->product_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            // Delete product specifications
            $product->specifications()->detach();

            // Delete the product
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error deleting product: ' . $e->getMessage()]);
        }
    }
}

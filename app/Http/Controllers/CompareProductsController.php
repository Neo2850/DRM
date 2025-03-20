<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompareProductsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        // Get category filter and pre-selected products from the request
        $categoryId = $request->get('category_id');
        $product1Id = $request->get('product1');
        $product2Id = $request->get('product2');

        // Get brands based on the selected category
        $brands = $categoryId
            ? Brand::whereHas('products', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })->get()
            : collect();

        // Query products based on category
        $query = Product::with(['category', 'brand', 'productSpecifications.specification']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'price' => $product->price,
                'stock' => $product->stock,
                'image' => $product->product_images[0] ?? null,
                'brand' => $product->brand->name,
                'specifications' => $product->productSpecifications->mapWithKeys(function ($spec) {
                    return [$spec->specification->name => $spec->value];
                })->toArray()
            ];
        });

        // Pre-select products if provided in the URL
        $selectedProduct1 = $product1Id ? $products->firstWhere('id', $product1Id) : null;
        $selectedProduct2 = $product2Id ? $products->firstWhere('id', $product2Id) : null;

        return Inertia::render('ClientSide/CompareProducts', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'preSelectedProduct1' => $selectedProduct1,
            'preSelectedProduct2' => $selectedProduct2,
            'selectedCategory' => $categoryId
        ]);
    }

}

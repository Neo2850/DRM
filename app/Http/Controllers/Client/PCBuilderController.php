<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PCBuilderController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'specifications'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'brand' => $product->brand->name,
                    'category' => $product->category->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'image' => $product->product_images[0] ?? null,
                    'specifications' => $product->specifications
                ];
            });
        $categories = Category::all();
        $brands = Brand::all();
        return Inertia::render('ClientSide/PCBuilder', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
}

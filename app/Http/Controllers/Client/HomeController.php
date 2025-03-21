<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LandingContent;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $landingContents = LandingContent::where('is_active', 1)->get();
        $exploreProducts = Product::with(['category', 'brand', 'specifications'])
            ->take(2)
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
            })
            ->random(2);
        $latestProducts = Product::with(['category', 'brand', 'specifications'])
            ->latest()
            ->take(2)
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
                        ->map(fn($spec) => [
                            'name' => $spec->name,
                            'value' => $spec->pivot->value
                        ])
                ];
            });

        return Inertia::render('ClientSide/GuestHome', [
            'exploreProducts' => $exploreProducts,
            'latestProducts' => $latestProducts,
            'categories' => $categories,
            'brands' => $brands,
            'landingContents' => $landingContents
        ]);
    }
}

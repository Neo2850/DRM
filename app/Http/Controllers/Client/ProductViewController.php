<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductViewController extends Controller
{
    public function index($slug) {
        $product = Product::where('slug', $slug)
            ->with([
                'brand',
                'category',
                'specifications' => function($query) {
                    $query->select('specifications.id', 'specifications.name', 'product_specifications.value');
                }
            ])
            ->firstOrFail();

        $similarProducts = Product::where(function($query) use ($product) {
                $query->where('category_id', $product->category_id)
                      ->orWhere('brand_id', $product->brand_id);
            })
            ->where('id', '!=', $product->id)
            ->with('brand')
            ->take(10)
            ->get();

        // Debug similar products query

        $mappedSimilarProducts = $similarProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'rating' => $product->rating ?? 5,
                'reviewCount' => $product->review_count ?? 0,
                'image' => $product->product_images[0] ?? null,
                'brand' => $product->brand->name,
                'specifications' => $product->specifications->map(function($spec) {
                    return [
                        'name' => $spec->name,
                        'value' => $spec->value
                    ];
                })
            ];
        });

        return Inertia::render('ClientSide/ProductView', [
            'product' => $product,
            'similarProducts' => $mappedSimilarProducts
        ]);
    }
}

<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ProductListController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->select(['id', 'name', 'slug', 'price', 'stock', 'brand_id', 'category_id', 'product_images', 'description', 'warranty'])
            ->with([
                'category:id,name',
                'brand:id,name',
                'specifications' => function($query) {
                    $query->select('specifications.id', 'specifications.name', 'product_specifications.value', 'product_specifications.product_id');
                }
            ])
            ->filter($request->only([
                'search',
                'priceRange',
                'inStock',
                'brands',
                'categories'
            ]));

        $filteredProductIds = (clone $query)->pluck('id');

        $categories = Category::select(['id', 'name'])
            ->whereHas('products', function($q) use ($filteredProductIds) {
                $q->whereIn('id', $filteredProductIds);
            })
            ->withCount(['products' => function($q) use ($filteredProductIds) {
                $q->whereIn('id', $filteredProductIds);
            }])
            ->having('products_count', '>', 0)
            ->get();

        $brands = Brand::select(['id', 'name'])
            ->whereHas('products', function($q) use ($filteredProductIds) {
                $q->whereIn('id', $filteredProductIds);
            })
            ->withCount(['products' => function($q) use ($filteredProductIds) {
                $q->whereIn('id', $filteredProductIds);
            }])
            ->having('products_count', '>', 0)
            ->get();

        $products = $query->paginate(16);

        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'slug' => $product->slug,
                'stock' => $product->stock,
                'brand' => $product->brand->name,
                'category' => $product->category->name,
                'image' => $product->product_images[0] ?? null,
                'rating' => 4,
                'description' => $product->description,
                'warranty' => $product->warranty,
                'specifications' => $product->specifications->map(function($spec) {
                    return [
                        'name' => $spec->name,
                        'value' => $spec->value
                    ];
                })
            ];
        });

        return Inertia::render('ClientSide/ProductList', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'searchTerm' => $request->search,
            'filters' => $request->only(['priceRange', 'inStock', 'brands', 'categories'])
        ]);
    }
}

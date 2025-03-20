<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CategoryProductsController extends Controller
{
    public function index(Request $request, $categorySlug = null)
    {
        $currentCategory = null;
        if ($categorySlug) {
            $currentCategory = Category::where('slug', $categorySlug)
                ->select(['id', 'name', 'slug'])
                ->first();

            if (!$currentCategory) {
                abort(404);
            }
        }

        $query = Product::query()
            ->select([
                'id', 'name', 'slug', 'price', 'stock',
                'brand_id', 'category_id', 'product_images',
                'description', 'warranty'
            ])
            ->with([
                'category:id,name,slug',
                'brand:id,name',
                'specifications' => function($query) {
                    $query->select(
                        'specifications.id',
                        'specifications.name',
                        'product_specifications.value',
                        'product_specifications.product_id'
                    );
                }
            ]);

        if ($currentCategory) {
            $query->where('category_id', $currentCategory->id);
        }

        $query->filter($request->only([
            'search',
            'priceRange',
            'inStock',
            'brands',
            'categories'
        ]));

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

        $categories = Cache::remember('categories', 3600, function () {
            return Category::select(['id', 'name', 'slug'])
                ->withCount('products')
                ->get();
        });

        $brands = Cache::remember('brands', 3600, function () {
            return Brand::select(['id', 'name'])
                ->withCount('products')
                ->get();
        });

        return Inertia::render('ClientSide/CategoryProducts', [
            'currentCategory' => $currentCategory ? [
                'id' => $currentCategory->id,
                'name' => $currentCategory->name,
                'slug' => $currentCategory->slug,
            ] : null,
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'searchTerm' => $request->search,
            'filters' => $request->only(['priceRange', 'inStock', 'brands', 'categories']),
        ]);
    }
}

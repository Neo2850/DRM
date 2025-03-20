<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComponentSelectionController extends Controller
{
    public function index(Request $request, $componentType)
    {
        // Get the products for the specific component type (category)
        $query = Product::query()
            ->select([
                'id', 'name', 'slug', 'price', 'stock',
                'brand_id', 'category_id', 'product_images',
                'description'
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
            ])
            ->whereHas('category', function($query) use ($componentType) {
                $query->where('slug', $componentType);
            })
            ->orderBy('price', 'asc');

        $products = $query->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'brand' => $product->brand->name,
                    'category' => $product->category->name,
                    'image' => $product->product_images[0] ?? null,
                    'description' => $product->description,
                    'specifications' => $product->specifications->map(function($spec) {
                        return [
                            'name' => $spec->name,
                            'value' => $spec->value
                        ];
                    })
                ];
            });

        return Inertia::render('ClientSide/ComponentSelection', [
            'products' => $products,
            'componentType' => $componentType
        ]);
    }
}

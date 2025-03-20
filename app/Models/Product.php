<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'description',
        'product_images',
        'price',
        'stock',
        'warranty',
    ];

    protected $casts = [
        'product_images' => 'array',
    ];

    public function scopeFilter($query, array $filters)
    {
        // Search filter
        if ($filters['search'] ?? false) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('category', function ($q) use ($filters) {
                      $q->where('name', 'like', '%' . $filters['search'] . '%');
                  })
                  ->orWhereHas('brand', function ($q) use ($filters) {
                      $q->where('name', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        // Price Range filter
        if ($filters['priceRange'] ?? false) {
            $query->whereBetween('price', $filters['priceRange']);
        }

        // In Stock filter
        if ($filters['inStock'] ?? false) {
            $query->where('stock', '>', 0);
        }

        // Brands filter
        if ($filters['brands'] ?? false) {
            $query->whereIn('brand_id', $filters['brands']);
        }

        // Categories filter
        if ($filters['categories'] ?? false) {
            $query->whereIn('category_id', $filters['categories']);
        }

        return $query;
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function specifications() {
        return $this->belongsToMany(Specification::class, 'product_specifications', 'product_id', 'spec_id')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function productSpecifications() {
        return $this->hasMany(ProductSpecification::class);
    }
}

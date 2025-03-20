<?php

namespace App\Models;

use App\Models\Specification;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'image',
    ];

    public function specifications() {
        return $this->hasMany(Specification::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}

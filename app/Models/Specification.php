<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function product() {
        return $this->belongsToMany(Product::class, 'product_specifications', 'spec_id', 'product_id')
        ->withPivot('value')
        ->withTimestamps();
    }
}

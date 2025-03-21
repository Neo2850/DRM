<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    /** @use HasFactory<\Database\Factories\ProductVariantsFactory> */
    use HasFactory;
    protected $fillable = [
        'product_id',
        'sizes',
        'kinds'
    ];

    protected $casts = [
        'sizes' => 'string',
        'kinds' => 'string'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use App\Models\Orders;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

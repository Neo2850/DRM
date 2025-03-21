<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    /** @use HasFactory<\Database\Factories\PromotionsFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'type',
        'discount',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'discount' => 'integer',
    ];
}

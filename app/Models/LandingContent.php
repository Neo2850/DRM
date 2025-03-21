<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    /** @use HasFactory<\Database\Factories\LandingContentFactory> */
    use HasFactory;
    protected $fillable = [
        'hero',
        'description',
        'image',
        'is_active',
    ];
}

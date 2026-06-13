<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'description',
        'features',
        'is_recommended'
    ];

    protected $casts = [
        'features' => 'array',
        'is_recommended' => 'boolean'
    ];
}

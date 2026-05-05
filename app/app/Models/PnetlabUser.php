<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PnetlabUser extends Model
{
    /** @use HasFactory<\Database\Factories\PnetlabUserFactory> */
    use HasFactory;

    protected $fillable = [
        'username',
        'name',
        'email',
        'pod',
        'role',
        'expired_time',
    ];

    protected $casts = [
        'expired_time' => 'datetime',
    ];
}

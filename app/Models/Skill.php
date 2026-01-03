<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'items',
        'sort_order',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}

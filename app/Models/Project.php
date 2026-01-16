<?php

namespace App\Models;

use App\Traits\ClearsHomepageCache;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, LogsActivity, ClearsHomepageCache;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'tags',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}

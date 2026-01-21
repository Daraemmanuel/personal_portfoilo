<?php

namespace App\Models;

use App\Traits\ClearsHomepageCache;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'is_archived',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    protected static function booted()
    {
        static::deleting(function (Project $project) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
        });
    }
}

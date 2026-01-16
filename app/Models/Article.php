<?php

namespace App\Models;

use App\Traits\ClearsHomepageCache;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, LogsActivity, ClearsHomepageCache;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'tags',
        'published_at',
        'views',
        'is_featured',
        'series',
        'series_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected $appends = ['featured_image_url', 'reading_time'];

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getReadingTimeAttribute(): int
    {
        // Average reading speed: 200-250 words per minute
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($wordCount / 200));
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopePopular($query, int $limit = 5)
    {
        return $query->published()
            ->orderBy('views', 'desc')
            ->limit($limit);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\ArticleComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(\App\Models\ArticleComment::class)->where('is_approved', true);
    }

    public function scopeRelated($query, Article $article, int $limit = 3)
    {
        return $query->published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                if ($article->category) {
                    $q->where('category', $article->category);
                }
                if ($article->tags) {
                    foreach ($article->tags as $tag) {
                        $q->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->orderBy('published_at', 'desc')
            ->limit($limit);
    }

    public function scopeInSeries($query, string $series)
    {
        return $query->where('series', $series)
            ->orderBy('series_order');
    }

    public function seriesArticles()
    {
        if (!$this->series) {
            return collect();
        }

        return static::published()
            ->inSeries($this->series)
            ->where('id', '!=', $this->id)
            ->get();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }
}

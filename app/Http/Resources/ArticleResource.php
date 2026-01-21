<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs(['api.articles.show', 'api.v1.article']), $this->content),
            'featured_image_url' => $this->featured_image_url,
            'category' => $this->category,
            'tags' => $this->tags,
            'published_at' => $this->published_at?->toIso8601String(),
            'views' => $this->views,
            'is_featured' => $this->is_featured,
            'reading_time' => $this->reading_time,
            'series' => $this->series,
            'series_order' => $this->series_order,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}


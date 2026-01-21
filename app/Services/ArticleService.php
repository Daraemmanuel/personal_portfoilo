<?php

namespace App\Services;

use App\Models\Article;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArticleService
{
    use ClearsHomepageCache;

    /**
     * Create a new article.
     */
    public function create(array $data, ?UploadedFile $image = null): Article
    {
        if ($image) {
            $data['featured_image'] = $this->storeImage($image);
        }

        $article = Article::create($data);
        $this->clearCache('articles');

        return $article;
    }

    /**
     * Update an existing article.
     */
    public function update(Article $article, array $data, ?UploadedFile $image = null): Article
    {
        if ($image) {
            // Delete old image if exists
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $data['featured_image'] = $this->storeImage($image);
        }

        $article->update($data);
        $this->clearCache('articles');

        return $article->fresh();
    }

    /**
     * Delete an article.
     */
    public function delete(Article $article): bool
    {
        // Delete featured image if exists
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $deleted = $article->delete();
        $this->clearCache('articles');

        return $deleted;
    }

    /**
     * Store article featured image.
     */
    private function storeImage(UploadedFile $file): string
    {
        return $file->store('articles', 'public');
    }
}


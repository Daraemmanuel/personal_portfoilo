<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsHomepageCache
{
    /**
     * Clear all homepage caches using cache tags
     */
    protected function clearHomepageCache(): void
    {
        // Use cache tags for better invalidation
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggedCache) {
            Cache::tags(['homepage'])->flush();
            Cache::tags(['articles'])->flush();
        } else {
            // Fallback for cache drivers that don't support tags (like file cache)
            Cache::forget('home.projects');
            Cache::forget('home.skills');
            Cache::forget('home.experiences');
            Cache::forget('home.testimonials');
            Cache::forget('articles.categories');
            Cache::forget('articles.tags');
            Cache::forget('articles.popular');
        }
    }

    /**
     * Clear specific homepage cache
     */
    protected function clearCache(string $key): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggedCache) {
            // Use tags for targeted cache clearing
            Cache::tags(["home.{$key}"])->flush();
            
            // Also clear article caches if articles are modified
            if (in_array($key, ['articles', 'projects'])) {
                Cache::tags(['articles'])->flush();
            }
        } else {
            // Fallback for cache drivers that don't support tags
            Cache::forget("home.{$key}");
            
            if (in_array($key, ['articles', 'projects'])) {
                Cache::forget('articles.categories');
                Cache::forget('articles.tags');
                Cache::forget('articles.popular');
            }
        }
    }
}


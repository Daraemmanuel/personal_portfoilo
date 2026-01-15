<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsHomepageCache
{
    /**
     * Clear all homepage caches
     */
    protected function clearHomepageCache(): void
    {
        Cache::forget('home.projects');
        Cache::forget('home.skills');
        Cache::forget('home.experiences');
        Cache::forget('home.testimonials');
    }

    /**
     * Clear specific homepage cache
     */
    protected function clearCache(string $key): void
    {
        Cache::forget("home.{$key}");
    }
}


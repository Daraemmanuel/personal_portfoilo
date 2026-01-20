<?php

// Public routes (homepage, articles, projects, etc.)
require __DIR__.'/public.php';

// Feeds (sitemap, RSS)
require __DIR__.'/feeds.php';

// Admin routes
require __DIR__.'/admin.php';

// API routes
require __DIR__.'/api.php';

// Settings routes (Fortify authentication)
require __DIR__.'/settings.php';

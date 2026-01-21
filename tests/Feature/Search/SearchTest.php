<?php

use App\Models\Article;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('search returns relevant articles', function () {
    Article::factory()->create([
        'title' => 'Laravel Best Practices',
        'published_at' => now(),
    ]);
    Article::factory()->create([
        'title' => 'Vue.js Tutorial',
        'published_at' => now(),
    ]);

    $response = $this->get(route('search', ['q' => 'Laravel']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Search/Results')
        ->has('articles', 1)
        ->where('articles.0.title', 'Laravel Best Practices')
    );
});

test('search returns relevant projects', function () {
    Project::factory()->create([
        'title' => 'E-commerce Platform',
        'description' => 'Built with Laravel and Vue.js',
        'is_archived' => false,
    ]);
    Project::factory()->create([
        'title' => 'Mobile App',
        'description' => 'React Native application',
        'is_archived' => false,
    ]);

    $response = $this->get(route('search', ['q' => 'Laravel', 'type' => 'projects']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Search/Results')
        ->has('projects', 1)
        ->where('projects.0.title', 'E-commerce Platform')
    );
});

test('search returns empty results for no matches', function () {
    Article::factory()->create([
        'title' => 'Laravel Tutorial',
        'published_at' => now(),
    ]);

    $response = $this->get(route('search', ['q' => 'Nonexistent']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Search/Results')
        ->has('articles', 0)
    );
});

test('search handles empty query', function () {
    $response = $this->get(route('search', ['q' => '']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Search/Results')
        ->has('articles', 0)
        ->has('projects', 0)
    );
});

test('search prioritizes title matches', function () {
    Article::factory()->create([
        'title' => 'Laravel Framework',
        'excerpt' => 'Introduction to PHP',
        'published_at' => now(),
    ]);
    Article::factory()->create([
        'title' => 'PHP Introduction',
        'excerpt' => 'Learn Laravel framework',
        'published_at' => now(),
    ]);

    $response = $this->get(route('search', ['q' => 'Laravel']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Search/Results')
        ->has('articles', 2)
        ->where('articles.0.title', 'Laravel Framework') // Title match should come first
    );
});


<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'email' => config('portfolio.admin_email', 'admin@example.com'),
    ]);
    
    $this->user = User::factory()->create([
        'email' => 'user@example.com',
    ]);
});

test('admin can view articles index', function () {
    actingAs($this->admin)
        ->get(route('admin.articles.index'))
        ->assertOk();
});

test('non-admin cannot access articles index', function () {
    actingAs($this->user)
        ->get(route('admin.articles.index'))
        ->assertForbidden();
});

test('admin can create article', function () {
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [
            'title' => 'Test Article',
            'slug' => 'test-article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'category' => 'Technology',
            'tags' => ['laravel', 'vue'],
            'is_featured' => false,
        ])
        ->assertRedirect(route('admin.articles.index'));
    
    assertDatabaseHas('articles', [
        'title' => 'Test Article',
        'slug' => 'test-article',
    ]);
});

test('non-admin cannot create article', function () {
    actingAs($this->user)
        ->post(route('admin.articles.store'), [
            'title' => 'Test Article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
        ])
        ->assertForbidden();
});

test('admin can update article', function () {
    $article = Article::factory()->create();
    
    actingAs($this->admin)
        ->put(route('admin.articles.update', $article), [
            'title' => 'Updated Title',
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
        ])
        ->assertRedirect(route('admin.articles.index'));
    
    assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => 'Updated Title',
    ]);
});

test('non-admin cannot update article', function () {
    $article = Article::factory()->create();
    
    actingAs($this->user)
        ->put(route('admin.articles.update', $article), [
            'title' => 'Updated Title',
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
        ])
        ->assertForbidden();
});

test('admin can delete article', function () {
    $article = Article::factory()->create();
    
    actingAs($this->admin)
        ->delete(route('admin.articles.destroy', $article))
        ->assertRedirect(route('admin.articles.index'));
    
    assertDatabaseMissing('articles', [
        'id' => $article->id,
    ]);
});

test('non-admin cannot delete article', function () {
    $article = Article::factory()->create();
    
    actingAs($this->user)
        ->delete(route('admin.articles.destroy', $article))
        ->assertForbidden();
});

test('article creation requires valid data', function () {
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [])
        ->assertSessionHasErrors(['title', 'excerpt', 'content']);
});

test('article slug must be unique', function () {
    $existing = Article::factory()->create(['slug' => 'existing-slug']);
    
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [
            'title' => 'New Article',
            'slug' => 'existing-slug',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
        ])
        ->assertSessionHasErrors(['slug']);
});


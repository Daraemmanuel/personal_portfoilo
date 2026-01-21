<?php

use App\Models\Article;
use App\Models\Project;
use App\Models\User;

use function Pest\Laravel\actingAs;
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

test('admin can access dashboard', function () {
    actingAs($this->admin)
        ->get(route('dashboard'))
        ->assertOk();
});

test('non-admin cannot access dashboard', function () {
    actingAs($this->user)
        ->get(route('dashboard'))
        ->assertForbidden();
});

test('admin can access admin routes', function () {
    actingAs($this->admin)
        ->get(route('admin.articles.index'))
        ->assertOk();
    
    actingAs($this->admin)
        ->get(route('admin.projects.index'))
        ->assertOk();
    
    actingAs($this->admin)
        ->get(route('admin.skills.index'))
        ->assertOk();
});

test('non-admin cannot access admin routes', function () {
    actingAs($this->user)
        ->get(route('admin.articles.index'))
        ->assertForbidden();
    
    actingAs($this->user)
        ->get(route('admin.projects.index'))
        ->assertForbidden();
    
    actingAs($this->user)
        ->get(route('admin.skills.index'))
        ->assertForbidden();
});

test('admin can perform CRUD operations', function () {
    $article = Article::factory()->create();
    
    // Create
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [
            'title' => 'New Article',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
        ])
        ->assertRedirect();
    
    // Update
    actingAs($this->admin)
        ->put(route('admin.articles.update', $article), [
            'title' => 'Updated',
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
        ])
        ->assertRedirect();
    
    // Delete
    actingAs($this->admin)
        ->delete(route('admin.articles.destroy', $article))
        ->assertRedirect();
});

test('non-admin cannot perform CRUD operations', function () {
    $article = Article::factory()->create();
    
    // Create
    actingAs($this->user)
        ->post(route('admin.articles.store'), [
            'title' => 'New Article',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
        ])
        ->assertForbidden();
    
    // Update
    actingAs($this->user)
        ->put(route('admin.articles.update', $article), [
            'title' => 'Updated',
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
        ])
        ->assertForbidden();
    
    // Delete
    actingAs($this->user)
        ->delete(route('admin.articles.destroy', $article))
        ->assertForbidden();
});

test('isAdmin method works correctly', function () {
    expect($this->admin->isAdmin())->toBeTrue();
    expect($this->user->isAdmin())->toBeFalse();
});


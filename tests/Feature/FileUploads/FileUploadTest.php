<?php

use App\Models\Article;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    Storage::fake('public');
    
    $this->admin = User::factory()->create([
        'email' => config('portfolio.admin_email', 'admin@example.com'),
    ]);
});

test('admin can upload article featured image', function () {
    $image = UploadedFile::fake()->image('article.jpg', 800, 600);
    
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [
            'title' => 'Test Article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'featured_image' => $image,
        ])
        ->assertRedirect();
    
    Storage::disk('public')->assertExists('articles/' . $image->hashName());
    
    assertDatabaseHas('articles', [
        'title' => 'Test Article',
    ]);
});

test('admin can upload project image', function () {
    $image = UploadedFile::fake()->image('project.jpg', 800, 600);
    
    actingAs($this->admin)
        ->post(route('admin.projects.store'), [
            'title' => 'Test Project',
            'description' => 'Test description',
            'image' => $image,
        ])
        ->assertRedirect();
    
    Storage::disk('public')->assertExists('projects/' . $image->hashName());
    
    assertDatabaseHas('projects', [
        'title' => 'Test Project',
    ]);
});

test('file upload validates image type', function () {
    $file = UploadedFile::fake()->create('document.pdf', 1000);
    
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [
            'title' => 'Test Article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'featured_image' => $file,
        ])
        ->assertSessionHasErrors(['featured_image']);
});

test('file upload validates file size', function () {
    $image = UploadedFile::fake()->image('large.jpg')->size(5000); // 5MB
    
    actingAs($this->admin)
        ->post(route('admin.articles.store'), [
            'title' => 'Test Article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'featured_image' => $image,
        ])
        ->assertSessionHasErrors(['featured_image']);
});

test('old image is deleted when updating article', function () {
    $article = Article::factory()->create([
        'featured_image' => 'articles/old-image.jpg',
    ]);
    
    Storage::disk('public')->put('articles/old-image.jpg', 'fake content');
    
    $newImage = UploadedFile::fake()->image('new-article.jpg');
    
    actingAs($this->admin)
        ->put(route('admin.articles.update', $article), [
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
            'featured_image' => $newImage,
        ])
        ->assertRedirect();
    
    Storage::disk('public')->assertMissing('articles/old-image.jpg');
    Storage::disk('public')->assertExists('articles/' . $newImage->hashName());
});


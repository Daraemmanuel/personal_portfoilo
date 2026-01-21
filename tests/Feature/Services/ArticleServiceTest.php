<?php

use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->service = new ArticleService();
});

test('service can create article without image', function () {
    $data = [
        'title' => 'Test Article',
        'slug' => 'test-article',
        'excerpt' => 'Test excerpt',
        'content' => 'Test content',
    ];

    $article = $this->service->create($data);

    expect($article)->toBeInstanceOf(Article::class);
    $this->assertDatabaseHas('articles', ['title' => 'Test Article']);
});

test('service can create article with image', function () {
    $image = UploadedFile::fake()->image('article.jpg');
    $data = [
        'title' => 'Test Article',
        'slug' => 'test-article',
        'excerpt' => 'Test excerpt',
        'content' => 'Test content',
    ];

    $article = $this->service->create($data, $image);

    expect($article->featured_image)->not->toBeNull();
    Storage::disk('public')->assertExists($article->featured_image);
});

test('service can update article', function () {
    $article = Article::factory()->create();
    $data = ['title' => 'Updated Title'];

    $updated = $this->service->update($article, $data);

    expect($updated->title)->toBe('Updated Title');
    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
        'title' => 'Updated Title',
    ]);
});

test('service deletes old image when updating with new image', function () {
    $article = Article::factory()->create([
        'featured_image' => 'articles/old-image.jpg',
    ]);
    
    Storage::disk('public')->put('articles/old-image.jpg', 'fake content');
    
    $newImage = UploadedFile::fake()->image('new-article.jpg');
    $data = ['title' => $article->title];

    $this->service->update($article, $data, $newImage);

    Storage::disk('public')->assertMissing('articles/old-image.jpg');
    Storage::disk('public')->assertExists('articles/' . $newImage->hashName());
});

test('service can delete article', function () {
    $article = Article::factory()->create();

    $result = $this->service->delete($article);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('articles', ['id' => $article->id]);
});

test('service deletes image when deleting article', function () {
    $article = Article::factory()->create([
        'featured_image' => 'articles/test-image.jpg',
    ]);
    
    Storage::disk('public')->put('articles/test-image.jpg', 'fake content');

    $this->service->delete($article);

    Storage::disk('public')->assertMissing('articles/test-image.jpg');
});


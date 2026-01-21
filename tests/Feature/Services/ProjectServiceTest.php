<?php

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->service = new ProjectService();
});

test('service can create project without image', function () {
    $data = [
        'title' => 'Test Project',
        'description' => 'Test description',
    ];

    $project = $this->service->create($data);

    expect($project)->toBeInstanceOf(Project::class);
    $this->assertDatabaseHas('projects', ['title' => 'Test Project']);
});

test('service can create project with image', function () {
    $image = UploadedFile::fake()->image('project.jpg');
    $data = [
        'title' => 'Test Project',
        'description' => 'Test description',
    ];

    $project = $this->service->create($data, $image);

    expect($project->image)->not->toBeNull();
    Storage::disk('public')->assertExists($project->image);
});

test('service can update project', function () {
    $project = Project::factory()->create();
    $data = ['title' => 'Updated Title'];

    $updated = $this->service->update($project, $data);

    expect($updated->title)->toBe('Updated Title');
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'title' => 'Updated Title',
    ]);
});

test('service deletes old image when updating with new image', function () {
    $project = Project::factory()->create([
        'image' => 'projects/old-image.jpg',
    ]);
    
    Storage::disk('public')->put('projects/old-image.jpg', 'fake content');
    
    $newImage = UploadedFile::fake()->image('new-project.jpg');
    $data = ['title' => $project->title];

    $this->service->update($project, $data, $newImage);

    Storage::disk('public')->assertMissing('projects/old-image.jpg');
    Storage::disk('public')->assertExists('projects/' . $newImage->hashName());
});

test('service can delete project', function () {
    $project = Project::factory()->create();

    $result = $this->service->delete($project);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

test('service deletes image when deleting project', function () {
    $project = Project::factory()->create([
        'image' => 'projects/test-image.jpg',
    ]);
    
    Storage::disk('public')->put('projects/test-image.jpg', 'fake content');

    $this->service->delete($project);

    Storage::disk('public')->assertMissing('projects/test-image.jpg');
});


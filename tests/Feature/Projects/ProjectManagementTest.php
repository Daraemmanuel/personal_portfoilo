<?php

use App\Models\Project;
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

test('admin can view projects index', function () {
    actingAs($this->admin)
        ->get(route('admin.projects.index'))
        ->assertOk();
});

test('non-admin cannot access projects index', function () {
    actingAs($this->user)
        ->get(route('admin.projects.index'))
        ->assertForbidden();
});

test('admin can create project', function () {
    actingAs($this->admin)
        ->post(route('admin.projects.store'), [
            'title' => 'Test Project',
            'description' => 'Test description',
            'link' => 'https://example.com',
            'tags' => ['laravel', 'vue'],
            'sort_order' => 1,
            'is_archived' => false,
        ])
        ->assertRedirect(route('admin.projects.index'));
    
    assertDatabaseHas('projects', [
        'title' => 'Test Project',
    ]);
});

test('non-admin cannot create project', function () {
    actingAs($this->user)
        ->post(route('admin.projects.store'), [
            'title' => 'Test Project',
            'description' => 'Test description',
        ])
        ->assertForbidden();
});

test('admin can update project', function () {
    $project = Project::factory()->create();
    
    actingAs($this->admin)
        ->put(route('admin.projects.update', $project), [
            'title' => 'Updated Title',
            'description' => $project->description,
        ])
        ->assertRedirect(route('admin.projects.index'));
    
    assertDatabaseHas('projects', [
        'id' => $project->id,
        'title' => 'Updated Title',
    ]);
});

test('non-admin cannot update project', function () {
    $project = Project::factory()->create();
    
    actingAs($this->user)
        ->put(route('admin.projects.update', $project), [
            'title' => 'Updated Title',
            'description' => $project->description,
        ])
        ->assertForbidden();
});

test('admin can delete project', function () {
    $project = Project::factory()->create();
    
    actingAs($this->admin)
        ->delete(route('admin.projects.destroy', $project))
        ->assertRedirect(route('admin.projects.index'));
    
    assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);
});

test('non-admin cannot delete project', function () {
    $project = Project::factory()->create();
    
    actingAs($this->user)
        ->delete(route('admin.projects.destroy', $project))
        ->assertForbidden();
});

test('project creation requires valid data', function () {
    actingAs($this->admin)
        ->post(route('admin.projects.store'), [])
        ->assertSessionHasErrors(['title', 'description']);
});


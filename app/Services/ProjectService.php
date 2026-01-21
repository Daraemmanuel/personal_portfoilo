<?php

namespace App\Services;

use App\Models\Project;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    use ClearsHomepageCache;

    /**
     * Create a new project.
     */
    public function create(array $data, ?UploadedFile $image = null): Project
    {
        if ($image) {
            $data['image'] = $this->storeImage($image);
        }

        $project = Project::create($data);
        $this->clearCache('projects');

        return $project;
    }

    /**
     * Update an existing project.
     */
    public function update(Project $project, array $data, ?UploadedFile $image = null): Project
    {
        if ($image) {
            // Delete old image if exists
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $this->storeImage($image);
        }

        $project->update($data);
        $this->clearCache('projects');

        return $project->fresh();
    }

    /**
     * Delete a project.
     */
    public function delete(Project $project): bool
    {
        // Delete image if exists
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $deleted = $project->delete();
        $this->clearCache('projects');

        return $deleted;
    }

    /**
     * Store project image.
     */
    private function storeImage(UploadedFile $file): string
    {
        return $file->store('projects', 'public');
    }
}


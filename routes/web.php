<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Features::enabled(Features::registration()),
        'projects' => Project::orderBy('sort_order')->latest()->get(),
        'skills' => Skill::orderBy('sort_order')->get(),
        'experiences' => Experience::orderBy('sort_order')->latest()->get(),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'projects' => Project::count(),
            'skills' => Skill::count(),
            'experiences' => Experience::count(),
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('experiences', ExperienceController::class);
});

require __DIR__.'/settings.php';

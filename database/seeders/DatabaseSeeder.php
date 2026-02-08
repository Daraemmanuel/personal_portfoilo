<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\NewsletterSubscriber;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $adminEmail = config('portfolio.admin_email') ?? 'immanuelatwork@gmail.com';
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'DaraEmmanuel',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        $this->call(RolesAndPermissionsSeeder::class);

        // Skills
        $skills = [
            [
                'name' => 'Backend Development',
                'icon' => 'server',
                'items' => ['Laravel', 'PHP', 'MySQL', 'REST APIs', 'Database Design'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Frontend Development',
                'icon' => 'code',
                'items' => ['Vue.js', 'TypeScript', 'JavaScript', 'Tailwind CSS', 'Responsive Design'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Tools & Technologies',
                'icon' => 'wrench',
                'items' => ['Git', 'Docker', 'Linux', 'CI/CD', 'Testing'],
                'sort_order' => 3,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Projects
        $projects = [
            [
                'title' => 'E-Commerce Platform',
                'description' => 'A full-featured e-commerce platform built with Laravel and Vue.js, featuring payment integration, inventory management, and admin dashboard.',
                'link' => 'https://example.com',
                'sort_order' => 1,
            ],
            [
                'title' => 'Portfolio Website',
                'description' => 'Modern portfolio website with blog functionality, built using Laravel, Inertia.js, and Vue 3.',
                'link' => 'https://example.com',
                'sort_order' => 2,
            ],
            [
                'title' => 'Task Management App',
                'description' => 'Collaborative task management application with real-time updates, built with Laravel and Vue.js.',
                'link' => 'https://example.com',
                'sort_order' => 3,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // Experiences
        $experiences = [
            [
                'role' => 'Senior Full Stack Developer',
                'company' => 'Tech Company Inc.',
                'period' => '2022 — Present',
                'description' => 'Led development of multiple web applications using Laravel and Vue.js. Mentored junior developers and improved code quality standards.',
                'sort_order' => 1,
            ],
            [
                'role' => 'Full Stack Developer',
                'company' => 'Startup Co.',
                'period' => '2020 — 2021',
                'description' => 'Developed and maintained web applications. Collaborated with cross-functional teams to deliver high-quality software solutions.',
                'sort_order' => 2,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::create($experience);
        }

        // Testimonials
        $testimonials = [
            [
                'name' => 'John Doe',
                'role' => 'CEO',
                'company' => 'Company Inc.',
                'content' => 'Outstanding work! Delivered exactly what we needed on time and within budget.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'Product Manager',
                'company' => 'Tech Startup',
                'content' => 'Professional, reliable, and highly skilled. Would definitely work with again.',
                'sort_order' => 2,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // Articles
        $articles = [
            [
                'title' => 'Getting Started with Laravel for Modern Web Development',
                'slug' => 'getting-started-with-laravel-for-modern-web-development',
                'excerpt' => 'Laravel is one of the most popular PHP frameworks today. This article introduces Laravel, explains why developers love it, and shows how it simplifies modern web application development.',
                'content' => 'Laravel is a powerful PHP framework that has revolutionized web development. In this comprehensive guide, we\'ll explore the fundamentals of Laravel and how it can help you build modern web applications.

## What is Laravel?

Laravel is a free, open-source PHP web framework created by Taylor Otwell. It follows the model-view-controller (MVC) architectural pattern and provides an elegant syntax that makes web development a joy.

## Why Choose Laravel?

1. **Eloquent ORM**: Laravel\'s built-in ORM makes database interactions simple and intuitive.
2. **Blade Templating**: Powerful templating engine that makes views clean and maintainable.
3. **Artisan CLI**: Command-line interface that speeds up development tasks.
4. **Rich Ecosystem**: Extensive package ecosystem and active community.

## Getting Started

To get started with Laravel, you\'ll need:

- PHP 8.2 or higher
- Composer
- A web server (Apache/Nginx) or Laravel Sail

Install Laravel using Composer:

```bash
composer create-project laravel/laravel my-app
```

## Conclusion

Laravel provides everything you need to build modern web applications. Its elegant syntax, powerful features, and active community make it an excellent choice for developers.',
                'category' => 'Tutorial',
                'tags' => ['Laravel', 'PHP', 'Web Development'],
                'published_at' => now(),
                'is_featured' => true,
            ],
            [
                'title' => 'Building Modern UIs with Vue.js 3',
                'slug' => 'building-modern-uis-with-vue-js-3',
                'excerpt' => 'Learn how to build modern, reactive user interfaces using Vue.js 3 and the Composition API.',
                'content' => 'Vue.js 3 introduces the Composition API, which provides a more flexible way to organize component logic. This article explores how to leverage Vue 3\'s features to build modern user interfaces.

## The Composition API

The Composition API allows you to organize code by feature rather than by option type, making it easier to reuse logic across components.

## Key Features

- **Reactive References**: Use `ref()` and `reactive()` to create reactive state
- **Computed Properties**: Create derived state with `computed()`
- **Lifecycle Hooks**: Access component lifecycle with hooks like `onMounted()`

## Example

```javascript
import { ref, computed } from \'vue\'

export default {
  setup() {
    const count = ref(0)
    const doubled = computed(() => count.value * 2)
    
    return { count, doubled }
  }
}
```

Vue 3 makes building modern UIs more intuitive and maintainable.',
                'category' => 'Tutorial',
                'tags' => ['Vue.js', 'JavaScript', 'Frontend'],
                'published_at' => now()->subDays(5),
                'is_featured' => false,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }

        // Sample contact messages
        ContactMessage::create([
            'name' => 'Sample User',
            'email' => 'sample@example.com',
            'subject' => 'Inquiry about services',
            'message' => 'I would like to learn more about your development services.',
            'read_at' => null,
        ]);

        // Sample newsletter subscribers
        NewsletterSubscriber::create([
            'email' => 'subscriber@example.com',
            'is_active' => true,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}

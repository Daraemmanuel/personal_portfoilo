<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap';

    public function handle()
    {
        $projects = Project::select('id', 'updated_at')->get();
        $articles = Article::published()->select('slug', 'updated_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . route('home') . '</loc>' . "\n";
        $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>weekly</changefreq>' . "\n";
        $xml .= '    <priority>1.0</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        // Articles index
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . route('articles.index') . '</loc>' . "\n";
        $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>daily</changefreq>' . "\n";
        $xml .= '    <priority>0.9</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        // Projects
        foreach ($projects as $project) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route('projects.show', $project->id) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $project->updated_at->toAtomString() . '</lastmod>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <priority>0.8</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        // Articles
        foreach ($articles as $article) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . route('articles.show', $article->slug) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $article->updated_at->toAtomString() . '</lastmod>' . "\n";
            $xml .= '    <changefreq>weekly</changefreq>' . "\n";
            $xml .= '    <priority>0.7</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        File::put(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated successfully at public/sitemap.xml');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate the XML sitemap.
     */
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(12), function () {
            $projects = Project::select('id', 'updated_at')->get();
            $articles = Article::published()
                ->select('slug', 'updated_at')
                ->get();

            $lines = [];
            $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            // Helper to escape XML values
            $escape = static function (string $value): string {
                return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
            };

            // Homepage
            $lines[] = '  <url>';
            $lines[] = '    <loc>'.$escape(route('home')).'</loc>';
            $lines[] = '    <lastmod>'.now()->toAtomString().'</lastmod>';
            $lines[] = '    <changefreq>weekly</changefreq>';
            $lines[] = '    <priority>1.0</priority>';
            $lines[] = '  </url>';

            // Projects
            foreach ($projects as $project) {
                $lines[] = '  <url>';
                $lines[] = '    <loc>'.$escape(route('projects.show', $project->id)).'</loc>';
                $lines[] = '    <lastmod>'.$project->updated_at->toAtomString().'</lastmod>';
                $lines[] = '    <changefreq>monthly</changefreq>';
                $lines[] = '    <priority>0.8</priority>';
                $lines[] = '  </url>';
            }

            // Articles
            foreach ($articles as $article) {
                $lines[] = '  <url>';
                $lines[] = '    <loc>'.$escape(route('articles.show', $article->slug)).'</loc>';
                $lines[] = '    <lastmod>'.$article->updated_at->toAtomString().'</lastmod>';
                $lines[] = '    <changefreq>weekly</changefreq>';
                $lines[] = '    <priority>0.7</priority>';
                $lines[] = '  </url>';
            }

            $lines[] = '</urlset>';

            return implode("\n", $lines);
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}



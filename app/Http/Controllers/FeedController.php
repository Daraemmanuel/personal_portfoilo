<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    /**
     * Generate the RSS feed.
     */
    public function index()
    {
        $rss = Cache::remember('feed.rss', now()->addHours(12), function () {
            $articles = Article::published()
                ->orderBy('published_at', 'desc')
                ->limit(20)
                ->get();

            $escape = static function (string $value): string {
                return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
            };

            $lines = [];
            $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $lines[] = '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
            $lines[] = '  <channel>';
            $lines[] = '    <title>'.$escape('DaraEmmanuel Akinyode - Blog').'</title>';
            $lines[] = '    <link>'.$escape(route('home')).'</link>';
            $lines[] = '    <description>'.$escape('Latest articles from DaraEmmanuel Akinyode').'</description>';
            $lines[] = '    <language>en-us</language>';
            $lines[] = '    <atom:link href="'.$escape(route('feed')).'" rel="self" type="application/rss+xml" />';

            foreach ($articles as $article) {
                $url = route('articles.show', $article->slug);

                $lines[] = '    <item>';
                $lines[] = '      <title>'.$escape($article->title).'</title>';
                $lines[] = '      <link>'.$escape($url).'</link>';
                $lines[] = '      <description>'.$escape($article->excerpt).'</description>';
                $lines[] = '      <pubDate>'.optional($article->published_at)->toRssString().'</pubDate>';
                $lines[] = '      <guid isPermaLink="true">'.$escape($url).'</guid>';
                $lines[] = '    </item>';
            }

            $lines[] = '  </channel>';
            $lines[] = '</rss>';

            return implode("\n", $lines);
        });

        return response($rss, 200)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}



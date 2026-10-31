<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate pure native XML Sitemap from database
     */
    public function index(): Response
    {
        $urls = [];

        // Static core routes
        $staticRoutes = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('public.cloud_infra'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('public.products'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('public.internet'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('public.smart_iot'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('public.bluecom'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('public.tech_services'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('public.partner'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('public.about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('public.projects'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('public.news'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => route('public.contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('public.privacy_policy'), 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];

        foreach ($staticRoutes as $r) {
            $urls[] = [
                'loc' => $r['loc'],
                'lastmod' => now()->toIso8601String(),
                'changefreq' => $r['changefreq'],
                'priority' => $r['priority'],
            ];
        }

        // Active Products
        $products = Product::where('is_active', true)->get();
        foreach ($products as $p) {
            $urls[] = [
                'loc' => route('public.product_detail', ['slug' => $p->slug]),
                'lastmod' => $p->updated_at ? $p->updated_at->toIso8601String() : now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => $p->is_featured ? '0.9' : '0.8',
            ];
        }

        // Active News Posts
        $posts = Post::where('is_published', true)->get();
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => route('public.post_detail', ['slug' => $post->slug]),
                'lastmod' => $post->updated_at ? $post->updated_at->toIso8601String() : now()->toIso8601String(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        // Build XML output
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8')."</loc>\n";
            $xml .= '    <lastmod>'.$item['lastmod']."</lastmod>\n";
            $xml .= '    <changefreq>'.$item['changefreq']."</changefreq>\n";
            $xml .= '    <priority>'.$item['priority']."</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}

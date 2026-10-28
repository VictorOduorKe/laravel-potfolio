<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Project;
use App\Services\SeoService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate dynamic, canonical XML sitemap for public search engine indexing.
     */
    public function index(SeoService $seo): Response
    {
        $xml = Cache::remember('portfolio_xml_sitemap', 3600, function () use ($seo) {
            $publishedProjects = Project::where('is_published', true)
                ->orderBy('updated_at', 'desc')
                ->get();

            $latestNews = News::where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->first();

            $latestProjectUpdate = $publishedProjects->first()?->updated_at;
            $siteLastMod = $latestProjectUpdate ?? now();

            return view('sitemap', [
                'seo' => $seo,
                'projects' => $publishedProjects,
                'latestNews' => $latestNews,
                'siteLastMod' => $siteLastMod,
            ])->render();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex', // standard convention for the sitemap file itself so it isn't shown as a search result snippet
        ]);
    }
}

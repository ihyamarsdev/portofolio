<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap for search engine indexing.
     */
    public function index(): Response
    {
        $baseUrl = 'https://ihyamars.my.id';

        $staticUrls = [
            ['url' => $baseUrl.'/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['url' => $baseUrl.'/projects', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['url' => $baseUrl.'/about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['url' => $baseUrl.'/certificates', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['url' => $baseUrl.'/blog', 'changefreq' => 'daily', 'priority' => '0.8'],
            ['url' => $baseUrl.'/contact', 'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        $projects = Project::query()->latest()->get();
        $projectUrls = $projects->map(function ($project) use ($baseUrl) {
            return [
                'url' => $baseUrl.'/projects/'.$project->slug,
                'lastmod' => $project->updated_at ? $project->updated_at->toAtomString() : now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        });

        $posts = Post::published()->latest('published_at')->get();
        $postUrls = $posts->map(function ($post) use ($baseUrl) {
            return [
                'url' => $baseUrl.'/blog/'.$post->slug,
                'lastmod' => $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        });

        $allUrls = array_merge($staticUrls, $projectUrls->toArray(), $postUrls->toArray());

        $xml = view('sitemap', ['urls' => $allUrls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}

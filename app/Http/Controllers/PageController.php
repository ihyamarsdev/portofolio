<?php

namespace App\Http\Controllers;

use App\Services\PortfolioCacheService;

class PageController extends Controller
{
    public function __construct(private readonly PortfolioCacheService $cache) {}

    public function home()
    {
        return view('pages.home', $this->cache->home());
    }

    public function projects()
    {
        return view('pages.projects', $this->cache->projects());
    }

    public function projectShow(string $slug)
    {
        return view('pages.projects.show', $this->cache->projectShow($slug));
    }

    public function about()
    {
        return view('pages.about', $this->cache->about());
    }

    public function certificates()
    {
        return view('pages.certificates', $this->cache->certificates());
    }

    public function blog()
    {
        return view('pages.blog.index', $this->cache->blog());
    }

    public function blogShow(string $slug)
    {
        return view('pages.blog.show', $this->cache->blogShow($slug, auth()->check()));
    }

    public function contact()
    {
        return view('pages.contact', $this->cache->contact());
    }
}

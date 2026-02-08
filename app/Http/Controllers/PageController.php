<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PageController extends Controller
{
    private function getProfile()
    {
        return \App\Models\Profile::where('is_primary', true)->first();
    }

    public function home()
    {
        $profile = $this->getProfile();
        $homeSettings = \App\Models\HomeSettings::getSettings();

        return view('pages.home', compact('profile', 'homeSettings'));
    }

    public function projects()
    {
        $homeSettings = \App\Models\HomeSettings::getSettings();

        return view('pages.projects', compact('homeSettings'));
    }

    public function about()
    {
        $profile = $this->getProfile();
        $homeSettings = \App\Models\HomeSettings::getSettings();

        return view('pages.about', compact('profile', 'homeSettings'));
    }

    public function certificates()
    {
        $homeSettings = \App\Models\HomeSettings::getSettings();

        return view('pages.certificates', compact('homeSettings'));
    }

    public function blog()
    {
        $homeSettings = \App\Models\HomeSettings::getSettings();
        $posts = Post::published()->latest('published_at')->paginate(9);

        return view('pages.blog.index', compact('homeSettings', 'posts'));
    }

    public function blogShow(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        $homeSettings = \App\Models\HomeSettings::getSettings();

        return view('pages.blog.show', compact('post', 'homeSettings'));
    }

    public function contact()
    {
        $profile = $this->getProfile();
        $homeSettings = \App\Models\HomeSettings::getSettings();

        return view('pages.contact', compact('profile', 'homeSettings'));
    }
}

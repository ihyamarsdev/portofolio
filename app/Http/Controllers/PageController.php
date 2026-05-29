<?php

namespace App\Http\Controllers;

use App\Models\HomeSettings;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;

class PageController extends Controller
{
    private function getProfile()
    {
        return Profile::where('is_primary', true)->first();
    }

    public function home()
    {
        $profile = $this->getProfile();
        $homeSettings = HomeSettings::getSettings();

        return view('pages.home', compact('profile', 'homeSettings'));
    }

    public function projects()
    {
        $homeSettings = HomeSettings::getSettings();

        return view('pages.projects', compact('homeSettings'));
    }

    public function projectShow(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $homeSettings = HomeSettings::getSettings();

        return view('pages.projects.show', compact('project', 'homeSettings'));
    }

    public function about()
    {
        $profile = $this->getProfile();
        $homeSettings = HomeSettings::getSettings();

        return view('pages.about', compact('profile', 'homeSettings'));
    }

    public function certificates()
    {
        $homeSettings = HomeSettings::getSettings();

        return view('pages.certificates', compact('homeSettings'));
    }

    public function blog()
    {
        $homeSettings = HomeSettings::getSettings();
        $posts = Post::published()->latest('published_at')->paginate(9);

        return view('pages.blog.index', compact('homeSettings', 'posts'));
    }

    public function blogShow(string $slug)
    {
        $query = Post::where('slug', $slug);

        if (! auth()->check()) {
            $query->published();
        }

        $post = $query->firstOrFail();

        $homeSettings = HomeSettings::getSettings();

        return view('pages.blog.show', compact('post', 'homeSettings'));
    }

    public function contact()
    {
        $profile = $this->getProfile();
        $homeSettings = HomeSettings::getSettings();

        return view('pages.contact', compact('profile', 'homeSettings'));
    }
}

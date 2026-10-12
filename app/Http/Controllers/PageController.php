<?php

namespace App\Http\Controllers;

use App\Models\HomeSettings;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stack;

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
        $stacks = Stack::orderBy('name')->get();

        return view('pages.home', compact('profile', 'homeSettings', 'stacks'));
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

        $evidence = fn (Skill $skill): int => $skill->projects->count() + $skill->experiences->count();

        $skills = Skill::with(['category', 'projects', 'experiences'])->get()
            ->sortBy([
                fn (Skill $a, Skill $b) => $evidence($b) <=> $evidence($a),
                fn (Skill $a, Skill $b) => (float) $b->years_experience <=> (float) $a->years_experience,
                fn (Skill $a, Skill $b) => strcmp($a->name, $b->name),
            ])->values();

        $skillGroups = $skills
            ->groupBy(fn (Skill $skill) => $skill->category?->name ?? __('messages.about.other_category'))
            ->sortKeys()
            ->sortByDesc(fn ($group) => $group->sum($evidence));

        $coreSkills = $skills->filter(fn (Skill $skill) => $evidence($skill) > 0)->take(6);

        return view('pages.about', compact('profile', 'homeSettings', 'skills', 'skillGroups', 'coreSkills'));
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

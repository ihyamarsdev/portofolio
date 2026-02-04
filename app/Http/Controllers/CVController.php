<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CVController extends Controller
{
    public function download()
    {
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $pdf = Pdf::loadView('cv.ats', compact('profile', 'experiences', 'educations', 'skills'));

        return $pdf->download('cv-' . ($profile->name ?? 'ats') . '.pdf');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CVAnalysis extends Model
{
    protected $table = 'cv_analyses';

    protected $fillable = [
        'profile_id',
        'ats_score',
        'format_score',
        'about_me_score',
        'keywords_found',
        'keywords_missing',
        'suggestions',
        'industry_match',
        'summary',
        'job_title_targeted',
        'job_match_score',
    ];

    protected $casts = [
        'keywords_found' => 'array',
        'keywords_missing' => 'array',
        'suggestions' => 'array',
        'industry_match' => 'array',
        'ats_score' => 'integer',
        'format_score' => 'integer',
        'about_me_score' => 'integer',
        'job_match_score' => 'integer',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}

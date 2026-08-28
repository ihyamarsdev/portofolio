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
        'impact_score',
        'role_alignment_score',
        'security_score',
        'reliability_score',
        'cloud_score',
        'keywords_found',
        'keywords_missing',
        'weak_evidence',
        'suggestions',
        'strengths',
        'critical_gaps',
        'missing_metrics',
        'rewrite_priorities',
        'experience_review',
        'industry_match',
        'summary',
        'job_title_targeted',
        'target_seniority',
        'job_match_score',
        'job_match_detail',
        'job_match_formula_version',
    ];

    protected $casts = [
        'keywords_found' => 'array',
        'keywords_missing' => 'array',
        'weak_evidence' => 'array',
        'suggestions' => 'array',
        'strengths' => 'array',
        'critical_gaps' => 'array',
        'missing_metrics' => 'array',
        'rewrite_priorities' => 'array',
        'experience_review' => 'array',
        'industry_match' => 'array',
        'ats_score' => 'integer',
        'format_score' => 'integer',
        'about_me_score' => 'integer',
        'impact_score' => 'integer',
        'role_alignment_score' => 'integer',
        'security_score' => 'integer',
        'reliability_score' => 'integer',
        'cloud_score' => 'integer',
        'job_match_score' => 'integer',
        'job_match_detail' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}

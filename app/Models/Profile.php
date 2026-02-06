<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'biography',
        'avatar',
        'email',
        'phone',
        'location',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'website_url',
        'is_primary',
        'cv_template',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function cvAnalyses()
    {
        return $this->hasMany(CVAnalysis::class);
    }

    public function latestAnalysis()
    {
        return $this->hasOne(CVAnalysis::class)->latestOfMany();
    }
}

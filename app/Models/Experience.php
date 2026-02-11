<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Experience extends Model
{
    protected $fillable = [
        'company',
        'position',
        'employment_type',
        'location',
        'start_date',
        'end_date',
        'description',
        'achievements',
        'order',
    ];

    protected $casts = [
        'achievements' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'order' => 'integer',
    ];

    /**
     * Get the skills associated with this experience
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}

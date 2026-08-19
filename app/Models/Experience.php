<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Experience extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'company',
        'position',
        'location',
        'description',
    ];

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

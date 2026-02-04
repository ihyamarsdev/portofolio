<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'category',
        'proficiency',
        'years_experience',
        'stack_id',
    ];

    protected $casts = [
        'proficiency' => 'integer',
        'years_experience' => 'decimal:1',
    ];

    public function stack(): BelongsTo
    {
        return $this->belongsTo(Stack::class);
    }
}

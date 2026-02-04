<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

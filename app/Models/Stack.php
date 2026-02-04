<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stack extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'icon',
        'color',
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}

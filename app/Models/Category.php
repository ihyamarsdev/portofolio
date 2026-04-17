<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function stacks(): HasMany
    {
        return $this->hasMany(Stack::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}

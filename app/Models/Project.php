<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Symfony\Component\HttpFoundation\File\File;

class Project extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public array $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'repository_url',
        'live_url',
        'image_url',
        'architecture_diagram',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                // Only accept image files
                return in_array($file->mimeType, [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/webp',
                    'image/gif',
                ]);
            });

        $this->addMediaCollection('diagrams')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                // Accept images and SVG diagrams
                $allowedTypes = [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/webp',
                    'image/svg+xml',
                ];

                return in_array($file->mimeType, $allowedTypes);
            });
    }
}

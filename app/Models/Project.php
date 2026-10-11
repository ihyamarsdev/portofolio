<?php

namespace App\Models;

use App\Models\Concerns\HasOptimizedImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Project extends Model implements HasMedia
{
    use HasOptimizedImage, HasTranslations, InteractsWithMedia;

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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->performOnCollections('images')
            ->fit(Fit::Max, 1600, 1600)
            ->format('webp')
            ->quality(80)
            ->nonQueued();

        $this->addMediaConversion('card')
            ->performOnCollections('images')
            ->fit(Fit::Max, 800, 800)
            ->format('webp')
            ->quality(78)
            ->nonQueued();
    }

    /**
     * Image URL for public pages: the remote URL when set, otherwise the
     * optimized WebP conversion of the uploaded image.
     */
    public function imageSrc(string $conversion = 'webp'): string
    {
        return $this->image_url ?: $this->optimizedMediaUrl('images', $conversion);
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasOptimizedImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{
    use HasOptimizedImage, HasTranslations, InteractsWithMedia;

    public array $translatable = [
        'title',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            if ($post->status === 'published' && blank($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'featured_image_url',
        'content',
        'status',
        'category_id',
        'is_published',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->performOnCollections('featured_image')
            ->fit(Fit::Max, 1600, 1600)
            ->format('webp')
            ->quality(80)
            ->nonQueued();
    }

    /**
     * Featured image URL for public pages: the remote URL when set, otherwise
     * the optimized WebP conversion of the uploaded image.
     */
    public function imageSrc(string $conversion = 'webp'): string
    {
        return $this->featured_image_url ?: $this->optimizedMediaUrl('featured_image', $conversion);
    }
}

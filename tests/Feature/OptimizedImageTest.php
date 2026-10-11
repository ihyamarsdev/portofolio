<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OptimizedImageTest extends TestCase
{
    use RefreshDatabase;

    private string $sourceImage;

    protected function setUp(): void
    {
        parent::setUp();

        config(['media-library.disk_name' => 'public']);
        Storage::fake('public');

        $this->sourceImage = tempnam(sys_get_temp_dir(), 'img').'.jpg';
        $image = imagecreatetruecolor(2400, 1200);
        imagejpeg($image, $this->sourceImage, 90);
        imagedestroy($image);
    }

    protected function tearDown(): void
    {
        @unlink($this->sourceImage);

        parent::tearDown();
    }

    public function test_uploaded_project_image_is_served_as_downscaled_webp(): void
    {
        $project = Project::query()->create(['title' => 'API', 'slug' => 'api', 'description' => 'd']);
        $media = $project->addMedia($this->sourceImage)->preservingOriginal()->toMediaCollection('images');

        $this->assertStringEndsWith('-card.webp', $project->fresh()->imageSrc('card'));
        $this->assertStringEndsWith('-webp.webp', $project->fresh()->imageSrc());

        $size = getimagesize(Storage::disk('public')->path($media->getPathRelativeToRoot('card')));
        $this->assertSame(800, $size[0]);
    }

    public function test_small_images_are_not_upscaled(): void
    {
        $small = tempnam(sys_get_temp_dir(), 'img').'.jpg';
        $image = imagecreatetruecolor(500, 300);
        imagejpeg($image, $small, 90);
        imagedestroy($image);

        $post = Post::query()->create(['title' => 'Hello', 'slug' => 'hello', 'content' => 'c', 'status' => 'draft']);
        $media = $post->addMedia($small)->toMediaCollection('featured_image');

        $size = getimagesize(Storage::disk('public')->path($media->getPathRelativeToRoot('webp')));
        $this->assertSame(500, $size[0]);
    }

    public function test_falls_back_to_original_when_conversion_was_not_generated(): void
    {
        $post = Post::query()->create(['title' => 'Old', 'slug' => 'old', 'content' => 'c', 'status' => 'draft']);
        $media = $post->addMedia($this->sourceImage)->preservingOriginal()->toMediaCollection('featured_image');
        $media->forceFill(['generated_conversions' => []])->save();

        $this->assertSame($media->getUrl(), $post->fresh()->imageSrc());
    }

    public function test_remote_image_url_takes_precedence_and_missing_image_yields_empty_string(): void
    {
        $remote = Project::query()->create([
            'title' => 'Remote', 'slug' => 'remote', 'description' => 'd',
            'image_url' => 'https://example.com/a.jpg',
        ]);
        $none = Project::query()->create(['title' => 'None', 'slug' => 'none', 'description' => 'd']);

        $this->assertSame('https://example.com/a.jpg', $remote->imageSrc('card'));
        $this->assertSame('', $none->imageSrc('card'));
    }
}

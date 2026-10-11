<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportRemoteImagesCommandTest extends TestCase
{
    use RefreshDatabase;

    private string $sampleJpegData;

    protected function setUp(): void
    {
        parent::setUp();

        config(['media-library.disk_name' => 'public']);
        Storage::fake('public');

        // Create in-memory binary JPEG data
        $img = imagecreatetruecolor(1200, 800);
        $blue = imagecolorallocate($img, 30, 100, 200);
        imagefill($img, 0, 0, $blue);
        ob_start();
        imagejpeg($img, null, 85);
        $this->sampleJpegData = ob_get_clean();
        imagedestroy($img);
    }

    public function test_imports_remote_image_generates_webp_and_nulls_url(): void
    {
        $projectUrl = 'https://remote.example.com/images/k8s-platform.jpg';
        $postUrl = 'https://remote.example.com/images/blog-intro.jpg';

        Http::fake([
            $projectUrl => Http::response($this->sampleJpegData, 200, ['Content-Type' => 'image/jpeg']),
            $postUrl => Http::response($this->sampleJpegData, 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $project = Project::query()->create([
            'title' => 'Kubernetes Platform',
            'slug' => 'k8s-platform',
            'description' => 'A great platform',
            'image_url' => $projectUrl,
        ]);

        $post = Post::query()->create([
            'title' => 'Blog Intro',
            'slug' => 'blog-intro',
            'content' => 'Content here',
            'status' => 'published',
            'featured_image_url' => $postUrl,
        ]);

        $this->artisan('media:import-remote-images')
            ->assertExitCode(0);

        // Project checks
        $project->refresh();
        $this->assertNull($project->image_url);
        $this->assertTrue($project->hasMedia('images'));
        $projectMedia = $project->getFirstMedia('images');
        $this->assertNotNull($projectMedia);
        $this->assertTrue($projectMedia->hasGeneratedConversion('webp'));
        $this->assertStringEndsWith('-webp.webp', $project->imageSrc());

        // Post checks
        $post->refresh();
        $this->assertNull($post->featured_image_url);
        $this->assertTrue($post->hasMedia('featured_image'));
        $postMedia = $post->getFirstMedia('featured_image');
        $this->assertNotNull($postMedia);
        $this->assertTrue($postMedia->hasGeneratedConversion('webp'));
        $this->assertStringEndsWith('-webp.webp', $post->imageSrc());
    }

    public function test_skips_records_that_already_have_media(): void
    {
        $url = 'https://remote.example.com/images/existing.jpg';

        Http::fake([
            $url => Http::response($this->sampleJpegData, 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $project = Project::query()->create([
            'title' => 'Existing Media Project',
            'slug' => 'existing-media',
            'description' => 'Test project',
            'image_url' => $url,
        ]);

        // Add media beforehand
        $temp = tempnam(sys_get_temp_dir(), 'test_img');
        file_put_contents($temp, $this->sampleJpegData);
        $project->addMedia($temp)->toMediaCollection('images');

        $this->artisan('media:import-remote-images --model=projects')
            ->expectsOutputToContain('SKIPPED (has media)')
            ->assertExitCode(0);

        // Remote URL should remain untouched because media was not re-imported
        $project->refresh();
        $this->assertSame($url, $project->image_url);
        $this->assertCount(1, $project->getMedia('images'));
    }

    public function test_dry_run_writes_nothing(): void
    {
        $url = 'https://remote.example.com/images/dryrun.jpg';

        Http::fake([
            $url => Http::response($this->sampleJpegData, 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $project = Project::query()->create([
            'title' => 'Dry Run Project',
            'slug' => 'dry-run-project',
            'description' => 'Test project',
            'image_url' => $url,
        ]);

        $this->artisan('media:import-remote-images --dry-run')
            ->expectsOutputToContain('WOULD_IMPORT')
            ->expectsOutputToContain('Dry-run complete. No changes were made.')
            ->assertExitCode(0);

        Http::assertNothingSent();

        $project->refresh();
        $this->assertSame($url, $project->image_url);
        $this->assertFalse($project->hasMedia('images'));
    }

    public function test_failing_download_leaves_url_untouched_and_exits_non_zero(): void
    {
        $url = 'https://remote.example.com/images/nonexistent.jpg';

        Http::fake([
            $url => Http::response('Not Found', 404),
        ]);

        $project = Project::query()->create([
            'title' => 'Failing Project',
            'slug' => 'failing-project',
            'description' => 'Test project',
            'image_url' => $url,
        ]);

        $this->artisan('media:import-remote-images --model=projects')
            ->expectsOutputToContain('FAILED: HTTP request failed with status 404')
            ->assertExitCode(1);

        $project->refresh();
        $this->assertSame($url, $project->image_url);
        $this->assertFalse($project->hasMedia('images'));
    }

    public function test_non_image_response_is_rejected(): void
    {
        $url = 'https://remote.example.com/document.html';

        Http::fake([
            $url => Http::response('<html><body>Not an image</body></html>', 200, ['Content-Type' => 'text/html']),
        ]);

        $project = Project::query()->create([
            'title' => 'Html Project',
            'slug' => 'html-project',
            'description' => 'Test project',
            'image_url' => $url,
        ]);

        $this->artisan('media:import-remote-images --model=projects')
            ->expectsOutputToContain('Disallowed mime type')
            ->assertExitCode(1);

        $project->refresh();
        $this->assertSame($url, $project->image_url);
        $this->assertFalse($project->hasMedia('images'));
    }
}

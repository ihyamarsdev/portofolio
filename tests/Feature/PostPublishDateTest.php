<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPublishDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_post_uses_current_time_when_publish_date_is_empty(): void
    {
        $this->freezeTime();

        $post = Post::query()->create([
            'title' => 'DevOps Release Notes',
            'slug' => 'devops-release-notes',
            'content' => 'Release content',
            'status' => 'published',
        ]);

        $post->refresh();

        $this->assertNotNull($post->published_at);
        $this->assertSame(now()->format('Y-m-d H:i:s'), $post->published_at->format('Y-m-d H:i:s'));
    }

    public function test_draft_post_keeps_publish_date_empty(): void
    {
        $post = Post::query()->create([
            'title' => 'Draft Notes',
            'slug' => 'draft-notes',
            'content' => 'Draft content',
            'status' => 'draft',
        ]);

        $this->assertNull($post->published_at);
    }
}

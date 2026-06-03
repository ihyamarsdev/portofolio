<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Stack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Profile::factory()->create(['is_primary' => true]);

        // Create a Category, Stack, and Skill to link them
        $category = Category::factory()->create();
        $stack = Stack::create([
            'name' => 'VueJS',
            'icon' => '🟢',
            'category_id' => $category->id,
        ]);
        $skill = new Skill([
            'name' => 'VueJS Skill',
            'category_id' => $category->id,
            'proficiency' => 5,
            'years_experience' => 3.0,
        ]);
        $skill->stack_id = $stack->id;
        $skill->save();
        $skill->stacks()->attach($stack);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('stacks');
        $response->assertSee('VueJS');
    }
}

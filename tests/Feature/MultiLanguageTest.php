<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiLanguageTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_language_is_english(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Home');
        $response->assertSee('Projects');
    }

    public function test_can_switch_language_to_indonesian(): void
    {
        $response = $this->get('/lang/id');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'id');

        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Beranda');
        $homeResponse->assertSee('Proyek');
    }

    public function test_can_switch_language_to_english(): void
    {
        $this->withSession(['locale' => 'id']);

        $response = $this->get('/lang/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');

        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Home');
        $homeResponse->assertSee('Projects');
    }

    public function test_query_parameter_lang_sets_locale(): void
    {
        $response = $this->get('/?lang=id');

        $response->assertStatus(200);
        $response->assertSessionHas('locale', 'id');
        $response->assertSee('Beranda');
    }
}

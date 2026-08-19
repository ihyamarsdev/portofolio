<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_route_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('https://ihyamars.my.id');
        $response->assertSee('/projects');
        $response->assertSee('/about');
        $response->assertSee('/certificates');
        $response->assertSee('/blog');
        $response->assertSee('/contact');
    }

    public function test_homepage_contains_seo_meta_tags_and_schema(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('rel="canonical"', false);
        $response->assertSee('https://ihyamars.my.id', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:url"', false);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('"@type": "Person"', false);
        $response->assertSee('"@type": "WebSite"', false);
        $response->assertSee('Ihya Muhammad Adam Rakasurya');
    }
}

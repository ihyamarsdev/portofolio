<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostFormRicherEditorTest extends TestCase
{
    public function test_post_form_configures_richer_editor_plugins(): void
    {
        $postForm = file_get_contents(app_path('Filament/Resources/Posts/Schemas/PostForm.php'));

        $this->assertNotFalse($postForm);
        $this->assertStringContainsString('EmbedPlugin', $postForm);
        $this->assertStringContainsString('SourceCodePlugin', $postForm);
        $this->assertStringContainsString('EmojiPlugin', $postForm);
        $this->assertStringContainsString('FullScreenPlugin', $postForm);
        $this->assertStringContainsString('IdPlugin', $postForm);
        $this->assertStringContainsString('LinkPlugin', $postForm);
        $this->assertStringContainsString("'embed'", $postForm);
        $this->assertStringContainsString("'sourceCode'", $postForm);
        $this->assertStringContainsString("'fullscreen'", $postForm);
        $this->assertStringContainsString('->floatingToolbars([', $postForm);
        $this->assertStringContainsString('->columns(12)', $postForm);
        $this->assertStringContainsString("->columnSpan(['default' => 12, 'lg' => 8])", $postForm);
        $this->assertStringContainsString("->columnSpan(['default' => 12, 'lg' => 4])", $postForm);
        $this->assertStringContainsString("Section::make('Excerpt & SEO')", $postForm);
        $this->assertStringContainsString("Section::make('Publishing')", $postForm);
        $this->assertStringContainsString("Section::make('Category & Media')", $postForm);

        $this->assertTrue(class_exists('Awcodes\\RicherEditor\\Plugins\\EmbedPlugin'));
        $this->assertTrue(class_exists('Awcodes\\RicherEditor\\Plugins\\SourceCodePlugin'));
        $this->assertTrue(class_exists('Awcodes\\RicherEditor\\Plugins\\EmojiPlugin'));
        $this->assertTrue(class_exists('Awcodes\\RicherEditor\\Plugins\\FullScreenPlugin'));
        $this->assertTrue(class_exists('Awcodes\\RicherEditor\\Plugins\\IdPlugin'));
        $this->assertTrue(class_exists('Awcodes\\RicherEditor\\Plugins\\LinkPlugin'));
    }
}

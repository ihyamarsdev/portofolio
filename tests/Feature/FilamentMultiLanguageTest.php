<?php

namespace Tests\Feature;

use App\Models\HomeSettings;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentMultiLanguageTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_model_supports_en_and_id_translations(): void
    {
        $project = Project::create([
            'slug' => 'test-multilang-project',
            'title' => [
                'en' => 'Automated K8s Pipeline',
                'id' => 'Pipeline Automasi K8s',
            ],
            'description' => [
                'en' => 'Cloud infrastructure automation with Terraform.',
                'id' => 'Otomasi infrastruktur awan dengan Terraform.',
            ],
        ]);

        $this->app->setLocale('en');
        $this->assertEquals('Automated K8s Pipeline', $project->title);
        $this->assertEquals('Cloud infrastructure automation with Terraform.', $project->description);

        $this->app->setLocale('id');
        $this->assertEquals('Pipeline Automasi K8s', $project->title);
        $this->assertEquals('Otomasi infrastruktur awan dengan Terraform.', $project->description);
    }

    public function test_home_settings_supports_en_and_id_translations(): void
    {
        $settings = HomeSettings::getSettings();
        $settings->setTranslation('hero_greeting', 'en', 'Hi, I am a');
        $settings->setTranslation('hero_greeting', 'id', 'Halo, saya seorang');
        $settings->save();

        $this->app->setLocale('en');
        $this->assertEquals('Hi, I am a', $settings->hero_greeting);

        $this->app->setLocale('id');
        $this->assertEquals('Halo, saya seorang', $settings->hero_greeting);
    }
}

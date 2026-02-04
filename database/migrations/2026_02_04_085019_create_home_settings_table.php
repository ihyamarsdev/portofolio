<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();

            // Hero Section
            $table->string('hero_greeting')->default('Hi, I\'m a');
            $table->text('hero_description')->nullable();
            $table->string('availability_status')->default('Available for new opportunities');
            $table->boolean('availability_active')->default(true);

            // Terminal Commands
            $table->json('terminal_commands')->nullable();

            // Tech Stack Section
            $table->string('tech_stack_title')->default('Tech Stack');
            $table->string('tech_stack_description')->default('Tools and technologies I work with daily');

            // Featured Projects Section
            $table->string('featured_projects_title')->default('Featured Projects');
            $table->string('featured_projects_description')->default('Infrastructure and automation work I\'m proud of');

            // Infrastructure Section
            $table->string('infrastructure_title')->default('Live Infrastructure');
            $table->string('infrastructure_description')->default('How this portfolio is deployed and monitored');
            $table->json('infrastructure_services')->nullable();
            $table->string('infrastructure_status_text')->default('All services running');
            $table->string('infrastructure_status')->default('operational');

            // CTA Section
            $table->string('cta_title')->default('Let\'s Build Something Together');
            $table->text('cta_description')->nullable();
            $table->string('cta_primary_button_text')->default('Get In Touch');
            $table->string('cta_secondary_button_text')->default('Download CV');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};

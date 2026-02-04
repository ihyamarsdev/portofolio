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
        Schema::table('home_settings', function (Blueprint $table) {
            $table->after('cta_secondary_button_text', function (Blueprint $table) {
                $table->string('site_brand')->default('~/devops');
                $table->string('site_title')->default('DevOps Engineer');
                $table->text('meta_description')->nullable();
                $table->string('footer_text')->default('DevOps Portfolio. Built with Laravel & Filament.');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn(['site_brand', 'site_title', 'meta_description', 'footer_text']);
        });
    }
};

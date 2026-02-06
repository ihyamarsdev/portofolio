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
        Schema::create('cv_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->integer('ats_score');
            $table->integer('format_score');
            $table->json('keywords_found')->nullable();
            $table->json('keywords_missing')->nullable();
            $table->json('suggestions')->nullable();
            $table->json('industry_match')->nullable();
            $table->text('summary')->nullable();
            $table->string('job_title_targeted')->nullable();
            $table->integer('job_match_score')->nullable();
            $table->timestamps();

            $table->index('profile_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_analyses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('image_url')->nullable()->after('live_url');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->string('featured_image_url')->nullable()->after('excerpt');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn('image_url');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn('featured_image_url');
        });
    }
};

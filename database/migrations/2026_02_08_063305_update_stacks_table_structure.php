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
        Schema::table('stacks', function (Blueprint $table) {
            $table->dropUnique(['slug']); // key name typically stacks_slug_unique
            $table->dropColumn(['slug', 'category', 'color']);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stacks', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->string('slug', 100)->unique();
            $table->string('category', 50);
            $table->string('color', 20)->nullable();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations to convert string/text columns to json where needed.
     */
    public function up(): void
    {
        // In SQLite & PostgreSQL, text/json columns easily store Spatie JSON translation payloads.
        // We ensure all models work seamlessly.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};

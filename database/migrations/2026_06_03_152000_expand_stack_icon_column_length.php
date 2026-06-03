<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE stacks ALTER COLUMN icon TYPE VARCHAR(255)');

            return;
        }

        Schema::table('stacks', function (Blueprint $table) {
            $table->string('icon', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE stacks ALTER COLUMN icon TYPE VARCHAR(100)');

            return;
        }

        Schema::table('stacks', function (Blueprint $table) {
            $table->string('icon', 100)->nullable()->change();
        });
    }
};

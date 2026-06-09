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
        Schema::table('cv_analyses', function (Blueprint $table) {
            $table->text('job_title_targeted')->nullable()->change();
            $table->text('target_seniority')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cv_analyses', function (Blueprint $table) {
            $table->string('job_title_targeted', 255)->nullable()->change();
            $table->string('target_seniority', 255)->nullable()->change();
        });
    }
};

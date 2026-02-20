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
        Schema::create('service_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('url')->nullable();
            $table->string('status', 20)->default('operational'); // operational, degraded, down
            $table->timestamp('last_checked_at')->nullable();
            $table->decimal('uptime_percentage', 5, 2)->default(100);
            $table->integer('response_time_ms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_statuses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cv_analyses', function (Blueprint $table) {
            $table->integer('impact_score')->nullable()->after('about_me_score');
            $table->integer('role_alignment_score')->nullable()->after('impact_score');
            $table->integer('security_score')->nullable()->after('role_alignment_score');
            $table->integer('reliability_score')->nullable()->after('security_score');
            $table->integer('cloud_score')->nullable()->after('reliability_score');
            $table->string('target_seniority')->nullable()->after('job_title_targeted');
            $table->json('weak_evidence')->nullable()->after('keywords_missing');
            $table->json('strengths')->nullable()->after('suggestions');
            $table->json('critical_gaps')->nullable()->after('strengths');
            $table->json('missing_metrics')->nullable()->after('critical_gaps');
            $table->json('rewrite_priorities')->nullable()->after('missing_metrics');
            $table->json('experience_review')->nullable()->after('rewrite_priorities');
        });
    }

    public function down(): void
    {
        Schema::table('cv_analyses', function (Blueprint $table) {
            $table->dropColumn([
                'impact_score',
                'role_alignment_score',
                'security_score',
                'reliability_score',
                'cloud_score',
                'target_seniority',
                'weak_evidence',
                'strengths',
                'critical_gaps',
                'missing_metrics',
                'rewrite_priorities',
                'experience_review',
            ]);
        });
    }
};

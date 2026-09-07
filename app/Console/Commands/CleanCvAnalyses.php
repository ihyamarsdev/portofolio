<?php

namespace App\Console\Commands;

use App\Models\CVAnalysis;
use App\Support\AiStructuredOutputSanitizer;
use Illuminate\Console\Command;

class CleanCvAnalyses extends Command
{
    /**
     * List fields that hold string-array payloads produced by the AI.
     *
     * @var array<int, string>
     */
    protected const LIST_FIELDS = [
        'keywords_found',
        'keywords_missing',
        'weak_evidence',
        'suggestions',
        'strengths',
        'critical_gaps',
        'missing_metrics',
        'rewrite_priorities',
    ];

    /**
     * List fields that hold structured (associative/array) payloads.
     *
     * @var array<int, string>
     */
    protected const STRUCTURED_FIELDS = [
        'experience_review',
        'job_match_detail',
    ];

    protected $signature = 'cv:clean-analyses {--id= : Only clean a specific CV analysis ID}';

    protected $description = 'Strip reasoning/markdown artifacts from stored CV analysis results';

    public function handle(): int
    {
        $query = CVAnalysis::query();

        if ($id = $this->option('id')) {
            $query->whereKey((int) $id);
        }

        $analyses = $query->get();
        $cleaned = 0;

        foreach ($analyses as $analysis) {
            foreach (self::LIST_FIELDS as $field) {
                if (is_array($analysis->{$field})) {
                    $analysis->{$field} = AiStructuredOutputSanitizer::cleanList($analysis->{$field});
                }
            }

            foreach (self::STRUCTURED_FIELDS as $field) {
                if (! is_array($analysis->{$field})) {
                    continue;
                }

                $analysis->{$field} = $field === 'experience_review'
                    ? AiStructuredOutputSanitizer::normalizeReviewList($analysis->{$field})
                    : AiStructuredOutputSanitizer::sanitize($analysis->{$field});
            }

            foreach (['summary', 'job_title_targeted', 'target_seniority'] as $field) {
                if (is_string($analysis->{$field}) && $analysis->{$field} !== '') {
                    $analysis->{$field} = AiStructuredOutputSanitizer::cleanText($analysis->{$field});
                }
            }

            if (! $analysis->isDirty()) {
                continue;
            }

            $analysis->save();
            $cleaned++;
            $this->line("Cleaned analysis #{$analysis->id} ({$analysis->job_title_targeted})");
        }

        $this->info("Done. {$cleaned} of {$analyses->count()} analyses cleaned.");

        return self::SUCCESS;
    }
}

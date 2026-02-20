<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class KeywordService
{
    /**
     * Get all keywords from a category
     */
    public function getKeywords(string $category = 'devops'): array
    {
        return Cache::remember("keywords_{$category}", 3600, function () use ($category) {
            $path = storage_path("app/keywords/{$category}.json");

            if (! file_exists($path)) {
                return [];
            }

            $data = json_decode(file_get_contents($path), true);

            return $data['keywords'] ?? [];
        });
    }

    /**
     * Get all keywords as flat array
     */
    public function getAllKeywordsFlat(string $category = 'devops'): array
    {
        $keywords = $this->getKeywords($category);

        $flat = [];
        foreach ($keywords as $subcategory => $items) {
            $flat = array_merge($flat, $items);
        }

        return array_unique($flat);
    }

    /**
     * Get combined DevOps and Cloud keywords
     */
    public function getCombinedKeywords(): array
    {
        $devops = $this->getAllKeywordsFlat('devops');
        $cloud = $this->getAllKeywordsFlat('cloud');

        return array_unique(array_merge($devops, $cloud));
    }

    /**
     * Match keywords against content
     */
    public function matchKeywords(string $content, array $keywords): array
    {
        $found = [];
        $missing = [];

        foreach ($keywords as $keyword) {
            // Case-insensitive search
            if (stripos($content, $keyword) !== false) {
                $found[] = $keyword;
            } else {
                $missing[] = $keyword;
            }
        }

        return [
            'found' => $found,
            'missing' => $missing,
        ];
    }

    /**
     * Get keyword suggestions based on CV content and target role
     */
    public function getKeywordSuggestions(string $content, string $targetRole = 'devops'): array
    {
        $category = strtolower($targetRole) === 'cloud' ? 'cloud' : 'devops';
        $keywords = $this->getAllKeywordsFlat($category);

        $result = $this->matchKeywords($content, $keywords);

        // Prioritize missing keywords
        $priorityMissing = array_slice($result['missing'], 0, 20);

        return [
            'found' => $result['found'],
            'missing' => $result['missing'],
            'priority_suggestions' => $priorityMissing,
            'total_found' => count($result['found']),
            'total_missing' => count($result['missing']),
            'coverage_percentage' => count($keywords) > 0
                ? round((count($result['found']) / count($keywords)) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get category-specific keywords
     */
    public function getKeywordsByCategory(string $category, string $subcategory): array
    {
        $keywords = $this->getKeywords($category);

        return $keywords[$subcategory] ?? [];
    }

    /**
     * Get top missing keywords (most important to add)
     */
    public function getTopMissingKeywords(string $content, int $limit = 15): array
    {
        $devopsKeywords = $this->getKeywords('devops');
        $cloudKeywords = $this->getKeywords('cloud');

        $topMissing = [];

        // Priority categories (most important for ATS)
        $priorities = [
            'devops' => ['core_concepts', 'containerization_orchestration', 'ci_cd_tools', 'monitoring_logging'],
            'cloud' => ['aws_services', 'azure_services', 'core_concepts', 'security'],
        ];

        foreach ($priorities['devops'] as $subcategory) {
            if (isset($devopsKeywords[$subcategory])) {
                foreach ($devopsKeywords[$subcategory] as $keyword) {
                    if (stripos($content, $keyword) === false) {
                        $topMissing[] = $keyword;
                    }
                }
            }
        }

        foreach ($priorities['cloud'] as $subcategory) {
            if (isset($cloudKeywords[$subcategory])) {
                foreach ($cloudKeywords[$subcategory] as $keyword) {
                    if (stripos($content, $keyword) === false) {
                        $topMissing[] = $keyword;
                    }
                }
            }
        }

        return array_slice(array_unique($topMissing), 0, $limit);
    }

    /**
     * Clear keyword cache
     */
    public function clearCache(): void
    {
        Cache::forget('keywords_devops');
        Cache::forget('keywords_cloud');
    }
}

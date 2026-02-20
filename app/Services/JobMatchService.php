<?php

namespace App\Services;

class JobMatchService
{
    /**
     * Calculate match score between CV and job keywords (local algorithm, no AI)
     */
    public function calculateMatchScore(array $cvKeywords, array $jobKeywords): int
    {
        if (empty($cvKeywords) || empty($jobKeywords)) {
            return 0;
        }

        $flattenedCV = [];
        foreach ($cvKeywords as $category => $keywords) {
            if (is_array($keywords)) {
                $flattenedCV = array_merge($flattenedCV, $keywords);
            }
        }

        $flattenedJob = [];
        foreach ($jobKeywords as $category => $keywords) {
            if (is_array($keywords)) {
                $flattenedJob = array_merge($flattenedJob, $keywords);
            }
        }

        $matches = 0;
        foreach ($flattenedJob as $jobKeyword) {
            $lowerJobKeyword = strtolower($jobKeyword);
            foreach ($flattenedCV as $cvKeyword) {
                if (
                    strpos(strtolower($cvKeyword), $lowerJobKeyword) !== false ||
                    strpos($lowerJobKeyword, strtolower($cvKeyword)) !== false
                ) {
                    $matches++;
                    break;
                }
            }
        }

        return count($flattenedJob) > 0 ? round(($matches / count($flattenedJob)) * 100) : 0;
    }

    /**
     * Generate gap analysis between CV skills and required skills (local algorithm, no AI)
     */
    public function generateGapAnalysis(array $cvSkills, array $requiredSkills): array
    {
        $missingSkills = [];
        $partialMatches = [];
        $strongMatches = [];

        $flattenedCV = [];
        foreach ($cvSkills as $category => $skills) {
            if (is_array($skills)) {
                $flattenedCV = array_merge($flattenedCV, $skills);
            }
        }

        $flattenedRequired = [];
        foreach ($requiredSkills as $category => $skills) {
            if (is_array($skills)) {
                $flattenedRequired = array_merge($flattenedRequired, $skills);
            }
        }

        foreach ($flattenedRequired as $requiredSkill) {
            $lowerRequired = strtolower($requiredSkill);
            $found = false;
            $matchType = 'missing';

            foreach ($flattenedCV as $cvSkill) {
                $lowerCV = strtolower($cvSkill);

                if ($lowerCV === $lowerRequired) {
                    $found = true;
                    $matchType = 'strong';
                    break;
                } elseif (
                    strpos($lowerCV, $lowerRequired) !== false ||
                    strpos($lowerRequired, $lowerCV) !== false
                ) {
                    $found = true;
                    $matchType = 'partial';
                }
            }

            if (! $found) {
                $missingSkills[] = $requiredSkill;
            } elseif ($matchType === 'partial') {
                $partialMatches[] = [
                    'required' => $requiredSkill,
                    'matched_with' => $cvSkill ?? 'similar skill',
                ];
            } elseif ($matchType === 'strong') {
                $strongMatches[] = $requiredSkill;
            }
        }

        return [
            'missing' => $missingSkills,
            'partial_matches' => $partialMatches,
            'strong_matches' => $strongMatches,
            'total_missing' => count($missingSkills),
            'total_partial' => count($partialMatches),
            'total_strong' => count($strongMatches),
        ];
    }
}

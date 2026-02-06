<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobMatchService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function parseJobDescription(string $jd): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found.'];
        }

        $prompt = 'Parse this job description and extract key information.

        Provide the response in strict JSON format with the following structure:
        {
            "title": string,
            "required_skills": [string],
            "preferred_skills": [string],
            "experience_years": string,
            "education_level": string,
            "key_responsibilities": [string],
            "keywords": [string]
        }

        Do not include markdown formatting (```json) in the response.

        Job Description:
        '.$jd;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: '.$response->body());

                return ['error' => 'Failed to parse job description'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('JobMatchService Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function extractKeywords(string $text): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found.'];
        }

        $prompt = 'Extract key keywords from this text for ATS matching.

        Provide the response in strict JSON format with the following structure:
        {
            "technical_keywords": [string],
            "soft_skills": [string],
            "industry_terms": [string],
            "tools_and_technologies": [string]
        }

        Do not include markdown formatting (```json) in the response.

        Text:
        '.$text;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: '.$response->body());

                return ['error' => 'Failed to extract keywords'];
            }

            $data = $response->json();
            $textResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $textResult = str_replace(['```json', '```'], '', $textResult);

            return json_decode($textResult, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('JobMatchService Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function extractSkills(string $text): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found.'];
        }

        $prompt = 'Extract skills from this text.

        Provide the response in strict JSON format with the following structure:
        {
            "technical_skills": [string],
            "programming_languages": [string],
            "frameworks": [string],
            "cloud_platforms": [string],
            "devops_tools": [string],
            "databases": [string]
        }

        Do not include markdown formatting (```json) in the response.

        Text:
        '.$text;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: '.$response->body());

                return ['error' => 'Failed to extract skills'];
            }

            $data = $response->json();
            $textResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $textResult = str_replace(['```json', '```'], '', $textResult);

            return json_decode($textResult, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('JobMatchService Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

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
                if (strpos(strtolower($cvKeyword), $lowerJobKeyword) !== false ||
                    strpos($lowerJobKeyword, strtolower($cvKeyword)) !== false) {
                    $matches++;
                    break;
                }
            }
        }

        return count($flattenedJob) > 0 ? round(($matches / count($flattenedJob)) * 100) : 0;
    }

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
                } elseif (strpos($lowerCV, $lowerRequired) !== false ||
                         strpos($lowerRequired, $lowerCV) !== false) {
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

    public function suggestAdditions(array $missingSkills): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found.'];
        }

        $skillsList = implode(', ', $missingSkills);
        $prompt = 'Provide suggestions for how to gain experience or highlight these missing skills in a CV.

        Missing Skills:
        '.$skillsList.'

        Provide the response in strict JSON format with the following structure:
        {
            "certifications_to_get": [string],
            "projects_to_showcase": [string],
            "skills_to_emphasize": [string],
            "training_resources": [string]
        }

        Do not include markdown formatting (```json) in the response.';

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: '.$response->body());

                return ['error' => 'Failed to generate suggestions'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('JobMatchService Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function analyzeCV(string $cvContent): array
    {
        if (empty($this->apiKey)) {
            return [
                'error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.',
                'ats_score' => 0,
            ];
        }

        $prompt = 'Analyze this CV/Resume for ATS (Applicant Tracking System) compatibility. 
        
        Provide the response in strict JSON format with the following structure:
        {
            "ats_score": number (0-100),
            "keywords": {
                "found": [string],
                "missing": [string]
            },
            "format_score": number (0-100),
            "suggestions": [string],
            "industry_match": {
                "devops": number,
                "cloud": number,
                "sre": number
            },
            "summary": string
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        '.$cvContent;

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

                return ['error' => 'Failed to connect to Gemini API: '.$response->status()];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

            // Clean markdown code blocks if present data
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function matchAgainstJob(string $cvContent, string $jobDescription): array
    {
        if (empty($this->apiKey)) {
            return [
                'error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.',
                'job_match_score' => 0,
            ];
        }

        $prompt = 'Compare this CV against the job description and provide a match score.

        Provide the response in strict JSON format with the following structure:
        {
            "job_match_score": number (0-100),
            "job_title_targeted": string,
            "keywords_found": [string],
            "keywords_missing": [string],
            "suggestions": [string],
            "summary": string
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        '.$cvContent.'

        Job Description:
        '.$jobDescription;

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

                return ['error' => 'Failed to connect to Gemini API: '.$response->status()];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function analyzeKeywords(string $cvContent): array
    {
        if (empty($this->apiKey)) {
            return [
                'error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.',
            ];
        }

        $prompt = 'Analyze the keywords in this CV for ATS optimization.

        Provide the response in strict JSON format with the following structure:
        {
            "found": [string],
            "missing": [string],
            "density_analysis": {
                "skill_keywords": number,
                "action_verbs": number,
                "technical_terms": number
            }
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        '.$cvContent;

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

                return ['error' => 'Failed to connect to Gemini API'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function analyzeActionVerbs(string $cvContent): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.'];
        }

        $prompt = 'Analyze the action verbs used in this CV.

        Provide the response in strict JSON format with the following structure:
        {
            "strong_verbs": [string],
            "weak_verbs": [string],
            "suggestions": [string],
            "action_verb_score": number (0-100)
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        '.$cvContent;

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

                return ['error' => 'Failed to connect to Gemini API'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function validateATSFormat(string $cvContent): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.'];
        }

        $prompt = 'Validate this CV for ATS compatibility.

        Provide the response in strict JSON format with the following structure:
        {
            "format_score": number (0-100),
            "issues": [string],
            "strengths": [string],
            "sections_present": [string],
            "format_checklist": {
                "single_column": boolean,
                "standard_fonts": boolean,
                "clear_headings": boolean,
                "no_tables": boolean
            }
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        '.$cvContent;

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

                return ['error' => 'Failed to connect to Gemini API'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function getImprovementSuggestions(array $analysis): array
    {
        if (empty($this->apiKey)) {
            return ['error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.'];
        }

        $analysisText = json_encode($analysis);

        $prompt = 'Based on this CV analysis, provide specific, actionable improvements.

        Provide the response in strict JSON format with the following structure:
        {
            "critical_improvements": [string],
            "recommended_improvements": [string],
            "optional_improvements": [string]
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        Analysis Data:
        '.$analysisText;

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

                return ['error' => 'Failed to connect to Gemini API'];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }

    public function comprehensiveCVAnalysis(string $cvContent, ?string $jobDescription = null): array
    {
        if (empty($this->apiKey)) {
            return [
                'error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.',
                'ats_score' => 0,
            ];
        }

        $jobPrompt = $jobDescription ? '

        Job Description to match against:
        '.$jobDescription : '';

        $prompt = 'Perform a comprehensive CV analysis for ATS optimization'.($jobDescription ? ' and job matching' : '').'.

        Provide the response in strict JSON format with the following structure:
        {
            "ats_score": number (0-100),
            "format_score": number (0-100),
            "keywords": {
                "found": [string],
                "missing": [string]
            },
            "suggestions": [string],
            "industry_match": {
                "devops": number,
                "cloud": number,
                "sre": number
            },
            "summary": string,
            "job_match_score": number'.($jobDescription ? ', "job_title_targeted": string' : '').'
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        '.$cvContent.$jobPrompt;

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

                return ['error' => 'Failed to connect to Gemini API: '.$response->status()];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: '.$e->getMessage());

            return ['error' => 'An error occurred: '.$e->getMessage()];
        }
    }
}
